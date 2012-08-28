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


//******************************************************************
// Akamai UI Components Version 1.1 public release ****************
//******************************************************************

HOW TO BUILD THE LIBRARY 
- load the project into Flash / Flex Builder and publish
or
- type "ant" from command line without the quotes at the same directory level as the src folder (assuming you have ant tasks installed)


SAMPLES OF THE COMPONENTS IN ACTION W/CODE EXAMPLES
http://openvideoplayer.sourceforge.net/ovpfl/samples/as3/index.html

----------

This UI component library contains the following UI Components

1. CONTROL_BAR
Media control bar that is very configurable in regards to both style and layout.
This component can be uses to control video and audio playback as well as any 
timeline animation that has progress events.

USE CASE - A developer would like to use a video control bar with limitless configurability 
without recoding the component for each project but rather just 
reconfiguring the component per project needs

----------

2. PRELOADING_SPINNER_WITH_LABEL_FIELD
Common spinning preloader wheel with a textfield label that can render text below the spinner. 

USE CASE - you need a spinner wheel with configuration for preloading elements of an application or showing busy time.  
The label needs to be dynamic to update the text label indicating the current preload action.

----------

3. POSTER_FRAME_BUTTON
This component is not complete - missing image loading and unloading support
currently and only holds the Large Play Button. In the next release it will 
be able to load a default image.

Currently this component is fully excluded from asdocs

USE CASE - You want a large play button over the video on load and possibly on pause and a 
default video image to overlay the video display before play is pressed.

----------

4. CAPTIONING_VIEW
A small sprite that can take a caption and will sit on the lower third of the screen.  
You can use this component for Closed Captioning or any other messaging you want to
overlay over the video.

USE CASE - you need to display text over the video display - typically in the lower third area of the display.
----------

5. DEBUG_PANEL_VIEW
A console window that can be placed anywhere and you can wire trace events 
into the console to view debug information while the video is playing
This component also has fields for bandwidth, bufferLength, and maxBuffer.
You must supply the info to render these fields.

USE CASE - You need a place for real time debug statements and/or stats to be displayed while the application is running.
----------
		
6. INPUT_FORM_WITH_SUBMIT_BUTTON
Simple input form with submit button for simple one line form inputs.  
Good for dynamic url or single line text inputs. 

USE CASE - You need to take in a single line of text for a dynamic application.
----------

7. PLAY_LIST
Scrollable playlist with thumbnail support,title and description text
Currently this component is fully excluded from asdocs
		
USE CASE - You need an scrollable unlimted length playlist that holds playlist items with thumb, title, and description support.
----------