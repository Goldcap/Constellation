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
	import flexunit.framework.TestCase;

	public class TestOSMFStrings extends TestCase
	{
		public function testResourceStringFunction():void
		{
			// Keys and values differ.
			assertTrue(OSMFStrings.getString(OSMFStrings.COMPOSITE_TRAIT_NOT_FOUND) != OSMFStrings.COMPOSITE_TRAIT_NOT_FOUND);
			
			// Irrelevant params are ignored.
			assertTrue(OSMFStrings.getString(OSMFStrings.COMPOSITE_TRAIT_NOT_FOUND, ["foo"]) != OSMFStrings.COMPOSITE_TRAIT_NOT_FOUND);
			assertTrue(OSMFStrings.getString(OSMFStrings.COMPOSITE_TRAIT_NOT_FOUND, ["foo"]) == OSMFStrings.getString(OSMFStrings.COMPOSITE_TRAIT_NOT_FOUND));
			
			// Specifying a bad key gives a canned message.
			assertTrue(OSMFStrings.getString("notAValidResourceString") == "No string for resource notAValidResourceString"); 
		}
	}
}