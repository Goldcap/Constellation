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
package org.osmf.net
{
	import flexunit.framework.TestCase;

	public class TestFMSURL extends TestCase
	{
		public function testFMSURL():void
		{
			// URI with query string params
			var fmsURL:FMSURL = new FMSURL("rtmp://myhostname/myappname/foo/mystream.flv?param1=one&param2=two");
			assertEquals(fmsURL.protocol, "rtmp");
			assertEquals(fmsURL.host, "myhostname");
			assertEquals(fmsURL.path, "myappname/foo/mystream.flv");
			assertEquals(fmsURL.getParamValue("param2"), "two");
			assertEquals(fmsURL.getParamValue("param1"), "one");
			assertEquals(fmsURL.appName, "myappname");
			assertEquals(fmsURL.instanceName, "");
			assertEquals(fmsURL.streamName, "foo/mystream.flv");
			
			// URI with an instance name
			fmsURL = new FMSURL("rtmp://myhostname/myappname/foo/mystream.flv?param1=one&param2=two", true);
			assertEquals(fmsURL.protocol, "rtmp");
			assertEquals(fmsURL.host, "myhostname");
			assertEquals(fmsURL.path, "myappname/foo/mystream.flv");
			assertEquals(fmsURL.getParamValue("param2"), "two");
			assertEquals(fmsURL.getParamValue("param1"), "one");
			assertEquals(fmsURL.appName, "myappname");
			assertEquals(fmsURL.instanceName, "foo");
			assertEquals(fmsURL.streamName, "mystream.flv");
						
			// A URI for the purpose of connecting to a local dev install of FMS
			fmsURL = new FMSURL("rtmp:/sudoku/room1");
			assertEquals(fmsURL.toString(), "rtmp:/sudoku/room1");
			assertEquals(fmsURL.protocol, "rtmp");
			assertEquals(fmsURL.host, "localhost");
			assertEquals(fmsURL.path, "sudoku/room1");
			assertEquals(fmsURL.appName, "sudoku");
			assertEquals(fmsURL.instanceName, "");
			assertEquals(fmsURL.streamName, "room1"); 
			
			// A URI with an empty port
			fmsURL = new FMSURL("rtmp://mtserver.com:/myapp");
			assertEquals(fmsURL.toString(), "rtmp://mtserver.com:/myapp");
			assertEquals(fmsURL.protocol, "rtmp");
			assertEquals(fmsURL.host, "mtserver.com");
			assertEquals(fmsURL.port, "");
			assertEquals(fmsURL.path, "myapp");
			assertEquals(fmsURL.appName, "myapp");
						
			// A URI with a prefix but no extension
			fmsURL = new FMSURL("rtmp://localhost/vod/mp3:Legend");
			assertEquals(fmsURL.protocol, "rtmp");
			assertEquals(fmsURL.host, "localhost");
			assertEquals(fmsURL.appName, "vod");
			assertEquals(fmsURL.streamName, "mp3:Legend");
			
			// A URI with a prefix and an extension
			fmsURL = new FMSURL("rtmp://localhost/vod/mp4:Legend.mp4");
			assertEquals(fmsURL.protocol, "rtmp");
			assertEquals(fmsURL.host, "localhost");
			assertEquals(fmsURL.appName, "vod");
			assertEquals(fmsURL.streamName, "mp4:Legend.mp4");
			
			// FMS URI with explicit _definst_
			fmsURL = new FMSURL("rtmp://myhost/foo/_definst_/bar/mystream.flv");
			assertEquals(fmsURL.appName, "foo");
			assertEquals(fmsURL.instanceName, "_definst_");
			assertEquals(fmsURL.streamName, "bar/mystream.flv");
			
			// FMS URI with explicit _definst_ and passing true to the ctor
			fmsURL = new FMSURL("rtmp://myhost/foo/_definst_/bar/mystream.flv", true);
			assertEquals(fmsURL.appName, "foo");
			assertEquals(fmsURL.instanceName, "_definst_");
			assertEquals(fmsURL.streamName, "bar/mystream.flv");
			
			// null URI then set it via a property
			fmsURL = new FMSURL(null);
			assertEquals(fmsURL.toString(), null);
			assertEquals(fmsURL.protocol, "");
			assertEquals(fmsURL.host, "");
			assertEquals(fmsURL.path, "");
			assertEquals(fmsURL.appName, "");
			assertEquals(fmsURL.instanceName, "");
			assertEquals(fmsURL.streamName, "");
															
			// Simple prog download test		
			fmsURL = new FMSURL("rtmp://testserver/myapp/myfile.flv");
			assertEquals(fmsURL.fileFormat, "");
			assertEquals(fmsURL.host, "testserver");
			assertEquals(fmsURL.streamName, "myfile.flv");
			assertEquals(fmsURL.appName, "myapp");
			
			// Another simple test
			fmsURL = new FMSURL("rtmp://testserver/myapp/myfile2.flv2");
			assertEquals(fmsURL.fileFormat, "");
			assertEquals(fmsURL.streamName, "myfile2.flv2");
			assertEquals(fmsURL.appName, "myapp");
			
			// A stream with the mp4 prefix and an extension	
			fmsURL = new FMSURL("rtmp://testserver/myapp/mp4:myfile2.flv2");
			assertEquals(fmsURL.fileFormat, FMSURL.MP4_STREAM);
			assertEquals(fmsURL.host, "testserver");
			assertEquals(fmsURL.streamName, "mp4:myfile2.flv2");
			assertEquals(fmsURL.appName, "myapp");
			
			// Another one with a prefix and an extension
			fmsURL = new FMSURL("rtmp://testserver/myapp/mp3:myfile2.flv");
			assertEquals(fmsURL.fileFormat, FMSURL.MP3_STREAM);
			assertEquals(fmsURL.host, "testserver");
			assertEquals(fmsURL.streamName, "mp3:myfile2.flv");
			assertEquals(fmsURL.appName, "myapp");
			
			// Another one with a strange extension
			fmsURL = new FMSURL("rtmp://testserver/myapp/myfile2.flv2.flv");
			assertEquals(fmsURL.fileFormat, "");
			assertEquals(fmsURL.host, "testserver");
			assertEquals(fmsURL.streamName, "myfile2.flv2.flv");
			assertEquals(fmsURL.appName, "myapp");
			
			//	
			fmsURL = new FMSURL("rtmp://testserver/myapp/myfile6.flv?param=myparam.flv");
			assertEquals(fmsURL.fileFormat, "");
			assertEquals(fmsURL.host, "testserver");			
			assertEquals(fmsURL.streamName, "myfile6.flv");
			assertEquals(fmsURL.appName, "myapp");
			assertEquals(fmsURL.getParamValue("param"), "myparam.flv");
			
			//
			fmsURL = new FMSURL("rtmp://testserver/myapp/myfile7.flv2?param=myparam.flv2");
			assertEquals(fmsURL.fileFormat, "");
			assertEquals(fmsURL.host, "testserver");						
			assertEquals(fmsURL.streamName , "myfile7.flv2");
			assertEquals(fmsURL.appName, "myapp");
			assertEquals(fmsURL.getParamValue("param"), "myparam.flv2");
						
			// An odd URI with an '@' in the path
			fmsURL = new FMSURL("rtmp://myserver.live.fms.net/live/Flash_Live_Benchmark@632");
			assertEquals(fmsURL.protocol, "rtmp");
			assertEquals(fmsURL.host, "myserver.live.fms.net");
			assertEquals(fmsURL.appName, "live");
			assertEquals(fmsURL.instanceName, "");
			assertEquals(fmsURL.streamName, "Flash_Live_Benchmark@632");
			
			// An FMS URI with edge/origin information
			fmsURL = new FMSURL("rtmp://edge1/?rtmp://edge2/?rtmp://edge3/?rtmp://edge4/?rtmp://origin/app");
			assertEquals(fmsURL.appName, "app");
			assertEquals(fmsURL.host, "edge1");
			assertEquals(fmsURL.instanceName, "");
			assertEquals(fmsURL.path, "");
			assertEquals(fmsURL.port, "");
			assertEquals(fmsURL.streamName, "");
			assertEquals(fmsURL.edges.length, 3);
			assertEquals(fmsURL.edges[0].host, "edge2");
			assertEquals(fmsURL.edges[1].host, "edge3");
			assertEquals(fmsURL.edges[2].host, "edge4");
			assertEquals(fmsURL.origins.length, 1);
			assertEquals(fmsURL.origins[0].host, "origin");

			// An FMS URI with edge/origin information containing an instance name
			fmsURL = new FMSURL("rtmp://edge1/?rtmp://edge2/?rtmp://origin/app/inst/mp4:folder/myfile.mp4?auth=123456", true);
			assertEquals(fmsURL.appName, "app");
			assertEquals(fmsURL.host, "edge1");
			assertEquals(fmsURL.instanceName, "inst");
			assertEquals(fmsURL.path, "");
			assertEquals(fmsURL.port, "");
			assertEquals(fmsURL.streamName, "mp4:folder/myfile.mp4");
			assertEquals(fmsURL.edges.length, 1);
			assertEquals(fmsURL.edges[0].host, "edge2");
			assertEquals(fmsURL.origins.length, 1);
			assertEquals(fmsURL.origins[0].host, "origin");
			assertEquals(fmsURL.getParamValue("auth"), "123456");
						
			//
			fmsURL = new FMSURL("rtmp://edge1/?rtmp://edge2/?rtmp://origin/app/inst/mp4:foldera/folder/b/myfile.mp4", true);
			assertEquals(fmsURL.appName, "app");
			assertEquals(fmsURL.host, "edge1");
			assertEquals(fmsURL.instanceName, "inst");
			assertEquals(fmsURL.path, "");
			assertEquals(fmsURL.port, "");
			assertEquals(fmsURL.streamName, "mp4:foldera/folder/b/myfile.mp4");
			assertEquals(fmsURL.edges.length, 1);
			assertEquals(fmsURL.edges[0].host, "edge2");
			assertEquals(fmsURL.origins.length, 1);
			assertEquals(fmsURL.origins[0].host, "origin");

			//
			fmsURL = new FMSURL("rtmp://mydomain.com/vod");
			assertEquals(fmsURL.appName, "vod");
			assertEquals(fmsURL.host, "mydomain.com");
			assertEquals(fmsURL.instanceName, "");
			assertEquals(fmsURL.path, "vod");
			assertEquals(fmsURL.port, "");
			assertEquals(fmsURL.streamName, "");
			assertEquals(fmsURL.edges, null);
			assertEquals(fmsURL.origins, null);

			//
			fmsURL = new FMSURL("rtmp://somedomain.com/live/");
			assertEquals(fmsURL.appName, "live");
			assertEquals(fmsURL.host, "somedomain.com");
			assertEquals(fmsURL.instanceName, "");
			assertEquals(fmsURL.path, "live/");
			assertEquals(fmsURL.port, "");
			assertEquals(fmsURL.streamName, "");
			assertEquals(fmsURL.edges, null);
			assertEquals(fmsURL.origins, null);

			//
			fmsURL = new FMSURL("rtmp:/myapp/streamname.flv");
			assertEquals(fmsURL.protocol, "rtmp");			
			assertEquals(fmsURL.host, "localhost");
			assertEquals(fmsURL.appName, "myapp");
			assertEquals(fmsURL.instanceName, "");
			assertEquals(fmsURL.path, "myapp/streamname.flv");
			assertEquals(fmsURL.port, "");
			assertEquals(fmsURL.streamName, "streamname.flv");
			assertEquals(fmsURL.edges, null);
			assertEquals(fmsURL.origins, null);

			//
			fmsURL = new FMSURL("rtmp:/myapp/streamname.flv?foo=123&bar=456");
			assertEquals(fmsURL.protocol, "rtmp");			
			assertEquals(fmsURL.host, "localhost");
			assertEquals(fmsURL.appName, "myapp");
			assertEquals(fmsURL.instanceName, "");
			assertEquals(fmsURL.path, "myapp/streamname.flv");
			assertEquals(fmsURL.port, "");
			assertEquals(fmsURL.streamName, "streamname.flv");
			assertEquals(fmsURL.edges, null);
			assertEquals(fmsURL.origins, null);
			assertEquals(fmsURL.getParamValue("foo"), "123");
			assertEquals(fmsURL.getParamValue("bar"), "456");
			
			//
			fmsURL = new FMSURL("rtmp://myserver/myapp/streamname.flv");
			assertEquals(fmsURL.protocol, "rtmp");			
			assertEquals(fmsURL.host, "myserver");
			assertEquals(fmsURL.appName, "myapp");
			assertEquals(fmsURL.instanceName, "");
			assertEquals(fmsURL.path, "myapp/streamname.flv");
			assertEquals(fmsURL.port, "");
			assertEquals(fmsURL.streamName, "streamname.flv");
			assertEquals(fmsURL.edges, null);
			assertEquals(fmsURL.origins, null);
			
			//
			fmsURL = new FMSURL("rtmp://myedge/?rtmp://myserver/myapp/streamname.flv");
			assertEquals(fmsURL.protocol, "rtmp");			
			assertEquals(fmsURL.host, "myedge");
			assertEquals(fmsURL.appName, "myapp");
			assertEquals(fmsURL.instanceName, "");
			assertEquals(fmsURL.path, "");
			assertEquals(fmsURL.streamName, "streamname.flv");
			assertEquals(fmsURL.edges, null);
			assertEquals(fmsURL.origins.length, 1);
			assertEquals(fmsURL.origins[0].host, "myserver");
		
			//
			fmsURL = new FMSURL("rtmpte://myedge1/?rtmp://myedge2/?rtmp://myorigin/myapp/streamname.mp4");
			assertEquals(fmsURL.protocol, "rtmpte");			
			assertEquals(fmsURL.host, "myedge1");
			assertEquals(fmsURL.appName, "myapp");
			assertEquals(fmsURL.instanceName, "");
			assertEquals(fmsURL.path, "");
			assertEquals(fmsURL.streamName, "streamname.mp4");
			assertEquals(fmsURL.edges.length, 1);
			assertEquals(fmsURL.edges[0].host, "myedge2");
			assertEquals(fmsURL.origins.length, 1);
			assertEquals(fmsURL.origins[0].host, "myorigin");
			
			//
			fmsURL = new FMSURL("rtmp://myedge:1935/?rtmp://myedge2/?rtmpt://myorigin:80/myapp2/strname.flv");
			assertEquals(fmsURL.protocol, "rtmp");			
			assertEquals(fmsURL.host, "myedge");
			assertEquals(fmsURL.port, "1935");
			assertEquals(fmsURL.appName, "myapp2");
			assertEquals(fmsURL.instanceName, "");
			assertEquals(fmsURL.path, "");
			assertEquals(fmsURL.streamName, "strname.flv");
			assertEquals(fmsURL.edges.length, 1);
			assertEquals(fmsURL.edges[0].host, "myedge2");
			assertEquals(fmsURL.origins.length, 1);
			assertEquals(fmsURL.origins[0].host, "myorigin");
			
			// Test the canonical form of specifying the stream name and stream type in the query string
			fmsURL = new FMSURL("rtmp://myedge:443/?rtmp://myedge2/?rtmp://myorigin:1935/myapp3/streamname.flv?streamName=qsstreamname&mode=live&streamType=MP4");
			assertEquals(fmsURL.protocol, "rtmp");			
			assertEquals(fmsURL.host, "myedge");
			assertEquals(fmsURL.appName, "myapp3");
			assertEquals(fmsURL.instanceName, "");
			assertEquals(fmsURL.path, "");
			assertEquals(fmsURL.port, "443");
			assertEquals(fmsURL.streamName, "qsstreamname");
			assertEquals(fmsURL.fileFormat.toLowerCase(), FMSURL.MP4_STREAM);
			assertEquals(fmsURL.edges.length, 1);
			assertEquals(fmsURL.edges[0].host, "myedge2");
			assertEquals(fmsURL.origins.length, 1);
			assertEquals(fmsURL.origins[0].host, "myorigin");
		}
		
		public function testFM943():void
		{
			var fmsURL:FMSURL = new FMSURL("rtmp://llnwqa.fcod.llnwd.net/a1218/o18/mp4:lexsamplecontent/king_500.f4v", true);
			assertEquals(fmsURL.protocol, "rtmp");			
			assertEquals(fmsURL.host, "llnwqa.fcod.llnwd.net");
			assertEquals(fmsURL.streamName, "mp4:lexsamplecontent/king_500.f4v");
			var serverUrl:String = fmsURL.protocol + "://" + fmsURL.host + ((fmsURL.port != null && fmsURL.port.length > 0)? ":" + fmsURL.port : "") + "/" + fmsURL.appName + (fmsURL.useInstance ? "/" + fmsURL.instanceName:""); 
			assertEquals(serverUrl, "rtmp://llnwqa.fcod.llnwd.net/a1218/o18");
				
			fmsURL = new FMSURL("rtmp://llnwqa.fcod.llnwd.net/a1218/o18/_definst_/mp4:lexsamplecontent/king_500.f4v", true);
			assertEquals(fmsURL.protocol, "rtmp");			
			assertEquals(fmsURL.host, "llnwqa.fcod.llnwd.net");
			assertEquals(fmsURL.streamName, "mp4:lexsamplecontent/king_500.f4v");
			serverUrl = fmsURL.protocol + "://" + fmsURL.host + ((fmsURL.port != null && fmsURL.port.length > 0)? ":" + fmsURL.port : "") + "/" + fmsURL.appName + (fmsURL.useInstance ? "/" + fmsURL.instanceName:""); 
			assertEquals(serverUrl, "rtmp://llnwqa.fcod.llnwd.net/a1218/o18/_definst_");

			fmsURL = new FMSURL("rtmp://llnwqa.fcod.llnwd.net/a1218/o18/mp3:lexsamplecontent/king_500.f4v", true);
			assertEquals(fmsURL.protocol, "rtmp");			
			assertEquals(fmsURL.host, "llnwqa.fcod.llnwd.net");
			assertEquals(fmsURL.streamName, "mp3:lexsamplecontent/king_500.f4v");
			serverUrl = fmsURL.protocol + "://" + fmsURL.host + ((fmsURL.port != null && fmsURL.port.length > 0)? ":" + fmsURL.port : "") + "/" + fmsURL.appName + (fmsURL.useInstance ? "/" + fmsURL.instanceName:""); 
			assertEquals(serverUrl, "rtmp://llnwqa.fcod.llnwd.net/a1218/o18");

			fmsURL = new FMSURL("rtmp://llnwqa.fcod.llnwd.net/a1218/o18/id3:lexsamplecontent/king_500.f4v", true);
			assertEquals(fmsURL.protocol, "rtmp");			
			assertEquals(fmsURL.host, "llnwqa.fcod.llnwd.net");
			assertEquals(fmsURL.streamName, "id3:lexsamplecontent/king_500.f4v");
			serverUrl = fmsURL.protocol + "://" + fmsURL.host + ((fmsURL.port != null && fmsURL.port.length > 0)? ":" + fmsURL.port : "") + "/" + fmsURL.appName + (fmsURL.useInstance ? "/" + fmsURL.instanceName:""); 
			assertEquals(serverUrl, "rtmp://llnwqa.fcod.llnwd.net/a1218/o18");

			fmsURL = new FMSURL("rtmp://llnwqa.fcod.llnwd.net/a1218/o18/_definst_/mp3:lexsamplecontent/king_500.f4v", true);
			assertEquals(fmsURL.protocol, "rtmp");			
			assertEquals(fmsURL.host, "llnwqa.fcod.llnwd.net");
			assertEquals(fmsURL.streamName, "mp3:lexsamplecontent/king_500.f4v");
			serverUrl = fmsURL.protocol + "://" + fmsURL.host + ((fmsURL.port != null && fmsURL.port.length > 0)? ":" + fmsURL.port : "") + "/" + fmsURL.appName + (fmsURL.useInstance ? "/" + fmsURL.instanceName:""); 
			assertEquals(serverUrl, "rtmp://llnwqa.fcod.llnwd.net/a1218/o18/_definst_");

			fmsURL = new FMSURL("rtmp://llnwqa.fcod.llnwd.net/a1218/o18/_definst_/id3:lexsamplecontent/king_500.f4v", true);
			assertEquals(fmsURL.protocol, "rtmp");			
			assertEquals(fmsURL.host, "llnwqa.fcod.llnwd.net");
			assertEquals(fmsURL.streamName, "id3:lexsamplecontent/king_500.f4v");
			serverUrl = fmsURL.protocol + "://" + fmsURL.host + ((fmsURL.port != null && fmsURL.port.length > 0)? ":" + fmsURL.port : "") + "/" + fmsURL.appName + (fmsURL.useInstance ? "/" + fmsURL.instanceName:""); 
			assertEquals(serverUrl, "rtmp://llnwqa.fcod.llnwd.net/a1218/o18/_definst_");
		}
		
		public function testFM950():void
		{
			var url:FMSURL = new FMSURL("rtmpte:/vod/sample");
			assertEquals(url.streamName, "sample");
			assertEquals(url.host, "localhost");
			assertEquals(url.appName, "vod");
			assertEquals(url.path, "vod/sample");
		}
	}
}
