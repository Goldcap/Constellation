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
package org.osmf.media
{
	import __AS3__.vec.Vector;
	
	import flash.display.Sprite;
	import flash.errors.IllegalOperationError;
	import flash.events.Event;
	import flash.events.EventDispatcher;
	import flash.events.TimerEvent;
	import flash.utils.Timer;
	
	import flexunit.framework.TestCase;
	
	import org.osmf.elements.ParallelElement;
	import org.osmf.events.AudioEvent;
	import org.osmf.events.BufferEvent;
	import org.osmf.events.DisplayObjectEvent;
	import org.osmf.events.DynamicStreamEvent;
	import org.osmf.events.LoadEvent;
	import org.osmf.events.MediaError;
	import org.osmf.events.MediaErrorEvent;
	import org.osmf.events.MediaPlayerCapabilityChangeEvent;
	import org.osmf.events.MediaPlayerStateChangeEvent;
	import org.osmf.events.PlayEvent;
	import org.osmf.events.SeekEvent;
	import org.osmf.events.TimeEvent;
	import org.osmf.traits.AudioTrait;
	import org.osmf.traits.DRMState;
	import org.osmf.traits.DisplayObjectTrait;
	import org.osmf.traits.LoadState;
	import org.osmf.traits.LoadTrait;
	import org.osmf.traits.MediaTraitType;
	import org.osmf.traits.PlayState;
	import org.osmf.traits.PlayTrait;
	import org.osmf.traits.TimeTrait;
	import org.osmf.utils.DynamicBufferTrait;
	import org.osmf.utils.DynamicDynamicStreamTrait;
	import org.osmf.utils.DynamicMediaElement;
	import org.osmf.utils.DynamicPlayTrait;
	import org.osmf.utils.DynamicSeekTrait;
	import org.osmf.utils.DynamicTimeTrait;
	import org.osmf.utils.NetFactory;
	
	public class TestMediaPlayer extends TestCase
	{
		// Overrides
		//
		
		override public function setUp():void
		{
			mediaPlayer = new MediaPlayer();
			mediaPlayer.autoPlay = false;
			mediaPlayer.autoRewind = false;
			eventDispatcher = new EventDispatcher();
			events = new Vector.<Event>();

			super.setUp();
		}

		override public function tearDown():void
		{
			super.tearDown();

			mediaPlayer = null;
			eventDispatcher = null;
		}
		
		// Tests
		//
		
		public function testSource():void
		{
			var mediaElement:MediaElement = createMediaElement(resourceForMediaElement);
			
			assertTrue(mediaPlayer.state == MediaPlayerState.UNINITIALIZED);
			
			if (this.hasLoadTrait)
			{
				eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
				
				var eventCount:int = 0;
				
				mediaPlayer.addEventListener
					( LoadEvent.LOAD_STATE_CHANGE
					, onTestSource
					);
				mediaPlayer.media = mediaElement;
				
				function onTestSource(event:LoadEvent):void
				{
					eventCount++;
					
					if (eventCount == 1)
					{
						assertTrue(event.loadState == LoadState.LOADING);
						assertTrue(mediaPlayer.state == MediaPlayerState.LOADING);
					}
					else if (eventCount == 2)
					{
						assertTrue(event.loadState == LoadState.READY);
						assertTrue(mediaPlayer.state == MediaPlayerState.READY);
						
						// Now verify that we can unload the media.
						mediaPlayer.media = null;
					}
					else if (eventCount == 3)
					{
						assertTrue(event.loadState == LoadState.UNLOADING);
						assertTrue(mediaPlayer.state == MediaPlayerState.READY);
					}
					else if (eventCount == 4)
					{
						assertTrue(event.loadState == LoadState.UNINITIALIZED);
						assertTrue(mediaPlayer.state == MediaPlayerState.UNINITIALIZED);
						
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}
					else fail();
				}
			}
			else
			{
				mediaPlayer.addEventListener
					( LoadEvent.LOAD_STATE_CHANGE
					, mustNotReceiveEvent
					);
						
				mediaPlayer.media = mediaElement;
				assertTrue(mediaPlayer.state == MediaPlayerState.READY);
				
				// Now verify that we can unload the media.
				mediaPlayer.media = null;
				assertTrue(mediaPlayer.state == MediaPlayerState.UNINITIALIZED);
			}
		}
		
		public function testSourceWithInvalidResource():void
		{
			var mediaElement:MediaElement = createMediaElement(invalidResourceForMediaElement);
			
			assertTrue(mediaPlayer.state == MediaPlayerState.UNINITIALIZED);
			
			if (this.hasLoadTrait)
			{
				eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
				
				var eventCount:int = 0;
				var errorCount:int = 0;
				var waitForError:Boolean = false;
				
				mediaPlayer.addEventListener(MediaErrorEvent.MEDIA_ERROR, onMediaError);
				mediaPlayer.addEventListener
					( LoadEvent.LOAD_STATE_CHANGE
					, onTestSourceWithInvalidResource
					);
				mediaPlayer.media = mediaElement;
				
				function onMediaError(event:MediaErrorEvent):void
				{
					errorCount++;
					
					if (waitForError)
					{
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}
				}
				
				function onTestSourceWithInvalidResource(event:LoadEvent):void
				{
					eventCount++;
					
					if (eventCount == 1)
					{
						assertTrue(event.loadState == LoadState.LOADING);
						assertTrue(mediaPlayer.state == MediaPlayerState.LOADING);
					}
					else if (eventCount == 2)
					{
						assertTrue(event.loadState == LoadState.LOAD_ERROR);
						assertTrue(mediaPlayer.state == MediaPlayerState.PLAYBACK_ERROR);

						if (errorCount == 1)
						{
							eventDispatcher.dispatchEvent(new Event("testComplete"));
						}
						else
						{
							waitForError = true;
						}
					}
					else fail();
				}
			}
			else
			{
				mediaPlayer.addEventListener
					( LoadEvent.LOAD_STATE_CHANGE
					, mustNotReceiveEvent
					);
						
				mediaPlayer.media = mediaElement;
				assertTrue(mediaPlayer.state == MediaPlayerState.READY);
			}
		}
		
		public function testVolume():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
			
			if (hasLoadTrait)
			{
				callAfterLoad(doTestVolume, false);
			}
			else
			{
				mediaPlayer.media = createMediaElement(resourceForMediaElement);
				doTestVolume();
			}
		}

		private function doTestVolume():void
		{
			doTestVolumeCommon(1);
		}

		public function testVolumeWithPreset():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
			
			// Assign a volume up front.  This value will take precedence over
			// any volume inherited from the MediaElement.
			mediaPlayer.addEventListener(AudioEvent.VOLUME_CHANGE, onVolumeChange);
			mediaPlayer.volume = 0.33;

			function onVolumeChange(event:AudioEvent):void
			{
				mediaPlayer.removeEventListener(AudioEvent.VOLUME_CHANGE, onVolumeChange);
				
				assertTrue(event.volume == 0.33);

				if (hasLoadTrait)
				{
					callAfterLoad(doTestVolumeWithPreset, false);
				}
				else
				{
					mediaPlayer.media = createMediaElement(resourceForMediaElement);
					doTestVolumeWithPreset();
				}
			}
		}
		
		private function doTestVolumeWithPreset():void
		{
			doTestVolumeCommon(0.33);
		}
		
		private function doTestVolumeCommon(expectedVolume:Number):void
		{
			if (traitExists(MediaTraitType.AUDIO))
			{
				assertTrue(mediaPlayer.volume == expectedVolume);
				
				mediaPlayer.addEventListener(AudioEvent.VOLUME_CHANGE, onTestVolume);
				mediaPlayer.volume = 0.2;
				
				function onTestVolume(event:AudioEvent):void
				{
					mediaPlayer.removeEventListener(AudioEvent.VOLUME_CHANGE, onTestVolume);
					
					assertTrue(mediaPlayer.volume == 0.2);
					assertTrue(event.volume == 0.2);
					
					eventDispatcher.dispatchEvent(new Event("testComplete"));
				}
			}
			else
			{
				assertTrue(mediaPlayer.hasAudio == false);
				assertTrue(mediaPlayer.volume == expectedVolume);
				
				// Setting the volume should apply, even when the MediaPlayer isn't audible.
				mediaPlayer.volume = 0.5;
				assertTrue(mediaPlayer.volume == 0.5);
								
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}
		
		public function testMuted():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
			
			if (hasLoadTrait)
			{
				callAfterLoad(doTestMuted, false);
			}
			else
			{
				mediaPlayer.media = createMediaElement(resourceForMediaElement);
				doTestMuted();
			}
		}
		
		private function doTestMuted():void
		{
			doTestMutedCommon(false);
		}

		public function testMutedWithPreset():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
			
			// Assign a muted value up front.  This value will take precedence over
			// any muted value inherited from the MediaElement.
			mediaPlayer.addEventListener(AudioEvent.MUTED_CHANGE, onMutedChange);
			mediaPlayer.muted = true;
			
			function onMutedChange(event:AudioEvent):void
			{
				mediaPlayer.removeEventListener(AudioEvent.MUTED_CHANGE, onMutedChange);
				
				assertTrue(event.muted == true);
				
				if (hasLoadTrait)
				{
					callAfterLoad(doTestMutedWithPreset, false);
				}
				else
				{
					mediaPlayer.media = createMediaElement(resourceForMediaElement);
					doTestMutedWithPreset();
				}
			}
		}
		
		private function doTestMutedWithPreset():void
		{
			doTestMutedCommon(true);
		}
		
		private function doTestMutedCommon(expectedMuted:Boolean):void
		{
			if (traitExists(MediaTraitType.AUDIO))
			{
				assertTrue(mediaPlayer.muted == expectedMuted);
				
				mediaPlayer.addEventListener(AudioEvent.MUTED_CHANGE, onTestMuted);
				mediaPlayer.muted = !expectedMuted;
				
				function onTestMuted(event:AudioEvent):void
				{
					mediaPlayer.removeEventListener(AudioEvent.MUTED_CHANGE, onTestMuted);
					
					assertTrue(mediaPlayer.muted == !expectedMuted);
					assertTrue(event.muted == !expectedMuted);
					
					eventDispatcher.dispatchEvent(new Event("testComplete"));
				}
			}
			else
			{
				assertTrue(mediaPlayer.hasAudio == false);
				assertTrue(mediaPlayer.muted == expectedMuted);
				
				// Setting muted should apply, even when the MediaPlayer isn't audible.
				mediaPlayer.muted = !expectedMuted;
				assertTrue(mediaPlayer.muted == !expectedMuted);
				
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}
		
		public function testPan():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));

			if (hasLoadTrait)
			{
				callAfterLoad(doTestPan, false);
			}
			else
			{
				mediaPlayer.media = createMediaElement(resourceForMediaElement);
				doTestPan();
			}
		}
		
		private function doTestPan():void
		{
			doTestPanCommon(0);
		}

		public function testPanWithPreset():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));

			// Assign a pan value up front.  This value will take precedence
			// over any pan value inherited from the MediaElement.
			mediaPlayer.addEventListener(AudioEvent.PAN_CHANGE, onPanChange);
			mediaPlayer.audioPan = -0.5;

			function onPanChange(event:AudioEvent):void
			{
				mediaPlayer.removeEventListener(AudioEvent.PAN_CHANGE, onPanChange);
				
				assertTrue(event.pan == -0.5);
					
				if (hasLoadTrait)
				{
					callAfterLoad(doTestPanWithPreset, false);
				}
				else
				{
					mediaPlayer.media = createMediaElement(resourceForMediaElement);
					doTestPanWithPreset();
				}
			}
		}
		
		private function doTestPanWithPreset():void
		{
			doTestPanCommon(-0.5);
		}
		
		private function doTestPanCommon(expectedPan:Number):void
		{
			if (traitExists(MediaTraitType.AUDIO))
			{
				assertTrue(mediaPlayer.audioPan == expectedPan);
				
				mediaPlayer.addEventListener(AudioEvent.PAN_CHANGE, onTestPan);
				mediaPlayer.audioPan = 0.7;
				
				function onTestPan(event:AudioEvent):void
				{
					mediaPlayer.removeEventListener(AudioEvent.PAN_CHANGE, onTestPan);
					
					assertTrue(mediaPlayer.audioPan == 0.7);
					assertTrue(event.pan == 0.7);
					
					eventDispatcher.dispatchEvent(new Event("testComplete"));
				}
			}
			else
			{
				assertTrue(mediaPlayer.hasAudio == false);
				assertTrue(mediaPlayer.audioPan == expectedPan);

				// Setting pan should apply, even when the MediaPlayer isn't audible.
				mediaPlayer.audioPan = 0.3;
				assertTrue(mediaPlayer.audioPan == 0.3);
				
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}
		
		public function testPlay():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
			
			if (hasLoadTrait)
			{
				callAfterLoad(doTestPlay, false);
			}
			else
			{
				mediaPlayer.media = createMediaElement(resourceForMediaElement);
				doTestPlay();
			}
		}
		
		private function doTestPlay():void
		{
			if (traitExists(MediaTraitType.PLAY))
			{
				doTestPlayPause(false);
			}
			else
			{
				assertTrue(mediaPlayer.canPlay == false);
				assertTrue(mediaPlayer.playing == false);
				
				try
				{
					mediaPlayer.play();
					
					fail();
				}
				catch (e:IllegalOperationError)
				{
					// Swallow.
				}
				
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}
		
		public function testPause():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
			
			if (hasLoadTrait)
			{
				callAfterLoad(doTestPause, false);
			}
			else
			{
				mediaPlayer.media = createMediaElement(resourceForMediaElement);
				doTestPause();
			}
		}
		
		private function doTestPause():void
		{
			if (traitExists(MediaTraitType.PLAY))
			{
				doTestPlayPause(true);
			}
			else
			{
				assertTrue(mediaPlayer.canPlay == false);
				assertTrue(mediaPlayer.paused == false);
				
				try
				{
					mediaPlayer.pause();
					
					fail();
				}
				catch (e:IllegalOperationError)
				{
					// Swallow.
				}
				
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}
		
		private function doTestPlayPause(pauseAfterPlay:Boolean):void
		{
			assertTrue(mediaPlayer.canPlay == true);
			assertTrue(mediaPlayer.playing == false);
			assertTrue(mediaPlayer.state == MediaPlayerState.READY);
				
			mediaPlayer.addEventListener(PlayEvent.PLAY_STATE_CHANGE, onTestPlayPause1);
			mediaPlayer.play();
		
			function onTestPlayPause1(event:PlayEvent):void
			{
				mediaPlayer.removeEventListener(PlayEvent.PLAY_STATE_CHANGE, onTestPlayPause1);
				
				assertTrue(mediaPlayer.playing == true);
				assertTrue(event.playState == PlayState.PLAYING);
				
				// Note that the MediaPlayerState might not be PLAYING yet
				// because the MediaPlayer waits until some data has buffered
				// before changing to PLAYING, so that we don't signal a
				// PLAYING state before we're truly PLAYING.
				assertTrue(		(mediaPlayer.state == MediaPlayerState.READY && mediaPlayer.canBuffer)
							||	(mediaPlayer.state == MediaPlayerState.BUFFERING && mediaPlayer.canBuffer)
							||	(mediaPlayer.state == MediaPlayerState.PLAYING && (!mediaPlayer.canBuffer || mediaPlayer.bufferTime < 0.001))
						  );
				
				if (pauseAfterPlay)
				{
					assertTrue(mediaPlayer.paused == false);
					
					if (mediaPlayer.canPause)
					{
						mediaPlayer.addEventListener(PlayEvent.PLAY_STATE_CHANGE, onTestPlayPause2);
						mediaPlayer.pause();
						
						function onTestPlayPause2(event2:PlayEvent):void
						{
							mediaPlayer.removeEventListener(PlayEvent.PLAY_STATE_CHANGE, onTestPlayPause2);
							
							assertTrue(mediaPlayer.paused == true);
							assertTrue(mediaPlayer.playing == false);
							assertTrue(event2.playState == PlayState.PAUSED);
							assertTrue(mediaPlayer.state == MediaPlayerState.PAUSED);
							
							eventDispatcher.dispatchEvent(new Event("testComplete"));
						}
					}
					else
					{
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}
				}
				else
				{
					eventDispatcher.dispatchEvent(new Event("testComplete"));
				}
			}
		}
		
		public function testStop():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
			
			if (hasLoadTrait)
			{
				callAfterLoad(doTestStop, false);
			}
			else
			{
				mediaPlayer.media = createMediaElement(resourceForMediaElement);
				doTestStop();
			}
		}
		
		public function testStopWithAutoRewind():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
			
			mediaPlayer.autoRewind = true;
			
			if (hasLoadTrait)
			{
				callAfterLoad(doTestStopWithAutoRewind, false);
			}
			else
			{
				mediaPlayer.media = createMediaElement(resourceForMediaElement);
				doTestStopWithAutoRewind();
			}
		}
		
		private function doTestStop():void
		{
			doTestStopCommon(false);
		}

		private function doTestStopWithAutoRewind():void
		{
			doTestStopCommon(true);
		}
		
		private function doTestStopCommon(autoRewind:Boolean):void
		{
			if (traitExists(MediaTraitType.PLAY) && traitExists(MediaTraitType.SEEK))
			{
				assertTrue(mediaPlayer.canPlay == true);
				assertTrue(mediaPlayer.playing == false);
				assertTrue(mediaPlayer.state == MediaPlayerState.READY);
					
				mediaPlayer.addEventListener(PlayEvent.PLAY_STATE_CHANGE, onTestStop1);
				mediaPlayer.play();
			
				function onTestStop1(event:PlayEvent):void
				{
					mediaPlayer.removeEventListener(PlayEvent.PLAY_STATE_CHANGE, onTestStop1);
					
					assertTrue(mediaPlayer.playing == true);
					assertTrue(event.playState == PlayState.PLAYING);
					
					// Note that the MediaPlayerState might not be PLAYING yet
					// because the MediaPlayer waits until some data has buffered
					// before changing to PLAYING, so that we don't signal a
					// PLAYING state before we're truly PLAYING.
					assertTrue(		(mediaPlayer.state == MediaPlayerState.READY && mediaPlayer.canBuffer)
								||	(mediaPlayer.state == MediaPlayerState.PLAYING && (!mediaPlayer.canBuffer || mediaPlayer.bufferTime < 0.001))
							  );

					var hasStopped:Boolean = false;
					var hasSeeked:Boolean = false;
					
					mediaPlayer.addEventListener(PlayEvent.PLAY_STATE_CHANGE, onTestStop2);
					mediaPlayer.addEventListener(SeekEvent.SEEKING_CHANGE, onTestStop3);
					
					mediaPlayer.stop();
					
					function onTestStop2(event2:PlayEvent):void
					{
						hasStopped = true;
						
						assertTrue(mediaPlayer.paused == false);
						assertTrue(mediaPlayer.playing == false);
						assertTrue(event2.playState == PlayState.STOPPED);
						
						if (autoRewind == false)
						{
							mediaPlayer.removeEventListener(PlayEvent.PLAY_STATE_CHANGE, onTestStop2);
							mediaPlayer.removeEventListener(SeekEvent.SEEKING_CHANGE, onTestStop3);
							
							eventDispatcher.dispatchEvent(new Event("testComplete"));
						}
					}
					
					function onTestStop3(event3:SeekEvent):void
					{
						assertTrue(hasStopped);
						
						if (hasSeeked == false)
						{
							hasSeeked = true;
							
							assertTrue(mediaPlayer.paused == false);
							assertTrue(mediaPlayer.playing == false);
							assertTrue(mediaPlayer.seeking == true);
							assertTrue(event3.seeking == true);
						}
						else
						{
							mediaPlayer.removeEventListener(PlayEvent.PLAY_STATE_CHANGE, onTestStop2);
							mediaPlayer.removeEventListener(SeekEvent.SEEKING_CHANGE, onTestStop3);
							
							assertTrue(mediaPlayer.paused == false);
							assertTrue(mediaPlayer.playing == false);
							assertTrue(mediaPlayer.seeking == false);
							assertTrue(event3.seeking == false);
							
							eventDispatcher.dispatchEvent(new Event("testComplete"));
						}
					}
				}
			}
			else
			{
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}
		
		public function testSeek():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
			
			if (hasLoadTrait)
			{
				callAfterLoad(doTestSeek, false);
			}
			else
			{
				mediaPlayer.media = createMediaElement(resourceForMediaElement);
				doTestSeek();
			}
		}

		private function doTestSeek():void
		{
			if (traitExists(MediaTraitType.SEEK))
			{
				assertTrue(mediaPlayer.canSeek == true);
				assertTrue(mediaPlayer.seeking == false);
				assertTrue(mediaPlayer.state == MediaPlayerState.READY);
				
				// For some media, triggering playback will cause the duration
				// to get set.
				if (mediaPlayer.duration == 0)
				{
					mediaPlayer.play();
					mediaPlayer.pause();
				}
				
				var seekTarget:Number = mediaPlayer.duration - 1;
				
				var canSeek:Boolean = mediaPlayer.canSeekTo(seekTarget);
				
				var eventCount:int = 0;

				mediaPlayer.addEventListener(SeekEvent.SEEKING_CHANGE, canSeek ? onTestSeek : mustNotReceiveEvent);
				mediaPlayer.seek(seekTarget);
				
				if (!canSeek)
				{
					eventDispatcher.dispatchEvent(new Event("testComplete"));
				}
				
				function onTestSeek(event:SeekEvent):void
				{
					eventCount++;
					
					if (eventCount == 1)
					{
						assertTrue(mediaPlayer.seeking == true);
						assertTrue(event.seeking);
						assertTrue(mediaPlayer.state == MediaPlayerState.BUFFERING);
					}
					else if (eventCount == 2)
					{
						assertTrue(mediaPlayer.seeking == false);
						assertTrue(event.seeking == false);
						assertTrue(mediaPlayer.state == MediaPlayerState.READY);
						
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}
				}
			}
			else
			{
				assertTrue(mediaPlayer.canSeek == false);
				assertTrue(mediaPlayer.seeking == false);
				
				try
				{
					mediaPlayer.seek(1);
					
					fail();
				}
				catch (e:IllegalOperationError)
				{
					// Swallow.
				}

				try
				{
					mediaPlayer.canSeekTo(1);
					
					fail();
				}
				catch (e:IllegalOperationError)
				{
					// Swallow.
				}
				
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}
		
		public function testCurrentTimeWithChangeEvents():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
			
			if (hasLoadTrait)
			{
				callAfterLoad(doTestCurrentTimeWithChangeEvents, false);
			}
			else
			{
				mediaPlayer.media = createMediaElement(resourceForMediaElement);
				doTestCurrentTimeWithChangeEvents();
			}
		}
		
		public function testCurrentTimeWithNoChangeEvents():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
			
			if (hasLoadTrait)
			{
				callAfterLoad(doTestCurrentTimeWithNoChangeEvents, false);
			}
			else
			{
				mediaPlayer.media = createMediaElement(resourceForMediaElement);
				doTestCurrentTimeWithNoChangeEvents();
			}
		}
		
		private function doTestCurrentTimeWithChangeEvents():void
		{
			doTestCurrentTime(true);
		}

		private function doTestCurrentTimeWithNoChangeEvents():void
		{
			doTestCurrentTime(false);
		}
		
		private function doTestCurrentTime(enableChangeEvents:Boolean):void
		{
			if (traitExists(MediaTraitType.TIME))
			{
				assertTrue(mediaPlayer.temporal == true);
				assertTrue(mediaPlayer.currentTime == 0 || isNaN(mediaPlayer.currentTime));
				
				assertTrue(mediaPlayer.currentTimeUpdateInterval == 250);

				mediaPlayer.addEventListener(TimeEvent.CURRENT_TIME_CHANGE, onCurrentTimeChange);
				mediaPlayer.addEventListener(TimeEvent.COMPLETE, onTestCurrentTime);
				
				if (enableChangeEvents)
				{
					mediaPlayer.currentTimeUpdateInterval = 1000;
					assertTrue(mediaPlayer.currentTimeUpdateInterval == 1000);
				}
				else
				{
					mediaPlayer.currentTimeUpdateInterval = 0;
					assertTrue(mediaPlayer.currentTimeUpdateInterval == 0);
				}
				
				mediaPlayer.play();
				
				var currentTimeUpdateCount:int = 0;
				
				function onTestCurrentTime(event:TimeEvent):void
				{
					mediaPlayer.removeEventListener(TimeEvent.CURRENT_TIME_CHANGE, onCurrentTimeChange);
					mediaPlayer.removeEventListener(TimeEvent.COMPLETE, onTestCurrentTime);
					
					assertTrue(Math.abs(mediaPlayer.currentTime - mediaPlayer.duration) <= 1);
					assertTrue(mediaPlayer.state == MediaPlayerState.BUFFERING ||
							   mediaPlayer.state == MediaPlayerState.READY);
					
					if (enableChangeEvents)
					{
						// We should get roughly 1 update per second.  Note that
						// the timing model isn't precise, so we leave some wiggle
						// room in our assertion (and in particular give ourselves
						// more wiggle room if the duration is longer).
						assertTrue(Math.abs(currentTimeUpdateCount - Math.round(mediaPlayer.duration)) <= Math.max(1, mediaPlayer.duration * 0.10));
					}
					else
					{
						assertTrue(currentTimeUpdateCount == 0);
					}
					
					eventDispatcher.dispatchEvent(new Event("testComplete"));
				}
				
				function onCurrentTimeChange(event:TimeEvent):void
				{
					currentTimeUpdateCount++;
				}
			}
			else
			{
				assertTrue(mediaPlayer.temporal == false);
				
				assertEquals(0, mediaPlayer.duration);
				assertEquals(0, mediaPlayer.currentTime);
				
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}
		
		public function testWidthHeight():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
			
			if (hasLoadTrait)
			{
				callAfterLoad(doTestWidthHeight, false);
			}
			else
			{
				mediaPlayer.media = createMediaElement(resourceForMediaElement);
				doTestWidthHeight();
			}
		}

		private function doTestWidthHeight():void
		{
			if (traitExists(MediaTraitType.DISPLAY_OBJECT))
			{							
				mediaPlayer.addEventListener(DisplayObjectEvent.MEDIA_SIZE_CHANGE, onTestWidthHeight);
				mediaPlayer.play();
												
				function onTestWidthHeight(event:DisplayObjectEvent):void
				{
					mediaPlayer.removeEventListener(DisplayObjectEvent.MEDIA_SIZE_CHANGE, onTestWidthHeight);

					assertTrue((mediaPlayer.mediaWidth == expectedMediaWidthAfterLoad) ||
							   (isNaN(mediaPlayer.mediaWidth) && isNaN(expectedMediaWidthAfterLoad)));
					assertTrue((mediaPlayer.mediaHeight == expectedMediaHeightAfterLoad) ||
							   (isNaN(mediaPlayer.mediaHeight) && isNaN(expectedMediaHeightAfterLoad)));

					eventDispatcher.dispatchEvent(new Event("testComplete"));
				}
			}
			else
			{
				assertTrue(isNaN(mediaPlayer.mediaWidth));
				assertTrue(isNaN(mediaPlayer.mediaHeight));
				
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}
		
		public function testDisplayObject():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
			
			if (hasLoadTrait)
			{
				callAfterLoad(doTestDisplayObject, false);
			}
			else
			{
				mediaPlayer.media = createMediaElement(resourceForMediaElement);
				doTestDisplayObject();
			}
		}

		private function doTestDisplayObject():void
		{
			if (traitExists(MediaTraitType.DISPLAY_OBJECT))
			{
				assertTrue(mediaPlayer.displayObject != null);

				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
			else
			{
				assertTrue(mediaPlayer.displayObject == null);
								
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}
		
		public function testBufferTime():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));

			if (hasLoadTrait)
			{
				callAfterLoad(doTestBufferTime, false);
			}
			else
			{
				mediaPlayer.media = createMediaElement(resourceForMediaElement);
				doTestBufferTime();
			}
		}

		private function doTestBufferTime():void
		{
			if (traitExists(MediaTraitType.BUFFER))
			{
				assertTrue(mediaPlayer.buffering == false);
				assertTrue(mediaPlayer.bufferLength == 0);
				assertTrue(mediaPlayer.bufferTime == 0);
				
				mediaPlayer.addEventListener(BufferEvent.BUFFER_TIME_CHANGE, onTestBufferTime);
								
				mediaPlayer.bufferTime = 10;
				
				function onTestBufferTime(event:BufferEvent):void
				{
					mediaPlayer.removeEventListener(BufferEvent.BUFFER_TIME_CHANGE, onTestBufferTime);

					assertTrue(mediaPlayer.bufferTime == 10);
					assertTrue(event.bufferTime == 10);

					eventDispatcher.dispatchEvent(new Event("testComplete"));
				}
			}
			else
			{
				assertTrue(mediaPlayer.canBuffer == false);
				assertTrue(mediaPlayer.buffering == false);
				assertEquals(0, mediaPlayer.bufferLength);
				assertEquals(0, mediaPlayer.bufferTime);

				// Setting the bufferTime has an effect.			
				mediaPlayer.bufferTime = 5;
				assertEquals(5, mediaPlayer.bufferTime);
				
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}
		
		public function testDynamicStream():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
			
			if (hasLoadTrait)
			{
				callAfterLoad(doTestDynamicStream, false, dynamicStreamResource);
			}
			else
			{
				mediaPlayer.media = createMediaElement(resourceForMediaElement);
				doTestDynamicStream();
			}
		}

		private function doTestDynamicStream():void
		{
			if (traitExists(MediaTraitType.DYNAMIC_STREAM))
			{
				// The stream must be playing before a switch can happen.
				mediaPlayer.play();
				
				assertTrue(mediaPlayer.autoDynamicStreamSwitch == true);
				assertTrue(mediaPlayer.currentDynamicStreamIndex == 0);
				assertTrue(mediaPlayer.maxAllowedDynamicStreamIndex == expectedMaxAllowedDynamicStreamIndex);
				assertTrue(mediaPlayer.dynamicStreamSwitching == false);
				
				for (var i:int = 0; i <= expectedMaxAllowedDynamicStreamIndex; i++)
				{
					assertTrue(mediaPlayer.getBitrateForDynamicStreamIndex(i) == getExpectedBitrateForDynamicStreamIndex(i));
				}
				
				var eventCount:int = 0;
				
				mediaPlayer.maxAllowedDynamicStreamIndex = 2;
				
				mediaPlayer.addEventListener(DynamicStreamEvent.SWITCHING_CHANGE, onTestDynamicStream);
				mediaPlayer.autoDynamicStreamSwitch = false;
				mediaPlayer.switchDynamicStreamIndex(1);
				
				function onTestDynamicStream(event:DynamicStreamEvent):void
				{
					eventCount++;
					
					if (eventCount == 1)
					{
						assertTrue(mediaPlayer.autoDynamicStreamSwitch == false);
						assertTrue(mediaPlayer.currentDynamicStreamIndex == 0);
						assertTrue(mediaPlayer.maxAllowedDynamicStreamIndex == 2);
						assertTrue(mediaPlayer.dynamicStreamSwitching == true);
					}
					else if (eventCount == 2)
					{
						assertTrue(mediaPlayer.autoDynamicStreamSwitch == false);
						assertTrue(mediaPlayer.currentDynamicStreamIndex == 1);
						assertTrue(mediaPlayer.maxAllowedDynamicStreamIndex == 2);
						assertTrue(mediaPlayer.dynamicStreamSwitching == false);
						
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}
					else fail();
				}
			}
			else
			{
				assertTrue(mediaPlayer.isDynamicStream == false);
				assertTrue(mediaPlayer.autoDynamicStreamSwitch == true);
				assertTrue(mediaPlayer.currentDynamicStreamIndex == 0);
				assertTrue(mediaPlayer.maxAllowedDynamicStreamIndex == 0);
				assertTrue(mediaPlayer.dynamicStreamSwitching == false);

				// Setting autoDynamicStreamSwitch should have an effect.
				mediaPlayer.autoDynamicStreamSwitch = false;
				assertTrue(mediaPlayer.autoDynamicStreamSwitch == false);
								
				try
				{
					mediaPlayer.switchDynamicStreamIndex(1);
					
					fail();
				}
				catch (e:IllegalOperationError)
				{
					// Swallow.
				}
				
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}
		
		public function testDRM():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
			
			if (hasLoadTrait)
			{
				callAfterLoad(doTestDRM, false);
			}
			else
			{
				mediaPlayer.media = createMediaElement(resourceForMediaElement);
				doTestDRM();
			}
		}
		
		private function doTestDRM():void
		{
			if (traitExists(MediaTraitType.DRM))
			{
				// TODO
				
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
			else
			{
				assertTrue(mediaPlayer.hasDRM == false);
				assertTrue(mediaPlayer.drmState == DRMState.UNINITIALIZED);
				
				assertTrue(isNaN(mediaPlayer.drmPeriod));
				assertTrue(mediaPlayer.drmStartDate == null);
				assertTrue(mediaPlayer.drmEndDate == null);
				
				try
				{
					mediaPlayer.authenticate();
					
					fail();
				}
				catch (e:IllegalOperationError)
				{
					// Swallow.
				}

				try
				{
					mediaPlayer.authenticateWithToken(null);
					
					fail();
				}
				catch (e:IllegalOperationError)
				{
					// Swallow.
				}

				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}
		
		public function testDVR():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
			
			if (hasLoadTrait)
			{
				callAfterLoad(doTestDVR, false);
			}
			else
			{
				mediaPlayer.media = createMediaElement(resourceForMediaElement);
				doTestDVR();
			}
		}
		
		private function doTestDVR():void
		{
			if (traitExists(MediaTraitType.DVR))
			{
				// TODO
				
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
			else
			{
				assertTrue(mediaPlayer.isDVRRecording == false);

				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}
		
		public function testDisplayObjectEventGeneration():void
		{
			
			var media:DynamicMediaElement = new DynamicMediaElement();
			mediaPlayer.media = media;
			mediaPlayer.addEventListener(DisplayObjectEvent.DISPLAY_OBJECT_CHANGE, eventCatcher);
			mediaPlayer.addEventListener(DisplayObjectEvent.MEDIA_SIZE_CHANGE, eventCatcher);
						
			assertTrue(isNaN(mediaPlayer.mediaHeight));
			assertTrue(isNaN(mediaPlayer.mediaWidth));
			assertEquals(null, mediaPlayer.displayObject);
			var display:Sprite = new Sprite();
			var doTrait:DisplayObjectTrait = new DisplayObjectTrait(display, 150, 200);
						
			media.doAddTrait(MediaTraitType.DISPLAY_OBJECT, doTrait);
								
			assertEquals(2, events.length);		
			assertTrue(events[0] is DisplayObjectEvent);		
			assertTrue(events[1] is DisplayObjectEvent);
			assertEquals(events[0].type, DisplayObjectEvent.DISPLAY_OBJECT_CHANGE);		
			assertEquals(events[1].type, DisplayObjectEvent.MEDIA_SIZE_CHANGE);
			
			assertEquals(DisplayObjectEvent(events[0]).newDisplayObject, doTrait.displayObject );
			assertEquals(DisplayObjectEvent(events[1]).newHeight, doTrait.mediaHeight );
			assertEquals(DisplayObjectEvent(events[1]).newWidth, doTrait.mediaWidth );
			
			media.doRemoveTrait(MediaTraitType.DISPLAY_OBJECT);
			
			assertEquals(4, events.length);	
			assertTrue(events[2] is DisplayObjectEvent);		
			assertTrue(events[3] is DisplayObjectEvent);
			assertEquals(events[2].type, DisplayObjectEvent.DISPLAY_OBJECT_CHANGE);		
			assertEquals(events[3].type, DisplayObjectEvent.MEDIA_SIZE_CHANGE);
		}
		
		public function testBufferEventGeneration():void
		{			
			var media:DynamicMediaElement = new DynamicMediaElement();
			mediaPlayer.media = media;
			mediaPlayer.addEventListener(BufferEvent.BUFFER_TIME_CHANGE, eventCatcher);
			mediaPlayer.addEventListener(BufferEvent.BUFFERING_CHANGE, eventCatcher);
					
			assertFalse(mediaPlayer.buffering);
			assertEquals(0, mediaPlayer.bufferTime);
			assertEquals(0, mediaPlayer.bufferLength);
			
			var bufferTrait:DynamicBufferTrait = new DynamicBufferTrait();
			bufferTrait.bufferTime = 25;
			bufferTrait.bufferLength = 15;
			bufferTrait.buffering = true;
			
			media.doAddTrait(MediaTraitType.BUFFER, bufferTrait);
			
			assertEquals(2, events.length);	
			assertTrue(events[0] is BufferEvent);	
			assertTrue(events[1] is BufferEvent);		
						
			assertEquals(BufferEvent(events[0]).bufferTime, bufferTrait.bufferTime, mediaPlayer.bufferTime);
			assertEquals(BufferEvent(events[0]).type, BufferEvent.BUFFER_TIME_CHANGE);
			assertEquals(bufferTrait.bufferLength, mediaPlayer.bufferLength);
			assertEquals(BufferEvent(events[1]).type, BufferEvent.BUFFERING_CHANGE);
			assertEquals(BufferEvent(events[1]).buffering, bufferTrait.buffering, mediaPlayer.buffering);
			
			media.doRemoveTrait(MediaTraitType.BUFFER);
			
			// Should get a buffer time change, back to the default of 0.
			
			assertEquals(4, events.length);	
			assertTrue(events[2] is BufferEvent);	
			assertEquals(events[2].type, BufferEvent.BUFFER_TIME_CHANGE);			
			assertTrue(events[3] is BufferEvent);	
			assertEquals(events[3].type, BufferEvent.BUFFERING_CHANGE);			
		}
		
		public function testDynamicStreamEventGeneration():void
		{			
			var media:DynamicMediaElement = new DynamicMediaElement();
			mediaPlayer.media = media;
			mediaPlayer.addEventListener(DynamicStreamEvent.AUTO_SWITCH_CHANGE, eventCatcher);
			mediaPlayer.addEventListener(DynamicStreamEvent.NUM_DYNAMIC_STREAMS_CHANGE, eventCatcher);
			mediaPlayer.addEventListener(DynamicStreamEvent.SWITCHING_CHANGE, eventCatcher);
						
			mediaPlayer.maxAllowedDynamicStreamIndex = 8;
			
			assertFalse(mediaPlayer.dynamicStreamSwitching);
			assertEquals(0, mediaPlayer.numDynamicStreams);
			assertEquals(8, mediaPlayer.maxAllowedDynamicStreamIndex);
			
			var dynamicTrait:DynamicDynamicStreamTrait = new DynamicDynamicStreamTrait(true, 5, 20);
			dynamicTrait.currentIndex = 5;
			dynamicTrait.maxAllowedIndex = 10;
			dynamicTrait.autoSwitch = true;
						
			media.doAddTrait(MediaTraitType.DYNAMIC_STREAM, dynamicTrait);
			
			assertFalse(dynamicTrait.switching);					
			assertEquals(8, mediaPlayer.maxAllowedDynamicStreamIndex, dynamicTrait.maxAllowedIndex);
			
			assertEquals(1, events.length);	
			assertTrue(events[0] is DynamicStreamEvent);	
			assertTrue(dynamicTrait.autoSwitch);
			assertTrue(mediaPlayer.autoDynamicStreamSwitch);
			assertEquals(dynamicTrait.numDynamicStreams, 20, mediaPlayer.numDynamicStreams);	
			assertEquals(events[0].type, DynamicStreamEvent.NUM_DYNAMIC_STREAMS_CHANGE);
			
			mediaPlayer.autoDynamicStreamSwitch = true;
			
			media.doRemoveTrait(MediaTraitType.DYNAMIC_STREAM);
			
			assertTrue(mediaPlayer.autoDynamicStreamSwitch);
			
			dynamicTrait.autoSwitch = false;
			
			assertEquals(2, events.length);	
			assertTrue(events[1] is DynamicStreamEvent);
			assertEquals(events[1].type, DynamicStreamEvent.NUM_DYNAMIC_STREAMS_CHANGE);
			
			media.doAddTrait(MediaTraitType.DYNAMIC_STREAM, dynamicTrait);
			
			// Should make it true.
			
			assertTrue(dynamicTrait.autoSwitch);
			
			assertEquals(4, events.length);	
			assertTrue(events[2] is DynamicStreamEvent);
			assertEquals(events[2].type, DynamicStreamEvent.AUTO_SWITCH_CHANGE);
			assertTrue(events[3] is DynamicStreamEvent);
			assertEquals(events[3].type, DynamicStreamEvent.NUM_DYNAMIC_STREAMS_CHANGE);
			
		}
		
		public function testAudioEventGeneration():void
		{			
			var media:DynamicMediaElement = new DynamicMediaElement();
			mediaPlayer.media = media;
			mediaPlayer.addEventListener(AudioEvent.MUTED_CHANGE, eventCatcher);
			mediaPlayer.addEventListener(AudioEvent.PAN_CHANGE, eventCatcher);
			mediaPlayer.addEventListener(AudioEvent.VOLUME_CHANGE, eventCatcher);
						
			assertEquals(1, mediaPlayer.volume);
			assertEquals(0, mediaPlayer.audioPan);
			assertFalse(mediaPlayer.muted);
			
			var audioTrait:AudioTrait = new AudioTrait();
			audioTrait.volume = .5;
			audioTrait.pan = 1;
			audioTrait.muted = true;
					
			media.doAddTrait(MediaTraitType.AUDIO, audioTrait);
			
			assertEquals(.5, mediaPlayer.volume, audioTrait.volume);
			assertEquals(1, mediaPlayer.audioPan, audioTrait.pan);
			assertEquals(true, mediaPlayer.muted, audioTrait.muted);
			
			assertEquals(3, events.length);	
			assertTrue(events[0] is AudioEvent);	
			assertTrue(events[1] is AudioEvent);
			assertTrue(events[2] is AudioEvent);
			
			assertTrue(events[0].type, AudioEvent.VOLUME_CHANGE);	
			assertTrue(events[1].type, AudioEvent.MUTED_CHANGE);
			assertTrue(events[2].type, AudioEvent.PAN_CHANGE);
			
			assertEquals(AudioEvent(events[0]).volume,  mediaPlayer.volume);
			assertEquals(AudioEvent(events[0]).pan,  mediaPlayer.audioPan);
			assertEquals(AudioEvent(events[0]).muted,  mediaPlayer.muted);			
			
			// No event generation from removal.
			media.doRemoveTrait(MediaTraitType.AUDIO);
			
			assertEquals(6, events.length);
			
			assertTrue(events[3].type, AudioEvent.VOLUME_CHANGE);	
			assertTrue(events[4].type, AudioEvent.MUTED_CHANGE);
			assertTrue(events[5].type, AudioEvent.PAN_CHANGE);
			
			assertEquals(AudioEvent(events[3]).volume,  mediaPlayer.volume);
			assertEquals(AudioEvent(events[3]).pan,  mediaPlayer.audioPan);
			assertEquals(AudioEvent(events[3]).muted,  mediaPlayer.muted);			
			
		}
		
		public function testTimeEventGeneration():void
		{	
			var media:DynamicMediaElement = new DynamicMediaElement();
			mediaPlayer.media = media;
			mediaPlayer.addEventListener(TimeEvent.CURRENT_TIME_CHANGE, eventCatcher);
			mediaPlayer.addEventListener(TimeEvent.DURATION_CHANGE, eventCatcher);
			
			assertEquals(1, mediaPlayer.volume);
			assertEquals(0, mediaPlayer.audioPan);
			assertFalse(mediaPlayer.muted);
			
			var timeTrait:TimeTrait = new TimeTrait(20);
						
			media.doAddTrait(MediaTraitType.TIME, timeTrait);
			
			assertEquals(20, mediaPlayer.duration, timeTrait.duration);
			
			assertEquals(2, events.length);	
			assertTrue(events[0] is TimeEvent);	
			assertTrue(events[1] is TimeEvent);	
			
			assertTrue(events[0].type, TimeEvent.CURRENT_TIME_CHANGE);	
			assertTrue(events[1].type, TimeEvent.DURATION_CHANGE);	
			assertEquals(TimeEvent(events[0]).time,  mediaPlayer.currentTime);		
			assertEquals(TimeEvent(events[1]).time,  mediaPlayer.duration);		
			
			// Removal Events
			
			media.doRemoveTrait(MediaTraitType.TIME);
			
			assertEquals(4, events.length);	
			assertTrue(events[2] is TimeEvent);	
			assertTrue(events[3] is TimeEvent);	
			
			assertTrue(events[2].type, TimeEvent.CURRENT_TIME_CHANGE);	
			assertTrue(events[3].type, TimeEvent.DURATION_CHANGE);	
			assertEquals(TimeEvent(events[2]).time,  mediaPlayer.currentTime);		
			assertEquals(TimeEvent(events[3]).time,  mediaPlayer.duration);		
		}
			
		
		public function testPlayEventGeneration():void
		{
			var media:DynamicMediaElement = new DynamicMediaElement();
			mediaPlayer.media = media;
			mediaPlayer.addEventListener(PlayEvent.CAN_PAUSE_CHANGE, eventCatcher);
			mediaPlayer.addEventListener(PlayEvent.PLAY_STATE_CHANGE, eventCatcher);
						
			var playTrait:DynamicPlayTrait = new DynamicPlayTrait();
			
			playTrait.canPause = true;
			
			assertFalse(mediaPlayer.playing);
			
			media.doAddTrait(MediaTraitType.PLAY, playTrait);
			
			assertEquals(1, events.length);
			assertTrue(events[0] is PlayEvent);	
			assertEquals(events[0].type, PlayEvent.CAN_PAUSE_CHANGE);
			
			media.doRemoveTrait(MediaTraitType.PLAY);
			
			assertEquals(2, events.length);
			assertTrue(events[1] is PlayEvent);	
			assertEquals(events[1].type, PlayEvent.CAN_PAUSE_CHANGE);
			
			assertFalse(mediaPlayer.canPause);
			assertFalse(mediaPlayer.canPlay);
			
			playTrait.canPause = false;
			playTrait.play();
			
			media.doAddTrait(MediaTraitType.PLAY, playTrait);
			
			assertEquals(3, events.length);
			assertTrue(events[2] is PlayEvent);	
			assertEquals(events[2].type, PlayEvent.PLAY_STATE_CHANGE);
			
			assertTrue(mediaPlayer.playing);	
		}
		
		public function testLoop():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
			
			if (hasLoadTrait)
			{
				callAfterLoad(doTestLoop, false);
			}
			else
			{
				mediaPlayer.media = createMediaElement(resourceForMediaElement);
				doTestLoop();
			}
		}
		
 		public function testLoopWithAutoRewind():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
			
			mediaPlayer.autoRewind = true;
			
			if (hasLoadTrait)
			{
				callAfterLoad(doTestLoop, false);
			}
			else
			{
				mediaPlayer.media = createMediaElement(resourceForMediaElement);
				doTestLoop();
			}
		}
 
		private function doTestLoop():void
		{
			if (traitExists(MediaTraitType.TIME))
			{
				mediaPlayer.loop = true;
				
				var states:Array = [];
				var loopCount:int = 0;
				
				mediaPlayer.addEventListener(MediaPlayerStateChangeEvent.MEDIA_PLAYER_STATE_CHANGE, onStateChange);
				mediaPlayer.addEventListener(TimeEvent.COMPLETE, onTestLoop);
				mediaPlayer.play();
				
				function onTestLoop(event:TimeEvent):void
				{
					loopCount++;
					
					assertTrue(mediaPlayer.paused == false);
					
					var statesStr:String = states.join(" ");
					assertTrue(statesStr == "playing" || statesStr == "ready playing");
					
					states = [];

					// Make sure we actually play a second time.
					if (loopCount == 2)
					{
						mediaPlayer.removeEventListener(MediaPlayerStateChangeEvent.MEDIA_PLAYER_STATE_CHANGE, onStateChange);
						mediaPlayer.removeEventListener(TimeEvent.COMPLETE, onTestLoop);

						mediaPlayer.pause();
					
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}
				}
				
				function onStateChange(event:MediaPlayerStateChangeEvent):void
				{
					// Ignore any buffering state changes, they can happen
					// intermittently.
					if (event.state != MediaPlayerState.BUFFERING &&
						!(states.length > 0 && event.state == states[states.length-1]))
					{
						states.push(event.state);
					}
				}
			}
			else
			{
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}
		
		public function testAutoRewind():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
			
			if (hasLoadTrait)
			{
				callAfterLoad(doTestAutoRewind, false);
			}
			else
			{
				mediaPlayer.media = createMediaElement(resourceForMediaElement);
				doTestAutoRewind();
			}
		}
		
		private function doTestAutoRewind():void
		{
			if (traitExists(MediaTraitType.TIME))
			{
				mediaPlayer.autoRewind = true;
				
				var states:Array = [];
				
				mediaPlayer.addEventListener(MediaPlayerStateChangeEvent.MEDIA_PLAYER_STATE_CHANGE, onStateChange);
				mediaPlayer.addEventListener(TimeEvent.COMPLETE, onTestAutoRewind);
				mediaPlayer.play();
				
				function onTestAutoRewind(event:TimeEvent):void
				{
					mediaPlayer.removeEventListener(MediaPlayerStateChangeEvent.MEDIA_PLAYER_STATE_CHANGE, onStateChange);
					mediaPlayer.removeEventListener(TimeEvent.COMPLETE, onTestAutoRewind);
					
					assertTrue(mediaPlayer.paused == false);
					assertTrue(mediaPlayer.playing == false);
					
					// These are all possible/permissible state sequences.
					var statesStr:String = states.join(" ");

					// II most cases, the state change will only cover PLAYING.  But if the
					// MediaElement can execute a synchronous rewind (e.g. DurationElement),
					// then the MediaPlayer will enter the READY state before the autoRewind
					// operation completes.  This is ok.
					assertTrue(statesStr == "playing" || statesStr == "playing ready");
					
					eventDispatcher.dispatchEvent(new Event("testComplete"));
				}
				
				function onStateChange(event:MediaPlayerStateChangeEvent):void
				{
					// Ignore any buffering state changes, they can happen
					// intermittently.
					if (event.state != MediaPlayerState.BUFFERING &&
						!(states.length > 0 && event.state == states[states.length-1]))
					{
						states.push(event.state);
					}
				}
			}
			else
			{
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}
		
		public function testAutoRewindAndAutoPlay():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
			
			if (hasLoadTrait)
			{
				callAfterLoad(doTestAutoRewindAndAutoPlay, false);
			}
			else
			{
				mediaPlayer.media = createMediaElement(resourceForMediaElement);
				doTestAutoRewindAndAutoPlay();
			}
		}
		
		private function doTestAutoRewindAndAutoPlay():void
		{
			if (traitExists(MediaTraitType.TIME))
			{
				mediaPlayer.autoRewind = true;
				mediaPlayer.autoPlay = true;
				
				var states:Array = [];
				
				mediaPlayer.addEventListener(MediaPlayerStateChangeEvent.MEDIA_PLAYER_STATE_CHANGE, onStateChange);
				mediaPlayer.addEventListener(TimeEvent.COMPLETE, onTestAutoRewind);
				mediaPlayer.play();
				
				function onTestAutoRewind(event:TimeEvent):void
				{
					mediaPlayer.removeEventListener(MediaPlayerStateChangeEvent.MEDIA_PLAYER_STATE_CHANGE, onStateChange);
					mediaPlayer.removeEventListener(TimeEvent.COMPLETE, onTestAutoRewind);
					
					assertTrue(mediaPlayer.paused == false);
					assertTrue(mediaPlayer.playing == false);
					
					// These are all possible/permissible state sequences.
					var statesStr:String = states.join(" ");

					// II most cases, the state change will only cover PLAYING.  But if the
					// MediaElement can execute a synchronous rewind (e.g. DurationElement),
					// then the MediaPlayer will enter the READY state before the autoRewind
					// operation completes.  This is ok.
					assertTrue(statesStr == "playing" || statesStr == "playing ready");
					
					eventDispatcher.dispatchEvent(new Event("testComplete"));
				}
				
				function onStateChange(event:MediaPlayerStateChangeEvent):void
				{
					// Ignore any buffering state changes, they can happen
					// intermittently.
					if (event.state != MediaPlayerState.BUFFERING &&
						!(states.length > 0 && event.state == states[states.length-1]))
					{
						states.push(event.state);
					}
				}
			}
			else
			{
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}
		
		public function testAutoRewindFalse():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
			
			if (hasLoadTrait)
			{
				callAfterLoad(doTestAutoRewindFalse, false);
			}
			else
			{
				mediaPlayer.media = createMediaElement(resourceForMediaElement);
				doTestAutoRewindFalse();
			}
		}
		
		private function doTestAutoRewindFalse():void
		{
			if (traitExists(MediaTraitType.TIME))
			{
				mediaPlayer.autoRewind = false;
				
				var states:Array = [];
				
				mediaPlayer.addEventListener(TimeEvent.COMPLETE, onTestAutoRewindFalse);
				mediaPlayer.play();
				
				function onTestAutoRewindFalse(event:TimeEvent):void
				{
					mediaPlayer.removeEventListener(TimeEvent.COMPLETE, onTestAutoRewindFalse);
					
					assertTrue(mediaPlayer.paused == false);
					assertTrue(mediaPlayer.playing == false);
					
					// Calling play when it's at the end should cause the media
					// to rewind and begin playback again.
					mediaPlayer.addEventListener(MediaPlayerStateChangeEvent.MEDIA_PLAYER_STATE_CHANGE, onStateChange);
					mediaPlayer.play();
				}
				
				function onStateChange(event:MediaPlayerStateChangeEvent):void
				{
					// If the autoRewind works, we should get a READY event.
					if (event.state == MediaPlayerState.READY || event.state == MediaPlayerState.PLAYING)
					{
						mediaPlayer.removeEventListener(MediaPlayerStateChangeEvent.MEDIA_PLAYER_STATE_CHANGE, onStateChange);

						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}
				}
			}
			else
			{
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}
		
		public function testAutoPlay():void
		{
			if (hasLoadTrait)
			{
				eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
				
				mediaPlayer.addEventListener(MediaPlayerStateChangeEvent.MEDIA_PLAYER_STATE_CHANGE, onStateChange);
				mediaPlayer.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onLoaded);

				var eventCount:int = 0;
				
				mediaPlayer.autoPlay = true;
				mediaPlayer.media = createMediaElement(resourceForMediaElement);
				
				function onStateChange(event:MediaPlayerStateChangeEvent):void
				{
					eventCount++;
					
					if (eventCount == 1)
					{
						assertTrue(event.state == MediaPlayerState.LOADING);
					}				
					else if (eventCount == 2)
					{
						if (mediaPlayer.canBuffer && mediaPlayer.bufferTime > 0)
						{
							assertTrue(event.state == MediaPlayerState.READY);
						}
						else
						{
							assertTrue(event.state == MediaPlayerState.PLAYING);

							mediaPlayer.removeEventListener(MediaPlayerStateChangeEvent.MEDIA_PLAYER_STATE_CHANGE, onStateChange);
							
							eventDispatcher.dispatchEvent(new Event("testComplete"));
						}
					}
					else if (eventCount == 3)
					{
						assertTrue(event.state == MediaPlayerState.BUFFERING);
					}				
					else if (eventCount == 4)
					{
						assertTrue(event.state == MediaPlayerState.PLAYING);
						
						mediaPlayer.removeEventListener(MediaPlayerStateChangeEvent.MEDIA_PLAYER_STATE_CHANGE, onStateChange);
						
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}				
					else
					{
						mediaPlayer.removeEventListener(MediaPlayerStateChangeEvent.MEDIA_PLAYER_STATE_CHANGE, onStateChange);
					
						fail();
					}
				}
			
				function onLoaded(event:LoadEvent):void
				{
					if (event.loadState == LoadState.READY)
					{
						if (traitExists(MediaTraitType.PLAY) == false)
						{
							assertTrue(eventCount == 2);
							
							eventDispatcher.dispatchEvent(new Event("testComplete"));
						}
					}
				}
			}
		}

		public function testMediaPlayerState():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
			
			if (hasLoadTrait)
			{
				callAfterLoad(doTestMediaPlayerState, false);
			}
			else
			{
				mediaPlayer.media = createMediaElement(resourceForMediaElement);
				doTestMediaPlayerState();
			}
		}

		private function doTestMediaPlayerState():void
		{
			if (traitExists(MediaTraitType.TIME))
			{
				var stateCount:int = 0;
				var lastNonBufferingState:String;
			
				assertTrue(mediaPlayer.state == MediaPlayerState.READY);
					
				mediaPlayer.addEventListener(MediaPlayerStateChangeEvent.MEDIA_PLAYER_STATE_CHANGE, onStateChange);
				mediaPlayer.play();
				
				function onStateChange(event:MediaPlayerStateChangeEvent):void
				{
					// Ignore buffering events, they can occur at any time.
					if (event.state == MediaPlayerState.BUFFERING ||
						event.state == lastNonBufferingState)
					{
						return;
					}
					
					lastNonBufferingState = event.state;
						
					stateCount++;
					
					if (stateCount == 1)
					{
						assertTrue(event.state == MediaPlayerState.PLAYING);
					}
					else if (stateCount == 2)
					{
						assertTrue(event.state == MediaPlayerState.READY);
						
						mediaPlayer.removeEventListener(MediaPlayerStateChangeEvent.MEDIA_PLAYER_STATE_CHANGE, onStateChange);
						
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}
				}
			}
			else
			{
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}
		
		public function testMediaErrorEvent():void
		{
			if (hasLoadTrait)
			{
				eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
				
				var mediaElement:MediaElement = createMediaElement(resourceForMediaElement);
				
				var loadTrait:LoadTrait = mediaElement.getTrait(MediaTraitType.LOAD) as LoadTrait;
				loadTrait.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onLoadStateChange);
				assertTrue(loadTrait);

				mediaPlayer.media = mediaElement;
				
				function onLoadStateChange(event:LoadEvent):void
				{
					if (event.loadState == LoadState.READY)
					{
						var testComplete:Boolean = false;
						var capabilityEvents:Array = [];
		
						mediaPlayer.addEventListener(MediaErrorEvent.MEDIA_ERROR, onMediaError);
						mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.CAN_BUFFER_CHANGE, onCapabilityChange);
						mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.CAN_LOAD_CHANGE, onCapabilityChange);
						mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.CAN_PLAY_CHANGE, onCapabilityChange);
						mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.CAN_SEEK_CHANGE, onCapabilityChange);
						mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.HAS_AUDIO_CHANGE, onCapabilityChange);
						mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.HAS_DISPLAY_OBJECT_CHANGE, onCapabilityChange);
						mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.HAS_DRM_CHANGE, onCapabilityChange);
						mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.IS_DYNAMIC_STREAM_CHANGE, onCapabilityChange);
						mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.TEMPORAL_CHANGE, onCapabilityChange);
						
						// Make sure error events dispatched on the trait are redispatched
						// on the MediaPlayer.
						loadTrait.dispatchEvent(new MediaErrorEvent(MediaErrorEvent.MEDIA_ERROR, false, false, new MediaError(99)));
						
						function onMediaError(event:MediaErrorEvent):void
						{
							assertTrue(event.error.errorID == 99);
							assertTrue(event.error.message == "");
							
							assertTrue(mediaPlayer.state == MediaPlayerState.PLAYBACK_ERROR);
							
							assertTrue(event.target == mediaPlayer);
							
							testComplete = true;
						}
						
						function onCapabilityChange(event:MediaPlayerCapabilityChangeEvent):void
						{
							if (event.enabled == false)
							{
								capabilityEvents.push(event);
							}
						}
						
						assertTrue(testComplete);

						// We should have disabled events for each expected trait (except
						// for the LoadTrait).
						assertTrue(capabilityEvents.length == existentTraitTypesAfterLoad.length - 1);
						
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}
				}
			}
		}
		
		// Fixes #FM-612, tests that a composition is stopped when a fatal error
		// is raised from one element.
		public function testMediaPlaybackErrorCompositions():void
		{
			var parallel:ParallelElement = new ParallelElement();
			var media1:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.PLAY], null, null, true);
			var media2:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.PLAY], null, null, true);
			
			var dynamicTime1:DynamicTimeTrait = new DynamicTimeTrait();
			dynamicTime1.duration = 30;
			var dynamicTime2:DynamicTimeTrait = new DynamicTimeTrait();
			dynamicTime2.duration = 30;
			
			var seekTrait1:DynamicSeekTrait = new DynamicSeekTrait(dynamicTime1);
			var seekTrait2:DynamicSeekTrait = new DynamicSeekTrait(dynamicTime2);
			seekTrait1.autoCompleteSeek = true;
			seekTrait2.autoCompleteSeek = true;
			
			media1.doAddTrait(MediaTraitType.TIME, dynamicTime1);
			media2.doAddTrait(MediaTraitType.TIME, dynamicTime2);
			media1.doAddTrait(MediaTraitType.SEEK, seekTrait1);
			media2.doAddTrait(MediaTraitType.SEEK, seekTrait2);
					
			parallel.addChild(media1);
			parallel.addChild(media2);
						
			mediaPlayer.media = parallel;
			mediaPlayer.play();
			
			assertTrue(mediaPlayer.playing);
			
			media1.dispatchEvent(new MediaErrorEvent(MediaErrorEvent.MEDIA_ERROR, false, false, new MediaError(2, "sample media error")));
			
			assertEquals(PlayState.STOPPED, (media2.getTrait(MediaTraitType.PLAY) as PlayTrait).playState);
						
		}
		
		public function testCapabilityAddEvents():void
		{
			if (hasLoadTrait)
			{
				eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
				
				var eventCount:int = 0;
				
				var numExistentTraitTypesAfterLoad:Number = existentTraitTypesAfterLoad.length;
				
				mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.HAS_AUDIO_CHANGE			, onCapabilityChange);
				mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.CAN_BUFFER_CHANGE			, onCapabilityChange);
				mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.CAN_LOAD_CHANGE			, onCapabilityChange);
				mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.CAN_PLAY_CHANGE			, onCapabilityChange);
				mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.CAN_SEEK_CHANGE			, onCapabilityChange);
				mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.IS_DYNAMIC_STREAM_CHANGE	, onCapabilityChange);
				mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.TEMPORAL_CHANGE			, onCapabilityChange);
				mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.HAS_DISPLAY_OBJECT_CHANGE , onCapabilityChange);
				mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.HAS_DRM_CHANGE  			, onCapabilityChange);
				mediaPlayer.addEventListener(MediaPlayerStateChangeEvent.MEDIA_PLAYER_STATE_CHANGE		, onStateChange);
				mediaPlayer.media = createMediaElement(resourceForMediaElement);
				
				function onCapabilityChange(event:MediaPlayerCapabilityChangeEvent):void
				{
					eventCount++;
					
					assertTrue(event.enabled);
					
					switch (event.type)
					{
						case MediaPlayerCapabilityChangeEvent.HAS_AUDIO_CHANGE:
							assertTrue(mediaPlayer.hasAudio == true);
							break;
						case MediaPlayerCapabilityChangeEvent.CAN_BUFFER_CHANGE:
							assertTrue(mediaPlayer.canBuffer == true);
							break;
						case MediaPlayerCapabilityChangeEvent.CAN_LOAD_CHANGE:
							assertTrue(mediaPlayer.canLoad == true);
							break;
						case MediaPlayerCapabilityChangeEvent.CAN_PLAY_CHANGE:
							assertTrue(mediaPlayer.canPlay == true);
							break;
						case MediaPlayerCapabilityChangeEvent.CAN_SEEK_CHANGE:
							assertTrue(mediaPlayer.canSeek == true);
							break;
						case MediaPlayerCapabilityChangeEvent.IS_DYNAMIC_STREAM_CHANGE:
							assertTrue(mediaPlayer.isDynamicStream == true);
							break;
						case MediaPlayerCapabilityChangeEvent.TEMPORAL_CHANGE:
							assertTrue(mediaPlayer.temporal == true);
							break;
						case MediaPlayerCapabilityChangeEvent.HAS_DISPLAY_OBJECT_CHANGE:
							// The display object won't be set until *after*
							// the change event.
							assertTrue(mediaPlayer.displayObject == null);
							break;
						case MediaPlayerCapabilityChangeEvent.HAS_DRM_CHANGE:
							assertTrue(mediaPlayer.drmState != null);
							break;
						default:
							fail();
					}
				}
				
				function onStateChange(event:MediaPlayerStateChangeEvent):void
				{
					if (event.state == MediaPlayerState.READY)
					{
						assertTrue(eventCount == numExistentTraitTypesAfterLoad);
					
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}
				}
			}
		}
		
		public function testCapabilityRemoveEvents():void
		{
			if (hasLoadTrait)
			{
				eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
				
				var eventCount:int = 0;
				
				var numExistentTraitTypesAfterLoad:Number = existentTraitTypesAfterLoad.length;
				
				mediaPlayer.addEventListener(MediaPlayerStateChangeEvent.MEDIA_PLAYER_STATE_CHANGE, onStateChange);
				mediaPlayer.media = createMediaElement(resourceForMediaElement);
								
				function onCapabilityChange(event:MediaPlayerCapabilityChangeEvent):void
				{
					eventCount++;
					
					assertTrue(event.enabled == false);
					
					// The value depends on whether the trait also existed
					// on initialization.
					switch (event.type)
					{
						case MediaPlayerCapabilityChangeEvent.HAS_AUDIO_CHANGE:
							assertTrue(mediaPlayer.hasAudio == (existentTraitTypesOnInitialization.indexOf(MediaTraitType.AUDIO) != -1));
							break;
						case MediaPlayerCapabilityChangeEvent.CAN_BUFFER_CHANGE:
							assertTrue(mediaPlayer.canBuffer == (existentTraitTypesOnInitialization.indexOf(MediaTraitType.BUFFER) != -1));
							break;
						case MediaPlayerCapabilityChangeEvent.CAN_LOAD_CHANGE:
							assertTrue(mediaPlayer.canLoad == false);

							// This should be the last trait to be removed.							
							assertTrue(eventCount - 1 == existentTraitTypesAfterLoad.length - existentTraitTypesOnInitialization.length);
							eventDispatcher.dispatchEvent(new Event("testComplete"));
							break;
						case MediaPlayerCapabilityChangeEvent.CAN_PLAY_CHANGE:
							assertTrue(mediaPlayer.canPlay == (existentTraitTypesOnInitialization.indexOf(MediaTraitType.PLAY) != -1));
							break;
						case MediaPlayerCapabilityChangeEvent.CAN_SEEK_CHANGE:
							assertTrue(mediaPlayer.canSeek == (existentTraitTypesOnInitialization.indexOf(MediaTraitType.SEEK) != -1));
							break;
						case MediaPlayerCapabilityChangeEvent.IS_DYNAMIC_STREAM_CHANGE:
							assertTrue(mediaPlayer.isDynamicStream == (existentTraitTypesOnInitialization.indexOf(MediaTraitType.DYNAMIC_STREAM) != -1));
							break;
						case MediaPlayerCapabilityChangeEvent.TEMPORAL_CHANGE:
							assertTrue(mediaPlayer.temporal == (existentTraitTypesOnInitialization.indexOf(MediaTraitType.TIME) != -1));
							break;
						case MediaPlayerCapabilityChangeEvent.HAS_DISPLAY_OBJECT_CHANGE:
							if (existentTraitTypesOnInitialization.indexOf(MediaTraitType.DISPLAY_OBJECT) != -1)
							{
								assertTrue(mediaPlayer.displayObject != null);
							}
							else
							{
								assertTrue(mediaPlayer.displayObject == null);
							}
							break;
						case MediaPlayerCapabilityChangeEvent.HAS_DRM_CHANGE:
							if (existentTraitTypesOnInitialization.indexOf(MediaTraitType.DRM) != -1)
							{
								assertTrue(mediaPlayer.drmState != null);
							}
							else
							{
								assertTrue(mediaPlayer.drmState == null);
							}
							break;
						default:
							fail();
					}
				}
							
				function onStateChange(event:MediaPlayerStateChangeEvent):void
				{
					if (event.state == MediaPlayerState.READY)
					{
						mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.HAS_AUDIO_CHANGE			, onCapabilityChange);
						mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.CAN_BUFFER_CHANGE			, onCapabilityChange);
						mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.CAN_LOAD_CHANGE			, onCapabilityChange);
						mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.CAN_PLAY_CHANGE			, onCapabilityChange);
						mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.CAN_SEEK_CHANGE			, onCapabilityChange);
						mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.IS_DYNAMIC_STREAM_CHANGE	, onCapabilityChange);
						mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.TEMPORAL_CHANGE			, onCapabilityChange);
						mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.HAS_DISPLAY_OBJECT_CHANGE , onCapabilityChange);
						mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.HAS_DRM_CHANGE  			, onCapabilityChange);
						mediaPlayer.media = null;
					}
				}
			}
		}
		
		public function testBytesLoadedTotalWithChangeEvents():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
			
			var changeEventCount:int = 0;
			
			assertTrue(mediaPlayer.bytesLoadedUpdateInterval == 250);
			
			if (hasLoadTrait)
			{
				assertTrue(mediaPlayer.bytesLoaded == expectedBytesLoadedOnInitialization);
				assertTrue(mediaPlayer.bytesTotal == expectedBytesTotalOnInitialization);
				
				mediaPlayer.addEventListener(LoadEvent.BYTES_LOADED_CHANGE, onBytesLoadedChange);

				mediaPlayer.bytesLoadedUpdateInterval = 100;
				assertTrue(mediaPlayer.bytesLoadedUpdateInterval == 100);
				
				callAfterLoad(doTestBytesLoadedTotalWithChangeEvents, false);
			}
			else
			{
				mediaPlayer.media = createMediaElement(resourceForMediaElement);
				doTestBytesLoadedTotalWithChangeEvents();
			}
			
			function onBytesLoadedChange(event:LoadEvent):void
			{
				changeEventCount++;
			}
		
			function doTestBytesLoadedTotalWithChangeEvents():void
			{
				if (traitExists(MediaTraitType.LOAD))
				{
					assertTrue(mediaPlayer.bytesLoaded == expectedBytesLoadedAfterLoad);
					assertTrue(mediaPlayer.bytesTotal == expectedBytesTotalAfterLoad);
				}
				else
				{
					assertTrue(mediaPlayer.bytesLoaded == 0);
					assertTrue(mediaPlayer.bytesTotal == 0);
				}
				
				if (mediaPlayer.bytesTotal > 0)
				{
					if (mediaPlayer.bytesLoaded == mediaPlayer.bytesTotal)
					{
						assertTrue(changeEventCount > 0);
						
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}
					else
					{
						// Wait until the bytes are all here.
						var timer:Timer = new Timer(100);
						timer.addEventListener(TimerEvent.TIMER, onTimer);
						timer.start();
						
						function onTimer(event:TimerEvent):void
						{
							if (mediaPlayer.bytesLoaded == mediaPlayer.bytesTotal)
							{
								timer.stop();
								
								assertTrue(changeEventCount > 0);
								
								eventDispatcher.dispatchEvent(new Event("testComplete"));
							}
						}
					}
				}
				else
				{
					assertTrue(changeEventCount == 0);
					
					eventDispatcher.dispatchEvent(new Event("testComplete"));
				}
			}
		}
		
		public function testSubclip():void
		{
			if (supportsSubclips)
			{
				eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
				
				if (hasLoadTrait)
				{
					callAfterLoad(doTestSubclip, false);
				}
				else
				{
					mediaPlayer.media = createMediaElement(resourceForMediaElement);
					doTestSubclip();
				}
			}
		}

		private function doTestSubclip():void
		{
			var states:Array = [];
			
			assertTrue(mediaPlayer.currentTime == 0);
			
			mediaPlayer.addEventListener(TimeEvent.COMPLETE, onTestSubclip);
			mediaPlayer.play();
			
			function onTestSubclip(event:TimeEvent):void
			{
				mediaPlayer.removeEventListener(TimeEvent.COMPLETE, onTestSubclip);
				
				assertTrue(Math.abs(mediaPlayer.currentTime - mediaPlayer.duration) < 1);
				assertTrue(mediaPlayer.duration == expectedSubclipDuration);
				
				eventDispatcher.dispatchEvent(new Event("testComplete"));
			}
		}
		
		// Protected
		//
		
		protected function createMediaElement(resource:MediaResourceBase):MediaElement
		{
			// Subclasses can override to specify the MediaElement subclass
			// to use with the MediaPlayer.
			var mediaElement:MediaElement = new MediaElement();
			mediaElement.resource = resource;
			return mediaElement; 
		}
		
		protected function get hasLoadTrait():Boolean
		{
			// Subclasses can override to specify that the MediaElement starts
			// with the LoadTrait.
			return false;
		}
		
		protected function get resourceForMediaElement():MediaResourceBase
		{
			// Subclasses can override to specify a resource that the
			// MediaElement can work with.
			return new URLResource("http://www.example.com");
		}

		protected function get invalidResourceForMediaElement():MediaResourceBase
		{
			// Subclasses can override to specify a resource that the
			// MediaElement should fail to load.
			return new URLResource("http://www.example.com/fail");
		}

		protected function get existentTraitTypesOnInitialization():Array
		{
			// Subclasses can override to specify the trait types which are
			// expected upon initialization of the MediaElement
			return [];
		}

		protected function get existentTraitTypesAfterLoad():Array
		{
			// Subclasses can override to specify the trait types which are
			// expected after a load of the MediaElement.  Ignored if the
			// MediaElement lacks the ILoadable trait.
			return [];
		}
		
		protected function get expectedMediaWidthOnInitialization():Number
		{
			// Subclasses can override to specify the expected mediaWidth of the
			// MediaElement upon initialization.
			return 0;
		}

		protected function get expectedMediaHeightOnInitialization():Number
		{
			// Subclasses can override to specify the expected mediaHeight of the
			// MediaElement upon initialization.
			return 0;
		}

		protected function get expectedMediaWidthAfterLoad():Number
		{
			// Subclasses can override to specify the expected mediaWidth of the
			// MediaElement after it has been loaded.
			return 0;
		}

		protected function get expectedMediaHeightAfterLoad():Number
		{
			// Subclasses can override to specify the expected mediaHeight of the
			// MediaElement after it has been loaded.
			return 0;
		}
		
		protected function get expectedBytesLoadedOnInitialization():Number
		{
			// Subclasses can override to specify the expected bytesLoaded of the
			// MediaElement upon initialization.
			return 0;
		}

		protected function get expectedBytesTotalOnInitialization():Number
		{
			// Subclasses can override to specify the expected bytesTotal of the
			// MediaElement upon initialization.
			return 0;
		}

		protected function get expectedBytesLoadedAfterLoad():Number
		{
			// Subclasses can override to specify the expected bytesLoaded of the
			// MediaElement after it has been loaded.
			return 0;
		}

		protected function get expectedBytesTotalAfterLoad():Number
		{
			// Subclasses can override to specify the expected bytesTotal of the
			// MediaElement after it has been loaded.
			return 0;
		}

		protected function get dynamicStreamResource():MediaResourceBase
		{
			// Subclasses can override to specify a media resource that is
			// a dynamic stream.
			return null;
		}
		
		protected function get expectedMaxAllowedDynamicStreamIndex():int
		{
			// Subclasses can override to specify the expected max allowed dynamic
			// stream index.
			return -1;
		}
		
		protected function getExpectedBitrateForDynamicStreamIndex(index:int):Number
		{
			// Subclasses can override to specify the expected bitrates for each
			// dynamic stream index.
			return -1;
		}
		
		protected function get supportsSubclips():Boolean
		{
			// Subclasses can override to indicate that they are capable of
			// playing subclips.
			return false;
		}
		
		protected function get expectedSubclipDuration():Number
		{
			// Subclasses can override to specify the expected duration of
			// the subclip.  Ignored unless supportsSubclips returns true.
			return 0;
		}
		
		// Internals
		//
		
		private function traitExists(traitType:String):Boolean
		{
			var traitTypes:Array = existentTraitTypesAfterLoad;
			for each (var type:String in traitTypes)
			{
				if (type == traitType)
				{
					return true;
				}
			}
			
			return false;
		}
		
		private function callAfterLoad(func:Function, triggerTestCompleteEvent:Boolean=true, requestedResource:MediaResourceBase=null):void
		{
			assertTrue(this.hasLoadTrait);
			
			if (triggerTestCompleteEvent)
			{
				eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, testDelay));
			}

			var mediaElement:MediaElement = createMediaElement(requestedResource != null ? requestedResource : resourceForMediaElement);
			
			assertTrue(mediaPlayer.state == MediaPlayerState.UNINITIALIZED);
			
			mediaPlayer.addEventListener
					( LoadEvent.LOAD_STATE_CHANGE
					, onTestCallAfterLoad
					);
			mediaPlayer.media = mediaElement;
			
			function onTestCallAfterLoad(event:LoadEvent):void
			{
				if (event.loadState == LoadState.READY)
				{
					mediaPlayer.removeEventListener(LoadEvent.LOAD_STATE_CHANGE, onTestCallAfterLoad);
					
					assertTrue(mediaPlayer.state == MediaPlayerState.READY);
					
					if (func != null)
					{
						func();
					}

					if (triggerTestCompleteEvent)
					{
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}
				}
			}
		}
		
		private function get testDelay():Number
		{
			if (NetFactory.neverUseMockObjects) //Using real NetConnections 
			{
				return ASYNC_DELAY;
			}
			else //Mocked net connections have smaller tolerances, should fail quicker.
			{	
				return MOCKED_ASYNC_DELAY;
			}
		}

		private function mustReceiveEvent(event:Event):void
		{
			// Placeholder to ensure an event is received.
		}

		private function mustNotReceiveEvent(event:Event):void
		{
			// Placeholder to ensure an event is NOT received.
			fail();
		}
		
		private function eventCatcher(event:Event):void
		{
			events.push(event);
		}
		
		// Large delay to accommodate non-mocked unit tests.
		private static const ASYNC_DELAY:Number = 53000;
		// Short delay to accommodate mocked unit tests.
		private static const MOCKED_ASYNC_DELAY:Number = 25000;
		
		private var mediaPlayer:MediaPlayer;
		private var eventDispatcher:EventDispatcher;
		private var events:Vector.<Event>;
	}
}