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

    import flash.net.NetStream;
    import flash.net.Responder;
    import flash.media.Video;

    public class StreamLength extends Sprite
    {
        var nc:NetConnection;
        var stream:NetStream;
        var video:Video;
        var responder:Responder;


        public function StreamLength()
        {
            nc = new NetConnection();
            nc.addEventListener(NetStatusEvent.NET_STATUS, netStatusHandler);
            nc.connect("rtmp://localhost/StreamLength");
        }

        private function netStatusHandler(event:NetStatusEvent):void
        {
            trace("connected is: " + nc.connected );
			trace("event.info.level: " + event.info.level);
			trace("event.info.code: " + event.info.code);

            switch (event.info.code)
            {
                case "NetConnection.Connect.Success":
	                trace("Congratulations! you're connected");
	                connectStream(nc);
	                break;
                case "NetConnection.Connect.Rejected":
				case "NetConnection.Connect.Failed":
	                trace ("Oops! the connection was rejected");
	                break;
	        }
        }


        // play a recorded stream on the server
        private function connectStream(nc:NetConnection):void {
            stream = new NetStream(nc);
            stream.addEventListener(NetStatusEvent.NET_STATUS, netStatusHandler);
            stream.client = new CustomClient();

            responder = new Responder(onResult);
            nc.call("getStreamLength", responder, "bikes" );
        }


        private function onResult(result:Object):void {
            trace("The stream length is " + result + " seconds");
			output.text = "The stream length is " + result + " seconds";
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
