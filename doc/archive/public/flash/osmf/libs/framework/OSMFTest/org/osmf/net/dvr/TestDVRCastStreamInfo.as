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

	public class TestDVRCastStreamInfo extends TestCaseEx
	{
		public function testDVRCastStreamInfo():void
		{
			assertThrows(function():void{ new DVRCastStreamInfo(null); });
			
			var now:Date = new Date();
			
			var si:DVRCastStreamInfo
				= new DVRCastStreamInfo
					(	{ callTime: now
						, offline: false
						, begOffset: 0
						, endOffset: 0
						, startRec: now
						, stopRec: now
						, isRec: false
						, streamName: "test"
						, lastUpdate: now
						, currLen: 0
						, maxLen: 0
						}
					);
				
			assertNotNull(si);
			assertEquals(now, si.callTime);
			assertEquals(false, si.offline);
			assertEquals(0, si.beginOffset);
			assertEquals(0, si.endOffset);
			assertEquals(now, si.recordingStart);
			assertEquals(now, si.recordingEnd);
			assertEquals(false, si.isRecording);
			assertEquals("test", si.streamName);
			assertEquals(now, si.lastUpdate);
			assertEquals(0, si.currentLength);
			assertEquals(0, si.maxLength); 
			
			var si2:DVRCastStreamInfo
				= new DVRCastStreamInfo
					(	{ callTime: null
						, offline: true
						, begOffset: 1
						, endOffset: 2
						, startRec: null
						, stopRec: now
						, isRec: true
						, streamName: "test2"
						, lastUpdate: null
						, currLen: 3
						, maxLen: 4
						}
					);
					
			assertThrows(si2.readFromDVRCastStreamInfo, null);
			
			si2.readFromDVRCastStreamInfo(si);
			assertEquals(now, si2.callTime);
			assertEquals(false, si2.offline);
			assertEquals(0, si2.beginOffset);
			assertEquals(0, si2.endOffset);
			assertEquals(now, si2.recordingStart);
			assertEquals(now, si2.recordingEnd);
			assertEquals(false, si2.isRecording);
			assertEquals("test", si2.streamName);
			assertEquals(now, si2.lastUpdate);
			assertEquals(0, si2.currentLength);
			assertEquals(0, si2.maxLength); 
		}
	}
}