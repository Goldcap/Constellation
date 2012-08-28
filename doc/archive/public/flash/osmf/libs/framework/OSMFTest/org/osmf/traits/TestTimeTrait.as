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
	import __AS3__.vec.Vector;
	
	import org.osmf.utils.InterfaceTestCase;
	
	import flash.events.Event;

	public class TestTimeTrait extends InterfaceTestCase
	{
		/**
		 * Subclasses can override to specify their own trait class.
		 **/
		override protected function createInterfaceObject(... args):Object
		{
			return new TimeTrait(args.length == 1 ? args[0] : NaN);
		}
		
		override public function setUp():void
		{
			super.setUp();
			
			timeTrait = createInterfaceObject() as TimeTrait;
			events = [];
		}
		
		public function testCurrentTime():void
		{
			assertTrue(isNaN(timeTrait.currentTime));
		}

		public function testDuration():void
		{
			assertTrue(isNaN(timeTrait.duration));
		}
		
		// Utils
		//
		
		protected var timeTrait:TimeTrait;
		protected var events:Array;
		
		protected function eventCatcher(event:Event):void
		{
			events.push(event);
		}
	}
}