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
	
	/**
	 * ...
	 * @author LLNW
	 * 
	 * Holds values stripped from a URL pertaining to a stream.
	 * 
	 */
	public class StreamValues 
	{
		
		public function StreamValues() 
		{
			//Create new Container for Stream Values 
		}
		
		private var _url:String = "";
		private var _urlParamsString:String = ""; 
		private var _filename:String = "";
		private var _pathNoStream:String = "";
		private var _urlParamsNoFSString:String = "";
		private var _domain:String = "";
		private var _hasFileSeekURLVar:Boolean;
		private var _protocol:String = "";
		private var _path:String = "";
		private var _extension:String = "";
		private var _stream:String = "";
		private var _domainNoPort:String = "";
		private var _urlParamsCollection:Array;
		private var _isProgressive:Boolean;
		
		public function get isProgressive():Boolean{ return _isProgressive; }
		public function set isProgressive(value:Boolean):void { _isProgressive = value; }
		
		
		public function get urlParamsCollection():Array{ return _urlParamsCollection; }
		public function set urlParamsCollection(value:Array):void { _urlParamsCollection = value; }
		
		public function get url():String { return _url; }
		public function set url(value:String):void { _url = value; }
		
		public function get urlParamsString():String { return _urlParamsString; }
		public function set urlParamsString(value:String):void { _urlParamsString = value; }
		
		public function get filename():String { return _filename; }
		public function set filename(value:String):void { _filename = value; }
		
		public function get pathNoStream():String { return _pathNoStream; }
		public function set pathNoStream(value:String):void { _pathNoStream = value; }
		
		public function get urlParamsNoFSString():String { return _urlParamsNoFSString; }
		public function set urlParamsNoFSString(value:String):void { _urlParamsNoFSString = value; }
		
		public function get domain():String { return _domain; }
		public function set domain(value:String):void { _domain = value; }
		
		public function get hasFileSeekURLVar():Boolean{ return _hasFileSeekURLVar; }
		public function set hasFileSeekURLVar(value:Boolean):void { _hasFileSeekURLVar = value; }
		
		public function get protocol():String { return _protocol; }
		public function set protocol(value:String):void { _protocol = value; }
		
		public function get path():String { return _path; }
		public function set path(value:String):void { _path = value; }
		
		public function get extension():String { return _extension; }
		public function set extension(value:String):void { _extension = value; }
		
		public function get stream():String { return _stream; }
		public function set stream(value:String):void { _stream = value; }
		
		public function get domainNoPort():String { return _domainNoPort; }
		public function set domainNoPort(value:String):void { _domainNoPort = value; }
		
	}
	
}