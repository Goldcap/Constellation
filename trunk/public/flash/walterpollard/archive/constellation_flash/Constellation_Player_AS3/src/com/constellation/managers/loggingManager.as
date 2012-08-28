/**
 * Manages how to receive and send logging messages
 */ 
package com.constellation.managers
{
	import com.constellation.externalConfig.ExternalConfig;
	
	import flash.events.Event;
	import flash.events.EventDispatcher;
	import flash.events.HTTPStatusEvent;
	import flash.events.IEventDispatcher;
	import flash.events.IOErrorEvent;
	import flash.external.ExternalInterface;
	import flash.net.URLRequestMethod;
	import flash.net.URLVariables;
	import flash.system.LoaderContext;
	import flash.system.Security;
	import flash.system.SecurityDomain;
	
	import org.casalib.display.CasaMovieClip;
	import org.casalib.events.LoadEvent;
	import org.casalib.load.DataLoad;
	
	public class loggingManager
	{
		private static var instance:loggingManager;
		private var _classname:String = "com.constellation.managers.loggingManager";
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
			Security.allowDomain("constellation.tv");
			var curUrl:String
			if(ExternalInterface.available){
				curUrl =  String( ExternalInterface.call(" function(){ return document.location.href.toString();}"));
			}else{
				curUrl = "stage.constellation.tv";
			}
			_curDomain = curUrl.split(".tv")[0]
			
			
			
		}
		public function logToServer(logMessage:String):void{
			//http://www.constellation.tv/services/log?i=10.206.18.15:17090&k=666&message=pummelme!
			var context:LoaderContext = new LoaderContext();
			context.securityDomain = SecurityDomain.currentDomain;
			
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
	}
}

class SingletonEnforcer
{
}