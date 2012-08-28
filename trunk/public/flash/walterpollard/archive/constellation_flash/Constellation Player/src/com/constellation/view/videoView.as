package com.constellation.view
{
	import com.akamai.hd.HDMBRObject;
	import com.akamai.osmf.AkamaiAdvancedStreamingPluginInfo;
	import com.akamai.osmf.elements.AkamaiVideoElement;
	import com.akamai.osmf.loaders.AkamaiAdvancedStreamingNetLoader;
	import com.akamai.osmf.media.AkamaiMediaResourceResolver;
	import com.akamai.osmf.net.AkamaiNetLoader;
	import com.akamai.osmf.utils.AkamaiMediaUtils;
	import com.akamai.osmf.utils.AkamaiStrings;
	import com.constellation.events.constellationEvent;
	import com.constellation.externalConfig.ExternalConfig;
	import com.constellation.parsers.smilParser;
	import com.sierrastarstudio.utils.tracer;
	
	import flash.display.Sprite;
	import flash.display.StageAlign;
	import flash.display.StageScaleMode;
	import flash.events.Event;
	import flash.utils.*;
	
	import org.openvideoplayer.net.dynamicstream.DynamicStreamBitrate;
	import org.osmf.containers.MediaContainer;
	import org.osmf.elements.VideoElement;
	import org.osmf.events.MediaElementEvent;
	import org.osmf.events.MediaErrorEvent;
	import org.osmf.events.MediaFactoryEvent;
	import org.osmf.events.MediaPlayerStateChangeEvent;
	import org.osmf.events.MetadataEvent;
	import org.osmf.events.NetConnectionFactoryEvent;
	import org.osmf.events.TimeEvent;
	import org.osmf.layout.LayoutTargetEvent;
	import org.osmf.layout.ScaleMode;
	import org.osmf.media.MediaElement;
	import org.osmf.media.MediaFactory;
	import org.osmf.media.MediaPlayer;
	import org.osmf.media.MediaPlayerSprite;
	import org.osmf.media.MediaResourceBase;
	import org.osmf.media.PluginInfoResource;
	import org.osmf.net.NetConnectionFactoryBase;
	import org.osmf.net.NetLoader;
	import org.osmf.net.StreamType;
	import org.osmf.net.StreamingURLResource;
	import com.akamai.osmf.AkamaiBasicStreamingPluginInfo;
	
	
	public class videoView extends Sprite
	{
		private var _classname:String = "com.constellation.view.videoView";
		
		// Plugin path
		private static const BASIC_STREAMING_PLUGIN:String = "com.akamai.osmf.AkamaiBasicStreamingPluginInfo";
		private static const ADVANCED_STREAMING_PLUGIN:String = "com.akamai.osmf.AkamaiAdvancedStreamingPluginInfo";
		
		private static const AKAMAI_PLUGIN:String = 
			"http://players.edgesuite.net/flash/plugins/osmf/advanced-streaming-plugin/fp10.1/staging/AkamaiAdvancedStreamingPlugin.swf";
		private static const HDN_MULTI_BITRATE_VOD:String = 
			"http://mediapm.edgesuite.net/edgeflash/public/debug/assets/smil/buckbunny-vod.smil";
		
		// Media paths
		public static const MEDIA_PATH:String = "rtmp://cp67126.edgefcs.net/ondemand/mp4:mediapm/ovp/content/demo/video/elephants_dream/elephants_dream_768x428_24.0fps_408kbps.mp4";
		public static const STREAM_AUTH:String = "rtmp://cp78634.edgefcs.net/ondemand/mp4:mediapmsec/osmf/content/test/SpaceAloneHD_sounas_640_700.mp4?auth=daEc2a9a5byaMa.avcxbiaoa8dBcibqbAa8-bkxDGK-b4toa-znnrqzzBvl&aifp=v0001";
		public static const PROGRESSIVE_SSL:String = "https://a248.e.akamai.net/7/248/67129/v0001/mediapm.download.akamai.com/67129/osmf/content/test/akamai_10_year_f8_512K.flv";
		public static const LOCAL_SMIL_PATH:String = "http://www.sierrastarstudio.com/dev/elephants_dream.smil";
		// Content
		private static const F4M_RTMP_MBR:String = "http://mediapm.edgesuite.net/osmf/content/test/manifest-files/dynamic_Streaming.f4m";
		private static const F4M_HDN_MBR:String = "http://mediapm.edgesuite.net/ovp/content/demo/f4m/akamai_hdn_vod_sample.f4m";
		
		
		
		private var _mediaPlayerSprite:MediaPlayerSprite;
		private var _videoWidth:int;
		private var _videoHeight:int;
		private var _offsetType:String;
	//	private var _mediaContainter:MediaContainer;
		private var _mediaFactory:MediaFactory;
	//	private var container:MediaContainer;
		private var _smilParser:smilParser;

		//private var mediaResourceResolver:AkamaiMediaResourceResolver;
		private var _nextTypeNum:int =0;
		private var _akamaiStrings:Array;
		private var _dataType:String = "SMIL";
		private var _unknownFormat:String;
		
		public function videoView()
		{
			if(this.stage){
				this.init();
			}else{
				this.addEventListener(Event.ADDED_TO_STAGE,init); 
			}
			
			
		}


		private function init(evt:Event=null):void{
			this.createAkamaiTypeArray();
			tracer.log("init video view",_classname);
			this.removeEventListener(Event.ADDED_TO_STAGE,init);
			 
			stage.addEventListener(Event.RESIZE, onStageResize);
			this._mediaPlayerSprite = new MediaPlayerSprite();
			
			this._mediaPlayerSprite.mediaPlayer.autoPlay = true;
			//this._mediaPlayerSprite.mediaPlayer.
			this._mediaPlayerSprite.scaleMode = ScaleMode.LETTERBOX;
			this._mediaPlayerSprite.width = stage.stageWidth;
			this._mediaPlayerSprite.height = stage.stageHeight;
			
			mediaPlayerSprite.mediaPlayer.addEventListener(TimeEvent.CURRENT_TIME_CHANGE, onCurrentTimeChange);
			
			this._mediaPlayerSprite.mediaContainer.addEventListener(ContainerChangeEvent.CONTAINER_CHANGE, onContainerChange);
			
				
			this._mediaPlayerSprite.mediaPlayer.addEventListener(MediaPlayerStateChangeEvent.MEDIA_PLAYER_STATE_CHANGE, stateChange);
			import org.osmf.events.ContainerChangeEvent
				
			this._mediaPlayerSprite.mediaPlayer.addEventListener(MediaElementEvent.TRAIT_REMOVE, onMediaTraitRemoved);
			this._mediaPlayerSprite.mediaPlayer.addEventListener(MediaErrorEvent.MEDIA_ERROR, onMediaError);
			this.addChild(this._mediaPlayerSprite);
			//this._mediaPlayerSprite.mediaPlayer.media.metadata.addEventListener(MetadataEvent.VALUE_CHANGE,metaDataEvent)
			// Create a mediafactory instance
			_mediaFactory = new MediaFactory()//this._mediaPlayerSprite.mediaFactory;
			
			//Marker 1: Add the listeners for the plugin load call
			_mediaFactory.addEventListener( MediaFactoryEvent.PLUGIN_LOAD, onPluginLoaded );
			_mediaFactory.addEventListener( MediaFactoryEvent.PLUGIN_LOAD_ERROR, onPluginLoadFailed );
			_mediaFactory.addEventListener( MediaFactoryEvent.MEDIA_ELEMENT_CREATE, onMediaElementCreate );
			
		
			//this.stage.addEventListener(Event.RESIZE, resizeVideo);
			this.stage.addEventListener(Event.FULLSCREEN,resizeVideo);
			
		}
		
		private function createAkamaiTypeArray():void
		{
			this._akamaiStrings = new Array();
			//this._akamaiStrings.push(AkamaiStrings.AKAMAI_MEDIA_TYPE_UNKNOWN);
			//this._akamaiStrings.push(AkamaiStrings.AKAMAI_ADVANCED_STREAMING_PLUGIN_METADATA_NAMESPACE);
			//this._akamaiStrings.push(AkamaiStrings.AKAMAI_HDN_METADATA_KEY_DATA);
			//this._akamaiStrings.push(AkamaiStrings.AKAMAI_MEDIA_TYPE_AMD_LIVE);
		//	this._akamaiStrings.push(AkamaiStrings.AKAMAI_MEDIA_TYPE_AMD_ONDEMAND);
		//	this._akamaiStrings.push(AkamaiStrings.AKAMAI_MEDIA_TYPE_AMD_PROGRESSIVE);
		this._akamaiStrings.push(AkamaiStrings.AKAMAI_MEDIA_TYPE_FMS_MBR);
			
			//this._akamaiStrings.push(AkamaiStrings.AKAMAI_MEDIA_TYPE_HDN_MBR);
			//this._akamaiStrings.push(AkamaiStrings.AKAMAI_MEDIA_TYPE_HDN_ADOBE_HTTP);
			
			//this._akamaiStrings.push(AkamaiStrings.AKAMAI_MEDIA_TYPE_HDN_SBR);
			
			/*this._akamaiStrings.push(AkamaiStrings.);
			this._akamaiStrings.push(AkamaiStrings.AKAMAI_ADVANCED_STREAMING_PLUGIN_METADATA_NAMESPACE);
			this._akamaiStrings.push(AkamaiStrings.AKAMAI_ADVANCED_STREAMING_PLUGIN_METADATA_NAMESPACE);
			this._akamaiStrings.push(AkamaiStrings.AKAMAI_ADVANCED_STREAMING_PLUGIN_METADATA_NAMESPACE);
			this._akamaiStrings.push(AkamaiStrings.AKAMAI_ADVANCED_STREAMING_PLUGIN_METADATA_NAMESPACE);
			this._akamaiStrings.push(AkamaiStrings.AKAMAI_ADVANCED_STREAMING_PLUGIN_METADATA_NAMESPACE);
			this._akamaiStrings.push(AkamaiStrings.AKAMAI_ADVANCED_STREAMING_PLUGIN_METADATA_NAMESPACE);
			*/
		}
		
		protected function onContainerChange(event:Event):void
		{
			tracer.log("************ on Container change ******** "+event,_classname);
			
		}
		
		protected function onMediaTraitRemoved(event:MediaElementEvent):void
		{
			tracer.log("media trait removed "+event,_classname);
			
		}
		private function onCurrentTimeChange(event:TimeEvent):void
		{
			trace(">>>>>>>>>>>>> time="+event.time);
		}
		public function createVideoDisplay():void{
			tracer.log("creating video stream ",_classname);
		
			
		//	this.resizeVideo();
			
		}
		
		private function loadPlugin( source:String ):void
		{
			// 
			var test:AkamaiAdvancedStreamingPluginInfo = new AkamaiAdvancedStreamingPluginInfo();
			var test1:AkamaiBasicStreamingPluginInfo = new AkamaiBasicStreamingPluginInfo(); 
			
			//Marker 3: Create the plugin resource using a static plugin
			var pluginResource:MediaResourceBase;
			var pluginInfoClass:Class = getDefinitionByName( source ) as Class;
			pluginResource = new PluginInfoResource( new pluginInfoClass() );
			
			//var pluginResource:MediaResourceBase = new URLResource(source);

			//Marker 4: Load the plugin
			this._mediaFactory.loadPlugin( pluginResource );
			
		}
		protected function onPluginLoaded( event:MediaFactoryEvent ):void
		{
			trace( "onPluginLoaded()" );
			
			//Marker 5: Load the media
			this.loadMedia(_akamaiStrings[this._nextTypeNum].toString())
		}
		protected function onSmilParsed(event:constellationEvent):void
		{
		
		//	this.loadMedia(_akamaiStrings[this._nextTypeNum].toString())
			tracer.log("loading Advanced plugin ",_classname);
			loadPlugin( ADVANCED_STREAMING_PLUGIN );
		}
		protected function onUnknownSmilParsed(event:constellationEvent):void
		{
			this._unknownFormat = event.data as String;
			
			var typeArr:Array =  this._unknownFormat.split(".")
				
			this._dataType = typeArr[typeArr.length-1];

			if(this._dataType=="mp4"){
				tracer.log("loading basic plugin ",_classname);
				loadPlugin(BASIC_STREAMING_PLUGIN);
			}else{
				tracer.log("loading Advanced plugin ",_classname);
				loadPlugin( ADVANCED_STREAMING_PLUGIN );
			}
			//	loadPlugin( BASIC_STREAMING_PLUGIN );
			
		//	this.loadMedia(_akamaiStrings[this._nextTypeNum].toString())
		}
		public function loadMedia(akamaiResourceType:String):void 
		{ 
		//	tracer.log("data type is "+this._dataType,_classname);
			var elementCreated:*
			try{
		tracer.log("LOAD MEDIA USING "+akamaiResourceType+"  _dataType "+_dataType,_classname);
			//Marker 6: The pointer to the media
		//	var resource:URLResource = new URLResource( MEDIA_PATH );
				if(this._dataType=="SMIL"){
								var streamName:String = 	this._smilParser.protocol+"://"+this._smilParser.hostName+"/"+this._smilParser.dsi.streams[0].name;
									//		mediaResourceResolver = new AkamaiMediaResourceResolver();
								//		var resource:StreamingURLResource = new StreamingURLResource(streamName, StreamType.LIVE_OR_RECORDED);
												
								//	var resource:StreamingURLResource = new StreamingURLResource(streamName, StreamType.LIVE_OR_RECORDED);
								//	var resource:StreamingURLResource = new StreamingURLResource(this._smilParser.protocol+"://"+this._smilParser.hostName+"/", StreamType.LIVE_OR_RECORDED);
								//	var resource:StreamingURLResource = new StreamingURLResource(streamName, StreamType.LIVE_OR_RECORDED/*, 120, 130*/);
									// It doesn't matter in this case what the resource is, it just needs to use
									// the HTTP protocol and have a .smil extension.
									//
									// You can specify the clip begin and end on the StreamURLResource or on 
									// the HDMBRObject as shown below.
									var resource:StreamingURLResource = new StreamingURLResource(streamName, StreamType.LIVE_OR_RECORDED/*, 120, 130*/);
									var hdMBRObject:HDMBRObject = new HDMBRObject();
								
									hdMBRObject.httpBase = this._smilParser.protocol+"://"+this._smilParser.hostName;//"http://efvod-hdnetwork.akamai.com.edgesuite.net/";
								var streamCount:int = this._smilParser.dsi.streamCount
									for(var i:int = 0;i<streamCount;i++){
									// These bitrates are in kbps
										var dsiObject:DynamicStreamBitrate = this._smilParser.dsi.streams[i] as DynamicStreamBitrate;
										
									hdMBRObject.addStream(dsiObject.name, dsiObject.rate);
									}
									/*
									
									hdMBRObject.httpBase = "http://efvod-hdnetwork.akamai.com.edgesuite.net/";
									// These bitrates are in kbps
									hdMBRObject.addStream("ElephantsDream2_h264_3500@14411", 3500);
									hdMBRObject.addStream("ElephantsDream2_h264_2500@14411", 2500);
									hdMBRObject.addStream("ElephantsDream2_h264_1500@14411", 1500);
									hdMBRObject.addStream("ElephantsDream2_h264_700@14411", 700);
									hdMBRObject.addStream("ElephantsDream2_h264_300@14411", 300);			
									*/
									//setupResolverListeners();
									//mediaResourceResolver.resolve(resource);
								
									
							//	AkamaiMediaUtils.addAkamaiMediaTypeToResource(resource,AkamaiStrings.AKAMAI_MEDIA_TYPE_HDN_MBR,null)
									
									var hdMBRObjects:Array = new Array();
										hdMBRObjects.push(hdMBRObject);
										
									AkamaiMediaUtils.addAkamaiMediaTypeToResource(resource, akamaiResourceType ,hdMBRObjects);
									
									
								//	AkamaiMediaUtils.addAkamaiMediaTypeToResource(resource, AkamaiStrings.AKAMAI_MEDIA_TYPE_UNKNOWN, null);
									
									
									// Add the media element
								//	var videoEle:AkamaiVideoElement = new AkamaiVideoElement(resource);
									
									 elementCreated =  _mediaFactory.createMediaElement(resource);
									
									if(elementCreated == null){
										this.tryingNextType("elementCreated "+null+" type Tried "+akamaiResourceType);
									}else{
										this._mediaPlayerSprite.mediaPlayer.media =elementCreated;
									//	this._mediaPlayerSprite.mediaPlayer.autoDynamicStreamSwitch = true;
									//	this._mediaPlayerSprite.mediaPlayer.switchDynamicStreamIndex(1);
									}
				}else{
					var typeAttempt:String = this._akamaiStrings[this._nextTypeNum].toString();
					var resource:StreamingURLResource = new StreamingURLResource(this._unknownFormat , StreamType.LIVE_OR_RECORDED/*, 120, 130*/);
					tracer.log("attempting unknown resource "+this._nextTypeNum+" resource "+typeAttempt,_classname);
					AkamaiMediaUtils.addAkamaiMediaTypeToResource(resource, typeAttempt, null);
					elementCreated =  _mediaFactory.createMediaElement(resource);
					if(elementCreated==null){
						tryingNextType("Cannot handle resource  "+elementCreated);	
					}else{
					this._mediaPlayerSprite.mediaPlayer.media =elementCreated;
					}
				}
		//	tracer.log(" this._mediaPlayerSprite.mediaPlayer.dynamicStreamSwitching  "+this._mediaPlayerSprite.mediaPlayer.dynamicStreamSwitching,_classname);
				}catch(err:Error){
					tracer.log("something wrong "+err,_classname);
					tryingNextType("error in loadMedia");
				}
			
			
			//_mediaContainter.addMediaElement( element );
		}
		
		
		protected function onPluginLoadFailed( event:MediaFactoryEvent ):void
		{
			trace( "onPluginFailedLoad() "+event );
		}
		
		private function onMediaError(event:MediaErrorEvent):void
		{
			
		
			tryingNextType("OSMF Media Error");
		}
		
		private function tryingNextType(fromStr:String):void
		{
			var stringCount:int =this._akamaiStrings.length-1
				if(this._nextTypeNum<stringCount){
				_nextTypeNum +=1;
				var testString:String = this._akamaiStrings[this._nextTypeNum].toString();
				
				tracer.log("!!!!! tryingNextType : "+fromStr+" next Test Type "+testString,_classname);
				
				
				this.loadMedia(testString);
			}
			
		}
		public function  setupListeners(add:Boolean=true):void
		{
			if (add)
			{
				
				_mediaPlayerSprite.mediaFactory.addEventListener(MediaFactoryEvent.PLUGIN_LOAD, onPluginLoaded);
				_mediaPlayerSprite.mediaFactory.addEventListener(MediaFactoryEvent.PLUGIN_LOAD_ERROR, onPluginLoadError);					
			}
			else
			{
				_mediaPlayerSprite.mediaFactory.removeEventListener(MediaFactoryEvent.PLUGIN_LOAD, onPluginLoaded);
				_mediaPlayerSprite.mediaFactory.removeEventListener(MediaFactoryEvent.PLUGIN_LOAD_ERROR, onPluginLoadError);
			}
		}
		
		
		private function onPluginLoadError(event:MediaFactoryEvent):void
		{
			trace("plugin failed to load: "+event);
			setupListeners(false);
		}
		private function onStageResize(event:Event):void
		{
			_mediaPlayerSprite.width = stage.stageWidth;
			_mediaPlayerSprite.height = stage.stageHeight;
		}
		
		public function resizeVideo(evt:Event = null):void
		{
			
		
			tracer.log("resizing video "+this._mediaPlayerSprite.width,_classname);
		try{
			var widthScale:Number
			var heightScale:Number
		/*	
			widthScale = stage.stageWidth / this._videoDisplay.width;
			
			heightScale = stage.stageHeight / (this._videoDisplay.height- ExternalConfig.CONTROLBAR_HEIGHT);
			
			var div:Number = this._offsetType == "max" ? Math.max(widthScale, heightScale) : Math.min(widthScale, heightScale);
			//These two lines resize this object according to the division ratio.  
			//If we use scaleX or scaleY here it wont work as we need it to.  
			this._videoDisplay.width *= div;
			this._videoDisplay.height *= div;
			
			this._videoDisplay.x = (stage.stageWidth/2)-this._videoDisplay.width/2;
			this._videoDisplay.y = ((stage.stageHeight/2) - this._videoDisplay.height/2);
			this._videoDisplay.visible = true;
			*/
		}catch(err:Error){
			tracer.log("video view not ready to resize",_classname);
		}
			
		}
		
		public function set smilParser(smilPar:smilParser):void{
			this._smilParser = smilPar;
			this._smilParser.addEventListener(constellationEvent.SMIL_PARSED,onSmilParsed);
			this._smilParser.addEventListener(constellationEvent.UNKNOWN_SMIL_FORMAT,onUnknownSmilParsed);
			//	tracer.log("attached smil parser "+this._smilParser,_classname);
		}
		
	
		protected function onLayoutAdd(event:LayoutTargetEvent):void
		{
			
			trace("*******   onLayoutAdd $$$$$$$$$$$$$$$$$$$"+event.layoutTarget);
		}
		protected function metaDataEvent(event:MetadataEvent):void
		{
			tracer.log("meta data event "+event,_classname);
		}
		
		protected function onMediaElementCreate(event:MediaFactoryEvent):void
		{
			tracer.log("media element create "+event.mediaElement.metadata.toString(),_classname);
		}
		
		protected function stateChange(event:MediaPlayerStateChangeEvent):void
		{
			var mediaCanPlay:Boolean = this._mediaPlayerSprite.mediaPlayer.canPlay;
			
			tracer.log("state change "+event.state+"  MP canPlay "+mediaCanPlay,_classname);
			if(mediaCanPlay==true){
				//this._mediaPlayerSprite.mediaPlayer.play();
			}
		}
		
		public function get mediaPlayerSprite():MediaPlayerSprite
		{
			return _mediaPlayerSprite;
		}
		
		public function get videoDisplaySprite():MediaPlayerSprite
		{
			return _mediaPlayerSprite;
		}
		/*
		public function get videoDisplay():MediaPlayer
		{
			return _videoDisplay;
		}
		
		public function get mediaContainter():MediaContainer
		{
			return _mediaContainter;
		}
		*/
	}
}