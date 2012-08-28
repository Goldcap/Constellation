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

    public class SimpleConnectManage extends Sprite
    {
        var nc:NetConnection;

        public function SimpleConnectManage()
        {
            nc = new NetConnection();
            nc.addEventListener(NetStatusEvent.NET_STATUS, netStatusHandler);
            nc.connect("rtmp://localhost/HelloServer");
        }

        public function netStatusHandler(event:NetStatusEvent):void
        {
            trace("connected is: " + nc.connected );
			trace("event.info.level: " + event.info.level);
			trace("event.info.code: " + event.info.code);

            switch (event.info.code)
            {
                case "NetConnection.Connect.Success":
	                trace("Congratulations! you're connected");
	                // create live streams
	                // play recorded streams
	                break;
                case "NetConnection.Connect.Rejected":
	                trace ("Oops! the connection was rejected");
	                // try to connect again
	                break;
	            case "NetConnection.Connect.InvalidApp":
	                trace("Please specify a different application name in the URI");
	                // try to connect again
	                break;
	            case "NetConnection.Connect.Failed":
	                trace("The server may be down or unreachable");
	                // display a message for the user
	                break;
	            case "NetConnection.Connect.AppShutDown":
	                trace("The application is shutting down");
	                // this method disconnects all stream objects
	                nc.close();
	                break;
	            case "NetConnection.Connect.Closed":
	                trace("The connection was closed successfully - goodbye");
	                // display a reconnect button
	                break;
	        }
        }

    }
}
