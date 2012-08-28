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
	import org.osmf.events.*;
	import org.osmf.media.MediaElement;
	import org.osmf.media.URLResource;
	import org.osmf.metadata.Metadata;
	import org.osmf.traits.*;
	import org.osmf.utils.DynamicMediaElement;

	public class TestSerialElement extends TestCompositeElement
	{
		// Overrides
		//
		
		override protected function createMediaElement():MediaElement
		{
			var composite:CompositeElement = new SerialElement();
			postCreateCompositeElement(composite);
			return composite;
		}
		
		// Tests
		//
		
		public function testCurrentChild():void
		{
			var currentChildChangeCount:int = 0;
			
			var serial:SerialElement = createSerialElement();
			serial.addEventListener(SerialElementEvent.CURRENT_CHILD_CHANGE, onCurrentChildChange);
			
			assertTrue(serial.currentChild == null);
			assertTrue(currentChildChangeCount == 0);
			
			// Add some children.
			//
			
			var child1:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.AUDIO, MediaTraitType.LOAD]);
			serial.addChild(child1);
			
			// As soon as we add a child, it becomes the "current child" of the composition.
			assertTrue(serial.currentChild == child1);
			assertTrue(currentChildChangeCount == 1);

			var child2:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.AUDIO, MediaTraitType.BUFFER]);
			serial.addChild(child2);
			
			// No change to the current child.
			assertTrue(serial.currentChild == child1);
			assertTrue(currentChildChangeCount == 1);
						
			// But if we remove and readd the first child, the second one
			// should now be reflected as the "current child".
			serial.removeChild(child1);
			assertTrue(serial.currentChild == child2);
			assertTrue(currentChildChangeCount == 2);
			
			serial.addChildAt(child1, 0);
			assertTrue(serial.currentChild == child2);
			assertTrue(currentChildChangeCount == 2);
			
			// Add some more children.
			//

			var child3:DynamicMediaElement = new DynamicMediaElement([]);
			
			serial.addChild(child3);
			assertTrue(serial.currentChild == child2);
			assertTrue(currentChildChangeCount == 2);
			
			var child4:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.AUDIO]);
			serial.addChildAt(child4, 0);
			assertTrue(serial.currentChild == child2);
			assertTrue(currentChildChangeCount == 2);

			var child5:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.AUDIO]);
			serial.addChild(child5);
			assertTrue(serial.currentChild == child2);
			assertTrue(currentChildChangeCount == 2);
			
			// When we remove the current child, the next child should be the
			// new current child.
			serial.removeChild(child2);
			assertTrue(serial.currentChild == child3);
			assertTrue(currentChildChangeCount == 3);

			serial.removeChild(child3);
			assertTrue(serial.currentChild == child5);
			assertTrue(currentChildChangeCount == 4);
			
			// Now when we remove the current child, the new current child is
			// the first child since there is no next child.
			serial.removeChild(child5);
			assertTrue(serial.currentChild == child4);
			assertTrue(currentChildChangeCount == 5);
			
			// When we remove the last child, we have no more current child.
			serial.removeChild(child4);
			assertTrue(serial.currentChild == child1);
			assertTrue(currentChildChangeCount == 6);
			serial.removeChild(child1);
			assertTrue(serial.currentChild == null);
			assertTrue(currentChildChangeCount == 7);
			
			function onCurrentChildChange(event:SerialElementEvent):void
			{
				currentChildChangeCount++;
				
				if (currentChildChangeCount == 1)
				{
					assertTrue(event.currentChild == child1);
				}
				else if (currentChildChangeCount == 7)
				{
					assertTrue(event.currentChild == null);
				}
				else
				{
					assertTrue(event.currentChild != null);
				}
			}
		}
		
		public function testGetResourceReturnsChildResource():void
		{
			var serial:SerialElement = createSerialElement();
			
			assertTrue(serial.resource == null);
			
			// The SerialElement's resource should always reflect the resource
			// of the current child.
			//
			
			var resource1:URLResource = new URLResource("http://example.com/1");
			var child1:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.AUDIO], null, resource1);
			serial.addChild(child1);
			assertTrue(serial.resource == resource1);

			var resource2:URLResource = new URLResource("http://example.com/2");
			var child2:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.AUDIO], null, resource2);
			serial.addChild(child2);
			assertTrue(serial.resource == resource1);
			
			serial.removeChild(child1);
			assertTrue(serial.resource == resource2);
						
			serial.removeChild(child2);
			assertTrue(serial.resource == null);
		}

		public function testGetTraitTypesDynamically():void
		{
			var serial:SerialElement = createSerialElement();
			serial.addEventListener(MediaElementEvent.TRAIT_ADD, onTraitAddRemoveEvent, false, 0, true);
			serial.addEventListener(MediaElementEvent.TRAIT_REMOVE, onTraitAddRemoveEvent, false, 0, true);
						
			assertTrue(serial.traitTypes != null);
			assertTrue(serial.traitTypes.length == 0);
			
			assertTrue(traitAddEventCount == 0);
			assertTrue(traitRemoveEventCount == 0);
			
			// Add some children with varying sets of traits.
			//
			
			var child1:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.AUDIO, MediaTraitType.LOAD]);
			serial.addChild(child1);
			
			// As soon as we add a child, the composition reflects its traits.
			// It becomes the "current child" of the composition.
			assertTrue(serial.traitTypes.length == 2);
			assertTrue(serial.traitTypes[0] == MediaTraitType.AUDIO);
			assertTrue(serial.traitTypes[1] == MediaTraitType.LOAD);
			assertTrue(traitAddEventCount == 2);
			assertTrue(traitRemoveEventCount == 0);

			// Although individual traits can internally cause the current child
			// to change, the only way to do so externally is to add the first
			// child to a serial composition.			
			var child2:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.AUDIO, MediaTraitType.BUFFER]);
			serial.addChild(child2);
			
			// No change, because a child already existed.
			assertTrue(serial.traitTypes.length == 2);
			assertTrue(serial.traitTypes[0] == MediaTraitType.AUDIO);
			assertTrue(serial.traitTypes[1] == MediaTraitType.LOAD);
			assertTrue(traitAddEventCount == 2);
			assertTrue(traitRemoveEventCount == 0);
			
			// But if we remove and readd the first child, the second one
			// should now be reflected as the "current child".
			serial.removeChild(child1);
			serial.addChildAt(child1, 0);
			assertTrue(serial.traitTypes[0] == MediaTraitType.AUDIO);
			assertTrue(serial.traitTypes[1] == MediaTraitType.BUFFER);
			assertTrue(traitAddEventCount == 4);
			assertTrue(traitRemoveEventCount == 2);
			serial.removeChild(child1);
			serial.removeChild(child2);
			assertTrue(traitAddEventCount == 4);
			assertTrue(traitRemoveEventCount == 4);
			
			// Add some more children.
			//

			var allTraitTypes:Array = vectorToArray(MediaTraitType.ALL_TYPES);
			
			var child3:DynamicMediaElement = new DynamicMediaElement([]);
			
			serial.addChild(child3);

			var child4:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.AUDIO]);
			serial.addChildAt(child4, 0);

			var child5:DynamicMediaElement = new DynamicMediaElement(allTraitTypes);
			serial.addChild(child5);
			
			// The first one we added should be the current child.
			assertTrue(serial.traitTypes.length == 0);
			assertTrue(traitAddEventCount == 4);
			assertTrue(traitRemoveEventCount == 4);
			
			// When we remove the current child, the next child should be the
			// new current child.
			serial.removeChild(child3);
			assertTrue(serial.traitTypes.length == allTraitTypes.length);
			assertTrue(traitAddEventCount == 4 + allTraitTypes.length);
			assertTrue(traitRemoveEventCount == 4);
			
			// Now when we remove the current child, the new current child is
			// the first child since there is no next child.
			serial.removeChild(child5);
			assertTrue(serial.traitTypes.length == 1);
			assertTrue(serial.traitTypes[0] == MediaTraitType.AUDIO);
			assertTrue(traitAddEventCount == 5 + allTraitTypes.length);
			assertTrue(traitRemoveEventCount == 4 + allTraitTypes.length);
			
			// When we remove the last child, we have no more traits.
			serial.removeChild(child4);
			assertTrue(serial.traitTypes.length == 0);
			assertTrue(traitAddEventCount == 5 + allTraitTypes.length);
			assertTrue(traitRemoveEventCount == 5 + allTraitTypes.length);
		}
		
		public function testHasTraitDynamically():void
		{
			var serial:SerialElement = createSerialElement();
			serial.addEventListener(MediaElementEvent.TRAIT_ADD, onTraitAddRemoveEvent, false, 0, true);
			serial.addEventListener(MediaElementEvent.TRAIT_REMOVE, onTraitAddRemoveEvent, false, 0, true);
					
			// No traits to begin with.
			assertHasTraits(serial,[]);
			
			var allTraitTypes:Array = vectorToArray(MediaTraitType.ALL_TYPES);
			
			// Add some children with varying sets of traits.
			//
			
			var child1:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.AUDIO, MediaTraitType.LOAD]);
			serial.addChild(child1);
			
			// The first child added is what gets reflected in the composite trait.
			assertHasTraits(serial, [MediaTraitType.AUDIO, MediaTraitType.LOAD]);
			
			var child2:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.AUDIO, MediaTraitType.BUFFER]);
			serial.addChild(child2);
			
			// No change, because this was the second child added.
			assertHasTraits(serial, [MediaTraitType.AUDIO, MediaTraitType.LOAD]);
			
			// But if we remove the first child, then the second becomes the current child.
			serial.removeChildAt(0);
			assertHasTraits(serial, [MediaTraitType.AUDIO, MediaTraitType.BUFFER]);
			serial.removeChildAt(0);
			
			var child3:DynamicMediaElement = new DynamicMediaElement([]);
			serial.addChild(child3);
			assertHasTraits(serial, []);
			serial.removeChild(child3);
			
			var child4:DynamicMediaElement = new DynamicMediaElement([MediaTraitType.AUDIO]);
			serial.addChild(child4);
			assertHasTraits(serial, [MediaTraitType.AUDIO]);
			serial.removeChild(child4);
			
			var child5:DynamicMediaElement = new DynamicMediaElement(allTraitTypes);
			serial.addChild(child5);
			assertHasTraits(serial, allTraitTypes);
		}
		
		override public function testMediaErrorEventDispatch():void
		{
			forceLoadTrait = true;
			
			super.testMediaErrorEventDispatch();
		}
		
		override public function testNestedMediaErrorEventDispatch():void
		{
			forceLoadTrait = true;
			
			super.testNestedMediaErrorEventDispatch();
		}

		
		public function testCompositeMetadata():void
		{
			var metadataAddCount:int = 0;
			var metadataRemoveCount:int = 0;
			
			var composite:CompositeElement = createCompositeElement();
			composite.addEventListener(MediaElementEvent.METADATA_ADD, onMetadataAdd);
			composite.addEventListener(MediaElementEvent.METADATA_REMOVE, onMetadataRemove);

			var mediaElement1:MediaElement = new MediaElement();
			var m1:Metadata = new Metadata();
			m1.addValue("foo1", "foo2");
			mediaElement1.addMetadata("ns1", m1);
			
			var mediaElement2:MediaElement = new MediaElement();
			var m2:Metadata = new Metadata();
			m2.addValue("bar1", "bar2");
			mediaElement2.addMetadata("ns1", m2);
			
			assertTrue(composite.getMetadata("ns1") == null);

			// Add the first child, this should give us our composite metadata.
			composite.addChild(mediaElement1);
			
			var cm:Metadata = composite.getMetadata("ns1");
			assertTrue(cm != null);
			assertTrue(cm.getValue("foo1") == "foo2");
			assertTrue(cm.getValue("bar1") == null);
			
			assertTrue(metadataAddCount == 1);
			assertTrue(metadataRemoveCount == 0);
			
			// Adding the second child shouldn't have an impact, since
			// the composite metadata reflects the active child.
			composite.addChild(mediaElement2);
			
			cm = composite.getMetadata("ns1");
			assertTrue(cm != null);
			assertTrue(cm.getValue("foo1") == "foo2");
			assertTrue(cm.getValue("bar1") == null);
			
			assertTrue(metadataAddCount == 1);
			assertTrue(metadataRemoveCount == 0);
			
			// Adding and removing metadata on a child should trigger
			// some events.
			mediaElement1.addMetadata("ns2", new Metadata());
			assertTrue(metadataAddCount == 2);
			assertTrue(metadataRemoveCount == 0);

			mediaElement2.addMetadata("ns2", new Metadata());
			assertTrue(metadataAddCount == 2);
			assertTrue(metadataRemoveCount == 0);

			mediaElement1.removeMetadata("ns2");
			assertTrue(metadataAddCount == 2);
			assertTrue(metadataRemoveCount == 1);

			mediaElement2.removeMetadata("ns2");
			assertTrue(metadataAddCount == 2);
			assertTrue(metadataRemoveCount == 1);
			
			// If we remove the first child, the second child will
			// become active.
			composite.removeChild(mediaElement1);

			cm = composite.getMetadata("ns1");
			assertTrue(cm != null);
			assertTrue(cm.getValue("foo1") == null);
			assertTrue(cm.getValue("bar1") == "bar2");
			
			assertTrue(metadataAddCount == 3);
			assertTrue(metadataRemoveCount == 2);

			// And if we remove the last child, the metadata is gone.
			composite.removeChild(mediaElement2);

			cm = composite.getMetadata("ns1");
			assertTrue(cm == null);

			assertTrue(metadataAddCount == 3);
			assertTrue(metadataRemoveCount == 3);
			
			function onMetadataAdd(event:MediaElementEvent):void
			{
				metadataAddCount++;
			} 

			function onMetadataRemove(event:MediaElementEvent):void
			{
				metadataRemoveCount++;
			} 
		}
		
		// Internals
		//

		private function createSerialElement():SerialElement
		{
			return createMediaElement() as SerialElement;
		}
	}
}