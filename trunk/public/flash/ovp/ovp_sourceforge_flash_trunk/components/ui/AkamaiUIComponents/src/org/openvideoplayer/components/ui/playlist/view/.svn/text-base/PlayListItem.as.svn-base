package org.openvideoplayer.components.ui.playlist.view
{
	
	import org.openvideoplayer.components.ui.playlist.event.PlayListEvent;
	
	import flash.display.Shape;
	import flash.display.Sprite;
	import flash.events.Event;
	import flash.events.MouseEvent;

	[ExcludeClass]
	
	/**
	 * @author Akamai Technologies, Inc 2011
	 */	
	public class PlayListItem extends Sprite
	{
		private var title:TitleLabel;
		private var desc:DescriptionLabel;
		private var thumbnail:Thumbnail;
		private var bg:Shape;
		private var selected:Shape;
		private var hover:Sprite;
		private var buttonWidth:int;
		private var buttonHeight:int = 90;
		private var _data:Object;
		
		/**
		 * 
		 * @param data
		 * @param buttonWidth
		 * 
		 */		
		public function PlayListItem(data:Object, buttonWidth:int) 
		{
			_data = data;
			this.buttonWidth = buttonWidth;
			addBackground();
			addHover();
			addSelected()
			addEventListeners();
			if(data.media.thumbnail.url)
			{
				addThumbnail();
			}
			else
			{
				addTitleText();
				addDescText();
			}
		}
		
		/**
		 * 
		 * @return 
		 * 
		 */		
		public function get data():Object
		{
			return _data;
		}
		
		/**
		 * 
		 * @param value
		 * 
		 */		
		public function toggleSelectedItem(value:Boolean):void
		{
			selected.visible = value;			
			hover.visible = false;
		}
		
		private function addThumbnail():void
		{
			this.addEventListener(Event.ENTER_FRAME, onImageLoad)
				
			thumbnail = new Thumbnail(_data.media.thumbnail, buttonHeight);
			thumbnail.x = 
			thumbnail.y = 5;
			addChild(thumbnail);
		}
	
		private function addEventListeners():void
		{
			this.addEventListener(MouseEvent.MOUSE_OVER, onMouseOver);
			this.addEventListener(MouseEvent.MOUSE_OUT, onMouseOut);
			this.addEventListener(MouseEvent.CLICK, onMouseClick);
			this.buttonMode = true;
		}
		
		private function onImageLoad(event:Event):void
		{
			if(thumbnail.width > 0)
			{
				this.removeEventListener(Event.ENTER_FRAME, onImageLoad);
				addTitleText();
				addDescText();
			}
		}
		
		private function onMouseOver(event:MouseEvent):void 
		{			
			hover.visible = true;
		}
		
		private function onMouseOut(event:MouseEvent):void 
		{
			hover.visible = false;
		}
		
		private function onMouseClick(event:MouseEvent):void 
		{			
			dispatchEvent(new PlayListEvent(PlayListEvent.MENU_ITEM_CHANGE, {payload:_data}));
		} 
		
		private function addTitleText():void 
		{
			title = new TitleLabel(_data.title, this.width - getThumbWidthOffest() - 5)
			title.x  = getThumbWidthOffest();
			title.y = 5;
			addChild(title);
		}
		
		private function addDescText():void 
		{
			desc = new DescriptionLabel(_data.description, this.width - getThumbWidthOffest() - 5);
			desc.x  = getThumbWidthOffest();
			desc.y = title.y +15; 
			addChild(desc);
		}
		
		private function getThumbWidthOffest():Number
		{
			return thumbnail.x+thumbnail.width+5;
		}
		
		private function addHover():void
		{
			hover = new Sprite();
			hover.addChild(getHighlighBox(Style.MENU_OVER_COLOR, 1));
			hover.visible = false;
			addChild(hover);
		}
		
		private function addSelected():void
		{
			selected = getHighlighBox(Style.MENU_SELECTED_COLOR, 1);
			selected.visible = false;
			addChild(selected);
		}
		
		private function addBackground():void 
		{
			bg = new Shape();
			bg.graphics.beginFill(0x000000, 1);
			bg.graphics.drawRect(0, 0, buttonWidth, buttonHeight);
			bg.graphics.endFill();
			addChild(bg);	
		}
		
		private function getHighlighBox(color:uint, alpha:Number):Shape
		{
			var s:Shape = new Shape();
			s.graphics.beginFill(color, alpha);
			s.graphics.drawRect(0, 0, buttonWidth, buttonHeight);
			s.graphics.endFill();
			return s;
		}

	}
}