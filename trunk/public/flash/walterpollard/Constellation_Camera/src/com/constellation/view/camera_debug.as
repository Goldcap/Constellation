package com.constellation.view
{
	import com.constellation.externalConfig.ExternalConfig;
	
	import flash.events.Event;
	import flash.events.TimerEvent;
	import flash.media.Camera;
	import flash.media.Microphone;
	import flash.system.Capabilities;
	import flash.text.TextField;
	import flash.text.TextFieldAutoSize;
	import flash.utils.Timer;
	
	import org.casalib.display.CasaSprite;
	
	public class camera_debug extends CasaSprite
	{
		// Internals
		private static const REFRESH_INTERVAL:int = 2000;
		
		
		private var _classname:String = "com.constellation.view";

		private var _debugContainer:CasaSprite;
		private var _debugBG:CasaSprite;
		private var bg_x:Number = -50;
		private var bg_y:Number = -50;
		private var _debugText:TextField;
		private var _debugTimer:Timer;
		private var _camera:Camera;
		
		public function camera_debug()
		{
			super();
			if(this.stage){
				init();
			}
			else{
				this.addEventListener(Event.ADDED_TO_STAGE,init);
					}
		}
		
			private function init(evt:Event=null):void{
				this._debugContainer = new CasaSprite();
				
				this._debugBG = new CasaSprite();
				this._debugBG.graphics.beginFill(0x000000,.5);
				this._debugBG.graphics.drawRect(0,0,stage.stageWidth+20,stage.stageHeight+20);
				this._debugBG.graphics.endFill();
				
				_debugText = new TextField();
				_debugText.textColor = 0xffffff;
				_debugText.width = this.stage.stageWidth-30;
				_debugText.height = this.stage.stageHeight -30;
		
				_debugTimer = new Timer(1000);
				this._debugTimer.addEventListener(TimerEvent.TIMER,updatePanel);
				this._debugTimer.start();
				this._debugContainer.addChild(this._debugText);
				this._debugContainer.addChildAt(this._debugBG,0);
				this._debugContainer.x = 0;
				this._debugContainer.y = 0
				this.addChild(this._debugContainer);
			}
			public function addCamera(cam:Camera=null):void{
			if(cam){
				this._camera = cam	
			}
			}
			protected function updatePanel(evt:TimerEvent):void
			{
				
					
					var videoInfo:String = "Build " + ExternalConfig.getInstance().version
					
					// Build a information text message. 
					var targetFP:String = "10.0";
					CONFIG::FLASH_10_1	
					{	
						targetFP = "10.2";
					}
					
					videoInfo += " for Flash Player "
						+ targetFP
						+ "\n";
					
					videoInfo += "Flash Player version:\t" + Capabilities.version;
					if (Capabilities.isDebugger)
					{
						videoInfo += " (debug)";
					}
					if(this._camera){
					videoInfo += "activityLevel:\t" 
						+ this._camera.activityLevel
						+"\n"
						+ "\t";
					videoInfo += "bandWidth:\t" 
						+ this._camera.bandwidth
						+"\n"
						+ "\t";
					videoInfo += "CurrentFPS:\t" 
						+ this._camera.currentFPS
						+"\n"
						+ "\t";
					videoInfo += "Max FPS:\t" 
						+ this._camera.fps
						+"\n"
						+ "\t";
					videoInfo += "keyFrameInterval:\t" 
						+ this._camera.keyFrameInterval
						+"\n"
						+ "\t";
					videoInfo += "Camera loopback - Used for testing (simulation of a user):\t" 
						+ this._camera.loopback
						+"\n"
						+ "\t";
					videoInfo += "motionLevel:\t" 
						+ this._camera.motionLevel
						+"\n"
						+ "\t";
					videoInfo += "motionTimeout:\t" 
						+ this._camera.motionTimeout
						+"\n"
						+ "\t";
					videoInfo += "quality:\t" 
						+ this._camera.quality
						+"\n"
						+ "\t";
					}
					videoInfo += "MicroPhone gain level:\t" 
						+ Microphone.getMicrophone().gain
						
						+"\n"
						+ "\t";
					videoInfo += "MicroPhone activity level:\t\n[Read Only] "
						+"MicroPhone activity level:\t"+ Microphone.getMicrophone().activityLevel
						
						+"\n"
						+ "\t";
					videoInfo += "MicroPhone silenceLevel level:\t\n[Read Only] The amount of sound required to activate the microphone and dispatch the activity event. The default value is 10.\n " 
						+"MicroPhone silenceLevel level:\t"+ Microphone.getMicrophone().silenceLevel
						
						+"\n"
						+ "\t";
					videoInfo += "MicroPhone enable VAD:\t" 
						+ Microphone.getMicrophone().enableVAD
						
						+"\n"
						+ "\t";
					videoInfo += ":\t" 
						
						+"\n"
						+ "\t";
					this._debugText.htmlText = videoInfo
					this._debugText.autoSize = TextFieldAutoSize.LEFT
				
				
			}		
			
	}
}