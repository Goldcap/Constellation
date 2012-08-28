package com.constellation.utilities
{
	import com.akamai.osmf.net.AkamaiHDNetStreamWrapper;
	import com.akamai.osmf.net.AkamaiZStreamWrapper;
	import com.constellation.controllers.ExternalInterfaceController;
	import com.constellation.externalConfig.ExternalConfig;
	import com.sierrastarstudio.utils.tracer;
	
	import flash.events.EventDispatcher;
	import flash.events.IEventDispatcher;
	import flash.events.TimerEvent;
	import flash.external.ExternalInterface;
	import flash.net.NetStream;
	import flash.utils.Timer;
	
	import org.casalib.util.ConversionUtil;
	
	public class AkamaiBandwidthMonitor extends EventDispatcher
	{
		private static var instance:AkamaiBandwidthMonitor;
		
		private static var BW_TIMER_INTERVAL:Number=500;
		private var BANDWIDTH_SAMPLE_COUNT:int = 30;

		
		private var _classname:String = "com.constellation.utilities.AkamaiBandwidthMonitor";
		
		private var _bandwidth:Number =0;
		private var _bufferLength:Number;
		private var _bufferMax:Number;
		
		private var _akamaiZStream:AkamaiZStreamWrapper;
		private var _bwTimer:Timer;
		
		private var _avgBandwidthArray:Array;
		
		private var _avgBandwidth:Number;

		private var _peakBandwidth:Number = 0;
		private var _akamaiHDStream:AkamaiHDNetStreamWrapper;
		private var _netStream:NetStream;
		
		private var _reportBandwidthTimer:Timer;
		private var _akamaiHDNetStream:AkamaiHDNetStreamWrapper;
		private var _maxBytesPerSecond:Number;
		private var _playbackBytesPerSecond:Number;
		
		
		public function AkamaiBandwidthMonitor(enforcer:SingletonEnforcer)
		{
		}


		

		public function get bufferMax():Number
		{
			return _bufferMax;
		}

		public function get akamaiHDNetStream():AkamaiHDNetStreamWrapper
		{
			return _akamaiHDNetStream;
		}

		public function set akamaiHDStream(value:AkamaiHDNetStreamWrapper):void
		{
			_akamaiHDNetStream = value;
			if(this._bwTimer==null){
				this.createTimers();
			}	
		}

		public function get akamaiZStream():AkamaiZStreamWrapper
		{
			return _akamaiZStream;
		}

		public function set akamaiZStream(value:AkamaiZStreamWrapper):void
		{
			_akamaiZStream = value;
			if(this._bwTimer==null){
				this.createTimers();
			}
		}
		public function set netStream(value:NetStream):void{
			this._netStream = value;
			if(this._bwTimer==null){
				this.createTimers();
			}
		}
		
		private function createTimers():void
		{
			this._bwTimer = new Timer(BW_TIMER_INTERVAL);
			this._bwTimer.addEventListener(TimerEvent.TIMER,onUpdateBW);
			this._bwTimer.start();
			
			this._reportBandwidthTimer = new Timer(ExternalConfig.getInstance().REPORT_BW_TIMER_INTERVAL,1);
			this._reportBandwidthTimer.addEventListener(TimerEvent.TIMER,onReportBandwidth);
			if(ExternalConfig.getInstance().reportBandwidth==true){
				this._reportBandwidthTimer.start();
			}
		}
		
		protected function onReportBandwidth(event:TimerEvent):void
		{
			ExternalInterfaceController.getInstance().reportBandwidth(this.bandwidth);
			
		}
		public function stopTimer():void{
			if(this._bwTimer.running){
				this._bwTimer.stop();
			}
		}
		
		protected function onUpdateBW(event:TimerEvent):void
		{
			if(!this._avgBandwidthArray){
				this._avgBandwidthArray = new Array();
			}
			
				
					
				if(this._akamaiZStream){
					if(this._akamaiZStream.bandwidth>0){
						_bandwidth=this._akamaiZStream.bandwidth;
					
					}
					this._bufferMax = this._akamaiZStream.bufferTime
				}else if(this._akamaiHDNetStream){
					var netHDStreamBW:Number = Number(this._akamaiHDNetStream.estimatedCurrentBandwidth);
					if(netHDStreamBW>0){
						_bandwidth=netHDStreamBW
							
					}
					this._bufferMax = this._akamaiHDNetStream.bufferTime
				}else{
					
					if(this._netStream){
							var netStreamBW:Number = org.casalib.util.ConversionUtil.bytesToKilobits(this._netStream.info.currentBytesPerSecond)

							if(netStreamBW>0){
								_bandwidth =netStreamBW
							}
					this._bufferMax = this._netStream.bufferTime
						if(this._netStream.info.maxBytesPerSecond>0){
							this._maxBytesPerSecond = this._netStream.info.maxBytesPerSecond
						}
						this._playbackBytesPerSecond = this._netStream.info.playbackBytesPerSecond;
					}
				}
					// Standard bandwidth measurement technique 
					_avgBandwidthArray.unshift(_bandwidth);
					var sampleLen:int = _avgBandwidthArray.length;
					
					if(sampleLen>BANDWIDTH_SAMPLE_COUNT){
						
						_avgBandwidthArray.pop();
						}
						_avgBandwidth = 0;
						
						for (var b:uint=0;b<sampleLen-1;b++) {
							_avgBandwidth += _avgBandwidthArray[b];
							_peakBandwidth = _avgBandwidthArray[b] > _peakBandwidth ? _avgBandwidthArray[b]: _peakBandwidth;
						}
						this._avgBandwidth=Number(this._avgBandwidth/sampleLen)
						
					
			//	tracer.log("sampleLen "+this._avgBandwidthArray.length+" max bandwidth "+this._avgBandwidth+" peak "+this._peakBandwidth,_classname);
				
		}
		public static function getInstance():AkamaiBandwidthMonitor
		{
			
			if (AkamaiBandwidthMonitor.instance == null)
			{
				AkamaiBandwidthMonitor.instance = new AkamaiBandwidthMonitor(new SingletonEnforcer());
			}
			return AkamaiBandwidthMonitor.instance;
		}
		public function get bandwidth():String
		{
			return _bandwidth.toFixed(2);
		}
		public function get avgBandwidth():String{
			return this._avgBandwidth.toFixed(2);
		}
		public function get peakBandwidth():String{
			return this._peakBandwidth.toFixed(2);
		}

		public function get maxBytesPerSecond():Number
		{
			return _maxBytesPerSecond;
		}

		public function get playbackBytesPerSecond():Number
		{
			return _playbackBytesPerSecond;
		}


	}
}

class SingletonEnforcer{}