/**
 * Alert class for AS3 in Flash, where mx.controls.Alert isn't available (as it would be in Flex)
 *  See:
 * 		http://fatal-exception.co.uk/downloads/classes/Alert.as
 * 		http://fatal-exception.co.uk/blog/?p=69
 * */

package com.dVyper.utils {
	//
	import flash.display.Bitmap;
	import flash.display.BitmapData;
	import flash.display.DisplayObject;
	import flash.display.GradientType;
	import flash.display.SimpleButton;
	import flash.display.Sprite;
	import flash.display.Stage;
	import flash.events.MouseEvent;
	import flash.filters.BitmapFilter;
	import flash.filters.BitmapFilterQuality;
	import flash.filters.BlurFilter;
	import flash.filters.DropShadowFilter;
	import flash.filters.GlowFilter;
	import flash.geom.Matrix;
	import flash.geom.Point;
	import flash.geom.Rectangle;
	import flash.text.TextField;
	import flash.text.TextFieldAutoSize;
	import flash.text.TextFormat;
	import flash.text.TextFormatAlign;
	//
	public class Alert {
		//
		private static var stage:Stage = null;
		private static var btnWidth:int = 75
		private static var btnHeight:int = 18;
		private static var minimumWidths:Array = new Array(150, 230, 310);
		//
		/*	ALERT DIALOG OPTIONS
		
		background:String		-	Type of background 
									>	"none" - invisible background
									>	"nonenotmodal" -  no background and make the area behind the Alert prompt focussable
									>	"simple -  simple colour background
									>	"blur" -  blurred background
									>   "image" - see imageClip
									"simple" style is used if background is not specified
		
		imageClip:DisplayObject	-	MovieClip image to incorporate in the dialog box.
		buttons:Array			-	An array (maximum of 3) containing the Strings of the buttons to be shown in the Alert prompt
		callBack:Function		-	The function to be called when a button on the Alert prompt has been clicked - returns the name of the button that was clicked
		colour:int				-	Main colour for the Alert
		promptAlpha:int			-	Alpha of the Alert prompt
		textColour:int			-	Colour of the text shown in the Alert dialog
		position:Point			-	Position of the Alert prompt
	
		*/
		public static function init(stageReference:Stage):void {
			stage = stageReference;
		}
		
		public static function show(Text:* = "Made by dVyper", ALERTOPTIONS:Object = null):void {
			if (stage == null) {
				trace("Alert class has not been initialised. Invoke init() with a stage reference!");
				return;
			}
			var alertOptions:AlertOptions = new AlertOptions(ALERTOPTIONS, Text);
			var myAlert:Sprite = new Sprite();
			myAlert.addChild(createBackground(alertOptions));
			myAlert.addChild(getPrompt(alertOptions));
			assignListeners(myAlert, alertOptions);
			stage.addChild(myAlert);
		}
		//
		private static function assignListeners(myAlert:Sprite, alertOptions:AlertOptions):void {
			var promptBackground:* = myAlert.getChildAt(1);
			var allButtons:Array = new Array();
			for (var n:int;n<alertOptions.buttons.length;n++) {
				var button:SimpleButton = promptBackground.getChildByName(alertOptions.buttons[n])
				button.addEventListener(MouseEvent.CLICK, myFunction);
				allButtons.push(button);
			}
			//	THIS IS DECLARED HERE SIMPLY SO I HAVE ACCESS TO alertOptions
			function myFunction(event:MouseEvent):void {
				for (var i:int;i<allButtons.length;i++) {
					allButtons[i].removeEventListener(MouseEvent.CLICK, myFunction);
				}
				closeAlert(myAlert);
				if (alertOptions.callback != null) alertOptions.callback(event.target.name);
			}
		}
		//
		private static function closeAlert(myAlert:Sprite):void {
			var promptBackground:* = myAlert.getChildAt(1);
			promptBackground.removeEventListener(MouseEvent.MOUSE_DOWN, doStartDrag);
			promptBackground.removeEventListener(MouseEvent.MOUSE_UP, doStopDrag);
			stage.removeChild(myAlert);
			myAlert = null;
		}
		//	Creates the background for the Alert
		private static function createBackground(alertOptions:AlertOptions):Sprite {
			var myBackground:Sprite = new Sprite();
			var colour:int = alertOptions.colour;
			if (alertOptions.background == null) {
				alertOptions.background = "simple";
			}
			switch (alertOptions.background) {
				case "blur" :
					var BackgroundBD:BitmapData = new BitmapData(stage.stageWidth, stage.stageHeight, true, 0xFF000000+colour);
					var stageBackground:BitmapData = new BitmapData(stage.stageWidth, stage.stageHeight);
					stageBackground.draw(stage);
					var rect:Rectangle = new Rectangle(0, 0, stage.stageWidth, stage.stageHeight);
					var point:Point = new Point(0, 0);
					var multiplier:uint = 120;
					BackgroundBD.merge(stageBackground, rect, point, multiplier, multiplier, multiplier, multiplier);
					BackgroundBD.applyFilter(BackgroundBD, rect, point, new BlurFilter(5, 5));
					var bitmap:Bitmap = new Bitmap(BackgroundBD);
					myBackground.addChild(bitmap);
					break;
				case "none" :
					myBackground.graphics.beginFill(colour, 0);	//	BACKGROUND IS STILL THERE BUT IS INVISIBLE
					myBackground.graphics.drawRect(0, 0, stage.stageWidth, stage.stageHeight);
					myBackground.graphics.endFill();
					break;
				case "nonenotmodal" :
					//	DRAW NO BACKGROUND AT ALL
					break;
				case "image":
					var imageClip:DisplayObject = alertOptions.imageClip;
					myBackground.addChild(imageClip);
					imageClip.x = stage.stageWidth/2 - imageClip.width/2;
					imageClip.y = stage.stageHeight/2 - imageClip.height/1.5;
					
					// Fall through to next case
				case "simple" :
					myBackground.graphics.beginFill(colour, 0.3);
					myBackground.graphics.drawRect(0, 0, stage.stageWidth, stage.stageHeight);
					myBackground.graphics.endFill();
					break;
			}
			return myBackground;
		}
		//	Returns an 'OK' button
		private static function createButton(buttonText:String, alertOptions:AlertOptions):SimpleButton {
			var colors:Array = new Array();
			var alphas:Array = new Array(1, 1);
			var ratios:Array = new Array(0, 255);
			var gradientMatrix:Matrix = new Matrix();
			gradientMatrix.createGradientBox(btnWidth, btnHeight, Math.PI/2, 0, 0);
			//
			var ellipseSize:int = 2;
			var btnUpState:Sprite = new Sprite();
			colors = [0xFFFFFF, alertOptions.colour];
			btnUpState.graphics.lineStyle(3, brightenColour(alertOptions.colour, -50));
			btnUpState.graphics.beginGradientFill(GradientType.LINEAR, colors, alphas, ratios, gradientMatrix);
			btnUpState.graphics.drawRoundRect(0, 0, btnWidth, btnHeight, ellipseSize, ellipseSize);
			btnUpState.addChild(createButtonTextField(buttonText, alertOptions));
			//
			var btnOverState:Sprite = new Sprite();
			colors = [0xFFFFFF, brightenColour(alertOptions.colour, 50)];
			btnOverState.graphics.lineStyle(1, brightenColour(alertOptions.colour, -50));
			btnOverState.graphics.beginGradientFill(GradientType.LINEAR, colors, alphas, ratios, gradientMatrix);
			btnOverState.graphics.drawRoundRect(0, 0, btnWidth, btnHeight, ellipseSize, ellipseSize);
			btnOverState.addChild(createButtonTextField(buttonText, alertOptions))
			//
			var btnDownState:Sprite = new Sprite();
			colors = [brightenColour(alertOptions.colour, -15), brightenColour(alertOptions.colour, 50)];
			btnDownState.graphics.lineStyle(1, brightenColour(alertOptions.colour, -50));
			btnDownState.graphics.beginGradientFill(GradientType.LINEAR, colors, alphas, ratios, gradientMatrix);
			btnDownState.graphics.drawRoundRect(0, 0, btnWidth, btnHeight, ellipseSize, ellipseSize);
			btnDownState.addChild(createButtonTextField(buttonText, alertOptions))
			//
			var myButton:SimpleButton = new SimpleButton(btnUpState, btnOverState, btnDownState, btnOverState);
			myButton.name = buttonText;
			return myButton;
		}
		//	returns a Sprite containing a prompt positioned in the middle of the stage
		private static function getPrompt(alertOptions:AlertOptions):Sprite {
			var actualPrompt:Sprite = createPrompt(alertOptions);
			actualPrompt.name = "actual_prompt";
			actualPrompt.addEventListener(MouseEvent.MOUSE_DOWN, doStartDrag);
			actualPrompt.addEventListener(MouseEvent.MOUSE_UP, doStopDrag);
			if (alertOptions.position) {
				actualPrompt.x = alertOptions.position.x;
				actualPrompt.y = alertOptions.position.y;
			} else {
				actualPrompt.x = (stage.stageWidth/2)-(actualPrompt.width/2);
				actualPrompt.y = (stage.stageHeight/2)-(actualPrompt.height/2);
			}
			return actualPrompt;
		}
		//
		private static function getBlurFilter():BitmapFilter {
            var blurX:Number = 100;
            var blurY:Number = 100;
            return new BlurFilter(blurX, blurY, BitmapFilterQuality.HIGH);
        }
		//
		private static function getDropShadowFilter(Colour:int):DropShadowFilter {
			var color:Number = 0x000000;
            var angle:Number = 90;
            var alpha:Number = 0.6;
            var blurX:Number = 12;
            var blurY:Number = 4;
            var distance:Number = 1;
            var strength:Number = 1;
            var inner:Boolean = false;
            var knockout:Boolean = false;
            var quality:Number = BitmapFilterQuality.LOW;
            return new DropShadowFilter(distance, angle, color, alpha, blurX, blurY, strength, quality, inner, knockout);
		}	
		//
		private static function getGlowFilter(Colour:int):GlowFilter {
			var color:Number = 0xFFFFFF;
            var alpha:Number = 0.8;
            var blurX:Number = 15;
            var blurY:Number = 15;
            var strength:Number = 0.7;
            var inner:Boolean = true;
            var knockout:Boolean = false;
            var quality:Number = BitmapFilterQuality.HIGH;
            return new GlowFilter(color, alpha, blurX, blurY, strength, quality, inner, knockout);
		}
		//	returns a sprite containing a prompt complete with a background, the specified text and an OK button
		private static function createPrompt(alertOptions:AlertOptions):Sprite {
			var promptBackground:Sprite = new Sprite();
			var textField:TextField = createTextField(alertOptions);
			var myWidth:int = textField.width+30;
			var myHeight:int = textField.height+65;
			if (myWidth < minimumWidths[alertOptions.buttons.length-1]) {
				myWidth = minimumWidths[alertOptions.buttons.length-1];
			}
			if (myHeight < 100) {
				myHeight = 100;
			}
			if (myHeight > stage.stageHeight) {
				myHeight = stage.stageHeight - 20;
				textField.autoSize = TextFieldAutoSize.NONE;
				textField.height = stage.stageHeight-40;
			}
			//	Create a background for the prompt
			var ellipseSize:int = 10;
			promptBackground.graphics.lineStyle(1);
			promptBackground.graphics.beginFill(alertOptions.colour);
			promptBackground.graphics.drawRoundRect(0, 0, myWidth, myHeight, ellipseSize, ellipseSize);
			promptBackground.graphics.endFill();
			//	Add the specified text to the prompt
			textField.x = (promptBackground.width/2)-(textField.width/2);
			textField.y = (promptBackground.height/2)-(textField.height/2)-10;
			//	ADD SPECIFIED BUTTONS TO THE PROMPT
			var alertButtons:Array = new Array();
			for (var n:int;n<alertOptions.buttons.length;n++) {
				alertButtons.push(createButton(alertOptions.buttons[n], alertOptions));
			}
			promptBackground.filters = [getGlowFilter(alertOptions.colour), getDropShadowFilter(alertOptions.colour)];
			promptBackground.alpha = alertOptions.promptAlpha;
			var actualPrompt:Sprite = new Sprite();
			actualPrompt.addChild(promptBackground);
			switch (alertButtons.length) {
				case 1 :
					alertButtons[0].x = (actualPrompt.width/2)-(btnWidth/2);
					break;
				case 2 :
					alertButtons[0].x = (actualPrompt.width/2)-btnWidth-10;
					alertButtons[1].x = alertButtons[0].x+btnWidth+15;
					break;
				case 3 :
					alertButtons[1].x = (actualPrompt.width/2)-(btnWidth/2)
					alertButtons[0].x = alertButtons[1].x-btnWidth-15;
					alertButtons[2].x = alertButtons[1].x+btnWidth+15;
					break;
			}
			actualPrompt.addChild(textField);
			for (var i:int;i<alertButtons.length;i++) {
				alertButtons[i].y = actualPrompt.height-35;
				actualPrompt.addChild(alertButtons[i]);
			}
			//
			return actualPrompt;
		}
		//
		private static function createButtonTextField(Text:String, alertOptions:AlertOptions):TextField {
			var myTextField:TextField = new TextField();
			myTextField.textColor = alertOptions.textColour;
			myTextField.selectable = false;
			myTextField.width = btnWidth;
			myTextField.height = btnHeight;
			var myTextFormat:TextFormat = new TextFormat();
			myTextFormat.align = TextFormatAlign.CENTER;
			myTextField.defaultTextFormat = myTextFormat;
			Text = "<b>"+Text+"</b>";
			myTextField.htmlText = '<font face="Verdana">'+Text+'</font>';
			myTextField.x = (btnWidth/2)-(myTextField.width/2);
			return myTextField;
		}
		private static function createTextField(alertOptions:AlertOptions):TextField {
			var Text:String = alertOptions.text;
			var myTextField:TextField = new TextField();
			myTextField.textColor = alertOptions.textColour;
			myTextField.multiline = true;
			myTextField.selectable = false;
			myTextField.autoSize = TextFieldAutoSize.CENTER;	
			myTextField.htmlText = '<font face="Verdana">'+Text+'</font>';
			myTextField.x = (btnWidth/2)-(myTextField.width/2);
			return myTextField;
		}
		//
		//	Helper functions
		//-----------------------------------------------------------------
		//
		//	returns a brighter version of the specified colour
		private static function brightenColour(colour:int, modifier:int):int {
			var hex:Array = hexToRGB(colour);
			var red:int = keepInBounds(hex[0]+modifier);
			var green:int = keepInBounds(hex[1]+modifier);
			var blue:int = keepInBounds(hex[2]+modifier);
			return RGBToHex(red, green, blue);
		}
		private static function doStartDrag(event:MouseEvent):void {
			if (event.target is Sprite) event.currentTarget.startDrag();
		}
		private static function doStopDrag(event:MouseEvent):void {
			if (event.target is Sprite) event.currentTarget.stopDrag();
		}
		private static function hexToRGB (hex:uint):Array {
			var Colours:Array = new Array(); 
			Colours.push(hex >> 16);
			var temp:uint = hex ^ Colours[0] << 16;
			Colours.push(temp >> 8);
			Colours.push(temp ^ Colours[1] << 8);
			return Colours;
		}
		private static function keepInBounds(number:int):int {
			if (number < 0)	number = 0;
			if (number > 255) number = 255;
			return number;
		}		
		private static function RGBToHex(uR:int, uG:int, uB:int):int {
			var uColor:uint;
			uColor =  (uR & 255) << 16;
			uColor += (uG & 255) << 8;
			uColor += (uB & 255);
			return uColor;
		}
	}
}

import flash.geom.Point;
import flash.display.DisplayObject;

internal class AlertOptions {
	
	//
	public var background:String;
	public var buttons:Array = new Array();
	public var callback:Function;
	public var colour:int;
	public var fadeIn:Boolean;
	public var position:Point;
	public var promptAlpha:Number;
	public var text:String;
	public var textColour:int = 0x000000;
	public var imageClip:DisplayObject;
	//
	public function AlertOptions(alertOptions:Object, Text:*):void {
		if (alertOptions != null) {
			if (alertOptions.background == null) {
				background = "simple";	
			} else {
				background = alertOptions.background;
			}
			if (alertOptions.buttons == null) {
				buttons = ["OK"];
			} else {
				if (alertOptions.buttons.length > 3) {
					buttons = alertOptions.buttons.slice(0, 2);
				} else {
					buttons = alertOptions.buttons;
				}
			}
			callback = alertOptions.callback; 
			if (alertOptions.colour == null) {
				colour = 0x4E7DB1;
			} else {
				colour = alertOptions.colour;
			}
			position = alertOptions.position;
			if (alertOptions.promptAlpha == null) {
				promptAlpha = 0.9;
			} else {
				promptAlpha = alertOptions.promptAlpha;
			}
			if (alertOptions.textColour != null) {
				textColour = alertOptions.textColour;
			} else {
				textColour = 0x000000;
			}
			if (alertOptions.imageClip != null) {
				imageClip = alertOptions.imageClip;
			}
		} else {
			background = "simple";
			buttons = ["OK"];
			colour = 0x4E7DB1;
			promptAlpha = 0.9;
			textColour = 0x000000;
		}
		text = Text.toString();
	}
}