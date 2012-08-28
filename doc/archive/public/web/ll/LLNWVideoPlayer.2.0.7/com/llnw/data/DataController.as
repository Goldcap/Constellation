package com.llnw.data 
{
	import com.llnw.utils.TimerUtil;
	import flash.display.MovieClip;
	import flash.events.*;

	/**
	 * ...
	 * @author LLNW
	 */
	public class DataController extends EventDispatcher
	{
		
		
		private var dataCollector:DataCollector;
		private var sendData:SendData;
		private var timerUtil:TimerUtil;
		
		public function DataController() 
		{
		
		}
		
		public function addData(name:String,value:String):void {
			
			dataCollector.dataQueue = name + "=" + value;
			
		}
		
		public function send():void {
			sendTheData();
			timerUtil.reset()
			timerUtil.start();
			
		}
		//optional value, will overwrite default path.
		public function set dataPath(value:String):void {
			sendData.defaultPath = value;
		}
		
		private function sendTheData():void {
			trace("--------------------------------------------------------")
			trace(dataCollector.dataQueue)
			trace("--------------------------------------------------------")
			sendData.shipData(dataCollector.dataQueue)
			
			dataCollector.clear();
		}
		
		
		private function timerHandler(event:Event):void {
		
			sendTheData();
		}
		
		public function start():void {
			dataCollector = new DataCollector();
			sendData = new SendData();
			timerUtil = new TimerUtil();
			timerUtil.delay = 10; //30 seconds
			timerUtil.addEventListener("timerEvent", timerHandler);
			timerUtil.start();
			addData("SystemData", firstRunCollection())
			sendTheData()
		}
		
		private function firstRunCollection():String {
			var systemData:SystemData = new SystemData();
			var sendString:String = systemData.userData;
			return sendString;
		}
		
	}
	
}