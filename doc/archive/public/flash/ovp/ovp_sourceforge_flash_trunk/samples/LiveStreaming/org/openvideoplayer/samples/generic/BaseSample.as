// Copyright (c) 2009-2011, the Open Video Player authors. All rights reserved.
//
// Redistribution and use in source and binary forms, with or without 
// modification, are permitted provided that the following conditions are 
// met:
//
//    * Redistributions of source code must retain the above copyright 
//		notice, this list of conditions and the following disclaimer.
//    * Redistributions in binary form must reproduce the above 
//		copyright notice, this list of conditions and the following 
//		disclaimer in the documentation and/or other materials provided 
//		with the distribution.
//    * Neither the name of the openvideoplayer.org nor the names of its 
//		contributors may be used to endorse or promote products derived 
//		from this software without specific prior written permission.
//
// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS 
// "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT 
// LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR 
// A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT 
// OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, 
// SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT 
// LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, 
// DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY 
// THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT 
// (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE 
// OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
//
package org.openvideoplayer.samples.generic
{
	
	import flash.display.Sprite;
	import flash.display.StageAlign;
	import flash.display.StageDisplayState;
	import flash.display.StageScaleMode;
	import flash.events.ContextMenuEvent;
	import flash.events.Event;
	import flash.events.NetStatusEvent;
	import flash.media.Video;
	import flash.net.SharedObject;
	import flash.net.URLRequest;
	import flash.net.navigateToURL;
	import flash.ui.ContextMenu;
	import flash.ui.ContextMenuItem;
	
	import org.openvideoplayer.events.OvpEvent;
	import org.openvideoplayer.net.OvpConnection;
	import org.openvideoplayer.net.OvpNetStream;
	import org.openvideoplayer.samples.presenter.BaseControlBarPresenter;
	import org.openvideoplayer.samples.view.BaseVideoView;
	import org.openvideoplayer.utils.Utils;
	import com.zeusprod.Log;

	public class BaseSample extends Sprite
	{
		private static const URL_PREFIX:String = "../../srczip/";
		private static const SCALE_MODE_FIT:String = "fit";
		private static const SCALE_MODE_NATIVE:String = "native";
		
		protected var view:BaseVideoView;
		protected var netStream:OvpNetStream;
		protected var netConnection:OvpConnection;
		protected var presenter:BaseControlBarPresenter;
		private var viewSrcToken:String;
		private var currentScaleMode:String;
		private var sharedObject:SharedObject;
		private var _vidWidth:Number;
		private var _vidHeight:Number;
	
		public function BaseSample(viewSrcToken:String)
		{
            Log.traceMsg ("BaseSample Constructor", Log.LOG_TO_CONSOLE);
		    this.addEventListener(Event.ADDED_TO_STAGE, activate);
			this.viewSrcToken = viewSrcToken;
			sharedObject = SharedObject.getLocal("AkamaiControlBarProperties");
			addContextMenu();	
		}
		
		protected function setHeightAndWidth(vidWidth:Number, vidHeight:Number):void {
			_vidWidth = vidWidth;
			_vidHeight = vidHeight;
			//Log.traceMsg ("BaseSample.setHeightAndWidth _vidHeight/ _vidHeight: " + _vidWidth + " x " + _vidHeight, Log.LOG_TO_CONSOLE);
		}
		
		
		/**
		 * Call destroy() method for release of objects to garbage cleanup.
		 * 
		 */	
		public function destroy():void
		{
			netConnection.close();
			netStream.close();
			netConnection.removeEventListener(NetStatusEvent.NET_STATUS, onNetStatus);
			netStream.removeEventListener(OvpEvent.NETSTREAM_METADATA, onMetaData);
			netConnection = null;
			netStream = null;
			view = null;
		}
		
		/**
		 * 
		 * @param event
		 * 
		 */		
		protected function activate(event:Event):void
		{
			addView()
			initNetConnection();
			setStageProperties();
			this.removeEventListener(Event.ADDED_TO_STAGE, activate);	
		}
		
		/**
		 * 
		 * 
		 */		
		protected function addView():void
		{
			view = getView();
			addChild(view)
		}
		
		/**
		 * 
		 * 
		 */		
		protected function initNetConnection():void
		{
			netConnection = getNetConnection();
			netConnection.addEventListener(NetStatusEvent.NET_STATUS, onNetStatus);	
		}
		
		/**
		 * 
		 * 
		 */		
		protected function setStageProperties():void
		{
			this.stage.scaleMode = StageScaleMode.NO_SCALE;
			this.stage.align = StageAlign.TOP_LEFT;
		}
		
		/**
		 * 
		 * @return 
		 * 
		 */		
		protected function getView():BaseVideoView
		{
			return null
		}
		
		/**
		 * 
		 * @return 
		 * 
		 */		
		protected function getNetConnection():OvpConnection
		{			
			return null;
		}
		
		/**
		 * 
		 * @return 
		 * 
		 */		
		protected function getNetStream():OvpNetStream
		{			
			return null;
		}
		
		protected function onNetStatus(event:NetStatusEvent):void
		{
			Log.traceMsg ("BaseSample onNetStatus: " + event.info.code, Log.LOG_TO_CONSOLE);
		    switch (event.info.code) 
			{
				case "NetConnection.Connect.Rejected":
					trace("Rejected by server. Reason is "+event.info.description);
					break;
				case "NetConnection.Connect.Success":
					initNetStream();
					break;
			}
		}
		
		/**
		 * 
		 * 
		 */		
		protected function initNetStream():void
		{
			Log.traceMsg ("BaseSample initNetStream: " , Log.LOG_TO_CONSOLE);
            netStream = getNetStream();
			netStream.addEventListener(OvpEvent.NETSTREAM_METADATA, onMetaData);
			netStream.addEventListener(OvpEvent.DEBUG, onDebug);
			if(view.video)
			{
				Log.traceMsg ("BaseSample view.video.attatchNetStream: " , Log.LOG_TO_CONSOLE);
                view.video.attachNetStream(netStream);
			}
			presenter = getPresenter();
		}
		
		
		protected function getPresenter():BaseControlBarPresenter
		{
			return null;
		}
		
		/**
		 * 
		 * @param event
		 * 
		 */		
		protected function onMetaData(event:OvpEvent):void
		{
			/*if (event.data.width != view.video.width || event.data.height != view.video.height)
			{
				scaleVideo(event.data.width, event.data.height);
			}*/
			Utils.traceMetaData(event.data);
		}
		
		/**
		 * 
		 * @param w
		 * @param h
		 * 
		 */		
		protected function scaleVideo(w:Number, h:Number):void
		{
			if(currentScaleMode == null/* && !sharedObject.data.scaleMode*/)
			{
				if (w / h >= 4 / 3)
				{
					view.video.width = 480;
					view.video.height = 480 * h / w;
				}
				else
				{
					view.video.width = BaseVideoView.VIDEO_HEIGHT * w / h;
					view.video.height = BaseVideoView.VIDEO_HEIGHT;
				}
				view.video.x = (BaseVideoView.VIDEO_WIDTH - view.video.width) / 2;
				view.video.y = (BaseVideoView.VIDEO_HEIGHT - view.video.height) / 2;
			}
		}
		
		/*private function doScaleMode(type:String):void
		{
			sharedObject.data.scaleMode = type;
			currentScaleMode = type
			if (view.video is Video && stage.displayState != StageDisplayState.FULL_SCREEN)
			{
				switch (type)
				{
					case SCALE_MODE_FIT:
						if (view.video.videoWidth/view.video.videoHeight > view.width / BaseVideoView.VIDEO_HEIGHT)
						{
							view.video.width = BaseVideoView.VIDEO_WIDTH;
							view.video.height = BaseVideoView.VIDEO_WIDTH*view.video.videoHeight/view.video.videoWidth
						}
						else
						{
							view.video.height = BaseVideoView.VIDEO_HEIGHT;
							view.video.width = BaseVideoView.VIDEO_HEIGHT*view.video.videoWidth/view.video.videoHeight;
						} 
						view.video.smoothing = true;
						break;
					case SCALE_MODE_NATIVE:
						view.video.width = view.video.videoWidth;
						view.video.height = view.video.videoHeight;
						
						if (view.video.width > BaseVideoView.VIDEO_WIDTH || view.video.height > BaseVideoView.VIDEO_HEIGHT)
						{
							view.video.opaqueBackground = null;
						}
						view.video.smoothing = false;
						break;
				}
				
				view.video.x = Math.round((BaseVideoView.VIDEO_WIDTH - view.video.width)/2) 
				view.video.y = Math.round((BaseVideoView.VIDEO_HEIGHT - view.video.height)/2)
			}
		}*/
		
		/**
		 * 
		 * @param token
		 * 
		 */		
		protected function addContextMenu():void
		{
			contextMenu = new ContextMenu();	
			contextMenu.hideBuiltInItems();
			contextMenu.customItems.push(getContextItem("Download Source Code Zip", getSourceUrl, true));
			contextMenu.customItems.push(getContextItem("Toggle Debug Panel", onToggleDebugConsole, true));
			/*contextMenu.customItems.push(getContextItem("Toggle Scale Mode", onToggleScaleMode, true));*/
			
		}
		
		/*private function onToggleScaleMode(event:ContextMenuEvent):void
		{
			var mode:String = (currentScaleMode == null || currentScaleMode == SCALE_MODE_NATIVE) 
							? SCALE_MODE_FIT 
							: SCALE_MODE_NATIVE;
			
			doScaleMode(mode);
		}*/
		
		/**
		 * 
		 * @param event
		 * 
		 */		
		protected function onToggleDebugConsole(event:ContextMenuEvent):void
		{
			view.console.visible = !view.console.visible;
		}
		
		/**
		 * 
		 * @param event
		 * 
		 */		
		protected function onDebug(event:OvpEvent):void
		{	
			view.console.log(String(event.data));
		}
		
		/**
		 * 
		 * @private 
		 * 
		 */		
		private function getContextItem(title:String, handler:Function, seperate:Boolean = false):ContextMenuItem
		{
			var item:ContextMenuItem = new ContextMenuItem(title,  seperate);
			item.addEventListener( ContextMenuEvent.MENU_ITEM_SELECT , handler as Function);
			return item as ContextMenuItem;
		}
		
		private function getSourceUrl(event:ContextMenuEvent):void
		{
			navigateToURL(new URLRequest(URL_PREFIX+viewSrcToken+".zip"), "_blank");
		}
	}
}
