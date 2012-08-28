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

	import org.openvideoplayer.advertising.IMASTSource;

	public class MASTSource implements IMASTSource {
		private var _childSources:Array;
		private var _targets:Array;
		private var _uri:String; // Source document to act upon when triggered
		private var _altReference:String; // ID of source to act upon when triggered, used along with Uri - assumes player has a source we can reference with this ID
		private var _format:String; // Format of source document, for example: "VAST".

		/**
		 * Constructor
		 */
		public function MASTSource() {
			_childSources = new Array();
			_targets = new Array();
		}

		public function get uri():String {
			return _uri;
		}

		public function set uri(val:String):void {
			_uri = val;
		}

		public function get altReference():String {
			return _altReference;
		}

		public function set altReference(val:String):void {
			_altReference = val;
		}

		public function get format():String {
			return _format;
		}

		public function set format(val:String):void {
			_format = val;
		}

		public function get childSources():Array {
			return _childSources;
		}

		public function get targets():Array {
			return _targets;
		}

		public function get sources():Array {
			return _childSources;
		}

		public function addChildSource(val:MASTSource):void {
			_childSources.push(val);
		}

		public function addTarget(val:MASTTarget):void {
			_targets.push(val);
		}
	}
}
