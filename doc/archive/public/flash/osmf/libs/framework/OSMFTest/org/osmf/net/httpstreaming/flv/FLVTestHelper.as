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
	
	internal class FLVTestHelper
	{
		public function FLVTestHelper()
		{
		}

		public static function createFLVHeader(
			signature0:uint, 
			signature1:uint, 
			signature2:uint, 
			version:uint, 
			audioFlag:Boolean, 
			videoFlag:Boolean, 
			dataOffset:uint,
			appendPrevTagSize:Boolean):ByteArray
		{
			var ba:ByteArray = new ByteArray();
			ba.writeByte(signature0);
			ba.writeByte(signature1);
			ba.writeByte(signature2);
			ba.writeByte(version);
			
			var flags:uint = 0;
			if (audioFlag)
			{
				flags |= 0x04;
			}
			if (videoFlag)
			{
				flags |= 0x01;
			}
			
			ba.writeByte(flags);			
			ba.writeUnsignedInt(dataOffset);
			if (appendPrevTagSize)
			{
				ba.writeUnsignedInt(0);
			}
			
			return ba;
		}
		
		public static function compareByteArray(ba1:ByteArray, ba2:ByteArray, ba1Offset:uint = 0, ba2Offset:uint = 0):Boolean
		{
			ba1.position = ba1Offset;
			ba2.position = ba2Offset;
			
			if (ba1.bytesAvailable != ba2.bytesAvailable)
			{
				return false;
			}
			
			var size:uint = ba1.bytesAvailable;
			for (var i:int = 0; i < size; i++)
			{
				if (ba1[ba1Offset + i] != ba2[ba2Offset + i])
				{
					return false;
				}
			}	
			
			return true;		
		}
		
		public static function createFLVAudioTag(
			tagType:int,
			dataSize:uint,
			timeStamp:uint,
			streamId:uint,
			soundFormat:uint,
			soundRate:uint,
			soundSize:uint,
			soundType:uint,
			soundData:ByteArray):ByteArray
		{
			var ba:ByteArray = new ByteArray();
			
			ba.writeByte(tagType);
			
			ba.writeByte((dataSize >> 16) & 0xff);
			ba.writeByte((dataSize >> 8) & 0xff);
			ba.writeByte(dataSize & 0xff);	
			
			ba.writeByte((timeStamp >> 16) & 0xff);
			ba.writeByte((timeStamp >> 8) & 0xff);
			ba.writeByte(timeStamp & 0xff);
			ba.writeByte((timeStamp >> 24) & 0xff);
			
			ba.writeByte((streamId >> 16) & 0xff);
			ba.writeByte((streamId >> 8) & 0xff);
			ba.writeByte(streamId & 0xff);
			
			var soundFlags:uint = 0;
			soundFlags |= ((soundFormat << 4) & 0xf0);
			soundFlags |= ((soundRate << 2) & 0x0c);
			soundFlags |= ((soundSize << 1) & 0x02);
			soundFlags |= (soundType & 0x01);
			
			ba.writeByte(soundFlags);
			ba.writeBytes(soundData, 0, soundData.length);
			ba.writeUnsignedInt(ba.length);
			
			return ba;		
		}

		public static function createFLVVideoTag(
			tagType:int,
			dataSize:uint,
			timeStamp:uint,
			streamId:uint,
			frameType:uint,
			codecId:uint,
			videoData:ByteArray):ByteArray
		{
			var ba:ByteArray = new ByteArray();
			
			ba.writeByte(tagType);
			
			ba.writeByte((dataSize >> 16) & 0xff);
			ba.writeByte((dataSize >> 8) & 0xff);
			ba.writeByte(dataSize & 0xff);	
			
			ba.writeByte((timeStamp >> 16) & 0xff);
			ba.writeByte((timeStamp >> 8) & 0xff);
			ba.writeByte(timeStamp & 0xff);
			ba.writeByte((timeStamp >> 24) & 0xff);
			
			ba.writeByte((streamId >> 16) & 0xff);
			ba.writeByte((streamId >> 8) & 0xff);
			ba.writeByte(streamId & 0xff);
			
			var videoFlags:uint = 0;
			videoFlags |= ((frameType << 4) & 0xf0);
			videoFlags |= (codecId & 0x0f);
			ba.writeByte(videoFlags);
			ba.writeBytes(videoData, 0, videoData.length);
			ba.writeUnsignedInt(ba.length);

			return ba;			
		}
	}
}