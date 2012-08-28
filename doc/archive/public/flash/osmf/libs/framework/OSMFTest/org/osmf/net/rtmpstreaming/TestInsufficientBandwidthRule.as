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
	
	import flexunit.framework.TestCase;
	
	import org.osmf.net.DynamicStreamingItem;
	import org.osmf.net.DynamicStreamingResource;
	import org.osmf.netmocker.MockRTMPNetStreamMetrics;
	import org.osmf.utils.NetFactory;

	public class TestInsufficientBandwidthRule extends TestCase
	{
		override public function setUp():void
		{
			super.setUp();

			netFactory = new NetFactory();
		}

		public function testGetNewIndex():void
		{
			var connection:NetConnection = netFactory.createNetConnection();
			connection.connect(null);
			
			var metrics:MockRTMPNetStreamMetrics = new MockRTMPNetStreamMetrics(netFactory.createNetStream(connection));
			
			var bwRule:InsufficientBandwidthRule = new InsufficientBandwidthRule(metrics);
			
			var result:int;
			
			// Test with an empty metrics object
			result = bwRule.getNewIndex();
			assertEquals(-1, result);
			
			// Test with bandwidth higher than the current stream
			var dsResource:DynamicStreamingResource = new DynamicStreamingResource(null);
			dsResource.streamItems.push(new DynamicStreamingItem("stream1_300kbps", 300));
			
			metrics.averageMaxBytesPerSecond = 5000 * 1024 / 8;
			metrics.resource = dsResource;
			result = bwRule.getNewIndex();
			assertEquals(-1, result);
			
			// Test with bandwidth lower than the current stream
			dsResource.streamItems.push(new DynamicStreamingItem("stream2_500kbps", 500));
			dsResource.streamItems.push(new DynamicStreamingItem("stream3_1000kbps", 1000));
			dsResource.streamItems.push(new DynamicStreamingItem("stream4_3000kpbs", 3000));
			
			metrics.averageMaxBytesPerSecond = 1234 * 1024 / 8;
			metrics.currentIndex = 3;
			result = bwRule.getNewIndex();
			assertEquals(2, result);
			
			// Another test with very low bandwidth
			metrics.averageMaxBytesPerSecond = 500 * 1024 / 8;
			result = bwRule.getNewIndex();
			assertEquals(0, result);
			
			// Another test with ridiculously low bandwidth
			metrics.averageMaxBytesPerSecond = 1 * 1024 / 8;
			result = bwRule.getNewIndex();
			assertEquals(-1, result);
		}
		
		private var netFactory:NetFactory;
	}
}
