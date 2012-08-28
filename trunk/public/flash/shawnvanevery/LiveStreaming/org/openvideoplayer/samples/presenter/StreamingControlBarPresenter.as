package org.openvideoplayer.samples.presenter
{
	import flash.events.NetStatusEvent;
	
	import org.openvideoplayer.components.ui.shared.event.ControlEvent;
	import org.openvideoplayer.events.OvpEvent;
	import org.openvideoplayer.net.OvpConnection;
	import org.openvideoplayer.net.OvpNetStream;
	import org.openvideoplayer.samples.view.BaseVideoView;
	
	public class StreamingControlBarPresenter extends BaseControlBarPresenter
	{
		public function StreamingControlBarPresenter(view:BaseVideoView, netStream:OvpNetStream, netConnection:OvpConnection, streamName:String)
		{		
			filename = streamName;
			super(view, netStream, netConnection, streamName);
		}
		
		override protected function addNetStreamLiseners():void
		{
			super.addNetStreamLiseners();			
			netStream.addEventListener(NetStatusEvent.NET_STATUS, onNetStreamStatus);
		}			
		
		override protected function addControlBarListeners():void
		{
			super.addControlBarListeners();
			controlBar.addEventListener(ControlEvent.SEEK_CHANGE, onSeekChange);
		}
	
		override protected function onSeekComplete(event:ControlEvent):void
		{
			super.onSeekComplete(event);
			netStream.seek(scrubBar.getThumbPosition(this.streamLength));
		}
		
		protected function onSeekChange(event:ControlEvent):void
		{					
			setTimeDisplay();
		}
		
		override protected function onPlayClick(event:ControlEvent=null, clipStartTime:Number = NaN, clipDuration:Number = NaN):void
		{
			if(streamLength == 0 && isNaN(clipDuration))
			{
				netConnection.requestStreamLength(filename);
				netConnection.addEventListener(OvpEvent.STREAM_LENGTH, onStreamLength);
			}
			if (netStream.time == 0)
			{
				if(dynamicStreamItem && !isNaN(clipStartTime))
				{
					dynamicStreamItem.start = clipStartTime
					dynamicStreamItem.len = !isNaN(clipDuration) ? clipDuration : streamLength;
				}
				if(!isNaN(clipDuration))
				{
					streamLength = clipDuration;
				}
				netStream.play((dynamicStreamItem) ? dynamicStreamItem : filename, clipStartTime, clipDuration);
			}
			else
			{
				netStream.togglePause();
			}
			super.onPlayClickHelper();
		}
		
		protected function onStreamLength(event:OvpEvent):void
		{
			streamLength = event.data.streamLength;
			netConnection.removeEventListener(OvpEvent.STREAM_LENGTH, onStreamLength);
		}
		
		protected function onNetStreamStatus(event:NetStatusEvent):void
		{
			switch (event.info.code) 
			{	
				case "NetStream.Buffer.Flush" :
					break;				
				case "NetStream.Buffer.Full" :
					onBufferFull();
					break;				
				case "NetStream.Play.Start" :
					if(playPauseButton.currentState == ControlEvent.PLAY)
					{
						onBufferStart();
					}
					break;
			}
		}
	}
}