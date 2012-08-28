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
package org.osmf.layout
{
	import flexunit.framework.TestCase;
	
	import org.osmf.events.MetadataEvent;
	import org.osmf.metadata.NullMetadataSynthesizer;

	public class TestAnchorLayoutMetadata extends TestCase
	{
		public function testAnchorLayoutMetadata():void
		{
			var lastEvent:MetadataEvent;
			var eventCounter:int = 0;

			function onMetadataChange(event:MetadataEvent):void
			{
				lastEvent = event;
				eventCounter++;
			}
			
			var metadata:AnchorLayoutMetadata = new AnchorLayoutMetadata();
			metadata.addEventListener(MetadataEvent.VALUE_CHANGE, onMetadataChange);
			
			metadata.left = 1;
			
			assertEquals(1, eventCounter);
			assertEquals(AnchorLayoutMetadata.LEFT, lastEvent.key);
			assertEquals(NaN, lastEvent.oldValue);
			assertEquals(1, lastEvent.value);
			assertEquals(metadata.left, metadata.getValue(AnchorLayoutMetadata.LEFT), 1);
			
			metadata.top = 2;
			
			assertEquals(2, eventCounter);
			assertEquals(AnchorLayoutMetadata.TOP, lastEvent.key);
			assertEquals(NaN, lastEvent.oldValue);
			assertEquals(2, lastEvent.value);
			assertEquals(metadata.top, metadata.getValue(AnchorLayoutMetadata.TOP), 2);
			
			metadata.right = 3;
			
			assertEquals(3, eventCounter);
			assertEquals(AnchorLayoutMetadata.RIGHT, lastEvent.key);
			assertEquals(NaN, lastEvent.oldValue);
			assertEquals(3, lastEvent.value);
			assertEquals(metadata.right, metadata.getValue(AnchorLayoutMetadata.RIGHT), 3);
			
			metadata.bottom = 4;
			
			assertEquals(4, eventCounter);
			assertEquals(AnchorLayoutMetadata.BOTTOM, lastEvent.key);
			assertEquals(NaN, lastEvent.oldValue);
			assertEquals(4, lastEvent.value);
			assertEquals(metadata.bottom, metadata.getValue(AnchorLayoutMetadata.BOTTOM), 4);
			
			assertEquals(undefined, metadata.getValue(null));
			assertEquals(undefined, metadata.getValue("@*#$^98367423874"));
			
			assertTrue(metadata.synthesizer is NullMetadataSynthesizer);
		}
	}
}