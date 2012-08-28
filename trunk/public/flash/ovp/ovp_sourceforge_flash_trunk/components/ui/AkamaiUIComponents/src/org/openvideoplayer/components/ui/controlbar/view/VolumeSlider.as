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
	import org.openvideoplayer.components.ui.shared.utils.ShapeFactory;
	
	import flash.display.Sprite;
	import flash.events.Event;
	import flash.events.MouseEvent;
	import org.openvideoplayer.components.ui.shared.view.SliderView;
	
	/**
	 * @author Akamai Technologies, Inc 2011
	 */
	public class VolumeSlider extends SliderView
	{
		
		/**
		 * @Constructor
		 * 
		 * This component is a volume slider that reports mute state to model. When the slider value
		 * reaches zero it sets the mute state in the model to true. 
		 */		
		public function VolumeSlider()
		{
			super(ControlType.VOLUME_CONTROL, 50);
		}
		
		/**
		 * Pass in a current value and a max value of the volume range to 
		 * set the volume slider's thumb position.
		 *  
		 * @param currentTime
		 * @param totalTime
		 */		
		override public function setThumbPosition(currentValue:Number, maxValue:Number):void
		{
			super.setThumbPosition(currentValue, maxValue);
			controlBarPropertyModel.volumeLevel = currentValue;
		}
		
		/**
		 * @private
		 */		
		override protected function activate(event:Event):void
		{
			super.activate(event);
			controlBarPropertyModel.addEventListener(ControlBarPropertyChangeEvent.MUTE_CHANGE, onMuteChange);
			alignItems();
		}
		
		/**
		 * @private
		 */
		override protected function onTrackClick(event:MouseEvent):void
		{
			super.onTrackClick(event);
			isSliding = true;
			onMouseMoveHandler(null);
			isSliding = false;
			dispatchEvent(new ControlEvent(ControlEvent.VOLUME_CHANGE, {value:getThumbPosition(1)}));
		}
		
		/**
		 * @private
		 */
		override protected function onMouseMoveHandler(event:Event):void
		{
			super.onMouseMoveHandler(event);
			controlBarPropertyModel.volumeLevel = getThumbPosition(1);
			if(getThumbPosition(1) == 0)
			{
				turnMuteOn();
			}else
				turnOffMute()
			dispatchEvent(new ControlEvent(ControlEvent.VOLUME_CHANGE, {value:getThumbPosition(1)}));
		}
		
		private function onMuteChange(event:ControlBarPropertyChangeEvent):void
		{
			if (!isSliding )
			{
				setThumbPosition(isMuteOff() ? controlBarPropertyModel.savedVolume : 0, 1);
				dispatchEvent(new ControlEvent(ControlEvent.VOLUME_CHANGE, {value:getThumbPosition(1)}));
			}
		}
		

		private function turnMuteOn():void
		{
			controlBarPropertyModel.muteState = ControlEvent.MUTE_ON;
		}

		
		private function turnOffMute():void 
		{
			if (!isMuteOff())
			{
				controlBarPropertyModel.muteState = ControlEvent.MUTE_OFF;
			}
		}
		
		private function isMuteOff():Boolean 
		{
			return (controlBarPropertyModel.muteState == ControlEvent.MUTE_OFF);
		}
	}
}