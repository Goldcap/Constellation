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
	import __AS3__.vec.Vector;
	
	import flexunit.framework.TestCase;
	
	import org.osmf.events.MetadataEvent;
	import org.osmf.utils.OSMFStrings;

	public class TestMetadata extends TestCase
	{
		
		override public function setUp():void
		{
			super.setUp();			
			metadata = new Metadata();				
		}
		
		public function testAddValue():void
		{				
			var addCalled:Boolean = false;	
			metadata.addEventListener(MetadataEvent.VALUE_ADD, onAdd);
			var key:String = "thekey";
			var value:String = "dfs3424f#@$@D";
			
			metadata.addValue(key, value);
			assertTrue(addCalled);
			assertEquals(value, metadata.getValue(key));		
			
			// Test the Catching of Errors
			try
			{
				metadata.addValue(null, "foo");
				
				fail();
			}
			catch(error:ArgumentError)
			{
				assertEquals(error.message, OSMFStrings.getString(OSMFStrings.NULL_PARAM));
			}
			
			function onAdd(event:MetadataEvent):void
			{
				addCalled = true;
				assertEquals(event.value, value);				
			}				
		}
		
		public function testRemoveValue():void
		{		
			var removeCalled:Boolean = false;
			var key:String = "thekey";	
			var value:String = "dfs3424f#@$@D";
			metadata.addEventListener(MetadataEvent.VALUE_REMOVE, onRemove);
			metadata.addValue(key, value);
			assertFalse(removeCalled);
			assertEquals(value, metadata.getValue(key));
					
			value = "dfs3424f#@$@D";
			
			assertNull(metadata.removeValue("unknown"));					
			assertFalse(removeCalled); // Make sure we didn't dispatch an event for an already removed item.
								
			assertEquals(metadata.removeValue(key), value);
			assertTrue(removeCalled);				
			assertNull(metadata.getValue(key));		
			
			// Test the Catching of Errors
			try
			{
				metadata.removeValue(null);
				
				fail();
			}
			catch(error:ArgumentError)
			{
				assertEquals(error.message, OSMFStrings.getString(OSMFStrings.NULL_PARAM));
			}
			
			function onRemove(event:MetadataEvent):void
			{
				removeCalled = true;
				assertEquals(event.value, value);				
			}				
		}
		
		public function testChangeValue():void
		{	
			var addCalled:Boolean = false;	
			var removeCalled:Boolean = false;
			var changeCalled:Boolean = false;
			metadata.addEventListener(MetadataEvent.VALUE_ADD, onAdd);
			metadata.addEventListener(MetadataEvent.VALUE_REMOVE, onRemove);
			metadata.addEventListener(MetadataEvent.VALUE_CHANGE, onChange);
			var key1:String = "thekey1";
			var key2:String = "thekey2";
			var value1:String = "dfs3424f#@$@D";
			var value2:String = "dfdfdsff#@$@D";
			
			metadata.addValue(key1, value1);
			assertTrue(addCalled);
			assertFalse(removeCalled);
			assertFalse(changeCalled);
			
			addCalled = false;
			
			metadata.addValue(key2, value2);				
			assertTrue(addCalled);
			assertFalse(removeCalled);
			assertFalse(changeCalled);

			addCalled = false;

			// Overwriting an existing value with the same value should
			// produce no events.
			metadata.addValue(key1, value1);				
			assertFalse(addCalled);
			assertFalse(removeCalled);
			assertFalse(changeCalled);

			// Overwriting an existing value with a different value should
			// produce a change event.
			metadata.addValue(key1, value2);				
			assertFalse(addCalled);
			assertFalse(removeCalled);
			assertTrue(changeCalled);
								
			function onAdd(event:MetadataEvent):void
			{
				addCalled = true;
			}	
			
			function onRemove(event:MetadataEvent):void
			{
				removeCalled = true;
			}

			function onChange(event:MetadataEvent):void
			{
				changeCalled = true;
			}
			
			metadata.removeEventListener(MetadataEvent.VALUE_ADD, onAdd);
			metadata.removeEventListener(MetadataEvent.VALUE_REMOVE, onRemove);
			metadata.removeEventListener(MetadataEvent.VALUE_CHANGE, onChange);
		}	
			
		public function testGetValue():void
		{
			var key1:String =  "k1";
			var key2:String = "k2";
			var value1:String =  "http://www.adobe.com/";
			var value2:String = "http://www.example.com/";
			
			metadata.addValue(key1, value1);
			metadata.addValue(key2, value2);
			
			assertEquals(metadata.getValue(key1), value1);
			assertEquals(metadata.getValue(key2), value2);
			assertEquals(metadata.getValue(value1), null);
			assertEquals(metadata.getValue(value2), null);
			assertEquals(metadata.getValue("blah"), null);

			// Test the Catching of Errors
			try
			{
				metadata.getValue(null);
				
				fail();
			}
			catch(error:ArgumentError)
			{
				assertEquals(error.message, OSMFStrings.getString(OSMFStrings.NULL_PARAM));
			}
		}
		
		public function testGetKeys():void
		{
			var key1:String =  "k1";
			var key2:String = "k2";
			var value1:String =  "http://www.adobe.com/";
			var value2:String = "http://www.example.com/";
			
			metadata.addValue(key1, value1);
			metadata.addValue(key2, value2);
			metadata.addValue(key2, value1);
			
			var keys:Vector.<String> = metadata.keys;
			assertEquals(keys.length, 2);
			
			// Can't predict the order of the keys, so we need to check for both.
			assertTrue(		(keys[0] == key1 && keys[1] == key2)
						||	(keys[1] == key1 && keys[0] == key2)
					  );
		}
		
		// Internals
		//
		
		private var metadata:Metadata;	
	}
}
