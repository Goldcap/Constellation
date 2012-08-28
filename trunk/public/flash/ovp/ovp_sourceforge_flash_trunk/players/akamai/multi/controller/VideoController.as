//
// Copyright (c) 2009-2011, the Open Video Player authors. All rights reserved.
//
// Redistribution and use in source and binary forms, with or without 
// modification, are permitted provided that the following conditions are 
// met:
//
//    * Redistributions of source code must retain the above copyright 
//notice, this list of conditions and the following disclaimer.
//    * Redistributions in binary form must reproduce the above 
//copyright notice, this list of conditions and the following 
//disclaimer in the documentation and/or other materials provided 
//with the distribution.
//    * Neither the name of the openvideoplayer.org nor the names of its 
//contributors may be used to endorse or promote products derived 
//from this software without specific prior written permission.
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

package controller{

	import com.akamai.net.*;
	import com.akamai.rss.*;
	import com.zeusprod.AEScrypto;
	import com.zeusprod.Log;
	
	import flash.display.*;
	import flash.events.*;
	import flash.external.ExternalInterface;
	import flash.media.SoundTransform;
	import flash.net.NetStream;
	import flash.utils.Timer;
	
	import model.Model;
	import controller.VideoClient;
	
	import org.openvideoplayer.events.*;
	import org.openvideoplayer.net.*;
	import org.openvideoplayer.net.dynamicstream.*;
	import org.openvideoplayer.parsers.*;
	import org.openvideoplayer.rss.*;
	import org.openvideoplayer.utilities.HTTPBandwidthEstimate;
	
	import view.VideoView;
	
	
    /**
	 * Akamai Multi Player - controller working in conjunction with the VideoView
	 */
	public class VideoController extends EventDispatcher {

		private var _model:Model;
		private var _view:VideoView;
		private var _rss:AkamaiMediaRSS;
		private var _boss:AkamaiBOSSParser;
		private var _be:HTTPBandwidthEstimate;
		private var _SMILparser:DynamicSmilParser;
		private var _a_SMILparser:DynamicSmilParser;
		private var _ak:AkamaiConnection;
		private var _streamName:String;
		private var _mustDetectBandwidth:Boolean;
		private var _successfulPort:String;
		private var _successfulProtocol:String;
		private var _connectionAuthParameters:String;
		private var _streamAuthParameters:String;
		private var _isLive:Boolean;
		private var _needsRestart:Boolean;
		private var _needsSeek:Boolean;
		private var _needsResume:Boolean;
		private var _lastConnectionKey:String;
		private var _isMultiBitrate:Boolean;
		private var _ns:AkamaiDynamicNetStream;
		public var _nsc:VideoClient;
		private var _dsi:DynamicStreamItem;
		private var _progressTimer:Timer;
		private var _adPlaying:Boolean;
		private var _startPlayPending:Boolean;
		private var _needToSetCuePointMgr:Boolean;
        
        public function VideoController(model:Model,view:VideoView):void {
			_model = model;
			_view = view;
			_adPlaying = false;
			_startPlayPending = false;
			_needToSetCuePointMgr = false;
			initializeChildren();
		}
		
		private function initializeChildren():void {
			
            _model.addEventListener(_model.EVENT_VOLUME_CHANGE, volumeChangeHandler);
			_model.addEventListener(_model.EVENT_PLAY, playHandler);
			_model.addEventListener(_model.EVENT_PAUSE, pauseHandler);
			_model.addEventListener(_model.EVENT_SEEK, seekHandler);
			_model.addEventListener(_model.EVENT_NEW_SOURCE, newSourceHandler);
			_model.addEventListener(_model.EVENT_SWITCH_UP, switchUpHandler);
			_model.addEventListener(_model.EVENT_SWITCH_DOWN, switchDownHandler);
			_model.addEventListener(_model.EVENT_TOGGLE_AUTO_SWITCH, toggleSwitchHandler);
			_model.addEventListener(_model.EVENT_AD_START, adStartHandler);
			_model.addEventListener(_model.EVENT_AD_END, adEndHandler);
			_model.addEventListener(_model.EVENT_SET_CUEPOINT_MGR, cuePointMgrSetHandler);
			
            _rss = new AkamaiMediaRSS();
			_rss.addEventListener(OvpEvent.PARSED, rssParsedHandler);
			_rss.addEventListener(OvpEvent.ERROR, parseErrorHandler);
			
            _boss = new AkamaiBOSSParser();
			_boss.addEventListener(OvpEvent.PARSED, bossParsedHandler);
			_boss.addEventListener(OvpEvent.ERROR, parseErrorHandler);
			
            _SMILparser = new DynamicSmilParser();
			_SMILparser.addEventListener(OvpEvent.PARSED, smilParsedHandler);
			_SMILparser.addEventListener(OvpEvent.RE_PARSED, smilReparsedHandler);
			_SMILparser.addEventListener(OvpEvent.ERROR, parseErrorHandler);
			
            _a_SMILparser = new DynamicSmilParser();
			_a_SMILparser.addEventListener(OvpEvent.PARSED, a_smilParsedHandler);
			_a_SMILparser.addEventListener(OvpEvent.RE_PARSED, a_smilReparsedHandler);
			_a_SMILparser.addEventListener(OvpEvent.ERROR, parseErrorHandler);
			
            _successfulPort = "any";
			_successfulProtocol = "any";
			_isLive = false;
			_lastConnectionKey = "";
			_progressTimer = new Timer(100);
			_progressTimer.addEventListener(TimerEvent.TIMER, progressHandler);

		}

		private function newSourceHandler(e:Event):void {
			//Log.traceMsg("Video Controller newSourceHandler clearing video", Log.LOG_TO_CONSOLE);
			newSourceHandler2(true);
		}
		
		private function newSourceHandler2(firstTime:Boolean):void {
			var protocol:String;
			
			_view.video.clear();
			
			//Log.traceMsg("Video Controller newSourceHandler2 and firstTime is " + firstTime, Log.LOG_TO_CONSOLE);
			
			if (_ns is NetStream) {
				//Log.traceMsg("newSourceHandler2: _ns.pause");
				_ns.pause();
			}
			_connectionAuthParameters = "";
			_streamAuthParameters = "";
			_isLive = false;
			_isMultiBitrate = false;
			
			//Log.traceMsg("Video Controller newSourceHandler2: _model.srcType: " + _model.srcType,Log.LOG_TO_CONSOLE);
			switch (_model.srcType) {
				case _model.TYPE_TRAILER :			
					Log.traceMsg ("We gots ourselves a trailer!", Log.LOG_TO_CONSOLE);
                    var host:String = _model.src.split("/")[2] + "/" + _model.src.split("/")[3];
					_streamName = _model.src.slice(_model.src.indexOf(host) + host.length + 1);
					
					if (_model.src.indexOf("?") != -1) 
					{
						Log.traceMsg ("Setting Auth Params", Log.LOG_TO_CONSOLE);
                        _connectionAuthParameters = getSecureAuthToken();
						_streamAuthParameters = getSecureAuthToken();
						 _streamName = _streamName.slice(0,_streamName.indexOf("?"));
					}
					
					protocol = _model.src.indexOf(":") != -1 ? _model.src.slice(0,_model.src.indexOf(":")).toLowerCase():"any";
					connect(host,protocol);
					break;
                case _model.TYPE_AMD_ONDEMAND :			
					
                    Log.traceMsg("VideoController Source param is: " + _model.src, Log.LOG_TO_CONSOLE);
                    _connectionAuthParameters = getSecureAuthString();
                    _streamAuthParameters = getSecureAuthString();
                    Log.traceMsg("VideoController Auth Params: "+_connectionAuthParameters, Log.LOG_TO_CONSOLE);
					
					_mustDetectBandwidth = true;
					
					// Append the ticket params, reencypted with a timestamp.
					Log.traceMsg("_SMILparser _model.ticketParams " + _model.ticketParams, Log.WARN);
					var tempSrc:String = _model.src;
					var tempParamsElements:String = "k=" + AEScrypto.reencrypt(_model.ticketParams);
					var tempCacheKiller:String = "ts=" + String((new Date()).getTime());
					
					var tempDelimiter:String = (tempSrc.indexOf("?") == -1) ? "?" : "&";
					
					tempSrc += tempDelimiter + tempCacheKiller;
					Log.traceMsg("_a_SMILparser Loading " + tempSrc, Log.LOG_TO_CONSOLE);
					
                    // Use "POST", if requested, and pass the params string, which will be converted to URLVariables
					_a_SMILparser.load(tempSrc, firstTime, tempParamsElements );
					
                    /*
                    var host:String = _model.src.split("/")[2] + "/" + _model.src.split("/")[3];
					_streamName = _model.src.slice(_model.src.indexOf(host) + host.length + 1);
					
					if (_model.src.indexOf("?") != -1) 
					{
						_connectionAuthParameters = getSecureAuthToken();
						_streamAuthParameters = getSecureAuthToken();
						 _streamName = _streamName.slice(0,_streamName.indexOf("?"));
					}
					
					if (_streamName.slice(-4) == ".flv" || _streamName.slice(-4) == ".mp3") 
					{
						_streamName = _streamName.slice(0,-4);
					}
                    */
					//protocol = _model.src.indexOf(":") != -1 ? _model.src.slice(0,_model.src.indexOf(":")).toLowerCase():"any";
					//connect(host,protocol);
					break;
				case _model.TYPE_BOSS_STREAM :
					_mustDetectBandwidth = false;
					_boss.load(_model.src);

					break;
				case _model.TYPE_MEDIA_RSS :
					_mustDetectBandwidth = false;
					_rss.load(_model.src);
					break;
				case _model.TYPE_BOSS_PROGRESSIVE :
					_streamName = _model.src;
					connect(null);
					break;
				case _model.TYPE_AMD_PROGRESSIVE :
					_streamName = _model.src;
					connect(null);
					break;
				case _model.TYPE_AMD_LIVE :
					var liveHost:String = _model.src.split("/")[2] + "/" + _model.src.split("/")[3];
					_streamName = _model.src.slice(_model.src.indexOf(liveHost) + liveHost.length + 1);
					_isLive = true;
					_model.isLive = _isLive;
					protocol = _model.src.indexOf(":") != -1 ? _model.src.slice(0,_model.src.indexOf(":")).toLowerCase():"any";
					connect(liveHost,protocol);
					break;
				case _model.TYPE_MBR_SMIL :
					_mustDetectBandwidth = false;
					//Log.traceMsg("newSourceHandler2: _model.TYPE_MBR_SMIL: ", Log.LOG_TO_CONSOLE);
			     
			        //Log.traceMsg("AUTH VAR IS " + _model.auth, Log.LOG_TO_CONSOLE);
					if (_model.auth != "") 
					{
						_connectionAuthParameters = getSecureAuthString();
                        _streamAuthParameters = getSecureAuthString();
                        Log.traceMsg("Auth Params: "+_connectionAuthParameters, Log.LOG_TO_CONSOLE);
            
					}
					
					// Append the ticket params, reencypted with a timestamp.
					//Log.traceMsg("_SMILparser _model.ticketParams " + _model.ticketParams, Log.WARN);
					var tmpSrc:String = _model.src;
					var paramsElements:String = "k=" + AEScrypto.reencrypt(_model.ticketParams);
					var cacheKiller:String = "ts=" + String((new Date()).getTime());
					
					var delimiter:String = (tmpSrc.indexOf("?") == -1) ? "?" : "&";
					
					tmpSrc += delimiter + cacheKiller;
					//Log.traceMsg("_SMILparser Loading " + tmpSrc, Log.WARN);
					
                    // Use "POST", if requested, and pass the params string, which will be converted to URLVariables
					_SMILparser.load(tmpSrc, firstTime, paramsElements );
					break;
			}

		}
		
		private function getSecureAuthToken():String
		{
			return _model.src.slice(_model.src.indexOf("?") + 1);
		}
        
        private function getSecureAuthString():String
		{
			var newStr:String = _model.slist.split("/").join("%2F");
            
            //return "auth="+_model.auth+"&aifp="+_model.aifp+"&slist="+newStr;
            if (_model._cdnSource == "1") {
                Log.traceMsg("VideoController _Auth Params:" + "auth%3D"+_model.auth+"%26aifp%3D"+_model.aifp+"%26slist%3D"+newStr, Log.LOG_TO_CONSOLE);
                return "auth%3D"+_model.auth+"%26aifp%3D"+_model.aifp+"%26slist%3D"+newStr;
            } else {
                Log.traceMsg("VideoController _Auth Params:" + "NITZ!", Log.LOG_TO_CONSOLE);
                return "";
            }
        }
		
		// Handles a successful connection
		private function connectedHandler():void {
			Log.traceMsg("VideoController connectedHandler: ", Log.LOG_TO_CONSOLE);
            
            if (! _ak || ! _ak.netConnection) {
				return;
			}

			_successfulPort = _ak.actualPort;
			_successfulProtocol = _ak.actualProtocol;

			_model.debug("Connected to " + _ak.netConnection.uri);
			_model.isLive = _ak.isLive;
            
            Log.traceMsg("VideoController connectedHandler Connected to : " + _ak.netConnection.uri, Log.LOG_TO_CONSOLE);
            	
			if (_ns != null)
			{
				_ns.close();
				setupListeners(false);
				_ns = null;
			}
			
			_ns = new AkamaiDynamicNetStream(_ak);
			
            setupListeners();

			if (_needToSetCuePointMgr) {
				cuePointMgrSetHandler(null);
			}
			
			_ns.createProgressivePauseEvents = true;

			volumeChangeHandler(null);

			_view.video.attachNetStream(_ns);
			if (_mustDetectBandwidth) {
			     //Log.traceMsg("VideoController Detecting Bandwidth: ", Log.LOG_TO_CONSOLE);
			     /*
                 _be = new HTTPBandwidthEstimate();
			     _be.addEventListener("complete", BandwidthCheck);
			     _be.addEventListener(ErrorEvent.ERROR, BandwidthCheckError);
                 _be.start( _model.src );
                 */
                 _ak.addEventListener(OvpEvent.BANDWIDTH,BandwidthCheck);
                 _ak.detectBandwidth();
                 //playStream();
			} else {
				//Log.traceMsg("VideoController Playing Stream: ", Log.LOG_TO_CONSOLE);
                playStream();
			}
			
			function setupListeners(add:Boolean=true):void
			{
				if (add)
				{
					_ns.addEventListener(NetStatusEvent.NET_STATUS, netStreamStatusHandler);
					_ns.addEventListener(OvpEvent.DEBUG, debugHandler);
					_ns.addEventListener(OvpEvent.COMPLETE, handleComplete);
					_ns.addEventListener(OvpEvent.NETSTREAM_METADATA, handleMetaData);
					_ns.addEventListener(OvpEvent.NETSTREAM_PLAYSTATUS, handleTransitionComplete);
					_ns.addEventListener(OvpEvent.STREAM_LENGTH, handleStreamLength);
					_ns.addEventListener(OvpEvent.STREAM_DURATION, handleStreamDuration);
					_ns.addEventListener(OvpEvent.NETSTREAM_CUEPOINT, handleCuePoint);
					_ns.addEventListener(OvpEvent.SUBSCRIBE_ATTEMPT, handleSubscribeAttempt);
					_ns.addEventListener(OvpEvent.SWITCH_REQUESTED, switchRequestedHandler);
					_ns.addEventListener(OvpEvent.SWITCH_ACKNOWLEDGED, switchAcknowledgedHandler);
					_ns.addEventListener(OvpEvent.SWITCH_COMPLETE, switchCompleteHandler);
				}
				else
				{
					_ns.removeEventListener(NetStatusEvent.NET_STATUS, netStreamStatusHandler);
					_ns.removeEventListener(OvpEvent.DEBUG, debugHandler);
					_ns.removeEventListener(OvpEvent.COMPLETE, handleComplete);
					_ns.removeEventListener(OvpEvent.NETSTREAM_METADATA, handleMetaData);
					_ns.removeEventListener(OvpEvent.NETSTREAM_PLAYSTATUS, handleTransitionComplete);
					_ns.removeEventListener(OvpEvent.STREAM_LENGTH, handleStreamLength);
					_ns.removeEventListener(OvpEvent.STREAM_DURATION, handleStreamDuration);
					_ns.removeEventListener(OvpEvent.NETSTREAM_CUEPOINT, handleCuePoint);
					_ns.removeEventListener(OvpEvent.SUBSCRIBE_ATTEMPT, handleSubscribeAttempt);
					_ns.removeEventListener(OvpEvent.SWITCH_REQUESTED, switchRequestedHandler);
					_ns.removeEventListener(OvpEvent.SWITCH_ACKNOWLEDGED, switchAcknowledgedHandler);
					_ns.removeEventListener(OvpEvent.SWITCH_COMPLETE, switchCompleteHandler);					
				}
			}
		}
        
        private function BandwidthCheck(e:OvpEvent):void {
        
		  //Log.traceMsg("VideoController Bandwidth Check: " + e.data.bandwidth, Log.LOG_TO_CONSOLE);
		  //Log.traceMsg("VideoController: streamCount: " + _dsi.streamCount, Log.LOG_TO_CONSOLE);
		  //Log.traceMsg("VideoController: STREAM_NAME_DISCOVERY: " + _dsi.getNameAt( 2 ), Log.LOG_TO_CONSOLE);
		  
		  //We start with the biggest Stream Rate
		  var _streamIndex = 2;
		  
		  /*
		  for (var k:int = _dsi.streamCount - 1; k >= 0; k--) {
		      Log.traceMsg("VideoController Stream Name: " + _dsi.getNameAt( k ), Log.LOG_TO_CONSOLE);
          }
           */
             
          // If the bandwidth has produced a valid value, use it
          if (e.data.bandwidth > 0) {
            for (var i:int = _dsi.streamCount - 1; i >= 0; i--) {
              //Log.traceMsg("VideoController: " + e.data.bandwidth + "(bandwidth) > " + _dsi.getRateAt(i) + "(streamrate)", Log.LOG_TO_CONSOLE);
              if (e.data.bandwidth > _dsi.getRateAt(i) ) {
                _streamIndex = i;
                break;
              }
            }
          } else {
            // Sometimes FMS returns a false 0 bandwidth result. In this
            // case, lets start with the first profile above 300kbps. 
            for (var j:int = 0; j < _dsi.streamCount; j++) {
              if (300 < _dsi.getRateAt(j) ) {
                _streamIndex = j;
                break;
              }
            }
          }
          //_streamIndex = 1;
          //Log.traceMsg("VideoController: Choosing stream: " + _streamIndex, Log.LOG_TO_CONSOLE);
		  
          //So now we need to reinitiate the process
          //Without BW Testing
          //Using auth params from our chosen stream
          _mustDetectBandwidth = false;
		  _streamName = _dsi.getNameAt( _streamIndex );
		  _streamAuthParameters = _streamName.slice(_streamName.indexOf("?") + 1);
		  _connectionAuthParameters = _streamAuthParameters;
		  //_streamName = _streamName.split(_streamAuthParameters).join("");
          //Log.traceMsg("VideoController STREAM AUTH PARAMS:" + _streamAuthParameters, Log.LOG_TO_CONSOLE);
		  
		  //We'll reconnect with our new auth params from the chosen stream,
		  //And then start that stream; This leads to the "connectedHandler"
		  //Which will just play, since _mustDetectBandwidth = false
          connect(_a_SMILparser.hostName, "any");
          //playStream();
        }
        
		private function switchRequestedHandler(e:OvpEvent):void {
			_model.switchRequested(e.data);
		}
		
		private function switchAcknowledgedHandler(e:OvpEvent):void {
			_model.switchAcknowledged(e.data);
		}
		
		private function switchCompleteHandler(e:OvpEvent):void {
			_model.switchComplete(e.data);
		}

		private function handleCuePoint(e:OvpEvent):void {
			_model.cuePointReached(e.data);
		}

		private function handleComplete(e:OvpEvent):void {
			_ns.pause();
			_ns.seek(0);
			_model.endOfItem();
		}
		private function handleTransitionComplete(e:OvpEvent):void {
			if (e.data.code == "NetStream.Play.TransitionComplete") {
				_model.currentIndex = _ns.renderingIndex;
			}
		}

		private function adStartHandler(e:Event):void {
			_adPlaying = true;
		}

		private function adEndHandler(e:Event):void {
			_adPlaying = false;
			if (_startPlayPending) {
				playStream();
			}
		}

		private function playStream():void 
		{
			//Log.traceMsg("VideoController: playStream", Log.LOG_TO_CONSOLE);     
			
			if (_adPlaying) {
				_startPlayPending = true;
				return;
			}

			_startPlayPending = false;
			if (_isMultiBitrate) {
				//Log.traceMsg("VideoController: MBR", Log.LOG_TO_CONSOLE);
     
                _model.maxIndex = _dsi.streamCount - 1;
				_ns.useFastStartBuffer = false;
				
				// Notifier function
				_model.netStreamCreated(_ns, null/*name*/, null/*start*/, null/*len*/, null/*reset*/, _dsi);

				if( _ns.liveStreamAuthParams != null && _ns.liveStreamAuthParams.length == 0 && _streamAuthParameters != null && _streamAuthParameters.length > 0)
				{
					_ns.liveStreamAuthParams = _streamAuthParameters;
				}
				
				_ns.play(_dsi);
				_progressTimer.start();
				
				if (_ak.isLive) {
					_ns.maxBufferLength = 10;
				} else {
					_ns.maxBufferLength = 15;
					_ak.requestStreamLength(_dsi.streams[0].name);
				}
			} else {
				Log.traceMsg("VideoController: PLAY", Log.LOG_TO_CONSOLE);
				Log.traceMsg("VideoController: AUTOSTART" + _model.autoStart, Log.LOG_TO_CONSOLE);
				
                //Since we're initiating a stream
                //And have already connected
                //Don't use the auth params used to connect
                //var name:String = _streamName + (_streamAuthParameters != "" ? "?" + _streamAuthParameters:"");
				var name:String = _streamName;
				
                _ns.useFastStartBuffer = ! _ak.isLive;
				_ns.maxBufferLength = 10;
				
				// Notifier function
				_model.netStreamCreated(_ns, name);
				
				_ns.play(name);
				_ns.volume = _model.autoStart ? _model.volume: 10;
				
				_progressTimer.start();
				
				if (_model.srcType == _model.TYPE_AMD_ONDEMAND || _model.srcType == _model.TYPE_BOSS_STREAM) {
					_ak.requestStreamLength(_streamName);
				}
				
			}
			
			if (_model.autoStart) {
				_model.showPauseButton();
			}
			
			if (_needsSeek) {
				_needsSeek = false;
				seekHandler(null);
			}
		}
		
		// Handles a successful stream length request
		private function handleStreamLength(e:OvpEvent):void {
			if (_dsi != null && !isNaN(_dsi.len) && _dsi.len > 0)
			{
				_model.streamLength = _dsi.len;
			}
			else
			{
				_model.streamLength = Number(e.data.streamLength);
				
			}
			_model.streamLengthReturned(e.data);
		}
		
		// Handles a successful stream length request
		private function handleStreamDuration(e:OvpEvent):void {
			_model.streamDuration = Number(e.data.streamDuration);
			_model.streamDurationReturned(e.data);
		}
		
		// Updates the UI elements as the  video plays
		private function progressHandler(e:TimerEvent):void {
			if (_ns && (_ns is AkamaiDynamicNetStream) && _ns.netConnection && (_ns.netConnection.connected)) {
				_model.time = _ns.time;
				_model.bufferPercentage = _ns.bufferLength * 100 / _ns.bufferTime;
				_model.bytesLoaded = _ns.bytesLoaded;
				_model.bytesTotal = _ns.bytesTotal;
				_model.bufferLength = _ns.bufferLength;
				if (_isMultiBitrate) {
					_model.maxBandwidth = Math.round(_ns.maxBandwidth);
					_model.currentStreamBitrate = Math.round(_dsi.getRateAt(_model.currentIndex));
				} else if (_model.srcType == _model.TYPE_AMD_ONDEMAND || _model.srcType == _model.TYPE_AMD_LIVE || _model.srcType == _model.TYPE_BOSS_STREAM) {
					_model.maxBandwidth = Math.round(_ns.info.maxBytesPerSecond * 8 / 1024);
					_model.currentStreamBitrate = Math.round(_ns.info.playbackBytesPerSecond * 8 / 1024);
				}
				_view.invokeResize();
			}
		}

		// Handles netstream status events. We trap the buffer full event
		// in order to start updating our slider again, to prevent the
		// slider bouncing after a drag.
		
		private var netStreamFirstPlay:Boolean;
		
		private function netStreamStatusHandler(e:NetStatusEvent):void 
		{
			_model.debug(e.info.code + " at stream time " + _ns.time);
			switch (e.info.code) 
			{
				case "NetStream.Play.StreamNotFound" :
					_model.streamNotFound();
					break;
				case "NetStream.Buffer.Full" :
					if (! _model.autoStart) {
						pauseHandler(null);
						_ns.volume = _model.volume;
						_needsRestart = true;
						_ns.close();
						_ak.close();
						_model.closeAfterPreview();
					}
					_view.showVideo();
					_model.bufferFull();
					break;
				case "NetStream.Buffer.Flush" :
					_model.bufferFull();
					break;
				case "NetStream.Play.Start" :
					_model.isBuffering = true;
					
					if (_ns.isProgressive) {
						_model.playStart();
					}
					break;
				case "NetStream.Play.Reset" :
					if (! _ns.isProgressive) {
						_model.playStart();
					}
					break;
				case "NetStream.Play.Transition" :
					_model.debug("Transition to new stream starting ...");
					break;
				/*
					TEMP: This was added to resume the play on a live stream when there is an on the init play call. 
					This issue seems to have been injected in player version Flash Player 10 - 10_1_102_64 Mac - OS 10.6.3+
					Adobe Bug Number http://bugs.adobe.com/jira/browse/FP-5847				
					Uncommnet code below if you are experience this issue with live streams ONLY!
				*/				
				/*************************************************/
				/******** ADDED BY AMADSEN 5/16/2011 *************/
                /*
                case "NetStream.Play.PublishNotify" :
					if(!netStreamFirstPlay)
					{
						netStreamFirstPlay = true;
						_ns.resume();
					}
					break;
				*/
			}
		}

		// Handles NetConnection status events. 
		private function netStatusHandler(e:NetStatusEvent):void {
			_model.debug(e.info.code);
			switch (e.info.code) {
				case "NetConnection.Connect.IdleTimeOut" :
					_needsRestart = true;
					pauseHandler(null);
					break;
				case "NetConnection.Connect.Closed" :
					_needsRestart = true;
					pauseHandler(null);
					break;
				case "NetConnection.Connect.Success" :
					connectedHandler();
					break;
			}
		}
		// Handles metadata that is released by the stream
		private function handleMetaData(e:OvpEvent):void {
			if (_view != null && _view.stage != null && _view.stage.displayState != StageDisplayState.FULL_SCREEN) {
				_view.scaleVideo(Number(e.data["width"]), Number(e.data["height"]));
			}
			_model.metaDataReturned(e.data);
		}

		private function volumeChangeHandler(e:Event):void {
		    //Log.traceMsg ("VideoController VolumeChange: " + _model.volume, Log.LOG_TO_CONSOLE);
        	_ns.soundTransform = new SoundTransform(_model.volume);
        	if (ExternalInterface.available) {
				try {
					ExternalInterface.call( "videoplayer.setVolume" , _model.volume );
				} catch (e:Error) {
					// don't notify the user since ExternalInterface is not necessary for standard operation;
				}
			}
		}
		private function switchUpHandler(e:Event):void {
			//Log.traceMsg ("VideoController.Switching up", Log.LOG_TO_CONSOLE);
			//newSourceHandler2(false);	// Avoid restart
			_ns.switchUp();
		}
		private function switchDownHandler(e:Event):void {
			//Log.traceMsg ("VideoController.Switching down", Log.LOG_TO_CONSOLE);
			//newSourceHandler2(false);	// Avoid restart
			_ns.switchDown();
		}
		private function toggleSwitchHandler(e:Event):void {
			_ns.useManualSwitchMode(!_model.useAutoDynamicSwitching);
		}


		// Handles any errors dispatched by the connection class.
		private function onError(e:OvpEvent):void {
			switch (e.data.errorNumber) {
				case 6 :
					_successfulPort = "any";
					_successfulProtocol = "any";
					_model.showError(_model.ERROR_TIME_OUT_CONNECTING);
					break;
				case 7 :
					_model.showError(_model.ERROR_FILE_NOT_FOUND);
					break;
				case 9 :
					_model.showError(_model.ERROR_LIVE_STREAM_TIMEOUT);
					break;
				case 13 :
					_model.showError(_model.ERROR_CONNECTION_REJECTED);
					break;
				case 22 :
					_model.showError(_model.ERROR_NETSTREAM_FAILED);
					break;
				case 23 :
					_needsRestart = true;
					_successfulPort = "any";
					_successfulProtocol = "any";
					_model.showError(_model.ERROR_CONNECTION_FAILED);
					break;

			}
		}
		
		// Handle play events
		private function playHandler(e:Event):void {
			//Log.traceMsg ("VideoController playhandler " + _needsRestart, Log.LOG_TO_CONSOLE);
			if (_needsRestart) {
				_needsRestart = false;
				newSourceHandler(null);
			} else {
				_ns.resume();
			}
		}
		// Handle pause events
		private function pauseHandler(e:Event):void {
			_ns.pause();
		}
		// Handle seek events
		private function seekHandler(e:Event):void {
			//Log.traceMsg ("VideoController seekHandler _needsRestart:" + _needsRestart, Log.LOG_TO_CONSOLE);
			
			if (_needsRestart) {
				_needsRestart = false;
				_needsSeek = true;
				newSourceHandler(null);
			} else {
				_ns.seek(_model.seekTarget);
			}
		}
		// Handle a resubscribe attempt
		private function handleSubscribeAttempt(e:OvpEvent):void {
			_model.debug("Trying to re-subscribe to the live stream ...");
		}
		// handle BOSS results
		private function bossParsedHandler(e:OvpEvent):void {
			_connectionAuthParameters = _boss.connectAuthParams;
			_streamAuthParameters = _boss.playAuthParams;
			_isLive = _boss.isLive;
			_streamName = _boss.streamName;
			var protocol:String = _boss.protocol != "" && _boss.protocol != null ? _boss.protocol:"any";
			connect(_boss.hostName,protocol);
		}
		
		/************************************************/
		/************ADDED BY AMADSEN 5/16/2011**********/
		// NON-MBR multi-bitrate SMIL results
		private function a_smilParsedHandler(e:OvpEvent):void {
			//Log.traceMsg("VideoController a_smilParsedHandler", Log.LOG_TO_CONSOLE);
			_isMultiBitrate = false;
			_model.isMultiBitrate = false;
			_dsi = _a_SMILparser.dsi;
			var protocol = _model.src.indexOf(":") != -1 ? _model.src.slice(0,_model.src.indexOf(":")).toLowerCase():"any";
			//Log.traceMsg("VideoController a_smilParsedHandler HOSTNAME:" + _a_SMILparser.hostName, Log.LOG_TO_CONSOLE);
            connect(_a_SMILparser.hostName, protocol);
		}
		
		/************************************************/
		/************ADDED BY AMADSEN 5/16/2011**********/
		// handle NON-MBR multi-bitrate SMIL updates
		private function a_smilReparsedHandler(e:OvpEvent):void {
			//Log.traceMsg("VideController a_smilReparsedHandler", Log.LOG_TO_CONSOLE);
			a_smilParsedHandler(e);
		}
		
        // handle multi-bitrate SMIL results
		private function smilParsedHandler(e:OvpEvent):void {
			//Log.traceMsg("VideoController smilParsedHandler", Log.NORMAL);
			_isMultiBitrate = true;
			_model.isMultiBitrate = true;
			_dsi = _SMILparser.dsi;
			var protocol:String = _SMILparser.protocol != "" ? _SMILparser.protocol:"any";
			connect(_SMILparser.hostName, protocol);
		}
		
		// handle multi-bitrate SMIL updates
		private function smilReparsedHandler(e:OvpEvent):void {
			Log.traceMsg("VideController REPARSED handler", Log.LOG_TO_CONSOLE);
			smilParsedHandler(e);
		}
		
		private function rssParsedHandler(e:OvpEvent):void {
			_model.playlistItems = _rss.itemArray;
		}
		
		private function parseErrorHandler(e:OvpEvent):void {
			
			switch (e.data.errorNumber) {
				case OvpError.HTTP_LOAD_FAILED :
					_model.showError(_model.ERROR_HTTP_LOAD_FAILED);
					break;
				case OvpError.XML_MALFORMED :
					_model.showError(_model.ERROR_BAD_XML);
					break;
				case OvpError.XML_MEDIARSS_MALFORMED :
					_model.showError(_model.ERROR_XML_NOT_RSS);
					break;
				case OvpError.XML_BOSS_MALFORMED :
					_model.showError(_model.ERROR_XML_NOT_BOSS);
					break;
				case OvpError.XML_LOAD_TIMEOUT :
					_model.showError(_model.ERROR_LOAD_TIME_OUT);
					break;
			}

		}


		private function debugHandler(e:OvpEvent):void {
			_model.debug(e.data.toString());
		}

		private function cuePointMgrSetHandler(e:Event):void {
			_needToSetCuePointMgr = true;

			if (_ns && _model.cuePointManager) {
				_model.cuePointManager.netStream = _ns;
				_needToSetCuePointMgr = false;
			}
		}

		private function connect(hostName:String, requestedProtocol:String = "any"):void {
			
            if (((hostName + _connectionAuthParameters) == _lastConnectionKey) && !_needsRestart && _ak.connected ) {
				//Log.traceMsg ("VideoController reusing the existing connection: " + _lastConnectionKey, Log.LOG_TO_CONSOLE);
				connectedHandler();
			} else {
			     
			    /*
                if (_ak != null && _ak.connected) {
                    Log.traceMsg ("VideoController closing prior connection", Log.LOG_TO_CONSOLE);
				
                    //Close any prior connection
                    _ak.close();
                }
                */
                
                //Log.traceMsg ("VideoController creating new connection: " + (hostName + "?" + _connectionAuthParameters) + " : " +_lastConnectionKey, Log.LOG_TO_CONSOLE);
				
				_ak = new AkamaiConnection();
				_ak.addEventListener(NetStatusEvent.NET_STATUS,netStatusHandler);
				_ak.addEventListener(OvpEvent.STREAM_LENGTH,handleStreamLength);
				_ak.addEventListener(OvpEvent.ERROR, onError);
				_ak.requestedPort = _successfulPort != null ? _successfulPort:"any";
				requestedProtocol = (requestedProtocol == "rtmpe" || requestedProtocol == "rtmpte") ? "rtmpe,rtmpte":requestedProtocol;
				_ak.requestedProtocol = requestedProtocol == "any" ? (_successfulProtocol != null ? _successfulProtocol:"any") : requestedProtocol;

				if (_connectionAuthParameters != "" && _connectionAuthParameters != null) {
					_ak.connectionAuth = _connectionAuthParameters;
				}
				
				if (! _model.autoStart) {
					_view.hideVideo();
				}
				
				// Notifier function
				_model.connectionCreated(_ak, hostName);
				
				_ak.connect(hostName);
				_lastConnectionKey = hostName + _connectionAuthParameters;
			}

		}
	}
}
