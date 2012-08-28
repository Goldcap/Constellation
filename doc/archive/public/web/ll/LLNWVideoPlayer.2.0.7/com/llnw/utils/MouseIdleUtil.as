/**
* LIMELIGHT NETWORKS INCORPORATED
* Copyright 2009 Limelight Networks Incorporated
* All Rights Reserved.
*
* NOTICE:  Limelight permits you to use, modify, and distribute this file in accordance with the
* terms of the Limelight end user license agreement accompanying it.  If you have received this file from a
* source other than Limelight, then your use, modification, or distribution of it requires the prior
* written permission of Limelight.
*/
package com.llnw.utils 
{
	import flash.display.Stage;
	import flash.events.Event;
	import flash.events.EventDispatcher;
	import flash.events.MouseEvent;

	import flash.events.TimerEvent;
	import flash.utils.Timer;
	/**
	 * ...
	 * @author LLNW.
	 * Handles idle mouse time. Dispatches events for when the mouse hasn't
	 * moved. 
	 * 
	 * Takes the Stage object and the amount of time you desire for 
	 * the idle to be detected in milliseconds.
	 * 
	 */
	public class MouseIdleUtil extends EventDispatcher
	{
		private var _mouseTimer:Timer;
		private var _mouseIdle:Boolean = false;
		private var _stage:Stage;
		public function MouseIdleUtil() 
		{
			//nada
		}
		
		/** 
		 * Takes the Stage object and the amount of time you desire for 
		 * the idle to be detected in milliseconds.
		 */
		public function startMouseIdle(thisStage:Stage, idleTime:int):void {
			_stage = thisStage;
			_mouseTimer = new Timer(idleTime, 1)
			_mouseTimer.addEventListener(TimerEvent.TIMER,mouseChecking)
			_stage.addEventListener(MouseEvent.MOUSE_MOVE,startMouseTimer)
		}

		private function startMouseTimer(e:MouseEvent){
			_mouseIdle = false;
			dispatchEvent(new Event("MouseActiveEvent",true))
			_mouseTimer.reset();
			_mouseTimer.start();
			_mouseTimer.start();
			
		}

		private function mouseChecking(event:TimerEvent):void{
			_mouseIdle = true;
			dispatchEvent(new Event("MouseIdleEvent",true))

		}
	}
}