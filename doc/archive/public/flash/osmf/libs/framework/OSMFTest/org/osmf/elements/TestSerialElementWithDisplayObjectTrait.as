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
	import flash.display.DisplayObjectContainer;
	import flash.display.Sprite;
	
	import flexunit.framework.TestCase;
	
	import org.osmf.containers.MediaContainer;
	import org.osmf.elements.compositeClasses.CompositeDisplayObjectTrait;
	import org.osmf.events.DisplayObjectEvent;
	import org.osmf.media.MediaElement;
	import org.osmf.traits.DisplayObjectTrait;
	import org.osmf.traits.MediaTraitType;
	import org.osmf.utils.DynamicDisplayObjectTrait;
	import org.osmf.utils.DynamicMediaElement;
	
	public class TestSerialElementWithDisplayObjectTrait extends TestCase
	{
		public function testDisplayObjectTraitDimensions():void
		{
			var serial:SerialElement = new SerialElement();
			
			var mediaElement1:MediaElement = new DynamicMediaElement([MediaTraitType.DISPLAY_OBJECT], null, null, true);
			var displayObjectTrait1:DynamicDisplayObjectTrait = mediaElement1.getTrait(MediaTraitType.DISPLAY_OBJECT) as DynamicDisplayObjectTrait;
			
			// No trait to begin with.
			assertFalse(serial.hasTrait(MediaTraitType.DISPLAY_OBJECT));
			
			serial.addChild(mediaElement1);
			
			assertTrue(serial.hasTrait(MediaTraitType.DISPLAY_OBJECT));
			var displayObjectTrait:DisplayObjectTrait = serial.getTrait(MediaTraitType.DISPLAY_OBJECT) as DisplayObjectTrait;
			assertNotNull(displayObjectTrait);
			
			displayObjectTrait.addEventListener(DisplayObjectEvent.MEDIA_SIZE_CHANGE, onMediaSizeChange);
			
			assertEquals(NaN, displayObjectTrait.mediaWidth);
			assertEquals(NaN, displayObjectTrait.mediaHeight);
			
			displayObjectTrait1.setSize(10, 100);
			
			// Force invalidation.
			CompositeDisplayObjectTrait(displayObjectTrait).layoutRenderer.validateNow();
			
			assertEquals(1, sizeChangeEventCount);

			assertEquals(10, displayObjectTrait.mediaWidth);
			assertEquals(100, displayObjectTrait.mediaHeight);
			
			var mediaElement2:MediaElement = new DynamicMediaElement([MediaTraitType.DISPLAY_OBJECT], null, null, true);
			var displayObjectTrait2:DynamicDisplayObjectTrait = mediaElement2.getTrait(MediaTraitType.DISPLAY_OBJECT) as DynamicDisplayObjectTrait;
			displayObjectTrait2.setSize(51, 52);
			
			serial.addChild(mediaElement2);
			assertEquals(displayObjectTrait, serial.getTrait(MediaTraitType.DISPLAY_OBJECT));
			
			serial.removeChild(mediaElement1);

			// Force invalidation.
			CompositeDisplayObjectTrait(displayObjectTrait).layoutRenderer.validateNow();
			
			assertNotNull(serial.getTrait(MediaTraitType.DISPLAY_OBJECT));
			assertEquals(2, sizeChangeEventCount);
			
			// Child removal got us a new trait:
			displayObjectTrait = serial.getTrait(MediaTraitType.DISPLAY_OBJECT) as DisplayObjectTrait;
			displayObjectTrait.addEventListener(DisplayObjectEvent.MEDIA_SIZE_CHANGE, onMediaSizeChange);
			
			assertEquals(51, displayObjectTrait.mediaWidth);
			assertEquals(52, displayObjectTrait.mediaHeight);
			
			serial.removeChild(mediaElement2);
			
			// No trait to end with.
			assertFalse(serial.hasTrait(MediaTraitType.DISPLAY_OBJECT));
			assertNull(serial.getTrait(MediaTraitType.DISPLAY_OBJECT));
		}
		
		public function testDisplayObjectTraitView():void
		{
			var serial:SerialElement = new SerialElement();
			
			var mediaElement1:MediaElement = new DynamicMediaElement([MediaTraitType.DISPLAY_OBJECT], null, null, true);
			var displayObjectTrait1:DynamicDisplayObjectTrait = mediaElement1.getTrait(MediaTraitType.DISPLAY_OBJECT) as DynamicDisplayObjectTrait;
			
			// No trait to begin with.
			assertFalse(serial.hasTrait(MediaTraitType.DISPLAY_OBJECT));
			
			serial.addChild(mediaElement1);
			
			assertTrue(serial.hasTrait(MediaTraitType.DISPLAY_OBJECT));
			var displayObjectTrait:DisplayObjectTrait = serial.getTrait(MediaTraitType.DISPLAY_OBJECT) as DisplayObjectTrait;
			assertNotNull(displayObjectTrait);
			
			displayObjectTrait.addEventListener(DisplayObjectEvent.DISPLAY_OBJECT_CHANGE, onDisplayObjectChanged);
			
			var view1:Sprite = new Sprite();
			displayObjectTrait1.displayObject = view1;
			
			// The view no longer changes: there's a fixed container sprite in between now:
			assertEquals(0, viewChangedEventCount);
			
			// The container should contain 'view1' though (once the layout has been updated!)
			CompositeDisplayObjectTrait(displayObjectTrait).layoutRenderer.validateNow();
			assertTrue(DisplayObjectContainer(displayObjectTrait.displayObject).contains(view1));
			
			var mediaElement2:MediaElement = new DynamicMediaElement([MediaTraitType.DISPLAY_OBJECT], null, null, true);
			var displayObjectTrait2:DynamicDisplayObjectTrait = mediaElement2.getTrait(MediaTraitType.DISPLAY_OBJECT) as DynamicDisplayObjectTrait;
			var view2:Sprite = new Sprite();
			displayObjectTrait2.displayObject = view2;
			
			serial.addChild(mediaElement2);
			serial.removeChild(mediaElement1);
			
			assertEquals(0, viewChangedEventCount);
			
			// The container should contain 'view2' now (once the layout has been updated!)
			CompositeDisplayObjectTrait(displayObjectTrait).layoutRenderer.validateNow();
			assertTrue(DisplayObjectContainer(displayObjectTrait.displayObject).contains(view2));
			assertFalse(DisplayObjectContainer(displayObjectTrait.displayObject).contains(view1));
				
			serial.removeChild(mediaElement2);
			
			// No trait to end with.
			assertFalse(serial.hasTrait(MediaTraitType.DISPLAY_OBJECT));
			
			// Add children once more:
			serial.addChild(mediaElement1);
			serial.addChild(mediaElement2);
			
			// Now move both children into another container:
			var container:MediaContainer = new MediaContainer();
			container.addMediaElement(mediaElement1);
			container.addMediaElement(mediaElement2);
			
			assertEquals(2, serial.numChildren);
		}
		
		private function onMediaSizeChange(event:DisplayObjectEvent):void
		{
			sizeChangeEventCount++;
		}
		
		private function onDisplayObjectChanged(event:DisplayObjectEvent):void
		{
			viewChangedEventCount++;
		}
		
		private var sizeChangeEventCount:int = 0;
		private var viewChangedEventCount:int = 0;
	}
}