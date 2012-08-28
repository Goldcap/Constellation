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
	
	import org.osmf.elements.beaconClasses.Beacon;
	import org.osmf.events.LoaderEvent;
	import org.osmf.events.PlayEvent;
	import org.osmf.media.MediaElement;
	import org.osmf.media.MediaResourceBase;
	import org.osmf.media.TestMediaElement;
	import org.osmf.media.URLResource;
	import org.osmf.traits.LoadState;
	import org.osmf.traits.MediaTraitType;
	import org.osmf.traits.PlayState;
	import org.osmf.traits.PlayTrait;
	import org.osmf.utils.DynamicBeaconElement;
	import org.osmf.utils.HTTPLoader;
	import org.osmf.utils.MockHTTPLoader;
	import org.osmf.utils.NetFactory;
	import org.osmf.utils.TestConstants;
	
	public class TestBeaconElement extends TestMediaElement
	{
		public function testPlay():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, 4000));
			
			var playStateChangeCount:int = 0;
			var pingComplete:Boolean = false;
			
			var httpLoader:HTTPLoader = createHTTPLoader();
			httpLoader.addEventListener(LoaderEvent.LOAD_STATE_CHANGE, onLoaderStateChange);
			
			var mediaElement:MediaElement = createBeaconElement(RESOURCE.url, httpLoader);
			var playTrait:PlayTrait = mediaElement.getTrait(MediaTraitType.PLAY) as PlayTrait;
			assertTrue(playTrait != null);
			assertTrue(playTrait.canPause == false);
			playTrait.addEventListener(PlayEvent.PLAY_STATE_CHANGE, onPlayStateChange);
			
			playTrait.play();
			
			function onPlayStateChange(event:PlayEvent):void
			{
				if (playStateChangeCount == 0)
				{
					assertTrue(event.playState == PlayState.PLAYING);
				}
				else if (playStateChangeCount == 1)
				{
					assertTrue(event.playState == PlayState.STOPPED);
				}
				else fail();
				
				playStateChangeCount++;
				
				checkForCompletion();
			}
			
			function onLoaderStateChange(event:LoaderEvent):void
			{
				if (event.loadTrait.loadState == LoadState.READY)
				{
					pingComplete = true;
					
					checkForCompletion();
				}
			}
			
			function checkForCompletion():void
			{
				if (playStateChangeCount == 2 && pingComplete)
				{
					eventDispatcher.dispatchEvent(new Event("testComplete"));
				}
			}
		}
		
		// Overrides
		//
		
		override protected function createMediaElement():MediaElement
		{
			return createBeaconElement(RESOURCE.url);
		}
		
		override protected function get hasLoadTrait():Boolean
		{
			return false;
		}
		
		override protected function get resourceForMediaElement():MediaResourceBase
		{
			return RESOURCE;
		}

		override protected function get existentTraitTypesOnInitialization():Array
		{
			return [MediaTraitType.PLAY];
		}

		override protected function get existentTraitTypesAfterLoad():Array
		{
			return [MediaTraitType.PLAY];
		}
		
		// Internals
		//
		
		private function createBeaconElement(url:String, httpLoader:HTTPLoader=null):BeaconElement
		{
			return new DynamicBeaconElement(new Beacon(url, httpLoader || createHTTPLoader()));
		}
		
		private function createHTTPLoader():HTTPLoader
		{
			if (NetFactory.neverUseMockObjects)
			{
				return new HTTPLoader();
			}
			else
			{
				var loader:MockHTTPLoader = new MockHTTPLoader();
				loader.setExpectationForURL(RESOURCE.url, true, null);
				return loader;
			}
		}
		
		private static const RESOURCE:URLResource = new URLResource(TestConstants.REMOTE_IMAGE_FILE);
	}
}