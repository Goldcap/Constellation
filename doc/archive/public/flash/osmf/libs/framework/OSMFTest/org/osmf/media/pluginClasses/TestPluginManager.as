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
package org.osmf.media.pluginClasses
{
	import __AS3__.vec.Vector;
	
	import flexunit.framework.TestCase;
	
	import org.osmf.elements.VideoElement;
	import org.osmf.events.*;
	import org.osmf.media.*;
	import org.osmf.net.DynamicStreamingResource;
	import org.osmf.utils.*;

	public class TestPluginManager extends TestCase
	{
		public function TestPluginManager(methodName:String=null)
		{
			super(methodName);
		}
		
		override public function setUp():void
		{
			mediaFactory  = new MediaFactory();
			pluginManager = new PluginManager(mediaFactory);

			super.setUp();
		}
		
		public function testMediaFactoryAccess():void
		{
			assertTrue(pluginManager.mediaFactory == mediaFactory);
		}
		
		public function testLoadPluginWithValidClassResource():void
		{
			var pluginResource:PluginInfoResource = new PluginInfoResource(new SimpleVideoPluginInfo);
			pluginManager.addEventListener(PluginManagerEvent.PLUGIN_LOAD, addAsync(onPluginManagerEvent, 500));
			pluginManager.addEventListener(PluginManagerEvent.PLUGIN_LOAD_ERROR, onPluginManagerEvent);
			pluginManager.loadPlugin(pluginResource);
			
			function onPluginManagerEvent(event:PluginManagerEvent):void
			{	
				if (event.type == PluginManagerEvent.PLUGIN_LOAD)
				{
					// expected
					assertTrue(event.resource != null);
				}
				else
				{
					assertTrue(false);
				}
			}
		}

		public function testCheckLoadStatusOfStaticPluginWithValidClassResource():void
		{
			var pluginResource:PluginInfoResource = new PluginInfoResource(new SimpleVideoPluginInfo);
			pluginManager.addEventListener(PluginManagerEvent.PLUGIN_LOAD, addAsync(onPluginManagerEvent, 500));
			pluginManager.addEventListener(PluginManagerEvent.PLUGIN_LOAD_ERROR, onPluginManagerEvent);
			pluginManager.loadPlugin(pluginResource);
			
			function onPluginManagerEvent(event:PluginManagerEvent):void
			{	
				assertTrue(event.type == PluginManagerEvent.PLUGIN_LOAD);
			}
		}

		public function testLoadStatusOfStaticPluginWithInvalidClassResource():void
		{
			var pluginResource:PluginInfoResource = new PluginInfoResource(new InvalidVersionPluginInfo);
			pluginManager.addEventListener(PluginManagerEvent.PLUGIN_LOAD, onPluginManagerEvent);
			pluginManager.addEventListener(PluginManagerEvent.PLUGIN_LOAD_ERROR, addAsync(onPluginManagerEvent, 500));
			pluginManager.loadPlugin(pluginResource);
			
			function onPluginManagerEvent(event:PluginManagerEvent):void
			{	
				assertTrue(event.type == PluginManagerEvent.PLUGIN_LOAD_ERROR);
				assertTrue(event.resource == pluginResource);
			}
		}
	
		public function testLoadStaticPluginWithInvalidClassResource():void
		{
			var pluginResource:PluginInfoResource = new PluginInfoResource(new InvalidVersionPluginInfo);
			pluginManager.addEventListener(PluginManagerEvent.PLUGIN_LOAD, onPluginManagerEvent);
			pluginManager.addEventListener(PluginManagerEvent.PLUGIN_LOAD_ERROR, addAsync(onPluginManagerEvent, 500));
			pluginManager.loadPlugin(pluginResource);
			
			function onPluginManagerEvent(event:PluginManagerEvent):void
			{	
				assertTrue(event.type == PluginManagerEvent.PLUGIN_LOAD_ERROR);
				assertTrue(event.resource == pluginResource);
			}
		}
	
		public function testLoadDynamicPluginWithInvalidURLResource():void
		{
			var pluginResource:URLResource = new URLResource("http://myinvalidurl.com/foo.swf");
			pluginManager.addEventListener(PluginManagerEvent.PLUGIN_LOAD, onPluginManagerEvent);
			pluginManager.addEventListener(PluginManagerEvent.PLUGIN_LOAD_ERROR, addAsync(onPluginManagerEvent, 500));
			pluginManager.loadPlugin(pluginResource);
			
			function onPluginManagerEvent(event:PluginManagerEvent):void
			{	
				assertTrue(event.type == PluginManagerEvent.PLUGIN_LOAD_ERROR);
				assertTrue(event.resource == pluginResource);
			}
		}

		public function testCheckLoadStatusOfDynamicPluginWithInvalidURLResource():void
		{
			var pluginResource:URLResource = new URLResource("http://myinvalidurl.com/foo.swf");
			pluginManager.addEventListener(PluginManagerEvent.PLUGIN_LOAD, onPluginManagerEvent);
			pluginManager.addEventListener(PluginManagerEvent.PLUGIN_LOAD_ERROR, addAsync(onPluginManagerEvent, 500));
			pluginManager.loadPlugin(pluginResource);
			
			function onPluginManagerEvent(event:PluginManagerEvent):void
			{
				assertTrue(event.type == PluginManagerEvent.PLUGIN_LOAD_ERROR);
				assertTrue(event.resource == pluginResource);
			}
		}
		
		public function testLoadPluginWithInvalidParameter():void
		{
			var pluginResource:URLResource = new DynamicStreamingResource("rtmp://example.com/vod");
			pluginManager.addEventListener(PluginManagerEvent.PLUGIN_LOAD, onPluginManagerEvent);
			pluginManager.addEventListener(PluginManagerEvent.PLUGIN_LOAD_ERROR, addAsync(onPluginManagerEvent, 500));
			pluginManager.loadPlugin(pluginResource);
			
			function onPluginManagerEvent(event:PluginManagerEvent):void
			{
				assertTrue(event.type == PluginManagerEvent.PLUGIN_LOAD_ERROR);
				assertTrue(event.resource == pluginResource);
			}
		}
		
		public function testLoadPluginWithCustomMetadata():void
		{
			var metadataNS:String = "http://sentinel/namespace";
			var pluginInfo:CreateOnLoadPluginInfo = new CreateOnLoadPluginInfo();
			
			assertNull(pluginInfo.pluginResource);
			
			var resource:PluginInfoResource = new PluginInfoResource(pluginInfo);
			resource.addMetadataValue(metadataNS, "foo");
			
			pluginManager.loadPlugin(resource);
			
			assertNotNull(pluginInfo.pluginResource);
			assertNotNull(pluginInfo.pluginResource.getMetadataValue(metadataNS));
		}
		
		public function testLoadPluginWithDefaultMetadata():void
		{
			var pluginInfo:CreateOnLoadPluginInfo = new CreateOnLoadPluginInfo();
			
			assertNull(pluginInfo.pluginResource);
			
			var resource:PluginInfoResource = new PluginInfoResource(pluginInfo);
			
			pluginManager.loadPlugin(resource);
			
			assertNotNull(pluginInfo.pluginResource);
			var injectedFactory:MediaFactory = pluginInfo.pluginResource.getMetadataValue(PluginInfo.PLUGIN_MEDIAFACTORY_NAMESPACE) as MediaFactory;
			assertNotNull(injectedFactory);
			assertEquals(injectedFactory, mediaFactory);
		}
		
		public function testLoadCreateOnLoadPlugin():void
		{
			var pluginInfo:CreateOnLoadPluginInfo = new CreateOnLoadPluginInfo();
			assertEquals(0, pluginInfo.createCount);
			var resource:PluginInfoResource = new PluginInfoResource(pluginInfo);
			
			pluginManager.loadPlugin(resource);
			assertEquals(0, pluginInfo.createCount);
			
			pluginManager.loadPlugin(resource); // No-op, plugin already loaded.
			assertEquals(0, pluginInfo.createCount);
			
			// Creating a MediaElement that the plugin can handle should trigger
			// the creation  function.
			var mediaElement:MediaElement = mediaFactory.createMediaElement(new URLResource("http://example.com/video.flv"));
			assertTrue(mediaElement == null);
			assertEquals(1, pluginInfo.createCount);
		}
		
		public function testLoadCreateOnLoadPluginWithCallbacks():void
		{
			mediaFactory = new DefaultMediaFactory();
			pluginManager = new PluginManager(mediaFactory);
			
			var pluginInfo:CreateOnLoadPluginInfo = new CreateOnLoadPluginInfo();
			assertEquals(0, pluginInfo.createCount);
			var resource:PluginInfoResource = new PluginInfoResource(pluginInfo);
			
			pluginManager.loadPlugin(resource);
			assertEquals(0, pluginInfo.createCount);

			// Creating a MediaElement that a default factory item can handle
			// should trigger the callback function.
			var mediaElement:MediaElement = mediaFactory.createMediaElement(new URLResource("http://example.com/image1.jpg"));
			assertTrue(mediaElement != null);
			assertEquals(0, pluginInfo.createCount);

			mediaElement = mediaFactory.createMediaElement(new URLResource("http://example.com/image2.jpg"));
			assertTrue(mediaElement != null);
			assertEquals(0, pluginInfo.createCount);
		}
		
		public function testLoadPluginWithCreationCallback():void
		{
			mediaFactory  = new DefaultMediaFactory();
			pluginManager = new PluginManager(mediaFactory);

			var createdElements:Array = [];
			
			// First create a MediaElement from the PluginManager.
			var createdElement:MediaElement = pluginManager.mediaFactory.createMediaElement(new URLResource("http://example.com/image.jpg"));
			assertTrue(createdElement != null);
			
			// Then load a plugin with a creation callback.
			var items:Vector.<MediaFactoryItem> = new Vector.<MediaFactoryItem>();
			items.push
				( new MediaFactoryItem
					( "id"
					, function(_:MediaResourceBase):Boolean {return true;}
					, function():VideoElement {return new VideoElement();}
					)
				);
			var pluginInfo:PluginInfo = new PluginInfo(items, onMediaElementCreate);
			
			var resource:PluginInfoResource = new PluginInfoResource(pluginInfo);
			
			pluginManager.loadPlugin(resource);
			
			// Once it's loaded, it should have been informed of the previously created
			// element.
			assertTrue(createdElements.length == 1);
			assertTrue(createdElements[0] == createdElement);
			
			// If we create another MediaElement, the plugin should be informed again.
			var createdElement2:MediaElement = pluginManager.mediaFactory.createMediaElement(new URLResource("http://example.com/audio.mp3"));
			assertTrue(createdElement2 != null);

			assertTrue(createdElements.length == 2);
			assertTrue(createdElements[1] == createdElement2);
			
			function onMediaElementCreate(mediaElement:MediaElement):void
			{
				createdElements.push(mediaElement);
			}
		}

		public function testLoadPluginWithDifferentVersions():void
		{
			// Note: This test will need updating any time the framework
			// version number changes.  I've added the following assertion
			// to make this explicit.
			assertTrue(Version.version == "1.5");
			
			// First number is minimum supported framework version.
			// Second number is plugin's framework version.
			
			// We can load older plugins.
			assertPluginHasValidVersion("0.9", "0.9", true);
			assertPluginHasValidVersion("0.9", "0.95", true);
			
			// But not newer plugins.
			assertPluginHasValidVersion("0.9", "1.9", false);
			
			// And we can't load older plugins if their version is less
			// than the minimum.
			assertPluginHasValidVersion("0.95", "0.9", false);
			
			assertPluginHasValidVersion("0.95", "0.95", true);
			assertPluginHasValidVersion("0.95", "1.9", false);
			assertPluginHasValidVersion("1.1", "0.9", false);
			assertPluginHasValidVersion("1.1", "0.95", false);
			assertPluginHasValidVersion("1.1", "1.9", false);
			
			// Verify we take the number of digits in the minor version
			// into account.
			assertPluginHasValidVersion("0.90", "0.90", true);
			assertPluginHasValidVersion("0.9", "0.90", true);
			assertPluginHasValidVersion("0.90", "0.9", true);
			assertPluginHasValidVersion("0.81", "0.90", true);
			assertPluginHasValidVersion("0.81", "0.9", true);
			assertPluginHasValidVersion("0.90", "0.81", false);
			assertPluginHasValidVersion("0.9", "0.81", false);
		}
		
		private function assertPluginHasValidVersion(minimumSupportedFrameworkVersion:String, pluginFrameworkVersion:String, mustBeTrue:Boolean):void
		{
			var loader:VersionCheckPluginLoader = new VersionCheckPluginLoader(minimumSupportedFrameworkVersion);
			assertEquals(mustBeTrue, loader.isVersionValid(pluginFrameworkVersion));
		}
		
		private var mediaFactory:MediaFactory;
		private var pluginManager:PluginManager;
	}
}

