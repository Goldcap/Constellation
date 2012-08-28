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
package org.openvideoplayer.components.ui
{
	
	/**
	 * This Class is a static class with public static const string references 
	 * for all the components that are available in the library.  This const are
	 * mostly for internal code reference but have been included in the docs to show 
	 * what components are available in the library.
	 * 
	 * @author Akamai Technologies, Inc 2011
	 */	
	public class ComponentType
	{
		/**
		 * Media control bar that is very configurable in regards to both style and layout.
		 * This component can be uses to control video and audio playback as well as any 
		 * timeline animation that has progress events.
		 */					
		public static const CONTROL_BAR:String = "controlBar";
		
		/**
		 * Common spinning preloader wheel with a textfield label that can render text below the spinner. 
		 */		
		public static const PRELOADING_SPINNER_WITH_LABEL_FIELD:String = "preloadingSpinnerWithLabelField";
		
		
		/**
		 * A small sprite that can take a caption and will sit on the lower third of the screen.  
		 * You can use this component for Closed Captioning or any other messaging you want to 
		 * overlay over the video
		 */		
		public static const CAPTIONING_VIEW:String = "captioningView";
		
		/**
		 * A console window that can be placed anywhere and you can wire trace events 
		 * into the console to view debug information while the video is playing
		 * This component also has fields for bandwidth, bufferLength, and maxBuffer.
		 * You must supply the info to render these fields...		 
		 */		
		public static const DEBUG_PANEL_VIEW:String = "debugPanelView";
		
		/**
		 * Simple input form with submit button for simple one line form inputs.  
		 * Good for dynamic url or single line text inputs. 
		 */				
		public static const INPUT_FORM_WITH_SUBMIT_BUTTON:String = "inputFormWithSubmitButton";
		
		/**
		 * Scrollable playlist with thumbnail support,title and description text
		 * Currently this component is fully excluded from asdocs
		 */		
		public static const PLAY_LIST:String = "playList";
		
		/**
		 * This component is not complete - missing image loading and unloading support
		 * Currently and only holds the Large Play Button but in the next release it will 
		 * be able to load a default image
		 * Currently this component is fully excluded from asdocs   
		 */
		public static const POSTER_FRAME_BUTTON:String = "posterFrameButton";
		
	}
}