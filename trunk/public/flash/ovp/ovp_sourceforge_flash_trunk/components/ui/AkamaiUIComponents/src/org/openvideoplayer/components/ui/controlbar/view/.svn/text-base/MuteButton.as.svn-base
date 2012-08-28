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
	import org.openvideoplayer.components.ui.controlbar.event.ControlBarPropertyChangeEvent;
	import org.openvideoplayer.components.ui.controlbar.view.icons.MuteOffIcon;
	import org.openvideoplayer.components.ui.controlbar.view.icons.MuteOnIcon;
	
	import flash.events.MouseEvent;
	import org.openvideoplayer.components.ui.shared.view.base.BaseButton;

	/**
	 * @author Akamai Technologies, Inc 2011
	 */
	public class MuteButton extends BaseButton
	{
		
		private var muteOffIcon:MuteOffIcon;
		private var muteOnIcon:MuteOnIcon;
		
		/**
		 * @Constructor 
		 */		
		public function MuteButton()
		{
			super(ControlType.MUTE_BUTTON_NO_VOLUME_SLIDER);
			addIcons();
			controlBarPropertyModel.addEventListener(ControlBarPropertyChangeEvent.MUTE_CHANGE, onMuteChange);
		}
		
		/**
		 * Use this accessor to set the current state or get the current state 
		 * found in the ControlEvent class as String consts.
		 *   
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
			return (muteOffIcon.visible) ? ControlEvent.MUTE_OFF : ControlEvent.MUTE_ON;
		}

		private function addIcons():void
		{
			this.scaleX = 
			this.scaleY = .8;
			addMuteOnIcon();
			addMuteOffIcon();
			addHotSpot(this.width, this.height)
		}
		
		override protected function onMouseClick(event:MouseEvent):void
		{
			toggleIcon();
			controlBarPropertyModel.muteState = currentState;
		}

		private function addMuteOffIcon():void
		{
			muteOffIcon = new MuteOffIcon();
			addChild(muteOffIcon);
		}

		private function addMuteOnIcon():void
		{
			muteOnIcon = new MuteOnIcon();
			muteOnIcon.visible = false;
			addChild(muteOnIcon);
		}
		
		private function toggleIcon():void
		{
			muteOffIcon.visible = !muteOffIcon.visible;
			muteOnIcon.visible = !muteOnIcon.visible;
		}
		
		private function onMuteChange(event:ControlBarPropertyChangeEvent):void 
		{
			if(muteOnIcon.visible && controlBarPropertyModel.muteState == ControlEvent.MUTE_OFF)
			{
				currentState = ControlEvent.MUTE_ON;
			}
			else if (muteOffIcon.visible && controlBarPropertyModel.muteState == ControlEvent.MUTE_ON)
			{
				currentState = ControlEvent.MUTE_OFF;
			}
		}
	}
}