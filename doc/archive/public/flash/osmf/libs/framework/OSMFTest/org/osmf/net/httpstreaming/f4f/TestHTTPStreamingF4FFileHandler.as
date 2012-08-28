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
package org.osmf.net.httpstreaming.f4f
{
	import flash.events.Event;
	import flash.events.EventDispatcher;
	import flash.net.URLLoader;
	import flash.net.URLRequest;
	import flash.utils.ByteArray;
	
	import flexunit.framework.TestCase;
	
	import mx.utils.Base64Decoder;
	
	public class TestHTTPStreamingF4FFileHandler extends TestCase
	{
		override public function setUp():void
		{
			fileHandler = new HTTPStreamingF4FFileHandler(null);
			bytes = new ByteArray();
			eventDispatcher = new EventDispatcher();
		}
		
		override public function tearDown():void
		{
			fileHandler = null;
			bytes = null;
			eventDispatcher = null;
		}
		
		public function testProcessFile():void
		{
			callAfterLoad(doTestProcessFile);
		}
		
		private function doTestProcessFile():void
		{
			fileHandler.beginProcessFile(false, 0);
			assertTrue(fileHandler.inputBytesNeeded == 8);
			
			while (bytes.bytesAvailable)
			{
				fileHandler.processFileSegment(bytes);
			}
		}
		
		private function callAfterLoad(func:Function, triggerTestCompleteEvent:Boolean=true):void
		{
			if (triggerTestCompleteEvent)
			{
				eventDispatcher.addEventListener("testComplete", addAsync(mustReceiveEvent, ASYNC_DELAY));
			}

			var urlRequest:URLRequest = new URLRequest("assets/mp4_bytes.txt");
			var urlLoader:URLLoader = new URLLoader();
			urlLoader.addEventListener(Event.COMPLETE, onLoadComplete);
			urlLoader.load(urlRequest);
			
			function onLoadComplete(event:Event):void
			{
				urlLoader.removeEventListener(Event.COMPLETE, onLoadComplete);
				
				var decoder:Base64Decoder = new Base64Decoder();
				decoder.decode(urlLoader.data);
				bytes = decoder.drain();
				
				func.call();
				
				if (triggerTestCompleteEvent)
				{
					eventDispatcher.dispatchEvent(new Event("testComplete"));
				}
			}
		}

		private function mustReceiveEvent(event:Event):void
		{
			// Placeholder to ensure an event is received.
		}

		private static const ASYNC_DELAY:Number = 8000;

		private var fileHandler:HTTPStreamingF4FFileHandler;
		private var bytes:ByteArray;
		private var eventDispatcher:EventDispatcher;
	}
}