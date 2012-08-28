/**
* LIMELIGHT NETWORKS INCORPORATED
* Copyright 2009 Limelight Networks Incorporated
* All Rights Reserved.
*
* NOTICE:  Limelight permits you to use, modify, and distribute this file in accordance with the
* terms of the Limelight end user license agreement accompanying it.  If you have received this file from a
* source other than Limelight, then your use, modification, or distribution of it requires the prior
* written permission of Limelight.
*/
package com.llnw.fms
{
	import com.llnw.events.LLNWEvent;
	
	//import com.llnw.utils.OutputLogger;
	//import com.llnw.valueObjects.BandwidthVO;
	import flash.events.Event;
	
	import flash.events.EventDispatcher;
	
	/**
	* Dispatched when bandwidth detection is complete
	*/	
	[Event(name="bandwidthComplete", type="com.llnw.events.LLNWEvent")]
	
	/**
	 * NetStream.Play.Complete
	 * @see http://livedocs.adobe.com/flash/9.0/ActionScriptLangRefV3/flash/events/NetStatusEvent.html
	 */ 
	[Event(name="netstreamPlayComplete", type="com.llnw.events.LLNWEvent")]
	
	/**
	 * @see http://livedocs.adobe.com/flash/9.0/ActionScriptLangRefV3/flash/net/NetStream.html#event:onMetaData
	 */
	[Event(name="metadataReceived", type="com.llnw.events.LLNWEvent")]
	
	/**
	 * Dispatched when the server calls <code>close()</code>.
	 */
	[Event(name="serverClosedConnection", type="com.llnw.events.LLNWEvent")]
	
	/**
	 * Dispatched from onFCSubscribe callback during a live stream connection attempt. 
	 * <p>This is not tied to a specific FCSubscribe event. It is called on every FCSubscribe event.</p>
	 */
	[Event(name="fcSubscribe", type="com.llnw.events.LLNWEvent")]
	
	/**
	 * Dispatched from onFCSubscribe callback during a live stream connection attempt. 
	 * This relates directly to the <code>NetStream.Play.StreamNotFound</code> event.
	 */
	[Event(name="fcSubscribeStreamNotFound", type="com.llnw.events.LLNWEvent")]
	
	/**
	 * Dispatched from onFCSubscribe callback during a live stream playback.
	 * This relates directly to the <code>NetStream.Play.Start</code> event.
	 */
	[Event(name="fcSubscribeNetstreamPlayStart", type="com.llnw.events.LLNWEvent")]
	
	/**
	 * Dispatched when onFCUnsubscribe method is called by the server
	 */ 
	[Event(name="fcUnsubscribe", type="com.llnw.events.LLNWEvent")]
	
	/**
	 * Dispatched if the class' onFI callback is called.
	 * 
	 */
	 [Event (name = "onFI", type = "com.llnw.events.LLNWEvent")]
	 
	 /**
	 * Dispatched if the class' onCuePoint callback is called.
	 * 
	 */
	 [Event (name = "onCuePoint", type = "com.llnw.events.LLNWEvent")]
	 
	
	/**
	 * This is the object on which callback methods will be invoked by a NetConnection and a NetStream. The functions are
	 * public but are not intended to be called directly. The documentation does not show the method signatures but the 
	 * functions are indeed public. Treat them as private though.
	 * 
	 * @example <listing version="3.0" >var connection:NetConnection = new NetConnection();
	 * connection.client = new ConnectionClient();</listing>
	 * 
	 * @example <listing version="3.0">var client:ConnectionClient = new ConnectionClient();
	 * client.addEventListener(LLNWEvent.BANDWIDTH_COMPLETE, handleBandwidthComplete);
	 * var connection:NetConnection = new NetConnection();
	 * connection.client = new ConnectionClient();
	 * 
	 * //...
	 * //elsewhere in your code
	 * //...
	 * 
	 * private function handleBandwidthComplete(event:LLNWEvent):void{
	 * 	trace(event);
	 * }</listing>
	 * 
	 * @author LLNW
	 * 
	 */
	public class ConnectionClient extends EventDispatcher
	{
		
		private var doDebug:Boolean = false;
		/**
		 * Called by the server to send bits back and forth for bandwidth checking
		 * 
		 * @private
		 */
		public function _onbwcheck(... rest):*{
			return rest[1];
		}
		
		/**
		 * Called by the server when the bandwidth check is complete.
		 * 
		 * @private
		 */
		public function _onbwdone(... rest):void{
			if(rest.length > 0){
				dispatchEvent(new LLNWEvent(LLNWEvent.BANDWIDTH_COMPLETE));
			}
		}
		public function onXMPData(... rest):*{
		
		}
		
		/**
		 * Called by the server when "checkbandwidth" is completed.
		 * 
		 * @private
		 */
		public function onBWDone(... rest):void{
			//trace("onBWDone", rest);
		}
		
		/**
		 * Called by the server for FCSubscribe events.
		 * 
		 * @private
		 */
		public function onFCSubscribe(event:Object):void {

			debug("FC Subscribe\t"+ event.code+ " : "+ event.description);
			if(event.code == "NetStream.Play.StreamNotFound"){
				dispatchEvent(new LLNWEvent(LLNWEvent.FC_SUBSCRIBE_STREAM_NOT_FOUND));
			}else if(event.code == "NetStream.Play.Start"){
				dispatchEvent(new LLNWEvent(LLNWEvent.FC_SUBSCRIBE_NETSTREAM_PLAY_START));
			}
		}
		
		/**
		 * Called by the server for DVRSubscribe
		 * 
		 * @param	event
		 */
		
		 public function onDVRSubscribe(event:Object):void{
			debug("DVR Subscribe\t"+ event.code+ " : "+ event.description);
			dispatchEvent(new LLNWEvent(LLNWEvent.DVR_SUBSCRIBE));
			if(event.code == "NetStream.Play.StreamNotFound"){
				dispatchEvent(new LLNWEvent(LLNWEvent.DVR_SUBSCRIBE_STREAM_NOT_FOUND));
			}else if(event.code == "NetStream.Play.Start"){
				dispatchEvent(new LLNWEvent(LLNWEvent.DVR_SUBSCRIBE_NETSTREAM_PLAY_START));
			}
		}
		 
		 
		/**
		 * Called by the server for FCUnsubscribe events.
		 * 
		 * @private
		 */
		public function onFCUnsubscribe(event:Object):void{
			debug("FC Unsubscribe\t"+ event.info.code+" : "+event.info.description);
			dispatchEvent(new LLNWEvent(LLNWEvent.FC_UNSUBSCRIBE));
		}
		/**
		 * Called by media when a cue point is present.
		 * 
		 */
		public function onCuePoint(event:Object):void {
			dispatchEvent(new LLNWEvent(LLNWEvent.ON_CUE_POINT))
		}
		
		/**
		 * Called by the server for stream close events.
		 * 
		 * @private
		 */
		public function close():void{
			debug("Connection closed by server");
			dispatchEvent(new LLNWEvent(LLNWEvent.SERVER_CLOSED_CONNECTION));
		}
		
		/**
		 * Called by the server for metadata events.
		 * 
		 * Access MetaData after event has been fired:
		 * 
		 * @private
		 */
		
		private var _metaDataObject:Object; 
		
		public function get metaDataObject():Object { return _metaDataObject; }
		public function onMetaData(info:Object):void {
		
			debug("---start--- Stream Meta Data");
			for(var i in info){
				debug( i + " : " + info[i]);
				
			}
			debug("----end---- Stream Meta Data");
			_metaDataObject 								= new Object()
			_metaDataObject.width 						= info.width;
			_metaDataObject.height 						= info.height;
			_metaDataObject.audioCodecID 				= info.audiocodecid;
			_metaDataObject.codecID 						= info.videocodecid;
			_metaDataObject.creationDate 				= new Date(Date.parse(info.creationdate));
			_metaDataObject.datarate 					= info.videodatarate;
			_metaDataObject.duration 					= info.duration;
			_metaDataObject.framerate 					= info.framerate;
			_metaDataObject.lastKeyframeTimestamp 		= info.lastkeyframetimestamp;
			_metaDataObject.keyframes 					= info.keyframes;

			dispatchEvent(new LLNWEvent(LLNWEvent.METADATA_RECEIVED));
		}
		
		public function set duration(value:String):void {
			_metaDataObject.duration = value;
		}
		
		public function onFI(info:Object):void {
			//onFI handler. 
		}
		
		
		/**
		 * Called by the server in reference to NetStream playback events
		 * 
		 * @private
		 */
		public function onPlayStatus(event:Object):void{
			debug("Play Status "+ event.code+" : "+ event.description);
			if(event.code == "NetStream.Play.Complete"){
				dispatchEvent(new LLNWEvent(LLNWEvent.NETSTREAM_PLAY_COMPLETE));
			}
		}
		

		
		public var debugMessage:String;
		private function debug(msg:String):void {
			if(doDebug){
				debugMessage = msg;
				dispatchEvent(new Event("debugEvent", true))
			}
		}
		
	}
}