package rss {
	import flash.events.IOErrorEvent;
	
	import asunit.framework.AsynchronousTestCase;
	import org.openvideoplayer.rss.*;
	import org.openvideoplayer.events.*;
	import com.akamai.rss.*;
	
	public class AkamaiMediaRSSTest extends AsynchronousTestCase {
		// Note: the tests below are specific to this feed, if you change the URL, you may break several tests
		private const TEST_URL:String = "http://rss.streamos.com/streamos/rss/genfeed.php?feedid=1679&groupname=products";
		private var instance:AkamaiMediaRSS;
		
		//--------------------------------------------------------------------------
		//
		// Construction, setup and tear down
		//
		//--------------------------------------------------------------------------
		
		public function AkamaiMediaRSSTest(testMethod:String) {
			trace(">>> In AkamaiMediaRSSTest ctor...");
			super(testMethod);
		}
		
		protected override function setUp():void {
			trace(">>> In AkamaiMediaRSSTest.setUp()...");
		}

		private function rssLoadHandler(e:OvpEvent):void {
			trace("RSS loaded successfully");
		}

		private function rssParsedHandler(e:OvpEvent):void {
			trace(">>> In AkamaiMediaRSSTest.rssParsedHandler()...");
			super.run();
		}
		
		private function errorHandler(e:OvpEvent):void {
			trace(">>> In AkamaiMediaRSSTest.errorHandler()...");
			trace("Error #" + e.data.errorNumber + " " +e.data.errorDescription + " " + e.currentTarget);
			var ioee:IOErrorEvent = IOErrorEvent(e.clone());
			this.ioErrorHandler(ioee);
		}
		
		protected override function tearDown():void {
			trace(">>> In AkamaiMediaRSSTest.tearDown()...");
			instance = null;
		}
		
		public override function run():void {
			trace(">>> In AkamaiMediaRSSTest.run()...");
			instance = new AkamaiMediaRSS();
			instance.addEventListener(OvpEvent.PARSED, rssParsedHandler);
			instance.addEventListener(OvpEvent.LOADED, rssLoadHandler);
			instance.addEventListener(OvpEvent.ERROR, errorHandler);
			instance.load(TEST_URL);
		}
		
		//--------------------------------------------------------------------------
		//
		// Tests
		//
		//--------------------------------------------------------------------------
		
		public function testInstantiated():void {
			assertTrue("AkamaiMediaRSS object instantiated", instance is AkamaiMediaRSS);
		}
		
		public function testFilterTextSimple():void {
			// Very simple test - looks for 'the' in any of the rss fields
			var filterText:String = "the";
			var arr:Array = instance.filterItemList(filterText);
			if (arr) {
				trace(">>> In AkamaiMediaRSSTest.testFilterTextSimple() - arr.length = "+arr.length);
			}
			assertTrue("testFilterTextSimple - array.length should equal 7", (arr != null) && (arr.length == 7));
		}

		public function testFilterTextSimpleWithWhiteSpace():void {
			// Very simple test - looks for 'the' with (extra white space added) in any of the rss fields
			var filterText:String = " the   ";
			var arr:Array = instance.filterItemList(filterText);
			if (arr) {
				trace(">>> In AkamaiMediaRSSTest.testFilterTextSimpleWithWhiteSpace() - arr.length = "+arr.length);
			}
			assertTrue("testFilterTextSimpleWithWhiteSpace - array.length should equal 7", (arr != null) && (arr.length == 7));
		}
		
		public function testFilterTextTitleAny():void {
			// Title text - looks for "earth" in the title field only
			var filterText:String = "earth";
			var ff:RSSFilterFields = new RSSFilterFields();
			ff.title = true;
			var arr:Array = instance.filterItemList(filterText, ff);
			if (arr) {
				trace(">>> In AkamaiMediaRSSTest.testFilterTextTitleAny() - arr.length = "+arr.length);
			}
			assertTrue("testFilterTextTitleAny - array.length should equal 1", (arr != null) && (arr.length == 1));
		}

		public function testFilterTextTitleAll():void {
			// Looks for the exact phrase "living -" in the title field
			var filterText:String = "living -";
			var ff:RSSFilterFields = new RSSFilterFields();
			ff.title = true;
			var arr:Array = instance.filterItemList(filterText, ff, instance.FILTER_ALL);
			if (arr) {
				trace(">>> In AkamaiMediaRSSTest.testFilterTextTitleAll() - arr.length = "+arr.length);
			}
			assertTrue("testFilterTextTitleAll - array is not null after simple filter", (arr != null) && (arr.length == 1));
		}
		
		public function testFilterTextDescriptionAny():void {
			// Title text - looks for "voice" in the description field only
			var filterText:String = "voice";
			var ff:RSSFilterFields = new RSSFilterFields();
			ff.description = true;
			var arr:Array = instance.filterItemList(filterText, ff);
			if (arr) {
				trace(">>> In AkamaiMediaRSSTest.testFilterTextDescriptionAny() - arr.length = "+arr.length);
			}
			assertTrue("testFilterTextDescriptionAny - array.length should equal 2", (arr != null) && (arr.length == 2));
		}

		public function testFilterTextDescriptionAny3():void {
			// Title text - looks for "voice" OR "and" OR "guitar" in the description field only
			var filterText:String = "voice and guitar";
			var ff:RSSFilterFields = new RSSFilterFields();
			ff.description = true;
			var arr:Array = instance.filterItemList(filterText, ff);
			if (arr) {
				trace(">>> In AkamaiMediaRSSTest.testFilterTextDescriptionAny3() - arr.length = "+arr.length);
			}
			assertTrue("testFilterTextDescriptionAny3 - array.length should equal 2", (arr != null) && (arr.length == 3));
		}

		public function testFilterTextDescriptionAll():void {
			// Title text - looks for an exact match of the string "voice and guitar" in the description field only
			var filterText:String = "voice and guitar";
			var ff:RSSFilterFields = new RSSFilterFields();
			ff.description = true;
			var arr:Array = instance.filterItemList(filterText, ff, instance.FILTER_ALL);
			if (arr) {
				trace(">>> In AkamaiMediaRSSTest.testFilterTextDescriptionAll() - arr.length = "+arr.length);
			}
			assertTrue("testFilterTextDescriptionAll - array.length should equal 2", (arr != null) && (arr.length == 1));
		}

		public function testFilterTextDescriptionAllWhiteSpace():void {
			// Title text - looks for an exact match of the string "voice and guitar" in the description field only
			var filterText:String = "  voice and guitar   ";
			var ff:RSSFilterFields = new RSSFilterFields();
			ff.description = true;
			var arr:Array = instance.filterItemList(filterText, ff, instance.FILTER_ALL);
			if (arr) {
				trace(">>> In AkamaiMediaRSSTest.testFilterTextDescriptionAllWhiteSpace() - arr.length = "+arr.length);
			}
			assertTrue("testFilterTextDescriptionAllWhiteSpace - array.length should equal 2", (arr != null) && (arr.length == 1));
		}
				
		public function testFilterDateSimpleLEQNow():void {
			// Looks for feeds with a date field less or equal than the current date and time, should find all
			var d:Date = new Date();
			var arr:Array = instance.filterItemList(null, null, 0, d);
			if (arr) {
				trace(">>> In AkamaiMediaRSSTest.testFilterDateSimpleLEQNow() - arr.length = "+arr.length);
			}
			assertTrue("testFilterDateSimpleLEQNow - array is not null after simple date filter", (arr != null) && (arr.length == 7));
		}
		public function testFilterDateSimpleGEQNow():void {
			// Looks for feeds with a date field greater than or equal to the current date and time, should find none
			var d:Date = new Date();
			var arr:Array = instance.filterItemList(null, null, 0, d, instance.FILTER_DATE_GEQ);
			if (arr) {
				trace(">>> In AkamaiMediaRSSTest.testFilterDateSimpleGEQNow() - arr.length = "+arr.length);
			}
			assertTrue("testFilterDateSimpleGEQNow - array is null after simple date filter", (arr == null) || (arr.length == 0));
		}
		public function testfilterDateSameDayNoTimestampLEQ():void {
			// Looks for feeds less than or equal to a specific pub date
			var d:Date = new Date(Date.parse("2007/12/15"));
			var arr:Array = instance.filterItemList(null, null, 0, d);
			if (arr) {
				trace(">>> In AkamaiMediaRSSTest.testfilterDateSameDayNoTimestampLEQ() - arr.length = "+arr.length);
			}
			assertTrue("testfilterDateSameDayNoTimestampLEQ - array is not null after simple date filter", (arr != null) && (arr.length == 2));
		}
		public function testfilterDateSameDayNoTimestampGEQ():void {
			// Looks for feeds greater than or equal to a specific pub date			
			var d:Date = new Date(Date.parse("2007/12/15"));
			var arr:Array = instance.filterItemList(null, null, 0, d, instance.FILTER_DATE_GEQ);
			if (arr) {
				trace(">>> In AkamaiMediaRSSTest.testfilterDateSameDayNoTimestampGEQ() - arr.length = "+arr.length);
			}
			assertTrue("testfilterDateSameDayNoTimestampGEQ - array is not null after simple date filter", (arr != null) && (arr.length == 6));
		}
		public function testfilterTextAndDateSameDayNoTimestampLEQ():void {
			// Looks for feeds with a text match in any field AND a date field less than or equal to a specific date
			var filterText:String = "  voice and guitar   ";
			var d:Date = new Date(Date.parse("2007/12/15"));
			var arr:Array = instance.filterItemList(filterText, null, 0, d);
			if (arr) {
				trace(">>> In AkamaiMediaRSSTest.testfilterTextAndDateSameDayNoTimestampLEQ() - arr.length = "+arr.length);
			}
			assertTrue("testfilterTextAndDateSameDayNoTimestampLEQ - array is not null after simple date filter", (arr != null) && (arr.length == 0));
		}
		public function testfilterTextAndDateSameDayNoTimestampLEQ2():void {
			// Looks for feeds with a text match in any field AND a date field less than or equal to a specific date
			var filterText:String = "  CNET ";
			var d:Date = new Date(Date.parse("2007/12/15"));
			var arr:Array = instance.filterItemList(filterText, null, 0, d);
			if (arr) {
				trace(">>> In AkamaiMediaRSSTest.testfilterTextAndDateSameDayNoTimestampLEQ2() - arr.length = "+arr.length);
			}
			assertTrue("testfilterTextAndDateSameDayNoTimestampLEQ2 - array is not null after simple date filter", (arr != null) && (arr.length == 1));
		}
		public function testfilterTextAndDateSameDayNoTimestampGEQ():void {
			// Looks for feeds greater than or equal to a specific pub date	
			var filterText:String = "the ";
			var d:Date = new Date(Date.parse("2001/12/15"));
			var arr:Array = instance.filterItemList(filterText, null, 0, d, instance.FILTER_DATE_GEQ);
			if (arr) {
				trace(">>> In AkamaiMediaRSSTest.testfilterTextAndDateSameDayNoTimestampGEQ() - arr.length = "+arr.length);
			}
			assertTrue("testfilterTextAndDateSameDayNoTimestampGEQ - array is not null after simple date filter", (arr != null) && (arr.length == 7));
		}
		public function testfilterTextAndDateSameDayNoTimestampGEQ2():void {
			// Looks for feeds greater than or equal to a specific pub date	
			var filterText:String = "from ";
			var d:Date = new Date(Date.parse("2008/04/5"));
			var arr:Array = instance.filterItemList(filterText, null, 0, d, instance.FILTER_DATE_GEQ);
			if (arr) {
				trace(">>> In AkamaiMediaRSSTest.testfilterTextAndDateSameDayNoTimestampGEQ2() - arr.length = "+arr.length);
			}
			assertTrue("testfilterTextAndDateSameDayNoTimestampGEQ2 - array is not null after simple date filter", (arr != null) && (arr.length == 2));
		}
		public function testfilterTextAndDateSameDayNoTimestampGEQ3():void {
			// Looks for feeds greater than or equal to a specific pub date	
			var filterText:String = "from ";
			var d:Date = new Date(Date.parse("2008/04/6"));
			var arr:Array = instance.filterItemList(filterText, null, 0, d, instance.FILTER_DATE_GEQ);
			if (arr) {
				trace(">>> In AkamaiMediaRSSTest.testfilterTextAndDateSameDayNoTimestampGEQ3() - arr.length = "+arr.length);
			}
			assertTrue("testfilterTextAndDateSameDayNoTimestampGEQ3 - array is not null after simple date filter", (arr != null) && (arr.length == 1));
		}
		public function testfilterTextAndDateNowGEQ():void {
			// Looks for feeds greater than or equal to a specific pub date	
			var filterText:String = "from ";
			var d:Date = new Date();
			var arr:Array = instance.filterItemList(filterText, null, 0, d, instance.FILTER_DATE_GEQ);
			if (arr) {
				trace(">>> In AkamaiMediaRSSTest.testfilterTextAndDateNowGEQ() - arr.length = "+arr.length);
			}
			assertTrue("testfilterTextAndDateNowGEQ - array is not null after simple date filter", (arr != null) && (arr.length == 0));
		}

	}
}
