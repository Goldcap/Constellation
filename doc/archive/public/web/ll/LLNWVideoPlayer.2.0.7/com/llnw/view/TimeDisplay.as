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
package com.llnw.view 
{
	import flash.display.MovieClip;
	import flash.events.Event;
	import flash.text.TextFieldAutoSize;
	import flash.text.TextFieldType;
	/**
	 * ...
	 * @author LLNW
	 */
	public class TimeDisplay extends MovieClip
	{
		

		private var _size:int = 0;
		public function TimeDisplay() 
		{
		



			currentTime_txt.x = 2
			dilimeter_mc.alpha = 0;

			currentTime_txt.autoSize = TextFieldAutoSize.LEFT;
			totalTime_txt.autoSize = TextFieldAutoSize.LEFT;
			_size = this.width;
			
			doResize()
			totalTime_txt.type = TextFieldType.INPUT;
			totalTime_txt.addEventListener(Event.CHANGE, checkSize)
			
		
		}


		private function checkSize(event:Event):void {
		

			doResize()
		}
		
		private var _oldSize:int;
		private function doResize():void {
			dilimeter_mc.x = currentTime_txt.x + currentTime_txt.width + 2;
			totalTime_txt.x = dilimeter_mc.x + 3;
			timebg_mc.width = this.width;
			_size = this.width;
			if (_totalTime != "") {
			
				dilimeter_mc.alpha = 1;
			}
			if(_oldSize != _size){
				
				dispatchEvent(new Event("timeResized", true));
				_oldSize = _size;
			}
			
		}
		
		public function get size():int {
			return _size;
		}
		
		
		private var _totalTime:String = "";
		private var _currentTime:String = "";

		
		public function set currentTime(value:String):void {
			 _currentTime = value;
			 currentTime_txt.text = _currentTime;
		}
		public function set totalTime(value:String):void {
			_totalTime = value;
			totalTime_txt.text = _totalTime;
			doResize()
			
		}
		
		
	}
	
}