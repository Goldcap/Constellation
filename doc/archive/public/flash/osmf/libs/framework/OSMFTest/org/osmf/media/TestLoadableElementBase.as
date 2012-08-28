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
package org.osmf.media
{
	import __AS3__.vec.Vector;
	
	import flash.events.Event;
	
	import org.osmf.events.LoadEvent;
	import org.osmf.traits.LoadState;
	import org.osmf.traits.LoadTrait;
	import org.osmf.traits.LoaderBase;
	import org.osmf.traits.MediaTraitType;
	import org.osmf.utils.DynamicLoadableElement;
	import org.osmf.utils.MockHTTPLoader;
	import org.osmf.utils.NullResource;
	import org.osmf.utils.SimpleLoader;
	import org.osmf.utils.SimpleResource;
	
	public class TestLoadableElementBase extends TestMediaElement
	{
		override protected function createMediaElement():MediaElement
		{
			return new LoadableElementBase(null, new SimpleLoader()); 
		}
		
		override protected function get hasLoadTrait():Boolean
		{
			return true;
		}
		
		override protected function get resourceForMediaElement():MediaResourceBase
		{
			return new NullResource();
		}
		
		override protected function get existentTraitTypesOnInitialization():Array
		{
			return [MediaTraitType.LOAD];
		}

		override protected function get existentTraitTypesAfterLoad():Array
		{
			return [MediaTraitType.LOAD];
		}
		
		public function testConstructor():void
		{
			new LoadableElementBase(null, new SimpleLoader());
			new LoadableElementBase(null, null);
		}
		
		public function testSetResourceUnloadsPreviousLoadTrait():void
		{
			var mediaElement:MediaElement = createMediaElement();
			mediaElement.resource = resourceForMediaElement;
			
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, 4000));

			var loadTrait:LoadTrait = mediaElement.getTrait(MediaTraitType.LOAD) as LoadTrait;
			assertTrue(loadTrait != null);
			loadTrait.addEventListener
					( LoadEvent.LOAD_STATE_CHANGE
					, onTestSetResourceUnloadsPreviousLoadTrait
					);
			loadTrait.load();
			
			function onTestSetResourceUnloadsPreviousLoadTrait(event:LoadEvent):void
			{
				if (event.loadState == LoadState.READY)
				{
					// If we set a new resource on the MediaElement, that
					// should result in the LoadTrait (which corresponds to
					// the previous resource) being unloaded.
					mediaElement.resource = resourceForMediaElement;
				}
				else if (event.loadState == LoadState.UNINITIALIZED)
				{
					loadTrait.removeEventListener(LoadEvent.LOAD_STATE_CHANGE, onTestSetResourceUnloadsPreviousLoadTrait);
					
					eventDispatcher.dispatchEvent(new Event("testComplete"));
				}
			}
		}
		
		public function testSetResourceSetsLoader():void
		{
			var alternateLoaders:Vector.<LoaderBase> = new Vector.<LoaderBase>();
			alternateLoaders.push(new MockHTTPLoader());
			alternateLoaders.push(new SimpleLoader());
			var mediaElement:MediaElement = new DynamicLoadableElement(null, null, alternateLoaders);
			
			assertTrue(mediaElement.getTrait(MediaTraitType.LOAD) == null);
			mediaElement.resource = new URLResource("http://example.com");
			assertTrue(mediaElement.getTrait(MediaTraitType.LOAD) != null);
			mediaElement.resource = new SimpleResource("foo");
			assertTrue(mediaElement.getTrait(MediaTraitType.LOAD) != null);
		}
		
		public function testSetResourceWithUnhandleResourceSetsLoader():void
		{
			var alternateLoaders:Vector.<LoaderBase> = new Vector.<LoaderBase>();
			alternateLoaders.push(new MockHTTPLoader());
			alternateLoaders.push(new SimpleLoader());
			var mediaElement:MediaElement = new DynamicLoadableElement(null, null, alternateLoaders);
			
			mediaElement.resource = new SimpleResource(SimpleResource.UNHANDLED);
			assertTrue(mediaElement.getTrait(MediaTraitType.LOAD) != null);
		}
	}
}