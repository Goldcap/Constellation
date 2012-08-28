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
	import org.osmf.events.DVREvent;
	import org.osmf.flexunit.TestCaseEx;
	import org.osmf.media.DefaultMediaFactory;
	import org.osmf.media.MediaElement;
	import org.osmf.net.StreamType;
	import org.osmf.net.StreamingURLResource;
	import org.osmf.netmocker.MockNetConnection;

	public class TestDVRCastDVRTrait extends TestCaseEx
	{
		public function testDVRCastDVRTrait():void
		{
			assertThrows(function():void{ new DVRCastDVRTrait(null, null, null); });
			
			var nc:MockDVRCastNetConnection = new MockDVRCastNetConnection();
			assertThrows(function():void{ new DVRCastDVRTrait(nc, null, null); });
			
			var now:Date = new Date();
			var streamInfo:DVRCastStreamInfo 
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
			
			var recordingInfo:DVRCastRecordingInfo = new DVRCastRecordingInfo();
			
			var resource:StreamingURLResource = new StreamingURLResource("rtmp://example.com", StreamType.DVR);
			resource.addMetadataValue(DVRCastConstants.STREAM_INFO_KEY, streamInfo);
			resource.addMetadataValue(DVRCastConstants.RECORDING_INFO_KEY, recordingInfo);
			
			var stream:DVRCastNetStream = new DVRCastNetStream(nc, resource);
			var trait:DVRCastDVRTrait = new DVRCastDVRTrait(nc, stream, resource);
			assertNotNull(trait);
			
			assertFalse(trait.isRecording);
			
			var f1:Function =  addAsync(onIsRecordingChange1, 10000);
			
			trait.addEventListener
				( DVREvent.IS_RECORDING_CHANGE
				, f1
				);
				
			var f2:Function = addAsync(onIsRecordingChange2, 12000)
			
			function onIsRecordingChange1(event:DVREvent):void
			{
				trait.removeEventListener(DVREvent.IS_RECORDING_CHANGE, f1);
				assertTrue(trait.isRecording);	
				trait.addEventListener(DVREvent.IS_RECORDING_CHANGE, f2);
			}
			
			function onIsRecordingChange2(event:DVREvent):void
			{
				assertFalse(trait.isRecording);	
			}
			
			var response:Object 
				= 	{ code: DVRCastConstants.RESULT_GET_STREAM_INFO_SUCCESS
					, data:
						{ callTime: null
						, offline: false
						, begOffset: 0
						, endOffset: 0
						, startRec: null
						, stopRec: null
						, isRec: true
						, streamName: "test"
						, lastUpdate: null
						, currLen: 0
						, maxLen: 0
						}
					};
			
			nc.pushCallResult(true, response);
			
			var response2:Object 
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
			
			nc.pushCallResult(true, response2);
			
		}
		
		public function testDVRCastDVRTrait2():void
		{
			var dmf:DefaultMediaFactory = new DefaultMediaFactory();
			var me:MediaElement = dmf.createMediaElement(new StreamingURLResource("rtmp://example.com", StreamType.DVR));
			
			assertNotNull(me);
		}
	}
}