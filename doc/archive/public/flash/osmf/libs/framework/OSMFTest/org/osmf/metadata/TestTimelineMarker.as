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
package org.osmf.metadata
{
	import flexunit.framework.TestCase;

	public class TestTimelineMarker extends TestCase
	{
		public function testTimelineMarker():void
		{
			var marker:TimelineMarker = new TimelineMarker(120, 5);
			
			assertEquals(120, marker.time);
			assertEquals(5, marker.duration);

			marker = new TimelineMarker(37);
			
			assertEquals(37, marker.time);
			assertEquals(NaN, marker.duration);
		}
	}
}
