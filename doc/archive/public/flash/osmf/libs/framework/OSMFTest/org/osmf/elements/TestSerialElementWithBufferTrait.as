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
	import org.osmf.utils.DynamicBufferTrait;
	import org.osmf.utils.DynamicMediaElement;
	
	public class TestSerialElementWithBufferTrait extends TestCase
	{
		public function testBufferTraitProperties():void
		{
			var serial:SerialElement = new SerialElement();
			
			// No trait to begin with.
			assertTrue(serial.getTrait(MediaTraitType.BUFFER) == null);

			var mediaElement1:MediaElement = new DynamicMediaElement([MediaTraitType.BUFFER], null, null, true);
			var bufferTrait1:DynamicBufferTrait = mediaElement1.getTrait(MediaTraitType.BUFFER) as DynamicBufferTrait;
			assertTrue(bufferTrait1 != null);

			var mediaElement2:MediaElement = new DynamicMediaElement([MediaTraitType.BUFFER], null, null, true);
			var bufferTrait2:DynamicBufferTrait = mediaElement2.getTrait(MediaTraitType.BUFFER) as DynamicBufferTrait;
			assertTrue(bufferTrait2 != null);
			
			// Set child bufferTrait properties
			bufferTrait1.bufferLength = 5;
			bufferTrait1.bufferTime = 10;
			bufferTrait1.buffering = true;

			bufferTrait2.bufferLength = 25;
			bufferTrait2.bufferTime = 20;
			bufferTrait2.buffering = false;
			
			serial.addChild(mediaElement1);
			serial.addChild(mediaElement2);
			
			var bufferTrait:BufferTrait = serial.getTrait(MediaTraitType.BUFFER) as BufferTrait;
			assertTrue(bufferTrait.bufferLength == bufferTrait1.bufferLength);
			assertTrue(bufferTrait.buffering == bufferTrait1.buffering);
			assertTrue(bufferTrait.bufferTime == bufferTrait1.bufferTime);
			
			bufferTrait.bufferTime = 50;
			assertTrue(bufferTrait.bufferTime == 50);
			assertTrue(bufferTrait1.bufferTime == bufferTrait.bufferTime);
			
			serial.removeChild(mediaElement1);
			assertTrue(bufferTrait.bufferLength == bufferTrait2.bufferLength);
			assertTrue(bufferTrait.buffering == bufferTrait2.buffering);
			assertTrue(bufferTrait.bufferTime == bufferTrait2.bufferTime);
		}

		public function testBufferTraitEvents():void
		{			
			events = new Vector.<Event>();
			
			var serial:SerialElement = new SerialElement();
			
			// No trait to begin with.
			assertTrue(serial.getTrait(MediaTraitType.BUFFER) == null);

			var mediaElement1:MediaElement = new DynamicMediaElement([MediaTraitType.BUFFER], null, null, true);
			var bufferTrait1:DynamicBufferTrait = mediaElement1.getTrait(MediaTraitType.BUFFER) as DynamicBufferTrait;
			assertTrue(bufferTrait1 != null);

			bufferTrait1.bufferLength = 5;
			bufferTrait1.bufferTime = 10;
			bufferTrait1.buffering = true;

			serial.addChild(mediaElement1);
			
			var bufferTrait:BufferTrait = serial.getTrait(MediaTraitType.BUFFER) as BufferTrait;
			bufferTrait.addEventListener(BufferEvent.BUFFERING_CHANGE, eventCatcher);
			bufferTrait.addEventListener(BufferEvent.BUFFER_TIME_CHANGE, eventCatcher);
			
			assertTrue(bufferTrait.bufferLength == bufferTrait1.bufferLength);
			assertTrue(bufferTrait.buffering == bufferTrait1.buffering);
			assertTrue(bufferTrait.bufferTime == bufferTrait1.bufferTime);
			
			bufferTrait.bufferTime = 20;
			assertTrue(events.length == 1);
			
			bufferTrait1.bufferTime = 30;
			assertTrue(events.length == 2);
			
			bufferTrait1.buffering = false;
			assertTrue(events.length == 3);

			bufferTrait1.buffering = false;
			assertTrue(events.length == 3);
		}
		
		private function eventCatcher(event:Event):void
		{
			events.push(event);
		}		
		private var events:Vector.<Event>;
	}
}