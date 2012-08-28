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
	
	import org.osmf.events.MediaErrorEvent;
	import org.osmf.media.MediaFactory;
	import org.osmf.media.URLResource;
	import org.osmf.traits.LoadTrait;

	public class TestF4MLoader extends TestCase
	{
		public function testConstructorParams():void
		{
			// Test that both constructors work...
			var loader:F4MLoader = new F4MLoader(null);
			var loader2:F4MLoader = new F4MLoader(new MediaFactory());
			var loader3:F4MLoader = new F4MLoader();
			assertTrue(true);	
		}
		
		public function testCanHandle():void
		{		
			var loader:F4MLoader = new F4MLoader();
			
			var res1:URLResource = new URLResource('http://example.com/manifest.f4m');
			
			var res2:URLResource = new URLResource('http://example.com/manifest.f4m');
			res2.mimeType = F4MLoader.F4M_MIME_TYPE;
			
			var res3:URLResource = new URLResource('http://example.com/manifest.blah');
			res3.mimeType = F4MLoader.F4M_MIME_TYPE;
			
			var res4:URLResource = new URLResource('http://example.com/manifest.blah');
			
			var res5:URLResource = new URLResource('http://example.com/manifest.blah');
			res5.mimeType = 'application/blah+xml';
										
			assertTrue(loader.canHandleResource(res1));
			assertTrue(loader.canHandleResource(res2));
			assertTrue(loader.canHandleResource(res3));
			assertFalse(loader.canHandleResource(res4));
			assertFalse(loader.canHandleResource(res5));
									
		}
		
		public function testUnloadFail():void
		{
			var loader:F4MLoader = new F4MLoader();
			var loadTrait:LoadTrait = new LoadTrait(loader, new URLResource("http://example.com/notValid"));
			var errorThrown:Boolean = false;
			try
			{
				loader.unload(loadTrait);
			}
			catch(error:Error)
			{
				errorThrown = true;	
			}
			assertTrue(errorThrown);			
		}
		
		public function testLoadFail():void
		{
			
			var loader:F4MLoader = new F4MLoader();
			var loadTrait:LoadTrait = new LoadTrait(loader, new URLResource("http://example.com/notValid.f4m"));
			loadTrait.addEventListener(MediaErrorEvent.MEDIA_ERROR, addAsync(onError, 10000));
			
			var errorThrown:Boolean = false;
			var errorThrownLoader:Boolean = false;
			
			loader.load(loadTrait);
					
			function onError(event:MediaErrorEvent):void
			{
			}
				
		}
		
		
		
	}
}