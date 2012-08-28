
package  
{

	import flash.display.MovieClip;
	import flash.display.SimpleButton;
	import flash.display.Sprite;
	import flash.events.Event;
	import flash.events.EventDispatcher;
	import flash.events.MouseEvent;
	import flash.events.TimerEvent;
	import flash.geom.Rectangle;
	import flash.utils.Timer;
	import flash.display.Stage;
	
	import fl.transitions.Tween;
	import fl.transitions.easing.*;
	
	import com.limelight.osmf.events.ControlBarEvent
	
	public class ControlBar extends MovieClip
	{
		private var _controlBarVisible:Boolean = true;
		
		private var _duration:Number = 0;
		private var _currentTime:Number = 0;
		private var _bytesTotal:Number = 0;
		private var _bytesLoaded:Number = 0;
		
		private var _dragTimer:Timer;
		private var _seekDrag:Boolean = false;
		private var _volDrag:Boolean = false;
		
		private var _hsTimer:Timer;
		
		public function ControlBar() 
		{
			//trace("ControlBar");
			
			_dragTimer = new Timer(500);
			_hsTimer = new Timer(3000);
			
			playButton.visible = false;
			volumeSlider.visible = false;
			fullscreenExitButton.visible = false;
			muteOnButton.visible = false;
			
			playButton.addEventListener(MouseEvent.CLICK, onPlayClickHandler);
			pauseButton.addEventListener(MouseEvent.CLICK, onPauseClickHandler);
			
			fullscreenEnterButton.addEventListener(MouseEvent.CLICK, onFullscreenEnterClickHandler);
			fullscreenExitButton.addEventListener(MouseEvent.CLICK, onFullscreenExitClickHandler);
			
			seekSlider.trackButton.addEventListener(MouseEvent.CLICK, onSeekClickHandler);
			seekSlider.seekHead.seekHeadButton.addEventListener(MouseEvent.MOUSE_DOWN, startSeekDrag);

			timeDisplay.selectable = false;
			
			muteOnButton.addEventListener(MouseEvent.CLICK, onMuteOnClickHandler);
			muteOffButton.addEventListener(MouseEvent.CLICK, onMuteOffClickHandler);
			
			volumeHotspot.addEventListener(MouseEvent.ROLL_OVER, onHotspotOverHandler);
			volumeSlider.trackButton.addEventListener(MouseEvent.CLICK, onVolumeClickHandler);
			volumeSlider.volumeHead.volumeHeadButton.addEventListener(MouseEvent.MOUSE_DOWN, startVolumeDrag);
			volumeSlider.volBar.addEventListener(Event.ENTER_FRAME, updateVolumeBar);
			
		}
		
		private function onPlayClickHandler(event:MouseEvent):void
		{
			//trace(".onPlayClickHandler");
			dispatchEvent(new ControlBarEvent(ControlBarEvent.PLAY));
		}
		
		private function onPauseClickHandler(event:MouseEvent):void
		{
			//trace(".onPauseClickHandler");
			dispatchEvent(new ControlBarEvent(ControlBarEvent.PAUSE));
		}
		
		private function onFullscreenEnterClickHandler(event:MouseEvent):void
		{
			//trace(".onFullscreenEnterClickHandler");
			dispatchEvent(new ControlBarEvent(ControlBarEvent.FULLSCREEN,"enter"));
		}
		
		private function onFullscreenExitClickHandler(event:MouseEvent):void
		{
			//trace(".onFullscreenExitClickHandler");
			dispatchEvent(new ControlBarEvent(ControlBarEvent.FULLSCREEN,"exit"));
		}
		
		private function onSeekClickHandler(event:MouseEvent):void
		{
			//trace(".onSeekClickHandler");
			var percentageSeek = event.target.mouseX / seekSlider.trackBar.width;
			var seekTime = _duration * percentageSeek;
			dispatchEvent(new ControlBarEvent(ControlBarEvent.SEEK,seekTime));
		}
		
		private function onSeekDragHandler(event:TimerEvent=null):void
		{
			//trace(".onSeekDragHandler");
			var percentageSeek = seekSlider.seekHead.x / seekSlider.trackBar.width;
			var seekTime = _duration * percentageSeek;
			dispatchEvent(new ControlBarEvent(ControlBarEvent.SEEK,seekTime));
		}
		
		private function onStageMouseUpHandler(event:MouseEvent):void
		{
			//trace(".onStageMouseUpHandler")
			if (_volDrag)
			{
				stopVolumeDrag();
			}
			if (_seekDrag)
			{
				stopSeekDrag();
			}
		}
		
		private function onMuteOnClickHandler(event:MouseEvent):void
		{
			//trace("onMuteOnClickHandler");
			dispatchEvent(new ControlBarEvent(ControlBarEvent.MUTE,false));
		}
		
		private function onMuteOffClickHandler(event:MouseEvent):void
		{
			//trace("onMuteOffClickHandler");
			dispatchEvent(new ControlBarEvent(ControlBarEvent.MUTE,true));
		}
		
		private function onVolumeClickHandler(event:MouseEvent):void
		{
			//trace("onVolumeClickHandler");
			var seekVolume = event.target.mouseX / volumeSlider.trackBar.height;
			//trace(" seekVolume = " + seekVolume);
			dispatchEvent(new ControlBarEvent(ControlBarEvent.VOLUME,seekVolume));
		}
		
		private function onVolumeDragHandler(event:TimerEvent=null):void
		{
			//trace("onVolumeDragHandler");
			var seekVolume = -(volumeSlider.volumeHead.y) / volumeSlider.trackBar.height;
			//trace(" seekVolume = " + seekVolume);
			dispatchEvent(new ControlBarEvent(ControlBarEvent.VOLUME,seekVolume));
		}
		
		
		// public methods for updating ui based on player states
		
		public function showControls():void
		{
			//trace("showControls");
			if (!_controlBarVisible)
			{
				var showTween:Tween = new Tween(this, "alpha", Strong.easeOut, 0, 1, 3, true);
				_controlBarVisible = true;
			}
		}
		
		public function hideControls():void
		{
			//trace("hideControls");
			if (_controlBarVisible)
			{
				var hideTween:Tween = new Tween(this, "alpha", Strong.easeOut, 1, 0, 3, true);
				_controlBarVisible = false;
			}
		}
		
		public function paused():void
		{
			//trace("paused");
			playButton.visible = true;
			pauseButton.visible = false;
		}
		
		public function playing():void
		{
			//trace("playing");
			playButton.visible = false;
			pauseButton.visible = true;
		}
		
		public function enterFullscreen():void
		{
			//trace("enterFullscreen");
			fullscreenEnterButton.visible = false;
			fullscreenExitButton.visible = true;
		}
		
		public function exitFullscreen():void
		{
			//trace("exitFullscreen");
			fullscreenEnterButton.visible = true;
			fullscreenExitButton.visible = false;
		}
		
		public function muteOn():void
		{
			//trace("muteOn");
			muteOnButton.visible = true;
			muteOffButton.visible = false;
		}
		
		public function muteOff():void
		{
			//trace("muteOff");
			muteOnButton.visible = false;
			muteOffButton.visible = true;
		}
		
		public function updateDuration(val:Number):void
		{
			//trace(".updateDuration :: time = " + val)
			_duration = val;
			updateSeekBarPlayed();
			updateTimeDisplay();
		}
		
		public function updateCurrentTime(val:Number):void
		{
			//trace(".updateCurrentTime :: time = " + val)
			_currentTime = val;
			updateSeekBarPlayed();
			updateTimeDisplay();
		}
		
		public function updateBytesTotal(val:Number):void
		{
			//trace(".updateBytesTotal :: bytes = " + val)
			_bytesTotal = val;
			updateSeekBarLaoded();
		}
		
		public function updateBytesLoaded(val:Number):void
		{
			//trace(".updateBytesLoaded :: bytes = " + val)
			_bytesLoaded = val;
			updateSeekBarLaoded();
		}
		
		public function updateVolume(val:Number):void
		{
			//trace(".updateVolume :: volume = " + val);
			if (!_volDrag)
			{
				var position:Number = volumeSlider.trackBar.height * val
				//trace(" position = " + position);
				volumeSlider.volumeHead.y = -(position);
			}
		}
		
		public function set barWidth(w:Number):void
		{
			controlBar.width = w;
		}
		public function get barWidth():Number
		{
			return controlBar.width
		}
		
		public function set barHeight(h:Number):void
		{
			controlBar.height = h;
		}
		public function get barHeight():Number
		{
			return controlBar.height
		}
		
		// private methods for formating ui updates
		
		private function updateTimeDisplay():void
		{
			timeDisplay.text = formatTime(_currentTime) + " | " + formatTime(_duration)
		}
		
		private function formatTime(seconds:Number):String
		{
			seconds = Math.floor(isNaN(seconds) ? 0 : Math.max(0, seconds));
			return Math.floor(seconds / 3600) 
				 + ":"
				 + (seconds % 3600 < 600 ? "0" : "")
				 + Math.floor(seconds % 3600 / 60)
				 + ":"
				 + (seconds % 60 < 10 ? "0" : "") + seconds % 60;
		}
		
		private function updateSeekBarPlayed():void
		{
			//trace(".updateSeekBarPlayed")
			if (!_seekDrag)
			{
				var percentagePlayed:Number = _currentTime / _duration;
				var played:Number = seekSlider.trackBar.width * percentagePlayed;
				played = (played>=0)? played : 0;
				seekSlider.seekHead.x = played;
				seekSlider.playedBar.width = played;
			}
		}
		
		private function updateSeekBarLaoded():void
		{
			//trace(".updateSeekBarLaoded")
			var percentageLoaded = _bytesLoaded / _bytesTotal;
			var loaeded:Number = seekSlider.trackBar.width * percentageLoaded;
			loaeded = (loaeded>=0)? loaeded : 0;
			seekSlider.loadedBar.width = loaeded;
		}
		
		private function onHotspotOverHandler(event:MouseEvent):void
		{
			//trace("onHotspotOverHandler");
			volumeSlider.visible = true;
			_hsTimer.addEventListener(TimerEvent.TIMER,onHotspotOutHandler);
			_hsTimer.start();
		}
		
		private function onHotspotOutHandler(event:TimerEvent):void
		{
			//trace("onHotspotOutHandler");
			if(!volumeHotspot.hitTestPoint(stage.mouseX,stage.mouseY) && !_volDrag){
				volumeSlider.visible = false;
				_hsTimer.stop();
				_hsTimer.removeEventListener(TimerEvent.TIMER,onHotspotOutHandler);
			}
		}
		
		private function updateVolumeBar(event:Event):void
		{
			//trace(".updateSeekBarLaoded :: height = " + -(volumeSlider.volumeHead.y));
			volumeSlider.volBar.height = -(volumeSlider.volumeHead.y);
		}
		
		// drag and drop methods
		
		private function startSeekDrag(event:MouseEvent):void 
		{
			_seekDrag = true;
			var dragArea:Rectangle = new Rectangle(seekSlider.trackBar.x, seekSlider.seekHead.y, seekSlider.trackBar.width, 0)
			seekSlider.seekHead.startDrag( true, dragArea);
			stage.addEventListener(MouseEvent.MOUSE_UP,onStageMouseUpHandler);
			_dragTimer.addEventListener(TimerEvent.TIMER,onSeekDragHandler);
			_dragTimer.start();
		}
		
		private function stopSeekDrag(event:MouseEvent=null):void
		{
			_seekDrag = false;
			seekSlider.seekHead.stopDrag();
			_dragTimer.stop();
			_dragTimer.removeEventListener(TimerEvent.TIMER,onSeekDragHandler);
			stage.removeEventListener(MouseEvent.MOUSE_UP,onStageMouseUpHandler);
			onSeekDragHandler();
		}
		
		private function startVolumeDrag(event:MouseEvent):void 
		{
			_volDrag = true;
			var dragArea:Rectangle = new Rectangle(volumeSlider.volumeHead.x, volumeSlider.trackBar.y-volumeSlider.trackBar.height, 0, volumeSlider.trackBar.height)
			volumeSlider.volumeHead.startDrag( true, dragArea);
			stage.addEventListener(MouseEvent.MOUSE_UP,onStageMouseUpHandler);
			_dragTimer.addEventListener(TimerEvent.TIMER,onVolumeDragHandler);
			_dragTimer.start();
		}
		
		private function stopVolumeDrag(event:MouseEvent=null):void
		{
			_volDrag = false;
			volumeSlider.volumeHead.stopDrag();
			_dragTimer.stop();
			_dragTimer.removeEventListener(TimerEvent.TIMER,onVolumeDragHandler);
			stage.removeEventListener(MouseEvent.MOUSE_UP,onStageMouseUpHandler);
			onVolumeDragHandler();
		}
	
	}
}