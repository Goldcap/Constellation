package com.constellation.view
{
	import com.akamai.net.AkamaiConnection;
	import com.akamai.net.AkamaiEnhancedNetStream;
	import com.akamai.net.AkamaiNetStream;
	import com.constellation.config.errorConfig;
	import com.constellation.events.NetEvent;
	import com.constellation.events.constellationEvent;
	import com.constellation.externalConfig.ExternalConfig;
	import com.constellation.net.FMSConnection;
	import com.constellation.utilities.MediaResources;
	import com.sierrastarstudio.utils.tracer;
	
	import flash.display.Sprite;
	import flash.events.AsyncErrorEvent;
	import flash.events.Event;
	import flash.events.EventDispatcher;
	import flash.events.NetStatusEvent;
	import flash.events.SecurityErrorEvent;
	import flash.events.StatusEvent;
	import flash.media.Camera;
	import flash.media.Microphone;
	import flash.media.Video;
	import flash.net.NetConnection;
	import flash.net.NetStream;
	
	import org.casalib.display.CasaSprite;
	import org.openvideoplayer.events.OvpEvent;
	

	public class viewerCameraView extends CasaSprite 
	{
		private var _classname:String = "com.sonstellation.view.viewerCameraView";
		
		private var _videoDisplay:Video; 
		//private var cam:Camera; 
		//private var mic:Microphone; 
		//private var _hostcam_cpCode:String;
		//private var _hostcam_streamName:String;
		//private var _hostcam_streamID:String;
		//private var _hostName:String
		
		private var hostName:String = MediaResources.RTMP_STREAMING_LIVE.hostName;
		private var aStreamName:String = MediaResources.RTMP_STREAMING_LIVE.streamName;
		private var streamName:String = MediaResources.RTMP_STREAMING_LIVE.streamName;
		private var authToken:String = MediaResources.RTMP_STREAMING_LIVE.authToken;
		

		private var _cameraStreamName:String;
		//private var _streamName:String;
		//private var _authToken:String;
		private var autoStart:Boolean;
		private var _nc:AkamaiConnection;
		
		private var _netStream:AkamaiNetStream;
		//private var _protocol:String;

		private var _clientObj:Object;

		//private var _streamID:String;
		private var _hostConnected:Boolean;
		
		
		public function viewerCameraView()
		{
			if(this.stage){
				this.init();
			}else{
				this.addEventListener(Event.ADDED_TO_STAGE,init); 
			}
			
		}
		private function init(evt:Event = null):void{
			
			var flashvars:Object = ExternalConfig.getInstance().flashvars;
			
			
			hostName = "cp" + ((flashvars.hostName == undefined) ? "113557" : flashvars.hostName) + ".live.edgefcs.net/live";
			aStreamName = (flashvars.streamName == undefined) ? "none" : flashvars.streamName;
			
			var streamID:String = (flashvars.streamID == undefined) ? "45907" : flashvars.streamID;
			streamName = aStreamName + "@" + streamID;	
			
			var auth:String = (flashvars.auth == undefined ) ? "noauth" :  flashvars.auth;
			var aifp:String = (flashvars.aifp == undefined) ? "v0006": flashvars.aifp;
			authToken = "auth=" + auth + "&amp;aifp=" + aifp;
			autoStart = (flashvars.autostartLiveViewer == undefined) ? true : flashvars.autostartLiveViewer;
			
			tracer.log("hostname '"		+ hostName 		+ "'",_classname);
			tracer.log ("streamName '" 	+ streamName 	+ "'",_classname);
			tracer.log ("authToken '" 	+ authToken 	+ "'",_classname);
			tracer.log ("autoStart '" 	+ autoStart 	+ "'",_classname);
			
			
		}
		public function createCameraViewer():void{
			this._videoDisplay = new Video();
			this._videoDisplay.width = 320;
			this._videoDisplay.height = 240;
			this._videoDisplay.x = 0;
			this._videoDisplay.y = 0;
			tracer.log("creating camera viewer ",_classname);
			
			this.addChild(this._videoDisplay);
			this.onConnectToFMS();
		}
		private function onConnectToFMS():void{
			if (((this._nc != null) && (! this._nc.connected)) || (this._nc == null)) {
			this._nc = new AkamaiConnection();
		
		//	this._nc.addEventListener("DEBUG",debugEvent);
			//hostcam_fmsConn.addEventListener( NetEvent.FC_PUBLISH_START, hostcam_createWebcamStream );
			this._nc.addEventListener( NetEvent.NET_CONNECTION_FAILURE, hostcam_connectionFailure );
			this._nc.addEventListener( NetEvent.NET_CONNECTION_SUCCESSFUL, hostcam_connectSuccess);
			this._nc.addEventListener(NetStatusEvent.NET_STATUS, onNetStatus);
			this._nc.addEventListener(OvpEvent.ERROR,onDisplayEvent);
			//rtmp://cp34973.live.edgefcs.net/live/Flash_Live_Benchmark@632
			
		//	this._nc.connect("rtmp://cp34973.live.edgefcs.net/live");//this._protocol+this._hostName);
		//	var FMSStr:String = this._protocol+this._hostName;
			
		//	var FMSObject:FMSConnection = new FMSConnection(this._hostcam_cpCode,this._streamID,this._streamName);
			tracer.log("creating AKAMAI connection for camera viewer "+hostName,_classname);
		this._nc.connect(hostName);
		
			//	this._nc.connect(FMSObject.entryPoint);
		//	this._nc.connect(this._nc.entryPoint);
			}else{
				//If we have a connection, but are trying to connect
				//Let's assume we're actually trying to stream
				//So let's start that process manually
				tracer.log("LiveStreaming initNetConnection is not null", _classname);
				initNetStream();
			}
		}
		private function initNetStream():void
		{
			tracer.log("VIEW CAMERA initNetStream  "+streamName+"  this._authToken "+authToken, _classname);
			//if (netStream == null) {
			
			//Log.traceMsg ("LiveStreaming initNetStream is null", Log.LOG_TO_CONSOLE);
			this._netStream = new AkamaiNetStream(this._nc);
			
			this._videoDisplay.attachNetStream(this._netStream);
			
			this._netStream.bufferTime = 1;
			//	this._netStream  = new NetStream(this._nc.nc);
			//this._netStream.addEventListener(NetStatusEvent.NET_STATUS, onNetStreamStatus);
			//this._netStream.client = this;
			AkamaiNetStream(this._netStream).liveStreamAuthParams = authToken;
			this._netStream.addEventListener(OvpEvent.SUBSCRIBED, onSubscribe);
			this._netStream.addEventListener(OvpEvent.UNSUBSCRIBED, onUnsubscribe);
			this._netStream.addEventListener(OvpEvent.SUBSCRIBE_ATTEMPT, onSubscribeAttempt);
			this._netStream.addEventListener(OvpEvent.ERROR,onDisplayEvent);
			
			//		this._netStream.play("Flash_Live_Benchmark@632");
		
			this._netStream.play(streamName);
			
			//}
		}
		
		
		private function hostcam_connectionFailure( evt:NetEvent ):void
		{
			tracer.log("connectionFailure is reached "+evt,_classname);	
		}
		
		private function hostcam_connectSuccess( evt:NetEvent ):void
		{
			this._nc.removeEventListener( NetEvent.NET_CONNECTION_SUCCESSFUL, hostcam_connectSuccess);
			
			tracer.log("connectSuccess is reached "+evt,_classname);	
			initNetStream()
			//hostcam_createWebcamStream(evt);
		}
		private function onDisplayEvent(evt:Event):void{
			tracer.log("display event "+evt,_classname);
		}
		public function onMetaData(obj:Object):void{
			tracer.log("METADATA event "+obj,_classname);
		}
	
		
		protected function onNetStreamStatus(evt:NetStatusEvent):void
		{
			// TODO Auto-generated method stub
			tracer.log("on net stream status evt "+evt,_classname);
		}
		private function onClientConnect(evt:Event):void{
			tracer.log("onClientConnect WHOO now TRY STREAM ",_classname);
		//	this._netStream.play(this._streamName);
		}
		private function onSubscribe(evt:OvpEvent):void{
			tracer.log("on subscribe",_classname);
			this._hostConnected = true;
			dispatchEvent(new constellationEvent(constellationEvent.CAMERA_READY))
		}
		public function onFCSubscribe(obj:Object):void{
			tracer.log("on onFCSubscribe ",_classname);
			
			for (var prop:String in obj) {
				tracer.log("onFCSubscribe " + prop + " " + obj[prop],_classname);
			}	
		}
		private function onUnsubscribe(evt:OvpEvent):void{
			tracer.log("on UN subscribe ",_classname);
			if(this._hostConnected==true){
				this._hostConnected = false;
				dispatchEvent(new constellationEvent(constellationEvent.CAMERA_LOST_HOST));
			}
		}
		private function onSubscribeAttempt(evt:OvpEvent):void{
			tracer.log("on subscribe attempt",_classname);
			dispatchEvent(new constellationEvent(constellationEvent.CAMERA_NO_HOST));
			
		}
		public function onBWDone(... rest):void { 
			tracer.log("onBWDone Start",_classname);
		}
		public function onBWCheck(... rest):void { 
			tracer.log("onBWCheck Start",_classname);
		}
		private function securityErrorHandler(event:SecurityErrorEvent):void {
		tracer.log("securityErrorHandler: " + event,_classname);
		
			ExternalConfig.getInstance().currentErrorCode = errorConfig.stream_securityError;
			messageView.getInstance().setMessage(ExternalConfig.getInstance().defaultErrorMessage+ExternalConfig.getInstance().currentErrorCode,false);
		}
		public function close(evt:Event):void{
			tracer.log("callback CLOSE called "+evt,_classname);
		}
		protected function onAsyncError(event:AsyncErrorEvent):void
		{
			// TODO Auto-generated method stub
			tracer.log("A sync error ",_classname);
			
		}
		private function onNetStatus(event:NetStatusEvent):void
		{
			tracer.log("ON NET STATUS "+event.info.code,_classname);
			for each(var prop:String in event){
				tracer.log(" info "+prop+" :  "+event[prop],_classname);
			}
			switch (event.info.code) 
			{
				case "NetConnection.Connect.Rejected":
					tracer.log("Rejected by server. Reason is "+event.info.description,_classname);
					break;
				case "NetConnection.Connect.Success":
					initNetStream();
					break;
			}
		}
		public function debug(code:String):void {
		//	var statusEvent:StatusEvent = new StatusEvent("DEBUG");
		//	statusEvent.code = code;
		//	dispatchEvent(statusEvent);
			tracer.log("DEBUG "+code,_classname);
			
		}	
		public function debugEvent(e:StatusEvent):void {
			tracer.log("DEBUG "+e.code,_classname);
		}

		public function get videoDisplay():Video
		{
			return _videoDisplay;
		}

	}
}