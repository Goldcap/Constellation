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
package org.osmf.media
{
	import org.osmf.elements.VideoElement;
	import org.osmf.elements.f4mClasses.*;
	import org.osmf.net.StreamingURLResource;
	import org.osmf.net.httpstreaming.*;
	import org.osmf.netmocker.MockNetLoader;
	import org.osmf.netmocker.NetConnectionExpectation;
	import org.osmf.utils.TestConstants;
	
	public class TestMediaPlayerWithHTTPStreamingVideoElement extends TestMediaPlayerWithLightweightVideoElement
	{
		public function TestMediaPlayerWithHTTPStreamingVideoElement()
		{
			super();
		}
		
		override public function setUp():void
		{
			super.setUp();
			
			this.parser = new ManifestParser();
			this.invalidResource = new StreamingURLResource("rtmp://cp67126.edgefcsfail.net/ondemand/mediapm/strobe/content/test/SpaceAloneHD_sounas_640_500_short.flv");
		}

		override public function tearDown():void
		{
			super.tearDown();
			
			this.parser = null;
			this.invalidResource = null;
		}

			/*
			 * There is a bug with MediaPlayer that causes this test to fail, FM-853.
			 * It is too late to fix MediaPlayer. Therefore, we temporarily disable
			 * the test for HTTPNetStream unit tests. 
			 */
/*
		override public function testAutoPlay():void
		{
		}
*/
		
		protected function createHTTPStreamingNetLoader():HTTPStreamingNetLoader
		{
			return new HTTPStreamingNetLoader();
		}
		
		override protected function createMediaElement(resource:MediaResourceBase):MediaElement
		{
			if (resource == this.invalidResource)
			{
				if (loader is MockNetLoader)
				{
					// Give our mock loader an arbitrary duration and size to ensure
					// we get metadata.
					MockNetLoader(loader).netStreamExpectedDuration = 1;//TestConstants.REMOTE_PROGRESSIVE_VIDEO_EXPECTED_DURATION;
					MockNetLoader(loader).netStreamExpectedWidth = TestConstants.REMOTE_PROGRESSIVE_VIDEO_EXPECTED_WIDTH;
					MockNetLoader(loader).netStreamExpectedHeight = TestConstants.REMOTE_PROGRESSIVE_VIDEO_EXPECTED_HEIGHT;				
					MockNetLoader(loader).netConnectionExpectation = NetConnectionExpectation.INVALID_FMS_APPLICATION;
					
				}
				return super.createMediaElement(resource);
			}
			
			
			return new VideoElement(resource as URLResource, createHTTPStreamingNetLoader());
		}
		
		override protected function get resourceForMediaElement():MediaResourceBase
		{
			var manifest:Manifest = createSingleStreamVODManifest();
			var rs:StreamingURLResource = new StreamingURLResource(SINGLE_STREAM_VOD_F4M_URL);
			var urlResource:StreamingURLResource = parser.createResource(manifest, rs) as StreamingURLResource;
			return urlResource;
		}
		
		override protected function get invalidResourceForMediaElement():MediaResourceBase
		{
			// Subclasses can override to specify a resource that the
			// MediaElement should fail to load.
			return this.invalidResource;
		}
		
		override protected function get expectedMediaWidthAfterLoad():Number
		{
			return 360;
		}

		override protected function get expectedMediaHeightAfterLoad():Number
		{
			return 288;
		}

		private function createSingleStreamVODManifest():Manifest
		{
			var xml:XML = 
				<manifest xmlns="http://ns.adobe.com/f4m/1.0">
					<id>
						barsandtone
					</id>
					<streamType>
						recorded
					</streamType>
					<duration>
						6.0599999999999996
					</duration>
					<bootstrapInfo
						 profile="named"
						 id="bootstrap1281"
					>
						AAAAi2Fic3QAAAAAAAAAAgAAAAPoAAAAAAAAF6wAAAAAAAAAAAAAAAAAAQAAABlhc3J0AAAAAAAAAAABAAAAAQAAAAIBAAAARmFmcnQAAAAAAAAD6AAAAAADAAAAAQAAAAAAAAAAAAAXcAAAAAIAAAAAAAAXlgAAAGQAAAAAAAAAAAAAAAAAAAAAAA==
					</bootstrapInfo>
					<media
						 streamId="barsandtone"
						 url="barsandtone"
						 bootstrapInfoId="bootstrap1281"
					>
						<metadata>
							AgAKb25NZXRhRGF0YQgAAAAAAAhkdXJhdGlvbgBAGD1wo9cKPQAFd2lkdGgAQHaAAAAAAAAABmhlaWdodABAcgAAAAAAAAAMdmlkZW9jb2RlY2lkAgAEVlA2RgAMYXVkaW9jb2RlY2lkAgAELm1wMwAOdmlkZW9mcmFtZXJhdGUAAAAAAAAAAAAAD2F1ZGlvc2FtcGxlcmF0ZQBA5XwAAAAAAAANYXVkaW9jaGFubmVscwBAAAAAAAAAAAAJdHJhY2tpbmZvCgAAAAIDAAZsZW5ndGgAQLesAAAAAAAACXRpbWVzY2FsZQBAj0AAAAAAAAAIbGFuZ3VhZ2UCAANlbmcAAAkDAAZsZW5ndGgAQLesAAAAAAAACXRpbWVzY2FsZQBAj0AAAAAAAAAIbGFuZ3VhZ2UCAANlbmcAAAkAB2N1c3RkZWYKAAAAAAAACQ==
						</metadata>
					</media>
				</manifest>;
			
			return parser.parse(xml.toXMLString(), SINGLE_STREAM_VOD_F4M_URL);
		}

		protected var parser:ManifestParser;
		private var invalidResource:URLResource;
		
		private static const SINGLE_STREAM_VOD_F4M_URL:String = "http://fms1j009f.corp.adobe.com/zeri-media/Fragments_Source_Media_Unprotected/235/testVideoElement/barsandtone.f4m";
	}
}