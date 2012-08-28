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
	import flexunit.framework.TestCase;

	public class TestLayoutRendererBase extends TestCase
	{
		public function testLayoutRenderer():void
		{
			var renderer:LayoutRendererBase = new LayoutRendererBase();
			assertNotNull(renderer);
			
			var c:TesterLayoutTargetSprite = new TesterLayoutTargetSprite();
			
			var l1:TesterLayoutTargetSprite = new TesterLayoutTargetSprite();
			l1.setIntrinsicDimensions(50,200);
			
			var l2:TesterLayoutTargetSprite = new TesterLayoutTargetSprite();
			l2.setIntrinsicDimensions(100,150);
			
			renderer.container = c;
			
			renderer.addTarget(l1);
			renderer.addTarget(l2);
			
			renderer.validateNow();
			
			assertEquals(50, l1.measuredWidth);
			assertEquals(200, l1.measuredHeight);
			
			assertEquals(100, l2.measuredWidth);
			assertEquals(150, l2.measuredHeight);
			
			// The base renderer does not calculate aggregate bounds:
			assertEquals(NaN, c.measuredWidth);
			assertEquals(NaN, c.measuredHeight);
		}
		
	}
}