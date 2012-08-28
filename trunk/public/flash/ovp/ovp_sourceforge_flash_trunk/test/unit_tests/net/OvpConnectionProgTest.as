package net {
	import flash.events.*;
	
	import asunit.framework.AsynchronousTestCase;
	import org.openvideoplayer.events.*;
	import org.openvideoplayer.net.*;
	
	public class OvpConnectionProgTest extends AsynchronousTestCase {
		// Note: the tests below are specific to this feed, if you change the URL, you may break several tests
		private const TEST_PROG_URL:String = "http://products.edgeboss.net/download/products/jsherry/testfiles/stream001.flv";
		private var instance:OvpConnection;
		
		//--------------------------------------------------------------------------
		//
		// Construction, setup and tear down
		//
		//--------------------------------------------------------------------------
		
		public function OvpConnectionProgTest(testMethod:String) {
			trace(">>> In OvpConnectionProgTest ctor...");
			super(testMethod);
		}
		
		protected override function setUp():void {
			trace(">>> In OvpConnectionProgTest.setUp()...");
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
			trace(">>> In OvpConnectionProgTest.connecctedHandler()...");
			super.run();
		}
		
		private function errorHandler(e:OvpEvent):void {
			trace(">>> In OvpConnectionProgTest.errorHandler()...");
			trace("Error #" + e.data.errorNumber + " " +e.data.errorDescription + " " + e.currentTarget);
			var ioee:IOErrorEvent = IOErrorEvent(e.clone());
			this.ioErrorHandler(ioee);
		}
		
		protected override function tearDown():void {
			trace(">>> In OvpConnectionProgTest.tearDown()...");
			instance = null;
		}
		
		public override function run():void {
			trace(">>> In OvpConnectionProgTest.run()...");
			instance = new OvpConnection();

			instance.addEventListener(OvpEvent.ERROR,errorHandler);
			instance.addEventListener(NetStatusEvent.NET_STATUS,netStatus);
   			//instance.addEventListener(SecurityErrorEvent.SECURITY_ERROR,netSecurityError);
   			//instance.addEventListener(AsyncErrorEvent.ASYNC_ERROR,asyncError);

			instance.connect(null);
		}
		
		//--------------------------------------------------------------------------
		//
		// Tests
		//
		//--------------------------------------------------------------------------
		
		public function testInstantiated():void {
			assertTrue("OvpConnectionProgTest object instantiated", instance is OvpConnection);
		}
		
		public function testConnect():void {
			trace(">>> In OvpConnectionProgTest.testConnect() - instance.connected "+instance.connected);
			trace("			- server IP = "+instance.serverIPaddress);
			trace("			- instance.netConnection.uri = "+instance.netConnection.uri);
			trace("			- port = "+instance.actualPort);
			trace("			- protocol = "+instance.actualProtocol);
			assertTrue("testConnect - instance.connected should be true", (instance.connected == true));
		}

	}
}

