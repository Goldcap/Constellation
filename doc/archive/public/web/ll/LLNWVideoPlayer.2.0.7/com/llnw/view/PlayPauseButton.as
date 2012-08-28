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
	
	/**
	 * ...
	 * @author LLNW
	 */
	
	public class PlayPauseButton extends MovieClip
	{
		public const PlayPauseClick:String = "PLAYPAUSE_CLICK"
		public function PlayPauseButton() 
		{
			
			this.addEventListener(MouseEvent.CLICK, clicked)
			this.addEventListener(MouseEvent.MOUSE_OVER, mouseOver)
			this.addEventListener(MouseEvent.MOUSE_OUT, mouseOut)
			this.mouseEnabled = true;
			this.useHandCursor = true;
			this.play_mc.visible = false;
		}
		
		private var _paused:Boolean;
		public function setState():void {
			//do toggle here.
			this.pause_mc.visible = !this.pause_mc.visible;
			this.play_mc.visible = !this.play_mc.visible;
			_paused = !this.play_mc.visible;
		}
		
		public function get paused():Boolean {
			return _paused;
		}
		
		private function clicked(event:MouseEvent):void {
			
			setState()
			dispatchEvent(new Event("playPauseClicked",true))
			
		}
		
		private function mouseOver(event:MouseEvent):void {
			//handled by nested buttons
		}
		private function mouseOut(event:MouseEvent):void {
			//handled by nested buttons
		}
		
	}
	
}