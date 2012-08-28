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
package org.openvideoplayer.components.ui.captioning
{
	import org.openvideoplayer.components.ui.ComponentType;
	import org.openvideoplayer.components.ui.shared.utils.ShapeFactory;
	import org.openvideoplayer.components.ui.shared.view.base.BaseComponent;
	import org.openvideoplayer.components.ui.shared.view.Label;
	
	import flash.display.Shape;
	
	/**
	 * 	This UI view class is great as a simple text rendeing component to overlay 
	 *  on a photo or video.  An example is a closed captioning window. 
	 * 
	 *  @author Akamai Technologies, Inc 2011
	 */	
	public class CaptioningView extends BaseComponent
	{
		private const textFieldPadding:uint = 10;
		
		private var background:Shape;
		private var _label:Label;
		private var windowWidth:Number;
		private var windowHeight:Number;
		
		/**
		 * @Constructor 
		 * @param width  The width of the caption window
		 * @param height The height of the caption window
		 */		
		public function CaptioningView(width:Number, height:Number)
		{
			super(ComponentType.CAPTIONING_VIEW);
			windowWidth = width;
			windowHeight = height;
			addBackground();
			addGenericLabelDisplay();
		}
		
		/**
		 * Returns an instance of the Label used in this component
		 * 
		 * @return 
		 */		
		public function get label():Label
		{
			return _label;
		}
		
		/**
		 * Takes a string value and sets it to the textfield in this component.
		 * Returns a string of the current text set to the textfield 
		 *  
		 * @return 
		 * @value
		 */		
		public function set text(value:String):void
		{
			_label.text = value;
		}
		
		public function get text():String
		{
			return _label.text;
		}
		
		private function addBackground():void
		{
			background 	 = ShapeFactory.getRectShape(0x000000, .2);
			background.width = windowWidth;
			background.height = windowHeight;
			addChild(background);
		}
		
		private function addGenericLabelDisplay():void
		{
			_label = new Label(windowWidth - (textFieldPadding*2) );
			_label.x = textFieldPadding;
			centerTextField();
			addChild(_label);
		}

		private function centerTextField():void
		{
			_label.y = background.height/2 - _label.height/2;
		}
		
	}
}