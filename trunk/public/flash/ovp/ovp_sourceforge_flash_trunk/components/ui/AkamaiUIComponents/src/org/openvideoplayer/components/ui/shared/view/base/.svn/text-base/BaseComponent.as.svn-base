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
package org.openvideoplayer.components.ui.shared.view.base
{
	import org.openvideoplayer.components.ui.controlbar.model.ControlBarPropertyModel;
	
	import flash.display.Sprite;
	
	[ExcludeClass]
	
	/**
	 * This class is the at the base of all control types and should 
	 * not to be added directly to the control bar. 
	 * 
	 * @author Akamai Technologies, Inc 2011
	 */	
	public class BaseComponent extends Sprite
	{
		private var _enabled:Boolean = true;
		private var _type:String;
		
		protected var controlBarPropertyModel:ControlBarPropertyModel;
		
		/**
		 * 
		 * Constructor
		 * @param type Type of ControlType this BaseComponent represents
		 * 
		 */			
		public function BaseComponent(type:String)
		{
			_type = type;
			controlBarPropertyModel = ControlBarPropertyModel.getInstance();
		}
		
		/**
		 * Use this accessor to get the ControlType that has been passed in
		 * @return 
		 */		
		public function get type():String
		{
			return _type;
		}
		
		/**
		 * Use this accessor to set the enabled state of the component
		 * or to get the enabled state of the component
		 * @param value
		 * @return  
		 */		
		public function get enabled():Boolean
		{
			return _enabled;
		}
		
		public function set enabled(value:Boolean):void
		{
			_enabled = value;
			mouseEnabled = value;
			alpha = (_enabled) ? 1 : .2;
		}
	}
}