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
package org.osmf.utils
{
	import org.osmf.events.MediaError;
	import org.osmf.events.MediaErrorCodes;
	import org.osmf.media.MediaResourceBase;
	import org.osmf.media.URLResource;
	import org.osmf.traits.LoadTrait;
	import org.osmf.traits.LoaderBase;
	import org.osmf.traits.TestLoaderBase;
	
	public class TestHTTPLoader extends TestLoaderBase
	{
		override protected function createInterfaceObject(... args):Object
		{
			var loader:HTTPLoader = null;
			
			if (NetFactory.neverUseMockObjects)
			{
				loader = new HTTPLoader();
			}
			else
			{
				loader = new MockHTTPLoader();
			}
			
			return loader;
		}

		override protected function createLoadTrait(loader:LoaderBase, resource:MediaResourceBase):LoadTrait
		{
			var mockLoader:MockHTTPLoader = loader as MockHTTPLoader;
			if (mockLoader)
			{
				if (resource == SUCCESSFUL_RESOURCE)
				{
					mockLoader.setExpectationForURL(SUCCESSFUL_RESOURCE.url, true, null);
				}
				else if (resource == FAILED_RESOURCE)
				{
					mockLoader.setExpectationForURL(FAILED_RESOURCE.url, false, null);
				}
				else if (resource == UNHANDLED_RESOURCE)
				{
					mockLoader.setExpectationForURL(UNHANDLED_RESOURCE.url, false, null);
				}
			}	
			return new HTTPLoadTrait(loader, resource);
		}
		
		override protected function get successfulResource():MediaResourceBase
		{
			return SUCCESSFUL_RESOURCE;
		}

		override protected function get failedResource():MediaResourceBase
		{
			return FAILED_RESOURCE;
		}

		override protected function get unhandledResource():MediaResourceBase
		{
			return UNHANDLED_RESOURCE;
		}
		
		override protected function verifyMediaErrorOnLoadFailure(error:MediaError):void
		{
			assertTrue(		error.errorID == MediaErrorCodes.IO_ERROR
						||	error.errorID == MediaErrorCodes.SECURITY_ERROR
					  );
		}
		
		override public function testCanHandleResource():void
		{
			super.testCanHandleResource();
			
			// Verify some valid resources.
			assertTrue(loader.canHandleResource(new URLResource("http://example.com")));
			assertTrue(loader.canHandleResource(new URLResource("http://example.com/video.flv")));
			assertTrue(loader.canHandleResource(new URLResource("http://example.com/script.php?param=value")));
			assertTrue(loader.canHandleResource(new URLResource("https://example.com")));
			assertTrue(loader.canHandleResource(new URLResource("https://example.com/video.flv")));
			assertTrue(loader.canHandleResource(new URLResource("https://example.com/script.php?param=value")));
			assertTrue(loader.canHandleResource(new URLResource("example.com/video.flv")));
			assertTrue(loader.canHandleResource(new URLResource("folder/video.flv")));
			assertTrue(loader.canHandleResource(new URLResource("video.flv")));
			
			// And some invalid ones.
			assertFalse(loader.canHandleResource(new URLResource("file:///audio.mp3")));
			assertFalse(loader.canHandleResource(new URLResource("httpt://example.com")));
			assertFalse(loader.canHandleResource(new URLResource("")));
			assertFalse(loader.canHandleResource(new URLResource(null)));
			assertFalse(loader.canHandleResource(new NullResource()));
			assertFalse(loader.canHandleResource(null));
		}
		
		private static const SUCCESSFUL_RESOURCE:URLResource = new URLResource(TestConstants.REMOTE_IMAGE_FILE);
		private static const FAILED_RESOURCE:URLResource = new URLResource(TestConstants.REMOTE_INVALID_IMAGE_FILE);
		private static const UNHANDLED_RESOURCE:URLResource = new URLResource("ftp://example.com");
	}
}