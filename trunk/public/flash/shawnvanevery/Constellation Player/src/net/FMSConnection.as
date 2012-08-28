package net
{
	import com.adobe.crypto.MD5;
	
	import events.NetEvent;
	
	import flash.events.EventDispatcher;
	import flash.events.NetStatusEvent;
	import flash.events.SecurityErrorEvent;
	import flash.net.NetConnection;
	import flash.net.Responder;
	
	import flash.events.StatusEvent;
	
	public class FMSConnection extends EventDispatcher
	{
		
		private const AKAMAI_PASSWORD:String ="co2010"; 
		
		public var entryPoint:String;
		public var playLive:String;
		
		public var nc:NetConnection;
		
		private var sessionKey:String;
		private var password:String;
		private var streamName:String;
		private var streamID:String;
				
		public function debug(code:String):void {
			var statusEvent:StatusEvent = new StatusEvent("DEBUG");
			statusEvent.code = code;
			dispatchEvent(statusEvent);
		}		
		
		/**
		 * 
		 * @param cpCode
		 * @param streamID
		 * @param streamName
		 * 
		 */		
		public function FMSConnection( cpCode:String, streamID:String, streamName:String ):void
		{
			this.playLive = "rtmp://cp" + cpCode + ".live.edgefcs.net/live/";
			this.entryPoint = "rtmp://p.ep" + streamID + ".i.akamaientrypoint.net/EntryPoint/";
			//this.playLive = "rtmp://reflectivity.globalchangemultimedia.net/constellation/";
			//this.entryPoint = "rtmp://reflectivity.globalchangemultimedia.net/constellation/";
			this.sessionKey = "encoder:1.2.3.4:" + cpCode;
			this.streamName = streamName;
			this.streamID = streamID;
			debug("Got to FMSConnection");
		}
		
		public function connect( pubOrView:String  ):void
		{
			
			debug("Got to FMSConnection.connect");
			if (!this.nc) this.nc = new NetConnection();
			
			nc.client = this;
			nc.addEventListener( NetStatusEvent.NET_STATUS, handleNetStatus);
			nc.addEventListener(SecurityErrorEvent.SECURITY_ERROR, handleSecurityError);
           
			nc.connect(  pubOrView, sessionKey, "", "2.0" );
		} 
		
		private function handleSecurityError(evt:SecurityErrorEvent):void {
			debug("Got to handleSecurityError " + evt.text);
		}
		
		public function setChallenge( sessionID:String, challenge:String):void
		{

			debug("Got to FMSConnection.setChallenge");
			
			debug(sessionID + "," + challenge);

			sessionKey = sessionKey+":"+sessionID;
			password = MD5.hash(challenge+":"+AKAMAI_PASSWORD+MD5.hash(this.sessionKey+":"+challenge+":"+AKAMAI_PASSWORD));

			debug("challenge-response: " + password);

			
			nc.call("ClientLogin", null, sessionKey, password);
		}
		
		private function handleNetStatus( evt:NetStatusEvent ):void
		{

			debug ("FMSConnection.handleNetStatus " + evt.info.code);
			
			for (var prop:String in evt.info) {
				debug("Got to FMSConnection.netConnectionStatus " + prop + " " + evt.info[prop]);
			}	
		
			if (evt.info.code == 'NetConnection.Connect.Success')
			{
				dispatchEvent( new NetEvent(NetEvent.NET_CONNECTION_SUCCESSFUL ) );
			} else if (evt.info.code == 'NetConnection.Connect.Closed') {
				debug ("FMSConnection.handleNetStatus unexpectedly closed? " + evt.info.code);
			
			} else {
				debug ("FMSConnection.handleNetStatus failure? " + evt.info.code);
			
				dispatchEvent( new NetEvent(NetEvent.NET_CONNECTION_FAILURE ) );
			}
		}
		
		public function onClientLogin( info:Object ):void
		{
			var name:String = streamName+'@'+streamID;
			
			debug ("FMSConnection.onClientLogin " + info.code);
			switch (info.code) 
			{
				case "Akamai.Connect.Success" :
					nc.call("FCPublish", new Responder( handleResponse ), name , sessionKey, password, 'primary');
					break;
				default: 
					debug ("Non-success! " + info.code);
					break;
			}
			

			function handleResponse( info:Object ):void
			{
				debug ('Response = ' + info);
			}

		}
		
		private function handleResponse( info:Object ):void {
			debug ('Response = ' + info);
		}
			
		public function onFCPublish( info:Object):void
		{
			debug("onFCPublish: " + info.code + ", description: " + info.description);
			switch (info.code) {
				case "NetStream.Publish.Start": 
					dispatchEvent( new NetEvent(NetEvent.FC_PUBLISH_START ) );
					break;
				case "NetStream.Publish.BadName":
					debug("onFCPublish NetStream.Publish.BadName: " + info.description);
					break;
				default:
					debug("onFCPublish unknown code: " + info.code + ": " + info.description);
					break;
			} 
		}
		
		public function onBWDone( ... args):void{};
		
		public function onBWCheck( ... args):void{};
		
		public function onFCSubscribe( info:Object ):void
		{
			debug ('onFCSubscribe');
			//debug (info);
		}
		public function onFCUnpublish( ... args ):void { debug  ('onFCUnpublish'); }
		
		public function onMetaData( ... args ):void{ 
			debug ('onMetaData');
		}

	}
}