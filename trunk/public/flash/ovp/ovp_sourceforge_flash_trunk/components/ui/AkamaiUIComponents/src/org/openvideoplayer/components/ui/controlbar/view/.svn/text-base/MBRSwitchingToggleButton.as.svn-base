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
	
	import flash.events.MouseEvent;
	import org.openvideoplayer.components.ui.shared.view.LabelButton;

	[ExcludeClass]
	
	/**
	 * @author Akamai Technologies, Inc 2011
	 */
	public class MBRSwitchingToggleButton extends LabelButton
	{
		
		protected const MANUAL:String = "Manual MBR";
		protected const AUTO:String = "Auto MBR";
		private var _currentState:String;
		
		/**
		 * @Constructor 
		 * @param buttonWidth
		 * @param buttonHeight
		 */		
		public function MBRSwitchingToggleButton(buttonWidth:uint=70, buttonHeight:uint=20)
		{
			super(AUTO, ControlType.MBR_SWITCH_TOGGLE_BUTTON, buttonWidth, buttonHeight);
		}
		
		/**
		 * @param value
		 * @return 
		 */		
		public function set currentState(value:String):void
		{
			if(value == ControlEvent.AUTO_MBR || value == ControlEvent.MANUAL_MBR)
			{
				_currentState = value;
				toggleState();
			}
			else
			{
				throw new Error(value+" is not a state of this button either use "+ControlEvent.AUTO_MBR+" or "+ ControlEvent.MANUAL_MBR+" as the state string");
			}
		}
		public function get currentState():String
		{
			return (textfield.text == MANUAL) ? ControlEvent.MANUAL_MBR : ControlEvent.AUTO_MBR;
		}
		
		override protected function onMouseClick(event:MouseEvent):void
		{			
			currentState = (_currentState == ControlEvent.AUTO_MBR) ?  ControlEvent.MANUAL_MBR : ControlEvent.AUTO_MBR;			
			dispatchEvent(new ControlEvent(currentState));
		}
		
		private function toggleState():void
		{			
			textfield.text = (_currentState == ControlEvent.AUTO_MBR) ? MANUAL : AUTO;  
		}
		
	}
}