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
package org.osmf.elements.audioClasses
{
	import flash.events.Event;
	import flash.events.EventDispatcher;
	import flash.media.Sound;
	import flash.media.SoundMixer;
	import flash.net.URLRequest;
	
	import org.osmf.events.SeekEvent;
	import org.osmf.media.MediaElement;
	import org.osmf.traits.TestSeekTrait;
	import org.osmf.traits.TimeTrait;
	import org.osmf.utils.TestConstants;
	
	public class TestAudioSeekTrait extends TestSeekTrait
	{
		override protected function createInterfaceObject(... args):Object
		{
			var sound:Sound = new Sound(new URLRequest(TestConstants.LOCAL_SOUND_FILE));
			soundAdapter = new SoundAdapter(new MediaElement(), sound);

			timeTrait = new AudioTimeTrait(soundAdapter);
			return new AudioSeekTrait(timeTrait, soundAdapter);
		}

		override protected function get maxSeekValue():Number
		{
			return timeTrait.duration;
		}
		
		override protected function get processesSeekCompletion():Boolean
		{
			return true;
		}
		
		override public function setUp():void
		{
			eventDispatcher = new EventDispatcher();
			
			super.setUp();
		}
		
		override public function tearDown():void
		{
			super.tearDown();
			
			// Kill all sounds.
			SoundMixer.stopAll();
			
			soundAdapter = null;
			timeTrait = null;
			
			eventDispatcher = null;
		}

		public function testSeekTrait():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, 3000));
			assertFalse(seekTrait.seeking);

			soundAdapter.addEventListener("downloadComplete", onDownloadComplete);
			seekTrait.addEventListener(SeekEvent.SEEKING_CHANGE, onTestSeekTrait);
			
			var eventCount:int = 0; 
			
			soundAdapter.soundTransform.volume = 0;
			soundAdapter.play();
			
			function onDownloadComplete(event:Event):void
			{
				assertTrue(eventCount == 0);
				eventCount++;
				
				assertFalse(seekTrait.seeking);
				
				assertTrue(seekTrait.canSeekTo(3));
					
				seekTrait.seek(3);
			}
	
			function onTestSeekTrait(event:SeekEvent):void
			{
				if (event.seeking == true)
				{
					assertTrue(eventCount == 1);
					eventCount++;
				}
				if (event.seeking == false)
				{
					assertTrue(eventCount == 2);
					
					eventDispatcher.dispatchEvent(new Event("testComplete"));
				}
			}
		}

		private function mustReceiveEvent(event:Event):void
		{
			// Placeholder to ensure an event is received.
		}

		private var eventDispatcher:EventDispatcher;
		private var soundAdapter:SoundAdapter;
		private var timeTrait:TimeTrait;
	}
}