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
package org.openvideoplayer.components.ui.spinner 
{
	
	import org.openvideoplayer.components.ui.shared.utils.ShapeFactory;
	import com.gskinner.motion.GTween;
	import com.gskinner.motion.easing.Sine;
	
	import flash.display.DisplayObject;
	import flash.display.Shape;
	import flash.display.Sprite;
	import flash.events.Event;
	import flash.events.TimerEvent;
	import flash.utils.Timer;

	/**
	 * @author Akamai Technologies, Inc 2011
	 */	
	public class Spinner extends Sprite                                       
	{
		private static const segmentWidth:Number = 5;
		
		private var _sectorInDegrees:int = 30;                                            
		private var _radius:int = 25;
		private var _segmentCorners:Number = 6;
		private var _segmentColor:Number = 0xFFFFFF;
		private var _segmentAlpha:Number = .9;
		private var _segmentHeight:Number = 12;
		private var _dispenseRate:Number = .1;
		
		private var segment:Shape;
		private var segmentDispenseTimer:Timer;
		private var count:int = 0;                                      		

		/**
		 * Constructor 
		 */		
		public function Spinner():void                                        
		{	                                                                      
			this.addEventListener(Event.REMOVED_FROM_STAGE, destroy);
			createSegmentDispenseTimer();
			start();
		}
		
		/**
		 * Use this accessor to set or get the current dispense rate 
		 * in seconds that is assigned to the spinner timer and spinner segment tween.
		 * 
		 * Default value is 0.1 seconds. 
		 * 
		 * @return 
		 * @param value
		 */		
		public function get dispenseRate():Number
		{
			return _dispenseRate;
		}
		
		public function set dispenseRate(value:Number):void
		{
			_dispenseRate = value;
		}
		
		/**		 	
		 * Set this value if you want to increase of decrese the radius of the spinners circle.
		 * 
		 * Default value is 25
		 * 
		 * @return 
		 * @param value
		 */			
		public function get radius():int
		{
			return _radius;
		}
		
		public function set radius(value:int):void
		{
			_radius = value;
		}
		
		/**
		 * sectorInDegrees is the degree value that will be used to divide the entire 360 degree circle into sectors.  
		 * Each sector will contain one spinner segments upon spinning
		 * 
		 * Change this value if you wish to see more or less segments 
		 * per each spinner revolution
		 * 
		 * Default value is 30.
		 *   
		 * @return 
		 * @param value
		 */			
		public function get sectorInDegrees():int
		{
			return _sectorInDegrees;
		}
		
		public function set sectorInDegrees(value:int):void
		{
			_sectorInDegrees = value;
		}
		
		/**
		 * Use this accessor to set the spinners segmentColor or 
		 * to get the current segmentColor value
		 * 
		 * Default is 0xFFFFFF
		 * 
		 * @return 
		 * @param value
		 */			
		public function get segmentColor():Number 
		{ 
			return _segmentColor; 
		}
		
		public function set segmentColor(value:Number):void 
		{
			_segmentColor = value;
		}
		
		/**
		 * Use this accessor to set the spinners segmentAlpha or 
		 * to get the current segmentAlpha value
		 * 
		 * Default is 0.9 
		 * 
		 * @return 
		 * @param value
		 */			
		public function get segmentAlpha():Number 
		{ 
			return _segmentAlpha; 
		}
		
		public function set segmentAlpha(value:Number):void 
		{
			_segmentAlpha = value;
		}
		
		/**
		 * Use this accessor to set the spinners segmentCorners radius or 
		 * to get the current segmentCorners value
		 * 
		 * Default is 6 pixels 
		 * 
		 * @return 
		 * @param value
		 */		
		public function get segmentCorners():Number
		{
			return _segmentCorners;
		}
		
		public function set segmentCorners(value:Number):void
		{
			_segmentCorners = value;
		}
		
		/**
		 * Use this accessor to set the spinners segmentHeight or 
		 * to get the current segmentHeight value
		 * 
		 * Default is 12 pixels
		 * 
		 * @return 
		 * @param value
		 */			
		public function get segmentHeight():Number
		{
			return _segmentHeight;
		}
		
		public function set segmentHeight(value:Number):void
		{
			_segmentHeight = value;
		}
		
		/**
		 * Starts the spinner
		 */		
		public function start():void
		{
			segmentDispenseTimer.start();
		}
		
		/**
		 * Stops the spinner 
		 */		
		public function stop():void
		{
			if (segmentDispenseTimer != null) {
                segmentDispenseTimer.stop();
			}
			count = 0;
		}
		
		/**
		 * releases elements of the spinner to garbage collection
		 * @param event
		 * 
		 */		
		public function destroy(event:Event=null):void
		{
			segmentDispenseTimer.stop();
			segmentDispenseTimer = null;
			removeEventListener(Event.REMOVED_FROM_STAGE, destroy);
		};
		
		private function addPill(event:TimerEvent):void 
		{
			var angle:Number = _sectorInDegrees*count;
			segment = getSegmentShape();
			with(segment)
			{
				x = getRadianX(angle);
				y = getRadianY(angle);
				rotation = angle;
				alpha = 0;
			}
			addEffect();
			addChild(segment);
			trackSegments();
		}
		
		private function addEffect():void
		{
			var tween:GTween = new GTween(segment, _dispenseRate);
			tween.setValues({alpha:1});
			tween.onComplete = fadeOutPill;
		};
		
		private function fadeOutPill(tween:GTween):void
		{
			var scale:Number = .8;				
			var tween:GTween = new GTween(tween.target, 1.5);
			tween.setValues({scaleX:scale, scaleY:scale, alpha:0});
			tween.ease = Sine.easeOut;
			tween.onComplete = removePill;	
		}
		
		private function removePill(tween:GTween):void
		{
			this.removeChild(tween.target as DisplayObject);
		}
		
		private function createSegmentDispenseTimer():void
		{
			segmentDispenseTimer = new Timer(_dispenseRate*1000);
			segmentDispenseTimer.addEventListener(TimerEvent.TIMER, addPill);
		}
		
		private function trackSegments():void
		{
			count++;
			if(count > ((360/_sectorInDegrees)-1))
			{
				count = 0;
			}
		}
		
		private function getSegmentShape():Shape
		{
			return ShapeFactory.getRoundedRectShape(_segmentColor, segmentWidth, _segmentHeight, _segmentCorners, _segmentAlpha);
		};	
		
		private function getRadianX (angle:Number):Number
		{
			return Math.cos ((angle-90) * Math.PI / 180) * _radius
		}
		
		private function getRadianY (angle:Number):Number
		{
			return Math.sin ((angle-90) * Math.PI / 180) * _radius
		}
	}
}

