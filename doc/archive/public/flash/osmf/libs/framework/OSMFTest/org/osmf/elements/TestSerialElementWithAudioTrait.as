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
	import flexunit.framework.TestCase;
	
	import org.osmf.media.MediaElement;
	import org.osmf.traits.AudioTrait;
	import org.osmf.traits.MediaTraitType;
	import org.osmf.traits.SeekTrait;
	import org.osmf.traits.TimeTrait;
	import org.osmf.utils.DynamicMediaElement;
	
	public class TestSerialElementWithAudioTrait extends TestCase
	{
		public function testAudioTrait():void
		{
			var serial:SerialElement = new SerialElement();
			
			// No trait to begin with.
			assertTrue(serial.getTrait(MediaTraitType.AUDIO) == null);
			
			// Create a few media elements with the AudioTrait and some
			// initial properties.
			//
			
			var mediaElement1:MediaElement = new DynamicMediaElement([MediaTraitType.AUDIO]);
			var audioTrait1:AudioTrait = mediaElement1.getTrait(MediaTraitType.AUDIO) as AudioTrait;
			audioTrait1.volume = 0.11;
			audioTrait1.muted = true;
			audioTrait1.pan = -0.22;

			var mediaElement2:MediaElement = new DynamicMediaElement([MediaTraitType.AUDIO]);
			var audioTrait2:AudioTrait = mediaElement2.getTrait(MediaTraitType.AUDIO) as AudioTrait;
			audioTrait2.volume = 0.33;
			audioTrait2.muted = false;
			audioTrait2.pan = -0.44;
			
			// Add the first child.  This should cause its properties to
			// propagate to the composition.
			serial.addChild(mediaElement1);
			var audioTrait:AudioTrait = serial.getTrait(MediaTraitType.AUDIO) as AudioTrait;
			assertTrue(audioTrait != null);
			assertTrue(audioTrait.volume == 0.11);
			assertTrue(audioTrait.muted == true);
			assertTrue(audioTrait.pan == -0.22);
			
			// Add the second child.
			serial.addChild(mediaElement2);
			
			// Adding it shouldn't affect the properties of the composition.
			audioTrait = serial.getTrait(MediaTraitType.AUDIO) as AudioTrait;
			assertTrue(audioTrait != null);
			assertTrue(audioTrait.volume == 0.11);
			assertTrue(audioTrait.muted == true);
			assertTrue(audioTrait.pan == -0.22);
			
			// But the added child should inherit the properties of the
			// composition.
			audioTrait2 = mediaElement2.getTrait(MediaTraitType.AUDIO) as AudioTrait;
			assertTrue(audioTrait2 != null);
			assertTrue(audioTrait2.volume == 0.11);
			assertTrue(audioTrait2.muted == true);
			assertTrue(audioTrait2.pan == -0.22);
			
			// Change the settings on the second child.
			audioTrait2.volume = 0.55;
			audioTrait2.muted = false;
			audioTrait2.pan = -0.66;
			
			// This should affect the composition and all of its children.
			audioTrait = serial.getTrait(MediaTraitType.AUDIO) as AudioTrait;
			assertTrue(audioTrait != null);
			assertTrue(audioTrait.volume == 0.55);
			assertTrue(audioTrait.muted == false);
			assertTrue(audioTrait.pan == -0.66);
			audioTrait1 = mediaElement1.getTrait(MediaTraitType.AUDIO) as AudioTrait;
			assertTrue(audioTrait1 != null);
			assertTrue(audioTrait1.volume == 0.55);
			assertTrue(audioTrait1.muted == false);
			assertTrue(audioTrait1.pan == -0.66);
			audioTrait2 = mediaElement2.getTrait(MediaTraitType.AUDIO) as AudioTrait;
			assertTrue(audioTrait2 != null);
			assertTrue(audioTrait2.volume == 0.55);
			assertTrue(audioTrait2.muted == false);
			assertTrue(audioTrait2.pan == -0.66);
		}
		
		public function testAudioTraitWithDynamicChildTraits():void
		{
			var serial:SerialElement = new SerialElement();
			
			var mediaElement1:DynamicMediaElement
				= new DynamicMediaElement([MediaTraitType.AUDIO]);
			var timeTrait1:TimeTrait = new TimeTrait(5);
			var seekTrait1:SeekTrait = new SeekTrait(timeTrait1);
			mediaElement1.doAddTrait(MediaTraitType.TIME, timeTrait1);
			mediaElement1.doAddTrait(MediaTraitType.SEEK, seekTrait1);
			var audioTrait1:AudioTrait = mediaElement1.getTrait(MediaTraitType.AUDIO) as AudioTrait;
			assertNotNull(audioTrait1);
			
			var mediaElement2:DynamicMediaElement = new DynamicMediaElement([]);

			var timeTrait2:TimeTrait = new TimeTrait(5);
			var seekTrait2:SeekTrait = new SeekTrait(timeTrait2);
			mediaElement2.doAddTrait(MediaTraitType.TIME, timeTrait2);
			mediaElement2.doAddTrait(MediaTraitType.SEEK, seekTrait2);

			var audioTrait2:AudioTrait = mediaElement2.getTrait(MediaTraitType.AUDIO) as AudioTrait;
			assertNull(audioTrait2);
			
			// Set the initial AudioTrait properties on the first child. The serial element will
			// adopt these for its composite AudioTrait:
			audioTrait1.muted = true;
			audioTrait1.pan = -1.0;
			audioTrait1.volume = 0.5;
			
			serial.addChild(mediaElement1);
			serial.addChild(mediaElement2);
			
			var compAudioTrait1:AudioTrait = serial.getTrait(MediaTraitType.AUDIO) as AudioTrait;
			assertTrue(compAudioTrait1.muted);
			assertEquals(-1.0, compAudioTrait1.pan);
			assertEquals(0.5, compAudioTrait1.volume);

			assertTrue(SeekTrait(serial.getTrait(MediaTraitType.SEEK)).canSeekTo(6));

			// Skip to the next child:
			SeekTrait(serial.getTrait(MediaTraitType.SEEK)).seek(6);
			
			// Re-get our current AudioTrait:
			var compAudioTrait2:AudioTrait = serial.getTrait(MediaTraitType.AUDIO) as AudioTrait;
			// assertEquals(compAudioTrait1,compAudioTrait2);
			
			// Add the audioTrait trait to the second child:
			mediaElement2.doAddTrait(MediaTraitType.AUDIO, new AudioTrait());
			
			// See if the audioTrait trait changed:
			compAudioTrait2 = serial.getTrait(MediaTraitType.AUDIO) as AudioTrait;
			// assertEquals(compAudioTrait1,compAudioTrait2);
			
			// See if the values propagated correctly:
			assertTrue(compAudioTrait1.muted);
			assertEquals(-1.0, compAudioTrait1.pan);
			assertEquals(0.5, compAudioTrait1.volume);
		}
	}
}