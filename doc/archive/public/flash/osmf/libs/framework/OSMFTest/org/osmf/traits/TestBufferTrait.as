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
package org.osmf.traits
{
	import __AS3__.vec.Vector;
	
	import flash.events.Event;
	
	import org.osmf.events.BufferEvent;
	import org.osmf.utils.InterfaceTestCase;

	public class TestBufferTrait extends InterfaceTestCase
	{
		/**
		 * Subclasses can override to specify their own trait class.
		 **/
		override protected function createInterfaceObject(... args):Object
		{
			return new BufferTrait();
		}
		
		override public function setUp():void
		{
			super.setUp();
			
			_bufferTrait = createInterfaceObject() as BufferTrait;
			events = [];
		}
		
		public function testBufferLength():void
		{
			assertTrue(bufferTrait.bufferLength == 0);
		}

		public function testBuffering():void
		{
			assertTrue(bufferTrait.buffering == false);
		}
		
		public function testBufferTime():void
		{
			bufferTrait.addEventListener(BufferEvent.BUFFER_TIME_CHANGE, eventCatcher);
			
			var oldTime:Number = bufferTrait.bufferTime;
			
			bufferTrait.bufferTime = 5;
			assertTrue(bufferTrait.bufferTime == 5);
			
			bufferTrait.bufferTime = 10;
			assertTrue(bufferTrait.bufferTime == 10);
			
			bufferTrait.bufferTime = -100;
			assertTrue(bufferTrait.bufferTime == 0);
			
			// Should not trigger an event.
			bufferTrait.bufferTime = 0;
			
			var bsce:BufferEvent;
			
			assertTrue(events.length == 3);
			
			bsce = events[0] as BufferEvent;
			assertNotNull(bsce);
			assertTrue(bsce.type == BufferEvent.BUFFER_TIME_CHANGE);
			assertTrue(bsce.bufferTime == 5);
			
			bsce = events[1] as BufferEvent;
			assertNotNull(bsce);
			assertTrue(bsce.type == BufferEvent.BUFFER_TIME_CHANGE);
			assertTrue(bsce.bufferTime == 10);
			
			bsce = events[2] as BufferEvent;
			assertNotNull(bsce);
			assertTrue(bsce.type == BufferEvent.BUFFER_TIME_CHANGE);
			assertTrue(bsce.bufferTime == 0);
		}
		
		// Internals
		//
		
		protected final function get bufferTrait():BufferTrait
		{
			return _bufferTrait;
		}

		protected function eventCatcher(event:Event):void
		{
			events.push(event);
		}
				
		protected var events:Array;

		private var _bufferTrait:BufferTrait;
	}
}