package
{
	import com.zeusprod.Log;
	
	import events.NetEvent;
	
	import flash.display.Sprite;
	import flash.events.Event;
	import flash.media.Video;
    import flash.external.ExternalInterface;
	
	import net.FMSConnection;
	import net.FMSStream;
	
	import view.VideoScreen;
	
	[SWF (width=275, height=205)]
	public class HostCam extends Sprite
	{
		private var fmsConn:FMSConnection;
		private var vid:Video;
		
		private var ns:FMSStream;
		private var cpCode:String;
		private var streamName:String;
		private var streamID:String;
		private var bandwidthLimit:Number;
		private var qualityLevel:Number;
		private var keyFrameInterval:Number;
		private var captureWidth:Number;
		private var captureHeight:Number;
		private var captureFps:Number;
		private var favorArea:Boolean;
		private var camLoopback:Boolean;
		private var micLoopback:Boolean;
		private var echoSuppression:Boolean;
		private var debugConsole:Boolean;
		private var debugAlert:Boolean;
		private var bufferMin:Number;
		private var bufferMax:Number;
		private var micRate:Number;
		private var micGain:Number;
		private var hostViewerWidth:Number;
		private var hostViewerHeight:Number;
		
		public function HostCam()
		{
			this.addEventListener(Event.ADDED_TO_STAGE, stageInit);
			// Name cannot be set for this "timeline-placed" object.
			//this.name += " - renamed HostCam";
			//Log.alert ("Reached HostCam constructor");
		}
		
		public function pauseVideoCapture(evt:Event):void {
			Log.traceMsg("Destroying HostCam.ns", Log.LOG_TO_CONSOLE);
			ns.destroy();
			ns = null;
            /*
            Log.traceMsg ("Reached HostCam.pauseVideoCapture: " + evt.toString(), Log.LOG_TO_CONSOLE);
			if (ns) {
				ns.pauseStream();
			} else {
				Log.traceMsg ("No HostCam stream to pause", Log.LOG_TO_CONSOLE);
			}
			*/
			//return "successful pause of video";
		}
		public function restartVideoCapture(evt:String):void {
			Log.traceMsg ("Reached HostCam.restartVideoCapture:" + evt.toString(), Log.LOG_TO_CONSOLE);
			
            ns  = new FMSStream( fmsConn.nc, vid );
			ns.addEventListener(FMSStream.CAMERA_DENIED, notifyCameraDenied);
			ns.addEventListener(FMSStream.CAMERA_ALLOWED, notifyCameraAllowed);
			ns.publishStream( streamName, streamID,
								bandwidthLimit, qualityLevel, keyFrameInterval,
								captureWidth, captureHeight, captureFps, favorArea,
								camLoopback,
								bufferMin,
								bufferMax,
								echoSuppression,
								micLoopback,
								micRate,
								micGain);
			/*
            if (ns) {
				ns.restartStream();
			} else {
				Log.traceMsg ("No HostCam stream to restart", Log.LOG_TO_CONSOLE);
			}
			*/
			//return  "successful restart of video";
		}
		
	
		public function init(initObj:Object):void {
			// May be invoked by external loading swf, but probably not in our case
			Log.alert ("Reached HostCam.init with initObject: " + initObj);
		}
		
		private function stageInit (evt:Event):void {
			Log.traceMsg ("Reached HostCam.stageInit", Log.LOG_TO_CONSOLE);
			this.removeEventListener(Event.ADDED_TO_STAGE, stageInit);
			// Wait for manual destroy, not when removed from stage
			//this.addEventListener(Event.REMOVED_FROM_STAGE, destroy);
			
			//http://www.adobe.com/devnet/flashmediaserver/articles/dynstream_live.html
			var flashParams:Object = stage.loaderInfo.parameters;
			cpCode 			= (flashParams.cpCode == undefined) ? "113557" : flashParams.cpCode;
			streamName 		= (flashParams.streamName == undefined) ? "none" : flashParams.streamName;
			streamID 		= (flashParams.streamID == undefined) ? "45907" : flashParams.streamID;
			bandwidthLimit 	= (flashParams.bandwidthLimit == undefined) ? 300000 : Number(flashParams.bandwidthLimit);
			qualityLevel 	= (flashParams.qualityLevel == undefined) ? 70 : Number(flashParams.qualityLevel);
			keyFrameInterval= (flashParams.keyFrameInterval == undefined) ? 30 : Number(flashParams.keyFrameInterval);
			captureWidth 	= (flashParams.captureWidth == undefined) ? 288 : Number(flashParams.captureWidth);
			captureHeight	= (flashParams.captureHeight == undefined) ? 216 : Number(flashParams.captureHeight);
			captureFps 		= (flashParams.captureFps == undefined) ? 20 : Number(flashParams.captureFps);
			bufferMin 		= (flashParams.bufferMin == undefined) ? 2 : Number(flashParams.bufferMin);
			bufferMax 		= (flashParams.bufferMax == undefined) ? 15 : Number(flashParams.bufferMax);
			micRate 		= (flashParams.micRate == undefined) ? 44 : Number(flashParams.micRate);
			micGain 		= (flashParams.micGain == undefined) ? 50 : Number(flashParams.micGain);
			hostViewerWidth = (flashParams.hostViewerWidth == undefined) ? 275 : Number(flashParams.hostViewerWidth);
			hostViewerHeight= (flashParams.hostViewerHeight == undefined) ? 205 : Number(flashParams.hostViewerHeight);
			
			favorArea 		= (flashParams.favorArea 		== "false" || flashParams.favorArea 	  == "0") ? false : true;
			camLoopback 	= (flashParams.camLoopback 		== "true"  || flashParams.camLoopback 	  == "1") ? true  : false;
			micLoopback 	= (flashParams.micLoopback 		== "true"  || flashParams.micLoopback 	  == "1") ? true  : false;
			echoSuppression = (flashParams.echoSuppression 	== "false" || flashParams.echoSuppression == "0") ? false : true;
			debugConsole 	= (flashParams.debugConsole 	== "true"  || flashParams.debugConsole 	  == "1") ? true  : false;
			debugAlert 		= (flashParams.debugAlert 		== "true"  || flashParams.debugAlert 	  == "1") ? true  : false;
			if (debugAlert) {
				Log.init(stage);
			}
			Log.consoleLogging = debugConsole;
			Log.traceMsg ("HostCam Version is 1.3", Log.LOG_TO_CONSOLE);
			Log.traceMsg ("Got to HostCam stageInit", Log.LOG_TO_CONSOLE);
			//Log.traceMsg("spotcheck cpCode: " + cpCode, Log.LOG_TO_CONSOLE);
			vid = new VideoScreen().vid;
			addChild( vid );
			vid.name = "VideoScreen.vid";
			
			if (isNaN(hostViewerWidth)) {
				hostViewerWidth = 275;
			}
			if (isNaN(hostViewerHeight)) {
				hostViewerHeight = 205;
			}
			vid.width = hostViewerWidth;
			vid.height = hostViewerHeight;
			
			
			//var tmpRect:Rectangle = new Rectangle(0, 0, 150, 100);
			//trace ("TempRect: " + tmpRect.width);
			//Log.consoleRect = tmpRect;
			fmsConn = new FMSConnection( cpCode, streamID, streamName);
			Log.traceMsg ("fmsConn is " + fmsConn, Log.LOG_TO_CONSOLE);
		      
            fmsConn.addEventListener( NetEvent.FC_PUBLISH_START, createWebcamStream );
			fmsConn.addEventListener( NetEvent.NET_CONNECTION_FAILURE, connectionFailure );
			fmsConn.addEventListener( NetEvent.NET_CONNECTION_SUCCESSFUL, connectSuccess);
			fmsConn.connect( fmsConn.entryPoint );
		}
		
		private function connectionFailure( evt:NetEvent ):void
		{
			Log.traceMsg ("connectionFailure is reached", Log.LOG_TO_CONSOLE);	
		}
		
		private function connectSuccess( evt:NetEvent ):void
		{
			Log.traceMsg ("connectSuccess is reached", Log.LOG_TO_CONSOLE);	
		}
		
		private function createWebcamStream( evt:NetEvent ):void
		{
			Log.traceMsg ("createWebcamStream is reached", Log.LOG_TO_CONSOLE);
			ns  = new FMSStream( fmsConn.nc, vid );
			Log.traceMsg ("ns is " + ns, Log.LOG_TO_CONSOLE);
			ns.addEventListener(FMSStream.CAMERA_DENIED, notifyCameraDenied);
			ns.addEventListener(FMSStream.CAMERA_ALLOWED, notifyCameraAllowed);
			ns.publishStream( streamName, streamID,
								bandwidthLimit, qualityLevel, keyFrameInterval,
								captureWidth, captureHeight, captureFps, favorArea,
								camLoopback,
								bufferMin,
								bufferMax,
								echoSuppression,
								micLoopback,
								micRate,
								micGain);							
		}
		
		private function notifyCameraDenied(evt:Event):void {
			dispatchEvent(evt);
		}
		
		private function notifyCameraAllowed(evt:Event):void {
			dispatchEvent(evt);
		}

		
		public function destroy (evt:Event=null):void {
			// Wait for manual destroy, not when removed from stage
			//this.removeEventListener(Event.REMOVED_FROM_STAGE, destroy);
			// I think removing this class from the stage destroys ns object, frankly
			if (ns == null) {
				Log.traceMsg("HostCam.ns is already null so there is nothing to destroy", Log.LOG_TO_CONSOLE);
			} else {
				Log.traceMsg("Destroying HostCam.ns", Log.LOG_TO_CONSOLE);
				ns.destroy();
				ns = null;
			}
		}
	}
}
