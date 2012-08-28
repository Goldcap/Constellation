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
	import flexunit.framework.TestCase;
	
	import org.osmf.events.PluginManagerEvent;
	import org.osmf.media.MediaFactory;
	import org.osmf.media.URLResource;
	import org.osmf.utils.IntegrationTestUtils;

	public class TestPluginManagerIntegration extends TestCase
	{
		public function TestPluginManagerIntegration(methodName:String=null)
		{
			super(methodName);
		}
		
		override public function setUp():void
		{
			mediaFactory  = new MediaFactory();
			pluginManager = new PluginManager(mediaFactory);

			super.setUp();
		}
		
		public function testLoadDynamicPluginWithValidURLResource():void
		{
			var pluginResource:URLResource 
				= new URLResource(IntegrationTestUtils.REMOTE_VALID_PLUGIN_SWF_URL);
			pluginManager.addEventListener(PluginManagerEvent.PLUGIN_LOAD, addAsync(onPluginLoadEvent, TEST_TIME));
			pluginManager.addEventListener(PluginManagerEvent.PLUGIN_LOAD_ERROR, onPluginLoadEvent);
			pluginManager.loadPlugin(pluginResource);
			
			function onPluginLoadEvent(event:PluginManagerEvent):void
			{	
				assertTrue(event.type == PluginManagerEvent.PLUGIN_LOAD);
			}
		}

		public function testLoadDynamicPluginWithValidURLResourceAndInvalidVersion():void
		{
			// If this test fails, be sure to recompile the plugin SWF with an invalid version
			// (e.g. 0.1).
			var pluginResource:URLResource 
				= new URLResource(IntegrationTestUtils.REMOTE_VALID_PLUGIN_WITH_INVALID_VERSION_SWF_URL);
			pluginManager.addEventListener(PluginManagerEvent.PLUGIN_LOAD, onPluginLoadEvent);
			pluginManager.addEventListener(PluginManagerEvent.PLUGIN_LOAD_ERROR, addAsync(onPluginLoadEvent, TEST_TIME));
			pluginManager.loadPlugin(pluginResource);
			
			function onPluginLoadEvent(event:PluginManagerEvent):void
			{	
				assertTrue(event.type == PluginManagerEvent.PLUGIN_LOAD_ERROR);
			}
		}
		
		public function testLoadTwiceDynamicPluginWithValidURLResource():void
		{
			var pluginResource:URLResource 
				= new URLResource(IntegrationTestUtils.REMOTE_VALID_PLUGIN_SWF_URL);
			pluginManager.addEventListener(PluginManagerEvent.PLUGIN_LOAD, addAsync(onPluginLoadEvent, TEST_TIME));
			pluginManager.addEventListener(PluginManagerEvent.PLUGIN_LOAD_ERROR, onPluginLoadEvent);
			pluginManager.loadPlugin(pluginResource);
			
			function onPluginLoadEvent(event:PluginManagerEvent):void
			{	
				assertTrue(event.type == PluginManagerEvent.PLUGIN_LOAD);
			}
			
			function onPluginLoadEvent2(event:PluginManagerEvent):void
			{	
				assertTrue(event.type == PluginManagerEvent.PLUGIN_LOAD)
			}
		}

		public function testLoadDynamicPluginWithInvalidURLResource():void
		{
			var pluginResource:URLResource 
				= new URLResource(IntegrationTestUtils.REMOTE_INVALID_PLUGIN_SWF_URL);
			pluginManager.addEventListener(PluginManagerEvent.PLUGIN_LOAD, onPluginLoadEvent);
			pluginManager.addEventListener(PluginManagerEvent.PLUGIN_LOAD_ERROR, addAsync(onPluginLoadEvent, TEST_TIME));
			pluginManager.loadPlugin(pluginResource);
			
			function onPluginLoadEvent(event:PluginManagerEvent):void
			{	
				assertTrue(event.type == PluginManagerEvent.PLUGIN_LOAD_ERROR);
			}
		}
		
		private static const TEST_TIME:int = 1000;

		private var mediaFactory:MediaFactory;
		private var pluginManager:PluginManager;
	}
}