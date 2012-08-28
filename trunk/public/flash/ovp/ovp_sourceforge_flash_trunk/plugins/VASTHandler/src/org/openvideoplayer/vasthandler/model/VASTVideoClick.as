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

	public class VASTVideoClick {

		/**
		 * an Array of VASTUrl objects
		 *
		 * @private
		 */
		private var _clickThroughs:Array;
		/**
		 * an Array of VASTUrl objects
		 *
		 * @private
		 */
		private var _clickTrackings:Array;
		/**
		 * an Array of VASTUrl objects
		 *
		 * @private
		 */
		private var _customClicks:Array;

		/**
		 * Constructor
		 */
		public function VASTVideoClick() {
			_clickThroughs = new Array();
			_clickTrackings = new Array();
			_customClicks = new Array();
		}

		/**
		 *
		 * @return
		 */
		public function get clickThroughs():Array {
			return _clickThroughs;
		}

		/**
		 *
		 * @param value
		 */
		public function set clickThroughs(value:Array):void {
			_clickThroughs = value.concat();
		}

		/**
		 *
		 * @return
		 */
		public function get clickTrackings():Array {
			return _clickTrackings;
		}

		/**
		 *
		 * @param value
		 */
		public function set clickTrackings(value:Array):void {
			_clickTrackings = value.concat();
		}

		/**
		 *
		 * @return
		 */
		public function get customClicks():Array {
			return _customClicks;
		}

		/**
		 *
		 * @param value
		 */
		public function set customClicks(value:Array):void {
			_customClicks = value.concat();
		}
	}
}
