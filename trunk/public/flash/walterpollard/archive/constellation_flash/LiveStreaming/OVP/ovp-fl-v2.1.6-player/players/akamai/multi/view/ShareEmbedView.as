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
	import flash.geom.*;
	import flash.text.*;
	import flash.utils.ByteArray;
	import flash.ui.Mouse;
	import flash.utils.*;
	import flash.system.*;
	import model.Model;
	import view.*;
	import ui.*;

	public class ShareEmbedView extends MovieClip {


		private var _model:Model;
		private var _background:MovieClip;
		private var _copyLinkButton:GenericButton;
		private var _copyEmbedButton:GenericButton;
		private var _linkLabel:TextField;
		private var _embedLabel:TextField;
		private var _linkText:TextField;
		private var _embedText:TextField;
		private var _isVisible:Boolean;


		public function ShareEmbedView(model:Model):void {
			_model = model;
			_model.addEventListener(_model.EVENT_RESIZE, resize);
			_model.addEventListener(_model.EVENT_TOGGLE_LINK, toggleHandler);
			_model.addEventListener(_model.EVENT_SHOW_CONTROLS, showHandler);
			_model.addEventListener(_model.EVENT_HIDE_CONTROLS, hideHandler);
			createChildren();
		}
		private function createChildren():void {
			_background = new MovieClip();
			addChild(_background);
			// Add copy share button
			_copyLinkButton = generateCopyButton("link");
			addChild(_copyLinkButton);
			// Add embed share button
			_copyEmbedButton = generateCopyButton("embed");
			_copyEmbedButton.y = 50;
			addChild(_copyEmbedButton);
			// Add liunk label
			_linkLabel = generateTextField("LINK",_model.fontColor,false);
			_linkLabel.x = 44;
			addChild(_linkLabel);
			// Add embed label
			_embedLabel = generateTextField("EMBED",_model.fontColor,false);
			_embedLabel.x = 30;
			addChild(_embedLabel);
			// Add link text
			_linkText = generateTextField(_model.share == "" ? "No link parameter has been supplied for this player":_model.share,_model.themeColor,true);
			addChild(_linkText);
			// Add link text
			_embedText = generateTextField(_model.embed == "" ? "No embed code has been provided for this player":_model.embed,_model.themeColor,true);
			addChild(_embedText);
			//
			this.visible = false;
			_isVisible = false;


		}
		private function showHandler(e:Event):void {
			if (_isVisible) {
				this.visible = true;
			}
		}
		private function hideHandler(e:Event):void {
				this.visible = false;
		}
		private function toggleHandler(e:Event):void {
			this.visible = !this.visible;
			_isVisible = this.visible;
		}
		private function generateCopyButton(name:String):GenericButton {
			var transform:ColorTransform = new ColorTransform();
			transform.color = _model.themeColor;
			var button:GenericButton = new GenericButton();
			button.name = name;
			button.highlight.transform.colorTransform = transform;
			button.highlight.alpha = 0;
			button.addEventListener(MouseEvent.MOUSE_OVER,genericMouseOver);
			button.addEventListener(MouseEvent.MOUSE_OUT,genericMouseOut);
			button.addEventListener(MouseEvent.MOUSE_DOWN,genericMouseDown);
			button.addEventListener(MouseEvent.MOUSE_UP,genericMouseUp);
			button.addEventListener(MouseEvent.CLICK,doCopy);
			var copyLabel:TextField = new TextField();
			copyLabel.embedFonts = true;
			var textFormat:TextFormat = new TextFormat();
			textFormat.font = new AkamaiArialBold().fontName;
			textFormat.size = 11;
			textFormat.color = _model.fontColor
			copyLabel.defaultTextFormat = textFormat;
			copyLabel.text = "COPY";
			copyLabel.mouseEnabled = false;
			copyLabel.antiAliasType = flash.text.AntiAliasType.ADVANCED;
			copyLabel.x = 5;
			copyLabel.y = 3;
			button.addChild(copyLabel);
			return button
			
		}
		private function generateTextField(text:String,color:Number,enabled:Boolean):TextField {
			var txt:TextField = new TextField();
			txt.embedFonts = true;
			var textFormat:TextFormat = new TextFormat();
			textFormat.font = new AkamaiArialBold().fontName;
			textFormat.size = 11;
			textFormat.color = color;
			txt.defaultTextFormat = textFormat;
			txt.mouseEnabled = enabled;
			txt.selectable = enabled;
			txt.antiAliasType = flash.text.AntiAliasType.ADVANCED;
			txt.text = text;
			return txt;
		}
		private function genericMouseDown(e:MouseEvent):void {
			e.currentTarget.x += 1;
			e.currentTarget.y += 1;
		}
		private function genericMouseUp(e:MouseEvent):void {
			e.currentTarget.x -= 1;
			e.currentTarget.y -= 1;
		}
		private function genericMouseOver(e:MouseEvent):void {
			e.currentTarget.highlight.alpha = 1;
		}
		private function genericMouseOut(e:MouseEvent):void {
			e.currentTarget.highlight.alpha = 0;
		}
		private function doCopy(e:MouseEvent):void {
			switch (e.currentTarget.name) {
				case "link":
					_linkText.stage.focus = _linkText;
					_linkText.setSelection(0, _linkText.length);
					System.setClipboard(_linkText.text);
				break;
				case "embed":
					_embedText.stage.focus = _embedText;
					_embedText.setSelection(0, _embedText.length);
					System.setClipboard(_embedText.text);
				break;
			}
		}
		public function duplicateItem(obj:*):* {
			var className:String = getQualifiedClassName(obj).split('::').join('.');
			var ClassRef:Class = getDefinitionByName(className) as Class;
			var item:* = new ClassRef();
			return item;
		}
		public function resize(e:Event):void  {
			//draw background
			var _availableVideoWidth:Number = _model.width - ((_model.hasPlaylist && _model.playlistVisible) ? _model.playlistWidth + (!_model.isOverlay ? 6:0):0) - 6;
			var _availableVideoHeight:Number = _model.height - (_model.isOverlay ? 0:_model.controlbarHeight) - 6;
			this.graphics.clear();
			this.graphics.beginFill(_model.controlbarOverlayColor, .6);
			this.graphics.drawRect(3, 3 + _availableVideoHeight - 118,_availableVideoWidth, 83);
			this.graphics.beginFill(0x000000);
			this.graphics.drawRect(80, 3 + _availableVideoHeight - 101, _availableVideoWidth - 180, 20);
			this.graphics.drawRect(80,3 + _availableVideoHeight - 71,_availableVideoWidth-180,20);
			this.graphics.endFill();
			_linkText.width = _availableVideoWidth - 190;
			_embedText.width = _availableVideoWidth - 190;
			_linkText.x = 83;
			_linkText.y = 3 + _availableVideoHeight - 99;
			_embedText.x = 83;
			_embedText.y = 3 + _availableVideoHeight - 69;
			
			_linkLabel.y = 3 + _availableVideoHeight - 100;
			_embedLabel.y = 3 + _availableVideoHeight - 70;
			_copyLinkButton.x = 3 + _availableVideoWidth - 95;
			_copyLinkButton.y = 3 + _availableVideoHeight - 102;
			_copyEmbedButton.x = 3 + _availableVideoWidth - 95;
			_copyEmbedButton.y = 3 + _availableVideoHeight - 72;
			
		}
	}
}
