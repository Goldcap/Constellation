package com.zeusprod {
	// Loosely based on http://flash.lillegutt.com/?p=55
	import flash.display.Loader;
	import flash.display.MovieClip;
	import flash.events.Event;
	import flash.events.EventDispatcher;
	import flash.events.IOErrorEvent;
	import flash.events.SecurityErrorEvent;
	import flash.net.URLRequest;
	import flash.system.LoaderContext;
    
    public class LoadExternalSwf extends MovieClip  
	{  
		private var _url:String = "";  
		private var _bytesLoadProgress:int = 0;  
		private var _loadedSwf:MovieClip;  
		
		private var context:LoaderContext;
		private var loader:Loader;
		private var initObj:Object;
		private var maskClip:MovieClip;
		private var maskWidth:Number;
		private var maskHeight:Number;
		private var maskPosX:Number;
		private var maskPosY:Number;
		private var useMask:Boolean;
		private var contentClip:*;
		
		private static const PAUSE_VIDEO_CAPTURE:String = "pauseVideoCapture";
		private static const RESTART_VIDEO_CAPTURE:String = "restartVideoCapture";
		public static const CAMERA_DENIED:String = "cameraDenied";
		public static const CAMERA_ALLOWED:String = "cameraAllowed";
		
		public function LoadExternalSwf (url:String, 
										maskWidth:Number, maskHeight:Number, 
										maskPosX:Number, maskPosY:Number, 
										useMask:Boolean=true, initParamsObj:Object=null)  
		{
			this.maskWidth = maskWidth;
			this.maskHeight = maskHeight;
			this.maskPosX = maskPosX;
			this.maskPosY = maskPosY;
			this.useMask = useMask;
			
		// See http://www.onegiantmedia.com/as3---load-a-remote-image-from-any-domain-with-no-security-sandbox-errors
			context = new LoaderContext();
			context.checkPolicyFile = false;
			//context.securityDomain = SecurityDomain.currentDomain;
			//context.applicationDomain = ApplicationDomain.currentDomain;
			//Log.traceMsg ("Constructed LoadExternalSwf: " + url, Log.NORMAL);
			this.name = "LoadExternalSwf: " + url;	
			loader = new Loader();  
			loader.contentLoaderInfo.addEventListener(Event.COMPLETE, onComplete);  
			loader.contentLoaderInfo.addEventListener(IOErrorEvent.IO_ERROR, onError); 
			loader.contentLoaderInfo.addEventListener(SecurityErrorEvent.SECURITY_ERROR, onSecurityError); 
			loader.load(new URLRequest(url),context); 
			initObj = initParamsObj;
			this.addEventListener(Event.ADDED_TO_STAGE, stageInit);
		}
		
		public function pauseVideo(evt:Event):void {
			//Log.traceMsg ("Got to LoadExternalSwf.pauseVideo", Log.LOG_TO_CONSOLE);
			/*
			try {
				var result:String = contentClip.pauseVideoCapture();
				Log.traceMsg ("contentClip.pauseVideoCapture result is: " + result, Log.ALERT);
			} catch (err:Error) {
				Log.traceMsg ("contentClip.pauseVideoCapture failed: " + err.message, Log.ALERT);
			}
			*/
			dispatchEvent (new Event(PAUSE_VIDEO_CAPTURE));
		}
		
		public function restartVideo(evt:Event):void {
			//Log.traceMsg ("Got to LoadExternalSwf.restartVideo", Log.LOG_TO_CONSOLE);
			/*
			try {
				var result:String = contentClip.restartVideoCapture();
				Log.traceMsg ("contentClip.restartVideoCapture result is: " + result, Log.ALERT);
			} catch (err:Error) {
				Log.traceMsg ("contentClip.restartVideoCapture failed: " + err.message, Log.ALERT);
			}
			*/
			dispatchEvent (new Event(RESTART_VIDEO_CAPTURE));
		}
		
		
		private function stageInit(evt:Event):void {
			this.removeEventListener(Event.ADDED_TO_STAGE, stageInit);
			//if (useMask) {
				// Create a mask to hide off-stage content
				maskClip = new MovieClip();
	    		maskClip.graphics.beginFill(0xFFFFFF, 1.0);	// white, opaque (100% alpha)
	    		maskClip.graphics.moveTo (maskPosX, 			maskPosY);
	    		maskClip.graphics.lineTo (maskPosX+maskWidth, 	maskPosY);
				maskClip.graphics.lineTo (maskPosX+maskWidth, 	maskPosY+maskHeight);
				maskClip.graphics.lineTo (maskPosX, 			maskPosY+maskHeight);
				maskClip.graphics.lineTo (maskPosX, 			maskPosY);
				maskClip.graphics.endFill();
			
				this.addChild(maskClip);
				maskClip.name = "maskClip";
			if (useMask) {
				this.mask = maskClip;  // apply the mask
			} 
		} 
		
		public function onComplete(evt:Event):void  
		{  
			Log.traceMsg ("Successfully loaded external swf: " + this.name, Log.LOG_TO_CONSOLE);
			
            removeEventListeners();
			//this.addChild(loader);
			// Don't cast to another datatype - it would corrupt, say, HostCam's custom features.
			contentClip = evt.target.loader.contentLoaderInfo.content;
			try {
				this.addEventListener(PAUSE_VIDEO_CAPTURE, contentClip.pauseVideoCapture);
			} catch (err:Error) {
				//Log.traceMsg ("contentClip.pauseVideoCapture error okay: " + err.message, Log.LOG_TO_CONSOLE);
			}
			try {
				this.addEventListener(RESTART_VIDEO_CAPTURE, contentClip.restartVideoCapture);
			} catch (err:Error) {
				//Log.traceMsg ("contentClip.restartVideoCapture error okay: " + err.message, Log.LOG_TO_CONSOLE);
			}
			EventDispatcher(contentClip).addEventListener(CAMERA_DENIED, notifyCameraDenied);
			EventDispatcher(contentClip).addEventListener(CAMERA_ALLOWED, notifyCameraAllowed);
			EventDispatcher(contentClip).addEventListener("Host_Cam_Timeout", destroyLiveViewer);
			
			var tracker:MovieClip = new MovieClip();
			tracker.name = "tracker Loaded contentClip";
			this.addChild(tracker);
			this.addChild(contentClip);
			//Log.traceMsg ("Successfully loaded external swf: " + this.name, Log.LOG_TO_CONSOLE);
			
			/*
			if ((contentClip is MovieClip) || (contentClip is Sprite)) {
				if (contentClip is MovieClip) {
					Log.traceMsg ("Loaded asset is a MovieClip", Log.LOG_TO_CONSOLE);
				} else {
					Log.traceMsg ("Loaded asset is a sprite", Log.LOG_TO_CONSOLE);
				}
				_loadedSwf = MovieClip(contentClip);
				if (initObj) {
					Log.alert ("Initializing swf with initObject: " + initObj);
					_loadedSwf.init(initObj);
				} else {
					Log.traceMsg ("No initiatialization object for swf: " + initObj, Log.LOG_TO_CONSOLE);
					
				}
				loader.addChild(_loadedSwf);
			
			} else {
                Log.alert ("Loaded asset is not a swf");
            }
            */
		}
		
		private function notifyCameraAllowed (evt:Event):void {
			dispatchEvent (new Event(CAMERA_ALLOWED));
		}
		
		private function notifyCameraDenied (evt:Event):void {
			dispatchEvent (new Event(CAMERA_DENIED));
		}
		
		private function destroyLiveViewer (evt:Event):void {
		    Log.traceMsg("loadExternalSwf destroyLiveViewer", Log.LOG_TO_CONSOLE);
			dispatchEvent (new Event("Host_Cam_Timeout"));
		}
		
		public function onError(evt:IOErrorEvent):void  
		{  
			removeEventListeners();
			Log.alert ("Error loading swf: " + evt.text);
			dispatchEvent(evt);
		}
		
		public function camViewerStart():void  
		{  
			Log.traceMsg("loadExternalSwf camViewerStart", Log.LOG_TO_CONSOLE);
            contentClip.camViewerStart();
		}
		
		private function onSecurityError(evt:SecurityErrorEvent):void {
			removeEventListeners();
			Log.alert ("Security Error loading swf: " + evt.text);
		}
		
		private function removeEventListeners():void {
			loader.contentLoaderInfo.removeEventListener(Event.COMPLETE, onComplete);  
			loader.contentLoaderInfo.removeEventListener(IOErrorEvent.IO_ERROR, onError); 
			loader.contentLoaderInfo.removeEventListener(SecurityErrorEvent.SECURITY_ERROR, onSecurityError); 
		}
		
		public function destroy():void {
			try {
				Log.traceMsg("Trying contentClip.destroy", Log.LOG_TO_CONSOLE);
				contentClip.destroy();
				Log.traceMsg("contentClip.destroy succeeded", Log.LOG_TO_CONSOLE);
				
			} catch (err:Error) {
				Log.traceMsg("contentClip.destroy failed, which is okay: " + err.message, Log.LOG_TO_CONSOLE);
				
			}
		}
	
	}  
}  
