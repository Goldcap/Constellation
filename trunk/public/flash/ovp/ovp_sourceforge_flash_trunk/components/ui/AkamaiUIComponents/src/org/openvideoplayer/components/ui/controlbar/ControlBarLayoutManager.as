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
package org.openvideoplayer.components.ui.controlbar
{
	import org.openvideoplayer.components.ui.controlbar.event.ControlBarPropertyChangeEvent;
	import org.openvideoplayer.components.ui.controlbar.model.ControlBarPropertyModel;
	import org.openvideoplayer.components.ui.controlbar.utils.LayoutUtil;
	import org.openvideoplayer.components.ui.shared.view.base.BaseComponent;
	
	import flash.display.DisplayObject;
	import flash.display.Sprite;
	import flash.events.Event;

	[ExcludeClass]
	
	/**
	 * @author Akamai Technologies, Inc 2011
	 */	
	public class ControlBarLayoutManager extends Sprite
	{
		private var controlBar:ControlBar;
		private var controlBarPropertyModel:ControlBarPropertyModel;
		
		public function ControlBarLayoutManager(controlBar:ControlBar)
		{
			this.controlBar = controlBar;
			controlBarPropertyModel = ControlBarPropertyModel.getInstance();
			controlBarPropertyModel.addEventListener(ControlBarPropertyChangeEvent.SCURB_BAR_WIDTH_CHANGE_EVENT, onBarChange);
			this.addEventListener(Event.REMOVED_FROM_STAGE, destroy);
		}
		
		/**
		 * Called by ControlBar.as only 
		 * @private
		 */		
		public function layoutControls():void
		{
			for each (var control:BaseComponent in controlBarPropertyModel.controlList)
			{
				addControlToControlBar(control);
			}
			alignControls();
		}
		
		private function onBarChange(event:ControlBarPropertyChangeEvent):void
		{
			this.addEventListener(Event.ENTER_FRAME, updateOnNextFrame);
		}

		private function updateOnNextFrame(event:Event):void
		{
			this.removeEventListener(Event.ENTER_FRAME, updateOnNextFrame);
			alignControls();
		}
		
		private function destroy(event:Event):void
		{
			this.removeEventListener(Event.REMOVED_FROM_STAGE, destroy);
		}
		
		private function addControlToControlBar(control:BaseComponent):void
		{
			var child:DisplayObject = DisplayObject(control);
			controlBar.addChild(child);
		}
	
		private function alignControls(event:ControlBarPropertyChangeEvent = null):void
		{
			for(var i:uint = 0 ;  i < controlBar.numChildren-1 ; i++)
			{				
				var control:BaseComponent = controlBarPropertyModel.controlList[i];
				control.x = getNextXpos(i);	
				control.y = LayoutUtil.calculateVerticleCenter(controlBarPropertyModel.controlBarHeight, control.height);
			}
		}
		
		private function getNextXpos(index:int):Number
		{
			if (index > 0)
			{
				var lastChildAdded:Sprite = controlBar.getChildAt(index) as Sprite;
			}
			return (lastChildAdded) ? (lastChildAdded.x + lastChildAdded.width) + controlBarPropertyModel.controlXposPadding 
									: controlBarPropertyModel.controlXposPadding;
		}
	}
}