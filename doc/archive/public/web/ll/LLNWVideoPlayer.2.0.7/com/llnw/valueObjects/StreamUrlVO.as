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
package com.llnw.valueObjects
{
	import com.llnw.utils.URLUtil;
	
	/**
	 * Object representation of a streaming url based on a basic url object representation, <code>UrlVO</code>.
	 *  
	 * @author LLNW
	 */	
	public class StreamUrlVO extends UrlVO
	{
		/*
		 * Override the protocol getter to determine if this is progressive or streaming
		 */
		public override function set protocol(value:String):void{
			super.protocol = value;
			
			_isProgressive = (_protocol.indexOf("rtmp") == -1);
		}
		
		private var _pathNoStream:String = "";
		public function get pathNoStream():String{ return _pathNoStream; }
		public function set pathNoStream(value:String):void{ _pathNoStream = value; }
		
		private var _stream:String = "";
		public function get stream():String{ return _stream; }
		public function set stream(value:String):void{ _stream = value; }
		
		private var _isProgressive:Boolean = false;
		
		/**
		 * Denotes whether the current stream is a progressive stream.
		 * 
		 * @default false 
		 * @return 
		 */		
		public function get isProgressive():Boolean{ return _isProgressive; }
		public function set isProgressive(value:Boolean):void{ _isProgressive = value; }
		
		private var _urlParamsString:String = "";
		
		/**
		 * The URL variables attached to the stream. For example, a url of <code>http://localhost/myvideo.flv?blah=1&ha=1&me=you</code>
		 * would fill this property with: <code>blah=1&ha=1&me=you</code>.
		 *   
		 * @return 
		 */		
		public function get urlParamsString():String{ return _urlParamsString; }
		public function set urlParamsString(value:String):void{ _urlParamsString = value; }
		
		private var _urlParamsNoFSString:String = "";
		/**nnnn+
		 * If a "file seek" (fs) param exists, it is stripped from the url. This is Limelight Networks specific.
		 *  
		 * @return 
		 */		
		public function get urlParamsNoFSString():String{ return _urlParamsNoFSString; }
		public function set urlParamsNoFSString(value:String):void{ _urlParamsNoFSString = value; }
		
		private var _urlParamsCollection:Array = new Array();
		/**
		 * An array representation of the url params.
		 *  
		 * @return 
		 */		
		public function get urlParamsCollection():Array{ return _urlParamsCollection; }
		public function set urlParamsCollection(value:Array):void{ _urlParamsCollection = value; }
		
		private var _hasFileSeekURLVar:Boolean = false;
		/**
		 * Denotes whether 	a "file seek" url param exists or not 
		 * @return 
		 */		
		public function get hasFileSeekURLVar():Boolean{ return _hasFileSeekURLVar; }
		public function set hasFileSeekURLVar(value:Boolean):void{ _hasFileSeekURLVar = value; }
		
		/**
		 * Parses a URL into a <code>StreamURLVO</code> object representation.
		 * 
		 * @example <listing version="3.0">StreamUrlVO.parseURL("http://www.mysite.com/myvideo.flv");</listing>
		 * 
		 * <p><i>Note:</i> This method taks a few assumptions with respect to the url following Limelight Networks url structure(s).
		 * Changing this function could break the flow of the app. Be sure to change this function with care.</p>
		 * 
		 * @param value String
		 * @returns StreamUrlVO
		 */
		public static function parseURL(value:String):StreamUrlVO{
			if(!URLUtil.isValid(value)){
				throw new Error("StreamURLVO: Invalid URL");
			}else{
				var _result:StreamUrlVO = new StreamUrlVO();
				_result.url = value;
				_result.domain = URLUtil.getDomainWithPort(value);
				_result.domainNoPort = URLUtil.getDomain(value);
				_result.protocol = URLUtil.getProtocol(value);
				_result.path = URLUtil.getFullPath(value);
				_result.filename = URLUtil.getFilename(value);
				if(_result.filename.length > 0) _result.extension = URLUtil.getFileExtension(_result.filename);
				
				_result.urlParamsString = _result.urlParamsNoFSString = URLUtil.getQueryParams(value);
				if(_result.urlParamsString.length > 0){
					_result.urlParamsCollection = _result.urlParamsString.split("&");
				}
				
				//Limelight specific for FLV Seek
				_result.hasFileSeekURLVar = _result.isProgressive && (_result.urlParamsString.indexOf("fs=") > -1);
				if(_result.hasFileSeekURLVar) _result.urlParamsNoFSString = _result.urlParamsString.replace(/fs=[a-zA-Z0-9]+[\&]/g, "");
				
				//Parse the stream name/path
				if(!_result.isProgressive){
					var pathSplit:Array = _result.path.split("/");
					pathSplit.shift();
					var len:Number = pathSplit.length;
					
					if(_result.extension.length > 0){
					//On Demand
						_result.pathNoStream = pathSplit[0] + "/" + pathSplit[1];
						//remove the first two
						pathSplit.shift();
						pathSplit.shift();
						_result.stream = pathSplit.join("/");
						_result.stream = sanitizeStream(_result);
					}else{
					//Live
						//Legacy Limelight Live URL's
						// ex - rtmp://hostname/live/axxxx/streamname
						if(_result.path.indexOf("/live/") == 0){
							_result.pathNoStream = pathSplit[0] + "/" + pathSplit[1];
							pathSplit.shift();
							pathSplit.shift();
							_result.stream = pathSplit.join("/"); 
						}else{
						//Limelight Shortname URL's
						// ex - rtmp://hostname/shortname/streamname
							_result.pathNoStream = pathSplit[0];
							pathSplit.shift();
							_result.stream = pathSplit.join("/");
						}
					}
				}
				return _result;
			}
		}
		
		/**
		 * Sanitizes the stream name to insure validity with FMS. 
		 * 
		 * @param stream A StreamURLVO instance
		 * @returns Proper stream url/path with prefix in place, where appropriate
		 */
		private static function sanitizeStream(stream:StreamUrlVO):String{
			//set a proper stream name for mp3 and other media formats supported by FMS 3 and Flash Player "Moviestar"
			stream.stream = stream.stream.replace("."+stream.extension, "")
			if(stream.extension == "flv"){
				return stream.stream;
			}else if(stream.extension == "mp3"){
				return "mp3:"+stream.stream;
			}else{
				return "mp4:"+stream.stream;
			}
		}
	}
}