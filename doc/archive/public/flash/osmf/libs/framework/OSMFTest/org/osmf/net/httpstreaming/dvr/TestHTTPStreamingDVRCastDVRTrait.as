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
	import flash.net.NetConnection;
	
	import flexunit.framework.TestCase;
	
	import org.osmf.events.*;

	public class TestHTTPStreamingDVRCastDVRTrait extends TestCase
	{
		public function TestHTTPStreamingDVRCastDVRTrait(methodName:String=null)
		{
			super(methodName);
		}
		
		override public function setUp():void
		{
			super.setUp();

			var nc:NetConnection = new NetConnection();
			nc.connect(null);

			_info = new DVRInfo();
			var id:String = "dvr info";
			var url:String = "http://www.my.dvr.info/dvrinfo";
			var beginOffset:uint = 200;
			var endOffset:uint = 100;
			var curLength:Number = 200.50;
			_isRecording = true;
			var offline:Boolean = true;
			var startTime:Number = 20.25;
			
			_info.id = id;
			_info.url = url;
			_info.beginOffset = beginOffset;
			_info.endOffset = endOffset;
			_info.curLength = curLength;
			_info.isRecording = _isRecording;
			_info.offline = offline;
			_info.startTime = startTime;

			_ns = new MockHTTPNetStream(nc, 200.25);
			_dvrTrait = new HTTPStreamingDVRCastDVRTrait(nc, _ns, _info);
		}
		
		public function testHTTPStreamingDVRCastDVRTrait():void
		{
			assertTrue(_dvrTrait.isRecording == _isRecording);
			
			_info.isRecording = !_isRecording;
			_ns.dvrInfo = _info;
			_ns.DVRGetStreamInfo(null);
			assertTrue(_dvrTrait.isRecording != _isRecording);
			
			_ns.dvrInfo = null;
			_ns.DVRGetStreamInfo(null);
			assertTrue(_dvrTrait.isRecording == false);
		}

		private var _ns:MockHTTPNetStream;
		private var _info:DVRInfo;
		private var _dvrTrait:HTTPStreamingDVRCastDVRTrait;
		private var _isRecording:Boolean;
	}
}