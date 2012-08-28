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
	
	public class TestSerialElementWithLoadTrait extends TestCase
	{
		public function testLoadTrait():void
		{
			var serial:SerialElement = new SerialElement();
			
			// No trait to begin with.
			assertTrue(serial.getTrait(MediaTraitType.LOAD) == null);
			
			// Create a few media elements with the LoadTrait and give
			// them various load states.
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

			var loader3:SimpleLoader = new SimpleLoader();
			var mediaElement3:MediaElement =
				new DynamicMediaElement([MediaTraitType.LOAD],
										loader3,
										new URLResource("http://www.example.com/loadTrait3"),
										true);
			var loadTrait3:DynamicLoadTrait = mediaElement3.getTrait(MediaTraitType.LOAD) as DynamicLoadTrait;
			loadTrait3.load();
			assertTrue(loadTrait3.loadState == LoadState.READY);
			
			// Nothing is added yet, so our composition shouldn't have the trait yet.
			assertTrue(serial.getTrait(MediaTraitType.LOAD) == null);
			
			// As soon as we add a child, that child will become our current
			// child, and the composite trait will reflect it.
			serial.addChild(mediaElement1);
			var loadTrait:LoadTrait = serial.getTrait(MediaTraitType.LOAD) as LoadTrait;
			assertTrue(loadTrait != null);
			assertTrue(loadTrait.loadState == LoadState.READY);
			assertTrue(loadTrait.resource != null &&
					   loadTrait.resource is URLResource &&
					   URLResource(loadTrait.resource).url.toString() == "http://www.example.com/loadTrait1");
			
			// The composite LoadTrait should always reflect the trait of the
			// current child.
			serial.removeChild(mediaElement1);
			assertTrue(serial.getTrait(MediaTraitType.LOAD) == null);
			serial.addChild(mediaElement2);
			loadTrait = serial.getTrait(MediaTraitType.LOAD) as LoadTrait;
			assertTrue(loadTrait != null);
			assertTrue(loadTrait.loadState == LoadState.UNINITIALIZED);
			assertTrue(loadTrait.resource != null &&
					   loadTrait.resource is URLResource &&
					   URLResource(loadTrait.resource).url.toString() == "http://www.example.com/loadTrait2");

			serial.addChild(mediaElement3);
			serial.removeChild(mediaElement2);
			loadTrait = serial.getTrait(MediaTraitType.LOAD) as LoadTrait;
			assertTrue(loadTrait != null);
			assertTrue(loadTrait.loadState == LoadState.READY);
			
			serial.addChildAt(mediaElement1, 0);
			serial.addChildAt(mediaElement2, 1);
			
			// Changing the state of a non-current child should not affect the
			// state of the composition.
			loadTrait1 = mediaElement1.getTrait(MediaTraitType.LOAD) as DynamicLoadTrait;
			assertTrue(loadTrait1.loadState == LoadState.READY);
			loadTrait1.unload();
			assertTrue(loadTrait1.loadState == LoadState.UNINITIALIZED);
			assertTrue(loadTrait.loadState == LoadState.READY);
			
			// Calling unload() on the composition should only affect the
			// current child.
			assertTrue(loadTrait.loadState == LoadState.READY);
			assertTrue(loadTrait1.loadState == LoadState.UNINITIALIZED);
			assertTrue(loadTrait2.loadState == LoadState.UNINITIALIZED);
			assertTrue(loadTrait3.loadState == LoadState.READY);
			loadTrait.unload();
			assertTrue(loadTrait.loadState == LoadState.UNINITIALIZED);
			assertTrue(loadTrait1.loadState == LoadState.UNINITIALIZED);
			assertTrue(loadTrait2.loadState == LoadState.UNINITIALIZED);
			assertTrue(loadTrait3.loadState == LoadState.UNINITIALIZED);

			// Calling load() on the composition should only affect the current
			// child.
			loadTrait.load();
			assertTrue(loadTrait.loadState == LoadState.READY);
			assertTrue(loadTrait1.loadState == LoadState.UNINITIALIZED);
			assertTrue(loadTrait2.loadState == LoadState.UNINITIALIZED);
			assertTrue(loadTrait3.loadState == LoadState.READY);
			
			loadTrait.unload();
			var mediaElement4:MediaElement =
				new DynamicMediaElement([MediaTraitType.LOAD],
				new SimpleLoader(),
				new SimpleResource(SimpleResource.FAILED),
				true);
			var loadTrait4:DynamicLoadTrait = mediaElement4.getTrait(MediaTraitType.LOAD) as DynamicLoadTrait;
			assertTrue(loadTrait4.loadState == LoadState.UNINITIALIZED);
			serial.addChild(mediaElement4);

			// If a load fails for a non-current child, then the composition
			// should be unaffected.
			loadTrait4.load();
			assertTrue(loadTrait.loadState == LoadState.UNINITIALIZED);
			assertTrue(loadTrait1.loadState == LoadState.UNINITIALIZED);
			assertTrue(loadTrait2.loadState == LoadState.UNINITIALIZED);
			assertTrue(loadTrait3.loadState == LoadState.UNINITIALIZED);
			assertTrue(loadTrait4.loadState == LoadState.LOAD_ERROR);
			
			// Verify the byte properties.
			//
			
			loadTrait1.bytesLoaded = 123;
			loadTrait1.bytesTotal = 456;
			assertTrue(loadTrait.bytesLoaded == 123);
			assertTrue(loadTrait.bytesTotal == 456);

			// Note that bytesLoaded is always the first contiguous chunk of bytes.
			loadTrait2.bytesLoaded = 100;
			loadTrait2.bytesTotal = 200;
			assertTrue(loadTrait.bytesLoaded == 123);
			assertTrue(loadTrait.bytesTotal == 656);
			
			loadTrait1.bytesLoaded = 456;
			assertTrue(loadTrait.bytesLoaded == 556);
		}
	}
}