
package  
{
	import flash.display.MovieClip;
	import flash.events.MouseEvent;
	
	import fl.transitions.Tween;
	import fl.transitions.easing.*;
	
	public class DebugPanel extends MovieClip
	{
		private var _enable:Boolean = false;
		
		private var _log:Array = new Array();
		
		private var date:Date = new Date();
		
		public function DebugPanel() 
		{
			//trace("AlertPanel");
			this.alpha = 0;
			title.selectable = false;
			
			closeButton.addEventListener(MouseEvent.CLICK, onCloseClickHandler);
			clearButton.addEventListener(MouseEvent.CLICK, onClearClickHandler);
			
			scrollUpButton.addEventListener(MouseEvent.CLICK, onScrollUpClickHandler);
			scrollDownButton.addEventListener(MouseEvent.CLICK, onScrollDownClickHandler);
			
			clearPanel();
			enable = false;
		}
		
		private function onCloseClickHandler(event:MouseEvent):void
		{
			//trace(".onCloseClickHandler");
			hidePanel();
		}
		
		private function onClearClickHandler(event:MouseEvent):void
		{
			//trace(".onClearClickHandler");
			clearPanel();
		}
		
		private function onScrollUpClickHandler(event:MouseEvent):void
		{
			//trace(".onScrollUpClickHandler");
			message.scrollV += 1;
		}
		
		private function onScrollDownClickHandler(event:MouseEvent):void
		{
			//trace(".onScrollDownClickHandler");
			message.scrollV -= 1;
		}
		
		
		
		private function clearPanel():void
		{
			_log = new Array();
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
		
		public function debug(msg:String):void
		{
			//trace(".debug");
			var entry:String = time + " : " + msg + "\n"
			_log.push(entry);
			
			var i:int = 0;
			var l:int = _log.length;
			for (i;i<l;i++)
			{
				message.appendText(_log[i]);
			}
		}
		
		public function get log():Array
		{
			return _log;
		}
		
		private function get time():String
		{
			var hour:String;
			var minute:String;
			var second:String;
			
			if (date.hoursUTC<10)
			{
				hour = "0" + String(date.hoursUTC);
			}
			else
			{
				hour = String(date.hoursUTC);
			}
			
			if (date.minutesUTC<10)
			{
				minute = "0" + String(date.minutesUTC);
			}
			else
			{
				minute = String(date.minutesUTC);
			}
			
			if (date.secondsUTC<10)
			{
				second = "0" + String(date.secondsUTC);
			}
			else
			{
				second = String(date.secondsUTC);
			}

			var time:String = hour+":"+minute+":"+second;
			
			return time
		}
		
	}
}