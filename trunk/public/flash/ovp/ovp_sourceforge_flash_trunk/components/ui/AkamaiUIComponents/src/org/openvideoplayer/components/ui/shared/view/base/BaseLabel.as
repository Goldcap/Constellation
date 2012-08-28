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
package org.openvideoplayer.components.ui.shared.view.base
{
	import flash.text.AntiAliasType;
	import flash.text.GridFitType;
	import flash.text.TextField;
	import flash.text.TextFormat;
	import flash.text.TextFormatAlign;

	[ExcludeClass]
	
	/**
	 * @author Akamai Technologies, Inc 2011
	 */
	public class BaseLabel extends BaseComponent 
	{
		protected var defaultTextFormat:TextFormat = new TextFormat();
		protected var _textfield:TextField =  new TextField();
		
		private var defaultWidth:int
		private var defaultHeight:int
		/**
		 * @Constructor 
		 * @param type
		 * @param defaultWidth
		 */		
		public function BaseLabel(type:String, defaultWidth:int = 20)
		{
			super(type);
			this.defaultWidth  = defaultWidth;
			this.defaultHeight = defaultHeight;
			addTextField();
		}
		
		/**
		 * Returns an instance of a the textfield that this label uses.
		 * @return 
		 */		
		public function get textfield():TextField
		{
			return _textfield
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
		
		private function addTextField():void 
		{
			with (textfield)
			{
				defaultTextFormat = getDefaultTextFormat();
				multiline = false;
				height = 15;
				width = defaultWidth;
				selectable = false;
				mouseEnabled = false;
				antiAliasType=AntiAliasType.ADVANCED;
				gridFitType = GridFitType.PIXEL;
			}
			addChild(textfield);
		}
		
		private function getDefaultTextFormat():TextFormat
		{
			defaultTextFormat.color = 0xFFFFFF;
			defaultTextFormat.font = "Arial";
			defaultTextFormat.bold = true;
			defaultTextFormat.size = 10;
			defaultTextFormat.align = TextFormatAlign.CENTER;			
			return defaultTextFormat;
		}
	}
}