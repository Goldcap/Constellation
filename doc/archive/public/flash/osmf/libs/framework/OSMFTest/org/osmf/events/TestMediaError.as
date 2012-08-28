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
package org.osmf.events
{
	import flexunit.framework.TestCase;
	
	public class TestMediaError extends TestCase
	{
		public function testGetters():void
		{
			var mediaError:MediaError = createMediaError(999);
			assertTrue(mediaError.errorID == 999);
			assertTrue(mediaError.message == "");
			assertTrue(mediaError.detail == null);
			
			mediaError = createMediaError(MediaErrorCodes.URL_SCHEME_INVALID, "Here are some details...");
			assertTrue(mediaError.errorID == MediaErrorCodes.URL_SCHEME_INVALID);
			assertTrue(mediaError.message == "Invalid URL scheme");
			assertTrue(mediaError.detail == "Here are some details...");

			mediaError = createMediaError(33, "");
			assertTrue(mediaError.errorID == 33);
			assertTrue(mediaError.message == "");
			assertTrue(mediaError.detail == "");
		}
		
		protected function createMediaError(errorID:int, detail:String=null):MediaError
		{
			return new MediaError(errorID, detail);
		}
	}
}