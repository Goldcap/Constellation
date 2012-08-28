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
	import flash.events.Event;
	
	import org.osmf.events.MediaElementEvent;
	import org.osmf.events.MediaError;
	import org.osmf.events.MediaErrorEvent;
	import org.osmf.media.MediaElement;
	import org.osmf.media.MediaResourceBase;
	import org.osmf.media.TestMediaElement;
	import org.osmf.media.URLResource;
	import org.osmf.traits.LoadState;
	import org.osmf.traits.LoadTrait;
	import org.osmf.traits.MediaTraitType;
	import org.osmf.utils.DynamicMediaElement;
	import org.osmf.utils.SimpleLoader;
	
	public class TestCompositeElement extends TestMediaElement
	{
		// Overrides
		//
		
		override public function setUp():void
		{
			super.setUp();
			
			traitAddEventCount = 0;
			traitRemoveEventCount = 0;
			forceLoadTrait = false;
		}

		override protected function createMediaElement():MediaElement
		{
			var composite:CompositeElement = new CompositeElement();
			postCreateCompositeElement(composite);
			return composite;
		}
		
		override protected function get hasLoadTrait():Boolean
		{
			return forceLoadTrait;
		}
		
		override protected function get resourceForMediaElement():MediaResourceBase
		{
			return new URLResource("http://www.example.com");
		}
		
		override protected function get existentTraitTypesOnInitialization():Array
		{
			return [];
		}

		override protected function get existentTraitTypesAfterLoad():Array
		{
			return [];
		}

		// Overridden because setting the resource on a composite is a no-op.
		override public function testSetResource():void
		{
			var mediaElement:MediaElement = createMediaElement();
			
			mediaElement.resource = resourceForMediaElement
			assertTrue(mediaElement.resource == null);
		}
		
		// Tests
		//

		public function testGetNumChildren():void
		{
			var composite:CompositeElement = createCompositeElement();
			
			assertTrue(composite.numChildren == 0);
			
			var mediaElement:MediaElement = new MediaElement();
			composite.addChild(mediaElement);
			
			assertTrue(composite.numChildren == 1);
		}

		public function testGetChildAt():void
		{
			var composite:CompositeElement = createCompositeElement();
			
			assertTrue(composite.getChildAt(-1) == null);
			assertTrue(composite.getChildAt(0) == null);
			assertTrue(composite.getChildAt(1) == null);
			
			var mediaElement1:MediaElement = new MediaElement();
			var mediaElement2:MediaElement = new MediaElement();
			composite.addChild(mediaElement1);
			composite.addChild(mediaElement2);
			
			assertTrue(composite.getChildAt(-1) == null);
			assertTrue(composite.getChildAt(0) == mediaElement1);
			assertTrue(composite.getChildAt(1) == mediaElement2);
		}
		
		public function testGetChildIndex():void
		{
			var composite:CompositeElement = createCompositeElement();

			var mediaElement1:MediaElement = new MediaElement();
			var mediaElement2:MediaElement = new MediaElement();
			
			assertTrue(composite.getChildIndex(null) == -1);
			assertTrue(composite.getChildIndex(mediaElement1) == -1);
			assertTrue(composite.getChildIndex(mediaElement2) == -1);
			
			composite.addChild(mediaElement1);
			composite.addChild(mediaElement2);
			
			assertTrue(composite.getChildIndex(null) == -1);
			assertTrue(composite.getChildIndex(mediaElement1) == 0);
			assertTrue(composite.getChildIndex(mediaElement2) == 1);
		}

		public function testAddChild():void
		{
			var composite:CompositeElement = createCompositeElement();

			var mediaElement1:MediaElement = new MediaElement();
			var mediaElement2:MediaElement = new MediaElement();

			assertTrue(composite.numChildren == 0);
			
			composite.addChild(mediaElement1);
			assertTrue(composite.numChildren == 1);
			
			// It's *not* possible to add the same child twice.
			try
			{
				composite.addChild(mediaElement1);
				fail();
			}
			catch(_:*)
			{
			}
			assertEquals(1, composite.numChildren);
			
			// Adding a new child should place it at the end of the list.
			composite.addChild(mediaElement2);
			assertTrue(composite.numChildren == 2);
			assertTrue(composite.getChildAt(0) == mediaElement1);
			assertTrue(composite.getChildAt(1) == mediaElement2);
			
			// Adding a null child should throw an error.
			try
			{
				composite.addChild(null);
				fail();
			}
			catch (e:ArgumentError)
			{
			}
		}
		
		public function testAddChildAt():void
		{
			var composite:CompositeElement = createCompositeElement();

			var mediaElement1:MediaElement = new MediaElement();
			var mediaElement2:MediaElement = new MediaElement();

			assertTrue(composite.numChildren == 0);
			
			composite.addChildAt(mediaElement1, 0);
			assertTrue(composite.numChildren == 1);
			
			// It's *not* possible to add the same child twice.
			try
			{
				composite.addChild(mediaElement1);
				fail();
			}
			catch(_:*)
			{
			}
			
			// We can add at any index.
			composite.addChildAt(mediaElement2, 1);
			assertTrue(composite.numChildren == 2);
			assertTrue(composite.getChildAt(0) == mediaElement1);
			assertTrue(composite.getChildAt(1) == mediaElement2);
			
			// Adding a null child should throw an error.
			try
			{
				composite.addChildAt(null,0);
				fail();
			}
			catch (e:ArgumentError)
			{
			}

			// Adding a child at a negative index should throw an error.
			try
			{
				composite.addChildAt(mediaElement1,-1);
				fail();
			}
			catch (e:RangeError)
			{
			}
			
			// Adding a child at an index greater than the length should
			// throw an error.
			try
			{
				composite.addChildAt(mediaElement1,composite.numChildren+1);
				fail();
			}
			catch (e:RangeError)
			{
			}
		}

		public function testRemoveChild():void
		{
			var composite:CompositeElement = createCompositeElement();

			var mediaElement1:MediaElement = new MediaElement();
			var mediaElement2:MediaElement = new MediaElement();
			
			composite.addChild(mediaElement1);
			composite.addChild(mediaElement2);
			
			var result:MediaElement = composite.removeChild(mediaElement1);
			assertTrue(result == mediaElement1);
			assertTrue(composite.numChildren == 1);
			assertTrue(composite.getChildAt(0) == mediaElement2);
			
			result = composite.removeChild(mediaElement2);
			assertTrue(result == mediaElement2);
			assertTrue(composite.numChildren == 0);
			
			// Removing a child that's not in the composition should throw
			// an error.
			try
			{
				composite.removeChild(mediaElement2);
				fail();
			}
			catch (e:ArgumentError)
			{
			}

			// Removing a null child should throw an error.
			try
			{
				composite.removeChild(null);
				fail();
			}
			catch (e:ArgumentError)
			{
			}
		}
		
		public function testRemoveChildAt():void
		{
			var composite:CompositeElement = createCompositeElement();

			var mediaElement1:MediaElement = new MediaElement();
			var mediaElement2:MediaElement = new MediaElement();
			
			composite.addChild(mediaElement1);
			composite.addChild(mediaElement2);
			
			var result:MediaElement = composite.removeChildAt(1);
			assertTrue(result == mediaElement2);
			assertTrue(composite.numChildren == 1);
			assertTrue(composite.getChildAt(0) == mediaElement1);
			
			result = composite.removeChildAt(0);
			assertTrue(result == mediaElement1);
			assertTrue(composite.numChildren == 0);
			
			// Removing a child with a negative index should throw an error.
			try
			{
				composite.removeChildAt(-1);
				fail();
			}
			catch (e:RangeError)
			{
			}
			assertTrue(composite.numChildren == 0);

			// Removing a child with too large an index should throw an error.
			try
			{
				composite.removeChildAt(1);
				fail();
			}
			catch (e:RangeError)
			{
			}
		}
		
		public function testNestedMediaErrorEventDispatch():void
		{
			if (hasLoadTrait)
			{
				var composite:CompositeElement = createCompositeElement();
				var child1:CompositeElement = createCompositeElement();
				composite.addChild(child1);
				var child2:CompositeElement = createCompositeElement();
				child1.addChild(child2);
				
				composite.addEventListener(MediaErrorEvent.MEDIA_ERROR, onMediaError);
				var loadTrait:LoadTrait = child2.getTrait(MediaTraitType.LOAD) as LoadTrait;
				assertTrue(loadTrait);
				
				eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, 1000));
				
				// Make sure error events dispatched on the trait are redispatched
				// on the root of the composition.
				loadTrait.dispatchEvent(new MediaErrorEvent(MediaErrorEvent.MEDIA_ERROR, false, false, new MediaError(99)));
				
				function onMediaError(event:MediaErrorEvent):void
				{
					assertTrue(event.error.errorID == 99);
					assertTrue(event.error.message == "");
					assertTrue(event.target == composite);
						
					eventDispatcher.dispatchEvent(new Event("testComplete"));
				}
			}
		}
				
		// Protected
		//
		
		final protected function postCreateCompositeElement(composite:CompositeElement):void
		{
			if (forceLoadTrait)
			{
				var loader:SimpleLoader = new SimpleLoader();
				var childElement:MediaElement = 
					new DynamicMediaElement([MediaTraitType.LOAD],
											loader,
											new URLResource("http://www.example.com/load"));
				var loadTrait:LoadTrait = childElement.getTrait(MediaTraitType.LOAD) as LoadTrait;
				loadTrait.load();
				assertTrue(loadTrait.loadState == LoadState.READY);
	
				// Add the child.  This should cause its properties to
				// propagate to the composition.
				composite.addChild(childElement);
			}
		}
		
		final protected function createCompositeElement():CompositeElement
		{
			return createMediaElement() as CompositeElement;
		}
		
		
		final protected function assertHasTraits(mediaElement:MediaElement, traitTypes:Array):void
		{
			// Create a separate list with the traits that should *not* exist
			// on the media element.
			var missingTraitTypes:Vector.<String> = MediaTraitType.ALL_TYPES.concat();
			for each (var traitType:String in traitTypes)
			{
				missingTraitTypes.splice(missingTraitTypes.indexOf(traitType), 1);
			}
			assertTrue(traitTypes.length + missingTraitTypes.length == MediaTraitType.ALL_TYPES.length);
			
			// Verify the ones that should exist do exist.
			for (var i:int = 0; i < traitTypes.length; i++)
			{
				assertTrue(mediaElement.hasTrait(traitTypes[i]) == true);
			}
			
			// Verify the ones that shouldn't exist don't exist.
			for (i = 0; i < missingTraitTypes.length; i++)
			{
				assertTrue(mediaElement.hasTrait(missingTraitTypes[i]) == false);
			}
		}
				
		final protected function onTraitAddRemoveEvent(event:MediaElementEvent):void
		{
			if (event.type == MediaElementEvent.TRAIT_ADD)
			{
				traitAddEventCount++;
			}
			else if (event.type == MediaElementEvent.TRAIT_REMOVE)
			{
				traitRemoveEventCount++;
			}
			else fail();
		}
		
		final protected function vectorToArray(input:Vector.<String>):Array
		{
			var result:Array = [];
			
			for each (var str:String in input)
			{
				result.push(str);
			}
			
			return result;
		}

		protected var traitAddEventCount:int;
		protected var traitRemoveEventCount:int;
		
		protected var forceLoadTrait:Boolean;
	}
}