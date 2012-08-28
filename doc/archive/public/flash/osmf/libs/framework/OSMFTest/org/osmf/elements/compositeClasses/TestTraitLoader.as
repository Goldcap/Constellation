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
package org.osmf.elements.compositeClasses
{
	import flexunit.framework.TestCase;
	
	import org.osmf.events.LoadEvent;
	import org.osmf.events.MediaErrorCodes;
	import org.osmf.events.MediaErrorEvent;
	import org.osmf.media.MediaElement;
	import org.osmf.media.URLResource;
	import org.osmf.traits.AudioTrait;
	import org.osmf.traits.LoadState;
	import org.osmf.traits.LoadTrait;
	import org.osmf.traits.MediaTraitType;
	import org.osmf.traits.SeekTrait;
	import org.osmf.utils.DynamicMediaElement;
	import org.osmf.utils.OSMFStrings;
	import org.osmf.utils.SimpleLoader;
	import org.osmf.utils.SimpleResource;
	
	public class TestTraitLoader extends TestCase
	{
		public function testFindOrLoadMediaElementWithTrait():void
		{
			var traitLoader:TraitLoader = new TraitLoader();
			traitLoader.addEventListener(TraitLoaderEvent.TRAIT_FOUND, onTraitFound);
			
			var mediaElements:Array = [];
			
			// Calling on an empty list should result in no returned
			// MediaElement.
			traitLoader.findOrLoadMediaElementWithTrait(mediaElements, MediaTraitType.SEEK);
			assertTrue(traitFoundEvents.length == 1);
			var traitFoundEvent:TraitLoaderEvent = TraitLoaderEvent(traitFoundEvents[0]);
			assertTrue(traitFoundEvent.mediaElement == null);
			
			// Create a few media elements.
			//

			var mediaElement1:MediaElement =
				new DynamicMediaElement([MediaTraitType.TIME, MediaTraitType.DISPLAY_OBJECT]);

			var loader2:SimpleLoader = new SimpleLoader();
			var mediaElement2:MediaElement =
				new DynamicMediaElement([MediaTraitType.LOAD, MediaTraitType.TIME],
										loader2,
										new URLResource("http://www.example.com/loadable2"));
			
			var loader3:SimpleLoader = new SimpleLoader();
			var mediaElement3:MediaElement =
				new DynamicMediaElement([MediaTraitType.LOAD, MediaTraitType.DISPLAY_OBJECT, MediaTraitType.SEEK],
										loader3,
										new URLResource("http://www.example.com/loadable3"));

			var mediaElement4:MediaElement =
				new DynamicMediaElement([MediaTraitType.TIME, MediaTraitType.SEEK, MediaTraitType.AUDIO]);
			
			mediaElements = [mediaElement1, mediaElement2, mediaElement3, mediaElement4]; 

			traitLoader.findOrLoadMediaElementWithTrait(mediaElements, MediaTraitType.SEEK);
			
			// Second child should be loaded, but not seekable.
			var loadTrait2:LoadTrait = mediaElement2.getTrait(MediaTraitType.LOAD) as LoadTrait;
			assertTrue(loadTrait2.loadState == LoadState.READY);
			var seekTrait2:SeekTrait = mediaElement2.getTrait(MediaTraitType.SEEK) as SeekTrait;
			assertTrue(seekTrait2 == null);
			
			// Third child already had the trait, but it wasn't loaded.  So it
			// should be loaded now.
			var loadTrait3:LoadTrait = mediaElement3.getTrait(MediaTraitType.LOAD) as LoadTrait;
			assertTrue(loadTrait3.loadState == LoadState.READY);
			var seekTrait3:SeekTrait = mediaElement3.getTrait(MediaTraitType.SEEK) as SeekTrait;
			assertTrue(seekTrait3 != null);

			// Found child is the third child.
			assertTrue(traitFoundEvents.length == 2);
			traitFoundEvent = TraitLoaderEvent(traitFoundEvents[1]);
			assertTrue(traitFoundEvent.mediaElement == mediaElement3);
			
			// Should get the same result if we call it again.
			traitLoader.findOrLoadMediaElementWithTrait(mediaElements, MediaTraitType.SEEK);
			assertTrue(traitFoundEvents.length == 3);
			traitFoundEvent = TraitLoaderEvent(traitFoundEvents[2]);
			assertTrue(traitFoundEvent.mediaElement == mediaElement3);
			
			// Try again with a trait that gets loaded.
			loadTrait3.unload();
			loadTrait3.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onLoadStateChange);
			function onLoadStateChange(event:LoadEvent):void
			{
				if (event.loadState == LoadState.READY)
				{
					loadTrait3.removeEventListener(LoadEvent.LOAD_STATE_CHANGE, onLoadStateChange);
					DynamicMediaElement(mediaElement3).doAddTrait(MediaTraitType.AUDIO, new AudioTrait());
				}
			}
			traitLoader.findOrLoadMediaElementWithTrait(mediaElements, MediaTraitType.AUDIO);

			// Second child should be loaded, but not have the AudioTrait.
			loadTrait2 = mediaElement2.getTrait(MediaTraitType.LOAD) as LoadTrait;
			assertTrue(loadTrait2.loadState == LoadState.READY);
			assertTrue(mediaElement2.getTrait(MediaTraitType.AUDIO) == null);
			
			// Third child didn't have the trait, but added it after loading.
			loadTrait3 = mediaElement3.getTrait(MediaTraitType.LOAD) as LoadTrait;
			assertTrue(loadTrait3.loadState == LoadState.READY);
			assertTrue(mediaElement3.getTrait(MediaTraitType.AUDIO) != null);

			// Found child is the third child.
			assertTrue(traitFoundEvents.length == 4);
			traitFoundEvent = TraitLoaderEvent(traitFoundEvents[3]);
			assertTrue(traitFoundEvent.mediaElement == mediaElement3);
			
			// If we remove the third child, we should get the fourth.
			mediaElements = [mediaElement1, mediaElement2, mediaElement4];
			traitLoader.findOrLoadMediaElementWithTrait(mediaElements, MediaTraitType.AUDIO);
			assertTrue(traitFoundEvents.length == 5);
			traitFoundEvent = TraitLoaderEvent(traitFoundEvents[4]);
			assertTrue(traitFoundEvent.mediaElement == mediaElement4);
			
			// Without the fourth child, we get no result.
			mediaElements = [mediaElement1, mediaElement2];
			traitLoader.findOrLoadMediaElementWithTrait(mediaElements, MediaTraitType.AUDIO);
			assertTrue(traitFoundEvents.length == 6);
			traitFoundEvent = TraitLoaderEvent(traitFoundEvents[5]);
			assertTrue(traitFoundEvent.mediaElement == null);
		}
		
		public function testFindOrLoadMediaElementWithTraitWhenLoadTraitIsRemoved():void
		{
			var traitLoader:TraitLoader = new TraitLoader();
			traitLoader.addEventListener(TraitLoaderEvent.TRAIT_FOUND, addAsync(onTestFindOrLoadMediaElementWithTraitWhenLoadTraitIsRemoved, 1000));
			
			var mediaElements:Array = [];
						
			// Create a few media elements.
			//

			var mediaElement1:DynamicMediaElement =
				new DynamicMediaElement([MediaTraitType.TIME, MediaTraitType.DISPLAY_OBJECT]);

			// Don't let the load complete immediately.
			var loader2:SimpleLoader = new SimpleLoader(true);
			var resource2:URLResource = new URLResource("http://www.example.com/loadable2");
			var mediaElement2:DynamicMediaElement =
				new DynamicMediaElement([MediaTraitType.LOAD, MediaTraitType.TIME],
										loader2,
										resource2);
			
			var loader3:SimpleLoader = new SimpleLoader();
			var mediaElement3:DynamicMediaElement =
				new DynamicMediaElement([MediaTraitType.LOAD, MediaTraitType.DISPLAY_OBJECT, MediaTraitType.SEEK],
										loader3,
										new URLResource("http://www.example.com/loadable3"));

			var mediaElement4:DynamicMediaElement =
				new DynamicMediaElement([MediaTraitType.TIME, MediaTraitType.SEEK, MediaTraitType.AUDIO]);
			
			mediaElements = [mediaElement1, mediaElement2, mediaElement3, mediaElement4]; 

			traitLoader.findOrLoadMediaElementWithTrait(mediaElements, MediaTraitType.SEEK);
			
			// The second child should be in the process of loading.
			var loadTrait2:LoadTrait = mediaElement2.getTrait(MediaTraitType.LOAD) as LoadTrait;
			assertTrue(loadTrait2.loadState == LoadState.LOADING);
			
			// Remove the load trait and add another, making sure that the
			// new trait gets the requested trait (SEEK) upon completion.
			mediaElement2.doRemoveTrait(MediaTraitType.LOAD);
			var newLoadTrait2:LoadTrait = new LoadTrait(loader2, resource2)
			newLoadTrait2.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onLoadStateChange, false, 100);
			
			mediaElement2.doAddTrait(MediaTraitType.LOAD, newLoadTrait2);
		
			function onLoadStateChange(event:LoadEvent):void
			{
				if (event.loadState == LoadState.READY)
				{
					mediaElement2.doAddTrait(MediaTraitType.SEEK, new SeekTrait(null));
				}
			}
			
			function onTestFindOrLoadMediaElementWithTraitWhenLoadTraitIsRemoved(event:TraitLoaderEvent):void
			{
				// Here's the payoff:  the found element should be the second one, not
				// the third one, because the second one's new LoadTrait caused the
				// SEEK trait to get added.
				assertTrue(event.mediaElement == mediaElement2);
			}
		}
		
		public function testFindOrLoadMediaElementWithTraitWithInvalidLoad():void
		{
			var traitLoader:TraitLoader = new TraitLoader();
			traitLoader.addEventListener(TraitLoaderEvent.TRAIT_FOUND, onTraitFound);
			
			var mediaErrorEvents:Array = [];
			var mediaElements:Array = [];
			
			// Create a media element with an unhandled resource.
			//

			var loader1:SimpleLoader = new SimpleLoader();
			var mediaElement1:MediaElement =
				new DynamicMediaElement([MediaTraitType.LOAD, MediaTraitType.TIME],
										loader1,
										new SimpleResource(SimpleResource.UNHANDLED));
			mediaElement1.addEventListener(MediaErrorEvent.MEDIA_ERROR, onMediaError);
			
			mediaElements = [mediaElement1]; 

			traitLoader.findOrLoadMediaElementWithTrait(mediaElements, MediaTraitType.SEEK);
			
			assertTrue(traitFoundEvents.length == 1);
			assertTrue(traitFoundEvents[0].mediaElement == null);

			assertTrue(mediaErrorEvents.length == 1);
			assertTrue(MediaErrorEvent(mediaErrorEvents[0]).error.errorID == MediaErrorCodes.MEDIA_LOAD_FAILED);
			assertTrue(MediaErrorEvent(mediaErrorEvents[0]).error.detail == OSMFStrings.getString(OSMFStrings.LOADER_CANT_HANDLE_RESOURCE));
			
			function onMediaError(event:MediaErrorEvent):void
			{
				mediaErrorEvents.push(event);
			}
		}
		
		private function onTraitFound(event:TraitLoaderEvent):void
		{
			traitFoundEvents.push(event);
		}
		
		override public function setUp():void
		{
			super.setUp();
			
			traitFoundEvents = []
		}
		
		private var traitFoundEvents:Array = []
	}
}