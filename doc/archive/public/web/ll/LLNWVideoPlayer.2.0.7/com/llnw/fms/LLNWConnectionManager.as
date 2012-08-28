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
package com.llnw.fms
{
	import com.adobe.fms.DynamicStream;
	import com.adobe.fms.DynamicStreamItem;
	import com.llnw.fms.LLNWConnection;
	import com.llnw.fms.ConnectionClient;
	import com.llnw.events.LLNWEvent;
	import com.llnw.valueObjects.FMSFallbackVO;
	import com.llnw.utils.URIUtil;

	import com.llnw.valueObjects.StreamStatVO;
	import flash.events.EventDispatcher;
	import flash.events.AsyncErrorEvent;
	import flash.events.NetStatusEvent;
	import flash.events.Event;
	import flash.events.TimerEvent;
	import flash.media.SoundTransform;
	import flash.utils.Timer;
	
	import flash.net.NetConnection;
	import flash.net.Responder;
	
	import flash.net.NetStreamPlayOptions;
	import flash.net.NetStreamPlayTransitions;
	
	import flash.display.Sprite;
	import flash.net.NetStream;
	import flash.media.Video;
	
	/**
	 * ...
	 * @author LLNW
	 * Connection class
	 * 
	 */
	public class LLNWConnectionManager extends EventDispatcher
	{

		private var _nc:NetConnection;
		private var _uri:String;
		private var _nsName:String;
		private var _ns:NetStream;
		
		private var _video:Video;
		private var _dsi:DynamicStreamItem;
		private var _ds:DynamicStream;
		private var _uriUtil:URIUtil;
		private var _connectionClient:ConnectionClient;
		
		private var _ismbr:Boolean;
	
		
		
		public function LLNWConnectionManager() 
		{
			streamList = new Array();
			_oldAddress = ""
			_connectionClient = new ConnectionClient();
			_connectionClient.addEventListener(LLNWEvent.BANDWIDTH_COMPLETE, onBandwidthDone);
			
			//FCSubscribe Events
			_connectionClient.addEventListener(LLNWEvent.FC_SUBSCRIBE_NETSTREAM_PLAY_START, onFCSubscribeStreamStart);
			_connectionClient.addEventListener(LLNWEvent.FC_SUBSCRIBE_STREAM_NOT_FOUND, onFCSubscribeStreamNotFound);
			_connectionClient.addEventListener(LLNWEvent.FC_UNSUBSCRIBE, onFCUnsubscribe);
			_connectionClient.addEventListener(LLNWEvent.ONFI, onFI);
			_connectionClient.addEventListener(LLNWEvent.ON_CUE_POINT, onCuePoint);
			
			//DVRSubscribe Events
			_connectionClient.addEventListener(LLNWEvent.DVR_SUBSCRIBE, onDVRSubscribe);
			_connectionClient.addEventListener(LLNWEvent.DVR_SUBSCRIBE_NETSTREAM_PLAY_START, onDVRSubscribeStreamStart);
			_connectionClient.addEventListener(LLNWEvent.DVR_SUBSCRIBE_STREAM_NOT_FOUND, onDVRSubscribeStreamNotFound);
			_connectionClient.addEventListener(LLNWEvent.DVR_UNSUBSCRIBE, onDVRUnsubscribe);
			
			_connectionClient.addEventListener(LLNWEvent.NETSTREAM_PLAY_COMPLETE, onNetStreamPlayComplete);
			_connectionClient.addEventListener(LLNWEvent.SERVER_CLOSED_CONNECTION, onConnectionClosed);
			_connectionClient.addEventListener(LLNWEvent.METADATA_RECEIVED, onMetaDataComplete);
			
			//turn on debugging
			_connectionClient.addEventListener("debugEvent", catchDebug)
			_soundTransform = new SoundTransform()
		}
		
		/**
		 * Method is called once the connection parameters have been deciphered out side of the class.
		 * 
		 * @param	uri : a string representing the FMS application to connect to.
		 */
		public function doConnection(uri:String):void {
		
			_uri = uri;
			_nc = new NetConnection();
			_nc.client = _connectionClient;
			
			_nc.addEventListener(AsyncErrorEvent.ASYNC_ERROR, asyncError)
			_nc.addEventListener(NetStatusEvent.NET_STATUS, onNetStatus)
			setDebug("connecting to: "+_uri)
			_nc.connect(_uri)
		
		}
		
		/**
		 * Method is called once the connection parameters have been deciphered out side of the class. Used for
		 * progressive download videos
		 * 
		 * @param	uri : a string representing the full url plus file name.
		 */
		public function connectHTTP(uri:String):void {
			_uri = uri;
			_nc = new NetConnection();
			_nc.connect(null)
			//skip NC listeners
			streamConnectHTTP()
			

		}
		
		
		private var _mResp:Responder = new Responder(onResult)
		private var liveResponder:Responder = new Responder(onFCResult);
		private function onNetStatus(event:NetStatusEvent):void {
			setDebug("onNetStatus: " + event.info.code)
			switch(event.info.code){
				case "NetConnection.Connect.Success":
					setDebug("setting up stream types on connect succcess")
					setStream()
					//dispatchEvent(new Event("ConnectionSuccess",true))
					break;
				case "NetStream.Play.Start":
					//dispatchEvent(new Event("NetStreamStart",true))
					break;
				case "NetConnection.Connect.Failed":
					forcePorts()
					break;
				case "NetConnection.Call.Failed":
					//nc.call("DVRGetStreamInfo",mResp, nsName)
					break;
			} 
		}
		
		//TO DO
		private function forcePorts():void {
			
			for (var i:int = 0; i < FALLBACK_PORTS.length; i++) {
				//hit all ports, pick up first available.
				
			}
			
		}
		
		private var countFCResults:int = 0;		
		//handles responder after nc.call("FCSubscribe")
		private function onFCResult(obj:Object):void {
			//do nothing here, obj is allways null
		}
		private function doConnects(event:Event):void {
			if (_streamType == "LiveMBR") {
				
				setDebug("streamList: "+streamList)
				setDebug("countFCResults: "+streamList[countFCResults])

				if (countFCResults == streamList.length-1) {
					setDebug("Recieved all onFCSubscribe responses.")
					
					countFCResults = 0;
					maxFCRetry = 0;
					streamConnectMBR();
				}else{
					countFCResults++
				}
			}else {
				maxFCRetry = 0;
				streamConnect();
			}
		}
		//autoPlay hack. netstream will play anyways.
		private var _autoPlay:Boolean = false;
		public function set autoPlay(value:Boolean):void {
			_autoPlay = value;
		}
		private function netStreamStatus(event:NetStatusEvent):void {
			
			setDebug("netStreamStatus: " + event.info.code)
	
			 switch (event.info.code) {
                case "NetConnection.Connect.Success":
                   // connectStream();
                    break;
				case "NetStream.Play.Start":
					if(_autoPlay){
						startStatsMonitor();
					}else {
						_autoPlay = true;
						dispatchEvent(new Event("doNotAutoPlay",true))
					}
					break;
                case "NetStream.Play.StreamNotFound":
					setDebug("NetStream.Play.StreamNotFound: ")
					setDebug(_nsName);
                    break;
				case "NetStream.Play.FileStructureInvalid":
					//what to do here
					setDebug("NetStream.Play.FileStructureInvalid")
					break;
				case "NetStream.Seek.InvalidTime":
					setDebug("NetStream.Seek.InvalidTime")
					  _ns.seek(event.info.details)
					  break;
				case "NetStream.Play.Stop":
					setDebug("NetStream.Play.Stop")
					dispatchEvent(new Event("NETSTREAM_PLAY_STOP", true))
					break;
            }
		}
		
		private function doResume():void {

			if (_streamType == "MBR" || _streamType == "LiveMBR") {
				_ds.resume()
			}else {
				_ns.resume();
			}
		}
		private function dsStatus(event:NetStatusEvent):void {
			setDebug("DynamicStream EVENT: " + event.info.code)
			switch (event.info.code) {
				case "NetStream.Play.Start":
					startStatsMonitor();
					break;
			}
			for(var i:String in event.info){
			//	trace(i + " : " + event.info[i]);
			}
		}
		
		
		/**
		 * Method sets the stream name to connect to.
		 * 
		 * @param value: name of the netstream
		 */
		public function set netStreamName(value:String):void {
			
			_nsName = value;
			
		}
		/**
		 * Method gets the stream name.
		 * 
		 * @return _nsName : the name of the stream.
		 */
		public function get netStreamName():String {
			return _nsName;
		}
		
		/**
		 * Method gets the stream name.
		 * 
		 * @return _nsName : the name of the stream.
		 */
		public function get netConnection():NetConnection {
			return _nc;
		}
		
		/**
		 * Method sets the type of stream you are playing.
		 * 
		 * @param value: the type of stream you are playing.
		 */
		public function set streamType(value:String):void {
			_streamType = value;
		}
		
		private var _streamType:String;
		private function setStream():void {
			setDebug("streamType: "+_streamType)
			switch(_streamType) {
				case "DVRStream" :
					getDVRMetaData()
					break;
				case "DVRMBRStream" :
					getDVRMBRMetaData();
					break;
				case "Streaming":
					setDebug("Streaming, calling streamConnect()")
					streamConnect()
					break;
				case "LiveStream":
					
					_nc.call("FCSubscribe", liveResponder, _nsName);
					break;
				case "LiveMBR":
					doMBRSubscribe()
					break;
				case "MBR":
					streamConnectMBR();
					//MBR here
					break;
				case "Progressive": 
					streamConnectHTTP();
					//Progressive stream playing here.
					break;
				case "FLVSeek": 
					//FLVSeek here
					break;
			}
			
			
		}
		private function doMBRSubscribe():void {
			for (var i:int = 0; i < streamList.length; i++) {
				setDebug("making call - FCSubscribe: "+ streamList[i])
				_nc.call("FCSubscribe", liveResponder, streamList[i]);
			}
		}
		private var waitTime:int = 5;
		private var tTimer:Timer;
		private function onResult(result:Object):void {
			setDebug("result.code: " + result.code)
			
			switch(result.code) {
				case "NetStream.DVRStreamInfo.Success":
				setDebug("calling  DVRSubscribe")
				//	getDVRMetaData()
					_nc.call("DVRSubscribe", null, _nsName)
					break;
				case "NetStream.DVRStreamInfo.Failed":
				
					setDebug("Stream not found")
					break;
				case "NetStream.DVRStreamInfo.Retry":
					//set up retry handler
					setDebug("Retrying DVRStreamInfo - reuslt: "+result.data.retry)
					tTimer = new Timer(100)
					_retryMax = result.data.retry;
					
					tTimer.addEventListener(TimerEvent.TIMER, repeat)
					tTimer.start();
					break;
				
				
			}
			
		}
		private var _repeatCount:int = 0;
		private var _retryMax:int;
		private var _currentRetry:int;
		private function repeat(event:TimerEvent):void {
			_repeatCount++
			
			if (_repeatCount > waitTime) {
				_repeatCount = 0;
				tTimer.removeEventListener(TimerEvent.TIMER, repeat)
				_currentRetry++;
				setDebug("Retry number "+_currentRetry)
				if(_currentRetry > _retryMax) {
					//alert or kill here
					setDebug("Retry Failed.")
				}else {
					getDVRMetaData()
				}
			}
		}
		
		private function getDVRMetaData() { 

			_nc.call("DVRGetStreamInfo", _mResp, _nsName) 
		}
		
		private function getDVRMBRMetaData():void {
			
			_nc.call("DVRGetStreamInfo", _mResp, dsi.streams[0].name) 
			
		}
		

		private var _bufferTime:Number;
		public function set buffer(value:Number):void {
			_bufferTime = value;
		}
		private function streamConnect():void {
			setDebug("streamConnect() called. Attaching netStream")
			_ns = new NetStream(_nc)
			_ns.client = _connectionClient;
			_ns.bufferTime = _bufferTime;
			_ns.addEventListener(NetStatusEvent.NET_STATUS, netStreamStatus)
			_ns.addEventListener(AsyncErrorEvent.ASYNC_ERROR, asyncError)
			
			_soundTransform = _ns.soundTransform;
			
			dispatchEvent(new Event("displayMedia",true))
		}
		
		private function streamConnectHTTP():void {
			_ns = new NetStream(_nc)
			_ns.client = _connectionClient;
			_ns.bufferTime = _bufferTime;
			_ns.addEventListener(NetStatusEvent.NET_STATUS, netStreamStatus)
			_ns.addEventListener(AsyncErrorEvent.ASYNC_ERROR, asyncError)
			_soundTransform = _ns.soundTransform;
			dispatchEvent(new Event("displayMedia",true))
		}
		private function streamSeekFLV():void {
			_ns = new NetStream(_nc)
			_ns.client = _connectionClient;
			_ns.bufferTime = _bufferTime;
			_ns.addEventListener(NetStatusEvent.NET_STATUS, netStreamStatus)
			_ns.addEventListener(AsyncErrorEvent.ASYNC_ERROR, asyncError)
			_soundTransform = _ns.soundTransform;
			dispatchEvent(new Event("handleFLVSeek",true))
		}
		private function streamConnectMBR():void {
			_ismbr = true;
			_ds = new DynamicStream(_nc)
			var client:Object = new Object()
			client.onMetaData = onMetaData
			_ds.client = _connectionClient;
			_ds.bufferTime = _bufferTime;
			_ds.addEventListener(NetStatusEvent.NET_STATUS, dsStatus)
			_ds.addEventListener(AsyncErrorEvent.ASYNC_ERROR, asyncError)
			_ds.addEventListener("netStatus", dsStatus);
			_soundTransform = _ds.soundTransform;
			dispatchEvent(new Event("displayMedia",true))
			
		}
		//on hold for now, awaiting Adobe response to DVRCast w/Live MBR support
		private function streamConnectDVRMBR():void {
			//trace("streamConnectDVRMBR")
			_ds = new DynamicStream(_nc)
			var client:Object = new Object()
			client.onMetaData = onMetaData
			_ds.client = _connectionClient;
			_ds.addEventListener(NetStatusEvent.NET_STATUS, dsStatus)
			_ds.addEventListener(AsyncErrorEvent.ASYNC_ERROR, asyncError)
			_ds.addEventListener("netStatus", dsStatus);
			_soundTransform = _ds.soundTransform;
			dispatchEvent(new Event("displayMedia",true))
			
		}
		
		public function get netStream():* {
			if (_ismbr) {
				//trace("returning dsi")
				return _ds;
			}else{
				return _ns;
			}
		}
		
		
		private function asyncError(error:AsyncErrorEvent):void {
			setDebug("Connection - AsyncErrorEvent: "+error);
		}
		
		private function onBandwidthDone(event:LLNWEvent):void {
			setDebug("Connection - onBandwidthDone event");
			
		}
		
		
		//FCSubscribe Events
		private function onFCSubscribe(info:Object) {
			
			setDebug("Connection - onFCSubscribe")
		}
		
		private function onFI(event:LLNWEvent):void {
			setDebug("onFI called")
			//
		}
		
		private function onCuePoint(event:LLNWEvent):void {
			debug("onCuePoint called", "Debug")
			//you would want to add any controls for onCuePoint
			//out side the connection manager's class. 
		}
	
		
		private function onFCSubscribeStreamStart(event:LLNWEvent):void {
			setDebug("Connection - onFCSubscribeStreamStart event");
			doConnects(event)
		}
		
		private var maxFCRetry:int = 0;
		private function onFCSubscribeStreamNotFound(event:LLNWEvent):void {
			setDebug("Connection - onFCSubscribeStreamNotFound event");
			if (maxFCRetry <= 5) {
				maxFCRetry++
				fcSubscribeRetry();
			}else {
				maxFCRetry = 0;
				//no more retries to connect
			}
		}
		private function onFCUnsubscribe(event:LLNWEvent):void {
			setDebug("Connection - onFCUnsubscribe event");
			
		}
		
		private var retryTimer:Timer;
		private function fcSubscribeRetry():void {
			setDebug("retrying FCSubscribe")
			retryTimer = new Timer(1000,3)
			retryTimer.addEventListener(TimerEvent.TIMER_COMPLETE, retryFCSubscribe)
			//retryTimer.addEventListener(TimerEvent.TIMER,retryFCSubscribe)
			retryTimer.start();
		}
		private function retryFCSubscribe(event:TimerEvent):void {
			setDebug("retry on FCSubscribe called")
			setDebug("FCSubscribe stream: "+_nsName)
			_nc.call("FCSubscribe", liveResponder, _nsName);
			
		}
		
		//DVRSubscribe Events
		private function onDVRSubscribe(event:LLNWEvent) {

			setDebug("Connection - onDVRSubscribe event");
		}
		
		private function onDVRSubscribeStreamStart(event:LLNWEvent):void {
			if (_streamType == "DVRMBRStream") {
				streamConnectDVRMBR()
			}else {
				streamConnect()
			}
			setDebug("Connection - onDVRSubscribeStreamStart event");
			
		}
		
		private function onDVRSubscribeStreamNotFound(event:LLNWEvent):void {
			setDebug("Connection - onDVRSubscribeStreamNotFound event");
			
		}
		private function onDVRUnsubscribe(event:LLNWEvent):void {
			setDebug("Connection - onDVRUnsubscribe event");
			
		}
		
		private function onMetaData(obj:*) {

		}
		private var _oldAddress:String;
		private var persistDuration:String;
		private var _metaDataObject:Object;
		private function onMetaDataComplete(event:LLNWEvent):void {
			_metaDataObject = new Object()
			_metaDataObject = _connectionClient.metaDataObject;
			
			var metaMessage:String = "-- metadata info:\n"
			for (var i in _metaDataObject) {
				metaMessage += i+ " : "+ _metaDataObject[i]+"\n"
			}
			setDebug(metaMessage)
			
			dispatchEvent(new Event("metaDataEvent",true))
			setDebug("Connection - onMetaDataComplete event");
		}
		public function get metaDataObject():Object { return _metaDataObject; }
		
		private function onNetStreamPlayComplete(event:LLNWEvent):void {
			setDebug("Connection - onNetStreamPlayComplete event");
			
		}
		private function onConnectionClosed(event:LLNWEvent):void {
			setDebug("Connection - onConnectionClosed event");
			
		}
		
		
		//////////////////////////////////////////////////////////
		//					Stat monitor
		//////////////////////////////////////////////////////////
		
		/**
		 * Method handles reporting stats assigning the values to 
		 * a StreamStatVO for accessing outside of class.
		 * 
		 * @return result : Stream stats value object.
		*/
		public function get stats():StreamStatVO{
			var result:StreamStatVO = new StreamStatVO();
			var ns:*;
			if (_streamType == "MBR") {
				ns = _ds;
			}else {
				ns = _ns;
			}
			try {
				
				result.bytesLoaded = ns.bytesLoaded;
				result.bytesTotal = ns.bytesTotal;
				result.bufferTime = ns.bufferTime
				result.bufferLength = ns.bufferLength;
				result.time = ns.time;
				result.duration = _metaDataObject.duration;
			
			}catch(e:Error){
				dispatchEvent(new Event("statsUnavailable",true));
			}
			
			return result;
		}
		
		private function updatePlaybackStats(event:TimerEvent = null):void {
			dispatchEvent(new Event("statsUpdated", true))
			
		}
		
		private var _statsTimer:Timer;
		/**
		 * Method handles starting a timer to track statistics.
		 * 
		 */
		private function startStatsMonitor():void{
		//	setDebug("Stats Monitor started");
			if (_statsTimer) {
				_statsTimer.stop();
				_statsTimer.reset();
				_statsTimer.removeEventListener(TimerEvent.TIMER, updatePlaybackStats)
				_statsTimer = null;
			}
			_statsTimer = new Timer(100)
			_statsTimer.addEventListener(TimerEvent.TIMER, updatePlaybackStats);
			_statsTimer.start();
		}
		/**
		 * Method handles stopping the timers that keep track of statistics.
		 * 
		 */
		private function stopStatsMonitor():void{
			if(_statsTimer){
				_statsTimer.stop(); 
				_statsTimer.reset();
				_statsTimer.removeEventListener(TimerEvent.TIMER, updatePlaybackStats)
				_statsTimer = null;
				updatePlaybackStats(); //1 last update to the stats to make sure we have the absolute latest
			}
		}
		
		
		private var streamList:Array;
		//MBR: Stream Names and Bitrates.  
		public function addStream(name:String, bitRate:Number):void {
			
			if (_dsi == null) {
				_dsi = new DynamicStreamItem();
			}
			setDebug("stream type: " + _streamType)
			if (_streamType == "LiveMBR") {
			
				_dsi.start = -1
			}else {
				_dsi.start = 0;
			}
			setDebug("adding stream: " + name + " bitrate: " + bitRate)
			streamList.push(name)
			_dsi.addStream(name, bitRate);
		}
		public function get dsi():DynamicStreamItem { return _dsi; }
		
		public function set isMBR(value:Boolean):void { _ismbr = value; }
		public function get isMBR():Boolean { return _ismbr; }
		
		
		/**
		 * Seeks to <code>value</code> as a percent of the available movie.
		 * <p>Metadata keyframes are searched to find the closest keyframe.</p>
		 * 
		 * @param value Percentage value to which to seek (ie - 50% of the movie)
		 */
		public function seekByPercent(value:Number):void{
	
		
			var durationPercent:Number = Math.floor((value / 100) * stats.duration);
			var moovTime:Number = value * stats.duration;
			if (_metaDataObject.keyframes != null && _metaDataObject.keyframes.times != undefined) {
					//FLV Seek
					var offset:Number = findKeyframeOffsetByPercent(durationPercent);
					var checkForAmpersands:Array = _nsName.split("?")
					var dilimeter:String = "?"
					var nsNameArray:Array = _nsName.split("?")
					_nsName = nsNameArray[0] +dilimeter+ "fs=" + offset;
					setDebug("seeking by percent: "+_nsName)
					streamSeekFLV()
					
				}else {
					
					_ns.seek((value * .01) * stats.duration);
			
					
				}

		}
		private function findKeyframeOffsetByPercent(percent:Number):Number{
			var metaIndexNum:Number = 0;
			var offsetValue:Number = 0;
		

			if(percent > 0 && _metaDataObject.keyframes.filepositions != undefined){
				var len:Number = _metaDataObject.keyframes.times.length;
				for (var i:Number = 0; i < len; i++) {
					var n:int = i+1
	
					if (Number(_metaDataObject.keyframes.times[i]) <= percent && Number(_metaDataObject.keyframes.times[n]) >= percent) {
			
						metaIndexNum = i;
						break;
					}
				}
				offsetValue = _metaDataObject.keyframes.filepositions[metaIndexNum];
			}
			return offsetValue;
		}
		//MOOVSeek
		
		private var _offset:Number = 0;
		/**
		 * Method handles H.264 seeking using time to find point in which to seek to.
		 * 
		 * @param	value : a number representing the time you desire to seek to. 
		 */
		public function seekByTime(value:Number):void {
				var timeConv = Math.floor(value)
				_offset = timeConv;
				
				var popped:Array = _nsName.split("?")

				if (popped.length == 1) {
					_nsName = popped[0]+"?ms=" + timeConv
				}else {
					if (_uriUtil.queryParams != "" && _uriUtil.queryParams != null) {
						_nsName = popped[0] + "?" + _uriUtil.queryParams + "&ms=" + timeConv
					}else {
						_nsName = popped[0] + "?ms=" + timeConv
					}
				}
				setDebug("MOOVSeek: "+_nsName)

				streamSeekFLV()
			
		}
		public function get offset():Number {
			if (_streamType != "MOOVSeek") {
				_offset = 0;
			}
			
			return _offset;
		}
		/*
		private function matchAndConvert(scrubNum:Number):int { 

			var metaIndexNum:Number; 

			for(var m:int = 0; m < _metaDataObject.times.length; m++){ 
				var n = m +1; 
				if(_metaDataObject.times[m] <= scrubNum && _metaDataObject.times[n] >= scrubNum){ 
					metaIndexNum = m; 
				} 
			} 
			return _metaDataObject.offsets[metaIndexNum]; 

		} 
*/
		/**
		 * Method exposes The sound transform object.
		 * @return _soundTransform
		 */
		 public function get soundTransform():SoundTransform{ return _soundTransform; }
		 
		private var _soundTransform:SoundTransform;
		/**
		 * Method sets the volume level of the SoundTransform object.
		 * 
		 * @param value : Number value between 0 and 100. 
		 */
		public function set volume(value:Number):void {
			
			try{
			if(value > 1) value = value*0.01; //expects a value between 0 and 1
				_soundTransform.volume = value;
				
				if (_streamType == "MBR" || _streamType == "LiveMBR") {
					_ds.soundTransform = _soundTransform;
				}else{
					_ns.soundTransform = _soundTransform;
				}
				
			}catch (e:Error) {
				
			}
		}
		
		
		/**
		 * Method closes NetConnection and NetStreams. Used when switching streams to prepare for a new one. 
		 */
		public function close():void {

			if (_streamType == "MBR" || _streamType == "LiveMBR") {
				//A specific modification to handle killing streams 
				//for switching types from a dynamicStream.
				_ds.killAll()
				_ds = null;
			}else {
				_ns.close();
				_ns = null;
		
			}
			_nc.close();
			
		}
		 
		public function set uriUtil(value:URIUtil):void {
			_uriUtil = value;
		}
		
		private static const FALLBACK_PORTS:Array = [new FMSFallbackVO("rtmp", 1935),
													 new FMSFallbackVO("rtmp", 443),
													 new FMSFallbackVO("rtmpt", 80),
													 new FMSFallbackVO("rtmpt", 443),
													 new FMSFallbackVO("rtmpt", 1935)];
		
		
		
		/*
		 * Used for debugging
		 * */
		public var debugMessage:String;
		public var debugType:String;
		private function catchDebug(event:Event):void {

			debug(event.target.debugMessage, "Debug")
		}
		
		private function setDebug(message:String, type:String = "Debug"):void {
			debug(message,type)
		}
		
		private function debug(message:String,type:String):void {
			debugMessage = message;
			debugType = type;
			
			dispatchEvent(new Event("debugEvent",true))
		}
	}
	
}