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

	public class TestFLVHeader extends TestCase
	{
		public function TestFLVHeader(methodName:String=null)
		{
			super(methodName);
		}
		
		public function testBasicConstructor():void
		{
			try 
			{
				var header:FLVHeader = new FLVHeader(null);
			}	
			catch (e:Error)
			{
				assertTrue(false);
			}
		}
		
		public function testShortHeader():void
		{
			var ba:ByteArray = new ByteArray();
			ba.writeByte(1);
			ba.position = 0;
			
			try 
			{
				var header:FLVHeader = new FLVHeader(ba);
				assertTrue(false);
			}	
			catch (e:Error)
			{
			}
		}
		
		public function testBasicFields():void
		{
			var ba:ByteArray = FLVTestHelper.createFLVHeader(
				0x46, // 'F'
				0x4C, // 'L'
				0x56, // 'V'
				0x01, // version 1
				false,
				true,
				FLVHeader.MIN_FILE_HEADER_BYTE_COUNT,
				true);
			ba.position = 0;
			
			try
			{
				var header:FLVHeader = new FLVHeader(ba);
				assertTrue(!header.hasAudioTags);
				assertTrue(header.hasVideoTags);
				
				var output:ByteArray = new ByteArray();
				header.write(output);
				
				assertTrue(FLVTestHelper.compareByteArray(ba, output));
				
				header.hasAudioTags = true;
				header.hasVideoTags = false;
				
				assertTrue(header.hasAudioTags);
				assertTrue(!header.hasVideoTags);
			}
			catch (e:Error)
			{
				assertTrue(false);
			}
		}
		
		public function testGoodFields():void
		{
			assertTrue(runGoodFieldsTest(
				0x46, // 'F'
				0x4C, // 'L'
				0x56, // 'V'
				0x01, // version 1
				true,
				true,
				FLVHeader.MIN_FILE_HEADER_BYTE_COUNT,
				true));

			assertTrue(runGoodFieldsTest(
				0x46, // 'F'
				0x4C, // 'L'
				0x56, // 'V'
				0x01, // version 1
				true,
				false,
				FLVHeader.MIN_FILE_HEADER_BYTE_COUNT,
				true));

			assertTrue(runGoodFieldsTest(
				0x46, // 'F'
				0x4C, // 'L'
				0x56, // 'V'
				0x01, // version 1
				false,
				true,
				FLVHeader.MIN_FILE_HEADER_BYTE_COUNT,
				true));

			assertTrue(runGoodFieldsTest(
				0x46, // 'F'
				0x4C, // 'L'
				0x56, // 'V'
				0x01, // version 1
				false,
				false,
				FLVHeader.MIN_FILE_HEADER_BYTE_COUNT,
				true));
		}
		
		private function runGoodFieldsTest(
			signature0:uint, 
			signature1:uint, 
			signature2:uint, 
			version:uint, 
			audioFlag:Boolean, 
			videoFlag:Boolean, 
			dataOffset:uint,
			appendPrevTagSize:Boolean):Boolean
		{
			var ba:ByteArray = FLVTestHelper.createFLVHeader(
				signature0,
				signature1,
				signature2,
				version,
				audioFlag,
				videoFlag,
				dataOffset,
				appendPrevTagSize);
			ba.position = 0;
			
			var flag:Boolean = false;
			try
			{
				var header:FLVHeader = new FLVHeader(ba);
				assertTrue(header.hasAudioTags == audioFlag);
				assertTrue(header.hasVideoTags == videoFlag);
				
				var output:ByteArray = new ByteArray();
				header.write(output);
				
				assertTrue(FLVTestHelper.compareByteArray(ba, output));
				flag = true;
			}
			catch (e:Error)
			{
			}
			
			return flag;
		}
		
		public function testBadFields():void
		{
			assertTrue(runBadFieldsTest(
				0x02,
				0x4C,
				0x56,
				0x01,
				FLVHeader.MIN_FILE_HEADER_BYTE_COUNT));

			assertTrue(runBadFieldsTest(
				0x46,
				0x02,
				0x56,
				0x01,
				FLVHeader.MIN_FILE_HEADER_BYTE_COUNT));

			assertTrue(runBadFieldsTest(
				0x46,
				0x4C,
				0x02,
				0x01,
				FLVHeader.MIN_FILE_HEADER_BYTE_COUNT));

			assertTrue(runBadFieldsTest(
				0x46,
				0x4C,
				0x56,
				0x02,
				FLVHeader.MIN_FILE_HEADER_BYTE_COUNT));

			assertTrue(runBadFieldsTest(
				0x46,
				0x4C,
				0x56,
				0x01,
				FLVHeader.MIN_FILE_HEADER_BYTE_COUNT - 2));
		}

		private function runBadFieldsTest(
			signature0:uint, 
			signature1:uint, 
			signature2:uint, 
			version:uint, 
			dataOffset:uint):Boolean
		{
			var ba:ByteArray = FLVTestHelper.createFLVHeader(
				signature0,
				signature1,
				signature2,
				version,
				false,
				true,
				dataOffset,
				true);
			ba.position = 0;
			
			var flag:Boolean = false;
			try
			{
				var header:FLVHeader = new FLVHeader(ba);
			}
			catch (e:Error)
			{
				flag = true;
			}
			
			return flag;
		}			
	}
}