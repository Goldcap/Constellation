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
	
	import flexunit.framework.TestCase;
	
	import org.osmf.layout.ScaleMode;
	import org.osmf.traits.DisplayObjectTrait;
	import org.osmf.traits.MediaTraitType;
	import org.osmf.utils.DynamicMediaElement;

	public class TestLayoutRenderer extends TestCase
	{
		public function testSingleChild():void
		{
			// Child
			
			var mediaElement:DynamicMediaElement = new DynamicMediaElement();
				
			var viewSprite:Sprite = new TesterSprite();
			var displayObjectTrait:DisplayObjectTrait = new DisplayObjectTrait(viewSprite);
			mediaElement.doAddTrait(MediaTraitType.DISPLAY_OBJECT, displayObjectTrait);

			var lm:LayoutMetadata = new LayoutMetadata();
			assertNotNull(lm);

			lm.percentX = 10;
			lm.percentY = 10;
			lm.percentWidth = 80;
			lm.percentHeight = 80;

			mediaElement.addMetadata(LayoutMetadata.LAYOUT_NAMESPACE, lm);
						
			// Container
			
			var container:LayoutTargetSprite = new LayoutTargetSprite();
			
			var layoutRenderer:LayoutRenderer = new LayoutRenderer();
			layoutRenderer.container = container;
			layoutRenderer.addTarget(MediaElementLayoutTarget.getInstance(mediaElement));
			layoutRenderer.invalidate();
			
			layoutRenderer.validateNow();
			
			assertEquals(0, viewSprite.x);
			assertEquals(0, viewSprite.y);
			assertEquals(0, viewSprite.width);
			assertEquals(0, viewSprite.height);
			
			container.width = 300;
			container.height = 200;
			layoutRenderer.validateNow();
			
			assertEquals(30, viewSprite.x);
			assertEquals(20, viewSprite.y);
			assertEquals(240, viewSprite.width);
			assertEquals(160, viewSprite.height);
			
			lm.percentX = 5;
			
			layoutRenderer.validateNow();
			
			assertEquals(15, viewSprite.x);
			
			lm.x = 50;
			layoutRenderer.validateNow();
			
			assertEquals(50, viewSprite.x);
			assertEquals(20, viewSprite.y);
			assertEquals(240, viewSprite.width);
			assertEquals(160, viewSprite.height);
			
			lm.y = 1;
			layoutRenderer.validateNow();
			
			assertEquals(50, viewSprite.x);
			assertEquals(1, viewSprite.y);
			assertEquals(240, viewSprite.width);
			assertEquals(160, viewSprite.height);
			
			lm.width = 100;
			layoutRenderer.validateNow();
			
			assertEquals(50, viewSprite.x);
			assertEquals(1, viewSprite.y);
			assertEquals(100, viewSprite.width);
			assertEquals(160, viewSprite.height);
			
			lm.height = 51;
			layoutRenderer.validateNow();
			
			assertEquals(50, viewSprite.x);
			assertEquals(1, viewSprite.y);
			assertEquals(100, viewSprite.width);
			assertEquals(51, viewSprite.height);
			
			lm.x = NaN;
			lm.y = NaN;
			lm.width = NaN;
			lm.height = NaN;
			layoutRenderer.validateNow();
			
			assertEquals(15, viewSprite.x);
			assertEquals(20, viewSprite.y);
			assertEquals(240, viewSprite.width);
			assertEquals(160, viewSprite.height);
			
			lm.left = 60;
			lm.top = 15;
			lm.percentX = NaN; // Set NaN, for relative takes precedence over anchoring.
			lm.percentY = NaN;
			
			layoutRenderer.validateNow();
			
			assertEquals(60, viewSprite.x);
			assertEquals(15, viewSprite.y);
			assertEquals(240, viewSprite.width);
			assertEquals(160, viewSprite.height);
		
			lm.right = 10;
			lm.bottom = 10;
			lm.percentWidth = NaN; // Set NaN, for relative takes precedence over anchoring.
			lm.percentHeight = NaN;
			layoutRenderer.validateNow();
			
			assertEquals(230, viewSprite.width);
			assertEquals(175, viewSprite.height);
			
			lm.paddingLeft = 1;
			lm.paddingTop = 2;
			lm.paddingRight = 3;
			lm.paddingBottom = 4;
			
			layoutRenderer.validateNow();
			
			assertEquals(61, viewSprite.x);
			assertEquals(17, viewSprite.y);
			assertEquals(226, viewSprite.width);
			assertEquals(169, viewSprite.height);
		}
		
		public function testSingleChildScaleAndAlign():void
		{
			// Child
			
			var mediaElement:DynamicMediaElement = new DynamicMediaElement();
				
			var viewSprite:Sprite = new TesterSprite();
			var displayObjectTrait:DisplayObjectTrait = new DisplayObjectTrait(viewSprite, 50, 50);
			mediaElement.doAddTrait(MediaTraitType.DISPLAY_OBJECT, displayObjectTrait);
			var layout:LayoutMetadata = new LayoutMetadata();
			layout.percentWidth = layout.percentHeight = 80;
			layout.percentX = layout.percentY = 10;
			layout.scaleMode = ScaleMode.NONE; // this is the default, actually.
			layout.verticalAlign = VerticalAlign.MIDDLE;
			layout.horizontalAlign = HorizontalAlign.RIGHT;
			
			mediaElement.addMetadata(LayoutMetadata.LAYOUT_NAMESPACE, layout);
			
			// Container
			
			var container:LayoutTargetSprite = new LayoutTargetSprite();
			
			var layoutRenderer:LayoutRenderer = new LayoutRenderer();
			layoutRenderer.container = container;
			
			var melt:ILayoutTarget = layoutRenderer.addTarget(MediaElementLayoutTarget.getInstance(mediaElement));
			layoutRenderer.invalidate();
			
			container.width = 800;
			container.height = 600;
			
			layoutRenderer.validateNow();
			
			// Without any scaling, we'd expect the element to be at 80x60 - 640x480.
			// However scaling is set to 'NONE' - meaning intrinsic width and height get bounced (50x50):
			
			assertEquals(50, melt.measuredWidth);
			assertEquals(50, melt.measuredHeight);
			
			assertEquals(80 + 640 - 50, melt.displayObject.x);
			assertEquals(60 + 480 / 2 - 50 / 2, melt.displayObject.y);
			
			layout.verticalAlign = VerticalAlign.MIDDLE;
			layout.horizontalAlign = HorizontalAlign.CENTER;
			layoutRenderer.validateNow();
			
			assertEquals(80 + 640 / 2 - 50 / 2, melt.displayObject.x);
			assertEquals(60 + 480 / 2 - 50 / 2, melt.displayObject.y);
			
			layout.verticalAlign = VerticalAlign.MIDDLE;
			layout.horizontalAlign = HorizontalAlign.LEFT;
			layoutRenderer.validateNow();
			
			assertEquals(80, melt.displayObject.x);
			assertEquals(60 + 480 / 2 - 50 / 2, melt.displayObject.y);
			
			layout.verticalAlign = VerticalAlign.TOP;
			layout.horizontalAlign = HorizontalAlign.LEFT;
			layoutRenderer.validateNow();
			
			assertEquals(80, melt.displayObject.x);
			assertEquals(60, melt.displayObject.y);
			
			layout.verticalAlign = VerticalAlign.TOP;
			layout.horizontalAlign = HorizontalAlign.CENTER;
			layoutRenderer.validateNow();
			
			assertEquals(80 + 640 / 2 - 50 / 2, melt.displayObject.x);
			assertEquals(60, melt.displayObject.y);
			
			layout.verticalAlign = VerticalAlign.TOP;
			layout.horizontalAlign = HorizontalAlign.RIGHT;
			layoutRenderer.validateNow();
			
			assertEquals(80 + 640 - 50, melt.displayObject.x);
			assertEquals(60, melt.displayObject.y);	
			
			layout.verticalAlign = VerticalAlign.BOTTOM;
			layout.horizontalAlign = HorizontalAlign.LEFT;
			layoutRenderer.validateNow();
			
			assertEquals(80, melt.displayObject.x);
			assertEquals(480 + 60 - 50, melt.displayObject.y);
			
			layout.verticalAlign = VerticalAlign.BOTTOM;
			layout.horizontalAlign = HorizontalAlign.CENTER;
			layoutRenderer.validateNow();
			
			assertEquals(80 + 640 / 2 - 50 / 2, melt.displayObject.x);
			assertEquals(480 + 60 - 50, melt.displayObject.y);
			
			layout.verticalAlign = VerticalAlign.BOTTOM;
			layout.horizontalAlign = HorizontalAlign.RIGHT;
			layoutRenderer.validateNow();
			
			assertEquals(80 + 640 - 50, melt.displayObject.x);
			assertEquals(480 + 60 - 50, melt.displayObject.y);	
		}
		
		public function testBottomUp():void
		{
			// Element with given dimenions: 400x800
			var mediaElement:DynamicMediaElement = new DynamicMediaElement();
				
			var viewSprite:Sprite = new TesterSprite();
			var displayObjectTrait:DisplayObjectTrait = new DisplayObjectTrait(viewSprite);
			mediaElement.doAddTrait(MediaTraitType.DISPLAY_OBJECT, displayObjectTrait);
			var layout:LayoutMetadata = new LayoutMetadata();
			layout.width = 400;
			layout.height = 800;
			
			mediaElement.addMetadata(LayoutMetadata.LAYOUT_NAMESPACE, layout);
			
			// Container without any dimenion settings: should bubble up from child element:
			var container:LayoutTargetSprite = new LayoutTargetSprite();
			
			var layoutRenderer:LayoutRenderer = new LayoutRenderer();
			layoutRenderer.container = container;
			
			assertEquals(NaN, container.measuredWidth);
			assertEquals(NaN, container.measuredHeight);
			
			layoutRenderer.addTarget(MediaElementLayoutTarget.getInstance(mediaElement));
			layoutRenderer.validateNow();
			
			assertEquals(400, container.measuredWidth);
			assertEquals(800, container.measuredHeight);
		}
	
		public function testBottomUpTwoLevels():void
		{
			// Element with given dimenions: 400x800
			var mediaElement:DynamicMediaElement = new DynamicMediaElement();
				
			var viewSprite:Sprite = new TesterSprite();
			var displayObjectTrait:DisplayObjectTrait = new DisplayObjectTrait(viewSprite);
			mediaElement.doAddTrait(MediaTraitType.DISPLAY_OBJECT, displayObjectTrait);
			
			var layout:LayoutMetadata = new LayoutMetadata();
			layout.width = 400;
			layout.height = 800;
			
			mediaElement.addMetadata(LayoutMetadata.LAYOUT_NAMESPACE, layout);
			
			// Container without any dimension settings: should bubble up from child element:
			var container:LayoutTargetSprite = new LayoutTargetSprite();
			
			var layoutRenderer:LayoutRenderer = new LayoutRenderer();
			layoutRenderer.container = container;
			
			assertEquals(NaN, container.measuredWidth);
			assertEquals(NaN, container.measuredHeight);
			
			layoutRenderer.addTarget(MediaElementLayoutTarget.getInstance(mediaElement));
			layoutRenderer.validateNow();
			
			assertEquals(400, container.measuredWidth);
			assertEquals(800, container.measuredHeight);
			
			// Container that holds the previous container: dimensions should 
			// bubble up another level:
			var container2:LayoutTargetSprite = new LayoutTargetSprite();
			
			var layoutRenderer2:LayoutRenderer = new LayoutRenderer();
			layoutRenderer2.container = container2;
			
			assertEquals(NaN, container2.measuredWidth);
			assertEquals(NaN, container2.measuredHeight);
			
			layoutRenderer2.addTarget(container);
			layoutRenderer2.validateNow();
			
			assertEquals(400, container2.measuredWidth);
			assertEquals(800, container2.measuredHeight);
		}
		
		public function testOrdering():void
		{
			var renderer:LayoutRenderer = new LayoutRenderer();
			var container:LayoutTargetSprite = new LayoutTargetSprite();
			renderer.container = container;
			
			var t1:TesterLayoutTargetSprite = new TesterLayoutTargetSprite();
			t1.layoutMetadata.index = 8;
			
			var t2:TesterLayoutTargetSprite = new TesterLayoutTargetSprite();
			t2.layoutMetadata.index = 2;
			
			var t3:TesterLayoutTargetSprite = new TesterLayoutTargetSprite();
			t3.layoutMetadata.index = 2;
			
			var t4:TesterLayoutTargetSprite = new TesterLayoutTargetSprite();
			
			renderer.addTarget(t1);
			renderer.addTarget(t2);
			renderer.addTarget(t3);
			renderer.addTarget(t4);
			
			renderer.validateNow();
			
			assertEquals(t4, container.getChildAt(0));
			assertEquals(t3, container.getChildAt(1));
			assertEquals(t2, container.getChildAt(2));
			assertEquals(t1, container.getChildAt(3));
			
			t4.layoutMetadata.index = 4;
			
			renderer.validateNow();
			
			assertEquals(t3, container.getChildAt(0));
			assertEquals(t2, container.getChildAt(1));
			assertEquals(t4, container.getChildAt(2));
			assertEquals(t1, container.getChildAt(3));
		}
		
		public function testPaddingAndRounding():void
		{
			var renderer:LayoutRenderer = new LayoutRenderer();
			var container:LayoutTargetSprite = new LayoutTargetSprite();
			renderer.container = container;
			
			var t1:TesterLayoutTargetSprite = new TesterLayoutTargetSprite();
			t1.layoutMetadata.width = t1.layoutMetadata.height = 100;
			t1.layoutMetadata.paddingLeft = 9.6;
			t1.layoutMetadata.paddingTop = 8.4;
			t1.layoutMetadata.paddingRight = 5.6;
			t1.layoutMetadata.paddingBottom = 3.8;
			t1.layoutMetadata.snapToPixel = true;
			t1.setIntrinsicDimensions(100,100);
			
			renderer.addTarget(t1);
			renderer.validateNow();
			
			assertEquals(10, t1.x);
			assertEquals(8, t1.y);
			assertEquals(85, t1.width);
			assertEquals(88, t1.height);
			
			t1.layoutMetadata.paddingLeft = NaN;
			t1.layoutMetadata.paddingTop = NaN;
			t1.layoutMetadata.paddingRight = NaN;
			t1.layoutMetadata.paddingBottom = NaN;
			
			renderer.validateNow();
			
			assertEquals(0, t1.x);
			assertEquals(0, t1.y);
			assertEquals(100, t1.width);
			assertEquals(100, t1.height);
		}
	}
}