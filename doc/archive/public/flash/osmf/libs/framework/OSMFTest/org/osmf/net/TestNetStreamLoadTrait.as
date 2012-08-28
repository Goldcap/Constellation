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
	import flash.events.TimerEvent;
	import flash.net.NetStream;
	import flash.utils.Timer;
	
	import org.osmf.events.LoadEvent;
	import org.osmf.media.MediaResourceBase;
	import org.osmf.media.URLResource;
	import org.osmf.netmocker.MockNetLoader;
	import org.osmf.netmocker.NetConnectionExpectation;
	import org.osmf.traits.LoadState;
	import org.osmf.traits.LoadTrait;
	import org.osmf.traits.LoaderBase;
	import org.osmf.traits.TestLoadTrait;
	import org.osmf.utils.NetFactory;
	import org.osmf.utils.NullResource;
	import org.osmf.utils.TestConstants;

	public class TestNetStreamLoadTrait extends TestLoadTrait
	{
		override public function setUp():void
		{
			netFactory = new NetFactory();
			eventDispatcher = new EventDispatcher();

			super.setUp();
		}
		
		override public function tearDown():void
		{
			super.tearDown();
			
			netFactory = null;
			eventDispatcher = null;
		}
		
		override protected function createInterfaceObject(... args):Object
		{
			var loader:LoaderBase = args.length > 0 ? args[0] : null;
			var resource:MediaResourceBase = args.length > 1 ? args[1] : null;
			
			var mockLoader:MockNetLoader = loader as MockNetLoader;
			if (mockLoader)
			{
				mockLoader.netStreamExpectedDuration = TestConstants.REMOTE_PROGRESSIVE_VIDEO_EXPECTED_DURATION;
				mockLoader.netStreamExpectedBytesTotal = TestConstants.REMOTE_PROGRESSIVE_VIDEO_EXPECTED_BYTES;

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
		
		override protected function createLoader():LoaderBase
		{
			return netFactory.createNetLoader();
		}

		override protected function get successfulResource():MediaResourceBase
		{
			return SUCCESSFUL_RESOURCE;
		}

		override protected function get failedResource():MediaResourceBase
		{
			return FAILED_RESOURCE;
		}

		override protected function get unhandledResource():MediaResourceBase
		{
			return UNHANDLED_RESOURCE;
		}

		override protected function get expectedBytesTotal():Number
		{
			return TestConstants.REMOTE_PROGRESSIVE_VIDEO_EXPECTED_BYTES;
		}
		
		public function testLoadThenPlayTriggersDownload():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, 5000));
			
			var loadTrait:LoadTrait = createLoadTrait(successfulResource);
			
			loadTrait.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onTestLoadThenPlayTriggersDownload);
			loadTrait.load();
		
			function onTestLoadThenPlayTriggersDownload(event:LoadEvent):void
			{
				if (event.loadState == LoadState.READY)
				{
					assertTrue(loadTrait.bytesLoaded == 0 || isNaN(loadTrait.bytesLoaded));
					
					// No bytes will download until the stream is first played.
					var netStream:NetStream = NetStreamLoadTrait(loadTrait).netStream;
					netStream.play(NetStreamUtils.getStreamNameFromURL(SUCCESSFUL_RESOURCE.url));
					
					var timer:Timer = new Timer(500);
					timer.addEventListener(TimerEvent.TIMER, onTimer);
					timer.start();
					
					function onTimer(event:TimerEvent):void
					{
						if (loadTrait.bytesLoaded == loadTrait.bytesTotal)
						{
							timer.stop();
							
							if (eventDispatcher != null)
							{
								eventDispatcher.dispatchEvent(new Event("testComplete"));
							}
						}
					}
				}
			}
		}
		
		private function mustReceiveEvent(event:Event):void
		{
			// Placeholder
		}
		
		private static const SUCCESSFUL_RESOURCE:URLResource = new URLResource(TestConstants.REMOTE_PROGRESSIVE_VIDEO);
		private static const FAILED_RESOURCE:URLResource = new URLResource(TestConstants.INVALID_STREAMING_VIDEO);
		private static const UNHANDLED_RESOURCE:MediaResourceBase = new NullResource();
		
		private var netFactory:NetFactory;
		private var eventDispatcher:EventDispatcher;
	}
}