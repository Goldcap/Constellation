/*
 * (C) Copyright 2010 Adobe Systems Incorporated. All Rights Reserved.
 *
 * NOTICE:  Adobe permits you to use, modify, and distribute this file in accordance with the
 * terms of the Adobe license agreement accompanying it.  If you have received this file from a
 * source other than Adobe, then your use, modification, or distribution of it requires the prior
 * written permission of Adobe.
 * THIS CODE AND INFORMATION IS PROVIDED "AS-IS" WITHOUT WARRANTY OF
 * ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE.
 *
 *  THIS CODE IS NOT SUPPORTED BY Adobe Systems Incorporated.
 *
 */

package {

	import flash.display.Sprite;
    import flash.net.NetConnection;
    import flash.events.NetStatusEvent;
    import flash.events.AsyncErrorEvent;

    public class Bandwidth extends Sprite
    {
        private var nc:NetConnection;

        public function Bandwidth()
        {
            nc = new NetConnection();
            nc.addEventListener(NetStatusEvent.NET_STATUS, netStatusHandler);
			nc.addEventListener(AsyncErrorEvent.ASYNC_ERROR, asyncErrorHandler);
            nc.client = new Client();
            nc.connect("rtmp://localhost/bandwidthcheck");
        }

        public function netStatusHandler(event:NetStatusEvent):void
        {
			trace(event.info.code);
            switch (event.info.code)
            {
                case "NetConnection.Connect.Success":
	                // Calls native bandwidth detection code on the server.
	                // You don't need to write any server-side code.
	                nc.call("checkBandwidth", null);
	                break;
	        }
        }
		
		public function asyncErrorHandler(event:AsyncErrorEvent):void
		{
			// Handle asyncErrorEvents. 
		}
    }
}


class Client {
     public function onBWCheck(... rest):Number {
		    return 0;
     }

	 public function onBWDone(... rest):void {
		    var bandwidthTotal:Number;
		    if (rest.length > 0){
				bandwidthTotal = rest[0];
				// Write code here to run when the 
		    	// bandwidth check is complete.
		   		trace("Bandwith from server to client is: " + bandwidthTotal + " Kbps");
			}

	}
}