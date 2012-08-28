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
	import org.openvideoplayer.components.ui.controlbar.utils.LayoutUtil;
	
	import flash.display.DisplayObject;
	import flash.events.Event;
	import flash.events.MouseEvent;
	import org.openvideoplayer.components.ui.shared.view.SliderView;
	
	/**
	 * @author Akamai Technologies, Inc 2011
	 */	
	public class ScrubBar extends SliderView
	{
	
		private var progressiveProgressBar:ProgressBarView;
		
		/**
		 * @Constructor 
		 * 
		 */		
		public function ScrubBar()
		{
			super(ControlType.SCRUB_BAR);
			controlBarPropertyModel.addEventListener(ControlBarPropertyChangeEvent.CONTROL_BAR_WIDTH_CHANGE_EVENT, onBarWidthChange);
			this.addEventListener(Event.REMOVED_FROM_STAGE, destroy);
		}
		
		/**
		 * This method is for use with progressive streams to show the 
		 * progress of the download of the video file in the scrub (seek) bar.
		 *  
		 * @param bytesLoaded
		 * @param bytesTotal
		 */		
		public function setProgressiveProgressBar(bytesLoaded:Number, bytesTotal:Number):void
		{
			progressiveProgressBar.width =  (bytesLoaded / bytesTotal)*(sliderTrackWidth-(innerBarXOffset*2));
		}

		override protected function activate(event:Event):void
		{
			super.activate(event);
			addProgressiveProgressBarView();
			onBarWidthChange();
		}
		
		override protected function onMouseDownHandler(event:MouseEvent):void
		{
			super.onMouseDownHandler(event);
			dispatchEvent(new ControlEvent(ControlEvent.SEEK_BEGIN));
		}
		
		override protected function onMouseUpHandler(event:MouseEvent):void
		{
			super.onMouseUpHandler(event);
			dispatchEvent(new ControlEvent(ControlEvent.SEEK_COMPLETE));
		}
		
		override protected function onTrackClick(event:MouseEvent):void
		{
			super.onTrackClick(event);
			onMouseMoveHandler(null);
			dispatchEvent(new ControlEvent(ControlEvent.SEEK_COMPLETE));
		}
		
		override protected function onMouseMoveHandler(event:Event):void
		{
			super.onMouseMoveHandler(event);
			dispatchEvent(new ControlEvent(ControlEvent.SEEK_CHANGE, {value:getThumbPosition(1)}));
		}
		
		private function onBarWidthChange(event:Event=null):void 
		{
			sliderTrackWidth = LayoutUtil.getSpaceAvailible(ControlType.SCRUB_BAR, controlBarPropertyModel);
			sliderTrack.width = sliderTrackWidth;
			alignItems();
			
			controlBarPropertyModel.scrubBarWidth = sliderTrackWidth;
			setThumbPosition(currentSliderValue, maxSliderValue);
		}
		
		private function addProgressiveProgressBarView():void
		{
			progressiveProgressBar = new ProgressiveProgressBarView(controlBarPropertyModel.progressBarColor);
			setInnerBarProperties(progressiveProgressBar);
		}
		
		private function destroy(event:Event):void
		{
			controlBarPropertyModel.removeEventListener(ControlBarPropertyChangeEvent.CONTROL_BAR_WIDTH_CHANGE_EVENT, onBarWidthChange);
			this.removeEventListener(Event.REMOVED_FROM_STAGE, destroy);
		}
	}
}