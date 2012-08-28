/*
 * (C) Copyright 2010 Adobe Systems Incorporated. All Rights Reserved.
 *
 * NOTICE:  Adobe permits you to use, modify, and distribute this file in accordance with the
 * terms of the Adobe license agreement accompanying it.  If you have received this file from a
 * source other than Adobe, then your use, modification, or distribution of it requires the prior
 * written permission of Adobe.
 * THIS CODE AND INFORMATION IS PROVIDED "AS-IS" WITHOUT WARRANTY OF
 * ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE.
 *
 *  THIS CODE IS NOT SUPPORTED BY Adobe Systems Incorporated.
 *
 */


package {
    import flash.display.MovieClip;
	import flash.utils.getTimer;
    import flash.net.NetConnection;
    import flash.events.*;
    import flash.net.NetStream;
    import flash.media.Video;
	import flash.media.Camera;
    import flash.media.Microphone;
	import fl.controls.Button;


    public class DVR extends MovieClip
    {
		private var nc:NetConnection;
		private var ns:NetStream;
		private var nsPlayer:NetStream;
		private var vid:Video;
		private var vidPlayer:Video;
		private var cam:Camera;
		private var mic:Microphone;
		private var pauseBtn:Button;
		private var rewindBtn:Button;
		private var playBtn:Button;
		private var seekBtn:Button;
		private var dvrFlag:Boolean;
		private var stamp:uint;
		private var duration:uint;
		private var currentDuration:uint;
		private var seekVal;uint;

		public function DVR()
        {
			nc = new NetConnection();
			nc.addEventListener(NetStatusEvent.NET_STATUS, onNetStatus);
			nc.connect("rtmp://localhost/dvr");
			setupButtons();
			dvrFlag = true;
        }

		private function onNetStatus(event:NetStatusEvent):void{
			trace(event.info.code);
			switch(event.info.code){
				case "NetConnection.Connect.Success":
					publishCamera();
					displayPublishingVideo();
					displayPlaybackVideo();
					break;
				case "NetStream.Play.Start":
					trace("dvrFlag " + dvrFlag);
					if(dvrFlag){
						nsPlayer.seek(getSeekToLiveValue());
						dvrFlag = false;
					}
					break;
			}
		}

		private function onAsyncError(event:AsyncErrorEvent):void{
			    trace(event.text);
		}

		private function onClick(event:MouseEvent):void {
			switch(event.currentTarget){
				case rewindBtn:
					nsPlayer.seek(nsPlayer.time - 5);
					break;
				case pauseBtn:
					nsPlayer.pause();
					break;
				case playBtn:
					nsPlayer.resume();
					break;
				case seekBtn:
					nsPlayer.seek(getSeekToLiveValue());
					break;
			}

        }

		private function publishCamera() {
			cam = Camera.getCamera();
			mic = Microphone.getMicrophone();
			ns = new NetStream(nc);
			ns.client = this;
			ns.addEventListener(NetStatusEvent.NET_STATUS, onNetStatus);
			ns.addEventListener(AsyncErrorEvent.ASYNC_ERROR, onAsyncError);
			ns.attachCamera(cam);
			ns.attachAudio(mic);
			ns.publish("video", "record");
		}

		private function displayPublishingVideo():void {
			vid = new Video(cam.width, cam.height);
			vid.x = 10;
			vid.y = 10;
			vid.attachCamera(cam);
			addChild(vid);
		}

		private function displayPlaybackVideo():void{
			nsPlayer = new NetStream(nc);
			nsPlayer.client = this;
			nsPlayer.addEventListener(NetStatusEvent.NET_STATUS, onNetStatus);
			nsPlayer.addEventListener(AsyncErrorEvent.ASYNC_ERROR, onAsyncError);
			nsPlayer.play("video", 0, -1);
			vidPlayer = new Video(cam.width, cam.height);
			vidPlayer.x = cam.width + 20;
			vidPlayer.y = 10;
			vidPlayer.attachNetStream(nsPlayer);
			addChild(vidPlayer);
		}
		
		private function getSeekToLiveValue():uint{
			currentDuration = Number((getTimer()-stamp)/1000) + duration;
			trace("currentDuration: " + currentDuration);
			seekVal = (currentDuration - nsPlayer.bufferTime) - 2;
			trace("seekVal: " + seekVal);
			return seekVal;
		}

		private function setupButtons():void {
            rewindBtn = new Button();
			pauseBtn = new Button();
            playBtn = new Button();
			seekBtn = new Button();

			rewindBtn.width = 52;
			pauseBtn.width = 52;
			playBtn.width = 52;
			seekBtn.width = 100;

            rewindBtn.move(180,150);
            pauseBtn.move(235,150);
            playBtn.move(290,150);
			seekBtn.move(345, 150);

            rewindBtn.label = "Rew 5s";
            pauseBtn.label = "Pause";
            playBtn.label = "Play";
			seekBtn.label = "Seek to Live";

            rewindBtn.addEventListener(MouseEvent.CLICK, onClick);
            pauseBtn.addEventListener(MouseEvent.CLICK, onClick);
            playBtn.addEventListener(MouseEvent.CLICK, onClick);
			seekBtn.addEventListener(MouseEvent.CLICK, onClick);

            addChild(rewindBtn);
            addChild(pauseBtn);
            addChild(playBtn);
			addChild(seekBtn);
        }
		
		public function onMetaData(info:Object):void {
       		trace("metadata:duration = " + info.duration);
			stamp = getTimer();
			trace("stamp: " + stamp);
			duration = info.duration;
    	}
	}
}