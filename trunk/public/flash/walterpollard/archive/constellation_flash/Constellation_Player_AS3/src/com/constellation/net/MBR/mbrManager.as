package com.constellation.net.MBR
{
	import com.constellation.events.constellationEvent;
	import com.constellation.net.DynamicStreamItem;
	import com.sierrastarstudio.utils.tracer;
	
	import flash.events.EventDispatcher;
	import flash.events.TimerEvent;
	import flash.net.NetStream;
	import flash.net.NetStreamPlayOptions;
	import flash.net.NetStreamPlayTransitions;
	import flash.utils.Timer;

	public class mbrManager extends EventDispatcher
	{
		private static var instance:mbrManager;
		
		private var _classname:String = "com.constellation.net.MBR.mbrManager";
		private var _ruleArray:Array;

		private var _targetNS:NetStream;
		private var _streamIndex:int;
		
		private var _statusTimer:Timer;
		// Initial buffer time to get things started
		private static var FAST_LOAD_BUFFERTIME:Number = .5; // in seconds
		
		
		private static var STATUS_INTERVAL:int = 3000;
		//max buffer size - 180 seconds
		private static var MAX_BUFFER_SIZE:int= 30;
		
		
		
		private var _streamDSI:DynamicStreamItem;
		
		
		private var _bufferSize:int;
		
		private var _metricProvider:MetricsProvider
		private var _testDFPS:int = 0;
		
		private var INCREASE_BUFFER_RATE:int = 5;
		
		private static var PANIC_BUFFER_SIZE:int = 2;
		
		private var DROP_ONE_FRAMEDROP_FPS:Number = 5;
		private var DROP_TWO_FRAMEDROP_FPS:Number = 10;
		private var PANIC_FRAMEDROP_FPS:Number = 14;
		
		private var BANDWIDTH_SAFETY_MULTIPLE:Number = 1.35;
		private var MOBILE_BANDWIDTH_SAFETY_MULTIPLE:Number = .65;
		
		private var _reason:String;
		private var MIN_FPS_DROP_COUNT:int = 3;
		
		
		public function mbrManager(enforcer:SingletonEnforcer)
		{
		}
		public static function getInstance():mbrManager
		{
			
			if (mbrManager.instance == null)
			{
				mbrManager.instance = new mbrManager(new SingletonEnforcer());
			} 
			return mbrManager.instance;
		}
		
		public function addNetStream(newNS:NetStream,streamDSI:DynamicStreamItem):void{
			this._targetNS = newNS;
			this._targetNS.bufferTime = FAST_LOAD_BUFFERTIME;
			this._metricProvider = new MetricsProvider(this._targetNS);
			this._metricProvider.dynamicStreamItem = streamDSI;
			this._metricProvider.targetBufferTime = MAX_BUFFER_SIZE/2
			this._streamDSI = streamDSI;
			
			//start the status checking timer
			this._statusTimer = new Timer(STATUS_INTERVAL);	
			this._statusTimer.addEventListener(TimerEvent.TIMER,checkStreamStatus);
			this._statusTimer.start()
				
			
		}
		
		protected function checkStreamStatus(event:TimerEvent):void
		{
			this._bufferSize = this._targetNS.bufferTime;
			var curVideoBufferLen:Number = this._targetNS.info.videoBufferLength;
			var netStreamBufferLen:Number = this._targetNS.bufferTime;
			var newIndex:int = -1
				var bandwidthSuggestIndex:int =  this.getNewBandWidthIndex();
				var FPSSuggestIndex:int = this.getNewFPSIndex();
				
			//tracer.log("checking stream status @ index "+this._streamIndex+"  currentLen "+curVideoBufferLen+" > this._bufferSize "+this._bufferSize+"  MAX_BUFFER_SIZE "+MAX_BUFFER_SIZE+" bandwidth suggests "+bandwidthSuggestIndex+" FPS Suggest "+FPSSuggestIndex,_classname);
		//	tracer.log("checking stream status @ index "+this._streamIndex,_classname);	
			//slowly raise buffer limit if player performance allows it
			if(curVideoBufferLen>this._bufferSize && this._bufferSize<MAX_BUFFER_SIZE){
				this._targetNS.bufferTime += INCREASE_BUFFER_RATE;
			}
			//if buffer is below threshold
			if(curVideoBufferLen<PANIC_BUFFER_SIZE){
								//drop down one level since we're running low on buffer
								this._targetNS.bufferTime = FAST_LOAD_BUFFERTIME;
							if(this._streamIndex-1<0){
								tracer.log("Already at lowest stream",_classname);
							}else{
								newIndex = this._streamIndex-1
								this.swapStream(newIndex);
								return
							}
			}
			
		if(FPSSuggestIndex>0){
			this.swapStream(FPSSuggestIndex);
			return;
		}
		if(bandwidthSuggestIndex>0){
			this.swapStream(bandwidthSuggestIndex);
		}
			
		}
		/**
		 * The new bitrate index to which this rule recommends switching. If the rule has no change request, either up or down, it will
		 * return a value of -1. 
		 * 
		 */
		public function getNewFPSIndex():int {
			var newIndex:int = -1;
			//tracer.log("this._metricProvider.averageDroppedFPS   "+this._metricProvider.averageDroppedFPS,_classname);
			if (this._metricProvider.averageDroppedFPS > PANIC_FRAMEDROP_FPS) {
				_reason = "Average droppedFPS of " + Math.round(this._metricProvider.averageDroppedFPS) + " > " + PANIC_FRAMEDROP_FPS ;
				newIndex = 0;
			} else if (this._metricProvider.averageDroppedFPS > DROP_TWO_FRAMEDROP_FPS) {
				newIndex = this._metricProvider.currentIndex -2 < 0 ? 0:this._metricProvider.currentIndex -2;
				_reason = "Average droppedFPS of " + Math.round(this._metricProvider.averageDroppedFPS) + " > " + DROP_TWO_FRAMEDROP_FPS ;
			} else if (this._metricProvider.averageDroppedFPS > DROP_ONE_FRAMEDROP_FPS) {
				newIndex = this._metricProvider.currentIndex -1 < 0 ? 0:this._metricProvider.currentIndex -1;
				_reason = "Average droppedFPS of " + Math.round(this._metricProvider.averageDroppedFPS) + " > " + DROP_ONE_FRAMEDROP_FPS ;
			}
			
			if (newIndex != -1 && newIndex < this._metricProvider.currentIndex) {
				if (!this._metricProvider.dynamicStreamItem.isLockedAt(this._metricProvider.currentIndex)) {
					this._metricProvider.dynamicStreamItem.lock(this._metricProvider.currentIndex);
					//dispatchEvent(new OvpEvent(OvpEvent.DEBUG, "Frame drop rule locking at index level "+_metrics.currentIndex));
				}
			}
		
			return newIndex;
		}
		
		/**
		 * The new bitrate index to which this rule recommends switching. If the rule has no change request, either up or down, it will
		 * return a value of -1. 
		 * 
		 */
		public function getNewBandWidthIndex():int {
			var newIndex:int = -1;
			// Wait until the metrics class can calculate a stable average bandwidth
			if (this._metricProvider.averageMaxBandwidth != 0) {
				// First find the preferred bitrate level
				
				
				for (var i:int = this._metricProvider.dynamicStreamItem.streamCount-1; i >= 0; i--) {
					var targetStreamRate:int = this._metricProvider.dynamicStreamItem.getRateAt(i)*BANDWIDTH_SAFETY_MULTIPLE
						
					if (this._metricProvider.averageMaxBandwidth > targetStreamRate) {
				//		tracer.log("this._metricProvider.averageMaxBandwidth  "+this._metricProvider.averageMaxBandwidth+"  streamRate "+targetStreamRate+"  newIndex "+newIndex,_classname);
						newIndex = i;
						_reason = "Average bandwidth of " + Math.round(this._metricProvider.averageMaxBandwidth) + " < " + BANDWIDTH_SAFETY_MULTIPLE + " * rendition bitrate";
						break;
					}
				}
				if (newIndex > this._metricProvider.currentIndex) {
					// We switch up only if conditions are perfect - no framedrops and a stable buffer
					newIndex = (this._metricProvider.droppedFPS < MIN_FPS_DROP_COUNT && this._metricProvider.bufferLength > this._metricProvider.targetBufferTime) ? newIndex:-1;
					_reason = "Move up since avg dropped FPS " + Math.round(this._metricProvider.droppedFPS) + " < "+MIN_FPS_DROP_COUNT+" and bufferLength > " + this._metricProvider.targetBufferTime;
				}
			} 
			
			return newIndex;
		}
		
		private function swapStream(targetIndex:int):void{
			if(this._streamIndex!=targetIndex){
				this._targetNS.bufferTime = FAST_LOAD_BUFFERTIME;
					this._streamIndex = targetIndex;
					var nsPlayOptions:NetStreamPlayOptions = new NetStreamPlayOptions(); 
					nsPlayOptions.streamName = this._streamDSI.streams[targetIndex].name; 
					nsPlayOptions.transition = NetStreamPlayTransitions.SWITCH; 
					this._targetNS.play2(nsPlayOptions); 
				tracer.log("switching streams reason:  "+this._reason,_classname);
			}
		}
		
		public function addRules():void{
			this._ruleArray = new Array();
			var buffRule:bufferRule = new bufferRule();
			 
			
		}
		public function set streamIndex(newIndex:int):void{
			this._streamIndex = newIndex;
		}
	}
}

class SingletonEnforcer
{
}