/**
* LIMELIGHT NETWORKS INCORPORATED
* Copyright 2009 Limelight Networks Incorporated
* All Rights Reserved.
*
* NOTICE:  Limelight permits you to use, modify, and distribute this file in accordance with the
* terms of the Limelight end user license agreement accompanying it.  If you have received this file from a
* source other than Limelight, then your use, modification, or distribution of it requires the prior
* written permission of Limelight.
* 
*/

package com.limelight.osmf.mbr
{
	import org.osmf.net.DynamicStreamingItem;
	import org.osmf.net.DynamicStreamingResource;
	
	public class ParseMBRStreams
	{
		public function ParseMBRStreams()
		{
		}

		public function parse(url:String):DynamicStreamingResource
		{
			trace("ParseMBRStreams.parse :: ")
			var dsResource:DynamicStreamingResource;
			var params:String = "";
			
			var a:int = url.indexOf("?");
			if( a>-1 )
			{
				params = url.substring(a,url.length);
				url = url.substring(0,a);
			}
			trace(" url = " + url)
			trace(" params = " + params)

			
			//Find the index of the slash
			//If useAppInstance is true fifth slash else forth slash
			var index:int = 1;
			var e:int = 0;
			
			for (e; e<5; e++)
			{
				trace("index = " + url.indexOf("/",index))
				if ( url.indexOf("/",index) > 0 )
				{
					index = url.indexOf("/",index)+1
				}
			}
			
			var streamsString:String = url.substring(index,url.length);
			var baseHostUrl:String = url.substring(0,index-1) + params;
			trace(" baseHostUrl = " + baseHostUrl)
			
			dsResource = new DynamicStreamingResource(baseHostUrl);
			
			var streamsArray:Array = streamsString.split("|");
			trace(" streamsArray = " + streamsArray)
			
			//Add stream paths and bitrates to the array collection
			var temp:Array = new Array();
			var dsItem:DynamicStreamingItem;
			var i:Number = 0;
			var l:Number = streamsArray.length;
			for (i; i<l; i++)
			{
				temp = streamsArray[i].split(",")
				if ( temp[2]!=null && temp[3]!=null )
				{
					// If a width and height have been passed in
					//trace(" temp[0] = " + temp[0])
					//trace(" temp[1] = " + temp[1])
					//trace(" temp[2] = " + temp[2])
					//trace(" temp[3] = " + temp[3])
					//dsItem = new DynamicStreamingItem(updateStreamName(temp[0]), temp[1], temp[2], temp[3]);
					dsItem = new DynamicStreamingItem(temp[0], temp[1], temp[2], temp[3]);
				}
				else
				{
					//trace(" temp[0] = " + temp[0])
					//trace(" temp[1] = " + temp[1])
					//dsItem = new DynamicStreamingItem(updateStreamName(temp[0]), temp[1]);
					dsItem = new DynamicStreamingItem(temp[0], temp[1]);
				}
				dsResource.streamItems.push(dsItem);
			}
			
			return dsResource;
			
		}
		
		
		/* private function updateStreamName(streamName:String):String
		{
			var extension:String = getFileExtension(streamName);

			if (extension == "flv")
			{
				streamName = streamName.substring( 0, streamName.indexOf(".") );
			}
			
			if (extension == "mp4" || extension == "f4v")
			{
				streamName = "mp4:" + streamName
			}
			
			return streamName;
		} */
		
		private function getFileExtension(url:String):String
		{
			if(url.indexOf("?") > -1){
				url = url.substr(0, url.indexOf("?"));
			}
			
			var matches:Array = url.match(/(\.\w+)$/);
			
			if (matches && matches.length > 0)
			{
				return matches[0].substr(1);
			}
			else
			{
				return "";
			}
        }
		
	}
}