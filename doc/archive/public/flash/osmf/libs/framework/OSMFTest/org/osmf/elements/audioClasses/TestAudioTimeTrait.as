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
	import flash.media.Sound;
	import flash.media.SoundMixer;
	import flash.net.URLRequest;
	
	import org.osmf.events.TimeEvent;
	import org.osmf.media.MediaElement;
	import org.osmf.traits.TestTimeTrait;
	import org.osmf.utils.TestConstants;

	public class TestAudioTimeTrait extends TestTimeTrait
	{
		override protected function createInterfaceObject(... args):Object
		{
			var sound:Sound = new Sound(new URLRequest(TestConstants.LOCAL_SOUND_FILE));
			soundAdapter = new SoundAdapter(new MediaElement(), sound);
			return new AudioTimeTrait(soundAdapter);
		}
		
		override public function tearDown():void
		{
			super.tearDown();
			
			// Kill all sounds.
			SoundMixer.stopAll();
			
			soundAdapter = null;
		}
		
		override public function testCurrentTime():void
		{
			assertTrue(timeTrait.currentTime == 0);
		}

		public function testTimeTrait():void
		{
			assertTrue(isNaN(timeTrait.duration));
			
			soundAdapter.addEventListener("playbackError", onPlaybackError);
			soundAdapter.soundTransform.volume = 0;
			soundAdapter.play();
			
			timeTrait.addEventListener(TimeEvent.COMPLETE, addAsync(onComplete, 7000));			
		}
		
		private function onComplete(event:TimeEvent):void
		{
			assertEquals(timeTrait.currentTime, timeTrait.duration);
			assertTrue(timeTrait.currentTime >= EXPECTED_DURATION);		
		}
		
		private function onPlaybackError(event:Event):void
		{
			assertTrue("Sound.play returned null, all sound channels are full", false);
		}
		
		private static const EXPECTED_DURATION:int = 5;
		
		private var soundAdapter:SoundAdapter;
	}
}