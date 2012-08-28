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
	import flash.events.MouseEvent;
	
	import org.openvideoplayer.components.ui.shared.ControlType;
	import org.openvideoplayer.components.ui.shared.event.ControlEvent;

	/**
	 * LabelButton extends ButtonView which holds the ui elements of all buttons. 
	 * The LabelButton class decorates ButtonView with a Label component. You can 
	 * either extend this class or add it to the display list
	 * 
	 * @author Akamai Technologies, Inc 2011
	 */
	public class LabelButton extends ButtonView
	{
		private var _textfield:Label
		
		/**
		 * @Constructor
		 * @param type  - The String that is registered in the BaseComponent class for identification. can be any value
		 * @param text
		 * @param buttonWidth
		 * @param buttonHeight
		 */		
		public function LabelButton(text:String, type:String = ControlType.LABEL_BUTTON, buttonWidth:uint=30, buttonHeight:uint=20)
		{
			super(type, buttonWidth, buttonHeight);
			addLabel(buttonWidth)
			if(text != null && text.length > 0 )
			{
				_textfield.text = text
			}
		}
		
		/**
		 * @return 
		 */		
		public function get textfield():Label
		{
			return _textfield;
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
			_textfield.text = value;
		}
		
		public function get text():String
		{
			return _textfield.text;
		}
		
		override protected function onMouseClick(event:MouseEvent):void
		{
			dispatchEvent(new ControlEvent(ControlEvent.LABEL_BUTTON_CLICK));
		}
		
		private function addLabel(width:uint):void
		{
			_textfield = new Label(width);
			_textfield.x = (this.width/2) - _textfield.width/2
			_textfield.y = (this.height/2) - _textfield.height/2
			_textfield.mouseEnabled = false;
			addChild(_textfield);
		}
	}
}