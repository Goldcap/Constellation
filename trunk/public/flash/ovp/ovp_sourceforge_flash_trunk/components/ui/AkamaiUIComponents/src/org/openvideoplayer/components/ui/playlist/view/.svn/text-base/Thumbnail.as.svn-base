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

	import flash.display.Bitmap;
	import flash.display.Loader;
	import flash.display.Sprite;
	import flash.events.Event;
	import flash.net.URLRequest;
	
	[ExcludeClass]
	/**
	 * @author Akamai Technologies, Inc 2011
	 */
	public class Thumbnail extends Sprite
	{
		private var thumbLoader:Loader
		private var urlReq:URLRequest
		private var url:String;
		private var data:Object;
		private var maxHieght:Number
		
		public function Thumbnail(data:Object, maxHieght:Number)
		{
			this.data = data;
			this.url = data.url;
			this.maxHieght = maxHieght;
			
			
			
			urlReq = new URLRequest(url);			
			thumbLoader = getLoader();			
			thumbLoader.load(urlReq);
		}
		
		private function getLoader():Loader
		{
			var loader:Loader = new Loader();
			loader.contentLoaderInfo.addEventListener(Event.COMPLETE, onLoadComplete, false, 0, true);			
			return loader;
		}

		public function onLoadComplete(event:Event):void
		{
			trace("Thumbnail onLoadComplete" )
			addAssetToStage(event.target.content);
		}
		
		private function addAssetToStage(bitmap:Bitmap):void
		{							
			addChild(scaleBitmap(bitmap));
		}
		
		private function scaleBitmap(bitmap:Bitmap):Bitmap
		{
			var originalHeight:Number = bitmap.height
			var paddedHeight:Number = maxHieght-10;			
			var scaleAmount:Number = paddedHeight/originalHeight
			bitmap.smoothing = true;			
			bitmap.pixelSnapping = "never";
			if(bitmap.height > paddedHeight)
			{
				bitmap.scaleX = bitmap.scaleY = scaleAmount					
			}
			return bitmap;
		}
	}
}