package com.limelight.osmf.chrome
{
	import com.limelight.osmf.data.DataResourceVO;
	
	import flash.display.Sprite;
	import flash.events.Event;
	import flash.events.FullScreenEvent;
	import flash.events.IOErrorEvent;
	
	public class AlertChrome extends Sprite
	{
		private var dataResourceVO:DataResourceVO;
		
		private var debugPanel:DebugChrome = new DebugChrome();
		
		private var alertPanel:AlertPanel;
		
		private var fullScreen:Boolean = false;
		
		public function AlertChrome()
		{
			this.mouseEnabled = false;
			
			this.addEventListener(Event.ADDED_TO_STAGE,setupAlertPanel)
		}

		private function setupAlertPanel(event:Event):void
		{
			dataResourceVO = DataResourceVO.getInstance();
			debugPanel = dataResourceVO.debugPanel;
			
			alertPanel = new AlertPanel();
			addChild(alertPanel);
			
			stage.addEventListener(FullScreenEvent.FULL_SCREEN,onFullscreenChange);
			positionAlertPanel();
		}
		
		private function onLoadError(event:IOErrorEvent):void
		{
			trace(event);
		}
		
		private function positionAlertPanel():void
		{
			if ( alertPanel && stage)
			{
				alertPanel.x = (stage.stageWidth-alertPanel.width)/2
				alertPanel.y = (stage.stageHeight-alertPanel.height)/2
			}
		}
		
		public function updateDisplay():void
		{
			trace(".updateDisplay")
			if (fullScreen)
			{
				alertPanel.scaleX = stage.stageWidth / stage.fullScreenWidth;;
				alertPanel.scaleY = stage.stageHeight / stage.fullScreenHeight;
			}
			else
			{
				alertPanel.scaleX = stage.stageWidth / dataResourceVO.resizeWidth;
				alertPanel.scaleY = stage.stageHeight / dataResourceVO.resizeHeight;
			}
			positionAlertPanel();
		}
		
		private function onFullscreenChange(event:FullScreenEvent):void
		{
			//trace(".onFullscreenChange :: fullScreen = " + event.fullScreen)
			fullScreen = event.fullScreen;
			updateDisplay();
		}
		
		public function alert(title:String,message:String):void
		{
			if (alertPanel)
			{
				Object(alertPanel).alert(title,message);
			}
			debugPanel.debug(title+": "+message);
		}

	}
}