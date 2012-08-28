package com.limelight.osmf.chrome
{
	import com.limelight.osmf.data.DataResourceVO;
	import com.limelight.osmf.events.ControlBarEvent;
	
	import flash.display.DisplayObject;
	import flash.display.Sprite;
	import flash.display.StageDisplayState;
	import flash.events.Event;
	import flash.events.FullScreenEvent;
	import flash.events.IOErrorEvent;
	import flash.events.MouseEvent;
	import flash.events.TimerEvent;
	import flash.utils.Timer;
	
	import org.osmf.events.AudioEvent;
	import org.osmf.events.BufferEvent;
	import org.osmf.events.DisplayObjectEvent;
	import org.osmf.events.LoadEvent;
	import org.osmf.events.MediaPlayerCapabilityChangeEvent;
	import org.osmf.events.MediaPlayerStateChangeEvent;
	import org.osmf.events.TimeEvent;
	import org.osmf.media.MediaPlayer;
	
	public class ControlsChrome extends Sprite
	{
		private var dataResourceVO:DataResourceVO;
		
		private var mediaPlayer:MediaPlayer;
		
		private var controlBar:DisplayObject;
		
		private var cbTimer:Timer;
		
		private var fullScreen:Boolean = false;
		
		public function ControlsChrome()
		{
			this.addEventListener(Event.ADDED_TO_STAGE,setupControlbar);
		}
		
		private function setupControlbar(event:Event):void
		{
			dataResourceVO = DataResourceVO.getInstance();
			mediaPlayer = dataResourceVO.mediaPlayer;
			
			controlBar = new ControlBar();
			addChild(controlBar);
			
			addEventListeners();
			positionControlBar();

			cbTimer = new Timer(5000);
			cbTimer.addEventListener(TimerEvent.TIMER,onHideControls)
			cbTimer.start();
		}
		
		private function addEventListeners():void
		{
			// Add MediaPlayer event handlers.
			mediaPlayer.addEventListener(MediaPlayerStateChangeEvent.MEDIA_PLAYER_STATE_CHANGE, onStateChange);
			
			mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.HAS_AUDIO_CHANGE, onCapabilityChange);
			mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.CAN_BUFFER_CHANGE, onCapabilityChange);
			mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.CAN_LOAD_CHANGE, onCapabilityChange);
			mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.CAN_PLAY_CHANGE, onCapabilityChange);
			mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.CAN_SEEK_CHANGE, onCapabilityChange);
			mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.TEMPORAL_CHANGE, onCapabilityChange);

			mediaPlayer.addEventListener(TimeEvent.DURATION_CHANGE, onDurationChange);
			mediaPlayer.addEventListener(TimeEvent.CURRENT_TIME_CHANGE, onCurrentTimeChange);
			mediaPlayer.addEventListener(AudioEvent.MUTED_CHANGE, onMutedChange);
			mediaPlayer.addEventListener(AudioEvent.VOLUME_CHANGE, onVolumeChange);
			mediaPlayer.addEventListener(BufferEvent.BUFFERING_CHANGE, onBufferingChange);
			mediaPlayer.addEventListener(BufferEvent.BUFFER_TIME_CHANGE, onBufferTimeChange);
			mediaPlayer.addEventListener(LoadEvent.BYTES_TOTAL_CHANGE, onBytesTotalChange);
			mediaPlayer.addEventListener(LoadEvent.BYTES_LOADED_CHANGE, onBytesLoadedChange);
			mediaPlayer.addEventListener(DisplayObjectEvent.DISPLAY_OBJECT_CHANGE, onDisplayObjectChange);
			
			// Add UI event handlers.
			controlBar.addEventListener(ControlBarEvent.PLAY, onPlayClick);					//play button
			controlBar.addEventListener(ControlBarEvent.PAUSE, onPauseClick);				//pause button);
			controlBar.addEventListener(ControlBarEvent.FULLSCREEN, onFullscreenClick);		//fullscreen button);
			controlBar.addEventListener(ControlBarEvent.VOLUME, onVolume);					//volume slider
			controlBar.addEventListener(ControlBarEvent.MUTE, onToggleMuteClick);			//mute toggle
			controlBar.addEventListener(ControlBarEvent.SEEK, onSeek);						//seek slider
			
			stage.addEventListener(FullScreenEvent.FULL_SCREEN,onFullscreenChange);
			stage.addEventListener(MouseEvent.MOUSE_MOVE,onMouseMoveChange)
		}
		
		
		
		private function onLoadError(event:IOErrorEvent):void
		{
			trace(event);
		}
		
		private function positionControlBar():void
		{
			if ( controlBar && stage )
			{
				//trace(" controlBarMode = " + dataResourceVO.configuration.controlBarMode)
				switch(dataResourceVO.configuration.controlBarMode)
				{
					case "none":
						controlBar.visible = false;
						break;
					case "docked":
						controlBar.x = 0
						controlBar.y = (stage.stageHeight-Object(controlBar).barHeight)
						Object(controlBar).barWidth = stage.stageWidth;
						break;
					case "floating":
					default:
						controlBar.x = (stage.stageWidth-controlBar.width)/2
						controlBar.y = (stage.stageHeight*.65)
				}
			}
		}
		
		public function updateDisplay():void
		{
			//trace(".updateDisplay :: fullScreen = " + fullScreen)
			if (fullScreen)
			{	
				controlBar.scaleX = stage.stageWidth / stage.fullScreenWidth;;
				controlBar.scaleY = stage.stageHeight / stage.fullScreenHeight;
			}
			else
			{
				controlBar.scaleX = stage.stageWidth / dataResourceVO.resizeWidth;
				controlBar.scaleY = stage.stageHeight / dataResourceVO.resizeHeight;
			}
			positionControlBar();
		}
		
		// MediaPlayer event handlers.
		
		private function onStateChange(event:MediaPlayerStateChangeEvent):void
		{
			//trace(".onStateChange :: state = " + event.state)
			switch (event.state)
			{
				case "paused":
					Object(controlBar).paused();
					break;
				case "playing":
					Object(controlBar).playing();
					break
				default:
					// no action required
			}
		}
		
		private function onFullscreenChange(event:FullScreenEvent):void
		{
			//trace(".onFullscreenChange :: fullScreen = " + event.fullScreen)
			fullScreen = event.fullScreen;
			if (fullScreen)
			{
				Object(controlBar).enterFullscreen();
			}
			else
			{
				Object(controlBar).exitFullscreen();
			}
			updateDisplay();
		}
		
		private function onCapabilityChange(event:MediaPlayerCapabilityChangeEvent):void
		{
			//trace(".onCapabilityChange")
		}
		
		private function onDurationChange(event:TimeEvent):void
		{
			//trace(".onDurationChange :: time = " + event.time)
			Object(controlBar).updateDuration(event.time);
		}

		private function onCurrentTimeChange(event:TimeEvent):void
		{
			//trace(".onCurrentTimeChange :: time = " + event.time)
			Object(controlBar).updateCurrentTime(event.time);
		}
		
		private function onMutedChange(event:AudioEvent):void
		{
			//trace(".onMutedChange :: muted = " + event.muted)
			if (event.muted)
			{
				Object(controlBar).muteOn();
			}
			else
			{
				Object(controlBar).muteOff();
			}
		}
		
		private function onVolumeChange(event:AudioEvent):void
		{
			//trace(".onVolumeChange :: volume = " + event.volume)
			Object(controlBar).updateVolume(event.volume);
		}
		
		private function onBufferingChange(event:BufferEvent):void
		{
			//trace(".onBufferingChange")
		}

		private function onBufferTimeChange(event:BufferEvent):void
		{
			//trace(".onBufferTimeChange")
		}
		
		private function onBytesTotalChange(event:LoadEvent):void
		{
			//trace(".onBytesTotalChange :: bytes = " + event.bytes)
			Object(controlBar).updateBytesTotal(event.bytes);
		}
		
		private function onBytesLoadedChange(event:LoadEvent):void
		{
			//trace(".onBytesLoadedChange :: bytes = " + event.bytes)
			Object(controlBar).updateBytesLoaded(event.bytes);
		}
		
		private function onDisplayObjectChange(event:DisplayObjectEvent):void
		{
			//trace(".onDisplayObjectChange")
		}
		
		private function onMouseMoveChange(evnt:MouseEvent):void
		{
			//trace(".onMouseMoveChange");
			if (controlBar)
			{
				Object(controlBar).showControls();
				cbTimer.reset();
				cbTimer.start();
			}
		}
		
		private function onHideControls(event:TimerEvent):void
		{
			Object(controlBar).hideControls();
			cbTimer.stop()
		}
		
		// UI Event Handlers
		
		private function onPlayClick(event:ControlBarEvent):void
		{
			//trace(".onPlayClick")
			if (mediaPlayer.canPlay) mediaPlayer.play();
		}
		
		private function onPauseClick(event:ControlBarEvent):void
		{
			//trace(".onPauseClick")
			if (mediaPlayer.canPause) mediaPlayer.pause();
		}
		
		private function onFullscreenClick(event:ControlBarEvent):void
		{
			//trace(".onFullscreenClick :: event = " + event)
			//trace(".onFullscreenClick :: value = " + event.value)
			switch(event.value)
			{
				case "enter":
					trace(" Enter Fullscreen")
					stage.displayState = StageDisplayState.FULL_SCREEN;
					break;
				case "exit":
					trace(" Exit Fullscreen")
					stage.displayState = StageDisplayState.NORMAL;
					break;
				default:
					// no action required
			}
		}
		
		private function onVolume(event:ControlBarEvent):void
		{
			//trace(".onVolume")
			mediaPlayer.volume = Number(event.value);
		}
		
		private function onToggleMuteClick(event:ControlBarEvent):void
		{
			//trace(".onToggleMuteClick")
			mediaPlayer.muted = event.value;
		}
		
		private function onSeek(event:ControlBarEvent):void
		{
			//trace(".onSeek")
			if (mediaPlayer.canSeek)
			{
				mediaPlayer.seek(Number(event.value));
			}	
		}

	}
}