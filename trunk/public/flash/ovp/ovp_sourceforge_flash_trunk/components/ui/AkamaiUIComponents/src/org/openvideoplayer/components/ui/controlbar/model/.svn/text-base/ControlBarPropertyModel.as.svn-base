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
package org.openvideoplayer.components.ui.controlbar.model
{
    import org.openvideoplayer.components.ui.shared.event.ControlEvent;
    import org.openvideoplayer.components.ui.controlbar.event.ControlBarPropertyChangeEvent;
    import org.openvideoplayer.components.ui.shared.view.base.BaseComponent;
    
    import flash.events.EventDispatcher;
    
	[ExcludeClass]
	
	/**
	 * @author Akamai Technologies, Inc 2011
	 */	
    public class ControlBarPropertyModel extends EventDispatcher
    {
        private static var instance:ControlBarPropertyModel;
        
        private var _controlXposPadding:Number 			= 8;
        private var _controlBarWidth:Number 			= 360;
        private var _controlBarHeight:Number 			= 30;
        private var _controlBarBackgroundColor:uint 	= 0x000000;
        private var _controlBarBackgroundAlpha:Number 	= 1;
		private var _controlUpperHighlightColor:uint 	= 0xFFFFFF;
		private var _controlUpperHighlightAlpha:Number 	= .1;
		private var _controlIconColor:uint 				= 0xFFFFFF;
		private var _controlIconAlpha:Number 			= 1;
		private var _controlStrokeColor:uint 			= 0xFFFFFF;
		private var _controlStrokeAlpha:Number 			= .5;
        private var _progressBarColor:uint 				= 0x000000;
        private var _downloadProgressBarColor:uint 		= 0x666666;
        private var _sliderTrackBackgroundColor:uint 	= 0xFFFFFF;
		
        private var _controlList:Vector.<BaseComponent> = new Vector.<BaseComponent>;
        private var _muteState:String = ControlEvent.MUTE_OFF;
		
        private var _scrubBarWidth:Number;
        private var _volumeLevel:Number;
        private var _savedVolume:Number;
		
        /**
         * @private
         */
        public static function getInstance():ControlBarPropertyModel
        {
            instance = (!instance) ? new ControlBarPropertyModel : instance;
            return instance;
        }
        
		/**
		 * Constuctor
		 * @private
		 */
        public function ControlBarPropertyModel()
        {
            if (instance)
            {
                throw new Error("ControlBarPropertyModel is a singleton - use the getInstance method to grab a ref of class");
            }
        }
		
		/**
		 * @private
		 */
        public function set scrubBarWidth(value:Number):void
        {
            _scrubBarWidth = value;
            dispatchEvent(new ControlBarPropertyChangeEvent(ControlBarPropertyChangeEvent.SCURB_BAR_WIDTH_CHANGE_EVENT));
        }
		
		/**
		 * @private
		 */
        public function get scrubBarWidth():Number
        {
            return _scrubBarWidth;
        }
        
        
		/**
		 * @private
		 */
        public function set controlBarHeight(value:Number):void
        {
            _controlBarHeight = value;
        }
		
		/**
		 * @private
		 */
        public function get controlBarHeight():Number
        {
            return _controlBarHeight;
        }
                
		/**
		 * @private
		 */
        public function set controlBarWidth(value:Number):void
        {
            _controlBarWidth = value;
           	dispatchEvent(new ControlBarPropertyChangeEvent(ControlBarPropertyChangeEvent.CONTROL_BAR_WIDTH_CHANGE_EVENT));
        }
		
		/**
		 * @private
		 */
        public function get controlBarWidth():Number
        {
            return _controlBarWidth;
        }
        
		/**
		 * @private
		 */
        public function set controlBarBackgroundColor(value:Number):void
        {
            _controlBarBackgroundColor = value;
        }
		
		/**
		 * @private
		 */
        public function get controlBarBackgroundColor():Number
        {
            return _controlBarBackgroundColor;
        }
		
		/**
		 * @private
		 */
        public function set controlBarBackgroundAlpha(value:Number):void
        {
            _controlBarBackgroundAlpha = value;
        }
		
		/**
		 * @private
		 */
        public function get controlBarBackgroundAlpha():Number
        {
            return _controlBarBackgroundAlpha;
        }
        
		/**
		 * @private
		 */		
		public function set progressBarColor(value:uint):void
		{
			_progressBarColor = value;
		}
		
		/**
		 * @private
		 */
		public function get progressBarColor():uint
		{
			return _progressBarColor;
		}

		/**
		 * @private
		 */		
		public function set downloadProgressBarColor(value:uint):void
		{
			_downloadProgressBarColor = value;
		}
		
		/**
		 * @private
		 */
		public function get downloadProgressBarColor():uint
		{
			return _downloadProgressBarColor;
		}

		/**
		 * @private
		 */		
		public function set sliderTrackBackgroundColor(value:uint):void
		{
			_sliderTrackBackgroundColor = value;
		}
		
		/**
		 * @private
		 */
		public function get sliderTrackBackgroundColor():uint
		{
			return _sliderTrackBackgroundColor;
		}

		/**
		 * @private
		 */		
		public function set controlUpperHighlightColor(value:uint):void
		{
			_controlUpperHighlightColor = value;
		}
		
		/**
		 * @private
		 */
		public function get controlUpperHighlightColor():uint
		{
			return _controlUpperHighlightColor;
		}

		/**
		 * @private
		 */
		public function set controlUpperHighlightAlpha(value:Number):void
		{
			_controlUpperHighlightAlpha = value;
		}
		
		/**
		 * @private
		 */
		public function get controlUpperHighlightAlpha():Number
		{
			return _controlUpperHighlightAlpha;
		}

		/**
		 * @private
		 */
		public function set controlStrokeColor(value:uint):void
		{
			_controlStrokeColor = value;
		}
		
		/**
		 * @private
		 */
		public function get controlStrokeColor():uint
		{
			return _controlStrokeColor;
		}
		
		/**
		 * @private
		 */		
		public function set controlStrokeAlpha(value:Number):void
		{
			_controlStrokeAlpha = value;
		}
		
		/**
		 * @private
		 */
		public function get controlStrokeAlpha():Number
		{
			return _controlStrokeAlpha;
		}
		
		/**
		 * @private
		 */
		public function set controlIconColor(value:uint):void
		{
			_controlIconColor = value;
		}
		
		/**
		 * @private
		 */
		public function get controlIconColor():uint
		{
			return _controlIconColor;
		}

		/**
		 * @private
		 */
		public function set controlIconAlpha(value:Number):void
		{
			_controlIconAlpha = value;
		}
		
		/**
		 * @private
		 */
		public function get controlIconAlpha():Number
		{
			return _controlIconAlpha;
		}
		
		/**
		 * @private
		 */
		public function set muteState(value:String):void
		{
			_muteState = value;
			if (value == ControlEvent.MUTE_ON)
			{
				_savedVolume = _volumeLevel;
			}
			dispatchEvent(new ControlBarPropertyChangeEvent(ControlBarPropertyChangeEvent.MUTE_CHANGE));
		}
		
		/**
		 * @private
		 */
		public function get muteState():String
		{
			return _muteState;
		}
		
		/**
		 * @private
		 */
		public function set volumeLevel(value:Number):void
		{
			_volumeLevel = value;
		}
		
		/**
		 * @private
		 */
		public function get volumeLevel():Number
		{
			return _volumeLevel;
		}
		
		/**
		 * @private
		 */	
		public function get savedVolume():Number
		{
			return _savedVolume;
		}
		
		/**
		 * @private
		 */
		public function get controlList():Vector.<BaseComponent>
		{
			return _controlList;
		}
		
		/**
		 * @private
		 */
		public function get controlXposPadding():Number
		{
			return _controlXposPadding;
		}

    }
}