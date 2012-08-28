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
package org.osmf.metadata
{
	import flexunit.framework.TestCase;
	
	public class TestMetadataWatcher extends TestCase
	{
		public function testWatchFacet():void
		{
			var callbackArgument:* = null;
			var callbackCount:int = 0;
			function changeCallback(value:*):void
			{
				callbackArgument = value;
				callbackCount++;
			}
			
			var ns1:String = new String("http://www.ns1.com");
			var ns2:String = new String("http://www.ns2.com");
			var parentMetadata:Metadata = new Metadata();
			var watcher:MetadataWatcher = new MetadataWatcher(parentMetadata, ns1, null, changeCallback);
			watcher.watch();
			
			assertEquals(1,callbackCount);
			assertNull(callbackArgument);
			
			var m1:Metadata = new Metadata();
			parentMetadata.addValue(ns1, m1);
			
			assertEquals(2, callbackCount);
			assertEquals(callbackArgument, m1);
			
			parentMetadata.removeValue(ns1);
			
			assertEquals(3, callbackCount);
			assertNull(callbackArgument);
			
			var m2:Metadata = new Metadata();
			parentMetadata.addValue(ns2, m2);
			
			assertEquals(3, callbackCount);
			assertNull(callbackArgument);
			
			// No event, we're not watching values.
			m1.addValue("foo", "bar");
			assertEquals(3, callbackCount);
		}
		
		public function testWatchValue():void
		{
			var callbackArgument:* = null;
			var callbackCount:int = 0;
			function valueChangeCallback(value:*):void
			{
				callbackArgument = value;
				callbackCount++;
			}
			
			var ns1:String = new String("http://www.ns1.com");
			var ns2:String = new String("http://www.ns2.com");
			var parentMetadata:Metadata = new Metadata();
			var watcher:MetadataWatcher
				= new MetadataWatcher
					( parentMetadata
					, ns1
					, "myKey"
					, valueChangeCallback
					);
			watcher.watch();
			
			assertEquals(1,callbackCount);
			assertNull(callbackArgument);
			
			var m1:Metadata = new Metadata();
			parentMetadata.addValue(ns1, m1);
			
			assertEquals(2, callbackCount);
			assertNull(callbackArgument);
			
			var m2:Metadata = new Metadata();
			m2.addValue("myKey", "myValue");
			parentMetadata.addValue(ns2, m2);
			
			assertEquals(2, callbackCount);
			
			// Event, we're watching values.
			m1.addValue("myKey", "bar");
			assertEquals(3, callbackCount);
			assertEquals(callbackArgument, "bar");

			m1.addValue("myKey", "23");
			assertEquals(4, callbackCount);
			assertEquals(callbackArgument, "23");

			m1.removeValue("myKey");
			
			assertEquals(5, callbackCount);
			assertNull(callbackArgument);

			m1.addValue("foo", "bar");
			assertEquals(5, callbackCount);
			assertNull(callbackArgument);
		}
	}
}