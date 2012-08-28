package com.limelight.osmf.chrome
{

	import com.limelight.osmf.data.DataResourceVO;
	
	import flash.display.Sprite;
	import flash.events.Event;
	import flash.events.FullScreenEvent;
	import flash.events.IOErrorEvent;
	import flash.events.KeyboardEvent;
	
	import org.osmf.events.AudioEvent;
	import org.osmf.events.BufferEvent;
	import org.osmf.events.DRMEvent;
	import org.osmf.events.DisplayObjectEvent;
	import org.osmf.events.DynamicStreamEvent;
	import org.osmf.events.LoadEvent;
	import org.osmf.events.PlayEvent;
	import org.osmf.events.SeekEvent;
	import org.osmf.events.TimeEvent;
	import org.osmf.media.MediaElement;
	import org.osmf.traits.TraitEventDispatcher;
	
	public class DebugChrome extends Sprite
	{
		private var dataResourceVO:DataResourceVO;
		
		private var debugPanel:DebugPanel;
		
		private var fullScreen:Boolean = false;
		
		public var tracing:Boolean = false;
		
		public function DebugChrome()
		{
			this.mouseEnabled = false;
			
			this.addEventListener(Event.ADDED_TO_STAGE,setupDebugPanel)
		}
		
		private function setupDebugPanel(event:Event):void
		{
			dataResourceVO = DataResourceVO.getInstance();
			
			debugPanel = new DebugPanel();
			addChild(debugPanel);
			
			stage.addEventListener(KeyboardEvent.KEY_DOWN, handleKeyDown);
			stage.addEventListener(FullScreenEvent.FULL_SCREEN,onFullscreenChange);
			
			positionDebugPanel();
		}
		
		private function onLoadError(event:IOErrorEvent):void
		{
			trace(event);
		}
		
		private function positionDebugPanel():void
		{
			if ( debugPanel && stage)
			{
				debugPanel.x = (stage.stageWidth-debugPanel.width)/2
				debugPanel.y = (stage.stageHeight-debugPanel.height)/2
			}
		}
		
		public function updateDisplay():void
		{
			//trace(".updateDisplay")
			if (fullScreen)
			{
				debugPanel.scaleX = stage.stageWidth / stage.fullScreenWidth;;
				debugPanel.scaleY = stage.stageHeight / stage.fullScreenHeight;
			}
			else
			{
				debugPanel.scaleX = stage.stageWidth / dataResourceVO.resizeWidth;
				debugPanel.scaleY = stage.stageHeight / dataResourceVO.resizeHeight;
			}
			positionDebugPanel();
		}
		
		private function onFullscreenChange(event:FullScreenEvent):void
		{
			//trace(".onFullscreenChange :: fullScreen = " + event.fullScreen)
			fullScreen = event.fullScreen;
			updateDisplay();
		}
		
		public function debug(message:String):void
		{
			if (debugPanel)
			{
				Object(debugPanel).debug(message);
			}
			if (tracing)
			{
				trace(message);
			}
		}
		
		private function handleKeyDown(event:KeyboardEvent):void 
		{
			if(event.ctrlKey && event.shiftKey && (event.keyCode == 39 || event.keyCode == 38))
			{
				Object(debugPanel).showPanel();
			}
			if(event.ctrlKey && event.shiftKey && (event.keyCode == 37 || event.keyCode == 40))
			{
				Object(debugPanel).hidePanel();
			}
		}
		
		public function set media(media:MediaElement):void
		{
        	
        	var dispatcher:TraitEventDispatcher = new TraitEventDispatcher();
        	dispatcher.media = media;
    
        	dispatcher.addEventListener(AudioEvent.MUTED_CHANGE, onMutedChange); 
			dispatcher.addEventListener(AudioEvent.PAN_CHANGE, onPanChange);
			dispatcher.addEventListener(AudioEvent.VOLUME_CHANGE, onVolumeChange);
			dispatcher.addEventListener(BufferEvent.BUFFER_TIME_CHANGE, onBufferTimeChange); 
			dispatcher.addEventListener(BufferEvent.BUFFERING_CHANGE, onBufferingChange);
			dispatcher.addEventListener(DisplayObjectEvent.DISPLAY_OBJECT_CHANGE, onDisplayObjectChange); 
			dispatcher.addEventListener(DisplayObjectEvent.MEDIA_SIZE_CHANGE, onMediaSizeChange); 
			dispatcher.addEventListener(DRMEvent.DRM_STATE_CHANGE, onDRMStateChange);
			dispatcher.addEventListener(DynamicStreamEvent.AUTO_SWITCH_CHANGE, onAutoSwitchChange);
			dispatcher.addEventListener(DynamicStreamEvent.NUM_DYNAMIC_STREAMS_CHANGE, onNumDynamicStreamsChange); 
			dispatcher.addEventListener(DynamicStreamEvent.SWITCHING_CHANGE, onSwitchingChange); 
			dispatcher.addEventListener(LoadEvent.BYTES_TOTAL_CHANGE, onBytesTotalChange);
			dispatcher.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onLoadStateChange);  
			dispatcher.addEventListener(PlayEvent.CAN_PAUSE_CHANGE, onCanPauseChange);
			dispatcher.addEventListener(PlayEvent.PLAY_STATE_CHANGE, onPlayStateChange); 
			dispatcher.addEventListener(SeekEvent.SEEKING_CHANGE, onSeekingChange); 
			dispatcher.addEventListener(TimeEvent.COMPLETE, onComplete);
			dispatcher.addEventListener(TimeEvent.DURATION_CHANGE, onDurationChange);
			
        }
        
        private function onPlayStateChange(event:PlayEvent):void
        {
        	debug("playStateChange: " + event.playState);
        }
		
		private function onAutoSwitchChange(event:DynamicStreamEvent):void
		{
			debug("autoSwitchChange: " + event.autoSwitch);
		}
		
		private function onBufferingChange(event:BufferEvent):void
		{
			debug("bufferingChange: " + event.buffering);
		}
		
		private function onBufferTimeChange(event:BufferEvent):void
		{
			debug("bufferTimeChange: " + event.bufferTime);
		}
		
		private function onComplete(event:TimeEvent):void
		{
			debug("COMPLETE");
		}
		
		private function onCanPauseChange(event:PlayEvent):void
		{
			debug("canPauseChange: " + event.canPause);
		}
		
		private function onDisplayObjectChange(event:DisplayObjectEvent):void
		{
			debug("displayObjectChange");
		}
		
		private function onDurationChange(event:TimeEvent):void
		{
			debug("durationChange: " + event.time);
		}
		
		private function onLoadStateChange(event:LoadEvent):void
		{
			debug("loadStateChange: " + event.loadState);
		}
		
		private function onBytesTotalChange(event:LoadEvent):void
		{
			debug("bytesTotalChange: " + event.bytes);
		}	
		
		private function onMediaSizeChange(event:DisplayObjectEvent):void
		{
			debug("mediaSizeChange width:" + event.newWidth + " height:" + event.newHeight );
		}
		
		private function onMutedChange(event:AudioEvent):void
		{
			debug("mutedChange: " + event.muted);
		}
		
		private function onNumDynamicStreamsChange(event:DynamicStreamEvent):void
		{
			debug("numDynamicStreamsChange");
		}
		
		private function onPanChange(event:AudioEvent):void
		{
			debug("panChange: " + event.pan);
		}
		
		private function onSeekingChange(event:SeekEvent):void
		{
			debug("seekingChange seeking:" + event.seeking + "time:" + event.time);
		}
		
		private function onSwitchingChange(event:DynamicStreamEvent):void
		{
			debug("switchingChange: " + event.switching);
		}
		
		private function onVolumeChange(event:AudioEvent):void
		{
			debug("volumeChange: " + event.volume);
		}
		
		private function onDRMStateChange(event:DRMEvent):void
		{
			debug("drmStateChange: " + event.drmState);
		}

	}
}