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
	import org.osmf.layout.LayoutMetadata;
	import org.osmf.layout.LayoutTargetSprite;
	import org.osmf.media.MediaElement;
	import org.osmf.traits.DisplayObjectTrait;
	import org.osmf.traits.MediaTraitType;
	import org.osmf.utils.DynamicDisplayObjectTrait;
	import org.osmf.utils.DynamicMediaElement;

	public class TestParallelElementWithDisplayObjectTrait extends TestCase
	{
		public function testDisplayObjectTraitDimensions():void
		{
			var parallel:ParallelElement = new ParallelElement();
			
			// No trait to begin with.
			assertFalse(parallel.hasTrait(MediaTraitType.DISPLAY_OBJECT));
			
			var mediaElement1:MediaElement = new DynamicMediaElement([MediaTraitType.DISPLAY_OBJECT], null, null, true);
			var displayObjectTrait1:DynamicDisplayObjectTrait = mediaElement1.getTrait(MediaTraitType.DISPLAY_OBJECT) as DynamicDisplayObjectTrait;
			
			parallel.addChild(mediaElement1);
			assertTrue(parallel.hasTrait(MediaTraitType.DISPLAY_OBJECT));
			
			var displayObjectTrait:DisplayObjectTrait = parallel.getTrait(MediaTraitType.DISPLAY_OBJECT) as DisplayObjectTrait;
			assertTrue(displayObjectTrait != null);
			assertEquals(NaN, displayObjectTrait.mediaWidth);
			assertEquals(NaN, displayObjectTrait.mediaHeight);
			assertEquals(0, displayObjectTrait1.mediaWidth);
			assertEquals(0, displayObjectTrait1.mediaHeight);
			
			
			displayObjectTrait.addEventListener(DisplayObjectEvent.MEDIA_SIZE_CHANGE, onMediaSizeChange);
			
			displayObjectTrait1.setSize(50, 50);
			
			LayoutTargetSprite(displayObjectTrait.displayObject).validateNow();
			
			assertTrue(sizeChangeEventCount == 1);
			assertEquals(50, displayObjectTrait.mediaWidth);
			assertEquals(50, displayObjectTrait.mediaHeight);
			
			var mediaElement2:MediaElement = new DynamicMediaElement([MediaTraitType.DISPLAY_OBJECT], null, null, true);
			var displayObjectTrait2:DynamicDisplayObjectTrait = mediaElement2.getTrait(MediaTraitType.DISPLAY_OBJECT) as DynamicDisplayObjectTrait;
			parallel.addChild(mediaElement2);
			
			assertEquals(50, displayObjectTrait.mediaWidth);
			assertEquals(50, displayObjectTrait.mediaHeight);
			
			displayObjectTrait2.setSize(100, 50);
			
			LayoutTargetSprite(displayObjectTrait.displayObject).validateNow();
			
			assertEquals(2, sizeChangeEventCount);
			
			assertEquals(100, displayObjectTrait.mediaWidth);
			assertEquals(50, displayObjectTrait.mediaHeight);
			
			displayObjectTrait2.setSize(100, 100);
			
			LayoutTargetSprite(displayObjectTrait.displayObject).validateNow();
			
			assertEquals(3, sizeChangeEventCount);
			
			assertEquals(displayObjectTrait.mediaWidth, 100);
			assertEquals(displayObjectTrait.mediaHeight, 100);
			
			var mediaElement3:MediaElement = new DynamicMediaElement([MediaTraitType.DISPLAY_OBJECT], null, null, true);
			var displayObjectTrait3:DynamicDisplayObjectTrait = mediaElement3.getTrait(MediaTraitType.DISPLAY_OBJECT) as DynamicDisplayObjectTrait;
			
			parallel.addChild(mediaElement3);
			
			assertTrue(parallel.hasTrait(MediaTraitType.DISPLAY_OBJECT));
			
			displayObjectTrait = parallel.getTrait(MediaTraitType.DISPLAY_OBJECT) as DisplayObjectTrait;
			assertNotNull(displayObjectTrait);
			
			LayoutTargetSprite(displayObjectTrait.displayObject).validateNow();
			
			assertEquals(3, sizeChangeEventCount);
			
			assertEquals(100, displayObjectTrait.mediaWidth);
			assertEquals(100, displayObjectTrait.mediaHeight);
			
			var view3:Sprite = new Sprite();
			view3.graphics.drawRect(0, 0, 600, 600);
			displayObjectTrait3.displayObject = view3;
			
			LayoutTargetSprite(displayObjectTrait.displayObject).validateNow();
			
			assertEquals(3, sizeChangeEventCount);
			
			// It is the set media width and height that counts: if a sprite's bounds
			// simply change, than that doesn't constitute for a mediaWidth and height
			// change too: the LayoutTarget should update that manually:
			assertEquals(100, displayObjectTrait.mediaWidth);
			assertEquals(100, displayObjectTrait.mediaHeight);
			
			parallel.removeChild(mediaElement3);
			parallel.removeChild(mediaElement2);
			parallel.removeChild(mediaElement1);
		
			// No trait to end with.
			assertFalse(parallel.hasTrait(MediaTraitType.DISPLAY_OBJECT));
		}
		
		public function testDisplayObjectTraitDisplayObject():void
		{
			var parallel:ParallelElement = new ParallelElement();
			
			// No trait to begin with.
			assertFalse(parallel.hasTrait(MediaTraitType.DISPLAY_OBJECT));
			
			var mediaElement1:MediaElement = new DynamicMediaElement([MediaTraitType.DISPLAY_OBJECT], null, null, true);
			var displayObjectTrait1:DynamicDisplayObjectTrait = mediaElement1.getTrait(MediaTraitType.DISPLAY_OBJECT) as DynamicDisplayObjectTrait;
			var view1:Sprite =  new Sprite();
			displayObjectTrait1.displayObject = view1;
			
			parallel.addChild(mediaElement1);
			
			assertTrue(parallel.hasTrait(MediaTraitType.DISPLAY_OBJECT));
			
			var displayObjectTrait:DisplayObjectTrait = parallel.getTrait(MediaTraitType.DISPLAY_OBJECT) as DisplayObjectTrait; 
			assertNotNull(displayObjectTrait);
			
			displayObjectTrait.addEventListener(DisplayObjectEvent.DISPLAY_OBJECT_CHANGE, onDisplayObjectChanged);
			var view:DisplayObjectContainer = displayObjectTrait.displayObject as DisplayObjectContainer;
			assertNotNull(view);
			
			// The display list is not updated until we validate. Force an update:
			CompositeDisplayObjectTrait(displayObjectTrait).layoutRenderer.validateNow();
			assertTrue(view.contains(displayObjectTrait1.displayObject));

			var mediaElement2:MediaElement = new DynamicMediaElement([MediaTraitType.DISPLAY_OBJECT], null, null, true);
			var displayObjectTrait2:DynamicDisplayObjectTrait = mediaElement2.getTrait(MediaTraitType.DISPLAY_OBJECT) as DynamicDisplayObjectTrait;
			var view2:Sprite =  new Sprite();
			displayObjectTrait2.displayObject = view2;
			
			parallel.addChild(mediaElement2);
			
			// The display list is not updated until we validate. Force an update:
			CompositeDisplayObjectTrait(displayObjectTrait).layoutRenderer.validateNow();
			assertTrue(view.contains(displayObjectTrait2.displayObject));
			
			// Create a second container, and add one of the child media-elements to it:
			var mc:MediaContainer = new MediaContainer();
			mc.addMediaElement(mediaElement2);
		}
		
		public function testDisplayObjectTraitLayout():void
		{
			var parallel:ParallelElement = new ParallelElement();
			var layout:LayoutMetadata = new LayoutMetadata();
			parallel.addMetadata(LayoutMetadata.LAYOUT_NAMESPACE, layout);
			layout.width = 300;
			layout.height = 200;
			
			var mediaElement1:MediaElement = new DynamicMediaElement([MediaTraitType.DISPLAY_OBJECT], null, null, true);
			layout = new LayoutMetadata();
			mediaElement1.addMetadata(LayoutMetadata.LAYOUT_NAMESPACE, layout);
			layout.x = 10;
			layout.y = 10;
			layout.bottom = 10;
			layout.right = 10;
			
			DynamicDisplayObjectTrait(mediaElement1.getTrait(MediaTraitType.DISPLAY_OBJECT)).displayObject = new Sprite();
			
			parallel.addChild(mediaElement1);
			
			var displayObjectTrait:DisplayObjectTrait = parallel.getTrait(MediaTraitType.DISPLAY_OBJECT) as DisplayObjectTrait;
				
			assertNotNull(displayObjectTrait);
		}
		
		private function onDisplayObjectChanged(event:DisplayObjectEvent):void
		{
			viewChangedEventCount++;
		}

		private function onMediaSizeChange(event:DisplayObjectEvent):void
		{
			sizeChangeEventCount++;
		}

		private var viewChangedEventCount:int = 0;
		private var sizeChangeEventCount:int = 0;
	}
}