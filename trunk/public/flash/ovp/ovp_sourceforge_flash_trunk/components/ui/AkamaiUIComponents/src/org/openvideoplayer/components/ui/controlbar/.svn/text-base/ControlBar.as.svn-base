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
package org.openvideoplayer.components.ui.controlbar
{
    import flash.display.Sprite;
    import flash.events.Event;
    
    import org.openvideoplayer.components.ui.controlbar.model.ControlBarPropertyModel;
    import org.openvideoplayer.components.ui.shared.view.base.BaseComponent;
    import org.openvideoplayer.components.ui.controlbar.view.ControlBarBackgroundView;
    
    /**
     * This is the main class for the ControlBar component.  This class holds all the
     * public API calls for the component and is meant to be the class that is instantiated
     * and referenced by your player.
     *
     * @author Akamai Technologies, Inc 2011
     *
     */
    public class ControlBar extends Sprite
    {
        private static const NULL_ARGUMENT_ERROR_FOR_ADD_CONTROL:String = 
			"The control type that was passed in is not a BaseComponent and is not listed in the class ControlType.as as one of the acceptable control types."
			
        private var controlBarBackgroundView:ControlBarBackgroundView;
        private var controlBarLayoutManager:ControlBarLayoutManager;
        private var controlBarPropertyModel:ControlBarPropertyModel = ControlBarPropertyModel.getInstance();
        
        /**
		 * @Constructor
		 * 
         * This will create the ControlBarLayoutManager and add the listeners
         * to handle activation and cleanup.
         */
        public function ControlBar()
        {
            controlBarLayoutManager = new ControlBarLayoutManager(this);
            this.addEventListener(Event.ADDED_TO_STAGE, activate);
            this.addEventListener(Event.REMOVED_FROM_STAGE, destroy);
        }
        
        /**
         * Use this method to add and Array of already instantiated BaseComponent
         * Controls to the Component at one time. The order of the controls in the
         * Array will determine the layout of them. If you wish to add a space or
         * spaces in your control bar between controls refer to the FlexibleSpacer
         * control to do so
         *
         * @param value Takes an array of already instantiated BaseComponent Controls
         */
        public function addControls(value:Array):void
        {
			try
			{
	            for each (var control:BaseComponent in value)
	            {
	                addControl(control);
	            }
			}catch(e:Error)
			{
				throw new ArgumentError(NULL_ARGUMENT_ERROR_FOR_ADD_CONTROL);
			}
        }
        
        /**
         * Use this method to add a single instantiated BaseComponent Control
         * to the Component. You can add a single item at anytime.
         *
         * @param value Takes an instantiated BaseComponent Controls
         */
        public function addControl(value:BaseComponent):void
        {
            if(value is BaseComponent)
			{
            	controlBarPropertyModel.controlList.push(value);
				if (!this.hasEventListener(Event.ENTER_FRAME))
				{
					this.addEventListener(Event.ENTER_FRAME, onEnterFrame);
				}
			}
			else
			{
				throw new ArgumentError(NULL_ARGUMENT_ERROR_FOR_ADD_CONTROL);
			}
        }
        
        /**
         * Use this method to get a reference to the BaseComponent Control after
         * it has been added to the control bar. You will find all the string constants
         * in the ControlType.as class.
         *
         * @param Takes ControlType string constant.
         * @return
         */
        public function getControl(type:String):BaseComponent
        {
            var baseComponent:BaseComponent;
            for each (var control:BaseComponent in controlBarPropertyModel.controlList)
            {
                if (control.type == type)
                {
					baseComponent = control as BaseComponent;
					break;
                }
            }
            return baseComponent;
        }
        
        
        /**
         * Adding functionality in before release?
         * @param value
         *
         */
        /*
        public function removeControls(value:Array):void
        {
            for each (var control:BaseComponent in value)
            {
                removeControl(control);
            }
        }
        
        public function removeControl(value:BaseComponent):void
        {
        }*/
        
        /**
         * Accessor for the width of the entire control bar. If you set this value 
         * before or after the control bar has been constructed it will automatically update 
         * all elements of the control bar.
         * 
         * Default 360
         * 
         * @param value
         * @return
         */
        override public function set width(value:Number):void
        {
            controlBarPropertyModel.controlBarWidth = value;
        }
        
        override public function get width():Number
        {
            return controlBarPropertyModel.controlBarWidth;
        }
        
        /**
         * Accessor for the height of the entire control bar. If you set this value before or 
         * after the control bar has been constructed it will automatically update all 
         * elements of the control bar. This property will not affect the height of each 
         * individual control within the control bar.
         * 
         * Default 30
         * 
         * @param value
         * @return
         */

        override public function set height(value:Number):void
        {
            controlBarPropertyModel.controlBarHeight = value;
        }
        
        override public function get height():Number
        {
            return controlBarPropertyModel.controlBarHeight;
        }

        /**
         * Accessor for the color of the highlight that covers 50% height of both the 
		 * control bar's main background shape and all the controls main backgrounds.
         * 
         * Default 0xFFFFFF
         *
         * @param value
         * @return
         */
        public function set controlUpperHighlightColor(value:uint):void
        {
            controlBarPropertyModel.controlUpperHighlightColor = value;
        }
        
        public function get controlUpperHighlightColor():uint
        {
            return controlBarPropertyModel.controlUpperHighlightColor;
        }

	   /**
       	 * Accessor for the alpha of the highlight that covers 50% height of both the 
		 * control bar's main background shape and all the controls main backgrounds.
       	 * 
       	 * Default 0.1
         * 
         * @param value
         * @return
         */
        public function set controlUpperHighlightAlpha(value:Number):void
        {
            controlBarPropertyModel.controlUpperHighlightAlpha = value;
        }
        
        public function get controlUpperHighlightAlpha():Number
        {
            return controlBarPropertyModel.controlUpperHighlightAlpha;
        }
        
        /**
         * Accessor for the background color for both the control bar's main background as well
		 * as each controls background that is added to the control bar.
         * 
         * Default 0x000000
         *
         * @param value
         * @return
         */
        public function set controlBarBackgroundColor(value:Number):void
        {
            controlBarPropertyModel.controlBarBackgroundColor = value;
        }
        
        public function get controlBarBackgroundColor():Number
        {
            return controlBarPropertyModel.controlBarBackgroundColor;
        }
        
        /**
         * Accessor for the main background alpha of the control bar. Change this 
		 * property if the you want to alter the main backgrounds's alpha transparency
		 * This property will not effect the entire alpha of the component.
		 * 
		 * Default 1
         *
         * @param value
         * @return
         */
        public function set controlBarBackgroundAlpha(value:Number):void
        {
            controlBarPropertyModel.controlBarBackgroundAlpha = value;
        }
        
        public function get controlBarBackgroundAlpha():Number
        {
            return controlBarPropertyModel.controlBarBackgroundAlpha;
        }
        
        /**
         * Accessor for the scrub bar's progress bar color. The progress bar is different from 
         * the download progress bar in that it shows the current progress of what ever the 
         * control bar is controlling.
         * 
         * Default 0x000000
         *
         * @param value
         * @return
         */
        public function set progressBarColor(value:uint):void
        {
            controlBarPropertyModel.progressBarColor = value;
        }
        
        public function get progressBarColor():uint
        {
            return controlBarPropertyModel.progressBarColor;
        }
        
        /**
         * Accessor for the scrub bar's download progress bar color. The download progress bar 
         * is used to indicate the current download progress for an item that the control bar is 
         * controlling.  An example of this would be on a progressive download video file it 
         * would show the BytesLoaded vs the BytesTotal of the FLV.
         * 
         * Default 0x666666
         *
         * @param value
         * @return
         */
        public function set downloadProgressBarColor(value:uint):void
        {
            controlBarPropertyModel.downloadProgressBarColor = value;
        }
        
        public function get downloadProgressBarColor():uint
        {
            return controlBarPropertyModel.downloadProgressBarColor;
        }
        
        /**
         * Accessor for the color of the scrub bar's background.
         * 
         * Default 0xFFFFFF
         *
         * @param value
         * @return
         */
        public function set sliderTrackBackgroundColor(value:uint):void
        {
            controlBarPropertyModel.sliderTrackBackgroundColor = value;
        }
        
        public function get sliderTrackBackgroundColor():uint
        {
            return controlBarPropertyModel.sliderTrackBackgroundColor;
        }             
        
        /**
         * Accessor for the stroke color of the control bar and all the controls.
         * 
         * Default 0xFFFFFF
         *
         * @param value
         * @return
         */
        public function set strokeColor(value:uint):void
        {
            controlBarPropertyModel.controlStrokeColor = value;
        }
        
        public function get strokeColor():uint
        {
            return controlBarPropertyModel.controlStrokeColor;
        }
        
        /**
         * Accessor for the stroke alpha of the control bar and all the controls.
         * 
         * Default 0.5
         *
         * @param value
         * @return
         */
        public function set strokeAlpha(value:Number):void
        {
            controlBarPropertyModel.controlStrokeAlpha = value;
        }
        
        public function get strokeAlpha():Number
        {
            return controlBarPropertyModel.controlStrokeAlpha;
        }
        
        /**
         * Accessor for the icon color that all the controls use.
         * 
         * Default 0xFFFFFF
         *
         * @param value
         * @return
         */
        public function set iconColor(value:uint):void
        {
            controlBarPropertyModel.controlIconColor = value;
        }
        
        public function get iconColor():uint
        {
            return controlBarPropertyModel.controlIconColor;
        }
        
        /**
         * Accessor for the icon alpha that all the controls use.
         * 
         * Default 1
         *
         * @param value
         * @return
         */
        public function set iconAlpha(value:Number):void
        {
            controlBarPropertyModel.controlIconAlpha = value;
        }
        
        public function get iconAlpha():Number
        {
            return controlBarPropertyModel.controlIconAlpha;
        }
        
        /**
         * @private
         */
        private function activate(event:Event):void
        {
            addBackground();
            this.removeEventListener(Event.ADDED_TO_STAGE, activate);
        }
        
        /**
         * @private
         */
        private function destroy( event:Event ):void
        {
            this.removeEventListener(Event.REMOVED_FROM_STAGE, destroy);
            controlBarPropertyModel.controlList.length = 0;
            controlBarPropertyModel = null;
            controlBarLayoutManager = null;
        }
        
        /**
         * @private
         */
        private function addBackground():void
        {
            controlBarBackgroundView = new ControlBarBackgroundView();
            addChildAt(controlBarBackgroundView, 0);
        }
        
        /**
         * @private
         */
        private function onEnterFrame(event:Event):void
        {
            this.removeEventListener(Event.ENTER_FRAME, onEnterFrame);
            controlBarLayoutManager.layoutControls();
        }
    }
}
