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
package view{
	import controller.PlaylistController;
	import ui.*;
	import model.Model;
	import org.openvideoplayer.rss.*
	import flash.geom.ColorTransform;
	import flash.geom.Matrix;
	import flash.geom.Rectangle;
	import flash.text.*;
	import flash.net.URLRequest;
	import flash.utils.Timer;
	import flash.display.*;
	import flash.events.*;
	import com.zeusprod.Log;
	
	/**
	 * Akamai Multi Player - generates the playlist view as a vertically scrolling list of playlist items. Each item consists of a thumbnail image, title
	 *  and description.
	 */
	public class PlaylistView extends MovieClip {
		private var _model:Model;
		private var _controller:PlaylistController;
		private var _background:MovieClip;
		private var _container:MovieClip;
		private var _detailPanel:MovieClip;
		private var _detailTitle:TextField;
		private var _detailDescription:TextField;
		private var _closeText:TextField;
		private var _closeButton:MovieClip
		private var _mask:MovieClip;
		private var _items:Array;
		private var _scrollDownButton:PlaylistScrollButton;
		private var _scrollUpButton:PlaylistScrollButton;
		private var _rec:Rectangle;
		private var _scrollTimer:Timer;
		private var _scrollDirection:String;
		private var _isVisible:Boolean;
		private var _detailTimer:Timer;
		private var _currentIndex:uint;
		public function PlaylistView(model:Model):void {
			_model = model;
			_model.addEventListener(_model.EVENT_RESIZE, resize);
			_model.addEventListener(_model.EVENT_PLAYLIST_ITEMS, playlistItemsHandler);
			_model.addEventListener(_model.EVENT_TOGGLE_PLAYLIST, togglePlaylistHandler);
			_model.addEventListener(_model.EVENT_SHOW_CONTROLS, showHandler);
			_model.addEventListener(_model.EVENT_HIDE_CONTROLS, hideHandler);
			_model.addEventListener(_model.EVENT_END_OF_ITEM, playNextHandler);
			
			_controller = new PlaylistController(_model, this);
			this.addEventListener(MouseEvent.MOUSE_WHEEL, mouseWheelHandler);
			createChildren();
		}
		private function createChildren():void {
			var _themeTransform:ColorTransform = new ColorTransform();
			_themeTransform.color = _model.themeColor;
			var textFormat:TextFormat=new TextFormat();
			textFormat.font = new AkamaiArialBold().fontName;
			_background = new MovieClip();
			addChild(_background);
			_scrollDownButton = new PlaylistScrollButton();
			_scrollDownButton.name = "down";
			disable(_scrollDownButton);
			_scrollDownButton.highlight.transform.colorTransform = _themeTransform;
			_scrollDownButton.highlight.alpha = 0;
			_scrollDownButton.addEventListener(MouseEvent.MOUSE_OVER,genericMouseOver);
			_scrollDownButton.addEventListener(MouseEvent.MOUSE_OUT,genericMouseOut);
			_scrollDownButton.addEventListener(MouseEvent.MOUSE_DOWN,genericMouseDown);
			_scrollDownButton.addEventListener(MouseEvent.MOUSE_UP,genericMouseUp);
			addChild(_scrollDownButton);
			_scrollUpButton = new PlaylistScrollButton();
			_scrollUpButton.name = "up";
			enable(_scrollUpButton);
			_scrollUpButton.highlight.transform.colorTransform = _themeTransform;
			_scrollUpButton.highlight.alpha = 0;
			_scrollUpButton.addEventListener(MouseEvent.MOUSE_OVER,genericMouseOver);
			_scrollUpButton.addEventListener(MouseEvent.MOUSE_OUT,genericMouseOut);
			_scrollUpButton.addEventListener(MouseEvent.MOUSE_DOWN,genericMouseDown);
			_scrollUpButton.addEventListener(MouseEvent.MOUSE_UP,genericMouseUp);
			_scrollUpButton.rotation = 180;
			addChild(_scrollUpButton);
			_container = new MovieClip();
			_container.y = 23;
			addChild(_container);
			_mask  = new MovieClip();
			addChild(_mask);
			_container.mask = _mask;
			_detailPanel = new MovieClip();
			_detailPanel.visible = false;
			addChild(_detailPanel);
			_detailTitle = new TextField();
			_detailTitle.width = 170;
			_detailTitle.height = 50;
			_detailTitle.embedFonts = true;
			textFormat.size = 16;
			textFormat.bold = true;
			textFormat.color = _model.themeColor;
			_detailTitle.defaultTextFormat = textFormat;
			_detailTitle.autoSize=TextFieldAutoSize.LEFT;
			_detailTitle.multiline = true;
			_detailTitle.wordWrap = true;
			_detailTitle.text = "hello world";
			_detailTitle.selectable=false;
			_detailTitle.antiAliasType = flash.text.AntiAliasType.ADVANCED;
			_detailPanel.addChild(_detailTitle);
			_detailDescription = new TextField();
			_detailDescription.width = _model.playlistWidth - 50;
			_detailDescription.height = 20;
			_detailDescription.embedFonts = true;
			textFormat.font = new AkamaiArial().fontName;
			textFormat.size = 11;
			textFormat.color = _model.fontColor;
			_detailDescription.defaultTextFormat = textFormat;
			_detailDescription.autoSize=TextFieldAutoSize.NONE;
			_detailDescription.multiline = true;
			_detailDescription.wordWrap = true;
			_detailDescription.text = "hello world aksjhd akshd kajs hdkajs hdkasd has";
			_detailDescription.selectable=false;
			_detailDescription.antiAliasType=flash.text.AntiAliasType.ADVANCED;
			_detailPanel.addChild(_detailDescription);
			_closeButton = new MovieClip();
			_closeButton.addEventListener(MouseEvent.MOUSE_DOWN,genericMouseDown);
			_closeButton.addEventListener(MouseEvent.MOUSE_UP, genericMouseUp);
			_closeButton.addEventListener(MouseEvent.CLICK, hideDetailPanel);
			_closeButton.buttonMode = true;
			_closeButton.useHandCursor = true;
			_detailPanel.addChild(_closeButton);
			_closeText = new TextField();
			_closeText.width = 50;
			_closeText.height = 20;
			_closeText.embedFonts = true;
			textFormat.font = new AkamaiArial().fontName;
			textFormat.size = 10;
			textFormat.color = _model.themeColor
			_closeText.defaultTextFormat = textFormat;
			_closeText.text = "CLOSE";
			_closeText.mouseEnabled = false;
			_closeText.antiAliasType = flash.text.AntiAliasType.ADVANCED;
			_closeButton.addChild(_closeText);
			_scrollTimer = new Timer(30);
			_scrollTimer.addEventListener(TimerEvent.TIMER, doAutoScroll);
			_detailTimer = new Timer(2000, 1);
			_detailTimer.addEventListener(TimerEvent.TIMER_COMPLETE, showDetailPanel);
			this.visible = _model.hasPlaylist;
			_model.playlistVisible = this.visible;
			_isVisible = this.visible;
			
		}
		private function enable(mc:MovieClip):void {
			mc.enabled = true;
			mc.alpha = 1
		}
		private function disable(mc:MovieClip):void {
			mc.enabled = false;
			mc.highlight.alpha = 0;
			mc.alpha = .5;
		}
		private function mouseWheelHandler(e:MouseEvent):void {
			if (e.delta > 0) {
				doScrollUp(new MouseEvent(MouseEvent.MOUSE_WHEEL));
			} else {
				doScrollDown(new MouseEvent(MouseEvent.MOUSE_WHEEL));
			}
		}
		private function doScrollUp(e:MouseEvent): void {
			var delta:Number = e == null ? 5:35;
			if (_container.y - delta <  _rec.y + 90 - _container.height) {
				_container.y = _rec.y + 90 - _container.height;
				disable(_scrollUpButton);
				
			} else {
				enable(_scrollDownButton);
				_container.y = _container.y - delta
			}
			
		}
		private function doScrollDown(e:MouseEvent): void {
			var delta:Number = e == null ? 5:35;
			if (_container.y + delta > _rec.y + 20) {
				_container.y = _rec.y + 20;
				disable(_scrollDownButton);
				
			} else {
				enable(_scrollUpButton);
				_container.y = _container.y + delta
			}
		}
		private function doAutoScroll(e:TimerEvent):void {
			_scrollDirection == "up" ? doScrollUp(null):doScrollDown(null);
		}
		private function genericMouseDown(e:MouseEvent):void {
			if (e.currentTarget.enabled) {
				_scrollDirection = e.currentTarget.name;
				_scrollTimer.start();
				e.currentTarget.x += 1;
				e.currentTarget.y += 1;
			}
		}
		private function genericMouseUp(e:MouseEvent):void {
			if (e.currentTarget.enabled) {
				_scrollTimer.stop();
				e.currentTarget.x -= 1;
				e.currentTarget.y -= 1;
			}
		}
		private function genericMouseOver(e:MouseEvent):void {
			if (e.currentTarget.enabled) {
				e.currentTarget.highlight.alpha = 1;
			}
		}
		private function genericMouseOut(e:MouseEvent):void {
			if (e.currentTarget.enabled) {
				e.currentTarget.highlight.alpha = 0;
			}
		}
		private function togglePlaylistHandler(e:Event):void {
			_isVisible = !_isVisible;
			_model.playlistVisible = _isVisible;
			this.visible = _isVisible;
		}
		private function playlistItemsHandler(e:Event):void {
			_items = _model.playlistItems;
			for (var i:uint = 0; i < _items.length ; i++) {
				var item:MovieClip = new MovieClip();
				item.name = "item" + i;
				item.selected = false;
				item.index = i;
				var frame:MovieClip = new MovieClip();
				frame.name = "frame";
				frame.addEventListener(MouseEvent.CLICK, playItem);
				frame.graphics.beginFill(0x666666);
				frame.graphics.drawRect(5, 5, 92, 70);
				frame.graphics.endFill();
				item.addChild(frame);
				var title:TextField = new TextField();
				title.width = 170;
				title.height = 20;
				title.embedFonts = true;
				var titleFormat:TextFormat = new TextFormat();
				titleFormat.bold = true;
				titleFormat.size = 11;
				titleFormat.color = _model.themeColor;
				titleFormat.font = new AkamaiArialBold().fontName;
				title.defaultTextFormat = titleFormat;
				title.autoSize=TextFieldAutoSize.LEFT;
				title.multiline = false;
				title.wordWrap = false;
				title.text = trim(ItemTO(_items[i]).title, 30);
				title.selectable=false;
				title.antiAliasType = flash.text.AntiAliasType.ADVANCED;
				title.x=105;
				title.y = 5;
				item.addChild(title);
				var description:TextField = new TextField();
				description.width = 170;
				description.height = 45;
				description.embedFonts = true;
				var descriptionFormat:TextFormat=new TextFormat();
				descriptionFormat.size = 11;
				descriptionFormat.color = _model.fontColor;
				descriptionFormat.font = new AkamaiArial().fontName;
				description.defaultTextFormat = descriptionFormat;
				description.autoSize = TextFieldAutoSize.NONE;
				description.multiline = true;
				description.wordWrap = true;
				description.text = trim(ItemTO(_items[i]).description,92);
				description.selectable=false;
				description.antiAliasType=flash.text.AntiAliasType.ADVANCED;
				description.x=105;
				description.y = 20;
				description.addEventListener(MouseEvent.MOUSE_OVER, startDescriptionShow);
				description.addEventListener(MouseEvent.MOUSE_OUT,stopDescriptionShow);
				item.addChild(description);
				item.x = 0;
				item.y = i * 75;
				item.useHandCursor = true;
				item.buttonMode = true;
				item.addEventListener(MouseEvent.MOUSE_OVER, highlightPlayItem);
				item.addEventListener(MouseEvent.MOUSE_OUT,lowlightPlayItem);
				item.addEventListener(MouseEvent.CLICK, playItem);
				
				if (ItemTO(_items[i]).media.thumbnail != null) {
					var loader:Loader = new Loader();
					loader.contentLoaderInfo.addEventListener(Event.COMPLETE,scaleThumb);
					loader.load(new URLRequest(ItemTO(_items[i]).media.thumbnail.url));
					loader.x = 7;
					loader.y= 7;
					item.addChild(loader);
				}
				_container.addChild(item);
			}
				playIndex(0);
		}
		private function highlightPlayItem(e:MouseEvent):void {
			if (!e.currentTarget.selected) {
				var themeTransform:ColorTransform = new ColorTransform();
				themeTransform.color = _model.themeColor;
				MovieClip(e.currentTarget).getChildByName("frame").transform.colorTransform = themeTransform;
			}
		}
		private function lowlightPlayItem(e:MouseEvent):void {
			if (!e.currentTarget.selected) {
				var colorTransform:ColorTransform = new ColorTransform();
				colorTransform.color = 0x666666;
				MovieClip(e.currentTarget).getChildByName("frame").transform.colorTransform = colorTransform;
			}
		}
		private function startDescriptionShow(e:MouseEvent):void {
			_detailTitle.text = ItemTO(_items[TextField(e.currentTarget).parent["index"]]).title;
			_detailDescription.text = ItemTO(_items[TextField(e.currentTarget).parent["index"]]).description;
			_detailTimer.reset();
			_detailTimer.start();
		}
		private function stopDescriptionShow(e:MouseEvent):void {
			_detailTimer.stop();
		}
		private function showDetailPanel(e:TimerEvent):void {
			_detailPanel.visible = true;
			resize(null);
		}
		private function hideDetailPanel(e:MouseEvent):void {
			_detailPanel.visible = false;
		}
		private function playItem(e:MouseEvent):void {
			playIndex(uint(e.currentTarget.index))
		}
		private function playIndex(i:uint):void {
			for (var w:uint = 0; w < _items.length; w ++ ) {
				_container.getChildByName("item" + w)["selected"] = (w == i);
				var colorTransform:ColorTransform = new ColorTransform();
				colorTransform.color = (w == i) ? 0xFFFFFF: 0x666666;
				MovieClip(_container.getChildByName("item" + w)).getChildByName("frame").transform.colorTransform = colorTransform;
			}
			_currentIndex = i;
			Log.traceMsg("playIndex " + i, Log.LOG_TO_CONSOLE);
			_controller.setNewSource(ItemTO(_items[i]).media.getContentAt(0).url);
		}
		
		private function playNextHandler(e:Event): void 
		{			
			if(stage.displayState == StageDisplayState.FULL_SCREEN)
			{
				stage.displayState = StageDisplayState.NORMAL;
			}			
			_currentIndex = _currentIndex + 1 >= _items.length ? 0: _currentIndex + 1;
			playIndex(_currentIndex);
		}
		
		private function scaleThumb(e:Event):void {
			var w:Number = e.target.width;
			var h:Number = e.target.height;
			if (w/h >= 88/66) {
				e.target.loader.width = 88;
				e.target.loader.height = 88*h/w;
			} else {
				e.target.loader.height = 66;
				e.target.loader.width = 66*w/h;
			}
			e.target.loader.x = 7 + (88-e.target.loader.width)/2;
			e.target.loader.y = 7 + (66-e.target.loader.height)/2;
		}
		private function trim(txt:String, length:Number): String {
			if (txt.length < length) {
				return txt;
			} else {
				txt = txt.slice(0, length);
				if (txt.indexOf(" ") == -1) {
					return txt.slice(0, length - 4) + " ...";
				} else {
					return txt.slice(0, txt.lastIndexOf(" ")) + " ...";
				}
			}
			
		}
		private function  showHandler(e:Event):void {
			if (!_model.isOverlay) {
				_isVisible = true;
			}
			if (_isVisible) {
				this.visible = true;
			}
		}
		private function  hideHandler(e:Event):void {
			this.visible = false;
		}
		public function resize(e:Event):void {
			_background.graphics.clear();
			_mask.graphics.clear();
			_detailPanel.graphics.clear();
			_scrollTimer.stop();
			_rec = new Rectangle();
			_rec.x = _model.width - 3 - _model.playlistWidth;
			_rec.y = 3;
			_rec.width = _model.playlistWidth;
			_rec.height = _model.isOverlay ? _model.height - 6 - _model.controlbarHeight:_model.height - 6;
			_background.graphics.beginFill(_model.isOverlay ?_model.controlbarOverlayColor:_model.backgroundColor, _model.isOverlay ? .6:1);
			_background.graphics.drawRect(_rec.x, _rec.y, _rec.width, _rec.height);
			_background.graphics.endFill();
			_detailPanel.graphics.beginFill(0x000000,.85);
			_detailPanel.graphics.drawRect(_rec.x, _rec.y, _rec.width, _rec.height);
			_detailPanel.graphics.endFill();
			_mask.graphics.beginFill(0xff0000);
			_mask.graphics.drawRect(_rec.x, _rec.y+25, _rec.width, _rec.height-50);
			_mask.graphics.endFill();
			_scrollDownButton.x = _rec.x + 5;
			_scrollDownButton.y = _rec.y + 5;
			_scrollUpButton.x = _rec.x + 5 + _scrollUpButton.width;
			_scrollUpButton.y = _rec.y + _rec.height - 5;
			_container.x = _rec.x + 5;
			_detailTitle.x = _rec.x + 25;
			_detailTitle.y = _rec.y + 25;
			_detailDescription.x = _rec.x + 25;
			_detailDescription.y = _detailTitle.y +_detailTitle.textHeight+ 10;
			_detailDescription.height = _rec.y + _rec.height -10 -_detailDescription.y;
			_closeButton.x = _rec.x + _model.playlistWidth - 48;
			_closeButton.y = _rec.y + 3;
	
		}
	}
}
