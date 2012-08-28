package com.constellation.net
{	
	import com.constellation.controllers.ExternalInterfaceController;
	import com.constellation.events.constellationEvent;
	import com.sierrastarstudio.utils.tracer;
	
	import flash.events.Event;
	import flash.events.EventDispatcher;
	import flash.events.NetStatusEvent;
	import flash.events.StatusEvent;
	import flash.external.ExternalInterface;
	import flash.media.Camera;
	import flash.media.Microphone;
	import flash.media.Video;
	import flash.net.NetConnection;
	import flash.net.NetStream;
	
	public class FMSStream extends EventDispatcher
	{
		private var _ns:NetStream;
		private var nc:NetConnection;
		private var vid:Video;
		private var bufferTimeMax:Number;
		private var bufferTimeMin:Number;
		private var _cam:Camera;
		private var mic:Microphone;
		public static const CAMERA_DENIED:String = "cameraDenied";
		public static const CAMERA_ALLOWED:String = "cameraAllowed";
				
		public function debug(code:String):void {
			var statusEvent:StatusEvent = new StatusEvent("DEBUG");
			statusEvent.code = code;
			dispatchEvent(statusEvent);
			tracer.log("DEBUG "+code,"FMSSTREAM")
				
		}
		
		public function FMSStream( nc:NetConnection, vid:Video  )
		{
			debug("FMSStream");
			this.nc = nc;
			this.vid = vid;
			ExternalInterfaceController.getInstance().addEventListener(constellationEvent.HOST_CAMERA_UPDATE_BUFFER_MAX,onUpdateBufferMax);
			ExternalInterfaceController.getInstance().addEventListener(constellationEvent.HOST_CAMERA_UPDATE_BUFFER_MIN,onUpdateBufferMin);
		}
		
		protected function onUpdateBufferMin(event:constellationEvent):void
		{
			
				this.bufferTimeMin = Number(event.data);
			
		}
		
		protected function onUpdateBufferMax(event:constellationEvent):void
		{
			this.bufferTimeMax = Number(event.data);
					
		}
		
		public function publishStream( streamName:String, streamID:String,
										bandwidthLimit:Number,
										qualityLevel:Number, 
										keyFrameInterval:Number ,
										captureWidth:Number, 
										captureHeight:Number, 
										captureFps:Number, 
										favorArea:Boolean,
										camLoopback:Boolean,
										bufferMin:Number,
										bufferMax:Number,
										useEchoSuppression:Boolean,
										micLoopback:Boolean,
										micRate:Number,
										micGain:Number,
										motionTimeout:Number,
										micSilenceLevel:Number,
										micSilenceTimeout:Number,
										micEnableVAD:Boolean
										):void
		{
			debug("in publishStream");
			// FIXME - may not always get camera reliably if there are pseudo-camera drivers installed
			_cam = Camera.getCamera();
		
			debug("called Camera.getCamera");
			if (cam == null) {
				debug("No camera was found");
			} else {
				cam.addEventListener(StatusEvent.STATUS, camDialogStatusHandler);
				debug("Camera is successful" + cam);
			}
			mic = Microphone.getMicrophone();
			debug("called Microphone.getMicrophone()");
			if (mic) {
				debug("Mic is successful " + mic);
				if(micEnableVAD){
					mic.enableVAD = true
				}
				mic.setSilenceLevel(micSilenceLevel,micSilenceTimeout);
				mic.setUseEchoSuppression(useEchoSuppression);
				mic.setLoopBack(micLoopback);
				
				mic.rate = isNaN(micRate) ? 8 : micRate;
				
				if (!isNaN(micGain)) {
					mic.gain = micGain;
				}
			} else {
				debug("No microphone was found");
			}
			
			bufferTimeMin = isNaN(bufferMin) ?  1 : bufferMin;
			bufferTimeMax = isNaN(bufferMax) ? 3 : bufferMax;
			

			if (cam) {
				if(isNaN(motionTimeout)){
					
					motionTimeout=2000;
				}
					
				cam.setMotionLevel(motionTimeout)
					
				if (isNaN(bandwidthLimit)) {
					bandwidthLimit = 0;
				}
				if (isNaN(qualityLevel)) {
					qualityLevel = 0;
				}
				cam.setQuality(bandwidthLimit, qualityLevel);
				
				if (isNaN(captureWidth)) {
					captureWidth = 160;
				}
				if (isNaN(captureHeight)) {
					captureHeight = 120;
				}
				if (isNaN(captureFps)) {
					captureFps = 24;
				}
				cam.setMode(captureWidth, captureHeight, captureFps, favorArea);
			
				cam.setLoopback(camLoopback);
			
				if (!isNaN(keyFrameInterval)) {
					cam.setKeyFrameInterval(keyFrameInterval);
				}
		
				ns.attachCamera( cam );
				vid.attachCamera( cam );
				
				debug ("bandwidth: " 		+ cam.bandwidth);
				debug ("quality: " 			+ cam.quality);
				debug ("keyframeInterval: "	+ cam.keyFrameInterval);
				debug ("currentFps: " 		+ cam.currentFPS);
				debug ("height: " 			+ cam.height);
				debug ("width: " 			+ cam.width);
				debug ("fps: " 				+ cam.fps);
				debug ("favorArea: " 		+ favorArea);
				debug ("camLoopback: " 		+ cam.loopback);
				debug ("bufferMin: " 		+ bufferTimeMin);
				debug ("bufferMax: " 		+ bufferTimeMax);
			} else {
				debug("No cam");
				
			}
			
			if (mic) {
				debug ("useEchoSuppression: "+ mic.useEchoSuppression);
				debug ("micLoopback: " 		+ micLoopback);
				debug ("micRate: " 			+ mic.rate);
				debug ("micGain: " 			+ mic.gain);
											
				ns.attachAudio( mic );
			} else {
				debug("No mic " + mic);
			}
			
			ns.publish( streamName + '@' + streamID, 'live');
			debug("published ns  " + (streamName + '@' + streamID));
				
		}
		
		private function camDialogStatusHandler(evt:StatusEvent):void 
		{ 
			var statusStr:String;
			
			if (ExternalInterface.available) {
			    switch (evt.code) 
			    { 
			        case "Camera.Muted": 
			        	statusStr = "deny";
			        	dispatchEvent (new Event (CAMERA_DENIED));
			            break; 
			        case "Camera.Unmuted": 
			            statusStr = "allow";
			            dispatchEvent (new Event (CAMERA_ALLOWED));
			            break; 
			    }
			    // Tell Javascript about it
			    ExternalInterface.call("notifyFlashPermissionDialogComplete", statusStr);
			           
			} else {
				debug("Can't notify javascript about camera state");
			}
		}
		
		public function playStream( streamName:String, streamID:String ):void
		{
			vid.attachNetStream( ns );
			ns.play( streamName + '@' + streamID );
		}
		
		public function pauseStream():void 
		{
			debug("Pausing netStream by setting attachCamera to null");
			//ns.pause();
			//ns.close();
			//ns.publish(null);
			ns.attachCamera(null);
		}
		
		
		public function restartStream():void 
		{
			debug("Restarting netStream by reattaching camera");
			//ns.resume();
			ns.attachCamera( cam );
		}
		
		private function get ns():NetStream
		{
			if (!_ns) 
			{
				_ns = new NetStream( nc );
				_ns.addEventListener( NetStatusEvent.NET_STATUS, handleNetStatus );
			}
			
			return _ns;
		}
		
		private function handleNetStatus( event:NetStatusEvent ):void
		{
			debug("FMS handle net status "+event.info.code);
			switch (event.info.code)
			{
				case "NetStream.Buffer.Full":
					ns.bufferTime = bufferTimeMax;
					break;
				case "NetStream.Buffer.Empty":
					ns.bufferTime = bufferTimeMin;
					break;
				case "NetStream.Buffer.Flush":
					break;
			}
		}
		
		public function destroy():void {
			// clean up
			try {
				debug("Destroying FMSStream");
				vid.attachCamera(null);
				vid.attachNetStream(null);
				vid.clear();
				ns.pause();
				// "Unpublish" the stream to release it.
				//ns.close();
				ns.publish(null);
				//ns.publish("");		// ns.publish("false");
				//ns.attachAudio(null);
				//ns.attachCamera(null);
				vid= null;
				_ns = null;
				_cam = null;
				mic = null;
				debug("Destruction of FMSStream succeeded");
			
			} catch (err:Error) {
				debug("Destruction of FMSStream failed");
			
			}
		}

		public function get cam():Camera
		{
			return _cam;
		}
		public function get microphone():Microphone
		{
			return mic;
		}

	}
}