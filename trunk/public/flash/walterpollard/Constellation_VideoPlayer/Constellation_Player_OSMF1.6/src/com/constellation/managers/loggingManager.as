/**
 * Manages how to receive and send logging messages
 */ 
package com.constellation.managers
{
	import com.akamai.osmf.net.AkamaiZStreamWrapper;
	import com.constellation.events.constellationEvent;
	import com.constellation.externalConfig.ExternalConfig;
	import com.constellation.utilities.AkamaiBandwidthMonitor;
	import com.constellation.view.videoView;
	import com.sierrastarstudio.utils.tracer;
	
	import flash.events.Event;
	import flash.events.EventDispatcher;
	import flash.events.HTTPStatusEvent;
	import flash.events.IOErrorEvent;
	import flash.external.ExternalInterface;
	import flash.net.URLRequestMethod;
	import flash.net.URLVariables;
	import flash.system.Capabilities;
	import flash.system.LoaderContext;
	import flash.system.Security;
	import flash.system.SecurityDomain;
	import flash.system.System;
	
	import org.casalib.events.LoadEvent;
	import org.casalib.load.DataLoad;
	import org.osmf.media.MediaPlayerSprite;
	import org.osmf.net.NetStreamLoadTrait;
	import org.osmf.traits.MediaTraitType;
	
	public class loggingManager extends EventDispatcher
	{
		private static var instance:loggingManager;
		private var _classname:String = "com.constellation.managers.loggingManager";
		public static const LEVEL_DEBUG:String = "DEBUG";
		public static const LEVEL_WARN:String = "WARN";
		public static const LEVEL_INFO:String = "INFO";
		public static const LEVEL_ERROR:String = "ERROR";
		public static const LEVEL_FATAL:String = "FATAL";
		
		
		
		private static const LEVEL_DEBUG_NUM:int = 0
		private static const LEVEL_INFO_NUM:int = 1
		private static const LEVEL_WARN_NUM:int = 2
		
		private static const LEVEL_ERROR_NUM:int = 3
		private static const LEVEL_FATAL_NUM:int = 4
		
		private var _logPath:String;
		
		private var _logLoad:DataLoad;

		private var _curDomain:String;
		
		
		public function loggingManager(enforcer:SingletonEnforcer)
		{
		}
		public static function getInstance():loggingManager
		{
			
			if (loggingManager.instance == null)
			{
				loggingManager.instance = new loggingManager(new SingletonEnforcer());
				AkamaiBandwidthMonitor.getInstance()
			}
			return loggingManager.instance;
		}
		public function loadLoggingPath(logPath:String=null):void{
		
			//set the path if given, or default to the config value
			if(logPath==null){
				this._logPath = ExternalConfig.getInstance().logServicePath+ExternalConfig.getInstance().heartbeatIP+":"+int(Number(ExternalConfig.getInstance().heartbeatPort)+Number(1000));
			}else{
				this._logPath = logPath;
			}
			Security.allowDomain("*");
			var curUrl:String
			if(ExternalInterface.available){
				curUrl =  String( ExternalInterface.call(" function(){ return document.location.href.toString();}"));
			}else{
				curUrl = "stage.constellation.tv";
			}
			_curDomain = curUrl.split(".tv")[0]
			
			
			
		}
		
		public function logToServer(logMessage:String,level:String=LEVEL_DEBUG):void{
			//http://www.constellation.tv/services/log?i=10.206.18.15:17090&k=666&message=pummelme!
			var messageLevel:int
			if(level==LEVEL_DEBUG){
				messageLevel =LEVEL_DEBUG_NUM; 
			}else if(level==LEVEL_WARN){
				messageLevel =LEVEL_WARN_NUM;
			}else if(level== LEVEL_INFO){
				messageLevel =LEVEL_INFO_NUM;
			}else if(level==LEVEL_ERROR){
				messageLevel =LEVEL_ERROR_NUM;
			}else if(level==LEVEL_FATAL){
				messageLevel =LEVEL_FATAL_NUM;
			}
			var thisDate:Date = new Date()
			
			if(ExternalConfig.getInstance().logToDB==true){		
		//	if(ExternalConfig.getInstance().testLocal==false){
					var mediaSprite:MediaPlayerSprite = ExternalConfig.getInstance().mediaPlayerSprite;
					var bandWidth:String = "n\a";
					var curTime:Number = 0;
					var bufferLength:Number = 0;
					var bufferMax:Number = 0;
					var streamIndex:int=0;
					var maxStreamIndex:int =0;
					var isBuffering:Boolean = false;
					if(mediaSprite!=null){
						curTime = mediaSprite.mediaPlayer.currentTime;
						bufferLength = mediaSprite.mediaPlayer.bufferLength;
						
						streamIndex = mediaSprite.mediaPlayer.currentDynamicStreamIndex;
						maxStreamIndex= mediaSprite.mediaPlayer.maxAllowedDynamicStreamIndex;
						isBuffering = mediaSprite.mediaPlayer.buffering;
					}
					
					var videoViewObject:videoView = ExternalConfig.getInstance().videoViewObject;
					bufferMax = AkamaiBandwidthMonitor.getInstance().bufferMax
					
					logMessage="[FP: "+Capabilities.version+"][ver: "+ExternalConfig.getInstance().version+"][Pt: "+videoViewObject.playbackCount+"][ft: "+videoViewObject.dataType+"] [curTime: "+curTime.toFixed(2)+"][curIndex: "+streamIndex+" of "+maxStreamIndex+"][Buffer: "+bufferLength+" of "+bufferMax+"][isBuffering: "+isBuffering+"][BW: "+AkamaiBandwidthMonitor.getInstance().bandwidth+"][Avg BW: "+AkamaiBandwidthMonitor.getInstance().avgBandwidth+"][Peak BW: "+AkamaiBandwidthMonitor.getInstance().peakBandwidth+" : Estimated RAW Max Bytes Per Second: "+AkamaiBandwidthMonitor.getInstance().maxBytesPerSecond+"] "+logMessage;
					
			//}
			}
			
	
			if(messageLevel>=ExternalConfig.getInstance().logLevel){
				if(ExternalConfig.getInstance().testLocal==false){
						var context:LoaderContext = new LoaderContext();
							context.securityDomain = SecurityDomain.currentDomain;
							
					if(ExternalConfig.getInstance().logToDB==true){	
						this._logLoad = new DataLoad(_curDomain+".tv"+this._logPath+"&rand="+new Date().getTime());
						
						this._logLoad.addEventListener(LoadEvent.COMPLETE, this._onComplete);
						this._logLoad.addEventListener(IOErrorEvent.IO_ERROR,this.catchLogIOError);
						this._logLoad.addEventListener(HTTPStatusEvent.HTTP_STATUS,this.httpStatusHandler);
						this._logLoad.urlRequest.method = URLRequestMethod.POST;	
						
						var postData:URLVariables = new URLVariables();
							postData.k = ExternalConfig.getInstance().ticketDecrypted;
							postData.message = logMessage;
						
						this._logLoad.urlRequest.data = postData;
						this._logLoad.start();
					}
				}
				
			}
			var thisDay:String = thisDate.toDateString()
			var thisTime:String = thisDate.toTimeString();
			logMessage = "[" + thisDay+" "+thisTime+ "] "+logMessage;
			
			dispatchEvent(new constellationEvent(constellationEvent.DEBUG_MESSAGE,logMessage));
			trace(logMessage);
		}
		
		protected function httpStatusHandler(event:Event):void
		{
			// TODO Auto-generated method stub
			
		}
		
		protected function catchLogIOError(event:Event):void
		{
			// TODO Auto-generated method stub
			
		}
		
		protected function _onComplete(event:LoadEvent):void
		{
			// TODO Auto-generated method stub
			
		}
		
		public function logToDebug(msg:String, level:String):void
		{
			if(level!=LEVEL_DEBUG){
			
				if(ExternalConfig.getInstance().logToDB==true){
					this.logToServer(msg,level);
				}			
			}else{
				dispatchEvent(new constellationEvent(constellationEvent.DEBUG_MESSAGE,msg));
			}
		}
	}
}

class SingletonEnforcer
{
}