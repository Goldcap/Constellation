package net
{
	import com.adobe.crypto.MD5;
	import com.zeusprod.Log;
	
	import events.NetEvent;
	
	import flash.events.EventDispatcher;
	import flash.events.NetStatusEvent;
	import flash.events.SecurityErrorEvent;
	import flash.net.NetConnection;
	import flash.net.Responder;
	import com.zeusprod.Log;
	
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
			Log.traceMsg("Got to FMSConnection", Log.LOG_TO_CONSOLE);
		}
		
		public function connect( pubOrView:String  ):void
		{
			
			Log.traceMsg("Got to FMSConnection.connect", Log.LOG_TO_CONSOLE);
			if (!this.nc) this.nc = new NetConnection();
			
			nc.client = this;
			nc.addEventListener( NetStatusEvent.NET_STATUS, handleNetStatus);
			nc.addEventListener(SecurityErrorEvent.SECURITY_ERROR, handleSecurityError);
           
			nc.connect(  pubOrView, sessionKey, "", "2.0" );
		} 
		
		private function handleSecurityError(evt:SecurityErrorEvent):void {
			Log.traceMsg("Got to handleSecurityError " + evt.text, Log.LOG_TO_CONSOLE);
		}
		
		public function setChallenge( sessionID:String, challenge:String):void
		{

			Log.traceMsg("Got to FMSConnection.setChallenge", Log.LOG_TO_CONSOLE);
			
			Log.traceMsg(sessionID + "," + challenge, Log.LOG_TO_CONSOLE);

			sessionKey = sessionKey+":"+sessionID;
			password = MD5.hash(challenge+":"+AKAMAI_PASSWORD+MD5.hash(this.sessionKey+":"+challenge+":"+AKAMAI_PASSWORD));

			Log.traceMsg("challenge-response: " + password, Log.LOG_TO_CONSOLE);

			
			nc.call("ClientLogin", null, sessionKey, password, Log.LOG_TO_CONSOLE);
		}
		
		private function handleNetStatus( evt:NetStatusEvent ):void
		{

			Log.traceMsg ("FMSConnection.handleNetStatus " + evt.info.code, Log.LOG_TO_CONSOLE);
			
			for (var prop:String in evt.info) {
				Log.traceMsg("Got to FMSConnection.netConnectionStatus " + prop + " " + evt.info[prop], Log.LOG_TO_CONSOLE);
			}	
		
			if (evt.info.code == 'NetConnection.Connect.Success')
			{
				dispatchEvent( new NetEvent(NetEvent.NET_CONNECTION_SUCCESSFUL ) );
			} else if (evt.info.code == 'NetConnection.Connect.Closed') {
				Log.traceMsg ("FMSConnection.handleNetStatus unexpectedly closed? " + evt.info.code, Log.ALERT);
			
			} else {
				Log.traceMsg ("FMSConnection.handleNetStatus failure? " + evt.info.code, Log.LOG_TO_CONSOLE);
			
				dispatchEvent( new NetEvent(NetEvent.NET_CONNECTION_FAILURE ) );
			}
		}
		
		public function onClientLogin( info:Object ):void
		{
			var name:String = streamName+'@'+streamID;
			
			Log.traceMsg ("FMSConnection.onClientLogin " + info.code, Log.LOG_TO_CONSOLE);
			switch (info.code) 
			{
				case "Akamai.Connect.Success" :
					nc.call("FCPublish", new Responder( handleResponse ), name , sessionKey, password, 'primary');
					break;
				default: 
					Log.traceMsg ("Non-success! " + info.code, Log.LOG_TO_CONSOLE);
					break;
			}
			

			function handleResponse( info:Object ):void
			{
				Log.traceMsg ('Response = ' + info, Log.LOG_TO_CONSOLE);
			}

		}
		
		private function handleResponse( info:Object ):void {
			Log.traceMsg ('Response = ' + info, Log.LOG_TO_CONSOLE);
		}
			
		public function onFCPublish( info:Object):void
		{
			Log.traceMsg("onFCPublish: " + info.code + ", description: " + info.description, Log.LOG_TO_CONSOLE);
			switch (info.code) {
				case "NetStream.Publish.Start": 
					dispatchEvent( new NetEvent(NetEvent.FC_PUBLISH_START ) );
					break;
				case "NetStream.Publish.BadName":
					Log.traceMsg("onFCPublish NetStream.Publish.BadName: " + info.description, Log.ALERT);
					break;
				default:
					Log.traceMsg("onFCPublish unknown code: " + info.code + ": " + info.description, Log.ALERT);
					break;
			} 
		}
		
		public function onBWDone( ... args):void{};
		
		public function onBWCheck( ... args):void{};
		
		public function onFCSubscribe( info:Object ):void
		{

			Log.traceMsg ('onFCSubscribe', Log.LOG_TO_CONSOLE)
			Log.traceMsg (info, Log.LOG_TO_CONSOLE);
		}
		public function onFCUnpublish( ... args ):void { Log.traceMsg  ('onFCUnpublish', Log.LOG_TO_CONSOLE) }
		
		public function onMetaData( ... args ):void{ 
			Log.traceMsg ('onMetaData', Log.LOG_TO_CONSOLE);
		}

	}
}