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

	import org.openvideoplayer.advertising.MASTAdapterEvent;
	import org.openvideoplayer.mainsail.managers.ConditionManager;

	public class MASTCondition {
		private var _childConditions:Array; // Child conditions - treated as an implicit boolean 'AND'
		private var _type:String; // "property" or "event"
		private var _name:String; // Name of player var or event to apply condition to
		private var _value:String; // The value the variable is compared against
		private var _operator:String; // The value the variable is compared against (see static consts below)
		private var _parentTrigger:MASTTrigger;
		private var _conditionManager:ConditionManager;

		public static const OPERATOR_EQ:String = "EQ";
		public static const OPERATOR_NEQ:String = "NEQ";
		public static const OPERATOR_GTR:String = "GTR";
		public static const OPERATOR_GEQ:String = "GEQ";
		public static const OPERATOR_LT:String = "LT";
		public static const OPERATOR_LEQ:String = "LEQ";
		public static const OPERATOR_MOD:String = "MOD";
		public static const CONDITION_TYPE_PROPERTY:String = "property";
		public static const CONDITION_TYPE_EVENT:String = "event";

		public function MASTCondition(parentTrigger:MASTTrigger) {
			_childConditions = new Array();
			_parentTrigger = parentTrigger;
		}

		public function get type():String {
			return _type;
		}

		public function set type(val:String):void {
			_type = val;
		}

		public function get name():String {
			return _name;
		}

		public function set name(val:String):void {
			_name = val;
		}

		public function get value():String {
			return _value;
		}

		public function set value(val:String):void {
			_value = val;
		}

		public function get operator():String {
			return _operator;
		}

		public function set operator(val:String):void {
			_operator = val;
		}

		public function get childConditions():Array {
			return _childConditions;
		}

		public function set conditionManager(value:ConditionManager):void {
			_conditionManager = value;
		}

		public function addChildCondition(val:MASTCondition):void {
			_childConditions.push(val);
		}

		public function addChildConditions(val:Array):void {
			_childConditions = _childConditions.concat(val);
		}

		public function mastStartConditionEventHandler(e:MASTAdapterEvent):void {
			if (_conditionManager) {
				_conditionManager.evaluate(this);
			}
		}

		public function mastEndConditionEventHandler(e:MASTAdapterEvent):void {
			if (_conditionManager) {
				_conditionManager.evaluate(this, false);
			}
		}

		/**
		 *
		 * @param e
		 */
		public function cuePointEventHandlerStartCondition(e:MASTAdapterEvent):void {
			if (_conditionManager) {
				_conditionManager.evaluate(this);
			}
		}

		public function cuePointEventHandlerEndCondition(e:MASTAdapterEvent):void {
			if (_conditionManager) {
				_conditionManager.evaluate(this, false);
			}
		}
	}
}
