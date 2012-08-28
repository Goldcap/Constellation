package
{
	import com.sierrastarstudio.utils.bandwidthCheck;
	
	import flash.display.Loader;
	import flash.display.LoaderInfo;
	import flash.display.Sprite;
	import flash.events.Event;
	import flash.events.EventDispatcher;
	import flash.events.IOErrorEvent;
	import flash.external.ExternalInterface;
	import flash.net.URLRequest;

	public class BandwidthCheckTester extends Sprite
	{
		private var _bwCheck:bandwidthCheck;
		/**
		 * bitrates to check against - looks like we'll check the first one and see if we're ok in the range
		 */ 
		private var _bitRates:Array;
		public function BandwidthCheckTester()
		{
				
			if(this.stage){
				init();
			}else{
				this.addEventListener(Event.ADDED_TO_STAGE,init);
			}
		}
		
		private function init():void{
			var paramObj:Object = LoaderInfo(this.stage.loaderInfo).parameters;
			var bitArray:String 
			var bwFilePath:String;
			
			if(paramObj["bitRates"]==null){
				bitArray = "100,200,300";
			}else{
				bitArray = paramObj["bitRates"];
			}
			this._bitRates = new Array();
			this._bitRates = bitArray.split(",");
			
			if(paramObj["filePath"]==null){
					bwFilePath =  "http://www.sierrastarstudio.com/wsp/QR_pingtag_Larger_500K.jpg";
				}else{
					bwFilePath = paramObj["filePath"]
				}
			this._bwCheck = new bandwidthCheck(bwFilePath)
				
			this._bwCheck.addEventListener(Event.COMPLETE,onBWComplete);
			this._bwCheck.start();
		}
		
		protected function onBWComplete(event:Event):void
		{
			var bitrateCount:int = this._bitRates.length;
			for(var i:int = 0;i<bitrateCount;i++){
				if(this._bwCheck.speed>bitrateCount){
					//TODO check against rates here
				}
			}
			if(ExternalInterface.available){
				ExternalInterface.call('speedResult',this._bwCheck.speed);
			}
			trace("results are "+this._bwCheck.speed);
			
		}
		
	}
}