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

package model {

	import com.zeusprod.AEScrypto;
	import com.zeusprod.Log;
    import com.constellation.Watermark;
    import com.constellation.Stats;
	
	import flash.display.Stage;
	import flash.display.StageDisplayState;
	import flash.events.*;
	import flash.geom.Rectangle;
	import flash.media.SoundChannel;
	import flash.net.*;
	import flash.text.*;
	import flash.utils.getTimer;
	
	import org.openvideoplayer.events.OvpEvent;
	import org.openvideoplayer.net.OvpCuePointManager;
	
	import ui.AkamaiArial;
	import ui.ClickSound;
	
	[Event (name="cuepoint", type="org.openvideoplayer.events.OvpEvent")]
	
	/**
	 * Akamai Multi Player - a central repository for all player state data. All events for the player are dispatched by this model. Default flashvar
	 * and player properties are set here, as well as the parsing routine which identifies the type of source content being played. 
	 */
	public class Model extends EventDispatcher {

		// Declare private vars
		private var _stage:Stage;
		private var _src:String;
		private var _isOverlay:Boolean;
		private var _frameColor:String
		private var _themeColor:String
		private var _backgroundColor:String;
		private var _videoBackgroundColor:String;
		private var _controlBarOverlayColor:String;
		private var _controlbarFontColor: String;
		private var _width:Number;
		private var _height:Number;
		private var _errorMessage:String;
		private var _borderColor:Number
		private var _srcType:String;
		private var _hasPlaylist:Boolean;
		private var _startTime:Number;
		private var _updateTimeStamp:Boolean;
		private var _volume:Number;
		private var _so:SharedObject;
		private var _isLive:Boolean;
		private var _seekTarget:Number;
		private var _state:String;
		private var _itemArray:Array;
		private var _UIready:Boolean;
		private var _autoStart:Boolean;
		private var _loadImage:String;
		private var _fullscreenButtonX:Number;
		private var _fullscreenButtonY:Number;
		private var _enableFullscreen:Boolean;
		private var _controlBarVisible:Boolean;
		private var _playlistVisible:Boolean;
		private var _link:String;
		private var _embed:String;
		private var _loadMode:String;
		public var _fitMode:String;
		public var _scaleMode:String;
		private var _trailerMode:Boolean;
		private var _asTrailer:Boolean;
		private var _ticketParams:String;
		private var _appendParams:Boolean;
		private var _usePostMethod:Boolean;
		private var _useSubstitution:Boolean;
		private var _debugTrace:String;
		private var _debugConsole:Boolean;
		//private var _delaySeek:Number; // Obsolete. Used before streamLength event was properly detected
		private var _seekInto:Number;
		private var _authString:String;
		private var _aifpString:String;
		private var _slistString:String;
		private var _clickSound:ClickSound;
		private var _time: Number;
		private var _streamLength:Number;
		private var _streamDuration:Number;
		private var _isBuffering:Boolean;
		private var _bufferPercentage:Number;
		private var _maxBandwidth:Number;
		private var _currentStreamBitrate:Number;
		private var _isFullScreen:Boolean;
		private var _bytesLoaded:Number;
		private var _bytesTotal:Number
		private var _autoDynamicSwitching:Boolean;
		private var _isMultiBitrate:Boolean;
		private var _maxIndex:int;
		private var _currentIndex:int;
		private var _bufferLength:Number;
		private var _availableVideoWidth:Number;
		private var _availableVideoHeight:Number;
		private var _cuePointMgr:OvpCuePointManager;
		private var _controllerVersion:String;
		public var _cdnSource:String;
			
		//Declare private constants
		private const DEFAULT_FRAMECOLOR:String = "000000"; //"333333";
		private const DEFAULT_BACKGROUNDCOLOR:String = "000000";
		private const DEFAULT_CONTROLBAR_FONT_COLOR:String = "CCCCCC";
		private const DEFAULT_THEMECOLOR:String = "0395d3";
		private const DEFAULT_ISOVERLAY:Boolean = true;
		private const DEFAULT_SRC:String = "";
		//private const DEFAULT_SRC:String = "./data/lottery.smil";
		private const PLAYLIST_WIDTH:Number = 290;
		private const CONTROLBAR_HEIGHT:Number = 35;
		private const DEFAULT_VIDEO_BACKGROUND_COLOR:String = "000000"; //0x242424; // Used for the left and right sides of the video
		private const DEFAULT_CONTROLBAR_OVERLAY_COLOR:String = "0E0E0E"; //0x0E0E0E; - used for the color of the highlight when using "overlay" mode
		private const FONT_COLOR:Number = 0xcccccc;

		// Event constants
		public const EVENT_LOAD_UI:String = "EVENT_LOAD_UI";
		public const EVENT_NEW_SOURCE:String = "EVENT_NEW_SOURCE";
		public const EVENT_RESIZE:String = "EVENT_RESIZE";
		public const EVENT_PARSE_SRC:String = "EVENT_PARSE_SRC";
		public const EVENT_SHOW_ERROR:String = "EVENT_SHOW_ERROR";
		public const EVENT_VOLUME_CHANGE:String = "EVENT_VOLUME_CHANGE";
		public const EVENT_PROGRESS:String = "EVENT_PROGRESS";
		public const EVENT_PLAY:String = "EVENT_PLAY";
		public const EVENT_PAUSE:String = "EVENT_PAUSE";
		public const EVENT_STOP:String = "EVENT_STOP";
		public const EVENT_SEEK:String = "EVENT_SEEK";
		public const EVENT_BUFFER_FULL:String = "EVENT_BUFFER_FULL";
		public const EVENT_END_OF_ITEM:String = "EVENT_END_OF_ITEM";
		public const EVENT_PLAYLIST_ITEMS:String = "EVENT_PLAYLIST_ITEMS";
		public const EVENT_TOGGLE_PLAYLIST:String = "EVENT_TOGGLE_PLAYLIST";
		public const EVENT_SHOW_CONTROLS:String = "EVENT_SHOW_CONTROLS";
		public const EVENT_HIDE_CONTROLS:String = "EVENT_HIDE_CONTROLS";
		public const EVENT_ENABLE_CONTROLS:String = "EVENT_ENABLE_CONTROLS";
		public const EVENT_DISABLE_CONTROLS:String = "EVENT_DISABLE_CONTROLS";
		public const EVENT_TOGGLE_FULLSCREEN:String = "EVENT_TOGGLE_FULLSCREEN";
		public const EVENT_TOGGLE_LINK:String = "EVENT_TOGGLE_LINK";
		public const EVENT_HIDE_FULLSCREEN:String = "EVENT_HIDE_FULLSCREEN";
		public const EVENT_SHOW_PAUSE:String = "EVENT_SHOW_PAUSE";
		public const EVENT_TOGGLE_DEBUG:String = "EVENT_TOGGLE_DEBUG";
		public const EVENT_UPDATE_DEBUG:String = "EVENT_UPDATE_DEBUG";
		public const EVENT_CLOSE_AFTER_PREVIEW: String = "EVENT_CLOSE_AFTER_PREVIEW";
		public const EVENT_STOP_PLAYBACK: String = "EVENT_STOP_PLAYBACK";
		public const EVENT_SWITCH_UP: String = "EVENT_SWITCH_UP";
		public const EVENT_SWITCH_DOWN: String = "EVENT_SWITCH_DOWN";
		public const EVENT_TOGGLE_AUTO_SWITCH: String = "EVENT_TOGGLE_AUTO_SWITCH";
		public const EVENT_PLAY_START: String = "EVENT_PLAY_START";
		public const EVENT_AD_START:String = "EVENT_AD_START";
		public const EVENT_AD_END:String = "EVENT_AD_END";
		public const EVENT_SET_CUEPOINT_MGR:String = "EVENT_SET_CUEPOINT_MGR";
		public const EVENT_STREAM_NOT_FOUND:String = "EVENT_STREAM_NOT_FOUND";
		public const EVENT_OVPCONNECTION_CREATED:String = "OVPCONNECTION_CREATED";
		public const EVENT_OVPNETSTREAM_CREATED:String = "OVPNETSTREAM_CREATED";


		// Error constants
		public const ERROR_INVALID_PROTOCOL:String = "ERROR_INVALID_PROTOCOL";
		public const ERROR_MISSING_SRC:String = "ERROR_MISSING_SRC";
		public const ERROR_UNKNOWN_TYPE:String = "ERROR_UNKNOWN_TYPE";
		public const ERROR_FILE_NOT_FOUND:String = "ERROR_FILE_NOT_FOUND";
		public const ERROR_UNRECOGNIZED_MEDIA_ITEM_TYPE:String = "ERROR_UNRECOGNIZED_MEDIA_ITEM_TYPE";
		public const ERROR_FULLSCREEN_NOT_ALLOWED:String = "ERROR_FULLSCREEN_NOT_ALLOWED";
		public const ERROR_HTTP_LOAD_FAILED:String = "ERROR_HTTP_LOAD_FAILED";
		public const ERROR_BAD_XML:String = "ERROR_BAD_XML";
		public const ERROR_XML_NOT_BOSS:String = "ERROR_XML_NOT_BOSS";
		public const ERROR_XML_NOT_RSS:String = "ERROR_XML_NOT_RSS";
		public const ERROR_LOAD_TIME_OUT:String = "ERROR_LOAD_TIME_OUT";
		public const ERROR_LIVE_STREAM_TIMEOUT:String = "ERROR_LIVE_STREAM_TIMEOUT";
		public const ERROR_CONNECTION_REJECTED:String = "ERROR_CONNECTION_REJECTED";
		public const ERROR_CONNECTION_FAILED:String = "ERROR_CONNECTION_FAILED";
		public const ERROR_NETSTREAM_FAILED:String = "ERROR_NETSTREAM_FAILED";
		public const ERROR_TIME_OUT_CONNECTING:String = "ERROR_TIME_OUT_CONNECTING";

		// Src types
		public const TYPE_TRAILER:String = "TYPE_TRAILER";
		public const TYPE_AMD_ONDEMAND:String = "TYPE_AMD_ONDEMAND";
		public const TYPE_AMD_LIVE:String = "TYPE_AMD_LIVE";
		public const TYPE_AMD_PROGRESSIVE:String = "TYPE_AMD_PROGRESSIVE";
		public const TYPE_BOSS_STREAM:String = "TYPE_BOSS_STREAM";
		public const TYPE_BOSS_PROGRESSIVE:String = "TYPE_BOSS_PROGRESSIVE";
		public const TYPE_MEDIA_RSS:String = "TYPE_MEDIA_RSS";
		public const TYPE_MBR_SMIL:String = "TYPE_MBR_SMIL";
		public const TYPE_UNRECOGNIZED:String = "TYPE_UNRECOGNIZED";
		
		// Scale mode constants
		public const SCALE_MODE_FIT:String = "SCALE_MODE_FIT";
		public const SCALE_MODE_STRETCH:String = "SCALE_MODE_STRETCH";
		public const SCALE_MODE_NATIVE:String = "SCALE_MODE_NATIVE";
		public const SCALE_MODE_NATIVE_OR_SMALLER:String = "SCALE_MODE_NATIVE_OR_SMALLER";
		public const SCALE_MODE_FORCE:String = "SCALE_MODE_FORCE";
		

		// Load Modes
		public const LOADMODE_ON_DEMAND:String = "LOADMODE_ON_DEMAND";
		public const LOADMODE_SCHEDULED_SCREENING:String = "LOADMODE_SCHEDULED_SCREENING";
		public const LOADMODE_LIVE_STREAM:String = "LOADMODE_LIVE_STREAM";


		public function Model(flashvars:Object):void {
			init(flashvars);
		}
		
		private function init(flashvars:Object):void {
			_debugConsole = convertToBoolean(flashvars.debugConsole, false);  // Turn on logging
			
            Log.consoleLogging = _debugConsole;
			Log.consoleRect = new Rectangle(0, 0, 900, 900);
			Log.traceMsg ("version 1.16.3: Console debugging is " + _debugConsole, Log.LOG_TO_CONSOLE);
		
            _src = (flashvars.src == undefined)?DEFAULT_SRC:unescape(flashvars.src.toString());
			//Log.traceMsg ("Initial Source is:" + _src, Log.LOG_TO_CONSOLE);
		
            Log.traceMsg("Flashvars mode is " + flashvars.fitMode, Log.LOG_TO_CONSOLE);
			_isOverlay = flashvars.mode == undefined ?DEFAULT_ISOVERLAY:flashvars.mode.toString() == "overlay";
			_frameColor = flashvars.frameColor == undefined ? DEFAULT_FRAMECOLOR:flashvars.frameColor.toString();
			_controlbarFontColor = flashvars.fontColor == undefined ? DEFAULT_CONTROLBAR_FONT_COLOR:flashvars.fontColor.toString();
			_themeColor = flashvars.themeColor == undefined ? DEFAULT_THEMECOLOR:flashvars.themeColor.toString();
			_autoStart = convertToBoolean(flashvars.autostart, true);
			_loadImage = flashvars.loadImage == undefined ? "assets/defaultLoadImage.png" : flashvars.loadImage.toString();
			_enableFullscreen = convertToBoolean(flashvars.enableFullscreen, true);
			_link = flashvars.link == undefined? ""  : flashvars.link.toString();
			_embed = flashvars.embed == undefined? "" : flashvars.embed.toString();	
			_loadMode = flashvars.loadMode == undefined? "1" : flashvars.loadMode.toString();// Default to onDemand
			_fitMode = flashvars.fitMode == undefined? "fit" : flashvars.fitMode.toString();// Default to onDemand
			_trailerMode = convertToBoolean(flashvars.trailerMode, false);
			_asTrailer = convertToBoolean(flashvars.asTrailer, false);
			_backgroundColor = flashvars.backgroundColor == undefined ? DEFAULT_BACKGROUNDCOLOR:flashvars.backgroundColor.toString();
			_videoBackgroundColor = flashvars.videoBackgroundColor == undefined ? DEFAULT_VIDEO_BACKGROUND_COLOR:flashvars.videoBackgroundColor.toString();
			_controlBarOverlayColor = flashvars.controlBarOverlayColor == undefined ? DEFAULT_CONTROLBAR_OVERLAY_COLOR:flashvars.controlBarOverlayColor.toString();
		
			// Set this before encoding/decoding ticket params
			_useSubstitution = convertToBoolean(flashvars.useSubstitution, false); // by default, don't use substitution
			
			var tmpParams:String;
			tmpParams = AEScrypto.encrypt("fake|ticket|params");
				
			_ticketParams = flashvars.ticketParams == undefined? tmpParams : replaceSpacesWithPlus(flashvars.ticketParams.toString());
			Log.traceMsg ("Model: Ticket Params:" + _ticketParams, Log.LOG_TO_CONSOLE);
            
			_fullscreenButtonX = (flashvars.fullscreenButtonX == undefined) ? 15 : Number(flashvars.fullscreenButtonX.toString());
			_fullscreenButtonY = (flashvars.fullscreenButtonY == undefined) ? -50 : Number(flashvars.fullscreenButtonY.toString());
			
			Log.traceMsg ("_fullscreenButtonX is " + _fullscreenButtonX, Log.LOG_TO_CONSOLE);
			Log.traceMsg ("_fullscreenButtonY is " + _fullscreenButtonY, Log.LOG_TO_CONSOLE);
			
			AEScrypto.substitution = _useSubstitution;
			
			//_delaySeek = (flashvars.delaySeek == undefined) ? 0 : Number(flashvars.delaySeek.toString());
			_seekInto = (flashvars.seekInto == undefined) ? 0 : Number(flashvars.seekInto.toString());
				
            Watermark.startup( _ticketParams );
			Watermark.startTimer();
			
			//Stats.startup( "Snuffulufugus" );
			
			//CSRC is the CDN Source Index
			//1=Akamai
			//2=Limelight
			_cdnSource = (flashvars.csrc == undefined) ? "1" : flashvars.csrc.toString();
			Log.traceMsg ("_cdnSource is " + _cdnSource, Log.LOG_TO_CONSOLE);
			
			_authString = (flashvars.auth == undefined) ? "" : flashvars.auth.toString();
			//Log.traceMsg ("_authString is " + _authString, Log.LOG_TO_CONSOLE);
			_aifpString = (flashvars.aifp == undefined) ? "v0006" : flashvars.aifp.toString();
			//Log.traceMsg ("_aifpString is " + _aifpString, Log.LOG_TO_CONSOLE);
			if (_cdnSource == "1") {
                _slistString = "constellation/movies/" +((flashvars.slist == undefined) ? "" : flashvars.slist.toString()) + "/movie-large";
		    } else if (_cdnSource == "2") {
                _slistString = "s/movies/" +((flashvars.slist == undefined) ? "" : flashvars.slist.toString()) + "/movie-large";
            }
            Log.traceMsg ("_slistString is " + _slistString, Log.LOG_TO_CONSOLE);
			_controllerVersion = (flashvars.controllerVersion == undefined) ? "" : flashvars.controllerVersion.toString();
		    //Log.traceMsg ("_controllerVersion is " + _controllerVersion, Log.LOG_TO_CONSOLE);
			
            //mode: 'sidebyside',
			//fontColor: 'cccccc',
			//showVideo: 'false',
			//showMuteButton: 'true',

			if (_fitMode == "fit") {
		        Log.traceMsg ("Scale Mode FIT", Log.LOG_TO_CONSOLE);
				_scaleMode  = SCALE_MODE_FIT;
			} else {
				  if (_fitMode == "stretch") {
                    _scaleMode = SCALE_MODE_STRETCH;
                } else if (_fitMode == "native") {
                    _scaleMode = SCALE_MODE_NATIVE;
                } else if (_fitMode == "force") {
                    _scaleMode = SCALE_MODE_FORCE;
                } else {
                    _scaleMode = SCALE_MODE_NATIVE_OR_SMALLER;
                }
            }
			scaleMode = _scaleMode;
			
			var soName:String = "contellationtv_ovp1"; //"akamaiflashplayer";
			_so = SharedObject.getLocal(soName, "/", false);
			
			/*
            if (loadMode == LOADMODE_ON_DEMAND) {
				//Log.traceMsg ("_so.data.startTime " + _so.data.startTime, Log.LOG_TO_CONSOLE);
				startTime = (_so.data.startTime == undefined) ?  0 : _so.data.startTime;
				//Log.traceMsg ("Start time is " + startTime, Log.LOG_TO_CONSOLE);
				time = startTime;
				//Log.traceMsg ("model time is " + time, Log.LOG_TO_CONSOLE);
				//seek(startTime);
				this.addEventListener(EVENT_PROGRESS, progressHandler);
				
			} else if (loadMode == LOADMODE_SCHEDULED_SCREENING) {
				startTime = (isNaN(_seekInto)) ?  0 : _seekInto;
				Log.traceMsg ("Model: _seekInto time is " + startTime, Log.LOG_TO_CONSOLE);
				time = startTime;
				
			} else {
				Log.traceMsg ("Model: Load Mode is " + loadMode, Log.LOG_TO_CONSOLE);
			}
			*/
            startTime = (isNaN(_seekInto)) ?  0 : _seekInto;
			Log.traceMsg ("Model: seekInto is " + startTime , Log.LOG_TO_CONSOLE);
            
			_volume = (_so.data.volume == undefined) ? 5 : _so.data.volume;
			
            Log.traceMsg ("Model: Volume is " + _volume , Log.LOG_TO_CONSOLE);
			//Just in case we've muted the movie
            if (_asTrailer) {
			     Log.traceMsg ("Model: Trailer Volume is " + _volume , Log.LOG_TO_CONSOLE);
                _volume = 3;
            }
            
			_UIready = false;
			_hasPlaylist = false;
			_controlBarVisible = true;
			_playlistVisible = false;
			_debugTrace = "";
			_isBuffering  = false;
			_bufferPercentage = 0;
			_isFullScreen = false;
			_autoDynamicSwitching = true;
			_isMultiBitrate = false;
		}
		
		private function replaceSpacesWithPlus (inString:String):String {
			var SPACE:String = " ";
			var PLUS:String = "+";
			return replace (inString, SPACE, PLUS);
		}
		
		private  function replace (inString:String, replaceThis:String, withThis:String):String {
			var tmp:String = (inString.split(replaceThis)).join(withThis);
			return tmp;
		} 
		
		public function set stage(value:Stage):void
		{
			_stage = value;			
		}
		
		public function resize(w:Number,h:Number):void {
			_width = w;
			_height = h;			
			if (!_stage || _stage.displayState != StageDisplayState.FULL_SCREEN)
			{
				sendEvent(EVENT_RESIZE);
			}
		}
		
		public function start():void {
			debug("Startup");
			parseSource();
		}
		
		public function adStarted():void {
			sendEvent(EVENT_AD_START);
		}
		
		public function adEnded():void {
			sendEvent(EVENT_AD_END);
		}
		
		public function get cuePointManager():OvpCuePointManager {
			return _cuePointMgr;
		}
		
		public function set cuePointManager(_value:OvpCuePointManager):void {
			_cuePointMgr = _value;
			sendEvent(EVENT_SET_CUEPOINT_MGR);
		}
			
		public function cuePointReached(data:Object):void {
			dispatchEvent(new OvpEvent(OvpEvent.NETSTREAM_CUEPOINT, data));
		}
		
		public function metaDataReturned(data:Object):void {
			dispatchEvent(new OvpEvent(OvpEvent.NETSTREAM_METADATA, data));
		}
		
		public function streamLengthReturned(data:Object):void {
			dispatchEvent(new OvpEvent(OvpEvent.STREAM_LENGTH, data));
		}
		
		public function streamDurationReturned(data:Object):void {
			dispatchEvent(new OvpEvent(OvpEvent.STREAM_DURATION, data));
		}
		
		public function switchRequested(data:Object):void {
			dispatchEvent(new OvpEvent(OvpEvent.SWITCH_REQUESTED, data));
		}
		
		public function switchAcknowledged(data:Object):void {
			dispatchEvent(new OvpEvent(OvpEvent.SWITCH_ACKNOWLEDGED, data));
		}
		
		public function switchComplete(data:Object):void {
			dispatchEvent(new OvpEvent(OvpEvent.SWITCH_COMPLETE, data));
		}
		
		public function netStreamCreated(ns:Object, name:Object, start:Object=null, len:Object=null, reset:Object=null, 
										 dsi:Object=null):void {
			var data:Object = new Object();
			data.arguments = new Object();
			data.arguments.name = name;
			data.arguments.start = start;
			data.arguments.len = len;
			data.dsi = dsi;
			data.netStream = ns;
			
			dispatchEvent(new OvpEvent(EVENT_OVPNETSTREAM_CREATED, data));
		}
		
		public function connectionCreated(nc:Object, uri:String, ...arguments):void {
			var data:Object = new Object();
			data.ovpConnection = nc;
			data.uri = uri;
			data.arguments = arguments;
			
			dispatchEvent(new OvpEvent(EVENT_OVPCONNECTION_CREATED, data));
		}
		
		
		public function UIready(): void {
			debug("UI initialized");
			Log.traceMsg("Model: UI initialized", Log.LOG_TO_CONSOLE);
			_UIready = true;
			parseSource();
		}
		public function get isOverlay():Boolean {
			Log.traceMsg("Model: isOverlay?: " + _isOverlay, Log.VERBOSE);
			return _isOverlay;
		}
		public function set isOverlay(isOverlay:Boolean):void {
			_isOverlay = isOverlay;
			if (!isOverlay) {
				sendEvent(EVENT_SHOW_CONTROLS);
			}
		}
		public function set isMultiBitrate(isMultiBitrate:Boolean):void {
			_isMultiBitrate = isMultiBitrate;
			// Send resize event so that ui compoenents can draw the HD meter if required			
			if (!_stage || _stage.displayState != StageDisplayState.FULL_SCREEN)
			{
				sendEvent(EVENT_RESIZE);
			}			
		}
		public function get isMultiBitrate():Boolean {
			return _isMultiBitrate;
		}
		public function set currentIndex(currentIndex:int):void {
			_currentIndex = currentIndex;
		}
		public function get currentIndex():int{
			return _currentIndex;
		}
		public function set maxIndex(maxIndex:int):void {
			_maxIndex= maxIndex;
		}
		public function get maxIndex():int{
			return _maxIndex;
		}

		public function set useAutoDynamicSwitching(isAuto:Boolean):void {
			_autoDynamicSwitching = isAuto;
			sendEvent(EVENT_TOGGLE_AUTO_SWITCH);
		}
		
		public function get useAutoDynamicSwitching():Boolean {
			return _autoDynamicSwitching;
		}
		
		public function switchUp():void{
			sendEvent(EVENT_SWITCH_UP);
		}
		
		public function switchDown():void{
			sendEvent(EVENT_SWITCH_DOWN);
		}
		
		public function playStart():void{
			sendEvent(EVENT_PLAY_START);
		}
		
		public function streamNotFound():void {
			sendEvent(EVENT_STREAM_NOT_FOUND);
		}
		
		public function get seekTarget():Number{
			return _seekTarget;
		}
		
		public function set seekTarget(seekTarget:Number):void {
			_seekTarget = seekTarget;
		}
		
		public function get time():Number {
			return _time;
		}
		
		public function set time(time:Number):void {
			_time = isNaN(time) ? 0:time;
			sendEvent(EVENT_PROGRESS);
		}
		/*
		public function get delaySeek():Number {
			return _delaySeek;
		}
		*/
		public function debug(obj:String):void {
			if (obj != null && obj != "") {
				_debugTrace = "[" + getTimer() + "] " + obj.toString() + "\n" + _debugTrace;
				dispatchEvent(new Event(EVENT_UPDATE_DEBUG));
				// Too verbose
				Log.traceMsg("debugTrace: " + _debugTrace, Log.VERBOSE);
			}
		}
		
		public function get debugTrace():String {
			return _debugTrace;
		}
		
		public function get isFullScreen():Boolean {
			return _isFullScreen;
		}
		public function set isFullScreen(fullscreen:Boolean):void {
			_isFullScreen = fullscreen;
		}
		public function get isTrailer():Boolean {
			return _trailerMode;
		}
		
		public function get isLive():Boolean {
			return _isLive;
		}
		public function set isLive(isLive:Boolean):void {
			_isLive = isLive;
		}
		public function get timeAsTimeCode():String {
			return timeCode(_time);
		}
		public function get streamLengthAsTimeCode():String {
			return timeCode(_streamLength);
		}
		public function get streamLength():Number {
			return _streamLength;
		}
		public function set streamLength(streamLength:Number):void {
			Log.traceMsg ("Setting streamlength to " + streamLength, Log.LOG_TO_CONSOLE);
			_streamLength = streamLength;
		}
		public function get streamDuration():Number {
			return _streamDuration;
		}
		public function set streamDuration(streamLength:Number):void {
			Log.traceMsg ("Setting streamlength to " + streamDuration, Log.LOG_TO_CONSOLE);
			_streamDuration = streamDuration;
		}
		
		public function get volume():Number {
			return _volume;
		}
		
		public function set volume(volume:Number):void {
			_volume = volume;
			try {
				_so.data.volume = volume;
				_so.flush();
			} catch (err:Error) {
				Log.traceMsg ("Error flushing sharedobject volume " + err, Log.LOG_TO_CONSOLE);
			}
			sendEvent(EVENT_VOLUME_CHANGE);
		}
		
		private function progressHandler (evt:Event):void {
			if (loadMode == LOADMODE_ON_DEMAND && !isLive && !isNaN (time)) {
				// Record the last playing time (for on-demand streams) in the LSO
				// FIXME - fairly inefficient
				storeTimeStamp(time);

				//Log.traceMsg ("Model time is " + time, Log.LOG_TO_CONSOLE);
			}
		}
		
		public function set startTime(currentTime:Number):void {
			//Log.traceMsg ("Setting start time to " + currentTime, Log.LOG_TO_CONSOLE);
			_startTime = currentTime;
			// Don't store the time stamp in LSO.
			//sendEvent(EVENT_RECORD_STARTTIME);
		}
		
		public function get startTime():Number {
			return _startTime;
		}
		
		// Separate the setting/reading of the timestamp from the storage of the LSO value.
		// This prevents it from changing the variable after it is read from the LSO.
		public function storeTimeStamp(value:Number):void {
			// FIXME - key this to the filmID
			_so.data.startTime = value;
			try {
				_so.flush();
			} catch (err:Error) {
				Log.traceMsg ("Error flushing sharedobject - probably two videos at once " + err, Log.LOG_TO_CONSOLE);
			}
		}
		
		public function get updateTimeStamp():Boolean {
			return _updateTimeStamp;
		}
		
		public function get isBuffering():Boolean {
			return _isBuffering;
		}
		public function set isBuffering(buffer:Boolean):void {
			_isBuffering = buffer;
		}
		public function get share(): String {
			return _link;
		}
		public function get embed(): String {
			return _embed;
		}
		public function get hasShareOrEmbed():Boolean {
			return _link != "" || _embed != "";
		}
		public function get loadMode():String {
			if (_loadMode == "1") {
				return LOADMODE_ON_DEMAND;
			} else if (_loadMode == "2") {
				return LOADMODE_SCHEDULED_SCREENING;
			} else {
				return LOADMODE_LIVE_STREAM;
			}
		}
		public function get bufferPercentage():Number {
			return _bufferPercentage;
		}
		public function set bufferPercentage(percent:Number):void {
			_bufferPercentage = isNaN(percent) ? 0:Math.min(100,Math.round(percent));
		}
		public function get bufferLength():Number {
			return _bufferLength;
		}
		public function set bufferLength(length:Number):void {
			_bufferLength = length;
		}
		public function seek(target:Number):void {
			//Log.traceMsg("Seeking to " + target, Log.LOG_TO_CONSOLE);
			_seekTarget = target;
			sendEvent(EVENT_SEEK);
		}
		public function get frameColor():Number{
			return hex(_frameColor);
		}
		public function get themeColor():Number {
			return hex(_themeColor);
		}
		public function get backgroundColor():Number {
			return hex(_backgroundColor);
		}
		public function get width():Number {
			return _width;
		}
		public function get height():Number {
			return _height;
		}
		public function get availableVideoWidth():Number {
			return _availableVideoWidth;
		}
		public function get availableVideoHeight():Number {
			return _availableVideoHeight;
		}
		public function get scaleMode():String {
			return _scaleMode;
		}
		public function set scaleMode(scaleMode:String):void {
			_scaleMode = scaleMode;
		}
		public function get playlistWidth():Number {
			return PLAYLIST_WIDTH;
		}
		public function get controlbarHeight():Number {
			return CONTROLBAR_HEIGHT;
		}
		public function playClickSound():void {
			var soundChannel:SoundChannel = new SoundChannel();
			_clickSound = new ClickSound();
			soundChannel = _clickSound.play();
		}
		public function clearDebugTrace(): void {
			_debugTrace = "";
			sendEvent(EVENT_UPDATE_DEBUG);
		}
		public function get ticketParams():String {
			return _ticketParams;
		}
		public function get appendParams():Boolean {
			return _appendParams;
		}
		
		public function get usePostMethod():Boolean {
			return _usePostMethod;
		}
		
		public function get useSubstitution():Boolean {
			return _useSubstitution;
		}
		
		public function get src():String {
			return _src;
		}
		public function set src(srcVal:String):void {
			_src = srcVal;
			Log.traceMsg ("Setting srcVal to " + srcVal, Log.LOG_TO_CONSOLE); 
			parseSource();
		}
		
		public function get auth():String {
			return _authString;
		}
		public function set auth(srcVal:String):void {
			_authString = srcVal;
			Log.traceMsg ("Setting 'authString' to " + srcVal, Log.LOG_TO_CONSOLE);
		}
		
		public function get aifp():String {
			return _aifpString;
		}
		public function set aifp(srcVal:String):void {
			_aifpString = srcVal;
			Log.traceMsg ("Setting 'aifpString' to " + srcVal, Log.LOG_TO_CONSOLE);
		}
		
		public function get slist():String {
			return _slistString;
		}
		public function set slist(srcVal:String):void {
			_slistString = srcVal;
			Log.traceMsg ("Setting 'slistString' to " + srcVal, Log.LOG_TO_CONSOLE);
		}
		
		public function get controllerVersion():String {
			return _controllerVersion;
		}
		public function set controllerVersion(srcVal:String):void {
			_controllerVersion = srcVal;
			Log.traceMsg ("Setting 'slistString' to " + srcVal, Log.LOG_TO_CONSOLE);
		}
		
		public function stopPlayback():void {
			sendEvent(EVENT_STOP_PLAYBACK);
		}
		public function get playlistVisible():Boolean {
			return _playlistVisible;
		}
		public function set playlistVisible(playlistVisible:Boolean):void {
			_playlistVisible = playlistVisible;
		}
		public function get errorMessage():String {
			return _errorMessage;
		}
		public function togglePlaylist():void {
			sendEvent(EVENT_TOGGLE_PLAYLIST);
			if (!_stage || _stage.displayState != StageDisplayState.FULL_SCREEN)
			{
				sendEvent(EVENT_RESIZE);
			}
		}
		public function get videoBackgroundColor():Number {
			return hex(_videoBackgroundColor);
			//return DEFAULT_VIDEO_BACKGROUND_COLOR;
		}
		public function get controlbarOverlayColor():Number {
			return hex(_controlBarOverlayColor);
			//return DEFAULT_CONTROLBAR_OVERLAY_COLOR;
		}
		public function get defaultTextFormat():TextFormat {
			var textFormat:TextFormat=new TextFormat();
			textFormat.font= new AkamaiArial().fontName;
			textFormat.color = hex(_controlbarFontColor);
			textFormat.align = TextFormatAlign.CENTER;
			return textFormat;
			
		}
		public function get maxBandwidth():Number {
			return _maxBandwidth;
		}
		public function set maxBandwidth(bw:Number):void {
			_maxBandwidth = bw;
		}
		public function get bytesLoaded():Number {
			return _bytesLoaded;
		}
		public function set bytesLoaded(bytesLoaded:Number):void {
			_bytesLoaded = bytesLoaded;
		}
		public function get bytesTotal():Number {
			return _bytesTotal;
		}
		public function set bytesTotal(bytesTotal:Number):void {
			_bytesTotal = bytesTotal;
		}
		public function get currentStreamBitrate():Number {
			return _currentStreamBitrate;
		}
		public function set currentStreamBitrate(bitrate:Number):void {
			_currentStreamBitrate = bitrate;
		}
		public function get autoStart():Boolean {
			return _autoStart;
		}
		public function set autoStart(doStart:Boolean):void {
			_autoStart = doStart;
		}
		public function get loadImage():String {
			return _loadImage;
		}
		public function get enableFullscreen():Boolean {
			return _enableFullscreen;
		}
		public function get fullscreenButtonX():Number {
			return _fullscreenButtonX;
		}
		public function get fullscreenButtonY():Number {
			return _fullscreenButtonY;
		}
		public function get fontColor():Number {
			return FONT_COLOR;
		}
		public function get hasPlaylist():Boolean {
			return _hasPlaylist;
		}
		public function get srcType():String {
			return _srcType;
		}

		private function hex(s:String):Number {
			return parseInt("0x" + s,16);
		}
		public function progress():void {
			sendEvent(EVENT_PROGRESS);	
		}
		public function play():void {
			_autoStart = true;
			Log.traceMsg("model.play", Log.LOG_TO_CONSOLE);
			sendEvent(EVENT_PLAY);	
		}
		
		public function pause():void {
			sendEvent(EVENT_PAUSE);	
		}
		
		public function stop():void {
			sendEvent(EVENT_STOP);
		}
		
		public function enableControls():void {
			sendEvent(EVENT_ENABLE_CONTROLS);
		}
		public function disableControls():void {
			sendEvent(EVENT_DISABLE_CONTROLS);
		}
		public function showPauseButton(): void {
			sendEvent(EVENT_SHOW_PAUSE);	
		}
		public function bufferFull():void {
			_autoStart = true;
			_isBuffering = false;
			sendEvent(EVENT_BUFFER_FULL);
		}
		public function endOfItem():void {
			sendEvent(EVENT_END_OF_ITEM);
		}
		public function set playlistItems(itemArray:Array):void {
			_itemArray = itemArray;
			sendEvent(EVENT_PLAYLIST_ITEMS);
		}
		public function get playlistItems():Array {
			return _itemArray
		}
		public function playlistNotAvailable(): void {
			showError(ERROR_FULLSCREEN_NOT_ALLOWED);
			sendEvent(EVENT_HIDE_FULLSCREEN);
		}
		public function toggleFullscreen(): void {
			sendEvent(EVENT_TOGGLE_FULLSCREEN);
		}
		public function toggleDebugPanel():void {
			sendEvent(EVENT_TOGGLE_DEBUG);
		}
		public function toggleShare(): void {
			sendEvent(EVENT_TOGGLE_LINK);
			if (!_stage || _stage.displayState != StageDisplayState.FULL_SCREEN)
			{
				sendEvent(EVENT_RESIZE);
			}
		}
		public function closeAfterPreview(): void {
			sendEvent(EVENT_CLOSE_AFTER_PREVIEW);
		}
		public function showControlBar(makeVisible:Boolean):void {
		  
    		_controlBarVisible = true;
    		sendEvent(EVENT_SHOW_CONTROLS);
			/*
            if (_isOverlay) {
				if (makeVisible && !_controlBarVisible) {
					_controlBarVisible = true;
					sendEvent(EVENT_SHOW_CONTROLS);
				}
				if (!makeVisible && _controlBarVisible)  {
					_controlBarVisible = false
					sendEvent(EVENT_HIDE_CONTROLS);
				}
			}
			*/
			
		}
		private function sendEvent(event:String):void {
			switch (event) {
				case EVENT_PROGRESS:
				break;
				case EVENT_UPDATE_DEBUG:
				break;
				case EVENT_TOGGLE_DEBUG:
				break;
				case EVENT_RESIZE:
				    //_availableVideoWidth = _width;
				    //_availableVideoHeight = _height;
				    _availableVideoWidth = _width - (_isOverlay ? 0:(_hasPlaylist && _playlistVisible)? playlistWidth+6:0) - 6;
					_availableVideoHeight = _height - (_isOverlay ? 0:controlbarHeight) - 6;
					debug(event);
				break;
				default:
					debug(event);
				break;
			}
			dispatchEvent (new Event(event));
		}
		public function showError(error:String):void {
			switch (error) {
				case ERROR_INVALID_PROTOCOL:
					_errorMessage = "Only the following protocols are supported: http, rtmp, rtmpt, rtmpe, rtmpte or none. Please check the src parameter.";
					break;
				case ERROR_MISSING_SRC:
					_errorMessage = "The 'src' parameter is missing or is empty";
					break;
				case ERROR_UNKNOWN_TYPE:
					_errorMessage = "The src type cannot be indentified";
					break;
				case ERROR_FILE_NOT_FOUND:
					_errorMessage = "The file could not be found on the server";
					break;
				case ERROR_UNRECOGNIZED_MEDIA_ITEM_TYPE:
					_errorMessage = "The playlist has supplied a media item with an unrecognized mime-type";
					break;
				case ERROR_FULLSCREEN_NOT_ALLOWED:
					_errorMessage = "Sorry - Fullscreen mode is not currently allowed for this player";
					break;
				case ERROR_HTTP_LOAD_FAILED:
					_errorMessage = "The HTTP loading operation failed";
					break;
				case ERROR_BAD_XML:
					_errorMessage = "The XML returned was invalid and could not be parsed";
					break;
				case ERROR_XML_NOT_BOSS:
					_errorMessage = "The XML returned did not represent a recognized BOSS metafile";;
					break;
				case ERROR_XML_NOT_RSS:
					_errorMessage = "The XML returned does not conform to the Media RSS standard";
					break;
				case ERROR_LOAD_TIME_OUT:
					_errorMessage = "Timed-out while trying to load an asset";
					break;
				case ERROR_LIVE_STREAM_TIMEOUT:
					_errorMessage = "Timed out trying to subscribe to the live stream";
					break;
				case ERROR_CONNECTION_REJECTED:
					_errorMessage = "The connection attempt was rejected by the server";
					break;
				case ERROR_CONNECTION_FAILED:
					_errorMessage = "The underlying NetConnection failed. Playback cannot continue";
					break;
				case ERROR_NETSTREAM_FAILED:
					_errorMessage = "The underlying NetStream failed. Playback cannot continue";
					break;
				case ERROR_TIME_OUT_CONNECTING:
					_errorMessage = "Timed-out trying to establish a connection to the server";
					break;
				default:
					_errorMessage = error;
					break;
				
			}
			debug("Error: " + _errorMessage);
			Log.traceMsg("Model.showError Errormsg is " + _errorMessage + "and error is " + error, Log.LOG_TO_CONSOLE);
			sendEvent(EVENT_SHOW_ERROR);	
			
		}
		private function timeCode(sec:Number):String {
			var h:Number = Math.floor(sec/3600);
			var m:Number = Math.floor((sec%3600)/60);
			var s:Number = Math.floor((sec%3600)%60);
			return (h == 0 ? "":(h<10 ? "0"+h.toString()+":" : h.toString()+":"))+(m<10 ? "0"+m.toString() : m.toString())+":"+(s<10 ? "0"+s.toString() : s.toString());
		}
		
		public function parseSource():void {
			var error:String = "";
			if (_src == "") {
				// Wait for the player to call setNewSource(src:String)
			} else {
				var protocol:String = _src.indexOf(":") != -1 ? _src.slice(0, _src.indexOf(":")).toLowerCase():"";
				var appName:String = _src.split("/")[3];
				var extension:String;
				if (_src.indexOf("?") != -1 ) {
					var s:String = _src.slice(0, _src.indexOf("?"));
					extension = s.slice(s.lastIndexOf(".")+1);
				} else {
					extension = _src.slice(_src.lastIndexOf(".")+1);
				}
				extension = extension.toLowerCase();
				
				Log.traceMsg ("Model: protocol " + protocol, Log.LOG_TO_CONSOLE);
				Log.traceMsg ("Model: appName " + appName, Log.LOG_TO_CONSOLE);
				Log.traceMsg ("Model: extension " + extension, Log.LOG_TO_CONSOLE);
				Log.traceMsg ("Model: _src " + _src, Log.LOG_TO_CONSOLE);
				
				if ((protocol != "") && (protocol != "rtmp") && (protocol != "rtmpt") && (protocol != "rtmpte") && (protocol != "rtmpe") && (protocol != "http")) {
					error = ERROR_INVALID_PROTOCOL;
				} else if (_src.toLowerCase().indexOf("trailerfile") != -1) {
				    Log.traceMsg ("Model: Type is Trailer!", Log.LOG_TO_CONSOLE);
					_srcType = TYPE_TRAILER;
					//_trailerMode = true;
                } else if ((protocol.indexOf("rtm") != -1 && appName != "live") || (extension == "smil")) {
					_srcType = TYPE_AMD_ONDEMAND;
				} else if (protocol.indexOf("rtm") != -1 && appName == "live") {
					_srcType = TYPE_AMD_LIVE;
				} else if (protocol == "http" &&( _src.toLowerCase().indexOf("streamos.com/flash") != -1 ||  _src.toLowerCase().indexOf("edgeboss.net/flash") != -1) && (appName.toLowerCase() == "flash" || appName.toLowerCase() == "flash-live" )) {
					_srcType = TYPE_BOSS_STREAM;
				} else if (protocol == "http" && (_src.toLowerCase().indexOf("streamos.com/download") != -1 || _src.toLowerCase().indexOf("edgeboss.net/download") != -1) && appName.toLowerCase() == "download") {
					_srcType = TYPE_BOSS_PROGRESSIVE;
				} else if (protocol == "http" && (_src.toLowerCase().indexOf("genfeed.php") != -1 || extension == "" || extension == "xml" || extension == "rss")) {
					_srcType = TYPE_MEDIA_RSS;
				//} else if (extension == "smil" || (_src.toLowerCase().indexOf("theplatform") != -1  && _src.toLowerCase().indexOf("smil") != -1 )) {
				//	_srcType = TYPE_MBR_SMIL;
				} else if (protocol == "http") {
					_srcType = TYPE_AMD_PROGRESSIVE;
				} else if (protocol == "" && (extension == "rss" || extension == "xml")) {
					_srcType = TYPE_MEDIA_RSS;
				} else if (protocol == "" && (extension == "flv" || extension == "mp4" || extension == "mov" || extension == "fv4" || extension == "3gp")) {
					_srcType = TYPE_AMD_PROGRESSIVE;
				} else {
					_srcType = TYPE_UNRECOGNIZED;
					error = ERROR_UNKNOWN_TYPE;
				}
				
			
				if (error != "") {
					showError(error);
				} else {
					if (_UIready) {
						debug("Src type: " + _srcType);
						sendEvent(EVENT_NEW_SOURCE)
					} else {
						_hasPlaylist = (_srcType == TYPE_MEDIA_RSS);
						sendEvent(EVENT_LOAD_UI)
					}
				}
			}
				
		}
		
		private function convertToBoolean (val:*, defaultVal:Boolean):Boolean {
			var tmpStr:String;
			if (val is Boolean) {
				return val;
			} else if (val == undefined) {
				return defaultVal;
			} else {
				tmpStr = val.toString().toLowerCase();
				if (tmpStr == "true" || tmpStr == "t" || tmpStr == "1") {
					return true;
				} else {
					return false
				}
			
			}
		}
	}
}
