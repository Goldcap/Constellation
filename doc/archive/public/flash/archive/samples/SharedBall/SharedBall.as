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
	import flash.net.SharedObject;

	import flash.events.NetStatusEvent;
	import flash.events.MouseEvent;
	import flash.events.SyncEvent;

	public class SharedBall extends MovieClip
	{
		private var so:SharedObject;
		private var nc:NetConnection;

		public function SharedBall()
		{
			nc = new NetConnection();
			addEventListeners();
			nc.connect("rtmp://localhost/SharedBall");
		}

		private function addEventListeners() {
		  	nc.addEventListener(NetStatusEvent.NET_STATUS, netStatusHandler);

			// sharedBall is defined in the FLA file
			sharedBall.addEventListener(MouseEvent.MOUSE_DOWN, pickup);
			sharedBall.addEventListener(MouseEvent.MOUSE_UP, place);
			sharedBall.addEventListener(MouseEvent.MOUSE_MOVE, moveIt);
		}


		// handle connection events
        public function netStatusHandler(event:NetStatusEvent):void
        {
            trace("connected is: " + nc.connected );
			trace("event.info.level: " + event.info.level);
			trace("event.info.code: " + event.info.code);

            switch (event.info.code)
            {
                case "NetConnection.Connect.Success":
	                trace("Congratulations! you're connected");
		    		so = SharedObject.getRemote("ballPosition", nc.uri, false);
					so.connect(nc);
					so.addEventListener(SyncEvent.SYNC, syncHandler);
	                break;
                case "NetConnection.Connect.Rejected":
				case "NetConnection.Connect.Failed":
	                trace ("Oops! you weren't able to connect");
	                break;
	        }
        }


		// pick up the ball
		function pickup( event:MouseEvent ):void {
			event.target.startDrag();
		}


		// move the ball
		function moveIt( event:MouseEvent ):void {
			// update the shared object when a user moves the ball
			// this fires a sync event
			if( so != null )
			{
				so.setProperty("x", sharedBall.x);
				so.setProperty("y", sharedBall.y);
			}
		}


		// place the ball when finished moving
		function place(event:MouseEvent):void {
			event.target.stopDrag();
		}


		// update clients when the shared object changes
		private function syncHandler(event:SyncEvent):void {
			// when a sync event is fired
			// update the position of the ball in clients
			sharedBall.x = so.data.x;
			sharedBall.y = so.data.y;
		}

	}
}