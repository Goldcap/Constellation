package com.constellation.events
{
	import flash.events.Event;
	
	public class constellationEvent extends Event
	{
		public static var HEART_ATTACK:String = "HEART_ATTACK";
		public static var SHOW_WATERMARK:String = "SHOW_WATERMARK";
		public static var SMIL_LOADED:String = "SMIL_LOADED";
		public static var SMIL_PARSED:String = "SMIL_PARSED";
		public static var XML_MALFORMED:String = "XML_MALFORMED";
		public static var HTTP_LOAD_FAILED:String = "HTTP_LOAD_FAILED";
		public static var ERROR:String = "ERROR";
		
		public static var SMIL_MALFORMED:String = "SMIL_MALFORMED";
		public static var STREAM_NOTIFY:String = "STREAM_NOTIFY";
		
		
		private var _data:Object;
		public static var TRACE:String="TRACE_MESSAGE";
		
		public static var SHOW_HOST_CAMERA:String ="SHOW_HOST_CAMERA";
		public static var HIDE_HOST_CAMERA:String = "HIDE_HOST_CAMERA";
		public static var SHOW_CAMERA_VIEWER:String = "SHOW_CAMERA_VIEWER";
		public static var HIDE_CAMERA_VIEWER:String = "HIDE_CAMERA_VIEWER";
		
		public static var SET_VOLUME:String = "SET_VOLUME";
		
		//citoRooN
		
		/*
		ExternalInterface.addCallback("showHostCam", showHostCam);
		ExternalInterface.addCallback("hideHostCam", hideHostCam);
		ExternalInterface.addCallback("showLiveViewer", showLiveViewer);
		ExternalInterface.addCallback("hideLiveViewer", hideLiveViewer);
		ExternalInterface.addCallback("camViewerStart", camViewerStart);
		
		*/
		public static var MEDIA_PLAY:String = "MEDIA_PLAY";
		public static var UNKNOWN_SMIL_FORMAT:String = "UNKNOWN_SMIL_FORMAT";
		public static var CAMERA_READY:String = "CAMERA_READY";
		public static var CAMERA_NO_HOST:String = "CAMERA_NO_HOST";
		public static var CAMERA_LOST_HOST:String = "CAMERA_LOST_HOST";
		
		
		public function constellationEvent(type:String, data:Object= null, bubbles:Boolean=false, cancelable:Boolean=false)
		{
			super(type, bubbles, cancelable);
			this._data = data;
		}
		public function get data():Object
		{
			return this._data;
		}
		
		public override function clone():Event
		{
			return new constellationEvent(type, bubbles, cancelable);
		}
		
		public override function toString():String
		{
			return formatToString("constellationEvent", "type", "bubbles", "cancelable", "eventPhase");
		}
		
	}
}