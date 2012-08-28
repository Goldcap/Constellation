
package com.sierrastarstudio.utils
{
	import flash.utils.getTimer;

	
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
	public class tracer
	{
		private var classname:String = "com.sierrastarstudio.utils.tracer";
		
		public function tracer()
		{
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
				trace(str);
			}
			
			
		}
		
	}
}