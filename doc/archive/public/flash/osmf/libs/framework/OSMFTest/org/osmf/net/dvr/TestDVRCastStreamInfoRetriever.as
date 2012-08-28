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
	import flash.events.Event;
	
	import org.osmf.flexunit.TestCaseEx;

	public class TestDVRCastStreamInfoRetriever extends TestCaseEx
	{
		public function testDVRCastStreamInfoRetreiverSucceed():void
		{
			var ncf:MockDVRCastNetConnection = new MockDVRCastNetConnection();
			
			ncf.pushCallResult(true, { code: DVRCastConstants.RESULT_GET_STREAM_INFO_RETRY });
			ncf.pushCallResult(true, { code: DVRCastConstants.RESULT_GET_STREAM_INFO_RETRY });
			ncf.pushCallResult(true, { code: DVRCastConstants.RESULT_GET_STREAM_INFO_RETRY });
			ncf.pushCallResult(true, { code: DVRCastConstants.RESULT_GET_STREAM_INFO_RETRY });
			
			var response:Object 
				= 	{ code: DVRCastConstants.RESULT_GET_STREAM_INFO_SUCCESS
					, data:
						{ callTime: null
						, offline: false
						, begOffset: 0
						, endOffset: 0
						, startRec: null
						, stopRec: null
						, isRec: false
						, streamName: "test"
						, lastUpdate: null
						, currLen: 0
						, maxLen: 0
						}
					};
			
			ncf.pushCallResult(true, response);
			
			sir = new DVRCastStreamInfoRetriever(ncf, "test");
			assertNotNull(sir);
			
			sir.addEventListener
				( Event.COMPLETE
				, addAsync(onSIRComplete, 5 * 1000 + 5000)
				);
				
			sir.retrieve(5, 1);
			
			function onSIRComplete(...args):void
			{
				assertNotNull(sir.streamInfo); 
				assertNull(sir.error);
			}
		}
		
		public function testDVRCastStreamInfoRetreiverFail():void
		{
			var ncf:MockDVRCastNetConnection = new MockDVRCastNetConnection();
			
			ncf.pushCallResult(true, { code: DVRCastConstants.RESULT_GET_STREAM_INFO_RETRY });
			ncf.pushCallResult(true, { code: "someUnknownCode" });
			
			sir = new DVRCastStreamInfoRetriever(ncf, "test");
			assertNotNull(sir);
			
			sir.addEventListener
				( Event.COMPLETE
				, addAsync(onSIRComplete, 2 * 1000 + 5000)
				);
				
			sir.retrieve(5, 1);
			
			function onSIRComplete(...args):void
			{
				assertNull(sir.streamInfo);
				assertNotNull(sir.error);
			}
		}
		
		public function testDVRCastStreamInfoRetreiverFail2():void
		{
			var ncf:MockDVRCastNetConnection = new MockDVRCastNetConnection();
			
			ncf.pushCallResult(false, null);
			
			sir = new DVRCastStreamInfoRetriever(ncf, "test");
			assertNotNull(sir);
			
			sir.addEventListener
				( Event.COMPLETE
				, addAsync(onSIRComplete, 2 * 1000 + 5000)
				);
				
			sir.retrieve(0, 1);
			
			function onSIRComplete(...args):void
			{
				assertNull(sir.streamInfo);
				assertNull(sir.error);
			}
		}
		
		private var sir:DVRCastStreamInfoRetriever;
	}
}