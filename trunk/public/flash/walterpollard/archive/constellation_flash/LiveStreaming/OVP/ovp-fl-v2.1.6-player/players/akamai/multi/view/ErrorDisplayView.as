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

package view {
	
	import flash.display.*;
	import flash.events.*;
	import flash.text.*;
	import model.Model;
	import ui.*;

	/**
	 * Akamai Multi Player - generates the error display view, which surfaces error warnings to the end-user.
	 */
	public class ErrorDisplayView extends MovieClip {


		private var _model:Model;
		private var _background:MovieClip;
		private var _mc:TextField;
		private var _closeButton:MovieClip;
		private var _closeText:TextField;

		public function ErrorDisplayView(model:Model):void {
			_model = model;
			_model.addEventListener(_model.EVENT_RESIZE, resize);
			_model.addEventListener(_model.EVENT_SHOW_ERROR, showError);
			createChildren();
		}
		private function createChildren():void {
			_background = new MovieClip();
			addChild(_background);
			//draw background
			_background.graphics.beginFill(0x990000);
			_background.graphics.drawRect(0, 0, 250, 100);
			_background.graphics.beginFill(0x000000);
			_background.graphics.drawRect(2,2,246,96);
			_background.graphics.endFill();
			//
			 _mc = new TextField()
			 _mc.width = 230;
			 _mc.height = 80;
			 _mc.embedFonts = true;
			 _mc.defaultTextFormat = _model.defaultTextFormat;
			 _mc.autoSize=TextFieldAutoSize.CENTER;
			 _mc.multiline = true
			 _mc.wordWrap = true;
			 _mc.text="Default error message";
			 _mc.selectable=false;
			 _mc.antiAliasType=flash.text.AntiAliasType.ADVANCED;
			 _mc.x=10;
			 _mc.y=10;
			 _background.addChild(_mc);
			 //
			 _closeButton = new MovieClip();
			_closeButton.addEventListener(MouseEvent.MOUSE_DOWN,genericMouseDown);
			_closeButton.addEventListener(MouseEvent.MOUSE_UP, genericMouseUp);
			_closeButton.addEventListener(MouseEvent.CLICK, closePanel);
			_closeButton.buttonMode = true;
			_closeButton.useHandCursor = true;
			_closeButton.x = 200;
			_closeButton.y = 3;
			_background.addChild(_closeButton);
			_closeText = new TextField();
			_closeText.width = 50;
			_closeText.height = 20;
			_closeText.embedFonts = true;
			var textFormat:TextFormat=new TextFormat();
			textFormat.font = new AkamaiArialBold().fontName;
			textFormat.size = 11;
			textFormat.color = 0xff0000;
			_closeText.defaultTextFormat = textFormat;
			_closeText.text = "CLOSE";
			_closeText.mouseEnabled = false;
			_closeText.antiAliasType = flash.text.AntiAliasType.ADVANCED;
			_closeButton.addChild(_closeText);
			// hide this panel
			this.visible = false;
			
		}
		private function showError(e:Event):void {
			this.visible = true;
			_mc.text = "ERROR\n\n"+_model.errorMessage;
		}
		private function closePanel(e:MouseEvent):void {
			this.visible = false;
		}
		private function genericMouseDown(e:MouseEvent):void {
			if (e.currentTarget.enabled) {
				e.currentTarget.x += 1;
				e.currentTarget.y += 1;
			}
		}
		private function genericMouseUp(e:MouseEvent):void {
			if (e.currentTarget.enabled) {
				e.currentTarget.x -= 1;
				e.currentTarget.y -= 1;
			}
		}
		public function resize(e:Event):void  {
			if (_model.isOverlay) {
				_background.x=Number(_model.width - _background.width) / 2;
				_background.y=Number(_model.height - _model.controlbarHeight - _background.height) / 2;
			} else {
				_background.x=Number(_model.width - (_model.hasPlaylist ? _model.playlistWidth:0) - _background.width) / 2;
				_background.y=Number(_model.height - _model.controlbarHeight - _background.height) / 2;
			}
			
		}
	}
}
