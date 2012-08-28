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
	import org.osmf.traits.TestPlayTrait;
	import org.osmf.utils.NetFactory;
	import org.osmf.utils.TestConstants;

	public class TestNetStreamPlayTrait extends TestPlayTrait
	{
		override public function setUp():void
		{
			netFactory = new NetFactory();
			
			var connection:NetConnection = netFactory.createNetConnection();
			connection.connect(null);
			stream = netFactory.createNetStream(connection);
			
			super.setUp();
		}
		
		override public function tearDown():void
		{
			super.tearDown();
			
			netFactory = null;
			stream = null;
		}

		override protected function createInterfaceObject(... args):Object
		{
			return new NetStreamPlayTrait(stream, new URLResource(TestConstants.REMOTE_PROGRESSIVE_VIDEO), false, null);
		}

		private var netFactory:NetFactory;
		private var stream:NetStream;
	}
}