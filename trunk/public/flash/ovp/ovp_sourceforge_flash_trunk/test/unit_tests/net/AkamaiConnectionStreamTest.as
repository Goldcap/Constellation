package net {
	import flash.events.*;
	
	import asunit.framework.AsynchronousTestCase;
	import org.openvideoplayer.events.*;
	import org.openvideoplayer.net.*;
	import com.akamai.net.*;
	
	public class AkamaiConnectionStreamTest extends AsynchronousTestCase {
		// Note: the tests below are specific to this feed, if you change the URL, you may break several tests
		private const HOSTNAME:String = "cp27886.edgefcs.net/ondemand";
		private const FILENAME:String = "14808/nocc_small307K";
		private var instance:AkamaiConnection;
		
		//--------------------------------------------------------------------------
		//
		// Construction, setup and tear down
		//
		//--------------------------------------------------------------------------
		
		public function AkamaiConnectionStreamTest(testMethod:String) {
			trace(">>> In AkamaiConnectionStreamTest ctor...");
			super(testMethod);
		}
		
		protected override function setUp():void {
			trace(">>> In AkamaiConnectionStreamTest.setUp()...");
		}

		private function netStatus(e:NetStatusEvent):void {
			trace(">>> In OvpConnectionStreamTest.netStatus() - event.info="+e.info);
			switch (e.info.code) {
				case "NetConnection.Connect.Success":
					connectedHandler(e);
					break;
				case "NetConnection.Connect.Refjected":
					trace(">>>   Connected rejected:"+e.info.description);
					this.ioErrorHandler(IOErrorEvent(e.clone()));
					break;
			}
		}

		private function connectedHandler(e:NetStatusEvent):void {
			trace(">>> In AkamaiConnectionStreamTest.connecctedHandler()...");
			super.run();
		}
		
		private function errorHandler(e:OvpEvent):void {
			trace(">>> In AkamaiConnectionStreamTest.errorHandler()...");
			trace("Error #" + e.data.errorNumber + " " +e.data.errorDescription + " " + e.currentTarget);
			var ioee:IOErrorEvent = new IOErrorEvent(IOErrorEvent.IO_ERROR, false, false, e.data.errorDescription);
			this.ioErrorHandler(ioee);
		}
		
		protected override function tearDown():void {
			trace(">>> In AkamaiConnectionStreamTest.tearDown()...");
			instance = null;
		}
		
		public override function run():void {
			trace(">>> In AkamaiConnectionStreamTest.run()...");
			instance = new AkamaiConnection();

			instance.addEventListener(OvpEvent.ERROR,errorHandler);
			instance.addEventListener(NetStatusEvent.NET_STATUS,netStatus);
   			//instance.addEventListener(SecurityErrorEvent.SECURITY_ERROR,netSecurityError);
   			//instance.addEventListener(AsyncErrorEvent.ASYNC_ERROR,asyncError);

			instance.connect(HOSTNAME);
		}
		
		//--------------------------------------------------------------------------
		//
		// Tests
		//
		//--------------------------------------------------------------------------
		
		public function testInstantiated():void {
			assertTrue("AkamaiConnectionStreamTest object instantiated", instance is AkamaiConnection);
		}
		
		public function testConnect():void {		
			trace(">>> In AkamaiConnectionStreamTest.testConnect() - instance.connected "+instance.connected);
			trace("			- server IP = "+instance.serverIPaddress);
			trace("			- instance.netConnection.uri = "+instance.netConnection.uri);
			trace("			- port = "+instance.actualPort);
			trace("			- protocol = "+instance.actualProtocol);
			assertTrue("testConnect - instance.connected should be true", (instance.connected == true));

		}
	}
}
