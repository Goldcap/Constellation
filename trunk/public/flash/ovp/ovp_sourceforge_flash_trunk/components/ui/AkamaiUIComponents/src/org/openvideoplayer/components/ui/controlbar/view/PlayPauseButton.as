//
// Copyright (c) 2009-2011, the Open Video Player authors. All rights reserved.
//
// Redistribution and use in source and binary forms, with or without 
// modification, are permitted provided that the following conditions are 
// met:
//
//    * Redistributions of source code must retain the above copyright 
//		notice, this list of conditions and the following disclaimer.
//    * Redistributions in binary form must reproduce the above 
//		copyright notice, this list of conditions and the following 
//		disclaimer in the documentation and/or other materials provided 
//		with the distribution.
//    * Neither the name of the openvideoplayer.org nor the names of its 
//		contributors may be used to endorse or promote products derived 
//		from this software without specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS 
// "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT 
// LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR 
// A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT 
// OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, 
// SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT 
// LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, 
// DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY 
// THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT 
// (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE 
// OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
//
package org.openvideoplayer.components.ui.controlbar.view
{
	import org.openvideoplayer.components.ui.shared.ControlType;
	import org.openvideoplayer.components.ui.shared.event.ControlEvent;
	import org.openvideoplayer.components.ui.controlbar.view.icons.PauseIcon;
	import org.openvideoplayer.components.ui.shared.view.icons.PlayIcon;
	
	import flash.display.Shape;
	import flash.events.MouseEvent;
	
	//import mx.events.ToolTipEvent;
	import org.openvideoplayer.components.ui.shared.view.ButtonView;

	/**
	 * @author Akamai Technologies, Inc 2011
	 */
	public class PlayPauseButton extends ButtonView
	{
	
		private var _currentState:String
		private var playIcon:PlayIcon;
		private var pauseIcon:PauseIcon;
			
		/**
		 * @Constructor 
		 */		
		public function PlayPauseButton()
		{
			super(ControlType.PLAY_PAUSE_BUTTON);
			addPlayIcon();
			addPauseIcon();
			currentState = ControlEvent.PAUSE;
		}
	
		/**
		 * Use this accessor to set the current state or get the current state 
		 * found in the ControlEvent class as String consts.
		 *   
		 * @return
		 * @param value 
		 */		
		public function get currentState():String
		{
			return (playIcon.visible) ? ControlEvent.PAUSE : ControlEvent.PLAY;
		}	
		
		public function set currentState(value:String):void
		{
			if(currentState != value)
			{
				this[value+"Icon"].visible =  true;
				toggleIcon();
			}
		}
		
		override protected function onMouseClick(event:MouseEvent):void
		{
			toggleIcon();
			dispatchEvent(new ControlEvent(currentState));
		}
		
		private function toggleIcon():void
		{	
			playIcon.visible = !playIcon.visible;
			pauseIcon.visible = !pauseIcon.visible;
		}
		
		private function addPlayIcon():void
		{
			playIcon = new PlayIcon(buttonHeight)
			centerIcon(playIcon)
			playIcon.visible = false;
			addChild(playIcon);
		}
		
		private function addPauseIcon():void
		{
			pauseIcon = new PauseIcon(buttonHeight)
			centerIcon(pauseIcon)
			pauseIcon.visible = false;
			addChild(pauseIcon);
		}
		
		private function centerIcon(icon:Shape):void
		{
			icon.x = (this.width/2);
			icon.y = (this.height/2);
		}
	}
}