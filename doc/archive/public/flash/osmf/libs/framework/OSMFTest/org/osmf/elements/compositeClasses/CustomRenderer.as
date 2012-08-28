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
	import flash.events.Event;
	import flash.geom.Rectangle;
	
	import org.osmf.layout.ILayoutTarget;
	import org.osmf.layout.LayoutRendererBase;

	/**
	 * Custom renderer class, used to test LayoutRendererBase
	 */	
	internal class CustomRenderer extends LayoutRendererBase
	{
		override protected function calculateTargetBounds(target:ILayoutTarget):Rectangle
		{
			var result:Rectangle = super.calculateTargetBounds(target);
			
			if (target != context)
			{
				dispatchEvent(new Event("calculateTargetBounds"));
			}
			
			return result;	
		}
		
		override protected function applyTargetLayout(target:ILayoutTarget, availableWidth:Number, availableHeight:Number):Rectangle
		{
			var result:Rectangle = super.applyTargetLayout(target, availableWidth, availableHeight);
			
			dispatchEvent(new Event("applyTargetLayout"));
			
			return result;
		}
		
		
	}
}