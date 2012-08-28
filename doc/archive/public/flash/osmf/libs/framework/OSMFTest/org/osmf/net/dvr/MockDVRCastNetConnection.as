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
*  Portions created by Adobe Systems Incorporated are Copyright (C) 2010 Adobe Systems 
*  Incorporated. All Rights Reserved. 
*  
*****************************************************/
package org.osmf.net.dvr
{
	import flash.net.Responder;
	
	import org.osmf.netmocker.MockNetConnection;

	public class MockDVRCastNetConnection extends MockNetConnection
	{
		public function MockDVRCastNetConnection()
		{
			super();
	
			connect(null);
		}
		
		public function pushCallResult(succeed:Boolean, response:Object):void
		{
			successStack.push(succeed);
			responseStack.push(response);
		}
		
		override public function call(command:String, responder:Responder, ...parameters):void
		{
			if (successStack.length)
			{
				var fail:Boolean = !successStack.shift();
				var response:Object = responseStack.shift();
				
				var testableResponder:TestableResponder = responder as TestableResponder;
				if (testableResponder)
				{
					if (fail && testableResponder.status != null)
					{
						testableResponder.status(response);
					}
					else if (fail == false && testableResponder.result != null)
					{
						testableResponder.result(response);
					}
				}
			}
		}
		
		private var successStack:Array = new Array();
		private var responseStack:Array = new Array(); 
		
	}
}