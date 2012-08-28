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
package org.openvideoplayer.components.ui.forms.view
{
	import org.openvideoplayer.components.ui.shared.event.ControlEvent;
	import org.openvideoplayer.components.ui.shared.utils.ShapeFactory;
	import org.openvideoplayer.components.ui.shared.view.Label;
	import org.openvideoplayer.components.ui.shared.view.LabelButton;
	import org.openvideoplayer.components.ui.forms.event.FormEvent;
	import org.openvideoplayer.components.ui.forms.event.FormEventVO;
	
	import flash.display.Shape;
	import flash.display.Sprite;
	import flash.events.MouseEvent;
	import flash.text.TextField;
	import flash.text.TextFieldType;
	import flash.text.TextFormat;
	import flash.text.TextFormatAlign;
	
	/**
	 * Basic single line input form component with a submit button
	 * 
	 * @author Akamai Technologies, Inc 2011
	 */	
	public class InputForm extends Sprite
	{
		private var _textfield:TextField;
		private var formWidth:uint;
		private var formHeight:uint;		
		private var background:Shape;
		private var button:LabelButton;
		private var padding:uint = 5;
		private var defaultText:String;	
		
		/**
		 * @Constructor
		 * @param formWidth
		 * @param formHeight
		 * @param defaultText
		 * 
		 */		
		public function InputForm(formWidth:uint, formHeight:uint, defaultText:String="")
		{
			this.formWidth = formWidth;
			this.formHeight = formHeight;
			this.defaultText = defaultText;
			createUI();				
		}
		
		/**
		 * Returns an instance of the textfield used in this form component
		 * 
		 * @return 
		 */		
		public function get textfield():TextField
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
		
		private function createUI():void
		{
			background 	= getBackground();
			_textfield 	= getTextfield();
			button 		= getButton();			
			addChild(background)
			addChild(_textfield);
			addChild(button);		
			alignChildren();
		}
		
		private function alignChildren():void
		{
			background.width = formWidth;
			background.height = formHeight;			
			textfield.width = formWidth - button.width - (padding*3);
			textfield.height = formHeight - (padding*2);
			textfield.x = padding;
			textfield.y = padding;			
			button.x = textfield.x+textfield.width+padding;
			button.y = textfield.y;
		}
		
		private function getTextfield():TextField
		{
			var textfield:TextField = new TextField();
			with(textfield)
			{
				defaultTextFormat = getDefaultTextFormat();
				background = true;
				selectable = true;
				multiline = false;			
				type = TextFieldType.INPUT;
				text = defaultText;	
			}
			return textfield;
		}
		
		private function getDefaultTextFormat():TextFormat
		{
			var format:TextFormat = new TextFormat();
			with(format)
			{
				color = 0x000000;
				font = "Arial";
				bold = true;
				size = 12;
				align = TextFormatAlign.LEFT;			
			}
			return format;
		}
		
		private function getBackground():Shape
		{
			return ShapeFactory.getRectShape(0xFFFFFF, .3);
		}
		
		private function getButton():LabelButton
		{
			var button:LabelButton = new LabelButton("Load", "submitButton",50, 20);
			button.addEventListener(MouseEvent.CLICK, onMouseClick)			
			return button
		}

		private function onMouseClick(event:MouseEvent):void
		{
			var vo:FormEventVO = new FormEventVO;
			vo.formValue = textfield.text;
			dispatchEvent(new FormEvent(FormEvent.BUTTON_CLICKED, vo));			
		}	
	}
}