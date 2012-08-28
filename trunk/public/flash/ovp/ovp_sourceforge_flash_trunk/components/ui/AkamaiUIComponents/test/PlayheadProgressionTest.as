package com.akamai.uicomponents.controlbar.test
{
	import com.akamai.uicomponents.controlbar.view.ScrubBar;
	
	import flash.events.TimerEvent;
	import flash.utils.Timer;

	public class PlayheadProgressionTest
	{
		private var scrubBar:ScrubBar;
		private var timer:Timer
		private var currentTimeInSecond:Number = 0;
		private var totalTimeInSeconds:Number = 500;
		
		public function PlayheadProgressionTest(scrubBar:ScrubBar)
		{
			this.scrubBar = scrubBar;
			timer = new Timer(1000);
			timer.addEventListener(TimerEvent.TIMER, onTick);
			timer.start();
		}
		
		private function onTick(event:TimerEvent):void 
		{
			currentTimeInSecond += 100;
			scrubBar.setThumbPosition(currentTimeInSecond, totalTimeInSeconds);
			if(currentTimeInSecond >= totalTimeInSeconds)
			{
				timer.stop();
			}
		}
	}
}