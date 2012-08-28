package com.constellation.view
{
	import com.constellation.externalConfig.ExternalConfig;
	
	import flash.display.GradientType;
	import flash.display.SpreadMethod;
	import flash.display.Sprite;
	import flash.events.Event;
	import flash.events.TextEvent;
	import flash.events.TimerEvent;
	import flash.geom.Matrix;
	import flash.text.AntiAliasType;
	import flash.text.TextField;
	import flash.text.TextFieldAutoSize;
	import flash.text.TextFormat;
	import flash.utils.Timer;
	
	import org.casalib.util.NavigateUtil;
	
	public class messageView extends Sprite
	{
		private var _classname:String = "com.constellation.view.messageView";
		
		private static var instance:messageView;
		//font
		[Embed(source='../graphics/fonts/HelveticaNeue-Medium.otf', fontFamily="helvNeuCond", embedAsCFF="false")]
		
		public var helvNeuCond:Class;
		
		
		public var _messageWindow:Sprite;
		private var _messageText:TextField;
		private var _messageTimer:Timer;
		private var _windowBG:Sprite;
		private var targetWidth:int = 400;
		private var targetHeight:int = 200;
		
		public function messageView(enforcer:SingletonEnforcer)
		{
		}
		public static function getInstance():messageView
		{
			if (messageView.instance == null)
			{
				messageView.instance = new messageView(new SingletonEnforcer());
			}
			return messageView.instance;
		}
		public function set messageWindow(mWindow:Sprite):void{
			
		
		this._messageTimer = new Timer(ExternalConfig.getInstance().MESSAGE_TIMER_INTERVAL,1);
		this._messageTimer.addEventListener(TimerEvent.TIMER,onClearMessageWindow);
		
		this._messageWindow = mWindow;
		//this._messageWindow.stage.addEventListener(Event.RESIZE,resizeBG);
		targetWidth = this._messageWindow.stage.stageWidth/2;
		targetHeight = this._messageWindow.stage.stageHeight/2;
		this._windowBG = new Sprite();
		
		this._windowBG.name = "BG";
		this._windowBG.graphics.lineStyle(1,0x93c5fa,1);
		var colors:Array = [0x336799,0x000144];
		var alphas:Array = [1, .4];
		var ratios:Array = [0, 255];
		var matr:Matrix = new Matrix();
		matr.createGradientBox(targetWidth, targetHeight,0, 0, 0);
		
		this._windowBG.graphics.beginGradientFill(GradientType.RADIAL,colors,alphas,ratios,matr,SpreadMethod.PAD,"rgb",0)
			
		
		this._windowBG.graphics.drawRoundRect(0,0,targetWidth,targetHeight,10,10);
		this._windowBG.graphics.endFill();
		this._windowBG.x = 0;
		this._windowBG.y = 0;
		
		this._messageWindow.addChild(this._windowBG);
		
		this._messageText = new TextField();
		this._messageText.embedFonts = true;
		this._messageText.antiAliasType = AntiAliasType.ADVANCED;
		this._messageText.multiline = true;
		this._messageText.wordWrap = true;
		this._messageText.antiAliasType = AntiAliasType.ADVANCED;
		this._messageText.autoSize = TextFieldAutoSize.CENTER;
		this._messageText.border = false;
		this._messageText.borderColor = 0xffffff
		var newTF:TextFormat = new TextFormat();
			newTF.font = "helvNeuCond"
		this._messageText.setTextFormat(newTF);
		
		this._messageWindow.addChild(this._messageText);
		this._messageWindow.visible = false;
	
		
		}
		public function resizeBG(evt:Event=null):void{
			targetWidth = this._messageWindow.stage.stageWidth/2;
			targetHeight = this._messageWindow.stage.stageHeight/2;
			
			this._windowBG.x = 0;
			this._windowBG.y = 0;
			this._windowBG.width = targetWidth;
			this._windowBG.height = targetHeight;
			
			this._messageText.width =  targetWidth - 10;
			this._messageText.height = targetHeight-10;
			
			this._messageText.x = this._windowBG.width/2-this._messageText.width/2;
			this._messageText.y = this._windowBG.height/2 - this._messageText.height/2;
			
		}
		
		protected function onClearMessageWindow(event:TimerEvent):void
		{
			this._messageWindow.visible = false;
			
		}
		public function textMessagEvent(evt:TextEvent):void{
			var messageEvent:String = evt.text.toLowerCase();
 
			
			trace("Event "+evt.text);
			if(messageEvent=="supportemail"){
				//navigateToURL(new URLRequest("mailto:support@constellation.tv?subject=Constellation.tv Screening Error "+ExternalConfig.getInstance().currentErrorCode), "_self");
				NavigateUtil.openWindow("mailto:support@constellation.tv?subject=Constellation.tv Screening Error "+ExternalConfig.getInstance().currentErrorCode,NavigateUtil.WINDOW_BLANK);
			}
		}
		public function setMessage(messageText:String, hideTimer:Boolean = true):void{
			
			
			var strippedText:String = messageText.replace("null","");
			this._messageText.htmlText = "<br>"+strippedText+"<br><br>";
			this._messageText.autoSize = TextFieldAutoSize.CENTER;
			
			
			var newTF:TextFormat = new TextFormat();
				newTF.font = "helvNeuCond"
					
			//	newTF.color = 0xffffff;
			//	newTF.size = 18;
			this._messageText.setTextFormat(newTF);
			
			
			
		
			//this._messageWindow.x = (this._messageWindow.stage.width - this._messageWindow.width)/2;
			//this._messageWindow.y = (this._messageWindow.stage.height - this._messageWindow.height)/2;
			this._messageWindow.visible = true;
			this.resizeBG();
			if(hideTimer == true){
				this._messageTimer.start();
			}
		}
		
	}
}
class SingletonEnforcer{}
