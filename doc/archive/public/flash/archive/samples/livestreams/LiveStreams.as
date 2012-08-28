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
    import flash.events.ActivityEvent;
	import flash.events.MouseEvent;

    import flash.net.NetStream;
    import flash.media.Video;
    import flash.media.Microphone;
    import flash.media.Camera;

    public class LiveStreams extends MovieClip
    {
        var nc:NetConnection;
        var ns:NetStream;
        var video:Video;
        var camera:Camera;
        var mic:Microphone;

        public function LiveStreams()
        {
			startBtn.addEventListener(MouseEvent.CLICK, startHandler);
			stopBtn.addEventListener(MouseEvent.CLICK, stopHandler);
        }

		/*
		 *  Connect and start publishing the live stream
		 */
		private function startHandler(event:MouseEvent):void {
			nc = new NetConnection();
            nc.addEventListener(NetStatusEvent.NET_STATUS, netStatusHandler);
            nc.connect("rtmp://localhost/livestreams");
		}


		/*
		 *  Disconnect from the server
		 */
		private function stopHandler(event:MouseEvent):void {
			trace("Now we're disconnecting");
			nc.close();
		}

        private function netStatusHandler(event:NetStatusEvent):void
        {
            trace(event.info.code);
            switch (event.info.code)
            {
                case "NetConnection.Connect.Success":
	                publishLiveStream();
	                break;
	        }
        }


       	private function activityHandler(event:ActivityEvent):void {
		    trace("activityHandler: " + event);
		    trace("activating: " + event.activating);
	    }

		/*
		 *  Create a live stream, attach the camera and microphone, and
		 *  publish it to the local server
		 */
        private function publishLiveStream():void {
		    ns = new NetStream(nc);
		    ns.addEventListener(NetStatusEvent.NET_STATUS, netStatusHandler);

		    camera = Camera.getCamera();
		    mic = Microphone.getMicrophone();

		    if (camera != null){

				camera.addEventListener(ActivityEvent.ACTIVITY, activityHandler);

				video = new Video();
				video.attachCamera(camera);

				ns.attachCamera(camera);

                addChild(video);
			}

			if (mic != null) {
				mic.addEventListener(ActivityEvent.ACTIVITY, activityHandler);

			    ns.attachAudio(mic);
			}

			if (camera != null || mic != null){
				// start publishing
			    // triggers NetStream.Publish.Start
			    ns.publish("localnews", "live");
		    } else {
			    trace("Please check your camera and microphone");
		    }
	    }
    }
}

