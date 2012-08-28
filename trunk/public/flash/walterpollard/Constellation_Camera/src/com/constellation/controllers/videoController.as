package com.constellation.controllers
{
	
	import com.akamai.osmf.AkamaiBasicStreamingPluginInfo;
	import com.constellation.config.errorConfig;
	import com.constellation.events.constellationEvent;
	import com.constellation.externalConfig.ExternalConfig;
	import com.constellation.managers.loggingManager;
	import com.constellation.net.MBR.mbrManager;
	import com.constellation.parsers.smilParser;
	import com.constellation.view.videoView;
	import com.sierrastarstudio.utils.tracer;
	
	import flash.events.AsyncErrorEvent;
	import flash.events.Event;
	import flash.events.NetStatusEvent;
	import flash.events.SecurityErrorEvent;
	import flash.media.SoundTransform;
	import flash.net.NetConnection;
	import flash.net.NetStream;
	import flash.utils.*;
	
	import org.osmf.events.MediaFactoryEvent;
	import org.osmf.media.DefaultMediaFactory;
	import org.osmf.media.MediaElement;
	import org.osmf.media.MediaPlayer;
	import org.osmf.media.MediaResourceBase;
	import org.osmf.media.PluginInfoResource;
	import org.osmf.media.URLResource;
	import org.osmf.net.StreamType;
	import org.osmf.net.StreamingURLResource;

	public class videoController
	{
		private var _classname:String  = "com.constellation.controllers.videoController";
		
		// Plugin path
		private static const BASIC_STREAMING_PLUGIN:String = "com.akamai.osmf.AkamaiBasicStreamingPluginInfo";
		private static const AKAMAI_PLUGIN:String = 
			"http://players.edgesuite.net/flash/plugins/osmf/advanced-streaming-plugin/fp10.1/staging/AkamaiAdvancedStreamingPlugin.swf";
		private static const HDN_MULTI_BITRATE_VOD:String = 
			"http://mediapm.edgesuite.net/edgeflash/public/debug/assets/smil/buckbunny-vod.smil";
		
		// Media paths
		public static const MEDIA_PATH:String = "rtmp://cp67126.edgefcs.net/ondemand/mp4:mediapm/ovp/content/demo/video/elephants_dream/elephants_dream_768x428_24.0fps_408kbps.mp4";
		public static const STREAM_AUTH:String = "rtmp://cp78634.edgefcs.net/ondemand/mp4:mediapmsec/osmf/content/test/SpaceAloneHD_sounas_640_700.mp4?auth=daEc2a9a5byaMa.avcxbiaoa8dBcibqbAa8-bkxDGK-b4toa-znnrqzzBvl&aifp=v0001";
		public static const PROGRESSIVE_SSL:String = "https://a248.e.akamai.net/7/248/67129/v0001/mediapm.download.akamai.com/67129/osmf/content/test/akamai_10_year_f8_512K.flv";
		
		private static var instance:videoController;
		private var _streamComplete:Boolean;
		private var _currentlySwitching:Boolean;
		private var _videoWidth:int;
		private var _videoHeight:int;
		private var _currentErrorCode:String;
		
		private var _netConnect:NetConnection;
		private var _alreadyConnected:Boolean;
		private var _smilParser:smilParser;
		private var _netConnection:NetConnection;
		private var _netStream:NetStream;
		private var _videoView:videoView;
		private var _mediaFactory:DefaultMediaFactory;
		
		
		public function videoController(enforcer:SingletonEnforcer)
		{
		}
		public static function getInstance():videoController
		{
			
			if (videoController.instance == null)
			{
				videoController.instance = new videoController(new SingletonEnforcer());
			}
			return videoController.instance;
		}
		public function get netStream():NetStream{
			return this._netStream
		}
		
		
			
	
		private function doConnection():void
		{
			//this._videoView.createVideoDisplay();
			
			var hostName:String = this._smilParser.hostName;
			var protocol:String = this._smilParser.protocol;
			var SMILstreamName:String = this._smilParser.dsi.streams[0].name;
			var streamURL:String = protocol+"://"+hostName+"/"+SMILstreamName;
			
			//this._videoView.loadMedia()
		}	
		protected function onMBRStreamNotify(evt:constellationEvent):void
		{
			tracer.log("stream notified from MBR "+evt.data,_classname);
			//	this.swapStream(int(evt.data));
		}
		
		
		
		protected function onPluginLoaded( event:MediaFactoryEvent ):void
		{
			trace( "onPluginLoaded()" );
			
			//Marker 5: Load the media
			loadMedia();
		}
		protected function loadMedia():void 
		{
			//Marker 6: The pointer to the media
			var resource:URLResource = new URLResource( MEDIA_PATH );
			var element:MediaElement = this._mediaFactory.createMediaElement( resource );
		 	
			// Add the media element
			//this._videoView.videoDisplay.media = element;
			//this._videoView.mediaContainter.addMediaElement( element );
		}
		protected function onPluginLoadFailed( event:MediaFactoryEvent ):void
		{
			trace( "onPluginFailedLoad()" );
		}
		
		
		private function startStream():void{
			//tracer.log("Starting stream ",_classname);
			
			this._netStream = new NetStream(this._netConnection);
			this._netStream.addEventListener(NetStatusEvent.NET_STATUS, ncStatus);
			
			// The client is this class for metadata and so on
			this._netStream.client = this;
			
			//pass netStream to mbrManager
			//mbrManager.getInstance().addNetStream(this._netStream,this._smilParser.dsi);
			
			// Set the bufferTime
			this._netStream.bufferTime = 2
			this._netStream.play(this._smilParser.dsi.streams[1].name);
			//seek to seek into point
			if(ExternalConfig.getInstance().seekInto!=0){
				tracer.log("seeking into point "+ExternalConfig.getInstance().seekInto,_classname);
				this._netStream.seek(ExternalConfig.getInstance().seekInto);
			}
			this._videoView.createVideoDisplay();
			//listen for external callback to change volume
 			ExternalInterfaceController.getInstance().addEventListener(constellationEvent.SET_VOLUME,onSetVolume);
			
		}
		private function onSetVolume(evt:constellationEvent):void{
			this._netStream.soundTransform = evt.data as SoundTransform;
			
		}
		public function onBWDone(... rest):void { 
			//tracer.log("onBWDone Start",_classname);
		}
		private function securityErrorHandler(event:SecurityErrorEvent):void {
			//debug("securityErrorHandler: " + event);
			ExternalConfig.getInstance().currentErrorCode = errorConfig.stream_securityError;
		//	this.displayMessage(ExternalConfig.getInstance().defaultErrorMessage+ExternalConfig.getInstance().currentErrorCode,false);
		}
		protected function onAsyncError(event:AsyncErrorEvent):void
		{
			// TODO Auto-generated method stub
			
		}
		
		// NetStream status messages
		public function onPlayStatus(info:Object):void { 
			switch (info.code) { 
				case "NetStream.Play.Complete": 
					//tracer.log("The stream has completed",_classname); 
					this._streamComplete = true;
					break; 
				case "NetStream.Play.TransitionComplete":
					//tracer.log("The stream transition has completed",_classname);
					this._currentlySwitching = false;
					break;
				default:
					//tracer.log("Default: " + info.code,_classname);
					break;
			} 
		} 
		
		// This is on the NetStream client
		public function onCuePoint(infoObject:Object):void {
			//tracer.log("cuePoint",_classname);
		}
		
		// This is on the NetStream client
		public function onMetaData(infoObject:Object):void {
			tracer.log("metaData",_classname);
			
		//	this._videoWidth = infoObject.width;
		//	this._videoHeight = infoObject.height;
			
			this._videoView.resizeVideo();
			
		}
		
		
		public function set smilParser(smilPar:smilParser):void{
			this._smilParser = smilPar
			//this._smilParser.addEventListener(constellationEvent.SMIL_PARSED,onSmilParsed);
		//	tracer.log("attached smil parser "+this._smilParser,_classname);
		}
		public function set videoView(vView:videoView):void{
			this._videoView = vView;
		}
		
		protected function onSmilParsed(event:Event):void
		{
			//smil ready
		//	tracer.log("SMIL PARSED - DO CONNECTION ",_classname)
			this.doConnection()
		}
		
		public function disconnectStream():void
		{
			// TODO Auto Generated method stub
			
		}
		
		
		private function ncStatus(event:NetStatusEvent):void 
		{
			//tracer.log("ncStatus : "+event.info.code,_classname);
			
			switch (event.info.code) {
				case "NetConnection.Connect.Closed":
					//tracer.log("NET CONNECTION IS CLOSED",_classname);
					loggingManager.getInstance().logToServer("FMS connection CLOSED - attempting to reconnect");
					this._alreadyConnected = false
					//this.doConnection();
					break;
				case "NetConnection.Connect.Success":
					
					if (!this._alreadyConnected) {
						
						// let's not run this again..
						this._alreadyConnected = true;
						
						/*	if(streamCheckTimer.running==false){
						//only start the timer if one is not running
						//	streamCheckTimer.start();
						}
						*/
						
						
						//tracer.log("Connected",_classname);
						loggingManager.getInstance().logToServer("Connected to FMS stream ");
						
						startStream();
						// If desired, do bandwidth check
						/*
						if (CHECKBANDWIDTH) {
						//tracer.log("Calling checkBandwidth");
						laterCheckBandwidth = false; // We are on the first one
						// This works phenominally better than the initial call when connecting.
						nc.call("checkBandwidth", null);
						} else {
						// If we aren't checking bandwidth, we'll just start the stream
						
						}								
						}
						*/
					}
					break;
				
				case "NetConnection.Connect.NetworkChange":
					//tracer.log("Netconnection Network Change",_classname);
					/*
					if (!nc.connected) {
					alreadyConnected = false;
					doConnection();
					}
					*/
					break;
				
				case "NetStream.Play.StreamNotFound":
					//tracer.log("Stream not found",_classname);
					
					ExternalConfig.getInstance().currentErrorCode = errorConfig.stream_streamNotFound;
					//this.displayMessage(ExternalConfig.getInstance().defaultErrorMessage+ExternalConfig.getInstance().currentErrorCode);
					loggingManager.getInstance().logToServer("FMS connection Stream name not found - disconnecting user");
					
					break;
				
				case "NetStream.Play.Transition":
					//tracer.log("Transition Event " +  event.info.reason,_classname);
					
					break;
				
				case "NetStream.Buffer.Full":
					//tracer.log("Buffer Full",_classname);
					this.netStream.bufferTime = 10;
					break;
				
				case "NetStream.Buffer.Empty":
					//tracer.log("Buffer Empty",_classname);
					if (!this._currentlySwitching) {
						/*	bufferEmptyCount++;
						if (bufferEmptyCount >= BUFFEREMPTYCOUNTTHRESHOLD) {
						if (currentStreamSize > 0) {
						//tracer.log("Lowering stream");
						swapStream(currentStreamSize-1);
						} else {
						//tracer.log("Already at smallest stream");
						}
						bufferEmptyCount = 0;
						}
						*/
					}
					break;
				
				default:
					//tracer.log(event.info.code,_classname);
					
					break;
			}
			
			
		}
	}
}
class SingletonEnforcer{}