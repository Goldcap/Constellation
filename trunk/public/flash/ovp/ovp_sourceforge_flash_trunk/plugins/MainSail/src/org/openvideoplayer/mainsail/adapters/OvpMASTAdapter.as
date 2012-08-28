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

package org.openvideoplayer.mainsail.adapters {

	import flash.display.Sprite;
	import flash.events.EventDispatcher;

	import org.openvideoplayer.advertising.*;
	import org.openvideoplayer.plugins.*;

	/**
	 * @eventType org.openvideoplayer.advertising.MASTAdapterEvent.OnPlay
	 */
	[Event(name="OnPlay", type="org.openvideoplayer.advertising.MASTAdapterEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.MASTAdapterEvent.OnStop
	 */
	[Event(name="OnStop", type="org.openvideoplayer.advertising.MASTAdapterEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.MASTAdapterEvent.OnPause
	 */
	[Event(name="OnPause", type="org.openvideoplayer.advertising.MASTAdapterEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.MASTAdapterEvent.OnMute
	 */
	[Event(name="OnMute", type="org.openvideoplayer.advertising.MASTAdapterEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.MASTAdapterEvent.OnVolumeChange
	 */
	[Event(name="OnVolumeChange", type="org.openvideoplayer.advertising.MASTAdapterEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.MASTAdapterEvent.OnSeek
	 */
	[Event(name="OnSeek", type="org.openvideoplayer.advertising.MASTAdapterEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.MASTAdapterEvent.OnItemStart
	 */
	[Event(name="OnItemStart", type="org.openvideoplayer.advertising.MASTAdapterEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.MASTAdapterEvent.OnItemEnd
	 */
	[Event(name="OnItemEnd", type="org.openvideoplayer.advertising.MASTAdapterEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.MASTAdapterEvent.OnFullScreenChange
	 */
	[Event(name="OnFullScreenChange", type="org.openvideoplayer.advertising.MASTAdapterEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.MASTAdapterEvent.OnPlayerSizeChanged
	 */
	[Event(name="OnPlayerSizeChanged", type="org.openvideoplayer.advertising.MASTAdapterEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.MASTAdapterEvent.OnError
	 */
	[Event(name="OnError", type="org.openvideoplayer.advertising.MASTAdapterEvent")]
	/**
	 * @eventType org.openvideoplayer.advertising.MASTAdapterEvent.OnMouseOver
	 */
	[Event(name="OnMouseOver", type="org.openvideoplayer.advertising.MASTAdapterEvent")]

	/**
	 * The purpose of this class is to map player events and properties to those 
	 * in a MAST document.
	 */
	public class OvpMASTAdapter extends EventDispatcher implements IMASTAdapter {
		private var _player:IOvpPlayer;
		private var _playerState:String;

		/**
		 * Constructor
		 *
		 * @param player The object implementing the IOvpPlayer
		 */
		public function OvpMASTAdapter(player:IOvpPlayer) {
			_player = player;
			_playerState = "";
			_player.addEventListener(OvpPlayerEvent.CUEPOINT, onPlayerCuepoint);
			_player.addEventListener(OvpPlayerEvent.ERROR, onPlayerError);
			_player.addEventListener(OvpPlayerEvent.VOLUME_CHANGE, onPlayerVolumeChange);
			_player.addEventListener(OvpPlayerEvent.STATE_CHANGE, onPlayerStateChange);
		}

		//-------------------------------------------------------------------
		// 
		// Properties
		//
		//-------------------------------------------------------------------

		/**
		 *
		 * @return
		 */
		public function get duration():Number {
			return _player.duration;
		}

		public function get position():Number {
			return _player.position;;
		}

		public function get watchedTime():Number {
			return 0;
		}

		public function get totalWatchedTime():Number {
			return 0;
		}

		public function get systemTime():Date {
			return new Date(); // now
		}

		public function get fullScreen():Boolean {
			return _player.fullScreen;
		}

		public function get isPlaying():Boolean {
			return (_playerState == OvpPlayerEvent.PLAYING);
		}

		public function get isPaused():Boolean {
			return (_playerState == OvpPlayerEvent.PAUSED);
		}

		public function get isStopped():Boolean {
			return (_playerState != OvpPlayerEvent.PLAYING);
		}

		public function get captionsActive():Boolean {
			return _player.captionsActive;
		}

		public function get hasVideo():Boolean {
			return _player.hasVideo;
		}

		public function get hasAudio():Boolean {
			return _player.hasAudio;;
		}

		public function get hasCaptions():Boolean {
			return _player.hasCaptions;
		}

		public function get itemsPlayed():int {
			return _player.itemsPlayed;
		}

		public function get playerWidth():int {
			return _player.playerWidth;
		}

		public function get playerHeight():int {
			return _player.playerHeight;
		}

		public function get contentWidth():int {
			return _player.contentWidth;
		}

		public function get contentHeight():int {
			return _player.contentHeight;
		}

		public function get contentBitrate():int {
			return _player.currentBitrate;
		}

		public function get contentTitle():String {
			return _player.contentTitle;
		}

		public function get contentUrl():String {
			return _player.contentURL;
		}

		public function get pollingFrequency():int {
			return 500;
		}

		//-------------------------------------------------------------------
		// 
		// Methods
		//
		//-------------------------------------------------------------------

		public function addCuePoint(cp:Object):void {
			_player.addCuePoint(cp);
		}

		/**
		 * Look for a plug-in that can handle the source format
		 */
		public function activateTrigger(trigger:IMASTTrigger):void {
			var sources:Array = trigger.sources;

			for (var i:int = 0; i < sources.length; i++) {
				var mastSource:IMASTSource = sources[i];

				// Get the list of plug-ins from the player and look for one that implements IMASTPayloadHandler
				var pluginSprites:Array = _player.plugins;

				for (var j:int = 0; j < pluginSprites.length; j++) {
					var plugInSprite:Object = pluginSprites[j];

					// Find the VAST Handler and ask it to handle our MAST payload						
					if (plugInSprite is IMASTPayloadHandler) {
						var mastPayloadHandler:IMASTPayloadHandler = plugInSprite as IMASTPayloadHandler;

						// See if it can handle our payload
						if (mastPayloadHandler.handlePayload(mastSource, this)) {
							if (plugInSprite is IOvpPlugIn) {
								pluginTrace("Found MAST payload handler: " + plugInSprite.ovpPlugInName + " for MAST Source format: " + mastSource.format);
							}
						}
					}
				}
			}
		}

		public function deactivateTrigger(trigger:IMASTTrigger):void {
		}

		/**
		 * Map the target regions in the MAST document to the Movie Clips in the player.
		 */
		public function getTargetRegionMovieClip(region:String):Sprite {
			switch (region.toLowerCase()) {
				case "linear":
					return _player.getSpriteById("linearAdMC");
					break;
			}

			return null;
		}


		//-------------------------------------------------------------------
		// 
		// Events
		//
		//-------------------------------------------------------------------

		private function onPlayerCuepoint(e:OvpPlayerEvent):void {
			dispatchEvent(new MASTAdapterEvent(MASTAdapterEvent.OnCuePoint, e.data));
		}

		private function onPlayerError(e:OvpPlayerEvent):void {
			dispatchEvent(new MASTAdapterEvent(MASTAdapterEvent.OnError, e.data));
		}

		private function onPlayerVolumeChange(e:OvpPlayerEvent):void {
			dispatchEvent(new MASTAdapterEvent(MASTAdapterEvent.OnVolumeChange, e.data));
		}

		private function onPlayerStateChange(e:OvpPlayerEvent):void {
			pluginTrace("onPlayerStateChange - e.data = " + e.data);

			_playerState = e.data as String;

			switch (e.data) {
				case OvpPlayerEvent.START_NEW_ITEM:
					dispatchEvent(new MASTAdapterEvent(MASTAdapterEvent.OnItemStart));
					break;
				case OvpPlayerEvent.WAITING:
					break;
				case OvpPlayerEvent.CONNECTING:
					break;
				case OvpPlayerEvent.BUFFERING:
					break;
				case OvpPlayerEvent.PLAYING:
					dispatchEvent(new MASTAdapterEvent(MASTAdapterEvent.OnPlay));
					break;
				case OvpPlayerEvent.PAUSED:
					dispatchEvent(new MASTAdapterEvent(MASTAdapterEvent.OnPause));
					break;
				case OvpPlayerEvent.SEEKING:
					dispatchEvent(new MASTAdapterEvent(MASTAdapterEvent.OnSeek));
					break;
				case OvpPlayerEvent.COMPLETE:
					dispatchEvent(new MASTAdapterEvent(MASTAdapterEvent.OnItemEnd));
					break;
			}
		}

		private function pluginTrace(... arguments):void {
			var debugmsg:String = "> OvpMastAdapter - " + arguments;
			dispatchEvent(new OvpPlayerEvent(OvpPlayerEvent.DEBUG_MSG, debugmsg));
		}
	}
}
