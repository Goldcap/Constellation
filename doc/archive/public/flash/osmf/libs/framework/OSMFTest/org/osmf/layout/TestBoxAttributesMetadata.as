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

package org.osmf.layout
{
	import org.osmf.events.MetadataEvent;
	import org.osmf.flexunit.TestCaseEx;
	import org.osmf.metadata.NullMetadataSynthesizer;

	public class TestBoxAttributesMetadata extends TestCaseEx
	{
		public function testBoxAttributesMetadata():void
		{
			var lastEvent:MetadataEvent;
			var eventCounter:int = 0;

			function onMetadataChange(event:MetadataEvent):void
			{
				lastEvent = event;
				eventCounter++;
			}
			
			var metadata:BoxAttributesMetadata = new BoxAttributesMetadata();
			metadata.addEventListener(MetadataEvent.VALUE_CHANGE, onMetadataChange);
						
			metadata.absoluteSum = 1;
			
			assertEquals(1, eventCounter);
			assertEquals(BoxAttributesMetadata.ABSOLUTE_SUM, lastEvent.key);
			assertEquals(0, lastEvent.oldValue);
			assertEquals(1, lastEvent.value);
			assertEquals(metadata.absoluteSum, metadata.getValue(BoxAttributesMetadata.ABSOLUTE_SUM), 1);
			
			metadata.absoluteSum = 1;
			assertEquals(metadata.absoluteSum, metadata.getValue(BoxAttributesMetadata.ABSOLUTE_SUM), 1);
			
			metadata.relativeSum = 2;
			
			assertEquals(2, eventCounter);
			assertEquals(BoxAttributesMetadata.RELATIVE_SUM, lastEvent.key);
			assertEquals(0, lastEvent.oldValue);
			assertEquals(2, lastEvent.value);
			assertEquals(metadata.relativeSum, metadata.getValue(BoxAttributesMetadata.RELATIVE_SUM), 2);
			
			metadata.relativeSum = 2;
			assertEquals(metadata.relativeSum, metadata.getValue(BoxAttributesMetadata.RELATIVE_SUM), 2);
			
			assertEquals(undefined, metadata.getValue(null));
			assertEquals(undefined, metadata.getValue("@*#$^98367423874"));
			
			assertTrue(metadata.synthesizer is NullMetadataSynthesizer);
		}
	}
}