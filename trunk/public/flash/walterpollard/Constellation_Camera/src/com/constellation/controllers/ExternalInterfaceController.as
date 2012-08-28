package com.constellation.controllers
{
	import com.constellation.events.constellationEvent;
	import com.sierrastarstudio.utils.tracer;
	
	import flash.events.EventDispatcher;
	import flash.events.MouseEvent;
	import flash.external.ExternalInterface;
	import flash.media.Camera;
	import flash.media.Microphone;
	import flash.media.SoundTransform;

	public class ExternalInterfaceController extends EventDispatcher
	{
		private var _classname:String = "com.constellation.controllers.externalInterfaceController";
		
		
		private static var instance:ExternalInterfaceController;
		
		
		public function ExternalInterfaceController(enforcer:SingletonEnforcer)
		{
		}
		public static function getInstance():ExternalInterfaceController
		{
			
			if (ExternalInterfaceController.instance == null)
			{
				ExternalInterfaceController.instance = new ExternalInterfaceController(new SingletonEnforcer());
			}
			return ExternalInterfaceController.instance;
		}
		public function loadExternalInterface():void{
			tracer.log("attempting to load external interface to JS "+ExternalInterface.available,_classname);
			if (ExternalInterface.available) {
				ExternalInterface.addCallback("setVolume", setVolume);
				ExternalInterface.addCallback("showHostCam", showHostCam);
				ExternalInterface.addCallback("hideHostCam", hideHostCam);
				ExternalInterface.addCallback("showLiveViewer", showLiveViewer);
				ExternalInterface.addCallback("hideLiveViewer", hideLiveViewer);
				ExternalInterface.addCallback("camViewerStart", camViewerStart);
				
				ExternalInterface.addCallback("host_update_bandwidthLimit", host_update_bandwidthLimit);
				ExternalInterface.addCallback("host_update_qualityLevel", host_update_qualityLevel);
				ExternalInterface.addCallback("host_update_keyFrameInterval", host_update_keyFrameInterval);
				ExternalInterface.addCallback("host_update_captureFPS", host_update_captureFPS);
				ExternalInterface.addCallback("host_update_bufferMin", host_update_bufferMin);
				ExternalInterface.addCallback("host_update_bufferMax", host_update_bufferMax);
				ExternalInterface.addCallback("host_update_micGain", host_update_micGain);
				ExternalInterface.addCallback("host_update_motionTimeout", host_update_motionTimeout);
				ExternalInterface.addCallback("host_update_favorArea", host_update_favorArea);
				ExternalInterface.addCallback("host_update_camLoopback", host_update_camLoopback);
				ExternalInterface.addCallback("host_update_micLoopback", host_update_micLoopback);
				ExternalInterface.addCallback("host_update_echoSuppression", host_update_echoSuppression);
				ExternalInterface.addCallback("host_update_silenceLevel", host_update_silenceLevel);
				ExternalInterface.addCallback("host_update_micSilenceTimeout", host_update_micSilenceTimeout);
				ExternalInterface.addCallback("host_update_enableVAD", host_update_enableVAD);
				
				ExternalInterface.addCallback("host_update_captureWidth", host_update_captureWidth);
				ExternalInterface.addCallback("host_update_captureHeight", host_update_captureHeight);
				
				
				
			} else {
				tracer.log("External Interface not available",_classname);
			}
			 
		}
		
		private function host_update_captureHeight(newHeight:Number):void
		{
			var cam:Camera = Camera.getCamera()
				cam.setMode(cam.width,newHeight,cam.fps)
		}
		
		private function host_update_captureWidth(newWidth:Number):void
		{
			var cam:Camera = Camera.getCamera()
				cam.setMode(newWidth,cam.height,cam.fps)
		}
		
		private function host_update_enableVAD(newVAD:String):void
		{
			var mic:Microphone = Microphone.getMicrophone()
				mic.enableVAD = newVAD=="true"?true:false;
		}
		
		private function host_update_micSilenceTimeout(newMicTimeout:Number):void
		{
			var mic:Microphone = Microphone.getMicrophone()
				mic.setSilenceLevel(mic.silenceLevel,newMicTimeout);
		}
		
		private function host_update_silenceLevel(newSilenceLevel:Number):void
		{
			var mic:Microphone = Microphone.getMicrophone()
				mic.setSilenceLevel(newSilenceLevel,mic.silenceTimeout);
		}
		
		private function host_update_echoSuppression(newEchoValue:String):void
		{
			var mic:Microphone = Microphone.getMicrophone();
			var newEcho:Boolean = newEchoValue=="true"?true:false
				mic.setUseEchoSuppression(newEcho);
			
		}
		
		private function host_update_micLoopback(micLoopBackValue:String):void
		{
			var mic:Microphone = Microphone.getMicrophone();
			var newMicLoopBack:Boolean = micLoopBackValue=="true"?true:false
				mic.setLoopBack(newMicLoopBack);
			
		}
		
		private function host_update_camLoopback(camLoopbackValue:String):void
		{
			var cam:Camera = Camera.getCamera();
			var newLoopBack:Boolean = camLoopbackValue=="true"?true:false;
				cam.setLoopback(newLoopBack);
		}
		
		private function host_update_favorArea(favorAreaValue:String):void
		{
			var cam:Camera = Camera.getCamera()
			var newFavor:Boolean = favorAreaValue=="true"?true:false;
			cam.setMode(cam.width,cam.height,cam.fps,newFavor)
		}
		
		private function host_update_motionTimeout(motionLevel:Number):void
		{
			var cam:Camera = Camera.getCamera();
				cam.setMotionLevel(motionLevel);
		}
		
		private function host_update_micGain(newGainLevel:Number):void
		{
			var mic:Microphone = Microphone.getMicrophone();
				mic.gain = newGainLevel;
		}
		
		private function host_update_bufferMax(newBufferMax:Number):void
		{
			dispatchEvent(new constellationEvent(constellationEvent.HOST_CAMERA_UPDATE_BUFFER_MAX,newBufferMax.toString()));
		}
		
		private function host_update_bufferMin(newBufferMin:Number):void
		{
			dispatchEvent(new constellationEvent(constellationEvent.HOST_CAMERA_UPDATE_BUFFER_MAX,newBufferMin.toString()));
			
		}
		
		private function host_update_captureFPS(newFPS:Number):void
		{
			var cam:Camera = Camera.getCamera()
			cam.setMode(cam.width,cam.height,newFPS)
				
		}
		
		private function host_update_keyFrameInterval(newKeyFrameInterval:Number):void
		{
			var cam:Camera = Camera.getCamera();
				cam.setKeyFrameInterval(newKeyFrameInterval);
		}
		
		private function host_update_qualityLevel(newQualityLevel:Number):void
		{
			var cam:Camera = Camera.getCamera();
				cam.setQuality(cam.bandwidth, newQualityLevel);
		}
		
		private function host_update_bandwidthLimit(newBandWidith:Number):void
		{
			var cam:Camera = Camera.getCamera();
			cam.setQuality(newBandWidith, cam.quality);
		}
		public function showQaWindow():void{
			ExternalInterface.call("showQaWindow");
		}
		public function restartViewerJS():void{
			ExternalInterface.call("videoplayer.camViewerReStart()");
		}
		private function camViewerStart():void
		{
			tracer.log("JS - camViewerStart",_classname);
			dispatchEvent(new constellationEvent(constellationEvent.SHOW_CAMERA_VIEWER));
		}
		
		private function hideLiveViewer(evt:MouseEvent=null,hideWindow:Boolean=true):void
		{
			if (hideWindow) {
				try {
					ExternalInterface.call("hideQaWindow");
				} catch (err:Error) {
					tracer.log("Error in invoke QA Window ",_classname);
				}
				dispatchEvent(new constellationEvent(constellationEvent.HIDE_CAMERA_VIEWER));	
			}else{
				dispatchEvent(new constellationEvent(constellationEvent.SHOW_CAMERA_VIEWER));
			}
			tracer.log("JS - hideLiveViewer ",_classname);
			
		}
		
		private function showLiveViewer():void
		{
			tracer.log("JS - showLiveViewer ",_classname);
			dispatchEvent(new constellationEvent(constellationEvent.SHOW_CAMERA_VIEWER));
			
		}
		
		private function hideHostCam():void
		{
			tracer.log("JS - hideHostCam ",_classname);
			dispatchEvent(new constellationEvent(constellationEvent.HIDE_HOST_CAMERA));
			
		}
		
		private function showHostCam():void
		{
			tracer.log("JS -showHostCam ",_classname);
			dispatchEvent(new constellationEvent(constellationEvent.SHOW_HOST_CAMERA));
		}
		
		private function setVolume(newVolume:Number):void
		{
			//ExternalInterface.call("alert(\"running setVolume\")");
			tracer.log("Setting Volume " + newVolume,_classname);
			
			
			var volumeTransform:SoundTransform = new SoundTransform();
				volumeTransform.volume = newVolume/10;
			dispatchEvent(new constellationEvent(constellationEvent.SET_VOLUME,volumeTransform));
			
			
		}		
		
		
	}
}
class SingletonEnforcer{}