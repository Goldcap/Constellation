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
	import flash.events.Event;
	
	import org.osmf.events.LoadEvent;
	import org.osmf.utils.DynamicLoadTrait;

	public class TestLoadTraitAsSubclass extends TestLoadTrait
	{
		override protected function createInterfaceObject(... args):Object
		{
			return new DynamicLoadTrait(args.length > 0 ? args[0] : null, args.length > 1 ? args[1] : null);
		}
		
		override public function testGetBytesLoaded():void
		{
			var loadTrait:DynamicLoadTrait = createLoadTrait() as DynamicLoadTrait;
			loadTrait.addEventListener(LoadEvent.BYTES_LOADED_CHANGE, mustNotReceiveEvent);
			assertTrue(isNaN(loadTrait.bytesLoaded));
			
			// Do some error checks first.
			//
			
			try
			{
				// Negative values are illegal.
				loadTrait.bytesLoaded = -30;
				
				fail();
			}
			catch (error:ArgumentError)
			{
			}
			
			try
			{
				// NaN is illegal.
				loadTrait.bytesLoaded = NaN;
				
				fail();
			}
			catch (error:ArgumentError)
			{
			}
			
			loadTrait.bytesTotal = 29;
			try
			{
				// Setting a value that's greater than bytesTotal is illegal. 
				loadTrait.bytesLoaded = 30;
				
				fail();
			}
			catch (error:ArgumentError)
			{
			}
			
			assertTrue(isNaN(loadTrait.bytesLoaded));
			
			// Now verify it worked.
			loadTrait.bytesLoaded = 29;
			assertTrue(loadTrait.bytesLoaded == 29);
		}
		
		override public function testGetBytesTotal():void
		{
			var loadTrait:DynamicLoadTrait = createLoadTrait() as DynamicLoadTrait;
			loadTrait.addEventListener(LoadEvent.BYTES_TOTAL_CHANGE, addAsync(onTestGetBytesTotal, 1000));
			assertTrue(isNaN(loadTrait.bytesTotal));
			
			loadTrait.bytesTotal = 60;
			assertTrue(loadTrait.bytesTotal == 60);
		
			function onTestGetBytesTotal(event:LoadEvent):void
			{
				assertTrue(event.type == LoadEvent.BYTES_TOTAL_CHANGE);
				assertTrue(event.bytes == 60);
				
				// Now we'll end with some error checks.
				//
				
				try
				{
					// Negative values are illegal.
					loadTrait.bytesTotal = -10;
					
					fail();
				}
				catch (error:ArgumentError)
				{
				}

				loadTrait.bytesLoaded = 20;
				try
				{
					// Values less than bytesLoaded are illegal.
					loadTrait.bytesTotal = 19;
					
					fail();
				}
				catch (error:ArgumentError)
				{
				}
			}
		}
		
		private function mustNotReceiveEvent(event:Event):void
		{
			fail();
		}
	}
}
