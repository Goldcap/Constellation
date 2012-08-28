package com.constellation.view
{
	import com.akamai.hd.HDMBRObject;
	import com.akamai.osmf.AkamaiAdvancedStreamingPluginInfo;
	import com.akamai.osmf.AkamaiBasicStreamingPluginInfo;
	import com.akamai.osmf.elements.AkamaiVideoElement;
	import com.akamai.osmf.net.AkamaiNetLoader;
	import com.akamai.osmf.utils.AkamaiMediaUtils;
	import com.akamai.osmf.utils.AkamaiStrings;
	import com.constellation.controllers.ExternalInterfaceController;
	import com.constellation.controllers.heartbeatController;
	import com.constellation.events.constellationEvent;
	import com.constellation.externalConfig.ExternalConfig;
	import com.constellation.net.DynamicStreamBitrate;
	import com.constellation.parsers.smilParser;
	import com.sierrastarstudio.utils.tracer;
	
	import flash.display.Sprite;
	import flash.display.StageAlign;
	import flash.display.StageScaleMode;
	import flash.events.Event;
	import flash.events.TimerEvent;
	import flash.utils.*;
	
	import org.osmf.elements.VideoElement;
	import org.osmf.events.ContainerChangeEvent;
	import org.osmf.events.DisplayObjectEvent;
	import org.osmf.events.LoadEvent;
	import org.osmf.events.MediaElementEvent;
	import org.osmf.events.MediaErrorEvent;
	import org.osmf.events.MediaFactoryEvent;
	import org.osmf.events.MediaPlayerStateChangeEvent;
	import org.osmf.events.MetadataEvent;
	import org.osmf.events.NetConnectionFactoryEvent;
	import org.osmf.events.PlayEvent;
	import org.osmf.events.SeekEvent;
	import org.osmf.events.TimeEvent;
	import org.osmf.layout.LayoutTargetEvent;
	import org.osmf.layout.ScaleMode;
	import org.osmf.media.DefaultMediaFactory;
	import org.osmf.media.MediaElement;
	import org.osmf.media.MediaPlayerSprite;
	import org.osmf.media.MediaResourceBase;
	import org.osmf.media.PluginInfoResource;
	import org.osmf.metadata.Metadata;
	import org.osmf.net.NetConnectionFactoryBase;
	import org.osmf.net.NetLoader;
	import org.osmf.net.NetStreamLoadTrait;
	import org.osmf.net.StreamType;
	import org.osmf.net.StreamingURLResource;
	import org.osmf.smil.SMILPluginInfo;
	import org.osmf.traits.BufferTrait;
	import org.osmf.traits.DynamicStreamTrait;
	import org.osmf.traits.LoadState;
	import org.osmf.traits.LoadTrait;
	import org.osmf.traits.MediaTraitType;
	import org.osmf.traits.TimeTrait;
	import org.osmf.utils.OSMFSettings;
	
	
	public class videoView extends Sprite
	{
		private var _classname:String = "com.constellation.view.videoView";
		
		// Plugin path
		private static const BASIC_STREAMING_PLUGIN:String = "com.akamai.osmf.AkamaiBasicStreamingPluginInfo";
		private static const ADVANCED_STREAMING_PLUGIN:String = "com.akamai.osmf.AkamaiAdvancedStreamingPluginInfo";
		private static const SMIL_PLUGIN_INFOCLASS:String = "org.osmf.smil.SMILPluginInfo";
		
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
		private var _mediaFactory:DefaultMediaFactory;
		private var _mediaElement:MediaElement;
		private var bufferTrait:BufferTrait;
		
	//	private var container:MediaContainer;
		private var _smilParser:smilParser;

		//private var mediaResourceResolver:AkamaiMediaResourceResolver;
		private var _nextTypeNum:int =0;
		private var _akamaiStrings:Array;
		private var _dataType:String = "SMIL";
		private var _unknownFormat:String;
		private var _pluginCount:int =-1;
		private var _pluginsLoaded:int = 0;
		private var _smilTimer:Timer;
		private var _smilLoaded:Boolean = false;
		private var _seekTimer:Object;
		private var _heartBeatController:heartbeatController;
		private var _heartTime:int;
		
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
			
			
			this._mediaPlayerSprite.mediaPlayer.addEventListener(DisplayObjectEvent.MEDIA_SIZE_CHANGE, onMediaSizeChange);		
			this._mediaPlayerSprite.mediaPlayer.addEventListener(TimeEvent.DURATION_CHANGE, onDurationChange);	
			this._mediaPlayerSprite.mediaPlayer.addEventListener(TimeEvent.CURRENT_TIME_CHANGE, onCurrentTimeChange);
			this._mediaPlayerSprite.mediaPlayer.addEventListener(SeekEvent.SEEKING_CHANGE, onSeekingChange);
			this._mediaPlayerSprite.mediaPlayer.addEventListener(PlayEvent.CAN_PAUSE_CHANGE, onCanPauseEvent);
			this._mediaPlayerSprite.mediaPlayer.addEventListener(PlayEvent.PLAY_STATE_CHANGE, onPlayStageChange);
			
			
			this._mediaPlayerSprite.mediaPlayer.addEventListener(MediaErrorEvent.MEDIA_ERROR, onMediaError);
			this._mediaPlayerSprite.mediaPlayer.addEventListener(MediaPlayerStateChangeEvent.MEDIA_PLAYER_STATE_CHANGE,onChange);
			this._mediaPlayerSprite.mediaContainer.addEventListener(LayoutTargetEvent.ADD_TO_LAYOUT_RENDERER,onLayoutAdd);
			this._mediaPlayerSprite.mediaContainer.addEventListener(ContainerChangeEvent.CONTAINER_CHANGE, onContainerChange);
			
				
			this._mediaPlayerSprite.mediaPlayer.addEventListener(MediaPlayerStateChangeEvent.MEDIA_PLAYER_STATE_CHANGE, stateChange);
			this._mediaPlayerSprite.mediaPlayer.addEventListener(MediaElementEvent.TRAIT_REMOVE, onMediaTraitRemoved);
			this._mediaPlayerSprite.mediaPlayer.addEventListener(MediaErrorEvent.MEDIA_ERROR, onMediaError);
			this.addChild(this._mediaPlayerSprite);
			//this._mediaPlayerSprite.mediaPlayer.media.metadata.addEventListener(MetadataEvent.VALUE_CHANGE,metaDataEvent)
			// Create a mediafactory instance
			_mediaFactory = new DefaultMediaFactory()//this._mediaPlayerSprite.mediaFactory;
			
			//Marker 1: Add the listeners for the plugin load call
		//	_mediaFactory.addEventListener( MediaFactoryEvent.PLUGIN_LOAD, onPluginLoaded );
		//	_mediaFactory.addEventListener( MediaFactoryEvent.PLUGIN_LOAD_ERROR, onPluginLoadFailed );
		//	_mediaFactory.addEventListener( MediaFactoryEvent.MEDIA_ELEMENT_CREATE, onMediaElementCreate );
		//	loadPlugin(SMIL_PLUGIN_INFOCLASS);
		//	loadPlugin(ADVANCED_STREAMING_PLUGIN);
		/*	loadPlugin(BASIC_STREAMING_PLUGIN);	
		//
		*/
			//loadPlugin( SMIL_PLUGIN_INFOCLASS );
			
			//this.stage.addEventListener(Event.RESIZE, resizeVideo);
			this.stage.addEventListener(Event.FULLSCREEN,resizeVideo);
		 ExternalInterfaceController.getInstance().addEventListener(constellationEvent.SET_VOLUME,onSettingVolume);
		 
		 
		}
		
		
		private function createAkamaiTypeArray():void
		{
			this._akamaiStrings = new Array();
			/*this._akamaiStrings.push(AkamaiStrings.AKAMAI_MEDIA_TYPE_UNKNOWN);
			this._akamaiStrings.push(AkamaiStrings.AKAMAI_ADVANCED_STREAMING_PLUGIN_METADATA_NAMESPACE);
			this._akamaiStrings.push(AkamaiStrings.AKAMAI_HDN_METADATA_KEY_DATA);
			this._akamaiStrings.push(AkamaiStrings.AKAMAI_MEDIA_TYPE_AMD_LIVE);
			this._akamaiStrings.push(AkamaiStrings.AKAMAI_MEDIA_TYPE_AMD_ONDEMAND);
			this._akamaiStrings.push(AkamaiStrings.AKAMAI_MEDIA_TYPE_AMD_PROGRESSIVE);
			this._akamaiStrings.push(AkamaiStrings.AKAMAI_MEDIA_TYPE_FMS_MBR);
			
			this._akamaiStrings.push(AkamaiStrings.AKAMAI_MEDIA_TYPE_HDN_MBR);
			this._akamaiStrings.push(AkamaiStrings.AKAMAI_MEDIA_TYPE_HDN_ADOBE_HTTP);
			
			this._akamaiStrings.push(AkamaiStrings.AKAMAI_MEDIA_TYPE_HDN_SBR);
			
			
			*/
			this._akamaiStrings.push(AkamaiStrings.AKAMAI_MEDIA_TYPE_HDN_MBR);
			this._akamaiStrings.push(AkamaiStrings.AKAMAI_MEDIA_TYPE_HDN_ADOBE_HTTP);
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
			var curTime:int = Math.round(event.time);
			var timeDiff:int = this._heartTime-curTime;
			
			trace(">>>>>>>>>>>>> time="+event.time+" this._heartTime "+this._heartTime+" cur index "+this._mediaPlayerSprite.mediaPlayer.currentDynamicStreamIndex+"  timeDiff  "+timeDiff);
			if(timeDiff>ExternalConfig.getInstance().heartTimeSkip){
				this._mediaPlayerSprite.mediaPlayer.seek(this._heartTime);
			}
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
			var test2:SMILPluginInfo = new SMILPluginInfo();
			
			//Marker 3: Create the plugin resource using a static plugin
			var pluginResource:MediaResourceBase;
			var pluginInfoClass:Class = getDefinitionByName( source ) as Class;
			pluginResource = new PluginInfoResource( new pluginInfoClass() );
			
			this.setupListeners();
			//Marker 4: Load the plugin
			this._mediaFactory.loadPlugin( pluginResource );
			
		}
		protected function onPluginLoaded( event:MediaFactoryEvent ):void
		{
			trace( "onPluginLoaded()  this._smilLoaded "+this._smilLoaded );
			setupListeners(false);
			//Marker 5: Load the media
			this._pluginsLoaded +=1;
			if(this._pluginsLoaded>=this._pluginCount && this._smilLoaded==true){
				//if(this._nextTypeNum<this._akamaiStrings.length){
					//this.loadMedia(_akamaiStrings[this._nextTypeNum].toString())
				//}
			}else{
				if(this._smilTimer==null){
						this._smilTimer = new Timer(100);
						this._smilTimer.addEventListener(TimerEvent.TIMER,onCheckSMILParsed);
						this._smilTimer.start();
				}
			}
		}
		
		protected function onCheckSMILParsed(event:TimerEvent):void
		{
			

			if(this._pluginsLoaded>=this._pluginCount && this._smilLoaded==true &&this._smilTimer.running){
				this._smilTimer.stop();
				tracer.log("SMIL TIMER plugins "+this._pluginsLoaded+" smilLoaded "+this._smilLoaded,_classname);
				this.loadMedia(_akamaiStrings[this._nextTypeNum].toString())
				
			}
		}
		protected function onSmilParsed(event:constellationEvent):void
		{
		this._smilLoaded = true;
		this._dataType=this._smilParser.isSMILFile==true?"SMIL":"unknown";
		//	this.loadMedia(_akamaiStrings[this._nextTypeNum].toString())
			tracer.log("onSmilParsed "+this._smilParser.protocol+"  _dataType "+_dataType,_classname);
			
			if(this._smilParser.protocol=="rtmp"){
				this._pluginCount =2;
				loadPlugin( SMIL_PLUGIN_INFOCLASS );
					loadPlugin( BASIC_STREAMING_PLUGIN );
					
			}else if(this._smilParser.protocol=="http"){
				this._pluginCount =2;
				
				loadPlugin( SMIL_PLUGIN_INFOCLASS );
					loadPlugin( ADVANCED_STREAMING_PLUGIN );
			}
			
			
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
				this._pluginCount =2;
				loadPlugin( ADVANCED_STREAMING_PLUGIN );
			}
			//	loadPlugin( BASIC_STREAMING_PLUGIN );
			
			this.loadMedia(_akamaiStrings[this._nextTypeNum].toString())
		}
		public function loadMedia(akamaiResourceType:String):void 
		{ 
		//	tracer.log("data type is "+this._dataType,_classname);
			var elementCreated:*
				var mediaProtocol:String = this._smilParser.protocol;
				var resource:StreamingURLResource;
				var dataPath:String = ExternalConfig.getInstance().smilPath
									//Marker 6: The pointer to the media
								//	var resource:URLResource = new URLResource( MEDIA_PATH );
								
				var streamName:String = this._smilParser.serverConnection
				tracer.log("LOAD MEDIA USING "+akamaiResourceType+"  _dataType "+_dataType+" media Protocol "+mediaProtocol+ "  dataPath "+dataPath+"  streamName "+streamName,_classname);
								
								
								
								if(this._dataType=="SMIL"){
										/* "";
													

														
														for(var i:int = 0;i<streamCount;i++){
															// These bitrates are in kbps
														//	var dsiObject:DynamicStreamBitrate = this._smilParser.dsi.streams[i] as DynamicStreamBitrate;
															if(streamName!=null){			
															streamName += 	this._smilParser.protocol+"://"+this._smilParser.hostName+"/"+this._smilParser.dsi.streams[i].name+"&";
															}
														}
												*/
													//	tracer.log("created stream name "+streamName,_classname);
														
															//		mediaResourceResolver = new AkamaiMediaResourceResolver();
														//				
														//	var resource:StreamingURLResource = new StreamingURLResource(streamName, StreamType.LIVE_OR_RECORDED);
														//	var resource:StreamingURLResource = new StreamingURLResource(this._smilParser.protocol+"://"+this._smilParser.hostName+"/", StreamType.LIVE_OR_RECORDED);
														//	var resource:StreamingURLResource = new StreamingURLResource(streamName, StreamType.LIVE_OR_RECORDED/*, 120, 130*/);
															// It doesn't matter in this case what the resource is, it just needs to use
															// the HTTP protocol and have a .smil extension.
															//
															// You can specify the clip begin and end on the StreamURLResource or on 
															// the HDMBRObject as shown below.
																
											//	var resource:XML = this._smilParser.rawData;
															//	var resource:StreamingURLResource = new StreamingURLResource("http://www.example.com/sopmething.smil", StreamType.LIVE_OR_RECORDED);
								
														
									if(mediaProtocol=="rtmp"){				
															
										resource = new StreamingURLResource(this._smilParser.rawData.toString(), StreamType.LIVE_OR_RECORDED/*, 120, 130*/);
										resource.mediaType = "loadedSMIL";
							
									
									
									
									
											var streamCount:int = this._smilParser.dsi.streamCount
										
															
																
																
																
												
																					
																tracer.log("auth token is "+this._smilParser.streamToken,_classname);
																
																	
									}else if(mediaProtocol=="http"){
													
													resource = new StreamingURLResource(streamName, StreamType.LIVE_OR_RECORDED/*, 120, 130*/);
													resource.mediaType = "loadedSMIL";
													
													var hdMBRObject:HDMBRObject = new HDMBRObject();
													
													
													
													hdMBRObject.httpBase =this._smilParser.protocol+"://"+this._smilParser.hostName;//"http://efvod-hdnetwork.akamai.com.edgesuite.net/";
													var streamCount:int = this._smilParser.dsi.streamCount
													for(var i:int = 0;i<streamCount;i++){
													// These bitrates are in kbps
													var dsiObject:DynamicStreamBitrate  = this._smilParser.dsi.streams[i] as DynamicStreamBitrate;
													
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
														var hdMBRObjects:Array = new Array();
															hdMBRObjects.push(hdMBRObject);
															AkamaiMediaUtils.addAkamaiMediaTypeToResource(resource,AkamaiStrings.AKAMAI_MEDIA_TYPE_HDN_MBR,hdMBRObjects)
																	
													//	AkamaiMediaUtils.addAkamaiMediaTypeToResource(resource, akamaiResourceType ,hdMBRObjects);
													//	AkamaiMediaUtils.addAkamaiMediaTypeToResource(resource,AkamaiStrings.AKAMAI_MEDIA_TYPE_FMS_MBR,hdMBRObjects)
													//	this._mediaElement =  _mediaFactory.createMediaElement(resource);
														
															
													
													
												}
									var metadata:Metadata = resource.getMetadataValue(AkamaiStrings.AKAMAI_ADVANCED_STREAMING_PLUGIN_METADATA_NAMESPACE)	as Metadata;
									if (metadata == null)
									{
										metadata = new Metadata();		
									}
									
									
									// Use one or the other of these metadata keys. If both are supplied, the plugin
									// will look at the starting bitrate to determine the corresponding index and 
									// use the lesser of the two indices.			
									metadata.addValue(AkamaiStrings.AKAMAI_METDATA_KEY_MBR_STARTING_INDEX, 1);
									metadata.addValue(AkamaiStrings.AKAMAI_METADATA_KEY_MBR_STARTING_BITRATE, 1500);
									
									if(this._smilParser.streamToken){
										metadata.addValue(AkamaiStrings.AKAMAI_METADATA_KEY_CONNECT_AUTH_PARAMS,this._smilParser.streamToken);
										metadata.addValue(AkamaiStrings.AKAMAI_METADATA_KEY_STREAM_AUTH_PARAMS,this._smilParser.streamToken);
										
									}
												this._mediaElement =  _mediaFactory.createMediaElement(resource);
												
												
												var resourceType:String = _akamaiStrings[this._nextTypeNum];
												
													//AkamaiMediaUtils.addAkamaiMediaTypeToResource(resource,resourceType,null)
														
														resource.addMetadataValue(AkamaiStrings.AKAMAI_ADVANCED_STREAMING_PLUGIN_METADATA_NAMESPACE, metadata);	
														
														tracer.log("metadata added  "+this._mediaElement+"   resourceType  "+resourceType,_classname);
															if(this._mediaElement == null){
																this.tryingNextType("elementCreated "+null+" type Tried "+akamaiResourceType);
															}else{
																setupMediaElementListeners();
													
																this._mediaPlayerSprite.mediaPlayer.media =this._mediaElement;
																
																this._mediaPlayerSprite.mediaPlayer.autoDynamicStreamSwitch = true;
															//	this._mediaPlayerSprite.mediaPlayer.switchDynamicStreamIndex(1);
															}
										}else{
											var typeAttempt:String = this._akamaiStrings[this._nextTypeNum].toString();
											var unknownResource:StreamingURLResource = new StreamingURLResource(this._unknownFormat , StreamType.LIVE_OR_RECORDED/*, 120, 130*/);
											tracer.log("attempting unknown resource "+this._nextTypeNum+" resource "+typeAttempt,_classname);
											AkamaiMediaUtils.addAkamaiMediaTypeToResource(unknownResource, typeAttempt, null);
											elementCreated =  _mediaFactory.createMediaElement(unknownResource);
											if(elementCreated==null){
												tryingNextType("Cannot handle resource  "+elementCreated);	
											}else{
											this._mediaPlayerSprite.mediaPlayer.media =elementCreated;
											}
										}
		//	tracer.log(" this._mediaPlayerSprite.mediaPlayer.dynamicStreamSwitching  "+this._mediaPlayerSprite.mediaPlayer.dynamicStreamSwitching,_classname);
			//	}catch(err:Error){
			//		tracer.log("something wrong "+err,_classname);
			//		tryingNextType("error in loadMedia");
			//	}
			
			
			//_mediaContainter.addMediaElement( element );
		}
		private function setupMediaElementListeners(add:Boolean=true):void
		{
			if (this._mediaElement == null)
			{
				return;
			}
			
			if (add)
			{
				// Listen for traits to be added, so we can adjust the UI. For example, enable the seek bar
				// when the seekable trait is added
				this._mediaElement.addEventListener(MediaElementEvent.TRAIT_ADD, onTraitAdd);
				this._mediaElement.addEventListener(MediaElementEvent.TRAIT_REMOVE, onTraitRemove);
				this._mediaElement.addEventListener(MediaErrorEvent.MEDIA_ERROR, onMediaError);
				
				var loadTrait:LoadTrait = this._mediaElement.getTrait(MediaTraitType.LOAD) as LoadTrait;
				if (loadTrait != null)
				{
					loadTrait.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onMediaLoadStateChange);
					
				}
			}
			else
			{
				this._mediaElement.removeEventListener(MediaElementEvent.TRAIT_ADD, onTraitAdd);
				this._mediaElement.removeEventListener(MediaElementEvent.TRAIT_REMOVE, onTraitRemove);
				this._mediaElement.removeEventListener(MediaErrorEvent.MEDIA_ERROR, onMediaError);
			}
		}
		private function onTraitAdd(event:MediaElementEvent):void
		{
			trace("on TRAIT ADD " +event.traitType) ;
			switch (event.traitType)
			{
				case MediaTraitType.SEEK:
					//seekBar.enabled = seekBar.visible = true;
					break;
				case MediaTraitType.DYNAMIC_STREAM:
					var dsTrait:DynamicStreamTrait = (event.target as MediaElement).getTrait(event.traitType) as DynamicStreamTrait;   					
					//setupSwitchingChangeListener(dsTrait);
					break;
				case MediaTraitType.TIME:
					var timeTrait:TimeTrait = (event.target as MediaElement).getTrait(event.traitType) as TimeTrait;
					//timeTrait.addEventListener(TimeEvent.COMPLETE, onMediaComplete);
					break;
				case MediaTraitType.LOAD:
					var loadTrait:LoadTrait = (event.target as MediaElement).getTrait(event.traitType) as LoadTrait;
					//loadTrait.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onMediaLoadStateChange);
					break;
				case MediaTraitType.BUFFER:
					bufferTrait = (event.target as MediaElement).getTrait(MediaTraitType.BUFFER) as BufferTrait;
					// For experimentation purposes. If you'd like to set a custom buffer length, here is how
					// to do it:
					//bufferTrait.bufferTime = 3;
					break;
			}	
		}
		private function onTraitRemove(event:MediaElementEvent):void
		{
			trace("on TRAIT REMOVE " +event) ;
			switch (event.traitType)
			{
				case MediaTraitType.SEEK:
					//	seekBar.enabled = seekBar.visible = false;
					break;
				case MediaTraitType.DYNAMIC_STREAM:
					var dsTrait:DynamicStreamTrait = (event.target as MediaElement).getTrait(event.traitType) as DynamicStreamTrait;
					//setupSwitchingChangeListener(dsTrait, false);
					break;
				case MediaTraitType.TIME:
					var timeTrait:TimeTrait = (event.target as MediaElement).getTrait(event.traitType) as TimeTrait;
					//	timeTrait.removeEventListener(TimeEvent.COMPLETE, onMediaComplete);
					break;
				case MediaTraitType.LOAD:
					var loadTrait:LoadTrait = (event.target as MediaElement).getTrait(event.traitType) as LoadTrait;
					loadTrait.removeEventListener(LoadEvent.LOAD_STATE_CHANGE, onMediaLoadStateChange);
					break;
			}	
		}
		private function onMediaLoadStateChange(event:LoadEvent):void
		{
			
			var loadTrait:NetStreamLoadTrait;
			
			switch(event.loadState)
			{
				case LoadState.READY:
					tracer.log("Media loaded   onMediaLoadStateChange  .",_classname);
					break;
				case LoadState.UNLOADING:
					tracer.log("Media unloaded  onMediaLoadStateChange  .",_classname);
					break;
			}
		}
		
		protected function onPluginLoadFailed( event:MediaFactoryEvent ):void
		{
			trace( "onPluginFailedLoad() "+event );
		}
		
		private function onMediaError(event:MediaErrorEvent):void
		{
			trace("!!!!! OSMF Media Error : "+event.error);
			var msg:String = "ERROR : error ID="+event.error.errorID;
			
			if (event.error.message != null && event.error.message.length > 0)
			{
				msg += " message="+event.error.message;
			}
			if (event.error.detail != null && event.error.detail.length > 0)
			{
				msg += " detail=" + event.error.detail;
			}
			messageView.getInstance().setMessage(msg);
			trace("MEDIA ERROR "+msg);
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
		
		protected function onSettingVolume(event:constellationEvent):void
		{
			var newVolume:Number = event.data as Number;
			
			this._mediaPlayerSprite.mediaPlayer.volume = newVolume;
			tracer.log(" setting volume in player "+newVolume+" MP Volume "+this._mediaPlayerSprite.mediaPlayer.volume,_classname);
		}
		
		protected function onPlayStageChange(event:Event):void
		{
			tracer.log("onPlay Stage change "+event.type,_classname)
		}
		
		protected function onCanPauseEvent(event:Event):void
		{
			tracer.log("onCanPauseEvent   "+event.type,_classname)
		}
		
		protected function onSeekingChange(event:Event):void
		{
			tracer.log("onSeekingChange  "+event.type,_classname)
			
		}
		
		protected function onDurationChange(event:Event):void
		{
			tracer.log(" onDurationChange  "+event.type,_classname)
			
		}
		
		protected function onMediaSizeChange(event:Event):void
		{
			tracer.log("onMediaSizeChange  "+event.type,_classname)
			
		}
		//	private function onEvent(evt:*):void { trace("event happened "+evt); }
		protected function onChange(event:MediaPlayerStateChangeEvent):void
		{
			
	/*		trace("****onChange***   "+event.state)
			if(event.state.toString()=="playbackError"){
				this._nextTypeNum+=1;
				if(this._nextTypeNum<this._akamaiStrings.length){
				var nextStringType:String = _akamaiStrings[this._nextTypeNum].toString();
				
				trace("****onChange***   "+event.state+"   nextStringType "+nextStringType);
				
				this.loadMedia(nextStringType)
				}
			}
			*/
		}
		public function  setupListeners(add:Boolean=true):void
		{
			if (add)
			{
				
				_mediaFactory.addEventListener(MediaFactoryEvent.PLUGIN_LOAD, onPluginLoaded);
				_mediaFactory.addEventListener(MediaFactoryEvent.PLUGIN_LOAD_ERROR, onPluginLoadError);					
			}
			else
			{
				_mediaFactory.removeEventListener(MediaFactoryEvent.PLUGIN_LOAD, onPluginLoaded);
				_mediaFactory.removeEventListener(MediaFactoryEvent.PLUGIN_LOAD_ERROR, onPluginLoadError);
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
			if(mediaCanPlay==true ){
				//this._mediaPlayerSprite.mediaPlayer.play();
				this._seekTimer = new Timer(100);
				this._seekTimer.addEventListener(TimerEvent.TIMER,canSeekYet);
				this._seekTimer.start();
			}
		}
		
		private function canSeekYet(evt:TimerEvent):void
		{
			var seekTo:int = ExternalConfig.getInstance().seekInto;
			if(this._mediaPlayerSprite.mediaPlayer.canSeekTo(seekTo)){
				if(this._mediaPlayerSprite.mediaPlayer.currentTime<seekTo){
					this._seekTimer.stop();
					this._mediaPlayerSprite.mediaPlayer.seek(seekTo);
					
				}
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

		public function set heartBeatController(value:heartbeatController):void
		{
			_heartBeatController = value;
			_heartBeatController.addEventListener(constellationEvent.HEART_BEAT,onHeartBeat);
		}
		
		protected function onHeartBeat(evt:constellationEvent):void
		{
			this._heartTime = evt.data as int;
			
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