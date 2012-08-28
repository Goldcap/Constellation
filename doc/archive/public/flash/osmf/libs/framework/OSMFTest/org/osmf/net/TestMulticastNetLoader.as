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
	import flexunit.framework.TestCase;
	
	import org.osmf.media.MediaResourceBase;
	import org.osmf.net.StreamingURLResource;

	public class TestMulticastNetLoader extends TestCase
	{
		public function TestMulticastNetLoader(methodName:String=null)
		{
			super(methodName);
		}

		CONFIG::FLASH_10_1	
		{
			public function testCreateNetGroup():void
			{
				// TODO: add test for createNetGroup
			}
		}
		
		public function testCanHandleResource():void
		{
			var loader:MulticastNetLoader = new MulticastNetLoader();
			var resource:MediaResourceBase = new MediaResourceBase();
			assertEquals(loader.canHandleResource(resource), false);
			
			var multicastResource:MulticastResource = new MulticastResource(URL, "live");
			assertEquals(loader.canHandleResource(multicastResource), false);
			
			multicastResource.groupspec = "";
			assertEquals(loader.canHandleResource(multicastResource), false);

			multicastResource.streamName = "";
			assertEquals(loader.canHandleResource(multicastResource), false);

			multicastResource.groupspec = GROUP_SPEC;
			assertEquals(loader.canHandleResource(multicastResource), false);

			multicastResource.groupspec = "";
			multicastResource.streamName = GROUP_NAME;
			assertEquals(loader.canHandleResource(multicastResource), false);

			multicastResource.groupspec = GROUP_SPEC;
			multicastResource.streamName = GROUP_NAME;
			assertEquals(loader.canHandleResource(multicastResource), true);
		}
		
		private static const GROUP_SPEC:String = "G:010121055e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8010c1b0e6f72672e6f736d662e6d756c7469636173742e73747265616d31210e7aaf87509c4271cc1e6459b903a6c18cca200d1ba1aa0f357cb769121d63918b011b00070ae00000fe8140";
		private static const GROUP_NAME:String = "p2pstream";
		private static const URL:String = "rtmfp:";
	}
}