package com.constellation
{
	/**
	 * @author Andy Madsen	 
	 *
	 * history: 04 May 2011 - Removed unused properties from initial LOG Class
	 *                         Authored by Bruce Epstein (com.Zeusprod.Log)	 
	 *          	 
	 * example usage - displaying a basic text message (priority defaults to Log.NORMAL):
	 * 		import com.constellation.Stats;
	 * 		Stats.displayMsg("some message");
	 
	 * example LOG_TO_CONSOLE usage - requires a reference to the stage for displaying the console and must turn on logging:
	 * 		import com.constellation.Stats;
	 * 		Stats.init (stage);		// Must invoke init() and pass it a stage reference (at least once)
	 *		Stats.startup( $somestring );		// Start showing the Watermark, passed as $somestring
	 *		// Change the watermark location and size by setting the setSize property (pass it a Rectangle with x, y, width, and height)
	 *		Stats.setSize = new Rectangle (100, 400, 300, 150);
	 */
	
	import com.zeusprod.Log;
	
	import flash.display.Stage;
	import flash.display.Sprite;
	import flash.text.TextField;
	import flash.text.TextFormat;
	import flash.geom.Rectangle;
	import flash.utils.Timer;
	import flash.events.TimerEvent;
	
	public class Stats
	{
		private static var _stageReference:Stage;
		private static var statsBox:Sprite;
		private static var statsField:TextField;
		public static var _statsText:String;
		public static var _displayTimer:Timer;
		public static var _blinkTimer:Timer;
		
		public function Stats()
		{
			trace("You should never construct the Stats class");
		}
		
		public static function init(stageReference:Stage):void {
			_stageReference = stageReference;
			_statsText = "Stats";
			showStats();
		}
		
        public static function startup(srcVal:String):void {
            _statsText = srcVal;
		    Log.traceMsg ("Starting Watermark with String: " + _statsText, Log.LOG_TO_CONSOLE);
			
            if (statsField == null) {
				var tf:TextFormat = new TextFormat();
				tf.color = 0xffffff;
				tf.size = 10;
				tf.font = "Arial";
         		
                 // output TextField
				statsField = new TextField();
				statsField.text = _statsText;
				statsField.setTextFormat (tf);
				statsField.x = 10;
				statsField.y = 35;
				statsField.width = 100;
				statsField.height = 20;
				statsField.multiline = false;
				statsField.wordWrap = false;
				statsField.border = true;
				statsField.background = false;
				//consoleField.type = TextFieldType.DYNAMIC;
				
				statsBox = new Sprite();
				statsBox.addChild(statsField);
				
				//Ten second timer
			}
			// Bring it to the front or add it to this swf's stage
			if (_stageReference == null) {
				trace("Stage reference must be initialised before opening console. Invoke Log.init() with a stage reference!");
			} else {
				_stageReference.addChild(statsBox);
				//statsBox.visible = true;
			}
			
		}
		
		public static function showStats():void {
			/*
            //Log.traceMsg ("Show Stats", Log.LOG_TO_CONSOLE);
			var _theWidth:Number = _stageReference.stageWidth;
            var _theXPos:Number = Math.floor(Math.random() * (1+(_theWidth-100)));
			//Log.traceMsg ("Stage Width: " + _theWidth, Log.LOG_TO_CONSOLE);
			var _theHeight:Number = _stageReference.stageHeight;
            var _theYPos:Number = Math.floor(Math.random() * (1+(_theHeight-10)));
			//Log.traceMsg ("Stage Height: " + _theHeight, Log.LOG_TO_CONSOLE);
			setSize = new Rectangle(_theXPos, _theYPos, 100, 20);
            */
            if (statsBox) {
				statsBox.visible = true;
			}
		}
		
		public static function hideStats():void {
			if (statsBox) {
				statsBox.visible = false;
			}
		}
		
	    public function get statsText():String {
			return _statsText;
		}
		
		public function set statsText(srcVal:String):void {
			_statsText = srcVal;
			Log.traceMsg ("Setting '_statsText': " + srcVal, Log.LOG_TO_CONSOLE);
		}
		
		public static function displayMsg (msg:*):void 
		{
			if (!(msg is String)) {
				msg = String(msg);
				//trace("Avoid tracing an integer alone; You should concatenate it with a string:" + msg);
				//return;
			}
 			
			if (statsField) {
				statsField.text = msg;
			}
    		return;
		}
		
	}
}
