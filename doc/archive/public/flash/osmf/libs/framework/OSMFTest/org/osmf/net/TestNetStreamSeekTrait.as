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
	import flash.net.NetConnection;
	import flash.net.NetStream;
	
	import org.osmf.media.URLResource;
	import org.osmf.netmocker.MockNetStream;
	import org.osmf.traits.LoadTrait;
	import org.osmf.traits.TestSeekTrait;
	import org.osmf.traits.TimeTrait;
	import org.osmf.utils.DynamicLoadTrait;
	import org.osmf.utils.NetFactory;
	import org.osmf.utils.TestConstants;

	public class TestNetStreamSeekTrait extends TestSeekTrait
	{
		public function testCanSeekToWithBytesLoaded():void
		{
			var connection:NetConnection = netFactory.createNetConnection();
			connection.connect(null);
			var stream:NetStream = netFactory.createNetStream(connection);
			
			if (stream is MockNetStream)
			{
				MockNetStream(stream).expectedDuration = 20;
			}
			
			var loadTrait:DynamicLoadTrait = new DynamicLoadTrait(null, null);
			var timeTrait:TimeTrait = new NetStreamTimeTrait(stream, new URLResource(TestConstants.REMOTE_PROGRESSIVE_VIDEO));
			var seekTrait:NetStreamSeekTrait =  new NetStreamSeekTrait(timeTrait, loadTrait, stream);	
			
			stream.play(TestConstants.REMOTE_PROGRESSIVE_VIDEO);
			stream.pause();

			assertTrue(seekTrait.canSeekTo(1) == true);
			assertTrue(seekTrait.canSeekTo(20) == true);
			assertTrue(seekTrait.canSeekTo(21) == false);
			
			// Set the byte count so that our canSeekTo returns false for some values.
			loadTrait.bytesTotal = 100;
			
			loadTrait.bytesLoaded = 10;
			assertTrue(seekTrait.canSeekTo(2) == true);
			assertTrue(seekTrait.canSeekTo(3) == false);
			
			loadTrait.bytesLoaded = 50;
			assertTrue(seekTrait.canSeekTo(10) == true);
			assertTrue(seekTrait.canSeekTo(11) == false);

			loadTrait.bytesLoaded = 99;
			assertTrue(seekTrait.canSeekTo(19) == true);
			assertTrue(seekTrait.canSeekTo(20) == false);
		}
		
		public function testCanSeekToWithFractionalDurationAndBytesLoaded():void
		{
			var connection:NetConnection = netFactory.createNetConnection();
			connection.connect(null);
			var stream:NetStream = netFactory.createNetStream(connection);
			
			if (stream is MockNetStream)
			{
				MockNetStream(stream).expectedDuration = 31.671;
			}
			
			var loadTrait:DynamicLoadTrait = new DynamicLoadTrait(null, null);
			var timeTrait:TimeTrait = new NetStreamTimeTrait(stream, new URLResource(TestConstants.REMOTE_PROGRESSIVE_VIDEO));
			var seekTrait:NetStreamSeekTrait =  new NetStreamSeekTrait(timeTrait, loadTrait, stream);	
			
			stream.play(TestConstants.REMOTE_PROGRESSIVE_VIDEO);
			stream.pause();
			
			// Verify that we don't truncate durations in the comparison (FM-875).
			loadTrait.bytesTotal = 2660334;
			loadTrait.bytesLoaded = 2660334;

			assertTrue(seekTrait.canSeekTo(31.671));
		}
		
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

		override protected function get processesSeekCompletion():Boolean
		{
			// NetStreamSeekTrait processes the completion of a seek itself.
			return true;
		}

		override protected function createInterfaceObject(... args):Object
		{
			var connection:NetConnection = netFactory.createNetConnection();
			connection.connect(null);
			var stream:NetStream = netFactory.createNetStream(connection);
			
			if (stream is MockNetStream)
			{
				MockNetStream(stream).expectedDuration = maxSeekValue;
			}
			
			var loadTrait:DynamicLoadTrait = new DynamicLoadTrait(null, null);
			var timeTrait:TimeTrait = new NetStreamTimeTrait(stream, new URLResource(TestConstants.REMOTE_PROGRESSIVE_VIDEO));
			var seekTrait:NetStreamSeekTrait =  new NetStreamSeekTrait(timeTrait, loadTrait, stream);	
			
			stream.play(TestConstants.REMOTE_PROGRESSIVE_VIDEO);
			stream.pause();
			
			return seekTrait;
		}
				
		private var netFactory:NetFactory;
	}
}