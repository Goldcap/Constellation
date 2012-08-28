//
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
package org.openvideoplayer.components.ui.playlist.view
{
	import org.openvideoplayer.components.ui.playlist.event.PlayListEvent;
	import org.openvideoplayer.components.ui.playlist.view.vscroll.VScrollBar;
	
	import flash.display.Shape;
	import flash.display.Sprite;
	import flash.events.Event;
	
	[ExcludeClass]
	
	/**
	 * @author Akamai Technologies, Inc 2011
	 */
	
	public class PlayList extends Sprite
	{
		
		private var listContainer:Sprite = new Sprite();
		private var scrollbar:VScrollBar;
		private var myMask:Shape
		private var isScrolling:Boolean;
		private var background:Shape;
		private var dataProvider:Array;
		private var menuWidth:uint;
		private var menuHeight:uint;
		private var menuItemHeight:int = 50;
		private var playListItemMap:Vector.<PlayListItem>;
		
		private var _currentSelectedItem:PlayListItem;
		private var _currentSelectedIndex:uint;
		
		public function PlayList(dataProvider:Array=null, menuWidth:uint=200, menuHeight:uint=100)
		{
			this.menuWidth = menuWidth;
			this.menuHeight = menuHeight;
			this.dataProvider = dataProvider;
			this.addEventListener(Event.ADDED_TO_STAGE, activate);
			this.addEventListener(PlayListEvent.MENU_ITEM_CHANGE, onMenuChange);
		}

		/**
		 * 
		 * @param event
		 * 
		 */		
		public function onMenuChange(event:PlayListEvent):void
		{
			if(_currentSelectedItem)
			{
				changeMenuItem(_currentSelectedItem, false);
			}
			_currentSelectedIndex = getMenuItemIndex(event.data.payload);
			_currentSelectedItem = getMenuItem(_currentSelectedIndex);
			changeMenuItem(_currentSelectedItem, true);
		}
		
		/**
		 * 
		 * @param value
		 * 
		 */		
		public function set currentSelectedItem(value:PlayListItem):void
		{
			_currentSelectedItem = value;
		}
		
		/**
		 * 
		 * @return 
		 * 
		 */		
		public function get currentSelectedIndex():uint
		{
			return _currentSelectedIndex;
		}
		
		/**
		 * 
		 * @param value
		 * 
		 */		
		public function set currentSelectedIndex(value:uint):void
		{
			_currentSelectedIndex = value;
			onMenuChange(getBuiltPlayListEventObjectByIndex(value));
		}
		
		/**
		 * 
		 * @param value
		 * @return 
		 * 
		 */		
		public function getBuiltPlayListEventObjectByIndex(value:uint):PlayListEvent
		{
			return new PlayListEvent(PlayListEvent.MENU_ITEM_CHANGE, {payload:getMenuItem(value).data});
		}
		
		/**
		 * 
		 * @param dataProvider
		 * 
		 */		
		public function initMenu(dataProvider:Array):void
		{
			this.dataProvider = dataProvider;
			addMenu();
			addMask();
		}
		
		private function getMenuItemIndex(payload:Object):uint
		{
			for (var i:int = 0; i < playListItemMap.length; i++)
			{
				var item:PlayListItem = playListItemMap[i] as PlayListItem;  
				if(item.data.media.contentArray[0].url == payload.media.contentArray[0].url)
				{
					return i;
					break;
				}
				
			}
			return null;
		}
		
		private function getMenuItem(index:uint):PlayListItem
		{			
			return playListItemMap[index];			
		}
		
		private function changeMenuItem(item:PlayListItem, value:Boolean):void
		{
			item.toggleSelectedItem(value);
		}
		
		private function activate(event:Event):void 
		{
			removeEventListener(Event.ADDED_TO_STAGE, activate);
			addBackground();
			if(dataProvider)
			{
				initMenu(dataProvider);
			}
		}
		
		private function addBackground():void
		{
			background = getBackground();
			addChild(background);
		}

		private function addMask():void
		{
			myMask = getMask();
			addChild(myMask);
			listContainer.mask = myMask;
		}
			
		private function getBackground():Shape
		{
			var shape:Shape = new Shape();
			shape.graphics.beginFill(Style.APP_PANEL_BACKGROUND_COLOR, 1);
			shape.graphics.drawRect(0, 0, menuWidth, menuHeight);
			shape.graphics.endFill();
			return shape;
		}
		
		private function addMenu():void 
		{
			playListItemMap = new Vector.<PlayListItem>();
			listContainer.x = 5;
			isScrolling = addScrollBar();
			for each (var data:Object in dataProvider)
			{
				createMenuItem(data);
			}
			addChild(listContainer);
		}

		private function createMenuItem(data:Object):void 
		{
			var buttonWidth:int = (isScrolling) ? menuWidth-20 : menuWidth-10
			var playlistItem:PlayListItem = new PlayListItem(data, buttonWidth);
			with(playlistItem)
			{
				padding = 3;
				y = playListItemMap.length*(height+padding)+padding;
			}
			listContainer.addChild(playlistItem);
			playListItemMap.push(playlistItem);
		}
		
		private function getMask():Shape
		{
			var s:Shape = new Shape();
			s.graphics.beginFill(0xFFFFFF);
			s.graphics.drawRect(0, 0, menuWidth, menuHeight);
			s.graphics.endFill();
			return s;
		}
		
		private function addScrollBar():Boolean
		{
			var itemHeight:int = menuItemHeight;
			var contentHeight:Number = dataProvider.length * itemHeight+3;
			var pageSize:Number = menuHeight / itemHeight;
			
			if (contentHeight > menuHeight)
			{
				scrollbar = new VScrollBar(this, this.width-10, 1, onScroll);
				scrollbar.setThumbPercent(menuHeight / contentHeight);
				scrollbar.setSliderParams(0, Math.max(0, dataProvider.length - pageSize), listContainer.y / itemHeight);
				scrollbar.pageSize = pageSize;
				scrollbar.height = menuHeight;
				scrollbar.draw();
			}
			return (contentHeight > menuHeight)
		}
		
		private function onScroll(event:Event):void
		{
			listContainer.y = -scrollbar.value * menuItemHeight;
		}
	}
}