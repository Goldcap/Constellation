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
	import __AS3__.vec.Vector;
	
	import flexunit.framework.TestCase;

	public class TestBinarySearch extends TestCase
	{
		public function testBinarySearch():void
		{
			// For the sake of coverage:
			assertNotNull(new BinarySearch());
			
			assertTrue
				( throws
					( function():void
						{
							BinarySearch.search(null, function(..._):int{return 0}, null);
						}
					)
				);
			
			assertTrue
				( throws
					( function():void
						{
							BinarySearch.search(new Vector.<int>(), null, null);
						}
					)
				);
			
			var list:Vector.<int> = new Vector.<int>();
			
			assertEquals(0, BinarySearch.search(list, compare, 1));
			
			list.push(1);			// [1]
			
			assertEquals(0, BinarySearch.search(list, compare, 1));
			assertEquals(-1, BinarySearch.search(list, compare, 2));
			
			list.push(10);			// [1,10]
			list.push(15);			// [1,10,15]
			
			assertEquals(1, BinarySearch.search(list, compare, 10));
			assertEquals(2, BinarySearch.search(list, compare, 15));
			assertEquals(-2, BinarySearch.search(list, compare, 11));
			
			list.splice(2,0,11);	// [1,10,11,15]
			
			assertEquals(-1, BinarySearch.search(list, compare, 2));
			
			list.splice(1,0,2);		// [1,2,10,11,15]
			
			assertEquals(1, BinarySearch.search(list, compare, 2));
			
			list.splice(1,0,2);		// [1,2,2,10,11,15]
			
			assertEquals(-3, BinarySearch.search(list, compare, 3));
		}
		
		private function compare(x:int,y:int):int
		{
			return (x == y)
				? 0
				: x > y 
					? 1
					: -1;
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