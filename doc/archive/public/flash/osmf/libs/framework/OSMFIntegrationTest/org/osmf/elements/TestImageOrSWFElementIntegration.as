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
	import flash.events.EventDispatcher;
	
	import org.osmf.events.LoadEvent;
	import org.osmf.media.MediaElement;
	import org.osmf.media.TestMediaElement;
	import org.osmf.traits.LoadState;
	import org.osmf.traits.LoadTrait;
	import org.osmf.traits.MediaTraitType;

	public class TestImageOrSWFElementIntegration extends TestMediaElement
	{
		override protected function get hasLoadTrait():Boolean
		{
			return true;
		}
		
		override protected function get existentTraitTypesOnInitialization():Array
		{
			return 	[ MediaTraitType.LOAD
					];
		}

		override protected function get existentTraitTypesAfterLoad():Array
		{
			return 	[ MediaTraitType.LOAD
					, MediaTraitType.DISPLAY_OBJECT
				   	];
		}
		
		/**
		 * Subclasses should override to specify the total number of bytes
		 * represented by resourceForMediaElement.
		 **/
		protected function get expectedBytesTotal():Number
		{
			return NaN;
		}
		
		public function testBytesLoaded():void
		{
			var eventDispatcher:EventDispatcher = new EventDispatcher();
			var count:int = 0;
			var bytesTotalFired:Boolean = false;
			
			var element:MediaElement = createMediaElement();
			element.resource = resourceForMediaElement;
			
			var loadTrait:LoadTrait = element.getTrait(MediaTraitType.LOAD) as LoadTrait;
			
			eventDispatcher.addEventListener(Event.COMPLETE, addAsync(mustReceiveEvent, 10000));
			
			loadTrait.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onLoadStateChange);
			loadTrait.load();
			
			function onLoadStateChange(event:LoadEvent):void
			{
				count++;
				if (count == 1)
				{
					assertEquals(LoadState.LOADING, event.loadState);
					assertEquals(NaN, loadTrait.bytesLoaded);
					assertEquals(NaN, loadTrait.bytesTotal);
					
					loadTrait.addEventListener(LoadEvent.BYTES_TOTAL_CHANGE, onBytesTotalChange);
				}
				else if (count == 2)
				{
					assertEquals(LoadState.READY, event.loadState);
					assertEquals(expectedBytesTotal, loadTrait.bytesLoaded);
					assertEquals(expectedBytesTotal, loadTrait.bytesTotal);
					
					assertTrue(bytesTotalFired);
					
					eventDispatcher.dispatchEvent(new Event(Event.COMPLETE));
				}
			}
			
			function onBytesTotalChange(event:LoadEvent):void
			{
				assertEquals(event.bytes, expectedBytesTotal);
				
				bytesTotalFired = true;
			}
		}
	}
}