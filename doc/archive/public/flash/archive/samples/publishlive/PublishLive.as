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
    import flash.net.NetConnection;
    import flash.events.NetStatusEvent;
    import flash.net.NetStream;
    import flash.media.Video;
	import flash.media.Camera;
    import flash.media.Microphone;

    public class PublishLive extends MovieClip
    {
		var nc:NetConnection;
		var ns:NetStream;
		var nsPlayer:NetStream;
		var vid:Video;
		var vidPlayer:Video;
		var cam:Camera;
		var mic:Microphone;

		public function PublishLive()
        {
			nc = new NetConnection();
			nc.addEventListener(NetStatusEvent.NET_STATUS, onNetStatus);
			nc.connect("rtmp://localhost/publishlive");
        }

		private function onNetStatus(event:NetStatusEvent):void{
			trace(event.info.code);
			if(event.info.code == "NetConnection.Connect.Success"){
				publishCamera();
				displayPublishingVideo();
				displayPlaybackVideo();
			}
		}

		private function publishCamera() {
			cam = Camera.getCamera();
			mic = Microphone.getMicrophone();
			ns = new NetStream(nc);
			ns.attachCamera(cam);
			ns.attachAudio(mic);
			ns.publish("myCamera", "live");
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
			nsPlayer.play("myCamera");
			vidPlayer = new Video(cam.width, cam.height);
			vidPlayer.x = cam.width + 20;
			vidPlayer.y = 10;
			vidPlayer.attachNetStream(nsPlayer);
			addChild(vidPlayer);
		}
	}
}