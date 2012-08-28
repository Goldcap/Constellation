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
	import org.openvideoplayer.components.ui.controlbar.view.icons.BaseFullScreenButtonIcon;
	import org.openvideoplayer.components.ui.controlbar.view.icons.GoFullScreenIcon;
	import org.openvideoplayer.components.ui.controlbar.view.icons.GoNormalScreenIcon;
	
	import flash.events.MouseEvent;
	import org.openvideoplayer.components.ui.shared.view.ButtonView;

	/**
	 * @author Akamai Technologies, Inc 2011
	 */
	public class FullscreenButton extends ButtonView 
	{
		private var _enabled:Boolean;
		private var fullscreenIcon:GoFullScreenIcon;
		private var normalscreenIcon:GoNormalScreenIcon;
		
		/**
		 * @Constructor 
		 */		
		public function FullscreenButton()
		{
			super(ControlType.FULLSCREEN_BUTTON);
			addGoFullscreenIcon();
			addGoNormalScreenIcon();
			currentState = ControlEvent.FULLSCREEN;
		}
		
		/**
		 * @param value
		 * @return 
		 */		
		public function set currentState(value:String):void
		{
			this[value+"Icon"].visible =  true;
			toggleIcon();
		}
		
		public function get currentState():String
		{
			return (fullscreenIcon.visible) ? ControlEvent.NORMALSCREEN : ControlEvent.FULLSCREEN;
		}

		override protected function onMouseClick(event:MouseEvent):void
		{
			toggleIcon();
			dispatchEvent(new ControlEvent(currentState));
		}
		
		private function toggleIcon():void
		{
			fullscreenIcon.visible = !fullscreenIcon.visible;
			normalscreenIcon.visible = !normalscreenIcon.visible;
		}
		
		private function addGoFullscreenIcon():void 
		{	
			fullscreenIcon = new GoFullScreenIcon();
			centerIcon(fullscreenIcon);
			fullscreenIcon.visible = false;
			addChild(fullscreenIcon);
		}
		
		private function addGoNormalScreenIcon():void
		{
			normalscreenIcon = new GoNormalScreenIcon();
			centerIcon(normalscreenIcon);
			normalscreenIcon.visible = false;
			addChild(normalscreenIcon);
		}
		
		private function centerIcon(icon:BaseFullScreenButtonIcon):void
		{
			icon.x = (this.width/2) - (icon.width/2);
			icon.y = (this.height/2) - (icon.height/2);
		}
	}
}