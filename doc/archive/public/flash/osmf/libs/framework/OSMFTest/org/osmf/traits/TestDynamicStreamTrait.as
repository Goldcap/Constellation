/*****************************************************
*  
*  Copyright 2009 Akamai Technologies, Inc.  All Rights Reserved.
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
*  The Initial Developer of the Original Code is Akamai Technologies, Inc.
*  Portions created by Akamai Technologies, Inc. are Copyright (C) 2009 Akamai 
*  Technologies, Inc. All Rights Reserved. 
*  
*****************************************************/
package org.osmf.traits
{
	import flash.errors.*;
	import flash.events.*;
	
	import org.osmf.events.DynamicStreamEvent;
	import org.osmf.utils.InterfaceTestCase;
	
	public class TestDynamicStreamTrait extends InterfaceTestCase
	{
		/**
		 * Subclasses can override to specify their own trait class.
		 **/
		override protected function createInterfaceObject(... args):Object
		{
			return new DynamicStreamTrait(args.length > 0 ? args[0] : true, args.length > 1 ? args[1] : 0, args.length > 2 ? args[2] : 1);
		}
		
		protected function get processesSwitchCompletion():Boolean
		{
			// Some implementations of DynamicStreamTrait will signal completion 
			// of a switch, although the default implementation doesn't.
			// Subclasses can override this to indicate that they process
			// completion.
			return false;
		}

		protected function get dynamicStreamTrait():DynamicStreamTrait
		{
			return _dynamicStreamTrait;
		}
		
		override public function setUp():void
		{
			super.setUp();
			
			_dynamicStreamTrait = createInterfaceObject(true, 0, 4) as DynamicStreamTrait;
			eventDispatcher = new EventDispatcher();
		}

		public function testSwitchUp():void
		{
			assertFalse(dynamicStreamTrait.switching);
			assertTrue(dynamicStreamTrait.autoSwitch);
			
			dynamicStreamTrait.addEventListener(DynamicStreamEvent.SWITCHING_CHANGE, onSwitchingChange);
			
			dynamicStreamTrait.autoSwitch = false;
			dynamicStreamTrait.switchTo(0);
			
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, TIMEOUT));
			
			dynamicStreamTrait.switchTo(dynamicStreamTrait.currentIndex + 1);
		}
		
		public function testSwitchDown():void
		{
			assertFalse(dynamicStreamTrait.switching);
			assertTrue(dynamicStreamTrait.autoSwitch);
			
			dynamicStreamTrait.addEventListener(DynamicStreamEvent.SWITCHING_CHANGE, onSwitchingChange);
			dynamicStreamTrait.autoSwitch = false;
			
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, TIMEOUT));
			dynamicStreamTrait.switchTo(dynamicStreamTrait.maxAllowedIndex);
			dynamicStreamTrait.switchTo(0);
		}
		
		public function testSwitchUpMax():void
		{
			assertFalse(dynamicStreamTrait.switching);
			assertTrue(dynamicStreamTrait.autoSwitch);
			
			dynamicStreamTrait.addEventListener(DynamicStreamEvent.SWITCHING_CHANGE, onSwitchingChange);
			dynamicStreamTrait.autoSwitch = false;
			
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, TIMEOUT));			
			dynamicStreamTrait.switchTo(dynamicStreamTrait.maxAllowedIndex);		
		}
		
		public function testBadMaxAllowedIndex():void
		{
			try
			{
				dynamicStreamTrait.maxAllowedIndex = 99;
				
				fail();
			}
			catch (e:RangeError)
			{
				return;
			}
		}
		
		public function testBadSwitch1():void
		{
			assertTrue(dynamicStreamTrait.autoSwitch);
			try
			{
				dynamicStreamTrait.switchTo(1);
				
				fail();
			}
			catch(e:IllegalOperationError)
			{
			}
		}
				
		public function testBadSwitch2():void
		{
			assertTrue(dynamicStreamTrait.autoSwitch);
			dynamicStreamTrait.autoSwitch = false;

			try
			{
				dynamicStreamTrait.switchTo(-1);
				
				fail();
			}
			catch(e:RangeError)
			{
			}
		}

		public function testBadSwitch3():void
		{
			assertTrue(dynamicStreamTrait.autoSwitch);
			dynamicStreamTrait.autoSwitch = false;

			try
			{
				dynamicStreamTrait.switchTo(99);
				
				fail();
			}
			catch(e:RangeError)
			{
			}
		}
		
		public function testGetBitrateForIndex():void
		{				
			assertEquals(0, dynamicStreamTrait.getBitrateForIndex(0));
			
			try
			{
				dynamicStreamTrait.getBitrateForIndex(-1);
				
				fail();
			}
			catch (error:RangeError)
			{
			}
		}

		protected function onSwitchingChange(event:DynamicStreamEvent):void
		{
			if (event.switching)
			{
				assertTrue(dynamicStreamTrait.switching);
				
				if (processesSwitchCompletion == false)
				{
					dynamicStreamTrait.removeEventListener(DynamicStreamEvent.SWITCHING_CHANGE, onSwitchingChange);
					eventDispatcher.dispatchEvent(new Event("testComplete"));	
				}
			}
			else
			{
				assertFalse(dynamicStreamTrait.switching);
					
				dynamicStreamTrait.removeEventListener(DynamicStreamEvent.SWITCHING_CHANGE, onSwitchingChange);
				eventDispatcher.dispatchEvent(new Event("testComplete"));									
			}
		}
		
		private function mustReceiveEvent(event:Event):void
		{
			// Placeholder to ensure an event is received.
		}
				
		private static const TIMEOUT:int = 4000;
		
		private var _dynamicStreamTrait:DynamicStreamTrait;
		private var eventDispatcher:EventDispatcher;
	}
}
