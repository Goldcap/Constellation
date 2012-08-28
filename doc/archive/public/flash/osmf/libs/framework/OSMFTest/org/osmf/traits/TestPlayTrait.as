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
package org.osmf.traits
{
	import __AS3__.vec.Vector;
	
	import org.osmf.events.PlayEvent;
	import org.osmf.utils.InterfaceTestCase;
	
	import flash.events.Event;

	public class TestPlayTrait extends InterfaceTestCase
	{
		/**
		 * Subclasses can override to specify their own trait class.
		 **/
		override protected function createInterfaceObject(... args):Object
		{
			return new PlayTrait();
		}
		
		override public function setUp():void
		{
			super.setUp();
			
			playTrait = createInterfaceObject() as PlayTrait;
			events = [];
		}
		
		public function testCanPause():void
		{
			assertTrue(playTrait.canPause == true);
		}

		public function testPlayState():void
		{
			assertTrue(playTrait.playState == PlayState.STOPPED);
		}
		
		public function testPlay():void
		{
			assertTrue(playTrait.playState == PlayState.STOPPED);
			
			playTrait.addEventListener(PlayEvent.PLAY_STATE_CHANGE, eventCatcher);
			
			playTrait.play();
			assertTrue(playTrait.playState == PlayState.PLAYING);
			
			// A change event should have been fired:
			assertTrue(events.length == 1);

			// Verify that the event holds the correct info:			
			var pce:PlayEvent = events[0] as PlayEvent;
			assertNotNull(pce);
			assertTrue(pce.type == PlayEvent.PLAY_STATE_CHANGE);
			assertTrue(pce.playState == PlayState.PLAYING);
			
			// Play again. This is not a change, so no event should be triggered:
			playTrait.play();
			assertTrue(playTrait.playState == PlayState.PLAYING);
			assertTrue(events.length == 1);
		}

		public function testPause():void
		{
			assertTrue(playTrait.playState == PlayState.STOPPED);
			
			playTrait.addEventListener(PlayEvent.PLAY_STATE_CHANGE, eventCatcher);
			
			playTrait.pause();
			assertTrue(playTrait.playState == PlayState.PAUSED);
			
			// A change event should have been fired:
			assertTrue(events.length == 1);

			// Verify that the event holds the correct info:			
			var pce:PlayEvent = events[0] as PlayEvent;
			assertNotNull(pce);
			assertTrue(pce.type == PlayEvent.PLAY_STATE_CHANGE);
			assertTrue(pce.playState == PlayState.PAUSED);
			
			// Pause again. This is not a change, so no event should be triggered:
			playTrait.pause();
			assertTrue(playTrait.playState == PlayState.PAUSED);
			assertTrue(events.length == 1);
		}

		public function testStop():void
		{
			assertTrue(playTrait.playState == PlayState.STOPPED);
			
			playTrait.addEventListener(PlayEvent.PLAY_STATE_CHANGE, eventCatcher);
			
			// Stopping when already stopped is not a change.
			playTrait.stop();
			assertTrue(playTrait.playState == PlayState.STOPPED);
			assertTrue(events.length == 0);
			
			playTrait.play();
			assertTrue(playTrait.playState == PlayState.PLAYING);
			assertTrue(events.length == 1);
			
			// Stopping when playing should trigger an event.
			playTrait.stop();
			assertTrue(playTrait.playState == PlayState.STOPPED);
			assertTrue(events.length == 2);
			
			// Verify that the event holds the correct info:			
			var pce:PlayEvent = events[1] as PlayEvent;
			assertNotNull(pce);
			assertTrue(pce.type == PlayEvent.PLAY_STATE_CHANGE);
			assertTrue(pce.playState == PlayState.STOPPED);
			
			playTrait.pause();
			assertTrue(playTrait.playState == PlayState.PAUSED);
			assertTrue(events.length == 3);
			
			// Stopping when paused should trigger an event.
			playTrait.stop();
			assertTrue(playTrait.playState == PlayState.STOPPED);
			assertTrue(events.length == 4);
			
			// Verify that the event holds the correct info:			
			pce = events[3] as PlayEvent;
			assertNotNull(pce);
			assertTrue(pce.type == PlayEvent.PLAY_STATE_CHANGE);
			assertTrue(pce.playState == PlayState.STOPPED);
		}
		
		// Utils
		//
		
		protected var playTrait:PlayTrait;
		protected var events:Array;
		
		protected function eventCatcher(event:Event):void
		{
			events.push(event);
		}
	}
}