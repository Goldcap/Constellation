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
package org.openvideoplayer.components.ui.controlbar.event
{
	import flash.events.Event;
	
	[ExcludeClass]
	
	/**
	 * @author Akamai Technologies, Inc 2011
	 */	
	public class ControlBarPropertyChangeEvent extends Event
	{
		
		/**
		 * Fired off for internal control layout updating whenever the width of the control bar changes
		 */		
		public static const CONTROL_BAR_WIDTH_CHANGE_EVENT:String = "controlBarWidthChange";
		
		/**
		 * Fires off when the width of the scrub bar changes based on controls being added or removed in real time
		 */		
		public static const SCURB_BAR_WIDTH_CHANGE_EVENT:String = "scrubBarWidthChange";
		
		/**
		 * Fires off when mute change occures for interal updateing of volume slider.  
		 * If mute is on then the volume slider needs to be all the way down and if 
		 * mute is off the volumen slider will restore to the former position. 
		 * The volumne for the control bar is stored in a local shared object 
		 * for persistant restoration accross sessions
		 */		
		public static const MUTE_CHANGE:String = "muteChange";
		
		/**
		 * Fires off when the control list is modified - adding controls ( coming soon-  removing controls)
		 */		
		public static const CONTROL_LIST_CHANGE_EVENT:String = "controlListChange";
		
		/**
		 * Type of Event to fire off
		 * 
		 * @param type
		 */		
		public function ControlBarPropertyChangeEvent(type:String)
		{
			super(type);
		}
		
		override public function clone():Event 
		{
			return new ControlBarPropertyChangeEvent(type);
		}
	}
}