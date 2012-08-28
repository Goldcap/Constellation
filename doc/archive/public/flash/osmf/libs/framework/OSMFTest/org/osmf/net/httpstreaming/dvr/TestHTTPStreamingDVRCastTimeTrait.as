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
package org.osmf.net.httpstreaming.dvr
{
	import flash.net.NetConnection;
	
	import flexunit.framework.TestCase;
	
	import org.osmf.events.*;

	public class TestHTTPStreamingDVRCastTimeTrait extends TestCase
	{
		public function TestHTTPStreamingDVRCastTimeTrait(methodName:String=null)
		{
			super(methodName);
		}
		
		override public function setUp():void
		{
			super.setUp();

			_complete = false;
			_currentTime = 150.15;
			
			var nc:NetConnection = new NetConnection();
			nc.connect(null);
			_ns = new MockHTTPNetStream(nc, _currentTime);
			_timeTrait = new HTTPStreamingDVRCastTimeTrait(nc, _ns, null);
		}
		
		public function testProperties():void
		{
			assertTrue(isNaN(_timeTrait.duration));
			assertEquals(_timeTrait.currentTime, _currentTime);
			
			_info = new DVRInfo();
			var id:String = "dvr info";
			var url:String = "http://www.my.dvr.info/dvrinfo";
			var beginOffset:uint = 200;
			var endOffset:uint = 100;
			var curLength:Number = 200.50;
			var isRecording:Boolean = false;
			var offline:Boolean = true;
			var startTime:Number = 20.25;
			
			_info.id = id;
			_info.url = url;
			_info.beginOffset = beginOffset;
			_info.endOffset = endOffset;
			_info.curLength = curLength;
			_info.isRecording = isRecording;
			_info.offline = offline;
			_info.startTime = startTime;
			
			_ns.dvrInfo = _info;
			_ns.DVRGetStreamInfo(null);
			
			assertEquals(_timeTrait.duration, _info.curLength);
			assertEquals(_timeTrait.currentTime, _currentTime);
		}
		
		public function testUnpublish():void
		{
			_timeTrait.addEventListener(TimeEvent.COMPLETE, onComplete);
			_ns.unpublish();
			
			assertTrue(_complete);
		}
		
		private function onComplete(event:TimeEvent):void
		{
			_complete = true;
		}
		
		private var _timeTrait:HTTPStreamingDVRCastTimeTrait;
		private var _ns:MockHTTPNetStream;
		private var _currentTime:Number;
		private var _info:DVRInfo;
		private var _complete:Boolean;
	}
}