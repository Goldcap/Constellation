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
	
	import org.osmf.events.LoadEvent;
	import org.osmf.events.MediaErrorEvent;
	import org.osmf.media.MediaPlayer;
	import org.osmf.media.URLResource;
	import org.osmf.traits.LoadState;
	
	public class TestF4MLoader extends TestCase
	{
		public function testDynamicVideoLoad():void
		{
			var loader:F4MLoader = new F4MLoader();			
			var res1:URLResource = new URLResource('http://flipside.corp.adobe.com/testing/oconnell/manifest/progressive.f4m');
						
			var finished:Function = addAsync(function():void{}, 3000);
						
			var proxy:F4MElement = new F4MElement(res1, loader);
			proxy.resource = res1;
			var player:MediaPlayer = new MediaPlayer();
			player.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onLoaded);
			player.autoPlay = false;
			player.addEventListener(MediaErrorEvent.MEDIA_ERROR, onLoadedError);
			
			player.media = proxy;
			
			function onLoadedError(event:MediaErrorEvent):void
			{
				assertTrue(false);
			}
			
			function onLoaded(event:LoadEvent):void
			{
				if (event.loadState == LoadState.READY)
				{
					player.media = null;
					finished(null);
				}
			}		
		}

		public function testSingleVideoLoad():void
		{
			var finished:Function = addAsync(function():void{}, 3000);
			
			var loader:F4MLoader = new F4MLoader();			
			var res1:URLResource = new URLResource('http://flipside.corp.adobe.com/testing/oconnell/manifest/dynamic_Streaming.f4m');
			var proxy:F4MElement = new F4MElement(res1, loader);
			
			var player:MediaPlayer = new MediaPlayer();
			player.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onLoaded);
			player.autoPlay = false;
			
			player.addEventListener(MediaErrorEvent.MEDIA_ERROR, onLoadedError);
			
			player.media = proxy;
			
			function onLoadedError(event:MediaErrorEvent):void
			{
				assertTrue(false);
			}
			
			function onLoaded(event:LoadEvent):void
			{
				if (event.loadState == LoadState.READY)
				{
					player.media = null;
					finished(null);
				}
			}	
		}
		
		public function testSingleExternalLoads():void
		{
			var finished:Function = addAsync(function():void{}, 5000);
			
			var loader:F4MLoader = new F4MLoader();			
			var res1:URLResource = new URLResource('http://flipside.corp.adobe.com/testing/oconnell/manifest/externals.f4m');
			var proxy:F4MElement = new F4MElement(null, loader);
			proxy.resource = res1;
			var player:MediaPlayer = new MediaPlayer();
			player.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onLoaded);
			player.autoPlay = false;
			player.addEventListener(MediaErrorEvent.MEDIA_ERROR, onLoadedError);
			
			player.media = proxy;
			
			function onLoadedError(event:MediaErrorEvent):void
			{
				assertTrue(false);
			}
			
			function onLoaded(event:LoadEvent):void
			{
				if (event.loadState == LoadState.READY)
				{
					player.media = null;
					finished(null);
				}
			}	
		}
						
		public function testDRMAdditionalHeaderURL2():void
		{
			var finished:Function = addAsync(function():void{}, 5000);
			
			var loader:F4MLoader = new F4MLoader();			
			var res1:URLResource = new URLResource('http://fms1j009f.corp.adobe.com/zeri-media/Fragments_Source_Media_Protected/215/production/external/avatar_4000.f4m');
			var proxy:F4MElement = new F4MElement(null, loader);
			proxy.resource = res1;
			var player:MediaPlayer = new MediaPlayer();
			player.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onLoaded);
			player.autoPlay = false;
			player.addEventListener(MediaErrorEvent.MEDIA_ERROR, onLoadedError);
			
			player.media = proxy;
			
			function onLoadedError(event:MediaErrorEvent):void
			{
				assertTrue(false);
			}
			
			function onLoaded(event:LoadEvent):void
			{
				if (event.loadState == LoadState.READY)
				{
					player.media = null;
					finished(null);
				}
			}	
		}
	
		
		public function testDRMAdditionalHeader():void
		{
			var finished:Function = addAsync(function():void{}, 5000);
			
			var loader:F4MLoader = new F4MLoader();			
			var res1:URLResource = new URLResource('http://fms1j009f.corp.adobe.com/zeri-media/Fragments_Source_Media_Protected/12%20LEGEND.f4m');
			var proxy:F4MElement = new F4MElement(null, loader);
			proxy.resource = res1;
			var player:MediaPlayer = new MediaPlayer();
			player.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onLoaded);
			player.autoPlay = false;
			player.addEventListener(MediaErrorEvent.MEDIA_ERROR, onLoadedError);
			
			player.media = proxy;
			
			function onLoadedError(event:MediaErrorEvent):void
			{
				assertTrue(false);
			}
			
			function onLoaded(event:LoadEvent):void
			{
				if (event.loadState == LoadState.READY)
				{
					player.media = null;
					finished(null);
				}
			}	
		}
		
	}
}