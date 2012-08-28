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
package com.llnw.events
{
	import flash.events.Event;
	
	/**
	 * This is a collection of event names used in Limelight Network applications. There is nothing special about this class.
	 * It directly extends flash.events.Event and adds no extra functionality.	 * 
	 *  
	 * @author LLNW
	 * 
	 * @see flash.events.Event
	 */	
	public class LLNWEvent extends Event
	{
		/**
		 * Requeue an item
		 */
		public static const REQUEUE:String = "requeue";
		
		/**
		 * Login complete
		 */
		public static const LOGIN:String = "login";
		
		/**
		 * Generic event telling the display to clear
		 */
		public static const CLEAR:String = "clear";
		
		/**
		 * Connection established
		 */
		public static const CONNECTION_ESTABLISHED:String = "connectionEstablished";
		
		/**
		 * Connection failed
		 */
		public static const CONNECTION_FAILED:String = "connectionFailed";
		
		/**
		 * Application shutdown; usually used in response to NetConnection.Connect.AppShutdown
		 */
		public static const APPLICATION_SHUTDOWN:String = "applicationShutdown";
		
		/**
		 * Connection rejected
		 */
		public static const CONNECTION_REJECTED:String = "rejected";
		
		/**
		 * Call failed
		 */
		 public static const CONNECTION_CALL_FAILED:String = "connectionCallFailed";
		
		/**
		 * Playback is starting
		 */
		public static const PLAY_STARTING:String = "playStarting";
		
		/**
		 * Playback has started
		 */
		public static const PLAY_STARTED:String = "playStarted";
		
		/**
		 * Playback has paused
		 */
		public static const PAUSED:String = "paused";
		
		/**
		 * Playback has unpaused
		 */
		public static const UNPAUSED:String = "unpaused";
		
		/**
		 * Playback was automatically paused once a netStream connection was established
		 */
		public static const PLAY_START_PAUSED:String = "playStartPaused";
		
		/**
		 * Generic event noting FCSubscribe or FCUnsubscribe was called.
		 */ 
		public static const SUBSCRIBE_INFO:String = "subscribeInfo";
		
		/**
		 * Stats were updated
		 */
		public static const STAT_UPDATE:String = "statUpdate";
		
		/**
		 * Stats are unavailable
		 */
		public static const STAT_UNAVAILABLE:String = "statUnavailable";
		
		/**
		 * Buffering is complete
		 */
		public static const BUFFERING_COMPLETE:String = "bufferingComplete";
		
		/**
		 * Stream is buffering
		 */
		public static const BUFFERING:String = "buffering";
		
		/**
		 * Stream has stopped
		 */
		public static const STOPPED:String = "Stopped";
		
		
		/**
		 * MP3 Playback has started
		 */
		public static const MP3_PLAY_STARTED:String = "mp3PlayStarted";
		
		/**
		 * MP3 Playback has stopped
		 */
		public static const MP3_STOPPED:String = "mp3Stopped";
		
		/**
		 * MP3 Playback is paused
		 */
		public static const MP3_PAUSED:String = "mp3Paused";
		
		/**
		 * Response to <code>NetStream.Play.StreamNotFound</code>
		 * @see http://livedocs.adobe.com/flash/9.0/ActionScriptLangRefV3/flash/events/NetStatusEvent.html#info
		 */
		public static const STREAM_NOT_FOUND:String = "streamNotFound";
		
		/**
		 * Loading a SMIL file failed
		 */
		public static const SMIL_LOAD_FAILED:String = "smilLoadFailed";
		
		/**
		 * Response to <code>NetStream.Play.FileStructureInvalid</code>
		 * @see http://livedocs.adobe.com/flash/9.0/ActionScriptLangRefV3/flash/events/NetStatusEvent.html#info
		 */
		public static const INVALID_FILE_STRUCTURE:String = "invalidFileStructure";
		
		/**
		 * Response to <code>NetStream.Play.NoSupportedTrackFound</code>
		 * @see http://livedocs.adobe.com/flash/9.0/ActionScriptLangRefV3/flash/events/NetStatusEvent.html#info
		 */
		public static const N0_SUPPORTED_TRACK_FOUND:String = "noSupportedTrackFound";
		
		/**
		 * API Error. The API is attempting to be called but it isn't fully initialized.
		 */
		public static const NOT_INITIALIZED:String = "notInitialized";
		
		/**
		 * Bandwidth check is complete
		 */
		public static const BANDWIDTH_COMPLETE:String = "bandwidthComplete";
		
		/**
		 * Response to <code>NetStream.Play.Complete</code>
		 */
		public static const NETSTREAM_PLAY_COMPLETE:String = "netstreamPlayComplete";
		
		/**
		 * Response to <code>NetStream.onMetaData</code>
		 * 
		 * @see http://livedocs.adobe.com/flash/9.0/ActionScriptLangRefV3/flash/net/NetStream.html
		 */
		public static const METADATA_RECEIVED:String = "metadataReceived";
		
		/**
		 * Response to an rtmp <code>close()</code> call from the server
		 */
		public static const SERVER_CLOSED_CONNECTION:String = "serverClosedConnection";
		
		/**
		 * Response to <code>onFCSubscribe(...)</code>.
		 */
		public static const FC_SUBSCRIBE:String = "fcSubscribe";
		
		/**
		 * Response to <code>onFCSubscribe</code> event <code>NetStream.Play.StreamNotFound</code>. 
		 */		
		public static const FC_SUBSCRIBE_STREAM_NOT_FOUND:String = "fcSubscribeStreamNotFound";
		
		/**
		 * Response to <code>onFCSubscrive</code> event <code>NetStream.Play.Start</code>.
		 */
		public static const FC_SUBSCRIBE_NETSTREAM_PLAY_START:String = "fcSubscribeNetstreamPlayStart";
		
		/**
		 * Response to <code>onFCUnsubscribe</code>.
		 */
		public static const FC_UNSUBSCRIBE:String = "fcUnsubscribe";
		/**
		 * Response to <code>onFI</code>.
		 */
		public static const ONFI:String = "onfi";
		
		/**
		 * Response to <code>onCuePoint</code>.
		 */
		public static const ON_CUE_POINT:String = "onCuePoint";
		
		/**
		 * Response to <code>onDVRSubscribe(...)</code>.
		 */
		public static const DVR_SUBSCRIBE:String = "dvrSubscribe";
		
		/**
		 * Response to <code>onDVRSubscribe</code> event <code>NetStream.Play.StreamNotFound</code>. 
		 */		
		public static const DVR_SUBSCRIBE_STREAM_NOT_FOUND:String = "dvrSubscribeStreamNotFound";
		
		/**
		 * Response to <code>onDVRSubscribe</code> event <code>NetStream.Play.Start</code>.
		 */
		public static const DVR_SUBSCRIBE_NETSTREAM_PLAY_START:String = "dvrSubscribeNetstreamPlayStart";
		
		/**
		 * Response to <code>onDVRUnsubscribe</code>.
		 */
		public static const DVR_UNSUBSCRIBE:String = "dvrUnsubscribe";
		
		
		
		/**
		 * Constructor
		 * @param type Event type
		 * @param bubbles Does this event bubble up the display tree?
		 * @param cancelable Is it cancelable?
		 * 
		 */		
		public function LLNWEvent(type:String, bubbles:Boolean=false, cancelable:Boolean=false){
			super(type, bubbles, cancelable);
		}
	}
}