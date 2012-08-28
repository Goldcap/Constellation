//
// Copyright (c) 2009-2011, the Open Video Player authors. All rights reserved.
//
// Redistribution and use in source and binary forms, with or without 
// modification, are permitted provided that the following conditions are 
// met:
//
//    * Redistributions of source code must retain the above copyright 
//notice, this list of conditions and the following disclaimer.
//    * Redistributions in binary form must reproduce the above 
//copyright notice, this list of conditions and the following 
//disclaimer in the documentation and/or other materials provided 
//with the distribution.
//    * Neither the name of the openvideoplayer.org nor the names of its 
//contributors may be used to endorse or promote products derived 
//from this software without specific prior written permission.
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
package
{
    import com.zeusprod.LoadExternalSwf;
    import com.zeusprod.Log;
    import com.constellation.Watermark;
    import com.constellation.Stats;
    
    import fl.controls.Button;
    
    import flash.display.*;
    import flash.events.*;
    import flash.external.ExternalInterface;
    import flash.geom.Rectangle;
    import flash.media.Camera;
    import flash.media.Video;
    import flash.text.TextField;
    import flash.text.TextFormat;
    import flash.system.Security;
    import flash.system.SecurityPanel;
  
    
    /**
     * This example illustrates the invocation of the Akamai Multi Player in a Flash CS4 project. Note that control
     * of the fullscreen behavior is externalized, since in many instances the player component itself may not be the only display object
     * on stage and therefore other layout and positioning methods may have to be called when moving to fullscreen.
     *
     * <p/>
     * Due to Dynamic Streaming Support, this project must be compiled for Flash Player 10 or higher.
     *
     * @see AkamaiMultiPlayer
     */
    public class AkamaiMultiPlayerExample extends MovieClip
    {
        private static const PUBLISHER_PLAYLIST_URL:String = "http://publisherqa.edgesuite.net/1/publications/00/50/feed_503.xml";
        private static const MBR_SMIL:String = "http://mediapm.edgesuite.net/ovp/content/demo/smil/elephants_dream.smil";
		//private static const MBR_SMIL:String = "./data/elephants.smil";
		//private static const MBR_SMIL:String = "./data/lottery.smil";
		//private static const MBR_SMIL:String = "./data/lottery_encrypted.smil";
		private static const SMIL_TEST_SUB_CLIPS:String	= "http://mediapm.edgesuite.net/ovp/content/demo/smil/elephants-dream-sub-clips.smil";
		private static const DEFAULT_TRAILER_WIDTH:Number = 420;
		private static const DEFAULT_TRAILER_HEIGHT:Number = 230;
		private static const PAUSE_VIDEO:String = "pauseVideo";
		private static const RESTART_VIDEO:String = "restartVideo";
    		
        private var player:AkamaiMultiPlayer;
        private var _video:Video;
        private var notify_mc:ChatBoxSym;
        private var tfm:TextFormat = new TextFormat();
        private var soundState:Boolean = true;
        private var lastVolLevel:String = null;		// Yes, it is a string, because js sends strings to Flash.
        private var hostCamSwf:LoadExternalSwf;
        private var liveViewerSwf:LoadExternalSwf;
        private var hostCamDialogBox:MovieClip;
        private var liveViewerDialogBox:MovieClip;
        private var _trailerMode:Boolean = false;
        private var trailerWidth:Number;
        private var trailerHeight:Number;
        private var boxAlpha:Number;
        private var borderSize:Number;
        private var regOffsetX:Number;
        private var regOffsetY:Number;
		
		private var accessDenied:Boolean = false;
        	
        /**
         * Constructor
         */
        public function AkamaiMultiPlayerExample():void
        {
        	this.addEventListener(Event.ADDED_TO_STAGE, stageInit);
        }
        
       public function displayMessage(msgType:String, msgTitle:String, msgText:String):void {
       		//Log.traceMsg("Flash displayMessage: " + msgType + " : " + msgTitle + " : " + msgText, Log.ALERT);
       		notify_mc.visible = true;
			var tfc:TextField = TextField(notify_mc.getChildByName("tf"))
			tfc.text = msgText;
			tfc.setTextFormat(tfm);
       }
	   
	   public function hideNotificationBox(evt:MouseEvent):void {
       		notify_mc.visible = false;
       }
	   
	   private function createTrailerBackground():void {
	   		var trailerBackground:TrailerBG = new TrailerBG();
	   		var closeTextButton:CloseText = new CloseText();
			trailerBackground.alpha = .50;
			trailerBackground.name = "trailerBackground";
	   		this.addChild(trailerBackground);
	   		trailerBackground.addChild(closeTextButton);
	   		closeTextButton.name = "closeTextButton";
	   		closeTextButton.y = 10;
	   		closeTextButton.x = trailerBackground.width - closeTextButton.width - 20;
	   		closeTextButton.addEventListener(MouseEvent.CLICK, tellJStoHideTrailer);
		}
		
		private function tellJStoHideTrailer(evt:MouseEvent):void {
			try {
				ExternalInterface.call("HideTrailer");
			} catch (err:Error) {
				Log.traceMsg ("Error in invoke tellJStoHideTrailer", Log.ALERT);
			}
		}
		
	   private function createNotificationDialogBox ():void {
	   		if (!notify_mc) {
				//var bg:Bitmap;
				notify_mc = new ChatBoxSym();
				notify_mc.x = 5;
				var bufferY:Number = 40;
				notify_mc.y = this.stage.stageHeight - notify_mc.height - bufferY;
				//bg = new Bitmap(new BitmapData(100, 60, false, 0xff0000));
				//notify_mc.addChild(bg);
				var closeBox:CloseXSym = new CloseXSym();
				closeBox.x = 157;
				closeBox.y = 5;
				closeBox.name = "closeBox";
				notify_mc.addChild(closeBox);
				closeBox.addEventListener(MouseEvent.CLICK, hideNotificationBox);
				
				var tf:TextField = new TextField();
				tf.x = 5;
				tf.y = 2;
				tf.width = 152;
				tf.multiline = true;
				tf.wordWrap = true;
				tf.height = 60;
				tf.name = "tf";
				notify_mc.addChild(tf);
				
				// FIXME - make these all configurable?
				tfm.color = 0x7BA5D2;	// blue
				tfm.font = "Arial";
				tfm.size = 10;
				tfm.bold = true;
			
				Log.traceMsg("Created notification dialog " + notify_mc, Log.NORMAL);
				notify_mc.name = "notify_mc";
				this.addChild(notify_mc);
				hideNotificationBox(null);
	   		}
		}
       
       public function resizeNotification():void {
			Log.traceMsg("resizeNotification from js received in Flash", Log.ALERT);
       }
	   
	   // This function isn't really used and hasn't been tested. Andy is controlling audio from javascript
	   public function toggleSound():void {
			soundState = !soundState;
			Log.traceMsg("toggleSound " + soundState, Log.ALERT);
			if (soundState && lastVolLevel != null) {
				setVolume(lastVolLevel);
			} else {
				setVolume("0");
			}
       }
	   
	   public function goFullscreen():void {
		   Log.alert("Click 'OK' to enter fullscreen mode", {callback:okayToStartFullscreen});
	   }
	   
	   /*
	   private var cam:Camera;
	   
	   private function camDialogStatusHandler(evt:StatusEvent):void 
		{ 
			var statusStr:String;
			cam.removeEventListener(StatusEvent.STATUS, camDialogStatusHandler);
			cam = Camera.getCamera(null);
			cam = null;
			switch (evt.code) 
		    { 
		        case "Camera.Muted":
		        	accessDenied = true; 
		        	break; 
		        case "Camera.Unmuted":
		        	accessDenied = false;
		        	showHostCam();
		            break;
		         default:
		         	Log.traceMsg("Unknown camera status: " + evt.code, Log.ALERT);
		    }
		}
		*/
	   public function showHostCam():void {
	    var cam = Camera.getCamera();
		
		if (cam == null) {
			Log.traceMsg("No camera was found", Log.LOG_TO_CONSOLE);
			try {
  				ExternalInterface.call("showQaWindow");
  			} catch (err:Error) {
  				Log.traceMsg ("Error in invoke QA Window", Log.LOG_TO_CONSOLE);
  			}
  			return;
		}
		
   		Log.consoleLogging = (loaderInfo.parameters.debugConsole == "true");
   		Log.traceMsg("Got to showHostCam in AS", Log.LOG_TO_CONSOLE);
   		if (accessDenied) {	
   			Log.alert ("To broadcast your video, restart the application and allow access to your camera.");
   			return;
   			/*
   			Log.traceMsg("Access was denied so show settings panel again", Log.LOG_TO_CONSOLE);
   			cam = Camera.getCamera();
		
			if (cam == null) {
				Log.alert("No camera was found");
			} else {
				cam.addEventListener(StatusEvent.STATUS, camDialogStatusHandler);
				Log.traceMsg("Camera was checked", Log.LOG_TO_CONSOLE);
			}
			if (cam.muted) {
				Log.traceMsg("Try showing SecurityPanel", Log.LOG_TO_CONSOLE);
				Security.showSettings(SecurityPanel.PRIVACY);
				return;
			} else {
				Log.traceMsg("Cam was unmuted, so keep going", Log.LOG_TO_CONSOLE);
				
				accessDenied = false;
			}
   			*/
	   		} else {
	   			Log.traceMsg("Access decision is still pending: " + accessDenied, Log.LOG_TO_CONSOLE);
	   		}
	   		
	   		var swfWidth:Number = Number(loaderInfo.parameters.hostViewerWidth);
	   		var swfHeight:Number = Number(loaderInfo.parameters.hostViewerHeight);
	   		// Must be at least 215 pixels high to show camera dialog 
	   		// (not really, since it is loaded as submovie, and dialog appears in middle of main movie?)
	   		swfWidth  = isNaN(swfWidth)  ? 275 : swfWidth,
   			swfHeight = isNaN(swfHeight) ? 205 : swfHeight
   			
            //var posX:Number = (loaderInfo.parameters.hostPosX == undefined) ? this.stage.stageWidth - swfWidth - borderSize*2 : Number(loaderInfo.parameters.hostPosX);
	   		var posX:Number = (loaderInfo.parameters.hostPosX == undefined) ? 19 : Number(loaderInfo.parameters.hostPosX);
	   		var posY:Number = (loaderInfo.parameters.hostPosY == undefined) ? 19 : Number(loaderInfo.parameters.hostPosY);
	   												 
	   		if (hostCamSwf == null) {
   				//Log.traceMsg("HostCamURL looks good: " + hostCamUrl, Log.LOG_TO_CONSOLE);
   				//Log.traceMsg("hostCamSwf width and height: " + swfWidth + ":" + swfHeight, Log.LOG_TO_CONSOLE);
   				
   				// Compensate for other offsets, so that the x,y specified by user is position of upper-left corner
   				posX += 3*borderSize + swfWidth;
   				posY += 3*borderSize + swfHeight;
   				var swfName:String = (loaderInfo.parameters.hostCamUrl == undefined) ? "/flash/HostCam.swf" : "/flash/HostCam_v" + loaderInfo.parameters.hostCamUrl + ".swf";
   				
   				try {
   					hostCamSwf = new LoadExternalSwf(swfName, 
   												swfWidth, swfHeight,
   												0, 0,
   												!(loaderInfo.parameters.maskHostCam == "false"));
   					// These are sent too often by other things.
   					//this.addEventListener (Event.ACTIVATE, hostCamSwf.activate);
   					//this.addEventListener (Event.DEACTIVATE, hostCamSwf.deactivate);
   					this.addEventListener (PAUSE_VIDEO, hostCamSwf.pauseVideo);
   					this.addEventListener (RESTART_VIDEO, hostCamSwf.restartVideo);
   					
   					hostCamSwf.addEventListener(LoadExternalSwf.CAMERA_ALLOWED, cameraAllowed);
   					hostCamSwf.addEventListener(LoadExternalSwf.CAMERA_DENIED, cameraDenied);
   					
   				} catch (err:Error) {
   					Log.traceMsg("Error recreating hostcamswf: " + err.message, Log.ALERT);
   				}
   				Log.traceMsg("hostCamSwf position: " + posX + ":" + posY, Log.NORMAL);
   				
   				// Offset the hostcam within the modified border.
   				hostCamSwf.x = - swfWidth/2;
   				hostCamSwf.y = - swfHeight/2;
   				
   				Log.traceMsg("hostCamSwf x,y: " + hostCamSwf.x + ":" + hostCamSwf.y, Log.NORMAL);
   				var boxAlpha:Number = Number(loaderInfo.parameters.dialogAlpha);
   				if (isNaN(boxAlpha)) {
   					boxAlpha = 0.50;
   				}
   				if (!hostCamDialogBox) {
   					hostCamDialogBox = makeDialogBox(hideHostCam, posX, posY, borderSize, 
   													swfWidth, swfHeight, 0x000000, boxAlpha);
   				}
   				
   				// The hostCamSwf is destroyed and reconstructed each time as needed.
   				hostCamDialogBox.addChild(hostCamSwf);
   				hostCamDialogBox.name = "hostCamDialogBox";
   				hostCamSwf.name += "hostCamSwf.akmpe";
	  			// Can't wait until Camera is allowed before showing box background, b/c then it never alerts anything
	  			this.addChild(hostCamDialogBox);
	   			
	   		} else {
	   			this.addChild(hostCamDialogBox);
	   			dispatchEvent (new Event(RESTART_VIDEO));
	   			try {
    				ExternalInterface.call("showQaWindow");
    			} catch (err:Error) {
    				Log.traceMsg ("Error in invoke QA Window", Log.LOG_TO_CONSOLE);
    			}
	   		}
	   }
	   
	   private function cameraAllowed (evt:Event):void {
	   		hostCamSwf.removeEventListener(LoadExternalSwf.CAMERA_ALLOWED, cameraAllowed);
   			hostCamSwf.removeEventListener(LoadExternalSwf.CAMERA_DENIED, cameraDenied);
	   		Log.traceMsg("Camera was allowed", Log.LOG_TO_CONSOLE);
	   		accessDenied = false;
	   		try {
				ExternalInterface.call("showQaWindow");
			} catch (err:Error) {
				Log.traceMsg ("Error in invoke QA Window", Log.LOG_TO_CONSOLE);
			}
	   		//this.addChild(hostCamDialogBox);
	   }
	   
	   private function cameraDenied (evt:Event):void {
	   		this.removeEventListener (PAUSE_VIDEO, hostCamSwf.pauseVideo);
   			this.removeEventListener (RESTART_VIDEO, hostCamSwf.restartVideo);
   			hostCamSwf.removeEventListener(LoadExternalSwf.CAMERA_ALLOWED, cameraAllowed);
   			hostCamSwf.removeEventListener(LoadExternalSwf.CAMERA_DENIED, cameraDenied);
	   		Log.traceMsg ("You can't broadcast to other users unless you allow access to your camera", Log.LOG_TO_CONSOLE);
	   		// Hide the dialog box background if the video was never allowed.
	   		hostCamDialogBox.removeChild(hostCamSwf);
	   		hostCamSwf.destroy();
	   		hostCamSwf = null;
	   		this.removeChild(hostCamDialogBox);
	   		accessDenied = true;
	   		Log.traceMsg ("Access is denied", Log.LOG_TO_CONSOLE);
	   		
	   }
	   
	   private function makeDialogBox (closeCallback:Function,
	   									posX:Number, posY:Number,
	   									border:Number,
	   									w:Number, h:Number, 
	   									color:uint, alpha:Number):MovieClip {
	   		//var holderClip:MovieClip = new MovieClip();		// Needed for offset for dragging operation
	   		var boxClip:MovieClip = new MovieClip();
	   		var boxWidth:Number  = w + 2 * border;
    		var boxHeight:Number = h + 2 * border;
    		boxClip.mouseChildren = true;
    		boxClip.mouseEnabled = true;
    		// Offset overything so the mouse is centered when dragging the box
    		regOffsetX = -(w/2 + border);
    		regOffsetY = -(h/2 + border);
    		//boxClip.transform.matrix = new Matrix(1, 0, 0, 1, regOffsetX, regOffsetY);
    		boxClip.graphics.beginFill(color, alpha);	// black,  with % alpha
    		boxClip.graphics.moveTo (regOffsetX, 			regOffsetY);
    		boxClip.graphics.lineTo (regOffsetX+boxWidth, 	regOffsetY);
			boxClip.graphics.lineTo (regOffsetX+boxWidth, 	regOffsetY+boxHeight);
			boxClip.graphics.lineTo (regOffsetX, 			regOffsetY+boxHeight);
			boxClip.graphics.lineTo (regOffsetX, 			regOffsetY);
			boxClip.graphics.endFill();
			boxClip.x = posX - border + regOffsetX;
   			boxClip.y = posY - border + regOffsetY;
   			
   			if (loaderInfo.parameters.dialogCloseX == "true") {
   				var closeBox:MovieClip = new CloseXSym();
   				//closeBox.startDrag(false, stage.stageFocusRect);
				closeBox.x = boxWidth - border/2 - closeBox.width + regOffsetX;
				closeBox.y = border/2 + regOffsetY;
				closeBox.name = "videoCloseBox";
				closeBox.addEventListener(MouseEvent.CLICK, closeCallback, false, 0, true); //Use a weak reference, 'cause I'm lazy
				boxClip.addChild(closeBox);
   			}
			boxClip.addEventListener(MouseEvent.MOUSE_DOWN, dragIt, false, 0, true); //Use a weak reference, 'cause I'm lazy
			boxClip.addEventListener(MouseEvent.MOUSE_UP, stopDragging, false, 0, true);
			boxClip.name += "_makeDialogBox";
			
			return boxClip;
	   }
	   
	   private function dragIt (evt:MouseEvent):void {
	  		var thisClip:MovieClip = MovieClip(evt.currentTarget);
	   		Log.traceMsg ("Got to dragit for boxClip", Log.LOG_TO_CONSOLE);
	   		//thisClip.addEventListener(MouseEvent.ROLL_OVER, keepDragging, false, 0, true);
	   		thisClip.addEventListener(MouseEvent.ROLL_OUT, stopDragging, false, 0, true);
	   		// Keep the box within the stage area - accounting for both the offset and width of the box
	   		thisClip.startDrag(false, new Rectangle (-regOffsetX, -regOffsetY, 
	   												stage.stageWidth + regOffsetX*2, stage.stageHeight+regOffsetY*2));
	   }	
	   /*
	   	private function keepDragging (evt:MouseEvent):void {
	   		var thisClip:MovieClip = MovieClip(evt.currentTarget);
	   		Log.traceMsg ("Got to Keep dragging", Log.LOG_TO_CONSOLE);
	   		thisClip.x = stage.mouseX;
	   		thisClip.y = stage.mouseY;
	   }
	   */
	   private function stopDragging (evt:MouseEvent):void {
	   		Log.traceMsg ("Got to stopDragging", Log.LOG_TO_CONSOLE);
	   		var thisClip:MovieClip = MovieClip(evt.currentTarget);
	   		thisClip.stopDrag();
	   		//thisClip.removeEventListener(MouseEvent.MOUSE_OVER, keepDragging, false);
	   		//thisClip.removeEventListener(MouseEvent.MOUSE_OUT, stopDragging, false);
	   		//thisClip.removeEventListener(MouseEvent.MOUSE_UP, stopDragging, false);		
	   }
	   
	   public function hideHostCam(evt:MouseEvent=null):void {
	   		//Log.traceMsg("Got to hideHostCam in AS", Log.ALERT);
	   		if (hostCamSwf && hostCamDialogBox) {
	   			Log.traceMsg("Hiding the host cam", Log.LOG_TO_CONSOLE);
	   			this.removeChild(hostCamDialogBox);
	   			dispatchEvent (new Event(PAUSE_VIDEO));
			
	   		} else {
	   			//Log.traceMsg("There was no host cam to hide", Log.ALERT);
	   		}
	   }
	   
	   public function camViewerStart():void {
		   //Log.traceMsg("AkamaiMultiPlayerExample camViewerStart", Log.ALERT);
           if (liveViewerSwf != null) {
		     //Log.traceMsg("AkamaiMultiPlayerExample camViewerStart", Log.ALERT);
             hideLiveViewer( null, false );
           }
           showLiveViewer();
	   }
	   
	    public function showLiveViewer():void {
	        
	    	var swfWidth:Number = Number(loaderInfo.parameters.viewerWidth);
	   		var swfHeight:Number = Number(loaderInfo.parameters.viewerHeight);
	   		var posX:Number = Number(loaderInfo.parameters.viewerPosX);
	   		var posY:Number = Number(loaderInfo.parameters.viewerPosY);
	   		//Log.traceMsg("Got to showLiveViewer in AS", Log.ALERT);
	   		swfWidth  = isNaN(swfWidth)  ? 275 : swfWidth,
   			swfHeight = isNaN(swfHeight) ? 205 : swfHeight
   				
	   		if (!liveViewerSwf) {
   				//Log.traceMsg("LiveViewerUrl looks good: " + liveViewerUrl, Log.LOG_TO_CONSOLE);
   				//Log.traceMsg("LiveViewerUrl width and height: " + swfWidth + ":" + swfHeight, Log.LOG_TO_CONSOLE);
   				
   				if (isNaN(posX)) {
   				  //posX = this.stage.stageWidth - swfWidth - borderSize*2;
   				  posX = 19;
   				  //Log.traceMsg("posX is NaN, setting to:" + posX, Log.LOG_TO_CONSOLE);
   				}
   				
   				if (isNaN(posY)) {
   					posY = 19;
   				} 
   				// Compensate for other offsets, so that the x,y specified by user is position of upper-left corner
   				posX += 3*borderSize + swfWidth;
   				posY += 3*borderSize + swfHeight;
   				var showViewer:Boolean = (loaderInfo.parameters.maskViewer == undefined) ? true : loaderInfo.parameters.maskViewer;
   				
   				var swfName:String = (loaderInfo.parameters.liveViewerUrl == undefined) ? "/flash/LiveStream.swf" : "/flash/LiveStream_v" + loaderInfo.parameters.liveViewerUrl + ".swf";
   				// We basically need to recreate this every time.
   				liveViewerSwf = new LoadExternalSwf(swfName, 
   													swfWidth, swfHeight,
   													0, 0,
   													showViewer);
   				//Log.traceMsg("LiveViewerUrl position: " + posX + ":" + posY, Log.LOG_TO_CONSOLE);
   		
   				liveViewerSwf.x = /* -borderSize */ - swfWidth/2;
   				liveViewerSwf.y = /* -borderSize */ - swfHeight/2;
   				//liveViewerSwf.width = swfWidth;
   				//liveViewerSwf.height = swfHeight;
   				//Log.traceMsg("LiveViewerUrl x,y: " + liveViewerSwf.x + ":" + liveViewerSwf.y, Log.LOG_TO_CONSOLE);
   				
   				if (!liveViewerDialogBox) {
   					liveViewerDialogBox = makeDialogBox(hideLiveViewer, posX, posY, borderSize, 
   													swfWidth, swfHeight, 0x000000, boxAlpha);
   				}
   				// The entire viewer is destroyed when it is removed from the stage
	   			// So we need to recreate it entirely.
   				liveViewerDialogBox.addChild(liveViewerSwf);
   				liveViewerDialogBox.name = "liveViewerDialogBox";
   				this.addChild(liveViewerDialogBox);
   				liveViewerSwf.addEventListener("Host_Cam_Timeout", destroyLiveViewer);
	        
	   		} else {
	   			//Log.traceMsg("LiveViewerUrl already loaded: " + liveViewerUrl, Log.ALERT);
	   		}
	   }
	   
	    public function hideLiveViewer(evt:MouseEvent=null,hideWindow:Boolean=true):void {
	    	
            if (liveViewerDialogBox) {
	   			try {
                   Log.traceMsg("AkamaiMultiplayerExample Hiding liveViewerDialogBox", Log.LOG_TO_CONSOLE);
	   			   this.removeChild(liveViewerDialogBox);
	   			} catch (err:Error) {
                  //Log.traceMsg ("Error in Hiding Live Viewer", Log.LOG_TO_CONSOLE);
                }
	   		}
	   		//Log.traceMsg("Got to hideLiveViewer in AS", Log.ALERT);
	   		if (liveViewerSwf) {
	   			Log.traceMsg("AkamaiMultiplayerExample Hiding live video", Log.LOG_TO_CONSOLE);
	   			// This destroys the object, as it mostly destroys itself when removed from stage
	   			// FIXME - need to verify this works as intended.
	   			if (hideWindow) {
                   try {
        				ExternalInterface.call("hideQaWindow");
        			} catch (err:Error) {
        				Log.traceMsg ("Error in invoke QA Window", Log.LOG_TO_CONSOLE);
        			}
    			}
                try {
                  liveViewerSwf.removeEventListener("Host_Cam_Timeout", destroyLiveViewer);
	              liveViewerDialogBox.removeChild(liveViewerSwf);
   			      liveViewerSwf = null;
   			    } catch (err:Error) {
                  Log.traceMsg ("Error in Hiding Live Viewer", Log.LOG_TO_CONSOLE);
                }
	   			    
	   		} else {
	   			//Log.traceMsg("There was no live viewer to hide", Log.ALERT);
	   		}
	   }
	   
	   public function destroyLiveViewer(evt:Event=null):void {
	        Log.traceMsg ("AkamaiMultiplayerExample destroyLiveViewer", Log.LOG_TO_CONSOLE);
	    	
            if (liveViewerDialogBox) {
	   			try {
                   Log.traceMsg("AkamaiMultiplayerExample Hiding liveViewerDialogBox", Log.LOG_TO_CONSOLE);
	   			   this.removeChild(liveViewerDialogBox);
	   			} catch (err:Error) {
                  Log.traceMsg ("Error in Hiding Live Viewer", Log.LOG_TO_CONSOLE);
                }
	   		}
	   		//Log.traceMsg("Got to hideLiveViewer in AS", Log.ALERT);
	   		if (liveViewerSwf) {
	   			Log.traceMsg("AkamaiMultiplayerExample Hiding live video", Log.LOG_TO_CONSOLE);
	   			try {
                  liveViewerSwf.removeEventListener("Host_Cam_Timeout", destroyLiveViewer);
	              liveViewerDialogBox.removeChild(liveViewerSwf);
   			      liveViewerSwf = null;
   			    } catch (err:Error) {
                  Log.traceMsg ("Error in Hiding Live Viewer", Log.LOG_TO_CONSOLE);
                }
	   			    
	   		} else {
	   			//Log.traceMsg("There was no live viewer to hide", Log.ALERT);
	   		}
	   }
	   
	   private function okayToStartFullscreen(answer:String):void {
			player.toggleFullscreen();
       }
	   
	   // Allow volume setting from 0 (mute) to 10 (100% volume); Flash uses 0 - 1.0 as the range.
	   public function setVolume(level:String):void {
			var volLevel:Number = Number(level);
			if (volLevel > 10) {
				volLevel = 10;
			}
			if (volLevel < 0) {
				volLevel = 0;
			}
			// Store it for retrieval later if toggling mute on/off
			if (volLevel > 0) {
				lastVolLevel = String(volLevel);
			}
			volLevel = volLevel/10;
			
			Log.traceMsg("setVolume: " + volLevel, Log.LOG_TO_CONSOLE);
			// Player stores this in LSO by default.
			player.volume = volLevel;
       }
       
       private function stageInit (evt:Event):void {
        
          if (loaderInfo.parameters.debugConsole == "true") {
            Log.init(stage);
          }
       	  
       	  Stats.init(stage);
       	  Watermark.init(stage,50);
       	  
       		_trailerMode = (loaderInfo.parameters.trailerMode == "true");
        	boxAlpha = Number(loaderInfo.parameters.dialogAlpha);
   			if (isNaN(boxAlpha)) {
   				boxAlpha = 0.50;
   			}
   			borderSize = Number(loaderInfo.parameters.borderSize);
   			if (isNaN(borderSize)) {
   				borderSize = 20;
   			}
			
		   // import com.zeusprod.AEScrypto;
		   //AEScrypto.unitTest();
       		
            if (ExternalInterface.available) {
        		try {
        			ExternalInterface.addCallback("displayMessage", displayMessage);
        			ExternalInterface.addCallback("resizeNotification", resizeNotification);
        			ExternalInterface.addCallback("setVolume", setVolume);
				    ExternalInterface.addCallback("toggleSound", toggleSound);
				    ExternalInterface.addCallback("goFullscreen", goFullscreen);
				    ExternalInterface.addCallback("showHostCam", showHostCam);
        			ExternalInterface.addCallback("hideHostCam", hideHostCam);
        			ExternalInterface.addCallback("showLiveViewer", showLiveViewer);
        			ExternalInterface.addCallback("hideLiveViewer", hideLiveViewer);
				    ExternalInterface.addCallback("camViewerStart", camViewerStart);
        			 
        		} catch (err:Error) {
        			Log.traceMsg("Make sure allowScriptAccess is true", Log.ALERT);
        		}
        	}
        	this.removeEventListener(Event.ADDED_TO_STAGE, stageInit);
            this.addEventListener(Event.REMOVED_FROM_STAGE, cleanup);
            stage.addEventListener(FullScreenEvent.FULL_SCREEN, exitFullScreen);
            stage.scaleMode = StageScaleMode.NO_SCALE;
            stage.align = StageAlign.TOP_LEFT;
			
            if (stage.stageWidth == 0 || stage.stageHeight == 0)
            {
				setStageListeners(onInitStageResize);
            }
            else
            {
				onInitStageResize(null);
            }
        }
        
        private function onInitStageResize(evt:Event):void
        {
        	var vidOffsetX:Number;
        	var vidOffsetY:Number;
        	
            if (stage.stageWidth > 0 && stage.stageHeight > 0)
            {
				setStageListeners(onInitStageResize, false);
				setStageListeners(onResize);
				if (_trailerMode) {
                	var supportTrailerBorder:Boolean = false;
                	
                	if (supportTrailerBorder) {
                		createTrailerBackground();
                	}
                	
                	trailerWidth = Number(loaderInfo.parameters.trailerVideoW);
                	if (isNaN(trailerWidth)) trailerWidth = DEFAULT_TRAILER_WIDTH;
                	trailerHeight = Number(loaderInfo.parameters.trailerVideoH);
                	if (isNaN(trailerHeight)) trailerHeight = DEFAULT_TRAILER_HEIGHT;
                	
                	player = new AkamaiMultiPlayer(trailerWidth, trailerHeight, loaderInfo.parameters);
                	
                	if (supportTrailerBorder) {
                		vidOffsetX = Number(loaderInfo.parameters.trailerVideoX);
                		if (isNaN(vidOffsetX)) vidOffsetX = 50;
                	
                		vidOffsetY = Number(loaderInfo.parameters.trailerVideoY);
                		if (isNaN(vidOffsetY)) vidOffsetY = 45;
                	
                		player.x = vidOffsetX;
                		player.y = vidOffsetY;
                	}
                } else {
                	player = new AkamaiMultiPlayer(stage.stageWidth, stage.stageHeight, loaderInfo.parameters);
                }
                
				Log.traceMsg("AkamaiMultiPlayerExample: onInitStageResize ", Log.LOG_TO_CONSOLE);
                // If you want to rely on the src coming in as a flash var,  then comment-out the next line
               // player.setNewSource(MBR_SMIL); //getTestMedia()
				
                player.addEventListener("toggleFullscreen", handleFullscreen);
                
                if (loaderInfo.parameters.showMuteButton == "true") {
                	var testButton:Button = new Button();
                	testButton.x = 50;
                	testButton.y = 50;
                	testButton.width = 200;
                	testButton.label = "ClickMe to toggle PrivateChatMute";
                	testButton.addEventListener(MouseEvent.CLICK, invokeJStest);
                	testButton.name = "testButton";
                	addChild(testButton);
            	} else {
            		//Log.traceMsg("showMuteButton " + loaderInfo.parameters.showMuteButton, Log.ALERT); 
            	}
                if (loaderInfo.parameters.showVideo != "false") {
                	player.name = "AkamaiMultiPlayerVideoThing";
                	addChild(player);
                	createNotificationDialogBox();
                } else {
                	//Log.traceMsg("showVideo " + loaderInfo.parameters.showVideo, Log.ALERT);
                }
      
            }
        }
		
		private function invokeJStest (evt:MouseEvent):void {
			try {
				ExternalInterface.call("PrivateChatMute");
			} catch (err:Error) {
				Log.traceMsg ("Error in invoke JS test", Log.ALERT);
			}
		}
		
		private function onResize(event:Event):void
		{		
			if (_trailerMode) {
				player.resizeTo(trailerWidth, trailerHeight);
			} else {
				player.resizeTo(stage.stageWidth, stage.stageHeight);
			}
			
		}
        
        private function handleFullscreen(e:Event):void
        {
            if (stage.displayState == StageDisplayState.NORMAL)
            {
				//remove stage listener here to during fullscreen
				setStageListeners(onResize, false);
                _video = new Video();
                _video = player.video;
				
                _video.width = _video.videoWidth;
                _video.height = _video.videoHeight;
                _video.smoothing = false;
                _video.x = 0;
                _video.y = 0;
                stage.addChild(_video);
                _video.name = "fullscreen video";
                stage.fullScreenSourceRect = new Rectangle(0, 0, _video.videoWidth, _video.videoHeight);
                try {
               		stage.displayState = StageDisplayState.FULL_SCREEN;
               	} catch (err:Error) {
               		Log.traceMsg ("Fullscreen mode not allowed: " + err.message, Log.ALERT);
               	}
            }           
        }
        
        private function exitFullScreen(e:FullScreenEvent = null):void
        {
            if (e && !e.fullScreen)
            {
				//since we remove the stage listener on fullscreen we add it back here
				setStageListeners(onResize);
				if (_trailerMode) {
					player.resizeTo(trailerWidth, trailerHeight);
				} else {				
                	player.resizeTo(stage.stageWidth, stage.stageHeight);
    			}
                stage.displayState = StageDisplayState.NORMAL;
            }
        }
		
		private function setStageListeners(handler:Function, add:Boolean = true):void
		{
			if (add)
			{
				stage.addEventListener(Event.RESIZE, handler);
			}else
			{
				stage.removeEventListener(Event.RESIZE, handler);
			}
		}
		
		private function cleanup(evt:Event):void {
			this.removeEventListener(Event.REMOVED_FROM_STAGE, stageInit);
           	hostCamSwf.destroy();
		}
    }
}
