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

package org.openvideoplayer.vasthandler.model {

	public class VASTAdInline {
		/**
		 * @private
		 */
		private var _adSystem:String;
		/**
		 * @private
		 */
		private var _adSystemVersion:String;
		/**
		 * @private
		 */
		private var _adTitle:String;
		/**
		 * @private
		 */
		private var _description:String;
		/**
		 * @private
		 */
		private var _surveyURL:String;
		/**
		 * @private
		 */
		private var _impression:VASTUrl;
		/**
		 * @private
		 */
		private var _errorURL:String;
		/**
		 * an array of VASTTrackingEvent objects
		 *
		 * @private
		 */
		private var _trackingEvents:Array;
		/**
		 * an array of VASTVideo objects
		 *
		 * @private
		 */
		private var _videoAds:Array;
		/**
		 * an array of VASTCompanionAd objects
		 *
		 * @private
		 */
		private var _companionAds:Array;
		/**
		 * an array of VASTNonLinearAd objects
		 *
		 * @private
		 */
		private var _nonLinearAds:Array;
		/**
		 * an array of Flex XML objects
		 *
		 * @private
		 */
		private var _extensions:Array;

		/**
		 * Constructor
		 */
		public function VASTAdInline() {
			_trackingEvents = new Array();
			_videoAds = new Array();
			_companionAds = new Array();
			_nonLinearAds = new Array();
			_extensions = new Array();
		}

		/**
		 *
		 * @return
		 */
		public function get adSystem():String {
			return _adSystem;
		}

		/**
		 *
		 * @param value
		 */
		public function set adSystem(value:String):void {
			_adSystem = value;
		}

		/**
		 *
		 * @return
		 */
		public function get adSystemVersion():String {
			return _adSystemVersion;
		}

		/**
		 *
		 * @param value
		 */
		public function set adSystemVersion(value:String):void {
			_adSystemVersion = value;
		}

		/**
		 *
		 * @return
		 */
		public function get adTitle():String {
			return _adTitle;
		}

		/**
		 *
		 * @param value
		 */
		public function set adTitle(value:String):void {
			_adTitle = value;
		}

		/**
		 *
		 * @return
		 */
		public function get description():String {
			return _description;
		}

		/**
		 *
		 * @param value
		 */
		public function set description(value:String):void {
			_description = value;
		}

		/**
		 *
		 * @return
		 */
		public function get surveyURL():String {
			return _surveyURL;
		}

		/**
		 *
		 * @param value
		 */
		public function set surveyURL(value:String):void {
			_surveyURL = value;
		}

		/**
		 *
		 * @return
		 */
		public function get impression():VASTUrl {
			return _impression;
		}

		/**
		 *
		 * @param value
		 */
		public function set impression(value:VASTUrl):void {
			_impression = value;
		}

		/**
		 *
		 * @return
		 */
		public function get errorURL():String {
			return _errorURL;
		}

		/**
		 *
		 * @param value
		 */
		public function set errorURL(value:String):void {
			_errorURL = value;
		}

		/**
		 *
		 * @return
		 */
		public function get trackingEvents():Array {
			return _trackingEvents;
		}

		/**
		 *
		 * @return
		 */
		public function get videoAds():Array {
			return _videoAds;
		}

		/**
		 *
		 * @return
		 */
		public function get companionAds():Array {
			return _companionAds;
		}

		/**
		 *
		 * @return
		 */
		public function get nonLinearAds():Array {
			return _nonLinearAds;
		}

		/**
		 *
		 * @return
		 */
		public function get extensions():Array {
			return _extensions;
		}

		/**
		 *
		 * @param value
		 */
		public function addVideoAd(value:VASTVideo):void {
			_videoAds.push(value);
		}

		/**
		 *
		 * @param value
		 */
		public function addTrackingEvent(value:VASTTrackingEvent):void {
			_trackingEvents.push(value);
		}

		/**
		 *
		 * @param value
		 */
		public function addCompandionAd(value:VASTCompanionAd):void {
			_companionAds.push(value);
		}

		/**
		 *
		 * @param value
		 */
		public function addNonLinearAd(value:VASTNonLinearAd):void {
			_nonLinearAds.push(value);
		}

		/**
		 *
		 * @param value
		 */
		public function addExtension(value:XML):void {
			_extensions.push(value);
		}
	}
}

