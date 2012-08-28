package
{
	import com.constellation.Watermark;
	import com.constellation.config.errorConfig;
	import com.constellation.controllers.ExternalInterfaceController;
	import com.constellation.controllers.heartbeatController;
	import com.constellation.controllers.videoController;
	import com.constellation.events.constellationEvent;
	import com.constellation.externalConfig.ExternalConfig;
	import com.constellation.managers.loggingManager;
	import com.constellation.parsers.smilParser;
	import com.constellation.services.tokenService;
	//import com.constellation.view.hostCameraView;
	import com.constellation.view.messageView;
	import com.constellation.view.videoView;
	//import com.constellation.view.viewerCameraView;
	import com.sierrastarstudio.utils.tracer;
	
	import flash.display.Bitmap;
	import flash.display.Sprite;
	import flash.display.StageAlign;
	import flash.display.StageDisplayState;
	import flash.display.StageScaleMode;
	import flash.events.Event;
	import flash.events.FullScreenEvent;
	import flash.events.MouseEvent;
	import flash.events.TimerEvent;
	import flash.external.ExternalInterface;
	import flash.net.URLLoader;
	import flash.net.URLRequest;
	import flash.system.Security;
	import flash.ui.Mouse;
	import flash.utils.Timer;
	
	[SWF(backgroundColor="0x000000", frameRate="24", width="500", height="375")]
	
	public class constellation_as3 extends Sprite
	{
		[Embed(source="../graphics/constellation_player_fullscreen.png")]
		private var fullscreenImg:Class;
		
		private var _classname:String = "constellation_main";
		
		
		private var _heartbeating:Boolean = true;
		
		// Version Number 0.1 implies going to AS3 based player
		private static var version:String = "0.1.1";
		
		// Are we in testing mode?
		private var _testLocal:Boolean;
		
		/**
		 * heartbeat controller takes care of dealing with the heartbeat
		 * 
		 * @param The Path to the heartbeat url
		 */ 			
		private var _heartbeatController:heartbeatController;
		
		private var _smilParser:smilParser;
		
	
		private var _originalTicketParam:String;
		//encoded string to prepData to send to service for auth			
		private var _prepData:String;
	//camera views
		//private var _hostCameraView:hostCameraView;
		//private var _viewerCameraView:viewerCameraView;
	//display objects
		private var _fullscreenImg:Sprite;
		private var _messageWindow:Sprite;
		private var _mouseMoveTimer:Timer;
		private var _videoView:videoView;
		
		
		public function constellation_as3()
		{
			if(this.stage){
				this.init();
			}else{
				this.addEventListener(Event.ADDED_TO_STAGE,init); 
			}
			ExternalInterfaceController.getInstance().loadExternalInterface();
			
		}
		
		private function init(evt:Event=null):void
		{
			Security.allowDomain("*.constellation.tv");
			
			this.stage.scaleMode = StageScaleMode.NO_SCALE;
			this.stage.align = StageAlign.TOP_LEFT;
			
			stage.addEventListener(Event.RESIZE,onResize);
			this.removeEventListener(Event.ADDED_TO_STAGE,init);
			ExternalConfig.getInstance().loadOptions(this);
			loggingManager.getInstance().loadLoggingPath();
			
			this._testLocal = ExternalConfig.getInstance().testLocal;
			
			this._videoView = new videoView();
			
			this.addChild(this._videoView);
			
			//videoController.getInstance().videoView = this._videoView;
			tokenService.getInstance().init();
			
			//create the smil parser and listen for smil parsed event
			this._smilParser = new smilParser();
			this._smilParser.addEventListener(constellationEvent.SMIL_PARSED,onSmilParsed);
			this._smilParser.addEventListener(constellationEvent.SMIL_MALFORMED,onSMIL_Malformed);
		tracer.log("smil parser created "+this._smilParser,_classname);
	//		videoController.getInstance().smilParser = this._smilParser;
			this._videoView.smilParser = this._smilParser;
			
			
			if (this._testLocal ==true) {
				this._smilParser.loadSMILDirect( ExternalConfig.getInstance().smilTestPath)
					this._heartbeating = false;
			}else{
				this._heartbeating = true;
			}
			//check if we need heartbeat
			if(this._heartbeating==true){
				this._heartbeatController = new heartbeatController()
				this._heartbeatController.addEventListener(constellationEvent.HEART_ATTACK,onHeartAttack);
				this._heartbeatController.startHeartBeat();
				this._videoView.heartBeatController = this._heartbeatController
			}
				//fullscreen button
			this._fullscreenImg = new Sprite();
			this._fullscreenImg.addChild(new fullscreenImg());
			this._fullscreenImg.addEventListener(MouseEvent.CLICK,makeFullScreen);
			this._fullscreenImg.buttonMode = true;
			this._fullscreenImg.mouseEnabled = true;
			this.addChild(this._fullscreenImg);
			//message display
			
	
			this._messageWindow = new Sprite();
			//this._messageWindow.visible = false;
			this.addChildAt(this._messageWindow,this.numChildren-1);
			messageView.getInstance().messageWindow = this._messageWindow;
			//messageView.getInstance().setMessage("OK here now",true);
			
			this.onResize();
			messageView.getInstance().resizeBG();
			this._messageWindow.x = this.stage.stageWidth/2-this._messageWindow.width/2;
			this._messageWindow.y = this.stage.stageHeight/2 - this._messageWindow.height/2;
	
			_originalTicketParam = ExternalConfig.getInstance().ticketParams
			
			Watermark.init(this.stage,1000);
			Watermark.startup(_originalTicketParam  );
			Watermark.startTimer();
			
			this.addEventListener(MouseEvent.MOUSE_MOVE,mouseMoveHandler);
		}
		
		
	
		
		protected function onResize(event:Event=null):void
		{
			this._fullscreenImg.x = 10;
			this._fullscreenImg.y = this.stage.stageHeight -this._fullscreenImg.height;
				
		//	this._messageWindow.width = this.stage.stageWidth/2;
		//	this._messageWindow.height = this.stage.stageHeight/2;
			
			messageView.getInstance().resizeBG();
			this._messageWindow.x = this.stage.stageWidth/2-this._messageWindow.width/2;
			this._messageWindow.y = this.stage.stageHeight/2 - this._messageWindow.height/2;
			
			this._videoView.resizeVideo();
		}
		private function onSmilParsed(constEvt:constellationEvent):void{
			//videoController.smilParsed()
		}
		
		private function doConnection():void
		{
			// TODO Auto Generated method stub
			
			
		}
		protected function onSMIL_Malformed(event:Event):void
		{
			ExternalConfig.getInstance().currentErrorCode= errorConfig.stream_smilMalformed;
			messageView(ExternalConfig.getInstance().defaultErrorMessage+errorConfig.stream_smilMalformed);
		}
		
		
		protected function onHeartAttack(evt:constellationEvent):void
		{
			var typeOfAttack:String = evt.data as String;
			var userMessage:String = ExternalConfig.getInstance().heartbeatFailMessage
			
			if(typeOfAttack.toLowerCase()==heartbeatController.RESPONSE_ATTACK.toLowerCase()){
				ExternalConfig.getInstance().currentErrorCode = errorConfig.heartbeat_FailedResponse_ErrorCode
				
			}else if(typeOfAttack.toLowerCase()==heartbeatController.SERVER_REJECT_ATTACK.toLowerCase()){
				ExternalConfig.getInstance().currentErrorCode = errorConfig.heartbeat_ServerReject_ErrorCode;
				
			}else if(typeOfAttack.toLowerCase()==heartbeatController.SKIPPED_BEATS_ATTACK.toLowerCase()){
				ExternalConfig.getInstance().currentErrorCode = errorConfig.heartbeat_SkippedBeats_ErrorCode;
				
			}else{
				userMessage = ExternalConfig.getInstance().heartbeatFailMessage_unknown;
			}
			if(	ExternalConfig.getInstance().currentErrorCode==null||	ExternalConfig.getInstance().currentErrorCode!=""){
				userMessage+=	ExternalConfig.getInstance().currentErrorCode
			}
			tracer.log("HEART ATTACK "+userMessage,_classname);
			this.disconnectStream();
			this.showHeartAttackView(userMessage);
		}
		
		private function disconnectStream():void
		{
			// TODO Auto Generated method stub
			videoController.getInstance().disconnectStream();
			
		}
		private function showHeartAttackView(message:String=null):void
		{
			if(message==null){
				messageView.getInstance().setMessage(ExternalConfig.getInstance().heartbeatFailMessage_unknown,false);
			}else{
				messageView.getInstance().setMessage(message,false);
			}
		//	this.videodisplay.alpha = .25;
		}
		// Mouse was moved, display fullscreen button
		public function mouseMoveHandler(e:Event):void {
			
			this._fullscreenImg.visible = true;
			Mouse.show()
			if (this._mouseMoveTimer != null && this._mouseMoveTimer.running) {
				this._mouseMoveTimer.stop();
			}
			this._mouseMoveTimer = new Timer(2500,1);
			
			this._mouseMoveTimer.addEventListener(TimerEvent.TIMER,mouseMoveTimerFired);
			this._mouseMoveTimer.start();
		}
		// Finished timing mouse, remove full screen button
		public function mouseMoveTimerFired(e:Event):void {
			this._fullscreenImg.visible = false;	
			Mouse.hide();
		}
		private function makeFullScreen(evt:Event = null):void 
		{
			
			if (stage.displayState == StageDisplayState.FULL_SCREEN || stage.displayState == 'fullScreenInteractive') 
			{
				//stage.fullScreenSourceRect = null; 
				stage.displayState = StageDisplayState.NORMAL;
				tracer.log("Make Normal",_classname);	
			}
			else if (stage.displayState == StageDisplayState.NORMAL)
			{
				//	stage.fullScreenSourceRect = new Rectangle(videodisplay.x,videodisplay.y,videodisplay.width,videodisplay.height);
				//stage.displayState = 'fullScreenInteractive';
				stage.displayState = StageDisplayState.FULL_SCREEN;
				tracer.log("Make Fullscreen",_classname);	
			}
			this.onResize();
		}
		
	
	
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	}
}