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
	import flash.net.Responder;
	import flash.net.NetConnection;
	import flash.events.NetStatusEvent;
	import flash.events.MouseEvent;


	public class HelloWorld extends MovieClip {

	  // Represents a network connection.
	  private var nc:NetConnection;

	  // Responder for call to server's serverHelloMsg -- see onReply() below.
	  private var myResponder:Responder = new Responder(onReply);


	  // Constructor.
	  public function HelloWorld() {
			// Set display values.
			textLbl.text = "";
			connectBtn.label = "Connect";

			// Register a listener for mouse clicks on the button.
			connectBtn.addEventListener(MouseEvent.CLICK, connectHandler);
	   }


	 // When button is pressed, connect to or disconnect from the server.
	 public function connectHandler(event:MouseEvent):void {
			if (connectBtn.label == "Connect") {
				trace("Connecting...");

				nc = new NetConnection();

				// Connect to the server.
				nc.connect("rtmp://localhost/HelloWorld");

				// Call the server's client function serverHelloMsg, in HelloWorld.asc.
				nc.call("serverHelloMsg", myResponder, "World");

				connectBtn.label = "Disconnect";

			} else {
				trace("Disconnecting...");

				// Close the connection.
				nc.close();

				connectBtn.label = "Connect";
				textLbl.text = "";
			}
	 }

	 // Responder function for nc.call() in connectHandler().
	 private function onReply(result:Object):void {
            trace("onReply received value: " + result);
			textLbl.text = String(result);
     }

   }
}
