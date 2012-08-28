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
	
	import flash.events.Event;
	import flash.events.EventDispatcher;
	import flash.net.NetConnection;
	
	import flexunit.framework.TestCase;
	
	import org.osmf.events.MediaErrorCodes;
	import org.osmf.events.NetConnectionFactoryEvent;
	import org.osmf.media.URLResource;
	import org.osmf.netmocker.DefaultNetConnectionFactory;
	import org.osmf.netmocker.NetConnectionExpectation;
	import org.osmf.utils.TestConstants;

	public class TestNetConnectionFactory extends TestCase
	{
		override public function setUp():void
		{
			eventDispatcher = new EventDispatcher();

			super.setUp();
		}
		
		override public function tearDown():void
		{
			super.tearDown();
			
			eventDispatcher = null;
		}
		
		public function testCreateWithSharingEnabled():void
		{
			testCreateMethod(true);
		}
		
		public function testCreateWithSharingDisabled():void
		{
			testCreateMethod(false);
		}

		public function testCreateMultipleWithSharingEnabled():void
		{
			testCreateMethod(true, SUCCESSFUL_RESOURCE2);
		}
		
		public function testCloseNetConnection():void
		{
			doTestCloseNetConnection(true);
		}
		
		public function testCreateWithGoodResource():void
		{
			// test protocols
			doTestCreateWithGoodResource(new URLResource(TestConstants.REMOTE_STREAMING_VIDEO_RTMP));
			doTestCreateWithGoodResource(new URLResource(TestConstants.REMOTE_STREAMING_VIDEO_RTMPE));
			doTestCreateWithGoodResource(new URLResource(TestConstants.REMOTE_STREAMING_VIDEO_RTMPTE));
			doTestCreateWithGoodResource(new URLResource(TestConstants.REMOTE_STREAMING_VIDEO_RTMPS));
			doTestCreateWithGoodResource(new URLResource(TestConstants.REMOTE_STREAMING_VIDEO_RTMPT));
			
			// test ports
			doTestCreateWithGoodResource(new URLResource(TestConstants.REMOTE_STREAMING_VIDEO_1935));
			doTestCreateWithGoodResource(new URLResource(TestConstants.REMOTE_STREAMING_VIDEO_443));
			doTestCreateWithGoodResource(new URLResource(TestConstants.REMOTE_STREAMING_VIDEO_80));
			
			doTestCreateWithGoodResource(new URLResource(TestConstants.REMOTE_STREAMING_VIDEO_RTMP));

			// test with useInstance
			var streamingURLResource:StreamingURLResource = new StreamingURLResource(TestConstants.REMOTE_STREAMING_VIDEO_RTMP);
			streamingURLResource.urlIncludesFMSApplicationInstance = true;
			doTestCreateWithGoodResource(streamingURLResource);
		}
		
		public function testCreateWithRTMPRedirect():void
		{
			doTestCreateWithRTMPRedirect();
		}
		
		public function testCreateWithBadResource():void
		{
			doTestCreateWithBadResource();
		}
		
		public function testCreateWithUnknownProtocolResource():void
		{
			doTestCreateWithUnknownProtocolResource();
		}

		public function testCreateWithConnectionArguments():void
		{
			doTestCreateWithConnectionArguments();
		}
		
		public function testDirectCreateWithBadResource():void
		{
			// The purpose of this test is to ensure coverage of the createNetConnection method
			// which is otherwise over-ridden by NetMocker. We test with an intentionally bad URL so that
			// the outcome of the test is not dependent on connectivity.
			doTestDirectCreateWithBadResource();
		}
		
		public function testCreateWithIOError():void
		{
			// test IO Error
			doTestError(NetConnectionExpectation.IO_ERROR,MediaErrorCodes.IO_ERROR);
		}
		
		public function testCreateWithArgumentError():void
		{
			// test Argument Error
			doTestError(NetConnectionExpectation.ARGUMENT_ERROR,MediaErrorCodes.ARGUMENT_ERROR);
		}
		
		public function testCreateWithSecurityError():void
		{
			// test Security Error
			doTestError(NetConnectionExpectation.SECURITY_ERROR,MediaErrorCodes.SECURITY_ERROR);
		}
		
		/////////////////////////////////////////
		
		private function testCreateMethod(sharing:Boolean, secondResource:URLResource=null):void
		{
			eventDispatcher.addEventListener("testComplete",addAsync(mustReceiveEvent,TEST_TIME));
			
			var factory:NetConnectionFactory = createNetConnectionFactory(sharing);
			factory.addEventListener(NetConnectionFactoryEvent.CREATION_COMPLETE,onCreated);
			var resource:URLResource = SUCCESSFUL_RESOURCE;
			factory.create(resource);
			function onCreated(event:NetConnectionFactoryEvent):void
			{
				assertTrue(event.type == NetConnectionFactoryEvent.CREATION_COMPLETE);
				assertTrue(event.netConnection.connected);
				assertStrictlyEquals(event.resource,resource);
				
				if (secondResource != null)
				{
					factory.removeEventListener(NetConnectionFactoryEvent.CREATION_COMPLETE,onCreated);
					factory.addEventListener(NetConnectionFactoryEvent.CREATION_COMPLETE,onCreated2);
					
					var resource2:URLResource = secondResource;
					factory.create(resource2);
					
					function onCreated2(event2:NetConnectionFactoryEvent):void
					{
						assertTrue(event2.type == NetConnectionFactoryEvent.CREATION_COMPLETE);
						assertTrue(event2.netConnection.connected);
						assertStrictlyEquals(event2.resource,resource2);
						
						// As long as both resources have the same app instance, they
						// should use the same NetConnection.
						assertStrictlyEquals(event.netConnection,event2.netConnection);
						
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}
				}
				else
				{
					eventDispatcher.dispatchEvent(new Event("testComplete"));
				}
			}
		}
		
		private function doTestCloseNetConnection(sharing:Boolean):void
		{
			eventDispatcher.addEventListener("testComplete",addAsync(mustReceiveEvent,TEST_TIME));
			
			var factory:NetConnectionFactory = createNetConnectionFactory(sharing);
			factory.addEventListener(NetConnectionFactoryEvent.CREATION_COMPLETE,onCreated);
			var resource:URLResource = SUCCESSFUL_RESOURCE;
			factory.create(resource);
			function onCreated(event:NetConnectionFactoryEvent):void
			{
				assertTrue(event.type == NetConnectionFactoryEvent.CREATION_COMPLETE);
				assertTrue(event.netConnection.connected);
				assertStrictlyEquals(event.resource,resource);
				var nc:NetConnection = event.netConnection;
				factory.closeNetConnection(nc);
				assertFalse(nc.connected);
				
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}
		
		private function doTestCreateWithGoodResource(resource:URLResource):void
		{
			eventDispatcher.addEventListener("testComplete",addAsync(mustReceiveEvent,TEST_TIME));
			
			var factory:NetConnectionFactory = createNetConnectionFactory();
			factory.addEventListener(NetConnectionFactoryEvent.CREATION_COMPLETE,onCreated);
			factory.create(resource);
			function onCreated(event:NetConnectionFactoryEvent):void
			{
				assertTrue(event.type == NetConnectionFactoryEvent.CREATION_COMPLETE);
				assertTrue(event.netConnection.connected);
				assertStrictlyEquals(event.resource,resource);
				assertTrue(event.mediaError == null);
				
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}
		
		private function doTestCreateWithRTMPRedirect():void
		{
			eventDispatcher.addEventListener("testComplete",addAsync(mustReceiveEvent,TEST_TIME));
			
			var factory:NetConnectionFactory = createNetConnectionFactory(true, NetConnectionExpectation.CONNECT_WITH_REDIRECT);
			factory.addEventListener(NetConnectionFactoryEvent.CREATION_COMPLETE,onCreated);
			factory.create(UNSUCCESSFUL_RESOURCE);
			function onCreated(event:NetConnectionFactoryEvent):void
			{
				assertTrue(event.type == NetConnectionFactoryEvent.CREATION_COMPLETE);
				assertTrue(event.netConnection.connected);
				
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}
		private function doTestCreateWithBadResource():void
		{
			eventDispatcher.addEventListener("testComplete",addAsync(mustReceiveEvent,TEST_TIME));
			
			var factory:NetConnectionFactory = createNetConnectionFactory(true, NetConnectionExpectation.REJECTED_CONNECTION);
			factory.addEventListener(NetConnectionFactoryEvent.CREATION_ERROR,onCreationFailed);
			factory.create(UNSUCCESSFUL_RESOURCE);
			function onCreationFailed(event:NetConnectionFactoryEvent):void
			{
				assertTrue(event.type == NetConnectionFactoryEvent.CREATION_ERROR);
				assertTrue(event.netConnection == null);
				assertTrue(event.mediaError.errorID == MediaErrorCodes.NETCONNECTION_REJECTED);
				
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}
		
		private function doTestCreateWithUnknownProtocolResource():void
		{
			eventDispatcher.addEventListener("testComplete",addAsync(mustReceiveEvent,TEST_TIME));
			
			var factory:NetConnectionFactory = createNetConnectionFactory(true, NetConnectionExpectation.REJECTED_CONNECTION);
			factory.addEventListener(NetConnectionFactoryEvent.CREATION_ERROR,onCreationFailed);
			factory.create(UNKNOWN_PROTOCOL_RESOURCE);
			function onCreationFailed(event:NetConnectionFactoryEvent):void
			{
				assertTrue(event.type == NetConnectionFactoryEvent.CREATION_ERROR);
				assertTrue(event.netConnection == null);
				assertTrue(event.mediaError.errorID == MediaErrorCodes.NETCONNECTION_REJECTED);
				
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}
		
		private function doTestCreateWithConnectionArguments():void
		{
			eventDispatcher.addEventListener("testComplete",addAsync(mustReceiveEvent,TEST_TIME));
			
			var streamingResource:StreamingURLResource = new StreamingURLResource(SUCCESSFUL_RESOURCE.url);
			streamingResource.connectionArguments = new Vector.<Object>();
			streamingResource.connectionArguments.push("myUsername");
			streamingResource.connectionArguments.push("myPassword");
			
			var factory:NetConnectionFactory = createNetConnectionFactory(true, NetConnectionExpectation.CONNECT_WITH_PARAMS);
			factory.addEventListener(NetConnectionFactoryEvent.CREATION_COMPLETE,onCreated);
			factory.create(streamingResource);
			function onCreated(event:NetConnectionFactoryEvent):void
			{
				assertTrue(event.type == NetConnectionFactoryEvent.CREATION_COMPLETE);
				assertTrue(event.netConnection.connected);
				assertStrictlyEquals(event.resource,streamingResource);
				assertTrue(event.mediaError == null);
				
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}

		
		private function doTestDirectCreateWithBadResource():void
		{
			eventDispatcher.addEventListener("testComplete",addAsync(mustReceiveEvent,TEST_TIME));
			
			var factory:NetConnectionFactory = new NetConnectionFactory();
			factory.addEventListener(NetConnectionFactoryEvent.CREATION_ERROR,onCreationFailed);
			factory.create(UNSUCCESSFUL_RESOURCE);
			function onCreationFailed(event:NetConnectionFactoryEvent):void
			{
				assertTrue(event.type == NetConnectionFactoryEvent.CREATION_ERROR);
				assertTrue(event.netConnection == null);
				assertTrue(event.mediaError.errorID == MediaErrorCodes.NETCONNECTION_FAILED);
				
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}

		}
		
		private function doTestError(expectation:NetConnectionExpectation,errorID:Number):void
		{
			eventDispatcher.addEventListener("testComplete",addAsync(mustReceiveEvent,TEST_TIME));
			
			var factory:NetConnectionFactory = createNetConnectionFactory(true, expectation);
			factory.addEventListener(NetConnectionFactoryEvent.CREATION_ERROR,onCreationFailed);
			factory.create(SUCCESSFUL_RESOURCE);
			
			function onCreationFailed(event:NetConnectionFactoryEvent):void
			{
				assertTrue(event.type == NetConnectionFactoryEvent.CREATION_ERROR);
				assertTrue(event.netConnection == null);
				assertTrue(event.mediaError.errorID == errorID);
				
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}
		
		private function createNetConnectionFactory(allowNetConnectionSharing:Boolean=true, expectation:NetConnectionExpectation=null):NetConnectionFactory
		{
			var ncf:DefaultNetConnectionFactory = new DefaultNetConnectionFactory(allowNetConnectionSharing);
			if (expectation != null)
			{
				ncf.netConnectionExpectation = expectation;
			}
			return ncf;
		}
		
		private function mustReceiveEvent(event:Event):void
		{
			// Placeholder to ensure an event is received.
		}
		
		private var eventDispatcher:EventDispatcher;
		
		private static const TEST_TIME:int = 4000;
		
		private static const SUCCESSFUL_RESOURCE:URLResource = new URLResource(TestConstants.REMOTE_STREAMING_VIDEO);
		private static const SUCCESSFUL_RESOURCE2:URLResource = new URLResource(TestConstants.STREAMING_AUDIO_FILE);
		private static const UNSUCCESSFUL_RESOURCE:URLResource = new URLResource(TestConstants.INVALID_STREAMING_VIDEO);
		private static const UNKNOWN_PROTOCOL_RESOURCE:URLResource = new URLResource("rtmpwtf://cp67126.edgefcs.net/ondemand/mediapm/strobe/content/test/SpaceAloneHD_sounas_640_500_short.flv");
	}
}