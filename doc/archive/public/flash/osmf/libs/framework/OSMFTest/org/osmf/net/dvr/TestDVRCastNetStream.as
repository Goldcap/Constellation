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
	import flash.events.NetStatusEvent;
	import flash.net.NetStreamPlayOptions;
	
	import org.osmf.flexunit.TestCaseEx;
	import org.osmf.net.StreamingURLResource;


	public class TestDVRCastNetStream extends TestCaseEx
	{
		public function testDVRCastNetStream():void
		{
			// Pro forma (the class does a single override on 'play'):
			assertThrows(function():void{ new DVRCastNetStream(null, null); });
			
			var c:MockDVRCastNetConnection = new MockDVRCastNetConnection();
			var r:StreamingURLResource = new StreamingURLResource("rtmp://example.com");
			r.addMetadataValue(DVRCastConstants.RECORDING_INFO_KEY, new DVRCastRecordingInfo());
			
			var stream:DVRCastNetStream = new DVRCastNetStream(c, r);
			assertNotNull(stream);
			stream.addEventListener(NetStatusEvent.NET_STATUS, function(..._):void{});
			
			stream.play("test", 0, 0);
			
			var options:NetStreamPlayOptions = new NetStreamPlayOptions();
			stream.play2(options);
			try
			{
				stream.play2(null);
			}
			catch(e:Error)
			{
			}
		}
		
	}
}