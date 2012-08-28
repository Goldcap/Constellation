package com.llnw.data 
{
	import flash.events.Event;
	import flash.events.HTTPStatusEvent;
	import flash.events.IOErrorEvent;
	import flash.events.SecurityErrorEvent;
	import flash.net.URLLoader;
	import flash.net.URLRequest;
	import flash.net.URLVariables;
	import flash.net.URLRequestMethod;
	import flash.net.URLLoaderDataFormat;
	/**
	 * ...
	 * @author 
	 */
	public class SendData 
	{
		
		private var _defaultPath:String = "http://localhost:8080/csr/queryTerm"
		public function SendData() 
		{
			//nada
		}
		
		//value is the collected data string of name/value pairs. 
		//this will be used to send the data to the reporting logs.
		public function shipData(value:String):void {
			trace("THE VALUE: ")
			trace(value)
			if (value == "") {
				value = "data=none";
			}
			var requestVars:URLVariables = new URLVariables(value);
			//	requestVars.object_name = "sentData";
			//	requestVars.test = "hiBill"
			//	requestVars.taptap = "thisThingOn"
			//	requestVars.sent = "doingSo";
			//	requestVars.cache = new Date().getTime();
			 
			var request:URLRequest = new URLRequest();
				
			
				request.url = _defaultPath;
				request.method = URLRequestMethod.GET;
				request.data = requestVars;
			 
				for (var prop:String in requestVars) {
					trace("Sent " + prop + " as: " + requestVars[prop]);
					
				}
			 
			var loader:URLLoader = new URLLoader();
				loader.dataFormat = URLLoaderDataFormat.TEXT;
				loader.addEventListener(Event.COMPLETE, loaderCompleteHandler);
				loader.addEventListener(HTTPStatusEvent.HTTP_STATUS, httpStatusHandler);
				loader.addEventListener(SecurityErrorEvent.SECURITY_ERROR, securityErrorHandler);
				loader.addEventListener(IOErrorEvent.IO_ERROR, ioErrorHandler);
			 
			try
			{
				loader.load(request);
			}
			catch (error:Error)
			{
				trace("Unable to load URL");
			}
			 
		}
		
		private function loaderCompleteHandler(e:Event):void
		{
			try{
			var variables:URLVariables = new URLVariables( e.target.data );
			if(variables.success)
			{
				
				trace(variables.success);
			}
			}catch (error:Error) {
				trace("error caught on loaderCompleteHandler event")
			}
		}
		private function httpStatusHandler (e:Event):void
		{
			//trace("httpStatusHandler:" + e);
		}
		private function securityErrorHandler (e:Event):void
		{
			trace("securityErrorHandler:" + e);
		}
		private function ioErrorHandler(e:Event):void
		{
			trace("ioErrorHandler: " + e);
		}
		
		public function set defaultPath(value:String):void {
			_defaultPath = value;
		}
		
		
	}
	
}