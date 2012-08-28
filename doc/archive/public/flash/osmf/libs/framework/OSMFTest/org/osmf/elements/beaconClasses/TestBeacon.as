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
package org.osmf.elements.beaconClasses
{
	import flash.events.Event;
	
	import flexunit.framework.TestCase;
	
	import org.osmf.events.BeaconEvent;
	import org.osmf.utils.HTTPLoader;
	import org.osmf.utils.MockHTTPLoader;
	import org.osmf.utils.NetFactory;
	import org.osmf.utils.TestConstants;
	
	public class TestBeacon extends TestCase
	{
		private function createHTTPLoader():HTTPLoader
		{
			if (NetFactory.neverUseMockObjects)
			{
				return new HTTPLoader();
			}
			else
			{
				var loader:MockHTTPLoader = new MockHTTPLoader();
				loader.setExpectationForURL(SUCCESSFUL_URL, true, null);
				loader.setExpectationForURL(FAILED_URL, false, null);
				return loader;
			}
		}
		
		public function testConstructor():void
		{
			try
			{
				// Should throw when url is null.
				new Beacon(null);
				
				fail();
			}
			catch (error:ArgumentError)
			{
			}
		}
		
		public function testPingWithSuccess():void
		{
			var loader:HTTPLoader = createHTTPLoader();
			
			var beacon:Beacon = new Beacon(SUCCESSFUL_URL, loader);
			beacon.addEventListener(BeaconEvent.PING_COMPLETE, addAsync(onTestPingWithSuccess, 4000));
			beacon.addEventListener(BeaconEvent.PING_ERROR, dontCall);
			beacon.ping();
		}
		
		private function onTestPingWithSuccess(event:BeaconEvent):void
		{
			assertTrue(event.type == BeaconEvent.PING_COMPLETE);
		}

		public function testPingWithFailure():void
		{
			var loader:HTTPLoader = createHTTPLoader();
			
			var beacon:Beacon = new Beacon(FAILED_URL, loader);
			beacon.addEventListener(BeaconEvent.PING_COMPLETE, dontCall);
			beacon.addEventListener(BeaconEvent.PING_ERROR, addAsync(onTestPingWithFailure, 4000));
			beacon.ping();
		}
		
		private function onTestPingWithFailure(event:BeaconEvent):void
		{
			assertTrue(event.type == BeaconEvent.PING_ERROR);
			assertTrue(event.errorText == null);
		}
		
		public function testPingWithInvalidURL():void
		{
			var loader:HTTPLoader = createHTTPLoader();
			
			var beacon:Beacon = new Beacon(INVALID_URL, loader);
			beacon.addEventListener(BeaconEvent.PING_COMPLETE, dontCall);
			beacon.addEventListener(BeaconEvent.PING_ERROR, addAsync(onTestPingWithInvalidURL, 4000));
			beacon.ping();
		}
		
		private function onTestPingWithInvalidURL(event:BeaconEvent):void
		{
			assertTrue(event.type == BeaconEvent.PING_ERROR);
			assertTrue(event.errorText == null);
		}
		
		private function dontCall(event:Event):void
		{
			fail();
		}

		private var SUCCESSFUL_URL:String = TestConstants.REMOTE_IMAGE_FILE;
		private var FAILED_URL:String = TestConstants.REMOTE_INVALID_IMAGE_FILE;
		private var INVALID_URL:String = "ftp://example.com";
	}
}