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
	
	public class FullScreenButton extends MovieClip
	{
		public const FullScreenClick:String = "FULLSCREEN_CLICK"
		public function FullScreenButton() 
		{
			this.gotoAndStop(1)
			this.addEventListener(MouseEvent.MOUSE_UP, clicked)
			this.addEventListener(MouseEvent.MOUSE_OVER, mouseOver)
			this.addEventListener(MouseEvent.MOUSE_OUT, mouseOut)
			this.mouseEnabled = true;
			this.useHandCursor = true;
		}
	
		private function clicked(event:MouseEvent):void {
			dispatchEvent(new Event("fullScreenClickEvent",true))
		}
		
		private function mouseOver(event:MouseEvent):void {
			this.gotoAndStop(2)
		}
		private function mouseOut(event:MouseEvent):void {
			this.gotoAndStop(1)
		}
		
	}
	
}