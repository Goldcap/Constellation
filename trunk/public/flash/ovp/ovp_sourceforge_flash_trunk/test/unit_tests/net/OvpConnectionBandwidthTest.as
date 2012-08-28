package net {
	import flash.events.*;
	
	import asunit.framework.AsynchronousTestCase;
	import org.openvideoplayer.events.*;
	import org.openvideoplayer.net.*;
	
	public class OvpConnectionBandwidthTest extends AsynchronousTestCase {
		// Note: the tests below are specific to this feed, if you change the URL, you may break several tests
		private const HOSTNAME:String = "cp27886.edgefcs.net/ondemand";
		private const FILENAME:String = "14808/nocc_small307K";
		private var instance:OvpConnection;
		private var bandwidth:Number;
		private var latency:Number;
		
		//--------------------------------------------------------------------------
		//
		// Construction, setup and tear down
		//
		//--------------------------------------------------------------------------
		
		public function OvpConnectionBandwidthTest(testMethod:String) {
			trace(">>> In OvpConnectionBandwidthTest ctor...");
			super(testMethod);
		}
		
		protected override function setUp():void {
			trace(">>> In OvpConnectionBandwidthTest.setUp()...");
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
			trace(">>> In OvpConnectionBandwidthTest.connecctedHandler()...");
			instance.detectBandwidth();
		}
		
		private function bandwidthHandler(e:OvpEvent):void {
			this.bandwidth = e.data.bandwidth;
			this.latency = e.data.latency;
			super.run();
		}
		
		private function errorHandler(e:OvpEvent):void {
			trace(">>> In OvpConnectionBandwidthTest.errorHandler()...");
			trace("Error #" + e.data.errorNumber + " " +e.data.errorDescription + " " + e.currentTarget);
			var ioee:IOErrorEvent = IOErrorEvent(e.clone());
			this.ioErrorHandler(ioee);
		}
		
		protected override function tearDown():void {
			trace(">>> In OvpConnectionBandwidthTest.tearDown()...");
			instance = null;
		}
		
		public override function run():void {
			trace(">>> In OvpConnectionBandwidthTest.run()...");
			instance = new OvpConnection();

			instance.addEventListener(OvpEvent.ERROR,errorHandler);
			instance.addEventListener(NetStatusEvent.NET_STATUS,netStatus);
			instance.addEventListener(OvpEvent.BANDWIDTH,bandwidthHandler);

			instance.connect(HOSTNAME);
		}
		
		//--------------------------------------------------------------------------
		//
		// Tests
		//
		//--------------------------------------------------------------------------
		
		public function testInstantiated():void {
			assertTrue("OvpConnectionBandwidthTest object instantiated", instance is OvpConnection);
		}
		
		public function testBandwidth():void {		
			trace(">>> In OvpConnectionBandwidthTest.testBandwidth() - instance.connected "+instance.connected);
			trace("			- bandwidth = "+this.bandwidth+"kbps");
			trace("			- latency = "+this.latency+"ms");
			assertTrue("testBandwidth - bandwidth should be > 0", (this.bandwidth > 0));
		}

	}
}
