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
package org.osmf.net.httpstreaming
{
	import flash.net.NetConnection;
	
	import flexunit.framework.TestCase;
	
	import org.osmf.net.DynamicStreamingItem;
	import org.osmf.net.DynamicStreamingResource;
	import org.osmf.netmocker.MockHTTPNetStreamMetrics;
	import org.osmf.utils.NetFactory;
	
	public class TestDownloadRatioRule extends TestCase
	{
		public function testGetNewIndex():void
		{
			var netFactory:NetFactory = new NetFactory();
			var connection:NetConnection = netFactory.createNetConnection();
			connection.connect(null);

			var metrics:MockHTTPNetStreamMetrics = new MockHTTPNetStreamMetrics(new HTTPNetStream(connection, new HTTPStreamingIndexHandlerBase(), new HTTPStreamingFileHandlerBase()));
			
			var drRule:DownloadRatioRule = new DownloadRatioRule(metrics);
			
			var dsResource:DynamicStreamingResource = new DynamicStreamingResource(null);
			dsResource.streamItems.push(new DynamicStreamingItem("stream1_300kbps", 300));
			dsResource.streamItems.push(new DynamicStreamingItem("stream2_500kbps", 500));
			dsResource.streamItems.push(new DynamicStreamingItem("stream3_1000kbps", 1000));
			dsResource.streamItems.push(new DynamicStreamingItem("stream4_3000kpbs", 3000));
			
			metrics.resource = dsResource;

			assertTrue(drRule.getNewIndex() == -1);
			
			// Case #1: downloadRatio is less than one, and less than the previous
			// index's switch ratio.
			metrics.currentIndex = 2;
			metrics.downloadRatio = 0.45;
			assertTrue(drRule.getNewIndex() == 0);
			
			// Case #2: downloadRatio is less than one, but more than the previous
			// index's switch ratio.
			metrics.downloadRatio = 0.55;
			assertTrue(drRule.getNewIndex() == 1);
			
			// Case #3: downloadRatio is greater than one, but less than the next
			// index's switch ratio.
			metrics.currentIndex = 0;
			metrics.downloadRatio = 1.5;
			assertTrue(drRule.getNewIndex() == -1);

			// Case #4a: downloadRatio is greater than one, and greater than the next
			// index's switch ratio.
			metrics.downloadRatio = 1.7;
			assertTrue(drRule.getNewIndex() == 1);

			// Case #4b: downloadRatio is greater than one, and greater than the next
			// two indices' switch ratio.
			metrics.downloadRatio = 3.5;
			assertTrue(drRule.getNewIndex() == 2);

			// Case #4c: downloadRatio is greater than one, and greater than all other
			// indices' switch ratios.
			metrics.downloadRatio = 11;
			assertTrue(drRule.getNewIndex() == 3);

			// Case #4d: downloadRatio is suspiciously high (a sign of cached data).
			metrics.downloadRatio = 101;
			assertTrue(drRule.getNewIndex() == 1);
			
			// Case #4d: downloadRatio is high enough for a switch, but we're at the
			// highest index.
			metrics.currentIndex = 3;
			assertTrue(drRule.getNewIndex() == -1);
		}
		
		public function testGetNewIndexWithAggressiveUpswitchingDisabled():void
		{
			var netFactory:NetFactory = new NetFactory();
			var connection:NetConnection = netFactory.createNetConnection();
			connection.connect(null);

			var metrics:MockHTTPNetStreamMetrics = new MockHTTPNetStreamMetrics(new HTTPNetStream(connection, new HTTPStreamingIndexHandlerBase(), new HTTPStreamingFileHandlerBase()));
			
			var drRule:DownloadRatioRule = new DownloadRatioRule(metrics, false);
			
			var dsResource:DynamicStreamingResource = new DynamicStreamingResource(null);
			dsResource.streamItems.push(new DynamicStreamingItem("stream1_300kbps", 300));
			dsResource.streamItems.push(new DynamicStreamingItem("stream2_500kbps", 500));
			dsResource.streamItems.push(new DynamicStreamingItem("stream3_1000kbps", 1000));
			dsResource.streamItems.push(new DynamicStreamingItem("stream4_3000kpbs", 3000));
			
			metrics.resource = dsResource;

			assertTrue(drRule.getNewIndex() == -1);
			
			// Case #4e: Same as 4c.
			metrics.currentIndex = 0;
			metrics.downloadRatio = 11;
			assertTrue(drRule.getNewIndex() == 1);
		}
	}
}