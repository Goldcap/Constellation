package
{
    import com.zeusprod.Log;
    
    import flash.display.*;
    import flash.events.*;
    import flash.media.Video;
    import flash.net.NetConnection;
    import flash.net.NetStream;
    
	import flash.geom.Rectangle;
  
    public class ConstellationPlayer extends MovieClip {
        	
        private var _video:Video;
        public var _ak:NetConnection;
        public var _ns:NetStream;
		private var _needsRestart:Boolean;
        public var _hostName:String;
        public var _scaleMode:String;
        public var serverName:String;
        public var appName:String;
        public var streamName:String;
        public var extension:String;
        public var queryTerms:String;
		private var _availableVideoWidth:Number
		private var _availableVideoHeight:Number
		private var _lastVideoWidth:Number;
		private var _lastVideoHeight:Number;
		public var _streamlength:Number;
		public var _bandwidth:Number;
        
        /**
         * Constructor
         */
        public function ConstellationPlayer():void {
            
            _scaleMode = "fit";
            serverName = "consteltv.fcod.llnwd.net";
            appName = "a6284/o38";
            streamName = "u/wrath_of_charley2-1.flv";
            queryTerms= "";
            _streamlength = 0;
            _bandwidth = 0;
            
            extension = streamName.toLowerCase().substr(-4, 4);
            
            Log.init(stage);
            Log.consoleLogging = true;
			Log.consoleRect = new Rectangle(0, 0, 900, 900);
			Log.traceMsg ("version 1.16.3: Console debugging is ON", Log.LOG_TO_CONSOLE);
		
            stage.scaleMode = StageScaleMode.NO_SCALE;
            stage.align = StageAlign.TOP_LEFT;
            
            _video = new Video(320, 240);
			_video.smoothing = false;
			_video.deblocking = 1;
			_video.x = 3;
			_video.y = 3;
			
			addChild(_video);
			scaleVideo();
			
			stage.addEventListener(Event.RESIZE, resizeVideo);
			
            this.connect();
          
        }
        
        var res = new Object();
        res.onResult = function (p_length) {
            _streamlength = p_length;
        }
        
        private function connect():void {
			
            if ( extension == ".flv" ) {
                Log.traceMsg ("Stream is an FLV", Log.LOG_TO_CONSOLE);
		        streamName = streamName.substr(0, streamName.length - 4);
            } else if ( extension == ".mp3" ) {
                Log.traceMsg ("Stream is an MP3", Log.LOG_TO_CONSOLE);
                streamName = "mp3:" + streamName.substr(0, streamName.length - 4);
            }
            
            _ak = new NetConnection();
            
            _ak.onBWCheck = function () {
                return ++payload;
            }
            
            _ak.onBWDone = function (p_bw) {
                _bandwidth = p_bw;
            }

    		_ak.addEventListener(NetStatusEvent.NET_STATUS,netStatusHandler);
    		Log.traceMsg ("Connecting to: " + "rtmp://" + serverName + "/" + appName, Log.LOG_TO_CONSOLE);
            _ak.connect("rtmp://" + serverName + "/" + appName, true, streamName, queryTerms);
            
            //Ask the server for the stream's length
            _ak.call("getStreamLength", res, streamName);
            
		}
		
		// Handles NetConnection status events. 
		private function netStatusHandler(e:NetStatusEvent):void {
			
			Log.traceMsg ("Connection Info" + e.info.code, Log.LOG_TO_CONSOLE);
		
            switch (e.info.code) {
				case "NetConnection.Connect.IdleTimeOut" :
					_needsRestart = true;
					//pauseHandler(null);
					break;
				case "NetConnection.Connect.Closed" :
					_needsRestart = true;
					//pauseHandler(null);
					break;
				case "NetConnection.Connect.Success" :
					connectedHandler();
					break;
			}
		}
		
		private function connectedHandler():void {
		    Log.traceMsg ("Starting Net Stream", Log.LOG_TO_CONSOLE);
		
            _ns = new NetStream(_ak);
            _ns.addEventListener(AsyncErrorEvent.ASYNC_ERROR, ErrorHandler);
            
            Log.traceMsg ("Asset Name: " + streamName, Log.LOG_TO_CONSOLE);
            // attach to the videoDisplay
            _video.attachNetStream(_ns);
            _ns.play(streamName);

        }
        
        public function resizeVideo(evt:Event):void {
            scaleVideo();
        }
        
        public function scaleVideo():void {
			
            //Log.traceMsg("Total Width: " + width, Log.LOG_TO_CONSOLE);
			//Log.traceMsg("Total Height: " + height, Log.LOG_TO_CONSOLE);
			_availableVideoWidth = stage.stageWidth;
			_availableVideoHeight = stage.stageHeight;
			
			Log.traceMsg("VideoView scaleVideo: " + _scaleMode, Log.LOG_TO_CONSOLE);
			Log.traceMsg("VideoView RequestedWidth: " + _video.width, Log.LOG_TO_CONSOLE);
			Log.traceMsg("VideoView RequestedHeight: " + _video.height, Log.LOG_TO_CONSOLE);
			Log.traceMsg("VideoView AvailableWidth: " + _availableVideoWidth, Log.LOG_TO_CONSOLE);
			Log.traceMsg("VideoView AvailableHeight: " + _availableVideoHeight, Log.LOG_TO_CONSOLE);
			
            switch (_scaleMode) {
				case "fit":
				    if (_video.width/_video.height >= _availableVideoWidth/_availableVideoHeight) {
				    	_video.width = _availableVideoWidth;
						_video.height = _availableVideoWidth*_video.height/_video.width;
					} else {
						_video.width = _availableVideoHeight*_video.width/_video.height;
						_video.height = _availableVideoHeight;
					}
					break;
				case "stretch":
					_video.width = _availableVideoWidth;
					_video.height = _availableVideoHeight;
					break;
			}
      		
			//__video.smoothing = (width != _video.width || height != _video.height) && (_model.isFullScreen == false);
			//_model.debug("Smoothing = " + __video.smoothing);
			//__video.smoothing  = false;
			_video.x = 3 + ((_availableVideoWidth - _video.width)/2);
			_video.y = 3 + ((_availableVideoHeight- _video.height)/2);
			
		}
		
		public function ErrorHandler(event:AsyncErrorEvent):void{ 
            Log.traceMsg ("Event Error" + event, Log.LOG_TO_CONSOLE);
        }
        
    }
    

}
