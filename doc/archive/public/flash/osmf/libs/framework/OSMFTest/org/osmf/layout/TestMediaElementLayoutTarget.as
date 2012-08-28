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
	import flash.events.Event;
	
	import flexunit.framework.TestCase;
	
	import org.osmf.elements.ParallelElement;
	import org.osmf.events.DisplayObjectEvent;
	import org.osmf.media.MediaElement;
	import org.osmf.metadata.Metadata;
	import org.osmf.traits.MediaTraitType;
	import org.osmf.utils.DynamicDisplayObjectTrait;
	import org.osmf.utils.DynamicMediaElement;

	public class TestMediaElementLayoutTarget extends TestCase
	{
		public function testMediaElementLayoutTarget():void
		{
			assertTrue(throws(function():void{MediaElementLayoutTarget.getInstance(null);}));
			
			var me:MediaElement = new MediaElement();
			var melt:MediaElementLayoutTarget = MediaElementLayoutTarget.getInstance(me);
			
			melt.measure();
			
			assertNull(melt.displayObject);
			assertNotNull(melt.layoutMetadata);
			assertEquals(melt.layoutMetadata, me.getMetadata(LayoutMetadata.LAYOUT_NAMESPACE) as LayoutMetadata);
			assertEquals(NaN, melt.measuredWidth);
			assertEquals(NaN, melt.measuredHeight);
			
			var lmd:LayoutMetadata = new LayoutMetadata();
			me.addMetadata(LayoutMetadata.LAYOUT_NAMESPACE, lmd);
			assertEquals(lmd, melt.layoutMetadata);
			
			var md:Metadata = new Metadata();
			me.addMetadata("test", md);
			
			me.removeMetadata("test");
			me.removeMetadata(LayoutMetadata.LAYOUT_NAMESPACE);
			assertFalse(melt.layoutMetadata == lmd);
		}
		
		public function testCompositeElement():void
		{
			var p:ParallelElement = new ParallelElement();
			var melt:MediaElementLayoutTarget = MediaElementLayoutTarget.getInstance(p);
			
			var me:DynamicMediaElement = new DynamicMediaElement();
			var lts:LayoutTargetSprite = new LayoutTargetSprite(me.getMetadata(LayoutMetadata.LAYOUT_NAMESPACE) as LayoutMetadata);
			var displayObjectTrait:DynamicDisplayObjectTrait = new DynamicDisplayObjectTrait(lts, 100, 200);
			me.doAddTrait(MediaTraitType.DISPLAY_OBJECT, displayObjectTrait);
			
			melt.dispatchEvent
				( new LayoutTargetEvent
					( LayoutTargetEvent.ADD_CHILD_AT
					)
				);
				
			melt.dispatchEvent
				( new LayoutTargetEvent
					( LayoutTargetEvent.REMOVE_CHILD
					)
				);
				
			melt.dispatchEvent
				( new LayoutTargetEvent
					( LayoutTargetEvent.SET_CHILD_INDEX
					)
				);
		}
		
		public function testMediaElementLayoutTargetWithDisplayObjectTrait():void
		{
			var me:DynamicMediaElement = new DynamicMediaElement();

			var lt:MediaElementLayoutTarget = MediaElementLayoutTarget.getInstance(me);
				
			var lts:LayoutTargetSprite = new LayoutTargetSprite(me.getMetadata(LayoutMetadata.LAYOUT_NAMESPACE) as LayoutMetadata);
			var displayObjectTrait:DynamicDisplayObjectTrait = new DynamicDisplayObjectTrait(lts, 100, 200);
			me.doAddTrait(MediaTraitType.DISPLAY_OBJECT, displayObjectTrait);

			lt.measure();
			
			assertEquals(lt.layoutMetadata, me.getMetadata(LayoutMetadata.LAYOUT_NAMESPACE) as LayoutMetadata);
			assertEquals(lt.displayObject, lts);
			assertEquals(lt.measuredWidth, 100);
			assertEquals(lt.measuredHeight, 200); 
			
			var renderer:LayoutRendererBase = new LayoutRenderer();
			renderer.container = lts;
				
			var lastEvent:Event;
			var eventCounter:int = 0;
			
			function onEvent(event:Event):void
			{
				lastEvent = event;
				eventCounter++;
			}
			
			lt.addEventListener(DisplayObjectEvent.MEDIA_SIZE_CHANGE, onEvent);
			lt.addEventListener(DisplayObjectEvent.DISPLAY_OBJECT_CHANGE, onEvent);
			
			var sprite2:Sprite = new Sprite();
			displayObjectTrait.displayObject = sprite2;
			
			assertEquals(1, eventCounter);
			var vce:DisplayObjectEvent = lastEvent as DisplayObjectEvent;
			assertNotNull(vce);
			assertEquals(vce.oldDisplayObject, lts);
			assertEquals(vce.newDisplayObject, sprite2);
			
			displayObjectTrait.setSize(300,400);
			
			assertEquals(2, eventCounter);
			var dce:DisplayObjectEvent = lastEvent as DisplayObjectEvent;
			assertNotNull(dce);
			assertEquals(dce.oldWidth, 100);
			assertEquals(dce.oldHeight, 200);
			assertEquals(dce.newWidth, 300);
			assertEquals(dce.newHeight, 400);
		}
		
		public function testSingletonConstruction():void
		{
			var mediaElement:MediaElement = new MediaElement();
			
			var check:Boolean;
			try
			{
				new MediaElementLayoutTarget(null,null);
			}
			catch(e:Error)
			{
				check = true;
			}
			
			assertTrue(check);
			
			var melt:MediaElementLayoutTarget = MediaElementLayoutTarget.getInstance(mediaElement);
			
			assertNotNull(melt);
			
			assertEquals(melt, MediaElementLayoutTarget.getInstance(mediaElement));
			assertEquals(melt, MediaElementLayoutTarget.getInstance(mediaElement));
		}
		
		private function throws(f:Function):Boolean
		{
			var result:Boolean;
			
			try
			{
				f();
			}
			catch(e:Error)
			{
				result = true;
			}
			
			return result;
		}
	}
}
