package com.constellation
{
	/**
	 * @author Andy Madsen	 
	 *
	 * history: 04 May 2011 - Removed unused properties from initial LOG Class
	 *                         Authored by Bruce Epstein (com.Zeusprod.Log)	 
	 *          	 
	 * example usage - displaying a basic text message (priority defaults to Log.NORMAL):
	 * 		import com.constellation.Watermark;
	 * 		Watermark.displayMsg("some message);
	 
	 * example LOG_TO_CONSOLE usage - requires a reference to the stage for displaying the console and must turn on logging:
	 * 		import com.constellation.Watermark;
	 * 		Watermark.init (stage);		// Must invoke init() and pass it a stage reference (at least once)
	 *		Watermark.startup( $somestring );		// Start showing the Watermark, passed as $somestring
	 *		// Change the watermark location and size by setting the setSize property (pass it a Rectangle with x, y, width, and height)
	 *		Watermark.setSize = new Rectangle (100, 400, 300, 150);
	 */
	
	import com.zeusprod.Log;
	
	import flash.display.Sprite;
	import flash.display.Stage;
	import flash.events.TimerEvent;
	import flash.geom.Rectangle;
	import flash.text.TextField;
	import flash.text.TextFieldAutoSize;
	import flash.text.TextFormat;
	import flash.utils.Timer;
	
	public class Watermark
	{
		private static var _stageReference:Stage;
		private static var waterBox:Sprite;
		private static var waterField:TextField;
		public static var _waterText:String;
		public static var _displayTimer:Timer;
		public static var _blinkTimer:Timer;
		
		public function Watermark()
		{
			trace("You should never construct the Watermark class");
		}
		
		public static function init(stageReference:Stage,displayInterval:Number):void {
			_stageReference = stageReference;
			_waterText = "Watermark";
			startDisplayTimer(displayInterval);
		}
		
		public static function startDisplayTimer(displayInterval:Number):void {
            //Ten Second Display
            _displayTimer = new Timer(displayInterval);
			_displayTimer.addEventListener(TimerEvent.TIMER, Watermark.endWatermark);
        }
        
        public static function startTimer():void {
            //Ten Second Display
            var _when:Number = Math.floor(Math.random() * (1+120000-60000)) + 60000;
            Log.traceMsg ("Watermark Interval: " + _when, Log.LOG_TO_CONSOLE);
            _blinkTimer = new Timer(_when);
			_blinkTimer.addEventListener(TimerEvent.TIMER, Watermark.blinkWatermark);
			_blinkTimer.start();
        }
        
        public static function startup(srcVal:String):void {
            _waterText = srcVal;
		    Log.traceMsg ("Starting Watermark with String: " + _waterText, Log.LOG_TO_CONSOLE);
			
            if (waterField == null) {
				var tf:TextFormat = new TextFormat();
				tf.color = 0x333333;
				tf.size = 15;
				tf.font = "Arial";
         		
                 // output TextField
				waterField = new TextField();
				waterField.text = _waterText;
				waterField.setTextFormat (tf);
				waterField.autoSize = TextFieldAutoSize.CENTER;
				waterField.x = 10;
				waterField.y = 35;
				waterField.width = 100;
				waterField.height = 20;
				waterField.multiline = false;
				waterField.wordWrap = false;
				waterField.border = false;
				waterField.background = false;
				//consoleField.type = TextFieldType.DYNAMIC;
				
				waterBox = new Sprite();
				waterBox.addChild(waterField);
				
				//Ten second timer
			}
			// Bring it to the front or add it to this swf's stage
			if (_stageReference == null) {
				trace("Stage reference must be initialised before opening console. Invoke Log.init() with a stage reference!");
			} else {
				_stageReference.addChild(waterBox);
				waterBox.visible = false;
			}
			
		}
		
		public static function showWatermark():void {
		//	Log.traceMsg ("Show Watermark", Log.LOG_TO_CONSOLE);
			var _theWidth:Number = _stageReference.stageWidth;
            var _theXPos:Number = Math.floor(Math.random() * (1+(_theWidth-100)));
		//	Log.traceMsg ("Stage Width: " + _theWidth, Log.LOG_TO_CONSOLE);
			var _theHeight:Number = _stageReference.stageHeight;
            var _theYPos:Number = Math.floor(Math.random() * (1+(_theHeight-10)));
		//	Log.traceMsg ("Stage Height: " + _theHeight, Log.LOG_TO_CONSOLE);
			setSize = new Rectangle(_theXPos, _theYPos, 100, 20);
            if (waterBox) {
				waterBox.visible = true;
				_displayTimer.start();
			}
		}
		
		public static function endWatermark(event:TimerEvent):void {
			_displayTimer.stop();
            hideWatermark();
		}
		
		public static function hideWatermark():void {
			if (waterBox) {
				waterBox.visible = false;
			}
		}
		
		public static function blinkWatermark(event:TimerEvent):void {
			Log.traceMsg ("Blink Watermark", Log.LOG_TO_CONSOLE);
			_blinkTimer.stop();
            showWatermark();
            startTimer();
			//hideWatermark();
		}
	   
	    public function get waterText():String {
			return _waterText;
		}
		
		public function set waterText(srcVal:String):void {
			_waterText = srcVal;
			Log.traceMsg ("Setting '_waterText': " + srcVal, Log.LOG_TO_CONSOLE);
		}
		
		// Set the console location as a rectangle, such as:
		// Log.consoleRect = new Rectangle (left, top, width, height);
		public static function set setSize(val:Rectangle):void {
			if (waterField) {
				waterField.width = val.width;
				waterField.height = val.height;
				waterField.x = val.left;
				waterField.y = val.top;
			}
		}
		
		public static function displayMsg (msg:*):void 
		{
			if (!(msg is String)) {
				msg = String(msg);
				trace("Avoid tracing an integer alone; You should concatenate it with a string:" + msg);
				return;
			}
 			
			if (waterField) {
				waterField.text = msg;
			}
    		return;
		}
		
	}
}
