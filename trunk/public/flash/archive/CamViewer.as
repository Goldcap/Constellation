package
{
	import com.zeusprod.Log;
	
	import events.NetEvent;
	
	import flash.display.Sprite;
	import flash.events.Event;
	import flash.events.NetStatusEvent;
	import flash.media.Camera;
	import flash.media.Video;
	import flash.net.NetConnection;
	
	import net.FMSConnection;
	import net.FMSStream;
	
	import view.VideoScreen;
	
	[SWF (width=320, height=240)]
	public class CamViewer extends Sprite
	{
		private var fmsConn:FMSConnection;
		private var vid:Video;
		private var ns:FMSStream;
		
		private var cpCode:String;
		private var streamName:String;
		private var streamID:String;
		
		
		public function CamViewer()
		{
			cpCode = root.loaderInfo.parameters.cpCode;
			streamName = root.loaderInfo.parameters.streamName;
			streamID = root.loaderInfo.parameters.streamID;
			
			vid = new VideoScreen().vid;
			addChild( vid );
			
			fmsConn = new FMSConnection( cpCode, streamID, streamName );
			fmsConn.addEventListener( NetEvent.NET_CONNECTION_SUCCESSFUL, viewStream );
			fmsConn.connect( fmsConn.playLive );
			this.addEventListener(Event.ADDED_TO_STAGE, stageInit);
		}
		
		private function stageInit(evt:Event):void {
			//Log.init(stage);
			//Log.consoleLogging = true;
		}
		
		private function viewStream( event:NetEvent ):void
		{
			ns = new FMSStream( fmsConn.nc, vid );
			
			ns.playStream(root.loaderInfo.parameters.streamName, root.loaderInfo.parameters.streamID );
		}
	}
}