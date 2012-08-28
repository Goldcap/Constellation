package rss {
	import asunit.framework.TestCase;
	import org.openvideoplayer.rss.*;
	import com.akamai.rss.*;
	
	public class AkamaiMediaRSSTest extends TestCase {
		private var instance:AkamaiMediaRSS;
		
		//////////////////////////////////////////
		// Construction, setup and tear down
		//
		public function AkamaiMediaRSSTest(testMethod:String) {
			super(testMethod);
		}
		
		protected override function setUp():void {
			instance = new AkamaiMediaRSS();
		}
		
		protected override function tearDown():void {
			instance = null;
		}
		
		//////////////////////////////////////////
		// Tests
		//
		public function testInstantiated():void {
			assertTrue("AkamaiMediaRSS object instantiated", instance is AkamaiMediaRSS);
		}
		
		public function testFilterTextSimple():void {
			var filterText:String = "the";
			var arr:Array = instance.filterItemList(filterText);
			assertTrue("testFilterTextSimple - array is not null after simple filter", arr != null);
		}
	}
}
