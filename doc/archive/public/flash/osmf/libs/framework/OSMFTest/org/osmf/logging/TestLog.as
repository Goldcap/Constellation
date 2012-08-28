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
package org.osmf.logging
{
	import flexunit.framework.TestCase;

	public class TestLog extends TestCase
	{
		public function TestLog(methodName:String=null)
		{
			super(methodName);
		}
		
		override public function tearDown():void
		{
			super.tearDown();
			
			Log.loggerFactory = null;
		}
		
		public function testAccessLoggerFactory():void
		{
			var loggerFactory:TraceLoggerFactory = new TraceLoggerFactory();
			Log.loggerFactory = loggerFactory;
			
			assertTrue(loggerFactory == Log.loggerFactory);
		}
		
		public function testGetLogger():void
		{
			var logger:Logger = Log.getLogger("testLogger");
			CONFIG::LOGGING
			{
				assertTrue(logger != null);
				logger = null;
			}
			
			assertTrue(logger == null);

			Log.loggerFactory = new TraceLoggerFactory();
			
			logger = Log.getLogger("testLogger");
			assertTrue(logger != null);
		}
	}
}