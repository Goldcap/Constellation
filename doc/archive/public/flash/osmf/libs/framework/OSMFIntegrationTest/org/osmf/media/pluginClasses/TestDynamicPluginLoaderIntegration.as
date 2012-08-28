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
	import org.osmf.media.MediaFactory;
	import org.osmf.media.MediaResourceBase;
	import org.osmf.media.URLResource;
	import org.osmf.traits.LoadTrait;
	import org.osmf.traits.LoaderBase;
	import org.osmf.traits.TestLoaderBase;
	import org.osmf.utils.IntegrationTestUtils;
	import org.osmf.utils.Version;
	
	public class TestDynamicPluginLoaderIntegration extends TestLoaderBase
	{
		override public function setUp():void
		{
			mediaFactory  = new MediaFactory();

			super.setUp();
		}
		
		override public function tearDown():void
		{
			super.tearDown();
			
			mediaFactory = null;
		}
		
		override protected function createInterfaceObject(... args):Object
		{
			return new DynamicPluginLoader(mediaFactory, Version.version);
		}
		
		override protected function createLoadTrait(loader:LoaderBase, resource:MediaResourceBase):LoadTrait
		{
			return new PluginLoadTrait(loader, resource);
		}
		
		override protected function get successfulResource():MediaResourceBase
		{
			return new URLResource(IntegrationTestUtils.REMOTE_VALID_PLUGIN_SWF_URL);
		}

		override protected function get failedResource():MediaResourceBase
		{
			return new URLResource(IntegrationTestUtils.REMOTE_INVALID_PLUGIN_SWF_URL);
		}

		override protected function get unhandledResource():MediaResourceBase
		{
			return new URLResource(IntegrationTestUtils.REMOTE_UNHANDLED_PLUGIN_RESOURCE_URL);
		}

		private var mediaFactory:MediaFactory;
	}
}