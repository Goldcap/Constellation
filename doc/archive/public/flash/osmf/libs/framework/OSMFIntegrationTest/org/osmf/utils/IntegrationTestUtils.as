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
	public class IntegrationTestUtils
	{
		// Root assets folder (currently only being used for SWFs)
		public static const INTEGRATION_TEST_ASSET_ROOT:String = "http://flipside.corp.adobe.com/strobe/integration_test_assets/";

		public static function set integrationTestAssetsRoot(value:String):void
		{
			REMOTE_VALID_PLUGIN_SWF_URL				= value + PLUGIN_NAME + "_valid.swf";
			REMOTE_VALID_PLUGIN_WITH_INVALID_VERSION_SWF_URL = value + PLUGIN_NAME + "_validWithBadVersion.swf";
			REMOTE_INVALID_PLUGIN_SWF_URL			= value + PLUGIN_NAME + "_invalid.swf";
			REMOTE_UNHANDLED_PLUGIN_RESOURCE_URL	= value + PLUGIN_NAME + "_unhandled.foo";
		}

		public static var REMOTE_VALID_SWF_URL:String = INTEGRATION_TEST_ASSET_ROOT + "BasicValid.swf";
		
		public static var REMOTE_VALID_PLUGIN_SWF_URL:String = INTEGRATION_TEST_ASSET_ROOT + PLUGIN_NAME + "_valid.swf";
		public static var REMOTE_VALID_PLUGIN_WITH_INVALID_VERSION_SWF_URL:String = INTEGRATION_TEST_ASSET_ROOT + PLUGIN_NAME + "_validWithBadVersion.swf";
		public static var REMOTE_INVALID_PLUGIN_SWF_URL:String = INTEGRATION_TEST_ASSET_ROOT + PLUGIN_NAME + "_invalid.swf";
		public static var REMOTE_UNHANDLED_PLUGIN_RESOURCE_URL:String = INTEGRATION_TEST_ASSET_ROOT + PLUGIN_NAME + "_unhandled.foo";
		
		public static const REMOTE_AVM1_SWF_FILE:String = "http://flipside.corp.adobe.com/test_assets/swf/redraw_80x60.swf";
		
		private static const PLUGIN_NAME:String = "SamplePlugin";
	}
}