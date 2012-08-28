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

package org.openvideoplayer.mainsail.parser {

	import org.openvideoplayer.events.*;
	import org.openvideoplayer.mainsail.model.MAST;
	import org.openvideoplayer.mainsail.model.MASTCondition;
	import org.openvideoplayer.mainsail.model.MASTSource;
	import org.openvideoplayer.mainsail.model.MASTTarget;
	import org.openvideoplayer.mainsail.model.MASTTrigger;
	import org.openvideoplayer.parsers.ParserBase;
	import org.openvideoplayer.plugins.OvpPlayerEvent;

	/**
	 * Dispatched when an error condition has occurred. The event provides an error number and a verbose description
	 * of each error.
	 * @see org.openvideoplayer.events.OvpEvent#ERROR
	 */
	[Event(name="error", type="org.openvideoplayer.events.OvpEvent")]
	/**
	 * Dispatched when the MAST document has been successfully parsed.
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

	public class MASTParser extends ParserBase {
		private var _mastObj:MAST;
		private var _tracingOn:Boolean;

		/**
		 * Constructor
		 *
		 * @param pluginName
		 * @param traceOn
		 */
		public function MASTParser(traceOn:Boolean) {
			_mastObj = new MAST();
			_tracingOn = traceOn;
		}

		/**
		 * Returns the top level MAST object. Use this propery after the MAST document has been parsed,
		 * i.e., in your parsed event listener
		 */
		public function get mastObj():MAST {
			return _mastObj;
		}

		/**
		 * @private
		 */
		override protected function parseXML():void {

			try {
				var children:XMLList = _xml.children();

				for (var i:int = 0; i < children.length(); i++) {
					var child:XML = children[i];

					switch (child.nodeKind()) {
						case "element":
							switch (child.localName()) {
							case "triggers":
								parseTriggers(child);
								break;
						}
							break;
					}
				}
			} catch (err:Error) {
				pluginTrace("Exception occurred in parseXML() - " + err.message);
				throw err;
			} finally {
				_busy = false;
			}

			dispatchEvent(new OvpEvent(OvpEvent.PARSED));
		}

		private function parseTriggers(node:XML):void {
			var children:XMLList = node.children();

			for (var i:int = 0; i < children.length(); i++) {
				var child:XML = children[i];

				switch (child.nodeKind()) {
					case "element":
						switch (child.localName()) {
						case "trigger":  {
							var trigger:MASTTrigger = new MASTTrigger();
							trigger.id = child.@id;
							trigger.description = child.@description;
							parseChildNodes(child, trigger);
							this._mastObj.addTrigger(trigger);
						}
							break;
					}
						break;
				}
			}
		}

		private function parseChildNodes(node:XML, trigger:MASTTrigger):void {
			var children:XMLList = node.children();

			for (var i:int = 0; i < children.length(); i++) {
				var child:XML = children[i];

				switch (child.nodeKind()) {
					case "element":
						switch (child.localName()) {
						case "startConditions":
							var startConditions:Array = parseCondition(child, trigger);
							trigger.addStartConditions(startConditions);
							break;
						case "endConditions":
							var endConditions:Array = parseCondition(child, trigger);
							trigger.addEndConditions(endConditions);
							break;
						case "sources":
							var source:MASTSource = parseSources(child);
							trigger.addSource(source);
							break;
					}
						break;
				}
			}
		}

		/**
		 * Returns an array of MASTCondition objects.
		 */
		private function parseCondition(node:XML, trigger:MASTTrigger):Array {
			var condObjs:Array = new Array();
			var children:XMLList = node.children();

			for (var i:int = 0; i < children.length(); i++) {
				var child:XML = children[i];

				switch (child.nodeKind()) {
					case "element":
						switch (child.localName()) {
						case "condition":
							var condObj:MASTCondition = new MASTCondition(trigger);
							condObj.type = child.@type;
							condObj.name = child.@name;
							condObj.value = child.@value;
							condObj.operator = child.@operator;
							// Look for child conditions
							var childConds:Array = parseCondition(child, trigger);
							if (childConds.length) {
								condObj.addChildConditions(childConds);
							}
							condObjs.push(condObj);
							break;
					}
						break;
				}
			}
			return condObjs;
		}

		private function parseSources(node:XML):MASTSource {
			var sourceObj:MASTSource = null;
			var children:XMLList = node.children();

			for (var i:int = 0; i < children.length(); i++) {
				var child:XML = children[i];

				switch (child.nodeKind()) {
					case "element":
						switch (child.localName()) {
						case "source":
							sourceObj = new MASTSource();
							sourceObj.uri = child.@uri;
							sourceObj.format = child.@format;
							parseSource(child, sourceObj)
							break;
					}
						break;
				}
			}

			return sourceObj;
		}

		private function parseSource(node:XML, sourceObj:MASTSource):void {
			var children:XMLList = node.children();

			for (var i:int = 0; i < children.length(); i++) {
				var child:XML = children[i];

				switch (child.nodeKind()) {
					case "element":
						switch (child.localName()) {
						case "sources":
							var childSource:MASTSource = parseSources(child);
							if (childSource) {
								sourceObj.addChildSource(childSource);
							}
							break;
						case "targets":
							var target:MASTTarget = parseTarget(child);
							if (target) {
								sourceObj.addTarget(target);
							}
							break;
					}
				}
			}

		}

		private function parseTarget(node:XML):MASTTarget {
			var targetObj:MASTTarget = null;
			var children:XMLList = node.children();

			for (var i:int = 0; i < children.length(); i++) {
				var child:XML = children[i];

				switch (child.nodeKind()) {
					case "element":
						switch (child.localName()) {
						case "target":
							targetObj = new MASTTarget();
							targetObj.region = child.@regionName;
							targetObj.id = child.@id;
							// Look for child targets
							var childTarget:MASTTarget = parseTarget(child);
							if (childTarget) {
								targetObj.addChildTarget(childTarget);
							}
							break;
					}
						break;
				}
			}
			return targetObj;


		}

		private function pluginTrace(... arguments):void {
			if (_tracingOn)
				dispatchEvent(new OvpPlayerEvent(OvpPlayerEvent.DEBUG_MSG, arguments));
		}
	}
}
