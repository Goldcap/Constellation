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
	import flash.errors.IllegalOperationError;
	import flash.events.Event;
	import flash.events.EventDispatcher;
	
	import org.osmf.events.LoadEvent;
	import org.osmf.media.MediaResourceBase;
	import org.osmf.utils.InterfaceTestCase;
	import org.osmf.utils.SimpleLoader;
	import org.osmf.utils.SimpleResource;
	
	public class TestLoadTrait extends InterfaceTestCase
	{
		/**
		 * Subclasses can override to specify their own trait class.
		 **/
		override protected function createInterfaceObject(... args):Object
		{
			return new LoadTrait(args.length > 0 ? args[0] : null, args.length > 1 ? args[1] : null);
		}

		/**
		 * Subclasses can override to specify their own loader.
		 **/
		protected function createLoader():LoaderBase
		{
			return useNullLoader ? null : new SimpleLoader();
		}
		
		/**
		 * Subclasses can override to specify their own resource.
		 **/
		protected function get successfulResource():MediaResourceBase
		{
			return new SimpleResource(SimpleResource.SUCCESSFUL);
		}

		protected function get failedResource():MediaResourceBase
		{
			return new SimpleResource(SimpleResource.FAILED);
		}

		protected function get unhandledResource():MediaResourceBase
		{
			return new SimpleResource(SimpleResource.UNHANDLED);
		}
		
		protected function get loadIsAsynchronous():Boolean
		{
			return false;
		}
		
		/**
		 * Subclasses can override if the media being loaded has a positive
		 * byte count.
		 **/
		protected function get expectedBytesTotal():Number
		{
			return NaN;
		}
		
		/**
		 * Subclasses can override to indicate that all bytes should be loaded
		 * when the trait enters the READY state.
		 **/
		protected function get allBytesLoadedWhenReady():Boolean
		{
			return false;
		}

		/**
		 * Subclasses can override to indicate that the bytesTotal property should
		 * be set to its correct value when the trait enters the READY state.
		 **/
		protected function get bytesTotalSetWhenReady():Boolean
		{
			return false;
		}
		
		override public function setUp():void
		{
			super.setUp();
			
			eventDispatcher = new EventDispatcher();
			eventCount = 0;
			doTwice = false;
			useNullLoader = false;
		}
		
		//---------------------------------------------------------------------
		
		public function testGetResource():void
		{
			var loadTrait:LoadTrait = createLoadTrait(null);
			
			assertTrue(loadTrait.resource == null);
			
			var resource:MediaResourceBase = successfulResource;
			
			loadTrait = createLoadTrait(resource);
			
			assertTrue(loadTrait.resource != null);
			assertTrue(loadTrait.resource == resource);
		}

		public function testGetLoadState():void
		{
			var loadTrait:LoadTrait = createLoadTrait();
			
			assertTrue(loadTrait.loadState == LoadState.UNINITIALIZED);
		}
		
		public function testGetBytesLoaded():void
		{
			var loadTrait:LoadTrait = createLoadTrait();
			
			assertTrue(isNaN(loadTrait.bytesLoaded));
		}

		public function testGetBytesTotal():void
		{
			var loadTrait:LoadTrait = createLoadTrait();
			
			assertTrue(isNaN(loadTrait.bytesTotal));
		}

		public function testNullLoader():void
		{
			useNullLoader = true;
			
			var loadTrait:LoadTrait = createLoadTrait();
			
			try
			{
				loadTrait.load();
				
				fail();
			}
			catch (error:IllegalOperationError)
			{
			}

			try
			{
				loadTrait.unload();
				
				fail();
			}
			catch (error:IllegalOperationError)
			{
			}
		}

		public function testLoad():void
		{
			doTestLoad();
		}
		
		public function testLoadTwice():void
		{
			doTwice = true;
			doTestLoad();
		}
		
		private function doTestLoad():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, TEST_TIME));
			
			var loadTrait:LoadTrait = currentLoadTrait = createLoadTrait(successfulResource);
			
			assertTrue(loadTrait.bytesLoaded == 0 || isNaN(loadTrait.bytesLoaded));
			
			loadTrait.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onTestLoad);
			loadTrait.load();
		}
		
		private function onTestLoad(event:LoadEvent):void
		{
			var loadTrait:LoadTrait = event.target as LoadTrait;
			assertTrue(loadTrait != null);
			
			var reload:Boolean = false;
			
			switch (eventCount)
			{
				case 0:
					assertTrue(event.loadState == LoadState.LOADING);
					break;
				case 1:
					assertTrue(event.loadState == LoadState.READY);
					
					if (doTwice)
					{
						reload = true;
					}
					else
					{
						if (expectedBytesTotal > 0)
						{
							if (bytesTotalSetWhenReady)
							{
								assertTrue(expectedBytesTotal == loadTrait.bytesTotal);
							}
							if (allBytesLoadedWhenReady)
							{
								assertTrue(loadTrait.bytesLoaded == loadTrait.bytesTotal);
							}
						}
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}
					break;
				case 2:
					assertTrue(doTwice);
					
					assertTrue(event.loadState == LoadState.UNLOADING);
					break;
				case 3:
					assertTrue(doTwice);
					
					assertTrue(event.loadState == LoadState.UNINITIALIZED);
					break;
				case 4:
					assertTrue(doTwice);
					
					assertTrue(event.loadState == LoadState.LOADING);
					break;
				case 5:
					assertTrue(doTwice);
					
					assertTrue(event.loadState == LoadState.READY);
					
					eventDispatcher.dispatchEvent(new Event("testComplete"));
					break;
				default:
					fail();
			}
			
			eventCount++;
			
			if (reload)
			{
				// Calling load a second time should throw an exception.
				try
				{
					loadTrait.load();
					
					fail();
				}
				catch (error:IllegalOperationError)
				{
					// But we can unload and then load again.
					loadTrait.unload();
					loadTrait.load();
				}
			}
		}
		
		public function testLoadWithFailure():void
		{
			doTestLoadWithFailure();
		}
		
		public function testLoadWithFailureThenReload():void
		{
			doTwice = true;
			doTestLoadWithFailure();
		}
		
		private function doTestLoadWithFailure():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, TEST_TIME));
			
			var loadTrait:LoadTrait = currentLoadTrait = createLoadTrait(failedResource);
			
			loadTrait.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onTestLoadWithFailure);
			loadTrait.load();
		}
				
		private function onTestLoadWithFailure(event:LoadEvent):void
		{
			assertTrue(event.target == currentLoadTrait);
			
			var reload:Boolean = false;
			
			switch (eventCount)
			{
				case 0:
					assertTrue(event.loadState == LoadState.LOADING);
					break;
				case 1:
					assertTrue(event.loadState == LoadState.LOAD_ERROR);
					
					if (eventCount == 1 && doTwice)
					{
						reload = true;
					}
					else
					{
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}
					break;
				case 2:
					assertTrue(doTwice);
					
					assertTrue(event.loadState == LoadState.LOADING);
					break;
				case 3:
					assertTrue(doTwice);
					
					assertTrue(event.loadState == LoadState.LOAD_ERROR);
					
					eventDispatcher.dispatchEvent(new Event("testComplete"));
					break;
				default:
					fail();
			}
			
			eventCount++;
			
			if (reload)
			{
				// Reloading should repeat the failure.
				currentLoadTrait.load();
			}
		}
		
		public function testLoadWithInvalidResource():void
		{
			var loadTrait:LoadTrait = createLoadTrait(unhandledResource);
			
			try
			{
				loadTrait.load();
				
				fail();
			}
			catch (error:IllegalOperationError)
			{
			}
		}
		
		public function testUnload():void
		{
			doTestUnload();
		}
		
		public function testUnloadTwice():void
		{
			doTwice = true;
			doTestUnload();
		}

		private function doTestUnload():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, TEST_TIME));
			
			var loadTrait:LoadTrait = currentLoadTrait = createLoadTrait(successfulResource);
			
			loadTrait.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onTestUnload);
			loadTrait.load();
		}
		
		private function onTestUnload(event:LoadEvent):void
		{
			assertTrue(event.target == currentLoadTrait);
			
			var doUnload:Boolean = false;
			
			switch (eventCount)
			{
				case 0:
					assertTrue(event.loadState == LoadState.LOADING);
					break;
				case 1:
					assertTrue(event.loadState == LoadState.READY);
					
					// Now unload.
					doUnload = true;
					
					break;
				case 2:
					assertTrue(event.loadState == LoadState.UNLOADING);
					break;
				case 3:
					assertTrue(event.loadState == LoadState.UNINITIALIZED);
					
					eventDispatcher.dispatchEvent(new Event("testComplete"));
					break;
					
				default:
					fail();
			}
			
			eventCount++;
			
			if (doUnload)
			{
				currentLoadTrait.unload();
				
				if (doTwice)
				{
					// Unloading a second time should throw an exception.
					try
					{
						currentLoadTrait.unload();
						
						fail();
					}
					catch (error:IllegalOperationError)
					{
					}
				}
			}
		}
		
		public function testUnloadAfterLoad():void
		{
			eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, TEST_TIME));
			
			var loadTrait:LoadTrait = currentLoadTrait = createLoadTrait(successfulResource);
			
			loadTrait.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onTestUnloadAfterLoad);
			loadTrait.load();
			loadTrait.unload();
		}
		
		private function onTestUnloadAfterLoad(event:LoadEvent):void
		{
			assertTrue(event.target == currentLoadTrait);
			
			switch (eventCount)
			{
				case 0:
					assertTrue(event.loadState == LoadState.LOADING);
					break;
				case 1:
					if (loadIsAsynchronous)
					{
						// The unload should begin before the load completes.
						assertTrue(event.loadState == LoadState.UNLOADING);
					}
					else
					{
						// The load will complete before the unload starts.
						assertTrue(event.loadState == LoadState.READY);
					}
					break;
				case 2:
					if (loadIsAsynchronous)
					{
						assertTrue(event.loadState == LoadState.UNINITIALIZED);
						
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}
					else
					{
						assertTrue(event.loadState == LoadState.UNLOADING);
					}
					break;
				case 3:
					if (loadIsAsynchronous)
					{
						fail();
					}
					else
					{
						assertTrue(event.loadState == LoadState.UNINITIALIZED);
						
						eventDispatcher.dispatchEvent(new Event("testComplete"));
					}
					
					break;
				
				default:
					fail();
			}
			
			eventCount++;
		}
		
		public function testUnloadWithInvalidResource():void
		{
			var loadTrait:LoadTrait = createLoadTrait(unhandledResource);
			
			try
			{
				loadTrait.unload();
				
				fail();
			}
			catch (error:IllegalOperationError)
			{
			}
		}
		
		//---------------------------------------------------------------------
		
		protected final function createLoadTrait(resource:MediaResourceBase=null):LoadTrait
		{
			return createInterfaceObject(createLoader(), resource) as LoadTrait; 
		}
		
		private function mustReceiveEvent(event:Event):void
		{
			// Placeholder to ensure an event is received.
		}

		private function mustNotReceiveEvent(event:Event):void
		{
			// Placeholder to ensure an event is NOT received.
			fail();
		}
		
		private static const TEST_TIME:int = 5000;
		
		private var eventDispatcher:EventDispatcher;
		private var eventCount:int = 0;
		private var currentLoadTrait:LoadTrait;
		private var doTwice:Boolean;
		private var useNullLoader:Boolean;
	}
}