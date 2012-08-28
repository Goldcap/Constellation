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
*  The Initial Developer of the Original Code is Adobe Systems Incorporated.
*  Portions created by Adobe Systems Incorporated are Copyright (C) 2010 Adobe Systems 
*  Incorporated. All Rights Reserved. 
*  
*****************************************************/
package org.osmf.net.httpstreaming
{
	import org.osmf.flexunit.TestCaseEx;

	public class TestHTTPStreamingFileHandlerBase extends TestCaseEx
	{
		public function TestHTTPStreamingFileHandlerBase(methodName:String=null)
		{
			super(methodName);
		}
		
		public function testHTTPStreamingFileHandlerBase():void
		{
			assertThrows(function():void{
				var fhb:HTTPStreamingFileHandlerBase = new HTTPStreamingFileHandlerBase();
				fhb.beginProcessFile(true, 0);
			});

			assertThrows(function():void{
				var fhb:HTTPStreamingFileHandlerBase = new HTTPStreamingFileHandlerBase();
				fhb.endProcessFile(null);
			});

			assertThrows(function():void{
				var fhb:HTTPStreamingFileHandlerBase = new HTTPStreamingFileHandlerBase();
				fhb.flushFileSegment(null);
			});

			assertThrows(function():void{
				var fhb:HTTPStreamingFileHandlerBase = new HTTPStreamingFileHandlerBase();
				var bytes:Number = fhb.inputBytesNeeded;
			});

			assertThrows(function():void{
				var fhb:HTTPStreamingFileHandlerBase = new HTTPStreamingFileHandlerBase();
				fhb.processFileSegment(null);
			});
		}
	}
}