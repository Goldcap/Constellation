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
	import flexunit.framework.TestCase;

	public class TestHTTPStreamRequest extends TestCase
	{
		public function TestHTTPStreamRequest(methodName:String=null)
		{
			super(methodName);
		}
		
		public function testHTTPStreamRequest():void
		{
			var url:String = "http://www.myserver.com/";
			var quality:int = 2;
			var truncateAt:Number = 300.25;
			var retryAfter:Number = 500.86;
			var unpublishNotify:Boolean = false;
			
			var request:HTTPStreamRequest = new HTTPStreamRequest(url, quality, truncateAt, retryAfter, unpublishNotify);
			assertEquals(request.urlRequest.url.toString(), url);
			assertEquals(request.retryAfter, retryAfter);
			assertEquals(request.unpublishNotify, unpublishNotify);
			
			url = null;
			request = new HTTPStreamRequest(url, quality, truncateAt, retryAfter, unpublishNotify);			
			assertEquals(request.urlRequest, null);
		}
		
		public function testFM986():void
		{
			var url:String = "http://www.myserver.com/";
			var quality:int = 2;
			var truncateAt:Number = 300.25;
			var retryAfter:Number = 500.86;
			var unpublishNotify:Boolean = false;
			
			var request:HTTPStreamRequest = new HTTPStreamRequest(url, quality, truncateAt, retryAfter, unpublishNotify);
			assertEquals(request.urlRequest.url.toString(), url);

			url = "http://part1/part2/../part3/../part4/par5";
			request = new HTTPStreamRequest(url, quality, truncateAt, retryAfter, unpublishNotify);
			assertEquals(request.urlRequest.url.toString(), "http://part1/part4/par5");
		}
	}
}