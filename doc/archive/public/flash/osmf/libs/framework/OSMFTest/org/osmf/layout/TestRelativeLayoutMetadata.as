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

	public class TestRelativeLayoutMetadata extends TestCase
	{
		public function testRelativeLayoutMetadata():void
		{
			var lastEvent:MetadataEvent;
			var eventCounter:int = 0;

			function onMetadataChange(event:MetadataEvent):void
			{
				lastEvent = event;
				eventCounter++;
			}
			
			var metadata:RelativeLayoutMetadata = new RelativeLayoutMetadata();
			metadata.addEventListener(MetadataEvent.VALUE_CHANGE, onMetadataChange);
			
			metadata.x = 1;
			
			assertEquals(1, eventCounter);
			assertEquals(RelativeLayoutMetadata.X, lastEvent.key);
			assertEquals(NaN, lastEvent.oldValue);
			assertEquals(1, lastEvent.value);
			assertEquals(metadata.x, metadata.getValue(RelativeLayoutMetadata.X), 1);
			
			metadata.y = 2;
			
			assertEquals(2, eventCounter);
			assertEquals(RelativeLayoutMetadata.Y, lastEvent.key);
			assertEquals(NaN, lastEvent.oldValue);
			assertEquals(2, lastEvent.value);
			assertEquals(metadata.y, metadata.getValue(RelativeLayoutMetadata.Y), 2);
			
			metadata.width = 3;
			
			assertEquals(3, eventCounter);
			assertEquals(RelativeLayoutMetadata.WIDTH, lastEvent.key);
			assertEquals(NaN, lastEvent.oldValue);
			assertEquals(3, lastEvent.value);
			assertEquals(metadata.width, metadata.getValue(RelativeLayoutMetadata.WIDTH), 3);
			
			metadata.height = 4;
			
			assertEquals(4, eventCounter);
			assertEquals(RelativeLayoutMetadata.HEIGHT, lastEvent.key);
			assertEquals(NaN, lastEvent.oldValue);
			assertEquals(4, lastEvent.value);
			assertEquals(metadata.height, metadata.getValue(RelativeLayoutMetadata.HEIGHT), 4);
			
			assertEquals(undefined, metadata.getValue(null));
			assertEquals(undefined, metadata.getValue("@*#$^98367423874"));
			
			assertTrue(metadata.synthesizer is NullMetadataSynthesizer);
		}
		
	}
}