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
package com.llnw.fms 
{
	import flash.events.Event;
	import flash.net.NetConnection;
	import com.llnw.fms.CustomClient;
	/**
	 * ...
	 * @author LLNW
	 */
	public class LLNWConnection extends NetConnection
	{
		
		public function LLNWConnection() 
		{
			//constructed
		}
		
		override public function connect(command:String, ... arguments):void {
			arguments.unshift(command);
			super.connect.apply(this, arguments);
		}
		
		public var onFCSubscribeCallBack:Function;
        public function onFCSubscribe(param1) : void
        {
		
            this.onFCSubscribeCallBack(param1);
            return;
        }
		
		public var onDVRSubscribeCallBack:Function;
        public function onDVRSubscribe(param1) : void
        {
			
			
            this.onDVRSubscribeCallBack(param1);
            return;
        }
		
		
		
		public function onBWDone() : void
        {
            return;
        }
		
		
		public var debugMessage:String;
		private function debug(msg:String):void {
			debugMessage = msg;
			dispatchEvent(new Event("debugEvent",true))
		}
		
	}
	
}