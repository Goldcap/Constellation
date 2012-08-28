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
	import flash.events.Event;
	import flash.events.EventDispatcher;
	import flash.events.TimerEvent;
	import flash.net.NetStream;
	import flash.utils.Timer;
	
	import flexunit.framework.TestCase;
	
	import org.osmf.events.MediaPlayerCapabilityChangeEvent;
	import org.osmf.media.DefaultMediaFactory;
	import org.osmf.media.MediaElement;
	import org.osmf.media.MediaFactory;
	import org.osmf.media.MediaPlayer;
	import org.osmf.media.URLResource;
	import org.osmf.traits.MediaTraitType;

	public class TestMulticast extends TestCase
	{
		public function TestMulticast(methodName:String=null)
		{
			super(methodName);
		}
		
		override public function setUp():void
		{
			super.setUp();

			eventDispatcher = new EventDispatcher();

			mediaPlayer = new MediaPlayer();
			mediaFactory = new DefaultMediaFactory();
		}
		
		override public function tearDown():void
		{
			super.tearDown();

			eventDispatcher = null;
			mediaPlayer = null;
			mediaFactory = null;
			timer = null;
		}

		public function testBasicMulticastPlayback():void
		{
			eventDispatcher.addEventListener(Event.COMPLETE, addAsync(onCompleteBasicMulticastPlayback, 10000));

			var resource:URLResource = new URLResource(MULTICAST_F4M);
			mediaPlayer.autoPlay = true;
			var media:MediaElement = mediaFactory.createMediaElement(resource);
			mediaPlayer.addEventListener(MediaPlayerCapabilityChangeEvent.CAN_PLAY_CHANGE, onCanPlayChange);
			mediaPlayer.media = media;
		}

		private function onCanPlayChange(event:MediaPlayerCapabilityChangeEvent):void
		{
			if (!mediaPlayer.canPlay)
			{
				return;
			}
			
			var loadTrait:NetStreamLoadTrait = mediaPlayer.media.getTrait(MediaTraitType.LOAD) as NetStreamLoadTrait;
			ns = loadTrait.netStream;	
			bufferFilled = false;		
			
			timer = new Timer(500);
			timer.addEventListener(TimerEvent.TIMER, onTimer);
			timer.start();
		}
		
		private function onTimer(event:TimerEvent):void
		{
			if (ns.bufferLength > 0)
			{
				bufferFilled = true;
				mediaPlayer.media = null;
				eventDispatcher.dispatchEvent(new Event(Event.COMPLETE));
			}
		}

		private function onCompleteBasicMulticastPlayback(event:Event):void
		{
			assertTrue(bufferFilled);
			timer.stop();
		}
		
		private var eventDispatcher:EventDispatcher;
		private var bufferFilled:Boolean;
		private var ns:NetStream;
		private var timer:Timer;
		private var mediaPlayer:MediaPlayer;
		private var mediaFactory:MediaFactory;
		
		private const MULTICAST_F4M:String = "http://flipside.corp.adobe.com/strobe/testing/multicast/manifestEpic.f4m";
	}
}