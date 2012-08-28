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
	
	public class MuteButton extends MovieClip
	{
		public const MuteClick:String = "Mute_CLICK"
		public function MuteButton() 
		{
			
			this.addEventListener(MouseEvent.CLICK, clicked)
			this.addEventListener(MouseEvent.MOUSE_OVER, mouseOver)
			this.addEventListener(MouseEvent.MOUSE_OUT, mouseOut)
			this.mouseEnabled = true;
			this.useHandCursor = true;
			this.unMute_btn.visible = false;
		}
		
		private var _mute:Boolean;
		public function setState():void {
			//do toggle here.

			this.doMute_btn.visible = !_mute;
			this.unMute_btn.visible = _mute;
			
		}
		
		
		private function clicked(event:MouseEvent):void {
			_mute = this.doMute_btn.visible;
			setState()
			dispatchEvent(new Event("muteClicked",true))
			
		}
		
		
		public function get mute():Boolean {
			return _mute;
		}
		public function set mute(value:Boolean):void {
			_mute = value;
			setState();
		}
		private function mouseOver(event:MouseEvent):void {
			//handled by nested buttons
		}
		private function mouseOut(event:MouseEvent):void {
			//handled by nested buttons
		}
		
	}
	
}