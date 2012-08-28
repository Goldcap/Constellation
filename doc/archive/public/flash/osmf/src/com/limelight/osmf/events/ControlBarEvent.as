package com.limelight.osmf.events
{
	import flash.events.Event;

	public class ControlBarEvent extends Event
	{

		// Public constructor. 
        public function ControlBarEvent(type:String, val:Object=null, bubbles:Boolean=false, cancelable:Boolean=false) {
                // Call the constructor of the superclass.
                super(type,bubbles,cancelable);
                value = val;
        }

        // Define static constant.
        public static const PLAY:String = "play";
        public static const PAUSE:String = "puase";
        public static const FULLSCREEN:String = "fullscreen";
        public static const VOLUME:String = "volume";
        public static const MUTE:String = "mute";
        public static const SEEK:String = "seek";
            
		// Define a public variable to hold the state of the enable property.
        public var value:Object;
        
        // Override the inherited clone() method. 
        override public function clone():Event {
            return new ControlBarEvent(type, value, bubbles, cancelable);
        }
		
	}
}