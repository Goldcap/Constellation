package com.llnw.utils 
{
	import flash.display.MovieClip;
	import flash.events.Event;
	import flash.events.TimerEvent;
	import flash.utils.Timer;
	/**
	 * ...
	 * @author 
	 */
	public class TimerUtil extends MovieClip
	{
		
		
		private var _timer:Timer;
		private var _delay:int;
		public function TimerUtil() 
		{
			
		}
		//this sets the value for how long to wait before 
		//broadcasting another event 
		public function set delay(value:int):void {
			_delay = Number(value)*1000;
		}
		public function killTimer():void {
			
		}
		public function reset():void {
			_timer.reset();
		}
		
		private function fireTimerEvent(event:TimerEvent):void {
			trace("fireTimerEvent has been hit")
			dispatchEvent(new Event("timerEvent", true));
		}
		public function start() {
			_timer = new Timer(_delay)
			_timer.addEventListener(TimerEvent.TIMER,fireTimerEvent)
			_timer.start();
		}
		
	}
	
}