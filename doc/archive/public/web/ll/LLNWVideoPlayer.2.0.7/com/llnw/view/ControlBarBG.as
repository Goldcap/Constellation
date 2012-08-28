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
package com.llnw.view 
{
	import flash.display.MovieClip;
	
	/**
	 * ...
	 * @author LLNW
	 */
	public class ControlBarBG extends MovieClip
	{
		
		public function ControlBarBG() 
		{
			
		}
		
		public function set theWidth(w:int):void {
			this.width = w;
		}
		public function get theWidth():int {
			
			return this.width;
		}
	}
	
}