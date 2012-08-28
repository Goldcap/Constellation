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
package org.osmf.media
{
	import __AS3__.vec.Vector;
	
	import flexunit.framework.TestCase;
	
	import org.osmf.utils.DynamicMediaElement;
	import org.osmf.utils.SampleResourceHandler;
	
	public class TestMediaFactoryItem extends TestCase
	{
		override public function setUp():void
		{
			super.setUp();
			
			createdElements = new Vector.<MediaElement>();
		}
		
		override public function tearDown():void
		{
			createdElements = null;
			
			super.tearDown();
		}
		
		public function testConstructor():void
		{
			var item:MediaFactoryItem = new MediaFactoryItem
										( "foo"
										, new SampleResourceHandler(canHandleResource).canHandleResource
										, function ():MediaElement { return null; }
										);
			assertTrue(item != null);
			
			// Verify that any null param triggers an exception.
			//
			
			try
			{
				item = new MediaFactoryItem
								( null
								, new SampleResourceHandler(canHandleResource).canHandleResource
								, function ():MediaElement { return null; }
								);
				fail();
			}
			catch (error:ArgumentError)
			{
			}

			try
			{
				item = new MediaFactoryItem
								( "foo"
								, null
								, function ():MediaElement { return null; }
								);
				fail();
			}
			catch (error:ArgumentError)
			{
			}

			try
			{
				item = new MediaFactoryItem
								( "foo"
								, new SampleResourceHandler(canHandleResource).canHandleResource
								, null
								);
				fail();
			}
			catch (error:ArgumentError)
			{
			}
		}
		
		public function testGetId():void
		{
			var item:MediaFactoryItem = createMediaFactoryItem("anId");
			assertTrue(item.id == "anId");
		}
		
		public function testGetCanHandleResourceFunction():void
		{
			var item:MediaFactoryItem = createMediaFactoryItem("id");
			var func:Function = item.canHandleResourceFunction;
			assertTrue(func != null);
			
			assertTrue(func.call(null, VALID_RESOURCE) == true);
			assertTrue(func.call(null, INVALID_RESOURCE) == false);
		}
		
		public function testGetMediaElementCreationFunction():void
		{
			var item:MediaFactoryItem = createMediaFactoryItem("id");
			var func:Function = item.mediaElementCreationFunction;
			assertTrue(func != null);
			
			var element1:MediaElement = func.call();
			assertTrue(element1 != null);
			assertTrue(element1 is DynamicMediaElement);
			var element2:MediaElement = func.call();
			assertTrue(element2 != null);
			assertTrue(element2 is DynamicMediaElement);
			assertTrue(element1 != element2);
		}

		public function testGetType():void
		{
			var item:MediaFactoryItem = createMediaFactoryItem("id");
			assertTrue(item.type == MediaFactoryItemType.STANDARD);
		}
		
		private function createMediaFactoryItem(id:String):MediaFactoryItem
		{
			return new MediaFactoryItem
					( id
					, new SampleResourceHandler(canHandleResource).canHandleResource
					, createDynamicMediaElement
					);
		}
		
		private function canHandleResource(resource:MediaResourceBase):Boolean
		{
			return resource == VALID_RESOURCE ? true : false;
		}
		
		private function createDynamicMediaElement():MediaElement
		{
			return new DynamicMediaElement();
		}
		
		private function creationCallback(mediaElement:MediaElement):void
		{
			createdElements.push(mediaElement);
		}
		
		private var createdElements:Vector.<MediaElement>;
		
		private static const VALID_RESOURCE:URLResource = new URLResource("http://www.example.com/valid");
		private static const INVALID_RESOURCE:URLResource = new URLResource("http://www.example.com/invalid");
	}
}