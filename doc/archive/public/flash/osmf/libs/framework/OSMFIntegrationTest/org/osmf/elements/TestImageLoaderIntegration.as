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
	import org.osmf.elements.loaderClasses.LoaderLoadTrait;
	import org.osmf.events.MediaError;
	import org.osmf.events.MediaErrorCodes;
	import org.osmf.media.MediaResourceBase;
	import org.osmf.media.URLResource;
	import org.osmf.traits.LoadTrait;
	import org.osmf.traits.LoaderBase;
	import org.osmf.traits.TestLoaderBase;
	import org.osmf.utils.TestConstants;
	
	public class TestImageLoaderIntegration extends TestLoaderBase
	{
		override protected function createInterfaceObject(... args):Object
		{
			return new ImageLoader();
		}
		
		override protected function createLoadTrait(loader:LoaderBase, resource:MediaResourceBase):LoadTrait
		{
			return new LoaderLoadTrait(loader, resource);
		}

		override protected function get successfulResource():MediaResourceBase
		{
			return new URLResource(TestConstants.REMOTE_IMAGE_FILE);
		}

		override protected function get failedResource():MediaResourceBase
		{
			return new URLResource(TestConstants.REMOTE_INVALID_IMAGE_FILE);
		}

		override protected function get unhandledResource():MediaResourceBase
		{
			return new URLResource(TestConstants.REMOTE_STREAMING_VIDEO);
		}
		
		override protected function verifyMediaErrorOnLoadFailure(error:MediaError):void
		{
			assertTrue(error.errorID == MediaErrorCodes.IO_ERROR ||
					   error.errorID == MediaErrorCodes.SECURITY_ERROR);
		}
	}
}