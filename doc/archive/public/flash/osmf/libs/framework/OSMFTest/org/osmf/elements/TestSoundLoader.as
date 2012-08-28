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
	import org.osmf.elements.audioClasses.SoundLoadTrait;
	import org.osmf.events.MediaError;
	import org.osmf.media.MediaResourceBase;
	import org.osmf.media.MediaType;
	import org.osmf.media.URLResource;
	import org.osmf.traits.LoadTrait;
	import org.osmf.traits.LoaderBase;
	import org.osmf.traits.TestLoaderBase;
	import org.osmf.utils.NullResource;
	import org.osmf.utils.TestConstants;
	
	public class TestSoundLoader extends TestLoaderBase
	{
		override protected function createInterfaceObject(... args):Object
		{
			return new SoundLoader();
		}

		override protected function createLoadTrait(loader:LoaderBase, resource:MediaResourceBase):LoadTrait
		{
			return new SoundLoadTrait(loader, resource);
		}
		
		override protected function get successfulResource():MediaResourceBase
		{
			return new URLResource(TestConstants.LOCAL_SOUND_FILE);
		}

		override protected function get failedResource():MediaResourceBase
		{
			return new URLResource(TestConstants.LOCAL_INVALID_SOUND_FILE);
		}

		override protected function get unhandledResource():MediaResourceBase
		{
			return new URLResource("http://example.com/blah.xml");
		}
		
		override protected function verifyMediaErrorOnLoadFailure(error:MediaError):void
		{
		}
		
		override public function testCanHandleResource():void
		{
			super.testCanHandleResource();
			
			// Verify some valid resources.
			assertTrue(loader.canHandleResource(new URLResource("file:///audio.mp3")));
			assertTrue(loader.canHandleResource(new URLResource("assets/audio.mp3")));
			assertTrue(loader.canHandleResource(new URLResource("http://example.com/audio.mp3")));
			assertTrue(loader.canHandleResource(new URLResource("http://example.com/audio.mp3?param=value")));
			assertTrue(loader.canHandleResource(new URLResource("audio.mp3")));
			assertTrue(loader.canHandleResource(new URLResource("http://example.com/audio")));
			assertTrue(loader.canHandleResource(new URLResource("http://example.com/audio?param=.mp3")));
			assertTrue(loader.canHandleResource(new URLResource("file:///audio.m4a")));
			assertTrue(loader.canHandleResource(new URLResource("assets/audio.m4a")));
			assertTrue(loader.canHandleResource(new URLResource("http://example.com/audio.m4a")));
			assertTrue(loader.canHandleResource(new URLResource("http://example.com/audio.m4a?param=value")));
			assertTrue(loader.canHandleResource(new URLResource("audio.m4a")));
			assertTrue(loader.canHandleResource(new URLResource("http://example.com/audio?param=.m4a")));
			
			
			// And some invalid ones.
			assertFalse(loader.canHandleResource(new URLResource("http://example.com/audio.foo")));
			assertFalse(loader.canHandleResource(new URLResource("http://example.com/audio.mp31")));
			assertFalse(loader.canHandleResource(new URLResource("http://example.com/audio.m4a1")));
			assertFalse(loader.canHandleResource(new URLResource("foo")));		
			assertFalse(loader.canHandleResource(new URLResource("")));
			assertFalse(loader.canHandleResource(new URLResource(null)));
			assertFalse(loader.canHandleResource(new NullResource()));
			assertFalse(loader.canHandleResource(null));
					
			// Verify some valid resources based on metadata information
			var resource:URLResource = new URLResource("http://example.com/test");
			resource.mediaType = MediaType.AUDIO;
			assertTrue(loader.canHandleResource(resource));
			
			resource = new URLResource("http://example.com/test");
			resource.mimeType = "audio/mpeg";
			assertTrue(loader.canHandleResource(resource));

			resource = new URLResource("http://example.com/test");
			resource.mediaType = MediaType.AUDIO;
			resource.mimeType = "audio/mpeg";
			assertTrue(loader.canHandleResource(resource));

			// Add some invalid cases based on metadata information
			//
			
			
			resource = new URLResource("http://example.com/test");
			resource.mediaType = MediaType.VIDEO;
			assertFalse(loader.canHandleResource(resource));

			resource = new URLResource("http://example.com/test");
			resource.mimeType = "video/x-flv";
			assertFalse(loader.canHandleResource(resource));
			
			resource = new URLResource("http://example.com/test");
			resource.mediaType = MediaType.VIDEO;
			resource.mimeType = "video/x-flv";
			assertFalse(loader.canHandleResource(resource));

			resource = new URLResource("http://example.com/test");
			resource.mediaType = MediaType.SWF;
			assertFalse(loader.canHandleResource(resource));

			resource = new URLResource("http://example.com/test");
			resource.mimeType ="Invalid Mime Type";
			assertFalse(loader.canHandleResource(resource));

			resource = new URLResource("http://example.com/test");
			resource.mediaType = MediaType.IMAGE;
			resource.mimeType = "Invalid Mime Type";
			assertFalse(loader.canHandleResource(resource));

			resource = new URLResource("http://example.com/test");
			resource.mediaType = MediaType.VIDEO;
			assertFalse(loader.canHandleResource(resource));

			resource = new URLResource("http://example.com/test");
			resource.mediaType = MediaType.AUDIO;
			resource.mimeType = "Invalid Mime Type";
			assertFalse(loader.canHandleResource(resource));

			resource = new URLResource("http://example.com/test");
			resource.mediaType = MediaType.IMAGE;
			resource.mimeType = "video/x-flv";
			assertFalse(loader.canHandleResource(resource));

			resource = new URLResource("http://example.com/test");
			resource.mediaType = MediaType.IMAGE;
			resource.mimeType = "audio/mpeg";
			assertFalse(loader.canHandleResource(resource));

			resource = new URLResource("http://example.com/test");
			resource.mediaType = MediaType.VIDEO;
			resource.mimeType = "audio/mpeg";
			assertFalse(loader.canHandleResource(resource));

			resource = new URLResource("http://example.com/test");
			resource.mediaType = MediaType.AUDIO;
			resource.mimeType = "video/x-flv";
			assertFalse(loader.canHandleResource(resource));
		}
	}
}