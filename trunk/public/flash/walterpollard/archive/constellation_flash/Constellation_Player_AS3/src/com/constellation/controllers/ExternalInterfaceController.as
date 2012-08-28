package com.constellation.controllers
{
	import com.constellation.events.constellationEvent;
	import com.sierrastarstudio.utils.tracer;
	
	import flash.events.EventDispatcher;
	import flash.external.ExternalInterface;
	import flash.media.SoundTransform;
	import flash.events.MouseEvent;

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
			//	ExternalInterface.addCallback("showHostCam", showHostCam);
			//	ExternalInterface.addCallback("hideHostCam", hideHostCam);
			//	ExternalInterface.addCallback("showLiveViewer", showLiveViewer);
			//	ExternalInterface.addCallback("hideLiveViewer", hideLiveViewer);
			//	ExternalInterface.addCallback("camViewerStart", camViewerStart);
				
			} else {
				tracer.log("External Interface not available",_classname);
			}
			 
		}
		public function showQaWindow():void{
			ExternalInterface.call("showQaWindow");
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
			
			var newVol:Number = newVolume/10;
			tracer.log("JS - Setting Volume " + newVolume +" newVol "+newVol,_classname);
			dispatchEvent(new constellationEvent(constellationEvent.SET_VOLUME,newVol));
			
			
		}		
		
		
	}
}
class SingletonEnforcer{}