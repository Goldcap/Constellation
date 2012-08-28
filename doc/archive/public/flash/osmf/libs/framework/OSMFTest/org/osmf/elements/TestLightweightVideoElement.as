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
package org.osmf.elements
{
	import flash.events.Event;
	import flash.events.TimerEvent;
	import flash.media.Video;
	import flash.utils.Timer;
	
	import org.osmf.events.DisplayObjectEvent;
	import org.osmf.events.LoadEvent;
	import org.osmf.events.MediaElementEvent;
	import org.osmf.events.MediaErrorCodes;
	import org.osmf.events.MediaErrorEvent;
	import org.osmf.events.TimelineMetadataEvent;
	import org.osmf.media.MediaElement;
	import org.osmf.media.MediaPlayer;
	import org.osmf.media.MediaResourceBase;
	import org.osmf.media.TestMediaElement;
	import org.osmf.media.URLResource;
	import org.osmf.metadata.CuePoint;
	import org.osmf.metadata.CuePointType;
	import org.osmf.metadata.TimelineMetadata;
	import org.osmf.net.NetLoader;
	import org.osmf.net.NetStreamCodes;
	import org.osmf.net.StreamType;
	import org.osmf.net.StreamingURLResource;
	import org.osmf.netmocker.EventInfo;
	import org.osmf.netmocker.MockNetLoader;
	import org.osmf.traits.DisplayObjectTrait;
	import org.osmf.traits.LoadState;
	import org.osmf.traits.LoadTrait;
	import org.osmf.traits.MediaTraitType;
	import org.osmf.traits.PlayTrait;
	import org.osmf.traits.SeekTrait;
	import org.osmf.traits.TimeTrait;
	import org.osmf.utils.NetFactory;
	import org.osmf.utils.TestConstants;

	public class TestLightweightVideoElement extends TestMediaElement
	{
		override public function setUp():void
		{
			netFactory = new NetFactory();
			loader = netFactory.createNetLoader();

			super.setUp();
		}
		
		override public function tearDown():void
		{
			super.tearDown();
			
			cuePoints = null;
			netFactory = null;
			loader = null;
		}
		
		////////////////////////////////////////////////////////
		//
		//	Tests
		//
		////////////////////////////////////////////////////////
		
		public function testDefaultDuration():void
		{
			var element:LightweightVideoElement = createMediaElement() as LightweightVideoElement;
			assertEquals(NaN, element.defaultDuration);
			
			element.defaultDuration = 100;
			assertEquals(100, element.defaultDuration);
			
			var timeTrait:TimeTrait = element.getTrait(MediaTraitType.TIME) as TimeTrait;
			assertNotNull(timeTrait);
			assertEquals(timeTrait.duration, 100);
			
			element.defaultDuration = NaN;
			assertEquals(NaN, element.defaultDuration);
			
			timeTrait = element.getTrait(MediaTraitType.TIME) as TimeTrait;
			assertNull(timeTrait);
		}
		
		public function testGetClient():void
		{
			var videoElement:LightweightVideoElement = createMediaElement() as LightweightVideoElement;
			videoElement.resource = resourceForMediaElement;

			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, 4000));

			var loadTrait:LoadTrait = videoElement.getTrait(MediaTraitType.LOAD) as LoadTrait;
			assertTrue(loadTrait != null);
			loadTrait.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onLoadStateChange);
			loadTrait.load();
			
			function onLoadStateChange(event:LoadEvent):void
			{
				if (event.loadState == LoadState.READY)
				{
					loadTrait.removeEventListener(LoadEvent.LOAD_STATE_CHANGE, onLoadStateChange);
					assertNotNull(videoElement.client);
					eventDispatcher.dispatchEvent(new Event("testComplete"));
				}
			}
		}
		
		public function testLiveTraits():void
		{
			var videoElement:LightweightVideoElement = createMediaElement() as LightweightVideoElement;
			videoElement.resource = new StreamingURLResource(TestConstants.REMOTE_STREAMING_VIDEO_LIVE,
															 StreamType.LIVE);

			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, 4000));

			var loadTrait:LoadTrait = videoElement.getTrait(MediaTraitType.LOAD) as LoadTrait;
			assertTrue(loadTrait != null);
			loadTrait.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onLoadStateChange);
			loadTrait.load();
			
			function onLoadStateChange(event:LoadEvent):void
			{
				if (event.loadState == LoadState.READY)
				{
					loadTrait.removeEventListener(LoadEvent.LOAD_STATE_CHANGE, onLoadStateChange);
					
					var seekTrait:SeekTrait = videoElement.getTrait(MediaTraitType.SEEK) as SeekTrait;
					assertNull(seekTrait);
					
					var playTrait:PlayTrait = videoElement.getTrait(MediaTraitType.PLAY) as PlayTrait;
					assertNotNull(playTrait);
					assertTrue(playTrait.canPause == true);
					eventDispatcher.dispatchEvent(new Event("testComplete"));
				}
			}
		}
		
		public function testDeblocking():void
		{
			var videoElement:LightweightVideoElement = createMediaElement() as LightweightVideoElement;
			videoElement.resource = resourceForMediaElement;
			assertTrue(videoElement.deblocking == 0);
			videoElement.deblocking = 4;
			assertTrue(videoElement.deblocking == 4);
			
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, 4000));
			
			var loadTrait:LoadTrait = videoElement.getTrait(MediaTraitType.LOAD) as LoadTrait;
			assertTrue(loadTrait != null);
			loadTrait.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onLoadStateChange);
			loadTrait.load();
			
			function onLoadStateChange(event:LoadEvent):void
			{
				if (event.loadState == LoadState.READY)
				{
					loadTrait.removeEventListener(LoadEvent.LOAD_STATE_CHANGE, onLoadStateChange);
					
					var displayObjectTrait:DisplayObjectTrait = videoElement.getTrait(MediaTraitType.DISPLAY_OBJECT) as DisplayObjectTrait;
					assertTrue(displayObjectTrait != null);
					var video:Video = displayObjectTrait.displayObject as Video;
					assertTrue(video != null);
					assertTrue(video.deblocking == 4);
					
					videoElement.deblocking = 1;
					assertTrue(video.deblocking == 1);
					
					eventDispatcher.dispatchEvent(new Event("testComplete"));
				}
			}
		}
		
		public function testSmoothing():void
		{
			var videoElement:LightweightVideoElement = createMediaElement() as LightweightVideoElement;
			videoElement.resource = resourceForMediaElement;
			assertTrue(videoElement.smoothing == false);
			videoElement.smoothing = true;
			assertTrue(videoElement.smoothing == true);
			
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, 4000));
			
			var loadTrait:LoadTrait = videoElement.getTrait(MediaTraitType.LOAD) as LoadTrait;
			assertTrue(loadTrait != null);
			loadTrait.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onLoadStateChange);
			loadTrait.load();
			
			function onLoadStateChange(event:LoadEvent):void
			{
				if (event.loadState == LoadState.READY)
				{
					loadTrait.removeEventListener(LoadEvent.LOAD_STATE_CHANGE, onLoadStateChange);
					
					var displayObjectTrait:DisplayObjectTrait = videoElement.getTrait(MediaTraitType.DISPLAY_OBJECT) as DisplayObjectTrait;
					assertTrue(displayObjectTrait != null);
					var video:Video = displayObjectTrait.displayObject as Video;
					assertTrue(video != null);
					assertTrue(video.smoothing == true);
					
					videoElement.smoothing = false;
					assertTrue(video.smoothing == false);
					
					eventDispatcher.dispatchEvent(new Event("testComplete"));
				}
			}
		}

		public function testCurrentFPS():void
		{
			var videoElement:LightweightVideoElement = createMediaElement() as LightweightVideoElement;
			videoElement.resource = resourceForMediaElement;
			assertTrue(videoElement.currentFPS == 0);
			
			assertTrue(new VideoElement().currentFPS == 0);
		}
		
		/**
		 * This test is for the in-stream onCuePoint callback
		 * in LightweightVideoElement.
		 */
		public function testOnCuePoint():void
		{
			var cuePointCount:int = 0;
			
			if (loader is MockNetLoader)
			{
				var testCuePoints:Array = [ {type:"event", time:1, name:"1 sec"},
										{type:"event", time:2, name:"2 sec"},
										{type:"event", time:NaN, name:"invalid cue point, ignore me"},
										{type:"event", time:3, name:"3 sec"} ];
				cuePoints = testCuePoints;
			}
			
			var videoElement:LightweightVideoElement = createMediaElement() as LightweightVideoElement;
			videoElement.resource = new URLResource(TestConstants.REMOTE_STREAMING_VIDEO);
			videoElement.addEventListener(MediaElementEvent.METADATA_ADD, onMetadataAdd);
			
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, 5000));

			var loadTrait:LoadTrait = videoElement.getTrait(MediaTraitType.LOAD) as LoadTrait;
			assertTrue(loadTrait != null);
			loadTrait.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onLoadStateChange);
			loadTrait.load();
			
			function onLoadStateChange(event:LoadEvent):void
			{
				if (event.loadState == LoadState.READY)
				{
					loadTrait.removeEventListener(LoadEvent.LOAD_STATE_CHANGE, onLoadStateChange);
					
					var playTrait:PlayTrait = videoElement.getTrait(MediaTraitType.PLAY) as PlayTrait;
					playTrait.play();
				}
			}

			function onMetadataAdd(event:MediaElementEvent):void
			{
				if (event.metadata is TimelineMetadata)
				{
					videoElement.removeEventListener(MediaElementEvent.METADATA_ADD, onMetadataAdd);
					event.metadata.addEventListener(TimelineMetadataEvent.MARKER_TIME_REACHED, onTimeReached);
				}
			}
			
			function onTimeReached(event:TimelineMetadataEvent):void
			{
				if (testCuePoints)
				{
					if (++cuePointCount >= testCuePoints.length - 1)
					{
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					} 
				}
			}
		}

		public function testOnMetadata():void
		{
			if (loader is MockNetLoader)
			{
				var testCuePoints:Array = 
					[ 	{type:"event", time:1, name:"1 sec"},
						{type:"event", time:2, name:"2 sec"},
						{type:"event", time:NaN, name:"invalid cue point, ignore me"},
						{type:"event", time:3, name:"3 sec"}
					];
				cuePoints = testCuePoints;
			}

			var mediaElement:MediaElement = createMediaElement();
			mediaElement.resource = resourceForMediaElement;

			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, 4000));

			var loadTrait:LoadTrait = mediaElement.getTrait(MediaTraitType.LOAD) as LoadTrait;
			assertTrue(loadTrait != null);
			loadTrait.addEventListener
					( LoadEvent.LOAD_STATE_CHANGE
					, onTestGetMetadata
					);
			loadTrait.load();
			
			function onTestGetMetadata(event:LoadEvent):void
			{
				if (event.loadState == LoadState.READY)
				{
					loadTrait.removeEventListener(LoadEvent.LOAD_STATE_CHANGE, onTestGetMetadata);
					
					// We should now have a view trait with the default dimensions:
					var displayObjectTrait:DisplayObjectTrait = mediaElement.getTrait(MediaTraitType.DISPLAY_OBJECT) as DisplayObjectTrait;
					assertTrue(displayObjectTrait != null);
					assertTrue(isNaN(displayObjectTrait.mediaWidth));
					assertTrue(isNaN(displayObjectTrait.mediaHeight));
					
					// See if the view matches the reported size:
					assertEquals(0, displayObjectTrait.displayObject.width);
					assertEquals(0, displayObjectTrait.displayObject.height);
					
					displayObjectTrait.addEventListener
							( DisplayObjectEvent.MEDIA_SIZE_CHANGE
							, onMediaSizeChange
							);
					
					assertTrue(mediaElement.getMetadata(CuePoint.DYNAMIC_CUEPOINTS_NAMESPACE) == null);
					
					// Playing the media should result in our receiving the
					// metadata, which should impact our dimensions.
					var playTrait:PlayTrait = mediaElement.getTrait(MediaTraitType.PLAY) as PlayTrait;
					assertTrue(playTrait != null);
					playTrait.play();
					
					function onMediaSizeChange(event:DisplayObjectEvent):void
					{
						displayObjectTrait.removeEventListener(DisplayObjectEvent.MEDIA_SIZE_CHANGE, onMediaSizeChange);
						
						assertTrue(displayObjectTrait.mediaWidth == TestConstants.REMOTE_PROGRESSIVE_VIDEO_EXPECTED_WIDTH);
						assertTrue(displayObjectTrait.mediaHeight == TestConstants.REMOTE_PROGRESSIVE_VIDEO_EXPECTED_HEIGHT);
						
						assertEquals(displayObjectTrait.mediaWidth, displayObjectTrait.displayObject.width);
						assertEquals(displayObjectTrait.mediaHeight, displayObjectTrait.displayObject.height);
						
						// We should also now have timeline metadata for the
						// cue points.
						var dynamicCuePoints:TimelineMetadata = mediaElement.getMetadata(CuePoint.DYNAMIC_CUEPOINTS_NAMESPACE) as TimelineMetadata;
						assertTrue(dynamicCuePoints != null);
						assertTrue(dynamicCuePoints.numMarkers == 3);
						var cuePoint:CuePoint = dynamicCuePoints.getMarkerAt(0) as CuePoint;
						assertTrue(cuePoint.name == "1 sec");
						assertTrue(cuePoint.time == 1);
						assertTrue(cuePoint.type == CuePointType.EVENT);
						
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}
				}
			}
		}

		public function testMediaErrorPlayFailed():void
		{
			doTestMediaError(NetStreamCodes.NETSTREAM_PLAY_FAILED, MediaErrorCodes.NETSTREAM_PLAY_FAILED);
		}

		public function testMediaErrorStreamNotFound():void
		{
			doTestMediaError(NetStreamCodes.NETSTREAM_PLAY_STREAMNOTFOUND, MediaErrorCodes.NETSTREAM_STREAM_NOT_FOUND);
		}
		
		public function testMediaErrorFileStructureInvalid():void
		{
			doTestMediaError(NetStreamCodes.NETSTREAM_PLAY_FILESTRUCTUREINVALID, MediaErrorCodes.NETSTREAM_FILE_STRUCTURE_INVALID);
		}

		public function testMediaErrorNoSupportedTrackFound():void
		{
			doTestMediaError(NetStreamCodes.NETSTREAM_PLAY_NOSUPPORTEDTRACKFOUND, MediaErrorCodes.NETSTREAM_NO_SUPPORTED_TRACK_FOUND);
		}

		////////////////////////////////////////////////////////
		//
		//	Private and Protected properties and methods
		//
		////////////////////////////////////////////////////////

		override protected function createMediaElement():MediaElement
		{
			if (loader is MockNetLoader)
			{
				// Give our mock loader an arbitrary duration and size to ensure
				// we get metadata.
				MockNetLoader(loader).netStreamExpectedDuration = TestConstants.REMOTE_PROGRESSIVE_VIDEO_EXPECTED_DURATION;
				MockNetLoader(loader).netStreamExpectedWidth = TestConstants.REMOTE_PROGRESSIVE_VIDEO_EXPECTED_WIDTH;
				MockNetLoader(loader).netStreamExpectedHeight = TestConstants.REMOTE_PROGRESSIVE_VIDEO_EXPECTED_HEIGHT;
				
				if (cuePoints != null && cuePoints.length > 0)
				{
					MockNetLoader(loader).netStreamExpectedCuePoints = cuePoints;
				}
			}

			return new LightweightVideoElement(null, loader); 
		}
		
		override protected function get hasLoadTrait():Boolean
		{
			return true;
		}
		
		override protected function get resourceForMediaElement():MediaResourceBase
		{
			// Use a valid URL so that the tests will pass if we use
			// a real NetLoader rather than a MockNetLoader.
			return new URLResource(TestConstants.REMOTE_PROGRESSIVE_VIDEO);
		}
		
		override protected function get existentTraitTypesOnInitialization():Array
		{
			return [MediaTraitType.LOAD];
		}

		override protected function get existentTraitTypesAfterLoad():Array
		{
			return [ MediaTraitType.AUDIO
				   , MediaTraitType.BUFFER
				   , MediaTraitType.LOAD
				   , MediaTraitType.PLAY
				   , MediaTraitType.TIME
				   , MediaTraitType.DISPLAY_OBJECT
				   ];
		}	

		private function doTestMediaError(netStreamCode:String, errorID:int, level:String="error"):void
		{
			var mockLoader:MockNetLoader = loader as MockNetLoader;
			if (mockLoader != null)
			{
				mockLoader.netStreamExpectedEvents = [ new EventInfo(netStreamCode, level, 0) ];
					
				var mediaElement:MediaElement = createMediaElement();
				mediaElement.resource = resourceForMediaElement;
				
				mediaElement.addEventListener(MediaErrorEvent.MEDIA_ERROR, onTestMediaError);
				
				eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, 4000));
				
				var mediaPlayer:MediaPlayer = new MediaPlayer(mediaElement);
				
				function onTestMediaError(event:MediaErrorEvent):void
				{
					assertTrue(event.error.errorID == errorID);
					
					eventDispatcher.dispatchEvent(new Event("testComplete"));
				}
			}
		}

		protected var loader:NetLoader;
		protected var cuePoints:Array;
		
		private var netFactory:NetFactory;
	}
}
