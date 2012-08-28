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
package org.osmf.net.httpstreaming.flv
{
	import flash.utils.ByteArray;
	
	import flexunit.framework.TestCase;

	public class TestFLVTagVideo extends TestCase
	{
		public function TestFLVTagVideo(methodName:String=null)
		{
			super(methodName);
		}
		
		public function testBasicVideoTagStructure():void
		{
			var videoData:ByteArray = new ByteArray();
			videoData.writeUnsignedInt(0x01000000);	// AVC NALU
			for (var i:int = 0; i < 10; i++)
			{
				videoData.writeByte(i);
			}
			videoData.position = 0;
			
			var timeStamp:uint = new Date().getTime();
			var ba:ByteArray = FLVTestHelper.createFLVVideoTag(
				FLVTag.TAG_TYPE_VIDEO, 
				videoData.length + 1, 
				timeStamp, 
				0, 
				FLVTagVideo.FRAME_TYPE_KEYFRAME, 
				FLVTagVideo.CODEC_ID_AVC, 
				videoData);
			ba.position = 0;
				
			try
			{
				var videoTag:FLVTagVideo = new FLVTagVideo();
				videoTag.read(ba);

				assertTrue(videoTag.tagType == FLVTag.TAG_TYPE_VIDEO);
				assertTrue(videoTag.dataSize == videoData.length + 1);
				assertTrue(videoTag.timestamp == timeStamp);
				assertTrue(videoTag.codecID == FLVTagVideo.CODEC_ID_AVC);
				assertTrue(videoTag.frameType == FLVTagVideo.FRAME_TYPE_KEYFRAME);
				assertTrue(videoTag.avcPacketType == FLVTagVideo.AVC_PACKET_TYPE_NALU);
				assertTrue(videoTag.avcCompositionTimeOffset == 0);
				try
				{
					assertTrue(videoTag.infoPacketValue == FLVTagVideo.AVC_PACKET_TYPE_NALU);
					assertTrue(false);
				}
				catch (er:Error)
				{
				}
				
				assertTrue(FLVTestHelper.compareByteArray(videoData, videoTag.data, 4));
				
				var videoData2:ByteArray = new ByteArray();
				for (i = 0; i < 16; i++)
				{
					videoData2.writeByte(20 - i);
				}
				videoData2.position = 0;
				
				videoTag.data = videoData2;
				assertTrue(FLVTestHelper.compareByteArray(videoData2, videoTag.data, 0));
				
				videoTag.frameType = FLVTagVideo.FRAME_TYPE_INTER;
				assertTrue(videoTag.frameType == FLVTagVideo.FRAME_TYPE_INTER);
				
				videoTag.codecID = FLVTagVideo.CODEC_ID_JPEG;
				assertTrue(videoTag.codecID == FLVTagVideo.CODEC_ID_JPEG);
				
				try
				{
					assertTrue(videoTag.avcPacketType == FLVTagVideo.AVC_PACKET_TYPE_NALU);
					assertTrue(false);
				}
				catch (er:Error)
				{
				}
				try
				{
					assertTrue(videoTag.avcCompositionTimeOffset == 0);
					assertTrue(false);
				}
				catch (er:Error)
				{
				}
				try
				{
					videoTag.avcCompositionTimeOffset = 100;
					assertTrue(false);
				}
				catch (er:Error)
				{
				}
				
				videoTag.codecID = FLVTagVideo.CODEC_ID_AVC;
				videoTag.avcPacketType = FLVTagVideo.AVC_PACKET_TYPE_NALU;
				videoTag.avcCompositionTimeOffset = 100;
				assertTrue(videoTag.avcCompositionTimeOffset == 100);
				videoTag.avcPacketType = FLVTagVideo.AVC_PACKET_TYPE_SEQUENCE_HEADER;
				assertTrue(videoTag.avcPacketType == FLVTagVideo.AVC_PACKET_TYPE_SEQUENCE_HEADER);
				videoTag.avcPacketType = FLVTagVideo.AVC_PACKET_TYPE_NALU;
				assertTrue(videoTag.avcCompositionTimeOffset == 0);
			}
			catch (e:Error)
			{
				assertTrue(false);
			}
		}
		
		public function testExtendedVideoTagStructure():void
		{
			var videoData:ByteArray = new ByteArray();
			for (var i:int = 0; i < 10; i++)
			{
				videoData.writeByte(i);
			}
			videoData.position = 0;
			
			var timeStamp:uint = new Date().getTime();
			var ba:ByteArray = FLVTestHelper.createFLVVideoTag(
				FLVTag.TAG_TYPE_VIDEO, 
				videoData.length + 1, 
				timeStamp, 
				0, 
				FLVTagVideo.FRAME_TYPE_KEYFRAME, 
				FLVTagVideo.CODEC_ID_VP6, 
				videoData);
			ba.position = 0;

			try
			{
				var videoTag:FLVTagVideo = new FLVTagVideo();
				videoTag.read(ba);

				assertTrue(videoTag.tagType == FLVTag.TAG_TYPE_VIDEO);
				assertTrue(videoTag.dataSize == videoData.length + 1);
				assertTrue(videoTag.timestamp == timeStamp);
				assertTrue(videoTag.codecID == FLVTagVideo.CODEC_ID_VP6);
				assertTrue(videoTag.frameType == FLVTagVideo.FRAME_TYPE_KEYFRAME);
				try
				{
					videoTag.avcPacketType = FLVTagVideo.AVC_PACKET_TYPE_NALU;
					assertTrue(false);
				}
				catch (er:Error)
				{
				}
				
				assertTrue(FLVTestHelper.compareByteArray(videoData, videoTag.data, 0));

				var videoData2:ByteArray = new ByteArray();
				for (i = 0; i < 16; i++)
				{
					videoData2.writeByte(20 - i);
				}
				videoData2.position = 0;
				
				videoTag.data = videoData2;
				assertTrue(FLVTestHelper.compareByteArray(videoData2, videoTag.data, 0));
				
				try
				{
					videoTag.infoPacketValue = 100;
					assertTrue(false);
				}
				catch (er:Error)
				{
				}
				try
				{
					assertTrue(videoTag.infoPacketValue == 100);
					assertTrue(false);
				}
				catch (er:Error)
				{
				}
				videoTag.frameType = FLVTagVideo.FRAME_TYPE_INFO;
				videoTag.infoPacketValue = FLVTagVideo.INFO_PACKET_SEEK_START;
				assertTrue(videoTag.infoPacketValue == FLVTagVideo.INFO_PACKET_SEEK_START);
			}
			catch (e:Error)
			{
				assertTrue(false);
			}
		}
	}
}