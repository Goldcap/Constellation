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
	import flexunit.framework.TestCase;
	
	import org.osmf.media.MediaElement;
	import org.osmf.media.URLResource;
	import org.osmf.traits.LoadState;
	import org.osmf.traits.LoadTrait;
	import org.osmf.traits.MediaTraitType;
	import org.osmf.utils.DynamicLoadTrait;
	import org.osmf.utils.DynamicMediaElement;
	import org.osmf.utils.SimpleLoader;
	import org.osmf.utils.SimpleResource;
	
	public class TestParallelElementWithLoadTrait extends TestCase
	{
		public function testLoadTrait():void
		{
			var parallel:ParallelElement = new ParallelElement();
			
			// No trait to begin with.
			assertTrue(parallel.getTrait(MediaTraitType.LOAD) == null);
			
			// Create a few media elements with the LoadTrait and some
			// initial properties.
			//
			
			var loader1:SimpleLoader = new SimpleLoader();
			var mediaElement1:MediaElement = 
				new DynamicMediaElement([MediaTraitType.LOAD],
										loader1,
										new URLResource("http://www.example.com/loadTrait1"),
										true);
			var loadTrait1:DynamicLoadTrait = mediaElement1.getTrait(MediaTraitType.LOAD) as DynamicLoadTrait;
			loadTrait1.load();
			assertTrue(loadTrait1.loadState == LoadState.READY);

			var loader2:SimpleLoader = new SimpleLoader();
			var mediaElement2:MediaElement =
				new DynamicMediaElement([MediaTraitType.LOAD],
										 loader2,
										 new URLResource("http://www.example.com/loadTrait2"),
										 true);
			var loadTrait2:DynamicLoadTrait = mediaElement2.getTrait(MediaTraitType.LOAD) as DynamicLoadTrait;
			assertTrue(loadTrait2.loadState == LoadState.UNINITIALIZED);

			// Add the first child.  This should cause its properties to
			// propagate to the composition.
			parallel.addChild(mediaElement1);
			var loadTrait:LoadTrait = parallel.getTrait(MediaTraitType.LOAD) as LoadTrait;
			assertTrue(loadTrait.loadState == LoadState.READY);
			assertTrue(loadTrait.resource == null);
			assertTrue(isNaN(loadTrait.bytesLoaded));
			assertTrue(isNaN(loadTrait.bytesTotal));

			// Add the second child.  Should cause the added child to get loaded.
			parallel.addChild(mediaElement2);
			loadTrait = parallel.getTrait(MediaTraitType.LOAD) as LoadTrait;
			assertTrue(loadTrait.loadState == LoadState.READY);
			assertTrue(loadTrait2.loadState == LoadState.READY);

			// Calling unload() on the composition should cause all children to
			// to unload.
			loadTrait.unload();
			assertTrue(loadTrait.loadState == LoadState.UNINITIALIZED);
			assertTrue(loadTrait1.loadState == LoadState.UNINITIALIZED);
			assertTrue(loadTrait2.loadState == LoadState.UNINITIALIZED);
			
			// Calling load() on the composition should cause all children to
			// load.
			loadTrait.load();
			assertTrue(loadTrait.loadState == LoadState.READY);
			assertTrue(loadTrait1.loadState == LoadState.READY);
			assertTrue(loadTrait2.loadState == LoadState.READY);
			
			// Calling unload() on a child should cause all other children to
			// unload (and the composition to reflect this).
			loadTrait1.unload();
			assertTrue(loadTrait.loadState == LoadState.UNINITIALIZED);
			assertTrue(loadTrait1.loadState == LoadState.UNINITIALIZED);
			assertTrue(loadTrait2.loadState == LoadState.UNINITIALIZED);

			// Calling load() on a child should cause all other children to
			// load (and the composition to reflect this).
			loadTrait2.load();
			assertTrue(loadTrait.loadState == LoadState.READY);
			assertTrue(loadTrait1.loadState == LoadState.READY);
			assertTrue(loadTrait2.loadState == LoadState.READY);
			
			// Adding a READY child to an UNINITIALIZED composition causes the
			// child to unload.
			loadTrait.unload();
			var mediaElement3:MediaElement =
				new DynamicMediaElement([MediaTraitType.LOAD],
				new SimpleLoader(),
				new URLResource("http://www.example.com/loadTrait3"),
				true);
			var loadTrait3:DynamicLoadTrait = mediaElement3.getTrait(MediaTraitType.LOAD) as DynamicLoadTrait;
			loadTrait3.load();
			assertTrue(loadTrait3.loadState == LoadState.READY);
			parallel.addChild(mediaElement3);
			assertTrue(loadTrait.loadState == LoadState.UNINITIALIZED);
			assertTrue(loadTrait1.loadState == LoadState.UNINITIALIZED);
			assertTrue(loadTrait2.loadState == LoadState.UNINITIALIZED);
			assertTrue(loadTrait3.loadState == LoadState.UNINITIALIZED);

			// Adding an UNINITIALIZED child to a READY composition causes the
			// child to load.
			loadTrait.load();
			assertTrue(loadTrait.loadState == LoadState.READY);
			var mediaElement4:MediaElement =
				new DynamicMediaElement([MediaTraitType.LOAD],
				new SimpleLoader(),
				new URLResource("http://www.example.com/loadTrait4"),
				true);
			var loadTrait4:DynamicLoadTrait = mediaElement4.getTrait(MediaTraitType.LOAD) as DynamicLoadTrait;
			assertTrue(loadTrait4.loadState == LoadState.UNINITIALIZED);
			parallel.addChild(mediaElement4);
			assertTrue(loadTrait.loadState == LoadState.READY);
			assertTrue(loadTrait1.loadState == LoadState.READY);
			assertTrue(loadTrait2.loadState == LoadState.READY);
			assertTrue(loadTrait3.loadState == LoadState.READY);
			assertTrue(loadTrait4.loadState == LoadState.READY);
			
			// If a load fails, then the composition should reflect the
			// failure.
			loadTrait.unload();
			var mediaElement5:MediaElement =
				new DynamicMediaElement([MediaTraitType.LOAD],
				new SimpleLoader(),
				new SimpleResource(SimpleResource.FAILED),
				true);
			var loadTrait5:DynamicLoadTrait = mediaElement5.getTrait(MediaTraitType.LOAD) as DynamicLoadTrait;
			assertTrue(loadTrait5.loadState == LoadState.UNINITIALIZED);
			parallel.addChild(mediaElement5);
			
			assertTrue(loadTrait.loadState == LoadState.UNINITIALIZED);
			assertTrue(loadTrait1.loadState == LoadState.UNINITIALIZED);
			assertTrue(loadTrait2.loadState == LoadState.UNINITIALIZED);
			assertTrue(loadTrait3.loadState == LoadState.UNINITIALIZED);
			assertTrue(loadTrait4.loadState == LoadState.UNINITIALIZED);
			assertTrue(loadTrait5.loadState == LoadState.UNINITIALIZED);
			
			loadTrait5.load();
			assertTrue(loadTrait.loadState == LoadState.LOAD_ERROR);
			assertTrue(loadTrait1.loadState == LoadState.READY);
			assertTrue(loadTrait2.loadState == LoadState.READY);
			assertTrue(loadTrait3.loadState == LoadState.READY);
			assertTrue(loadTrait4.loadState == LoadState.READY);
			assertTrue(loadTrait5.loadState == LoadState.LOAD_ERROR);
			
			// Verify the byte properties.
			//
			
			loadTrait1.bytesLoaded = 123;
			loadTrait1.bytesTotal = 456;
			assertTrue(loadTrait.bytesLoaded == 123);
			assertTrue(loadTrait.bytesTotal == 456);

			loadTrait2.bytesLoaded = 100;
			loadTrait2.bytesTotal = 200;
			assertTrue(loadTrait.bytesLoaded == 223);
			assertTrue(loadTrait.bytesTotal == 656);
			
			loadTrait3.bytesLoaded = 0;
			loadTrait3.bytesTotal = 50;
			assertTrue(loadTrait.bytesLoaded == 223);
			assertTrue(loadTrait.bytesTotal == 706);

			loadTrait4.bytesLoaded = 90;
			loadTrait4.bytesTotal = 90;
			assertTrue(loadTrait.bytesLoaded == 313);
			assertTrue(loadTrait.bytesTotal == 796);
		}
	}
}