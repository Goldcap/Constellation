package org.openvideoplayer.components.ui.posterframe
{
	import flash.display.Bitmap;
	import flash.display.Shape;
	import flash.events.MouseEvent;
	
	import org.openvideoplayer.components.ui.ComponentType;
	import org.openvideoplayer.components.ui.shared.event.ControlEvent;
	import org.openvideoplayer.components.ui.shared.utils.ShapeFactory;
	import org.openvideoplayer.components.ui.shared.view.base.BaseButton;
	import org.openvideoplayer.components.ui.shared.view.icons.PlayIcon;

	import com.zeusprod.Log;
	
	[ExcludeClass]
	
	/**
	 * @author Akamai Technologies, Inc 2011
	 */	
	public class PosterFramePlayButton extends BaseButton
	{
		
		private var buttonSize:uint;
		private var frameWidth:Number;
		private var frameHeight:Number;
		private var baseBGColor:Number;
		private var imagePath:String;
		private var playIcon:PlayIcon;
		private var buttonBackground:Shape;
		private var mainBackground:Shape;
		private var dimAlpha:Number = .5;
		
		
		/**
		 * @Constructor 
		 * @param frameWidth
		 * @param frameHeight
		 * @param buttonSize
		 * @param imagePath
		 * @param baseBGColor
		 * 
		 */		
		public function PosterFramePlayButton(frameWidth:Number, frameHeight:Number, 
											  buttonSize:uint, imagePath:String = "", 
											  baseBGColor:Number = 0x333333)
		{
			this.buttonSize = buttonSize;
			this.frameWidth = frameWidth;
			this.frameHeight = frameHeight;
			this.imagePath = imagePath;
			this.baseBGColor = baseBGColor;
			addPoster();
			addButtonBackground();
			addPlayIcon();
			super(ComponentType.POSTER_FRAME_BUTTON);
		}
		
		override protected function onMouseClick(event:MouseEvent):void
		{
			dispatchEvent(new ControlEvent(ControlEvent.PLAY));
			Log.traceMsg("Got here onMouseClick for poster button", Log.LOG_TO_CONSOLE);
		}
		
		override protected function onMouseOver(event:MouseEvent):void
		{
			playIcon.alpha = 1; 
			// FAKE the event to autostart
			//dispatchEvent(new ControlEvent(ControlEvent.PLAY));
			//Log.init(stage);
			//Log.traceMsg("Got here onMouseOver for poster button", Log.LOG_TO_CONSOLE);
		}
		
		override protected function onMouseOut(event:MouseEvent):void
		{
			playIcon.alpha = dimAlpha;			
		}
		
		private function addPoster():void
		{
			mainBackground = ShapeFactory.getRectShape(baseBGColor, 1);
			mainBackground.width = frameWidth;
			mainBackground.height = frameHeight
			mainBackground.x =  -(frameWidth/2);
			mainBackground.y =  -(frameHeight/2);
			addChild(mainBackground);	
		}

		private function addButtonBackground():void
		{
			buttonBackground = ShapeFactory.getRoundedRectShape(0x000000, buttonSize, buttonSize, 20, .5);
			buttonBackground.x =  -(buttonBackground.width/2);
			buttonBackground.y =  -(buttonBackground.height/2);
			addChild(buttonBackground);
		}
		
		private function addPlayIcon():void
		{
			playIcon = new PlayIcon(buttonSize-30);
			with(playIcon)
			{
				alpha = dimAlpha;
				x = buttonBackground.x + (buttonBackground.width/2);
				y = buttonBackground.y + (buttonBackground.height/2);
			}
			addChild(playIcon);
		}
		
		private function addEventListeners():void
		{
			this.addEventListener(MouseEvent.CLICK, onMouseClick, false, 500);
			this.addEventListener(MouseEvent.MOUSE_OVER, onMouseOver);
			this.addEventListener(MouseEvent.MOUSE_OUT, onMouseOut);
		}
	}
}