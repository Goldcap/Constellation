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
	import flash.events.NetStatusEvent;
    import flash.utils.Timer;
    import flash.events.TimerEvent;
	
	import org.openvideoplayer.events.OvpEvent;
	import org.openvideoplayer.net.OvpConnection;
	import org.openvideoplayer.net.OvpNetStream;
	import org.openvideoplayer.samples.generic.BaseSample;
	import org.openvideoplayer.samples.presenter.BaseControlBarPresenter;
	import org.openvideoplayer.samples.presenter.LiveControlBarPresenter;
	import org.openvideoplayer.samples.view.BaseVideoView;
	import org.openvideoplayer.samples.view.LiveVideoView;
	import org.openvideoplayer.utils.MediaResources;
    import org.openvideoplayer.components.ui.shared.event.ControlEvent;
	
	public class LiveStreaming extends BaseSample
	{
		
		private var hostName:String = MediaResources.RTMP_STREAMING_LIVE.hostName;
		private var aStreamName:String = MediaResources.RTMP_STREAMING_LIVE.streamName;
		private var streamName:String = MediaResources.RTMP_STREAMING_LIVE.streamName;
		private var authToken:String = MediaResources.RTMP_STREAMING_LIVE.authToken;
		private static var _autoStart:Boolean = true;
		
        private var fpsTimer:Timer = new Timer(10);
        private var _FPSCount:Number = 0;   
        private var _FPSLimit:Number = 5000;   
        
		public function LiveStreaming()
		{
			this.addEventListener(Event.ADDED_TO_STAGE, stageInit);
			this.addEventListener(Event.REMOVED_FROM_STAGE, destroyMe);
			// The following doesn't work when loaded as a sub-movie swf
			//var flashParams:Object = root.loaderInfo.parameters;
			// Wait until it is added to the stage to get the params from the stage.loaderInfo.parameters object.
			super(describeType(this).@name.toString().split("::")[1], NaN, NaN);
		}
		
		private function stageInit (evt:Event=null):void {
			// This is reached before the super class invokes its activate() function
			//Log.traceMsg ("LiveStreaming stageInit", Log.LOG_TO_CONSOLE);
			
            this.removeEventListener(Event.ADDED_TO_STAGE, stageInit);
            
			var flashParams:Object = stage.loaderInfo.parameters;
			
			hostName = "cp" + ((flashParams.hostName == undefined) ? "113557" : flashParams.hostName) + ".live.edgefcs.net/live";
            aStreamName = (flashParams.streamName == undefined) ? "constellation" : flashParams.streamName;
            var streamID:String = (flashParams.streamID == undefined) ? "45907" : flashParams.streamID;
            streamName = aStreamName + "@" + streamID;	
            var auth:String = (flashParams.auth == undefined ) ? "noauth" :  flashParams.auth;
            var aifp:String = (flashParams.aifp == undefined) ? "v0006": flashParams.aifp;
            authToken = "auth=" + auth + "&amp;aifp=" + aifp;
            autoStart = (flashParams.autostartLiveViewer == undefined) ? true : flashParams.autostartLiveViewer;
            
			traceIt ("hostname '"		+ hostName 		+ "'");
			traceIt ("streamName '" 	+ streamName 	+ "'");
			traceIt ("authToken '" 	+ authToken 	+ "'");
			traceIt ("autoStart '" 	+ autoStart 	+ "'");
			
			
			var vidWidth:Number = 275;
			var vidHeight:Number = 205;
			
			//Log.traceMsg ("LiveStreaming.stageInit default width/height " + vidWidth + " " + vidHeight, Log.LOG_TO_CONSOLE);
			
			// Use the viewerWidth and viewerHeight, as specified, if applicable.
			if (!isNaN(Number(flashParams.viewerWidth))) {
				
				vidWidth = Number(flashParams.viewerWidth);
				//Log.traceMsg ("LiveStreaming.stageInit Good vidWidth " + vidWidth, Log.LOG_TO_CONSOLE);
			
			} else {
				//Log.traceMsg ("LiveStreaming.stageInit Bad flashParams.viewerWidth " + flashParams.viewerWidth + " Number: "+ Number(flashParams.viewerWidth), Log.ALERT);
			}
			
			if (!isNaN(Number(flashParams.viewerHeight))) {
				vidHeight = Number(flashParams.viewerHeight);
				//Log.traceMsg ("LiveStreaming.stageInit Good vidHeight " + vidHeight, Log.LOG_TO_CONSOLE);
			
			}else {
				//Log.traceMsg ("LiveStreaming.stageInit Bad flashParams.viewerHeight " + flashParams.viewerHeight + " Number: " + Number(flashParams.viewerHeight), Log.ALERT);
			}
			//Log.traceMsg ("LiveStreaming.stageInit vidWidth and vidHeight " + vidWidth + " " + vidHeight, Log.LOG_TO_CONSOLE);
			setHeightAndWidth(vidWidth, vidHeight);
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
		
		private function setFPSTimer() {
            fpsTimer.addEventListener(TimerEvent.TIMER, FPSTick);
            fpsTimer.start();
        }
        
		private function FPSTick(event:TimerEvent) {
            
          //Log.traceMsg ("BaseControlBarPresenter onTick", Log.LOG_TO_CONSOLE);
          if (netStream != null) {
             //Log.traceMsg("Current FPS:"+netStream.currentFPS,Log.LOG_TO_CONSOLE);
             if (netStream.currentFPS == 0) {
              _FPSCount++;
             } else {
              _FPSCount=0;
             }
             //Log.traceMsg("Current FPSCount:"+_FPSCount,Log.LOG_TO_CONSOLE);
             if (_FPSCount > _FPSLimit) {
              Log.traceMsg ("LiveStreaming Host Connect Timeout", Log.LOG_TO_CONSOLE);
              dispatchEvent( new Event("Host_Cam_Timeout") );
              fpsTimer.removeEventListener(TimerEvent.TIMER, FPSTick);
              fpsTimer.stop();
              fpsTimer = null;
              //destroyMe();
             }
          }
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
		
		override protected function getView(vidWidth:Number, vidHeight:Number):BaseVideoView
		{
			//Log.traceMsg ("LiveStreaming getView size is " + vidWidth + " x " + vidHeight, Log.LOG_TO_CONSOLE);
			return new LiveVideoView(vidWidth, vidHeight);
		}
		
		override protected function getNetConnection():OvpConnection
		{				
			return new AkamaiConnection();
		}
		
		override protected function initNetConnection():void
		{
			Log.traceMsg ("LiveStreaming initNetConnection", Log.LOG_TO_CONSOLE);
			if (((netConnection != null) && (! netConnection.connected)) || (netConnection == null)) {
              Log.traceMsg ("LiveStreaming initNetConnection is null", Log.LOG_TO_CONSOLE);
              super.initNetConnection()
  			  netConnection.connect(hostName);
			} else {
			 //If we have a connection, but are trying to connect
			 //Let's assume we're actually trying to stream
			 //So let's start that process manually
              Log.traceMsg ("LiveStreaming initNetConnection is not null", Log.LOG_TO_CONSOLE);
              initNetStream();
            }
		}
		
		override protected function initNetStream():void
		{
			Log.traceMsg ("LiveStreaming initNetStream", Log.LOG_TO_CONSOLE);
            //if (netStream == null) {
                //Log.traceMsg ("LiveStreaming initNetStream is null", Log.LOG_TO_CONSOLE);
                super.initNetStream();
    			AkamaiNetStream(netStream).liveStreamAuthParams = authToken;
    			netStream.addEventListener(OvpEvent.SUBSCRIBED, onSubscribe);
    			netStream.addEventListener(OvpEvent.UNSUBSCRIBED, onUnsubscribe);
    			netStream.addEventListener(OvpEvent.SUBSCRIBE_ATTEMPT, onSubscribeAttempt);
    		//}
		}
		
		override protected function getNetStream():OvpNetStream
		{
			return new AkamaiNetStream(netConnection);
		}
		
		override protected function getPresenter():BaseControlBarPresenter
		{
			Log.traceMsg ("LiveStreaming getPresenter", Log.LOG_TO_CONSOLE);
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
			setFPSTimer();
		}
		
		private function hostTimeout(evt:Event):void
		{
			Log.traceMsg ("LiveStreaming hostTimeout", Log.LOG_TO_CONSOLE);
		}
		
		//If we need to restart the viewer from Javascript
		//This will do that...
        public function camViewerStart():void {
			Log.traceMsg ("camViewerStart Triggered", Log.LOG_TO_CONSOLE);
			destroyMe();
            stageInit();
            activate();
            
			this.addEventListener(Event.REMOVED_FROM_STAGE, destroyMe);
		}

		private function destroyMe (evt:Event=null):void {
			try {
              Log.traceMsg ("LiveStreaming destroyMe", Log.LOG_TO_CONSOLE);
              this.addEventListener(Event.ADDED_TO_STAGE, stageInit);
              
              // Put kill stuff here
              this.removeEventListener(Event.REMOVED_FROM_STAGE, destroyMe);
              //netStream.volume = 0;
              if (netStream != null) {
                Log.traceMsg ("LiveStreaming Close Net Stream", Log.LOG_TO_CONSOLE);
                
                netStream.close();
                netStream.removeEventListener(OvpEvent.SUBSCRIBED, onSubscribe);
                netStream.removeEventListener(OvpEvent.UNSUBSCRIBED, onUnsubscribe);
                netStream.removeEventListener(OvpEvent.SUBSCRIBE_ATTEMPT, onSubscribeAttempt);
                netStream.removeEventListener(OvpEvent.NETSTREAM_METADATA, onMetaData);
                netStream = null;
              }
              if (netConnection != null) {
                Log.traceMsg ("LiveStreaming Close Net Connection", Log.LOG_TO_CONSOLE);
                
                netConnection.close();
                netConnection.removeEventListener(NetStatusEvent.NET_STATUS, onNetStatus);
                netConnection = null;
              }
              
              view = null;
              presenter.destroy();
		    } catch (e:Error) {
            
            }
		    //fpsTimer.removeEventListener(TimerEvent.TIMER, FPSTick);
            //fpsTimer.stop();
            //fpsTimer = null;
            
			//dispatchEvent(new ControlEvent(ControlEvent.PAUSE));
		}
		
	}
}
