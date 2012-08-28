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
	
	import org.osmf.layout.ScaleMode;
	import org.osmf.events.MetadataEvent;
	import org.osmf.metadata.NullMetadataSynthesizer;

	public class TestLayoutAttributesMetadata extends TestCase
	{
		public function testLayoutAttributesMetadata():void
		{
			var lastEvent:MetadataEvent;
			var eventCounter:int = 0;

			function onMetadataChange(event:MetadataEvent):void
			{
				lastEvent = event;
				eventCounter++;
			}
			
			var metadata:LayoutAttributesMetadata = new LayoutAttributesMetadata();
			metadata.addEventListener(MetadataEvent.VALUE_CHANGE, onMetadataChange);
			
			metadata.verticalAlign = VerticalAlign.BOTTOM;
			
			assertEquals(1, eventCounter);
			assertEquals(LayoutAttributesMetadata.VERTICAL_ALIGN, lastEvent.key);
			assertEquals(null, lastEvent.oldValue);
			assertEquals(VerticalAlign.BOTTOM, lastEvent.value);
			assertEquals(metadata.verticalAlign, metadata.getValue(LayoutAttributesMetadata.VERTICAL_ALIGN), VerticalAlign.BOTTOM);
			
			metadata.horizontalAlign = HorizontalAlign.RIGHT;
			
			assertEquals(2, eventCounter);
			assertEquals(LayoutAttributesMetadata.HORIZONTAL_ALIGN, lastEvent.key);
			assertEquals(null, lastEvent.oldValue);
			assertEquals(HorizontalAlign.RIGHT, lastEvent.value);
			assertEquals(metadata.horizontalAlign, metadata.getValue(LayoutAttributesMetadata.HORIZONTAL_ALIGN), HorizontalAlign.RIGHT);
			
			metadata.index = 2;
			
			assertEquals(3, eventCounter);
			assertEquals(LayoutAttributesMetadata.INDEX, lastEvent.key);
			assertEquals(NaN, lastEvent.oldValue);
			assertEquals(2, lastEvent.value);
			assertEquals(metadata.index, metadata.getValue(LayoutAttributesMetadata.INDEX), 2);
			
			metadata.snapToPixel = false;
			
			assertEquals(4, eventCounter);
			assertEquals(LayoutAttributesMetadata.SNAP_TO_PIXEL, lastEvent.key);
			assertEquals(true, lastEvent.oldValue);
			assertEquals(false, lastEvent.value);
			assertEquals(metadata.snapToPixel, metadata.getValue(LayoutAttributesMetadata.SNAP_TO_PIXEL), false);
			
			metadata.scaleMode = ScaleMode.LETTERBOX;
			
			assertEquals(5, eventCounter);
			assertEquals(LayoutAttributesMetadata.SCALE_MODE, lastEvent.key);
			assertEquals(null, lastEvent.oldValue);
			assertEquals(ScaleMode.LETTERBOX, lastEvent.value);
			assertEquals(metadata.scaleMode, metadata.getValue(LayoutAttributesMetadata.SCALE_MODE), ScaleMode.LETTERBOX);
			
			assertEquals(undefined, metadata.getValue(null));
			assertEquals(undefined, metadata.getValue("@*#$^98367423874"));
			
			assertTrue(metadata.synthesizer is NullMetadataSynthesizer);
		}
	}
}