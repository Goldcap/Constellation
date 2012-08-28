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
package org.openvideoplayer.components.ui.spinner
{
	import org.openvideoplayer.components.ui.ComponentType;
	import org.openvideoplayer.components.ui.shared.ControlType;
	import org.openvideoplayer.components.ui.shared.utils.ShapeFactory;
	import org.openvideoplayer.components.ui.shared.view.base.BaseComponent;
	
	import flash.display.Shape;
	import org.openvideoplayer.components.ui.shared.view.Label;
	
	/**
	 * @author Akamai Technologies, Inc 2011
	 */	
	public class SpinnerView extends BaseComponent
	{
		
		//TODO open up API for user configuration. 
		private var size:uint = 100;
		private var padding:uint = size/20;
		private var background:Shape;
		
		private var _spinner:Spinner;
		private var _textfield:Label;
		
		/**
		 * @Constructor 
		 * This class is a composit of both the spinner a label component along 
		 * with a black 50 % alpha background with rounded corners
		 *  
		 *  
		 */		
		public function SpinnerView()
		{
			super(ComponentType.PRELOADING_SPINNER_WITH_LABEL_FIELD);
			addBackground();	
			addSpinner();
			addGenericLabelDisplay();
		}
		
		/**
		 * Returns the Label instance of SpinnerView which can 
		 * then be used to modify the text of the label field
		 * 
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
		
		/**
		 * Returns an instance of the sinner class which gives you access to the 
		 * spinners public API.
		 * 
		 * @return 
		 */		
		public function get spinner():Spinner
		{
			return _spinner;
		}
		
		/**
		 * Boolean value to toggle the spinners visiblity and will automatically start or stop the spinner base on visibility
		 * @param value
		 */		
		override public function set visible(value:Boolean):void
		{
			try {
            value ? _spinner.start() : _spinner.stop();
            } catch (err:Error) {}
			super.visible = value;
		}
		
		private function addBackground():void
		{
			background = ShapeFactory.getRoundedRectShape(0x000000, size, size, 20, .5);
			background.x =  -(background.width/2);
			background.y =  -(background.height/2);
			addChild(background);
		}
		
		private function addSpinner():void	
		{		
			_spinner = new Spinner();
			addChild(_spinner);
		}
		
		private function addGenericLabelDisplay():void
		{
			_textfield = new Label(size);
			_textfield.x = background.x;
			_textfield.y = background.y + background.height - _textfield.height - padding;
			addChild(_textfield);
		}
	}
}
