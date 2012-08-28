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
	import org.openvideoplayer.components.ui.controlbar.event.ControlBarPropertyChangeEvent;
	import org.openvideoplayer.components.ui.controlbar.utils.LayoutUtil;
	import org.openvideoplayer.components.ui.shared.utils.ShapeFactory;
	
	import flash.display.Shape;
	import flash.events.Event;
	import org.openvideoplayer.components.ui.shared.view.base.BaseComponent;

	/**
	 * This class should be used to replace the scrub bar and will define a left and right column 
	 * for contorls to be laid out. If you add a control before the FlexibleSpacer class is added 
	 * it will reside on the left side.  If you a control after the FlexibleSpacer class it 
	 * will reside on the right side.
	 *  
	 * @author Akamai Technologies, Inc 2011
	 * 
	 */
	public class FlexibleSpacer extends BaseComponent
	{
		
		private var spacer:Shape;
		private var _spacerWidth:Number;
		
		/**
		 * @Constructor 
		 */		
		public function FlexibleSpacer()
		{
			super(ControlType.FLEXIBLE_SPACER);
			this.addEventListener(Event.ADDED_TO_STAGE, activate)
		}
		
		/**
		 * Internal api for the layout manager to control the width
		 * 
		 * @private
		 */		
		public function set spacerWidth(value:Number):void
		{
			_spacerWidth = value;
			spacer.width = value;
		}
		public function get spacerWidth():Number
		{
			return _spacerWidth;
		}

		private function activate(event:Event):void
		{
			this.removeEventListener(Event.ADDED_TO_STAGE, activate)
			spacer = createSpacer();
			controlBarPropertyModel.addEventListener(ControlBarPropertyChangeEvent.CONTROL_BAR_WIDTH_CHANGE_EVENT, onBarWidthChange);
			onBarWidthChange();
			this.addEventListener(Event.REMOVED_FROM_STAGE, destroy);
		}
		
		private function destroy(event:Event):void
		{
			controlBarPropertyModel.removeEventListener(ControlBarPropertyChangeEvent.CONTROL_BAR_WIDTH_CHANGE_EVENT, onBarWidthChange);
			this.removeEventListener(Event.REMOVED_FROM_STAGE, destroy);
		}
		
		private function createSpacer():Shape
		{
			var s:Shape = ShapeFactory.getRectShape(0xFFFFFF, 1);
			s.visible = false;
			addChild(s);
			s.height = 1;			
			return s;
		}
		
		private function onBarWidthChange(event:Event=null):void 
		{
			spacerWidth = LayoutUtil.getSpaceAvailible(ControlType.FLEXIBLE_SPACER, controlBarPropertyModel);			
		}
	}
}