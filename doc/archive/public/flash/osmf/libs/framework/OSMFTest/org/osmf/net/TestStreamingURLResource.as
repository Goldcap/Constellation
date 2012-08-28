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
package org.osmf.net
{
	import org.osmf.media.TestURLResource;
	
	public class TestStreamingURLResource extends TestURLResource
	{
		override protected function createInterfaceObject(... args):Object
		{
			var resource:StreamingURLResource = null;
		
			if (args.length > 0)
			{
				assertTrue(args.length == 1);
				resource = new StreamingURLResource(args[0]);
			}
			
			return resource;
		}
		
		public function testStreamType():void
		{
			var resource:StreamingURLResource = new StreamingURLResource("http://example.com");
			assertTrue(resource.streamType == StreamType.RECORDED);
			
			resource = new StreamingURLResource("http://example.com", StreamType.LIVE);
			assertTrue(resource.streamType == StreamType.LIVE);
		}

		public function testURLIncludesFMSApplicationInstance():void
		{
			var resource:StreamingURLResource = new StreamingURLResource("http://example.com");
			assertTrue(resource.urlIncludesFMSApplicationInstance == false);
			
			resource = new StreamingURLResource("http://example.com", StreamType.LIVE_OR_RECORDED, NaN, NaN, null, true);
			assertTrue(resource.urlIncludesFMSApplicationInstance == true);
		}
	}
}