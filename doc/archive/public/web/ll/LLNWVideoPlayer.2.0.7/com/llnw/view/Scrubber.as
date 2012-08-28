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
	import flash.events.MouseEvent;
	import flash.geom.Rectangle;
	
	/**
	 * ...
	 * @author LLNW
	 */
	public class Scrubber extends MovieClip
	{
		private var _trackThumb_mc:MovieClip;
		private var _bufferBar_mc:MovieClip;
		private var _trackBG_mc:MovieClip;
		private var _trackProgress_mc:MovieClip;
		
		//init
		public function Scrubber() 
		{
	
			_trackThumb_mc = this.trackThumb_mc;
			_bufferBar_mc = this.bufferBar_mc;
			_trackBG_mc = this.trackBG_mc;
			_trackProgress_mc = this.trackProgress_mc;
			
			_trackThumb_mc.mouseEnabled = true;
			_trackThumb_mc.useHandCursor = true;
			_trackThumb_mc.addEventListener(MouseEvent.MOUSE_DOWN, thumbClicked)
			this.addEventListener(MouseEvent.MOUSE_DOWN, thumbClicked)
			
		}
		
		public function dimentions(w:int, h:int):void {
			_trackBG_mc.width = w;
			_trackBG_mc.height = h;
			_trackProgress_mc.width = w - 2;
			_trackProgress_mc.height = h - 2;
			_trackProgress_mc.y = 1;
			_trackProgress_mc.x = 1;
			_bufferBar_mc.width = w -2
			_bufferBar_mc.height = h -2
			_bufferBar_mc.y = 1;
			_bufferBar_mc.x = 1;
		}
		private var endModifier:int;
		private function thumbClicked(event:MouseEvent):void {
			endModifier = Math.floor(_trackBG_mc.width-9);
			
			var rect:Rectangle = new Rectangle(4, 4, endModifier, 0);
			
			_trackThumb_mc.startDrag(true, rect)
			dispatchEvent(new Event("scrubberClickEvent",true))
			stage.addEventListener(MouseEvent.MOUSE_UP,thumbReleased)
			
		}
		
		private function thumbReleased(event:MouseEvent):void {
			
			_trackThumb_mc.stopDrag()
			handleChange(event)
	
		}
		private var _scrubberLoc:int = 0;
		private var _total:int = 0;
		private function handleChange(event:MouseEvent = null):void {
			
			var scrubberModifier:int = _trackThumb_mc.width+2
			
			
			
			_trackProgress_mc.width = _trackThumb_mc.x;
			
			_scrubberLoc = _trackProgress_mc.width - (_trackThumb_mc.width / 2);
			_total = _trackBG_mc.width - 10;
			
			
			

			dispatchEvent(new Event("scrubberReleaseEvent", true));
			stage.removeEventListener(MouseEvent.MOUSE_UP, thumbReleased)
			
		}
		
		
		public function get scrubberPosition():int {
			return _scrubberLoc;
		}
		public function get total():int {
			return _total;
		}
		
		//fix this BS....
		public function setDisplay(x:int):void {
			var tWidth:int = _trackThumb_mc.width;
			
			if (x < tWidth) {
				x = tWidth;
			}else if(x > (_trackBG_mc.width-2)){
				x = x-(_trackThumb_mc.width/2)-1
			
			}
			_trackProgress_mc.width = x;
			_trackThumb_mc.x = x - 3
		
		}
		
		public function setBufferWidth(w:Number):void {
			_bufferBar_mc.width = w
			
		}
		public function set bufferVisible(value:Boolean):void {
			_bufferBar_mc.visible = value;
		}
		public function get bufferVisible():Boolean {
			return _bufferBar_mc.visible;
		}
		
	}
	
}