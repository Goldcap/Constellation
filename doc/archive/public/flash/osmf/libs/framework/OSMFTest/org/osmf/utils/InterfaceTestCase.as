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
package org.osmf.utils
{
	import org.osmf.flexunit.TestCaseEx;
	
	/**
	 * A subclass of TestCase which is specific to testing implementations of
	 * an interface.
	 **/
	public class InterfaceTestCase extends TestCaseEx
	{
		/**
		 * Constructor.
		 **/
		public function InterfaceTestCase(methodName:String=null):void
		{
			super(methodName);
		}
		
		/**
		 * @inheritDoc
		 **/
		override public function setUp():void
		{
			super.setUp();
			
			// Instantiate the implementation of the interface.
			interfaceObj = createInterfaceObject();
		}
		
		/**
		 * Override this method to create the specific implementation of the
		 * interface.  This method will be called by InterfaceTestCase.setUp.
		 **/
		protected function createInterfaceObject(... args):Object
		{
			throw new Error("Must override createInterfaceObject!");
		}

		/**
		 * The instantiated object which implements the interface being tested.
		 **/
		protected var interfaceObj:Object;
	}
}