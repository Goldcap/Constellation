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
	import org.osmf.utils.InterfaceTestCase;
	
	public class TestURLResource extends InterfaceTestCase
	{
		public function testGetURL():void
		{
			// Simple URL
			var resource:URLResource = createURLResource("http://www.example.com");
			assertTrue(resource != null);
			assertTrue(resource.url == "http://www.example.com");
			
			// Empty URL
			resource = createURLResource("");
			assertTrue(resource != null);
			assertTrue(resource.url == "");

			// null URL
			resource = createURLResource(null);
			assertTrue(resource != null);
			assertTrue(resource.url == null);			
		}

		public function testMetadata():void
		{
			// Simple URL
			var resource:URLResource = createURLResource("http://www.example.com");
			assertTrue(resource != null);
			
			resource.addMetadataValue("foo1", "foovalue1");
			resource.addMetadataValue("foo2", "foovalue2a");
			resource.addMetadataValue("foo2", "foovalue2b");
			resource.addMetadataValue("foo3", "foovalue3");
			
			assertTrue(resource.metadataNamespaceURLs.length == 3);
			assertTrue(resource.getMetadataValue("foo1") == "foovalue1");
			assertTrue(resource.getMetadataValue("foo2") == "foovalue2b");
			assertTrue(resource.getMetadataValue("foo3") == "foovalue3");
			
			resource.removeMetadataValue("foo3");

			assertTrue(resource.metadataNamespaceURLs.length == 2);
			assertTrue(resource.getMetadataValue("foo3") == null);
		}
		
		private function createURLResource(url:String):URLResource
		{
			return createInterfaceObject(url) as URLResource;
		}
				
		override protected function createInterfaceObject(... args):Object
		{
			var resource:URLResource = null;
		
			if (args.length > 0)
			{
				assertTrue(args.length == 1);
				resource = new URLResource(args[0]);
			}
			
			return resource;
		}
	}
}