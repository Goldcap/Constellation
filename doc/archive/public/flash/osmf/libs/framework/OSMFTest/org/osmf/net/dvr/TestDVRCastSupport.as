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
*  
*  The Initial Developer of the Original Code is Adobe Systems Incorporated.
*  Portions created by Adobe Systems Incorporated are Copyright (C) 2010 Adobe Systems 
*  Incorporated. All Rights Reserved. 
*  
*****************************************************/

package org.osmf.net.dvr
{
	import org.osmf.elements.VideoElement;
	import org.osmf.events.MediaErrorEvent;
	import org.osmf.flexunit.TestCaseEx;
	import org.osmf.media.MediaPlayer;
	import org.osmf.net.StreamType;
	import org.osmf.net.StreamingURLResource;
	import org.osmf.netmocker.NetConnectionExpectation;

	public class TestDVRCastSupport extends TestCaseEx
	{
		override public function setUp():void
		{
			super.setUp();
			
			mockConnectionFactory = new MockDVRCastNetConnectionFactory();
			mockConnection = mockConnectionFactory.connection;
			dvrCastNetConnectionFactory = new DVRCastNetConnectionFactory(mockConnectionFactory);
			
			video
				= new VideoElement
					( new StreamingURLResource("rtmp://example.com/non-existent", StreamType.DVR)
					, new DVRCastNetLoaderForTest(dvrCastNetConnectionFactory, false)
					);
		}
		
		override public function tearDown():void
		{
			video = null;
			mockConnectionFactory = null;
			mockConnection = null;
		}
		
		public function testDVRCastSupport_SecurityError():void
		{
			mockConnection.expectation = NetConnectionExpectation.SECURITY_ERROR;
			
			video.addEventListener
				( MediaErrorEvent.MEDIA_ERROR
				, addAsync
					( function(event:MediaErrorEvent):void
						{
								
						}
					, 5000
					)
				);
			
			var player:MediaPlayer = new MediaPlayer(video);
			
			dvrCastNetConnectionFactory.closeNetConnection(mockConnection);
			player.media = null;
			video = null;
		}
		
		public function testDVRCastSupport_RejectedConnection():void
		{
			mockConnection.expectation = NetConnectionExpectation.REJECTED_CONNECTION;
			
			video.addEventListener
				( MediaErrorEvent.MEDIA_ERROR
				, addAsync
					( function(event:MediaErrorEvent):void
						{
								
						}
					, 5000
					)
				);
			
			var player:MediaPlayer = new MediaPlayer(video);
		}
		
		public function testDVRCastSupport_SubscriptionFailure():void
		{
			mockConnection.expectation = NetConnectionExpectation.VALID_CONNECTION;
			mockConnection.pushCallResult(false, null);
			
			video.addEventListener
				( MediaErrorEvent.MEDIA_ERROR
				, addAsync
					( function(event:MediaErrorEvent):void
						{
								
						}
					, 5000
					)
				);
			
			var player:MediaPlayer = new MediaPlayer(video);
		}
		
		public function testDVRCastSupport_Valid_Online():void
		{
			mockConnection.expectation = NetConnectionExpectation.VALID_CONNECTION;
			mockConnection.pushCallResult(true, {});
			mockConnection.pushCallResult
				(	true
				,	{ code: DVRCastConstants.RESULT_GET_STREAM_INFO_SUCCESS 
					, data: { callTime: new Date()
							, offline: false
							, begOffset: 0
							, endOffset: 0
							, startRec: null
							, stopRec: null
							, isRec: true
							, streamName: "non-existent"
							, lastUpdate: new Date()
							, currLen: 100
							, maxLen: NaN
							}
					}
				);
			
			video.addEventListener
				( MediaErrorEvent.MEDIA_ERROR
				, addAsync
					( function(event:MediaErrorEvent):void
						{
								
						}
					, 5000
					)
				);
			
			var player:MediaPlayer = new MediaPlayer(video);
		}
		
		public function testDVRCastSupport_Valid_Offline():void
		{
			mockConnection.expectation = NetConnectionExpectation.VALID_CONNECTION;
			mockConnection.pushCallResult(true, {});
			mockConnection.pushCallResult
				(	true
				,	{ code: DVRCastConstants.RESULT_GET_STREAM_INFO_SUCCESS 
					, data: { callTime: new Date()
							, offline: true
							, begOffset: 0
							, endOffset: 0
							, startRec: null
							, stopRec: null
							, isRec: true
							, streamName: "non-existent"
							, lastUpdate: new Date()
							, currLen: 100
							, maxLen: NaN
							}
					}
				);
			
			video.addEventListener
				( MediaErrorEvent.MEDIA_ERROR
				, addAsync
					( function(event:MediaErrorEvent):void
						{
								
						}
					, 5000
					)
				);
			
			var player:MediaPlayer = new MediaPlayer(video);
		}
		
		private var dvrCastNetConnectionFactory:DVRCastNetConnectionFactory;
		private var mockConnectionFactory:MockDVRCastNetConnectionFactory;
		private var mockConnection:MockDVRCastNetConnection;
		private var video:VideoElement;
	}
}