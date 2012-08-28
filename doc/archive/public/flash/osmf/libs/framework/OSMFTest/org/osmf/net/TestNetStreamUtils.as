/*****************************************************
*  
*  Copyright 2009 Adobe Systems Incorporated.  All Rights Reserved.
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
*  Portions created by Adobe Systems Incorporated are Copyright (C) 2009 Adobe Systems 
*  Incorporated. All Rights Reserved. 
*  
*****************************************************/
package org.osmf.net
{
	import __AS3__.vec.Vector;
	
	import flexunit.framework.TestCase;
	
	import org.osmf.media.MediaResourceBase;
	import org.osmf.media.URLResource;
	import org.osmf.net.httpstreaming.HTTPStreamingUtils;
	import org.osmf.utils.NullResource;
	
	CONFIG::FLASH_10_1
	{
	import org.osmf.metadata.MetadataNamespaces;
	}
	
	public class TestNetStreamUtils extends TestCase
	{
		public function testGetStreamType():void
		{
			assertTrue(NetStreamUtils.getStreamType(null) == StreamType.RECORDED);
			assertTrue(NetStreamUtils.getStreamType(new NullResource()) == StreamType.RECORDED);
			assertTrue(NetStreamUtils.getStreamType(new URLResource("rtmp://example.com")) == StreamType.RECORDED);
			assertTrue(NetStreamUtils.getStreamType(new StreamingURLResource("rtmp://example.com")) == StreamType.RECORDED);
			assertTrue(NetStreamUtils.getStreamType(new StreamingURLResource("rtmp://example.com", StreamType.LIVE_OR_RECORDED)) == StreamType.LIVE_OR_RECORDED);
			assertTrue(NetStreamUtils.getStreamType(new StreamingURLResource("rtmp://example.com", StreamType.LIVE)) == StreamType.LIVE);
			assertTrue(NetStreamUtils.getStreamType(new StreamingURLResource("rtmp://example.com", StreamType.RECORDED)) == StreamType.RECORDED);
			assertTrue(NetStreamUtils.getStreamType(new DynamicStreamingResource("rtmp://example.com")) == StreamType.RECORDED);
			assertTrue(NetStreamUtils.getStreamType(new DynamicStreamingResource("rtmp://example.com", StreamType.LIVE_OR_RECORDED)) == StreamType.LIVE_OR_RECORDED);
			assertTrue(NetStreamUtils.getStreamType(new DynamicStreamingResource("rtmp://example.com", StreamType.LIVE)) == StreamType.LIVE);
			assertTrue(NetStreamUtils.getStreamType(new DynamicStreamingResource("rtmp://example.com", StreamType.RECORDED)) == StreamType.RECORDED);
		}

		public function testGetStreamNameFromURL():void
		{
			assertTrue(NetStreamUtils.getStreamNameFromURL(null) == "");
			assertTrue(NetStreamUtils.getStreamNameFromURL("") == "");
			assertTrue(NetStreamUtils.getStreamNameFromURL("http://example.com/example") == "http://example.com/example");
			assertTrue(NetStreamUtils.getStreamNameFromURL("rtmp://example.com/app") == "");
			assertTrue(NetStreamUtils.getStreamNameFromURL("rtmp://example.com/app/stream") == "stream");
			assertTrue(NetStreamUtils.getStreamNameFromURL("rtmp://example.com/app/stream?foo=bar") == "stream?foo=bar");
			assertTrue(NetStreamUtils.getStreamNameFromURL("rtmp://example.com/app/stream1/stream2") == "stream1/stream2");
			assertTrue(NetStreamUtils.getStreamNameFromURL("rtmp://example.com/app/stream1/stream2", true) == "stream2");
			assertTrue(NetStreamUtils.getStreamNameFromURL("rtmp://example.com/app/stream1/stream2?foo=bar", true) == "stream2?foo=bar");
		}
		
		public function testIsRTMPStream():void
		{
			assertTrue(NetStreamUtils.isRTMPStream(null) == false);
			assertTrue(NetStreamUtils.isRTMPStream("") == false);
			assertTrue(NetStreamUtils.isRTMPStream("http://example.com") == false);
			assertTrue(NetStreamUtils.isRTMPStream("rtmfp://example.com") == false);
			assertTrue(NetStreamUtils.isRTMPStream("rtmp") == false);

			assertTrue(NetStreamUtils.isRTMPStream("rtmp://example.com") == true);
			assertTrue(NetStreamUtils.isRTMPStream("rtmpe://example.com") == true);
			assertTrue(NetStreamUtils.isRTMPStream("rtmpt://example.com") == true);
			assertTrue(NetStreamUtils.isRTMPStream("rtmpte://example.com") == true);
			assertTrue(NetStreamUtils.isRTMPStream("rtmps://example.com") == true);
			assertTrue(NetStreamUtils.isRTMPStream(" rtmp://example.com") == true);
		}

		public function testIsStreamingResource():void
		{
			assertTrue(NetStreamUtils.isStreamingResource(null) == false);
			assertTrue(NetStreamUtils.isStreamingResource(new NullResource()) == false);
			assertTrue(NetStreamUtils.isStreamingResource(new URLResource("")) == false);
			assertTrue(NetStreamUtils.isStreamingResource(new URLResource("http://example.com")) == false);
			assertTrue(NetStreamUtils.isStreamingResource(new URLResource("rtmfp://example.com")) == false);
			assertTrue(NetStreamUtils.isStreamingResource(new URLResource("rtmp")) == false);
			assertTrue(NetStreamUtils.isStreamingResource(new DynamicStreamingResource("")) == false);
			assertTrue(NetStreamUtils.isStreamingResource(new DynamicStreamingResource("http://example.com")) == false);
			assertTrue(NetStreamUtils.isStreamingResource(new DynamicStreamingResource("rtmfp://example.com")) == false);
			assertTrue(NetStreamUtils.isStreamingResource(new DynamicStreamingResource("rtmp")) == false);

			assertTrue(NetStreamUtils.isStreamingResource(new URLResource("rtmp://example.com")) == true);
			assertTrue(NetStreamUtils.isStreamingResource(new URLResource("rtmpe://example.com")) == true);
			assertTrue(NetStreamUtils.isStreamingResource(new URLResource("rtmpt://example.com")) == true);
			assertTrue(NetStreamUtils.isStreamingResource(new URLResource("rtmpte://example.com")) == true);
			assertTrue(NetStreamUtils.isStreamingResource(new URLResource("rtmps://example.com")) == true);
			assertTrue(NetStreamUtils.isStreamingResource(new DynamicStreamingResource("rtmp://example.com")) == true);
			assertTrue(NetStreamUtils.isStreamingResource(new DynamicStreamingResource("rtmpe://example.com")) == true);
			assertTrue(NetStreamUtils.isStreamingResource(new DynamicStreamingResource("rtmpt://example.com")) == true);
			assertTrue(NetStreamUtils.isStreamingResource(new DynamicStreamingResource("rtmpte://example.com")) == true);
			assertTrue(NetStreamUtils.isStreamingResource(new DynamicStreamingResource("rtmps://example.com")) == true);
			
			CONFIG::FLASH_10_1
			{
				var resource:URLResource = new URLResource("http://example.com");
				var serverBaseURLs:Vector.<String> = new Vector.<String>();
				resource.addMetadataValue
					( MetadataNamespaces.HTTP_STREAMING_METADATA
					, HTTPStreamingUtils.createHTTPStreamingMetadata("http://example.com/abstURL", null, serverBaseURLs)
					);
				assertTrue(NetStreamUtils.isStreamingResource(resource));
			}
		}
		
		public function testGetPlayArgsForResource():void
		{
			// First try some negative/default cases.
			//
			
			var resource:MediaResourceBase = null;
			var result:Object = NetStreamUtils.getPlayArgsForResource(resource);
			assertTrue(result["start"] == NetStreamUtils.PLAY_START_ARG_RECORDED &&
					   result["len"] == NetStreamUtils.PLAY_LEN_ARG_ALL);

			resource = new NullResource();
			result = NetStreamUtils.getPlayArgsForResource(resource);
			assertTrue(result["start"] == NetStreamUtils.PLAY_START_ARG_RECORDED &&
					   result["len"] == NetStreamUtils.PLAY_LEN_ARG_ALL);

			resource = new URLResource("http://example.com");
			result = NetStreamUtils.getPlayArgsForResource(resource);
			assertTrue(result["start"] == NetStreamUtils.PLAY_START_ARG_RECORDED &&
					   result["len"] == NetStreamUtils.PLAY_LEN_ARG_ALL);

			resource = new StreamingURLResource("rtmp://example.com");
			result = NetStreamUtils.getPlayArgsForResource(resource);
			assertTrue(result["start"] == NetStreamUtils.PLAY_START_ARG_RECORDED &&
					   result["len"] == NetStreamUtils.PLAY_LEN_ARG_ALL);

			var streamingResource:StreamingURLResource = new StreamingURLResource("http://example.com");
			streamingResource.clipStartTime = 10;
			streamingResource.clipEndTime = 15;
			result = NetStreamUtils.getPlayArgsForResource(streamingResource);
			assertTrue(result["start"] == NetStreamUtils.PLAY_START_ARG_RECORDED &&
					   result["len"] == NetStreamUtils.PLAY_LEN_ARG_ALL);

			// Now try with positive/non-default cases.
			//
			
			streamingResource = new StreamingURLResource("rtmp://example.com", StreamType.LIVE);
			result = NetStreamUtils.getPlayArgsForResource(streamingResource);
			assertTrue(result["start"] == NetStreamUtils.PLAY_START_ARG_LIVE &&
					   result["len"] == NetStreamUtils.PLAY_LEN_ARG_ALL);

			streamingResource = new StreamingURLResource("rtmp://example.com", StreamType.RECORDED);
			result = NetStreamUtils.getPlayArgsForResource(streamingResource);
			assertTrue(result["start"] == NetStreamUtils.PLAY_START_ARG_RECORDED &&
					   result["len"] == NetStreamUtils.PLAY_LEN_ARG_ALL);

			streamingResource = new StreamingURLResource("rtmp://example.com", StreamType.LIVE_OR_RECORDED);
			result = NetStreamUtils.getPlayArgsForResource(streamingResource);
			assertTrue(result["start"] == NetStreamUtils.PLAY_START_ARG_ANY &&
					   result["len"] == NetStreamUtils.PLAY_LEN_ARG_ALL);

			streamingResource = new DynamicStreamingResource("rtmp://example.com", StreamType.RECORDED);
			result = NetStreamUtils.getPlayArgsForResource(streamingResource);
			assertTrue(result["start"] == NetStreamUtils.PLAY_START_ARG_RECORDED &&
					   result["len"] == NetStreamUtils.PLAY_LEN_ARG_ALL);

			streamingResource = new DynamicStreamingResource("rtmp://example.com", StreamType.LIVE);
			result = NetStreamUtils.getPlayArgsForResource(streamingResource);
			assertTrue(result["start"] == NetStreamUtils.PLAY_START_ARG_LIVE &&
					   result["len"] == NetStreamUtils.PLAY_LEN_ARG_ALL);

			streamingResource = new DynamicStreamingResource("rtmp://example.com", StreamType.LIVE_OR_RECORDED);
			result = NetStreamUtils.getPlayArgsForResource(streamingResource);
			assertTrue(result["start"] == NetStreamUtils.PLAY_START_ARG_ANY &&
					   result["len"] == NetStreamUtils.PLAY_LEN_ARG_ALL);

			streamingResource = new StreamingURLResource("rtmp://example.com");
			streamingResource.clipStartTime = 10;
			streamingResource.clipEndTime = 15;
			result = NetStreamUtils.getPlayArgsForResource(streamingResource);
			assertTrue(result["start"] == 10 &&
					   result["len"] == 5);

			streamingResource = new StreamingURLResource("rtmp://example.com");
			streamingResource.clipStartTime = 10;
			result = NetStreamUtils.getPlayArgsForResource(streamingResource);
			assertTrue(result["start"] == 10 &&
					   result["len"] == NetStreamUtils.PLAY_LEN_ARG_ALL);

			streamingResource = new StreamingURLResource("rtmp://example.com");
			streamingResource.clipEndTime = 15;
			result = NetStreamUtils.getPlayArgsForResource(streamingResource);
			assertTrue(result["start"] == NetStreamUtils.PLAY_START_ARG_RECORDED &&
					   result["len"] == 15);

			streamingResource = new StreamingURLResource("rtmp://example.com");
			streamingResource.clipStartTime = 10;
			streamingResource.clipEndTime = 0;
			result = NetStreamUtils.getPlayArgsForResource(streamingResource);
			assertTrue(result["start"] == 10 &&
					   result["len"] == 0);

			streamingResource = new StreamingURLResource("rtmp://example.com");
			streamingResource.clipStartTime = 10;
			streamingResource.clipEndTime = 5;
			result = NetStreamUtils.getPlayArgsForResource(streamingResource);
			assertTrue(result["start"] == 10 &&
					   result["len"] == 0);
		}
	}
}