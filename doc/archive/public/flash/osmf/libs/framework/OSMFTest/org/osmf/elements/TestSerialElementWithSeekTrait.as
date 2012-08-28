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
	
	import flexunit.framework.TestCase;
	
	import org.osmf.media.MediaElement;
	import org.osmf.traits.MediaTraitType;
	import org.osmf.traits.SeekTrait;
	import org.osmf.traits.TimeTrait;
	import org.osmf.utils.DynamicMediaElement;
	import org.osmf.utils.DynamicSeekTrait;
	import org.osmf.utils.DynamicTimeTrait;
	
	public class TestSerialElementWithSeekTrait extends TestCase
	{
		override public function setUp():void
		{
			events = [];
		}
		
		public function testSeekTraitCanSeekTo():void
		{
			var serial:SerialElement = new SerialElement();

			// No trait to begin with.
			assertTrue(serial.getTrait(MediaTraitType.TIME) == null);
			assertTrue(serial.getTrait(MediaTraitType.SEEK) == null);

			var mediaElement1:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.TIME, MediaTraitType.SEEK, MediaTraitType.PLAY], null, null, true);
			var timeTrait1:DynamicTimeTrait = mediaElement1.getTrait(MediaTraitType.TIME) as DynamicTimeTrait;
			var seekTrait1:DynamicSeekTrait = mediaElement1.getTrait(MediaTraitType.SEEK) as DynamicSeekTrait;
			timeTrait1.duration = 30;
			timeTrait1.currentTime = 0;

			var mediaElement2:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.TIME, MediaTraitType.SEEK], null, null, true);
			var timeTrait2:DynamicTimeTrait = mediaElement2.getTrait(MediaTraitType.TIME) as DynamicTimeTrait;
			var seekTrait2:DynamicSeekTrait = mediaElement2.getTrait(MediaTraitType.SEEK) as DynamicSeekTrait;
			timeTrait2.duration = 15;
			timeTrait2.currentTime = 0;

			var mediaElement3:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.TIME, MediaTraitType.SEEK, MediaTraitType.PLAY], null, null, true);
			var timeTrait3:DynamicTimeTrait = mediaElement3.getTrait(MediaTraitType.TIME) as DynamicTimeTrait;
			var seekTrait3:DynamicSeekTrait = mediaElement3.getTrait(MediaTraitType.SEEK) as DynamicSeekTrait;
			timeTrait3.duration = 20;
			timeTrait3.currentTime = 0;

			var mediaElement4:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.TIME], null, null, true);
			var timeTrait4:DynamicTimeTrait = mediaElement4.getTrait(MediaTraitType.TIME) as DynamicTimeTrait;
			timeTrait4.duration = 10;
			timeTrait4.currentTime = 0;

			var mediaElement5:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.TIME, MediaTraitType.SEEK], null, null, true);
			var timeTrait5:DynamicTimeTrait = mediaElement5.getTrait(MediaTraitType.TIME) as DynamicTimeTrait;
			var seekTrait5:DynamicSeekTrait = mediaElement5.getTrait(MediaTraitType.SEEK) as DynamicSeekTrait;
			timeTrait5.duration = 40;
			timeTrait5.currentTime = 0;
			
			serial.addChild(mediaElement1);
			serial.addChild(mediaElement2);
			serial.addChild(mediaElement3);
			serial.addChild(mediaElement4);
			serial.addChild(mediaElement5);
			
			var timeTrait:TimeTrait = serial.getTrait(MediaTraitType.TIME) as TimeTrait;
			var seekTrait:SeekTrait = serial.getTrait(MediaTraitType.SEEK) as SeekTrait;
			assertTrue(timeTrait != null);
			assertTrue(seekTrait != null);
			assertTrue(seekTrait.seeking == false);
			
			// Can only seek as far as the last seekable child.
			assertTrue(seekTrait.canSeekTo(0) == true);
			assertTrue(seekTrait.canSeekTo(20) == true);
			assertTrue(seekTrait.canSeekTo(35) == true);
			assertTrue(seekTrait.canSeekTo(55) == true);
			assertTrue(seekTrait.canSeekTo(70) == false);
			assertTrue(seekTrait.canSeekTo(90) == false);
			assertTrue(seekTrait.canSeekTo(65) == true);
			assertTrue(seekTrait.canSeekTo(65.1) == false);
			assertTrue(seekTrait.canSeekTo(-100) == false);
			
			// Now make the intermediate child seekable.
			mediaElement4.doAddTrait(MediaTraitType.SEEK, new SeekTrait(timeTrait4));

			// Can now seek to the end.
			assertTrue(seekTrait.canSeekTo(0) == true);
			assertTrue(seekTrait.canSeekTo(20) == true);
			assertTrue(seekTrait.canSeekTo(35) == true);
			assertTrue(seekTrait.canSeekTo(55) == true);
			assertTrue(seekTrait.canSeekTo(70) == true);
			assertTrue(seekTrait.canSeekTo(90) == true);
			assertTrue(seekTrait.canSeekTo(65) == true);
			assertTrue(seekTrait.canSeekTo(65.1) == true);
			assertTrue(seekTrait.canSeekTo(115) == true);
			assertTrue(seekTrait.canSeekTo(115.1) == false);
			assertTrue(seekTrait.canSeekTo(-100) == false);
		}
		
		public function testSeekTraitSeek():void
		{
			var serial:SerialElement = new SerialElement();

			// No trait to begin with.
			assertTrue(serial.getTrait(MediaTraitType.TIME) == null);
			assertTrue(serial.getTrait(MediaTraitType.SEEK) == null);

			var mediaElement1:MediaElement = new DynamicMediaElement([MediaTraitType.TIME, MediaTraitType.SEEK, MediaTraitType.PLAY], null, null, true);
			var timeTrait1:DynamicTimeTrait = mediaElement1.getTrait(MediaTraitType.TIME) as DynamicTimeTrait;
			var seekTrait1:DynamicSeekTrait = mediaElement1.getTrait(MediaTraitType.SEEK) as DynamicSeekTrait;
			timeTrait1.duration = 30;
			timeTrait1.currentTime = 0;

			var mediaElement2:MediaElement = new DynamicMediaElement([MediaTraitType.TIME, MediaTraitType.SEEK], null, null, true);
			var timeTrait2:DynamicTimeTrait = mediaElement2.getTrait(MediaTraitType.TIME) as DynamicTimeTrait;
			var seekTrait2:DynamicSeekTrait = mediaElement2.getTrait(MediaTraitType.SEEK) as DynamicSeekTrait;
			timeTrait2.duration = 15;
			timeTrait2.currentTime = 0;

			var mediaElement3:MediaElement = new DynamicMediaElement([MediaTraitType.TIME, MediaTraitType.SEEK, MediaTraitType.PLAY], null, null, true);
			var timeTrait3:DynamicTimeTrait = mediaElement3.getTrait(MediaTraitType.TIME) as DynamicTimeTrait;
			var seekTrait3:DynamicSeekTrait = mediaElement3.getTrait(MediaTraitType.SEEK) as DynamicSeekTrait;
			timeTrait3.duration = 20;
			timeTrait3.currentTime = 0;

			var mediaElement4:MediaElement = new DynamicMediaElement([MediaTraitType.TIME], null, null, true);
			var timeTrait4:DynamicTimeTrait = mediaElement4.getTrait(MediaTraitType.TIME) as DynamicTimeTrait;
			timeTrait4.duration = 10;
			timeTrait4.currentTime = 0;

			var mediaElement5:MediaElement = new DynamicMediaElement([MediaTraitType.TIME, MediaTraitType.SEEK], null, null, true);
			var timeTrait5:DynamicTimeTrait = mediaElement5.getTrait(MediaTraitType.TIME) as DynamicTimeTrait;
			var seekTrait5:DynamicSeekTrait = mediaElement5.getTrait(MediaTraitType.SEEK) as DynamicSeekTrait;
			timeTrait5.duration = 40;
			timeTrait5.currentTime = 0;
			
			serial.addChild(mediaElement1);
			serial.addChild(mediaElement2);
			serial.addChild(mediaElement3);
			serial.addChild(mediaElement4);
			serial.addChild(mediaElement5);
			
			var timeTrait:TimeTrait = serial.getTrait(MediaTraitType.TIME) as TimeTrait;
			var seekTrait:SeekTrait = serial.getTrait(MediaTraitType.SEEK) as SeekTrait;
			assertTrue(timeTrait != null);
			assertTrue(seekTrait != null);
			assertTrue(seekTrait.seeking == false);
			
			seekTrait.seek(50);
			timeTrait = serial.getTrait(MediaTraitType.TIME) as TimeTrait;
			seekTrait1.completeSeek(30);
			seekTrait3.completeSeek(5);
			assertTrue(timeTrait.currentTime == 50);
			
			// Based on current implementation of SerialElement, change of current child will
			// invalidate the current composite seekTrait and create a new one.
			seekTrait = serial.getTrait(MediaTraitType.SEEK) as SeekTrait;			

			seekTrait.seek(5);
			assertTrue(seekTrait3.seeking == true);
			seekTrait3.completeSeek(0);
			// Intermediate children will seek too.
			assertTrue(seekTrait2.seeking == true);
			assertTrue(seekTrait1.seeking == true);
			seekTrait1.completeSeek(5);
			timeTrait = serial.getTrait(MediaTraitType.TIME) as TimeTrait;
			seekTrait = serial.getTrait(MediaTraitType.SEEK) as SeekTrait;
			assertTrue(timeTrait.currentTime == 5);

			// Seek within the current child does not invalidate the current composite seekTrait.			
			seekTrait.seek(25);
			seekTrait1.completeSeek(25);
			assertTrue(timeTrait.currentTime == 25);

			seekTrait.seek(15);
			seekTrait1.completeSeek(15);
			assertTrue(timeTrait.currentTime == 15);
		}
		
		public function testSeekTraitSeekWhenUnseekable():void
		{
			var serial:SerialElement = new SerialElement();

			// No trait to begin with.
			assertTrue(serial.getTrait(MediaTraitType.TIME) == null);
			assertTrue(serial.getTrait(MediaTraitType.SEEK) == null);

			var mediaElement1:MediaElement = new DynamicMediaElement([MediaTraitType.TIME, MediaTraitType.SEEK], null, null, true);
			var timeTrait1:DynamicTimeTrait = mediaElement1.getTrait(MediaTraitType.TIME) as DynamicTimeTrait;
			var seekTrait1:DynamicSeekTrait = mediaElement1.getTrait(MediaTraitType.SEEK) as DynamicSeekTrait;
			timeTrait1.duration = 30;
			timeTrait1.currentTime = 0;

			var mediaElement2:MediaElement = new DynamicMediaElement([MediaTraitType.TIME], null, null, true);
			var timeTrait2:DynamicTimeTrait = mediaElement2.getTrait(MediaTraitType.TIME) as DynamicTimeTrait;
			timeTrait2.duration = 15;
			timeTrait2.currentTime = 0;

			var mediaElement3:MediaElement = new DynamicMediaElement([MediaTraitType.TIME, MediaTraitType.SEEK], null, null, true);
			var timeTrait3:DynamicTimeTrait = mediaElement3.getTrait(MediaTraitType.TIME) as DynamicTimeTrait;
			var seekTrait3:DynamicSeekTrait = mediaElement3.getTrait(MediaTraitType.SEEK) as DynamicSeekTrait;
			timeTrait3.duration = 20;
			timeTrait3.currentTime = 0;

			serial.addChild(mediaElement3);
			serial.addChildAt(mediaElement1, 0);
			serial.addChildAt(mediaElement2, 1);
			
			var timeTrait:TimeTrait = serial.getTrait(MediaTraitType.TIME) as TimeTrait;
			var seekTrait:SeekTrait = serial.getTrait(MediaTraitType.SEEK) as SeekTrait;
			assertTrue(timeTrait != null);
			assertTrue(seekTrait != null);
			assertTrue(seekTrait.seeking == false);
			
			assertTrue(seekTrait.canSeekTo(20) == true);
			assertTrue(seekTrait.canSeekTo(40) == true);
			
			// A SerialElement whose duration is NaN should not be seekable.
			var mediaElement4:MediaElement = new DynamicMediaElement([MediaTraitType.TIME, MediaTraitType.SEEK], null, null, true);
			var timeTrait4:DynamicTimeTrait = mediaElement4.getTrait(MediaTraitType.TIME) as DynamicTimeTrait;
			var seekTrait4:DynamicSeekTrait = mediaElement4.getTrait(MediaTraitType.SEEK) as DynamicSeekTrait;
			timeTrait4.duration = NaN;
			serial = new SerialElement();
			serial.addChild(mediaElement4);
			seekTrait = serial.getTrait(MediaTraitType.SEEK) as SeekTrait;
			assertTrue(seekTrait.canSeekTo(0) == false);
			assertTrue(seekTrait4.canSeekTo(0) == false);
			
			// A SerialElement where the first child is unseekable but the second
			// is seekable should be seekable.
			var mediaElement5:MediaElement = new DynamicMediaElement();
			var mediaElement6:MediaElement = new DynamicMediaElement([MediaTraitType.TIME, MediaTraitType.SEEK], null, null, true);
			var timeTrait6:DynamicTimeTrait = mediaElement6.getTrait(MediaTraitType.TIME) as DynamicTimeTrait;
			timeTrait6.duration = 10;
			serial = new SerialElement();
			serial.addChild(mediaElement6);
			serial.addChildAt(mediaElement5, 0);
			seekTrait = serial.getTrait(MediaTraitType.SEEK) as SeekTrait;
			assertTrue(seekTrait.canSeekTo(0) == true);
			assertTrue(seekTrait.canSeekTo(9) == true);
			assertTrue(seekTrait.canSeekTo(11) == false);
			
			// A SerialElement whose TimeTrait is added later should be seekable later.
			var mediaElement7:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.SEEK], null, null, true);
			assertTrue(mediaElement7.getTrait(MediaTraitType.SEEK) != null);
			serial = new SerialElement();
			serial.addChild(mediaElement7);
			seekTrait = serial.getTrait(MediaTraitType.SEEK) as SeekTrait;
			assertTrue(seekTrait != null);
			assertTrue(seekTrait.canSeekTo(0) == false);
			assertTrue(seekTrait.canSeekTo(9) == false);
			mediaElement7.doAddTrait(MediaTraitType.TIME, new TimeTrait(10));
			seekTrait = serial.getTrait(MediaTraitType.SEEK) as SeekTrait;
			assertTrue(seekTrait != null);
			assertTrue(seekTrait.canSeekTo(9) == true);
			assertTrue(seekTrait.canSeekTo(11) == false);
		}
				
		private function eventCatcher(event:Event):void
		{
			events.push(event);
		}

		private var events:Array;
	}
}