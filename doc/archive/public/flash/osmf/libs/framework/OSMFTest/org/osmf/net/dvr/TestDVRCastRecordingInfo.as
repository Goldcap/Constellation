/*****************************************************
*  
*  Copyright 2010 Adobe Systems Incorporated.  All Rights Reserved.
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
*  The Initial Developer of the Original Code is Adobe Systems Incorporated.
*  Portions created by Adobe Systems Incorporated are Copyright (C) 2010 Adobe Systems 
*  Incorporated. All Rights Reserved. 
*  
*****************************************************/

package org.osmf.net.dvr
{
	import org.osmf.flexunit.TestCaseEx;

	public class TestDVRCastRecordingInfo extends TestCaseEx
	{
		public function testDVRCastRecordingInfo():void
		{
			var ri:DVRCastRecordingInfo = new DVRCastRecordingInfo();
			assertNotNull(ri);
			
			ri.startDuration = 1;
			assertEquals(ri.startDuration, 1);
			
			ri.startOffset = 2;
			assertEquals(ri.startOffset, 2);
			
			var now:Date = new Date();
			ri.startTime = now;
			assertEquals(ri.startTime, now);
		}
		
	}
}