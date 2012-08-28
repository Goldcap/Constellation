package org.openvideoplayer.samples.view
{
    import flash.display.DisplayObject;
    import flash.display.Shape;
    import flash.display.Sprite;
    import flash.events.Event;
    import flash.geom.Rectangle;
    import flash.media.Video;
    
    import org.openvideoplayer.components.ui.controlbar.ControlBar;
    import org.openvideoplayer.components.ui.controlbar.view.DualTimeCodeDisplay;
    import org.openvideoplayer.components.ui.controlbar.view.FullscreenButton;
    import org.openvideoplayer.components.ui.controlbar.view.PlayPauseButton;
    import org.openvideoplayer.components.ui.controlbar.view.ScrubBar;
    import org.openvideoplayer.components.ui.controlbar.view.VolumeControl;
    import org.openvideoplayer.components.ui.debug.DebugPanelView;
    import org.openvideoplayer.components.ui.posterframe.PosterFramePlayButton;
    import org.openvideoplayer.components.ui.spinner.SpinnerView;
    import org.openvideoplayer.samples.akamai.LiveStreaming;
    
    import com.zeusprod.Log;
    
    public class BaseVideoView extends Sprite
    {
		public static const VIDEO_WIDTH:Number = 640;
		public static const VIDEO_HEIGHT:Number = 360;
		
		private static var APP_PANEL_BACKGROUND_COLOR:uint = 0x333333;
		
        private var _video:Video;
        private var _controlBar:ControlBar;
        private var _posterFrameButton:PosterFramePlayButton;
        private var _spinnerView:SpinnerView;
        private var _console:DebugPanelView;
		private var _background:Shape
		
		private var originalVideoRect:Rectangle; 
		private var oringinalVideoDisplayIndex:int;
        
        public function BaseVideoView()
        {
			addEventListener(Event.ADDED_TO_STAGE, onAddedToStage);
            addEventListener(Event.REMOVED_FROM_STAGE, destroy);
        }
		
		public function get video():Video
		{
			return _video;
		}

		public function get controlBar():ControlBar
		{
			return _controlBar;
		}
		
		public function get posterFrameButton():PosterFramePlayButton
		{
			return _posterFrameButton;
		}
		
		public function get spinnerView():SpinnerView
		{
			return _spinnerView;
		}
		
		public function get console():DebugPanelView
		{
			return _console;
		}
		
		public function get background():Shape
		{
			return _background;
		}
		
		public function enterFullscreen():void
		{	
			if(_video != null)
			{
				oringinalVideoDisplayIndex = oringinalVideoDisplayIndex == 0 ? getChildIndex(video) : oringinalVideoDisplayIndex;
				
				if(originalVideoRect == null)
				{
					originalVideoRect = new Rectangle()
				}
				
				originalVideoRect.x = _video.x;
				originalVideoRect.y = _video.y;
				originalVideoRect.width = _video.width;
				originalVideoRect.height = _video.height;
				
				_video.smoothing = false;
				setChildIndex(video, numChildren-1);
			}
			
			_background.visible = false
			_controlBar.visible = false;
		}
		
		public function exitFullscreen():void
		{
			if(_video != null)
			{
				_video.smoothing = true;
				_video.x = originalVideoRect.x;
				_video.y = originalVideoRect.y;
				_video.width  = originalVideoRect.width;
				_video.height = originalVideoRect.height;
				setChildIndex(video, oringinalVideoDisplayIndex);
			}
			_background.visible = true;
			_controlBar.visible = true;
		}

		protected function onAddedToStage(event:Event):void
		{
			addBackgroundPanel();
			addVideo();
			addPosterFrameButton();
			addControlBar();
			addBusySpinner();
			addDebugPanel();
			this.removeEventListener(Event.ADDED_TO_STAGE, onAddedToStage);
		}
		
		protected function addBackgroundPanel():void 
		{
			addChild(_background = getBackground());
		}
		
        protected function addVideo():void
        {
            Log.traceMsg ("Creating video that is " + VIDEO_WIDTH + " x " + VIDEO_HEIGHT, Log.LOG_TO_CONSOLE); 
            _video = new Video(VIDEO_WIDTH, VIDEO_HEIGHT);
			_video.smoothing = true;
			_video.opaqueBackground = 0x000000;
            addChild(_video);
        }
        
        protected function addControlBar():void
        {
            _controlBar = getControlBar();
            _controlBar.y = VIDEO_HEIGHT;
            addChild(_controlBar);
        }
        
        protected function getControlBar():ControlBar
        {
            var controlBar:ControlBar = new ControlBar();
            controlBar.width = VIDEO_WIDTH;
            controlBar.height = 30;
            controlBar.controlBarBackgroundColor = 1;
            controlBar.controlBarBackgroundColor = 0x000000;
            controlBar.addControls([new PlayPauseButton, new ScrubBar, new DualTimeCodeDisplay, new VolumeControl, new FullscreenButton]);
            return controlBar;
        }
        
        protected function addBusySpinner():void
        {
            _spinnerView = new SpinnerView();			
            _spinnerView.visible = false;
            centerDisplayObject(_spinnerView);
            addChild(_spinnerView);
        }
        
        protected function addPosterFrameButton():void
        {
            _posterFrameButton = new PosterFramePlayButton(VIDEO_WIDTH, VIDEO_HEIGHT, 100);
            centerDisplayObject(_posterFrameButton);
            if (!LiveStreaming.autoStart) {
            	addChild(_posterFrameButton);
            }
        }
        
		protected function addDebugPanel():void
		{
			_console = new DebugPanelView(VIDEO_WIDTH, VIDEO_HEIGHT);
			_console.visible = false;
			addChild(_console);
		}
        
        protected function destroy(event:Event):void
        {
            removeEventListener(Event.REMOVED_FROM_STAGE, destroy);
            removeChild(_video);
            removeChild(_controlBar);
            removeChild(_posterFrameButton);
            removeChild(_spinnerView);
            removeChild(_console);
            _video = null;
            _controlBar = null;
            _posterFrameButton = null;
            _spinnerView = null;
            _console = null;
        }
        
		private function getBackground():Shape
		{
			var shape:Shape = new Shape();
			shape.graphics.beginFill(APP_PANEL_BACKGROUND_COLOR, 1);
			shape.graphics.drawRect(0, 0, VIDEO_WIDTH, stage.stageHeight);
			shape.graphics.endFill();
			return shape;
		}
		
		private function centerDisplayObject(displayObject:DisplayObject):void
		{
			displayObject.x = (VIDEO_WIDTH / 2);
			displayObject.y = (VIDEO_HEIGHT / 2);
		}
    }
}
