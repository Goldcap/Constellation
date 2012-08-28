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
	import flash.events.NetStatusEvent;
	import flash.net.NetConnection;
	import flash.net.NetStream;
	
	import org.osmf.events.BufferEvent;
	import org.osmf.traits.TestBufferTrait;
	import org.osmf.utils.NetFactory;

	public class TestNetStreamBufferTrait extends TestBufferTrait
	{		
		override public function setUp():void
		{
			netFactory = new NetFactory(true);

			super.setUp();
		}
		
		override public function tearDown():void
		{
			super.tearDown();
			
			netFactory = null;
		}

		override protected function createInterfaceObject(... args):Object
		{
			var connection:NetConnection = netFactory.createNetConnection();
			connection.connect(null); 
			stream = netFactory.createNetStream(connection);
			return new NetStreamBufferTrait(stream);
		}
		
		public function testInitialBuffering():void
		{
			var values:Array = [true,false];
			var bufferingDone:Function = addAsync(function():void{}, 3000);
			
			assertFalse(bufferTrait.buffering);			
			bufferTrait.addEventListener(BufferEvent.BUFFERING_CHANGE, bufferingChange);
			stream.play("http://test/myvideo.flv");				
			
			function bufferingChange(event:BufferEvent):void
			{
				assertEquals(values.shift(), event.buffering);
				if (values.length == 0)
				{
					bufferingDone(null);					
				}
			}							
		}
		
		public function testBufferSizeAfterPlay():void
		{
			// Minimum 0.1 second buffer for VOD (only happens once play starts).
			
			assertEquals(bufferTrait.bufferTime, 0);
			stream.play("http://test/myvideo.flv");
			stream.addEventListener(NetStatusEvent.NET_STATUS, addAsync(onNetStatus, 1500));
			function onNetStatus(event:NetStatusEvent):void
			{
				if (event.info.code == NetStreamCodes.NETSTREAM_PLAY_START)
				{
					assertEquals(bufferTrait.bufferTime, .1);
				}				
			}			
		}
		
		private var netFactory:NetFactory;
		private var stream:NetStream;
	}
}
