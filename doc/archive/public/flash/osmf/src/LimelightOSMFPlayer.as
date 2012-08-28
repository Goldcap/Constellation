package {
	import com.limelight.osmf.chrome.AlertChrome;
	import com.limelight.osmf.chrome.ControlsChrome;
	import com.limelight.osmf.chrome.DebugChrome;
	import com.limelight.osmf.chrome.PlayOverlayChrome;
	import com.limelight.osmf.configuration.Configuration;
	import com.limelight.osmf.data.DataResourceVO;
	import com.limelight.osmf.data.LimelightConstants;
	import com.limelight.osmf.data.LimelightStreamType;
	import com.limelight.osmf.mbr.ParseMBRStreams;
	import com.limelight.osmf.poster.PosterFrameElement;
	
	import flash.display.LoaderInfo;
	import flash.display.Sprite;
	import flash.events.Event;
	import flash.external.ExternalInterface;
	
	import org.osmf.containers.MediaContainer;
	import org.osmf.elements.SerialElement;
	import org.osmf.events.MediaErrorEvent;
	import org.osmf.events.MediaFactoryEvent;
	import org.osmf.layout.HorizontalAlign;
	import org.osmf.layout.LayoutMetadata;
	import org.osmf.layout.VerticalAlign;
	import org.osmf.media.DefaultMediaFactory;
	import org.osmf.media.MediaElement;
	import org.osmf.media.MediaPlayer;
	import org.osmf.media.MediaResourceBase;
	import org.osmf.media.URLResource;
	import org.osmf.metadata.Metadata;
	import org.osmf.net.DynamicStreamingResource;
	import org.osmf.net.StreamType;
	import org.osmf.net.StreamingURLResource;
	
	[SWF(width="800", height="480", alpha="0", backgroundColor="#FFFFFF")]
	public class LimelightOSMFPlayer extends Sprite
	{
	
		private var limelightConstants:LimelightConstants = new LimelightConstants();
		private var limelightStreamType:LimelightStreamType = new LimelightStreamType();
		
		private var dataResourceVO:DataResourceVO = DataResourceVO.getInstance();
		
		private var container:MediaContainer = dataResourceVO.container;
		
		private var mediaFactory:DefaultMediaFactory = dataResourceVO.mediaFactory;
		
		private var mediaPlayer:MediaPlayer = dataResourceVO.mediaPlayer;
		
		private var controlBar:ControlsChrome = dataResourceVO.controlBar;
		
		private var playOverlay:PlayOverlayChrome = dataResourceVO.playOverlay;
		
		private var debugPanel:DebugChrome = dataResourceVO.debugPanel;
		
		private var alertPanel:AlertChrome = dataResourceVO.alertPanel;
		
		private var configuration:Configuration = dataResourceVO.configuration;
		
		private var parseMBRStreams:ParseMBRStreams = new ParseMBRStreams();
		
		private var pluginCount:int;
		
		private var params:Object = new Object();
		
		
		public function LimelightOSMFPlayer()
		{	
			debugPanel.tracing = true;
			debugPanel.debug("Begin Setup");
			
			setup();
			
		}
		
		private function setup():void
		{
			
			defaultSize();
			
			// Add the container class to the display list.
			addChild(container);
			
			// Add the controlBar class to the display list.
			addChild(controlBar);
			
			// Add the playOverlay class to the display list.
			addChild(playOverlay);
			
			// Add the alertPanel class to the display list.
			addChild(debugPanel);
			
			// Add the alertPanel class to the display list.
			addChild(alertPanel);
			
			// Used to test if plugins load correctly
			mediaFactory.addEventListener(MediaFactoryEvent.PLUGIN_LOAD,onPluginLoaded);
			mediaFactory.addEventListener(MediaFactoryEvent.PLUGIN_LOAD_ERROR,onPluginLoadFailed);
			
			mediaPlayer.addEventListener(MediaErrorEvent.MEDIA_ERROR, onMediaError);
			
			var parameters:Object = LoaderInfo(this.root.loaderInfo).parameters;
			
			configuration.addEventListener(Event.COMPLETE,loadConfigComplete)
			configuration.load(parameters);
			
		}
		
		protected function defaultSize():void
		{
			dataResourceVO.resizeWidth = stage.stageWidth;
			dataResourceVO.resizeHeight = stage.stageHeight;
		}
		
		private function loadConfigComplete(event:Event):void
		{
			//trace(".configComplete")
			debugPanel.debug("Configuration LOADED");
			trace("configuration: " + configuration.toString());
			
			if (configuration.plugins.length > 0)
			{
				loadPlugins();
			}
			else
			{
				initializePlayer();
			}
			
		}
		 
		private function initializePlayer():void
		{
			//trace(".processSetupComplete")
			debugPanel.debug("Initialize Player");
			
			mediaPlayer.volume = configuration.volume;
			mediaPlayer.autoPlay = configuration.autoPlay;

			container.clipChildren = true;
			container.backgroundColor = configuration.backgroundColor;
			container.backgroundAlpha = isNaN(configuration.backgroundColor) ? 0 : 1;
			
			externalInterface();
			
			loadMedia();
		}
		
		/**
		 * 
		 */
		private function loadMedia():void
		{
			trace(".loadMedia ::")
			
			debugPanel.debug("url = " + configuration.url);
			if (configuration.url=="" || configuration.url==null)
			{
				alertPanel.alert("Alert:","No URL avalible")
				return;
			}
			
			if (mediaPlayer.media)
			{
				trace("Remove existing media element")
				//mediaPlayer.media.removeEventListener(MediaErrorEvent.MEDIA_ERROR, onMediaError)
				container.removeMediaElement(mediaPlayer.media);
				mediaPlayer.media = null;
			}
			
			// Set the MediaElement on a MediaPlayer.
			var mediaElement:MediaElement = createMediaElement();
			
			if (mediaElement)
			{
				if (configuration.poster)
				{
					trace(" poster = " + configuration.poster)
					var serialElement:SerialElement = new SerialElement();
					serialElement.addChild(new PosterFrameElement(new URLResource(configuration.poster)));
					serialElement.addChild(mediaElement);
					
					mediaPlayer.media = serialElement //new MonitorProxy(serialElement,debug);
				}
				else
				{
					mediaPlayer.media = mediaElement //new MonitorProxy(mediaElement,debug);
				}
				
				container.addMediaElement(mediaPlayer.media);
				container.width = stage.stageWidth;
				container.height = stage.stageHeight;
				
				debugPanel.media = mediaPlayer.media;

				var layoutMetadata:LayoutMetadata = new LayoutMetadata();
            	layoutMetadata.width = stage.stageWidth;
            	layoutMetadata.height = stage.stageHeight;
            	layoutMetadata.scaleMode = configuration.scaleMode;
            	layoutMetadata.horizontalAlign = HorizontalAlign.CENTER;
            	layoutMetadata.verticalAlign = VerticalAlign.MIDDLE;
				mediaPlayer.media.addMetadata(LayoutMetadata.LAYOUT_NAMESPACE, layoutMetadata)
			}
			else
			{
				//trace("ERROR: Media Element is Null")
				alertPanel.alert("Error:","Media Element is Null")
			}
		}
		
		/**
		 * Method creates and returns the correct type of 
		 * media element based on stream type 
		 */
		private function createMediaElement():MediaElement
		{
			trace("configuration = " + configuration.toString())
			
			//trace(".createMediaElement ::")
			debugPanel.debug("llnwStreamType = " + configuration.llnwStreamType);

			var limelightMetadata:Metadata  = new Metadata();
			limelightMetadata.addValue(limelightConstants.LIMELIGHT_STREAM_TYPE, configuration.llnwStreamType);
			
			// Create the resource to play.
			if( configuration.llnwStreamType==limelightStreamType.MBR || 
				configuration.llnwStreamType==limelightStreamType.LIVE_MBR )
			{
				trace(" :: Limelight MBR Content :: ");
				if (String(configuration.url.slice(configuration.url.length-3,configuration.url.length)).toLowerCase() == "f4m")
				{
					trace(" MBR using a f4m file");
					// MBR using a f4m file
					var dynamicStreamingResource:DynamicStreamingResource = new DynamicStreamingResource(configuration.url)
					dynamicStreamingResource.urlIncludesFMSApplicationInstance = configuration.useAppInstance;
					if (configuration.llnwStreamType==limelightStreamType.LIVE_MBR) dynamicStreamingResource.streamType = StreamType.LIVE;
					dynamicStreamingResource.addMetadataValue(limelightConstants.LIMELIGHT_IDENTIFIER,limelightMetadata);
					//dynamicStreamingResource.addMetadataValue(ANALYTICS_FACET_URL,debug);
				}
				else
				{
					trace(" Legacy MBR URL");
					// MBR legacy
					var dynamicStreamingResource:DynamicStreamingResource = parseMBRStreams.parse(configuration.url);
					dynamicStreamingResource.urlIncludesFMSApplicationInstance = configuration.useAppInstance;
					if (configuration.llnwStreamType==limelightStreamType.LIVE_MBR) dynamicStreamingResource.streamType = StreamType.LIVE;
					dynamicStreamingResource.addMetadataValue(limelightConstants.LIMELIGHT_IDENTIFIER,limelightMetadata);
					//dynamicStreamingResource.addMetadataValue(ANALYTICS_FACET_URL,debug);	
				}
				
				return mediaFactory.createMediaElement(dynamicStreamingResource);
			}
			else if( configuration.llnwStreamType!="" && configuration.llnwStreamType!=null )
			{
				trace(" :: Limelight Content :: ");
				var streamingURLResource:StreamingURLResource = new StreamingURLResource(configuration.url);
				streamingURLResource.urlIncludesFMSApplicationInstance = configuration.useAppInstance;
				if (configuration.llnwStreamType==limelightStreamType.DVR) streamingURLResource.streamType = StreamType.DVR;
				if (configuration.llnwStreamType==limelightStreamType.LIVE_STREAM) streamingURLResource.streamType = StreamType.LIVE;
				streamingURLResource.addMetadataValue(limelightConstants.LIMELIGHT_IDENTIFIER,limelightMetadata);
				//streamingURLResource.addMetadataValue(ANALYTICS_FACET_URL,debug);
				
				trace("streamType = " + streamingURLResource.streamType)
				return mediaFactory.createMediaElement(streamingURLResource);
			}
			else
			{
				trace(" :: Standard Content :: ");
				if (String(configuration.url.slice(0,4)).toLowerCase() == "rtmp")
				{
					trace(" StreamingURLResource")
					var streamingURLResource:StreamingURLResource = new StreamingURLResource(configuration.url);
					streamingURLResource.urlIncludesFMSApplicationInstance = configuration.useAppInstance;
					return mediaFactory.createMediaElement(streamingURLResource);
				}
				else
				{
					trace(" URLResource")
					var urlResource:URLResource = new URLResource(configuration.url);
					return mediaFactory.createMediaElement(urlResource);
				}
			}
		}
		
		private function onMediaError(event:MediaErrorEvent):void
		{
			//trace("ERROR :: \n" + event.error.message + "\n" + event.error.detail)
			alertPanel.alert("Error: " + event.error.message, event.error.detail)
		}
		
		private function loadPlugins():void
		{
			trace(".loadPlugins")
			pluginCount = 0;
			for each (var source:String in configuration.plugins)
			{
				debugPanel.debug("Load Plugin = " + source);
				var pluginResource:MediaResourceBase = new URLResource(source);
				mediaFactory.loadPlugin(pluginResource);
			}
		}
		
		private function onPluginLoaded(event:MediaFactoryEvent):void
		{
			//trace("Plugin LOADED");
			debugPanel.debug("Plugin LOADED");
			pluginCount++;
			if(pluginCount>=configuration.plugins.length)
			{
				initializePlayer();
			}
			
		}

		private function onPluginLoadFailed(event:MediaFactoryEvent):void
		{
			//trace("ERROR: Plugin LOAD FAILED");
			var urlResource:URLResource = event.resource as URLResource;
			alertPanel.alert("Error:","Plugin LOAD FAILED : " + urlResource.url)
			trace("event.resource = " + event.resource)
		}
		
		/**
		 * Method adds javascript callbacks for switching media and 
		 * resizing the video as well as controls.
		 *  - play: name of the JS call to play the current video.
		 *  - pause: name of the JS call to pause the current video.
		 *  - stop: name of the JS call to stop the current video.
		 *  - rewind: name of the JS call to rewind the current video.
		 *  - llnwStreamType: name of the JS call to change stream type for the current video.
		 *  - url: name of the JS call to swap the current video.
		 *  - size: name of the JS call to change the dimentions of the video and player.
		 */
		private function externalInterface():void
		{
			//trace(".externalInterface");
			if (ExternalInterface.available) {
                try {
                    //trace("Adding callback");
                    ExternalInterface.addCallback("play", play);
                    ExternalInterface.addCallback("pause", pause);
                    ExternalInterface.addCallback("stop", stop);
                    ExternalInterface.addCallback("rewind", rewind);
                    ExternalInterface.addCallback("llnwStreamType", llnwStreamType);
                    ExternalInterface.addCallback("url", url);
					ExternalInterface.addCallback("size", size);
                } catch (error:SecurityError) {
                    //trace("ERROR: SecurityError: " + error.message)
                    alertPanel.alert("Security Error:", error.message )
                } catch (error:Error) {  
                    //trace("ERROR: " + error.message)
                    alertPanel.alert("Error:", error.message )
                }
            } else {
                //trace("ERROR: External interface is not available")
                alertPanel.alert("Error:","External interface is not available")
            }
		}
		
		private function play():void
		{
			//trace(".play");
			if (mediaPlayer)
			{
				mediaPlayer.play();
			}
		}
		
		private function pause():void
		{
			//trace(".pause");
			if (mediaPlayer)
			{
				mediaPlayer.pause();
			}
		}
		
		private function stop():void
		{
			//trace(".stop");
			if (mediaPlayer)
			{
				mediaPlayer.stop();
			}
		}
		
		private function rewind():void
		{
			//trace(".rewind");
			if (mediaPlayer)
			{
				mediaPlayer.seek(0);
				mediaPlayer.play();
			}
		}
		
		private function llnwStreamType(streamType:String):void
		{
			//trace(".llnwStreamType");
			configuration.llnwStreamType = streamType;
		}
		
		private function url(url:String):void
		{
			//trace(".url : url = " + url);
			if (mediaPlayer)
			{
				configuration.url = url;
				loadMedia();
			}
		}
		
		private function size(w:Number,h:Number):void
		{
			//trace(".size")
			debugPanel.debug("Player Resize");
			dataResourceVO.resizeWidth = w;
			dataResourceVO.resizeHeight = h;
			
			controlBar.updateDisplay();
			playOverlay.updateDisplay();
			alertPanel.updateDisplay();
			debugPanel.updateDisplay();
		}
		
	}
}
