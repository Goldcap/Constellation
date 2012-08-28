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
package org.osmf.net
{
	import __AS3__.vec.Vector;
	
	import flash.errors.IllegalOperationError;
	import flash.events.Event;
	import flash.events.EventDispatcher;
	import flash.events.NetStatusEvent;
	import flash.net.NetConnection;
	import flash.net.NetStream;
	import flash.net.NetStreamPlayOptions;
	import flash.net.NetStreamPlayTransitions;
	
	import flexunit.framework.TestCase;
	
	import org.osmf.netmocker.MockNetStream;
	import org.osmf.netmocker.MockRTMPNetStreamMetrics;
	import org.osmf.utils.DynamicSwitchingRule;
	import org.osmf.utils.NetFactory;
	
	public class TestNetStreamSwitchManager extends TestCase
	{
		override public function setUp():void
		{
			super.setUp();
			
			eventDispatcher = new EventDispatcher();
			
			dsResource = new DynamicStreamingResource("rtmp://www.example.com/ondemand");
			dsResource.streamItems.push(new DynamicStreamingItem("stream_500kbps", 500));
			dsResource.streamItems.push(new DynamicStreamingItem("stream_800kbps", 800));
			dsResource.streamItems.push(new DynamicStreamingItem("stream_1000kbps", 1000));
			dsResource.streamItems.push(new DynamicStreamingItem("stream_3000kbps", 3000));
			
			netFactory = new NetFactory();
			
			var connection:NetConnection = netFactory.createNetConnection();
			connection.connect(null);
			stream = netFactory.createNetStream(connection);
			stream.client = new NetClient();
			
			if (stream is MockNetStream)
			{
				(stream as MockNetStream).expectedDuration = 2;
			}
			
			metrics = new MockRTMPNetStreamMetrics(stream);

			var rules:Vector.<SwitchingRuleBase> = new Vector.<SwitchingRuleBase>();
			switchingRule = new DynamicSwitchingRule(metrics);
			rules.push(switchingRule);

			switchManager = new NetStreamSwitchManager(connection, stream, dsResource, metrics, rules);
		}
		
		override public function tearDown():void
		{
			super.tearDown();
			
			dsResource = null;
			metrics = null;
			switchingRule = null;
			switchManager = null;
			netFactory = null;
			eventDispatcher = null;
			stream.close();
			stream = null;
		}
		
		public function testConstructorWithInitialIndex():void
		{
			dsResource.initialIndex = 3;
			
			var connection:NetConnection = netFactory.createNetConnection();
			connection.connect(null);
			var stream:NetStream = netFactory.createNetStream(connection);
			stream.client = new NetClient();
			
			if (stream is MockNetStream)
			{
				(stream as MockNetStream).expectedDuration = 2;
			}
			
			metrics = new MockRTMPNetStreamMetrics(stream);

			var rules:Vector.<SwitchingRuleBase> = new Vector.<SwitchingRuleBase>();
			switchingRule = new DynamicSwitchingRule(metrics);
			rules.push(switchingRule);

			switchManager = new NetStreamSwitchManager(connection, stream, dsResource, metrics, rules);

			assertTrue(switchManager.currentIndex == 3);
		}
		
		public function testMaxAllowedIndexToWithInvalidValue():void
		{
			try
			{
				switchManager.maxAllowedIndex = 5;
				
				fail();
			}
			catch (e:RangeError)
			{
			}
		}

		
		public function testSwitchTo():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, 5000));
			
			assertTrue(switchManager.autoSwitch);
			
			stream.addEventListener(NetStatusEvent.NET_STATUS, onTestSwitchTo1);
			NetClient(stream.client).addHandler(NetStreamCodes.ON_PLAY_STATUS, onTestSwitchTo2);
			
			switchManager.autoSwitch = false;
			
			playStream();
			switchManager.switchTo(2);
		}

		private function onTestSwitchTo1(event:NetStatusEvent):void
		{
			if (event.info.code == NetStreamCodes.NETSTREAM_PLAY_TRANSITION)
			{
				assertTrue(switchManager.currentIndex == 0);
			}
		}

		private function onTestSwitchTo2(info:Object):void
		{
			if (info.code == NetStreamCodes.NETSTREAM_PLAY_TRANSITION_COMPLETE)
			{
				assertTrue(switchManager.currentIndex == 2);
				
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}
		
		public function testSwitchToWithInvalidParams():void
		{
			// Switching when autoSwitch is true is illegal.
			switchManager.autoSwitch = true;
			try
			{
				switchManager.switchTo(2);
				
				fail();
			}
			catch (e:IllegalOperationError)
			{
			}
			
			// Switching to too high an index is illegal.
			switchManager.autoSwitch = false;
			try
			{
				switchManager.switchTo(100);
				
				fail();
			}
			catch (e:RangeError)
			{
			}

			// Switching to too low an index is illegal.
			switchManager.autoSwitch = false;
			try
			{
				switchManager.switchTo(-1);
				
				fail();
			}
			catch (e:RangeError)
			{
			}

			// Switching to a valid index but one that's above
			// maxAllowedIndex is illegal.
			switchManager.maxAllowedIndex = 1; 
			try
			{
				switchManager.switchTo(2);
				
				fail();
			}
			catch (e:RangeError)
			{
			}
		}
		
		public function testAutoSwitchWithNoSwitch():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, 5000));
			
			assertTrue(switchManager.autoSwitch);
			NetClient(stream.client).addHandler(NetStreamCodes.ON_PLAY_STATUS, onTestAutoSwitchWithNoSwitch);

			
			playStream();
		}
		
		private function onTestAutoSwitchWithNoSwitch(info:Object):void
		{
			if (info.code == NetStreamCodes.NETSTREAM_PLAY_TRANSITION_COMPLETE)
			{
				// There should be no switch.
				fail();
			}
			else if (info.code == NetStreamCodes.NETSTREAM_PLAY_COMPLETE)
			{
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}

		public function testAutoSwitchWithSwitchDown():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, 6000));
			
			assertTrue(switchManager.autoSwitch);
			NetClient(stream.client).addHandler(NetStreamCodes.ON_PLAY_STATUS, onTestAutoSwitchWithSwitchDown);
			
			dsResource.initialIndex = 2;
			
			playStream();
			
			// Suggest a down-switch.
			switchingRule.newIndex = 1;
		}
		
		private function onTestAutoSwitchWithSwitchDown(info:Object):void
		{
			if (info.code == NetStreamCodes.NETSTREAM_PLAY_TRANSITION_COMPLETE)
			{
				assertTrue(switchManager.currentIndex == 1);
				
				stream.addEventListener(NetStatusEvent.NET_STATUS, onTestAutoSwitchWithSwitchDown2);
				
				/// Suggest an up-switch.  This should fail, because we'll be locked
				// out.
				switchingRule.newIndex = 2;
			}
		}
		
		private function onTestAutoSwitchWithSwitchDown2(event:NetStatusEvent):void
		{
			if (event.info.code == NetStreamCodes.NETSTREAM_PLAY_STOP)
			{
				// Should reach the end without switching.
				assertTrue(switchManager.currentIndex == 1);
				
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}

		public function testAutoSwitchWithSwitchUp():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, 5000));
			
			assertTrue(switchManager.autoSwitch);
			NetClient(stream.client).addHandler(NetStreamCodes.ON_PLAY_STATUS, onTestAutoSwitchWithSwitchUp);
			
			playStream();
			
			// Suggest an up-switch.
			switchingRule.newIndex = 2;
		}
		
		private function onTestAutoSwitchWithSwitchUp(info:Object):void
		{
			if (info.code == NetStreamCodes.NETSTREAM_PLAY_TRANSITION_COMPLETE)
			{
				assertTrue(switchManager.currentIndex == 2);
				
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}

		public function testAutoSwitchWithSwitchUpWithMaxIndex():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, 5000));
			
			assertTrue(switchManager.autoSwitch);
			NetClient(stream.client).addHandler(NetStreamCodes.ON_PLAY_STATUS, onTestAutoSwitchWithSwitchUpWithMaxIndex);
			
			playStream();
			
			// Set our max index.
			switchManager.maxAllowedIndex = 2;
			
			// Suggest an up-switch.
			switchingRule.newIndex = 3;
		}
		
		private function onTestAutoSwitchWithSwitchUpWithMaxIndex(info:Object):void
		{
			if (info.code == NetStreamCodes.NETSTREAM_PLAY_TRANSITION_COMPLETE)
			{
				assertTrue(switchManager.currentIndex == 2);
				
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}
		private function mustReceiveEvent(event:Event):void
		{
			// Placeholder to ensure an event is received.
		}
		
		private function playStream():void
		{
			var nso:NetStreamPlayOptions = new NetStreamPlayOptions();

			nso.start = -2;
			nso.len = -1;
			nso.streamName = dsResource.streamItems[0].streamName;
			nso.transition = NetStreamPlayTransitions.RESET;

			stream.play2(nso);
		}
		
		private static const ASYNC_DELAY:Number = 90000;

		private var eventDispatcher:EventDispatcher;
		private var netFactory:NetFactory;
		private var switchingRule:DynamicSwitchingRule;
		private var switchManager:NetStreamSwitchManager;
		private var metrics:NetStreamMetricsBase;
		private var stream:NetStream;
		private var dsResource:DynamicStreamingResource;
	}
}