package com.limelight.osmf.chrome
{
	import com.limelight.osmf.data.DataResourceVO;
	import com.limelight.osmf.events.ControlBarEvent;
	
	import flash.display.Sprite;
	import flash.events.Event;
	import flash.events.FullScreenEvent;
	import flash.events.IOErrorEvent;
	
	import org.osmf.events.MediaPlayerStateChangeEvent;
	import org.osmf.media.MediaPlayer;
	
	public class PlayOverlayChrome extends Sprite
	{
		private var dataResourceVO:DataResourceVO;
		
		private var mediaPlayer:MediaPlayer;
		
		private var playOverlay:PlayOverlay;
		
		private var fullScreen:Boolean = false;
		
		public function PlayOverlayChrome()
		{
			this.addEventListener(Event.ADDED_TO_STAGE,setupPlayOverlay)
		}
		
		private function setupPlayOverlay(event:Event):void
		{
			dataResourceVO = DataResourceVO.getInstance();
			mediaPlayer = dataResourceVO.mediaPlayer;
			
			playOverlay = new PlayOverlay();
			addChild(playOverlay);
			
			addEventListeners();
			positionPlayOverlay();

		}
		
		private function addEventListeners():void
		{
			// Add MediaPlayer event handlers.
			mediaPlayer.addEventListener(MediaPlayerStateChangeEvent.MEDIA_PLAYER_STATE_CHANGE, onStateChange);
			
			// Add UI event handlers.
			playOverlay.addEventListener(ControlBarEvent.PLAY, onPlayClick);				//play button
			
			stage.addEventListener(FullScreenEvent.FULL_SCREEN,onFullscreenChange);
		}
		

		private function onLoadError(event:IOErrorEvent):void
		{
			trace(event);
		}
		
		private function positionPlayOverlay():void
		{
			if ( playOverlay && stage )
			{ 
				playOverlay.x = (stage.stageWidth-playOverlay.width)/2
				playOverlay.y = (stage.stageHeight-playOverlay.height)/2
			}
		}
		
		public function updateDisplay():void
		{
			trace(".updateDisplay")
			if (fullScreen)
			{
				playOverlay.scaleX = stage.stageWidth / stage.fullScreenWidth;;
				playOverlay.scaleY = stage.stageHeight / stage.fullScreenHeight;
			}
			else
			{
				playOverlay.scaleX = stage.stageWidth / dataResourceVO.resizeWidth;
				playOverlay.scaleY = stage.stageHeight / dataResourceVO.resizeHeight;
			}
			positionPlayOverlay();
		}
		
		// MediaPlayer event handlers.
		
		private function onStateChange(event:MediaPlayerStateChangeEvent):void
		{
			//trace("PlayOverlayChrome.onStateChange :: state = " + event.state)
			switch (event.state)
			{
				case "paused":
					Object(playOverlay).paused();
					break;
				case "playing":
					Object(playOverlay).playing();
					break
				default:
					// no action required
			}
		}
		
		private function onFullscreenChange(event:FullScreenEvent):void
		{
			//trace("PlayOverlayChrome.onFullscreenChange :: fullScreen = " + event.fullScreen)
			fullScreen = event.fullScreen;
			updateDisplay();
		}
		
		// UI Event Handlers
		
		private function onPlayClick(event:ControlBarEvent):void
		{
			//trace("PlayOverlayChrome.onPlayClick")
			mediaPlayer.play();
		}
		
		
	}
}