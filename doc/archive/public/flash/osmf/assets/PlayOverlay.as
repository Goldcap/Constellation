
package  
{
	import flash.display.MovieClip;
	import flash.events.MouseEvent;
	
	import fl.transitions.Tween;
	import fl.transitions.easing.*;
	
	import com.limelight.osmf.events.ControlBarEvent
	
	public class PlayOverlay extends MovieClip
	{
		private var _enable:Boolean = false;
		
		public function PlayOverlay() 
		{
			//trace("PlayOverlay");
			this.alpha = 0;
			enable = false;
		}
		
		private function set enable(val:Boolean):void
		{
			mouseChildren = val;
			mouseEnabled = val;
			_enable = val
		}
		
		private function onPlayClickHandler(event:MouseEvent):void
		{
			//trace(".onPlayClickHandler");
			if (_enable)
			{
				dispatchEvent(new ControlBarEvent(ControlBarEvent.PLAY));
			}
		}
		
		public function paused():void
		{
			if (!_enable)
			{
				//trace("paused");
				var showTween:Tween = new Tween(this, "alpha", Strong.easeOut, 0, 1, 1, true);
				enable = true;
				stage.addEventListener(MouseEvent.MOUSE_UP,onPlayClickHandler);
			}
		}
		
		public function playing():void
		{
			if (_enable)
			{
				//trace("playing");
				var hideTween:Tween = new Tween(this, "alpha", Strong.easeOut, 1, 0, 1, true);
				enable = false;
				stage.removeEventListener(MouseEvent.MOUSE_UP,onPlayClickHandler);
			}
		}

	
	}
}