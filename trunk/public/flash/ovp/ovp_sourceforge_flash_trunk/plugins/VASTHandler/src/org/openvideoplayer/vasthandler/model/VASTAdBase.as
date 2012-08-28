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

	public class VASTAdBase {
		/**
		 * @private
		 */
		private var _id:String;
		/**
		 * @private
		 */
		private var _width:int;
		/**
		 * @private
		 */
		private var _height:int;
		/**
		 * @private
		 */
		private var _expandedWidth:int;
		/**
		 * @private
		 */
		private var _expandedHeight:int;
		/**
		 * iframe, script, html, static, other
		 *
		 * @private
		 */
		private var _resourceType:String;
		/**
		 * @private
		 */
		private var _creativeType:String;
		/**
		 * @private
		 */
		private var _url:String;
		/**
		 * @private
		 */
		private var _code:String;
		/**
		 * @private
		 */
		private var _clickThroughURL:String;
		/**
		 * @private
		 */
		private var _altText:String;
		/**
		 * @private
		 */
		private var _adParameters:String;


		/**
		 * Constructor
		 */
		public function VASTAdBase() {
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
		public function get expandedWidth():int {
			return _expandedWidth;
		}

		/**
		 *
		 * @param value
		 */
		public function set expandedWidth(value:int):void {
			_expandedWidth = value;
		}

		/**
		 *
		 * @return
		 */
		public function get expandedHeight():int {
			return _expandedHeight;
		}

		/**
		 *
		 * @param value
		 */
		public function set expandedHeight(value:int):void {
			_expandedHeight = value;
		}

		/**
		 *
		 * @return
		 */
		public function get resourceType():String {
			return _resourceType;
		}

		/**
		 *
		 * @param value
		 */
		public function set resourceType(value:String):void {
			_resourceType = value;
		}

		/**
		 *
		 * @return
		 */
		public function get creativeType():String {
			return _creativeType;
		}

		/**
		 *
		 * @param value
		 */
		public function set creativeType(value:String):void {
			_creativeType = value;
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
		public function get code():String {
			return _code;
		}

		/**
		 *
		 * @param value
		 */
		public function set code(value:String):void {
			_code = value;
		}

		/**
		 *
		 * @return
		 */
		public function get clickThroughURL():String {
			return _clickThroughURL;
		}

		/**
		 *
		 * @param value
		 */
		public function set clickThroughURL(value:String):void {
			_clickThroughURL = value;
		}

		/**
		 *
		 * @return
		 */
		public function get altText():String {
			return _altText;
		}

		/**
		 *
		 * @param value
		 */
		public function set altText(value:String):void {
			_altText = value;
		}

		/**
		 *
		 * @return
		 */
		public function get adParameters():String {
			return _adParameters;
		}

		/**
		 *
		 * @param value
		 */
		public function set adParameters(value:String):void {
			_adParameters = value;
		}
	}
}
