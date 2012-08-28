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

	import org.openvideoplayer.advertising.IMASTTarget;

	public class MASTTarget implements IMASTTarget {
		private var _childTargets:Array;	// Child targets - treated as a dependant
		private var _region:String;	// Named target region to use
		private var _id:String;	// User defined type of target, can be used to determine target usage, such as 'companion', 'caption', etc.

		/**
		 * Constructor
		 */
		public function MASTTarget() {
			_childTargets = new Array();
		}

		/**
		 *
		 * @inheritDoc
		 */
		public function get region():String {
			return _region;
		}

		public function set region(val:String):void {
			_region = val;
		}

		/**
		 *
		 * @inheritDoc
		 */
		public function get id():String {
			return _id;
		}

		public function set id(val:String):void {
			_id = val;
		}

		/**
		 *
		 * @inheritDoc
		 */
		public function get childTargets():Array {
			return _childTargets;
		}

		/**
		 * Adds a child target to the collection of child targets.
		 */
		public function addChildTarget(val:MASTTarget):void {
			_childTargets.push(val);
		}
	}
}
