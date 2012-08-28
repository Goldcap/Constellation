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
	import org.openvideoplayer.components.ui.controlbar.event.ControlBarPropertyChangeEvent;
	import org.openvideoplayer.components.ui.controlbar.model.ControlBarPropertyModel;
	
	import flash.display.Shape;
	import flash.display.Sprite;
	import flash.events.Event;
	import org.openvideoplayer.components.ui.shared.view.base.BaseComponent;
	
	[ExcludeClass]
	
	/**
	 * @author Akamai Technologies, Inc 2011
	 */	
	public class ControlBarBackgroundView extends BaseComponent
	{
		private var bg:Shape;
		private var highlight:Shape;

		public function ControlBarBackgroundView()
		{
			super("conrolBarBackground");
			controlBarPropertyModel.addEventListener(ControlBarPropertyChangeEvent.CONTROL_BAR_WIDTH_CHANGE_EVENT, onBarWidthChange)
			createUI();
		}
		
		private function createUI():void
		{
			bg = new Shape();
			bg.graphics.beginFill(controlBarPropertyModel.controlBarBackgroundColor, controlBarPropertyModel.controlBarBackgroundAlpha);
			bg.graphics.drawRect(0, 0, controlBarPropertyModel.controlBarWidth, controlBarPropertyModel.controlBarHeight);
			bg.graphics.endFill();
			addChild(bg);
			
			highlight = new Shape();
			highlight.graphics.beginFill(controlBarPropertyModel.controlUpperHighlightColor, controlBarPropertyModel.controlUpperHighlightAlpha);
			highlight.graphics.drawRect(0, 0, controlBarPropertyModel.controlBarWidth, controlBarPropertyModel.controlBarHeight/2);
			highlight.graphics.endFill();
			addChild(highlight);
		}
		
		private function onBarWidthChange(event:Event):void 
		{
			this.width = controlBarPropertyModel.controlBarWidth;
			this.height = controlBarPropertyModel.controlBarHeight;
		}
	}
}