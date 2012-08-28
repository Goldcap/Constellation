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
	import flash.display.Sprite;
	
	import org.osmf.flexunit.TestCaseEx;

	public class TestLayoutTargetEvent extends TestCaseEx
	{
		public function testLayoutTargetEvent():void
		{
			var renderer:LayoutRenderer = new LayoutRenderer();
			var layoutTarget:LayoutTargetSprite = new LayoutTargetSprite();
			var displayObject:Sprite = new Sprite();
				
			var lte:LayoutTargetEvent
				= new LayoutTargetEvent
					( LayoutTargetEvent.UNSET_AS_LAYOUT_RENDERER_CONTAINER
					, true, true
					, renderer
					, layoutTarget
					, displayObject
					, 10
					);
					
			assertEquals(LayoutTargetEvent.UNSET_AS_LAYOUT_RENDERER_CONTAINER, lte.type);
			assertTrue(lte.bubbles);
			assertTrue(lte.cancelable);
			assertEquals(lte.layoutRenderer, renderer);
			assertEquals(lte.layoutTarget, layoutTarget);
			assertEquals(lte.displayObject, displayObject);
			assertEquals(lte.index, 10);
			
			var clone:LayoutTargetEvent = lte.clone() as LayoutTargetEvent;
			
			assertEquals(LayoutTargetEvent.UNSET_AS_LAYOUT_RENDERER_CONTAINER, clone.type);
			assertTrue(clone.bubbles);
			assertTrue(clone.cancelable);
			assertEquals(clone.layoutRenderer, renderer);
			assertEquals(clone.layoutTarget, layoutTarget);
			assertEquals(clone.displayObject, displayObject);
			assertEquals(clone.index, 10);
			
		}
		
	}
}