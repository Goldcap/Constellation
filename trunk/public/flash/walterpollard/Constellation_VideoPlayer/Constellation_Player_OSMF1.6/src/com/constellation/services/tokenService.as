package com.constellation.services
{
	import com.adobe.serialization.json.JSONDecoder;
	import com.constellation.config.errorConfig;
	import com.constellation.events.constellationEvent;
	import com.constellation.externalConfig.ExternalConfig;
	import com.constellation.managers.loggingManager;
	import com.constellation.parsers.dataParser;
	import com.constellation.utilities.StringUtil;
	import com.constellation.view.messageView;
	import com.sierrastarstudio.utils.tracer;
	import com.zeusprod.AEScrypto;
	
	import flash.events.Event;
	import flash.events.EventDispatcher;
	import flash.events.IOErrorEvent;
	import flash.events.TimerEvent;
	import flash.net.URLLoader;
	import flash.net.URLRequest;
	import flash.net.URLRequestMethod;
	import flash.net.URLVariables;
	import flash.utils.Timer;

	public class tokenService extends EventDispatcher
	{
		private var _classname:String = "com.constellation.services.tokenService";
		private static var instance:tokenService;
		private var _timeStampServer:Number;
		private var TIMESTAMP_INTERVAL:int =10;
		
		private var _originalTicketParam:String;
		private var _ticketParams:String;
		private var _hmac:String;
		private var _tokenUrlLoader:URLLoader;
		private var _timeStampTimer:Timer;
		private var _dataPostStr:String;
		private var _prepData:String;
		private var _tokenUrlRequest:URLRequest;
		private var _testLocal:Boolean;
		private var _currentErrorCode:Object;
		private var MIN_TIMESTAMP:Number=1300000000;
		
		
		
		public function tokenService(enforcer:SingletonEnforcer)
		{
		}
		public static function getInstance():tokenService
		{
			
			if (tokenService.instance == null)
			{
				tokenService.instance = new tokenService(new SingletonEnforcer());
			}
			return tokenService.instance;
		}
		
		public function init():void
		{
			this._testLocal = ExternalConfig.getInstance().testLocal;
			
			//create the timer for timestampping
			this._timeStampTimer = new Timer(TIMESTAMP_INTERVAL);
			this._timeStampTimer.addEventListener(TimerEvent.TIMER,onIncrementTimeStamp);
			//check for an initTimeStamp 
			if(!isNaN(ExternalConfig.getInstance().initTimeStamp)){
				this._timeStampServer = ExternalConfig.getInstance().initTimeStamp;
				this._timeStampTimer.start();
			}
			
			//get and pull the token
			
			this._originalTicketParam = ExternalConfig.getInstance().ticketParams;
			this._ticketParams = StringUtil.replaceSpacesWithPlus(this._originalTicketParam);
			
			var decryptedTicketParam:String = AEScrypto.decrypt(this._ticketParams);
			
			var newTicketParam:String = decryptedTicketParam.split(AEScrypto.DELIMITER)[0];
			
			this._ticketParams = newTicketParam;
			//store the decrypted ticket
			ExternalConfig.getInstance().ticketDecrypted = this._ticketParams;
			
			var newFilmNumber:int = int(decryptedTicketParam.split(AEScrypto.DELIMITER)[1]);
			
			ExternalConfig.getInstance().filmNumber = newFilmNumber;
			
			var newHMAC:String = decryptedTicketParam.split(AEScrypto.DELIMITER)[2];
			
			this._hmac = newHMAC;
			//log that the user has valid decrypt ticket
			tracer.log("User has valid ticket : Attempting Token",_classname,loggingManager.LEVEL_INFO);
			
			this.makeTokenRequest();
		}
		
		private function makeTokenRequest():void
		{
			this._tokenUrlLoader = new URLLoader();
			var streamNumber:int = ExternalConfig.getInstance().filmNumber;
			this._dataPostStr = this._ticketParams+AEScrypto.DELIMITER+streamNumber+AEScrypto.DELIMITER+this._hmac+AEScrypto.DELIMITER+this._timeStampServer
			//	tracer.log("********************  new encrypt string "+this._dataPostStr,_classname);
			var encryptDataPost:String = AEScrypto.encrypt(this._dataPostStr);
			var postData:URLVariables = new URLVariables();
			//postData.k = ticketParams;
			this._prepData = AEScrypto.reencrypt(encryptDataPost);
			
			postData.k = encryptDataPost;//this._prepData;
			
			/*	if(this._testLocal==true){
			// For testing
			var serverTest:String = "/services/Tokenizer/"+this.streamNumber+"/map.smil?k=" + this._prepData;
			var localTest:String = ExternalConfig.getInstance().smilTestPath
			tokenUrlRequest = new URLRequest(serverTest);	
			
			}else{
			/* NOT TESTING */
			this._tokenUrlRequest = new URLRequest("/services/Tokenizer/"+streamNumber+"/map.smil?k=" + encryptDataPost);//this._prepData);
			//}
			this._tokenUrlRequest.method = URLRequestMethod.POST;	
			
			//tokenUrlRequest.contentType = "application/xhtml+xml"
			this._tokenUrlRequest.data = postData;
			
			//debug("Hitting: " + tokenUrlRequest.url);
			//debug("With: " + tokenUrlRequest.data);
			/* NOT TESTING */
			
			this._tokenUrlLoader.addEventListener(Event.COMPLETE, tokenUrlLoaderComplete);
			this._tokenUrlLoader.addEventListener(IOErrorEvent.IO_ERROR,onTokenHTTPError);
			
			if(this._testLocal==false){
				ExternalConfig.getInstance().smilPath = this._tokenUrlRequest.url
				this._tokenUrlLoader.load(this._tokenUrlRequest);
			}
		}
		public function parseJSON(rawJSON:Object = null):void{
			 
			
			var rawString:String = decodeURI(rawJSON.toString());
			
			var jsonDecode:JSONDecoder = new JSONDecoder(rawString,false);
			var decodeObj:Object = jsonDecode.getValue()
			var filePath:String = decodeObj["fileURL"]
			tracer.log("rawString "+rawString+"  filePath  "+filePath,_classname,loggingManager.LEVEL_DEBUG);
			ExternalConfig.getInstance().smilPath = filePath;
			dispatchEvent(new constellationEvent(constellationEvent.JSON_LOADED,filePath));
		}
		// Token Loaded
		private function tokenUrlLoaderComplete(evt:Event):void 
		{
			//tracer.log("TOKEN LOADED Attempting to hand to parse",_classname);
			
			try{
				if(	this._timeStampTimer.running==true){
					this._timeStampTimer.stop();
				}
				var rawData:Object = evt.currentTarget.data;
				var returnedSMIL:XML
				if(rawData.toString().indexOf("<smil")>-1){
							var loadedData:XML = new XML(rawData);
							
							if(loadedData.toXMLString().indexOf("unauth")>-1){
								
								ExternalConfig.getInstance().currentErrorCode = errorConfig.auth_authorizeError
								loggingManager.getInstance().logToServer("Token server returned UNAUTH",loggingManager.LEVEL_FATAL);
								
								if (this._testLocal ==true) {
									messageView.getInstance().setMessage("unauth user ticketParam: "+this._originalTicketParam+"\nk: "+this._prepData+"\ndecrypted info "+this._dataPostStr,false);
								}else{
									messageView.getInstance().setMessage(ExternalConfig.getInstance().defaultErrorMessage+errorConfig.auth_authorizeError,false)
								}
							
							}
							returnedSMIL	 = loadedData as XML;
							dispatchEvent(new constellationEvent(constellationEvent.SMIL_LOADED,returnedSMIL));
							
				}else{
					
					//we have either a smil or json data
					var pathArray:Array = rawData.toString().split(":");
					if(pathArray[0].toString().toLowerCase().indexOf("fileurl")>-1){
						this.parseJSON(rawData)
						
					}else{
						returnedSMIL = loadedData as XML;
						dispatchEvent(new constellationEvent(constellationEvent.SMIL_LOADED,returnedSMIL));
					}
				}
		
			}catch(err:Error){
				ExternalConfig.getInstance().currentErrorCode = errorConfig.init_ParseDataError
				loggingManager.getInstance().logToServer("Token server returned Error in parsing - Data formatting Error",loggingManager.LEVEL_FATAL);
				
				if (this._testLocal ==true) {
					messageView.getInstance().setMessage("unauth user ticketParam: "+this._originalTicketParam+"\nk: "+this._prepData+"\ndecrypted info "+this._dataPostStr,false);
				}else{
					messageView.getInstance().setMessage(ExternalConfig.getInstance().defaultErrorMessage+errorConfig.init_ParseDataError,false)
				}
				
			}
			
			
		}
	
		protected function onTokenHTTPError(event:Event):void
		{
			//had an error getting auth and token
			ExternalConfig.getInstance().currentErrorCode = errorConfig.auth_tokenHTTPErrorCode;
			loggingManager.getInstance().logToServer("HTTP error getting token",loggingManager.LEVEL_FATAL);
		
		messageView.getInstance().setMessage(ExternalConfig.getInstance().defaultErrorMessage+errorConfig.auth_tokenHTTPErrorCode,false);
			
		}
		
		
		
		protected function onIncrementTimeStamp(event:TimerEvent):void
		{
			if(this._timeStampServer==0){
				this._timeStampServer = ExternalConfig.getInstance().initTimeStamp;
				
			}
			this._timeStampServer +=TIMESTAMP_INTERVAL
			//tracer.log("timestamp increment "+this._timeStampServer,_classname);
			if(this._timeStampServer<MIN_TIMESTAMP){
				this._timeStampServer=-1
			}
		}

		

		
		
	}
}
class SingletonEnforcer{}