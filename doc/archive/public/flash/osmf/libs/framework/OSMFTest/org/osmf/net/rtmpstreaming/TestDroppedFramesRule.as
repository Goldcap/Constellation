/*****************************************************
*  
*  Copyright 2009 Akamai Technologies, Inc.  All Rights Reserved.
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
*  The Initial Developer of the Original Code is Akamai Technologies, Inc.
*  Portions created by Akamai Technologies, Inc. are Copyright (C) 2009 Akamai 
*  Technologies, Inc. All Rights Reserved. 
*  
*****************************************************/
package org.osmf.net.rtmpstreaming
{
	import flash.net.NetConnection;
	import flash.net.NetStream;
	
	import flexunit.framework.TestCase;
	
	import org.osmf.netmocker.MockRTMPNetStreamMetrics;
	import org.osmf.net.DynamicStreamingItem;
	import org.osmf.net.DynamicStreamingResource;
	import org.osmf.utils.NetFactory;

	public class TestDroppedFramesRule extends TestCase
	{
		public function testGetNewIndex():void
		{
			var netFactory:NetFactory = new NetFactory();
			var connection:NetConnection = netFactory.createNetConnection();
			connection.connect(null);
			
			var ns:NetStream = netFactory.createNetStream(connection);
			
			var metrics:MockRTMPNetStreamMetrics = new MockRTMPNetStreamMetrics(ns);
			
			var fdRule:DroppedFramesRule = new DroppedFramesRule(metrics);
			
			var result:int;
			
			// Test with an empty metrics object
			result = fdRule.getNewIndex();
			assertEquals(-1, result);
			
			var dsResource:DynamicStreamingResource = new DynamicStreamingResource(null);

			dsResource.streamItems.push(new DynamicStreamingItem("stream1_300kbps", 300));		
			dsResource.streamItems.push(new DynamicStreamingItem("stream2_500kbps", 500));
			dsResource.streamItems.push(new DynamicStreamingItem("stream3_1000kbps", 1000));
			dsResource.streamItems.push(new DynamicStreamingItem("stream4_3000kpbs", 3000));

			metrics.resource = dsResource;
			
			// Test dropping more than 10 frames
			metrics.currentIndex = 3;
			metrics.averageDroppedFPS = 11;
			result = fdRule.getNewIndex();
			assertEquals(2, result);
			
			// Test dropping more than 10 frames at current index at zero
			metrics.currentIndex = 0;
			metrics.averageDroppedFPS = 11;
			result = fdRule.getNewIndex();
			assertEquals(0, result);

			// Test dropping more than 20 frames
			metrics.currentIndex = 3;
			metrics.averageDroppedFPS = 21;
			result = fdRule.getNewIndex();
			assertEquals(1, result);

			// Test dropping more than 20 frames with current index at zero
			metrics.currentIndex = 0;
			metrics.averageDroppedFPS = 21;
			result = fdRule.getNewIndex();
			assertEquals(0, result);
			
			// Test dropping lots of frames
			metrics.currentIndex = 3;
			metrics.averageDroppedFPS = 40;
			result = fdRule.getNewIndex();
			assertEquals(0, result);
			
			// TODO: Add tests for the index locking behavior.
		}		
	}
}
