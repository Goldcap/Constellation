/*****************************************************
*  
*  Copyright 2009 Akamai Technologies, Inc.  All Rights Reserved.
*  
*****************************************************
*  The contents of this file are subject to the Mozilla Public License
*  Version 1.1 (the "License"); you may not use this file except in
*  compliance with the License. You may obtain a copy of the License at
*  http://www.mozilla.org/MPL/
*   
*  Software distributed under the License is distributed on an "AS IS"
*  basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
*  License for the specific language governing rights and limitations
*  under the License.
*   
*  
*  The Initial Developer of the Original Code is Akamai Technologies, Inc.
*  Portions created by Akamai Technologies, Inc. are Copyright (C) 2009 Akamai 
*  Technologies, Inc. All Rights Reserved. 
*  
*****************************************************/
package org.osmf.utils
{
	import flexunit.framework.TestCase;

	public class TestTimeUtil extends TestCase
	{
		public function testFormatTime():void
		{
			// Test hh:mm:ss format
			var seconds:Number = TimeUtil.parseTime("5:12:30");
			assertEquals(seconds, 18750);
			seconds = TimeUtil.parseTime("0:0:31");
			assertEquals(seconds, 31);
			seconds = TimeUtil.parseTime("0:2:01");
			assertEquals(seconds, 121);
			
			// Test offset times, e.g. 10s, 5m, 2h
			seconds = TimeUtil.parseTime("12s");
			assertEquals(seconds, 12);
			seconds = TimeUtil.parseTime("2m");
			assertEquals(seconds, 120);
			seconds = TimeUtil.parseTime("3h");
			assertEquals(seconds, 10800);
			
			// Give it some invalid formats
			seconds = TimeUtil.parseTime("12:30");
			assertTrue(isNaN(seconds));
			seconds = TimeUtil.parseTime("12;30");
			assertTrue(isNaN(seconds));
			seconds = TimeUtil.parseTime("23:");
			assertTrue(isNaN(seconds));
			seconds = TimeUtil.parseTime(":23");
			assertTrue(isNaN(seconds));
			seconds = TimeUtil.parseTime("37n");
			assertTrue(isNaN(seconds));
			seconds = TimeUtil.parseTime("abc");
			assertTrue(isNaN(seconds));
		}
		
		public function testformatAsTimeCode():void
		{
			var time:String = TimeUtil.formatAsTimeCode(126);
			assertEquals(time, "02:06");
			time = TimeUtil.formatAsTimeCode(18750);
			assertEquals(time, "05:12:30");
			time = TimeUtil.formatAsTimeCode(31);
			assertEquals(time, "00:31");
			time = TimeUtil.formatAsTimeCode(1);
			assertEquals(time, "00:01");
		}
	
	}
}
