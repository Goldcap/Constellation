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
	import flexunit.framework.TestCase;

	public class TestURL extends TestCase
	{
		public function testURL():void
		{
			// A URL with username:password, a port number, a query string, and a fragment
			var url:URL = new URL("http://fred:wilma@hostexample.com:80/foo/bar.php?var1=foo&var2=bar#yyz");
			assertEquals(url.toString(), "http://fred:wilma@hostexample.com:80/foo/bar.php?var1=foo&var2=bar#yyz");
			assertEquals(url.protocol, "http");
			assertEquals(url.userInfo, "fred:wilma");
			assertEquals(url.host, "hostexample.com");
			assertEquals(url.port, "80");
			assertEquals(url.path, "foo/bar.php");
			assertEquals(url.getParamValue("var1"), "foo");
			assertEquals(url.getParamValue("var2"), "bar");
			assertEquals(url.fragment, "yyz");
			assertEquals(url.extension, "php");
			assertTrue(url.absolute);

			// A URI with an IP address
			url = new URL("telnet://192.0.2.16:80/");
			assertEquals(url.toString(), "telnet://192.0.2.16:80/");
			assertEquals(url.protocol, "telnet");
			assertEquals(url.host, "192.0.2.16");
			assertEquals(url.port, "80");
			assertEquals(url.path, "");
			assertEquals(url.extension, "");
			assertTrue(url.absolute);
			
			// A local file location
			url = new URL("/Users/mynamehere/Documents/media/myfile.flv");
			assertEquals(url.protocol, "");
			assertEquals(url.userInfo, "");
			assertEquals(url.host, "");
			assertEquals(url.port, "");
			assertEquals(url.path, "Users/mynamehere/Documents/media/myfile.flv");
			assertEquals(url.extension, "flv");
			assertFalse(url.absolute);
			
			// A URL with no slashes
			url = new URL("rtmfp:");
			assertEquals(url.protocol, "rtmfp");
			assertEquals(url.userInfo, "");
			assertEquals(url.host, "");
			assertEquals(url.port, "");
			assertEquals(url.path, "");
			assertEquals(url.extension, "");
			assertTrue(url.absolute);

			// A URL with one trailing slash
			url = new URL("rtmfp:/");
			assertEquals(url.protocol, "rtmfp");
			assertEquals(url.userInfo, "");
			assertEquals(url.host, "");
			assertEquals(url.port, "");
			assertEquals(url.path, "");
			assertEquals(url.extension, "");
			assertTrue(url.absolute);
			
			// A URL with one slash
			url = new URL("blah:/hostexample.com:80/foo/bar.php?var1=foo&var2=bar#yyz");
			assertEquals(url.toString(), "blah:/hostexample.com:80/foo/bar.php?var1=foo&var2=bar#yyz");
			assertEquals(url.protocol, "blah");
			assertEquals(url.userInfo, "");
			assertEquals(url.host, "hostexample.com");
			assertEquals(url.port, "80");
			assertEquals(url.path, "foo/bar.php");
			assertEquals(url.getParamValue("var1"), "foo");
			assertEquals(url.getParamValue("var2"), "bar");
			assertEquals(url.fragment, "yyz");
			assertEquals(url.extension, "php");
			assertTrue(url.absolute);

			// Relative path test
			url = new URL("../../../myfile.flv");
			assertEquals(url.protocol, "");
			assertEquals(url.host, "");
			assertEquals(url.path, "../../../myfile.flv");
			assertEquals(url.extension, "flv");
			assertFalse(url.absolute);

			// Relative path test
			url = new URL("../myfile.flv");
			assertEquals(url.protocol, "");
			assertEquals(url.host, "");
			assertEquals(url.path, "../myfile.flv");

			// Relative path test
			url = new URL("./myfile.flv");
			assertEquals(url.protocol, "");
			assertEquals(url.host, "");
			assertEquals(url.path, "./myfile.flv");

			// Relative path test
			url = new URL("foo/bar/myfile.flv");
			assertEquals(url.protocol, "");
			assertEquals(url.host, "");
			assertEquals(url.path, "foo/bar/myfile.flv");

			// Relative path test
			url = new URL("foo/myfile.flv");
			assertEquals(url.protocol, "");
			assertEquals(url.host, "");
			assertEquals(url.path, "foo/myfile.flv");

			// Relative path test
			url = new URL("myfile.flv");
			assertEquals(url.protocol, "");
			assertEquals(url.host, "");
			assertEquals(url.path, "myfile.flv");

			// Test 14
			url = new URL("http://foo.com/mymp4.mp4");
			assertEquals(url.protocol, "http");
			assertEquals(url.host, "foo.com");
			assertEquals(url.path, "mymp4.mp4");
			assertEquals(url.port, "");
			assertEquals(url.extension, "mp4");
			assertTrue(url.absolute);

			// Leading and trailing whitespace test
			url = new URL("  http://foo.com/file.flv  ");
			assertEquals(url.protocol, "http");
			assertEquals(url.host, "foo.com");
			assertEquals(url.path, "file.flv");
			assertEquals(url.port, "");
			assertEquals(url.extension, "flv");
		}
	}
}