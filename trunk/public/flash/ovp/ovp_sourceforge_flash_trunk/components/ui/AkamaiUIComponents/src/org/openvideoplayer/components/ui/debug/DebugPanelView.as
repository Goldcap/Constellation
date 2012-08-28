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

package org.openvideoplayer.components.ui.debug
{
    import org.openvideoplayer.components.ui.shared.view.ButtonView;
    import org.openvideoplayer.components.ui.shared.view.LabelButton;
    import org.openvideoplayer.components.ui.shared.view.ScrollDownButton;
    import org.openvideoplayer.components.ui.shared.view.ScrollUpButton;
    
    import flash.display.Sprite;
    import flash.events.Event;
    import flash.events.MouseEvent;
    import flash.events.TimerEvent;
    import flash.system.System;
    import flash.text.TextField;
    import flash.text.TextFormat;
    import flash.text.TextFormatAlign;
    import flash.utils.Timer;
    
	/**
	 * @author Akamai Technologies, Inc 2011
	 */
    public class DebugPanelView extends Sprite
    {
        
        private var background:Sprite;
        private var clearButton:LabelButton;
        private var copyButton:LabelButton;
        private var displayPanel:TextField;
        private var scrollUpButton:ScrollUpButton;
        private var scrollDownButton:ScrollDownButton;
        private var scrollTimer:Timer;
        private var scrollDirection:String;
		
        private var bandwidthLabel:TextField;
        private var streamLabel:TextField;
        private var bufferLabel:TextField;
		
        private var _bandwidthPanel:TextField;
        private var _streamPanel:TextField;
        private var _bufferPanel:TextField;
        
        private var myWidth:Number;
		private var myHeight:Number;
		
		/**
		 * @Constructor
		 * @param width
		 * @param height
		 */		
        public function DebugPanelView(width:Number, height:Number)
        {
			myWidth = width;
			myHeight = height;	
            createChildren();
        }
		/**
		 * 
		 * @return 
		 * 
		 */		
		public function get bandwidthPanel():TextField
		{
			return _bandwidthPanel;
		}
		
		/**
		 * 
		 * @return 
		 * 
		 */		
		public function get streamPanel():TextField
		{
			return _streamPanel;
		}
		
		
		/**
		 * 
		 * @return 
		 * 
		 */		
		public function get bufferPanel():TextField
		{
			return _bufferPanel;
		}
		
		/**
		 *  
		 * @param value
		 */		
		public function log(value:String):void
		{
			displayPanel.appendText(value+"\n");
			displayPanel.scrollV = displayPanel.maxScrollV;
		}
		
		
		/**
		 * 
		 * @param e
		 * 
		 */		
		public function resize(e:Event = null):void
		{
			this.graphics.clear();
			this.graphics.beginFill(0x000000);
			this.graphics.drawRect(0, 0, myWidth, 30);
			this.graphics.beginFill(0x111111, .8);
			this.graphics.drawRect(0, 30, myWidth, myHeight - 30);
			this.graphics.endFill();
			displayPanel.y = 30;
			displayPanel.width = myWidth - 30;
			displayPanel.height = myHeight - 30;
			clearButton.x = myWidth - 100;
			clearButton.y = 6;
			copyButton.x = myWidth - 50
			copyButton.y = 6;
			bandwidthPanel.x = myWidth - 345;
			bandwidthLabel.x = myWidth - 250;
			streamPanel.x = myWidth - 345;
			streamLabel.x = myWidth - 250;
			bufferPanel.x = myWidth - 345;
			bufferLabel.x = myWidth - 250;
		}
		
        private function createChildren():void
        {
			addBackground();
			addClearButton();
			addCopyButton();
			addDisplayPanel();
            addBandwidthPanel()
			addBandwidthLabel();
			addStreamPanel();
			addStreamLabel();
			addBufferPanel();
			addBufferLabel();
			addScrollUpButton();
			addScrollDownButton();
			resize();
			
            scrollTimer = new Timer(30);
            scrollTimer.addEventListener(TimerEvent.TIMER, doAutoScroll);
        }
        
        private function toggleHandler(event:Event):void
        {
            this.visible = !this.visible;
        }
        
        private function generateButton(name:String):LabelButton
        {
            var button:LabelButton = new LabelButton(name, name, 40);
			button.textfield.text = name
			button.name = name;	
            button.addEventListener(MouseEvent.CLICK, onClick);
            return button
        }
        
        private function generateTextField():TextField
        {
            var txt:TextField = new TextField();
            /*txt.embedFonts = true;*/
            var textFormat:TextFormat = new TextFormat();
            textFormat.font = "Arial"
            textFormat.size = 11;
            textFormat.color = 0xcccccc;
            txt.defaultTextFormat = textFormat;
            txt.mouseEnabled = false;
            txt.wordWrap = true;
            txt.selectable = true;
            txt.text = "";
            txt.antiAliasType = flash.text.AntiAliasType.ADVANCED;
            return txt;
        }
		
        private function scrollDown(event:MouseEvent):void
        {
            scrollDirection = (event.currentTarget is ScrollDownButton) ? "down" : "up";
            scrollTimer.start();
        }
        
        private function scrollUp(event:MouseEvent):void
        {
            scrollTimer.stop();
        }
        
        private function doScrollUp(e:MouseEvent):void
        {
            displayPanel.scrollV -= 1;
        }
        
        private function doScrollDown(e:MouseEvent):void
        {
            displayPanel.scrollV += 1;
        }
        
        private function doAutoScroll(e:TimerEvent):void
        {
            scrollDirection == "up" ? doScrollUp(null) : doScrollDown(null);
        }
        
        
        private function onClick(event:MouseEvent):void
        {
            switch (event.currentTarget.name)
            {
                case "COPY":
                    displayPanel.stage.focus = displayPanel;
                    displayPanel.setSelection(0, displayPanel.length);
                    System.setClipboard(displayPanel.text);
                    break;
                case "CLEAR":
                    displayPanel.text = ""
                    break;
            }
        }
		
		private function addBackground():void
		{
			background = new Sprite();
			addChild(background);
		}

		private function addClearButton():void
		{
			clearButton = generateButton("CLEAR");
			addChild(clearButton);
		}

		private function addCopyButton():void
		{
			copyButton = generateButton("COPY");
			copyButton.y = 50;
			addChild(copyButton);
		}

		private function addDisplayPanel():void
		{
			displayPanel = generateTextField();
			displayPanel.x = 20;
			addChild(displayPanel);
		}
		
		private function addBandwidthPanel():void
		{
			_bandwidthPanel = generateTextField();
			bandwidthPanel.width = 300;
			bandwidthPanel.defaultTextFormat = getTextFormat();
			bandwidthPanel.y = 70;
			bandwidthPanel.text = "0 kbps";
			addChild(bandwidthPanel);
			
		}

		private function addBandwidthLabel():void
		{
			bandwidthLabel = generateTextField();
			bandwidthLabel.width = 250;
			bandwidthLabel.text = "MAXIMUM CONNECTION BANDWIDTH";
			bandwidthLabel.y = 50;
			addChild(bandwidthLabel);
		}

		private function addStreamPanel():void
		{
			_streamPanel = generateTextField();
			streamPanel.width = 300;
			streamPanel.defaultTextFormat = getTextFormat();
			streamPanel.y = 150;
			streamPanel.text = "0 kbps";
			addChild(streamPanel);
		}

		private function addStreamLabel():void
		{
			streamLabel = generateTextField();
			streamLabel.width = 250;
			streamLabel.text = "CURRENT STREAM BITRATE";
			streamLabel.y = 130;
			addChild(streamLabel);
		}

		private function addBufferPanel():void
		{
			_bufferPanel = generateTextField();
			bufferPanel.width = 300;
			bufferPanel.defaultTextFormat = getTextFormat();
			bufferPanel.y = 230;
			bufferPanel.text = "0 kbps";
			addChild(bufferPanel);
		}

		private function addBufferLabel():void
		{
			bufferLabel = generateTextField();
			bufferLabel.width = 250;
			bufferLabel.text = "CURRENT BUFFER LENGTH";
			bufferLabel.y = 210;
			addChild(bufferLabel);
		}
		
		private function addScrollUpButton():void
		{
			scrollUpButton = new ScrollUpButton();
			scrollUpButton.x = 5;
			scrollUpButton.y = 5;
			addScrollButtonListeners(scrollUpButton);
			addChild(scrollUpButton);
		}

		private function addScrollDownButton():void
		{
			scrollDownButton = new ScrollDownButton()
			scrollDownButton.x = (scrollUpButton.x*2)+ scrollUpButton.width
			scrollDownButton.y = 5;
			addScrollButtonListeners(scrollDownButton);
			addChild(scrollDownButton);
		}
		
		private function addScrollButtonListeners(instance:ButtonView):void
		{
			instance.addEventListener(MouseEvent.MOUSE_DOWN, scrollDown);
			instance.addEventListener(MouseEvent.MOUSE_UP, scrollUp);
			
		}
		
		private function getTextFormat():TextFormat
		{	
			var textFormat:TextFormat = new TextFormat();
			textFormat.font = "Arial"
			textFormat.size = 40;
			textFormat.align = TextFormatAlign.RIGHT;
			textFormat.color = 0xffffff;
			return textFormat;
		}
    }
}
