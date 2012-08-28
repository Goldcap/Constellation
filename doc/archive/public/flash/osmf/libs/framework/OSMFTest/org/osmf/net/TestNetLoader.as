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
	import flash.events.Event;
	import flash.events.EventDispatcher;
	import flash.events.NetStatusEvent;
	import flash.events.TimerEvent;
	import flash.net.NetConnection;
	import flash.net.NetStream;
	import flash.utils.Timer;
	
	import org.osmf.events.LoadEvent;
	import org.osmf.events.MediaError;
	import org.osmf.events.MediaErrorCodes;
	import org.osmf.events.MediaErrorEvent;
	import org.osmf.media.MediaResourceBase;
	import org.osmf.media.MediaType;
	import org.osmf.media.URLResource;
	import org.osmf.netmocker.DefaultNetConnectionFactory;
	import org.osmf.netmocker.IMockNetLoader;
	import org.osmf.netmocker.MockNetLoader;
	import org.osmf.netmocker.NetConnectionExpectation;
	import org.osmf.traits.LoadState;
	import org.osmf.traits.LoadTrait;
	import org.osmf.traits.LoaderBase;
	import org.osmf.traits.TestLoaderBase;
	import org.osmf.utils.NetFactory;
	import org.osmf.utils.NullResource;
	import org.osmf.utils.OSMFStrings;
	import org.osmf.utils.TestConstants;

	public class TestNetLoader extends TestLoaderBase
	{
		override public function setUp():void
		{
			netFactory = createNetFactory();
			eventDispatcher = new EventDispatcher();

			super.setUp();
		}
		
		override public function tearDown():void
		{
			super.tearDown();
			
			netFactory = null;
			eventDispatcher = null;
		}

		override public function testCanHandleResource():void
		{
			super.testCanHandleResource();
			
			// Verify some valid remote resources.
			assertTrue(loader.canHandleResource(new URLResource("http://example.com")));	
	    	assertTrue(loader.canHandleResource(new URLResource("http://example.com/test")));	    	
		   	assertTrue(loader.canHandleResource(new URLResource("https://example.com/test")));
		   	assertTrue(loader.canHandleResource(new URLResource("http://example.com:8080")));
		   	assertTrue(loader.canHandleResource(new URLResource("file://example.com/test")));
	    	assertTrue(loader.canHandleResource(new URLResource("rtmp://example.com/test")));	    	
	    	assertTrue(loader.canHandleResource(new URLResource("rtmps://example.com/test")));	    	
	    	assertTrue(loader.canHandleResource(new URLResource("rtmpe://example.com/test")));	    	    	
	    	assertTrue(loader.canHandleResource(new URLResource("rtmpte://example.com/test")));
	    	assertTrue(loader.canHandleResource(new URLResource("rtmp://example.com:8080/appname/test")));
	    	assertTrue(loader.canHandleResource(new URLResource("rtmp://example.com/appname/filename.flv")));
	    	assertTrue(loader.canHandleResource(new URLResource("rtmpte://example.com:8080/appname/mp4:filename.mp4")));
			assertTrue(loader.canHandleResource(new URLResource("example.com/test.flv")));
			assertTrue(loader.canHandleResource(new URLResource("http://example.com/test.flv?param=value")));
	    	
			// And some invalid ones.
	    	assertFalse(loader.canHandleResource(new URLResource("javascript://test.com/test.flv")));
	    	assertFalse(loader.canHandleResource(new URLResource("rtmpet://example.com/test")));	    	
			assertFalse(loader.canHandleResource(new URLResource("httpt://example.com/video.foo")));
			assertFalse(loader.canHandleResource(new URLResource("example.com/test.mp3")));
			assertFalse(loader.canHandleResource(new URLResource("foo")));
			assertFalse(loader.canHandleResource(new URLResource("")));
			assertFalse(loader.canHandleResource(new URLResource(null)));
			assertFalse(loader.canHandleResource(new NullResource()));
			assertFalse(loader.canHandleResource(null));
			
			// Verify some valid local resources.
			assertTrue(loader.canHandleResource(new URLResource("file:///video.flv")));
			assertTrue(loader.canHandleResource(new URLResource("file:///video.f4v")));
			assertTrue(loader.canHandleResource(new URLResource("file:///video.mov")));
			assertTrue(loader.canHandleResource(new URLResource("file:///video.mp4")));
			assertTrue(loader.canHandleResource(new URLResource("file:///video.mp4v")));
			assertTrue(loader.canHandleResource(new URLResource("file:///video.m4v")));
			assertTrue(loader.canHandleResource(new URLResource("file:///video.3gp")));
			assertTrue(loader.canHandleResource(new URLResource("file:///video.3gpp2")));
			assertTrue(loader.canHandleResource(new URLResource("file:///video.3g2")));
			
			// And some invalid ones.
			assertFalse(loader.canHandleResource(new URLResource("video.avi")));
			assertFalse(loader.canHandleResource(new URLResource("video.mpeg")));
			assertFalse(loader.canHandleResource(new URLResource("video.mpg")));
			assertFalse(loader.canHandleResource(new URLResource("video.wmv")));
			assertFalse(loader.canHandleResource(new URLResource("audio.mp3")));
			assertFalse(loader.canHandleResource(new URLResource("video.flv1")));
			assertFalse(loader.canHandleResource(new URLResource("video.f4v1")));
			assertFalse(loader.canHandleResource(new URLResource("video.mov1")));
			assertFalse(loader.canHandleResource(new URLResource("video.mp41")));
			assertFalse(loader.canHandleResource(new URLResource("video.mp4v1")));
			assertFalse(loader.canHandleResource(new URLResource("video.m4v1")));
			assertFalse(loader.canHandleResource(new URLResource("video.3gp1")));
			assertFalse(loader.canHandleResource(new URLResource("video.3gpp21")));
			assertFalse(loader.canHandleResource(new URLResource("video.3g21")));
			assertFalse(loader.canHandleResource(new URLResource("audio.mp31")));
					
			// Verify some valid resources based on metadata information
			var resource:URLResource = new URLResource("http://example.com/test");
			resource.mediaType = MediaType.VIDEO;
			assertTrue(loader.canHandleResource(resource));
			
			resource = new URLResource("http://example.com/test");
			resource.mimeType = "video/x-f4v";
			assertTrue(loader.canHandleResource(resource));
			
			resource = new URLResource("http://example.com/test");
			resource.mimeType = "video/x-flv";
			assertTrue(loader.canHandleResource(resource));

			resource = new URLResource("http://example.com/test");
			resource.mimeType = "video/mp4";
			assertTrue(loader.canHandleResource(resource));

			resource = new URLResource("http://example.com/test");
			resource.mimeType = "video/mp4v-es";
			assertTrue(loader.canHandleResource(resource));

			resource = new URLResource("http://example.com/test");
			resource.mimeType = "video/x-m4v";
			assertTrue(loader.canHandleResource(resource));

			resource = new URLResource("http://example.com/test");
			resource.mimeType = "video/3gpp";
			assertTrue(loader.canHandleResource(resource));

			resource = new URLResource("http://example.com/test");
			resource.mimeType = "video/3gpp2";
			assertTrue(loader.canHandleResource(resource));

			resource = new URLResource("http://example.com/test");
			resource.mimeType = "video/quicktime";
			assertTrue(loader.canHandleResource(resource));

			resource = new URLResource("http://example.com/test");
			resource.mediaType = MediaType.VIDEO;
			resource.mimeType = "video/x-flv";
			assertTrue(loader.canHandleResource(resource));

			// Add some invalid cases based on metadata information
			//
			
			resource = new URLResource("http://example.com/test");
			resource.mediaType = MediaType.AUDIO;
			assertFalse(loader.canHandleResource(resource));

			resource = new URLResource("http://example.com/test");
			resource.mimeType = "audio/mpeg";
			assertFalse(loader.canHandleResource(resource));
			
			resource = new URLResource("http://example.com/test");
			resource.mediaType = MediaType.AUDIO;
			resource.mimeType = "audio/mpeg";
			assertFalse(loader.canHandleResource(resource));

			resource = new URLResource("http://example.com/test");
			resource.mediaType = MediaType.SWF;
			assertFalse(loader.canHandleResource(resource));

			resource = new URLResource("http://example.com/test");
			resource.mimeType = "Invalid MIME Type";
			assertFalse(loader.canHandleResource(resource));

			resource = new URLResource("http://example.com/test");
			resource.mediaType = MediaType.IMAGE;
			resource.mimeType = "Invalid MIME Type";
			assertFalse(loader.canHandleResource(resource));

			resource = new URLResource("http://example.com/test");
			resource.mediaType = MediaType.VIDEO;
			resource.mimeType = "Invalid MIME Type";
			assertFalse(loader.canHandleResource(resource));

			resource = new URLResource("http://example.com/test");
			resource.mediaType = MediaType.AUDIO;
			resource.mimeType = "Invalid MIME Type";
			assertFalse(loader.canHandleResource(resource));

			resource = new URLResource("http://example.com/test");
			resource.mediaType = MediaType.IMAGE;
			resource.mimeType = "video/x-flv";
			assertFalse(loader.canHandleResource(resource));

			resource = new URLResource("http://example.com/test");
			resource.mediaType = MediaType.IMAGE;
			resource.mimeType = "audio/mpeg";
			assertFalse(loader.canHandleResource(resource));

			resource = new URLResource("http://example.com/test");
			resource.mediaType = MediaType.VIDEO;
			resource.mimeType = "audio/mpeg";
			assertFalse(loader.canHandleResource(resource));

			resource = new URLResource("http://example.com/test");
			resource.mediaType = MediaType.AUDIO;
			resource.mimeType = "video/x-flv";
			assertFalse(loader.canHandleResource(resource));
		}

		public function testMultipleConcurrentLoads():void
		{
			doTestMultipleConcurrentLoads();
		}
		
		public function testAllowConnectionSharing():void
		{
			doTestAllowConnectionSharing();
		}
		
		public function testDisallowConnectionSharing():void
		{
			doTestDisallowConnectionSharing();
		}
		
		public function testUnloadWithSharedConnections():void
		{
			doTestUnloadWithSharedConnections();
		}

		public function testUnloadWithUnsharedConnections():void
		{
			doTestUnloadWithUnsharedConnections();
		}
		
		public function testNetConnectionFactoryArgument():void
		{
			doTestNetConnectionFactoryArgument();
		}
		
		public function testLoadOverHTTP():void
		{
			doTestLoadOverHTTP();
		}
		
		public function testLoadRTMP():void
		{
			doTestLoadProtocol(new URLResource("rtmp://example.com"));
		}

		public function testLoadRTMPS():void
		{
			doTestLoadProtocol(new URLResource("rtmps://example.com"));
		}

		public function testLoadRTMPT():void
		{
			doTestLoadProtocol(new URLResource("rtmpt://example.com"));
		}

		public function testLoadRTMPE():void
		{
			doTestLoadProtocol(new URLResource("rtmpe://example.com"));
		}

		public function testLoadRTMPTE():void
		{
			doTestLoadProtocol(new URLResource("rtmpte://example.com"));
		}

		public function testLoadHTTP():void
		{
			doTestLoadProtocol(new URLResource("http://example.com"));
		}

		public function testLoadHTTPS():void
		{
			doTestLoadProtocol(new URLResource("https://example.com"));
		}

		public function testLoadFile():void
		{
			doTestLoadProtocol(new URLResource("file://example.com"));
		}
		
		public function testLoadInvalidProtocol():void
		{
			var netLoader:NetLoader = netFactory.createNetLoader();
			var mockLoader:IMockNetLoader = netLoader as IMockNetLoader;
			if (mockLoader != null)
			{
				eventDispatcher.addEventListener("testComplete",addAsync(mustReceiveEvent,TEST_TIME));
				
				mockLoader.netConnectionExpectation = NetConnectionExpectation.VALID_CONNECTION;
				
				var loadErrorReceived:Boolean = false;
				
				var resource:URLResource = new URLResource("foo://example.com")
				resource.mediaType= MediaType.VIDEO;
				
				var loadTrait:LoadTrait = new NetStreamLoadTrait(netLoader, resource);
				loadTrait.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onProtocolLoad);
				loadTrait.addEventListener(MediaErrorEvent.MEDIA_ERROR, onProtocolError);
				loadTrait.load();
				
				function onProtocolLoad(event:LoadEvent):void
				{
					if (event.loadState == LoadState.LOAD_ERROR)
					{
						loadErrorReceived = true;
					}
				}
				
				function onProtocolError(event:MediaErrorEvent):void
				{
					assertTrue(loadErrorReceived == true);
					assertTrue(event.error.errorID == MediaErrorCodes.URL_SCHEME_INVALID);

					eventDispatcher.dispatchEvent(new Event("testComplete"));
				}
			}
		}
		
		CONFIG::FLASH_10_1	
		{
			public function testReconnectProperties():void
			{
				var netLoader:NetLoader = new NetLoader();
				assertTrue(netLoader.reconnectStreams);
				netLoader.reconnectTimeout = 900;
				assertTrue(netLoader.reconnectTimeout == 900);
				
				try 
				{
					netLoader.reconnectTimeout = -5;
					fail("NetLoader.reconnectStreamsTimeout should have thrown an ArgumentError");				
				}
				catch(e:ArgumentError)
				{
					assertEquals(e.message, OSMFStrings.getString(OSMFStrings.INVALID_PARAM));
				}
			}
			
			public function testStreamReconnect():void
			{
				doTestStreamReconnect(new URLResource("rtmp://host/appname/mp4:example.mp4"), true);
			}

			public function testStreamReconnectWithInvalidFMSVersion():void
			{
				doTestStreamReconnect(new URLResource("rtmp://host/appname/mp4:example.mp4"), false);
			}

			public function testStreamReconnectWithInstanceName():void
			{
				var resource:StreamingURLResource = new StreamingURLResource("rtmp://host/appname/instname/mp4:example.mp4", null, NaN, NaN, null, true); 
				doTestStreamReconnect(resource, true);
			}

			public function testStreamReconnectWhenPaused():void
			{
				doTestStreamReconnect(new URLResource("rtmp://host/appname/mp4:example.mp4"), true, true);
			}

			private function doTestStreamReconnect(resource:MediaResourceBase, fmsSupportsStreamReconnect:Boolean, pauseStream:Boolean=false):void
			{
				var netLoader:NetLoader = netFactory.createNetLoader(null, true);
				var mockLoader:IMockNetLoader = netLoader as IMockNetLoader;
				
				if (mockLoader != null)
				{
					eventDispatcher.addEventListener("testComplete",addAsync(mustReceiveEvent, TEST_TIME));
					
					mockLoader.netConnectionExpectation = NetConnectionExpectation.VALID_CONNECTION;
					
					if (fmsSupportsStreamReconnect)
					{
						mockLoader.netConnectionExpectedFMSVersion = "3,5,3";
					}
					else
					{
						mockLoader.netConnectionExpectedFMSVersion = "3,5,0";
					}
					
					resource.mediaType = MediaType.VIDEO;
					
					var loadTrait:LoadTrait = new NetStreamLoadTrait(netLoader, resource);
					loadTrait.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onProtocolLoad);
					loadTrait.load();

					var nc:NetConnection;
										
					function onProtocolLoad(event:LoadEvent):void
					{
						if (event.loadState == LoadState.READY)
						{
							if (pauseStream)
							{
								var ns:NetStream = (loadTrait as NetStreamLoadTrait).netStream;
								ns.play();
								ns.pause();
							}
							nc = (loadTrait as NetStreamLoadTrait).connection;
							setupNetConnectionListeners();
							// Close the NetConnection without using loadTrait.unload(). This
							// will cause the reconnect logic to kick in since the LoadState 
							// is still READY
							nc.close();
						}
						else if (event.loadState == LoadState.UNINITIALIZED)
						{
							setupNetConnectionListeners(false);
							eventDispatcher.dispatchEvent(new Event("testComplete"));
						}
					}
					
					function setupNetConnectionListeners(add:Boolean=true):void
					{
						if (add)
						{
							nc.addEventListener(NetStatusEvent.NET_STATUS, onNetStatus);
						}
						else
						{
							nc.removeEventListener(NetStatusEvent.NET_STATUS, onNetStatus);
						}
					}
					
					function onNetStatus(event:NetStatusEvent):void
					{
						var timer:Timer = new Timer(2000, 1);
						timer.addEventListener(TimerEvent.TIMER_COMPLETE, onTimerComplete, false, 0, true);
						
						switch (event.info.code)
						{
							case NetConnectionCodes.CONNECT_CLOSED:
								// We got here from the NetConnection.close() call above
								// to simulate connection loss. Now we'll wait a second
								// and see if we get a new NetConnection
								setupNetConnectionListeners(false);
								timer.start();
								break;
						}
					}
					
					function onTimerComplete(event:TimerEvent):void
					{
						var tempNc:NetConnection = (loadTrait as NetStreamLoadTrait).connection;
						
						if (fmsSupportsStreamReconnect)
						{
							// We're connected
							assertTrue(tempNc.connected);
							// And we got a different NetConnection object
							assertFalse(tempNc === nc);
						}
						else
						{
							// We're not connected
							assertFalse(tempNc.connected);
							// And we got the same NetConnection object
							assertTrue(tempNc === nc);
						}
						
						// We're done
						loadTrait.unload();
					}
				}
			}
		}
		
		private function doTestLoadProtocol(resource:MediaResourceBase):void
		{
			var netLoader:NetLoader = netFactory.createNetLoader();
			var mockLoader:IMockNetLoader = netLoader as IMockNetLoader;
			if (mockLoader != null)
			{
				eventDispatcher.addEventListener("testComplete",addAsync(mustReceiveEvent,TEST_TIME));
				
				mockLoader.netConnectionExpectation = NetConnectionExpectation.VALID_CONNECTION;
				
				var loadTrait:LoadTrait = new NetStreamLoadTrait(netLoader, resource);
				loadTrait.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onProtocolLoad);
				loadTrait.load();
				
				function onProtocolLoad(event:LoadEvent):void
				{
					if (event.loadState == LoadState.READY)
					{
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}
				}
			}
		}

		private function doTestMultipleConcurrentLoads():void
		{
			eventDispatcher.addEventListener("testComplete",addAsync(mustReceiveEvent,TEST_TIME));
			
			var netLoader:NetLoader = netFactory.createNetLoader();
			var mockLoader:IMockNetLoader = netLoader as IMockNetLoader;
			if (mockLoader != null)
			{
				mockLoader.netConnectionExpectation = NetConnectionExpectation.VALID_CONNECTION;
			}
			
			var loadTrait1:LoadTrait = new NetStreamLoadTrait(netLoader, successfulResource);
			loadTrait1.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onMultiLoad);
			loadTrait1.load();
			var loadTrait2:LoadTrait = new NetStreamLoadTrait(netLoader, successfulResource);
			loadTrait2.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onMultiLoad);
			loadTrait2.load();
			var loadTrait3:LoadTrait = new NetStreamLoadTrait(netLoader, successfulResource);
			loadTrait3.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onMultiLoad);
			loadTrait3.load();
			var loadTrait4:LoadTrait = new NetStreamLoadTrait(netLoader, successfulResource);
			loadTrait4.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onMultiLoad);
			loadTrait4.load();
			var responses:int = 0;
			function onMultiLoad(event:LoadEvent):void
			{
				assertTrue(event.type == LoadEvent.LOAD_STATE_CHANGE);
					
				if (event.loadState == LoadState.READY)
				{
					responses++;
					if (responses == 4)
					{
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}
				}
			}
			
		}
		
		private function doTestAllowConnectionSharing():void
		{
			eventDispatcher.addEventListener("testComplete",addAsync(mustReceiveEvent,TEST_TIME));
			
			var netLoader:NetLoader = netFactory.createNetLoader();
			var mockLoader:IMockNetLoader = netLoader as IMockNetLoader;
			if (mockLoader != null)
			{
				mockLoader.netConnectionExpectation = NetConnectionExpectation.VALID_CONNECTION;
			}

			var loadTrait1:LoadTrait = new NetStreamLoadTrait(netLoader, successfulResource);
			loadTrait1.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onMultiLoad);
			loadTrait1.load();
			var loadTrait2:LoadTrait = new NetStreamLoadTrait(netLoader, successfulResource);
			loadTrait2.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onMultiLoad);
			loadTrait2.load();

			var responses:int = 0;
			function onMultiLoad(event:LoadEvent):void
			{
				assertTrue(event.type == LoadEvent.LOAD_STATE_CHANGE);
					
				if (event.loadState == LoadState.READY)
				{
					responses++;
					if (responses == 2)
					{
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}
				}
			}
			
		}
		
		private function doTestDisallowConnectionSharing():void
		{
			eventDispatcher.addEventListener("testComplete",addAsync(mustReceiveEvent,TEST_TIME));
			
			var netLoader:NetLoader = netFactory.createNetLoader(new DefaultNetConnectionFactory(false));
			var mockLoader:IMockNetLoader = netLoader as IMockNetLoader;
			if (mockLoader != null)
			{
				mockLoader.netConnectionExpectation = NetConnectionExpectation.VALID_CONNECTION;
			}

			var loadTrait1:LoadTrait = new NetStreamLoadTrait(netLoader, successfulResource);
			loadTrait1.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onMultiLoad);
			loadTrait1.load();
			var loadTrait2:LoadTrait = new NetStreamLoadTrait(netLoader, successfulResource);
			loadTrait2.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onMultiLoad);
			loadTrait2.load();

			var responses:int = 0;
			function onMultiLoad(event:LoadEvent):void
			{
				assertTrue(event.type == LoadEvent.LOAD_STATE_CHANGE);
					
				if (event.loadState == LoadState.READY)
				{
					responses++;
					if (responses == 2)
					{
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}
				}
			}
			
		}
		
		private function doTestUnloadWithSharedConnections():void
		{
			eventDispatcher.addEventListener("testComplete",addAsync(mustReceiveEvent,TEST_TIME));
			
			var netLoader:NetLoader = netFactory.createNetLoader();
			var mockLoader:IMockNetLoader = netLoader as IMockNetLoader;
			if (mockLoader != null)
			{
				mockLoader.netConnectionExpectation = NetConnectionExpectation.VALID_CONNECTION;
			}

			var loadTrait1:LoadTrait = new NetStreamLoadTrait(netLoader, successfulResource);
			loadTrait1.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onMultiLoad);
			loadTrait1.load();
			var loadTrait2:LoadTrait = new NetStreamLoadTrait(netLoader, successfulResource);
			loadTrait2.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onMultiLoad);
			loadTrait2.load();

			var responses:int = 0;
			function onMultiLoad(event:LoadEvent):void
			{
				assertTrue(event.type == LoadEvent.LOAD_STATE_CHANGE);
					
				if (event.loadState == LoadState.READY)
				{
					responses++;
					if (responses == 2)
					{
						loadTrait1.unload();
					}
				}
				if (event.loadState == LoadState.UNINITIALIZED)
				{
					responses++;
					if (responses == 3)
					{
						assertTrue(NetStreamLoadTrait(loadTrait1).connection.connected);
						assertTrue(NetStreamLoadTrait(loadTrait2).connection.connected);
						
						loadTrait2.unload();
					}
					else if (responses == 4)
					{
						// For some reason, the connected property doesn't show up as
						// false when testing against Akamai, even though we're closing
						// the connection.
						assertTrue(NetStreamLoadTrait(loadTrait2).connection.connected == false || mockLoader == null);
						
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}
				}
			}
		}
		
		private function doTestUnloadWithUnsharedConnections():void
		{
			eventDispatcher.addEventListener("testComplete",addAsync(mustReceiveEvent,TEST_TIME));
			
			var netLoader:NetLoader = netFactory.createNetLoader(new DefaultNetConnectionFactory(false));
			var mockLoader:IMockNetLoader = netLoader as IMockNetLoader;
			if (mockLoader != null)
			{
				mockLoader.netConnectionExpectation = NetConnectionExpectation.VALID_CONNECTION;
			}

			var loadTrait1:LoadTrait = new NetStreamLoadTrait(netLoader, successfulResource);
			loadTrait1.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onMultiLoad);
			loadTrait1.load();

			var loadTrait2:LoadTrait = new NetStreamLoadTrait(netLoader, successfulResource2);
			loadTrait2.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onMultiLoad);

			var responses:int = 0;
			function onMultiLoad(event:LoadEvent):void
			{
				assertTrue(event.type == LoadEvent.LOAD_STATE_CHANGE);
					
				if (event.loadState == LoadState.READY)
				{
					responses++;
					if (responses == 1)
					{
						loadTrait2.load();
					}
					else if (responses == 2)
					{
						loadTrait1.unload();
					}
				}
				if (event.loadState == LoadState.UNINITIALIZED)
				{
					responses++;
					if (responses == 3)
					{
						assertFalse(NetStreamLoadTrait(loadTrait1).connection.connected);
						assertTrue(NetStreamLoadTrait(loadTrait2).connection.connected);
						
						loadTrait2.unload();
					}
					else if (responses == 4)
					{
						// For some reason, the connected property doesn't show up as
						// false when testing against Akamai, even though we're closing
						// the connection.
						assertTrue(NetStreamLoadTrait(loadTrait2).connection.connected == false || mockLoader == null);
						
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}
				}
			}
		}
		
		private function doTestNetConnectionFactoryArgument():void
		{
			eventDispatcher.addEventListener("testComplete",addAsync(mustReceiveEvent,TEST_TIME));
			
			var netLoader:NetLoader = netFactory.createNetLoader(new DefaultNetConnectionFactory(true));
			var mockLoader:IMockNetLoader = netLoader as IMockNetLoader;
			if (mockLoader != null)
			{
				mockLoader.netConnectionExpectation = NetConnectionExpectation.VALID_CONNECTION;
			}
			
			var loadTrait1:LoadTrait = new NetStreamLoadTrait(netLoader, successfulResource);
			loadTrait1.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onMultiLoad);
			loadTrait1.load();
			var loadTrait2:LoadTrait = new NetStreamLoadTrait(netLoader, successfulResource);
			loadTrait2.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onMultiLoad);
			loadTrait2.load();
			
			var responses:int = 0;

			function onMultiLoad(event:LoadEvent):void
			{
				assertTrue(event.type == LoadEvent.LOAD_STATE_CHANGE);
					
				if (event.loadState == LoadState.READY)
				{
					responses++;
					if (responses == 2)
					{
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}
				}
			}
		}
		
		private function doTestLoadOverHTTP():void
		{
			eventDispatcher.addEventListener("testComplete",addAsync(mustReceiveEvent,TEST_TIME));
			
			var netLoader:NetLoader = netFactory.createNetLoader(new DefaultNetConnectionFactory());
			var mockLoader:IMockNetLoader = netLoader as IMockNetLoader;
			if (mockLoader != null)
			{
				mockLoader.netConnectionExpectation = NetConnectionExpectation.VALID_CONNECTION;
			}
			
			var loadTrait1:LoadTrait = new NetStreamLoadTrait(netLoader, SUCCESSFUL_HTTP_RESOURCE);
			loadTrait1.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onMultiLoad);
			loadTrait1.load();
			var loadTrait2:LoadTrait = new NetStreamLoadTrait(netLoader, SUCCESSFUL_HTTP_RESOURCE);
			loadTrait2.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onMultiLoad);
			loadTrait2.load();
			
			var responses:int = 0;

			function onMultiLoad(event:LoadEvent):void
			{
				assertTrue(event.type == LoadEvent.LOAD_STATE_CHANGE);
					
				if (event.loadState == LoadState.READY)
				{
					responses++;
					if (responses == 2)
					{
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}
				}
			}
		}
		
		//---------------------------------------------------------------------
				
		override protected function createInterfaceObject(... args):Object
		{
			return netFactory.createNetLoader();
		}
		
		override protected function createLoadTrait(loader:LoaderBase, resource:MediaResourceBase):LoadTrait
		{
			var mockLoader:MockNetLoader = loader as MockNetLoader;
			if (mockLoader)
			{
				if (resource == successfulResource)
				{
					mockLoader.netConnectionExpectation = NetConnectionExpectation.VALID_CONNECTION;
				}
				else if (resource == failedResource)
				{
					mockLoader.netConnectionExpectation = NetConnectionExpectation.REJECTED_CONNECTION;
				}
				else if (resource == unhandledResource)
				{
					mockLoader.netConnectionExpectation = NetConnectionExpectation.REJECTED_CONNECTION;
				}
			}
			return new NetStreamLoadTrait(loader, resource);
		}
		
		override protected function get successfulResource():MediaResourceBase
		{
			return SUCCESSFUL_RESOURCE;
		}

		override protected function get failedResource():MediaResourceBase
		{
			return UNSUCCESSFUL_RESOURCE;
		}

		override protected function get unhandledResource():MediaResourceBase
		{
			return UNHANDLED_RESOURCE;
		}
		
		override protected function verifyMediaErrorOnLoadFailure(error:MediaError):void
		{
			assertTrue(error.errorID == MediaErrorCodes.URL_SCHEME_INVALID ||
					   error.errorID == MediaErrorCodes.NETCONNECTION_REJECTED ||
					   error.errorID == MediaErrorCodes.NETCONNECTION_APPLICATION_INVALID ||
					   error.errorID == MediaErrorCodes.NETCONNECTION_FAILED ||
					   error.errorID == MediaErrorCodes.NETCONNECTION_TIMEOUT ||
					   error.errorID == MediaErrorCodes.SECURITY_ERROR ||
					   error.errorID == MediaErrorCodes.ASYNC_ERROR ||
					   error.errorID == MediaErrorCodes.IO_ERROR ||
					   error.errorID == MediaErrorCodes.ARGUMENT_ERROR);
		}
		
		protected function createNetFactory():NetFactory
		{
			return new NetFactory();
		}
		
		private function get successfulResource2():MediaResourceBase
		{
			return SUCCESSFUL_RESOURCE2;
		}

		private function mustReceiveEvent(event:Event):void
		{
			// Placeholder to ensure an event is received.
		}
		
		protected var netFactory:NetFactory;
		
		private var eventDispatcher:EventDispatcher;
		
		private static const SUCCESSFUL_RESOURCE:URLResource = new URLResource(TestConstants.REMOTE_STREAMING_VIDEO);
		private static const SUCCESSFUL_RESOURCE2:URLResource = new URLResource(TestConstants.STREAMING_AUDIO_FILE);
		private static const UNSUCCESSFUL_RESOURCE:URLResource = new URLResource(TestConstants.INVALID_STREAMING_VIDEO);
		private static const UNHANDLED_RESOURCE:NullResource = new NullResource();
		private static const SUCCESSFUL_HTTP_RESOURCE:URLResource = new URLResource(TestConstants.REMOTE_PROGRESSIVE_VIDEO);
		private static const TEST_TIME:Number = 4000;
		private static const PORT_443:String = "443";
		private static const RTMPTE:String = "rtmpte";
		private static const DEFAULT_PORT_PROTOCOL_RESULT:String = TestConstants.DEFAULT_PORT_PROTOCOL_RESULT;
		private static const RTMPTE_443_RESULT:String = TestConstants.RESULT_FOR_RTMPTE_443;
		private static const RESOURCE_WITH_PORT_PROTOCOL:URLResource = new URLResource(TestConstants.REMOTE_STREAMING_VIDEO_WITH_PORT_PROTOCOL);
		
	}
}