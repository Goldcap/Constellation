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
package org.openvideoplayer.components.ui.shared.view
{
	
	import org.openvideoplayer.components.ui.controlbar.utils.LayoutUtil;
	import org.openvideoplayer.components.ui.shared.utils.ShapeFactory;
	
	import flash.display.DisplayObject;
	import flash.display.Sprite;
	import flash.events.Event;
	import flash.events.MouseEvent;
	import flash.geom.Rectangle;
	import org.openvideoplayer.components.ui.shared.view.base.BaseComponent;
	import org.openvideoplayer.components.ui.controlbar.view.SliderThumbButton;
	import org.openvideoplayer.components.ui.controlbar.view.SliderThumbProgressBarView;
	
	[ExcludeClass]
	
	/**
	 * @author Akamai Technologies, Inc 2011
	 */	
	public class SliderView extends BaseComponent
	{
		protected var innerBarXOffset:Number = 1;
		private var sliderTrackHeight:Number = 12;
		private var scrubableAreaRectangle:Rectangle;
		private var itemsToAlignList:Vector.<DisplayObject> = new Vector.<DisplayObject>;
		
		protected var sliderTrackWidth:Number;
		protected var sliderThumb:SliderThumbButton;
		protected var sliderTrack:Sprite = new Sprite();
		protected var sliderThumbProgressBar:SliderThumbProgressBarView;
		
		protected var currentSliderValue:Number = 0;
		protected var maxSliderValue:Number = 1;	
		protected var isSliding:Boolean;
		
		
		/**
		 * Constructor 
		 * @param type
		 * @param sliderTrackWidth
		 * 
		 */		
		public function SliderView(type:String, sliderTrackWidth:int=1)
		{
			super(type);
			this.sliderTrackWidth = sliderTrackWidth;
			this.addEventListener(Event.ADDED_TO_STAGE, activate);
			addSliderTrack();
			addSliderThumbTrailBar();
			addSliderThumb();
		}
		
		/**
		 * 
		 * @param currentTime
		 * @param totalTime
		 * 
		 */		
		public function setThumbPosition(currentValue:Number, maxValue:Number):void
		{			
			currentSliderValue = currentValue;
			maxSliderValue = maxValue;			
			var sliderPosition:Number = (currentValue / maxValue) * (sliderTrackWidth-(sliderThumb.width-1));
			if(!isNaN(sliderPosition) && sliderPosition != Infinity)
			{				
				sliderThumb.x = sliderPosition;
				setSliderThumbTrailBarWidth();
			}
		}
		
		/**
		 * Returns the scrub bar's thumb position relative to the total time passed in  
		 * @param totalTime
		 * @return 
		 * 
		 */		
		public function getThumbPosition(totalTime:Number):Number
		{
			return (sliderThumb.x) * totalTime / (sliderTrackWidth-(sliderThumb.width-1));
		}
		
		protected function activate(event:Event):void
		{
			stage.addEventListener(MouseEvent.MOUSE_UP, onStageMouseUp);
			this.removeEventListener(Event.ADDED_TO_STAGE, activate);
		}
		
		protected function onTrackClick(event:MouseEvent):void
		{
			sliderThumb.x = getValidMouseX();			
			this.currentSliderValue = getThumbPosition(maxSliderValue);
			setSliderThumbTrailBarWidth(); 
		}
		
		protected function onMouseDownHandler(event:MouseEvent):void
		{
			isSliding = true;
			this.addEventListener(Event.ENTER_FRAME, onMouseMoveHandler);
			sliderThumb.startDrag(false, scrubableAreaRectangle);
		}
		
		protected function onMouseUpHandler(event:MouseEvent):void
		{
			sliderThumb.stopDrag();
			this.removeEventListener(Event.ENTER_FRAME, onMouseMoveHandler);
			
			//entry
			this.currentSliderValue = getThumbPosition(maxSliderValue);
			
			
			isSliding = false;
		}

		protected function onMouseMoveHandler(event:Event):void
		{
			setSliderThumbTrailBarWidth();
		
		}
		
		protected function onStageMouseUp(event:MouseEvent):void
		{
			if(isSliding)
			{
				onMouseUpHandler(event);
			}
		}
		
		protected function setInnerBarProperties(displayObject:DisplayObject):void
		{
			displayObject.height = sliderTrackHeight-(innerBarXOffset*2);
			displayObject.x = innerBarXOffset;			
			itemsToAlignList.push(displayObject);
			addChildAt(displayObject, 1);
		}
		
		protected function alignItems():void
		{
			for each(var item:DisplayObject in itemsToAlignList)
			{
				item.y = LayoutUtil.calculateVerticleCenter(this.height, item.height);
			}
			scrubableAreaRectangle  = new Rectangle(0, 0, this.width-sliderThumb.width+1, 0);
		}
		
		private function addSliderThumbTrailBar():void
		{
			sliderThumbProgressBar = new SliderThumbProgressBarView(controlBarPropertyModel.progressBarColor);
			setInnerBarProperties(sliderThumbProgressBar);
		}
		
		private function addSliderTrack():void
		{
			sliderTrack.addChild(ShapeFactory.getRectShape(controlBarPropertyModel.sliderTrackBackgroundColor, 1));
			sliderTrack.addEventListener(MouseEvent.CLICK, onTrackClick);
			sliderTrack.buttonMode = true;
			sliderTrack.height = sliderTrackHeight;
			sliderTrack.width = sliderTrackWidth;
			itemsToAlignList.push(sliderTrack);
			addChild(sliderTrack);
		}
		
		private function addSliderThumb():void
		{
			sliderThumb = new SliderThumbButton();
			sliderThumb.addEventListener(MouseEvent.MOUSE_DOWN, onMouseDownHandler);
			sliderThumb.addEventListener(MouseEvent.MOUSE_UP, onMouseUpHandler);
			itemsToAlignList.push(sliderThumb);
			addChild(sliderThumb);
		}
		
		protected function setSliderThumbTrailBarWidth(event:Event = null):void
		{
			sliderThumbProgressBar.width = (sliderThumb.x >= 0) ? sliderThumb.x : 0;
		}
		
		protected function getValidMouseX():Number 
		{
			var endOfTrack:Number = sliderTrack.width-sliderThumb.width;
			return (mouseX > endOfTrack) ? endOfTrack+1 : mouseX;
		}
	}
}