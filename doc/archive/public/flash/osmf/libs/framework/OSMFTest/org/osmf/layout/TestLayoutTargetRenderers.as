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
*  
*  The Initial Developer of the Original Code is Adobe Systems Incorporated.
*  Portions created by Adobe Systems Incorporated are Copyright (C) 2010 Adobe Systems 
*  Incorporated. All Rights Reserved. 
*  
*****************************************************/

package org.osmf.layout
{
	import org.osmf.flexunit.TestCaseEx;

	public class TestLayoutTargetRenderers extends TestCaseEx
	{
		public function testLayoutTargetRenderers():void
		{
			var renderer:LayoutRenderer = new LayoutRenderer();
			var lts:LayoutTargetSprite = new LayoutTargetSprite();
			var ltr:LayoutTargetRenderers = new LayoutTargetRenderers(lts);
			
			assertNotNull(ltr);
			
			renderer.container = lts;
			assertEquals(ltr.containerRenderer, renderer);
			
			var renderer2:LayoutRenderer = new LayoutRenderer();
			var lts2:LayoutTargetSprite = new LayoutTargetSprite();
			renderer2.container = lts2;
			
			renderer2.addTarget(lts);
			assertEquals(ltr.parentRenderer, renderer2);
			
			renderer2.removeTarget(lts);
			assertNull(ltr.parentRenderer);
			
			lts.dispatchEvent(new LayoutTargetEvent(LayoutTargetEvent.UNSET_AS_LAYOUT_RENDERER_CONTAINER));
			
			renderer2.addTarget(lts);
			
			renderer2.container = null;
			
			renderer.container = null;
			assertNull(ltr.containerRenderer);
			
			lts.dispatchEvent(new LayoutTargetEvent(LayoutTargetEvent.UNSET_AS_LAYOUT_RENDERER_CONTAINER));
			lts.dispatchEvent(new LayoutTargetEvent(LayoutTargetEvent.SET_AS_LAYOUT_RENDERER_CONTAINER));
			
			lts.dispatchEvent(new LayoutTargetEvent(LayoutTargetEvent.REMOVE_FROM_LAYOUT_RENDERER));
			lts.dispatchEvent(new LayoutTargetEvent(LayoutTargetEvent.ADD_TO_LAYOUT_RENDERER));
		}
		
	}
}