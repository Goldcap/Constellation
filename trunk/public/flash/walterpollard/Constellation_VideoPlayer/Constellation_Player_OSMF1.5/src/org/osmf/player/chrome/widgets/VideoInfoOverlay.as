/***********************************************************
 * Copyright 2010 Adobe Systems Incorporated.  All Rights Reserved.
 *
 * *********************************************************
 * The contents of this file are subject to the Berkeley Software Distribution (BSD) Licence
 * (the "License"); you may not use this file except in
 * compliance with the License. 
 *
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 *
 *
 * The Initial Developer of the Original Code is Adobe Systems Incorporated.
 * Portions created by Adobe Systems Incorporated are Copyright (C) 2010 Adobe Systems
 * Incorporated. All Rights Reserved.
 * 
 **********************************************************/

package org.osmf.player.chrome.widgets
{
	import com.akamai.osmf.net.AkamaiZStreamWrapper;
	import com.constellation.events.constellationEvent;
	import com.constellation.externalConfig.ExternalConfig;
	import com.constellation.managers.loggingManager;
	import com.constellation.parsers.smilParser;
	import com.constellation.view.videoView;
	
	import flash.display.DisplayObject;
	import flash.display.MovieClip;
	import flash.display.Sprite;
	import flash.events.ContextMenuEvent;
	import flash.events.Event;
	import flash.events.MouseEvent;
	import flash.events.TimerEvent;
	import flash.net.NetStream;
	import flash.system.Capabilities;
	import flash.system.System;
	import flash.text.TextField;
	import flash.text.TextFieldAutoSize;
	import flash.text.TextFormat;
	import flash.ui.ContextMenu;
	import flash.ui.ContextMenuItem;
	import flash.utils.Timer;
	import flash.utils.getDefinitionByName;
	
	import org.osmf.logging.Log;
	import org.osmf.logging.Logger;
	import org.osmf.media.MediaElement;
	import org.osmf.media.MediaPlayer;
	import org.osmf.media.MediaPlayerSprite;
	import org.osmf.media.videoClasses.VideoSurface;
	import org.osmf.media.videoClasses.VideoSurfaceInfo;
	import org.osmf.net.*;
	import org.osmf.player.media.StrobeMediaPlayer;
	import org.osmf.player.utils.StrobePlayerStrings;
	import org.osmf.player.utils.StrobeUtils;
	import org.osmf.traits.MediaTraitType;
	import org.osmf.utils.OSMFSettings;
	
	CONFIG::FLASH_10_1	
	{	
		import flash.net.NetGroup;	
		import flash.net.NetStreamMulticastInfo;
	}

	/**
	 * VideoInfoOverlay can be used for troubleshooting common Strobe Media Playback issues.
	 * 
	 * You can activate it by choosing the "Strobe Media Playback Info" on the ControlBar context menu (right click on the control bar).
	 */ 
	public class VideoInfoOverlay extends Sprite
	{	
		/**
		 * Registers the context menu item. Note that we use the ControlBar as the ContextMenu target.
		 */ 
		public function register(target:videoView, container:Sprite, mediaPlayer:MediaPlayer):void
		{
			this.container = container;
			this.mediaPlayer = mediaPlayer;
			
			var customContextMenu:ContextMenu;
			customContextMenu = new ContextMenu();
			var videoInfoItem:ContextMenuItem = new ContextMenuItem("Constellation Info v"+ExternalConfig.getInstance().version);
			customContextMenu.hideBuiltInItems();
			customContextMenu.customItems = [videoInfoItem];
			videoInfoItem.addEventListener(ContextMenuEvent.MENU_ITEM_SELECT, onItemSelect);
			this.target = target;  
			target.contextMenu = customContextMenu;
			loggingManager.getInstance().addEventListener(constellationEvent.DEBUG_MESSAGE,onDebugMessage);
			this._debugTextField = new TextField();
			this._debugTextField.textColor = 0xffffff;		
			this._debugTextField.autoSize = TextFieldAutoSize.LEFT
			this.container.addChild(this._debugTextField);
			this.container.visible = ExternalConfig.getInstance().showDebugPanel;
		}
		
		protected function onDebugMessage(event:constellationEvent):void
		{
			var msg:String = event.data as String;
			
			if(this._debugTextField.length>1500){
				this._debugTextField.text = msg;
			}else{
				this._debugTextField.appendText("\n"+msg);
			}
			this._debugTextField.width = 400;
			this._debugTextField.height = 30;
			
		}
		
		public function showInfo():void
		{
			if (refreshTimer != null)
			{
				return;
			}
			
			refreshTimer = new Timer(REFRESH_INTERVAL);
			refreshTimer.addEventListener(TimerEvent.TIMER, onTimer);
			refreshTimer.start();
			
			videoInfoOverlay = this.container;//new MovieClip()//new ASSET_VideoInfoOverlay();
		
			
			containerWidth = container.width;
			videoInfoOverlayWidth = videoInfoOverlay.width;
			videoInfoOverlay.x = 5;
			videoInfoOverlay.y = 5;
			
			for (var idx:int=0; idx < videoInfoOverlay.numChildren; idx++)
			{
				var child:DisplayObject = videoInfoOverlay.getChildAt(idx);	
			/*	if (child is CloseButton)
				{
					closeButton = child as CloseButton;
					closeButton.addEventListener(MouseEvent.CLICK, onCloseButtonClick);
				}*/
				
				if (child.name == "videoInfo")
				{
					textField = child as TextField;
				}
			}
		
		
			onTimer(null);
		}
		public function stopInfo():void{
			refreshTimer.stop();
			refreshTimer = null;
			
			
		//	container.removeChild(videoInfoOverlay);
			videoInfoOverlay = null;
		}
		// Internals
		private static const REFRESH_INTERVAL:int = 3000;
		
		private var refreshTimer:Timer;
		private var textField:TextField;		
		private var container:Sprite;
		private var target:videoView;
		private var mediaPlayer:MediaPlayer;
	//	private var closeButton:CloseButton;
		private var videoInfoOverlay:Sprite;
		private var containerWidth:Number;
		private var videoInfoOverlayWidth:Number;
		private var _logListen:Logger;
		private var _debugTextField:TextField;
		private var _netStream:NetStream;
		private var _akamaiZStream:AkamaiZStreamWrapper;
		
		private function onItemSelect(event:Event):void
		{
			this.container.visible = true;
			showInfo();
		}
		
		private function onCloseButtonClick(event:MouseEvent):void
		{
			refreshTimer.stop();
			refreshTimer = null;
			
		/*	closeButton.removeEventListener(
				MouseEvent.CLICK, 
				onCloseButtonClick
			);*/
		
			container.removeChild(videoInfoOverlay);
			videoInfoOverlay = null;
		}
		
		private function onTimer(event:Event = null):void
		{
			// Adjust the Info overlay if it doesn't fit inside the vide. The content of the overlay will be scaled.
		/*	if (container.width < videoInfoOverlay.width || containerWidth != container.width)
			{				
				containerWidth = container.width;
				var oldWidth:Number = videoInfoOverlay.width;
				var newWidth:Number = Math.min(containerWidth - 10, videoInfoOverlayWidth);
				videoInfoOverlay.width = newWidth;
				videoInfoOverlay.height *= newWidth / oldWidth;
			}*/
			
			var videoInfo:String = "Build " + ExternalConfig.getInstance().version
			
			// Build a information text message. 
			var targetFP:String = "10.0";
			CONFIG::FLASH_10_1	
			{	
				targetFP = "10.2";
			}
			
			videoInfo += " for Flash Player "
				+ targetFP
				+ "\n";
			
			videoInfo += "Flash Player version:\t" + Capabilities.version;
			if (Capabilities.isDebugger)
			{
				videoInfo += " (debug)";
			}			
				
			// TODO: Move this to a 'Capabilities' kind of class
			var stageVideoSupport:Boolean = OSMFSettings.supportsStageVideo;
			videoInfo += "\nStage Video SUPPORT \t"+ stageVideoSupport+"\n";
			
			
			var videoSurface:VideoSurface = mediaPlayer.displayObject as VideoSurface;
			if (videoSurface)
			{
				var videoSurfaceInfo:VideoSurfaceInfo = videoSurface.info;
				var decodingMode:String = 
				videoInfo += "\nHardware Video Decoding:   \t"
					+ (videoSurfaceInfo.renderStatus == "accelerated" ? "Yes" : "No")
					+ "\n";	
				videoInfo += "Hardware Video Rendering:  \t";
				
				if (stageVideoSupport)
				{
					if (videoSurfaceInfo.stageVideoInUse)
					{
						videoInfo += "Yes (StageVideo " + videoSurfaceInfo.stageVideoInUseCount + "/" + videoSurfaceInfo.stageVideoCount + ")";
					}
					else
					{
						videoInfo += "No";
					}		
				}
				else
				{
					videoInfo += "Not available in this version of Flash Player. Flash Player 10.2 is required.";					
				}				
				videoInfo += "\n";
			}
			videoInfo += "\n";
			
			this._netStream = null;
			this._akamaiZStream = null;
			CONFIG::FLASH_10_1	
			{
				var netGroup:NetGroup = null;
			}
			var media:MediaElement = mediaPlayer.media;
			if (media && media.hasTrait(MediaTraitType.LOAD))
			{
				var loadTrait:NetStreamLoadTrait = media.getTrait(MediaTraitType.LOAD) as NetStreamLoadTrait;
				if (loadTrait)
				{
					var targetStream :* = loadTrait.netStream;
					
					if(targetStream is AkamaiZStreamWrapper){
						this._akamaiZStream = targetStream;
					}else{
						
							this._netStream = targetStream
						
					}
					CONFIG::FLASH_10_1	
					{
						netGroup = loadTrait.netGroup;
					}
					if (this._netStream || this._akamaiZStream)
					{
						
						var streamName:String = this.getStreamName()
						var streamBitRate:String = this.getStreamBitRate();
						var currentKB:String = this.getCurrentKB();
						var frameRate:String = this.getFrameRate();
						var droppedFrames:String = this.getDroppedFrames();
						var bufferLength:String = this.getBufferLength();
						var bufferTime:String = this.getBufferTime();
						var bufferTimeMax:String = this.getBufferTimeMax();
						
						videoInfo += "Current AUTO MBR:\t" 
							+ this.target.mediaPlayerSprite.mediaPlayer.autoDynamicStreamSwitch
							+"\n"
							+ "\t";
						videoInfo += "Current Video:\t" 
							+ "width: "+this.target.mediaPlayerSprite.mediaPlayer.mediaWidth
							+ "\theight: "+this.target.mediaPlayerSprite.mediaPlayer.mediaHeight
							+"\n"
							+ "\t";
						videoInfo += "Current Dynamic Stream Index:\t" 
							+ this.target.mediaPlayerSprite.mediaPlayer.currentDynamicStreamIndex.toString()
							+ " index of "+this.target.mediaPlayerSprite.mediaPlayer.maxAllowedDynamicStreamIndex.toString()+"\n"
							+ "\t";
						videoInfo += "Current Stream Name\t" 
							+ streamName
							+ "\n"
							+ "\t";
						videoInfo += "Current BitRate \t" 
							+ streamBitRate
							+ "\n"
							+ "\t";	
						videoInfo += "Current KB Per Second \t" 
							+ currentKB
							+ "\n"
							+ "\t";	
						videoInfo += "Frame rate:\t" 
							+ frameRate
							+ " fps\n"
							+ "\t";
						
						videoInfo += "Dropped frames:\t" 
							+ droppedFrames							
							+ "\n";
						
						videoInfo += "Buffer length / time / Time MAX:\t"
							+ bufferLength 
							+ " s"	
							+ " / " 
							+ bufferTime
							+ " s"
							+ " / "
							+bufferTimeMax
							+ " s"
							+ "\n";
						if(this._akamaiZStream){
							videoInfo += "Encrypted:\t" 
								+ this._akamaiZStream.encrypted					
								+ "\n";
							videoInfo += "Bandwidth:\t" 
								+ this._akamaiZStream.bandwidth					
								+ "\n";
							videoInfo += "Video Codec:\t" 
								+ this._akamaiZStream.videoCodec					
								+ "\n";
						}
					}
				}
			}
			
			videoInfo += "Memory usage:\t" 
				+ StrobeUtils.bytes2String(System.totalMemory)
				+ "\n";

			CONFIG::FLASH_10_1	
			{	
				if (this._netStream)
				{
					var multicastInfo:NetStreamMulticastInfo = this._netStream.multicastInfo;			
					if (netGroup)
					{
						videoInfo += "\nNeighbors (count/estimated):\t" 
							+ netGroup.neighborCount 
							+ " / " 
							+ netGroup.estimatedMemberCount.toFixed(2) 
							+ "\n";
					}
					if (multicastInfo)
					{
						
						videoInfo += "Download speed:\t" 
							+ StrobeUtils.bytesPerSecond2String(multicastInfo.receiveDataBytesPerSecond)
							+ " ( " 
							+ StrobeUtils.bytesPerSecond2ByteString(multicastInfo.receiveDataBytesPerSecond)
							+ " )\n";
						
						videoInfo += "Upload speed:\t" 
							+ StrobeUtils.bytesPerSecond2String(multicastInfo.sendDataBytesPerSecond) + 
							" ( " 
							+ StrobeUtils.bytesPerSecond2ByteString(multicastInfo.sendDataBytesPerSecond) 
							+ " )\n";
						
						
						videoInfo += "Total Bytes Pushed From/To Peers:\t" 
							+ StrobeUtils.bytes2String(multicastInfo.bytesPushedFromPeers) 
							+ " / " 
							+ StrobeUtils.bytes2String(multicastInfo.bytesPushedToPeers) 
							+ "\n";
						
						
						videoInfo += "Total Bytes Requested From Peers:\t" 
							+ StrobeUtils.bytes2String(multicastInfo.bytesRequestedFromPeers) 
							+ "\n";
						
						videoInfo += "Total Bytes Received From IP Multicast:\t" 
							+ StrobeUtils.bytes2String(multicastInfo.bytesReceivedFromIPMulticast) 
							+ "\n";
						
//						*bytesPushedFromPeers 
//						*bytesRequestedFromPeers 
//						bytesReceivedFromIPMulticast 
//						*bytesPushedToPeers
					}
					

					videoInfo += "\nStream state:\t"
						+ mediaPlayer.state 
						+ "\n";	
					videoInfo += "\nStream Stream Switching:\t"
						+ mediaPlayer.dynamicStreamSwitching 
						+ "\n";
					videoInfo += "\nStream Time:\t"
						+ mediaPlayer.currentTime
						+ "\n";	
					
				}	
			}
			
			textField.text = videoInfo;
			this._debugTextField.autoSize = TextFieldAutoSize.LEFT
			this._debugTextField.y= textField.y+textField.height+5;	
		}
		
		private function getBufferTimeMax():String
		{
			var bufferTimeMax:String;
			if(this._netStream){
				bufferTimeMax = this._netStream.bufferTimeMax.toFixed(2).toString();
			}else if(this._akamaiZStream){
				bufferTimeMax = this._akamaiZStream.bufferTimeMax.toFixed(2).toString();
			}
			return bufferTimeMax;
		}
		
		private function getBufferTime():String
		{
			var bufferTime:String;
			if(this._netStream){
				bufferTime = this._netStream.bufferTime.toFixed(2).toString();
			}else if(this._akamaiZStream){
				bufferTime = this._akamaiZStream.bufferTime.toFixed(2).toString();
			}
			return bufferTime;
		}
		
		private function getBufferLength():String
		{
			var bufferLength:String;
			if(this._netStream){
				bufferLength = this._netStream.bufferLength.toFixed(2).toString();
			}else if(this._akamaiZStream){
				bufferLength = this._akamaiZStream.bufferLength.toFixed(2).toString();
			}
			return bufferLength;
		}
		
		private function getDroppedFrames():String
		{
			var droppedFrames:String;
			if(this._netStream){
				droppedFrames = this._netStream.info.droppedFrames.toString();
			}else if(this._akamaiZStream){
				droppedFrames = this._akamaiZStream.info.droppedFrames.toString();
			}
			
			return droppedFrames;
		}
		
		private function getFrameRate():String
		{
			var frameRate:String;
			if(this._netStream){
				frameRate = this._netStream.currentFPS.toFixed(2).toString();
			}else if(this._akamaiZStream){
				frameRate = this._akamaiZStream.currentFPS.toFixed(2).toString();
			}
			return frameRate;
		}
		
		private function getCurrentKB():String
		{
			var currentKB:String
			if(this._netStream){
				currentKB = Number(this._netStream.info.currentBytesPerSecond/1000).toFixed(3).toString();
					
					
			}else if(this._akamaiZStream){
				currentKB = Number(this._akamaiZStream.info.currentBytesPerSecond/1000).toFixed(3).toString();
			}
			return currentKB;
		}
		
		private function getStreamBitRate():String
		{
			var streamBitRate:String;
			if(this._netStream){
				streamBitRate = this.mediaPlayer.getBitrateForDynamicStreamIndex(this.target.mediaPlayerSprite.mediaPlayer.currentDynamicStreamIndex).toString();
			}else if(this._akamaiZStream){
				var currentIndex:int = this._akamaiZStream.currentIndex;
				streamBitRate = this._akamaiZStream.manifest.media[currentIndex].bitrate.toString();
				
			}
			return streamBitRate;
		}
		
		private function getStreamName():String
		{
			var streamName:String;
			
				if(this.target.streamSMILParser.dsi!=null){
					streamName = this.target.streamSMILParser.dsi.getNameAt(this.target.mediaPlayerSprite.mediaPlayer.currentDynamicStreamIndex)
				}else if(this._akamaiZStream){
					streamName = this._akamaiZStream.manifest.id;
				}
			
			return streamName;
		}
	}
}