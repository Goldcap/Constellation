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
	
	import flash.utils.ByteArray;
	
	public class HTTPStreamingTestsHelper
	{
		public static function createAdobeBootstrapBox(descriptor:AdobeBootstrapBoxDescriptor):ByteArray
		{
			var ba:ByteArray = new ByteArray();
			writeAbstAttributes(descriptor, ba);
			
			return ba;
		}
		
		public static function createBasicAdobeBootstrapBoxDescriptor():AdobeBootstrapBoxDescriptor
		{
			var abstDescriptor:AdobeBootstrapBoxDescriptor = new AdobeBootstrapBoxDescriptor();
			abstDescriptor.bootstrapInfoVersion = 100;
			abstDescriptor.live = false;
			abstDescriptor.update = false;
			abstDescriptor.timeScale = 1000;
			abstDescriptor.currentMediaTimeH = 0;
			abstDescriptor.currentMediaTimeL = 22000;
			abstDescriptor.smpteTimeCodeOffsetH = 0;
			abstDescriptor.smpteTimeCodeOffsetL = 0;
			abstDescriptor.movieIdentifier = "movie";
			abstDescriptor.addServerBaseUrl("http://www.httpstreaming.net/server_base_url");

			var segmentRunTable:AdobeSegmentRunTableDescriptor = new AdobeSegmentRunTableDescriptor();
			segmentRunTable.addEntry(1, 10);
			segmentRunTable.addEntry(2, 5);
			abstDescriptor.addSegmentRunTable(segmentRunTable);
			
			var fragmentRunTable:AdobeFragmentRunTableDescriptor = new AdobeFragmentRunTableDescriptor();
			fragmentRunTable.timeScale = 1000;
			//
			//	Fragment runtable entries
			//  
			//  first_fragment		first_fragment_time_stamp		fragment_duration		discontinuity_indicator
			//  	1					0								4000					-
			// 		5					16000							0						1
			// 		6					16000							0						2
			// 		7					16000							500						-
			// 		11					18000							0						3
			// 		12					18000							1000					-
			// 		16					22000							0						0 
			//
			fragmentRunTable.addEntry(1, 0, 0, 4000, 0);
			fragmentRunTable.addEntry(5, 0, 16000, 0, 1);
			fragmentRunTable.addEntry(6, 0, 16000, 0, 2);
			fragmentRunTable.addEntry(7, 0, 16000, 500, 0);
			fragmentRunTable.addEntry(11, 0, 18000, 0, 3);
			fragmentRunTable.addEntry(12, 0, 18000, 1000, 0);
			fragmentRunTable.addEntry(0, 0, 22000, 0, 0);
			abstDescriptor.addFragmentRunTable(fragmentRunTable);
			
			return abstDescriptor;
		}

		private static function writeAbstAttributes(descriptor:AdobeBootstrapBoxDescriptor, ba:ByteArray):void
		{			
			writeFullBox("abst", ba);
			// bootstrapinfo_version 
			ba.writeUnsignedInt(descriptor.bootstrapInfoVersion);
			writeAbstFlags(descriptor.profile, descriptor.live, descriptor.update, ba);
			// timescale is ms
			ba.writeUnsignedInt(descriptor.timeScale);
			// current media time
			ba.writeUnsignedInt(descriptor.currentMediaTimeH);
			ba.writeUnsignedInt(descriptor.currentMediaTimeL);
			// smpte time code offset
			ba.writeUnsignedInt(descriptor.smpteTimeCodeOffsetH);
			ba.writeUnsignedInt(descriptor.smpteTimeCodeOffsetL);
			// movie identifier
			ba.writeUTFBytes(descriptor.movieIdentifier);
			ba.writeByte(0);

			// server base urls
			writeStrings(descriptor.serverBaseUrls, ba);
			// quality segment url modifiers
			writeStrings(descriptor.qualitySegmentUrlModifiers, ba);
							
			// drm data
			ba.writeUTFBytes(descriptor.drmData);
			ba.writeByte(0);
			// metadata
			ba.writeUTFBytes(descriptor.metadata);
			ba.writeByte(0);
			
			writeSegmentRunTables(descriptor, ba);
			writeFragmentRunTables(descriptor, ba);					
		}
		
		private static function writeStrings(strings:Vector.<String>, ba:ByteArray):void
		{
			ba.writeByte(strings.length);
			for (var i:int = 0; i < strings.length; i++)
			{
				ba.writeUTFBytes(strings[i]);
				ba.writeByte(0);
			}
		}
		
		private static function writeSegmentRunTables(descriptor:AdobeBootstrapBoxDescriptor, ba:ByteArray):void
		{
			// segment runtable count
			ba.writeByte(descriptor.segmentRunTables.length);
			for (var i:int = 0; i < descriptor.segmentRunTables.length; i++)
			{
				writeSegmentRunTable(descriptor.segmentRunTables[i], ba);
			}
		}
		
		private static function writeSegmentRunTable(table:AdobeSegmentRunTableDescriptor, ba:ByteArray):void
		{
			writeFullBox("asrt", ba);
			
			// quality segment url modifiers
			writeStrings(table.qualitySegmentUrlModifiers, ba);
			
			// entry count
			ba.writeUnsignedInt(table.segmentRunTableEntries.length);
			for (var i:int = 0; i < table.segmentRunTableEntries.length; i++)
			{
				// first segment
				ba.writeUnsignedInt(table.segmentRunTableEntries[i].firstSegment);
				// fragments per segment
				ba.writeUnsignedInt(table.segmentRunTableEntries[i].fragmentsPerSegment);
			}
		}
		
		private static function writeFragmentRunTables(descriptor:AdobeBootstrapBoxDescriptor, ba:ByteArray):void
		{
			// fragment runtable count
			ba.writeByte(descriptor.fragmentRunTables.length);
			for (var i:int = 0; i < descriptor.fragmentRunTables.length; i++)
			{
				writeFragmentRunTable(descriptor.fragmentRunTables[i], ba);
			}
		}

		private static function writeFragmentRunTable(table:AdobeFragmentRunTableDescriptor, ba:ByteArray):void
		{
			writeFullBox("afrt", ba);
			// time scale ms
			ba.writeUnsignedInt(table.timeScale);
			// quality segment url modifiers
			writeStrings(table.qualitySegmentUrlModifiers, ba);

			// entry count
			ba.writeUnsignedInt(table.fragmentRunTableEntries.length);
			for (var i:int = 0; i < table.fragmentRunTableEntries.length; i++)
			{
				var entry:FragmentRunTableEntryDescriptor = table.fragmentRunTableEntries[i];
				writeFragmentRuntableEntry(
					entry.firstFragment, 
					entry.firstFragmentTimeStampH, 
					entry.firstFragmentTimeStampL, 
					entry.duration, 
					entry.discontinuityIndicator,
					ba);
			}
		}

		private static function writeFragmentRuntableEntry(
			firstFragment:uint, hbts:uint, lbts:uint, duration:uint, discontinuityIndicator:uint, ba:ByteArray):void
		{
			ba.writeUnsignedInt(firstFragment);				// first fragment
			ba.writeUnsignedInt(hbts);						// high 32-bit of time stamp
			ba.writeUnsignedInt(lbts);						// low 32-bit of time stamp
			ba.writeUnsignedInt(duration);					// fragment duration
			if (duration == 0)
			{ 
				ba.writeByte(discontinuityIndicator);		// discontinuity indicator
			} 
		} 
		
		private static function writeFullBox(type:String, ba:ByteArray):void
		{
			ba.writeUnsignedInt(0);
			ba.writeUTFBytes(type);
			// version = 0
			ba.writeByte(0);
			// flags= 0
			ba.writeByte(0);
			ba.writeByte(0);
			ba.writeByte(0);			
		}	
			
		private static function writeAbstFlags(profile:uint, live:Boolean, update:Boolean, ba:ByteArray):void
		{
			var data:uint = 0;
			if (live)
			{
				data |= 0x0200;
			}
			if (update)
			{
				data |= 0x0100;
			}
			if (profile == 1)
			{
				data |= 0x1000;
			}
			ba.writeByte(data);
		}
	}
}