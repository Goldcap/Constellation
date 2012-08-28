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

	public class VASTMediaFile {

		/**
		 * @private
		 */
		private var _id:String;
		/**
		 * streaming or progressive
		 *
		 * @private
		 */
		private var _delivery:String;
		/**
		 * @private
		 */
		private var _bitrate:int;
		/**
		 * @private
		 */
		private var _url:String;
		/**
		 * @private
		 */
		private var _width:int;
		/**
		 * @private
		 */
		private var _height:int;
		/**
		 * The MIME type of the video file asset, i.e., video/x-flv, video/x-ms-wmv, video/x-ra
		 *
		 * @private
		 */
		private var _type:String;

		/**
		 * Constructor
		 */
		public function VASTMediaFile() {
		}

		/**
		 *
		 * @return
		 */
		public function get id():String {
			return _id;
		}

		/**
		 *
		 * @param value
		 */
		public function set id(value:String):void {
			_id = value;
		}

		/**
		 *
		 * @return
		 */
		public function get delivery():String {
			return _delivery;
		}

		/**
		 *
		 * @param value
		 */
		public function set delivery(value:String):void {
			_delivery = value;
		}

		/**
		 *
		 * @return
		 */
		public function get bitrate():int {
			return _bitrate;
		}

		/**
		 *
		 * @param value
		 */
		public function set bitrate(value:int):void {
			_bitrate = value;
		}

		/**
		 *
		 * @return
		 */
		public function get url():String {
			return _url;
		}

		/**
		 *
		 * @param value
		 */
		public function set url(value:String):void {
			_url = value;
		}

		/**
		 *
		 * @return
		 */
		public function get width():int {
			return _width;
		}

		/**
		 *
		 * @param value
		 */
		public function set width(value:int):void {
			_width = value;
		}

		/**
		 *
		 * @return
		 */
		public function get height():int {
			return _height;
		}

		/**
		 *
		 * @param value
		 */
		public function set height(value:int):void {
			_height = value;
		}

		/**
		 *
		 * @return
		 */
		public function get type():String {
			return _type;
		}

		/**
		 *
		 * @param value
		 */
		public function set type(value:String):void {
			_type = value;
		}
	}
}
