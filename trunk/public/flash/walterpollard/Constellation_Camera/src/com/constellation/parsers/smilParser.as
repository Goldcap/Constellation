package com.constellation.parsers
{
	import com.constellation.events.constellationEvent;
	import com.constellation.externalConfig.ExternalConfig;
	import com.constellation.services.tokenService;
	import com.constellation.utilities.StringUtil;
	import com.constellation.utilities.TimeUtil;
	import com.sierrastarstudio.utils.tracer;
	
	import flash.events.Event;
	import flash.events.EventDispatcher;
	import flash.events.IOErrorEvent;
	import flash.events.SecurityErrorEvent;
	import flash.net.URLLoader;
	import flash.net.URLRequest;
	
	import org.casalib.events.LoadEvent;
	import org.casalib.load.DataLoad;
	import org.openvideoplayer.net.dynamicstream.DynamicStreamItem;

	public class smilParser extends EventDispatcher
	{
		private var _classname:String = "com.constellation.parsers.smilParser";
		private var _rawData:XML;
		private var _xml:XML	
		private var _busy:Boolean = false;
		private var _hostName:String;
		private var _protocol:String;
		private var _dsi:DynamicStreamItem;
		private var _streamName:String;

		private var _smilLoad:DataLoad;
		
		public function smilParser()
		{
			tokenService.getInstance().addEventListener(constellationEvent.SMIL_LOADED,onSMILLoaded);
			
		}
		
		protected function onSMILLoaded(evt:constellationEvent):void
		{
			this.smilData = evt.data as XML
			
		}
		
		public function loadSMILDirect(smilPath:String):void{
			var pathArray = smilPath.split(".");
			var lastChunk:String = pathArray[pathArray.length-1];
			
			var isSMILFile:Boolean;
			if(lastChunk == "smil"){
				_smilLoad = new DataLoad(smilPath);
				_smilLoad.addEventListener(LoadEvent.COMPLETE,onDirectSMILLoaded);
				_smilLoad.addEventListener(IOErrorEvent.IO_ERROR,onSMILError);
				_smilLoad.start();
			}else{
				tracer.log("NOT A SMIL FILE "+lastChunk,_classname);
				dispatchEvent(new constellationEvent(constellationEvent.UNKNOWN_SMIL_FORMAT,smilPath));
			}
		}
		
		protected function onSMILError(event:IOErrorEvent):void
		{
			tracer.log("error loading file "+event.text,_classname);
			dispatchEvent(new constellationEvent(constellationEvent.UNKNOWN_SMIL_FORMAT));	
		}
		
		protected function onDirectSMILLoaded(event:LoadEvent):void
		{
		
			this.smilData = new XML(this._smilLoad.dataAsXml);
		
			
		}
		/** Parses the SMIL file into useful properties
		 * @private
		 
		 protected function parseXML():void 
		{
			if (!verifySMIL(_xml)) 
			{
				//dispatchEvent(new constellationEvent(constellationEvent.ERROR, new constellationEvent(OvpError.XML_BOSS_MALFORMED)));
			} 
			else 
			{
				var ns:Namespace = _xml.namespace();
				_hostName = _xml.ns::head.ns::meta.@base.slice(_xml.ns::head.ns::meta.@base .indexOf("://")+3);
				_protocol = _xml.ns::head.ns::meta.@base.slice(0,_xml.ns::head.ns::meta.@base .indexOf("://")).toLowerCase();
				_dsi = parseDsi(_xml);
				_streamName = _dsi.streams[0].name;
				tracer.log("smil parsed dispatch event ",_classname);
				dispatchEvent(new constellationEvent(constellationEvent.SMIL_PARSED));
			}
			_busy = false;
		}*/
		 protected function parseXML():void 
		{
			//errors at this level will go ahead and bubble up to the model and display to the user
			
			if (!verifySMIL(_xml)) 
			{
			//	dispatchEvent(new OvpEvent(OvpEvent.ERROR, new OvpError(OvpError.XML_MALFORMED)));
			} 
			else 
			{
				var ns:Namespace = _xml.namespace();
				
				var isBaseConnectionType:String// = _xml.ns::head.ns::meta.@base.slice(_xml.ns::head.ns::meta.@base .indexOf("://")+3)>
				var isContentConnectionType:String// = _xml.ns::head.ns::meta.@base.slice(_xml.ns::head.ns::meta.@content .indexOf("://")+3)
				
				var isBaseProtocolType:String// = _xml.ns::head.ns::meta.@base.slice(_xml.ns::head.ns::meta.@base .indexOf("://")+3)>
				var isContentProtocolType:String// = _xml.ns::head.ns::meta.@base.slice(_xml.ns::head.ns::meta.@content .indexOf("://")+3)
				
				try{	
					isBaseConnectionType = (_xml.ns::head.ns::meta.@base !=null)?_xml.ns::head.ns::meta.@base:"";
					_protocol = _xml.ns::head.ns::meta.@base.slice(0,_xml.ns::head.ns::meta.@base .indexOf("://")).toLowerCase();
				}catch (err:Error) {
					tracer.log("no base",_classname);
				}
				//	if(_protocol!=null){
				try{	
					var targetContentNode:XMLList = _xml.ns::head.ns::meta.(@name == "httpBase");
					
					isContentConnectionType = ( targetContentNode != null)? targetContentNode.@content:"";
					_protocol = targetContentNode.@content.slice(0,targetContentNode.@content.indexOf("://")).toLowerCase();
				}catch (err:Error) {
					tracer.log("no content",_classname);
				}
				//	}
				
				var serverConnection:String = (isBaseConnectionType.length>0)?isBaseConnectionType:isContentConnectionType
				//	var protocol:String = isBaseProtocol.length>0)?isBaseProtocol:
				tracer.log("serverConnection " +serverConnection +"  isContentType "+isContentConnectionType+"  _protocol "+_protocol, _classname);
				if(serverConnection.length>1){
							//_xml.ns::head.ns::meta.@base.slice(_xml.ns::head.ns::meta.@base .indexOf("://")+3);
						_hostName =serverConnection.slice(serverConnection.indexOf("://")+3)
						//	_protocol = _xml.ns::head.ns::meta.@base.slice(0,_xml.ns::head.ns::meta.@base .indexOf("://")).toLowerCase();
						_dsi = parseDsi(_xml);
						_streamName = _dsi.streams[0].name;
						tracer.log("smil parsed dispatch event ",_classname);
							dispatchEvent(new constellationEvent(constellationEvent.SMIL_PARSED));
				}else{
					tracer.log("unknown smil format",_classname);
					dispatchEvent(new constellationEvent(constellationEvent.UNKNOWN_SMIL_FORMAT));
				}
			}
			_busy = false;
		}
		
		 /** Parses the SMIL into a DynamicStreamItem
		  * @private
		  */
		 protected function parseDsi(x:XML):DynamicStreamItem 
		 {
			 var ns: Namespace = x.namespace();
			 var dsi:DynamicStreamItem = new DynamicStreamItem();
			 for (var i:uint = 0; i < x.ns::body.ns::["switch"].ns::video.length(); i++) 
			 {
				 var streamName:String = x.ns::body.ns::["switch"].ns::video[i].@src;
				 //streamName = StringUtil.addPrefix(streamName);
				 
				 var bitrate:Number = Number(x.ns::body.ns::["switch"].ns::video[i].@["system-bitrate"])/1000;
				 
				 var clipBegin:Number = NaN;
				 var clipEnd:Number = NaN;
				 var tempSubClip:String = String(x.ns::body.ns::["switch"].ns::video[i].@["clipBegin"]);
				 
				 if (tempSubClip != null && tempSubClip.length > 0)
				 {
					 clipBegin = TimeUtil.parseTime(tempSubClip); 
				 }
				 
				 tempSubClip = String(x.ns::body.ns::["switch"].ns::video[i].@["clipEnd"]);
				 if (tempSubClip != null && tempSubClip.length > 0)
				 {
					 clipEnd = TimeUtil.parseTime(tempSubClip); 
				 }
				 
				 dsi.addStream(streamName, bitrate);
				 if (!isNaN(clipBegin))
				 {
					 dsi.start = clipBegin;
				 }
				 
				 if (!isNaN(clipEnd))
				 {
					 dsi.len = dsi.start > 0 ? clipEnd - dsi.start : clipEnd;
				 }
			 }
			 return dsi;
		 }
		/** A simple verification routine to check if the XML received conforms
		 * to some basic requirements. This routine does not validate against
		 * any DTD.
		 * @private
		 */
		protected function verifySMIL(src:XML):Boolean 
		{
			//TODO create appropriate verify of smil format
			var ns:Namespace = src.namespace();
			var isVerified:Boolean = true;
			/*
			if (src.ns::body.ns::["switch"] != undefined) 
			{
				var checkBase:Boolean = Boolean(src.ns::head.ns::meta.@base.toXMLString());
				var checkContent:Boolean = Boolean(src.ns::head.ns::meta.@content.toXMLString());
				
				isVerified = !((checkBase==false&&checkContent==false)|| src.ns::body.ns::["switch"].ns::video.length() < 1 );
			}
			*/
			return isVerified;
		}
		/**
		 * The response data as an XML object. 
		 * 
		 */
		public function get xml():XML {
			return _xml;
		}
		/**
		 * Smil data passed in directly
		 */ 
		public function set smilData(smil:XML):void{
			this._rawData=smil;
			try {
				_xml = new XML(_rawData);
				
				dispatchEvent(new constellationEvent(constellationEvent.SMIL_LOADED));
				parseXML();
			} catch (err:Error) {
				_busy = false;
				
				dispatchEvent(new constellationEvent(constellationEvent.ERROR, new constellationEvent(constellationEvent.XML_MALFORMED)));
			}
		}
		/** Handles the XML request response
		 * @private
		 */
		
		protected function xmlLoaded(e:Event):void {
			//_timeoutTimer.stop();
			_rawData=e.currentTarget.data.toString();
			try {
				_xml = new XML(_rawData);
				
				dispatchEvent(new constellationEvent(constellationEvent.SMIL_LOADED));
				parseXML();
			} catch (err:Error) {
				_busy = false;
				
				dispatchEvent(new constellationEvent(constellationEvent.ERROR, new constellationEvent(constellationEvent.XML_MALFORMED)));
			}
		}
		/**
		 * The raw data string returned by the request. This value will still
		 * be populated even if the data is not well-formed, to assist with debugging.
		 * 
		 * @return string representing the text returned by the request.
		 * 
		 */
		public function get rawData():String {
			return _rawData;
		}
		/**
		 * Boolean parameter indicating whether the class is already busy loading a file. Since the 
		 * load is asynchronous, the class will not allow a new <code>load()</code> request until
		 * the prior request has ended.
		 * 
		 */
		public function get isBusy():Boolean {
			return _busy;
		}
		
		/** Catches IO errors when requesting the xml 
		 * @private
		 */
		private function catchIOError(e:IOErrorEvent):void {
			//_timeoutTimer.stop();
			_busy = false;
			dispatchEvent(new constellationEvent(constellationEvent.ERROR, new constellationEvent(constellationEvent.HTTP_LOAD_FAILED)));
		}
		
		/** Catches Security errors when requesting the xml 
		 * @private
		 */
		private function catchSecurityError(e:SecurityErrorEvent):void {
			catchIOError(null);
		}

		public function get dsi():DynamicStreamItem
		{
			return _dsi;
		}

		public function get hostName():String
		{
			return _hostName;
		}

		public function get protocol():String
		{
			return _protocol;
		}
		/**
		 * Returns the highest index available in the DynamicStreamItem which is being played.
		 */
		public function get maxIndex():int
		{
			return _dsi.streamCount - 1;
		}
		


	}
}