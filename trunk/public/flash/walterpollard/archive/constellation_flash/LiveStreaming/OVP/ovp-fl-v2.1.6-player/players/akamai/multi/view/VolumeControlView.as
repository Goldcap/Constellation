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
	import flash.geom.*;
	import model.Model;
	import controller.*;
	import ui.*;
	/**
	 * Akamai Multi Player - generates the volume control as a volume icon accompanied by 10 clickable level boxes. 
	 */
	public class VolumeControlView extends MovieClip {
		private var _model:Model;
		private var _controller:VolumeControlController;
		private var _volumeIcon:VolumeIcon;
		private var _muteState:Boolean;
		private var _lastVolume:Number;
		public function VolumeControlView(model:Model):void {
			_model=model;
			_controller=new VolumeControlController(_model,this);
			createChildren();
		}
		private function createChildren():void {
			_muteState = _model.volume == 0 ? true : false;
			_lastVolume = 1;
			this.graphics.beginFill(_model.frameColor);
			this.graphics.drawRect(20,1,71,10);
			this.graphics.endFill();
			_volumeIcon=new VolumeIcon() ;
			_volumeIcon.x=0;
			_volumeIcon.y=0;
			_volumeIcon.addEventListener(MouseEvent.CLICK,handleIconClick);
			if (_muteState) {
				_volumeIcon.transform.colorTransform = new ColorTransform(1,1,1,1,200);
			}
			addChild(_volumeIcon);
			for (var i:int=1; i < 11; i++) {
				addVolButton(i);
			}
		}
		private function addVolButton(i:int):void {
			var mc:MovieClip=new MovieClip  ;
			mc.name="button" + i.toString();
			mc.index=i;
			mc.x=21 + ((i-1) * 7);
			mc.y=2;
			mc.addEventListener(MouseEvent.CLICK,volButtonClick);
			mc.graphics.beginFill(0x000000);
			mc.graphics.drawRect(0,0,6,8);
			mc.graphics.endFill();
			var highlight:MovieClip=new MovieClip  ;
			highlight.name="highlight";
			highlight.index=i;
			highlight.addEventListener(MouseEvent.CLICK,volButtonClick);
			var matrix:Matrix=new Matrix  ;
			matrix.createGradientBox(6,8,Math.PI / 4,0,0);
			highlight.graphics.beginGradientFill(GradientType.LINEAR,[_model.themeColor,_model.themeColor],[1,.7],[50,255],matrix);
			highlight.graphics.drawRect(0,0,6,8);
			highlight.graphics.endFill();
			highlight.visible = i <= _model.volume*10;
			mc.addChild(highlight);
			addChild(mc);
		}
		private function volButtonClick(e:MouseEvent):void {
			if (!_muteState) {
				implementClick(e.currentTarget.index);
			}
			
		}
		private function implementClick(index:int):void {
			for (var k:int=1; k < 11; k++) {
				(this.getChildByName("button" + k)  as  MovieClip).getChildByName("highlight").visible=k <= index;
			}
			_controller.setVolume(index/10);
			
		}
			
			
		private function handleIconClick(e:MouseEvent):void {
			if (_muteState) {
				_volumeIcon.transform.colorTransform = new ColorTransform(1,1,1,1,0);
				_muteState = false;
				implementClick(Math.round(_lastVolume*10));
			} else {
				_volumeIcon.transform.colorTransform = new ColorTransform(1,1,1,1,200);
				_muteState = true;
				_lastVolume = _model.volume;
				implementClick(0);
			}
		}
	}
}
