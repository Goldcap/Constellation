package events
{
	import flash.events.Event;

	public class NetEvent extends Event
	{
		public static const FC_PUBLISH_START:String = 'FCPublishStart';
		public static const NET_CONNECTION_SUCCESSFUL:String = 'netConnectionSuccessful';
		public static const NET_CONNECTION_FAILURE:String = 'netConnectionFailure';
		
		public function NetEvent( type:String )
		{
			super( type );
		}
		
		public override function clone():Event
		{
			return new NetEvent(type);
		}
	}
}