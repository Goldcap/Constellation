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
	
	import org.osmf.events.TimeEvent;
	import org.osmf.media.MediaElement;
	import org.osmf.traits.MediaTraitType;
	import org.osmf.traits.TimeTrait;
	import org.osmf.utils.DynamicMediaElement;
	import org.osmf.utils.DynamicTimeTrait;
	
	public class TestParallelElementWithTimeTrait extends TestCase
	{
		public function testTimeTrait():void
		{
			var parallel:ParallelElement = new ParallelElement();
			
			// No trait to begin with.
			assertTrue(parallel.getTrait(MediaTraitType.TIME) == null);
			
			// Create a few media elements with the TimeTrait and some
			// initial properties.
			//
			
			var mediaElement1:MediaElement = new DynamicMediaElement([MediaTraitType.TIME], null, null, true);
			var timeTrait1:DynamicTimeTrait = mediaElement1.getTrait(MediaTraitType.TIME) as DynamicTimeTrait;
			timeTrait1.duration = 10;
			timeTrait1.currentTime = 5;

			var mediaElement2:MediaElement = new DynamicMediaElement([MediaTraitType.TIME], null, null, true);
			var timeTrait2:DynamicTimeTrait = mediaElement2.getTrait(MediaTraitType.TIME) as DynamicTimeTrait;
			timeTrait2.duration = 30;
			timeTrait2.currentTime = 10;

			var mediaElement3:MediaElement = new DynamicMediaElement([MediaTraitType.TIME], null, null, true);
			var timeTrait3:DynamicTimeTrait = mediaElement3.getTrait(MediaTraitType.TIME) as DynamicTimeTrait;
			timeTrait3.duration = 20;
			timeTrait3.currentTime = 15;
			
			// Add the children, this should cause the properties to propagate
			// to the composition.
			parallel.addChild(mediaElement1);
			var timeTrait:TimeTrait = parallel.getTrait(MediaTraitType.TIME) as TimeTrait;
			assertTrue(timeTrait != null);
			assertTrue(timeTrait.duration == 10);
			assertTrue(timeTrait.currentTime == 5);
			assertTrue(timeTrait1.currentTime == 5);
			
			timeTrait.addEventListener(TimeEvent.DURATION_CHANGE, onDurationChanged);
			
			// The currentTime is the max of the children of the composition.
			parallel.addChild(mediaElement2);
			assertTrue(timeTrait.duration == 30);
			assertTrue(timeTrait.currentTime == 10);
			assertTrue(timeTrait1.currentTime == 5);
			assertTrue(timeTrait2.currentTime == 10);
			assertTrue(durationChangedEventCount == 1);
			
			parallel.addChild(mediaElement3);
			assertTrue(timeTrait.duration == 30);
			assertTrue(timeTrait.currentTime == 15);
			assertTrue(timeTrait1.currentTime == 5);
			assertTrue(timeTrait2.currentTime == 10);
			assertTrue(timeTrait3.currentTime == 15);
			assertTrue(durationChangedEventCount == 1);
			
			// Changing the duration of a child should affect the duration of
			// the composition. 
			timeTrait1.duration = 25;
			assertTrue(timeTrait.duration == 30);
			assertTrue(timeTrait.currentTime == 15);
			assertTrue(durationChangedEventCount == 1);
			timeTrait1.duration = 35;
			assertTrue(timeTrait.duration == 35);
			assertTrue(timeTrait.currentTime == 15);
			assertTrue(durationChangedEventCount == 2);
			
			// Changing the duration below the currentTime should cause the
			// currentTime to change too.
			timeTrait1.duration = 3;
			assertTrue(timeTrait.duration == 30);
			assertTrue(timeTrait.currentTime == 15);
			assertTrue(durationChangedEventCount == 3);
			
			timeTrait1.duration = 10;
			timeTrait1.currentTime = 5;
			
			// The composite trait dispatches the complete event
			// when every child has reached its duration.
			//
			
			assertTrue(timeTrait.currentTime == 15);
			assertTrue(timeTrait1.currentTime == 5);
			assertTrue(timeTrait2.currentTime == 10);
			assertTrue(timeTrait3.currentTime == 15);
			assertTrue(durationChangedEventCount == 3);
			
			timeTrait.addEventListener(TimeEvent.COMPLETE, onComplete);
			
			timeTrait1.currentTime = 10;
			assertTrue(completeEventCount == 0);
			
			timeTrait2.currentTime = 25;
			assertTrue(completeEventCount == 0);
			
			timeTrait3.currentTime = 20;
			assertTrue(completeEventCount == 0);

			timeTrait2.currentTime = 30;
			assertTrue(completeEventCount == 1);
			
			// If two children have the same (max) duration, then we should
			// only get one event (when both have reached their duration).

			timeTrait2.currentTime = 25;
			timeTrait3.currentTime = 15;
			timeTrait3.duration = 30;
			
			timeTrait2.currentTime = 30;
			assertTrue(completeEventCount == 1);
			
			timeTrait3.currentTime = 30;
			assertTrue(completeEventCount == 2);
			
			timeTrait1.currentTime = 5;
			timeTrait2.currentTime = 10;
			timeTrait3.currentTime = 15;
			timeTrait3.duration = 20;
			
			// Removing a child may affect duration and currentTime.
			parallel.removeChild(mediaElement2);
			assertTrue(timeTrait.duration == 20);
			assertTrue(timeTrait.currentTime == 15);

			parallel.removeChild(mediaElement3);
			assertTrue(timeTrait.duration == 10);
			assertTrue(timeTrait.currentTime == 5);
		}
		
		private function onDurationChanged(event:TimeEvent):void
		{
			durationChangedEventCount++;
		}
		
		private function onComplete(event:TimeEvent):void
		{
			completeEventCount++;
		}
		
		private var durationChangedEventCount:int = 0;
		private var completeEventCount:int = 0;
	}
}