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
package com.llnw.utils 
{
	import com.llnw.utils.URLUtil
	/**
	 * ...
	 * @author LLNW
	 * a middle class to comunicate with URLUtil.as to keep parsing with in the URLUtil.
	 * 
	 */
	public class URIUtil
	{
		
		
		private var _fullFilePath:String;
		private var _applicationName:String;
		private var _shortName:String;
		private var _fileName:String;
		private var _serverName:String;
		private var _protocol:String;
		private var _fileExtension:String;
		private var _queryParams:String;
		private var _params:Object;
		
		public function URIUtil() 
		{

		}
		
		public function parseURI(value:String):void {

			var uri:String = value;
		
			_serverName = URLUtil.getDomain(uri)
			_protocol = URLUtil.getProtocol(uri)
	
			_fileName = URLUtil.getFilename(uri)
			_fullFilePath = URLUtil.getFullPath(uri)
			_fileExtension = URLUtil.getFileExtension(uri)
			writeParams(URLUtil.getQueryParams(uri))
			buildQuery(_params)
			
			/*
			trace("File extension: "+URLUtil.getFileExtension(uri))
			trace("full path: "+URLUtil.getFullPath(uri))
			trace("file name: "+URLUtil.getFilename(uri))
			trace("is a valid url: "+URLUtil.isValid(uri))
			*/
		}
		
		
		private function sanitizeStream():String{
			
			_fileName = _fileName.replace("." + _fileExtension, "")

			//this is to handle sub directories after the app name.
			var urlArray:Array = _fullFilePath.split("/")
			var directory:String = "";
			for (var i:int = 3; i < urlArray.length; i++) {
				directory += urlArray[i]

				if (i != urlArray.length - 1) {
					directory +="/"
				}

			}
			directory = directory.replace("mp4:", "");
			//use _fileExtension to decide how to format the URL.
			if (_fileExtension == "flv" || _fileExtension == "") {
				directory = directory
				return directory.replace(".flv", "");
			}else if(_fileExtension == "mp3"){
				return "mp3:"+directory;
			}else {
				
				return "mp4:"+directory;
			}
		}
		
		
		private var _fullQuery:String = ""
		private function writeParams(values:String) {
			_fullQuery = values;
			_params = new Object();
			var tempBreak:Array = new Array();
			tempBreak = values.split("&")
			for (var i:int = 0; i < tempBreak.length; i++) {
				var namePair:Array = new Array()
				namePair = tempBreak[i].split("=")
				_params[namePair[0]] = namePair[1];
			}
			trace(" " )
		}
		
		private var _streamType:String = "";
		private function buildQuery(obj:Object):void {
			_queryParams = "";
			for (var i:String in obj) {
				if(i != "ms" && i != "streamType"){
					_queryParams += i + "=" + obj[i]+"&"
				}
				if (i == "streamType") {
					_streamType = obj[i];
				}
			}
			if(_streamType == "MOOVSeek" || _streamType == "FLVSeek"){
				_queryParams = _queryParams.slice(0, -1)
			}else {
				var val:Array = _fullQuery.split("&streamType")
				_queryParams = val[0];
			}
		}
		public function get streamType():String {
			return _streamType;
		}
		
		
		public function set params(value:String):void {
			writeParams(value)
		}
		
		
		
		public function get sanitizedStream():String {
			return sanitizeStream()
		}
		
		public function get serverName():String {
			return _protocol+"://"+_serverName;
		}
		
		public function get applicationName():String {
			return _applicationName;
		}
		
		public function get shortName():String {
			return _shortName;
		}
		public function get fileName():String {
			
			return _fileName;
		}
		public function get protocol():String {
			return _protocol;
		}
		public function get fullPath():String {
			return _fullFilePath;
		}
		public function get fileExtension():String {

			return _fileExtension;
		}
		public function get queryParams():String {

			return _queryParams;
		}
		
	}
	
}