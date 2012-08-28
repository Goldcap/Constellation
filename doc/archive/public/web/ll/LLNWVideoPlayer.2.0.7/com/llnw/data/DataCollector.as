package com.llnw.data 
{
	
	/**
	 * ...
	 * @author 
	 */
	public class DataCollector
	{
		
		private var _dataQueue:String = "";
		public function DataCollector() 
		{
			collector = new Array();
		}
		
		//collector looks like collector(name,value)
		private var collector:Array;
		private function checkDuplicates(value:String) {
			var values:Array = value.split("=")
			trace("values: " + values)
			trace(values.length)
			if(values.length <= 2){
				var haveMatch:Boolean = false;
				for (var i:int = 0; i < collector.length; i++) {
					
					if (collector[i][0] == values[0]) {
						collector[i][1] += ";"+values[1]
						haveMatch = true;
					}
					trace(collector[i][0] + " : "+collector[i][1])
				}
				
				if (!haveMatch) {
					trace("&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&")
					collector.push([values[0], values[1]])
					haveMatch = false;
				}
				buildQueue()
			}else {
				
				_dataQueue = value;
			}
		}
		private function buildQueue():void {
			trace("collector.length = "+collector.length)
			for (var i:int = 0; i < collector.length; i++) {
				_dataQueue += collector[i][0] + "=" +collector[i][1]
				if (i < collector.length - 1) {
					_dataQueue += "&";
				}
			}
		}
		public function set dataQueue(value:String):void {
			checkDuplicates(value)
			//_dataQueue += value;
		}
		public function get dataQueue():String {
			return _dataQueue;
		}
		public function clear():void {
			collector = [];
			_dataQueue = "";
		}
		
	}
	
} 