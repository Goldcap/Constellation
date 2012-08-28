package com.constellation.view
{
	import com.akamai.hd.HDMBRObject;
	import com.akamai.osmf.AkamaiAdvancedStreamingPluginInfo;
	import com.akamai.osmf.AkamaiBasicStreamingPluginInfo;
	import com.akamai.osmf.net.AkamaiZStreamWrapper;
	import com.akamai.osmf.utils.AkamaiMediaUtils;
	import com.akamai.osmf.utils.AkamaiStrings;
	import com.constellation.config.errorConfig;
	import com.constellation.controllers.ExternalInterfaceController;
	import com.constellation.controllers.heartbeatController;
	import com.constellation.events.constellationEvent;
	import com.constellation.externalConfig.ExternalConfig;
	import com.constellation.managers.loggingManager;
	import com.constellation.net.DynamicStreamBitrate;
	import com.constellation.parsers.smilParser;
	import com.constellation.services.tokenService;
	import com.sierrastarstudio.utils.tracer;
	
	import flash.display.Sprite;
	import flash.events.Event;
	import flash.events.TimerEvent;
	import flash.external.ExternalInterface;
	import flash.net.NetStream;
	import flash.text.TextField;
	import flash.text.TextFieldAutoSize;
	import flash.text.TextFormat;
	import flash.utils.*;
	
	import org.osmf.events.BufferEvent;
	import org.osmf.events.ContainerChangeEvent;
	import org.osmf.events.LoadEvent;
	import org.osmf.events.MediaElementEvent;
	import org.osmf.events.MediaErrorEvent;
	import org.osmf.events.MediaFactoryEvent;
	import org.osmf.events.MediaPlayerStateChangeEvent;
	import org.osmf.events.MetadataEvent;
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
	import org.osmf.net.NetStreamLoadTrait;
	import org.osmf.net.StreamType;
	import org.osmf.net.StreamingURLResource;
	import org.osmf.player.chrome.widgets.VideoInfoOverlay;
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
		//font
		[Embed(source='../graphics/fonts/HelveticaNeue-Medium.otf', fontFamily="helvNeuCond", embedAsCFF="false")]
		
		public var helvNeuCond:Class;
		
		private var _mediaPlayerSprite:MediaPlayerSprite;
		private var _videoWidth:int;
		private var _videoHeight:int;
		
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
		
		private var _smilLoaded:Boolean = false;
		private var _seekTimer:Timer;
		private var _heartBeatController:heartbeatController;
		private var _heartTime:int;
		private var _initialSeek:Boolean = true;
		private var _videoComplete:Boolean = false;
		private var _debugText:TextField;
		private var _debugPanel:Sprite;

		private var _qosOverlay:VideoInfoOverlay;
		
		
		private var _statusMessageView:statusMessageView;
		
		
		private var _initialConnect:Boolean = true;
		//timers 
		private var _smilTimer:Timer;
		private var _bufferWatcher:Timer;
		
		private var _debugJSTimer:Timer;
		private var _debugJSTimerInterval:int;
		private var _mediaProtocol:String;

		private var _filePath:String;
		private var _streamToken:String;
		private var _authToken:String;
		
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
			
			this.removeEventListener(Event.ADDED_TO_STAGE,init); 
			stage.addEventListener(Event.RESIZE, onStageResize);
			this._bufferWatcher = new Timer(1000);
			this._bufferWatcher.addEventListener(TimerEvent.TIMER,onBufferWatch);
			this._bufferWatcher.start();
			this._mediaPlayerSprite = new MediaPlayerSprite();
			this._mediaPlayerSprite.visible = false;
			this._mediaPlayerSprite.mediaPlayer.autoPlay = false;
			//this._mediaPlayerSprite.mediaPlayer.
			this._mediaPlayerSprite.scaleMode = ScaleMode.LETTERBOX;
			this._mediaPlayerSprite.width = stage.stageWidth;
			this._mediaPlayerSprite.height = stage.stageHeight;
			
			
			this.addMediaListeners();
			
			this.addChild(this._mediaPlayerSprite);
			//this._mediaPlayerSprite.mediaPlayer.media.metadata.addEventListener(MetadataEvent.VALUE_CHANGE,metaDataEvent)
			// Create a mediafactory instance
			_mediaFactory = new DefaultMediaFactory()//this._mediaPlayerSprite.mediaFactory;
		
		//	this.stage.addEventListener(Event.FULLSCREEN,resizeVideo);
		 ExternalInterfaceController.getInstance().addEventListener(constellationEvent.SET_VOLUME,onSettingVolume);
		 
			 if(OSMFSettings.supportsStageVideo==false){
				 OSMFSettings.enableStageVideo = false
			 }
			//small status at bottom left for things like buffering...
			this._statusMessageView = new statusMessageView();
			
			 this.addChildAt(this._statusMessageView,this.numChildren-1);
			 
			
			 if (ExternalConfig.getInstance().showDebugPanel==true){
				 this.showDebugOverlay()
			 }
			 if(ExternalConfig.getInstance().showTimeDebug ==true){
				this.startDebugTimer(); 
			 }
			 this.showStatusMessage("<p align=\"right\">Loading</p>");
		tokenService.getInstance().addEventListener(constellationEvent.JSON_LOADED, this.onJSONLoaded);
		
		}
		
		protected function onBufferWatch(event:TimerEvent):void
		{
			if(bufferTrait){
				var isBuffering:Boolean = bufferTrait.buffering;
				var bufferLength:int = bufferTrait.bufferLength;
				
				if(isBuffering==true){
					this.showStatusMessage("<p align=\"right\">Buffering</p>");
					
				}else{
					this.showStatusMessage("");
					this._statusMessageView.stopDotTimer();
				}
				if(bufferLength<2){
					//bufferTrait.bufferTime = 1;
					this.showStatusMessage("<p align=\"right\">Buffering</p>");
				
				}else{
				//	bufferTrait.bufferTime = 10;
				}
				if(bufferLength==0 && this._initialConnect==true){
					this.showStatusMessage("<p align=\"right\">Connecting to your movie</p>");
				}
				//else if(bufferLength==0 && this._initialConnect==false && this._mediaPlayerSprite.mediaPlayer.currentTime>2){
					var netStream:NetStream = null;
					var akamaiNetStream:AkamaiZStreamWrapper = null;
					
					var media:MediaElement = this._mediaPlayerSprite.mediaPlayer.media;
					var loadTrait:NetStreamLoadTrait = media.getTrait(MediaTraitType.LOAD) as NetStreamLoadTrait;
					if (loadTrait)
					{
						
						var targetNetStream:* = loadTrait.netStream;
						if(targetNetStream is AkamaiZStreamWrapper){
							akamaiNetStream = targetNetStream;
						}else{
							netStream = targetNetStream;
						}
					}
				/*	if(netStream!=null){
						this.showStatusMessage("<p align=\"left\">Your bandwidth is low ("+Number(netStream.info.currentBytesPerSecond/1000).toFixed(3)+"). If this message persists, please refresh your browser. Thank you.</p> ",true);
					}else if(akamaiNetStream!=null){
						this.showStatusMessage("<p align=\"left\">Your bandwidth is low ("+Number(akamaiNetStream.info.currentBytesPerSecond/1000).toFixed(3)+"). If this message persists, please refresh your browser. Thank you.</p> ",true);
					}
					*/
				//}
				
				tracer.log("Watching buffer "+bufferTrait.buffering+" Length: "+bufferTrait.bufferLength+" TIME: "+bufferTrait.bufferTime,_classname)
			}
		}
		
		private function showStatusMessage(msg:String,isBuffering:Boolean=false):void{
			
			this._statusMessageView.statusMessage(msg,isBuffering);
			var statusIndex:int = this.getChildIndex(this._statusMessageView);
		try{
			var mediaIndex:int = this.getChildIndex(this._mediaPlayerSprite);
		//	if(statusIndex<mediaIndex){
				this.swapChildrenAt(statusIndex,mediaIndex);
			//}
				statusIndex = this.getChildIndex(this._statusMessageView);
				mediaIndex = this.getChildIndex(this._mediaPlayerSprite);
		}catch(err:Error){
			tracer.log("can't shuffle status message for "+msg,_classname);
		}
			this.onStageResize();
			
			
			
		}
		
		protected function onCompleteChange(event:Event=null,userMessage:Boolean=false):void
		{
			this._videoComplete = true;
			if(this._mediaPlayerSprite!=null){
				this._mediaPlayerSprite.mediaPlayer.stop();
			}
			
			if(this._bufferWatcher.running){
				this._bufferWatcher.stop()
			}
			this.removeMediaListeners();
			this.removeChild(this._mediaPlayerSprite);
			this._mediaPlayerSprite = null;
			if(this._qosOverlay){
				this._qosOverlay.stopInfo();
			}
			if(userMessage==true){
				
			}else{
				//this.showStatusMessage("<p align=\"right\">Movie completed</p>");
			}
		}
		
		protected function stateChange(event:MediaPlayerStateChangeEvent):void
		{
			if(this._mediaPlayerSprite==null){
			return;
			}
			try{
			var mediaCanPlay:Boolean = this._mediaPlayerSprite.mediaPlayer.canPlay;
			var mediaCanSeek:Boolean = this._mediaPlayerSprite.mediaPlayer.canSeek;
			var currentState:String = event.state;
			var seekIntoPoint:int = ExternalConfig.getInstance().seekInto
			tracer.log("this._initialSeek  "+this._initialSeek+"  state change "+event.state+"  MP canPlay "+mediaCanPlay+" canSeek "+mediaCanSeek+" seekInto point "+seekIntoPoint,_classname);
			
			if(currentState =="ready" && mediaCanPlay==true  && this._videoComplete==false ){
			
				try{
					this._mediaPlayerSprite.mediaPlayer.play();
				}catch(err:Error){
					tracer.log("caught play error ",_classname);
				}
			//	this._mediaPlayerSprite.mediaPlayer.pause();
				
				
				if(ExternalConfig.getInstance().seekInto!=0 && this._initialSeek==true){
					this._seekTimer = new Timer(10000);
					this._seekTimer.addEventListener(TimerEvent.TIMER,canSeekYet);
				//	this._seekTimer.start();
				}
			}
			if(currentState =="playing"){
				this._mediaPlayerSprite.visible = true;
				this._initialConnect = false;
				this._statusMessageView.statusMessage("");
			}
			}catch(err:Error){
				tracer.log("caught empty player",_classname);
			}
		}
		private function validateSeekPoint():Boolean{
			if(this._mediaPlayerSprite){
			var seekIntoPoint:int = ExternalConfig.getInstance().seekInto
				var mediaDuration:int = this._mediaPlayerSprite.mediaPlayer.duration;
				tracer.log("mediaDuration "+mediaDuration+" seekInto "+seekIntoPoint,_classname);
				if(seekIntoPoint>mediaDuration){
					return false;
				}else{
					return true;
				}
			}else{
				return false;
			}
		}
		private function canSeekYet(evt:TimerEvent):void
		{
			tracer.log("*******************  can seek yet "+evt.type,_classname);
			if(this._mediaPlayerSprite==null){
				return;
			}
			
			if(this._initialSeek == true){
				var validSeekPoint:Boolean = this.validateSeekPoint()
				var seekTo:int = ExternalConfig.getInstance().seekInto;
				var allowedSeek:Boolean = this._mediaPlayerSprite.mediaPlayer.canSeekTo(seekTo);
				var mediaDuration:Number = this._mediaPlayerSprite.mediaPlayer.duration;
				tracer.log("initial seek "+this._initialSeek +  "   seeking to "+seekTo+"   allowedSeek "+allowedSeek+" media "+this._mediaPlayerSprite.mediaPlayer.canSeek+" duration "+mediaDuration,_classname);
			if(seekTo>mediaDuration){
				this.onCompleteChange(null,true);
				this.showStatusMessage("");
				messageView.getInstance().setMessage(ExternalConfig.getInstance().enteredTheaterAfterCompleteMessage,false);
				
			}
				if(allowedSeek==true && validSeekPoint==true){
					if(this._mediaPlayerSprite.mediaPlayer.currentTime<seekTo){
								this._seekTimer.stop();
								this._mediaPlayerSprite.visible = true;
								
								this._mediaPlayerSprite.mediaPlayer.seek(seekTo);
								this._mediaPlayerSprite.mediaPlayer.play();
								this._initialSeek = false;	
					}
				}
			}
			
		}
		
		
		private function createAkamaiTypeArray():void
		{
			//attempt to create an array in which resource element would be created - not currently used
			this._akamaiStrings = new Array();
			/*this._akamaiStrings.push(AkamaiStrings.AKAMAI_MEDIA_TYPE_UNKNOWN);
			this._akamaiStrings.push(AkamaiStrings.AKAMAI_ADVANCED_STREAMING_PLUGIN_METADATA_NAMESPACE);
			this._akamaiStrings.push(AkamaiStrings.AKAMAI_HDN_METADATA_KEY_DATA);
			this._akamaiStrings.push(AkamaiStrings.AKAMAI_MEDIA_TYPE_AMD_LIVE);
			this._akamaiStrings.push(AkamaiStrings.AKAMAI_MEDIA_TYPE_AMD_ONDEMAND);
			this._akamaiStrings.push(AkamaiStrings.AKAMAI_MEDIA_TYPE_AMD_PROGRESSIVE);
			*/
			this._akamaiStrings.push(AkamaiStrings.AKAMAI_MEDIA_TYPE_FMS_MBR);
			
			this._akamaiStrings.push(AkamaiStrings.AKAMAI_MEDIA_TYPE_HDN_MBR);
			this._akamaiStrings.push(AkamaiStrings.AKAMAI_MEDIA_TYPE_HDN_ADOBE_HTTP);
			
			this._akamaiStrings.push(AkamaiStrings.AKAMAI_MEDIA_TYPE_HDN_SBR);
			
			
			
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
			
		//	tracer.log(">>>>>>>>>>>>> time="+event.time+" this._heartTime "+this._heartTime+" cur index "+this._mediaPlayerSprite.mediaPlayer.currentDynamicStreamIndex+"  timeDiff  "+timeDiff,_classname);
			if(timeDiff>ExternalConfig.getInstance().heartTimeSkip){
			//	this._mediaPlayerSprite.mediaPlayer.seek(this._heartTime);
			}
		}
		public function createVideoDisplay():void{
			tracer.log("creating video stream ",_classname);
		
			
			this.onStageResize(null);
			
		}
		
		private function loadPlugin( source:String ):void
		{
			//ensure the plugin info is loaded by creating local vars 
			var advPlugin:AkamaiAdvancedStreamingPluginInfo = new AkamaiAdvancedStreamingPluginInfo();
			var basePlugin:AkamaiBasicStreamingPluginInfo = new AkamaiBasicStreamingPluginInfo(); 
			var smilPlugin:SMILPluginInfo = new SMILPluginInfo();
			
			//Create the plugin resource using a static plugin
			var pluginResource:MediaResourceBase;
			var pluginInfoClass:Class = getDefinitionByName( source ) as Class;
			pluginResource = new PluginInfoResource( new pluginInfoClass() );
			
			this.setupListeners();
			//Load the plugin
			this._mediaFactory.loadPlugin( pluginResource );
			
		}
		protected function onPluginLoaded( event:MediaFactoryEvent ):void
		{
			trace( "onPluginLoaded()  this._smilLoaded "+this._smilLoaded );
			setupListeners(false);
			//Marker 5: Load the media
			this._pluginsLoaded +=1;
			if(this._dataType!="f4m"){
				if(this._smilTimer==null){
						this._smilTimer = new Timer(100);
						this._smilTimer.addEventListener(TimerEvent.TIMER,onCheckSMILParsed);
						this._smilTimer.start();
				}
			}
			this.loadMedia()
			
		}
		
		protected function onCheckSMILParsed(event:TimerEvent):void
		{
			if(this._pluginsLoaded>=this._pluginCount && this._smilLoaded==true &&this._smilTimer.running){
				this._smilTimer.stop();
				this._smilTimer = null;
				//tracer.log("SMIL TIMER plugins "+this._pluginsLoaded+" smilLoaded "+this._smilLoaded,_classname);
				this.loadMedia()
				
			}
		}
		protected function onSmilParsed(event:constellationEvent):void
		{
		
		this._dataType=this._smilParser.isSMILFile==true?"SMIL":"unknown";
		this._filePath = ExternalConfig.getInstance().smilPath;
		this._streamToken = this._smilParser.streamToken;
		this._authToken = this._smilParser.authToken;
		
			tracer.log("onSmilParsed "+this._smilParser.protocol+"  _dataType "+_dataType,_classname);
			this._mediaProtocol = this._smilParser.protocol
				this._pluginCount =2;
				
					loadPlugin( SMIL_PLUGIN_INFOCLASS );
					loadPlugin( ADVANCED_STREAMING_PLUGIN );
		
			this._smilLoaded = true;
			
		}
		protected function onUnknownSmilParsed(event:constellationEvent):void
		{
			this._dataType=this._smilParser.isSMILFile==true?"SMIL":"unknown";
			
			this._unknownFormat = event.data as String;
			
			var typeArr:Array =  this._unknownFormat.split(".")
				
			this._dataType = typeArr[typeArr.length-1];
			this._filePath = ExternalConfig.getInstance().smilPath;
		
				this._pluginCount =2;
				loadPlugin( ADVANCED_STREAMING_PLUGIN );
			
		//	this.loadMedia(_akamaiStrings[this._nextTypeNum].toString())
		}
		protected function onJSONLoaded(event:constellationEvent):void{
			this._dataType="f4m";
			_filePath = event.data as String;
		
			this._mediaProtocol = _filePath.split("://")[0];
			this._pluginCount =2;
			loadPlugin( ADVANCED_STREAMING_PLUGIN );
		}
		public function loadMedia(akamaiResourceType:String=null):void 
		{ 
			/*
			
			*/
		//	tracer.log("data type is "+this._dataType,_classname);
				var elementCreated:*
				var mediaProtocol:String = this._mediaProtocol;
				var resource:StreamingURLResource;
				var dataPath:String = this._filePath;
								
								if(this._dataType=="SMIL"){
												if(mediaProtocol=="rtmp"){				
																		
														resource = new StreamingURLResource(this._smilParser.rawData.toString(), StreamType.LIVE_OR_RECORDED/*, 120, 130*/);
														resource.mediaType = "loadedSMIL";
											
														tracer.log("auth token is "+this._smilParser.streamToken,_classname);
																			
																				
												}else if(mediaProtocol=="http"){
																
																resource = new StreamingURLResource( this._smilParser.serverConnection, StreamType.LIVE_OR_RECORDED/*, 120, 130*/);
																resource.mediaType = "loadedSMIL";
																
																var hdMBRObject:HDMBRObject = new HDMBRObject();
																	hdMBRObject.httpBase =this._smilParser.protocol+"://"+this._smilParser.hostName;//"http://efvod-hdnetwork.akamai.com.edgesuite.net/";
																var streamCount:int = this._smilParser.dsi.streamCount
														//loop through and populate the hdMBRObject
																for(var i:int = 0;i<streamCount;i++){
																	// These bitrates are in kbps
																		var dsiObject:DynamicStreamBitrate  = this._smilParser.dsi.streams[i] as DynamicStreamBitrate;
																	
																		hdMBRObject.addStream(dsiObject.name, dsiObject.rate);
																}
															/* test object
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
												}
								}else{
										resource = new StreamingURLResource(this._filePath , StreamType.LIVE_OR_RECORDED/*, 120, 130*/);
												
								}
							//	AkamaiMediaUtils.addAkamaiMediaTypeToResource(resource,AkamaiStrings.AKAMAI_MEDIA_TYPE_HDN_ADOBE_HTTP,null)	
									
								this._mediaElement =  _mediaFactory.createMediaElement(resource);
								var metadata:Metadata = resource.getMetadataValue(AkamaiStrings.AKAMAI_ADVANCED_STREAMING_PLUGIN_METADATA_NAMESPACE)	as Metadata;
								if (metadata == null)
								{
									metadata = new Metadata();		
								}
								
								
								// Use one or the other of these metadata keys. If both are supplied, the plugin
								// will look at the starting bitrate to determine the corresponding index and 
								// use the lesser of the two indices.			
								metadata.addValue(AkamaiStrings.AKAMAI_METDATA_KEY_MBR_STARTING_INDEX, 1);
								metadata.addValue(AkamaiStrings.AKAMAI_METADATA_KEY_MBR_STARTING_BITRATE, 400);
								
								if(this._streamToken){
									metadata.addValue(AkamaiStrings.AKAMAI_METADATA_KEY_CONNECT_AUTH_PARAMS,this._streamToken);
									metadata.addValue(AkamaiStrings.AKAMAI_METADATA_KEY_STREAM_AUTH_PARAMS,this._streamToken);
									
								}
								
								resource.addMetadataValue(AkamaiStrings.AKAMAI_ADVANCED_STREAMING_PLUGIN_METADATA_NAMESPACE, metadata);	
								
								
								
								if(this._mediaElement == null){
									this.tryingNextType("elementCreated "+null+" type Tried "+akamaiResourceType);
								}else{
									setupMediaElementListeners();
									
									this._mediaPlayerSprite.mediaPlayer.media =this._mediaElement;
									
									this._mediaPlayerSprite.mediaPlayer.autoDynamicStreamSwitch = true;
									//	this._mediaPlayerSprite.mediaPlayer.switchDynamicStreamIndex(1);
								}
		
		}
		private function addMediaListeners():void{
			if(this._mediaPlayerSprite){
				//	this._mediaPlayerSprite.mediaPlayer.addEventListener(DisplayObjectEvent.MEDIA_SIZE_CHANGE, onMediaSizeChange);		
					this._mediaPlayerSprite.mediaPlayer.addEventListener(TimeEvent.DURATION_CHANGE, onDurationChange);	
					this._mediaPlayerSprite.mediaPlayer.addEventListener(TimeEvent.CURRENT_TIME_CHANGE, onCurrentTimeChange);
					this._mediaPlayerSprite.mediaPlayer.addEventListener(TimeEvent.COMPLETE, onCompleteChange);
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
			}
		}
		private function removeMediaListeners():void{
			if(this._mediaPlayerSprite){
				//	this._mediaPlayerSprite.mediaPlayer.removeEventListener(DisplayObjectEvent.MEDIA_SIZE_CHANGE, onMediaSizeChange);		
					this._mediaPlayerSprite.mediaPlayer.removeEventListener(TimeEvent.DURATION_CHANGE, onDurationChange);	
					this._mediaPlayerSprite.mediaPlayer.removeEventListener(TimeEvent.CURRENT_TIME_CHANGE, onCurrentTimeChange);
					this._mediaPlayerSprite.mediaPlayer.removeEventListener(TimeEvent.COMPLETE, onCompleteChange);
					this._mediaPlayerSprite.mediaPlayer.removeEventListener(SeekEvent.SEEKING_CHANGE, onSeekingChange);
					this._mediaPlayerSprite.mediaPlayer.removeEventListener(PlayEvent.CAN_PAUSE_CHANGE, onCanPauseEvent);
					this._mediaPlayerSprite.mediaPlayer.removeEventListener(PlayEvent.PLAY_STATE_CHANGE, onPlayStageChange);
					this._mediaPlayerSprite.mediaPlayer.removeEventListener(MediaErrorEvent.MEDIA_ERROR, onMediaError);
					this._mediaPlayerSprite.mediaPlayer.removeEventListener(MediaPlayerStateChangeEvent.MEDIA_PLAYER_STATE_CHANGE,onChange);
					
					this._mediaPlayerSprite.mediaContainer.removeEventListener(LayoutTargetEvent.ADD_TO_LAYOUT_RENDERER,onLayoutAdd);
					this._mediaPlayerSprite.mediaContainer.removeEventListener(ContainerChangeEvent.CONTAINER_CHANGE, onContainerChange);
					
					
					this._mediaPlayerSprite.mediaPlayer.removeEventListener(MediaPlayerStateChangeEvent.MEDIA_PLAYER_STATE_CHANGE, stateChange);
					this._mediaPlayerSprite.mediaPlayer.removeEventListener(MediaElementEvent.TRAIT_REMOVE, onMediaTraitRemoved);
					this._mediaPlayerSprite.mediaPlayer.removeEventListener(MediaErrorEvent.MEDIA_ERROR, onMediaError);
			}
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
			tracer.log("on TRAIT ADD " +event.traitType,_classname) ;
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
						timeTrait.addEventListener(TimeEvent.COMPLETE, onCompleteChange);
					break;
				case MediaTraitType.LOAD:
					var loadTrait:LoadTrait = (event.target as MediaElement).getTrait(event.traitType) as LoadTrait;
					//loadTrait.addEventListener(LoadEvent.LOAD_STATE_CHANGE, onMediaLoadStateChange);
					break;
				case MediaTraitType.BUFFER:
					bufferTrait = (event.target as MediaElement).getTrait(MediaTraitType.BUFFER) as BufferTrait;
					bufferTrait.addEventListener(BufferEvent.BUFFERING_CHANGE,onBufferingChange);
					bufferTrait.addEventListener(BufferEvent.BUFFER_TIME_CHANGE,onBufferTimeChange);
				//	bufferTrait.bufferTime = .5
					tracer.log("buffer Trait "+bufferTrait.bufferLength+" length TIME "+bufferTrait.bufferTime,_classname);
					// For experimentation purposes. If you'd like to set a custom buffer length, here is how
					// to do it:
					//bufferTrait.bufferTime = 3;
					break;
			}	
		}
		
		protected function onBufferTimeChange(evt:BufferEvent):void
		{
			tracer.log("on buffer TIME CHANGE "+evt.buffering+" time "+evt.bufferTime,_classname);
			
		}
		
		protected function onBufferingChange(evt:BufferEvent):void
		{
			tracer.log("on BUFFERING CHANGE isBuffering : "+evt.buffering+" time "+evt.bufferTime,_classname);
			tracer.log("FROM TRAIT BUFFERING CHANGE isBuffering : "+bufferTrait.buffering+" time "+bufferTrait.bufferTime,_classname);
		}
		private function onTraitRemove(event:MediaElementEvent):void
		{
			tracer.log("on TRAIT REMOVE " +event.traitType,_classname) ; 
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
						timeTrait.removeEventListener(TimeEvent.COMPLETE, onCompleteChange);
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
			var msg:String = "OSMF ERROR : error ID="+event.error.errorID;
			
			if (event.error.message != null && event.error.message.length > 0)
			{
				msg += " message="+event.error.message;
			}
			if (event.error.detail != null && event.error.detail.length > 0)
			{
				msg += " detail=" + event.error.detail;
			}
			if(this._bufferWatcher.running == true){
				this._bufferWatcher.stop();
			}
			
			var stringLen:int = this._akamaiStrings.length
			if(this._nextTypeNum>=stringLen){
						this._statusMessageView.statusMessage("");
						loggingManager.getInstance().logToServer(msg);
						messageView.getInstance().setMessage(ExternalConfig.getInstance().defaultErrorMessage+errorConfig.osmf_error,true);
					if(this._seekTimer!=null){
						if(this._seekTimer.running){
							this._seekTimer.stop();
						}
		}
		}else{
			//this.tryingNextType("OSMF ERROR "+event.error.message);
		}
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
			//tracer.log(" setting volume in player "+newVolume+" MP Volume "+this._mediaPlayerSprite.mediaPlayer.volume,_classname);
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
		
		protected function onDurationChange(event:TimeEvent):void
		{
			tracer.log(" onDurationChange  "+event.type+" duration "+event.time,_classname)
			
		}
		
		protected function onMediaSizeChange(event:Event):void
		{
			tracer.log("onMediaSizeChange  "+event.type,_classname)
			this.onStageResize()
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
		public function onStageResize(event:Event=null):void
		{
			if(this._mediaPlayerSprite==null){
				return;
			}
			_mediaPlayerSprite.width = stage.stageWidth;
			_mediaPlayerSprite.height = stage.stageHeight;
			this._statusMessageView.textWidth = stage.stageWidth -80
			this._statusMessageView.textHeight = 20;
			this._statusMessageView.x = 70//stage.stageWidth-this._statusTextField.width-10
			this._statusMessageView.y = stage.stageHeight-this._statusMessageView.height-5;
			
		}
		public function onHeartAttack(event:Event=null):void
		{
			this._videoComplete = true;
			this._mediaPlayerSprite.mediaPlayer.pause();
			var delayStopTimer:Timer = new Timer(10000,1);
				delayStopTimer.addEventListener(TimerEvent.TIMER,onHeartAttackRemovePlayer);
				delayStopTimer.start();
				
			this._mediaPlayerSprite.alpha = .75
			this.removeMediaListeners();
			if(this._qosOverlay){
				this._qosOverlay.stopInfo();
			}
		}
		
		protected function onHeartAttackRemovePlayer(event:TimerEvent):void
		{
			this.onCompleteChange(null,true);
			
		}	
		
		public function set smilParser(smilPar:smilParser):void{
			this._smilParser = smilPar;
			this._smilParser.addEventListener(constellationEvent.SMIL_PARSED,onSmilParsed);
			this._smilParser.addEventListener(constellationEvent.UNKNOWN_SMIL_FORMAT,onUnknownSmilParsed);
			//	tracer.log("attached smil parser "+this._smilParser,_classname);
		}
		public function get streamSMILParser():smilParser{
			return this._smilParser
				
		}
		public function showDebugOverlay(evt:Event=null):void{
			CONFIG::FLASH_10_1
			{
				if(this._debugPanel==null){
					this._debugPanel = new Sprite();
					var debugBG:Sprite = new Sprite();
					debugBG.graphics.beginFill(0x000000,.5);
					debugBG.graphics.drawRect(0,0,800,500);
					debugBG.graphics.endFill();
					this._debugPanel.addChild(debugBG);
					
					
					this._debugText = new TextField();
					this._debugText.x = 0;
					this._debugText.y = 0;
					this._debugText.name = "videoInfo";
					this._debugText.autoSize = TextFieldAutoSize.LEFT
					this._debugText.textColor = 0xffffff;
					this._debugPanel.addChild(this._debugText);
					this._qosOverlay = new VideoInfoOverlay();			
					this._qosOverlay.register(this, this._debugPanel, this._mediaPlayerSprite.mediaPlayer);
					
					this._qosOverlay.showInfo();
					
					this.addChild(this._debugPanel);
				}else{
					this._qosOverlay.stopInfo();
					this.removeChild(this._debugPanel)
					this._debugPanel = null;
				}
			}
		}
		private function startDebugTimer():void{
			this._debugJSTimer= new Timer(ExternalConfig.getInstance().showTimeDebugInterval);
			this._debugJSTimer.addEventListener(TimerEvent.TIMER,onDebugInterval);
			this._debugJSTimer.start();
		}
		
		protected function onDebugInterval(event:TimerEvent):void
		{
			var currentTime:String = this._mediaPlayerSprite.mediaPlayer.currentTime.toFixed(2);
			tracer.log("debug ExternalInterface.available  "+ExternalInterface.available+"  current time "+currentTime,_classname,tracer.LEVEL_DEBUG);
			if(ExternalInterface.available){
				ExternalInterface.call("setCurrentTime",currentTime);
			}
			
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
		public function switchUpHandler(evt:Event=null):void{
			var streamCount:int = this._mediaPlayerSprite.mediaPlayer.maxAllowedDynamicStreamIndex
			var nextHigh:int = this._mediaPlayerSprite.mediaPlayer.currentDynamicStreamIndex+1
				if(streamCount>=nextHigh){
					this._mediaPlayerSprite.mediaPlayer.switchDynamicStreamIndex(nextHigh);
				}
		}
		public function switchDownHandler(evt:Event=null):void{
			
			var nextLow:int = this._mediaPlayerSprite.mediaPlayer.currentDynamicStreamIndex-1
			if(nextLow>=0){
				this._mediaPlayerSprite.mediaPlayer.switchDynamicStreamIndex(nextLow);
			}
		}
		public function autoSwitchHandler(evt:Event=null):void{
			this._mediaPlayerSprite.mediaPlayer.autoDynamicStreamSwitch = true;
		}
		public function manualSwitchHandler(evt:Event=null):void{
			this._mediaPlayerSprite.mediaPlayer.autoDynamicStreamSwitch = false;
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