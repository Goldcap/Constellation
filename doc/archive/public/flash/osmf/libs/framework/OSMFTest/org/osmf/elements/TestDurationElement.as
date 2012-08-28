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
*  The Initial Developer of the Original Code is Adobe Systems Incorporated.
*  Portions created by Adobe Systems Incorporated are Copyright (C) 2009 Adobe Systems 
*  Incorporated. All Rights Reserved. 
*  
*****************************************************/
package org.osmf.elements
{
	import __AS3__.vec.Vector;
	
	import flash.errors.IllegalOperationError;
	import flash.events.Event;
	
	import org.osmf.events.SeekEvent;
	import org.osmf.events.TimeEvent;
	import org.osmf.media.MediaElement;
	import org.osmf.media.MediaResourceBase;
	import org.osmf.media.TestMediaElement;
	import org.osmf.media.URLResource;
	import org.osmf.traits.MediaTraitType;
	import org.osmf.traits.PlayState;
	import org.osmf.traits.PlayTrait;
	import org.osmf.traits.SeekTrait;
	import org.osmf.traits.TimeTrait;
	import org.osmf.utils.DynamicMediaElement;

	public class TestDurationElement extends TestMediaElement
	{
		public function TestDurationElement()
		{
			super();
		}
		
		public function testConstructor():void
		{
			try 
			{
				new DurationElement(NaN);
				new DurationElement(NaN, new MediaElement());
			}
			catch(error:IllegalOperationError)
			{
				fail();
			}
		}
		
		public function testTraits():void
		{
			var mediaElement:MediaElement = new MediaElement();
			var proxy:DurationElement = new DurationElement(NaN, mediaElement);
			
			assertTrue(mediaElement.getTrait(MediaTraitType.TIME) == null);
			assertTrue(proxy.getTrait(MediaTraitType.TIME) != null);
						
			assertTrue(mediaElement.getTrait(MediaTraitType.PLAY) == null);
			assertTrue(proxy.getTrait(MediaTraitType.PLAY) != null);
			
			assertTrue(mediaElement.getTrait(MediaTraitType.SEEK) == null);
			assertTrue(proxy.getTrait(MediaTraitType.SEEK) != null);
			
			assertTrue(proxy.getTrait(MediaTraitType.AUDIO) == null);
			assertTrue(proxy.getTrait(MediaTraitType.BUFFER) == null);
			assertTrue(proxy.getTrait(MediaTraitType.LOAD) == null);
			assertTrue(proxy.getTrait(MediaTraitType.DISPLAY_OBJECT) == null);
		}
		
		public function testBlockedTraits():void
		{
			var mediaElement:DynamicMediaElement = new DynamicMediaElement
				(
				[ MediaTraitType.AUDIO
				, MediaTraitType.BUFFER
				, MediaTraitType.DISPLAY_OBJECT
				, MediaTraitType.DRM
				, MediaTraitType.DVR
				, MediaTraitType.DYNAMIC_STREAM
				, MediaTraitType.LOAD
				, MediaTraitType.PLAY
				, MediaTraitType.SEEK
				, MediaTraitType.TIME
				]
				, null, null, true
				); 
			var proxy:DurationElement = new DurationElement(10, mediaElement);
			
			// All the base traits should be blocked initially.
			assertHasTraits(proxy, [MediaTraitType.LOAD, MediaTraitType.PLAY, MediaTraitType.SEEK, MediaTraitType.TIME]);
			
			// As soon as we begin playback, the traits should be unblocked.
			var playTrait:PlayTrait = proxy.getTrait(MediaTraitType.PLAY) as PlayTrait;
			playTrait.play();

			assertHasTraits(proxy, [], false);
			
			// Pausing should have no effect.
			playTrait.pause();
			assertHasTraits(proxy, [], false);
			
			// Ditto for seeking.
			var seekTrait:SeekTrait = proxy.getTrait(MediaTraitType.SEEK) as SeekTrait;
			seekTrait.seek(5);
			assertHasTraits(proxy, [], false);
			
			// Unless we seek(0), which should block the traits.
			seekTrait.seek(0);
			assertHasTraits(proxy, [MediaTraitType.LOAD, MediaTraitType.PLAY, MediaTraitType.SEEK, MediaTraitType.TIME]);

			// But if we seek(0) while playing, it should unblock the traits.
			seekTrait.seek(5);
			playTrait.play();
			seekTrait.seek(0);
			assertHasTraits(proxy, [], false);
			
			// Or if we seek(duration), which is equivalent to completing playback.
			seekTrait.seek(10);
			assertHasTraits(proxy, [MediaTraitType.LOAD, MediaTraitType.PLAY, MediaTraitType.SEEK, MediaTraitType.TIME]);
			
			// But seeking back in should expose the traits.
			seekTrait.seek(5);
			assertHasTraits(proxy, [], false);
		}
		
		private function assertHasTraits(mediaElement:MediaElement, traitTypes:Array, mustHave:Boolean=true):void
		{
			// Create a separate list with the traits that should *not* exist
			// on the media element.
			var missingTraitTypes:Vector.<String> = MediaTraitType.ALL_TYPES.concat();
			for each (var traitType:String in traitTypes)
			{
				missingTraitTypes.splice(missingTraitTypes.indexOf(traitType), 1);
			}
			assertTrue(traitTypes.length + missingTraitTypes.length == MediaTraitType.ALL_TYPES.length);
			
			// Verify the ones that should exist do exist.
			for (var i:int = 0; i < traitTypes.length; i++)
			{
				assertTrue(mediaElement.hasTrait(traitTypes[i]) == mustHave);
			}
			
			// Verify the ones that shouldn't exist don't exist.
			for (i = 0; i < missingTraitTypes.length; i++)
			{
				assertTrue(mediaElement.hasTrait(missingTraitTypes[i]) == !mustHave);
			}
		}
		
		public function testEvents():void
		{
			events = new Vector.<Event>();
			var mediaElement:MediaElement = new MediaElement();
			var proxy:DurationElement = new DurationElement(50, mediaElement);
			
			var timeTrait:TimeTrait = proxy.getTrait(MediaTraitType.TIME) as TimeTrait;
			var playTrait:PlayTrait = proxy.getTrait(MediaTraitType.PLAY) as PlayTrait;
			var seekTrait:SeekTrait = proxy.getTrait(MediaTraitType.SEEK) as SeekTrait;
			seekTrait.addEventListener(SeekEvent.SEEKING_CHANGE, eventCatcher);
			
			playTrait.play();
			assertTrue(playTrait.playState == PlayState.PLAYING);
			playTrait.pause();
			assertTrue(playTrait.playState == PlayState.PAUSED);
			playTrait.play();
			assertTrue(playTrait.playState == PlayState.PLAYING);
			seekTrait.seek(15);			
			assertFalse(seekTrait.seeking);
			assertEquals(2, events.length);
			assertTrue(events[0] is SeekEvent);
			assertTrue(events[1] is SeekEvent);
			assertTrue(SeekEvent(events[0]).seeking == true);
			assertTrue(SeekEvent(events[1]).seeking == false);		
		}
		
		public function testAutoRewindOnSeek():void
		{
			var proxy:DurationElement = new DurationElement(10);
			
			var timeTrait:TimeTrait = proxy.getTrait(MediaTraitType.TIME) as TimeTrait;
			var playTrait:PlayTrait = proxy.getTrait(MediaTraitType.PLAY) as PlayTrait;
			var seekTrait:SeekTrait = proxy.getTrait(MediaTraitType.SEEK) as SeekTrait;
			
			seekTrait.seek(10);
			assertTrue(timeTrait.currentTime == 10);
			
			// Playing when at the end should not cause an auto-rewind.
			playTrait.play();
			assertTrue(timeTrait.currentTime == 10);
		}

		public function testAutoRewindOnComplete():void
		{
			var proxy:DurationElement = new DurationElement(1);
			
			var timeTrait:TimeTrait = proxy.getTrait(MediaTraitType.TIME) as TimeTrait;
			timeTrait.addEventListener(TimeEvent.COMPLETE, addAsync(onTestAutoRewindComplete, 2000));
			var playTrait:PlayTrait = proxy.getTrait(MediaTraitType.PLAY) as PlayTrait;
			
			playTrait.play();
		
			function onTestAutoRewindComplete(event:TimeEvent):void
			{
				assertTrue(timeTrait.currentTime == 1);
				
				// Playing when at the end should cause an auto-rewind.
				playTrait.play();
				assertTrue(timeTrait.currentTime == 0);
			}
		}
		
		public function eventCatcher(event:Event):void
		{
			events.push(event);
		}
		
		// Overrides
		//
		
		override protected function createMediaElement():MediaElement
		{
			return new DurationElement(0);
		}
		
		override protected function get hasLoadTrait():Boolean
		{
			return false;
		}
		
		override protected function get resourceForMediaElement():MediaResourceBase
		{
			return new URLResource("http://example.com");
		}
		
		override protected function get existentTraitTypesOnInitialization():Array
		{
			return [MediaTraitType.TIME, MediaTraitType.SEEK, MediaTraitType.PLAY];
		}

		override protected function get existentTraitTypesAfterLoad():Array
		{
			return [MediaTraitType.TIME, MediaTraitType.SEEK, MediaTraitType.PLAY];
		}

		private var events:Vector.<Event>;
	}
}