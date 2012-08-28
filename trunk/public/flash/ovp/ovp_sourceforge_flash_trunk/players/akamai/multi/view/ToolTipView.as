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
	import flash.events.*;
	import flash.geom.*;
	import flash.text.*;
	import model.Model;
	import ui.*;
	import flash.utils.Timer;

	/**
	 * Akamai Multi Player - generates the button tips views.
	 */
	public class ToolTipView extends MovieClip {


		private var _model:Model;
		private var _background:ToolTip;
		private var _title:TextField;
		private var _watchedItems:Object;
		private var _timer:Timer;
		private var _currentTarget:MovieClip;
		
		private const DELAY:Number = 1000;
		
		
		public function ToolTipView(model:Model):void {
			_model = model;
			createChildren();
		}
		private function createChildren():void {
			var _themeTransform:ColorTransform = new ColorTransform();
			_themeTransform.color = _model.themeColor;
			var textFormat:TextFormat=new TextFormat();
			textFormat.font= new AkamaiArial().fontName;
			textFormat.color = 0x000000;
			textFormat.size = 10;
			textFormat.align = TextFormatAlign.CENTER;
			//
			_background = new ToolTip();
			_background.transform.colorTransform = _themeTransform;
			addChild(_background);
			//
			 _title = new TextField()
			 _title.width = 80;
			 _title.height = 15;
			 _title.embedFonts = true;
			 _title.defaultTextFormat = textFormat;
			 _title.autoSize = TextFieldAutoSize.NONE;
			 _title.multiline = false;
			 _title.wordWrap = false;
			 _title.text = "FULL SCREEN";
			 _title.selectable=false;
			 _title.antiAliasType=flash.text.AntiAliasType.ADVANCED;
			 _title.x = 3;
			 _title.y = 3;
			 addChild(_title);
			 //
			 _watchedItems  = new Object();
			 //
			 _timer  = new Timer(DELAY,1);
			 _timer.addEventListener(TimerEvent.TIMER_COMPLETE, completeHandler);
			// hide this panel
			this.visible = false;
			
		}
		public function register(mc:MovieClip, txt:String):void {
			 _watchedItems[mc.name] = txt;
			 mc.addEventListener(MouseEvent.MOUSE_OVER, handleMouseOver);
			 mc.addEventListener(MouseEvent.MOUSE_OUT, handleMouseOut);
		}
		private function handleMouseOver(e:MouseEvent):void {
			_timer.reset();
			_timer.start();
			_currentTarget = e.currentTarget as MovieClip;
			
		}
		private function completeHandler(e:TimerEvent):void {
			this.visible = true;
			reposition(_currentTarget);
			_title.text = _watchedItems[_currentTarget.name]
		}
		private function handleMouseOut(e:MouseEvent):void {
			_timer.stop();
			this.visible = false;
		}
		private function reposition(mc:MovieClip):void {
			this.x = mc.x - 52;
			this.y = mc.y - 20;
		}

	}
}
