//
// Copyright (c) 2009-2010, the Open Video Player authors. All rights reserved.
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
package {
	import flash.display.Sprite;
	
	import org.openvideoplayer.plugins.IOvpPlayer;
	import org.openvideoplayer.plugins.IOvpPlugIn;
	import org.openvideoplayer.plugins.OvpPlayerEvent;
	import org.openvideoplayer.version.OvpVersion;

	public class OvpTestPlugin extends Sprite implements IOvpPlugIn
	{
		private var _player:IOvpPlayer;
		
		// ----------------------------------------------------------
		//
		// The IOvpPlugin implementation
		//
		// ----------------------------------------------------------
		
		/**
		 * Should return the human readable name for the plug-in.
		 */
		public function get ovpPlugInName():String {
			return "OvpTestPlugin";
		}

		/**
		 * Should return a human readable description of the plug-in.
		 */
		public function get ovpPlugInDescription():String {
			return "A test plugin for OVP implementing the IOvpPlugin interface."
		}

		/**
		 * Should return the version of OVP the plug-in was compiled against.
		 */
		public function get ovpPlugInVersion():String {
			return OvpVersion.version;
		}
		
		/**
		 * @see org.openvideoplayer.plugins.IOvpPlugin
		 */
		public function get ovpPlugInCoreVersion():String {
			return OvpVersion.version;
		}		

		/**
		 * Tells the plug-in to turn on/off tracing.
		 */ 
		public function get ovpPlugInTracingOn():Boolean {
			return true;
		}
		
		public function set ovpPlugInTracingOn(value:Boolean):void {
			// We'll ignore this since the only value of this plugin is in it's trace statements ;)
		}

		// Methods
		/**
		 * Called from the player
		 */
		public function initOvpPlugIn(player:IOvpPlayer):void {
			_player = player;
			_player.addEventListener(OvpPlayerEvent.STATE_CHANGE, onPlayerEvent);
			_player.addEventListener(OvpPlayerEvent.SWITCH_ACKNOWLEDGED, onPlayerEvent);
			_player.addEventListener(OvpPlayerEvent.SWITCH_COMPLETE, onPlayerEvent);
			_player.addEventListener(OvpPlayerEvent.SWITCH_REQUESTED, onPlayerEvent);
			_player.addEventListener(OvpPlayerEvent.CONNECTION_CREATED, onConnectionCreated);
			_player.addEventListener(OvpPlayerEvent.NETSTREAM_CREATED, onNetStreamCreated);
			
			// Show flashvars that were passed to the player from html or javascript
			debug("flashvars = "+ _player.flashvars);
		}
		
		// ----------------------------------------------------------
		//
		// The internal methods
		//
		// ----------------------------------------------------------
		
		private function onPlayerEvent(e:OvpPlayerEvent):void {
			var msg:String = "event type="+e.type+" : ";
			
			switch (e.type) {
				case OvpPlayerEvent.SWITCH_REQUESTED:
					msg += "targetIndex="+e.data.targetIndex+", streamName="+e.data.streamName+", firstPlay="+e.data.firstPlay+", reason="+e.data.reason;
					break;
				case OvpPlayerEvent.SWITCH_COMPLETE:
					msg += "renderingIndex="e.data.renderingIndex+", renderingBitrate="+e.data.renderingBitrate;
					break;
				case OvpPlayerEvent.STATE_CHANGE:
					msg += "state="+e.data;
					break;
			}	
			debug(msg);
		}
		
		private function onConnectionCreated(e:OvpPlayerEvent):void {
			debug("onConnectionCreated() - e.data.ovpConnection = "+e.data.ovpConnection+", e.data.uri = "+e.data.uri+
					"e.data.arguments = "+e.data.arguments);
		}
		
		private function onNetStreamCreated(e:OvpPlayerEvent):void {
			debug("onNetStreamCreated() - e.data.netStream = "+e.data.netStream+
		 			", e.data.arguments = "+e.data.arguments+
					", e.data.arguments.name = "+e.data.arguments.name+
					", e.data.arguments.start = "+e.data.arguments.start+
					", e.data.arguments.len = "+e.data.arguments.len+
					", e.data.arguments.reset = "+e.data.arguments.reset+
					", e.data.dsi = "+e.data.dsi);
		}
		
		private function debug(...args):void {
			trace(">>> OvpTestPlugin : " + args);
		}
	}
}
