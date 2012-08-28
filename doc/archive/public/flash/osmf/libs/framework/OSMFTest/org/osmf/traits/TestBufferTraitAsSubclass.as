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
	import org.osmf.events.BufferEvent;
	import org.osmf.utils.DynamicBufferTrait;

	public class TestBufferTraitAsSubclass extends TestBufferTrait
	{
		override protected function createInterfaceObject(... args):Object
		{
			return new DynamicBufferTrait();
		}
		
		override public function testBuffering():void
		{
			super.testBuffering();
			
			dynamicBufferTrait.addEventListener(BufferEvent.BUFFERING_CHANGE, eventCatcher);
			
			assertFalse(dynamicBufferTrait.buffering);
			
			dynamicBufferTrait.buffering = true;
			
			assertTrue(dynamicBufferTrait.buffering);
			
			dynamicBufferTrait.buffering = false;
			assertFalse(dynamicBufferTrait.buffering);
			
			// Should not trigger a change event:
			dynamicBufferTrait.buffering = false;
			
			assertTrue(events.length == 2);
			
			var bce:BufferEvent;
			
			bce = events[0] as BufferEvent;
			assertNotNull(bce);
			assertTrue(bce.buffering);
			
			bce = events[1] as BufferEvent;
			assertNotNull(bce);
			assertFalse(bce.buffering);
		}
		
		override public function testBufferLength():void
		{
			super.testBufferLength();
			
			dynamicBufferTrait.bufferLength = 10;

			assertTrue(dynamicBufferTrait.bufferLength == 10);
		}
		
		private function get dynamicBufferTrait():DynamicBufferTrait
		{
			return bufferTrait as DynamicBufferTrait;
		}
	}
}
