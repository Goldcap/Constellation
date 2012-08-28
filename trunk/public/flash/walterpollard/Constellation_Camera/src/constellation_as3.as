package
{
	import com.constellation.config.errorConfig;
	import com.constellation.controllers.ExternalInterfaceController;
	import com.constellation.events.constellationEvent;
	import com.constellation.externalConfig.ExternalConfig;
	import com.constellation.managers.loggingManager;
	import com.constellation.view.CircleSlicePreloader;
	import com.constellation.view.camera_debug;
	import com.constellation.view.hostCameraView;
	import com.constellation.view.messageView;
	import com.constellation.view.viewerCameraView;
	import com.sierrastarstudio.utils.tracer;
	
	import flash.display.Bitmap;
	import flash.display.Sprite;
	import flash.display.StageAlign;
	import flash.display.StageDisplayState;
	import flash.display.StageScaleMode;
	import flash.events.ContextMenuEvent;
	import flash.events.Event;
	import flash.events.KeyboardEvent;
	import flash.events.MouseEvent;
	import flash.events.TimerEvent;
	import flash.external.ExternalInterface;
	import flash.media.Camera;
	import flash.system.Security;
	import flash.system.SecurityPanel;
	import flash.text.AntiAliasType;
	import flash.text.TextField;
	import flash.text.TextFieldAutoSize;
	import flash.text.TextFormat;
	import flash.ui.ContextMenu;
	import flash.ui.ContextMenuItem;
	import flash.ui.Mouse;
	import flash.utils.Timer;
	
	import org.casalib.display.CasaTextField;
	
	[SWF(backgroundColor="0x000000", frameRate="24", width="500", height="375")]
	
	public class constellation_as3 extends Sprite
	{
		[Embed(source="../graphics/constellation_player_fullscreen.png")]
		private var fullscreenImg:Class;
		//font
		[Embed(source='../graphics/fonts/HelveticaNeue-Medium.otf', fontFamily="helvNeuCond", embedAsCFF="false")]
		
		public var helvNeuCond:Class;
		
		private var _classname:String = "constellation_main";
		
		
		private var _heartbeating:Boolean = true;
		
		
		
		// Are we in testing mode?
		private var _testLocal:Boolean;
		
	
		private var _originalTicketParam:String;
		//encoded string to prepData to send to service for auth			
		private var _prepData:String;
	//camera views
		private var _hostCameraView:hostCameraView;
		private var _viewerCameraView:viewerCameraView;
	//display objects
		private var _fullscreenImg:Sprite;
		private var _messageWindow:Sprite;
		private var _mouseMoveTimer:Timer;
		//circle preloader
		private var _preloader:CircleSlicePreloader
		
		private var _statusTextField:CasaTextField;
		private var _restartTimer:Timer;
		private var _contextMenu:ContextMenu;
		
		private var _charsTyped:String = "";
		private var _cameraDebugPanel:camera_debug;
		
		public function constellation_as3()
		{
			if(this.stage){
				this.init();
			}else{
				this.addEventListener(Event.ADDED_TO_STAGE,init); 
			}
			
		}
		
		private function init(evt:Event=null):void
		{
			Security.allowDomain("*.constellation.tv");
			
			this.stage.scaleMode = StageScaleMode.NO_SCALE;
			this.stage.align = StageAlign.TOP_LEFT;
			
			this.stage.addEventListener(Event.RESIZE,onResize);
			this.removeEventListener(Event.ADDED_TO_STAGE,init);
			ExternalConfig.getInstance().loadOptions(this);
			loggingManager.getInstance().loadLoggingPath();
			
			this._testLocal = ExternalConfig.getInstance().testLocal;
			
			
			ExternalInterfaceController.getInstance().loadExternalInterface();
			ExternalInterfaceController.getInstance().addEventListener(constellationEvent.SHOW_HOST_CAMERA,onLoadHostCamera);
			ExternalInterfaceController.getInstance().addEventListener(constellationEvent.HIDE_HOST_CAMERA,onHideHostCamera);
			ExternalInterfaceController.getInstance().addEventListener(constellationEvent.SHOW_CAMERA_VIEWER,onShowViewerCamera);
			ExternalInterfaceController.getInstance().addEventListener(constellationEvent.HIDE_CAMERA_VIEWER,onHideViewerCamera);
			
			this.stage.addEventListener(KeyboardEvent.KEY_DOWN, onKeyboardDown, false, 0, true);
			 //preloader
			this._preloader = new CircleSlicePreloader();
			this.addChild(this._preloader);
			this.addContextMenu();
		
			this.onLoadHostCamera(null);
		//			this.onShowViewerCamera(null);
			//showDebugPanel();
			this._fullscreenImg = new Sprite();
			this._fullscreenImg.addChild(new fullscreenImg());
			this._fullscreenImg.cacheAsBitmap = true;
			//this._fullscreenImg.addEventListener(MouseEvent.CLICK,makeFullScreen);
			this._fullscreenImg.buttonMode = true;
			this._fullscreenImg.mouseEnabled = true;
			this._fullscreenImg.alpha = 0;
			this.addChild(this._fullscreenImg);
			this.onResize() 
		}
		public function onKeyboardDown(e:KeyboardEvent):void
		{
			
			var character:String = String.fromCharCode(e.charCode);
			
			_charsTyped += character;
			
			tracer.log("chars typed "+_charsTyped,_classname);
			if (_charsTyped.substr(-5) == "debug")
			{
				
				ExternalConfig.getInstance().showDebugPanel = true;
				addContextMenu("debug");
				//extended base debugPanelView for more specific store debugging
			}
			if (_charsTyped.substr(-7) == "update0")
			{
				
				var cam:Camera = Camera.getCamera();
				cam.setQuality(cam.bandwidth, 1);
			
				//extended base debugPanelView for more specific store debugging
			}
			if (_charsTyped.substr(-7) == "update1")
			{
				
				var cam:Camera = Camera.getCamera();
				cam.setQuality(cam.bandwidth, 100);
				
				//extended base debugPanelView for more specific store debugging
			}
		}
		protected function onHideViewerCamera(event:Event):void
		{
			if(this._viewerCameraView!=null){
				this._viewerCameraView.visible = false;
				try{
					this._viewerCameraView.destroy();
				}catch(err:Error){
					tracer.log("viewer not present to removeChild properly",_classname);
				}
			}
		}
		
		protected function onShowViewerCamera(event:Event):void
		{
			if(this._viewerCameraView==null){
				//viewer camera
				tracer.log("creating viewer camera object",_classname);
				this.createViewer();
			}else{
				
				
			}
			
		}
		private function createViewer(evt:Event=null):void{
			this._viewerCameraView = new viewerCameraView();
			this._viewerCameraView.addEventListener(constellationEvent.CAMERA_READY,onCameraReady);
			this._viewerCameraView.addEventListener(constellationEvent.CAMERA_NO_HOST,onCameraNoHost);
			this._viewerCameraView.addEventListener(constellationEvent.CAMERA_LOST_HOST,onCameraLostHost);
			this.addChild(this._viewerCameraView);
			this._viewerCameraView.createCameraViewer();
		}
		protected function onCameraLostHost(event:constellationEvent):void
		{
			this._viewerCameraView.destroy();
			var restartTimer:Timer = new Timer(5000,1);
				restartTimer.addEventListener(TimerEvent.TIMER,restartViewerJS)
				restartTimer.start();
			this.showStatusMessage(ExternalConfig.getInstance().lostHostMessage);
			
		}
		
		protected function restartViewerJS(event:Event=null):void
		{
			ExternalInterfaceController.getInstance().restartViewerJS();
			
		}
		protected function onCameraNoHost(event:constellationEvent):void
		{
				this.showStatusMessage(ExternalConfig.getInstance().noHostMessage);
		
		}
		private function showStatusMessage(msg:String):void{
			if(this._preloader!=null){
				this._preloader.destroy();
			}
				if(this._statusTextField==null){
				this._statusTextField = new CasaTextField();
				this._statusTextField.embedFonts = true;
				this._statusTextField.antiAliasType = AntiAliasType.ADVANCED;
				
				this._statusTextField.width = 200;
				this._statusTextField.height = 150;
				this._statusTextField.multiline = true;
				this._statusTextField.wordWrap = true;
			//	this._statusTextField.border = true;
			//	this._statusTextField.borderColor  = 0xffffff
				this._statusTextField.htmlText = msg;
				var newTF:TextFormat = new TextFormat();
					newTF.font = "helvNeuCond"
					newTF.color = ExternalConfig.getInstance().statusTextColor;
					//		newTF.align = TextFormatAlign.LEFT
					newTF.size = 18
				this._statusTextField.setTextFormat(newTF);
				this.addChild(this._statusTextField);
				this._statusTextField.x = this.stage.stageWidth/2-this._statusTextField.width/2;
				this._statusTextField.y = this.stage.stageHeight/2-this._statusTextField.height/2;
				//this._statusTextField.autoSize = TextFieldAutoSize.CENTER;
			}
			
			
			
		}
		
		protected function onHideHostCamera(event:Event):void
		{
			try{
					if(this._hostCameraView!=null){
						this._hostCameraView.visible = false;
						this._hostCameraView.destroy();
					//	this.removeChild(this._hostCameraView);
					}
			}catch(err:Error){
				tracer.log("HIDE HOST CAMERA - Child is not there",_classname);
			}
		}
		
		protected function onLoadHostCamera(evt:constellationEvent):void
		{
			//do we need host cam?
			if(this._hostCameraView==null){
				this._hostCameraView = new hostCameraView();
				this._hostCameraView.addEventListener(constellationEvent.CAMERA_READY,onCameraReady);
				this.addChild(this._hostCameraView);
				this._hostCameraView.startHostCamera();
			}else{
				this._hostCameraView.visible = false;
				this._hostCameraView.destroy();
			}
		
			
		}
		
		private function onCameraReady(evt:constellationEvent):void
		{
			this._preloader.destroy();
			if(this._statusTextField!=null){
				this._statusTextField.destroy();
			}
		}
		
		protected function onResize(event:Event=null):void
		{
			this._fullscreenImg.x = 10;
			this._fullscreenImg.y = this.stage.stageHeight -this._fullscreenImg.height;
			
			this._preloader.x = this.stage.stageWidth/2 - this._preloader.width/2;
			this._preloader.y = this.stage.stageHeight/2 - this._preloader.height/2
		
		}
		public function addContextMenu(type:String="standard"):void
		{
		
			
			_contextMenu = new ContextMenu();
			_contextMenu.hideBuiltInItems();
			tracer.log("[MP]~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~"+_contextMenu, _classname) ;
			var idItem:ContextMenuItem = new ContextMenuItem("Constellation.tv Camera Viewer v: " + ExternalConfig.getInstance().version, true);
			_contextMenu.customItems.push(idItem); //7
			//idItem.addEventListener( ContextMenuEvent.MENU_ITEM_SELECT , idItemSelectHandler );
		if(type=="debug"){
			if(this._hostCameraView){
			var debugShowPanel:ContextMenuItem = new ContextMenuItem("Show Debug Panel", true, true);
				debugShowPanel.addEventListener(ContextMenuEvent.MENU_ITEM_SELECT, showDebugPanel);
			var debugHidePanel:ContextMenuItem = new ContextMenuItem("hide Debug Panel", true, true);
				debugHidePanel.addEventListener(ContextMenuEvent.MENU_ITEM_SELECT, hideDebugPanel);
			
				
				_contextMenu.customItems.push(debugShowPanel); //7
				_contextMenu.customItems.push(debugHidePanel); //7
			}
			var restartViewTest:ContextMenuItem = new ContextMenuItem("restartViewTest", true, true);
				restartViewTest.addEventListener(ContextMenuEvent.MENU_ITEM_SELECT, restartViewerJS);
			_contextMenu.customItems.push(restartViewTest); //7
		}
			this.contextMenu = this._contextMenu;
			
			
			
		}
		
		protected function hideDebugPanel(event:ContextMenuEvent=null):void
		{
			if(this._cameraDebugPanel){
				this._cameraDebugPanel.destroy();
			}
		}
		
		protected function showDebugPanel(evt:ContextMenuEvent=null):void
		{
			if(this._cameraDebugPanel){
				this._cameraDebugPanel.destroy();
			}
			this._cameraDebugPanel = new camera_debug();
			if(this._hostCameraView){
				this._cameraDebugPanel.addCamera(this._hostCameraView.camera);
			}
			this.addChildAt(this._cameraDebugPanel,this.numChildren-1);
		}		
		
		
	
		
	
	
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	}
}