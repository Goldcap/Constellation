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
package org.openvideoplayer.components.ui.controlbar.view
{
	import org.openvideoplayer.components.ui.shared.ControlType;
	import org.openvideoplayer.components.ui.controlbar.model.ControlBarPropertyModel;
	import org.openvideoplayer.components.ui.controlbar.utils.LayoutUtil;
	
	import flash.display.Shape;
	import org.openvideoplayer.components.ui.shared.view.base.BaseComponent;
	
	/**
	 * This is a composite of the VolumeSlider and MuteButton components to create a 
	 * typical VolumeControl for a media player.
	 * 
	 * @author Akamai Technologies, Inc 2011
	 */
	public class VolumeControl extends BaseComponent
	{
		
		private var muteButton:MuteButton;
		private var volumenSlider:VolumeSlider;
		private var bg:Shape;
		private var highlight:Shape;
		
		/**
		 * @Constructor 
		 */		
		public function VolumeControl()
		{
			super(ControlType.VOLUME_CONTROL);
			addVolumeSlider();
			addMuteButton();
			alignItems();
		}
		
		/**
		 * @param value
		 * @return 
		 */		
		public function set currentState(value:String):void
		{
			muteButton.currentState = value;
		}
		public function get currentState():String
		{
			return muteButton.currentState;
		}
		
		/**
		 * 
		 * @param currentTime
		 * @param totalTime
		 */		
		public function setThumbPosition(currentTime:Number, totalTime:Number):void
		{
			volumenSlider.setThumbPosition(currentTime, totalTime);
		}
		
		/**
		 * @param totalTime
		 * @return 
		 */		
		public function getThumbPosition(totalTime:Number=1):Number
		{
			return volumenSlider.getThumbPosition(totalTime);
		}
		
		private function addMuteButton():void
		{
			muteButton = new MuteButton();
			addChild(muteButton);
		}
		
		private function addVolumeSlider():void 
		{
			volumenSlider = new VolumeSlider();
			addChild(volumenSlider);
		}
		
		private function alignItems():void 
		{
			volumenSlider.x = muteButton.x + muteButton.width + 3;
			muteButton.y = LayoutUtil.calculateVerticleCenter(this.height, muteButton.height*muteButton.scaleX)-1;
		}
		
	}
}