package com.llnw.data 
{
	import flash.system.Capabilities;
	import flash.system.System;
	import flash.system.Security;
	import flash.system.SecurityPanel;
	/**
	 * ...
	 * @author 
	 */
	public class SystemData 
	{
		
		public function SystemData() 
		{
			
		}
		public function get userData():String {
			
			var returnData:String = collectData(); 
			
			return returnData;
		}
		
		
		private function collectData():String {
			
			
			var theString = ""
			theString += "avHardwareDisable=" + Capabilities.avHardwareDisable
			theString += "&hasAccessibility=" + Capabilities.hasAccessibility
			theString += "&hasAudio=" + Capabilities.hasAudio
			theString += "&hasAudioEncoder=" + Capabilities.hasAudioEncoder
			theString += "&hasEmbeddedVideo=" + Capabilities.hasEmbeddedVideo
			theString += "&hasMP3=" + Capabilities.hasMP3
			theString += "&hasPrinting=" + Capabilities.hasPrinting
			theString += "&hasScreenBroadcast=" + Capabilities.hasScreenBroadcast
			theString += "&hasScreenPlayback=" + Capabilities.hasScreenPlayback
			theString += "&hasStreamingAudio=" + Capabilities.hasStreamingAudio
			theString += "&hasVideoEncoder=" + Capabilities.hasVideoEncoder
			
			theString += "&isDebugger=" + Capabilities.isDebugger
			theString += "&language=" + Capabilities.language
			theString += "&localFileReadDisable=" + Capabilities.localFileReadDisable
			theString += "&manufacturer=" + Capabilities.manufacturer
			theString += "&os=" + Capabilities.os
			theString += "&pixelAspectRatio=" + Capabilities.pixelAspectRatio
			theString += "&playerType=" + Capabilities.playerType
			theString += "&screenColor=" + Capabilities.screenColor
			theString += "&screenDPI=" + Capabilities.screenDPI
			theString += "&screenResolutionX=" + Capabilities.screenResolutionX
			theString += "&screenResolutionY=" + Capabilities.screenResolutionY
			theString += "&serverString=" + Capabilities.serverString
			theString += "&version=" + Capabilities.version
			
			//Display how much ram is being used by the
			var b:int = System.totalMemory;
			var kb:int = b / 1024;
			var mb:int = kb / 1024;
			theString += "This app is using=" + kb + "kbs/" + mb + "mbs of ram."
			return theString;
		}
		
	}
	
}