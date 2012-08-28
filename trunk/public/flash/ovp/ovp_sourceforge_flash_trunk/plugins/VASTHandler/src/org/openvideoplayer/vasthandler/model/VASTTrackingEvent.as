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

	public class VASTTrackingEvent {
		
		public static const START:String = "start";
		public static const FIRST_QUARTILE:String = "firstQuartile";
		public static const MIDPOINT:String = "midpoint";
		public static const THIRD_QUARTILE:String = "thirdQuartile";
		public static const COMPLETE:String = "complete";
		public static const MUTE:String = "mute";
		public static const PAUSE:String = "pause";
		public static const REPLAY:String = "replay";
		public static const FULLSCREEN:String = "fullscreen";
		public static const STOP:String = "stop";

		/**
		 * i.e., "start", "midpoint", "complete", etc.
		 *
		 * @private
		 */
		private var _event:String;

		/**
		 * an Array of VASTUrl projects
		 *
		 * @private
		 */
		private var _urls:Array;

		/**
		 * Constructor
		 *
		 * @param event
		 */
		public function VASTTrackingEvent(event:String) {
			_event = event;
			_urls = new Array();
		}

		/**
		 *
		 * @return
		 */
		public function get event():String {
			return _event;
		}

		/**
		 *
		 * @param value
		 */
		public function set event(value:String):void {
			_event = value;
		}

		/**
		 *
		 * @return
		 */
		public function get urls():Array {
			return _urls;
		}

		/**
		 *
		 * @param value
		 */
		public function set urls(value:Array):void {
			_urls = value.concat();
		}
	}
}
