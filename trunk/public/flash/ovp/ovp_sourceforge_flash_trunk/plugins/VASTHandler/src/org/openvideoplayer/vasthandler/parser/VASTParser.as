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

package org.openvideoplayer.vasthandler.parser {

	import org.openvideoplayer.events.*;
	import org.openvideoplayer.parsers.ParserBase;
	import org.openvideoplayer.plugins.OvpPlayerEvent;
	import org.openvideoplayer.vasthandler.model.*;

	/**
	 * Dispatched when an error condition has occurred. The event provides an error number and a verbose description
	 * of each error.
	 * @see org.openvideoplayer.events.OvpEvent#ERROR
	 */
	[Event(name="error", type="org.openvideoplayer.events.OvpEvent")]
	/**
	 * Dispatched when the DFXP response has been successfully parsed.
	 *
	 * @see org.openvideoplayer.events.OvpEvent#PARSED
	 */
	[Event(name="parsed", type="org.openvideoplayer.events.OvpEvent")]
	/**
	 * Dispatched for debugging purposes.
	 *
	 * @see org.openvideoplayer.events.OvpEvent#DEBUG
	 */
	[Event(name="debug", type="org.openvideoplayer.plugins.OvpPlayerEvent")]


	public class VASTParser extends ParserBase {
		/**
		 * @private
		 */
		private var _xsi:Namespace;
		/**
		 * @private
		 */
		private var _ads:Array;
		/**
		 * should we trace?
		 *
		 * @private
		 */
		private var _tracingOn:Boolean;
		/**
		 * what's the name of the plugin using this class
		 *
		 * @private
		 */
		private var _pluginName:String;

		/**
		 * Constructor
		 *
		 * @param pluginName
		 * @param traceOn
		 */
		public function VASTParser(pluginName:String, traceOn:Boolean) {
			_pluginName = pluginName;
			_tracingOn = traceOn;
			_ads = new Array();
			super();
		}

		/**
		 *
		 * @return
		 */
		public function get ads():Array {
			return _ads;
		}

		/**
		 * @private
		 */
		override protected function parseXML():void {
			_xsi = _xml.namespace("xsi");

			try {
				// A VAST document can contain multiple Ad tags
				var adTags:XMLList = _xml.*;
				for each (var adNode:XML in adTags) {
					var vastAdObj:VASTAd = new VASTAd();
					vastAdObj.id = adNode.@id;
					parseAdTag(adNode, vastAdObj);
					_ads.push(vastAdObj);
				}
			} catch (err:Error) {
				pluginTrace("Exception occurred in parseXML() - " + err.message);
				throw err;
			} finally {
				_busy = false;
			}

			dispatchEvent(new OvpEvent(OvpEvent.PARSED));
		}

		/**
		 * @private
		 */
		private function parseAdTag(adNode:XML, vastAdObj:VASTAd):void {
			var children:XMLList = adNode.children();

			for (var i:uint = 0; i < children.length(); i++) {
				var child:XML = children[i];
				switch (child.nodeKind()) {
					case "element":
						switch (child.localName()) {
						case "InLine":
							parseInLineTag(child, vastAdObj);
							break;
						case "Wrapper":
							parseWrapperTag(child, vastAdObj);
							break;
						default:
							pluginTrace("parseAdTag() - Unsupported VAST tag :" + child.localName());
							break;
					}
						break;
				}
			}
		}

		/**
		 * @private
		 */
		private function parseInLineTag(inlineNode:XML, vastAdObj:VASTAd):void {
			var vastInlineObj:VASTAdInline = new VASTAdInline();

			var children:XMLList = inlineNode.children();

			for (var i:uint = 0; i < children.length(); i++) {
				var child:XML = children[i];
				switch (child.nodeKind()) {
					case "element":
						switch (child.localName()) {
						case "AdSystem":
							vastInlineObj.adSystem = child.toString();
							vastInlineObj.adSystemVersion = child.@version;
							break;
						case "AdTitle":
							vastInlineObj.adTitle = child.toString();
							break;
						case "Description":
							vastInlineObj.description = child.toString();
							break;
						case "Survey":
							vastInlineObj.surveyURL = child.toString();
							break;
						case "Error":
							vastInlineObj.errorURL = child.toString();
							break;
						case "Impression":
							parseImpressionTag(child, vastInlineObj);
							break;
						case "TrackingEvents":
							parseTrackingEvents(child, vastInlineObj);
							break;
						case "Video":
							parseVideo(child, vastInlineObj);
							break;
						case "CompanionAds":
							parseCompanionAds(child, vastInlineObj);
							break;
						case "NonLinearAds":
							parseNonLinearAds(child, vastInlineObj);
							break;
						case "Extensions":
							parseExtensions(child, vastInlineObj);
							break;
						default:
							pluginTrace("parseInlineTag() - Unsupported VAST tag:" + child.localName());
							break;
					}
				}
			}
			vastAdObj.inlineAd = vastInlineObj;
		}

		/**
		 * @private
		 */
		private function parseExtensions(node:XML, vastInlineObj:VASTAdInline):void {
			var children:XMLList = node.children();

			for (var i:uint = 0; i < children.length(); i++) {
				var child:XML = children[i];
				switch (child.nodeKind()) {
					case "element":
						switch (child.localName()) {
						case "Extension":
							vastInlineObj.addExtension(child);
							break;
						default:
							pluginTrace("parseExtensions() - Unsupported VAST tag:" + child.localName());
							break;
					}
						break;
				}
			}
		}

		/**
		 * @private
		 */
		private function parseImpressionTag(node:XML, vastInlineObj:VASTAdInline):void {
			if (node.URL) {
				var impression:VASTUrl = new VASTUrl(node.URL.@id, node.URL.toString());
				vastInlineObj.impression = impression;
			}
		}

		/**
		 * @private
		 */
		private function parseTrackingEvents(node:XML, vastInlineObj:VASTAdInline):void {
			var children:XMLList = node.children();

			for (var i:uint = 0; i < children.length(); i++) {
				var child:XML = children[i];
				switch (child.nodeKind()) {
					case "element":
						switch (child.localName()) {
						case "Tracking":  {
							var trackingEventObj:VASTTrackingEvent = new VASTTrackingEvent(child.@event);
							var trackingURLs:Array = parseURLTags(child);

							if (trackingURLs.length) {
								trackingEventObj.urls = trackingURLs;
								vastInlineObj.addTrackingEvent(trackingEventObj);
							}
							break;
						}
							break;
						default:
							pluginTrace("parseTrackingEvents() - Unsupported VAST tag:" + child.localName());
							break;
					}
						break;
				}
			}
		}

		/**
		 * Returns an array of VASTUrl objects.
		 *
		 * @private
		 */
		private function parseURLTags(parentNode:XML):Array {
			var urlArray:Array = new Array();
			var children:XMLList = parentNode.children();

			for (var i:uint = 0; i < children.length(); i++) {
				var child:XML = children[i];
				switch (child.nodeKind()) {
					case "element":
						switch (child.localName()) {
						case "URL":  {
							var vastURL:VASTUrl = new VASTUrl(child.@id, child.toString());
							urlArray.push(vastURL);
						}
							break;
						default:
							pluginTrace("parseURLTags() - Unsupported VAST tag:" + child.localName());
							break;
					}
						break;
				}
			}
			return urlArray;
		}

		/**
		 * @private
		 */
		private function parseVideo(node:XML, vastInlineObj:VASTAdInline):void {
			var videoObj:VASTVideo = new VASTVideo();

			var children:XMLList = node.children();

			for (var i:uint = 0; i < children.length(); i++) {
				var child:XML = children[i];
				switch (child.nodeKind()) {
					case "element":
						switch (child.localName()) {
						case "Duration":
							videoObj.duration = child.toString();
							break;
						case "AdID":
							videoObj.adID = child.toString();
							break;
						case "VideoClicks":
							parseVideoClicks(child, videoObj);
							break;
						case "MediaFiles":
							parseMediaFiles(child, videoObj);
							break;
						default:
							pluginTrace("parseVideo() - Unsupported VAST tag:" + child.localName());
							break;
					}
						break;
				}
			}
			vastInlineObj.addVideoAd(videoObj);
		}

		/**
		 * @private
		 */
		private function parseVideoClicks(node:XML, videoObj:VASTVideo):void {
			var videoClick:VASTVideoClick = new VASTVideoClick();

			var children:XMLList = node.children();

			for (var i:uint = 0; i < children.length(); i++) {
				var child:XML = children[i];
				switch (child.nodeKind()) {
					case "element":
						switch (child.localName()) {
						case "ClickThrough":  {
							var clickThrus:Array = this.parseURLTags(child);
							if (clickThrus.length) {
								videoClick.clickThroughs = clickThrus;
							}
						}
							break;
						case "ClickTracking":  {
							var clickTrackings:Array = this.parseURLTags(child);
							if (clickTrackings.length) {
								videoClick.clickTrackings = clickTrackings;
							}
						}
							break;
						case "CustomClick":  {
							var customClicks:Array = this.parseURLTags(child);
							if (customClicks.length) {
								videoClick.customClicks = customClicks;
							}
						}
							break;
						default:
							pluginTrace("parseVideoClicks() - Unsupported VAST tag:" + child.localName());
							break;
					}
						break;
				}
			}
			videoObj.addVideoClick(videoClick);
		}

		/**
		 * @private
		 */
		private function parseMediaFiles(node:XML, videoObj:VASTVideo):void {
			var children:XMLList = node.children();

			for (var i:uint = 0; i < children.length(); i++) {
				var child:XML = children[i];
				switch (child.nodeKind()) {
					case "element":
						switch (child.localName()) {
						case "MediaFile":  {
							var mediaFile:VASTMediaFile = new VASTMediaFile();
							mediaFile.id = child.@id;
							mediaFile.bitrate = parseInt(child.@bitrate);
							mediaFile.height = parseInt(child.@height);
							mediaFile.width = parseInt(child.@width);
							mediaFile.delivery = child.@delivery;
							mediaFile.type = child.@type;
							mediaFile.url = child.URL.toString();
							videoObj.addMediaFile(mediaFile);
						}
							break;
						default:
							pluginTrace("parseMediaFiles() - Unsupported VAST tag:" + child.localName());
							break;

					}
						break;
				}
			}
		}

		/**
		 * @private
		 */
		private function parseCompanionAds(node:XML, vastInlineObj:VASTAdInline):void {
			var children:XMLList = node.children();

			for (var i:uint = 0; i < children.length(); i++) {
				var child:XML = children[i];
				switch (child.nodeKind()) {
					case "element":
						switch (child.localName()) {
						case "Companion":  {
							var compAd:VASTCompanionAd = new VASTCompanionAd();
							compAd.id = child.@id;
							compAd.width = child.@width;
							compAd.height = child.@height;
							compAd.resourceType = child.@resourceType;
							compAd.creativeType = child.@creativeType;
							compAd.expandedWidth = child.@expandedWidth;
							compAd.expandedHeight = child.@expandedHeight;
							parseCompanionAd(child, compAd);
							vastInlineObj.addCompandionAd(compAd);
						}
							break;
						default:
							pluginTrace("parseCompanionAds() - Unsupported VAST tag:" + child.localName());
							break;
					}
						break;
				}
			}
		}

		/**
		 * @private
		 */
		private function parseCompanionAd(node:XML, compAd:VASTCompanionAd):void {
			var children:XMLList = node.children();

			for (var i:uint = 0; i < children.length(); i++) {
				var child:XML = children[i];
				switch (child.nodeKind()) {
					case "element":
						switch (child.localName()) {
						case "URL":
							compAd.url = child.toString();
							break;
						case "Code":
							compAd.code = child.toString();
							break;
						case "CompanionClickThrough":
							compAd.clickThroughURL = child.toString();
							break;
						case "AltText":
							compAd.altText = child.toString();
							break;
						case "AdParameters":
							compAd.adParameters = child.toString();
							break;
						default:
							pluginTrace("parseCompanionAd() - Unsupported VAST tag:" + child.localName());
							break;

					}
						break;
				}
			}
		}

		/**
		 * @private
		 */
		private function parseNonLinearAds(node:XML, vastInlineObj:VASTAdInline):void {
			var children:XMLList = node.children();

			for (var i:uint = 0; i < children.length(); i++) {
				var child:XML = children[i];
				switch (child.nodeKind()) {
					case "element":
						switch (child.localName()) {
						case "NonLinear":  {
							var nlAd:VASTNonLinearAd = new VASTNonLinearAd();
							nlAd.id = child.@id;
							nlAd.width = child.@width;
							nlAd.height = child.@height;
							nlAd.resourceType = child.@resourceType;
							nlAd.creativeType = child.@creativeType;
							nlAd.expandedWidth = child.@expandedWidth;
							nlAd.expandedHeight = child.@expandedHeight;
							nlAd.scalable = child.@scalable;
							nlAd.maintainAspectRatio = child.@maintainAspectRatio;
							nlAd.apiFramework = child.@apiFramework;
							parseNonLinearAd(child, nlAd);
							vastInlineObj.addNonLinearAd(nlAd);
						}
							break;
						default:
							pluginTrace("parseNonLinearAds() - Unsupported VAST tag:" + child.localName());
							break;
					}
						break;
				}
			}
		}

		/**
		 * @private
		 */
		private function parseNonLinearAd(node:XML, nlAd:VASTNonLinearAd):void {
			var children:XMLList = node.children();

			for (var i:uint = 0; i < children.length(); i++) {
				var child:XML = children[i];
				switch (child.nodeKind()) {
					case "element":
						switch (child.localName()) {
						case "URL":
							nlAd.url = child.toString();
							break;
						case "Code":
							nlAd.code = child.toString();
							break;
						case "NonLinearClickThrough":
							nlAd.clickThroughURL = child.toString();
							break;
						case "AltText":
							nlAd.altText = child.toString();
							break;
						case "AdParameters":
							nlAd.adParameters = child.toString();
							break;
						default:
							pluginTrace("parseNonLinearAd() - Unsupported VAST tag:" + child.localName());
							break;

					}
						break;
				}
			}
		}

		/**
		 * @private
		 */
		private function parseWrapperTag(wrapperNode:XML, vastAdObj:VASTAd):void {

		}


		//-------------------------------------------------------------------
		//
		// Helper Methods
		//
		//-------------------------------------------------------------------

		/**
		 * @private
		 */
		private function pluginTrace(... arguments):void {
			var debugmsg:String = "In VASTParser - " + arguments;
			if (_tracingOn)
				dispatchEvent(new OvpPlayerEvent(OvpPlayerEvent.DEBUG_MSG, debugmsg));
		}
	}
}