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
	import flash.display.Bitmap;
	import flash.display.Loader;
	import flash.events.Event;
	import flash.events.EventDispatcher;
	
	import org.osmf.events.LoadEvent;
	import org.osmf.media.MediaElement;
	import org.osmf.media.MediaResourceBase;
	import org.osmf.media.URLResource;
	import org.osmf.traits.DisplayObjectTrait;
	import org.osmf.traits.LoadState;
	import org.osmf.traits.LoadTrait;
	import org.osmf.traits.MediaTraitType;
	import org.osmf.utils.TestConstants;

	public class TestImageElementIntegration extends TestImageOrSWFElementIntegration
	{
		override protected function createMediaElement():MediaElement
		{
			return new ImageElement(null, new ImageLoader()); 
		}
		
		override protected function get resourceForMediaElement():MediaResourceBase
		{
			return new URLResource(TestConstants.REMOTE_IMAGE_FILE);
		}
		
		override protected function get expectedBytesTotal():Number
		{
			// Size of resourceForMediaElement.
			return 42803;
		}
		
		public function testSmoothing():void
		{
			var eventDispatcher:EventDispatcher = new EventDispatcher();
			
			var element:ImageElement = createMediaElement() as ImageElement;
			element.resource = resourceForMediaElement;
			element.smoothing = true;
			assertTrue(element.smoothing);
			
			var loadTrait:LoadTrait = element.getTrait(MediaTraitType.LOAD) as LoadTrait;
			
			eventDispatcher.addEventListener(Event.COMPLETE, addAsync(mustReceiveEvent, 10000));
			
			loadTrait.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onLoadStateChange);
			loadTrait.load();
			
			function onLoadStateChange(event:LoadEvent):void
			{
				if (event.loadState == LoadState.READY)
				{
					var displayObjectTrait:DisplayObjectTrait = element.getTrait(MediaTraitType.DISPLAY_OBJECT) as DisplayObjectTrait;
					assertTrue(displayObjectTrait != null);
					var loader:Loader = displayObjectTrait.displayObject as Loader;
					assertTrue(loader != null);
					var bitmap:Bitmap = loader.content as Bitmap;
					assertTrue(bitmap != null);
					assertTrue(bitmap.smoothing);
					
					eventDispatcher.dispatchEvent(new Event(Event.COMPLETE));
				}
			}
		}
	}
}