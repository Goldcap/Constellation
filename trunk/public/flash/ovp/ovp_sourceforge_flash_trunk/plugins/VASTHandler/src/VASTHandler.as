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
	import flash.display.Sprite;
	import flash.events.Event;
	
	import org.openvideoplayer.advertising.*;
	import org.openvideoplayer.events.OvpError;
	import org.openvideoplayer.events.OvpEvent;
	import org.openvideoplayer.plugins.IOvpPlayer;
	import org.openvideoplayer.plugins.IOvpPlugIn;
	import org.openvideoplayer.plugins.OvpPlayerEvent;
	import org.openvideoplayer.vasthandler.model.VASTAd;
	import org.openvideoplayer.vasthandler.model.VASTMediaFile;
	import org.openvideoplayer.vasthandler.model.VASTTrackingEvent;
	import org.openvideoplayer.vasthandler.model.VASTUrl;
	import org.openvideoplayer.vasthandler.model.VASTVideo;
	import org.openvideoplayer.vasthandler.parser.VASTParser;
	import org.openvideoplayer.vasthandler.utils.Beacon;
	import org.openvideoplayer.version.OvpVersion;

	public class VASTHandler extends Sprite implements IOvpPlugIn, IMASTPayloadHandler {

		private var _tracingOn:Boolean;
		private var _hostPlayer:IOvpPlayer;
		private var _vastParser:VASTParser;
		private var _adPlayer:IVPAID;
		private var _adPlayerSWF:Sprite;
		private var _adPlayerParentSprite:Sprite;
		private var _mastAdapter:IMASTAdapter;
		private var _mastSource:IMASTSource;
		private var _currentAdVolume:Number;

		private const _PLUGIN_NAME_:String = "OVP VAST Handler";
		private const _PLUGIN_VERSION_:String = "v.1.0.3";
		private const _PLUGIN_DESC_:String = "The OVP VAST Handler knows how to load and play ads using the VAST IAB standard.";

		/**
		 * Constructor
		 */
		public function VASTHandler() {
			if (this.stage) {
				init();
			} else {
				addEventListener(Event.ADDED_TO_STAGE, init);
			}
		}

		private function init(e:Event = null):void {
			_tracingOn = false;
			_currentAdVolume = .8;
		}


		//-------------------------------------------------------------------
		//
		// IOvpPlugIn Implementation
		//
		//-------------------------------------------------------------------

		/**
		 * @inheritDoc
		 */
		public function get ovpPlugInName():String {
			return (_PLUGIN_NAME_ + " " + _PLUGIN_VERSION_);
		}

		/**
		 * @inheritDoc
		 */
		public function get ovpPlugInDescription():String {
			return _PLUGIN_DESC_
		}

		/**
		 * @inheritDoc
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
		 * @inheritDoc
		 */
		public function get ovpPlugInTracingOn():Boolean {
			return _tracingOn;
		}

		/**
		 * @inheritDoc
		 */
		public function set ovpPlugInTracingOn(value:Boolean):void {
			_tracingOn = value;
		}

		/**
		 * @inheritDoc
		 */
		public function initOvpPlugIn(player:IOvpPlayer):void {
			pluginTrace("initOvpPlugIn called...");
			_hostPlayer = player;
		}

		//-------------------------------------------------------------------
		//
		// IMASTPayloadHandler Implementation
		//
		//-------------------------------------------------------------------

		/**
		 * @inheritDoc
		 */
		public function handlePayload(src:IMASTSource, mastAdapter:IMASTAdapter):Boolean {
			_mastSource = src;

			var srcFormat:String = _mastSource.format;
			if (srcFormat.toLowerCase() != "vast") {
				return false;
			}

			_mastAdapter = mastAdapter;
			_mastAdapter.addEventListener(MASTAdapterEvent.OnEnd, onItemEnd);
			_mastAdapter.addEventListener(MASTAdapterEvent.OnItemEnd, onItemEnd);
			_mastAdapter.addEventListener(MASTAdapterEvent.OnVolumeChange, onVolumeChange);
			
			// Tell the host player we are going into "ad mode"
			_hostPlayer.advertisingMode = true;

			_vastParser = new VASTParser(_PLUGIN_NAME_, _tracingOn);
			_vastParser.addEventListener(OvpEvent.PARSED, parsedHandler);
			_vastParser.addEventListener(OvpEvent.ERROR, parseErrorHandler);
			pluginTrace("Parsing VAST document");
			_vastParser.load(src.uri);

			return true;
		}

		//-------------------------------------------------------------------
		//
		// Private
		//
		//-------------------------------------------------------------------
		
		private function parseErrorHandler(e:OvpEvent):void {
			_hostPlayer.advertisingMode = false;
			
			var err:OvpError = e.data as OvpError;
			pluginTrace("VAST Parser error: " + err.errorNumber + " : " + err.errorDescription);
		}

		private function parsedHandler(e:OvpEvent):void {
			var mediaFile:VASTMediaFile = determineAdToPlay();
			if (mediaFile) {
				pluginTrace("VAST plugin found an Ad player!");
				pluginTrace("Selected this Ad to play: " + mediaFile.url);
				if (_adPlayer) {
					_adPlayer.addEventListener(VPAIDEvent.AdLoaded, vpaidAdLoadedHandler);
					_adPlayer.addEventListener(VPAIDEvent.AdStopped, vpaidAdStoppedHandler);
					_adPlayer.addEventListener(VPAIDEvent.AdVideoComplete, vpaidAdStoppedHandler);
					_adPlayer.addEventListener(VPAIDEvent.AdError, vpaidAdStoppedHandler);
					_adPlayer.addEventListener(VPAIDEvent.AdUserClose, vpaidAdStoppedHandler);
					_adPlayer.addEventListener(VPAIDEvent.AdStarted, adTrackingHandler);
					_adPlayer.addEventListener(VPAIDEvent.AdVideoFirstQuartile, adTrackingHandler);
					_adPlayer.addEventListener(VPAIDEvent.AdVideoMidpoint, adTrackingHandler);
					_adPlayer.addEventListener(VPAIDEvent.AdVideoThirdQuartile, adTrackingHandler);
					_adPlayer.addEventListener(VPAIDEvent.AdVideoComplete, adTrackingHandler);
					_adPlayer.addEventListener(VPAIDEvent.AdPaused, adTrackingHandler);
					_adPlayer.addEventListener(VPAIDEvent.AdStopped, adTrackingHandler);
					
					

					_adPlayer.initAd(_mastAdapter.contentWidth, _mastAdapter.contentHeight, "normal", 0, mediaFile.url, "");
				}
			} else {
				pluginTrace("VAST plugin did not find an Ad player!");
			}

		}

		protected function determineAdToPlay():VASTMediaFile {
			pluginTrace("VAST plugin searching for Ad player");

			var vastAd:VASTAd = _vastParser.ads[0];
			var mimeType:String;
			var adURL:String;

			if (vastAd && vastAd.inlineAd && vastAd.inlineAd.videoAds.length) {
				for (var i:int = 0; i < vastAd.inlineAd.videoAds.length; i++) {
					var videoAd:VASTVideo = vastAd.inlineAd.videoAds[i];
					// Match on bitrate first
					var filteredArray:Array;
					if (_hostPlayer.currentBitrate > 0) {
						filteredArray = findAdByBitrate(videoAd.mediaFiles);
					} else {
						filteredArray = videoAd.mediaFiles.concat();
					}

					// Get the list of plug-ins from the player and look for one that implements IAdPlayer
					var pluginSprites:Array = this._hostPlayer.plugins;

					for (var j:int = 0; j < pluginSprites.length; j++) {
						var plugInSprite:Object = pluginSprites[j];

						if (plugInSprite is IAdPlayer) {
							var tempAdPlayer:IAdPlayer = (plugInSprite as IAdPlayer);
							if (tempAdPlayer) {
								// Now loop thru the filtered list and pick the first one the ad player can handle
								for (var k:int = 0; k < filteredArray.length; k++) {
									if (tempAdPlayer.supportsMimeType(filteredArray[k].type)) {
										if ((plugInSprite is IVPAID) || plugInSprite.hasOwnProperty("getVPAID")) {
											_adPlayer = (plugInSprite.getVPAID() as IVPAID);
											_adPlayerSWF = plugInSprite as Sprite;
										}
										return filteredArray[k];
									}
								}
							}
						}
					}

				}
			}
			return null;
		}

		protected function findAdByBitrate(mediaFiles:Array):Array {
			var filteredMediaFiles:Array = new Array();

			// Sort on bitrate, find the first one that is less than or equal to the player's bitrate
			var tempArray:Array = mediaFiles.concat();
			tempArray.sortOn("bitrate", Array.DESCENDING | Array.NUMERIC);

			var playerBitrate:int = _hostPlayer.currentBitrate;

			// There may be more than one with the same bitrate, i.e., progressive and streaming
			var lastSelectedBitrate:int = 0;
			var lowestBitrate:int = int.MAX_VALUE;
			var lowestBitrateIndex:int = 0;

			for (var i:int = 0; i < tempArray.length; i++) {
				var mediaFile:VASTMediaFile = tempArray[i] as VASTMediaFile;
				
				// Keep track of the lowest bitrate as we iterate
				if (mediaFile && (mediaFile.bitrate < lowestBitrate)) {
					lowestBitrateIndex = i;
					lowestBitrate = mediaFile.bitrate;
				}
				
				if ((mediaFile.bitrate <= playerBitrate) && (mediaFile.bitrate >= lastSelectedBitrate)) {
					lastSelectedBitrate = mediaFile.bitrate;
					filteredMediaFiles.push(mediaFile);
				}
			}
			
			// If we didn't find a match, return the ad with the lowest bitrate
			if (filteredMediaFiles.length == 0) {
				filteredMediaFiles.push(tempArray[lowestBitrateIndex]);
			}

			return filteredMediaFiles;
		}

		//-------------------------------------------------------------------
		//
		// VPAID Event Handlers
		//
		//-------------------------------------------------------------------

		private function vpaidAdLoadedHandler(e:VPAIDEvent):void {
			var parentMC:Sprite = null;

			if (_adPlayer && _mastSource && _mastSource.targets) {
				// Now find out what movie clip we need to add the ad player to
				for (var i:int = 0; i < _mastSource.targets.length; i++) {
					var mastTarget:IMASTTarget = _mastSource.targets[i];
					parentMC = _mastAdapter.getTargetRegionMovieClip(mastTarget.region);

					if (parentMC)
						break;
				}
			} else if (_adPlayer) {
				// No MAST source, so maybe a test app loaded this plug-in, give it a chance to return a parent movie clip
				parentMC = _hostPlayer.getSpriteById("vast_handler_test_sprite");
			}

			if (parentMC) {
				parentMC.addChild(_adPlayerSWF);
				_adPlayer.startAd();
				_adPlayer.adVolume = _currentAdVolume;
				_adPlayerParentSprite = parentMC;
			}
		}

		private function vpaidAdStoppedHandler(e:VPAIDEvent):void {
			if (_adPlayerParentSprite.contains(_adPlayerSWF)) {
				_adPlayerParentSprite.removeChild(_adPlayerSWF);
			}
			_hostPlayer.advertisingMode = false;
		}

		private function adTrackingHandler(e:VPAIDEvent):void {
			switch (e.type)
			{
				case VPAIDEvent.AdStarted:
					processTrackingEvent(VASTTrackingEvent.START);
					break;
				case VPAIDEvent.AdVideoFirstQuartile:
					processTrackingEvent(VASTTrackingEvent.FIRST_QUARTILE);
					break;
				case VPAIDEvent.AdVideoMidpoint:
					processTrackingEvent(VASTTrackingEvent.MIDPOINT);
					break;
				case VPAIDEvent.AdVideoThirdQuartile:
					processTrackingEvent(VASTTrackingEvent.THIRD_QUARTILE);
					break;
				case VPAIDEvent.AdVideoComplete:
					processTrackingEvent(VASTTrackingEvent.COMPLETE);
					break;
				case VPAIDEvent.AdPaused:
					processTrackingEvent(VASTTrackingEvent.PAUSE);
					break;
				case VPAIDEvent.AdStopped:
					processTrackingEvent(VASTTrackingEvent.STOP);
					break;
			}
		}
		
		private function processTrackingEvent(eventName:String):void {
			var vastAd:VASTAd = _vastParser.ads[0];

			if (vastAd && vastAd.inlineAd && vastAd.inlineAd.trackingEvents) {
				for each (var trackingEvent:VASTTrackingEvent in vastAd.inlineAd.trackingEvents) {
					if (trackingEvent.event == eventName)
					{
						for each (var vastURL:VASTUrl in trackingEvent.urls) {
							var beacon:Beacon = new Beacon(vastURL.url);
							beacon.ping();
						}
					}
				}
			}			
		}
		
		//-------------------------------------------------------------------
		//
		// MASTAdapterEvent Event Handlers
		//
		//-------------------------------------------------------------------

		/**
		 * Stop the ad if one is playing
		 */
		private function onItemEnd(e:Event):void {
			if (_adPlayer) {
				_adPlayer.stopAd();
			}
		}

		private function onVolumeChange(e:MASTAdapterEvent):void {
			pluginTrace("onVolumeChange: e.data=" + e.data+ " _adPlayer="+_adPlayer);

			if (_adPlayer) {
				pluginTrace("asking ad player to set volume to: " + e.data);
				_adPlayer.adVolume = Number(e.data);
			}
			_currentAdVolume = Number(e.data);
		}

		//-------------------------------------------------------------------
		//
		// Helper Methods
		//
		//-------------------------------------------------------------------

		private function pluginTrace(... arguments):void {
			if (arguments[0] && _tracingOn && _hostPlayer) {
				var debugmsg:String = "> OVP VAST Handler - " + arguments;
				trace(debugmsg);
				_hostPlayer.dispatchEvent(new OvpPlayerEvent(OvpPlayerEvent.DEBUG_MSG, debugmsg));
			} else
				return;
		}
	}
}
