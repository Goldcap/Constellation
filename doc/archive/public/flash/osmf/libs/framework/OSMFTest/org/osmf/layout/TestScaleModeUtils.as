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
package org.osmf.layout
{
	import flash.geom.Point;
	
	import flexunit.framework.TestCase;

	public class TestScaleModeUtils extends TestCase
	{
		public function testGetScaledSize():void
		{
			// ScaleMode.NONE
			//
			
			var point:Point = ScaleModeUtils.getScaledSize(ScaleMode.NONE, 1000, 900, 800, 700);
			assertTrue(point.x == 800);
			assertTrue(point.y == 700);
			
			point = ScaleModeUtils.getScaledSize(ScaleMode.NONE, 1000, 900, NaN, NaN);
			assertTrue(point.x == 1000);
			assertTrue(point.y == 900);
			
			// ScaleMode.STRETCH
			//
			
			point = ScaleModeUtils.getScaledSize(ScaleMode.STRETCH, 1000, 900, 800, 700);
			assertTrue(point.x == 1000);
			assertTrue(point.y == 900);

			// ScaleMode.LETTERBOX
			//
			
			point = ScaleModeUtils.getScaledSize(ScaleMode.LETTERBOX, 1000, 500, 880, 800);
			assertTrue(point.x == 550);
			assertTrue(point.y == 500);

			point = ScaleModeUtils.getScaledSize(ScaleMode.LETTERBOX, 500, 1000, 800, 700);
			assertTrue(point.x == 500);
			assertTrue(point.y == 437.5);

			// ScaleMode.ZOOM
			//
			
			point = ScaleModeUtils.getScaledSize(ScaleMode.ZOOM, 1000, 500, 800, 700);
			assertTrue(point.x == 1000);
			assertTrue(point.y == 875);
			
			point = ScaleModeUtils.getScaledSize(ScaleMode.ZOOM, 500, 1000, 880, 800);
			assertTrue(point.x == 1100);
			assertTrue(point.y == 1000);
		}
	}
}