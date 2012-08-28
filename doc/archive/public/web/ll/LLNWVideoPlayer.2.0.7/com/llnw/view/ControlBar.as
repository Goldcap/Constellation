/**
* LIMELIGHT NETWORKS INCORPORATED
* Copyright 2009 Limelight Networks Incorporated
* All Rights Reserved.
*
* NOTICE:  Limelight permits you to use, modify, and distribute this file in accordance with the
* terms of the Limelight end user license agreement accompanying it.  If you have received this file from a
* source other than Limelight, then your use, modification, or distribution of it requires the prior
* written permission of Limelight.
*/
package com.llnw.view 
{
	import com.llnw.events.LLNWEvent;
	import fl.transitions.*;
	import fl.transitions.easing.*; 
	import fl.transitions.TransitionManager;
	
	import flash.display.MovieClip;
	import flash.display.Sprite;
	import flash.events.Event;
	import flash.events.MouseEvent;
	import com.llnw.view.FullScreenButton;
	import com.llnw.view.PlayPauseButton;
	import com.llnw.view.MuteButton;
	import com.llnw.view.RestartButton;
	import com.llnw.view.TimeDisplay;
	import com.llnw.view.ControlBarBG;
	import com.llnw.utils.SecondsFormatter;
//	import com.llnw.utils.OutputLogger;
	/**
	 * ...
	 * @author LLNW
	 */
	public dynamic class ControlBar extends MovieClip 
	{
		
		
	
		public function ControlBar() 
		{
			
			addLayoutItems()

			
		}
	
		private var _fullScreen_btn:FullScreenButton;
		private var _playPause_btn:PlayPauseButton;
		private var _mute_btn:MuteButton;
		private var _restart_btn:RestartButton;
		private var _timeDisplay_mc:TimeDisplay;
		private var _controlBG_mc:ControlBarBG;
		private var _playHead_mc:Scrubber;
		private var _volumeDisplay_mc:Scrubber;
		private var _formatSeconds:SecondsFormatter;
		
		private var _constantMax:int;
		private var _volumeConstantMax:int;
		
		private function addLayoutItems():void {
			
			_formatSeconds = new SecondsFormatter();
			_controlBG_mc = new ControlBarBG();
			this.addChild(_controlBG_mc)

			//Restart button
			_restart_btn = new RestartButton()
			_restart_btn.x = 5;
			_restart_btn.y = 4;
			this.addChild(_restart_btn);
			
			
			//Play pause Button
			_playPause_btn = new PlayPauseButton();
			_playPause_btn.x = 21
			_playPause_btn.y = 3
			this.addChild(_playPause_btn)
			
			//Play Head Scrubber
			_playHead_mc = new Scrubber();
			_playHead_mc.x = 50;
			_playHead_mc.y = 10;
			this.addChild(_playHead_mc)
			_playHead_mc.dimentions(260, 8)
			_constantMax = _playHead_mc.width - 2;
			
			//Time Display
			_timeDisplay_mc = new TimeDisplay();
			_timeDisplay_mc.x = 321;
			_timeDisplay_mc.y = 7;
			this.addChild(_timeDisplay_mc)
			
			//Mute Button
			_mute_btn = new MuteButton();
			_mute_btn.x = 403;
			_mute_btn.y = 10;
			this.addChild(_mute_btn)
			
			//Volume Scrubber
			_volumeDisplay_mc = new Scrubber();
			_volumeDisplay_mc.x = 415;
			_volumeDisplay_mc.y = 10;
			this.addChild(_volumeDisplay_mc)
			_volumeDisplay_mc.dimentions(40, 8)
			
			_volumeConstantMax = _volumeDisplay_mc.width;
			//Full Screen button
			_fullScreen_btn = new FullScreenButton()
			_fullScreen_btn.x = 462;
			_fullScreen_btn.y = 8;
			this.addChild(_fullScreen_btn)

			addListeners()
		}
		private function setStartState():void {
			
		}
		
		private function addListeners():void {
			_playPause_btn.addEventListener("playPauseClicked", handlePlayClick);
			_mute_btn.addEventListener("muteClicked", handleMuteClick);
			_restart_btn.addEventListener("restartClicked", restartMedia)
			_fullScreen_btn.addEventListener("fullScreenClickEvent", fullScreenClicked)
			_playHead_mc.addEventListener("scrubberReleaseEvent",handleProgressRelease)
			_playHead_mc.addEventListener("scrubberClickEvent",handleProgressClick)
			_volumeDisplay_mc.addEventListener("scrubberReleaseEvent", volumeChangeClick)
			_timeDisplay_mc.addEventListener("timeResized",updateScrubberWidth)

		}
		private var _paused:Boolean;
		public function get paused():Boolean {
			return _paused;
		}
		
		private function restartMedia(event:Event):void {

			dispatchEvent(new Event("restartMedia",true))
			//restart cookies
		}
		
		private function handlePlayClick(event:Event):void {
	
			_paused = _playPause_btn.paused;
			dispatchEvent(new Event("playClickEvent",true))
		}
		

		
		public function get mute():Boolean {
			return _mute_btn.mute;
		}
		public function set mute(value:Boolean):void {
			_mute_btn.mute = value;
		}
		
		
		private function handleMuteClick(event:Event):void {

			dispatchEvent(new Event("muteClickEvent", true))
			
		}
		
		
		private var _currentValue:int;
		public function get currentValue():int {
			
			return _currentValue;
		}
		private var _maxValue:int;
		public function get maxValue():int {
			return _maxValue;
		}
		
		private function handleProgressRelease(event:Event):void {

			_maxValue = event.target.total;
			_currentValue = event.target.scrubberPosition;
			dispatchEvent(new Event("ProgressReleaseEvent",true))
		}
		private function handleProgressClick(event:Event):void {
			//pause while mouse is down
			dispatchEvent(new Event("pausePlayHead",true))
		}
		
		

		var num:int;
		private function fullScreenClicked(event:Event):void {
			num++;
	                     
			dispatchEvent(new Event("fullScreenClicked",true))
		}

		
		private var currentPos:int;
		public function controlBarPlacement(c:int){
			currentPos = c;
			_controlBG_mc.width = c;
		
			_fullScreen_btn.x = currentPos-18
			//set locations via bar width 

			_volumeDisplay_mc.x = currentPos - 65

			_mute_btn.x = currentPos - 77

			this._timeDisplay_mc.x = currentPos - 159
			
			var barWidth:int = (_timeDisplay_mc.x - _playHead_mc.x) - 11;
			_playHead_mc.dimentions(barWidth, 8)
			_constantMax = barWidth - 2;
			//setStatUpdate()
		}
		
		private var _playState:Boolean = false;
		public function set playingState(value:Boolean):void {
			
			_playState = value;//no getter needed since this is just an inti value.
			setPausedView()
		}
		
		private function setPausedView():void {
			_playPause_btn.setState();
			
		}
		
		public function set bufferVisible(value:Boolean):void {
			_playHead_mc.bufferVisible = value;
		}
		public function get bufferVisible():Boolean {
			return _playHead_mc.bufferVisible;
		}
		public function setBufferUpdate(minValue:Number, maxValue:Number):void {
				var myBuffer:int = minValue/maxValue*_constantMax

				_playHead_mc.setBufferWidth(myBuffer)
		}
		public function setStatUpdate(minValue:int, maxValue:int):void {
			/*
			if (_playHead_mc.bufferVisible) {
				var bufferValue:int = minValue/maxValue*_constantMax
				_playHead_mc.setBufferWidth(bufferValue)
				
			}
			*/
			var myProgress:int = minValue/maxValue*_constantMax
			
			_playHead_mc.setDisplay(myProgress)
		}
		
		public function set progressBarVisible(value:Boolean):void {
			
			_playHead_mc.visible = value;
			
		}
		public function set timeDisplayVisible(value:Boolean):void {
			
			_timeDisplay_mc.visible = value;
			
		}
		
		
		public function setTime(currentTime:int, totalTime:int):void {
			_timeDisplay_mc.currentTime =  _formatSeconds.secondsToTimeFormat(currentTime);
			_timeDisplay_mc.totalTime = _formatSeconds.secondsToTimeFormat(totalTime);
			
		}
		
	
		private function updateScrubberWidth(e:Event):void {

			if (_timeDisplay_mc.width > 72) {
		
	
				_timeDisplay_mc.x = Math.floor((_mute_btn.x - _timeDisplay_mc.width) - 8);
				var endLoc:int = _timeDisplay_mc.x - 11
				
				
				var barWidth:int =  (endLoc - _playHead_mc.x)
				_playHead_mc.dimentions(barWidth, 8)
				
				_constantMax = barWidth - 2;
			}
		}
		
		private var _volumeMax:int;
		private var _volumeValue:int;
		private function volumeChangeClick(event:Event):void {
			_volumeMax = event.target.total;
			_volumeValue = event.target.scrubberPosition;
			dispatchEvent(new Event("VolumeReleaseEvent",true))
			
		}
		
		public function set volume(value:int):void {
			
			var myValue:int = value / 100 * _volumeConstantMax;
			_volumeDisplay_mc.setDisplay(myValue)
			
		}
		
		public function get volumeMax():int {
			return _volumeMax;
		}
		public function get volumeValue():int {
			return _volumeValue;
		}
		
		
		private var _autoHide:Boolean = false;
		public function set autoHide(value:Boolean) {
		
			_autoHide = value;
		}
		public function get autoHide():Boolean {
			return _autoHide;
		}
		
		
		private var _autoHideTime:int;
		public function set autoHideTime(value:int) {
			_autoHideTime = value;
		}
		public function get autoHideTimer():int {
			return _autoHideTime;
		}
		
		
		private var mTween:Tween;
		
		private var _doShow:Boolean = false;
		private function handleShowTransition():void {

			_doShow = true;
		}
		//handle show feature
		public function show():void {
			
			if(_doShow){
				mTween.removeEventListener("motionFinish",handleHideTransition)
				_doShow = false;
				mTween = new Tween(this, "alpha", None.easeNone, this.alpha, 1, 1, true);
			}
		}

		//handle hiding feature
		private function handleHideTransition(e:Event):void {
			_doHide = true;
			_doShow = true;
		}


		private var _doHide:Boolean = true; 
		public function hide():void {
			if (_doHide) {
				mTween = new Tween(this, "alpha",None.easeNone, 1, 0, 1, true);
				mTween.addEventListener("motionFinish",handleHideTransition)
				_doHide = false;
			}
		}
		
		
		
		
	}
	
}