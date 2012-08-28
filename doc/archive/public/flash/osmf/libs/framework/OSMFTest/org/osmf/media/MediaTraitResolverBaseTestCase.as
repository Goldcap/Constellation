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
	import org.osmf.flexunit.TestCaseEx;
	import org.osmf.traits.BufferTrait;
	import org.osmf.traits.MediaTraitBase;
	import org.osmf.traits.MediaTraitType;
	import org.osmf.traits.TimeTrait;
	import org.osmf.traits.DisplayObjectTrait;

	public class MediaTraitResolverBaseTestCase extends TestCaseEx
	{
		public function constructResolver(type:String, traitOfType:MediaTraitBase):MediaTraitResolver
		{
			return null;	
		}
		
		public function testConstructor():void
		{
			var resolver:MediaTraitResolver;
			assertThrows
				( function():void
					{
						resolver = constructResolver(null, null);
					}
				);
				
			assertNull(resolver);
		}
		
		public function testType():void
		{
			var resolver:MediaTraitResolver;
			resolver = constructResolver(MediaTraitType.BUFFER, new BufferTrait());
			assertNotNull(resolver);
			assertEquals(MediaTraitType.BUFFER, resolver.type);
		}
		
		public function testAddTrait():void
		{
			var type:String = MediaTraitType.TIME;
			var resolver:MediaTraitResolver = constructResolver(type, new TimeTrait());
			
			assertThrows
				( function():void
					{
						resolver.addTrait(null);
					}
				);
			
			assertThrows
				( function():void
					{
						resolver.addTrait(new DisplayObjectTrait(null));
					}
				);
		}
		
		public function testRemoveTrait():void
		{
			var type:String = MediaTraitType.TIME;
			var resolver:MediaTraitResolver = constructResolver(type, new TimeTrait());
			
			assertThrows
				( function():void
					{
						resolver.removeTrait(null);
					}
				);
				
			var tt:TimeTrait = new TimeTrait();
				
			assertThrows
				( function():void
					{
						resolver.removeTrait(tt);
					}
				);
				
			resolver.addTrait(tt);
			resolver.removeTrait(tt);
		}
	}
}