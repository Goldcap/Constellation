package com.constellation.view
{
	import com.constellation.externalConfig.ExternalConfig;
	import com.sierrastarstudio.utils.tracer;
	
	import flash.events.Event;
	import flash.events.TimerEvent;
	import flash.text.AntiAliasType;
	import flash.text.TextField;
	import flash.text.TextFieldAutoSize;
	import flash.text.TextFormat;
	import flash.text.TextFormatAlign;
	import flash.utils.Timer;
	
	import org.casalib.display.CasaSprite;
	import flash.display.Sprite;
	
	public class statusMessageView extends CasaSprite
	{
		private var _classname:String = "com.constellation.view.statusMessageView";
		//font
		[Embed(source='../graphics/fonts/HelveticaNeue-Medium.otf', fontFamily="helvNeuCond", embedAsCFF="false")]
		
		public var helvNeuCond:Class;
		
		private var _statusTextField:TextField;
		private var _dotCount:int  = 0;
		private var _dotTimer:Timer;
		private var _originalStatusMessage:String = "";
		private var _isBufferingTextField:TextField;
		private var _dotMax:int = 3;
		private var _bgSprite:Sprite;
		
		
		
		public function statusMessageView()
		{
			super();
			if(this.stage){
				this.init();
			}else{
				this.addEventListener(Event.ADDED_TO_STAGE,init);
			}
		}
		private function init(evt:Event = null):void{
			this.removeEventListener(Event.ADDED_TO_STAGE,init);
			this._bgSprite = new Sprite();
			this._bgSprite.graphics.beginFill(0x000000);
			this._bgSprite.graphics.drawRect(0,0,100,100);
			this._bgSprite.graphics.endFill();
			
			this._statusTextField = new TextField();
			this._statusTextField.embedFonts = true;
			this._statusTextField.antiAliasType = AntiAliasType.ADVANCED;
			//buffering text
			this._isBufferingTextField = new TextField();
			this._isBufferingTextField.embedFonts = true;
			this._isBufferingTextField.antiAliasType = AntiAliasType.ADVANCED;
		
			this.addChild(this._statusTextField);
			this.addChild(this._isBufferingTextField);
			
			
			this._dotTimer = new Timer(1000);
			this._dotTimer.addEventListener(TimerEvent.TIMER,onAddDots);
			this._bgSprite.alpha = 0.1;
			this.addChildAt(this._bgSprite,0);
		}
		private function showStatusMessage(msg:String,isBuffering:Boolean=false):void{
			
			this._originalStatusMessage = msg;
			
			this._statusTextField.htmlText = _originalStatusMessage;
		
	//tracer.log("show status message  visible "+this.visible+" msg:"+msg,_classname);		
			var newTF:TextFormat = new TextFormat();
				newTF.font = "helvNeuCond"
				newTF.color = ExternalConfig.getInstance().statusTextColor;
				newTF.size = 14
			this._statusTextField.setTextFormat(newTF);
			
			if(isBuffering==true){
				this._isBufferingTextField.text = "Buffering";
				newTF.align = TextFormatAlign.RIGHT
				this._isBufferingTextField.setTextFormat(newTF);
			}else{
				this._isBufferingTextField.text = "";
			}
			this.startDotTimer();
		}
		
		private function onAddDots(evt:TimerEvent):void{
			if(this._originalStatusMessage==""){
				if(this._dotTimer.running){
					this._dotTimer.stop();
				}
			}
			
		//	if(this._dotCount==0){
				this.showStatusMessage(this._originalStatusMessage);
		//	}
			
			for(var i:int =0; i<this._dotCount+1;i++){
				if(this._isBufferingTextField.text==""){
					this._statusTextField.appendText(".")
				}else{
					this._isBufferingTextField.appendText(".")
				}
			}
			this._dotCount+=1
			if(this._dotCount>this._dotMax){
				this._dotCount = 0;
				this.showStatusMessage(this._originalStatusMessage);
			}
			//tracer.log("dot timer "+this._originalStatusMessage+" dotCount "+this._dotCount+"  status field "+this._statusTextField.text,_classname);
		}
		public function stopDotTimer():void{
			if(this._dotTimer.running){
				this._dotTimer.stop()
			}
		}
		public function startDotTimer():void{
			if(this._dotTimer.running==false){
				this._dotTimer.start()
			}
		}
		public function get currentStatusMessage():String{
			return this._originalStatusMessage
		}
		public function statusMessage(str:String,isBuffering:Boolean=false):void{
			//if empty message is sent, stop timer and hide
			if(str==""){
				this.visible = false;
				if(this._dotTimer.running ==true){
					this._dotTimer.stop();
				}
			}else{
				this.visible = true
			}
			this.showStatusMessage(str,isBuffering);
		}
		public function set textWidth(newWidth:int):void{
			this._bgSprite.width = newWidth;
			this._statusTextField.width = newWidth
			this._isBufferingTextField.x = newWidth-100;
		}
		public function set textHeight(newHeight:int):void{
			this._bgSprite.height = newHeight;
			this._statusTextField.height = newHeight;
			this._isBufferingTextField.height = newHeight;
		}
	}
}