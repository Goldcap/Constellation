/*****************************************************
*  
*  Copyright 2010 Adobe Systems Incorporated.  All Rights Reserved.
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
*  Portions created by Adobe Systems Incorporated are Copyright (C) 2010 Adobe Systems 
*  Incorporated. All Rights Reserved. 
*  
*****************************************************/

package org.osmf.elements
{
	import org.osmf.flexunit.TestCaseEx;
	import org.osmf.traits.DVRTrait;
	import org.osmf.traits.MediaTraitType;
	import org.osmf.utils.DynamicDVRTrait;
	import org.osmf.utils.DynamicMediaElement;

	public class TestParallelElementWithDVRTrait extends TestCaseEx
	{
		public function testParallelElementWithDVRTrait():void
		{
			var parallel:ParallelElement = new ParallelElement();
			
			var child1:DynamicMediaElement
				= new DynamicMediaElement
					( [MediaTraitType.DVR, MediaTraitType.TIME, MediaTraitType.PLAY], null, null, true
					);
					
			var dvr1:DynamicDVRTrait = child1.getTrait(MediaTraitType.DVR) as DynamicDVRTrait;
			dvr1.isRecording = true;
					
			var child2:DynamicMediaElement
				= new DynamicMediaElement
					( [MediaTraitType.DVR, MediaTraitType.TIME, MediaTraitType.PLAY], null, null, true
					);
					
			var dvr2:DynamicDVRTrait = child2.getTrait(MediaTraitType.DVR) as DynamicDVRTrait;
			dvr2.isRecording = true;
			
			parallel.addChild(child1);
			parallel.addChild(child2);
			
			assertTrue(parallel.hasTrait(MediaTraitType.DVR));
			var dvrS:DVRTrait = parallel.getTrait(MediaTraitType.DVR) as DVRTrait;
			assertNotNull(dvrS);
			assertTrue(dvrS.isRecording);
			
			dvr1.isRecording = false;
			assertTrue(dvrS.isRecording);
			
			dvr2.isRecording = false;
			assertFalse(dvrS.isRecording);
			
			dvr1.isRecording = true;
			assertTrue(dvrS.isRecording);
			
			parallel.removeChild(child1);
			assertFalse(dvrS.isRecording);
			
			parallel.removeChild(child2);
			assertFalse(parallel.hasTrait(MediaTraitType.DVR));
		}
		
	}
}