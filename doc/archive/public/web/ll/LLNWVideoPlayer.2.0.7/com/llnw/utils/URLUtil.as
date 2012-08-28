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
	/**
	 * URL Utility class used to parse URL's.
	 * 
	 * @author LLNW
	 * 
	 */	
	public class URLUtil
	{
		public static function isValid(url:String):Boolean{
			var pattern:RegExp = new RegExp("([^:]+)://([^:/]+)(:[0-9]+)?", "i");
			var matches:Array = url.match(pattern);
			return matches != null;
		}
		
		public static function getDomainWithPort(url:String):String{
			var pattern:RegExp = new RegExp("([^:]+)://([^:/]+)(:[0-9]+)?", "i");
			var matches:Array = url.match(pattern);
			if(matches && matches.length > 2){
				var result:String = matches[2];
				if(matches[3] != undefined){
					result += matches[3];
				}
				return result;
			}else{
				return "";
			}
		}
		
		public static function getDomain(url:String):String{
			var pattern:RegExp = new RegExp("([^:]+)://([^:/]+)(:[0-9]+)?", "i");
			var matches:Array = url.match(pattern);
			if (matches && matches.length > 2) {

				return matches[2];
			}else{
				return "";
			}
		}
		
		public static function getPort(url:String):String{
			var pattern:RegExp = new RegExp("(:[0-9]+)", "i");
			var matches:Array = url.match(pattern);
			if (matches) {

				return matches[0].substring(1);
			}else{
				return "";
			}
		}
		
		public static function getProtocol(url:String):String{
			var pattern:RegExp = new RegExp("([^:]+)://", "i");
			var matches:Array = url.match(pattern);
			if (matches && matches.length > 1) {

				return matches[1];
			}else{
				return "";
			}
		}
		
		public static function getFullPath(url:String):String{
			var pattern:RegExp = new RegExp("([^:]+)://([^:/]+)(:[0-9]+)?(/.*)", "i");
			var matches:Array = url.match(pattern);
			
			if (matches && matches.length > 1) {

				return matches[4];
			}else{
				return "";
			}
		}
		
		public static function getFilename(url:String):String{
			//clear url params
			if(url.indexOf("?") > -1){
				url = url.substr(0, url.indexOf("?"));
			}
			var matches:Array = url.match(/(\w+(\.\w+))$/);
			
			if (matches && matches.length > 0) {

	
				return matches[0];
			}else {
			
				return "";
			}
		}
		
		public static function getFileExtension(url:String):String {
			if(url.indexOf("?") > -1){
				url = url.substr(0, url.indexOf("?"));
			}
			
			var matches:Array = url.match(/(\.\w+)$/);
			
			if (matches && matches.length > 0) {

				return matches[0].substr(1);
			}else{
				return "";
			}
		}
		
		public static function getQueryParams(url:String):String{
			var matches:Array = url.match(/[\?&](?<name>[^&=]+)=(?<value>[^&=]+)/g);
			if (matches && matches.length > 0) {

				return matches.join("").substr(1);
			}else{
				return "";
			}
		}
		
	}
}