package com.constellation.view
{
	import com.constellation.controllers.ExternalInterfaceController;
	import com.constellation.events.NetEvent;
	import com.constellation.events.constellationEvent;
	import com.constellation.externalConfig.ExternalConfig;
	import com.constellation.net.FMSConnection;
	import com.constellation.net.FMSStream;
	import com.sierrastarstudio.utils.tracer;
	
	import flash.display.Sprite;
	import flash.events.Event;
	import flash.events.StatusEvent;
	import flash.external.ExternalInterface;
	import flash.media.Video;

	public class hostCameraView extends Sprite
	{
		private var _classname:String = "com.constellation.view.hostCameraView";
		
		private var _hostcam_fmsConn:FMSConnection;
		private var _hostcam_ns:FMSStream;
		public var _hostcam_vid:Video;
		
		private var hostcam_cpCode:String;
		private var hostcam_streamName:String;
		private var hostcam_streamID:String;
		private var hostcam_bandwidthLimit:Number;
		private var hostcam_qualityLevel:Number;
		private var hostcam_keyFrameInterval:Number;
		private var hostcam_captureWidth:Number;
		private var hostcam_captureHeight:Number;
		private var hostcam_captureFps:Number;
		private var hostcam_favorArea:Boolean;
		private var hostcam_camLoopback:Boolean;
		private var hostcam_micLoopback:Boolean;
		private var hostcam_echoSuppression:Boolean;
		private var hostcam_debugConsole:Boolean;
		private var hostcam_debugAlert:Boolean;
		private var hostcam_bufferMin:Number;
		private var hostcam_bufferMax:Number;
		private var hostcam_micRate:Number;
		private var hostcam_micGain:Number;
		private var hostcam_hostViewerWidth:Number;
		private var hostcam_hostViewerHeight:Number;			
		
		private static const PAUSE_VIDEO:String = "pauseVideo";
		private static const RESTART_VIDEO:String = "restartVideo";
		
		private var flashvars:Object;
		private var _hostcamVideoDisplay:Sprite;
		private var _offsetType:String;
		
		public function hostCameraView()
		{
			if(this.stage){
				this.init();
			}else{
				this.addEventListener(Event.ADDED_TO_STAGE,init); 
			}
		}
		private function init(evt:Event=null):void{
		//	ExternalInterfaceController.getInstance().addEventListener(constellationEvent.SHOW_HOST_CAMERA,startHostCamera);
		
			
			
			
			flashvars  = ExternalConfig.getInstance().flashvars;
			this.removeEventListener(Event.ADDED_TO_STAGE,init);
			//this.addEventListener(
			//gather values for cam
			// Get the specific flashvars variables for the host cam
			hostcam_cpCode 			= (flashvars.cpCode == undefined) ? "113557" : flashvars.cpCode;
			hostcam_streamName 		= (flashvars.streamName == undefined) ? "none" : flashvars.streamName;
			hostcam_streamID 		= (flashvars.streamID == undefined) ? "45907" : flashvars.streamID;
			
			hostcam_bandwidthLimit 	= (flashvars.bandwidthLimit == undefined) ? 300000 : Number(flashvars.bandwidthLimit);
			
			hostcam_qualityLevel 	= (flashvars.qualityLevel == undefined) ? 0 : Number(flashvars.qualityLevel);
			hostcam_keyFrameInterval= (flashvars.keyFrameInterval == undefined) ? 30 : Number(flashvars.keyFrameInterval);
			hostcam_captureWidth 	= (flashvars.captureWidth == undefined) ? 320 : Number(flashvars.captureWidth);
			hostcam_captureHeight	= (flashvars.captureHeight == undefined) ? 240 : Number(flashvars.captureHeight);
			
			hostcam_captureFps 		= (flashvars.captureFps == undefined) ? 20 : Number(flashvars.captureFps);
			hostcam_bufferMin 		= (flashvars.bufferMin == undefined) ? 2 : Number(flashvars.bufferMin);
			hostcam_bufferMax 		= (flashvars.bufferMax == undefined) ? 15 : Number(flashvars.bufferMax);
			hostcam_micRate 		= (flashvars.micRate == undefined) ? 44 : Number(flashvars.micRate);
			hostcam_micGain 		= (flashvars.micGain == undefined) ? 90 : Number(flashvars.micGain);
			hostcam_hostViewerWidth = (flashvars.hostViewerWidth == undefined) ? 320 : Number(flashvars.hostViewerWidth);
			hostcam_hostViewerHeight= (flashvars.hostViewerHeight == undefined) ? 240 : Number(flashvars.hostViewerHeight);
			
			hostcam_favorArea 		= (flashvars.favorArea 		== "false" || flashvars.favorArea 	  == "0") ? false : true;
			hostcam_camLoopback 	= (flashvars.camLoopback 		== "true"  || flashvars.camLoopback 	  == "1") ? true  : false;
			hostcam_micLoopback 	= (flashvars.micLoopback 		== "true"  || flashvars.micLoopback 	  == "1") ? true  : false;
			hostcam_echoSuppression = (flashvars.echoSuppression 	== "false" || flashvars.echoSuppression == "0") ? false : true;
			
			tracer.log("hostcam defaults: 113557 none 45907",_classname);
			tracer.log("hostcam params: cpCode: " + hostcam_cpCode + " streamName: " + hostcam_streamName + " hostcam_streamID: " + hostcam_streamID,_classname);
			
		
		}
		public function startHostCamera(evt:constellationEvent=null):void{
			this._hostcamVideoDisplay = new Sprite();
			this.stage.addEventListener(Event.RESIZE, resizeVideo);
			_hostcam_fmsConn = new FMSConnection( hostcam_cpCode, hostcam_streamID, hostcam_streamName,true);
		//	_hostcam_fmsConn.addEventListener("DEBUG",debugEvent);
			_hostcam_fmsConn.addEventListener( NetEvent.FC_PUBLISH_START, hostcam_createWebcamStream );
			_hostcam_fmsConn.addEventListener( NetEvent.NET_CONNECTION_FAILURE, hostcam_connectionFailure );
			_hostcam_fmsConn.addEventListener( NetEvent.NET_CONNECTION_SUCCESSFUL, hostcam_connectSuccess);
			_hostcam_fmsConn.connect( _hostcam_fmsConn.entryPoint );
			tracer.log(" _hostcam_fmsConn.entryPoint  "+ _hostcam_fmsConn.entryPoint,_classname);
			
			try {
				
				ExternalInterfaceController.getInstance().showQaWindow()
			//	ExternalInterface.call("showQaWindow");
				tracer.log("Triggering JS QA Window",_classname);
			} catch (err:Error) {
				tracer.log("Error in invoke QA Window",_classname);
			}
		}
		
		protected function hostcam_connectSuccess(evt:NetEvent):void
		{
			// TODO Auto-generated method stub
			tracer.log("connected to host cam ", _classname);
			//hostcam_createWebcamStream(evt);
		}
		
		private function hostcam_createWebcamStream( evt:NetEvent ):void
		{			
			tracer.log("hostcam_createWebcamStream",_classname); 
			
			_hostcam_vid = new Video();
			if (isNaN(hostcam_hostViewerWidth)) {
				hostcam_hostViewerWidth = 320;
			}
			if (isNaN(hostcam_hostViewerHeight)) {
				hostcam_hostViewerHeight = 240;
			}
			_hostcam_vid.width = hostcam_hostViewerWidth;
			_hostcam_vid.height = hostcam_hostViewerHeight;
			
			this.addChild(_hostcam_vid);
			//this._hostcamVideoDisplay.visible = true;		
			
			_hostcam_ns  = new FMSStream( _hostcam_fmsConn.nc, _hostcam_vid );
			_hostcam_ns.addEventListener("DEBUG",debugEvent);
	//		debug("added DEBUG listener to hostcam_ns");
			//hostcam_ns.addEventListener(FMSStream.CAMERA_DENIED, notifyCameraDenied);
			//hostcam_ns.addEventListener(FMSStream.CAMERA_ALLOWED, notifyCameraAllowed);
			_hostcam_ns.publishStream( hostcam_streamName, hostcam_streamID,
				hostcam_bandwidthLimit, hostcam_qualityLevel, hostcam_keyFrameInterval,
				hostcam_captureWidth, hostcam_captureHeight, hostcam_captureFps, hostcam_favorArea,
				hostcam_camLoopback,
				hostcam_bufferMin,
				hostcam_bufferMax,
				hostcam_echoSuppression,
				hostcam_micLoopback,
				hostcam_micRate,
				hostcam_micGain);
			this.resizeVideo();
		}	
		
		protected function hostcam_connectionFailure(event:NetEvent):void
		{
			// TODO Auto-generated method stub
			
		}
		public function debugEvent(e:StatusEvent):void {
			tracer.log(e.code,_classname);
		}
		public function resizeVideo(evt:Event = null):void
		{
		tracer.log("resize camera placement and size",_classname);
			try{
				//
			var widthScale:Number
			var heightScale:Number
			
			widthScale = stage.stageWidth*.35 / this._hostcam_vid.width;
			
			heightScale = stage.stageHeight*.35 / this._hostcam_vid.height;
			
			var div:Number = this._offsetType == "max" ? Math.max(widthScale, heightScale) : Math.min(widthScale, heightScale);
			//These two lines resize this object according to the division ratio.  
			//If we use scaleX or scaleY here it wont work as we need it to.  
			this._hostcam_vid.width *= div;
			this._hostcam_vid.height *= div;
			
			this._hostcam_vid.x = 0//(stage.stageWidth/2)-this._videoDisplay.width/2;
			this._hostcam_vid.y = 0//((stage.stageHeight/2) - this._videoDisplay.height/2);
			}catch(err:Error){
				tracer.log("can't resize camera yet",_classname);
			}
			
		}
	}
}