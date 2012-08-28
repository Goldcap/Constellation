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
package org.osmf.net.httpstreaming
{
	import __AS3__.vec.Vector;
	
	import flash.net.NetConnection;
	
	import flexunit.framework.TestCase;
	
	import org.osmf.net.DynamicStreamingItem;
	import org.osmf.net.DynamicStreamingResource;
	import org.osmf.net.httpstreaming.dvr.MockHTTPNetStream;

	public class TestHTTPNetStreamMetrics extends TestCase
	{
		public function TestHTTPNetStreamMetrics(methodName:String=null)
		{
			super(methodName);
		}
		
		public function testHTTPNetStreamMetrics():void
		{
			var nc:NetConnection = new NetConnection();
			nc.connect(null);
			
			var ns:MockHTTPNetStream = new MockHTTPNetStream(nc, 200.25);
			ns.downloadRatio = _downloadRatio;
			
			var metrics:HTTPNetStreamMetrics = new HTTPNetStreamMetrics(ns);
			assertEquals(metrics.downloadRatio, _downloadRatio);
			
			var dsResource:DynamicStreamingResource = new DynamicStreamingResource("http://www.host.com");
			var streamItem1:DynamicStreamingItem = new DynamicStreamingItem("stream1", 100.0, 200, 100);
			var streamItem2:DynamicStreamingItem = new DynamicStreamingItem("stream2", 200.0, 400, 200);
			var streamItem3:DynamicStreamingItem = new DynamicStreamingItem("stream3", 300.0, 600, 300);
			var items:Vector.<DynamicStreamingItem> = new Vector.<DynamicStreamingItem>();
			items[0] = streamItem1; 
			items[1] = streamItem2; 
			items[2] = streamItem3;
			dsResource.streamItems = items;
			metrics.resource = dsResource;
			
			assertEquals(metrics.getBitrateForIndex(0), 100.0);
			assertEquals(metrics.getBitrateForIndex(1), 200.0);
			assertEquals(metrics.getBitrateForIndex(2), 300.0);
		}
		
		private var _downloadRatio:Number = 20.5;
	}
}