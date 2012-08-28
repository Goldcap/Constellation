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
	import __AS3__.vec.Vector;
	
	import flash.utils.ByteArray;
	
	import flexunit.framework.TestCase;
	
	import org.osmf.elements.f4mClasses.*;
	import org.osmf.media.URLResource;
	import org.osmf.metadata.*;
	import org.osmf.net.*;
	import org.osmf.net.httpstreaming.f4f.*;

	public class TestHTTPStreamingUtils extends TestCase
	{
		public function TestHTTPStreamingUtils(methodName:String=null)
		{
			super(methodName);
		}
		
		public function testCreateHTTPStreamingMetadata():void
		{
			var abstUrl:String = null;
			var data:int = 123;
			var abstData:ByteArray = new ByteArray();
			abstData.writeInt(data);
			var serverBaseUrls:Vector.<String> = null;
			var sburls:Vector.<String> = null;
			
			var metadata:Metadata = HTTPStreamingUtils.createHTTPStreamingMetadata(abstUrl, abstData, serverBaseUrls);
			var bootstrapInfo:BootstrapInfo = metadata.getValue(MetadataNamespaces.HTTP_STREAMING_BOOTSTRAP_KEY);
			assertTrue(bootstrapInfo != null);
			assertEquals(bootstrapInfo.url, null);
			assertTrue(bootstrapInfo.data != null);
			bootstrapInfo.data.position = 0;
			assertEquals(bootstrapInfo.data.readInt(), data);
			sburls = metadata.getValue(MetadataNamespaces.HTTP_STREAMING_SERVER_BASE_URLS_KEY);
			assertEquals(sburls, null);

			abstUrl = "http://www.myserver.com/abst";
			metadata = HTTPStreamingUtils.createHTTPStreamingMetadata(abstUrl, abstData, serverBaseUrls);
			bootstrapInfo = metadata.getValue(MetadataNamespaces.HTTP_STREAMING_BOOTSTRAP_KEY);
			assertTrue(bootstrapInfo != null);
			assertTrue(bootstrapInfo.url != null);
			assertTrue(bootstrapInfo.url.toString(), abstUrl);
			
			serverBaseUrls = new Vector.<String>();
			metadata = HTTPStreamingUtils.createHTTPStreamingMetadata(abstUrl, abstData, serverBaseUrls);
			sburls = metadata.getValue(MetadataNamespaces.HTTP_STREAMING_SERVER_BASE_URLS_KEY);
			assertEquals(sburls, null);
			
			serverBaseUrls[0] = "http://www.myserver.com/server0/";
			serverBaseUrls[1] = "http://www.myserver.com/server1/";
			metadata = HTTPStreamingUtils.createHTTPStreamingMetadata(abstUrl, abstData, serverBaseUrls);
			sburls = metadata.getValue(MetadataNamespaces.HTTP_STREAMING_SERVER_BASE_URLS_KEY);
			assertTrue(sburls != null);
			assertTrue(sburls.length == 2);
			assertEquals(sburls[0], serverBaseUrls[0]);  
			assertEquals(sburls[1], serverBaseUrls[1]);  
		}
		
		public function testCreateF4FIndexInfoForSingleStreamVOD():void
		{
			var urlResource:URLResource = new URLResource(SINGLE_STREAM_VOD_F4M_URL);
			var info:HTTPStreamingF4FIndexInfo = HTTPStreamingUtils.createF4FIndexInfo(urlResource);
			assertEquals(info, null);
			
			var manifest:Manifest = createSingleStreamVODManifest();
			assertTrue(manifest != null);
			
			var resource:URLResource = parser.createResource(manifest, urlResource) as URLResource;
			assertTrue(resource != null);
			
			info = HTTPStreamingUtils.createF4FIndexInfo(resource);
			assertTrue(info != null);
			assertEquals(info.dvrInfo, null);
			assertEquals(info.streamInfos.length, 1);
			assertEquals(info.serverBaseURL, "http://fms1j009f.corp.adobe.com/zeri-media/Fragments_Source_Media_Unprotected/215");
		}
		
		public function testCreateF4FIndexInfoForDVRMBR():void
		{
			var urlResource:URLResource = new URLResource(DVR_MBR_F4M_URL);
			var info:HTTPStreamingF4FIndexInfo = HTTPStreamingUtils.createF4FIndexInfo(urlResource);
			assertEquals(info, null);

			var manifest:Manifest = createDVRMBRManifest();
			assertTrue(manifest != null);
			
			var resource:DynamicStreamingResource = parser.createResource(manifest, urlResource) as DynamicStreamingResource;
			assertTrue(resource != null);

			info = HTTPStreamingUtils.createF4FIndexInfo(resource);
			assertTrue(info != null);
			assertTrue(info.dvrInfo != null);
			assertTrue(info.streamInfos.length > 1);
			assertEquals(info.serverBaseURL, "http://fms1j009f.corp.adobe.com/zeri_live/events/zeriDVRMBRAppendSegment/events/_definst_");

			manifest = createDVRMBRManifest2();
			assertTrue(manifest != null);
			
			resource = parser.createResource(manifest, urlResource) as DynamicStreamingResource;
			assertTrue(resource != null);

			info = HTTPStreamingUtils.createF4FIndexInfo(resource);
			assertTrue(info != null);
			assertTrue(info.dvrInfo != null);
			assertTrue(info.streamInfos.length > 1);
			assertEquals(info.serverBaseURL, "http://fms1j009f.corp.adobe.com/zeri_live/events/zeriDVRMBRAppendSegment/events/_definst_");
		}
		
		public function testFM986():void
		{
			assertEquals(HTTPStreamingUtils.normalizeURL("http://path1/path4/path5/path6"), "http://path1/path4/path5/path6");
			assertEquals(HTTPStreamingUtils.normalizeURL("http://path1/path2/path3/../../path4/path5/path6"), "http://path1/path4/path5/path6");
			assertEquals(HTTPStreamingUtils.normalizeURL("http://path1/../path3/../path4/path5/path6"), "http://path4/path5/path6");
		}
		
		private function createSingleStreamVODManifest():Manifest
		{
			var xml:XML = 
			<manifest xmlns="http://ns.adobe.com/f4m/1.0">
				<id>
					avatar_4000
				</id>
				<streamType>
					recorded
				</streamType>
				<duration>
					210.90133333333333
				</duration>
				<bootstrapInfo
					 profile="named"
					 id="bootstrap1160"
				>
					AAAAi2Fic3QAAAAAAAAAAgAAAAPoAAAAAAADN8AAAAAAAAAAAAAAAAAAAQAAABlhc3J0AAAAAAAAAAABAAAAAQAAADUBAAAARmFmcnQAAAAAAAAD6AAAAAADAAAAAQAAAAAAAAAAAAAPoAAAADUAAAAAAAMsgAAAC1QAAAAAAAAAAAAAAAAAAAAAAA==
				</bootstrapInfo>
				<media
					 streamId="avatar_4000"
					 url="avatar_4000"
					 bootstrapInfoId="bootstrap1160"
				>
					<metadata>
						AgAKb25NZXRhRGF0YQgAAAAAAAhkdXJhdGlvbgBAalzXuQCuwwAFd2lkdGgAQIqgAAAAAAAABmhlaWdodABAfgAAAAAAAAAMdmlkZW9jb2RlY2lkAgAEYXZjMQAMYXVkaW9jb2RlY2lkAgAEbXA0YQAKYXZjcHJvZmlsZQBAWQAAAAAAAAAIYXZjbGV2ZWwAQD8AAAAAAAAABmFhY2FvdAAAAAAAAAAAAAAOdmlkZW9mcmFtZXJhdGUAQDgAAAAAAAAAD2F1ZGlvc2FtcGxlcmF0ZQBA53AAAAAAAAANYXVkaW9jaGFubmVscwBAAAAAAAAAAAAJdHJhY2tpbmZvCgAAAAIDAAZsZW5ndGgAQVNOYgAAAAAACXRpbWVzY2FsZQBA13AAAAAAAAAIbGFuZ3VhZ2UCAANlbmcAAAkDAAZsZW5ndGgAQWNPAAAAAAAACXRpbWVzY2FsZQBA53AAAAAAAAAIbGFuZ3VhZ2UCAANlbmcAAAkAAAk=
					</metadata>
				</media>
			</manifest>;
			
			return parser.parse(xml.toXMLString(), SINGLE_STREAM_VOD_F4M_URL);
		}
		
		private function createDVRMBRManifest():Manifest
		{
			var xml:XML = 
			<manifest xmlns="http://ns.adobe.com/f4m/1.0">
				<id>
					zeriDVRMBRAppendSegment/events/_definst_/live_dvr_mbr_event
				</id>
				<mimeType>
					
				</mimeType>
				<streamType>
					live
				</streamType>
				<duration>
					0
				</duration>
				<dvrInfo
					 beginOffset="0"
					 endOffset="0"
					 offline="false"
				>
				</dvrInfo>
				<media
					 streamId="livestream1"
					 bitrate="350"
					 url="../../../../streams/zeriDVRMBRAppendSegment/streams/_definst_/livestream1/livestream1"
					 bootstrapInfoId="bootstrap903"
				>
					<metadata>
						AgAKb25NZXRhRGF0YQgAAAAAAAhkdXJhdGlvbgAAAAAAAAAAAAAFd2lkdGgAAAAAAAAAAAAABmhlaWdodAAAAAAAAAAAAAAMdmlkZW9jb2RlY2lkAgAESDI2NAAMYXVkaW9jb2RlY2lkAgAEbXA0YQAKYXZjcHJvZmlsZQBAUIAAAAAAAAAIYXZjbGV2ZWwAQD8AAAAAAAAABmFhY2FvdAAAAAAAAAAAAAAOdmlkZW9mcmFtZXJhdGUA//gAAAAAAAAAD2F1ZGlvc2FtcGxlcmF0ZQBA5YiAAAAAAAANYXVkaW9jaGFubmVscwA/8AAAAAAAAAAJdHJhY2tpbmZvCgAAAAMDAAZsZW5ndGgAAAAAAAAAAAAACXRpbWVzY2FsZQBAj0AAAAAAAAAIbGFuZ3VhZ2UCAANlbmcAAAkDAAZsZW5ndGgAAAAAAAAAAAAACXRpbWVzY2FsZQBAj0AAAAAAAAAIbGFuZ3VhZ2UCAANlbmcAAAkDAAZsZW5ndGgAAAAAAAAAAAAACXRpbWVzY2FsZQBAj0AAAAAAAAAIbGFuZ3VhZ2UCAANlbmcAAAkAB2N1c3RkZWYKAAAAAAAACQ==
					</metadata>
				</media>
				<media
					 streamId="livestream2"
					 bitrate="500"
					 url="../../../../streams/zeriDVRMBRAppendSegment/streams/_definst_/livestream2/livestream2"
					 bootstrapInfoId="bootstrap28"
				>
					<metadata>
						AgAKb25NZXRhRGF0YQgAAAAAAAhkdXJhdGlvbgAAAAAAAAAAAAAFd2lkdGgAAAAAAAAAAAAABmhlaWdodAAAAAAAAAAAAAAMdmlkZW9jb2RlY2lkAgAESDI2NAAMYXVkaW9jb2RlY2lkAgAEbXA0YQAKYXZjcHJvZmlsZQBAUIAAAAAAAAAIYXZjbGV2ZWwAQD8AAAAAAAAABmFhY2FvdAAAAAAAAAAAAAAOdmlkZW9mcmFtZXJhdGUA//gAAAAAAAAAD2F1ZGlvc2FtcGxlcmF0ZQBA5YiAAAAAAAANYXVkaW9jaGFubmVscwA/8AAAAAAAAAAJdHJhY2tpbmZvCgAAAAMDAAZsZW5ndGgAAAAAAAAAAAAACXRpbWVzY2FsZQBAj0AAAAAAAAAIbGFuZ3VhZ2UCAANlbmcAAAkDAAZsZW5ndGgAAAAAAAAAAAAACXRpbWVzY2FsZQBAj0AAAAAAAAAIbGFuZ3VhZ2UCAANlbmcAAAkDAAZsZW5ndGgAAAAAAAAAAAAACXRpbWVzY2FsZQBAj0AAAAAAAAAIbGFuZ3VhZ2UCAANlbmcAAAkAB2N1c3RkZWYKAAAAAAAACQ==
					</metadata>
				</media>
				<media
					 streamId="livestream3"
					 bitrate="1000"
					 url="../../../../streams/zeriDVRMBRAppendSegment/streams/_definst_/livestream3/livestream3"
					 bootstrapInfoId="bootstrap1503"
				>
					<metadata>
						AgAKb25NZXRhRGF0YQgAAAAAAAhkdXJhdGlvbgAAAAAAAAAAAAAFd2lkdGgAAAAAAAAAAAAABmhlaWdodAAAAAAAAAAAAAAMdmlkZW9jb2RlY2lkAgAESDI2NAAMYXVkaW9jb2RlY2lkAgAEbXA0YQAKYXZjcHJvZmlsZQBAUIAAAAAAAAAIYXZjbGV2ZWwAQD8AAAAAAAAABmFhY2FvdAAAAAAAAAAAAAAOdmlkZW9mcmFtZXJhdGUA//gAAAAAAAAAD2F1ZGlvc2FtcGxlcmF0ZQBA5YiAAAAAAAANYXVkaW9jaGFubmVscwA/8AAAAAAAAAAJdHJhY2tpbmZvCgAAAAMDAAZsZW5ndGgAAAAAAAAAAAAACXRpbWVzY2FsZQBAj0AAAAAAAAAIbGFuZ3VhZ2UCAANlbmcAAAkDAAZsZW5ndGgAAAAAAAAAAAAACXRpbWVzY2FsZQBAj0AAAAAAAAAIbGFuZ3VhZ2UCAANlbmcAAAkDAAZsZW5ndGgAAAAAAAAAAAAACXRpbWVzY2FsZQBAj0AAAAAAAAAIbGFuZ3VhZ2UCAANlbmcAAAkAB2N1c3RkZWYKAAAAAAAACQ==
					</metadata>
				</media>
				<bootstrapInfo
					 profile="named"
					 url="../../../../streams/zeriDVRMBRAppendSegment/streams/_definst_/livestream1/livestream1.bootstrap"
					 id="bootstrap903"
				>
				</bootstrapInfo>
				<bootstrapInfo
					 profile="named"
					 url="../../../../streams/zeriDVRMBRAppendSegment/streams/_definst_/livestream2/livestream2.bootstrap"
					 id="bootstrap28"
				>
				</bootstrapInfo>
				<bootstrapInfo
					 profile="named"
					 url="../../../../streams/zeriDVRMBRAppendSegment/streams/_definst_/livestream3/livestream3.bootstrap"
					 id="bootstrap1503"
				>
				</bootstrapInfo>
			</manifest>;

			return parser.parse(xml.toXMLString(), DVR_MBR_F4M_URL);
		}
		
		private function createDVRMBRManifest2():Manifest
		{
			var xml:XML = 
			<manifest xmlns="http://ns.adobe.com/f4m/1.0">
				<id>
					zeriDVRMBRAppendSegment/events/_definst_/live_dvr_mbr_event
				</id>
				<mimeType>
					
				</mimeType>
				<streamType>
					live
				</streamType>
				<duration>
					0
				</duration>
				<dvrInfo id="dvrInfo"/>
				<media
					 streamId="livestream1"
					 bitrate="350"
					 url="../../../../streams/zeriDVRMBRAppendSegment/streams/_definst_/livestream1/livestream1"
					 bootstrapInfoId="bootstrap903"
				>
					<metadata>
						AgAKb25NZXRhRGF0YQgAAAAAAAhkdXJhdGlvbgAAAAAAAAAAAAAFd2lkdGgAAAAAAAAAAAAABmhlaWdodAAAAAAAAAAAAAAMdmlkZW9jb2RlY2lkAgAESDI2NAAMYXVkaW9jb2RlY2lkAgAEbXA0YQAKYXZjcHJvZmlsZQBAUIAAAAAAAAAIYXZjbGV2ZWwAQD8AAAAAAAAABmFhY2FvdAAAAAAAAAAAAAAOdmlkZW9mcmFtZXJhdGUA//gAAAAAAAAAD2F1ZGlvc2FtcGxlcmF0ZQBA5YiAAAAAAAANYXVkaW9jaGFubmVscwA/8AAAAAAAAAAJdHJhY2tpbmZvCgAAAAMDAAZsZW5ndGgAAAAAAAAAAAAACXRpbWVzY2FsZQBAj0AAAAAAAAAIbGFuZ3VhZ2UCAANlbmcAAAkDAAZsZW5ndGgAAAAAAAAAAAAACXRpbWVzY2FsZQBAj0AAAAAAAAAIbGFuZ3VhZ2UCAANlbmcAAAkDAAZsZW5ndGgAAAAAAAAAAAAACXRpbWVzY2FsZQBAj0AAAAAAAAAIbGFuZ3VhZ2UCAANlbmcAAAkAB2N1c3RkZWYKAAAAAAAACQ==
					</metadata>
				</media>
				<media
					 streamId="livestream2"
					 bitrate="500"
					 url="../../../../streams/zeriDVRMBRAppendSegment/streams/_definst_/livestream2/livestream2"
					 bootstrapInfoId="bootstrap28"
				>
					<metadata>
						AgAKb25NZXRhRGF0YQgAAAAAAAhkdXJhdGlvbgAAAAAAAAAAAAAFd2lkdGgAAAAAAAAAAAAABmhlaWdodAAAAAAAAAAAAAAMdmlkZW9jb2RlY2lkAgAESDI2NAAMYXVkaW9jb2RlY2lkAgAEbXA0YQAKYXZjcHJvZmlsZQBAUIAAAAAAAAAIYXZjbGV2ZWwAQD8AAAAAAAAABmFhY2FvdAAAAAAAAAAAAAAOdmlkZW9mcmFtZXJhdGUA//gAAAAAAAAAD2F1ZGlvc2FtcGxlcmF0ZQBA5YiAAAAAAAANYXVkaW9jaGFubmVscwA/8AAAAAAAAAAJdHJhY2tpbmZvCgAAAAMDAAZsZW5ndGgAAAAAAAAAAAAACXRpbWVzY2FsZQBAj0AAAAAAAAAIbGFuZ3VhZ2UCAANlbmcAAAkDAAZsZW5ndGgAAAAAAAAAAAAACXRpbWVzY2FsZQBAj0AAAAAAAAAIbGFuZ3VhZ2UCAANlbmcAAAkDAAZsZW5ndGgAAAAAAAAAAAAACXRpbWVzY2FsZQBAj0AAAAAAAAAIbGFuZ3VhZ2UCAANlbmcAAAkAB2N1c3RkZWYKAAAAAAAACQ==
					</metadata>
				</media>
				<media
					 streamId="livestream3"
					 bitrate="1000"
					 url="../../../../streams/zeriDVRMBRAppendSegment/streams/_definst_/livestream3/livestream3"
					 bootstrapInfoId="bootstrap1503"
				>
					<metadata>
						AgAKb25NZXRhRGF0YQgAAAAAAAhkdXJhdGlvbgAAAAAAAAAAAAAFd2lkdGgAAAAAAAAAAAAABmhlaWdodAAAAAAAAAAAAAAMdmlkZW9jb2RlY2lkAgAESDI2NAAMYXVkaW9jb2RlY2lkAgAEbXA0YQAKYXZjcHJvZmlsZQBAUIAAAAAAAAAIYXZjbGV2ZWwAQD8AAAAAAAAABmFhY2FvdAAAAAAAAAAAAAAOdmlkZW9mcmFtZXJhdGUA//gAAAAAAAAAD2F1ZGlvc2FtcGxlcmF0ZQBA5YiAAAAAAAANYXVkaW9jaGFubmVscwA/8AAAAAAAAAAJdHJhY2tpbmZvCgAAAAMDAAZsZW5ndGgAAAAAAAAAAAAACXRpbWVzY2FsZQBAj0AAAAAAAAAIbGFuZ3VhZ2UCAANlbmcAAAkDAAZsZW5ndGgAAAAAAAAAAAAACXRpbWVzY2FsZQBAj0AAAAAAAAAIbGFuZ3VhZ2UCAANlbmcAAAkDAAZsZW5ndGgAAAAAAAAAAAAACXRpbWVzY2FsZQBAj0AAAAAAAAAIbGFuZ3VhZ2UCAANlbmcAAAkAB2N1c3RkZWYKAAAAAAAACQ==
					</metadata>
				</media>
				<bootstrapInfo
					 profile="named"
					 url="../../../../streams/zeriDVRMBRAppendSegment/streams/_definst_/livestream1/livestream1.bootstrap"
					 id="bootstrap903"
				>
				</bootstrapInfo>
				<bootstrapInfo
					 profile="named"
					 url="../../../../streams/zeriDVRMBRAppendSegment/streams/_definst_/livestream2/livestream2.bootstrap"
					 id="bootstrap28"
				>
				</bootstrapInfo>
				<bootstrapInfo
					 profile="named"
					 url="../../../../streams/zeriDVRMBRAppendSegment/streams/_definst_/livestream3/livestream3.bootstrap"
					 id="bootstrap1503"
				>
				</bootstrapInfo>
			</manifest>;

			return parser.parse(xml.toXMLString(), DVR_MBR_F4M_URL);
		}

		private static var parser:ManifestParser = new ManifestParser();
		
		private static const SINGLE_STREAM_VOD_F4M_URL:String = "http://fms1j009f.corp.adobe.com/zeri-media/Fragments_Source_Media_Unprotected/215/avatar_4000.f4m";
		private static const DVR_MBR_F4M_URL:String = "http://fms1j009f.corp.adobe.com/zeri_live/events/zeriDVRMBRAppendSegment/events/_definst_/live_dvr_mbr_event.f4m";
	}
}