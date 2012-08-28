
package  
{
	import flash.display.MovieClip;
	import flash.events.MouseEvent;
	
	import fl.transitions.Tween;
	import fl.transitions.easing.*;
	
	public class AlertPanel extends MovieClip
	{
		private var _enable:Boolean = false;
		
		public function AlertPanel() 
		{
			//trace("AlertPanel");
			this.alpha = 0;
			title.selectable = false;
			
			okButton.addEventListener(MouseEvent.CLICK, onOkClickHandler);
			
			clearPanel();
			enable = false;
		}
		
		private function onOkClickHandler(event:MouseEvent):void
		{
			//trace(".onPlayClickHandler");
			hidePanel();
			clearPanel();
		}
		
		private function clearPanel():void
		{
			title.text = "";
			message.text = "";
		}
		
		private function set enable(val:Boolean):void
		{
			message.selectable = val;
			mouseChildren = val;
			mouseEnabled = val;
			_enable = val
		}
		
		public function showPanel():void
		{
			if (!_enable)
			{
				//trace(">> showPanel");
				var showTween:Tween = new Tween(this, "alpha", Strong.easeOut, 0, 1, 3, true);
				enable = true;
			}
		}
		
		public function hidePanel():void
		{
			if (_enable)
			{
				//trace(">> hidePanel");
				var hideTween:Tween = new Tween(this, "alpha", Strong.easeOut, 1, 0, 3, true);
				enable = false;
			}
		}
		
		// public methods for updating ui
		
		public function alert(ttl:String,msg:String):void
		{
			//trace(".alert");
			title.text = ttl;
			message.text = msg;
			showPanel();
		}
		
	}
}