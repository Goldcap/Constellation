package utilities {
	import asunit.framework.TestCase;
	import org.openvideoplayer.utilities.*;
	
	public class DateUtilTest extends TestCase {
		private var instance:DateUtil;
		private var datesSetUp:Boolean = false;
		
		// Test with these 3 formats:
		// 1) Wed, 08 Oct 2008 18:44:56 +0000
		// 2) YYYY-MM-DD 
		// 3) YYYY-MM-DD HH:MM:SS

		private var dateStr1:String = "Wed, 08 Oct 2008 18:44:56 +0000";
		private var dateStr2:String = "1997-04-05";
		private var dateStr3:String = "1997-04-05 13:25:55";
		
		private	var date1:Date;
		private var date2:Date;
		private var date3:Date;
		private	var today:Date = new Date();
		
		//--------------------------------------------------------------------------
		//
		// Construction, setup and tear down
		//
		//--------------------------------------------------------------------------
		
		public function DateUtilTest(testMethod:String) {
			super(testMethod);
		}
		
		protected override function setUp():void {
			instance = new DateUtil();
			
			// format 1)
			var date1Ts:Number = Date.parse(dateStr1);
			date1 = new Date(date1Ts);
			// format 2)
			// Date.parse can't handle this format, we need to modify it:
			dateStr2 = dateStr2.replace(/-/g, "/");
			var date2Ts:Number = Date.parse(dateStr2);
			date2 = new Date(date2Ts);
			// format 3) 
			dateStr3 = dateStr3.replace(/-/g, "/");
			var date3Ts:Number = Date.parse(dateStr3);
			date3 = new Date(date3Ts);
		}
		
		protected override function tearDown():void {
			instance = null;
		}
		
		//--------------------------------------------------------------------------
		//
		// Tests
		//
		//--------------------------------------------------------------------------
		
		public function testInstantiated():void {
			assertTrue("DateUtil object instantiated", instance is DateUtil);
		}
		// Compare today with an earlier date - format 1
		public function testCompareTodayWithTimestampEarlier():void {
			var result1:Number = DateUtil.compare(date1, today); // first date is earlier than the second
			assertTrue("testCompareTodayWithTimestampEarlier - result1 should be -1, it is "+result1, result1==(-1));
		}
		public function testCompareTodayWithTimestampLater():void {
			var result2:Number = DateUtil.compare(today, date1); // first date is later than the second
			assertTrue("testCompareTodayWithTimestampLater - result2 should be 1, it is "+result2, result2==1);
		}
		public function testCompareWithTimestampEqual():void {
			var result3:Number = DateUtil.compare(date1, date1); // first date is eauql to the second
			assertTrue("testCompareWithTimestampEqual - result3 should be 0, it is "+result3, result3==0);
		}
		// Compare today with an earlier date - format 2
		public function testCompareTodayWithTimestampEarlier2():void {
			var result1:Number = DateUtil.compare(date2, today); // first date is earlier than the second
			assertTrue("testCompareTodayWithTimestampEarlier2 - result1 should be -1, it is "+result1, result1==(-1));
		}
		public function testCompareTodayWithTimestampLater2():void {
			var result2:Number = DateUtil.compare(today, date2); // first date is later than the second
			trace("date2.toString() = "+date2.toString());
			assertTrue("testCompareTodayWithTimestampLater2 - result2 should be 1, it is "+result2, result2==1);
		}
		public function testCompareWithTimestampEqual2():void {
			var result3:Number = DateUtil.compare(date2, date2); // first date is eauql to the second
			assertTrue("testCompareWithTimestampEqual2 - result3 should be 0, it is "+result3, result3==0);
		}
		// Compare today with an earlier date - format 3
		public function testCompareTodayWithTimestampEarlier3():void {
			var result1:Number = DateUtil.compare(date3, today); // first date is earlier than the second
			assertTrue("testCompareTodayWithTimestampEarlier3 - result1 should be -1, it is "+result1, result1==(-1));
		}
		public function testCompareTodayWithTimestampLater3():void {
			var result2:Number = DateUtil.compare(today, date3); // first date is later than the second
			trace("date3.toString() = "+date3.toString());
			assertTrue("testCompareTodayWithTimestampLater3 - result2 should be 1, it is "+result2, result2==1);
		}
		public function testCompareWithTimestampEqual3():void {
			var result3:Number = DateUtil.compare(date3, date3); // first date is eauql to the second
			assertTrue("testCompareWithTimestampEqual2 - result3 should be 0, it is "+result3, result3==0);
		}
	}
}
