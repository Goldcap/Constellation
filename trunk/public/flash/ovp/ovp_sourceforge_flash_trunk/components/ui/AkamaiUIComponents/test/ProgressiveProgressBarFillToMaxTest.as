package com.akamai.uicomponents.controlbar.test
{
	import com.akamai.uicomponents.controlbar.view.ScrubBar;
	
	import flash.events.TimerEvent;
	import flash.utils.Timer;

	public class ProgressiveProgressBarFillToMaxTest
	{
		private var scrubBar:ScrubBar;
		
		private var timer:Timer
		
		private var bytesLoad:Number = 0;
		private var bytesTotal:Number = 14000;
		
		public function ProgressiveProgressBarFillToMaxTest(scrubBar:ScrubBar)
		{
			this.scrubBar = scrubBar
			timer = new Timer(100);
			timer.addEventListener(TimerEvent.TIMER, onTick)
			timer.start();
				
		}
		
		
		private function onTick(event:TimerEvent):void 
		{
			bytesLoad += 500
			trace(bytesLoad)
			scrubBar.setProgressiveProgressBar(bytesLoad, bytesTotal);
			if(bytesLoad >= bytesTotal)
			{
				timer.stop();
			}
		}
		
				
		
	}
}