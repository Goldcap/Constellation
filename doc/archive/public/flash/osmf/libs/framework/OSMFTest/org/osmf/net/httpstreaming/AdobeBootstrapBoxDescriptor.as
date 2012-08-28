/*****************************************************
*  
*  Copyright 2009 Adobe Systems Incorporated.  All Rights Reserved.
*  
*****************************************************
*  The contents of this file are subject to the Mozilla Public License
*  Version 1.1 (the "License"); you may not use this file except in
*  compliance with the License. You may obtain a copy of the License at
*  http://www.mozilla.org/MPL/
*   
*  Software distributed under the License is distributed on an "AS IS"
*  basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
*  License for the specific language governing rights and limitations
*  under the License.
*   
*  
*  The Initial Developer of the Original Code is Adobe Systems Incorporated.
*  Portions created by Adobe Systems Incorporated are Copyright (C) 2009 Adobe Systems 
*  Incorporated. All Rights Reserved. 
*  
*****************************************************/
package org.osmf.net.httpstreaming
{
	import __AS3__.vec.Vector;
	
	public class AdobeBootstrapBoxDescriptor
	{
		public var bootstrapInfoVersion:uint = 0;
		public var profile:uint = 0;
		public var live:Boolean = false;
		public var update:Boolean = false;
		public var timeScale:uint = 1000;
		public var currentMediaTimeH:uint = 0;
		public var currentMediaTimeL:uint = 0;
		public var smpteTimeCodeOffsetH:uint = 0;
		public var smpteTimeCodeOffsetL:uint = 0;
		public var movieIdentifier:String = "";
		
		public var serverBaseUrls:Vector.<String> = new Vector.<String>();
		public var qualitySegmentUrlModifiers:Vector.<String> = new Vector.<String>();
		
		public var drmData:String = "";
		public var metadata:String = "";
		
		public var segmentRunTables:Vector.<AdobeSegmentRunTableDescriptor> = new Vector.<AdobeSegmentRunTableDescriptor>();
		public var fragmentRunTables:Vector.<AdobeFragmentRunTableDescriptor> = new Vector.<AdobeFragmentRunTableDescriptor>();
		
		public function AdobeBootstrapBoxDescriptor()
		{
		}

		public function addServerBaseUrl(url:String):void
		{
			serverBaseUrls.push(url);
		}
		
		public function addQualitySegmentUrlModifier(modifier:String):void
		{
			qualitySegmentUrlModifiers.push(modifier);
		}
		
		public function addSegmentRunTable(table:AdobeSegmentRunTableDescriptor):void
		{
			segmentRunTables.push(table);
		}

		public function addFragmentRunTable(table:AdobeFragmentRunTableDescriptor):void
		{
			fragmentRunTables.push(table);
		}
	}
}