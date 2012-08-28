/*****************************************************
*  
*  Copyright 2009 Akamai Technologies, Inc.  All Rights Reserved.
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
*  The Initial Developer of the Original Code is Akamai Technologies, Inc.
*  Portions created by Akamai Technologies, Inc. are Copyright (C) 2009 Akamai 
*  Technologies, Inc. All Rights Reserved. 
*  
*****************************************************/

package
{
	import flash.display.Sprite;
	import flash.display.StageAlign;
	import flash.display.StageScaleMode;
	import flash.events.Event;
	import flash.utils.getDefinitionByName;
	
	import org.osmf.containers.MediaContainer;
	import org.osmf.elements.VideoElement;
	import org.osmf.events.*;
	import org.osmf.layout.ScaleMode;
	import org.osmf.mast.MASTPluginInfo;
	import org.osmf.media.MediaElement;
	import org.osmf.media.MediaFactory;
	import org.osmf.media.MediaFactoryItem;
	import org.osmf.media.MediaPlayer;
	import org.osmf.media.MediaResourceBase;
	import org.osmf.media.PluginInfoResource;
	import org.osmf.media.URLResource;
	import org.osmf.metadata.*;
	import org.osmf.net.NetLoader;

	[SWF(backgroundColor="0x333333")]
	public class MASTSample extends Sprite
	{
		public function MASTSample()
		{
			stage.scaleMode = StageScaleMode.NO_SCALE;
			stage.align = StageAlign.TOP_LEFT;
			
			mediaFactory = new MediaFactory();
			
			stage.scaleMode = StageScaleMode.NO_SCALE;
			stage.align = StageAlign.TOP_LEFT;
            
  			// Create the Sprite class that holds our MediaPlayer.
 			sprite = new MediaContainer();
			addChild(sprite);
			
			// Set the Sprite's size to match that of the stage, and
			// prevent the content from being scaled.
			sprite.layoutMetadata.scaleMode = ScaleMode.NONE;
			sprite.width = stage.stageWidth;
			sprite.height = stage.stageHeight;
			
			// Make sure we resize the Sprite when the stage dimensions
			// change.
			stage.addEventListener(Event.RESIZE, onStageResize);
			
			loadPlugin(MAST_PLUGIN_INFOCLASS);
		}
		
		private function loadPlugin(source:String):void
		{
			var pluginResource:MediaResourceBase;
			if (source.substr(0, 4) == "http" || source.substr(0, 4) == "file")
			{
				// This is a URL, create a URLResource
				pluginResource = new URLResource(source);
			}
			else
			{
				// Assume this is a class
				var pluginInfoRef:Class = getDefinitionByName(source) as Class;
				pluginResource = new PluginInfoResource(new pluginInfoRef);
			}
			
			loadPluginFromResource(pluginResource);			
		}
		
		private function loadPluginFromResource(pluginResource:MediaResourceBase):void
		{
			mediaFactory.addEventListener(MediaFactoryEvent.PLUGIN_LOAD, onPluginLoaded);
			mediaFactory.addEventListener(MediaFactoryEvent.PLUGIN_LOAD_ERROR, onPluginLoadFailed);
			mediaFactory.loadPlugin(pluginResource);
		}
		
		private function onPluginLoaded(event:MediaFactoryEvent):void
		{
			trace(">>> Plugin successfully loaded.");
			loadMainVideo(REMOTE_STREAM);
		}
		
		private function onPluginLoadFailed(event:MediaFactoryEvent):void
		{
			trace(">>> Plugin failed to load.");
		}
					
		private function loadMainVideo(url:String):void
		{	
			var resource:URLResource = new URLResource(url);

			// Assign to the resource the metadata that indicates that it should have a MAST
			// document applied (and include the URL of that MAST document).
			var metadata:Metadata = new Metadata();
			metadata.addValue(MASTPluginInfo.MAST_METADATA_KEY_URI, MAST_URL_PREROLL);
			resource.addMetadataValue(MASTPluginInfo.MAST_METADATA_NAMESPACE, metadata);
			
			var mediaElement:MediaElement = mediaFactory.createMediaElement(resource);
			
			if (mediaElement == null)
			{
				var netLoader:NetLoader = new NetLoader();
				
				// Add a default VideoElement
				mediaFactory.addItem(new MediaFactoryItem("org.osmf.elements.video", netLoader.canHandleResource, createVideoElement));
				mediaElement = mediaFactory.createMediaElement(resource);
			}
			
			mediaElement.addEventListener(MediaErrorEvent.MEDIA_ERROR, onMediaError, false, 0, true);
			
			sprite.addMediaElement(mediaElement);
			mediaPlayer.media = mediaElement;
		}
		
		private function createVideoElement():MediaElement
		{
			return new VideoElement();
		}
		
   		private function onMediaError(event:MediaErrorEvent):void
   		{
   			var errMsg:String = "Media error : code="+event.error.errorID+" description="+event.error.message;
   			
   			trace(errMsg);
   		}
		
		
		private function onStageResize(event:Event):void
		{
			sprite.width = stage.stageWidth;
			sprite.height = stage.stageHeight;
		}
		
		private var mediaFactory:MediaFactory;	
		private var sprite:MediaContainer;
		private var mediaPlayer:MediaPlayer = new MediaPlayer();

		private static const MAST_PLUGIN_INFOCLASS:String = "org.osmf.mast.MASTPluginInfo";		
		private static const loadTestRef:MASTPluginInfo = null;
		
		// MAST documents
		private static const MAST_URL_POSTROLL:String 				= "http://mediapm.edgesuite.net/osmf/content/mast/mast_sample_onitemend.xml";
		private static const MAST_URL_PREROLL:String 				= "http://mediapm.edgesuite.net/osmf/content/mast/mast_sample_onitemstart.xml";
				
		
		private static const REMOTE_STREAM:String
			= "rtmp://cp67126.edgefcs.net/ondemand/mediapm/strobe/content/test/SpaceAloneHD_sounas_640_500_short";
	}
}
