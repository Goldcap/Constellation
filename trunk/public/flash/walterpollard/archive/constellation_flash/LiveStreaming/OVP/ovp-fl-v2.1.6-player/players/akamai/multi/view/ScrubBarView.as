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
	import controller.*;

	/**
	 * Akamai Multi Player - generates the scrub bar view as a compilation of the current progress, download progress, scrub head, current time and 
	 * total time controls. 
	 */
	public class ScrubBarView extends MovieClip {


		private var _model:Model;
		private var _controller:ScrubBarController;
		private var _maxLength:Number;
		private var _currentTimeDisplay:TextField;
		private var _totalTimeDisplay:TextField;
		private var _currentProgress:MovieClip;
		private var _downloadProgress:MovieClip;
		private var _scrubber:MovieClip;
		private var _dragging:Boolean;
		private var _background:MovieClip;
		private var _enabled:Boolean;

		public function ScrubBarView(model:Model):void {
			_model=model;
			_controller=new ScrubBarController(_model,this);
			addEventListener(Event.ADDED_TO_STAGE, addReleaseOutsideHandler);
			//addEventListener(MouseEvent.MOUSE_DOWN, clickHandler);
			_model.addEventListener(_model.EVENT_PROGRESS, progressHandler);
			_model.addEventListener(_model.EVENT_BUFFER_FULL, bufferFullHandler);
			_model.addEventListener(_model.EVENT_NEW_SOURCE, newSourceHandler);
			_model.addEventListener(_model.EVENT_CLOSE_AFTER_PREVIEW,closeAfterPreviewHandler);
			_model.addEventListener(_model.EVENT_ENABLE_CONTROLS, enableHandler);
			_model.addEventListener(_model.EVENT_DISABLE_CONTROLS, disableHandler);
			
			createChildren();
		}
		
		private function addReleaseOutsideHandler(e:Event):void {
			 stage.addEventListener(MouseEvent.MOUSE_UP, doOnReleaseOutside);
		}
		
		private function enableHandler(e:Event):void {
			enable();
		}
		
		private function disableHandler(e:Event):void {
			enable(false);
		}
		
		private function enable(value:Boolean=true):void {
			_enabled = value;
			_background.mouseEnabled = _background.mouseChildren = 
				_downloadProgress.mouseEnabled = _downloadProgress.mouseChildren = 
				_currentProgress.mouseEnabled = _currentProgress.mouseChildren = 
				_scrubber.mouseEnabled = _scrubber.mouseChildren = value;
			alpha = value ? 1.0 : 0.25;
		}
		
		private function createChildren():void {
			_background = new MovieClip();
			_background.useHandCursor = true;
			_background.buttonMode = true;
			addChild(_background);
			 _dragging = false;
			 _enabled = true;
			// Add current time display
			 _currentTimeDisplay = new TextField()
			 _currentTimeDisplay.embedFonts = true;
			 _currentTimeDisplay.defaultTextFormat = _model.defaultTextFormat;
			 _currentTimeDisplay.autoSize=TextFieldAutoSize.RIGHT;
			 _currentTimeDisplay.multiline = false;
			 _currentTimeDisplay.wordWrap = false;
			 _currentTimeDisplay.text="Waiting";
			 _currentTimeDisplay.selectable=false;
			 _currentTimeDisplay.antiAliasType=flash.text.AntiAliasType.ADVANCED;
			 _currentTimeDisplay.x=0;
			 _currentTimeDisplay.y=0;
			 addChild(_currentTimeDisplay);
			 // Add total time display
			 _totalTimeDisplay = new TextField()
			 _totalTimeDisplay.embedFonts = true;
			 _totalTimeDisplay.defaultTextFormat = _model.defaultTextFormat;
			 _totalTimeDisplay.autoSize=TextFieldAutoSize.LEFT;
			 _totalTimeDisplay.multiline = false;
			 _totalTimeDisplay.wordWrap = false;
			 _totalTimeDisplay.text="";
			 _totalTimeDisplay.selectable=false;
			 _totalTimeDisplay.antiAliasType=flash.text.AntiAliasType.ADVANCED;
			
			 _totalTimeDisplay.y=0;
			 addChild(_totalTimeDisplay);
			 // Add download progress
			 _downloadProgress = new MovieClip();
			 _downloadProgress.graphics.beginFill(_model.themeColor,.3);
			 _downloadProgress.graphics.drawRect(0,0,1,8);
			 _downloadProgress.graphics.endFill();
			 _downloadProgress.x = 56;
			 _downloadProgress.y = 5;
			 _downloadProgress.useHandCursor = true;
			 _downloadProgress.buttonMode = true;
			 addChild(_downloadProgress);
			  // Add current progress
			 _currentProgress = new MovieClip();
			 _currentProgress.graphics.beginFill(_model.themeColor);
			 _currentProgress.graphics.drawRect(0,0,1,8);
			 _currentProgress.graphics.endFill();
			 _currentProgress.x = 56;
			 _currentProgress.y = 5;
			 _currentProgress.useHandCursor = true;
			 _currentProgress.buttonMode = true;
			 _currentProgress.addEventListener(Event.ENTER_FRAME,updateCurrentProgress);
			 addChild(_currentProgress);
			 // Add scrubber
			 _scrubber = new MovieClip();
			 _scrubber.graphics.beginFill(0xAAAAAA);
			 _scrubber.graphics.drawRect(0,0,7,12);
			 _scrubber.graphics.endFill();
			 _scrubber.x = 56;
			 _scrubber.y = 3;
			 _scrubber.addEventListener(MouseEvent.MOUSE_DOWN,scrubberDown);
			 _scrubber.addEventListener(MouseEvent.MOUSE_UP, scrubberUp, true);
			 _scrubber.useHandCursor = true;
			 _scrubber.buttonMode = true;
			 addChild(_scrubber);

		}
		private function newSourceHandler(e:Event):void {
			_currentTimeDisplay.text = "Loading";
		}
		private function closeAfterPreviewHandler(e:Event):void {
			_currentTimeDisplay.text = "00:00";
		}
		private function scrubberDown(e:MouseEvent):void {
			_scrubber.startDrag(false,new Rectangle(55,3,_maxLength-7,0));
			_dragging = true;
		}
		private function scrubberUp(e:MouseEvent):void {
			_scrubber.stopDrag();
			var t:Number = (_scrubber.x - 55) * _model.streamLength / (_maxLength -7);
			_controller.seek(t);
			
		}

		private function doOnReleaseOutside(e:MouseEvent):void {
			if (!_enabled) {
				return;
			}
			if (_dragging) {
				scrubberUp(null);
			} else {
				if (!_scrubber.hitTestPoint(e.stageX,e.stageY) && _scrubber.visible) {
				var topLeft:Point = new Point(55,4);
				var bottomRight:Point = new Point(55+_maxLength,14);
				if (e.stageX > this.localToGlobal(topLeft).x && 
					e.stageX < this.localToGlobal(bottomRight).x && 
					e.stageY < this.localToGlobal(bottomRight).y && 
					e.stageY > this.localToGlobal(topLeft).y ) {
						_scrubber.x = this.globalToLocal(new Point(e.stageX,e.stageY)).x;
						_dragging = true;
						scrubberUp(null);
					}
				}
			}
		}
		private function progressHandler(e:Event):void {
	
			if (!isNaN(_model.time) && (_model.isLive || !isNaN(_model.streamLength))) {
				
				_totalTimeDisplay.text = _model.isLive ? "LIVE":_model.streamLengthAsTimeCode;
				_scrubber.visible = _currentProgress.visible = !_model.isLive;
				
				
				if (_model.isBuffering) {
					_currentTimeDisplay.text = _model.bufferPercentage+"%";
				} else {
					_currentTimeDisplay.text = _model.timeAsTimeCode;
				}

				if (!_dragging) {
					_scrubber.x = Math.max(55,55 + (_model.time*(_maxLength - 7)/_model.streamLength));
				}
				if (_model.srcType == _model.TYPE_AMD_PROGRESSIVE || _model.srcType == _model.TYPE_BOSS_PROGRESSIVE) {
					_downloadProgress.width = _maxLength * _model.bytesLoaded / _model.bytesTotal;
				} else {
					_downloadProgress.width = 0;
				}
			}
		}
		private function bufferFullHandler(e:Event):void {
			_dragging = false;
		}
		private function updateCurrentProgress(e:Event):void {
			_currentProgress.width = _scrubber.x - 55 + 3;
		}
		public function setWidth(w:Number):void {
			_maxLength = w - 120;
			 _totalTimeDisplay.x= w-60;
			_background.graphics.clear();
			_background.graphics.beginFill(0x000000);
			_background.graphics.drawRect(55,4,_maxLength,10);
			_background.graphics.endFill();
			
		}

	}
}
