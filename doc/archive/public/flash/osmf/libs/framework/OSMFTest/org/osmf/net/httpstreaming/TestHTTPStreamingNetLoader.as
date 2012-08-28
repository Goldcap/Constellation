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
*  The Initial Developer of the Original Code is Adobe Systems Incorporated.
*  Portions created by Adobe Systems Incorporated are Copyright (C) 2009 Adobe Systems 
*  Incorporated. All Rights Reserved. 
*  
*****************************************************/
package org.osmf.net.httpstreaming
{
	import flash.events.EventDispatcher;
	import flash.events.Event;
	
	import flexunit.framework.TestCase;
	
	import org.osmf.elements.f4mClasses.*;
	import org.osmf.events.*;
	import org.osmf.media.*;
	import org.osmf.net.*;
	import org.osmf.traits.*;

	public class TestHTTPStreamingNetLoader extends TestCase
	{
		public function TestHTTPStreamingNetLoader(methodName:String=null)
		{
			super(methodName);
		}
		
		override public function setUp():void
		{
			super.setUp();
			
			this.parser = new ManifestParser();
			this.loader = new HTTPStreamingNetLoader();
			this.eventDispatcher = new EventDispatcher();
		}
		
		override public function tearDown():void
		{
			super.tearDown();
			
			this.loader = null;
			this.parser = null;
			this.eventDispatcher = null;
		}
		
		public function testCanHandleResource():void
		{
			assertEquals(loader.canHandleResource(new URLResource("http://example.com")), false);	
			assertEquals(loader.canHandleResource(new URLResource("http://example.com/movie.f4m")), false);	

			var manifest:Manifest = createSingleStreamVODManifest();
			assertTrue(manifest != null);
			
			var resource:URLResource = new URLResource(SINGLE_STREAM_VOD_F4M_URL);
			var urlResource:URLResource = parser.createResource(manifest, resource) as URLResource;
			assertTrue(urlResource != null);
			assertEquals(loader.canHandleResource(urlResource), true);	
		}
		
		public function testLoadVOD():void
		{
			testLoad(createSingleStreamVODManifest(), SINGLE_STREAM_VOD_F4M_URL);
		}
		
		public function testLoadDVRMBR():void
		{
			testLoad(createDVRMBRManifest(), DVR_MBR_F4M_URL);
		}
		
		private function testLoad(manifest:Manifest, url:String):void
		{
			assertTrue(manifest != null);

			eventDispatcher.addEventListener("testComplete",addAsync(mustReceiveEvent,TEST_TIME));
			
			var resource:URLResource = new URLResource(url);
			var urlResource:URLResource = parser.createResource(manifest, resource) as URLResource;
			assertTrue(urlResource != null);
			
			var loadTrait:LoadTrait = new NetStreamLoadTrait(loader, urlResource);
			loadTrait.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onLoad);
			loadTrait.load();

			function onLoad(event:LoadEvent):void
			{
				assertTrue(event.type == LoadEvent.LOAD_STATE_CHANGE);
					
				if (event.loadState == LoadState.READY)
				{
					eventDispatcher.dispatchEvent(new Event("testComplete"));
				}
			}
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
					 id="bootstrap903"
				>
					AAAAi2Fic3QAAAAAAAAAAgAAAAPoAAAAAAADN8AAAAAAAAAAAAAAAAAAAQAAABlhc3J0AAAAAAAAAAABAAAAAQAAADUBAAAARmFmcnQAAAAAAAAD6AAAAAADAAAAAQAAAAAAAAAAAAAPoAAAADUAAAAAAAMsgAAAC1QAAAAAAAAAAAAAAAAAAAAAAA==
				</bootstrapInfo>
				<bootstrapInfo
					 profile="named"
					 id="bootstrap28"
				>
					AAAAi2Fic3QAAAAAAAAAAgAAAAPoAAAAAAADN8AAAAAAAAAAAAAAAAAAAQAAABlhc3J0AAAAAAAAAAABAAAAAQAAADUBAAAARmFmcnQAAAAAAAAD6AAAAAADAAAAAQAAAAAAAAAAAAAPoAAAADUAAAAAAAMsgAAAC1QAAAAAAAAAAAAAAAAAAAAAAA==
				</bootstrapInfo>
				<bootstrapInfo
					 profile="named"
					 id="bootstrap1503"
				>
					AAAAi2Fic3QAAAAAAAAAAgAAAAPoAAAAAAADN8AAAAAAAAAAAAAAAAAAAQAAABlhc3J0AAAAAAAAAAABAAAAAQAAADUBAAAARmFmcnQAAAAAAAAD6AAAAAADAAAAAQAAAAAAAAAAAAAPoAAAADUAAAAAAAMsgAAAC1QAAAAAAAAAAAAAAAAAAAAAAA==
				</bootstrapInfo>
			</manifest>;

			return parser.parse(xml.toXMLString(), DVR_MBR_F4M_URL);
		}

		private function mustReceiveEvent(event:Event):void
		{
			// Placeholder to ensure an event is received.
		}

		private var loader:HTTPStreamingNetLoader;
		private var parser:ManifestParser;
		private var eventDispatcher:EventDispatcher;

		private static const SINGLE_STREAM_VOD_F4M_URL:String = "http://fms1j009f.corp.adobe.com/zeri-media/Fragments_Source_Media_Unprotected/215/avatar_4000.f4m";
		private static const DVR_MBR_F4M_URL:String = "http://fms1j009f.corp.adobe.com/zeri_live/events/zeriDVRMBRAppendSegment/events/_definst_/live_dvr_mbr_event.f4m";
		private static const TEST_TIME:Number = 4000;
	}
}