//
// Copyright (c) 2009-2011, the Open Video Player authors. All rights reserved.
//
// Redistribution and use in source and binary forms, with or without 
// modification, are permitted provided that the following conditions are 
// met:
//
//    * Redistributions of source code must retain the above copyright 
//		notice, this list of conditions and the following disclaimer.
//    * Redistributions in binary form must reproduce the above 
//		copyright notice, this list of conditions and the following 
//		disclaimer in the documentation and/or other materials provided 
//		with the distribution.
//    * Neither the name of the openvideoplayer.org nor the names of its 
//		contributors may be used to endorse or promote products derived 
//		from this software without specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS 
// "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT 
// LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR 
// A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT 
// OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, 
// SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT 
// LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, 
// DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY 
// THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT 
// (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE 
// OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
//
package org.openvideoplayer.samples.akamai
{
	import com.akamai.net.AkamaiConnection;
	import com.akamai.net.AkamaiNetStream;
	import com.zeusprod.Log;
	
	import flash.events.Event;
	import flash.utils.describeType;
	
	import org.openvideoplayer.events.OvpEvent;
	import org.openvideoplayer.net.OvpConnection;
	import org.openvideoplayer.net.OvpNetStream;
	import org.openvideoplayer.samples.generic.BaseSample;
	import org.openvideoplayer.samples.presenter.BaseControlBarPresenter;
	import org.openvideoplayer.samples.presenter.LiveControlBarPresenter;
	import org.openvideoplayer.samples.view.BaseVideoView;
	import org.openvideoplayer.samples.view.LiveVideoView;
	import org.openvideoplayer.utils.MediaResources;


	public class LiveStreaming extends BaseSample
	{
		
		private var hostName:String = MediaResources.RTMP_STREAMING_LIVE.hostName;
		private var streamName:String = MediaResources.RTMP_STREAMING_LIVE.streamName;
		private var authToken:String = MediaResources.RTMP_STREAMING_LIVE.authToken;
		private static var _autoStart:Boolean = true;
		
		public function LiveStreaming()
		{
			this.addEventListener(Event.ADDED_TO_STAGE, stageInit);
			
            //super(describeType(this).@name.toString().split("::")[1]);
			super(describeType(this).@name.toString().split("::")[1]);
		}
		
		private function stageInit (evt:Event):void {
			// This is reached before the super class invokes its activate() function
			this.removeEventListener(Event.ADDED_TO_STAGE, stageInit);
			
			var flashParams:Object = stage.loaderInfo.parameters;
			
			//traceIt ("Before hostname '" 	+ hostName 		+ "'");
			//traceIt ("Before streamName '" 	+ streamName 	+ "'");
			//traceIt ("Before authToken '" 	+ authToken 	+ "'");
			if (flashParams) {
				if (flashParams.hostName) {
					hostName = "cp" + flashParams.hostName + ".live.edgefcs.net/live";		//"cp113557.live.edgefcs.net/live";
				}
				if (flashParams.streamName && flashParams.streamID) {
				streamName = flashParams.streamName + "@" + flashParams.streamID;			// "constellation@45907";
				}
				if (flashParams.auth && flashParams.aifp) {
					authToken = "auth=" + flashParams.auth + "&amp;aifp=" + flashParams.aifp;	// "auth=yyyy&amp;aifp=zzzz";
				}
				// Not difference in case of "autostart" vs. autoStart
				autoStart = (flashParams.autostartLiveViewer != "false");
			}
			traceIt ("After hostname '"		+ hostName 		+ "'");
			traceIt ("After streamName '" 	+ streamName 	+ "'");
			traceIt ("After authToken '" 	+ authToken 	+ "'");
			
			/*
			var vidWidth:Number = 200;
			var vidHeight:Number = 152;
			
			Log.traceMsg ("LiveStreaming.stageInit default width/height " + vidWidth + " " + vidHeight, Log.LOG_TO_CONSOLE);
			
			// Use the viewerWidth and viewerHeight, as specified, if applicable.
			if (!isNaN(Number(flashParams.viewerWidth))) {
				
				vidWidth = Number(flashParams.viewerWidth);
				Log.traceMsg ("LiveStreaming.stageInit Good vidWidth " + vidWidth, Log.LOG_TO_CONSOLE);
			
			} else {
				Log.traceMsg ("LiveStreaming.stageInit Bad flashParams.viewerWidth " + flashParams.viewerWidth + " Number: "+ Number(flashParams.viewerWidth), Log.ALERT);
			}
			
			if (!isNaN(Number(flashParams.viewerHeight))) {
				vidHeight = Number(flashParams.viewerHeight);
				Log.traceMsg ("LiveStreaming.stageInit Good vidHeight " + vidHeight, Log.LOG_TO_CONSOLE);
			
			}else {
				Log.traceMsg ("LiveStreaming.stageInit Bad flashParams.viewerHeight " + flashParams.viewerHeight + " Number: " + Number(flashParams.viewerHeight), Log.ALERT);
			}
			Log.traceMsg ("LiveStreaming.stageInit vidWidth and vidHeight " + vidWidth + " " + vidHeight, Log.LOG_TO_CONSOLE);
			setHeightAndWidth(vidWidth, vidHeight);
			*/
            //if (autostart) {
				// This won't help because it is the wrong object dispatching the event and no one is listening.
				//dispatchEvent(new ControlEvent(ControlEvent.PLAY));
			//}
		}
		
		public static function get autoStart():Boolean {
			return _autoStart;
		}
		
		public static function set autoStart(value:Boolean):void {
			_autoStart = value;
		}
		
		private function traceIt(msg:String):void {
			try {
				if (stage.loaderInfo.parameters.debugAlert == "true") {
					Log.init(stage);
				}
				if (stage.loaderInfo.parameters.debugConsole == "true") {
					Log.consoleLogging = true;
				}
				Log.traceMsg (msg, Log.LOG_TO_CONSOLE);
			} catch (err:Error) {
				trace (msg);
			}
		}
		
		override protected function getView():BaseVideoView
		{
			return new LiveVideoView();
		}
		
		override protected function getNetConnection():OvpConnection
		{				
			return new AkamaiConnection();
		}
		
		override protected function initNetConnection():void
		{
			traceIt("initNetConnection");
            super.initNetConnection()
			netConnection.connect(hostName);
		}
		
		override protected function initNetStream():void
		{
			super.initNetStream();
			AkamaiNetStream(netStream).liveStreamAuthParams = authToken;
			netStream.addEventListener(OvpEvent.SUBSCRIBED, onSubscribe);
			netStream.addEventListener(OvpEvent.UNSUBSCRIBED, onUnsubscribe);
			netStream.addEventListener(OvpEvent.SUBSCRIBE_ATTEMPT, onSubscribeAttempt);
		}
		
		override protected function getNetStream():OvpNetStream
		{
			return new AkamaiNetStream(netConnection);
		}
		
		override protected function getPresenter():BaseControlBarPresenter
		{
			return new LiveControlBarPresenter(view, netStream, netConnection, streamName);
		}
	
		private function onSubscribeAttempt(evt:OvpEvent):void
		{			
			traceIt("onSubscribeAttempt " + evt);
		}

		private function onUnsubscribe(evt:OvpEvent):void
		{
			traceIt("onUnsubscribe " + evt);
		}

		private function onSubscribe(evt:OvpEvent):void
		{
			traceIt("onSubscribe " + evt);
		}
	}
}
