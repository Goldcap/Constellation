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
	import org.osmf.flexunit.TestCaseEx;

	public class TestLayoutMetadata extends TestCaseEx
	{
		public function testLayoutUtils():void
		{
			var lm:LayoutMetadata = new LayoutMetadata();
			assertNotNull(lm);
			
			assertEquals(NaN, lm.index);
			assertEquals(null, lm.scaleMode);
			assertEquals(null, lm.verticalAlign);
			assertEquals(null, lm.horizontalAlign);
			assertEquals(true, lm.snapToPixel);
			assertEquals(LayoutMode.NONE, lm.layoutMode);
			
			assertEquals(NaN, lm.x);
			assertEquals(NaN, lm.y);			
			assertEquals(NaN, lm.width);
			assertEquals(NaN, lm.height);
			
			assertEquals(NaN, lm.percentX);
			assertEquals(NaN, lm.percentY);
			assertEquals(NaN, lm.percentWidth);
			assertEquals(NaN, lm.percentHeight);
						
			assertEquals(NaN, lm.left);
			assertEquals(NaN, lm.right);
			assertEquals(NaN, lm.top);
			assertEquals(NaN, lm.bottom);
			
			assertEquals(NaN, lm.paddingLeft);
			assertEquals(NaN, lm.paddingRight);
			assertEquals(NaN, lm.paddingTop);
			assertEquals(NaN, lm.paddingBottom);
			
			// Check all routines twice; first time around, a metadata object
			// could be created.
			for (var i:int = 0; i<2; i++)
			{
				lm.index = 1;
				assertEquals(1, lm.index);
				
				lm.verticalAlign = VerticalAlign.BOTTOM;
				assertEquals(VerticalAlign.BOTTOM, lm.verticalAlign);
				
				lm.horizontalAlign = HorizontalAlign.CENTER;
				assertEquals(HorizontalAlign.CENTER, lm.horizontalAlign);
				
				lm.scaleMode = ScaleMode.LETTERBOX;
				assertEquals(ScaleMode.LETTERBOX, lm.scaleMode);
				
				lm.snapToPixel = true;
				assertTrue(lm.snapToPixel);
				
				lm.layoutMode = LayoutMode.HORIZONTAL;
				assertEquals(LayoutMode.HORIZONTAL, lm.layoutMode); 
				
				lm.x = 1;
				assertEquals(1, lm.x);
				
				lm.y = 2;
				assertEquals(2, lm.y);
				
				lm.width = 3;
				assertEquals(3, lm.width);
				
				lm.height = 4;
				assertEquals(4, lm.height);
				
				lm.percentX = 5;
				assertEquals(5, lm.percentX);
				
				lm.percentY = 6;
				assertEquals(6, lm.percentY);
				
				lm.percentWidth = 7;
				assertEquals(7, lm.percentWidth);
				
				lm.percentWidth = 8;
				assertEquals(8, lm.percentWidth);
				
				lm.left = 9;
				assertEquals(9, lm.left);
				
				lm.right = 10;
				assertEquals(10, lm.right);
				
				lm.top = 11;
				assertEquals(11, lm.top);
				
				lm.bottom = 12;
				assertEquals(12, lm.bottom);
				
				lm.paddingLeft = 13;
				assertEquals(13, lm.paddingLeft);
				
				lm.paddingRight = 14;
				assertEquals(14, lm.paddingRight);
				
				lm.paddingTop = 15;
				assertEquals(15, lm.paddingTop);
				
				lm.paddingBottom = 16;
				assertEquals(16, lm.paddingBottom);
			} 
		}
	}
}