package com.constellation.view
{
	import com.constellation.externalConfig.ExternalConfig;
	
	import flash.display.Sprite;
	import flash.events.Event;
	import flash.events.TextEvent;
	import flash.events.TimerEvent;
	import flash.text.TextField;
	import flash.text.TextFieldAutoSize;
	import flash.text.TextFormat;
	import flash.utils.Timer;
	
	import org.casalib.util.NavigateUtil;
	
	public class messageView extends Sprite
	{
		private var _classname:String = "com.constellation.view.messageView";
		
		private static var instance:messageView;
		
		
		public var _messageWindow:Sprite;
		private var _messageText:TextField;
		private var _messageTimer:Timer;
		private var _windowBG:Sprite;
		
		
		
		
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
		
		var targetWidth:int = this._messageWindow.stage.stageWidth/2//+250;
		var targetHeight:int = 300;
		this._windowBG = new Sprite();
		this._windowBG.name = "BG";
		this._windowBG.graphics.beginFill(0x505050);
		this._windowBG.graphics.drawRect(0,0,targetWidth,targetHeight);
		this._windowBG.graphics.endFill();
		this._messageWindow.addChild(this._windowBG);
		this._messageText = new TextField();
		this._messageText.width = this._messageWindow.width-25;
		this._messageText.height = this._messageWindow.height - 100;
		this._messageText.multiline = true;
		this._messageWindow.addChild(this._messageText);
		this._messageWindow.visible = false;
		
		
		}
		public function resizeBG():void{
			this._windowBG.width = this._messageWindow.width;
			this._windowBG.height = this._messageWindow.height;
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
			this._messageText.width= this._messageWindow.width-25;
			this._messageText.height = this._messageWindow.height - 25;
			
			this._messageText.htmlText = "<br>"+messageText+"<br><br>";
			this._messageText.autoSize = TextFieldAutoSize.CENTER;
			this._messageText.multiline= true;
			var messageTF:TextFormat = new TextFormat();
				messageTF.color = 0xffffff;
			//	messageTF.font = 
				messageTF.size = 18;
			
			this._messageText.setTextFormat(messageTF);
			
			
			
			this._messageText.x = this._messageWindow.width/2-this._messageText.width/2;
			this._messageText.y = this._messageWindow.height/2 - this._messageText.height/2;
			//this._messageWindow.x = (this._messageWindow.stage.width - this._messageWindow.width)/2;
			//this._messageWindow.y = (this._messageWindow.stage.height - this._messageWindow.height)/2;
			this._messageWindow.visible = true;
			if(hideTimer == true){
				this._messageTimer.start();
			}
		}
		
	}
}
class SingletonEnforcer{}
