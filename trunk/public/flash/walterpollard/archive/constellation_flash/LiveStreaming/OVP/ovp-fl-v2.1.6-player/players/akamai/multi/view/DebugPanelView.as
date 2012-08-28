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
	
	//import fl.controls.TextArea;
	import flash.display.*;
	import flash.events.*;
	import flash.geom.*;
	import model.Model;
	import controller.*;
	import view.*;
	import ui.*;
	import flash.text.*;
	import flash.utils.ByteArray;
	import flash.ui.Mouse;
	import flash.utils.*;
	import flash.system.*;

	/**
	 * Akamai Multi Player - generates the debug panel view, which provides a handy runtime data on the operation of the player.
	 */
	public class DebugPanelView extends MovieClip {


		private var _model:Model;
		private var _background:MovieClip;
		private var _clearButton:GenericButton;
		private var _copyButton:GenericButton;
		private var _displayPanel:TextField;
		private var _scrollUpButton:PlaylistScrollButton;
		private var _scrollDownButton:PlaylistScrollButton;
		private var _scrollTimer:Timer;
		private var _scrollDirection:String;
		private var _bandwidthPanel:TextField;
		private var _bandwidthLabel:TextField;
		private var _streamPanel:TextField;
		private var _streamLabel:TextField;
		private var _bufferPanel:TextField;
		private var _bufferLabel:TextField;


		public function DebugPanelView(model:Model):void {
			_model = model;
			_model.addEventListener(_model.EVENT_RESIZE, resize);
			_model.addEventListener(_model.EVENT_TOGGLE_DEBUG, toggleHandler);
			_model.addEventListener(_model.EVENT_UPDATE_DEBUG, updateHandler);
			_model.addEventListener(_model.EVENT_PROGRESS, progressHandler);
			createChildren();
		}
		private function createChildren():void {
			
			_background = new MovieClip();
			addChild(_background);
			// Add clear button
			_clearButton = generateButton("CLEAR");
			addChild(_clearButton);
			// Add copy button
			_copyButton = generateButton("COPY");
			_copyButton.y = 50;
			addChild(_copyButton);
			// Add display panel
			_displayPanel = generateTextField();
			_displayPanel.x = 20;
			addChild(_displayPanel);
			// Add bandwidth panel
			_bandwidthPanel = generateTextField();
			_bandwidthPanel.embedFonts = true;
			_bandwidthPanel.width = 300;
			var textFormat:TextFormat = new TextFormat();
			textFormat.font = new AkamaiLCD().fontName;
			textFormat.size = 40;
			textFormat.align = TextFormatAlign.RIGHT;
			textFormat.color = 0xffffff;
			_bandwidthPanel.defaultTextFormat = textFormat;
			_bandwidthPanel.y = 70;
			_bandwidthPanel.text = "0 kbps";
			addChild(_bandwidthPanel);
			//Add bandwidth label
			_bandwidthLabel = generateTextField();
			_bandwidthLabel.width = 250;
			_bandwidthLabel.text = "MAXIMUM CONNECTION BANDWIDTH";
			_bandwidthLabel.y = 50;
			addChild(_bandwidthLabel);
			// Add streampanel
			_streamPanel = generateTextField();
			_streamPanel.embedFonts = true;
			_streamPanel.width = 300;
			_streamPanel.defaultTextFormat = textFormat;
			_streamPanel.y = 150;
			_streamPanel.text = "0 kbps";
			addChild(_streamPanel);
			//Add stream label
			_streamLabel = generateTextField();
			_streamLabel.width = 250;
			_streamLabel.text = "CURRENT STREAM BITRATE";
			_streamLabel.y = 130;
			addChild(_streamLabel);
			//
			// Add bufferpanel
			_bufferPanel = generateTextField();
			_bufferPanel.embedFonts = true;
			_bufferPanel.width = 300;
			_bufferPanel.defaultTextFormat = textFormat;
			_bufferPanel.y = 230;
			_bufferPanel.text = "0 kbps";
			addChild(_bufferPanel);
			//Add  buffer label
			_bufferLabel = generateTextField();
			_bufferLabel.width = 250;
			_bufferLabel.text = "CURRENT BUFFER LENGTH";
			_bufferLabel.y = 210;
			addChild(_bufferLabel);
			//
			var _themeTransform:ColorTransform = new ColorTransform();
			_themeTransform.color = 0x333333;
			//
			_scrollUpButton = new PlaylistScrollButton();
			_scrollUpButton.name = "up";
			_scrollUpButton.highlight.transform.colorTransform = _themeTransform;
			_scrollUpButton.highlight.alpha = 0;
			_scrollUpButton.addEventListener(MouseEvent.MOUSE_OVER,scrollOver);
			_scrollUpButton.addEventListener(MouseEvent.MOUSE_OUT,scrollOut);
			_scrollUpButton.addEventListener(MouseEvent.MOUSE_DOWN,scrollDown);
			_scrollUpButton.addEventListener(MouseEvent.MOUSE_UP, scrollUp);
			_scrollUpButton.x = 20;
			_scrollUpButton.y = 5;
			//_scrollUpButton.addEventListener(MouseEvent.CLICK,doScrollUp);
			addChild(_scrollUpButton);
			_scrollDownButton = new PlaylistScrollButton();
			_scrollDownButton.name = "down";
			_scrollDownButton.highlight.transform.colorTransform = _themeTransform;
			_scrollDownButton.highlight.alpha = 0;
			_scrollDownButton.addEventListener(MouseEvent.MOUSE_OVER,scrollOver);
			_scrollDownButton.addEventListener(MouseEvent.MOUSE_OUT,scrollOut);
			_scrollDownButton.addEventListener(MouseEvent.MOUSE_DOWN,scrollDown);
			_scrollDownButton.addEventListener(MouseEvent.MOUSE_UP,scrollUp);
			//_scrollDownButton.addEventListener(MouseEvent.CLICK,doScrollDown);
			_scrollDownButton.rotation = 180;
			_scrollDownButton.x = 220;
			_scrollDownButton.y = 26;
			addChild(_scrollDownButton);
			//
			this.visible = false;
			_scrollTimer = new Timer(30);
			_scrollTimer.addEventListener(TimerEvent.TIMER, doAutoScroll);
		}
		private function toggleHandler(e:Event):void {
			this.visible = !this.visible;
		}
		private function updateHandler(e:Event):void {
			_displayPanel.text = _model.debugTrace;
		}
		private function generateButton(name:String):GenericButton {
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
			copyLabel.text = name;
			copyLabel.mouseEnabled = false;
			copyLabel.antiAliasType = flash.text.AntiAliasType.ADVANCED;
			copyLabel.x = 5;
			copyLabel.y = 3;
			button.addChild(copyLabel);
			return button
			
		}
		private function generateTextField():TextField {
			var txt:TextField = new TextField();
			txt.embedFonts = true;
			var textFormat:TextFormat = new TextFormat();
			textFormat.font = new AkamaiArial().fontName;
			textFormat.size = 11;
			textFormat.color = 0xcccccc;
			txt.defaultTextFormat = textFormat;
			txt.mouseEnabled = enabled;
			txt.selectable = enabled;
			txt.wordWrap = true;
			txt.selectable = true;
			txt.text = "";
			txt.antiAliasType = flash.text.AntiAliasType.ADVANCED;
			return txt;
		}
		private function scrollOver(e:MouseEvent):void {
				e.currentTarget.highlight.alpha = 1;
		}
		private function scrollOut(e:MouseEvent):void {
				e.currentTarget.highlight.alpha = 0;
		}
		private function scrollDown(e:MouseEvent):void {
			_scrollDirection = e.currentTarget.name;
			_scrollTimer.start();
			e.currentTarget.x += 1;
			e.currentTarget.y += 1;
		}
		private function scrollUp(e:MouseEvent):void {
			_scrollTimer.stop();
			e.currentTarget.x -= 1;
			e.currentTarget.y -= 1;
		}
		private function doScrollUp(e:MouseEvent): void {
			_displayPanel.scrollV -= 1;
			
		}
		private function doScrollDown(e:MouseEvent): void {
			_displayPanel.scrollV += 1;
		}
		private function doAutoScroll(e:TimerEvent):void {
			_scrollDirection == "up" ? doScrollUp(null):doScrollDown(null);
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
				case "COPY":
					_displayPanel.stage.focus = _displayPanel;
					_displayPanel.setSelection(0,_displayPanel.length);
					System.setClipboard(_displayPanel.text);
					break;
				case "CLEAR":
					_model.clearDebugTrace();
				break;
			}
		}
		private function progressHandler(e:Event):void {
			_bandwidthPanel.text = _model.maxBandwidth == 0 ? "Calculating": isNaN(_model.maxBandwidth) ? "Unavailable":_model.maxBandwidth + " kbps";
			_streamPanel.text = _model.currentStreamBitrate == 0 ? "Calculating": isNaN(_model.currentStreamBitrate) ? "Unavailable":_model.currentStreamBitrate+ " kbps";
			_bufferPanel.text  = isNaN(_model.bufferLength) ? "Unavailable":Math.round(_model.bufferLength*10)/10 + " sec";
			}
		public function resize(e:Event):void  {
			//draw background
			//var _availableVideoWidth:Number = _model.width - (_model.isOverlay ? 0:(_model.hasPlaylist && _model.playlistVisible) ? _model.playlistWidth+6:0) - 6;
			var _availableVideoWidth:Number = _model.width - ((_model.hasPlaylist && _model.playlistVisible) ? _model.playlistWidth + (!_model.isOverlay ? 6:0):0) - 6;
			
			var _availableVideoHeight:Number = _model.height - _model.controlbarHeight - 6;
			this.graphics.clear();
			this.graphics.beginFill(0x000000);
			this.graphics.drawRect(3, 3,_availableVideoWidth, 30);
			this.graphics.beginFill(_model.controlbarOverlayColor, .8);
			this.graphics.drawRect(3, 33, _availableVideoWidth, _availableVideoHeight - 30);
			this.graphics.endFill();

			
			_displayPanel.y = 33;
			_displayPanel.width = _availableVideoWidth - 30;
			_displayPanel.height = _availableVideoHeight - 33;

			_clearButton.x = _availableVideoWidth - 100;
			_clearButton.y = 6;
			_copyButton.x = _availableVideoWidth - 50
			_copyButton.y = 6;
			
			_bandwidthPanel.x = _availableVideoWidth - 345;
			_bandwidthLabel.x = _availableVideoWidth - 250;
			
			_streamPanel.x = _availableVideoWidth - 345;
			_streamLabel.x = _availableVideoWidth - 250;
			
			_bufferPanel.x = _availableVideoWidth - 345;
			_bufferLabel.x = _availableVideoWidth - 250;
			
		}
	}
}
