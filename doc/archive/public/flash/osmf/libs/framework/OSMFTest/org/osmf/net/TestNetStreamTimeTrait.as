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
	import org.osmf.traits.TestTimeTrait;
	import org.osmf.utils.NetFactory;
	import org.osmf.utils.TestConstants;

	public class TestNetStreamTimeTrait extends TestTimeTrait
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
		
		override public function testCurrentTime():void
		{
			assertTrue(timeTrait.currentTime == 0);
		}

		override protected function createInterfaceObject(... args):Object
		{
			var connection:NetConnection = netFactory.createNetConnection();
			connection.connect(null);
			stream = netFactory.createNetStream(connection);
			
			if (stream is MockNetStream)
			{
				MockNetStream(stream).expectedDuration = EXPECTED_DURATION;
			}
			return new NetStreamTimeTrait(stream, new URLResource(TestConstants.REMOTE_PROGRESSIVE_VIDEO));
		}
		
		private var stream:NetStream;
		private var netFactory:NetFactory;
		
		private static const EXPECTED_DURATION:Number = 5;
	}
}