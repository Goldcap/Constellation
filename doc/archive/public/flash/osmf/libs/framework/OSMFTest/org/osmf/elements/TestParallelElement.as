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
	import org.osmf.events.MediaElementEvent;
	import org.osmf.media.MediaElement;
	import org.osmf.metadata.Metadata;
	import org.osmf.traits.MediaTraitType;
	import org.osmf.utils.DynamicMediaElement;
	
	public class TestParallelElement extends TestCompositeElement
	{
		// Overrides
		//
		
		override protected function createMediaElement():MediaElement
		{
			var composite:CompositeElement = new ParallelElement();
			postCreateCompositeElement(composite);
			return composite;
		}

		// Tests
		//
		
		public function testGetTraitTypesDynamically():void
		{
			var parallel:ParallelElement = createParallelElement();
			parallel.addEventListener(MediaElementEvent.TRAIT_ADD, onTraitAddRemoveEvent, false, 0, true);
			parallel.addEventListener(MediaElementEvent.TRAIT_REMOVE, onTraitAddRemoveEvent, false, 0, true);
			
			assertTrue(parallel.traitTypes != null);
			assertTrue(parallel.traitTypes.length == 0);
			
			assertTrue(traitAddEventCount == 0);
			assertTrue(traitRemoveEventCount == 0);
			
			// Adding a media element with a new trait should cause that trait
			// to be reflected on the composition.
			var mediaElement1:MediaElement = new DynamicMediaElement([MediaTraitType.AUDIO]);
			parallel.addChild(mediaElement1);
			assertTrue(parallel.traitTypes.length == 1);
			assertTrue(parallel.traitTypes[0] == MediaTraitType.AUDIO);

			assertTrue(traitAddEventCount == 1);
			assertTrue(traitRemoveEventCount == 0);
			
			// Adding another media element with the same trait won't affect
			// the existing trait.
			var mediaElement2:MediaElement = new DynamicMediaElement([MediaTraitType.AUDIO, MediaTraitType.BUFFER]);
			parallel.addChild(mediaElement2);
			assertTrue(parallel.traitTypes.length == 2);
			assertTrue(parallel.traitTypes[0] == MediaTraitType.AUDIO);
			assertTrue(parallel.traitTypes[1] == MediaTraitType.BUFFER);

			assertTrue(traitAddEventCount == 2);
			assertTrue(traitRemoveEventCount == 0);

			// Adding a media element with all traits should cause all traits
			// to be reflected on the composition.
			var allTraitTypes:Array = vectorToArray(MediaTraitType.ALL_TYPES);
			var mediaElement3:MediaElement = new DynamicMediaElement(allTraitTypes);
			parallel.addChild(mediaElement3);
			assertTrue(parallel.traitTypes.length == allTraitTypes.length);
			assertHasTraits(parallel, allTraitTypes);
			
			assertTrue(traitAddEventCount == allTraitTypes.length);
			assertTrue(traitRemoveEventCount == 0);

			// Removing a media element whose traits overlap with those of
			// other elements in the composition won't affect the reflected
			// traits.
			parallel.removeChild(mediaElement2);
			assertTrue(parallel.traitTypes.length == allTraitTypes.length);
			assertHasTraits(parallel, allTraitTypes);
			
			assertTrue(traitAddEventCount == allTraitTypes.length);
			assertTrue(traitRemoveEventCount == 0);

			// But as soon as we remove a media element which is the only one
			// in the composition with a particular set of traits, then those
			// traits should no longer be reflected on the composition.
			parallel.removeChild(mediaElement3);
			assertTrue(parallel.traitTypes.length == 1);
			assertTrue(parallel.traitTypes[0] == MediaTraitType.AUDIO);
			
			assertTrue(traitAddEventCount == allTraitTypes.length);
			assertTrue(traitRemoveEventCount == allTraitTypes.length - 1);

			// Removing the last child should remove all traits.
			parallel.removeChild(mediaElement1);
			assertTrue(parallel.traitTypes.length == 0);

			assertTrue(traitAddEventCount == allTraitTypes.length);
			assertTrue(traitRemoveEventCount == allTraitTypes.length);
		}
		
		public function testHasTraitDynamically():void
		{
			var parallel:ParallelElement = createParallelElement();
			
			var allTraitTypes:Array = vectorToArray(MediaTraitType.ALL_TYPES);
			
			// No traits to begin with.
			assertHasTraits(parallel,[]);
				
			// Adding a media element with a new trait should cause that trait
			// to be reflected on the composition.
			var mediaElement1:MediaElement = new DynamicMediaElement([MediaTraitType.AUDIO]);
			parallel.addChild(mediaElement1);
			assertHasTraits(parallel,[MediaTraitType.AUDIO]);
			
			// Adding another media element with the same trait won't affect
			// the existing trait.
			var mediaElement2:MediaElement = new DynamicMediaElement([MediaTraitType.AUDIO, MediaTraitType.BUFFER]);
			parallel.addChild(mediaElement2);
			assertHasTraits(parallel,[MediaTraitType.AUDIO, MediaTraitType.BUFFER]);

			// Adding a media element with all traits should cause all traits
			// to be reflected on the composition.
			var mediaElement3:MediaElement = new DynamicMediaElement(allTraitTypes);
			parallel.addChild(mediaElement3);
			assertHasTraits(parallel,allTraitTypes);
			
			// Removing a media element whose traits overlap with those of
			// other elements in the composition won't affect the reflected
			// traits.
			parallel.removeChild(mediaElement2);
			assertHasTraits(parallel,allTraitTypes);
			
			// But as soon as we remove a media element which is the only one
			// in the composition with a particular set of traits, then those
			// traits should no longer be reflected on the composition.
			parallel.removeChild(mediaElement3);
			assertHasTraits(parallel,[MediaTraitType.AUDIO]);
			
			// Removing the last child should remove all traits.
			parallel.removeChild(mediaElement1);
			assertHasTraits(parallel,[]);
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
			// the composite metadata reflects the first child (in the
			// absence of a non-default synthesizer).
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

			// When we remove a metadata where another child has metadata
			// in the same namespace, it's essentially a "change" event
			// (which means a remove followed by an add).
			mediaElement1.removeMetadata("ns2");
			assertTrue(metadataAddCount == 3);
			assertTrue(metadataRemoveCount == 1);

			mediaElement2.removeMetadata("ns2");
			assertTrue(metadataAddCount == 3);
			assertTrue(metadataRemoveCount == 2);
			
			// If we remove the first child, the metadata of the second
			// child will be reflected through the composite.
			composite.removeChild(mediaElement1);

			cm = composite.getMetadata("ns1");
			assertTrue(cm != null);
			assertTrue(cm.getValue("foo1") == null);
			assertTrue(cm.getValue("bar1") == "bar2");
			
			assertTrue(metadataAddCount == 4);
			assertTrue(metadataRemoveCount == 3);

			// And if we remove the last child, the metadata is gone.
			composite.removeChild(mediaElement2);

			cm = composite.getMetadata("ns1");
			assertTrue(cm == null);

			assertTrue(metadataAddCount == 4);
			assertTrue(metadataRemoveCount == 4);
			
			function onMetadataAdd(event:MediaElementEvent):void
			{
				metadataAddCount++;
			} 

			function onMetadataRemove(event:MediaElementEvent):void
			{
				metadataRemoveCount++;
			} 
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
		
		// Internals
		//

		private function createParallelElement():ParallelElement
		{
			return createMediaElement() as ParallelElement;
		}
	}
}