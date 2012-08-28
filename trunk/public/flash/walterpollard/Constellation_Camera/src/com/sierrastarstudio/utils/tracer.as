
package com.sierrastarstudio.utils
{
	import com.constellation.events.constellationEvent;
	import com.constellation.externalConfig.ExternalConfig;
	import com.constellation.managers.loggingManager;
	
	import flash.utils.getTimer;
	
	import org.osmf.logging.Logger;

	
	/**
	 * Static class trace in a cleaner format tailer to your needs<br>
	 * Current output: [time][classname]+message
	 * 
	 * @example Use static function log
	 * 	tracer.log("message","classname")
	 * 
	 * 
	 * @author Walter Steve Pollard Jr. walter.pollard.jr@gmail.com
	 * 
	 */
	public class tracer extends Logger
	{
		private var classname:String = "com.sierrastarstudio.utils.tracer";
		
		public function tracer(category:String)
		{
			super(category);
		}
		override public function debug(message:String, ...rest):void
		{
			logMessage(LEVEL_DEBUG, message, rest);
		}
		
		/**
		 * @private
		 */
		override public function info(message:String, ...rest):void
		{
			logMessage(LEVEL_INFO, message, rest);
		}
		
		/**
		 * @private
		 */
		override public function warn(message:String, ...rest):void
		{
			logMessage(LEVEL_WARN, message, rest);
		}
		
		/**
		 * @private
		 */
		override public function error(message:String, ...rest):void
		{
			logMessage(LEVEL_ERROR, message, rest);
		}
		public static function externalLog(msg:String):void{
			
		
		}
		protected function logMessage(level:String, message:String, params:Array):void
		{
			var msg:String = "";
			
			// add datetime
			msg += new Date().toLocaleString() + " [" + level + "] ";
			
			// add category and params
			msg += "[" + category + "] " + applyParams(message, params);
			
			if(ExternalConfig.getInstance().logToDB==true){
				loggingManager.getInstance().logToServer(msg,level);
			}
			// trace the message
			trace(msg);
		}
		public static function log(msg:String, classname:String):void
		{
			//return;
			//loggingManager.getInstance().tracer(level,classname,msg);
			
			var str:String;
			var targetClassName:String = "";
			var allowTrace:Boolean = true;
			if (classname != null)
			{
				targetClassName = classname.split(".").pop();
				str = "[" + getTimer() + "][" + targetClassName + "]" + msg;
			}
			
			
			if (allowTrace == true)
			{
				if(ExternalConfig.getInstance().logToDB==true){
					loggingManager.getInstance().logToServer(str);
				}
				trace(str);
			}
			
			
		}
		/**
		 * Returns a string with the parameters replaced.
		 *  
		 *  @langversion 3.0
		 *  @playerversion Flash 10
		 *  @playerversion AIR 1.5
		 *  @productversion OSMF 1.0
		 */
		private function applyParams(message:String, params:Array):String
		{
			var result:String = message;
			var numParams:int = params.length;
			
			for (var i:int = 0; i < numParams; i++)
			{
				result = result.replace(new RegExp("\\{" + i + "\\}", "g"), params[i]);
			}
			
			return result;
		}
		private static const LEVEL_DEBUG:String = "DEBUG";
		private static const LEVEL_WARN:String = "WARN";
		private static const LEVEL_INFO:String = "INFO";
		private static const LEVEL_ERROR:String = "ERROR";
		private static const LEVEL_FATAL:String = "FATAL";

	}
}