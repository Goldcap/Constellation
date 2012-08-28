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
	
	public class RestartButton extends MovieClip
	{
		public const RestartClick:String = "RESTART_CLICK"
		public function RestartButton() 
		{
			this.gotoAndStop(1)
			this.addEventListener(MouseEvent.CLICK, clicked)
			this.addEventListener(MouseEvent.MOUSE_OVER, mouseOver)
			this.addEventListener(MouseEvent.MOUSE_OUT, mouseOut)
			this.mouseEnabled = true;
			this.useHandCursor = true;
		}
		/*
		public function set viewState():void {
			//nada here.
		}
		*/
		private function clicked(event:MouseEvent):void {
			// viewState();
			dispatchEvent(new Event("restartClicked",true))
			
		}
		
		private function mouseOver(event:MouseEvent):void {
			this.gotoAndStop(2)
		}
		private function mouseOut(event:MouseEvent):void {
			this.gotoAndStop(1)
		}
		
	}
	
}