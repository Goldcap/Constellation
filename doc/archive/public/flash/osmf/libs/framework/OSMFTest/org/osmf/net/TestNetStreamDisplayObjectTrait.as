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
	import flash.display.Sprite;
	import flash.display.Stage;
	import flash.events.Event;
	import flash.net.NetConnection;
	import flash.net.NetStream;
	import flash.system.ApplicationDomain;
	
	import mx.core.Application;
	import mx.core.FlexGlobals;
	
	import org.osmf.events.DisplayObjectEvent;
	import org.osmf.netmocker.MockNetStream;
	import org.osmf.traits.TestDisplayObjectTrait;
	import org.osmf.utils.NetFactory;
	import org.osmf.utils.TestConstants;
	
	public class TestNetStreamDisplayObjectTrait extends TestDisplayObjectTrait
	{
		override public function setUp():void
		{
			netFactory = new NetFactory();

			super.setUp();
		}
		
		override public function tearDown():void
		{
			super.tearDown();
			
			netFactory = null;
		}

		override protected function createInterfaceObject(... args):Object
		{
			return new NetStreamDisplayObjectTrait(createStream(), args.length > 0 ? args[0] : null, args.length > 1 ? args[1] : 0, args.length > 2 ? args[2] : 0);
		}
		
		public function testVideoSize():void
		{
			var stream:NetStream = createStream(20, 40);
			
			var sprite:VideoSizes = new VideoSizes();
			var displayObjectTrait:NetStreamDisplayObjectTrait = new NetStreamDisplayObjectTrait(stream, sprite);
			assertTrue(displayObjectTrait.mediaWidth == 0);
			assertTrue(displayObjectTrait.mediaHeight == 0);
					
			displayObjectTrait.addEventListener(DisplayObjectEvent.MEDIA_SIZE_CHANGE, onTestOnMetadata);
			
			stream.play(NetStreamUtils.getStreamNameFromURL(TestConstants.REMOTE_PROGRESSIVE_VIDEO));
			
			function onTestOnMetadata(event:DisplayObjectEvent):void
			{				
				assertTrue(displayObjectTrait.mediaWidth == 20);
				assertTrue(displayObjectTrait.mediaHeight == 40);
				displayObjectTrait.removeEventListener(DisplayObjectEvent.MEDIA_SIZE_CHANGE, onTestOnMetadata);
			}		
			
			function onTestVideoSizeDetection(event:DisplayObjectEvent):void
			{				
				assertTrue(displayObjectTrait.mediaWidth == 80);
				assertTrue(displayObjectTrait.mediaHeight == 90);
				stream.close();
			}			
			
			displayObjectTrait.addEventListener(DisplayObjectEvent.MEDIA_SIZE_CHANGE, addAsync(onTestVideoSizeDetection, 5000));
			
			var app:Application = FlexGlobals.topLevelApplication as Application;
			
			if (!app.stage)
			{
				app.addEventListener(Event.ADDED_TO_STAGE, onStage);
				function onStage(event:Event):void
				{					
					app.stage.addChild(sprite);	
				}
			}
			else
			{
				app.stage.addChild(sprite);	
			}			
		}	
		
					
		public function testOnMetadata():void
		{
			var stream:NetStream = createStream();
			
			var sprite:Sprite = new Sprite();
			var displayObjectTrait:NetStreamDisplayObjectTrait = new NetStreamDisplayObjectTrait(stream, sprite);
			assertTrue(displayObjectTrait.mediaWidth == 0);
			assertTrue(displayObjectTrait.mediaHeight == 0);
			
			displayObjectTrait.addEventListener(DisplayObjectEvent.MEDIA_SIZE_CHANGE, addAsync(onTestOnMetadata, 5000));
			
			stream.play(NetStreamUtils.getStreamNameFromURL(TestConstants.REMOTE_PROGRESSIVE_VIDEO));
		
			function onTestOnMetadata(event:DisplayObjectEvent):void
			{
				assertTrue(displayObjectTrait.mediaWidth == TestConstants.REMOTE_PROGRESSIVE_VIDEO_EXPECTED_WIDTH);
				assertTrue(displayObjectTrait.mediaHeight == TestConstants.REMOTE_PROGRESSIVE_VIDEO_EXPECTED_HEIGHT);
			}
		}
		
		// Internals
		//
		
		private function createStream(width:Number = TestConstants.REMOTE_PROGRESSIVE_VIDEO_EXPECTED_WIDTH, height:Number = TestConstants.REMOTE_PROGRESSIVE_VIDEO_EXPECTED_HEIGHT):NetStream
		{
			var connection:NetConnection = netFactory.createNetConnection();
			connection.connect(null);
			var stream:NetStream = netFactory.createNetStream(connection);
			
			if (stream is MockNetStream)
			{
				MockNetStream(stream).expectedDuration = TestConstants.REMOTE_PROGRESSIVE_VIDEO_EXPECTED_DURATION;
				MockNetStream(stream).expectedWidth = width;
				MockNetStream(stream).expectedHeight = height;
			}
			
			return stream;
		}
		
		private var netFactory:NetFactory;
	}
}
import flash.media.Video;

class VideoSizes extends Video 
{
	override public function get videoWidth():int
	{
		return 80;
	}
	
	
	override public function get videoHeight():int
	{
		return 90;
	}

	
}