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
package org.osmf.elements.compositeClasses
{
	import org.osmf.elements.SerialElement;
	import org.osmf.flexunit.TestCaseEx;
	import org.osmf.layout.*;
	import org.osmf.media.MediaElement;
	import org.osmf.metadata.*;

	public class TestCompositeDisplayObjectTrait extends TestCaseEx
	{
		public function testCompositedisplayObjectTrait():void
		{
			var aggregator:TraitAggregator = new TraitAggregator();
			
			var owner:MediaElement = new SerialElement();
			var trait:CompositeDisplayObjectTrait
				= new CompositeDisplayObjectTrait(aggregator, owner); 
			
			// No layout renderer class being assigned, the default
			// should have been used:
			assertTrue(trait.layoutRenderer is LayoutRenderer);
			
			var md:Metadata = new Metadata();
			md.addValue(MetadataNamespaces.LAYOUT_RENDERER_TYPE, MyLayoutRenderer);
			owner.addMetadata(MetadataNamespaces.LAYOUT_RENDERER_TYPE, md);
			
			// Layout should now have changed to be of type
			// MyLayoutRenderer:
			assertTrue(trait.layoutRenderer is MyLayoutRenderer);
			
			owner.removeMetadata(MetadataNamespaces.LAYOUT_RENDERER_TYPE);
			
			// Should be 'default' once more: 
			assertTrue(trait.layoutRenderer is LayoutRenderer);
			
			// Assigning class that is not LayoutRenderer implementing:
			md = new Metadata();
			md.addValue(MetadataNamespaces.LAYOUT_RENDERER_TYPE, Array);
			assertThrows(owner.addMetadata,MetadataNamespaces.LAYOUT_RENDERER_TYPE, md);
			
			// Should be 'default' once more: 
			assertTrue(trait.layoutRenderer is LayoutRenderer);
		}
	}
	
}

import org.osmf.layout.*;
import flash.events.EventDispatcher;
import flash.geom.Rectangle;

internal class MyLayoutRenderer extends LayoutRendererBase
{
	
}