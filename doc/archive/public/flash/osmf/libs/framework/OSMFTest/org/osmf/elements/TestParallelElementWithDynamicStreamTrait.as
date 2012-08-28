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
	
	import org.osmf.events.DynamicStreamEvent;
	import org.osmf.media.MediaElement;
	import org.osmf.traits.DynamicStreamTrait;
	import org.osmf.traits.MediaTraitType;
	import org.osmf.utils.DynamicDynamicStreamTrait;
	import org.osmf.utils.DynamicMediaElement;
	
	public class TestParallelElementWithDynamicStreamTrait extends TestCase
	{
		public function testDynamicStreamTrait():void
		{
			var parallel:ParallelElement = new ParallelElement();
			
			// No trait to begin with.
			assertTrue(parallel.getTrait(MediaTraitType.DYNAMIC_STREAM) == null);
			
			// Create a few media elements with the DynamicStreamTrait and some
			// initial properties.
			//
			
			var mediaElement1:MediaElement = new DynamicMediaElement([MediaTraitType.DYNAMIC_STREAM], null, null, true);
			var dsTrait1:DynamicDynamicStreamTrait = mediaElement1.getTrait(MediaTraitType.DYNAMIC_STREAM) as DynamicDynamicStreamTrait;
			dsTrait1.autoSwitch = false;
			dsTrait1.currentIndex = 0;
			dsTrait1.bitrates = [100, 300, 500, 700, 900];
			dsTrait1.maxAllowedIndex = 4;

			var mediaElement2:MediaElement = new DynamicMediaElement([MediaTraitType.DYNAMIC_STREAM], null, null, true);
			var dsTrait2:DynamicDynamicStreamTrait = mediaElement2.getTrait(MediaTraitType.DYNAMIC_STREAM) as DynamicDynamicStreamTrait;
			dsTrait2.autoSwitch = false;
			dsTrait2.currentIndex = 2;
			dsTrait2.bitrates = [100, 200, 300, 400];
			dsTrait2.maxAllowedIndex = 3;

			var mediaElement3:MediaElement = new DynamicMediaElement([MediaTraitType.DYNAMIC_STREAM], null, null, true);
			var dsTrait3:DynamicDynamicStreamTrait = mediaElement3.getTrait(MediaTraitType.DYNAMIC_STREAM) as DynamicDynamicStreamTrait;
			dsTrait3.autoSwitch = true;
			dsTrait3.currentIndex = 0;
			dsTrait3.bitrates = [200, 400, 600];
			dsTrait3.maxAllowedIndex = 2;
			
			// Add the first child, this should cause the properties to propagate
			// to the composition.
			parallel.addChild(mediaElement1);
			var dsTrait:DynamicStreamTrait = parallel.getTrait(MediaTraitType.DYNAMIC_STREAM) as DynamicStreamTrait;
			assertTrue(dsTrait != null);
			assertTrue(dsTrait.autoSwitch == false);
			assertTrue(dsTrait.currentIndex == 0);
			assertTrue(dsTrait.numDynamicStreams == 5);
			assertTrue(dsTrait.maxAllowedIndex == 4);
			assertTrue(dsTrait.getBitrateForIndex(0) == 100);
			assertTrue(dsTrait.getBitrateForIndex(1) == 300);
			assertTrue(dsTrait.getBitrateForIndex(2) == 500);
			assertTrue(dsTrait.getBitrateForIndex(3) == 700);
			assertTrue(dsTrait.getBitrateForIndex(4) == 900);

			// Add the second child.
			parallel.addChild(mediaElement2);
			dsTrait = parallel.getTrait(MediaTraitType.DYNAMIC_STREAM) as DynamicStreamTrait;
			assertTrue(dsTrait != null);
			assertTrue(dsTrait.autoSwitch == false);
			assertTrue(dsTrait.currentIndex == 0);
			assertTrue(dsTrait.numDynamicStreams == 7);
			assertTrue(dsTrait.maxAllowedIndex == 6);
			assertTrue(dsTrait.getBitrateForIndex(0) == 100);
			assertTrue(dsTrait.getBitrateForIndex(1) == 200);
			assertTrue(dsTrait.getBitrateForIndex(2) == 300);
			assertTrue(dsTrait.getBitrateForIndex(3) == 400);
			assertTrue(dsTrait.getBitrateForIndex(4) == 500);
			assertTrue(dsTrait.getBitrateForIndex(5) == 700);
			assertTrue(dsTrait.getBitrateForIndex(6) == 900);
			
			// Add the third child, whose autoSwitch property differs.
			parallel.addChild(mediaElement3);
			dsTrait = parallel.getTrait(MediaTraitType.DYNAMIC_STREAM) as DynamicStreamTrait;
			assertTrue(dsTrait != null);
			assertTrue(dsTrait.autoSwitch == false);
			assertTrue(dsTrait.currentIndex == 0);
			assertTrue(dsTrait.numDynamicStreams == 8);
			assertTrue(dsTrait.maxAllowedIndex == 7);
			assertTrue(dsTrait.getBitrateForIndex(0) == 100);
			assertTrue(dsTrait.getBitrateForIndex(1) == 200);
			assertTrue(dsTrait.getBitrateForIndex(2) == 300);
			assertTrue(dsTrait.getBitrateForIndex(3) == 400);
			assertTrue(dsTrait.getBitrateForIndex(4) == 500);
			assertTrue(dsTrait.getBitrateForIndex(5) == 600);
			assertTrue(dsTrait.getBitrateForIndex(6) == 700);
			assertTrue(dsTrait.getBitrateForIndex(7) == 900);
			
			// The child's autoSwitch property should have been changed to match
			// that of the composite trait.
			assertTrue(dsTrait3.autoSwitch == false);
			
			dsTrait.addEventListener(DynamicStreamEvent.AUTO_SWITCH_CHANGE, onAutoSwitchChange);
			
			// Changing the autoSwitch property on the composite trait
			// should affect all children.
			dsTrait.autoSwitch = true;
			assertTrue(dsTrait.autoSwitch == true);
			assertTrue(dsTrait1.autoSwitch == true);
			assertTrue(dsTrait2.autoSwitch == true);
			assertTrue(dsTrait3.autoSwitch == true);
			assertTrue(autoSwitchChangeEventCount == 1);
			
			// Changing the autoSwitch property on a child should affect
			// the composite trait and all children.
			dsTrait3.autoSwitch = false;
			assertTrue(dsTrait.autoSwitch == false);
			assertTrue(dsTrait1.autoSwitch == false);
			assertTrue(dsTrait2.autoSwitch == false);
			assertTrue(dsTrait3.autoSwitch == false);
			assertTrue(autoSwitchChangeEventCount == 2);
			
			dsTrait.addEventListener(DynamicStreamEvent.SWITCHING_CHANGE, onSwitchingChange);
			
			// Switching on the composite trait should affect all children
			// based on the relation between the bitrate of the selected
			// index and the closest bitrate index for each child.
			dsTrait.switchTo(4);
			assertTrue(dsTrait.currentIndex == 4);		// 500
			assertTrue(dsTrait1.currentIndex == 2); 	// 500
			assertTrue(dsTrait2.currentIndex == 3); 	// 400
			assertTrue(dsTrait3.currentIndex == 1);		// 500
			assertTrue(switchingChangeEventCount == 2);
			
			dsTrait.switchTo(7);
			assertTrue(dsTrait.currentIndex == 7);		// 900
			assertTrue(dsTrait1.currentIndex == 4); 	// 400
			assertTrue(dsTrait2.currentIndex == 3); 	// 600
			assertTrue(dsTrait3.currentIndex == 2); 	// 600
			assertTrue(switchingChangeEventCount == 4);
			
			dsTrait.addEventListener(DynamicStreamEvent.NUM_DYNAMIC_STREAMS_CHANGE, onNumDynamicStreamsChange);
			
			// Removing children should affect the set of streams, but only
			// if there are unique streams among the removed ones.
			parallel.removeChild(mediaElement2);
			assertTrue(dsTrait.numDynamicStreams == 8);
			assertTrue(dsTrait.maxAllowedIndex == 7);
			assertTrue(dsTrait.getBitrateForIndex(0) == 100);
			assertTrue(dsTrait.getBitrateForIndex(1) == 200);
			assertTrue(dsTrait.getBitrateForIndex(2) == 300);
			assertTrue(dsTrait.getBitrateForIndex(3) == 400);
			assertTrue(dsTrait.getBitrateForIndex(4) == 500);
			assertTrue(dsTrait.getBitrateForIndex(5) == 600);
			assertTrue(dsTrait.getBitrateForIndex(6) == 700);
			assertTrue(dsTrait.getBitrateForIndex(7) == 900);
			assertTrue(numDynamicStreamsChangeEventCount == 0);
			
			parallel.removeChild(mediaElement1);
			assertTrue(dsTrait.numDynamicStreams == 3);
			assertTrue(dsTrait.maxAllowedIndex == 2);
			assertTrue(dsTrait.getBitrateForIndex(0) == 200);
			assertTrue(dsTrait.getBitrateForIndex(1) == 400);
			assertTrue(dsTrait.getBitrateForIndex(2) == 600);
			assertTrue(numDynamicStreamsChangeEventCount == 1);			
		}
		
		private function onSwitchingChange(event:DynamicStreamEvent):void
		{
			switchingChangeEventCount++;
		}

		private function onAutoSwitchChange(event:DynamicStreamEvent):void
		{
			autoSwitchChangeEventCount++;
		}
		
		private function onNumDynamicStreamsChange(event:DynamicStreamEvent):void
		{
			numDynamicStreamsChangeEventCount++;
		}
		
		private var switchingChangeEventCount:int;
		private var autoSwitchChangeEventCount:int;
		private var numDynamicStreamsChangeEventCount:int;
	}
}