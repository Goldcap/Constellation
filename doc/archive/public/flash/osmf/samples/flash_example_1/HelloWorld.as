package 
{ 
    import flash.display.Sprite; 
    import org.osmf.containers.MediaContainer; 
    import org.osmf.media.DefaultMediaFactory; 
    import org.osmf.media.MediaElement; 
    import org.osmf.media.MediaFactory; 
    import org.osmf.media.MediaPlayer; 
    import org.osmf.media.URLResource; 
    import org.osmf.events.DisplayObjectEvent;
	import org.osmf.events.TimeEvent;
	 import org.osmf.events.MediaFactoryEvent;
    import org.osmf.media.MediaResourceBase;
 	import org.osmf.elements.SWFLoader;
 	import org.osmf.net.StreamingURLResource;
	 import org.osmf.events.MediaErrorEvent;
	 
    import com.llnw.view.ControlBar;	
	
    import flash.events.Event;
    import flash.events.FullScreenEvent;
    import flash.display.StageDisplayState;
    import org.osmf.metadata.Metadata;
   

    public class HelloWorld extends Sprite 
    { 
	
		private var container:MediaContainer;
		private var mediaPlayer:MediaPlayer;
		private var controlBar:ControlBar;
		
		private var savedWidth:Number = stage.stageWidth;
		private var savedHeight:Number = stage.stageHeight;
		public static const LIMELIGHT_FACET_URL:String = "com.limelight.video";
		
		private var mediaFactory:MediaFactory;
		
		//example:
		//"http://www.somedomain.com/plugin/LimelightStreamingPlugin.swf"
		//This path is an example of how to run the plugin localy.
		private var pluginPath:String = "file://C:/SDK/OSMF/LimelightStreamingPlugin.swf"
		
        public function HelloWorld() 
        { 
			//Set up Stage properties for transitioning to fullscreen.
			stage.scaleMode = "noScale"
			stage.align = "TL"
			stage.addEventListener(FullScreenEvent.FULL_SCREEN, handleFullScreen);
			createPlugin()
        } 
		
		public function createPlugin()
		{
			//Create a new factory
			mediaFactory = new DefaultMediaFactory();

			//Add event listeners to the factory for Plugin load events.
			mediaFactory.addEventListener(MediaFactoryEvent.PLUGIN_LOAD, onPluginLoad);
			mediaFactory.addEventListener(MediaFactoryEvent.PLUGIN_LOAD_ERROR, onPluginLoadError);
			
			//Create a Media Resource to load the plugin into.
			var pluginResource:MediaResourceBase = new URLResource(pluginPath)   
				mediaFactory.loadPlugin(pluginResource);
		}
 		private function onPluginLoadError(event:MediaFactoryEvent):void
		{
			trace("The plug-in failed to load.");
		}
 		private function onPluginLoad(event:MediaFactoryEvent):void
 		{
			//The plugin has loaded successfully. Call the method to set up media.
		 	buildMedia()
		}
		
		private function buildMedia():void{
			trace("Building Media display")
			// Create the container class that displays the media. 
            container = new MediaContainer(); 
			container.x = 0;
			container.y = 0;
            addChild(container); 
			
       
            // Create the streaming resource to play. 
            var resource:StreamingURLResource = new StreamingURLResource("rtmp://llnwqa.fcod.llnwd.net/a1218/o18/flashmbr/sample1_500kbps.f4v")
				resource.urlIncludesFMSApplicationInstance = true
				var limelightMetadata:Metadata  = new Metadata();
				limelightMetadata.addValue("llnwStreamType", "Streaming");
				
				resource.addMetadataValue(LIMELIGHT_FACET_URL,limelightMetadata);
				
            // Create MediaElement using MediaFactory and add it to the container class. 
            var mediaElement:MediaElement = mediaFactory.createMediaElement(resource); 
            container.addMediaElement(mediaElement); 
             
			
			 
            // Add MediaElement to a MediaPlayer. Because the MediaPlayer 
            // autoPlay property defaults to true, playback begins immediately. 
            mediaPlayer = new MediaPlayer(); 
			
			mediaPlayer.addEventListener(MediaErrorEvent.MEDIA_ERROR, onMediaError);
			
			mediaPlayer.addEventListener(DisplayObjectEvent.MEDIA_SIZE_CHANGE, setUpControls)
            mediaPlayer.media = mediaElement; 
			
		}
		
		private function onMediaError(event:MediaErrorEvent):void{
			trace("Error: " + event.error.message, event.error.detail)
			for (var i:String in event){
				trace(i+" : "+event[i])
			}
			
		}
		
		//this method handles adding the controlbar.
		private function setUpControls(event:DisplayObjectEvent):void{
			
			//instantiate a control bar instance
			controlBar = new ControlBar();
			
			//add listeners for control bar events:
			controlBar.addEventListener("ProgressReleaseEvent", handleProgressClick)
			controlBar.addEventListener("VolumeReleaseEvent",handleVolumeClick)
			controlBar.addEventListener("fullScreenClicked",handleFullScreenClick)
			controlBar.addEventListener("muteClickEvent",handleMuteClick)
			controlBar.addEventListener("restartMedia",restartMedia)
			controlBar.addEventListener("playClickEvent",handlePlayClick)
			
			//add control bar to the stage:
			addChild(controlBar)
			
			//setting the start value to 50% 
			controlBar.volume = 50;

			//set width and y position of the control bar
			setControlBarPossitionining(mediaPlayer.mediaWidth, mediaPlayer.mediaHeight)
			
			//add listener to the media player so we can update the time display
			mediaPlayer.addEventListener(TimeEvent.CURRENT_TIME_CHANGE, updateTime)
			
		}
		
		//this method handles placing the controlbar.
		private function setControlBarPossitionining(vidWidth:Number, vidHeight:Number):void{
			controlBar.y = Math.floor(vidHeight - (controlBar.height)+1);
			controlBar.x = 0;
			controlBar.controlBarPlacement(vidWidth)
		}
		//Updates display time, buffer and Scrubber position
		private function updateTime(event:TimeEvent):void{
			controlBar.setTime(mediaPlayer.currentTime, mediaPlayer.duration)
			controlBar.setStatUpdate(mediaPlayer.currentTime, mediaPlayer.duration)
			controlBar.setBufferUpdate(mediaPlayer.bytesLoaded, mediaPlayer.bytesTotal); 
		}
		
		
		private function handleProgressClick(event:Event):void{
			//Current position in the progress bar devided by the 
			//full width multiplied by the media duration: 
			//currentTime = currentValue / totalValue * mediaDuration;
			mediaPlayer.seek((controlBar.currentValue/controlBar.maxValue) * mediaPlayer.duration);
			
		}
	
		private function handleVolumeClick(event:Event):void{
			var volumeValue = Math.floor(controlBar.volumeValue);
			changeVolume(volumeValue)
		}
		
		private function setVolume(value:Number):void{
			trace("volume value: "+value)
			mediaPlayer.volume = value;
		}
		
		private function changeVolume(value:Number){
			
			var volumeMax = Math.floor(controlBar.volumeMax);
			var volumeValue = Math.floor(value);
			
			var theVolumeLevel = volumeValue / volumeMax * 100;
			//change to decimal representation
			theVolumeLevel = theVolumeLevel * 0.01
			
			setVolume(theVolumeLevel)
			
		}
		
		
		private var volumeConstant:Number = 0;
		private function handleMuteClick(event:Event):void{
			var mutedValue:Number;
			if(mediaPlayer.volume > 0){
				volumeConstant = controlBar.volumeValue;
				mutedValue = 0
			}else{
				mutedValue = volumeConstant
			}
			
			changeVolume(mutedValue)
			
		}
		
		//full screen button click handler
		private function handleFullScreenClick(event:Event):void {
			try{
				var isFullScreen:Boolean = (stage.displayState == StageDisplayState.FULL_SCREEN);
				stage.displayState = isFullScreen ? StageDisplayState.NORMAL :  StageDisplayState.FULL_SCREEN;
			}catch(e:Error){
				//catch error
			}
		}
		
		private function restartMedia(event:Event):void{
			mediaPlayer.seek(0)
		}
		private function handlePlayClick(event:Event):void{
			//this handles the play/pause toggle
			if(mediaPlayer.playing){
				mediaPlayer.pause()
			}else{
				mediaPlayer.play()
			}
		}
		
		//Full screen example
		private function handleFullScreen(event:FullScreenEvent):void {
			var currentPos:Number = 0;
			if (event.fullScreen) {
				// save values when going to fullScreen
				savedWidth = mediaPlayer.mediaWidth;
				savedHeight = mediaPlayer.mediaHeight;
				// set the size of the video object to the size of the stage
				container.width = stage.stageWidth;
				container.height = stage.stageHeight;
				
				//capture width for control bar positioning.
				currentPos = stage.stageWidth;
				
				controlBar.y = Math.floor(stage.stageHeight - controlBar.height)+1;
				controlBar.x = stage.x;
				
			} else {				
			
				container.width = savedWidth;
				container.height = savedHeight;
				currentPos = Math.floor(savedWidth)
				controlBar.y = Math.floor(savedHeight - (controlBar.height)+1);
				
			}
			controlBar.controlBarPlacement(currentPos)
		}
		
    } 
}