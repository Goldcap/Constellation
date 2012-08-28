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

	import flash.display.MovieClip;
	import flash.net.NetConnection;
	import flash.events.NetStatusEvent;
	import flash.events.MouseEvent;

	public class HelloServer extends MovieClip {

	  private var nc:NetConnection;

	  /*
	   *  Constructor.
	   */
	  public function HelloServer() {
		    // register listeners for mouse clicks on the two buttons
			connectBtn.addEventListener(MouseEvent.CLICK, connectHandler);
			closeBtn.addEventListener(MouseEvent.CLICK, closeHandler);
	  }


	 /*
	  *  Connect to the server.
	  */
	 public function connectHandler(event:MouseEvent):void {
		    trace("Okay, let's connect now");
		 	nc = new NetConnection();
			nc.addEventListener(NetStatusEvent.NET_STATUS, netStatusHandler);
            nc.connect("rtmp://localhost/HelloServer");
	 }


	 /*
	  *  Disconnect from the server.
	  */
	 public function closeHandler(event:MouseEvent):void {
		    trace("Now we're disconnecting");
			nc.close();
	 }


	 /*
	  *  Handle events relating to the server connection.
	  */
	 public function netStatusHandler(event:NetStatusEvent):void {
            trace("connected is: " + nc.connected);
			trace("event.info.level: " + event.info.level);
			trace("event.info.code: " + event.info.code);

			switch (event.info.code)
            {
                case "NetConnection.Connect.Success":
	                trace("Congratulations! you're connected" + "\n");
	                break;
                case "NetConnection.Connect.Rejected":
	                trace ("Oops! the connection was rejected" + "\n");
	                break;
				case "NetConnection.Connect.Closed":
					trace("Thanks! the connection has been closed" + "\n");
					break;
	       }
     }


   }
}












