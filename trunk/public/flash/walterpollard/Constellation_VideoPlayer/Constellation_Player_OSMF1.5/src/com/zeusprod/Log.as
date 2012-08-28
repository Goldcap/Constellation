package com.zeusprod
{
	/**
	 * @author Bruce Epstein
	 *
	 * history: 26 February 2010 - initial version committed
	 *			05 April 2010 - added console logging support
	 * 
	 * example usage - displaying a basic text message (priority defaults to Log.NORMAL):
	 * 		import com.zeusprod.Log;
	 * 		Log.traceMsg ("some message);
	 *
	 * example usage - displaying a text message with a different priority level (in this case, a warning):
	 *		import com.zeusprod.Log;
	 * 		Log.traceMsg ("some warning message", Log.WARN);
	 *		// Setting the traceThreshold suppresses messages below the threshold level.
	 * 		Log.traceThreshold = Log.WARN;		// Show only warning level or higher
	 * 		Log.traceMsg ("low priority message not shown if traceThreshold is higher", Log.NORMAL);
	 *
	 * example ALERT usage - requires a reference to the stage for posting the alert dialog box:
	 * 		import com.zeusprod.Log;
	 * 		Log.init (stage);		// Must invoke init() and pass it a stage reference (at least once)
	 * 		Log.traceMsg ("Some alert text", Log.ALERT);
	 *
	 * example LOG_TO_CONSOLE usage - requires a reference to the stage for displaying the console and must turn on logging:
	 * 		import com.zeusprod.Log;
	 * 		Log.init (stage);				// Must invoke init() and pass it a stage reference (at least once)
	 *		Log.consoleLogging = true;		// Turn on console logging
	 * 		Log.traceMsg ("Some text to display in the log console", Log.LOG_TO_CONSOLE);
	 *		// Change the console location and size by setting the consoleRect property (pass it a Rectangle with x, y, width, and height)
	 *		Log.consoleRect = new Rectangle (100, 400, 300, 150);
	 */
	/**
	 * NOTES:
	 * This utility replaces the basic trace() method so that all logging is centralized.
	 * Note that it doesn't accept the same parameters as trace(), which outputs a comma-separated list of arguments.
	 * Second parameter to traceMsg() is a priority threshold. Use Log.traceThreshold to change the verbosity of the output.
	 * Optional logging to server requires call to setServerLogging(url) to specify remote url to receive log messages.
	 * 
	 * FIXME -
	 * Logging to a local file not yet supported (requires AIR classes)
	*/
	import com.dVyper.utils.Alert;
	
	import flash.display.Sprite;
	import flash.display.Stage;
	import flash.events.Event;
	import flash.events.IOErrorEvent;
	import flash.events.MouseEvent;
	import flash.geom.Rectangle;
	import flash.net.URLLoader;
	import flash.net.URLRequest;
	import flash.net.URLRequestMethod;
	import flash.net.URLVariables;
	import flash.text.TextField;
	import flash.text.TextFormat;
	
	public class Log
	{
		private static var alertCount:int = 0;
		private static var  SHOW_ALERTS:Boolean = true;
		private static var  SHOW_DEBUG_ALERTS:Boolean = true;
		//private static var 	LOG_FILE:File = "logfile.txt"; //File.applicationStorageDirectory.resolvePath("logfile.txt");
		
		public static const NO_LOG:int		= 12;	// Avoid writing to log regardless of threshold
		public static const LOG_TO_SERVER:int = 11;	// Write to log on server only regardless of _serverLogThreshold but obeying _serverLogging
		public static const LOG_TO_CLIENT:int = 10;	// Write to log on client only regardless of _clientLogThreshold but obeying _clientLogging
		public static const LOG_TO_CS:int  	= 9;	// Write to log on client and server regardless of threshold
		public static const LOG_TO_CONSOLE:int = 9;	// Write to onscreen console textfield
		public static const EXCLUDE:int  	= 8;	// Nothing gets through
		public static const ALERT:int 	 	= 7;	// Displays result on screen
		public static const ALERT_DEBUG:int = 6;	// Reminder for later without displaying results on screen
		public static const ERROR:int 	 	= 5;	// Most urgent
		public static const WARN:int 	 	= 4;	// Important enough to warrant attention
		public static const NORMAL:int 	 	= 3;	// You want it to show up by default (if no priority is specified)
		public static const INFO:int   		= 2;	// Nice to know, more often than DEBUG
		public static const DEBUG:int 	 	= 1;	// Usually not needed but helpful for debugging
		public static const VERBOSE:int  	= 0;	// Mostly extraneous or lengthy (used in loops)
		public static const ESOTERIC:int 	= -1;	// Exclude except in most lenient situations
		// Consider allowing these settings to be overridden by a config.xml
		private static var _traceThreshold:int = NORMAL;	
		public static var _clientLogThreshold:int = WARN;
		public static var _serverLogThreshold:int = ERROR;
		public static var _consoleLogThreshold:int = ERROR;
		public static var _clientLogging:Boolean = false; 	// Enables logging to local logfile.txt
		public static var _serverLogging:Boolean = false;	// Enables logging to server
		public static var _consoleLogging:Boolean = false;	// Enables logging to a TextField output window
		
		private static var _serverLogUrl:String = null;		// URL of remote script to receive server log messages	
		private static var _failureCode:int = -1;
		private static var _successCode:int = 0;
		private static var _stageReference:Stage;
		
		private static var consoleBox:Sprite;
		private static var consoleField:TextField;
		private static var closeBox:TextField;
		
		
		public function Log()
		{
			traceMsg("You should never construct the Log class", ERROR);
		}
		
		public static function init(stageReference:Stage):void {
			_stageReference = stageReference;
			Alert.init(_stageReference);
		}
		
		public static function get traceThreshold ():int {
			return _traceThreshold;
		}
		
		
		/**
		 *  Set traceThreshold to VERBOSE or ESOTERIC for most permissive messages.
		 *  Set traceThreshold to EXCLUDE to prevent all messages.
		 *  Set traceThreshold to ERROR to prevent all but the most important messages.
		*/
		public static function set traceThreshold (value:int):void {
			_traceThreshold = value;
		}
		
		public static function get clientLogThreshold ():int {
			return _clientLogThreshold;
		}
		
		public static function set clientLogThreshold (value:int):void {
			_clientLogThreshold = value;
		}
		
		public static function get serverLogThreshold ():int {
			return _serverLogThreshold;
		}
		
		public static function set serverLogThreshold (value:int):void {
			_serverLogThreshold = value;
		}
		
		public static function get consoleLogThreshold ():int {
			return _consoleLogThreshold;
		}
		
		public static function set consoleLogThreshold (value:int):void {
			_consoleLogThreshold = value;
		}
	
		public static function get consoleLogging ():Boolean {
			return _consoleLogging;
			
		}
		
		public static function set consoleLogging (value:Boolean):void {
			_consoleLogging = value;
			if (value == true) {
				if (consoleField == null) {
					var tf:TextFormat = new TextFormat();
					tf.color = 0x000000;
					tf.size = 10;
					tf.font = "Arial";
           			 // output TextField
					consoleField = new TextField();
					consoleField.text = "Console Log: \n\n\n";
					consoleField.setTextFormat (tf);
					consoleField.x = 10;
					consoleField.y = 35;
					consoleField.width = 150;
					consoleField.height = 100;
					consoleField.multiline = true;
					consoleField.wordWrap = false;
					consoleField.border = true;
					consoleField.background = true;
					//consoleField.type = TextFieldType.DYNAMIC;
					
					closeBox = new TextField();
					closeBox.text = "X";
					closeBox.setTextFormat (tf);
					closeBox.width = 10;
					closeBox.height = 20;
					closeBox.x = consoleField.x + consoleField.width - closeBox.width - 5;
					closeBox.y = consoleField.y;
					closeBox.multiline = false;
					closeBox.wordWrap = false;
					closeBox.border = true;
					closeBox.background = true;
					//closeBox.type = TextFieldType.DYNAMIC;
					closeBox.selectable = false;
					closeBox.addEventListener(MouseEvent.CLICK, hideConsole);
					
					
					consoleBox = new Sprite();
					consoleBox.addChild(consoleField);
					consoleBox.addChild(closeBox);
				}
				// Bring it to the front or add it to this swf's stage
				if (_stageReference == null) {
					traceMsg("Stage reference must be initialised before opening console. Invoke Log.init() with a stage reference!", Log.ERROR);
				} else {
					_stageReference.addChild(consoleBox);
					consoleBox.visible = true;
				}
			}
			
		}
		
		private static function hideConsole(evt:MouseEvent):void {
			if (consoleBox) {
				consoleBox.visible = false;
			}
		}
	
		// Set the console location as a rectangle, such as:
		// Log.consoleRect = new Rectangle (100, 400, 300, 150);
		public static function set consoleRect(val:Rectangle):void {
			if (consoleField && closeBox) {
				consoleField.width = val.width;
				consoleField.height = val.height;
				consoleField.x = val.left;
				consoleField.y = val.top;
				closeBox.x = consoleField.x + consoleField.width - closeBox.width - 5;
				closeBox.y = consoleField.y;
			}
		}
		
		public static function get clientLogging ():Boolean {
			return _clientLogging;
		}
		
		public static function set clientLogging (value:Boolean):void {
			_clientLogging = value;
		}
		
		public static function get serverLogging ():Boolean {
			return _serverLogging;
		}
		
		public static function set serverLogging (value:Boolean):void {
			_serverLogging = value;
		}
		
		public static function get serverLogUrl ():String {
			return _serverLogUrl;
		}
		
		public static function set serverLogUrl (value:String):void {
			serverLogging = true;
			_serverLogUrl = value;
		}
		
		public static function setServerLogging (url:String, failureCode:int, successCode:int):void {
			serverLogUrl = url;
			serverLogging = true;
			_failureCode = failureCode;
			_successCode = successCode;
		}
		
		
		// traceMsgByCode() gives you a way to look up a localized version of the error text by its error code.
		// This is not presently implemented.
		public static function traceMsgByCode(msgCode:String = "0", msgPriority:int=NORMAL):void {
			traceMsg ("Need to lookup code " + msgCode, msgPriority);
		}
		
		public static function traceMsg (msg:*, msgPriority:int=NORMAL, msgCode:String = "0"):void 
		{
			if (!(msg is String)) {
				msg = String(msg);
				traceMsg ("Avoid tracing an integer alone; You should concatenate it with a string:" + msg, Log.WARN);
				return;
			}
 			if (msgPriority >= _traceThreshold) {
    			trace( msg);
 			}
			
			if (msgPriority >= consoleLogThreshold || msgPriority == LOG_TO_CONSOLE) {
 				if (consoleLogging && msgPriority != NO_LOG) {
    				logToConsole (msg, msgPriority, msgCode);
    			}
 			}
			
 			if (msgPriority >= clientLogThreshold || msgPriority == LOG_TO_CS || msgPriority == LOG_TO_CLIENT) {
 				if (clientLogging && msgPriority != NO_LOG && msgPriority != LOG_TO_SERVER) {
    				logToFile(msg, msgPriority, msgCode);
    			}
 			}
 			
 			if (msgPriority >= serverLogThreshold || msgPriority == LOG_TO_CS || msgPriority == LOG_TO_SERVER) {
 				if (serverLogging && msgPriority != NO_LOG && msgPriority != LOG_TO_CLIENT) {
    				logToServer(msg, msgPriority, msgCode);
    			}
 			}
 			
 			if ((SHOW_ALERTS && msgPriority == ALERT) || (SHOW_DEBUG_ALERTS && msgPriority == ALERT_DEBUG)) {
	 			//mx.controls.Alert.okLabel = getLocalizedString("Okay");
	 			// Note, it is possible to show an ALERT_DEBUG message if SHOW_DEBUG_ALERTS
	 			// even if SHOW_ALERTS is false! This lets us turn off just what we want.
	 			if (SHOW_ALERTS && msgPriority == ALERT) {
					showNextAlert(msg);
					
	 			} else if (SHOW_DEBUG_ALERTS && msgPriority == ALERT_DEBUG) {
					showNextAlert(msg);
	 			}
	 			return;
	 		} else {
	 			return;
	 		}
		}
		
		
		public static function alert (msg:String, alertOptions:*=null):void {
			if (alertOptions is Number) {
				Log.traceMsg ("ERROR: Specified wrong type of alertOptions to Log.ALERT", Log.LOG_TO_CONSOLE);
				Alert.show(msg, null);
			} else {
				Alert.show(msg, alertOptions);
			}
		}
		
		
		private static function showNextAlert(msg:String):void {
			alertCount++;
	 		Alert.show ("Alert " + alertCount + ": " + msg);
		}
		
		 public static function postDialog (msg:String, titleText:String, closeHandler:Function):void {
 			var flags:uint;
 			//flags = mx.controls.Alert.NO | mx.controls.Alert.YES;
 			
 			//Alert.CANCEL,  Alert.NONMODAL, Alert.OK,
 			var defaultButtonFlag:uint;
 			//defaulButtonFlag = mx.controls.Alert.NO;
 			var parentSpr:Sprite = null;
 			var iconClass:Class = null;
 			// Translate the message and title text, or pass in a custom string.
 			//msg 	  = (TextUtils.undefinedOrEmpty(getLocalizedString(msg)))		? msg 		: getLocalizedString(msg);
 			//titleText = (TextUtils.undefinedOrEmpty(getLocalizedString(titleText))) ? titleText : getLocalizedString(titleText);
 			msg 	  = (undefinedOrEmpty(getLocalizedString(msg)))		? msg 		: getLocalizedString(msg);
 			titleText = (undefinedOrEmpty(getLocalizedString(titleText))) ? titleText : getLocalizedString(titleText);
 			//mx.controls.Alert.okLabel = getLocalizedString("Okay");
 			//mx.controls.Alert.cancelLabel = getLocalizedString("Cancel");
 			//mx.controls.Alert.noLabel  = getLocalizedString("No");
 			//mx.controls.Alert.yesLabel = getLocalizedString("Yes");
 			//mx.controls.Alert.show(msg, titleText, flags, parentSpr, closeHandler, iconClass, defaultButtonFlag);
			com.dVyper.utils.Alert.show(msg);
		}
		
		
		private static function logToConsole (msg:String, msgPriority:int, msgCode:String):void {
			if (consoleField) {
				consoleField.appendText (msg + "\n");
				consoleField.scrollV = consoleField.maxScrollV;
			}
		}
		
		
		private static function logToFile (msg:String, msgPriority:int, msgCode:String):void {
			// FIXME - disabled for now because it worsk in AIR only.
			return;
			/*
			var logStream:FileStream;
			var now:Date = new Date();
				
			if (!LOG_FILE.exists) {
 				traceMsg("Log file doesn't exist " + LOG_FILE.nativePath + " so we'll create it.", NO_LOG);
 			}
			logStream = new FileStream();
			logStream.addEventListener(IOErrorEvent.IO_ERROR, logFileError);
			try {
				logStream.open( LOG_FILE, FileMode.APPEND );
			} catch (err:Error) {
				traceMsg("CATCH: Error logging to file: " + err.errorID + ": " + err.message, NO_LOG);
 				return;
			}
			
			// Writes a UTF-8 string to the file stream. The length of the UTF-8 string, in bytes,
			// is written first, as a 16-bit integer, followed by the bytes 
			// representing the characters of the string.
			logStream.writeMultiByte("LOG: " + now.toDateString() + " " +  now.toTimeString() + 
								"; priority: " + msgPriority + "; code: " + msgCode + "; msg: " + msg + 
								"\n", File.systemCharset);
			logStream.close();
			*/
		}
		
		
		private static function logFileError(evt:IOErrorEvent):void {
			traceMsg("Error writing to log file" + evt.text, NO_LOG);
			Alert.show ("Error writing to log file" + evt.text);
		}
		
		
		private static function logToServer (msg:String, msgPriority:int, msgCode:String):void {
			// FIXME - check if connection exists...
			/*
			if (monitor == null || !monitor.available) {
				traceMsg ("Not logging to server because there is no active connection: " + msg, NO_LOG);
				return;
			}
			*/
			
			//trace ("Log this to server in xml post format" + msg);
			var url:String = _serverLogUrl;
			if (!url) {
				traceMsg ("Not logging to server because server url has not been set: " + msg, NO_LOG);
				return;
			}
            var request:URLRequest = new URLRequest(url);
            var variables:URLVariables = new URLVariables();
            var loader:URLLoader = new URLLoader();
            
            variables.msg = escape(msg);
            variables.priority = msgPriority;
            variables.code = msgCode;
            request.data = variables;
            request.method = URLRequestMethod.POST;
            
			loader.addEventListener(Event.COMPLETE, onServerLogCheckSuccess);
			loader.addEventListener(IOErrorEvent.IO_ERROR, onServerLogError);
			loader.load(request);
		}
		
		protected static function onServerLogCheckSuccess(evt:Event):void {
			if ( evt.target.data == _successCode ) {
				//traceMsg ("Succesfully logged info to server", NO_LOG);
			} else {
				var stringMe:String = evt.target.data as String;
				// Problem logging to server. Perhaps a 500 Internal Server Error...
				traceMsg ("onServerLogCheckSuccess: Failed to log to server: " + evt.type + ": " + 
						stringMe.substr(0, Math.min(100, stringMe.length)), LOG_TO_CLIENT, "SL1"); // "APE
			}
		}
		
		protected static function onServerLogError(evt:IOErrorEvent):void {
			traceMsg ("Failed to log info to server: " + evt.text, LOG_TO_CLIENT, "SL2");  // "APE
		}
		
		private static function getLocalizedString(inString:String):String {
			// Dummy for now, always returns same string.
			// TODO - implement localization framework
			return inString;
		}
		
		// TODO - move to TextUtils class
		private static function undefinedOrEmpty(inString:String):Boolean {
			if (inString == "" || inString == null) {
				return true;
			} else {
				return false;
			}
		}

	}
}
