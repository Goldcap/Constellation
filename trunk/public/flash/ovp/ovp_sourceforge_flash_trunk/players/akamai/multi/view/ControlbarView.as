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
	
	import controller.*;
	
	import flash.display.*;
	import flash.events.*;
	import flash.geom.*;
	
	import model.Model;
	
	import ui.*;
    import com.zeusprod.Log;
	
	/**
	 * Akamai Multi Player - generates the control bar view, including the play, pause, fullscreen, share, and playlist buttons, as well as the volume and scrub-bar controls.
	 */
	public class ControlbarView extends MovieClip {


		private var _model:Model;
		private var _controller:ControlbarController;
		private var _background:MovieClip;
		private var _container:MovieClip;
		(CONFIG::CTV) {
			private var _playButton:PlayButton_CTV;
			private var _pauseButton:PauseButton_CTV;
		}
		(CONFIG::NON_CTV) {
			private var _playButton:PlayButton;
			private var _pauseButton:PauseButton;
		}
		private var _fullscreenButton:FullscreenButton;
		private var _shareButton:ShareButton;
		private var _playlistButton:PlaylistButton;
		private var _volumeControl:VolumeControlView;
		private var _HDmeter:HDMeter2View;
		private var _scrubBar:ScrubBarView;
		private var _toolTip:ToolTipView;
		private var _themeTransform:ColorTransform;
		private var _b6b6b6Transform:ColorTransform;

		public function ControlbarView(model:Model,
										includeBackground:Boolean,
										includeContainer:Boolean,
										includeFullscreenButton:Boolean,
										includeVolumeControl:Boolean,
										includeScrubBar:Boolean,
										includeTooltip:Boolean,
										includePlayButton:Boolean, 
										includePauseButton:Boolean,
										includeShareButton:Boolean		= false,
										includePlayListButton:Boolean	= false,
										includeHDmeter:Boolean			= false
										):void {
			_model = model;
			_model.addEventListener(_model.EVENT_RESIZE, resize);
			_model.addEventListener(_model.EVENT_SHOW_CONTROLS, showHandler);
			_model.addEventListener(_model.EVENT_HIDE_CONTROLS, hideHandler);
			_model.addEventListener(_model.EVENT_HIDE_FULLSCREEN, fullscreenHandler);
			_model.addEventListener(_model.EVENT_SHOW_PAUSE, showPauseHandler);
			_model.addEventListener(_model.EVENT_END_OF_ITEM, endOfItemHandler);
			_model.addEventListener(_model.EVENT_ENABLE_CONTROLS, enableHandler);
			_model.addEventListener(_model.EVENT_DISABLE_CONTROLS, disableHandler);
			
			_controller = new ControlbarController(_model,this);
			createChildren(includeBackground, includeContainer, includeFullscreenButton,
							includeVolumeControl, includeScrubBar, includeTooltip,
							includePlayButton, includePauseButton, includeShareButton,
							includePlayListButton, includeHDmeter);
			this.visible = !_model.isOverlay;
		}
		
		private function createChildren(includeBackground:Boolean,
										includeContainer:Boolean,
										includeFullscreenButton:Boolean,
										includeVolumeControl:Boolean,
										includeScrubBar:Boolean,
										includeTooltip:Boolean,
										includePlayButton:Boolean, 
										includePauseButton:Boolean,
										includeShareButton:Boolean,
										includePlayListButton:Boolean,
										includeHDmeter:Boolean
										):void {
			
			_background = new MovieClip();
			addChild(_background);
			_container = new MovieClip();
			addChild(_container);
			
			// Define color transforms
			_themeTransform = new ColorTransform();
			_themeTransform.color = _model.themeColor;
			_b6b6b6Transform = new ColorTransform();
			_b6b6b6Transform.color = 0xB6B6B6;
			// Add playbutton
			if (CONFIG::CTV) {
				_playButton = new PlayButton_CTV();
			} else {
				_playButton = new PlayButton();
			}
			if (CONFIG::HIGHLIGHT) {
				_playButton.highlight.transform.colorTransform = _themeTransform;
				_playButton.highlight.alpha = 0;
			}
			_playButton.addEventListener(MouseEvent.MOUSE_OVER,buttonMouseOver);
			_playButton.addEventListener(MouseEvent.MOUSE_OUT,buttonMouseOut);
			_playButton.addEventListener(MouseEvent.MOUSE_DOWN,genericMouseDown);
			_playButton.addEventListener(MouseEvent.MOUSE_UP,genericMouseUp);
			_playButton.addEventListener(MouseEvent.CLICK,doPlay);
			_playButton.x = 40;
			_playButton.y = 9;
			_playButton.visible = !_model.autoStart;
			
			if (includePlayButton) {
				_container.addChild(_playButton);
			}
			
			// Add pausebutton
			if (CONFIG::CTV) {
				_pauseButton = new PauseButton_CTV();
			} else {
				_pauseButton = new PauseButton();
			}
			if (CONFIG::HIGHLIGHT) {
				_pauseButton.highlight.transform.colorTransform = _themeTransform;
				_pauseButton.highlight.alpha = 0;
			}
			_pauseButton.addEventListener(MouseEvent.MOUSE_OVER,buttonMouseOver);
			_pauseButton.addEventListener(MouseEvent.MOUSE_OUT,buttonMouseOut);
			_pauseButton.addEventListener(MouseEvent.MOUSE_DOWN,genericMouseDown);
			_pauseButton.addEventListener(MouseEvent.MOUSE_UP,genericMouseUp);
			_pauseButton.addEventListener(MouseEvent.CLICK,doPause);
			_pauseButton.x = 40;
			_pauseButton.y = 9;
			_pauseButton.visible = _model.autoStart;
			if (includePauseButton) {
				_container.addChild(_pauseButton);
			}
			//Add fullscreen button
			_fullscreenButton = new FullscreenButton();
			_fullscreenButton.addEventListener(MouseEvent.MOUSE_OVER,genericMouseOver);
			_fullscreenButton.addEventListener(MouseEvent.MOUSE_OUT, genericMouseOut);
			_fullscreenButton.addEventListener(MouseEvent.MOUSE_DOWN,genericMouseDown);
			_fullscreenButton.addEventListener(MouseEvent.MOUSE_UP, genericMouseUp);
			_fullscreenButton.addEventListener(MouseEvent.CLICK,toggleFullscreen);
			if (_model.isTrailer) {
				_fullscreenButton.x = 250;
				_fullscreenButton.y = 0;
			} else {
				_fullscreenButton.x = _model.fullscreenButtonX;
				_fullscreenButton.y = _model.fullscreenButtonY;
			}
			_fullscreenButton.visible = _model.enableFullscreen;
			if (includeFullscreenButton && _model.enableFullscreen) {
				_container.addChild(_fullscreenButton);
			}
			
			//Add share button
			_shareButton = new ShareButton();
			_shareButton.addEventListener(MouseEvent.MOUSE_OVER,genericMouseOver);
			_shareButton.addEventListener(MouseEvent.MOUSE_OUT, genericMouseOut);
			_shareButton.addEventListener(MouseEvent.MOUSE_DOWN,genericMouseDown);
			_shareButton.addEventListener(MouseEvent.MOUSE_UP, genericMouseUp);
			_shareButton.addEventListener(MouseEvent.CLICK,toggleShare);
			_shareButton.x = 250;
			_shareButton.y = 6;
			if (includeShareButton) {
				_container.addChild(_shareButton);
			}
			//Add playlist button
			_playlistButton = new PlaylistButton();
			_playlistButton.addEventListener(MouseEvent.MOUSE_OVER,genericMouseOver);
			_playlistButton.addEventListener(MouseEvent.MOUSE_OUT, genericMouseOut);
			_playlistButton.addEventListener(MouseEvent.MOUSE_DOWN,genericMouseDown);
			_playlistButton.addEventListener(MouseEvent.MOUSE_UP, genericMouseUp);
			_playlistButton.addEventListener(MouseEvent.CLICK, togglePlaylist);
			_playlistButton.x = 300;
			_playlistButton.y = 6;
			if (includePlayListButton) {
				_container.addChild(_playlistButton);
			}
			// Add HDMeter
			_HDmeter = new HDMeter2View(_model);
			_HDmeter.y = 6;
			if (includeHDmeter) {
				_container.addChild(_HDmeter);
			}
			// Add volume control
			_volumeControl = new VolumeControlView(_model);
			_volumeControl.y = 12;
			if (includeVolumeControl) {
				_container.addChild(_volumeControl);
			}
			// Add scrub bar
			_scrubBar = new ScrubBarView(_model);
			_scrubBar.x = 70;
			_scrubBar.y = 9;
			if (includeScrubBar) {
				_container.addChild(_scrubBar);
			}
			// Add tooltip
			_toolTip = new ToolTipView(_model);
			_toolTip.register(_fullscreenButton, "FULL SCREEN");
			_toolTip.register(_playlistButton , "PLAYLIST");
			_toolTip.register(_shareButton, "SHARE|EMBED");
			_toolTip.register(_HDmeter, "HD METER");
			if (includeTooltip) {
				_container.addChild(_toolTip);
			}
		}
		private function buttonMouseOver(e:MouseEvent):void {
				e.currentTarget.highlight.alpha = 1;
		}
		private function buttonMouseOut(e:MouseEvent):void {
				e.currentTarget.highlight.alpha = 0;
		}
		private function genericMouseDown(e:MouseEvent):void {
			e.currentTarget.x += 1;
			e.currentTarget.y += 1;
			_controller.playClickSound();
		}
		private function genericMouseUp(e:MouseEvent):void {
			e.currentTarget.x -= 1;
			e.currentTarget.y -= 1;
		}
		private function genericMouseOver(e:MouseEvent):void {
			e.currentTarget.icon.transform.colorTransform = _themeTransform;
		}
		private function genericMouseOut(e:MouseEvent):void {
			e.currentTarget.icon.transform.colorTransform = _b6b6b6Transform;
		}
		private function doPlay(e:MouseEvent):void {
			_playButton.visible = false;
			_pauseButton.visible = true;
			_controller.play();
		}
		private function doPause(e:MouseEvent):void {
			_playButton.visible = true;
			_pauseButton.visible = false;
			_controller.pause();
			
		}
		private function togglePlaylist(e:MouseEvent):void {
			_controller.togglePlaylist();
		}
		private function toggleFullscreen(e:MouseEvent):void {
			_controller.toggleFullscreen();
		}
		private function toggleShare(e:MouseEvent):void {
			_controller.toggleShare();
		}
		private function endOfItemHandler(e:Event):void {
			_playButton.visible = true;
			_pauseButton.visible = false;
		}
		private function  showHandler(e:Event):void {
			this.visible = true;
		}
		private function  hideHandler(e:Event):void {
			this.visible = false;
		}
		private function enableHandler(e:Event):void {
			_fullscreenButton.alpha = _playButton.alpha = _pauseButton.alpha = 1.0;
			_fullscreenButton.mouseEnabled = _playButton.mouseEnabled = _pauseButton.mouseEnabled = 
				_fullscreenButton.mouseChildren = _playButton.mouseChildren = _pauseButton.mouseChildren = true;			
		}
		private function disableHandler(e:Event):void {
			_fullscreenButton.alpha = _playButton.alpha = _pauseButton.alpha = 0.25;
			_fullscreenButton.mouseEnabled = _playButton.mouseEnabled = _pauseButton.mouseEnabled = 
				_fullscreenButton.mouseChildren = _playButton.mouseChildren = _pauseButton.mouseChildren = false;	
		}
		private function showPauseHandler(e:Event):void {
			_playButton.visible = false;
			_pauseButton.visible = true;
		}
		private function fullscreenHandler(e:Event): void {
			_fullscreenButton.visible = false;
			resize(null);
		}
		public function resize(e:Event):void  {
			//draw background
			_background.graphics.clear();
			_playlistButton.visible = _model.hasPlaylist;
			_shareButton.visible = _model.hasShareOrEmbed;
			_HDmeter.visible = _model.isMultiBitrate;
			
			if (_model.isOverlay) {
				_background.graphics.beginFill(_model.controlbarOverlayColor,0.6);
				_background.graphics.drawRect(3,_model.height-3-_model.controlbarHeight,_model.width -6 ,_model.controlbarHeight);
				_background.graphics.endFill();
				_container.x = 3;
				_container.y = _model.height-3-_model.controlbarHeight;
			} else {
				_container.x = 0;
				_container.y = _model.height - _model.controlbarHeight;

				if (_model.hasPlaylist && _model.playlistVisible) {
					_background.graphics.beginFill(_model.frameColor);
					_background.graphics.drawRect(0,_model.height-_model.controlbarHeight,_model.width - _model.playlistWidth -7,_model.controlbarHeight);
					_background.graphics.endFill();
				} else {
					_background.graphics.beginFill(_model.frameColor);
					_background.graphics.drawRect(0,_model.height-_model.controlbarHeight,_model.width ,_model.controlbarHeight);
					_background.graphics.endFill();
					
				}
				
			}
			var availableWidth:Number = _model.width - (_model.isOverlay ? 0:(_model.hasPlaylist && _model.playlistVisible) ? _model.playlistWidth+6:0) - 6;
			if (_model.isTrailer) {
				_fullscreenButton.x = availableWidth - 5 - _fullscreenButton.width;
			} else {
				// Nothing. Leave the fullscreen button where it is.
			}
			_shareButton.x = availableWidth - 5 - (_fullscreenButton.visible ? _fullscreenButton.width:0) - _shareButton.width ;
			_playlistButton.x = availableWidth - 5 - (_fullscreenButton.visible ? _fullscreenButton.width:0) - (_shareButton.visible ? _shareButton.width:0) - _playlistButton.width;
			_volumeControl.x  = availableWidth - 5 - (_fullscreenButton.visible ? _fullscreenButton.width:0) - (_shareButton.visible ? _shareButton.width:0) - (_playlistButton.visible ? _playlistButton.width + 5:0) -10 - _volumeControl.width;
			_HDmeter.x  = availableWidth - 5 - (_fullscreenButton.visible ? _fullscreenButton.width:0) - (_shareButton.visible ? _shareButton.width:0) - (_playlistButton.visible ? _playlistButton.width + 5:0) - _volumeControl.width -18 - _HDmeter.width;
			
			var _tempWidth:Number = _HDmeter.visible ? (_HDmeter.x - _scrubBar.x) : (_volumeControl.x - _scrubBar.x );
			_scrubBar.setWidth(_tempWidth);
				

		}
	}
}
