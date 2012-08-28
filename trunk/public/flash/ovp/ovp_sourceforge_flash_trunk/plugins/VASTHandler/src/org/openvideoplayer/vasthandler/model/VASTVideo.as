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

	public class VASTVideo {

		/**
		 * @private
		 */
		private var _duration:String;
		/**
		 * @private
		 */
		private var _adID:String;
		/**
		 * an Array of VideoClick objects
		 *
		 * @private
		 */
		private var _videoClicks:Array;
		/**
		 * an Array of MediaFile objects
		 *
		 * @private
		 */
		private var _mediaFiles:Array;

		/**
		 * Constructor
		 */
		public function VASTVideo() {
			_videoClicks = new Array();
			_mediaFiles = new Array();
		}

		/**
		 *
		 * @return
		 */
		public function get duration():String {
			return _duration;
		}

		/**
		 *
		 * @param value
		 */
		public function set duration(value:String):void {
			_duration = value;
		}

		/**
		 *
		 * @return
		 */
		public function get adID():String {
			return _adID;
		}

		/**
		 *
		 * @param value
		 */
		public function set adID(value:String):void {
			_adID = value;
		}

		/**
		 *
		 * @return
		 */
		public function get videoClicks():Array {
			return _videoClicks;
		}

		/**
		 *
		 * @return
		 */
		public function get mediaFiles():Array {
			return _mediaFiles;
		}

		/**
		 *
		 * @param videoClick
		 */
		public function addVideoClick(videoClick:VASTVideoClick):void {
			_videoClicks.push(videoClick);
		}

		/**
		 *
		 * @param mediaFile
		 */
		public function addMediaFile(mediaFile:VASTMediaFile):void {
			_mediaFiles.push(mediaFile);
		}
	}
}
