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
	import com.llnw.fms.LLNWConnectionManager;
	import flash.display.MovieClip;
	import flash.events.Event;
	import flash.media.Video;
	import flash.net.NetStreamPlayOptions;
	
	/**
	 * ...
	 * @author LLNW 
	 */
	public class VideoDisplay extends MovieClip
	{
		private var _connectionManager:LLNWConnectionManager;
		private var _videoDisplay:Video;
		
	
		
		
		public function VideoDisplay() 
		{

		}
		/**
		 * Method sets the connectionManager object to handle displaying net streams.
		 * 
		 * @param cm: Connection Manager object
		 */
		public function set connectionManager(cm:LLNWConnectionManager):void {
			_connectionManager = cm;
		}
		/**
		 * Method creates the video object and connects the NetStream refference to display.
		 * 
		 * 
		 * @param	nsName : the stream to play
		 */
		public function setMedia(nsName:String = null):void {
			_videoDisplay = new Video()
			_videoDisplay.attachNetStream(_connectionManager.netStream);

			if (_connectionManager.isMBR) {
				_connectionManager.netStream.startPlay(_connectionManager.dsi)
	
			}else{
				_connectionManager.netStream.play(nsName)
			}
			if (!_autoPlay) {
				
				_connectionManager.netStream.seek(0)
				_connectionManager.netStream.pause();
			}
			addChild(_videoDisplay)
			
		}
		
		public function pauseAtStart():void {
		
				//_autoPlay = true;
				trace("THIS SHOULD PAUSE AT START!")
				_connectionManager.netStream.seek(0);
				_connectionManager.netStream.pause();

		}
		
		private var _autoPlay:Boolean = false;
		public function set autoPlay(value:Boolean):void {
			_autoPlay = value;
		}
		public function get autoPlay():Boolean {
			return _autoPlay
		}
		
		/**
		 * Method updates the video display with a new netstream 
		 * @param	nsName: the stream to play
		 */
		public function updateVideo(nsName:String):void {
			_videoDisplay.attachNetStream(_connectionManager.netStream);
			_connectionManager.netStream.play(nsName)
			
		}
		/**
		 * Method sets the height of the video object.
		 * 
		 * @param height : the height to set the video to
		 */
		public function set setHeight(height:int):void {
			_videoDisplay.height = height;
		}
		/**
		 * Method sets the width of the video object.
		 * 
		 * @param width : the width to set the video to
		 */
		public function set setWidth(width:int):void {
			_videoDisplay.width = width;
		}
		/**
		 * Sets the video smoothing property.
		 * 
		 * @param value : boolean
		 */
		public function set smoothing(value:Boolean):void {
			_videoDisplay.smoothing = value;
		}
		/**
		 * Gets the video smoothing property.
		 * 
		 * @return smoothing : boolean
		 */
		public function get smoothing():Boolean{
			return _videoDisplay.smoothing;
		}
		/**
		 * Sets the video deblocking value.
		 * 
		 * @param value : int
		 */
		public function set deblocking(value:int):void {
				_videoDisplay.deblocking = value;
		}
		/**
		 * Gets the video deblocking value.
		 * 
		 * @return value : int
		 */
		public function get deblocking():int {
			return _videoDisplay.deblocking;
		}
		
		
	}
	
}