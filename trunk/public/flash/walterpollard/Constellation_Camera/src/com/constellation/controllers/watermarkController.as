package com.constellation.controllers
{
	import com.constellation.externalConfig.ExternalConfig;
	
	import com.constellation.events.constellationEvent;
	
	import flash.events.Event;
	import flash.events.EventDispatcher;

	public class watermarkController extends EventDispatcher
	{
		private var _classname:String ="com.constellation.controlers.watermarkController";
		/**
		*Array for the time in seconds the watermark should appear<br>
		*Passed in
		**/
		private var _timeArray:Array;
		/**
		 * How long to keep the watermark showing 
		 * Can be adjusted in flashvar watermarkDuration
		 */ 
		private var _watermarkDuration:int;
		public function watermarkController()
		{
			this.onInit();
		}
		public function checkWatermarkTime(curTime:int):void{
			var timeArrayLen:int = this._timeArray.length;
			var displayWatermark:String;
			
			for(var i:int = 0;i<timeArrayLen;i++){
				if(curTime==timeArrayLen){
					dispatchEvent(new constellationEvent(constellationEvent.SHOW_WATERMARK,displayWatermark));
				}
			}
		}
		public function set timeArray(timeIntervalArray:Array):void{
			this._timeArray = timeIntervalArray;
		}
		protected function onInit(event:Event):void
		{
			this._watermarkDuration = ExternalConfig.getInstance().watermarkDuration
			
		}
	}
}