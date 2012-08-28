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
    import flash.display.Sprite;
    import flash.net.NetConnection;
    import flash.events.NetStatusEvent;
    import flash.events.AsyncErrorEvent;
    import flash.net.NetStream;
    import flash.media.Video;

    public class MediaPlayer extends Sprite
    {
        var nc:NetConnection;
        var ns:NetStream;
        var video:Video;

        public function MediaPlayer()
        {
            nc = new NetConnection();
            nc.addEventListener(NetStatusEvent.NET_STATUS, netStatusHandler);
			nc.connect("rtmp://localhost/mediaplayer");
        }



        private function netStatusHandler(event:NetStatusEvent):void
        {
			trace("event.info.level: " + event.info.level + "\n", "event.info.code: " + event.info.code);
            switch (event.info.code)
            {
                case "NetConnection.Connect.Success":
					// Call doPlaylist() or doVideo() here.
	                doPlaylist(nc);
	                break;
				case "NetConnection.Connect.Failed":
					// Handle this case here.
					break;
                case "NetConnection.Connect.Rejected":
					// Handle this case here.
					break;
	            case "NetStream.Play.Stop":
					// Handle this case here.
					break;
	            case "NetStream.Play.StreamNotFound":
					// Handle this case here.
					break;
	            case "NetStream.Publish.BadName":
	                trace("The stream name is already used");
					// Handle this case here.
	                break;
	        }
        }


        // play a recorded stream on the server
        private function doVideo(nc:NetConnection):void {
            ns = new NetStream(nc);
            ns.addEventListener(NetStatusEvent.NET_STATUS, netStatusHandler);
            ns.client = new CustomClient();

            video = new Video();
            video.attachNetStream(ns);

            ns.play("bikes", 0);
            addChild(video);
        }

        // create a playlist on the server
        private function doPlaylist(nc:NetConnection):void {
            ns = new NetStream(nc);
            ns.addEventListener(NetStatusEvent.NET_STATUS, netStatusHandler);
            ns.client = new CustomClient();

            video = new Video();
            video.attachNetStream(ns);

            // Play the first 3 seconds of the video
            ns.play( "bikes", 0, 3, true );
			// Play from 20 seconds on
            ns.play( "bikes", 20, -1, false);
			// End on frame 5
			ns.play( "bikes", 5, 0, false );
            addChild(video);
        }
    }
}


class CustomClient {
    public function onMetaData(info:Object):void {
        trace("metadata: duration=" + info.duration + " width=" + info.width + " height=" + info.height + " framerate=" + info.framerate);
    }
    public function onPlayStatus(info:Object):void {
        trace("handling playstatus here");
    }
}





