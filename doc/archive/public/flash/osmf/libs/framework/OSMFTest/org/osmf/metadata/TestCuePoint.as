/*****************************************************
*  
*  Copyright 2009 Akamai Technologies, Inc.  All Rights Reserved.
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
*  The Initial Developer of the Original Code is Akamai Technologies, Inc.
*  Portions created by Akamai Technologies, Inc. are Copyright (C) 2009 Akamai 
*  Technologies, Inc. All Rights Reserved. 
*  
*****************************************************/
package org.osmf.metadata
{
	import flexunit.framework.TestCase;

	public class TestCuePoint extends TestCase
	{
		public function testCuePoint():void
		{
			var params:Object = new Object();
			params["100"] = "a";
			params["101"] = "b";
			var cuePoint:CuePoint = new CuePoint(CuePointType.ACTIONSCRIPT, 120, "test cue point", params, 5);
			
			assertEquals(CuePointType.ACTIONSCRIPT, cuePoint.type);
			assertEquals(120, cuePoint.time);
			assertEquals("test cue point", cuePoint.name);
			
			params = cuePoint.parameters;
			assertEquals("a", params["100"]);
			assertEquals("b", params["101"]);
			
			assertEquals(5, cuePoint.duration);
		}
	}
}
