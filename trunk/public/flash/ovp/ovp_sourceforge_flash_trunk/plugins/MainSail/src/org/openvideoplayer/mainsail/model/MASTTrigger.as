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

package org.openvideoplayer.mainsail.model {

	import org.openvideoplayer.advertising.IMASTTrigger;

	public class MASTTrigger implements IMASTTrigger {
		private var _startConditions:Array;
		private var _endConditions:Array;
		private var _sources:Array;
		private var _id:String;
		private var _description:String;

		/**
		 * Constructor
		 */
		public function MASTTrigger() {
			_startConditions = new Array();
			_endConditions = new Array();
			_sources = new Array();
		}

		/**
		 * @inheritDoc
		 */
		public function get id():String {
			return _id;
		}

		/**
		 * @inheritDoc
		 */
		public function set id(value:String):void {
			_id = value;
		}

		/**
		 * @inheritDoc
		 */
		public function get description():String {
			return _description;
		}

		/**
		 * @inheritDoc
		 */
		public function set description(value:String):void {
			_description = value;
		}

		/**
		 * @inheritDoc
		 */
		public function get startConditions():Array {
			return _startConditions;
		}

		/**
		 * @inheritDoc
		 */
		public function get endConditions():Array {
			return _endConditions;
		}

		/**
		 * @inheritDoc
		 */
		public function get sources():Array {
			return _sources;
		}

		public function addStartCondition(value:MASTCondition):void {
			_startConditions.push(value);
		}

		public function addStartConditions(value:Array):void {
			_startConditions = _startConditions.concat(value);
		}

		public function addEndCondition(value:MASTCondition):void {
			_endConditions.push(value);
		}

		public function addEndConditions(value:Array):void {
			_endConditions = _endConditions.concat(value);
		}

		public function addSource(value:MASTSource):void {
			_sources.push(value);
		}
	}
}
