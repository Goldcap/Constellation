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
package org.openvideoplayer.flashadplayer.controller {
	import flash.display.DisplayObject;
	import flash.display.Loader;
	import flash.display.Sprite;
	import flash.events.*;
	import flash.media.Video;
	import flash.net.URLRequest;
	import flash.system.ApplicationDomain;
	import flash.system.LoaderContext;

	import org.openvideoplayer.advertising.IVPAID;
	import org.openvideoplayer.advertising.VPAIDEvent;
	import org.openvideoplayer.events.*;
	import org.openvideoplayer.flashadplayer.events.VideoEvent;
	import org.openvideoplayer.net.*;
	import org.openvideoplayer.plugins.OvpPlayerEvent;

	/**
	 * @eventType org.openvideoplayer.advertising.VPAIDEvent.AdLoaded
	 */
	[Event(name="AdLoaded", type="org.openvideoplayer.advertising.VPAIDEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.VPAIDEvent.AdStarted
	 */
	[Event(name="AdStarted", type="org.openvideoplayer.advertising.VPAIDEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.VPAIDEvent.AdStopped
	 */
	[Event(name="AdStopped", type="org.openvideoplayer.advertising.VPAIDEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.VPAIDEvent.AdLinearChange
	 */
	[Event(name="AdLinearChange", type="org.openvideoplayer.advertising.VPAIDEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.VPAIDEvent.AdExpandedChange
	 */
	[Event(name="AdExpandedChange", type="org.openvideoplayer.advertising.VPAIDEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.VPAIDEvent.AdRemainingTimeChange
	 */
	[Event(name="AdRemainingTimeChange", type="org.openvideoplayer.advertising.VPAIDEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.VPAIDEvent.AdVolumeChange
	 */
	[Event(name="AdVolumeChange", type="org.openvideoplayer.advertising.VPAIDEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.VPAIDEvent.AdImpression
	 */
	[Event(name="AdImpression", type="org.openvideoplayer.advertising.VPAIDEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.VPAIDEvent.AdVideoStart
	 */
	[Event(name="AdVideoStart", type="org.openvideoplayer.advertising.VPAIDEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.VPAIDEvent.AdVideoFirstQuartile
	 */
	[Event(name="AdVideoFirstQuartile", type="org.openvideoplayer.advertising.VPAIDEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.VPAIDEvent.AdVideoMidpoint
	 */
	[Event(name="AdVideoMidpoint", type="org.openvideoplayer.advertising.VPAIDEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.VPAIDEvent.AdVideoThirdQuartile
	 */
	[Event(name="AdVideoThirdQuartile", type="org.openvideoplayer.advertising.VPAIDEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.VPAIDEvent.AdVideoComplete
	 */
	[Event(name="AdVideoComplete", type="org.openvideoplayer.advertising.VPAIDEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.VPAIDEvent.AdClickThru
	 */
	[Event(name="AdClickThru", type="org.openvideoplayer.advertising.VPAIDEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.VPAIDEvent.AdUserAcceptInvitation
	 */
	[Event(name="AdUserAcceptInvitation", type="org.openvideoplayer.advertising.VPAIDEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.VPAIDEvent.AdUserMinimize
	 */
	[Event(name="AdUserMinimize", type="org.openvideoplayer.advertising.VPAIDEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.VPAIDEvent.AdUserClose
	 */
	[Event(name="AdUserClose", type="org.openvideoplayer.advertising.VPAIDEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.VPAIDEvent.AdPaused
	 */
	[Event(name="AdPaused", type="org.openvideoplayer.advertising.VPAIDEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.VPAIDEvent.AdPlaying
	 */
	[Event(name="AdPlaying", type="org.openvideoplayer.advertising.VPAIDEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.VPAIDEvent.AdLog
	 */
	[Event(name="AdLog", type="org.openvideoplayer.advertising.VPAIDEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.VPAIDEvent.AdError
	 */
	[Event(name="AdError", type="org.openvideoplayer.advertising.VPAIDEvent")]

	/**
	 * @eventType org.openvideoplayer.flashadplayer.events.VideoEvent.RESIZE
	 */
	[Event(name="resize", type="org.openvideoplayer.flashadplayer.events.VideoEvent")]

	/**
	 * Dispatched for debugging purposes.
	 *
	 * @see org.openvideoplayer.events.OvpEvent#DEBUG
	 */
	[Event(name="debug", type="org.openvideoplayer.plugins.OvpPlayerEvent")]

	/**
	 * The VideoController class implements the IVPAID interface for ad/player
	 * communication.
	 */
	public class VideoController extends EventDispatcher implements IVPAID {
		private var _nc:OvpConnection;
		private var _ns:OvpNetStream;
		private var _hostName:String;
		private var _streamName:String;
		private var _streamLength:Number;
		private var _isProgressive:Boolean;
		private var _firstQuartileEventFired:Boolean;
		private var _midpointEventFired:Boolean;
		private var _thirdQuartileEventFired:Boolean;
		private var _video:Video;
		private var _initWidth:Number;
		private var _initHeight:Number;
		private var _initViewMode:String;
		private var _initDesiredBitrate:Number;
		private var _initEnvVars:String;
		private var _lastVideoWidth:int = 0;
		private var _lastVideoHeight:int = 0;
		private var _tracingOn:Boolean;
		private var _adSWF:IVPAID;
		private var _videoAndSwfContainer:Sprite;
		private const _QUARTILE_EVENT_TOLERANCE_:Number = .005;

		/**
		 * Constructor
		 *
		 * @param videoAndSwfContainer The sprite where the swf ad or video ad should be added as a child.
		 * @param videoObj The video instance to use for a video ad.
		 */
		public function VideoController(videoAndSwfContainer:Sprite, videoObj:Video) {
			_streamLength = 0;
			_isProgressive = false;
			_video = videoObj;
			_videoAndSwfContainer = videoAndSwfContainer;
			_initWidth = 0;
			_initHeight = 0;
			_initViewMode = "normal";
			_initDesiredBitrate = 0;
			_initEnvVars = "";
			_tracingOn = false;

			reset();
		}

		/**
		 * True to turn tracing on.
		 *
		 * @param value
		 */
		public function set tracingOn(value:Boolean):void {
			_tracingOn = value;
		}

		/**
		 * Check if the ad is a SWF ad
		 *
		 * @return
		 */
		public function get isSwfAd():Boolean {
			if (_adSWF == null) {
				return false;
			}
			return true;
		}

		private function reset():void {
			_firstQuartileEventFired = false;
			_midpointEventFired = false;
			_thirdQuartileEventFired = false;
		}

		/**
		 * This method is called from the netStatusHandler below when we receive a good connection
		 */
		private function connectedHandler():void {
			pluginTrace("Successfully connected to: " + _nc.netConnection.uri);

			// Instantiate an OvpNetStream object
			_ns = new OvpNetStream(_nc);

			// Add the necessary listeners
			_ns.addEventListener(NetStatusEvent.NET_STATUS, streamStatusHandler, false, 0, true);
			_ns.addEventListener(OvpEvent.NETSTREAM_PLAYSTATUS, streamPlayStatusHandler, false, 0, true);
			_ns.addEventListener(OvpEvent.NETSTREAM_METADATA, metadataHandler, false, 0, true);
			_ns.addEventListener(OvpEvent.STREAM_LENGTH, streamLengthHandler, false, 0, true);
			_ns.addEventListener(OvpEvent.PROGRESS, update, false, 0, true);
			_ns.addEventListener(OvpEvent.ERROR, errorHandler, false, 0, true);

			// Give the video symbol on stage our net stream object
			_videoAndSwfContainer.addChild(_video);
			_video.attachNetStream(_ns);
			_video.visible = false;
			_ns.createProgressivePauseEvents = true;

			pluginTrace("OVP Flash Ad Player firing LOADED event");
			dispatchEvent(new VPAIDEvent(VPAIDEvent.AdLoaded));

		}

		/**
		 * Sends quartile events to the player
		 */
		private function update(e:OvpEvent):void {
			var percentComplete:Number = _ns.time / _streamLength;

			// first quartile?
			if (!_firstQuartileEventFired) {
				if ((percentComplete > (.25 - _QUARTILE_EVENT_TOLERANCE_)) && (percentComplete < (.25 + _QUARTILE_EVENT_TOLERANCE_))) {
					pluginTrace("OVP Flash Ad Player firing FIRSTQUARTILE");
					dispatchEvent(new VPAIDEvent(VPAIDEvent.AdVideoFirstQuartile));
					_firstQuartileEventFired = true;
				}
			}

			// midpoint ?
			if (!_midpointEventFired) {
				if ((percentComplete > (.5 - _QUARTILE_EVENT_TOLERANCE_)) && (percentComplete < (.5 + _QUARTILE_EVENT_TOLERANCE_))) {
					pluginTrace("OVP Flash Ad Player firing MIDPOINT event");
					dispatchEvent(new VPAIDEvent(VPAIDEvent.AdVideoMidpoint));
					_midpointEventFired = true;
				}
			}

			// third quartile?
			if (!_thirdQuartileEventFired) {
				if ((percentComplete > (.75 - _QUARTILE_EVENT_TOLERANCE_)) && (percentComplete < (.75 + _QUARTILE_EVENT_TOLERANCE_))) {
					pluginTrace("OVP Flash Ad Player firing THIRDQUARTIME event");
					dispatchEvent(new VPAIDEvent(VPAIDEvent.AdVideoThirdQuartile));
					_thirdQuartileEventFired = true;
				}
			}

			if (this._isProgressive && (_ns.time > 0)) {
				// complete?
				if (_ns.time == _streamLength) {
					handleComplete();
				}
			}

			if (_lastVideoWidth != _video.videoWidth || _lastVideoHeight != _video.videoHeight) {
				resizeAd(_initWidth, _initHeight, _initViewMode);
				_lastVideoWidth = _video.videoWidth;
				_lastVideoHeight = _video.videoHeight;
			}
		}


		/**
		 * Handles all OvpEvent.ERROR events
		 */
		private function errorHandler(e:OvpEvent):void {
			pluginTrace("Error #" + e.data.errorNumber + ": " + e.data.errorDescription, "ERROR");
			dispatchEvent(new VPAIDEvent(VPAIDEvent.AdError, e.data));
		}

		private function streamLengthHandler(e:OvpEvent):void {
			pluginTrace("Stream length is " + e.data.streamLength);
			_streamLength = e.data.streamLength;
		}

		private function netStatusHandler(e:NetStatusEvent):void {
			pluginTrace(e.info.code);
			switch (e.info.code) {
				case "NetConnection.Connect.Rejected":
					pluginTrace("Rejected by server. Reason is " + e.info.description);
					break;
				case "NetConnection.Connect.Success":
					connectedHandler();
					break;
				case "NetConnection.Connect.Closed":
					pluginTrace("cleanUp: VPAIDEvent.AdStopped");
					dispatchEvent(new VPAIDEvent(VPAIDEvent.AdStopped));
					break;
			}
		}

		private function streamStatusHandler(e:NetStatusEvent):void {
			pluginTrace("streamStatusHandler() - e.data.code=" + e.info.code);
			switch (e.info.code) {
				case "NetStream.Play.Start":
					// Send the width, height, and view mode we got in the init method
					resizeAd(_initWidth, _initHeight, _initViewMode);
					dispatchEvent(new VPAIDEvent(VPAIDEvent.AdStarted));
					break;
				case "NetStream.Pause.Notify":
					dispatchEvent(new VPAIDEvent(VPAIDEvent.AdPaused));
					break;
				case "NetStream.Unpause.Notify":
					dispatchEvent(new VPAIDEvent(VPAIDEvent.AdPlaying));
					break;
				case "NetStream.Buffer.Full":
					_video.visible = true;
					break;
				case "NetStream.Play.Stop":
					// See if a PDL file has ended (streaming end is handled in streamPlayStatusHandler)
					if (_ns.isProgressive && (this._streamLength >= (_ns.time - 1))) {
						handleComplete();
					}
					break;
					
			}
		}

		private function streamPlayStatusHandler(e:OvpEvent):void {
			pluginTrace("streamPlayStatusHandler() - e.data.code=" + e.data.code);
			switch (e.data.code) {
				case "NetStream.Play.Complete":
					handleComplete();
					break;
			}
		}

		private function handleComplete():void {
			pluginTrace("OVP Flash Ad Player firing COMPLETE event");
			dispatchEvent(new VPAIDEvent(VPAIDEvent.AdVideoComplete));

			cleanUp(false);
		}

		private function cleanUp(fireAdStoppedEvent:Boolean = true):void {
			if (_ns) {
				_ns.close();
			}
			if (_nc) {
				_nc.close();
			}

			var numChildren:uint = _videoAndSwfContainer.numChildren;
			for (var i:uint = 0; i < numChildren; i++) {
				_videoAndSwfContainer.removeChildAt(i);
			}

			if (fireAdStoppedEvent) {
				pluginTrace("cleanUp: VPAIDEvent.AdStopped");
				dispatchEvent(new VPAIDEvent(VPAIDEvent.AdStopped));
			}

			_ns = null;
			_nc = null;
		}

		private function metadataHandler(e:OvpEvent):void {
			for (var propName:String in e.data) {
				pluginTrace("metadata: " + propName + " = " + e.data[propName]);
			}
		}

		/**
		 * Load the SWF into it's own Application Domain, which is a child of the players Application Domain.
		 */
		private function loadSWF(url:String):void {
			var loader:Loader = new Loader();
			var req:URLRequest = new URLRequest(url);
			var context:LoaderContext = new LoaderContext();

			context.applicationDomain = new ApplicationDomain(ApplicationDomain.currentDomain);
			loader.contentLoaderInfo.addEventListener(Event.COMPLETE, onSwfLoadComplete);
			loader.load(req, context);
		}

		private function onSwfLoadComplete(e:Event):void {
			pluginTrace("in onSwfLoadComplete : e.target.content=" + e.target.content);

			var loader:Loader = e.target as Loader;
			loader.removeEventListener(Event.COMPLETE, onSwfLoadComplete);

			var content:DisplayObject = e.target.content;

			this._videoAndSwfContainer.addChild(content);

			if (content.hasOwnProperty("getVPAID")) {
				var getVPAIDFunc:Function = (content["getVPAID"] as Function);

				if (getVPAIDFunc != null) {
					_adSWF = getVPAIDFunc.call(this);
					initSwfAd();
				}
			} else if (content.hasOwnProperty("initAd")) {
				_adSWF = content as IVPAID;
				initSwfAd();
			} else {
				// The SWF does not implement IVPAID
				var data:Object = new Object();
				data.message = "Error - Ad SWF does not implement IVPAID";
				pluginTrace(data.message);
				dispatchEvent(new VPAIDEvent(VPAIDEvent.AdError, data));
			}
		}

		private function initSwfAd():void {
			_adSWF.resizeAd(this._initWidth, this._initHeight, this._initViewMode);
			resizeAd(this._initWidth, this._initHeight, this._initViewMode);
			dispatchEvent(new VPAIDEvent(VPAIDEvent.AdLoaded));
		}

		//-------------------------------------------------------------------
		//
		// IVPAID Implementation
		//
		//-------------------------------------------------------------------

		// -------------------
		// Properties
		//

		/**
		 * @see org.openvideoplayer.advertising.IVPAID
		 */
		public function get adLinear():Boolean {
			if (_adSWF) {
				try {
					return _adSWF.adLinear
				} catch (e:Error) {
					pluginTrace("Unhandled exception in get linearAd() - " + e.message);
					throw(e);
				}
			}
			return true;
		}

		/**
		 * @see org.openvideoplayer.advertising.IVPAID
		 */
		public function get adExpanded():Boolean {
			if (_adSWF) {
				try {
					return _adSWF.adExpanded;
				} catch (e:Error) {
					pluginTrace("Unhandled exception in get expandedAd() - " + e.message);
					throw(e);
				}
			}
			return false;
		}

		/**
		 * @see org.openvideoplayer.advertising.IVPAID
		 */
		public function get adRemainingTime():Number {
			if (_adSWF) {
				try {
					return _adSWF.adRemainingTime;
				} catch (e:Error) {
					pluginTrace("Unhandled exception in get remainingTimeAd() - " + e.message);
					throw(e);
				}
			} else if (_ns && (_streamLength > 0)) {
				return _streamLength - _ns.time;
			}
			return -2; // unknown according to the VPAID spec
		}

		/**
		 * @see org.openvideoplayer.advertising.IVPAID
		 */
		public function get adVolume():Number {
			if (_adSWF) {
				try {
					return _adSWF.adVolume;
				} catch (e:Error) {
					pluginTrace("Unhandled exception in get volumeAd() - " + e.message);
					throw(e);
				}

			} else if (_ns) {
				return _ns.volume;
			}
			return 0;
		}

		/**
		 * @see org.openvideoplayer.advertising.IVPAID
		 */
		public function set adVolume(value:Number):void {
			if (_adSWF) {
				try {
					_adSWF.adVolume = value;
				} catch (e:Error) {
					pluginTrace("Unhandled exception in set volumeAd() - " + e.message);
					throw(e);
				}
			} else if (_ns) {
				_ns.volume = value;
			}
		}

		// -------------------
		// Methods
		//

		/**
		 * @see org.openvideoplayer.advertising.IVPAID
		 */
		public function handshakeVersion(playerVPAIDVersion:String):String {
			return "1.0.0";
		}

		/**
		 * @see org.openvideoplayer.advertising.IVPAID
		 */
		public function initAd(width:Number, height:Number, viewMode:String, desiredBitrate:Number, creativeData:String, environmentVars:String):void {
			pluginTrace("initAd called with width=" + width + ", height=" + height + "creativeData=" + creativeData);
			trace("initAd called with width = " + width + " height = " + height);

			if (!creativeData || !creativeData.length) {
				return;
			}

			var isSWF:Boolean = false;

			if (creativeData.search(/\.swf/) > 0) {
				// It's an Ad SWF
				isSWF = true;
			} else {
				// It's an Ad Video
				if (creativeData.search(/^http/) == 0) {
					// It's a progressive download video Ad
					_hostName = null;
					_streamName = creativeData;
					_isProgressive = true;
				} else {
					// It's a streaming video Ad
					_hostName = creativeData.split("/")[2] + "/" + creativeData.split("/")[3];
					_streamName = creativeData.slice(creativeData.indexOf(_hostName) + _hostName.length + 1);
					// Check for ending file extensions that shouldn't be there
					_streamName = _streamName.replace(/\.flv$|\.f4v$/, "");
				}
			}

			_initWidth = width;
			_initHeight = height;
			_initViewMode = viewMode;
			_initDesiredBitrate = desiredBitrate;
			_initEnvVars = environmentVars;

			if (isSWF) {
				loadSWF(creativeData);
			} else {
				if (_nc == null) {
					// Create the connection object and add the necessary event listeners
					_nc = new OvpConnection()
					_nc.addEventListener(OvpEvent.ERROR, errorHandler, false, 0, true);
					_nc.addEventListener(NetStatusEvent.NET_STATUS, netStatusHandler, false, 0, true);
					_nc.addEventListener(OvpEvent.STREAM_LENGTH, streamLengthHandler, false, 0, true);
				}
				_nc.connect(_hostName);
			}
		}

		/**
		 * @see org.openvideoplayer.advertising.IVPAID
		 */
		public function resizeAd(width:Number, height:Number, viewMode:String):void {

			if (_adSWF) {
				try {
					_adSWF.resizeAd(width, height, viewMode);
				} catch (e:Error) {
					pluginTrace("Unhandled exception in resizeAd() - " + e.message);
					throw(e);
				}
			}

			var data:Object = new Object();
			data.width = width;
			data.height = height;
			data.viewMode = viewMode;

			dispatchEvent(new VideoEvent(VideoEvent.RESIZE, data));
		}

		/**
		 * @see org.openvideoplayer.advertising.IVPAID
		 */
		public function startAd():void {
			if (_adSWF) {
				try {
					_adSWF.startAd();
				} catch (e:Error) {
					pluginTrace("Unhandled exception in startAd() - " + e.message);
					throw(e);
				}
			} else if (_ns) {
				_ns.play(_streamName);
			}
		}

		/**
		 * @see org.openvideoplayer.advertising.IVPAID
		 */
		public function stopAd():void {
			if (_adSWF) {
				try {
					_adSWF.stopAd();
				} catch (e:Error) {
					pluginTrace("Unhandled exception in stopAd() - " + e.message);
					throw(e);
				}
			}
			cleanUp();
		}

		/**
		 * @see org.openvideoplayer.advertising.IVPAID
		 */
		public function pauseAd():void {
			if (_adSWF) {
				try {
					_adSWF.pauseAd();
				} catch (e:Error) {
					pluginTrace("Unhandled exception in pauseAd() - " + e.message);
					throw(e);
				}
			} else if (_ns) {
				_ns.pause();
			}
		}

		/**
		 * @see org.openvideoplayer.advertising.IVPAID
		 */
		public function resumeAd():void {
			if (_adSWF) {
				try {
					_adSWF.resumeAd();
				} catch (e:Error) {
					pluginTrace("Unhandled exception in resumeAd() - " + e.message);
					throw(e);
				}
			} else if (_ns) {
				_ns.resume();
			}
		}

		/**
		 * @see org.openvideoplayer.advertising.IVPAID
		 */
		public function expandAd():void {
			if (_adSWF) {
				try {
					_adSWF.expandAd();
				} catch (e:Error) {
					pluginTrace("Unhandled exception in expandAd() - " + e.message);
					throw(e);
				}
			}
		}

		/**
		 * @see org.openvideoplayer.advertising.IVPAID
		 */
		public function collapseAd():void {
			if (_adSWF) {
				try {
					_adSWF.collapseAd();
				} catch (e:Error) {
					pluginTrace("Unhandled exception in collapseAd() - " + e.message);
					throw(e);
				}
			}
		}

		private function pluginTrace(... arguments):void {
			if (_tracingOn)
				dispatchEvent(new OvpPlayerEvent(OvpPlayerEvent.DEBUG_MSG, arguments));
		}
	}
}
