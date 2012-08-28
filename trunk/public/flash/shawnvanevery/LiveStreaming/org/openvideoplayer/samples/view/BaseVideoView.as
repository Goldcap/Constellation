package org.openvideoplayer.samples.view
{
    import flash.display.DisplayObject;
    import flash.display.Shape;
    import flash.display.Sprite;
    import flash.events.Event;
    import flash.media.Video;
    import flash.media.Video;
    
    import org.openvideoplayer.components.ui.controlbar.ControlBar;
    import org.openvideoplayer.components.ui.controlbar.view.DualTimeCodeDisplay;
    import org.openvideoplayer.components.ui.controlbar.view.FullscreenButton;
    import org.openvideoplayer.components.ui.controlbar.view.PlayPauseButton;
    import org.openvideoplayer.components.ui.controlbar.view.ScrubBar;
    import org.openvideoplayer.components.ui.controlbar.view.VolumeControl;
    import org.openvideoplayer.components.ui.debug.DebugPanelView;
    import org.openvideoplayer.components.ui.playlist.view.Style;
    import org.openvideoplayer.components.ui.posterframe.PosterFramePlayButton;
    import org.openvideoplayer.components.ui.spinner.SpinnerView;
    import org.openvideoplayer.samples.akamai.LiveStreaming;
    
    import com.zeusprod.Log;
    
    public class BaseVideoView extends Sprite
    {
        private var _video:Video;
        private var _controlBar:ControlBar;
        private var _posterFrameButton:PosterFramePlayButton;
        private var _spinnerView:SpinnerView;
        private var _console:DebugPanelView;
		private var _background:Shape
		private static var _vidWidth:Number;
		private static var _vidHeight:Number;
		
		//public static const VIDEO_WIDTH:Number = 320; //640;
		//public static const VIDEO_HEIGHT:Number = 240; //360;
        
        public function BaseVideoView(vidWidth:Number=320, vidHeight:Number = 240)
        {
        	_vidWidth = vidWidth;
        	_vidHeight = vidHeight;
			addEventListener(Event.ADDED_TO_STAGE, onAddedToStage);
            addEventListener(Event.REMOVED_FROM_STAGE, destroy);
        }

		public static function get VIDEO_WIDTH ():Number {
			return _vidWidth;
		}
		
		public static function get VIDEO_HEIGHT ():Number {
			return _vidHeight;
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
		
		private function getBackground():Shape
		{
			var shape:Shape = new Shape();
			shape.graphics.beginFill(Style.APP_PANEL_BACKGROUND_COLOR, 1);
			shape.graphics.drawRect(0, 0, VIDEO_WIDTH, stage.stageHeight);
			shape.graphics.endFill();
			return shape;
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
		
        private function centerDisplayObject(displayObject:DisplayObject):void
        {
            displayObject.x = (VIDEO_WIDTH / 2);
            displayObject.y = (VIDEO_HEIGHT / 2);
        }
        
        protected function destroy(event:Event):void
        {
            removeEventListener(Event.REMOVED_FROM_STAGE, destroy);
            
            removeChild(_video);
            //removeChild(_controlBar);
            //removeChild(_posterFrameButton);
            //removeChild(_spinnerView);
            //removeChild(_console);
            _video = null;
            //_controlBar = null;
            //_posterFrameButton = null;
            //_spinnerView = null;
            //_console = null;
        }
        
        
        /**
         *
         * @return Returns an instance of Video
         *
         */
        public function get video():Video
        {
            return _video;
        }
		
        /**
         *
         * @return Returns an instance of ControlBar
         *
         */
        public function get controlBar():ControlBar
        {
            return _controlBar;
        }
        
        /**
         *
         * @return Returns an instance of PosterFramePlayButton
         *
         */
        public function get posterFrameButton():PosterFramePlayButton
        {
            return _posterFrameButton;
        }
        
        /**
         *
         * @return Returns an instance of BusySpinner.
         *
         */
        public function get spinnerView():SpinnerView
        {
            return _spinnerView;
        }
        
        /**
         *
         * @return
         *
         */
        public function get console():DebugPanelView
        {
            return _console;
        }


		public function get background():Shape
		{
			return _background;
		}

    }
}
