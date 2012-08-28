package com.constellation.controllers
{
	// JSON Library, for parsing token request
	import com.adobe.serialization.json.*;
	import com.constellation.externalConfig.ExternalConfig;
	import com.constellation.managers.loggingManager;
	import com.sierrastarstudio.utils.tracer;
	
	import com.constellation.events.constellationEvent;
	
	import flash.events.Event;
	import flash.events.EventDispatcher;
	import flash.events.HTTPStatusEvent;
	import flash.events.IEventDispatcher;
	import flash.events.IOErrorEvent;
	import flash.events.TimerEvent;
	import flash.external.ExternalInterface;
	import flash.net.URLRequest;
	import flash.net.URLRequestMethod;
	import flash.net.URLVariables;
	import flash.system.LoaderContext;
	import flash.system.Security;
	import flash.system.SecurityDomain;
	import flash.utils.Timer;
	
	import org.casalib.display.CasaMovieClip;
	import org.casalib.events.LoadEvent;
	import org.casalib.load.DataLoad;
	
	public class heartbeatController extends EventDispatcher
	{
		//easy way to name the class when doing traces to see where from
		private var _classname:String = "com.constellation.controllers.heartbeatController";
		
		//types of heart attacks
		public static var SKIPPED_BEATS_ATTACK:String = "skippedBeats"//in errorConfig - 001
		public static var RESPONSE_ATTACK:String = "response";//in errorConfig - 002
		public static var SERVER_REJECT_ATTACK:String = "serverReject";//in errorConfig - 003
		
		
		private var _hbLoad:DataLoad;
		private var _hbTimer:Timer;
		private var _heartbeatPath:String;
		private var _heartbeatInProgress:Boolean = false;
		private var _skippedBeats:int = 0;
		private var _inProgressFailLimit:int;//how many times can an inprogress call fail
		private var _progressFailCount:int;
		private var _runningBeat:Boolean = false;
		
		public function heartbeatController(hbPath:String = null)
		{
			//set the path if given, or default to the config value
			if(hbPath==null){
				this._heartbeatPath = ExternalConfig.getInstance().heartbeatPath+ExternalConfig.getInstance().heartbeatIP+":"+ExternalConfig.getInstance().heartbeatPort;	
			}else{
				this._heartbeatPath = hbPath;
			}
			this._inProgressFailLimit = ExternalConfig.getInstance().heartbeatProgressFailLimit;
			
		}
		public function startHeartBeat():void{
			if(this._runningBeat==false){
				this.init();
			}
		}
		private function init():void{
			this._runningBeat = true;
			//set up the url call
			//TODO - possible need to update the heartbeatPath with data. Would like to split this out to become populated with data if needed
		
			Security.allowDomain("*.constellation.tv");
			var curUrl:String
			var curDomain:String
		if(ExternalInterface.available==true){
				curUrl=  String( ExternalInterface.call(" function(){ return document.location.href.toString();}"));
			 	curDomain= curUrl.split(".tv")[0]
		}
			var context:LoaderContext = new LoaderContext();
				context.securityDomain = SecurityDomain.currentDomain;
				
			this._hbLoad = new DataLoad(curDomain+".tv"+this._heartbeatPath);
			this._hbLoad.addEventListener(LoadEvent.COMPLETE, this._onComplete);
			this._hbLoad.addEventListener(IOErrorEvent.IO_ERROR,this.catchHBIOError);
			this._hbLoad.addEventListener(HTTPStatusEvent.HTTP_STATUS,this.httpStatusHandler);
			this._hbLoad.urlRequest.method = URLRequestMethod.POST;	
			
			//set up the timer
			this._hbTimer = new Timer(ExternalConfig.getInstance().heartbeatInterval*1000);
			this._hbTimer.addEventListener(TimerEvent.TIMER,callHeartBeat);
			this._hbTimer.start();
		}
		/**
		 * The action of loading data with the heartbeat information 
		 * @param evt
		 * 
		 */
		private function callHeartBeat(evt:TimerEvent):void{
			
				
		tracer.log("heart beat _heartbeatInProgress @ "+this._heartbeatPath+"            inProgress: "+_heartbeatInProgress+" skips  "+this._skippedBeats+"   _progressFailCount "+_progressFailCount,this._classname)	
			if(this._heartbeatInProgress==false){
				this._progressFailCount = 0;
				//only call another hb if we're not waiting for a returned beat
				var postData:URLVariables = new URLVariables();
					postData.k = ExternalConfig.getInstance().ticketParams;
					postData.filmStartTime = ExternalConfig.getInstance().filmStartTime;
					
				this._hbLoad.urlRequest.data = postData;
				this._hbLoad.start();
			}else{
				//have we failed the number of times we're allowing a heartbeat in progress to linger?
				if(this._progressFailCount>this._inProgressFailLimit){
						
				//	dispatchEvent(new constellationEvent(constellationEvent.HEART_ATTACK,RESPONSE_ATTACK));
					loggingManager.getInstance().logToServer("HEART ATTACK progressFailCount : "+this._progressFailCount+" failLimit "+this._inProgressFailLimit);
				}
				this._progressFailCount+=1;
			}
			
			this._heartbeatInProgress = true;
			
			
		}
		
		/**
		 *Called through heartbeat timer and passes returned data to be parsed
		 * @param e
		 * 
		 */
		protected function _onComplete(e:LoadEvent):void {
			this._heartbeatInProgress = false;
		
		
			if(ExternalConfig.getInstance().testLocal==true){
				var testString:String = '{"heartResponse":{"status":"false","message":"heart message here"}}';
				this.parseHeartBeatReturn(testString);//let the parser deal with the json
			}else{
				this.parseHeartBeatReturn(this._hbLoad.dataAsString);//let the parser deal with the json
			}
			
			
		}
		
		private function parseHeartBeatReturn(heartDataString:String):void
		{
			try{
					var rawHeartJSON:Object = JSON.decode(heartDataString);
					var heartResult:Object = rawHeartJSON["heartResponse"]
					
					var heartPassed:Boolean 
					if(heartResult["status"].toString().toLowerCase()=="true"){
						heartPassed = true;
					}else{
						heartPassed = false;
					}
					var heartMessage:String = heartResult["message"]
					
					if(heartPassed==false){
						if(this._hbTimer.running==true){
							this._hbTimer.stop();
						}
					//	dispatchEvent(new constellationEvent(constellationEvent.HEART_ATTACK,SERVER_REJECT_ATTACK));
						loggingManager.getInstance().logToServer("HEART ATTACK SERVER REJECT status: "+heartResult["status"].toString().toLowerCase()+" heartMessage "+heartMessage);
					}else{
						this.resetSkippedBeats();
					}
					
					
			}catch(err:Error){
				tracer.log("error parsing heart beat "+heartDataString,_classname);
				loggingManager.getInstance().logToServer("HEART BEAT failed parse - error in JSON format "+rawHeartJSON);
				this.heartSkip();
			}
		}
		private function catchHBIOError(evt:IOErrorEvent):void {
			trace("catchHBIOError evt: "+evt	);
			this._heartbeatInProgress = false;
			this.heartSkip();
			
		}
		private function heartSkip():void{
			this._skippedBeats+=1
				
				//lets see if we have a heart attack on our hands
				if(this._skippedBeats>ExternalConfig.getInstance().heartbeatLimit){
					loggingManager.getInstance().logToServer("HEART ATTACK - skipped beats tripped "+this._skippedBeats+" limit skipped "+ExternalConfig.getInstance().heartbeatLimit);
					//reset skipped beats
					this.resetSkippedBeats();
					
				//	dispatchEvent(new constellationEvent(constellationEvent.HEART_ATTACK,SKIPPED_BEATS_ATTACK));

					if(this._hbTimer.running==true){
						this._hbTimer.stop();
					}
				}
		}
		
		private function resetSkippedBeats():void
		{
			this._skippedBeats = 0;
		}
		
		private function httpStatusHandler(evt:HTTPStatusEvent):void {
			this._heartbeatInProgress = false;
			var returnStatus:String = evt.status as String;
			
			if (returnStatus != "200" && returnStatus != "0")
				this.heartSkip();
		}
	}
}