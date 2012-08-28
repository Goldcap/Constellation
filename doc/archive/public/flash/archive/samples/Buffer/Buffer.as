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
	import flash.net.NetStream;
	import flash.events.NetStatusEvent;
	import flash.media.Video;

	public class Buffer extends MovieClip {

	  	private var nc:NetConnection;
		private var ns:NetStream;
		private var video:Video;

		/*
		 *  Constructor.
		 */
		public function Buffer() {

			trace("We're connecting...");
			nc = new NetConnection();
			nc.addEventListener(NetStatusEvent.NET_STATUS, netStatusHandler);
			nc.connect("rtmp://localhost/Buffer");
		}

		private function netStatusHandler(event:NetStatusEvent):void {
			switch (event.info.code) {
				case "NetConnection.Connect.Success":
					trace("The connection was successful");
					connectStream(nc);
					break;
				case "NetConnection.Connect.Failed":
					trace("The connection failed");
					break;
				case "NetConnection.Connect.Rejected":
					trace("The connection was rejected");
					break;
				case "NetStream.Buffer.Full":
					ns.bufferTime = 10;
					trace("Expanded buffer to 10");
					break;
				case "NetStream.Buffer.Empty":
					ns.bufferTime = 2;
					trace("Reduced buffer to 2");
					break;
			}
		}

		/*
		 *  Play a recorded stream on the server, and set the initial buffer time
		 */
		private function connectStream(nc:NetConnection):void {
			ns = new NetStream(nc);
			ns.addEventListener(NetStatusEvent.NET_STATUS, netStatusHandler);
			ns.client = new CustomClient();

			video = new Video();
			video.attachNetStream(ns);

			ns.play( "bikes", 0 );
			ns.bufferTime = 2;
			trace("Set an initial buffer time of 2");

			addChild(video);
		}
   }
}

class CustomClient {

	public function onMetaData(info:Object):void {
		trace("Metadata: duration=" + info.duration + " width=" + info.width + " height=" + info.height + " framerate=" + info.framerate);
	}

	public function onPlayStatus(info:Object):void {
		switch (info.code) {
			case "NetStream.Play.Complete":
				trace("The stream has completed");
				break;
		}
	}
}












