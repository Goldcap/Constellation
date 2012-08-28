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
	import flash.display.Shape;
	import flash.display.Sprite;
	import flash.events.MouseEvent;
	import org.openvideoplayer.components.ui.shared.view.base.BaseButton;
	
	[ExcludeClass]
	
	/**
	 * @author Akamai Technologies, Inc 2011
	 */	
	public class ButtonView extends BaseButton
	{	
		protected var buttonWidth:int;
		protected var buttonHeight:int;
	
		private var bg:Shape;
		private var highlight:Shape;
		private var hover:Sprite;
		
		public function ButtonView(type:String, buttonWidth:uint = 30, buttonHeight:uint = 20)
		{
			super(type);
			this.buttonWidth = buttonWidth;
			this.buttonHeight = buttonHeight;
			addBackground()
			addHighlight();
			addHover();
		}
		
		override protected function onMouseOver(event:MouseEvent):void
		{
			hover.visible = true;
		}
		
		override protected function onMouseOut(event:MouseEvent):void
		{
			hover.visible = false;
		}
		
		private function addHover():void
		{
			hover = new Sprite();
			hover.addChild(getHighlighBox(buttonHeight));
			hover.visible = false;
			/*hover.alpha = 0;*/// when and if i tween i will use this plus the visible..
			addChild(hover);
		}

		private function addHighlight():void
		{
			highlight = getHighlighBox(buttonHeight/2);
			addChild(highlight);
		}
		
		private function addBackground():void 
		{
			bg = new Shape();
			bg.graphics.lineStyle(1, controlBarPropertyModel.controlStrokeColor, controlBarPropertyModel.controlStrokeAlpha, true);
			bg.graphics.beginFill(controlBarPropertyModel.controlBarBackgroundColor, controlBarPropertyModel.controlBarBackgroundAlpha);
			bg.graphics.drawRect(0, 0, buttonWidth, buttonHeight);
			bg.graphics.endFill();
			addChild(bg);	
		}
		
		private function getHighlighBox(height:Number):Shape
		{
			var s:Shape = new Shape();
			s.graphics.beginFill(controlBarPropertyModel.controlUpperHighlightColor, controlBarPropertyModel.controlUpperHighlightAlpha);
			s.graphics.drawRect(0, 0, buttonWidth, height);
			s.graphics.endFill();
			return s
		}
	}
}