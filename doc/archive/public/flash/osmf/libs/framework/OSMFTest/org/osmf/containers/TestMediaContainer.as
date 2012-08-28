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
package org.osmf.containers
{
	import flash.display.Sprite;
	import flash.errors.IllegalOperationError;
	
	import org.osmf.flexunit.TestCaseEx;
	import org.osmf.layout.HorizontalAlign;
	import org.osmf.layout.LayoutMetadata;
	import org.osmf.layout.LayoutRenderer;
	import org.osmf.layout.LayoutRendererBase;
	import org.osmf.layout.ScaleMode;
	import org.osmf.layout.TesterSprite;
	import org.osmf.layout.VerticalAlign;
	import org.osmf.traits.DisplayObjectTrait;
	import org.osmf.traits.MediaTraitType;
	import org.osmf.utils.DynamicMediaElement;

	public class TestMediaContainer extends TestCaseEx
	{
		public function constructContainer(renderer:LayoutRendererBase=null):MediaContainer
		{
			return new MediaContainer(renderer);
		}
		
		public function testContainerElements():void
		{
			var container:MediaContainer = constructContainer();
			
			// Direct child addition and removal is prohibited:
			//
			
			assertThrows(container.addChild, null);
			assertThrows(container.addChildAt, null, null);
			assertThrows(container.removeChild, null);
			assertThrows(container.removeChildAt, null);
			assertThrows(container.setChildIndex, null, null);
		}
		
		public function testContainerMediaElements():void
		{
			var renderer:LayoutRenderer = new LayoutRenderer();
			var parent:MediaContainer = constructContainer(renderer);
			parent.backgroundColor = 0xff0000;
			parent.backgroundAlpha = 1;
			parent.clipChildren = true;
			
			assertThrows(parent.addMediaElement,null);
			
			var element1:DynamicMediaElement = new DynamicMediaElement();
			var element2:DynamicMediaElement = new DynamicMediaElement();
			
			assertNotNull(parent);
			assertFalse(parent.containsMediaElement(element1));
			assertFalse(parent.containsMediaElement(element2));
			
			parent.addMediaElement(element1);
			assertTrue(parent.containsMediaElement(element1));
			
			assertThrows(parent.addMediaElement,element1);
			
			parent.addMediaElement(element2);
			assertTrue(parent.containsMediaElement(element2));
			
			assertTrue(element1 == parent.removeMediaElement(element1));
			assertFalse(parent.containsMediaElement(element1));
			
			var c2:MediaContainer = constructContainer();
			c2.addMediaElement(element2);
			
			assertFalse(parent.containsMediaElement(element2));
			
			var error:Error;
			try
			{
				parent.removeMediaElement(element1);
			}
			catch(e:Error)
			{
				error = e;
			}
			
			assertNotNull(error);
			assertTrue(error is IllegalOperationError);
			
			assertThrows(parent.removeMediaElement, null);
		}
		
		public function testContainerScaleAndAlign():void
		{
			// Child
			
			var mediaElement:DynamicMediaElement = new DynamicMediaElement();
			
			var viewSprite:Sprite = new TesterSprite();
			var viewTrait:DisplayObjectTrait = new DisplayObjectTrait(viewSprite, 486, 60);
			mediaElement.doAddTrait(MediaTraitType.DISPLAY_OBJECT, viewTrait);
			var layout:LayoutMetadata = new LayoutMetadata();
			mediaElement.addMetadata(LayoutMetadata.LAYOUT_NAMESPACE, layout);
			layout.scaleMode = ScaleMode.NONE;
			layout.verticalAlign = VerticalAlign.MIDDLE;
			layout.horizontalAlign = HorizontalAlign.CENTER;
			
			var container:MediaContainer = constructContainer();
			container.width = 800;
			container.height = 80;
			
			container.addMediaElement(mediaElement);
			
			container.validateNow();
			
			assertEquals(486, viewSprite.width);
			assertEquals(60, viewSprite.height);
			
			assertEquals(800/2 - 486/2, viewSprite.x);
			assertEquals(80/2 - 60/2, viewSprite.y);
		}
		
		public function testContainerAttributes():void
		{
			var container:MediaContainer = constructContainer();
			container.width = 500;
			container.height = 400;
			
			assertEquals(NaN,container.backgroundColor);
			assertEquals(NaN,container.backgroundAlpha);
			
			container.backgroundColor = 0xFF00FF;
			assertEquals(0xFF00FF, container.backgroundColor);
			
			container.backgroundColor = 0xFF00FF;
			assertEquals(0xFF00FF, container.backgroundColor);
			
			container.backgroundAlpha = 0.5;
			assertEquals(0.5, container.backgroundAlpha);
			
			container.backgroundAlpha = 0.5;
			assertEquals(0.5, container.backgroundAlpha);
			
			assertFalse(container.clipChildren);
			container.clipChildren = true;
			assertTrue(container.clipChildren);
			
			container.clipChildren = true;
			assertTrue(container.clipChildren);
			
			container.clipChildren = false;
			assertFalse(container.clipChildren);
			
			container.validateNow();
			assertEquals(500, container.width);
			assertEquals(400, container.height);
		}
		
		public function testConstructor():void
		{
			var renderer:LayoutRenderer = new LayoutRenderer();
			var container:MediaContainer = constructContainer(renderer);
			assertEquals(renderer, container.layoutRenderer);
			
			var container2:MediaContainer = new MediaContainer();
			assertNotNull(container2.layoutRenderer);
		}
	}
}