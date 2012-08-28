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
package org.openvideoplayer.samples.presenter
{
    import com.gskinner.motion.GTween;
    import com.gskinner.motion.easing.Sine;
    import com.zeusprod.Log;
    
    import flash.display.StageDisplayState;
    import flash.events.EventDispatcher;
    import flash.events.FullScreenEvent;
    import flash.events.TimerEvent;
    import flash.geom.Point;
    import flash.geom.Rectangle;
    import flash.net.SharedObject;
    import flash.system.Capabilities;
    import flash.utils.Timer;
    
    import org.openvideoplayer.components.ui.controlbar.ControlBar;
    import org.openvideoplayer.components.ui.controlbar.view.DualTimeCodeDisplay;
    import org.openvideoplayer.components.ui.controlbar.view.FullscreenButton;
    import org.openvideoplayer.components.ui.controlbar.view.PlayPauseButton;
    import org.openvideoplayer.components.ui.controlbar.view.ScrubBar;
    import org.openvideoplayer.components.ui.controlbar.view.VolumeControl;
    import org.openvideoplayer.components.ui.posterframe.PosterFramePlayButton;
    import org.openvideoplayer.components.ui.shared.ControlType;
    import org.openvideoplayer.components.ui.shared.event.ControlEvent;
    import org.openvideoplayer.components.ui.shared.view.base.BaseComponent;
    import org.openvideoplayer.events.OvpEvent;
    import org.openvideoplayer.net.OvpConnection;
    import org.openvideoplayer.net.OvpNetStream;
    import org.openvideoplayer.net.dynamicstream.DynamicStreamItem;
    import org.openvideoplayer.samples.view.BaseVideoView;
    import org.openvideoplayer.utils.Utils;

	/**
	 * 
	 * This class holds all the logic and listeners and handlers to drive the view.  
	 * This class is like a mediator class for the view
	 *
	 */
	
    public class BaseControlBarPresenter extends  EventDispatcher
    {
        private static const DEFAULT_BUFFER_SPINNER_LABEL:String = "Buffering";
		
        protected var netStream:OvpNetStream;
        protected var netConnection:OvpConnection
        protected var view:BaseVideoView;
		protected var posterFrameButton:PosterFramePlayButton;
		
        protected var controlBar:ControlBar;
        protected var playPauseButton:PlayPauseButton;
        protected var scrubBar:ScrubBar;
        protected var dualTimeCodeDisplay:DualTimeCodeDisplay;
        protected var volumeControl:VolumeControl;
        protected var fullscreenButton:FullscreenButton;
		
        protected var streamLength:Number = 0;
		protected var dynamicStreamItem:DynamicStreamItem;
		protected var sharedObject:SharedObject;
		protected var exclusionList:Vector.<BaseComponent>
		
        private var _filename:String
		
        private var scrubTimer:Timer = new Timer(10);        

        
        /**
         *
         * @param controlBar
         * @param netStream
         * @param media
         * @param view
         *
         */
        public function BaseControlBarPresenter(view:BaseVideoView, netStream:OvpNetStream, netConnection:OvpConnection, filename:String)
        {
            this.view = view;
            this.controlBar = view.controlBar;
			this.posterFrameButton = view.posterFrameButton;
            this.netStream = netStream;
            this.netConnection = netConnection;
			view.stage.addEventListener(FullScreenEvent.FULL_SCREEN, onStageFullScreen);
			Utils.console = view.console;			
            _filename = filename;
			sharedObject = SharedObject.getLocal("AkamaiControlBarProperties");
			setControlReferences();            
			toggleLockedControls(true)
            addNetStreamLiseners();
            addControlBarListeners();
            setScrubTimer();
            initVolume();
        }
		
		public function destroy():void 
		{
			netStream.removeEventListener(OvpEvent.COMPLETE, onComplete);
			controlBar.removeEventListener(ControlEvent.PLAY, onPlayClick);
			controlBar.removeEventListener(ControlEvent.PAUSE, onPauseClick);
			controlBar.removeEventListener(ControlEvent.SEEK_BEGIN, onSeekBegin);
			controlBar.removeEventListener(ControlEvent.SEEK_COMPLETE, onSeekComplete);
			controlBar.removeEventListener(ControlEvent.VOLUME_CHANGE, onVolumeChange);
			controlBar.removeEventListener(ControlEvent.FULLSCREEN, onFullscreenClick);
			controlBar.removeEventListener(ControlEvent.NORMALSCREEN, onFullscreenClick);
			scrubTimer.removeEventListener(TimerEvent.TIMER, onTick);
			scrubTimer.stop()
			scrubTimer = null;
			controlBar = null;
			netStream = null;
			netConnection = null;
			_filename = null;
			view = null;			
		}
		
		/**
		 * 
		 * @return 
		 * 
		 */		
		public function get filename():String
		{
			return _filename;
		}
		
		/**
		 * 
		 * @param value
		 * 
		 */		
		public function set filename(value:String):void
		{
			_filename = value;
		}
		
		protected function onStageFullScreen(event:FullScreenEvent):void
		{
			if (view.stage.displayState == StageDisplayState.NORMAL)
			{
				view.exitFullscreen();
				fullscreenButton.currentState = ControlEvent.FULLSCREEN;
			}
		}
		
		protected function setControlReferences():void
		{
			playPauseButton = controlBar.getControl(ControlType.PLAY_PAUSE_BUTTON) as PlayPauseButton;
			scrubBar = controlBar.getControl(ControlType.SCRUB_BAR) as ScrubBar;
			dualTimeCodeDisplay = controlBar.getControl(ControlType.CURRENT_AND_DURATION_TIME_DISPLAY) as DualTimeCodeDisplay;
			volumeControl = controlBar.getControl(ControlType.VOLUME_CONTROL) as VolumeControl;
			fullscreenButton = controlBar.getControl(ControlType.FULLSCREEN_BUTTON) as FullscreenButton;
		}
        
        protected function addNetStreamLiseners():void
        {			
            netStream.addEventListener(OvpEvent.COMPLETE, onComplete);
        }
		
		protected function addControlBarListeners():void
        {
            Log.traceMsg ("BaseControlBarPresenter addControlBarListener", Log.LOG_TO_CONSOLE);
            controlBar.addEventListener(ControlEvent.PLAY, onPlayClick);
            controlBar.addEventListener(ControlEvent.PAUSE, onPauseClick);
            controlBar.addEventListener(ControlEvent.SEEK_BEGIN, onSeekBegin);
            controlBar.addEventListener(ControlEvent.SEEK_COMPLETE, onSeekComplete);
            controlBar.addEventListener(ControlEvent.VOLUME_CHANGE, onVolumeChange);
            controlBar.addEventListener(ControlEvent.FULLSCREEN, onFullscreenClick);
			if(posterFrameButton)
			{
				posterFrameButton.addEventListener(ControlEvent.PLAY, onPosterFrameClick);				
			}
			onPosterFrameClick();
        }

		private function onPosterFrameClick(event:ControlEvent  = null):void
		{			
			Log.traceMsg ("BaseControlBarPresenter onPosterFrameClick", Log.LOG_TO_CONSOLE);
            onPlayClick();
			playPauseButton.currentState = ControlEvent.PLAY;
		}
        
		protected function initVolume():void
        {
            netStream.volume = (sharedObject.data.volume) ? sharedObject.data.volume : .5;
			if(volumeControl)
			{
            	volumeControl.setThumbPosition(netStream.volume, 1);
			}
        }
        
		protected function setScrubTimer():void
        {
            scrubTimer.addEventListener(TimerEvent.TIMER, onTick);
            scrubTimer.start();
        }
        
		protected function onTick(event:TimerEvent):void
        {
			if(scrubBar)
			{
            	scrubBar.setThumbPosition(netStream.time, this.streamLength);
			}
			setTimeDisplay();
			//setStatsPanel();
        }
		
		protected function setTimeDisplay():void
		{
			var currentTime:String = Utils.convertToTimeCode(scrubBar.getThumbPosition(streamLength));
			if(dualTimeCodeDisplay)
			{
				dualTimeCodeDisplay.setTimeCodeText(currentTime, netConnection.streamLengthAsTimeCode(streamLength));
			}
		}
        
		protected function onComplete(event:OvpEvent):void
        {
			onPauseClick();
			netStream.seek(0);
			if(!netStream.isProgressive)
			{
				netStream.close();
			}
			if(view.stage.displayState == StageDisplayState.FULL_SCREEN)
			{
				view.stage.displayState = StageDisplayState.NORMAL;
				onStageFullScreen(null);
			}
			if(view.spinnerView.visible)
			{
				onBufferFull();
			}
			
			scrubBar.setThumbPosition(0, streamLength);		
			setTimeDisplay();
			setPosterFrameButtonVisible(true);
			playPauseButton.currentState = ControlEvent.PAUSE;
			toggleLockedControls(true);
        }
		
		protected function createExclusionFromLockList():void
		{
			exclusionList = new Vector.<BaseComponent>
			exclusionList.push(playPauseButton);
			exclusionList.push(volumeControl);
		}
		
		protected function toggleLockedControls(lockControls:Boolean):void
		{
			return;
			/*
            for each(var control:BaseComponent in controlBar.controlBarControlsList )
			{
				if (!checkExcludedFromLocking(control))
				{
					control.enabled = !lockControls;
				}
			}
			*/
		}
		
		protected function checkExcludedFromLocking(value:BaseComponent):Boolean
		{
			var result:Boolean
			if (exclusionList ==  null)
			{
				createExclusionFromLockList();
			}
			
			for each(var control:BaseComponent in exclusionList)
			{
				if (value == control)
				{
					result = true;
				}
			}
			return result;
		}
		
		protected function onFullscreenClick(event:ControlEvent):void
        {    
			if (view.stage.displayState == StageDisplayState.NORMAL)
			{
				var w:Number;
				var h:Number;
				
				view.enterFullscreen();
				
				if (Capabilities.screenResolutionX/ Capabilities.screenResolutionY> view.video.videoWidth / view.video.videoHeight)
				{
					h = Capabilities.screenResolutionY;
					w = view.video.videoWidth * h / view.video.videoHeight;
				}
				else
				{
					w = Capabilities.screenResolutionX;
					h = view.video.videoHeight * w / view.video.videoWidth;
				} 
				
				view.video.width = Math.max(w, view.video.videoWidth);
				view.video.height = Math.max(h, view.video.videoHeight);
				view.stage.fullScreenSourceRect = new Rectangle(view.video.x, view.video.y, w, h);
	            view.stage.displayState = StageDisplayState.FULL_SCREEN;
			}
        }
        
		protected function onVolumeChange(event:ControlEvent):void
        {
            netStream.volume = event.data.value;
			sharedObject.data.volume = event.data.value;
        }
        
		protected function onSeekBegin(event:ControlEvent):void
        {
            scrubTimer.stop();
            if (playPauseButton.currentState == ControlEvent.PLAY)
            {
                netStream.pause();
            }
        }
		
		protected function onSeekComplete(event:ControlEvent):void
        {
            if (playPauseButton.currentState == ControlEvent.PLAY)
            {
                scrubTimer.start();
                netStream.resume();
            }
        }
        
		protected function onPlayClick(event:ControlEvent = null, clipStartTime:Number = NaN, clipDuration:Number = NaN):void
        {
            Log.traceMsg ("BaseControlBarPresenter onPlayClick", Log.LOG_TO_CONSOLE);
            if (netStream.time == 0)
            {
				netStream.play(_filename)
            }
            else
            {
                netStream.togglePause();
            }
			onPlayClickHelper();
        }
		
		protected function hidePosterFrameButton():void
		{
			if(posterFrameButton && posterFrameButton.visible)
			{
				posterFrameButton.visible = false;
			}
		}
		
		protected function setPosterFrameButtonVisible(value:Boolean):void
		{
			if(posterFrameButton)
			{
				posterFrameButton.visible = value;
			}
		}
		
		protected function onPauseClick(event:ControlEvent = null):void
        {
            netStream.togglePause();
            if (scrubTimer.running)
            {
                scrubTimer.stop();
            }
        }
		
		protected function onBufferStart(label:String = DEFAULT_BUFFER_SPINNER_LABEL):void
		{	
			with(view.spinnerView)
			{
				alpha = 
					scaleX = 
					scaleY = 1;
				visible = true;
				textfield.text = label;
			}
		}
		
		protected function onBufferFull():void
		{
			var scaleValue:Number = 1.3;
			var tween:GTween = new GTween(view.spinnerView, .5);			
			tween.setValues({ alpha:0, scaleX:scaleValue, scaleY:scaleValue});
			tween.ease = Sine.easeOut;
			tween.onComplete = onSpinnerFadeOut;			
			view.spinnerView.textfield.text = "";
		}
		
		protected function onSpinnerFadeOut(tween:GTween):void
		{
			tween.target.visible = false;
		}
		
		protected function onPlayClickHelper():void
		{
			if (!scrubTimer.running)
			{
				scrubTimer.start();
			}
			hidePosterFrameButton();
			toggleLockedControls(false)
		}

		private function autoStartPlayer():void
		{
			onPosterFrameClick();
		}
		
		private function setStatsPanel():void
		{
		  /*
          if (netStream != null) {
            var maxBandwidth:Number = Math.round(netStream.info.maxBytesPerSecond * 8 / 1024);		
			view.console.bandwidthPanel.text = maxBandwidth == 0 ? "Calculating" : isNaN(maxBandwidth) ? "Unavaliable" : maxBandwidth +" kbps";
			view.console.streamPanel.text = Math.round(netStream.info.playbackBytesPerSecond * 8 / 1024) +" kbps";
			view.console.bufferPanel.text = Math.round(netStream.bufferLength) + " sec";
		  }
		  */
        }

    }
}
