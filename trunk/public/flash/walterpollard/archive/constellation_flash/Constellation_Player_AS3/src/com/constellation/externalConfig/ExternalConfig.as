/**
 * ExternalConfig holds all the known flashvars as properties that are exposed<br>
 * Just a tool to easily pull flashvars from anywhere in the project<br>
 * Singleton Object<br>
 * loadOptions must be called with the main stage display object<br>
 * 
 * @author Walter Steve Pollard Jr - walter.pollard.jr@gmail.com
 */
package com.constellation.externalConfig
{
	import com.constellation.config.errorConfig;
	
	import flash.display.DisplayObject;
	import flash.display.LoaderInfo;
	
	
	public class ExternalConfig
	{
		
			private static var instance:ExternalConfig;
			/**
			 * Stores parameters of the stage.loaderInfo <br>
			 * Only populated once
			 */
			public var _loaderInfo:LoaderInfo;
			
			 
			public static var CONTROLBAR_HEIGHT:int = 100;
			//are we testing
			private var _testLocal:Boolean = false;
			//test value
			// Content
			private static const F4M_RTMP_MBR:String = "http://mediapm.edgesuite.net/osmf/content/test/manifest-files/dynamic_Streaming.f4m";
			private static const F4M_HDN_MBR:String = "http://mediapm.edgesuite.net/ovp/content/demo/f4m/akamai_hdn_vod_sample.f4m";
			private static const F4M_HD_ZERI:String = "http://multiplatform-f.akamaihd.net/z/multi/april11/hdworld/hdworld_,512x288_450_b,640x360_700_b,768x432_1000_b,1024x576_1400_m,1280x720_1900_m,1280x720_2500_m,1280x720_3500_m,.mp4.csmil/manifest.f4m";
			private static const HDN_MULTI_BITRATE_VOD:String = 
				"http://mediapm.edgesuite.net/edgeflash/public/debug/assets/smil/buckbunny-vod.smil";
			
			// Media paths
			public static const MEDIA_PATH:String = "rtmp://cp67126.edgefcs.net/ondemand/mp4:mediapm/ovp/content/demo/video/elephants_dream/elephants_dream_768x428_24.0fps_408kbps.mp4";
			public static const STREAM_AUTH:String = "rtmp://cp78634.edgefcs.net/ondemand/mp4:mediapmsec/osmf/content/test/SpaceAloneHD_sounas_640_700.mp4?auth=daEc2a9a5byaMa.avcxbiaoa8dBcibqbAa8-bkxDGK-b4toa-znnrqzzBvl&aifp=v0001";
			public static const PROGRESSIVE_SSL:String = "https://a248.e.akamai.net/7/248/67129/v0001/mediapm.download.akamai.com/67129/osmf/content/test/akamai_10_year_f8_512K.flv";
			public static const LOCAL_SMIL_PATH:String = "elephants_dream.smil";
			
			//"http://mediapm.edgesuite.net/edgeflash/public/debug/assets/smil/buckbunny-vod.smil";//
			public var smilTestPath:String =HDN_MULTI_BITRATE_VOD
				//"http://mediapm.edgesuite.net/edgeflash/public/debug/assets/smil/buckbunny-vod.smil"//"../data/elephants_dream.smil";
			
			private var _logServicePath:String = "/services/log?i=";
			//stock values that were before wsp 
			private var _bitrates:String;//bit rates passed in
			private var _ticketParams:String = "pvxP6s/ySk4eZJCg RqFyaK8FF3/yxaTedK97w=="
			//test values
			public var testTokenData:String = '{"tokenResponse":{"status":"success","token":"da.aPandGbUcideaidYb.dwavb4coc3cYdW-bosfBa-vga-DnEF0BDqzxr-rbkomXl9kepbo8p6kgs0l0n0mJq9sJ"}}'
			//heartbeat values
			private var _heartbeatInterval:int = 10;//rate in seconds of how long to wait between heartbeats
			private var _heartbeatLimit:int = 4;//number of times the heartbeat needs to fail before a heart attack
			private var _heartbeatPath:String = "/heartbeat?i=";
			private var _heartbeatFailMessage:String = "Timeout Occured with Server, Please Refresh"
				//"../data/dummyHeartBeat.txt?k=";
			
			private var _watermarkDuration:int;
			
			//for heartbeat testing currently taking flashvars to set
			private var _heartbeatIP:String;
			private var _heartbeatPort:String;
			private var  _heartbeatProgressFailLimit:int = 4;
			private var _defaultErrorMessage:String = "<b><font size=\"20\">We're sorry, there was an error with this screening.</font></b> <br> Please refresh your browser<br><br>Click here to contact <u><a href=\"event:SupportEmail\">customer service</a></u><br>Or<br> email support@constellation.tv with this code: "
			
			
			//timestamp from server
			private var _initTimeStamp:Number;
			private var _seekInto:int	
			public var ticketDecrypted:String;
			//film number
			private var _filmNumber:int;
			private var _flashvars:Object;
			public var currentErrorCode:String;
			public var MESSAGE_TIMER_INTERVAL:Number = 15000;
			
			//film start time to send out in heartbeat
			private var _filmStartTime:int;
			private var _heartTimeSkip:int = 30;
			public var smilPath:String;
			
			public function ExternalConfig(enforcer:SingletonEnforcer)
			{
			}
			public static function getInstance():ExternalConfig
			{
				
				if (ExternalConfig.instance == null)
				{
					ExternalConfig.instance = new ExternalConfig(new SingletonEnforcer());
				}
				return ExternalConfig.instance;
			}
			
			/**
			 *Load the flashvar values targeted in the ExternalConfig class.<br>
			 * Can only populate this class once 
			 * @param sp The displayObject to load flashvars from
			 * 
			 */			
			
			public function loadOptions(sp:DisplayObject):void
			{
				
				if(this._loaderInfo!=null){
					return;
				}
				this._loaderInfo = sp.stage.loaderInfo;
				
				var paramObj:Object = LoaderInfo(sp.stage.loaderInfo).parameters;
				this._flashvars = LoaderInfo(sp.stage.loaderInfo).parameters;
					
					
				var keyStr:String;
				var valueStr:String;
				
				for (keyStr in paramObj)
				{
					
					valueStr = String(paramObj[keyStr]);
					
					switch (keyStr)
					{
						case "ticketParams":
							this._ticketParams = valueStr;
						break;
						case "bitrates":
							this._bitrates = valueStr;
						break;
						case "heartbeatInterval":
							this._heartbeatInterval = int(valueStr);
						break;
						case "heartbeatLimit":
							this._heartbeatLimit = int(valueStr);
						break;
						case "heartbeatPath":
							this._heartbeatPath = valueStr;
						break;
						case "watermarkDuration":
							this._watermarkDuration = int(valueStr);
							break;
						case "sip":
							this._heartbeatIP = valueStr;
							break;
						case "sprt":
							this._heartbeatPort = valueStr;
							break;
						case "heartbeatProgressFailLimit":
							this._heartbeatProgressFailLimit = int(valueStr);
							break;
						case "timestamp":
							this._initTimeStamp = Number(valueStr);
							break;
						case "seekInto":
							this._seekInto = int(valueStr);
							break;
						case "filmStartTime":
							this._filmStartTime = int(valueStr);
							break;
						case "heartTimeSkip":
							this._heartTimeSkip = int(valueStr);
							break;
					}
				}
			}

			
			/**
			 * The bitrates passed in from flashvar (usually a comma seperated string)
			 * @return 
			 * 
			 */
			public function get bitrates():String
			{
				return _bitrates;
			}

			/**
			 * The interval in which heartbeats should operate (in seconds)
			 * This is translated in the usage of this value
			 * @return  
			 * 
			 */
			public function get heartbeatInterval():int
			{
				return _heartbeatInterval;
			}

			/**
			 * The amount of heartbeats that are missed before a heart attack occurs
			 * @return  int
			 * 
			 */
			public function get heartbeatLimit():int
			{
				return _heartbeatLimit;
			}

			/**
			 * The path to get a heartbeat value if one was set in the flashvars  
			 * @return string 
			 * 
			 */
			public function get heartbeatPath():String
			{
				return _heartbeatPath;
			}
			/**
			 * The heartbeat IP for targeting the correct server that handles the hearbeat  
			 * @return string 
			 * 
			 */
			public function get heartbeatIP():String
			{
				return _heartbeatIP;
			}
			/**
			 * The heartbeat port for targeting the correct server that handles the hearbeat  
			 * @return string 
			 * 
			 */
			public function get heartbeatPort():String
			{
				return _heartbeatPort;
			}


			/**
			 * ID of the ticket passed in as a flashvar that IDs the user
			 * @return string 
			 */
			public function get ticketParams():String
			{
				return _ticketParams;
			}
			/**
			 *How long to show the watermark 
			 * @return int
			 * 
			 */
			public function get watermarkDuration():int
			{
				return _watermarkDuration;
			}
			
			/**
			 *Is this in a local testing mode? 
			 * @return 
			 * 
			 */
			public function get testLocal():Boolean
			{
				return _testLocal;
			}

			public function get heartbeatProgressFailLimit():int
			{
				return _heartbeatProgressFailLimit;
			}

			public function get heartbeatFailMessage_unknown():String
			{
				return _defaultErrorMessage+errorConfig.heartbeat_Unknown;
			}
			public function get heartbeatFailMessage():String
			{
				return _defaultErrorMessage;
			}

			public function get defaultErrorMessage():String
			{
				return _defaultErrorMessage;
			}

			public function get initTimeStamp():Number
			{
				return _initTimeStamp;
			}

			public function get logServicePath():String
			{
				return _logServicePath;
			}

			public function get seekInto():int
			{
				return _seekInto;
			}

			public function get filmNumber():int
			{
				return _filmNumber;
			}

			public function set filmNumber(value:int):void
			{
				_filmNumber = value;
			}

			public function get flashvars():Object
			{
				return _flashvars;
			}

			public function get filmStartTime():int
			{
				return _filmStartTime;
			}

			public function get heartTimeSkip():int
			{
				return _heartTimeSkip;
			}

			
			
			
		
	}
}
class SingletonEnforcer{}