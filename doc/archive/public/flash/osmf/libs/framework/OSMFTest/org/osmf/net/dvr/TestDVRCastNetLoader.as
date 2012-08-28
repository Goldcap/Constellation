/*****************************************************
*  
*  Copyright 2010 Adobe Systems Incorporated.  All Rights Reserved.
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
*  The Initial Developer of the Original Code is Adobe Systems Incorporated.
*  Portions created by Adobe Systems Incorporated are Copyright (C) 2010 Adobe Systems 
*  Incorporated. All Rights Reserved. 
*  
*****************************************************/

package org.osmf.net.dvr
{
	import org.osmf.flexunit.TestCaseEx;
	import org.osmf.net.StreamingURLResource;

	public class TestDVRCastNetLoader extends TestCaseEx
	{
		public function testDVRCastNetLoader():void
		{
			var nl:DVRCastNetLoader = new DVRCastNetLoaderForTest(null, false);
			assertNotNull(nl);
		}
		
		
		public function testFM934():void
		{
			// This unit test is derived based on bug FM-934. It makes sure that
			// DVRCastNetLoader.canHandleResource is not fooled by a bogus dvr streamType
			// without corresponding metadata under DVRCastConstants.STREAM_INFO_KEY and
			// DVRCastConstants.RECORDING_INFO_KEY.
			var nl:DVRCastNetLoader = new DVRCastNetLoaderForTest(null, false);
			assertNotNull(nl);
			
			var resource:StreamingURLResource 
				= new StreamingURLResource("http://catherine.corp.adobe.com/seq/bmw_beacons.f4v", "dvr");
			assertTrue(!nl.canHandleResource(resource));
		}
		
	}
}