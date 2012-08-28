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
	
	import org.osmf.elements.audioClasses.*;
	import org.osmf.events.MediaErrorCodes;
	import org.osmf.events.MediaErrorEvent;
	import org.osmf.media.MediaElement;
	import org.osmf.media.MediaPlayer;
	import org.osmf.media.MediaResourceBase;
	import org.osmf.media.TestMediaElement;
	import org.osmf.media.URLResource;
	import org.osmf.net.NetLoader;
	import org.osmf.net.NetLoaderForTest;
	import org.osmf.net.NetStreamCodes;
	import org.osmf.net.NetStreamLoadTrait;
	import org.osmf.netmocker.EventInfo;
	import org.osmf.netmocker.MockNetLoader;
	import org.osmf.traits.MediaTraitType;
	import org.osmf.traits.TimeTrait;
	import org.osmf.utils.NetFactory;
	import org.osmf.utils.SimpleLoader;
	import org.osmf.utils.TestConstants;

	public class TestAudioElement extends TestMediaElement
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
			
			loader = null;
			netFactory = null;
		}

		override protected function createMediaElement():MediaElement
		{
			if (loader is MockNetLoader)
			{
				// Give our mock loader an arbitrary duration to ensure
				// we get metadata.
				MockNetLoader(loader).netStreamExpectedDuration = TestConstants.REMOTE_STREAMING_VIDEO_EXPECTED_DURATION;
			}
			
			return new AudioElement(null, loader);
		}
		
		override protected function get hasLoadTrait():Boolean
		{
			return true;
		}

		override protected function get resourceForMediaElement():MediaResourceBase
		{
			return new URLResource(TestConstants.STREAMING_AUDIO_FILE);
		}
		
		override protected function get existentTraitTypesOnInitialization():Array
		{
			// Subclasses can override to specify the trait types which are
			// expected upon initialization.
			return [MediaTraitType.LOAD];
		}

		override protected function get existentTraitTypesAfterLoad():Array
		{
			// Subclasses can override to specify the trait types which are
			// expected after a load.  Ignored if the MediaElement
			// lacks the LoadTrait.
			return [ MediaTraitType.AUDIO
				   , MediaTraitType.BUFFER
				   , MediaTraitType.LOAD
				   , MediaTraitType.PLAY
				   , MediaTraitType.SEEK
				   , MediaTraitType.TIME
				   ];
		}
		
		public function testConstructor():void
		{
			new AudioElement(null, new NetLoaderForTest(null, false));
			new AudioElement(null, new SoundLoader());
			
			// Loader must be a NetLoader or a SoundLoader.
			try
			{
				new AudioElement(null, new SimpleLoader());
				
				fail();
			}
			catch (error:ArgumentError)
			{
			}

			// Verify that the appropriate loader eventually gets set if we don't supply one.
			var audioElement:AudioElement = new AudioElement();
			assertTrue(audioElement.getTrait(MediaTraitType.LOAD) == null);
			audioElement.resource = new URLResource(TestConstants.LOCAL_SOUND_FILE);
			assertTrue(audioElement.getTrait(MediaTraitType.LOAD) is SoundLoadTrait);
			audioElement.resource = new URLResource(TestConstants.STREAMING_AUDIO_FILE);
			assertTrue(audioElement.getTrait(MediaTraitType.LOAD) is NetStreamLoadTrait);
		}
		
		public function testDefaultDuration():void
		{
			var element:AudioElement = createMediaElement() as AudioElement;
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
		
		private var netFactory:NetFactory;
		private var loader:NetLoader;
	}
}