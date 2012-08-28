/**
* LIMELIGHT NETWORKS INCORPORATED
* Copyright 2009 Limelight Networks Incorporated
* All Rights Reserved.
*
* NOTICE:  Limelight permits you to use, modify, and distribute this file in accordance with the
* terms of the Limelight end user license agreement accompanying it.  If you have received this file from a
* source other than Limelight, then your use, modification, or distribution of it requires the prior
* written permission of Limelight.
*/
package com.llnw.utils 
{
	
	/**
	 * ...
	 * @author LLNW
	 */
	public class SecondsFormatter 
	{
		
		/**
		 * this handles the conversion of seconds to 
		 * minutes. Useful for playhead display times.
		 */
		  
		 
		public function SecondsFormatter() 
		{
			
		}
		public function secondsToTimeFormat(seconds:Number):String {
			var minutes:Number = Math.floor(seconds/60);

			var hours:Number = Math.floor(minutes/60);
			
			var secondsString:String = String(Math.floor(seconds%60));
			var minutesString:String = String(Math.floor(minutes%60));
			var hoursString:String = String(Math.floor(hours%23));
			
			if(secondsString.length == 1) secondsString = "0" + secondsString;
			if(minutesString.length == 1) minutesString = "0" + minutesString;
			//if(hoursString.length == 1) hoursString = "0" + hoursString; //Hours greater than 10???
			if(hoursString.length == 1) hoursString = hoursString;
			var time:String = "";
			if(hoursString != "0"){
				time = hoursString + ":"
			}
			time += minutesString + ":" + secondsString;
			
			return time;
		}
		
	}
	
}