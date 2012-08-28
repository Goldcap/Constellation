/**
* LIMELIGHT NETWORKS INCORPORATED
* Copyright 2009 Limelight Networks Incorporated
* All Rights Reserved.
*
* NOTICE:  Limelight permits you to use, modify, and distribute this file in accordance with the
* terms of the Limelight end user license agreement accompanying it.  If you have received this file from a
* source other than Limelight, then your use, modification, or distribution of it requires the prior
* written permission of Limelight.
* 
* LimeLight Video Player 2.0.0
* 
*/

package  
{


	import com.llnw.utils.MouseIdleUtil;
	import com.llnw.utils.NullUtil;
	import com.llnw.utils.CenterUtil;
	import com.llnw.utils.URIUtil;
	import com.llnw.valueObjects.StreamUrlVO;
	import com.llnw.valueObjects.StreamValues;
	import com.llnw.view.ReplayButton;
	import com.llnw.view.RestartButton;
	import com.llnw.view.VideoDisplay;
	import com.llnw.fms.LLNWConnectionManager;
	import com.llnw.view.ControlBar;

	import fl.controls.TextArea;
	import flash.display.Graphics;
	import flash.display.Loader;
	import flash.display.MovieClip;
	import flash.display.Shape;
	import flash.display.Sprite;
	import flash.events.Event;
	import flash.events.FullScreenEvent;
	import flash.events.KeyboardEvent;
	import flash.events.MouseEvent;
	import flash.display.LoaderInfo;
	import flash.display.Stage;
	import flash.display.StageAlign;
	import flash.display.StageDisplayState;
	import flash.display.StageScaleMode;
	import flash.events.TimerEvent;
	import flash.net.URLRequest;
	import flash.external.ExternalInterface;
	import flash.text.TextField;
	import flash.utils.Timer;
	/**
	 * ...
	 * @author LLNW
	 * 
	 * The Document Level class exposes the functionality of controling the comunication between 
	 * the ConnectionManager, VideoDisplay and ControlBar. Modification of the Methods found with in this class 
	 * are not recommended. 
	 * 
	 */
	public class DocumentLevel extends Sprite
	{


		private var _connectionManager:LLNWConnectionManager;
		private var _videoDisplay:VideoDisplay;
		private var _uri:String;
		private var _streamName:String;
		private var _fullURL:String;
		
		//MBR CONTENT: _connectionManager class will check the length of this array
		// Still need to decide to use 1st slot or just _streamName.
		private var streamItems:Array = new Array()//([fileName, bitRate])

		
		
		private var _streamType:String;
		//acceptable _streamType values:
		//private var _streamType:String = "MBR";
		//private var _streamType:String = "DVRStream";
		
		//private var _streamType = "Progressive";
		//private var _streamType = "LiveStream";
		//private var _streamType = "FLVSeek";
		//private var _streamType = "MOOVSeek";
		//private var _streamType = "Streaming";
		
		private var _controlBar:ControlBar;


		//SETTINGS VARIABLES - PULLED FROM FLASH VARS
		private var _autoRewind:Boolean;
		private var _buffer:String;
		private var _videoURL:String;
		private var _imageURL:String;
		private var _autoPlay:Boolean;
		private var _autoRepeat:Boolean;
		private var _playerWidth:String;
		private var _playerHeight:String; 
		private var _showImage:String;
		private var _autoHideControls:Boolean;
		private var _autoHideTime:String;
		private var _initialVolume:String;
		private var _queryTerms:String;
		private var _mbrValues:String;
		private var _bgColor:uint = 0x000000;
		
		
		//converts missing flash var values to empty strings or default values.
		private var checkString:NullUtil;
		
		/**
		 * DocumentLevel
		 * This contains the base level to instantiate a stream including controls
		 * 
		 */
		public function DocumentLevel() 
		{
			
			stage.scaleMode	= StageScaleMode.NO_SCALE;

			stage.align	= StageAlign.TOP_LEFT;
			stage.displayState = "normal";
			stage.addEventListener(FullScreenEvent.FULL_SCREEN, handleFullScreen);
			setJSEnabled();
			
			//add Connection Manager and listeners 
			_connectionManager = new LLNWConnectionManager();
			_connectionManager.addEventListener("failure", handleFailure);
			_connectionManager.addEventListener("displayMedia", handleDisplay);
			_connectionManager.addEventListener("metaDataEvent", handleMetaData);
			_connectionManager.addEventListener("debugEvent", catchDebug);
			_connectionManager.addEventListener("statsUpdated", handleStatUpdate)
			_connectionManager.addEventListener("handleFLVSeek", handleFLVSeek)
			_connectionManager.addEventListener("NETSTREAM_PLAY_STOP", netStreamStopped)
			createDebugWindow();

			stage.addEventListener(KeyboardEvent.KEY_DOWN, handleKeyDown);
			setSettings();
			loadImage()
			
			updateVideoSource(_uri)
		
			
		}
		/**
		 * Private method handleStreamTypes()
		 * Method handles the various stream types by accessing the 
		 * _streamType variable to make criticle decisions on how to handle
		 * each stream. 
		 * 
		 * _streamType set to:
		 *  - FLVSeek: Handles FLV seekable progressive videos. LLNW specific service
		 *  - MOOVSeek: Handles H264 moov formatted seekable videos.
		 *  - Progressive: Video on demand for http streamed videos.
		 *  - Streaming: for streaming videos using the rtmp protocol
		 *  - LiveStream: for streaming live video
		 *  - DVR: for streaming DVR enabled videos from FMS
		 *  - MBR: Multibitrate video streams
		 *  - LiveMBR: to do a Live multi bit rate stream
		 */
		
		private function handleStreamTypes():void {
			_connectionManager.isMBR = false;
			_connectionManager.streamType = _streamType;
			if (_streamType == "Progressive" || _streamType == "FLVSeek" || _streamType == "MOOVSeek") {
				_streamName = _fullURL;
				_connectionManager.netStreamName = _streamName;

				_connectionManager.connectHTTP(null)

			}else {
				//This case handles adding streams and their bit rates to the dynamicStream class by 
				//pulling them out of the uri. 
				if (_streamType == "MBR" || _streamType == "LiveMBR") {
					_connectionManager.isMBR = true;
					var mbrAa:Array = _mbrValues.split("|")
					for (var i:int = 0; i < mbrAa.length; i++) {
						var mbrAb:Array = mbrAa[i].split(",")
						_streamName = mbrAb[0]
						var formatted:String = stripExtenstion()
						_connectionManager.addStream(formatted, int(mbrAb[1]))
						//_connectionManager.addStream(String(mbrAb[0]),int(mbrAb[1]))
					}
					
					_connectionManager.netStreamName = "";
					_connectionManager.doConnection(_uri)
				
				}else if (_streamType == "DVRMBRStream") {
					//DISABLED
					//on hold till adobe comments on DVRCast implementation.
					_connectionManager.isMBR = true;
					
					//for testing live DVR+MBR
					_connectionManager.addStream("dvrtest1", 300)
					_connectionManager.addStream("dvrtest2", 700)
					_connectionManager.addStream("dvrtest3", 1500)
					_connectionManager.netStreamName = "dvrtest1";
					_connectionManager.doConnection(_uri)
				}else {
					if (_streamType == "Streaming" || _streamType == "LiveStream") {
						_streamName = stripExtenstion()
					}
					_connectionManager.netStreamName = _streamName;
					_connectionManager.doConnection(_uri)
				}
				
			}
		}
		/**
		 * Removes trailing extentions and adds to the front of
		 * the file name. Also checks url for a parent directory
		 * of the file name other than application and short name.
		 * 
		 * @return formattedStream
		 */
		private var urlUtil:URIUtil = new URIUtil()
		private function stripExtenstion():String {

			urlUtil.parseURI(_uri + "/" + _streamName)
			var formattedStream:String = urlUtil.sanitizedStream;
	
			return formattedStream;
		}
		
		private var h:String = ""
		
		/**
		 * Sets the settings passed in from Flashvars. Required values for video to work. 
		 */
		private function setSettings():void {
			checkString = new NullUtil();
			if (LoaderInfo(this.root.loaderInfo).parameters.mediaURL == null) {
				//if no flash var mediaURL is set, set these variables to hard code values.
				//example formatting for a live stream:
				
				//example formatting for a live MBR stream: 
				//_uri = "rtmp://fms35_liveMBR.phx.llnw.net/livembr/_definst_/livembr1.flv,500|livembr2.flv,800|livembr3.flv,1200?streamType=LiveMBR"
				//_uri = "rtmp://youruri.net/appname/_definst_/streamname.flv?streamType=Progressive"
				//_uri = "rtmp://llnwpm.fc.llnwd.net/llnwpm/_definst_/live24x7?streamType=LiveStream"
				
				_queryTerms = ""
				_autoRewind = true
				_buffer = "0" 
				_videoURL = ""
				_imageURL = ""// "llnwLogo.jpg"
				_autoPlay = true;
				_autoRepeat = true;
				_playerWidth = "480"
				_playerHeight = "300" 
				_autoHideControls = false;
				_autoHideTime = "5"
				_initialVolume = "0"
				//uncomment to test different streams, streams are listed near end of this class.
				//testButtons()
			}else{
				_uri = checkString.checkValue(LoaderInfo(this.root.loaderInfo).parameters.mediaURL)
			
				_streamName = LoaderInfo(this.root.loaderInfo).parameters.streamName;
				
				_streamType = LoaderInfo(this.root.loaderInfo).parameters.streamType;
	
				var a:Array = _uri.split("?")
				if (a.length > 1) {
					var theParams:String = a[1]
					var firstParam:String = ""
					var secondParam:String = ""
					var isFirst:Boolean = true;
					for (var i:int = 0; i < theParams.length; i++) {	
						if (isFirst) {
							firstParam +=  theParams.charAt(i);
							if (theParams.charAt(i) == "=") {
								isFirst = false;
							}
						}else {
							secondParam +=  theParams.charAt(i);
						}
					}
					var s:String = firstParam + escape(secondParam)
					_uri = a[0] + "?" + s + "&streamType=" + _streamType;
				}else{
					_uri = _uri + "?streamType=" + _streamType;
				}
				_mbrValues = checkString.checkValue(LoaderInfo(this.root.loaderInfo).parameters.mbrValues);
				
				_queryTerms = checkString.checkValue(LoaderInfo(this.root.loaderInfo).parameters.queryTerms)
				_autoRewind = checkString.checkValue(LoaderInfo(this.root.loaderInfo).parameters.autoRewind) == "true" ? true : false
				_buffer = checkString.checkValue(LoaderInfo(this.root.loaderInfo).parameters.buffer)
				_videoURL = checkString.checkValue(LoaderInfo(this.root.loaderInfo).parameters.videoURL)
				_imageURL = checkString.checkValue(LoaderInfo(this.root.loaderInfo).parameters.imageURL)
				_autoPlay = checkString.checkValue(LoaderInfo(this.root.loaderInfo).parameters.autoPlay) == "true" ? true : false;
				_autoRepeat = checkString.checkValue(LoaderInfo(this.root.loaderInfo).parameters.autoRepeat) == "true" ? true : false;
				_playerWidth = checkString.checkValue(LoaderInfo(this.root.loaderInfo).parameters.playerWidth)
				_playerHeight = checkString.checkValue(LoaderInfo(this.root.loaderInfo).parameters.playerHeight) 
				_showImage = checkString.checkValue(LoaderInfo(this.root.loaderInfo).parameters.showImage)
				_autoHideControls = checkString.checkValue(LoaderInfo(this.root.loaderInfo).parameters.autoHideControls)  == "true" ? true : false;
				_autoHideTime = checkString.checkValue(LoaderInfo(this.root.loaderInfo).parameters.autoHideTime)
				_initialVolume = checkString.checkValue(LoaderInfo(this.root.loaderInfo).parameters.initialVolume)
			
			}
		
			_fullURL = _uri

		}
		/**
		 * Method adds javascript callbacks for switching media and 
		 * resizing the video as well as controls. 
		 *  - updateVideoSource: name of the JS call to swap the current video.
		 *  - updateDimentions: name of the JS call to change the dimentions of the video.
		 */
		private function setJSEnabled() {
			if (ExternalInterface.available) {
				ExternalInterface.addCallback("updateDisplay", updateVideoSource)
				ExternalInterface.addCallback("updateDimentions", updatePlayerSize)
			}
		}
		
		/**
		 * Method handles the size of video and the controlbar. Used when changing the size via javascript.
		 * 
		 * Height and Width of the video as type string:
		 * 
		 * @param	w
		 * @param	h
		 */
		private function updatePlayerSize(w:String, h:String):void {
			_videoDisplay.height = Number(h);
			_videoDisplay.width = Number(w)
			_playerHeight = h;
			_playerWidth = w;
			_controlBar.y = _videoDisplay.height -_controlBar.height+1;
			_controlBar.controlBarPlacement(Number(w))
		}
		/**
		 * Method handles changing the video update from JS calls or swapping video.
		 * 
		 * parameter passed in is the full url and stream name plus streamType.<br/>
		 * 
		 * 
		 *  	example for streaming:
		 * 		rtmp://domainname.com/appname/streamname.flv?streamType=Streaming
		 * 
		 *   	example for live:
		 * 		rtmp://domainname.com/appname/streamname?streamType=LiveStream
		 * 
		 *  	example for FLVSeek:
		 * 		http://domainname.com/directory/flvseekname.flv?ri=300&rs=250&streamType=FLVSeek
		 * 
		 *  	example for H264Seek(aka MOOVSeek):
		 * 		http://domainname.com/directory/seekname_mp4.h264?streamType=MOOVSeek
		 * 
		 * 		example for Video on Demand:
		 * 		http://domainname.com/directory/downloadstreamname.flv?streamType=Progressive
		 * 
		 * @param	value
		 */
		private function updateVideoSource(value:String):void {
			
			setDebug("URI~~~ "+value)
			_connectionManager.buffer = Number(_buffer);

			closeConnections();
	
			urlUtil.parseURI(unescape(value))
			_connectionManager.autoPlay = _autoPlay;
	
			//pass this instance to persist querey parameters.
			_connectionManager.uriUtil = urlUtil;
			
			_streamName = ""
			var popped:Array = new Array();
			popped = value.split("?")
			var params:String = urlUtil.queryParams;
			
			_streamType = urlUtil.streamType;
		
			var myTerms:String = ""
			_uri = popped[0];
			myTerms = urlUtil.queryParams;
			trace("myTerms: "+myTerms)
			setDebug("updateVideoSource - _streamType: "+_streamType)
			if(myTerms != ""){
				_uri += "?" + myTerms;
			}
			
			trace("THIS IS URI: "+_uri)
			
			popped.push(_streamType)
			if (_streamType == "LiveStream" || _streamType == "Streaming" || _streamType == "MBR" || _streamType == "LiveMBR") {
				var theURI:String = "";
				var theStream:String = ""
			
				var urlStrip:Array = _uri.split("/")
	
				var jv:Number = 5;
				if ((urlStrip.length - 1) < 5) {
					jv = 4;
				}
				for (var j:int = 0; j < urlStrip.length; j++) {
					if (j < jv) {
						theURI += urlStrip[j];
						if (j < 4) {
							theURI+="/"
						}
					}else {
						theStream += urlStrip[j] 
						if (j < urlStrip.length-1) {
							theStream+="/"
						}
					}
	
				}
				_uri = theURI;
			
				
				var arr:Array = theStream.split("?")
				theStream = arr[0];
					if(arr.length != 1){
					_streamName = theStream + "?" + urlUtil.queryParams;
				}else {
					_streamName = theStream;
				}
				if (_streamType == "MBR" || _streamType == "LiveMBR") {
					_mbrValues = _streamName;
				}
			
				_connectionManager.streamType = _streamType;
			}

			if(_streamName != ""){
				_fullURL = _uri +"/" + _streamName
			}else {
				_fullURL = _uri;
			}
			
			handleStreamTypes();
		}

		private var firstRun:Boolean = true;
		/**
		 * Method kills the currrent net connection. 
		 */
		private function closeConnections():void { 
			if (!firstRun) {
				_connectionManager.close(); 
			} 
			firstRun = false;
		}
		
		//adds & removes video object.
		private function handleDisplay(event:Event = null):void {
			
			//unescaping the URI for a proper play call.
			_streamName = unescape(_streamName)
			setDebug("playing: " + _streamName)
			if (_videoDisplay != null) {
				removeChild(_videoDisplay)
				
		
			}
			_videoDisplay = new VideoDisplay()
			_videoDisplay.connectionManager = _connectionManager;
			_videoDisplay.addEventListener("startAtPaused", setStartState)
			_videoDisplay.addEventListener("debugEvent", catchDebug);
			_videoDisplay.autoPlay = _autoPlay;
			if (!_autoPlay) {
				_connectionManager.addEventListener("doNotAutoPlay", pauseAtStart)
			}
			
			_videoDisplay.setMedia(_streamName);
			
			if (_playerWidth != "") _videoDisplay.width = Number(_playerWidth);
			if (_playerHeight != "") _videoDisplay.height = Number(_playerHeight);
			addChildAt(_videoDisplay, 0)
			
			if (!_controlsAdded) {
				initControls();
			}
			setLargePlayIcon()
			
			changeVolume(_theVolumeLevel)
			
		}
		
		private function pauseAtStart(event:Event):void {
			
			_videoDisplay.pauseAtStart();
		}
		private function setStartState(event:Event):void {
			
			_controlBar.playingState = false;
			
		}
		private function handleFLVSeek(event:Event):void {

			_videoDisplay.updateVideo(_connectionManager.netStreamName)
		}
		private function handleMOOVSeek(event:Event):void {
			
			_videoDisplay.updateVideo(_connectionManager.netStreamName)
		}
		
		private function handleFailure(event:Event):void {
			//handle a failure, check type. Used for debugging.
		//	trace("handleFailure")
		}
		private var _metaDataObject:Object;
		/**
		 * Method handles the event return of the onMetaData of the video 
		 * requested. The values used in the object are used for getting duration,
		 * keyframe offset for FLVSeek and time offsets for MOOVSeek.
		 * 
		 * @param	event
		 */
		private function handleMetaData(event:Event):void { 
	
			
			_metaDataObject = new Object()
			/**
			 * can access the object like so: 
			 * _connectionManager.metaDataObject
			 * or with in this method:
			 * _metaDataObject = event.target.metaDataObject
			 */
			_metaDataObject = event.target.metaDataObject;
			//uncomment to view metadata
			/*for (var i:String in _metaDataObject) {
				trace(i +" : " + _metaDataObject[i])
			}*/
		}
		
		private var _controlsAdded:Boolean = false;
		private function initControls():void{
			
			_center = new CenterUtil();
			//Set Controls:
		
			_controlBar = new ControlBar();
			_controlBar.addEventListener("ProgressReleaseEvent", handleProgressClick)
			_controlBar.addEventListener("pausePlayHead", pausePlayHead)
			_controlBar.addEventListener("VolumeReleaseEvent",handleVolumeClick)
			_controlBar.addEventListener("fullScreenClicked",handleFullScreenClick)
			_controlBar.addEventListener("muteClickEvent",handleMuteClick)
			_controlBar.addEventListener("restartMedia",restartMedia)
			_controlBar.addEventListener("playClickEvent",handlePlayClick)

			_controlBar.autoHide = _autoHideControls;
			_controlBar.autoHideTime = Number(_autoHideTime);
			_controlBar.y =  Number(_playerHeight)-_controlBar.height +1//_videoDisplay.height + _videoDisplay.y;
			//pass in width of vid
			_controlBar.controlBarPlacement( Number(_playerWidth))//_videoDisplay.width)
			_theVolumeLevel = int(_initialVolume);
			_controlBar.volume = _theVolumeLevel;
			
			
			addChild(_controlBar)
			if (!_autoPlay) {
				_controlBar.playingState = _autoPlay;
			}
			initAutoHide();
			
			_controlsAdded = true;
		}
		
		private function setLargePlayIcon():void {
			if (_playIcon != null) {
				removeChild(_playIcon)
			}
			_playIcon = new ReplayButton();
			var obj:Object = new Object();
			obj.width = _playerWidth;
			obj.height = _playerHeight;
			_center.centerObj(_playIcon, obj)
			_playIcon.addEventListener(MouseEvent.CLICK, handlePlayIconClick)
			addChild(_playIcon)
		
			_playIcon.visible = !_autoPlay
			
		}
		
		private var _total:int = 0;
		private var _current:int = 0;

		private var _currentTime:int = 0;
		private var _duration:int;
	
		
		function handleProgressClick(event:Event):void {
					
			var theCurrent = _controlBar.currentValue;
			_total = _controlBar.maxValue;
			
			//currentTime = theCurrent/ total * duration;
		
			if(_streamType == "FLVSeek"){
				
				_connectionManager.seekByPercent((theCurrent/_total )* 100)
			
			}else if (_streamType == "MOOVSeek") {
				//position / scrubBar._width; 
				_connectionManager.seekByTime((theCurrent/_total)*(_metaDataObject.duration+_connectionManager.offset) );
			}else{
				_connectionManager.netStream.seek((theCurrent/_total)*_metaDataObject.duration);
			}
			
			_playHeadPause = false;
		}
		
		
		private var _playHeadPause:Boolean = false;
		private function pausePlayHead(event:Event):void { _playHeadPause = true; }
		
		
		private var _volumeValue:int;
		private var _volumeMax:int;
		private var _theVolumeLevel:int;
		/**
		 * Method to change the volume of the sound object
		 * 
		 * @param	volumeLevel
		 */
		private function changeVolume(volumeLevel:int):void { 

				_connectionManager.volume = volumeLevel; 

		}
		private function handleVolumeClick(event:Event):void { setVolume() }
		private function setVolume():void {
			_volumeMax = _controlBar.volumeMax;
			_volumeValue = _controlBar.volumeValue;
			_theVolumeLevel = _volumeValue / _volumeMax * 100;
			changeVolume(_theVolumeLevel)
		}
		
		/**
		 * Catches the event fired from the controlbar's mute button.
		 * 
		 * @param	event
		 */
		private function handleMuteClick(event:Event):void{
			var sndValue:int = 0
			if(_controlBar.mute){
				sndValue = 0;
			}else{
				sndValue = _theVolumeLevel;
			}
			_controlBar.volume = sndValue;
			changeVolume(sndValue)
		}
		
		//Displays a play icon for when auto start is turned off.
		private function handlePlayIconClick(event:MouseEvent):void {
			handlePlay();
			
			_controlBar.playingState = true;
		
			_connectionManager.netStream.resume();
			
			_playIcon.visible = false;
			
		}
		private function playIconHandler():void {

			_playIcon.visible = (_controlBar.paused == false ? true:false);
			killImage();
		}
		
		private var _playIcon:ReplayButton;
		private var _center:CenterUtil;
		
		//handles play button click.
		function handlePlayClick(event:Event):void {
			handlePlay();
		}
		private function handlePlay():void {
			if (!_autoPlay) {
				_autoPlay = true;
				handleDisplay()
			}
			if (_controlBar.paused) {
				
				_connectionManager.netStream.resume();
			}else {
				
				_connectionManager.netStream.pause();
			}
			playIconHandler();
		}
		
		private function restartMedia(event:Event):void { doRestart() }
		/**
		 * Method to handle restart of the video. Checks streamType to decipher 
		 * between MOOVSeek, FLVSeek or other to decide how to handle the restart 
		 * method.
		 */
		private function doRestart():void {
			if (_streamType == "FLVSeek") { 
				_connectionManager.seekByPercent(0);
			}else if (_streamType == "MOOVSeek"){
				_connectionManager.seekByTime(0);
			}else{
				_connectionManager.netStream.seek(0); 
			}
		}
		
		private var isBuffering:Boolean = false;
		private function handleStatUpdate(event:Event):void { setStatUpdate(); }
		/**
		 * Method handles the update of the UI. Stream time, playhead possition and buffer amount.
		 */
		private function setStatUpdate() {
			
			//trace(_connectionManager.stats.bufferLength + " / "+_connectionManager.stats.bufferTime)
			if (_streamType == "LiveStream" || _streamType == "LiveMBR") {
				_controlBar.progressBarVisible = false;
				_controlBar.timeDisplayVisible = false;

			}else {

				_controlBar.progressBarVisible = true;
				_controlBar.timeDisplayVisible = true;
				
				var offset:Number = _connectionManager.offset;
				
				if (_controlBar.bufferVisible) {
					_controlBar.setBufferUpdate(_connectionManager.stats.bytesLoaded, _connectionManager.stats.bytesTotal); 

				}
				if (!_playHeadPause) {
					
					_controlBar.setStatUpdate((_connectionManager.stats.time+offset), (_connectionManager.stats.duration+offset))
				}
				_controlBar.setTime((_connectionManager.stats.time+offset), (_connectionManager.stats.duration+offset))
				//if (_connectionManager.stats.time >= Math.floor(_connectionManager.stats.duration)) { /*handleEndView()*/ }
				var timeDifference = (_connectionManager.stats.duration - _connectionManager.stats.time)
				if (timeDifference < .08 && timeDifference > -2) {
					if(restartTimer == null){
					restartTimer = new Timer(1000,1)
					restartTimer.addEventListener(TimerEvent.TIMER_COMPLETE, doEndView,false,0,true)
					restartTimer.start()
					}
				}
			}
		}
		private function doEndView(event:TimerEvent):void {
			restartTimer.stop()
			handleEndView();
		}
		private var restartTimer:Timer;
		private function handleEndView():void {
	
			if (_autoRepeat) {
				doRestart();
			}else {
				doRestart()

				handlePlay();
			}
			restartTimer = null
		}
		
		private function netStreamStopped(event:Event):void {
			setDebug("Video has stopped")
		}
		//mouse active 
		
		private var mouseIdle:MouseIdleUtil;

		private function initAutoHide():void {

			if (_autoHideControls) {

				mouseIdle = new MouseIdleUtil()
				mouseIdle.startMouseIdle(stage, 3000)
				mouseIdle.addEventListener("MouseIdleEvent",mouseIsIdle)
				mouseIdle.addEventListener("MouseActiveEvent",mouseIsActive)
			}else{
				
			}
		}
		private function mouseIsIdle(e:Event):void{
			killControls();
		}

		private function killControls() {
			_controlBar.hide()
		}

		private function mouseIsActive(e:Event){
			activateControls()
		}
		private function activateControls(){
			_controlBar.show();
		}
		
		//FULL SCREEN HANDLER
		
		private var _savedWidth:int;
		private var _savedHeight:int;
		private var _savedSmoothing:Boolean;
		private var _savedDeblocking:int = 0;

		private var currentPos:int;

		private function handleFullScreen(event:FullScreenEvent):void {

			if (event.fullScreen) {
				
				// save values when going to fullScreen
				_savedWidth = _videoDisplay.width;
				_savedHeight = _videoDisplay.height;
				_savedSmoothing = _videoDisplay.smoothing;
				_savedDeblocking = _videoDisplay.deblocking;
				
				// set the size of the video object to the size of the stage
				// remove smoothing and deblocking to save on CPU
				_videoDisplay.width = stage.stageWidth;
				_videoDisplay.height = stage.stageHeight;
				_videoDisplay.smoothing = false;
				_videoDisplay.deblocking = 0;
				currentPos = stage.stageWidth
				_controlBar.y = Math.floor(_videoDisplay.height - _controlBar.height)+1;
				_controlBar.x = stage.x;
				
			} else {				
				_videoDisplay.width = _savedWidth;
				_videoDisplay.height = _savedHeight;
				_videoDisplay.smoothing = _savedSmoothing;
				_videoDisplay.deblocking = _savedDeblocking;
				currentPos = Math.floor(_savedWidth)
				_controlBar.y = Math.floor(_savedHeight - (_controlBar.height)+1);
				
			}
			if(_playIcon != null){
				_center.center(_playIcon, _videoDisplay)
			}
			_controlBar.controlBarPlacement(currentPos)
		}

		private function handleFullScreenClick(event:Event):void {
			try{
				var isFullScreen:Boolean = (stage.displayState == StageDisplayState.FULL_SCREEN);
				stage.displayState = isFullScreen ? StageDisplayState.NORMAL :  StageDisplayState.FULL_SCREEN;
			}catch(e:Error){
				//catch error
			}
		}
		
		//private var _imageLoader:Loader;
		private var _imageLoader:MovieClip;
		private var _iLoader:Loader;
		private function loadImage():void{
			try {
				if(_imageURL != ""){

					_imageLoader = new MovieClip();
					addChild(_imageLoader)
					
					_iLoader = new Loader();
				
					var image:URLRequest = new URLRequest(_imageURL);
					_iLoader.load(image);

					//Create fill for background :
					var child:Shape = new Shape();
					child.graphics.beginFill(_bgColor);
					child.graphics.drawRect(0, 0, stage.stageWidth, stage.stageHeight);
					child.graphics.endFill();
					_imageLoader.addChild(child);

					_imageLoader.addChild (_iLoader);
				}
			}catch(e:Error){
				//no image or bad image URL.
			}
		}
		
		private function killImage(event:Event = null):void {
			if (_imageLoader != null) {
				
				removeChild(_imageLoader)
				_imageLoader = null;
				
			}
			
		}
		//debug window code:
		private var hideValue:Number = -1000;
		private var debugText:TextArea;
		private function createDebugWindow():void {
			debugText = new TextArea();
			debugText.x = hideValue;
			debugText. width = stage.stageWidth;
			debugText.height = stage.stageHeight - 30;
			addChild(debugText);
		}
		
		
		private function handleKeyDown(event:KeyboardEvent):void {
		
			if(event.ctrlKey && event.shiftKey && event.keyCode == 39){
					debugText.x = 0;
			}
			if(event.ctrlKey && event.shiftKey && event.keyCode == 37){
				debugText.x = hideValue;
			}
			
		}	
		
		//TEST STREAM SWITCHING - Place your own streams in each slot in the array
		//this creates a clickable list to test your videos and swapping between them swapping.
		private var urlA:Array = new Array("rtmp://isf.fcod.llnwd.net/a3971/o35/test.f4v?streamType=Streaming",
											"http://test-moovseek.vo.llnwd.net/o1/brad/episodic/a4920.mp4?streamType=MOOVSeek",
											"http://llnwqa.vo.llnwd.net/o18/content/fsdemo.flv?ri=300&rs=250&streamType=FLVSeek",
											"rtmp://fcds84.lax.llnw.net/a1151/o16/ll_b_roll_150kbps.flv,150|ll_b_roll_400kbps.flv,400|ll_b_roll_700kbps.flv,700?streamType=MBR",
											"http://llnwqa.vo.llnwd.net/o18/content/DownloadSample_interview.flv?streamType=Progressive",
											"rtmp://fcds84.lax.llnw.net/a1151/o16/flashmbr/sample1_1000kbps.f4v&streamType=Streaming",
											"rtmp://llnwqa.fcod.llnwd.net/a1218/o18/StreamSample_action.flv?streamType=Streaming")
		
											
											
										
		private function testButtons():void {
			var lastY:int = 323
			for (var i:int = 0; i < urlA.length; i++) {
			
				var b:TextField = new TextField()
				
				b.text = urlA[i]
				
				b.y = lastY
				b.width = stage.stageWidth;
				addChild(b)
				b.addEventListener(MouseEvent.CLICK,swapMedia)
				lastY = b.y + 14;
			
			}

		}
		
		private function swapMedia(event:MouseEvent):void {
			updateVideoSource(event.target.text)
		}
		
		//USE These functions to handle debugging
		//Tie to a text field, store in .sol, send to reporting etc.
		private function catchDebug(event:Event):void { debug(event.target.debugMessage) }
		private function setDebug(message:String):void { debug("BASE CLASS: " + message) }
		//final debugging step
		private function debug(message:String):void { 
			
			trace(message) 
			var date = "time : " + (new Date()).toLocaleString()
			debugText.text += date+ ":\n  "+message + "\n";
		}
	}
	//end class
}