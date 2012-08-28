//
// Copyright (c) 2009-2011, the Open Video Player authors. All rights reserved.
//
// Redistribution and use in source and binary forms, with or without 
// modification, are permitted provided that the following conditions are 
// met:
//
//    * Redistributions of source code must retain the above copyright 
//		notice, this list of conditions and the following disclaimer.
//    * Redistributions in binary form must reproduce the above 
//		copyright notice, this list of conditions and the following 
//		disclaimer in the documentation and/or other materials provided 
//		with the distribution.
//    * Neither the name of the openvideoplayer.org nor the names of its 
//		contributors may be used to endorse or promote products derived 
//		from this software without specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS 
// "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT 
// LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR 
// A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT 
// OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, 
// SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT 
// LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, 
// DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY 
// THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT 
// (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE 
// OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
//
package org.openvideoplayer.components.ui.shared.event
{
	import flash.events.Event;
	
	/**
	 * This class extends Event and is dispatched for all control bar events. Use the 
	 * String const in this class to addEventListeners for the controlbar events
	 * 
	 * @author Akamai Technologies, Inc 2011
	 */
	public class ControlEvent extends Event
	{
	
		/**
		 * Fires when the playpause button is clicked into pause mode
		 */		
		public static const PAUSE:String = "pause";
		
		/**
		 * Fires when the playpause button is clicked into play mode 
		 */		
		public static const PLAY:String = "play";
		
		/**
		 * Fires when a seek has begun
		 */		
		public static const SEEK_BEGIN:String = "seekBegin";
		
		/**
		 * Fires when the scrub bar position has been changed
		 */		
		public static const SEEK_CHANGE:String = "seekChange";
		
		/**
		 * Fires when a seek has completed
		 */		
		public static const SEEK_COMPLETE:String = "seekComplete";
		
		/**
		 * Fires when the fullscreen button is clicked into fullscreen mode
		 */		
		public static const FULLSCREEN:String = "fullscreen";
		
		/**
		 * Fires when the fullscreen button is clicked into normalscreen mode
		 */		
		public static const NORMALSCREEN:String = "normalscreen";
		
		/**
		 * Fires when the mute button is clicked into mute on mode
		 */		
		public static const MUTE_ON:String = "muteOn";
		
		/**
		 * Fires when the mute button is clicked into mute off mode 
		 */		
		public static const MUTE_OFF:String = "muteOff";
		
		/**
		 * Fires when the volume bar position has been changed
		 */		
		public static const VOLUME_CHANGE:String = "volumeChange";
		
		/**
		 * Fires when the MBR button is clicked into manual mode 
		 */		
		public static const MANUAL_MBR:String = "manualMBR";
		
		/**
		 * Fires when the MBR button is clicked into auto mode
		 */		
		public static const AUTO_MBR:String = "autoMBR";
		
		/**
		 * Fires when the ScrollUp button is clicked 
		 */		
		public static const SCROLL_UP:String = "scrollUp";
		
		/**
		 * Fires when the ScrollDown button is clicked 
		 */		
		public static const SCROLL_DOWN:String = "scrollDown";
		
		/**
		 * Fires when a LabelButton is clicked 
		 */		
		public static const LABEL_BUTTON_CLICK:String = "labelButtonClick";
		
		/**
		 * Fires when button is pressed down 
		 */		
		public static const SLOW_MOTION_FORWARD_DOWN:String = "slowMotionForwardDown";
		/**
		 * Fires when button is released 
		 */		
		public static const SLOW_MOTION_FORWARD_UP:String = "slowMotionForwardUp";
		/**
		 * Fires when button is pressed down 
		 */		
		public static const SLOW_MOTION_REWIND_DOWN:String = "slowMotioRewindDown";
		/**
		 * Fires when button is released 
		 */		
		public static const SLOW_MOTION_REWIND_UP:String = "slowMotionRewindUp";
		
		/**
		 * Fires when button is pressed down 
		 */		
		public static const FAST_FORWARD_DOWN:String = "fastForwardDown";
		/**
		 * Fires when button is released 
		 */		
		public static const FAST_FORWARD_UP:String = "fastForwardUp";
		
		/**
		 * Fires when button is pressed down 
		 */		
		public static const REWIND_DOWN:String = "rewindDown";
		/**
		 * Fires when button is released 
		 */		
		public static const REWIND_UP:String = "rewindUp";
		
		/**
		 * Testing only
		 * @private 
		 */		
		public static const STAGE_VIDEO_OVERLAY_CLICK:String = "stageVideoOverlayButtonClick";
		

		private var _data:Object;
		
		/**
		 * @Constuctor 
		 */		
		public function ControlEvent(type:String, data:Object = null)
		{
			_data = data;
			super(type, true);
		}
		
		/**
		 * Returns data object of the event. 
		 * @return 
		 */		
		public function get data():Object
		{
			return _data;
		}
		
		/**
		 * @return 
		 */		
		override public function clone():Event 
		{
			return new ControlEvent(type, _data);
		}	 
	}
}