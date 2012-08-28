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
	
	import org.osmf.events.BufferEvent;
	import org.osmf.media.MediaElement;
	import org.osmf.traits.BufferTrait;
	import org.osmf.traits.MediaTraitType;
	import org.osmf.utils.DynamicMediaElement;
	import org.osmf.utils.DynamicBufferTrait;
	
	public class TestParallelElementWithBufferTrait extends TestCase
	{
		public function testBufferTraitProperties():void
		{
			var parallel:ParallelElement = new ParallelElement();
			
			// No trait to begin with.
			assertTrue(parallel.getTrait(MediaTraitType.BUFFER) == null);

			var mediaElement1:MediaElement = new DynamicMediaElement([MediaTraitType.BUFFER], null, null, true);
			var bufferTrait1:DynamicBufferTrait = mediaElement1.getTrait(MediaTraitType.BUFFER) as DynamicBufferTrait;
			assertTrue(bufferTrait1 != null);

			var mediaElement2:MediaElement = new DynamicMediaElement([MediaTraitType.BUFFER], null, null, true);
			var bufferTrait2:DynamicBufferTrait = mediaElement2.getTrait(MediaTraitType.BUFFER) as DynamicBufferTrait;
			assertTrue(bufferTrait2 != null);
			
			var mediaElement3:MediaElement = new DynamicMediaElement([MediaTraitType.BUFFER], null, null, true);
			var bufferTrait3:DynamicBufferTrait = mediaElement3.getTrait(MediaTraitType.BUFFER) as DynamicBufferTrait;
			assertTrue(bufferTrait3 != null);
			
			// Set child bufferTrait properties
			bufferTrait1.bufferLength = 6;
			bufferTrait1.bufferTime = 12;
			bufferTrait1.buffering = true;

			bufferTrait2.bufferLength = 20;
			bufferTrait2.bufferTime = 15;
			bufferTrait2.buffering = true;
			
			bufferTrait3.bufferLength = 40;
			bufferTrait3.bufferTime = 18;
			bufferTrait3.buffering = true;

			parallel.addChild(mediaElement1);
			parallel.addChild(mediaElement2);
			parallel.addChild(mediaElement3);
			
			var bufferTrait:BufferTrait = parallel.getTrait(MediaTraitType.BUFFER) as BufferTrait;

			var avgBufferTime:Number = (bufferTrait1.bufferTime + bufferTrait2.bufferTime + bufferTrait3.bufferTime) / 3;
			assertTrue(bufferTrait.bufferTime == avgBufferTime);
			
			var curBufferLength:Number = (bufferTrait1.bufferLength + bufferTrait2.bufferTime + bufferTrait3.bufferTime) / 3;
			assertTrue(bufferTrait.bufferLength == curBufferLength);
			
			bufferTrait1.bufferLength = 15;
			curBufferLength = (bufferTrait1.bufferLength + bufferTrait2.bufferLength + bufferTrait3.bufferLength) / 3;
			assertTrue(bufferTrait.bufferLength == curBufferLength);
			
			bufferTrait1.buffering = false;
			assertTrue(bufferTrait.buffering == true);
			bufferTrait2.buffering = false;
			bufferTrait3.buffering = false;
			assertTrue(bufferTrait.buffering == false);
			
			bufferTrait.bufferTime = 80;
			assertTrue(bufferTrait1.bufferTime == bufferTrait.bufferTime);
			assertTrue(bufferTrait2.bufferTime == bufferTrait.bufferTime);
			assertTrue(bufferTrait3.bufferTime == bufferTrait.bufferTime);
		}

		public function testBufferTraitEvents():void
		{
			events = new Vector.<Event>();
			var parallel:ParallelElement = new ParallelElement();
			
			// No trait to begin with.
			assertTrue(parallel.getTrait(MediaTraitType.BUFFER) == null);

			var mediaElement1:MediaElement = new DynamicMediaElement([MediaTraitType.BUFFER], null, null, true);
			var bufferTrait1:DynamicBufferTrait = mediaElement1.getTrait(MediaTraitType.BUFFER) as DynamicBufferTrait;
			assertTrue(bufferTrait1 != null);

			var mediaElement2:MediaElement = new DynamicMediaElement([MediaTraitType.BUFFER], null, null, true);
			var bufferTrait2:DynamicBufferTrait = mediaElement2.getTrait(MediaTraitType.BUFFER) as DynamicBufferTrait;
			assertTrue(bufferTrait2 != null);

			bufferTrait1.bufferLength = 6;
			bufferTrait1.bufferTime = 12;
			bufferTrait1.buffering = true;

			bufferTrait2.bufferLength = 20;
			bufferTrait2.bufferTime = 15;
			bufferTrait2.buffering = true;

			parallel.addChild(mediaElement1);
			parallel.addChild(mediaElement2);

			var bufferTrait:BufferTrait = parallel.getTrait(MediaTraitType.BUFFER) as BufferTrait;
			bufferTrait.addEventListener(BufferEvent.BUFFERING_CHANGE, eventCatcher);
			bufferTrait.addEventListener(BufferEvent.BUFFER_TIME_CHANGE, eventCatcher);

			bufferTrait.bufferTime = 20;
			assertTrue(events.length == 1);
			
			bufferTrait.bufferTime = 20;
			assertTrue(events.length == 1);

			bufferTrait1.bufferTime = 30;
			assertTrue(events.length == 2);
			
			bufferTrait1.bufferTime = 30;
			assertTrue(events.length == 2);

			bufferTrait1.buffering = false;
			assertTrue(events.length == 2);

			bufferTrait2.buffering = false;
			assertTrue(events.length == 3);
		}

		private function eventCatcher(event:Event):void
		{
			events.push(event);
		}		
		private var events:Vector.<Event>;
	}
}