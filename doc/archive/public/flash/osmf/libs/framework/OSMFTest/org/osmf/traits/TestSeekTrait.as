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
	import flash.events.EventDispatcher;
	
	import org.osmf.events.SeekEvent;
	import org.osmf.utils.InterfaceTestCase;

	public class TestSeekTrait extends InterfaceTestCase
	{
		/**
		 * Subclasses can override to specify their own trait class.
		 **/
		override protected function createInterfaceObject(... args):Object
		{
			return new SeekTrait(args.length > 0 ? args[0] : null);
		}
		
		override public function setUp():void
		{
			super.setUp();
			
			_seekTrait = createInterfaceObject(new TimeTrait(totalDuration)) as SeekTrait;
			eventDispatcher = new EventDispatcher();
			events = [];
		}

		protected function get maxSeekValue():Number
		{
			return 1;
		}

		protected function get totalDuration():Number
		{
			return 1;
		}
		
		protected function get processesSeekCompletion():Boolean
		{
			// Some implementations of ISeekable will signal completion 
			// of a seek, although the default implementation doesn't.
			// Subclasses can override this to indicate that they process
			// completion.
			return false;
		}
		
		public function testSeeking():void
		{
			assertFalse(seekTrait.seeking);
		}
		
		public function testCanSeekTo():void
		{
			assertFalse(seekTrait.canSeekTo(NaN));
			assertFalse(seekTrait.canSeekTo(-1));
			if (maxSeekValue > 0)
			{
				assertTrue(seekTrait.canSeekTo(maxSeekValue));
			}
			assertFalse(seekTrait.canSeekTo(maxSeekValue+1));
		}
		
		public function testSeek():void
		{
			if (maxSeekValue > 0)
			{
				eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, 5000));

				var eventCount:int = 0;

				seekTrait.addEventListener(SeekEvent.SEEKING_CHANGE, onTestSeek);
				assertTrue(seekTrait.canSeekTo(maxSeekValue));
				seekTrait.seek(maxSeekValue);
				
				function onTestSeek(event:SeekEvent):void
				{
					eventCount++;
					
					if (eventCount == 1)
					{
						assertTrue(event.seeking);
						assertTrue(event.time == maxSeekValue);
						if (processesSeekCompletion == false)
						{
							eventDispatcher.dispatchEvent(new Event("testComplete"));
						}
					}
					else if (eventCount == 2)
					{
						assertTrue(event.seeking == false);
						assertTrue(event.time == maxSeekValue);
						assertTrue(processesSeekCompletion == true);
						
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}
					else fail();
				}
			}
		}

		public function testSeekConsecutively():void
		{
			if (maxSeekValue > 0.1 && processesSeekCompletion)
			{
				eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, 5000));

				var beginEventCount:int = 0;
				var endEventCount:int = 0;

				seekTrait.addEventListener(SeekEvent.SEEKING_CHANGE, onTestSeekConsecutively);
				assertTrue(seekTrait.canSeekTo(maxSeekValue));
				
				// Consecutive seeks should result in either two BEGIN/END event
				// pairs, or a two BEGINs and one END.
				seekTrait.seek(maxSeekValue-0.1);
				seekTrait.seek(maxSeekValue);
				
				function onTestSeekConsecutively(event:SeekEvent):void
				{
					if (event.seeking)
					{
						beginEventCount++;
					}

					if (event.seeking == false)
					{
						endEventCount++;
					}
					
					if (beginEventCount == 2 && endEventCount > 0)
					{
						assertTrue(event.time == maxSeekValue);
						
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}
				}
			}
		}
		
		public function testSeekInvalid():void
		{
			if (maxSeekValue > 0)
			{
				seekTrait.addEventListener(SeekEvent.SEEKING_CHANGE, eventCatcher);
			
				assertFalse(seekTrait.canSeekTo(maxSeekValue + 1));
				
				seekTrait.seek(maxSeekValue + 1);
					
				// This should not cause any change events:
				assertTrue(events.length == 0);
			}
		}
		
		// Protected
		//
		
		protected function get seekTrait():SeekTrait
		{
			return _seekTrait;
		}
		
		// Internals
		//

		private function eventCatcher(event:Event):void
		{
			events.push(event);
		}

		private function mustReceiveEvent(event:Event):void
		{
			// Placeholder
		}

		private var _seekTrait:SeekTrait;
		private var events:Array;
		private var eventDispatcher:EventDispatcher;
	}
}