package net {
	import flash.events.*;
	
	import asunit.framework.AsynchronousTestCase;
	import org.openvideoplayer.events.*;
	import org.openvideoplayer.net.*;
	
	public class OvpConnectionStreamLengthTest extends AsynchronousTestCase {
		// Note: the tests below are specific to this feed, if you change the URL, you may break several tests
		private const HOSTNAME:String = "cp27886.edgefcs.net/ondemand";
		private const FILENAME:String = "14808/nocc_small307K";
		private var instance:OvpConnection;
		private var streamLength:Number;
		
		//--------------------------------------------------------------------------
		//
		// Construction, setup and tear down
		//
		//--------------------------------------------------------------------------
		
		public function OvpConnectionStreamLengthTest(testMethod:String) {
			trace(">>> In OvpConnectionStreamLengthTest ctor...");
			super(testMethod);
		}
		
		protected override function setUp():void {
			trace(">>> In OvpConnectionStreamLengthTest.setUp()...");
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
			trace(">>> In OvpConnectionStreamLengthTest.connecctedHandler()...");
			instance.requestStreamLength(FILENAME);
		}
		
		private function streamLengthHandler(e:OvpEvent):void {
			this.streamLength = e.data.streamLength;
			super.run();
		}
		
		private function errorHandler(e:OvpEvent):void {
			trace(">>> In OvpConnectionStreamLengthTest.errorHandler()...");
			trace("Error #" + e.data.errorNumber + " " +e.data.errorDescription + " " + e.currentTarget);
			var ioee:IOErrorEvent = IOErrorEvent(e.clone());
			this.ioErrorHandler(ioee);
		}
		
		protected override function tearDown():void {
			trace(">>> In OvpConnectionStreamLengthTest.tearDown()...");
			instance = null;
		}
		
		public override function run():void {
			trace(">>> In OvpConnectionStreamLengthTest.run()...");
			instance = new OvpConnection();

			instance.addEventListener(OvpEvent.ERROR,errorHandler);
			instance.addEventListener(NetStatusEvent.NET_STATUS,netStatus);
			instance.addEventListener(OvpEvent.STREAM_LENGTH,streamLengthHandler); 


			instance.connect(HOSTNAME);
		}
		
		//--------------------------------------------------------------------------
		//
		// Tests
		//
		//--------------------------------------------------------------------------
		
		public function testInstantiated():void {
			assertTrue("OvpConnectionStreamLengthTest object instantiated", instance is OvpConnection);
		}
		
		public function testStreamLength():void {		
			trace(">>> In OvpConnectionStreamLengthTest.testStreamLength() - instance.connected "+instance.connected);
			trace("			- streamLength = "+this.streamLength+" secs");
			assertTrue("testStreamLength - streamLength should be > 0", (this.streamLength > 0));
		}

	}
}
