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
package org.osmf.net.httpstreaming.flv
{
	import flexunit.framework.TestCase;

	public class TestFLVTagScriptDataObject extends TestCase
	{
		public function TestFLVTagScriptDataObject(methodName:String=null)
		{
			super(methodName);
		}
		
		public function testBasics():void
		{
			var object1:Object = new Object();
			object1.data1 = 123;
			object1.data2 = "abc";
			
			var object2:Object = new Object();
			object2.data1 = 4567;
			object2.data2 = "defg";

			var scriptTag:FLVTagScriptDataObject = new FLVTagScriptDataObject();
			var objects:Array = new Array();
			objects.push(object1);
			objects.push(object2);
			
			scriptTag.objects = objects;
			
			objects = scriptTag.objects;
			assertTrue(objects.length == 2);
			
			var obj:Object = objects[0];
			assertTrue(obj.data1 == object1.data1);
			assertTrue(obj.data2 == object1.data2);
			assertTrue(obj.data1 != object1.data2);
			assertTrue(obj.data2 != object1.data1);
			
			obj = objects[1];
			assertTrue(obj.data1 == object2.data1);
			assertTrue(obj.data2 == object2.data2);
			assertTrue(obj.data1 != object2.data2);
			assertTrue(obj.data2 != object2.data1);
		}
	}
}