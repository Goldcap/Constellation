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

package org.openvideoplayer.mainsail.managers {

	import flash.utils.describeType;

	import org.openvideoplayer.advertising.IMASTAdapter;
	import org.openvideoplayer.advertising.MASTAdapterEvent;
	import org.openvideoplayer.mainsail.model.MASTCondition;

	public class ConditionManager {
		private var _triggerManager:TriggerManager;
		private var _mastAdapter:IMASTAdapter;
		private var _mastAdapterMeta:Array;	// The property names and their types from the IMastAdapter interface
		private var _propertyStartConditions:Array;
		private var _propertyEndConditions:Array;

		/**
		 * Constructor
		 */
		public function ConditionManager(triggerManager:TriggerManager, mastAdapter:IMASTAdapter) {
			_triggerManager = triggerManager;
			_mastAdapter = mastAdapter;

			_mastAdapterMeta = new Array();
			buildMastAdapterMetaData();

			_propertyStartConditions = new Array();
			_propertyEndConditions = new Array();
		}

		/**
		 * Adds a start condition to this condition manager instance.
		 * <p>
		 * If the condition is an event, we add an event listener right on the condition object. If the
		 * condition is a property, we add it to a collection so we can poll these
		 * conditions on an interval set by the MAST adapter, with the one exception
		 * of a "position" property. This is because it is more efficient to use the cue point
		 * manager class in OVP core for position property conditions.
		 * </p>
		 */
		public function addCondition(condition:MASTCondition, isStartCondition:Boolean = true):void {
			condition.conditionManager = this;
			if (condition.type == MASTCondition.CONDITION_TYPE_EVENT) {
				_mastAdapter.addEventListener(condition.name, isStartCondition ? condition.mastStartConditionEventHandler : condition.mastEndConditionEventHandler);
			} else {
				// Special case for the position condition because we can use an ActionScript cue point
				if (condition.name.toLowerCase() == "position") {
					_mastAdapter.addEventListener(MASTAdapterEvent.OnCuePoint, isStartCondition ? condition.cuePointEventHandlerStartCondition : condition.cuePointEventHandlerEndCondition);

					var cuePoint:Object = new Object();
					var time:Number = convertNumber(condition.value);

					cuePoint.time = time;
					cuePoint.name = condition.name;

					_mastAdapter.addCuePoint(cuePoint);
				} else {
					// Keep an array of the top-level property conditions so they can be polled
					if (isStartCondition) {
						_propertyStartConditions.push(condition);
					} else {
						_propertyEndConditions.push(condition);
					}
				}
			}
		}


		/**
		 * Evaluates Event conditions and any/all children of the event. Typically called from an
		 * event handler in MASTCondition object.
		 */
		public function evaluate(condition:MASTCondition, startCondition:Boolean = true):void {
			// We know the condition that was passed in to this method is true, that's how we got here. 
			// Now we need to evaluate it's children.  Child conditions are an implicit boolean 'AND', so they
			// all have to evaluate to true in order to activate the trigger.
			for (var i:int = 0; i < condition.childConditions.length; i++) {
				var childCondition:MASTCondition = condition.childConditions[i];
				if (childCondition.type == MASTCondition.CONDITION_TYPE_EVENT) {
					// Event condition types are not allowed as child conditions
					continue;
				}
				// If any child conditions evaluate to false, we can ignore this trigger. 
				if (!evaluateChild(childCondition)) {
					// Bail
					return;
				}
			}

			// We made it this far, so activate/de-activate the trigger
			if (startCondition) {
				_triggerManager.activate();
			} else {
				_triggerManager.deactivate();
			}
		}

		/**
		 * Evaluates top level property conditions, typically the result of a polling action in
		 * the Trigger Manager.
		 */
		public function evaluateAllPropertyConditions():void {
			for (var i:int = 0; i < _propertyStartConditions.length; i++) {
				var startCondition:MASTCondition = _propertyStartConditions[i];
				if (evaluateChild(startCondition)) {
					_triggerManager.activate();
				}
			}
			for (var j:int = 0; j < _propertyEndConditions.length; j++) {
				var endCondition:MASTCondition = _propertyEndConditions[j];
				if (evaluateChild(endCondition)) {
					_triggerManager.deactivate();
				}
			}
		}

		/**
		 * A recursive function to evaluate a condition.
		 */
		private function evaluateChild(condition:MASTCondition):Boolean {
			var retVal:Boolean = false;

			// Iterate over the IMASTAdapter metadata to find the property and value matching the condition
			for (var i:int = 0; i < _mastAdapterMeta.length; i++) {
				var metaObj:MASTAdapterMetaObject = _mastAdapterMeta[i];

				if (metaObj.name.toLowerCase() == condition.name.toLowerCase()) {
					var value:* = _mastAdapter[metaObj.name];
					retVal = evaluateCondition(value, metaObj.type, condition);
					break;
				}
			}
			if (retVal) {
				// Evaluate children
				for (var j:int = 0; j < condition.childConditions.length; j++) {
					retVal = evaluateChild(condition.childConditions[j]);
				}
			}

			return retVal;
		}

		private function evaluateCondition(value:*, type:String, condition:MASTCondition):Boolean {
			switch (type) {
				case "Number":
					return compareNumberCondition(value, condition);
				case "Boolean":
					return compareBooleanCondition(value, condition);
				case "Date":
					return compareDateCondition(value, condition);
				case "int":
					return compareIntCondition(value, condition);
				case "String":
					return compareStringCondition(value, condition);
			}
			return false;
		}

		private function compareStringCondition(value:String, condition:MASTCondition):Boolean {
			var condVal:String = condition.value;

			switch (condition.operator) {
				case MASTCondition.OPERATOR_EQ:
					return (value == condVal);
				case MASTCondition.OPERATOR_GEQ:
					return (value >= condVal);
				case MASTCondition.OPERATOR_GTR:
					return (value > condVal);
				case MASTCondition.OPERATOR_LEQ:
					return (value <= condVal);
				case MASTCondition.OPERATOR_LT:
					return (value < condVal);
				case MASTCondition.OPERATOR_NEQ:
					return (value != condVal);
			}
			return false;

		}

		private function compareIntCondition(value:int, condition:MASTCondition):Boolean {
			var condVal:int = int(condition.value);

			switch (condition.operator) {
				case MASTCondition.OPERATOR_EQ:
					return (value == condVal);
				case MASTCondition.OPERATOR_GEQ:
					return (value >= condVal);
				case MASTCondition.OPERATOR_GTR:
					return (value > condVal);
				case MASTCondition.OPERATOR_LEQ:
					return (value <= condVal);
				case MASTCondition.OPERATOR_LT:
					return (value < condVal);
				case MASTCondition.OPERATOR_MOD:
					return ((value % condVal) == 0);
				case MASTCondition.OPERATOR_NEQ:
					return (value != condVal);
			}
			return false;
		}

		private function compareNumberCondition(value:Number, condition:MASTCondition):Boolean {
			var condVal:Number = convertNumber(condition.value);

			switch (condition.operator) {
				case MASTCondition.OPERATOR_EQ:
					return (value == condVal);
				case MASTCondition.OPERATOR_GEQ:
					return (value >= condVal);
				case MASTCondition.OPERATOR_GTR:
					return (value > condVal);
				case MASTCondition.OPERATOR_LEQ:
					return (value <= condVal);
				case MASTCondition.OPERATOR_LT:
					return (value < condVal);
				case MASTCondition.OPERATOR_MOD:
					return ((value % condVal) == 0);
				case MASTCondition.OPERATOR_NEQ:
					return (value != condVal);
			}
			return false;
		}

		private function compareBooleanCondition(value:Boolean, condition:MASTCondition):Boolean {
			var condVal:Boolean = (condition.value.toLowerCase() == "true" ? true : false);

			switch (condition.operator) {
				case MASTCondition.OPERATOR_EQ:
					return (value == condVal);
				case MASTCondition.OPERATOR_NEQ:
					return (value != condVal);
			}
			return false;
		}

		private function compareDateCondition(value:Date, condition:MASTCondition):Boolean {
			var valueDate:Number = value.getTime();
			var condDate:Number = (condition.value as Date).getTime();

			switch (condition.operator) {
				case MASTCondition.OPERATOR_EQ:
					return (valueDate == condDate);
				case MASTCondition.OPERATOR_GEQ:
					return (valueDate >= condDate);
				case MASTCondition.OPERATOR_GTR:
					return (valueDate > condDate);
				case MASTCondition.OPERATOR_LEQ:
					return (valueDate <= condDate);
				case MASTCondition.OPERATOR_LT:
					return (valueDate < condDate);
				case MASTCondition.OPERATOR_NEQ:
					return (valueDate != condDate);
			}
			return false;
		}

		/**
		 * Rather than add constants here that match the properties in the IOvpMASTAdapter interface,
		 * we'll introspect (reflect) the interface instance for it's properties and return types.
		 * These propery names will map to the condition names in the MAST document.
		 */
		private function buildMastAdapterMetaData():void {
			var mastAdapterDescription:XML = flash.utils.describeType(_mastAdapter);
			var accessorList:XMLList = mastAdapterDescription..accessor;

			for (var i:int = 0; i < accessorList.length(); i++) {
				var metaObj:MASTAdapterMetaObject = new MASTAdapterMetaObject();
				metaObj.name = accessorList[i].@name.toString();
				metaObj.type = accessorList[i].@type.toString();

				this._mastAdapterMeta.push(metaObj);
			}
		}

		/**
		 * Takes a value as a string and converts it to a Number type.
		 *
		 * Checks for time value expressions in the following formats:
		 * 1) full clock format in "hours:minutes:seconds (such as 00:03:00)
		 * 2) offset time (such as 101s or 2m), h=hours, m=minutes, s=seconds.  Offset times without units should be assumed to be seconds.
		 * If any of these time value format are found, the Number returned should be considered in seconds.
		 *
		 * If the time string passed in does not contain any of the following
		 * characters: ":, h, m, or s", then the string will simply be converted to
		 * a Number value.
		 *
		 * @private
		 */
		private function convertNumber(time:String):Number {
			var timeVal:Number = 0;
			var a:Array = time.split(":");

			if (a.length > 1) {
				// clock format,  "hh:mm:ss"
				if (a.length == 3) {
					// Hour is present
					timeVal = a[0] * 3600;
					timeVal += a[1] * 60;
					timeVal += Number(a[2]);
				} else {
					// Hour is implied to be 00
					timeVal = a[0] * 60;
					timeVal += Number(a[1]);
				}
			} else {
				// offset time format, "1h", "8m", "10s"
				var mul:int = 0;

				switch (time.charAt(time.length - 1)) {
					case 'h':
						mul = 3600;
						break;
					case 'm':
						mul = 60;
						break;
					case 's':
						mul = 1;
						break;
				}

				if (mul) {
					timeVal = Number(time.substr(0, time.length - 1)) * mul;
				} else {
					timeVal = Number(time);
				}
			}

			return timeVal;
		}
	}
}
