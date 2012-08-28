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

	public class TestSerialElementWithDVRTrait extends TestCaseEx
	{
		public function testSerialElementWithDVRTrait():void
		{
			var serial:SerialElement = new SerialElement();
			
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
			
			serial.addChild(child1);
			serial.addChild(child2);
			
			assertTrue(serial.hasTrait(MediaTraitType.DVR));
			var dvrS:DVRTrait = serial.getTrait(MediaTraitType.DVR) as DVRTrait;
			assertNotNull(dvrS);
			assertTrue(dvrS.isRecording);
			
			dvr1.isRecording = false;
			assertFalse(dvrS.isRecording);
			
			dvr1.isRecording = true;
			assertTrue(dvrS.isRecording);

			serial.removeChild(child1);
			assertTrue(dvrS.isRecording);
			
			dvr2.isRecording = false;
			assertFalse(dvrS.isRecording);
			
			serial.removeChild(child2);
			assertFalse(serial.hasTrait(MediaTraitType.DVR));
		}
		
	}
}