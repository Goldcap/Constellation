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
	import flash.events.MouseEvent;
	import flash.events.AsyncErrorEvent;
    import flash.net.NetStream;
    import flash.media.Video;
	import flash.media.Camera;
    import flash.media.Microphone;
	import fl.controls.Button;
	import fl.controls.Label;
	import fl.controls.TextArea;


    public class Metadata extends MovieClip {
		private var nc:NetConnection;
		private var ns:NetStream;
		private var nsPlayer:NetStream;
		private var vid:Video;
		private var vidPlayer:Video;
		private var cam:Camera;
		private var mic:Microphone;
		private var clearBtn:Button;
		private var startPlaybackBtn:Button;
		private var outgoingLbl:Label;
		private var incomingLbl:Label;
		private var myMetadata:Object;
		private var outputWindow:TextArea;

		public function Metadata(){
			setupUI();
			nc = new NetConnection();
			nc.addEventListener(NetStatusEvent.NET_STATUS, onNetStatus);
			nc.connect("rtmp://localhost/publishlive");
        }

		/*
		 *  Clear the MetaData associated with the stream
		 */
		private function clearHandler(event:MouseEvent):void {
			if (ns){
				trace("Clearing MetaData");
				ns.send("@clearDataFrame", "onMetaData");
			}
		}

		private function startHandler(event:MouseEvent):void {
			displayPlaybackVideo();
		}

		private function onNetStatus(event:NetStatusEvent):void {
			trace(event.target + ": " + event.info.code);
            switch (event.info.code)
            {
                case "NetConnection.Connect.Success":
					publishCamera();
					displayPublishingVideo();
					break;
	        }
        }

		private function asyncErrorHandler(event:AsyncErrorEvent):void {
			trace(event.text);
		}

		private function sendMetadata():void {
			trace("sendMetaData() called")
			myMetadata = new Object();
            myMetadata.customProp = "This message is sent by @setDataFrame.";
            ns.send("@setDataFrame", "onMetaData", myMetadata);
		}

		private function publishCamera():void {
			cam = Camera.getCamera();
			mic = Microphone.getMicrophone();
			ns = new NetStream(nc);
			ns.client = this;
			ns.addEventListener(NetStatusEvent.NET_STATUS, onNetStatus);
			ns.addEventListener(AsyncErrorEvent.ASYNC_ERROR, asyncErrorHandler);
			ns.attachCamera(cam);
			ns.attachAudio(mic);
			ns.publish("myCamera", "record");
			sendMetadata();

		}

		private function displayPublishingVideo():void {
			vid = new Video(cam.width, cam.height);
			vid.x = 10;
			vid.y = 10;
			vid.attachCamera(cam);
			addChild(vid);
		}

		private function displayPlaybackVideo():void {
			nsPlayer = new NetStream(nc);
			nsPlayer.client = this;
			nsPlayer.addEventListener(NetStatusEvent.NET_STATUS, onNetStatus);
			nsPlayer.addEventListener(AsyncErrorEvent.ASYNC_ERROR, asyncErrorHandler);
			nsPlayer.play("myCamera", 1);
			vidPlayer = new Video(cam.width, cam.height);
			vidPlayer.x = cam.width + 100;
			vidPlayer.y = 10;
			vidPlayer.attachNetStream(nsPlayer);
			addChild(vidPlayer);
		}

		private function setupUI():void {
			outputWindow = new TextArea();
			outputWindow.move(250, 175);
			outputWindow.width = 250;
			outputWindow.height = 150;

			outgoingLbl = new Label();
			incomingLbl = new Label();
			outgoingLbl.width = 150;
			incomingLbl.width = 150;
			outgoingLbl.text = "Publishing Stream";
			incomingLbl.text = "Playback Stream";
			outgoingLbl.move(30, 150);
			incomingLbl.move(300, 150);

			startPlaybackBtn = new Button();
			startPlaybackBtn.width = 150;
			startPlaybackBtn.move(250, 345)
			startPlaybackBtn.label = "View Live Event";
			startPlaybackBtn.addEventListener(MouseEvent.CLICK, startHandler);

			clearBtn = new Button();
			clearBtn.width = 100;
            clearBtn.move(135,345);
            clearBtn.label = "Clear Metadata";
			clearBtn.addEventListener(MouseEvent.CLICK, clearHandler);

            addChild(clearBtn);
			addChild(outgoingLbl);
			addChild(incomingLbl);
			addChild(startPlaybackBtn);
			addChild(outputWindow);
        }

		public function onMetaData(info:Object):void { 
			var key:String; 
			for (key in info){ 
				outputWindow.appendText(key + ": " + info[key] + "\n"); 
			} 
		}
	}
}