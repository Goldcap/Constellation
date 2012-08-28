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

package org.osmf.net.httpstreaming.dvr
{
	import flexunit.framework.TestCase;
	
	public class TestDVRInfo extends TestCase
	{
		public function TestDVRInfo(methodName:String=null)
		{
			super(methodName);
		}
		
		public function testDVRInfo():void
		{
			var info:DVRInfo = new DVRInfo();
			var id:String = "dvr info";
			var url:String = "http://www.my.dvr.info/dvrinfo";
			var beginOffset:uint = 200;
			var endOffset:uint = 100;
			var curLength:Number = 200.50;
			var isRecording:Boolean = false;
			var offline:Boolean = true;
			var startTime:Number = 20.25;
			
			info.id = id;
			info.url = url;
			info.beginOffset = beginOffset;
			info.endOffset = endOffset;
			info.curLength = curLength;
			info.isRecording = isRecording;
			info.offline = offline;
			info.startTime = startTime;
			
			assertEquals(info.id, id);
			assertEquals(info.url, url);
			assertEquals(info.beginOffset, beginOffset);
			assertEquals(info.endOffset, endOffset);
			assertEquals(info.curLength, curLength);
			assertEquals(info.isRecording, isRecording);
			assertEquals(info.offline, offline);
			assertEquals(info.startTime, startTime);
		}
	}
}