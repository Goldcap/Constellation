/*****************************************************
*  
*  Copyright 2010 Adobe Systems Incorporated.  All Rights Reserved.
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
	import org.osmf.events.DVREvent;
	import org.osmf.utils.InterfaceTestCase;

	public class TestDVRTrait extends InterfaceTestCase
	{
		override protected function createInterfaceObject(...args):Object
		{
			return new DVRTrait();
		}
		
		override public function setUp():void
		{
			super.setUp();
			
			dvrTrait = createInterfaceObject() as DVRTrait;
		}
		
		// Public API
		//
		
		public function testIsRecording():void
		{
			assertFalse(dvrTrait.isRecording);
		}
		
		private var dvrTrait:DVRTrait;
	}
}