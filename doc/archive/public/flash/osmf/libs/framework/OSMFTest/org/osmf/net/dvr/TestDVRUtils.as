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

	public class TestDVRUtils extends TestCaseEx
	{
		public function testDVRUtils():void
		{
			var dvrUtils:DVRUtils = new DVRUtils();
			var offset:Number;
			
			offset = DVRUtils.calculateOffset(0, 40, 100);
			assertEquals(60, offset);
			
			offset = DVRUtils.calculateOffset(60, 40, 100);
			assertEquals(60, offset);
			
			offset = DVRUtils.calculateOffset(20, 40, 100);
			assertEquals(60, offset);
			
			offset = DVRUtils.calculateOffset(20, 0, 100);
			assertEquals(20, offset);
			
			offset = DVRUtils.calculateOffset(110, 30, 100);
			assertEquals(70, offset);
			
			offset = DVRUtils.calculateOffset(110, 0, 100);
			assertEquals(100, offset);
			
			offset = DVRUtils.calculateOffset(0, 0, 100);
			assertEquals(0, offset);
			
			offset = DVRUtils.calculateOffset(100, 0, 50);
			assertEquals(50, offset);
			
			offset = DVRUtils.calculateOffset(0, 100, 50);
			assertEquals(0, offset);
		}
	}
}