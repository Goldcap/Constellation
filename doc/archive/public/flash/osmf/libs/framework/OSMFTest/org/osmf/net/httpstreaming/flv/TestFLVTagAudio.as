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

	public class TestFLVTagAudio extends TestCase
	{
		public function TestFLVTagAudio(methodName:String=null)
		{
			super(methodName);
		}
		
		public function testAACAudioTagStructure():void
		{
			var soundData:ByteArray = new ByteArray();
			soundData.writeByte(0);
			for (var i:int = 0; i < 10; i++)
			{
				soundData.writeByte(i);
			}
			soundData.position = 0;
			
			var timeStamp:uint = new Date().getTime();
			var ba:ByteArray = FLVTestHelper.createFLVAudioTag(
				FLVTag.TAG_TYPE_AUDIO, 
				soundData.length + 1, 
				timeStamp, 
				0,	// always 0
				FLVTagAudio.SOUND_FORMAT_AAC,
				3,	// SoundRate 44kHz
				1,	// SoundSize snd16Bit
				1,	// SoundType sndStereo
				soundData);  
			ba.position = 0;
			
			try
			{
				var audioTag:FLVTagAudio = new FLVTagAudio();
				audioTag.read(ba);
				
				assertTrue(audioTag.tagType == FLVTag.TAG_TYPE_AUDIO);
				assertTrue(audioTag.dataSize == soundData.length + 1);
				assertTrue(audioTag.timestamp == timeStamp);
				assertTrue(audioTag.soundFormat == FLVTagAudio.SOUND_FORMAT_AAC);
				assertTrue(audioTag.soundRate == FLVTagAudio.SOUND_RATE_44K);
				assertTrue(audioTag.soundSize == FLVTagAudio.SOUND_SIZE_16BITS);
				assertTrue(audioTag.soundChannels == FLVTagAudio.SOUND_CHANNELS_STEREO);
				assertTrue(audioTag.isAACSequenceHeader);
				assertTrue(audioTag.soundFormatByte == 175);
				assertTrue(FLVTestHelper.compareByteArray(soundData, audioTag.data, 1));
				
				var soundData2:ByteArray = new ByteArray();
				for (i = 0; i < 10; i++)
				{
					soundData2.writeByte(15 - i);
				}
				soundData2.position = 0;
				
				audioTag.data = soundData2;
				assertTrue(FLVTestHelper.compareByteArray(soundData2, audioTag.data, 0));

				audioTag.soundFormatByte = 0xB8;				
				assertTrue(audioTag.soundFormat == FLVTagAudio.SOUND_FORMAT_SPEEX);
				assertTrue(audioTag.soundRate == FLVTagAudio.SOUND_RATE_22K);
				assertTrue(audioTag.soundSize == FLVTagAudio.SOUND_SIZE_8BITS);
				assertTrue(audioTag.soundChannels == FLVTagAudio.SOUND_CHANNELS_MONO);
				
				audioTag.soundSize = FLVTagAudio.SOUND_SIZE_16BITS;
				assertTrue(audioTag.soundFormatByte == 0xBA);
				
				audioTag.soundChannels = FLVTagAudio.SOUND_CHANNELS_STEREO;
				assertTrue(audioTag.soundFormatByte == 0xBB);
				
				audioTag.soundChannels = FLVTagAudio.SOUND_CHANNELS_MONO;
				assertTrue(audioTag.soundFormatByte == 0xBA);

				audioTag.soundFormat = FLVTagAudio.SOUND_FORMAT_MP3_8K;
				assertTrue(audioTag.soundFormatByte == 0xEA);
				
				audioTag.soundRate = FLVTagAudio.SOUND_RATE_11K;
				assertTrue(audioTag.soundFormatByte == 0xE6);
				assertTrue(audioTag.soundRate == FLVTagAudio.SOUND_RATE_11K);
				
				audioTag.soundRate = FLVTagAudio.SOUND_RATE_5K;
				assertTrue(audioTag.soundFormatByte == 0xE2);
				assertTrue(audioTag.soundRate == FLVTagAudio.SOUND_RATE_5K);
				
				audioTag.soundRate = FLVTagAudio.SOUND_RATE_22K;
				assertTrue(audioTag.soundFormatByte == 0xEA);
				assertTrue(audioTag.soundRate == FLVTagAudio.SOUND_RATE_22K);
				
				audioTag.soundSize = FLVTagAudio.SOUND_SIZE_8BITS;
				assertTrue(audioTag.soundFormatByte == 0xE8);

				try
				{
					audioTag.soundRate = 5;
					assertTrue(false);
				}
				catch (er:Error)
				{				
				}

				try
				{
					audioTag.soundSize = 32;
					assertTrue(false);
				}
				catch (er:Error)
				{				
				}

				try
				{
					audioTag.soundChannels = 3;
					assertTrue(false);
				}
				catch (er:Error)
				{				
				}

				try
				{
					audioTag.isAACSequenceHeader = false;
					assertTrue(false);
				}
				catch (er:Error)
				{				
				}
				
				try
				{
					assertTrue(audioTag.isAACSequenceHeader);
					assertTrue(false);
				}
				catch (er:Error)
				{				
				}

				audioTag.soundFormat = FLVTagAudio.SOUND_FORMAT_AAC;
				audioTag.isAACSequenceHeader = false;
				assertTrue(audioTag.isAACSequenceHeader == false);
				audioTag.isAACSequenceHeader = true;
				assertTrue(audioTag.isAACSequenceHeader == true);
			}
			catch (e:Error)
			{
				assertTrue(false);
			}
		}
		
		public function testNonAACAudioTagStructure():void
		{
			var soundData:ByteArray = new ByteArray();
			for (var i:int = 0; i < 20; i++)
			{
				soundData.writeByte(i);
			}
			soundData.position = 0;
			
			var timeStamp:uint = new Date().getTime();
			var ba:ByteArray = FLVTestHelper.createFLVAudioTag(
				FLVTag.TAG_TYPE_AUDIO, 
				soundData.length + 1, 
				timeStamp, 
				0,	// always 0
				FLVTagAudio.SOUND_FORMAT_SPEEX,
				3,	// SoundRate 44kHz
				1,	// SoundSize snd16Bit
				1,	// SoundType sndStereo
				soundData);  
			ba.position = 0;
			
			try
			{
				var audioTag:FLVTagAudio = new FLVTagAudio();
				audioTag.read(ba);
				
				assertTrue(audioTag.tagType == FLVTag.TAG_TYPE_AUDIO);
				assertTrue(audioTag.dataSize == soundData.length + 1);
				assertTrue(audioTag.timestamp == timeStamp);
				assertTrue(audioTag.soundFormat == FLVTagAudio.SOUND_FORMAT_SPEEX);
				assertTrue(audioTag.soundRate == FLVTagAudio.SOUND_RATE_44K);
				assertTrue(audioTag.soundSize == FLVTagAudio.SOUND_SIZE_16BITS);
				assertTrue(audioTag.soundChannels == FLVTagAudio.SOUND_CHANNELS_STEREO);
				try
				{
					assertTrue(audioTag.isAACSequenceHeader == false);
					assertTrue(false);
				}
				catch(er:Error)
				{
				}
				assertTrue(audioTag.soundFormatByte == 0xBF);
				assertTrue(FLVTestHelper.compareByteArray(soundData, audioTag.data, 0));

				var soundData2:ByteArray = new ByteArray();
				for (i = 0; i < 10; i++)
				{
					soundData2.writeByte(15 - i);
				}
				soundData2.position = 0;
				
				audioTag.data = soundData2;
				assertTrue(FLVTestHelper.compareByteArray(soundData2, audioTag.data, 0));
			}
			catch (e:Error)
			{
				assertTrue(false);
			}
		}
	}
}