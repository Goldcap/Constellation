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
package org.openvideoplayer.components.ui.shared
{
	/**
	 * This Class is a static class with public static const string references 
	 * for all the controls that are available in the library that can be added 
	 * to the control bar component.  Please use these string constants in 
	 * conjunction with the method getControl found in ControlBar.as
	 * 
	 * Example:
	 * <listing>myControlBar.getControl(ControlType.PLAY_PAUSE_BUTTON);</listing>
	 * 
	 * @author Akamai Technologies, Inc 2011
	 */	
	public class ControlType
	{
		/**
		 * Reference to the PlayPauseButton control. Use this const to reference 
		 * the Play-Pause control after it has been added to the Control Bar 
		 * layout list.
		 */		
		public static const PLAY_PAUSE_BUTTON:String = "playPause";
		
		/**
		 * Reference to the ScrubBar control. Use this const to reference 
		 * the ScrubBar control after it has been added to the Control Bar 
		 * layout list.
		 */	
		public static const SCRUB_BAR:String =  "scrub";
			
		/**
		 * Reference to the SingleTimeCodeDisplay control. Use this const to reference 
		 * the SingleTimeCodeDisplay control after it has been added to the Control Bar
		 * layout list.
		 */		
		public static const SINGLE_TIMECODE_DISPLAY:String = "singleTimeCode";
			
		/**
		 * Reference to the DualTimeCodeDisplay control. Use this const to reference 
		 * the DualTimeCodeDisplay control after it has been added to the Control Bar 
		 * layout list.
		 */		
		public static const CURRENT_AND_DURATION_TIME_DISPLAY:String = "dualTimeCode";
			
		/**
		 * Reference to the MuteButton control. Use this const to reference 
		 * the MuteButton control after it has been added to the Control Bar 
		 * layout list.
		 */		
		public static const MUTE_BUTTON_NO_VOLUME_SLIDER:String = "mute";
			
		/**
		 * Reference to the VolumeControl which is a composite of the MuteButton and the 
		 * VolumeSlider controls. Use this const to reference the VolumeControl  
		 * after it has been added to the Control Bar layout list.
		 */		
		public static const VOLUME_CONTROL:String = "volumeControl";
			
		/**
		 * Reference to the FullscreenButton control. Use this const to reference 
		 * the FullscreenButton control after it has been added to the Control Bar 
		 * layout list.
		 */	
		public static const FULLSCREEN_BUTTON:String = "fullscreen";
		
		/**
		 * Reference to the MBRSwitchingToggleButton control. Use this const to reference 
		 * the MBRSwitchingToggleButton control after it has been added to the Control Bar
		 *  layout list.
		 */		
		public static const MBR_SWITCH_TOGGLE_BUTTON:String = "mbrToggle";
		
		/**
		 * Reference to the ScrollUpButton control. Use this const to reference 
		 * the ScrollUpButton control after it has been added to the Control Bar 
		 * layout list.
		 */		
		public static const SCROLL_UP_BUTTON:String = "scrollUpButton";
		
		/**
		 * Reference to the ScrollDownButton control. Use this const to reference 
		 * the ScrollDownButton control after it has been added to the Control Bar 
		 * layout list.
		 */		
		public static const SCROLL_DOWN_BUTTON:String = "scrollDownButton";
		
		/**
		 * Reference to the FlexibleSpacer control. Use this const to reference 
		 * the FlexibleSpacer control after it has been added to the Control Bar 
		 * layout list.
		 */		
		public static const FLEXIBLE_SPACER:String = "flexibleSpacer";
		
		/**
		 * Reference to the SlowMotionForwardButton control. Use this const to reference 
		 * the SlowMotionForwardButton control after it has been added to the Control Bar 
		 * layout list.  
		 */		
		public static const SLOW_MOTION_FORWARD_BUTTON:String = "slowMotionForward";
		
		/**
		 * Reference to the SlowMotionRewindButton control. Use this const to reference 
		 * the SlowMotionRewindButton control after it has been added to the Control Bar 
		 * layout list.   
		 */		
		public static const SLOW_MOTION_REWIND_BUTTON:String = "slowMotionRewind";
		
		/**
		 * Reference to the FastForwardButton control. Use this const to reference 
		 * the FastForwardButton control after it has been added to the Control Bar 
		 * layout list. 
		 */	
		public static const FAST_FORWARD_BUTTON:String = "fastForward";
		
		/**
		 * Reference to the RewindButton control. Use this const to reference 
		 * the RewindButton control after it has been added to the Control Bar 
		 * layout list.
		 */
		public static const REWIND_BUTTON:String = "rewind";
		
		/**
		 * String Reference to the LabelButton class that gets registered in 
		 * the BaseComponent class and can be referenced later on.
		 */
		public static const LABEL_BUTTON:String = "labelButton";
		
		/**
		 * String Reference to the Label class that gets registered in 
		 * the BaseComponent class and can be referenced later on.
		 */
		public static const LABEL:String = "label";
		
		/**
		 * Not supported - testing only
		 * @private 
		 */		
		public static const STAGE_VIDEO_OVERLAY_BUTTON:String = "stageVideoOverlayButton";
	
	}
}