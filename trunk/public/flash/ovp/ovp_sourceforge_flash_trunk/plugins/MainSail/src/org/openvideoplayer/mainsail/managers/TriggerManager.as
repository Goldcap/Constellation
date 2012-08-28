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

	import flash.events.TimerEvent;
	import flash.utils.Timer;

	import org.openvideoplayer.advertising.IMASTAdapter;
	import org.openvideoplayer.mainsail.model.MASTCondition;
	import org.openvideoplayer.mainsail.model.MASTTrigger;

	/**
	 * There is a TriggerManager for each MASTTrigger. The TriggerManager is responsible for activating and
	 * and de-activating the trigger.  Each TriggerManager object has an array of ConditionManager objects. Any of
	 * the ConditionManager objects can ask the TriggerManager to activate it's trigger. The TriggerManager can then
	 * look to see if it's trigger is active (activated by a previous condition), if not, it will activate the
	 * trigger.  If it's trigger is active, it can ignore the request to activate.
	 */
	public class TriggerManager {
		private var _mastAdapter:IMASTAdapter;
		private var _conditionManagers:Array;
		private var _triggerIsActive:Boolean;
		private var _trigger:MASTTrigger;
		private var _pollingTimer:Timer;

		public function TriggerManager(mastAdapter:IMASTAdapter) {
			_mastAdapter = mastAdapter;
			_conditionManagers = new Array();
			_triggerIsActive = false;
			_pollingTimer = new Timer(_mastAdapter.pollingFrequency);
			_pollingTimer.addEventListener(TimerEvent.TIMER, onPollingTimer);
		}

		public function set trigger(trigger:MASTTrigger):void {
			_trigger = trigger;
			setupStartConditions();
			setupEndConditions();
			_pollingTimer.start();
		}

		/**
		 * Each ConditionManager object contains one condition, but that condition can have child conditions and the
		 * ConditionManager knows how to evaluate the conditions and it's children.
		 */
		private function setupStartConditions():void {
			var len:int = _trigger.startConditions.length;

			for (var i:int = 0; i < len; i++) {
				var conditionManager:ConditionManager = new ConditionManager(this, _mastAdapter);
				var condition:MASTCondition = _trigger.startConditions[i];

				conditionManager.addCondition(condition);
				_conditionManagers.push(conditionManager);
			}
		}

		/**
		 * Each ConditionManager object contains one condition, but that condition can have child conditions and the
		 * ConditionManager knows how to evaluate the conditions and it's children.
		 */
		private function setupEndConditions():void {
			var len:int = _trigger.endConditions.length;

			for (var i:int = 0; i < len; i++) {
				var conditionManager:ConditionManager = new ConditionManager(this, _mastAdapter);
				var condition:MASTCondition = _trigger.endConditions[i];

				conditionManager.addCondition(condition, false);
				_conditionManagers.push(conditionManager);
			}
		}

		/**
		 * Activates the trigger in the MAST Adapter.
		 */
		public function activate():void {
			if (_mastAdapter && !_triggerIsActive) {
				_mastAdapter.activateTrigger(_trigger);
				_triggerIsActive = true;
			}
		}

		/**
		 * Deactivates the trigger, allowing it to be activated again.
		 */
		public function deactivate():void {
			_triggerIsActive = false;
		}

		public function startPolling(start:Boolean = true):void {
			if (start) {
				if (!_pollingTimer.running) {
					_pollingTimer.start();
				}
			} else {
				_pollingTimer.stop();
			}
		}

		private function onPollingTimer(e:TimerEvent):void {
			for (var i:int = 0; i < _conditionManagers.length; i++) {
				var condMgr:ConditionManager = _conditionManagers[i];
				condMgr.evaluateAllPropertyConditions();
			}
		}
	}
}
