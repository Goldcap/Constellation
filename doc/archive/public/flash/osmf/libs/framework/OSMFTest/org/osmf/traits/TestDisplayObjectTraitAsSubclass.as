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
package org.osmf.traits
{
	import flash.display.Sprite;
	
	import org.osmf.events.DisplayObjectEvent;
	import org.osmf.utils.DynamicDisplayObjectTrait;

	public class TestDisplayObjectTraitAsSubclass extends TestDisplayObjectTrait
	{
		override protected function createInterfaceObject(... args):Object
		{
			return new DynamicDisplayObjectTrait(args.length > 0 ? args[0] : null, args.length > 1 ? args[1] : 0, args.length > 2 ? args[2] : 0);
		}
		
		override public function testDisplayObject():void
		{
			super.testDisplayObject();
			
			var dynamicDisplayObjectTrait:DynamicDisplayObjectTrait = displayObjectTrait as DynamicDisplayObjectTrait;
			
			dynamicDisplayObjectTrait.addEventListener(DisplayObjectEvent.DISPLAY_OBJECT_CHANGE, eventCatcher);

			assertNull(dynamicDisplayObjectTrait.displayObject);
			
			var displayObject1:Sprite = new Sprite();
			var displayObject2:Sprite = new Sprite();			
			
			dynamicDisplayObjectTrait.displayObject = displayObject1;
			assertTrue(dynamicDisplayObjectTrait.displayObject == displayObject1);
			
			dynamicDisplayObjectTrait.displayObject = displayObject2;
			assertTrue(dynamicDisplayObjectTrait.displayObject == displayObject2);
			
			// Should not cause a change event:
			dynamicDisplayObjectTrait.displayObject = displayObject2;
			
			var vce:DisplayObjectEvent;
			
			assertTrue(events.length == 2);
			
			vce = events[0] as DisplayObjectEvent;
			assertNotNull(vce);
			assertTrue(vce.type == DisplayObjectEvent.DISPLAY_OBJECT_CHANGE);
			assertTrue(vce.oldDisplayObject == null);
			assertTrue(vce.newDisplayObject == displayObject1);
			
			vce = events[1] as DisplayObjectEvent;
			assertNotNull(vce);
			assertTrue(vce.type == DisplayObjectEvent.DISPLAY_OBJECT_CHANGE);
			assertTrue(vce.oldDisplayObject == displayObject1);
			assertTrue(vce.newDisplayObject == displayObject2);
		}
		
		override public function testMediaDimensions():void
		{
			super.testMediaDimensions();
			
			var dynamicDisplayObjectTrait:DynamicDisplayObjectTrait = displayObjectTrait as DynamicDisplayObjectTrait;
			
			dynamicDisplayObjectTrait.addEventListener(DisplayObjectEvent.MEDIA_SIZE_CHANGE, eventCatcher);
			
			// Should not cause a change event:
			dynamicDisplayObjectTrait.setSize(0, 0);
			
			assertTrue(events.length == 0);
			
			dynamicDisplayObjectTrait.setSize(30, 60);
			
			assertTrue(events.length == 1);
			
			var dce:DisplayObjectEvent = events[0] as DisplayObjectEvent;
			assertNotNull(dce);
			assertTrue(dce.type == DisplayObjectEvent.MEDIA_SIZE_CHANGE);
			assertTrue(dce.oldWidth == 0);
			assertTrue(dce.oldHeight == 0);
			assertTrue(dce.newWidth == 30);
			assertTrue(dce.newHeight == 60);
		}
	}
}
