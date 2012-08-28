package com.limelight.osmf.configuration
{
	import flash.events.Event;
	import flash.events.EventDispatcher;
	import flash.events.IOErrorEvent;
	import flash.net.URLLoader;
	import flash.net.URLRequest;
	
	import org.osmf.layout.ScaleMode;
	import org.osmf.net.StreamType;
	
	public class Configuration extends EventDispatcher
	{
		
		public var url:String = "";
		
		public var streamType:String = StreamType.LIVE_OR_RECORDED;
		public var loop:Boolean = false;
		public var autoPlay:Boolean = true;
		public var playButtonOverlay:Boolean = true;
		public var scaleMode:String = ScaleMode.LETTERBOX;
		public var controlBarMode:String = "floating";
		public var autoHideControlBar:Boolean = true;
		public var backgroundColor:Number = 0x000000;
		public var poster:String = "";
		
		public var llnwStreamType:String = "";
		public var useAppInstance:Boolean = true;
		public var volume:Number = .5;
		
		public var plugins:Array = new Array();
		
		private var params:Object;
		
		public function Configuration():void
		{
		}
		
		public function load(parameters:Object):void
		{
			trace("Configuration. ::")
			if (parameters.configuration != undefined)
			{
				trace("Configuration xml found")
				params = parameters;
				loadConfigXML(parameters.configuration);
			}
			else
			{
				trace("No configuration xml")
				processParams(parameters);
				this.dispatchEvent(new Event(Event.COMPLETE));
			}
		}
		
		private function loadConfigXML(url:String):void
		{
			var loader:URLLoader = new URLLoader();
			loader.addEventListener( IOErrorEvent.IO_ERROR, loadError );
			loader.addEventListener( Event.COMPLETE, loadComplete);
			loader.load(new URLRequest(url));
		}
		
		private function loadError(event:IOErrorEvent):void
		{
			trace("WARNING: configuration loading error:", event.text);
		}
		
		private function loadComplete(event:Event):void
		{
			var loader:URLLoader = URLLoader(event.target);
            var configXML:XML = new XML(loader.data);

            processParams(configXML);
            processParams(params);
			this.dispatchEvent(new Event(Event.COMPLETE));
		}
		
		private function processParams(parameters:Object):void
		{
			//trace("Configuration.processParams ::")
			
			if (parameters.url != undefined) 
				url = parameters.url;
			
			if (parameters.streamType != undefined) 
				streamType = parameters.streamType;
			
			if (parameters.loop != undefined)
				loop = 
					parameters.loop.toString().toLowerCase() == "true" 
						? true
						: false;
			
			if (parameters.autoPlay != undefined)
				autoPlay = 
					parameters.autoPlay.toString().toLowerCase() == "false" 
						? false
						: true;
			
			if (parameters.playButtonOverlay != undefined) 
				playButtonOverlay = 
					parameters.playButtonOverlay.toString().toLowerCase() == "false" 
						? false
						: true;
						
			if (parameters.scaleMode != undefined) 
				scaleMode = parameters.scaleMode;		
			
			if (parameters.controlBarMode != undefined) 
				controlBarMode = parameters.controlBarMode;
			
			if (parameters.autoHideControlBar != undefined) 
				autoHideControlBar = 
					parameters.autoHideControlBar.toString().toLowerCase() == "false" 
						? false
						: true;
			
			if (parameters.backgroundColor != undefined) 
				backgroundColor = parseInt(parameters.backgroundColor);

			if (parameters.poster != undefined) 
				poster = parameters.poster;	
	
			if (parameters.llnwStreamType != undefined) 
				llnwStreamType = parameters.llnwStreamType;
			
			if (parameters.useAppInstance != undefined) 
				useAppInstance = 
					parameters.useAppInstance.toString().toLowerCase() == "false" 
						? false
						: true;
			
			if (parameters.volume != undefined) 
				volume = Number(parameters.volume)/100;
			
			
			if (parameters.plugin != undefined)
			{ 
				if (parameters is XML)
				{
					var pluginList:XMLList = parameters.plugin;
					trace("pluginList = " + pluginList)
					
					for each (var pluginNode:XML in pluginList)
					{
						trace("plugin = " + pluginNode)
						plugins.push(pluginNode);
					}
				}
				else
				{
					var pluginArray:Array = String(parameters.plugin).split(",")
					trace("pluginArray = " + pluginArray)
					
					for each (var pluginItem:String in pluginArray)
					{
						trace("plugin = " + pluginItem)
						plugins.push(pluginItem);
					}
				}
			}
										
		}
		
		override public function toString():String
		{
		
			var str:String = ""
			str += "streamType:" + streamType + ","
			str += "loop:" + loop + ","
			str += "autoPlay:" + autoPlay + ","
			str += "playButtonOverlay:" + playButtonOverlay + ","
			str += "scaleMode:" + scaleMode + ","
			str += "controlBarMode:" + controlBarMode + ","
			str += "autoHideControlBar:" + autoHideControlBar + ","
			str += "backgroundColor:" + backgroundColor + ","
			str += "poster:" + poster + ","
			str += "llnwStreamType:" + llnwStreamType + ","
			str += "useAppInstance:" + useAppInstance + ","
			str += "volume:" + volume + ","
			str += "plugins:" + plugins
			
			return str;

		}
		
	}
}