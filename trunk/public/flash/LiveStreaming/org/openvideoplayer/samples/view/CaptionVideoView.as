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
package org.openvideoplayer.samples.view
{
	import flash.events.Event;
	
	import org.openvideoplayer.components.ui.captioning.CaptioningView;

	import com.zeusprod.Log;
	
	public class CaptionVideoView extends BaseVideoView
	{
		private var _captioningView:CaptioningView;
		
		public function CaptionVideoView(vidWidth:Number, vidHeight:Number)
		{
			//Log.traceMsg ("CaptionVideoView size is " + vidWidth + " x " + vidHeight, Log.LOG_TO_CONSOLE);
			super (vidWidth, vidHeight);
		}
		
		override protected function onAddedToStage(event:Event):void
		{
			super.onAddedToStage(event);
			addCaptionView();
		}

		public function get captioningView():CaptioningView
		{
			return _captioningView;
		}

		private function addCaptionView():void
		{
			var h:uint = 30;
			_captioningView = new CaptioningView(controlBar.width, h);
			_captioningView.y = controlBar.y - h - 2;
			_captioningView.visible = false;
			addChild(_captioningView);
		}
	}
}
