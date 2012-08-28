//
// Copyright (c) 2009-2010, the Open Video Player authors. All rights reserved.
//
// Redistribution and use in source and binary forms, with or without 
// modification, are permitted provided that the following conditions are 
// met:
//
//    * Redistributions of source code must retain the above copyright 
//		notice, this list of conditions and the following disclaimer.
//    * Redistributions in binary form must reproduce the above 
//		copyright notice, this list of conditions and the following 
//		disclaimer in the documentation and/or other materials provided 
//		with the distribution.
//    * Neither the name of the openvideoplayer.org nor the names of its 
//		contributors may be used to endorse or promote products derived 
//		from this software without specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS 
// "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT 
// LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR 
// A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT 
// OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, 
// SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT 
// LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, 
// DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY 
// THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT 
// (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE 
// OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
//

package {
	import flash.display.*;
	import flash.events.Event;
	import flash.external.ExternalInterface;
	import flash.media.Video;
	import flash.net.URLLoader;
	import flash.text.StyleSheet;
	import flash.ui.ContextMenu;
	import flash.ui.ContextMenuItem;
	
	import org.openvideoplayer.advertising.*;
	import org.openvideoplayer.events.*;
	import org.openvideoplayer.flashadplayer.controller.VideoController;
	import org.openvideoplayer.flashadplayer.events.VideoEvent;
	import org.openvideoplayer.plugins.IOvpPlayer;
	import org.openvideoplayer.plugins.OvpPlayerEvent;
	import org.openvideoplayer.version.OvpVersion;

	/**
	 * The AdPlayer plugin for OVP implements the IVPAID interface and 
	 * can be controlled from JavaScript or ActionScript.
	 */
	public class AdPlayer extends Sprite implements IAdPlayer {

		private var _videoController:VideoController;
		private var _fullScreen:Boolean;
		private var _uic:Sprite;
		private var _videoSettings:Object;
		private var _video:Video;
		private var _hostPlayer:IOvpPlayer;
		private var _styleSheet:StyleSheet;
		private var _loader:URLLoader;
		private var _tracingOn:Boolean;

		private const _PLUGIN_NAME_:String = "OVP Ad Player";
		private const _PLUGIN_VERSION_:String = "v.1.0.0";
		private const _PLUGIN_DESC_:String = "The OVP Ad Player can load and play progressive download video ads, streaming video ads, and SWF ads. The plug-in implements the IOvpPlugIn and IVPAID interfaces.";
		private const _SUPPORTED_MIME_TYPES_:Array = new Array("video/x-flv", "video/mp4", "application/x-shockwave-flash");

		/**
		 * Constructor
		 */
		public function AdPlayer() {
			if (this.stage) {
				init();
			} else {
				addEventListener(Event.ADDED_TO_STAGE, init);
			}
		}

		private function init(e:Event = null):void {
			removeEventListener(Event.ADDED_TO_STAGE, init);
			addChildren();
			initVars();
			initEI();
		}

		private function addChildren():void {
			_uic = new Sprite();
			addChild(_uic);

			_video = new Video(100, 100); // arbitrary width and height, we'll get set in the resize event handler
			_video.x = 0;
			_video.y = 0;

			_videoController = new VideoController(_uic, _video);
			_videoController.addEventListener(VPAIDEvent.AdClickThru, onVPAIDEvent);
			_videoController.addEventListener(VPAIDEvent.AdError, onVPAIDEvent);
			_videoController.addEventListener(VPAIDEvent.AdExpandedChange, onVPAIDEvent);
			_videoController.addEventListener(VPAIDEvent.AdImpression, onVPAIDEvent);
			_videoController.addEventListener(VPAIDEvent.AdLinearChange, onVPAIDEvent);
			_videoController.addEventListener(VPAIDEvent.AdLoaded, onVPAIDEvent);
			_videoController.addEventListener(VPAIDEvent.AdLog, onVPAIDEvent);
			_videoController.addEventListener(VPAIDEvent.AdPaused, onVPAIDEvent);
			_videoController.addEventListener(VPAIDEvent.AdPlaying, onVPAIDEvent);
			_videoController.addEventListener(VPAIDEvent.AdRemainingTimeChange, onVPAIDEvent);
			_videoController.addEventListener(VPAIDEvent.AdStarted, onVPAIDEvent);
			_videoController.addEventListener(VPAIDEvent.AdStopped, onVPAIDEvent);
			_videoController.addEventListener(VPAIDEvent.AdUserAcceptInvitation, onVPAIDEvent);
			_videoController.addEventListener(VPAIDEvent.AdUserClose, onVPAIDEvent);
			_videoController.addEventListener(VPAIDEvent.AdUserMinimize, onVPAIDEvent);
			_videoController.addEventListener(VPAIDEvent.AdVideoComplete, onVPAIDEvent);
			_videoController.addEventListener(VPAIDEvent.AdVideoFirstQuartile, onVPAIDEvent);
			_videoController.addEventListener(VPAIDEvent.AdVideoMidpoint, onVPAIDEvent);
			_videoController.addEventListener(VPAIDEvent.AdVideoStart, onVPAIDEvent);
			_videoController.addEventListener(VPAIDEvent.AdVideoThirdQuartile, onVPAIDEvent);
			_videoController.addEventListener(VPAIDEvent.AdVolumeChange, onVPAIDEvent);
			_videoController.addEventListener(VideoEvent.RESIZE, onVideoResize);
			_videoController.addEventListener(OvpPlayerEvent.DEBUG_MSG, onDebugMessage);

			var cm:ContextMenu = new ContextMenu();
			var item:ContextMenuItem = new ContextMenuItem(this._PLUGIN_NAME_);
			cm.customItems.push(item);
			this.contextMenu = cm;
			this.contextMenu.hideBuiltInItems();
		}

		private function initVars():void {
			_fullScreen = false;
			_tracingOn = false;
		}

		//-------------------------------------------------------------------
		//
		// Functions called *FROM* JavaScript
		//
		//-------------------------------------------------------------------

		/**
		 * Returns the VPAID property value for property name specified.
		 * 
		 * @param propName The name of the VPAID property to get.
		 * @return *
		 */
		public function js_getVPAIDProperty(propName:String):* {
			var value:*;

			switch (propName) {
				case "adLinear":
					value = _videoController.adLinear;
					break;
				case "adExpanded":
					value = _videoController.adExpanded;
					break;
				case "adRemainingTime":
					value = _videoController.adRemainingTime;
					break;
				case "adVolume":
					value = _videoController.adVolume;
					break;
			}
			pluginTrace("in getVPAIDProperty() - property name =" + propName + ", about to return value of: " + value);
			return value;

		}

		/**
		 * Sets a VPAID property value for the property name specified.
		 * 
		 * @param propName The name of the VPAID property to set.
		 */
		public function js_setVPAIDProperty(propName:String, value:*):void {
			switch (propName) {
				case "adVolume":
					_videoController.adVolume = Number(value);
					break;
			}
		}

		/**
		 * @see org.openvideoplayer.advertising.IVPAID
		 */
		public function js_handshakeVersion(playerVPAIDVersion:String):String {
			return _videoController.handshakeVersion(playerVPAIDVersion);
		}

		/**
		 * @see org.openvideoplayer.advertising.IVPAID
		 */
		public function js_initAd(width:Number, height:Number, viewMode:String,
			desiredBitrate:Number, creativeData:String, environmentVars:String):void {
			_videoController.initAd(width, height, viewMode, desiredBitrate, creativeData, environmentVars);
		}

		/**
		 * @see org.openvideoplayer.advertising.IVPAID
		 */
		public function js_resizeAd(width:Number, height:Number, viewMode:String):void {
			_videoController.resizeAd(width, height, viewMode);
		}

		/**
		 * @see org.openvideoplayer.advertising.IVPAID
		 */
		public function js_startAd():void {
			_videoController.startAd();
		}

		/**
		 * @see org.openvideoplayer.advertising.IVPAID
		 */
		public function js_stopAd():void {
			_videoController.stopAd();
		}

		/**
		 * @see org.openvideoplayer.advertising.IVPAID
		 */
		public function js_pauseAd():void {
			_videoController.pauseAd();
		}

		/**
		 * @see org.openvideoplayer.advertising.IVPAID
		 */
		public function js_resumeAd():void {
			_videoController.resumeAd();
		}

		/**
		 * @see org.openvideoplayer.advertising.IVPAID
		 */
		public function js_expandAd():void {
			_videoController.expandAd();
		}

		/**
		 * @see org.openvideoplayer.advertising.IVPAID
		 */
		public function js_collapseAd():void {
			_videoController.collapseAd();
		}

		/**
		 * @see org.openvideoplayer.advertising.IVPAID
		 */
		public function resizeVideo(width:Number, height:Number, viewMode:String):void {
			pluginTrace("resizeVideo width: " + width + " height: " + height);

			_video.width = width;
			_video.height = height;
			_uic.height = height;
			_uic.width = width;
		}

		//-------------------------------------------------------------------
		//
		// IOvpPlugIn Implementation
		//
		//-------------------------------------------------------------------

		/**
		 * @see org.openvideoplayer.plugins.IOvpPlugin
		 */
		public function get ovpPlugInName():String {
			return (_PLUGIN_NAME_ + " " + _PLUGIN_VERSION_);
		}

		/**
		 * @see org.openvideoplayer.plugins.IOvpPlugin
		 */
		public function get ovpPlugInDescription():String {
			return _PLUGIN_DESC_
		}

		/**
		 * @see org.openvideoplayer.plugins.IOvpPlugin
		 */
		public function get ovpPlugInVersion():String {
			return _PLUGIN_VERSION_;
		}
		
		/**
		 * @see org.openvideoplayer.plugins.IOvpPlugin
		 */
		public function get ovpPlugInCoreVersion():String {
			return OvpVersion.version;
		}

		/**
		 * @see org.openvideoplayer.plugins.IOvpPlugin
		 */
		public function get ovpPlugInTracingOn():Boolean {
			return _tracingOn;
		}

		/**
		 * @see org.openvideoplayer.plugins.IOvpPlugin
		 */
		public function set ovpPlugInTracingOn(value:Boolean):void {
			_tracingOn = value;
			_videoController.tracingOn = value;
		}

		/**
		 * @see org.openvideoplayer.plugins.IOvpPlugin
		 */
		public function initOvpPlugIn(player:IOvpPlayer):void {
			pluginTrace("initOvpPlugIn called...");
			_hostPlayer = player;
		}

		//-------------------------------------------------------------------
		//
		// IAdPlayer Implementation
		//
		//-------------------------------------------------------------------

		/**
		 * @see org.openvideoplayer.advertising.IAdPlayer
		 */
		public function get supportedMimeTypes():Array {
			var mimeTypes:Array = _SUPPORTED_MIME_TYPES_.concat();
			return mimeTypes;
		}

		/**
		 * @see org.openvideoplayer.advertising.IAdPlayer
		 */
		public function supportsMimeType(mimeType:String):Boolean {
			for (var i:int = 0; i < _SUPPORTED_MIME_TYPES_.length; i++) {
				if (_SUPPORTED_MIME_TYPES_[i] == mimeType) {
					return true;
				}
			}
			return false;
		}

		//-------------------------------------------------------------------
		//
		// IVPAID Implementation for ActionScript hosts
		//
		//-------------------------------------------------------------------

		/**
		 * @see org.openvideoplayer.advertising.IVPAID
		 */
		public function getVPAID():IVPAID {
			return (_videoController as IVPAID);
		}

		//-------------------------------------------------------------------
		//
		// Internal methods
		//
		//-------------------------------------------------------------------

		private function onVideoResize(e:VideoEvent):void {
			this.width = e.data.width;
			this.height = e.data.height;
			this.resizeVideo(e.data.width, e.data.height, e.data.viewMode);
		}

		private function onVPAIDEvent(e:VPAIDEvent):void {
			// Bubble the event up to any ActionScript players that may have loaded this plug-in
			dispatchEvent(e);

			// Bubbling up to JavaScript means calling a function with the same name as the event type,
			// but adding "VPAID" as a prefix, i.e., "VPAIDAdLoaded"
			// This is part of the VPAID spec
			ExternalInterface.call("VPAID" + e.type, e.data);
		}

		//-------------------------------------------------------------------
		//
		// IVPAID Implementation for JavaScript hosts
		//			
		// Extenal Interface Calls
		//
		// This player can be controlled via JavaScript. The functions below
		// model the VPAID spec and simply call into this ad player's IVPAID
		// implementation.
		//
		//-------------------------------------------------------------------

		/**
		 * Adds the JavaScript Bridge functions from the VPAID spec
		 */
		private function initEI():void {
			if (ExternalInterface.available) {
				ExternalInterface.addCallback("getVPAIDProperty", js_getVPAIDProperty);
				ExternalInterface.addCallback("setVPAIDProperty", js_setVPAIDProperty);
				ExternalInterface.addCallback("handshakeVersion", js_handshakeVersion);
				ExternalInterface.addCallback("initAd", js_initAd);
				ExternalInterface.addCallback("resizeAd", js_resizeAd);
				ExternalInterface.addCallback("startAd", js_startAd);
				ExternalInterface.addCallback("stopAd", js_stopAd);
				ExternalInterface.addCallback("pauseAd", js_pauseAd);
				ExternalInterface.addCallback("resumeAd", js_resumeAd);
				ExternalInterface.addCallback("expandAd", js_expandAd);
				ExternalInterface.addCallback("collapseAd", js_collapseAd);

				ExternalInterface.call("flashReady");
			}
		}

		//-------------------------------------------------------------------
		//
		// Helper Methods
		//
		//-------------------------------------------------------------------

		private function onDebugMessage(event:OvpPlayerEvent):void {
			pluginTrace(event.data as String);
		}

		private function pluginTrace(... arguments):void {
			if (arguments[0] && _tracingOn && _hostPlayer) {
				var debugmsg:String = "> OVP Ad Player - " + arguments;
				trace(debugmsg);
				_hostPlayer.dispatchEvent(new OvpPlayerEvent(OvpPlayerEvent.DEBUG_MSG, debugmsg));
			} else
				return;
		}
	}
}
