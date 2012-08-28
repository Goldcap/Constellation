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
package view{
	import flash.display.*;
	import flash.text.*;
	import flash.events.*;
	import flash.geom.*;
	import model.Model;
	import controller.*;
	import ui.*;
	/**
	 * Akamai Multi Player - generates the volume control as a volume icon accompanied by 10 clickable level boxes. 
	 */
	public class HDMeter2View extends MovieClip {
		private var _model:Model;
		private var _logo:HDlogo;
		private var _activeTransform:ColorTransform;
		private var _availableTransform:ColorTransform;
		private var _notAvailableTransform:ColorTransform;
		
		private var _currentField:TextField;
		
		public function HDMeter2View(model:Model):void {
			_model = model;
			_model.addEventListener(_model.EVENT_PROGRESS, update);
			this.buttonMode = true;
			this.useHandCursor = true;
			this.addEventListener(MouseEvent.CLICK, handleClick);
			createChildren();
		}
		private function createChildren():void {
			this.graphics.beginFill(0x000000);
			this.graphics.drawRoundRect(0,0,95,25,5);
			this.graphics.endFill();
			_logo = new HDlogo();
			_logo.x=4;
			_logo.y = 4;
			addChild(_logo);
			
			_currentField = new TextField();
			_currentField.embedFonts = true;
			_currentField.mouseEnabled = false;
			_currentField.selectable = false;
			_currentField.wordWrap = false;
			_currentField.selectable = false;
			_currentField.antiAliasType = flash.text.AntiAliasType.ADVANCED;
			_currentField.width = 70;
			var textFormat:TextFormat = new TextFormat();
			textFormat.font = new AkamaiLCD().fontName;
			textFormat.size = 10;
			textFormat.align = TextFormatAlign.RIGHT;
			textFormat.color = _model.themeColor;// 0xcccccc;
			_currentField.defaultTextFormat = textFormat;
			_currentField.x = 19;
			_currentField.y = 6;
			_currentField.text = "Waiting";
			addChild(_currentField);
		}
		private function handleClick(e:MouseEvent):void {
			_model.toggleDebugPanel();
		}
		
			
		private function update(e:Event):void {
				_currentField.text = _model.currentStreamBitrate.toString() +  " kbps";
		}
		private function handleIconClick(e:MouseEvent):void {
			if (true) {
				_logo.transform.colorTransform = new ColorTransform(1,1,1,1,0);
			} else {
				_logo.transform.colorTransform = new ColorTransform(1,1,1,1,200);
			}
		}
	}
}
