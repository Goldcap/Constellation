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
package view{
	import flash.display.*;
	import flash.events.*;
	import model.Model;
	/**
	 * Akamai Multi Player - generates the background views 
	 */
	public class BackgroundView extends MovieClip {
		private var _model:Model;
		public function BackgroundView(model:Model):void {
			_model = model;
			_model.addEventListener(_model.EVENT_RESIZE, resize);
			createChildren();
		}
		private function createChildren():void {
		}
		public function resize(e:Event):void {
			this.graphics.clear();
			this.graphics.beginFill(_model.frameColor);
			this.graphics.drawRect(0,0,_model.width,_model.height);
			this.graphics.endFill();
			if (!_model.isOverlay) {
				if (_model.hasPlaylist && _model.playlistVisible) {
					this.graphics.lineStyle(1,_model.backgroundColor);
					this.graphics.moveTo(_model.width - _model.playlistWidth - 7,0);
					this.graphics.lineTo(_model.width - _model.playlistWidth - 7,_model.height);
					this.graphics.moveTo(0,_model.height - _model.controlbarHeight - 1);
					this.graphics.lineTo(_model.isOverlay ? _model.width : _model.width - _model.playlistWidth-7,_model.height - _model.controlbarHeight -1);
				} else {
					this.graphics.lineStyle(1,_model.backgroundColor);
					this.graphics.moveTo(0,_model.height - _model.controlbarHeight - 1);
					this.graphics.lineTo(_model.width, _model.height - _model.controlbarHeight - 1);
				
					
				}
			}
		}
	}
}
