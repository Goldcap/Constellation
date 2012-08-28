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
	import flash.events.Event;
	
	import org.openvideoplayer.advertising.IMASTAdapter;
	import org.openvideoplayer.events.OvpError;
	import org.openvideoplayer.events.OvpEvent;
	import org.openvideoplayer.mainsail.adapters.OvpMASTAdapter;
	import org.openvideoplayer.mainsail.managers.TriggerManager;
	import org.openvideoplayer.mainsail.model.MASTTrigger;
	import org.openvideoplayer.mainsail.parser.MASTParser;
	import org.openvideoplayer.plugins.IOvpPlayer;
	import org.openvideoplayer.plugins.IOvpPlugIn;
	import org.openvideoplayer.plugins.OvpPlayerEvent;
	import org.openvideoplayer.version.OvpVersion;

	/**
	 * MainSail is the OVP MAST engine plugin. MAST, Media Abstract Sequencing Template,
	 * is a pre-standard by Akamai. MAST is a declarative language and markup specification 
	 * based on XML. It can reference ad content via a link to a VAST document, 
	 * the payload of the trigger. MAST is the "where" and "when" to show an ad. Though useful
	 * for advertising, MAST is not limited to this use.
	 * 
	 * @see http://openvideoplayer.sourceforge.net/mast/mast_specification.pdf
	 */
	public class MainSail extends Sprite implements IOvpPlugIn {

		private var _hostPlayer:IOvpPlayer;
		private var _tracingOn:Boolean;
		private var _mastParser:MASTParser;
		private var _mastAdapter:IMASTAdapter;
		private var _triggerManagers:Array;	// An array of TriggerManager objects, one for each trigger
		private const _PLUGIN_NAME_:String = "OVP MainSail";
		private const _PLUGIN_VERSION_:String = "v.1.0.0";
		private const _PLUGIN_DESC_:String = "The OVP MainSail plug-in knows how to parse a MAST document and look for payload handlers tied to triggers in the MAST document.";

		/**
		 * Constructor
		 */
		public function MainSail() {
			if (this.stage) {
				init();
			} else {
				addEventListener(Event.ADDED_TO_STAGE, init);
			}
		}

		private function init(e:Event = null):void {
			_tracingOn = false;
			_triggerManagers = new Array();
		}


		//-------------------------------------------------------------------
		//
		// IOvpPlugIn Implementation
		//
		//-------------------------------------------------------------------

		/**
		 * @inheritDoc
		 */
		public function get ovpPlugInName():String {
			return (_PLUGIN_NAME_ + " " + _PLUGIN_VERSION_);
		}

		/**
		 * @inheritDoc
		 */
		public function get ovpPlugInDescription():String {
			return _PLUGIN_DESC_;
		}

		/**
		 * @inheritDoc
		 */
		public function get ovpPlugInVersion():String {
			return _PLUGIN_VERSION_;
		}
		
		/**
		 * @see org.openvideoplayer.plugins.IOvpPlugin
		 */
		public function get ovpPlugInCoreVersion():String {
			return OvpVersion.version;
		}
		

		/**
		 * @inheritDoc
		 */
		public function get ovpPlugInTracingOn():Boolean {
			return _tracingOn;
		}

		/**
		 * @inheritDoc
		 */
		public function set ovpPlugInTracingOn(value:Boolean):void {
			_tracingOn = value;
		}

		/**
		 * @inheritDoc
		 */
		public function initOvpPlugIn(player:IOvpPlayer):void {
			pluginTrace("initOvpPlugIn called...");

			_hostPlayer = player;
			_mastAdapter = new OvpMASTAdapter(player);
			_mastAdapter.addEventListener(OvpPlayerEvent.DEBUG_MSG, onDebugMessage);

			if (_hostPlayer && _hostPlayer.flashvars) {
				// Ask the player for it's FlashVars so we can get the MAST document to load and parse
				var mastURL:String = _hostPlayer.flashvars.masturl;
				if (!mastURL || mastURL == "") {
					pluginTrace("No MAST document found in FlashVars, should look something like this: 'masturl=\"http://someurl/mymastdoc.xml\"");
					return;
				}

				_mastParser = new MASTParser(_tracingOn);
				_mastParser.addEventListener(OvpEvent.PARSED, mastParsedHandler);
				_mastParser.addEventListener(OvpEvent.ERROR, mastParserError);
				_mastParser.addEventListener(OvpPlayerEvent.DEBUG_MSG, onDebugMessage);
				pluginTrace("Parsing MAST document");
				_mastParser.load(mastURL);
			}
		}


		//-------------------------------------------------------------------
		//
		// Internal methods
		//
		//-------------------------------------------------------------------

		private function mastParsedHandler(e:OvpEvent):void {
			_mastParser.removeEventListener(OvpEvent.PARSED, mastParsedHandler);

			// Create a TriggerManager for each trigger and add it to the array of TriggerManagers
			for (var i:int = 0; i < _mastParser.mastObj.triggers.length; i++) {
				var triggerManager:TriggerManager = new TriggerManager(_mastAdapter);
				var trigger:MASTTrigger = _mastParser.mastObj.triggers[i];
				triggerManager.trigger = trigger;
				_triggerManagers.push(triggerManager);
			}
		}

		private function mastParserError(e:OvpEvent):void {
			_mastParser.removeEventListener(OvpEvent.ERROR, mastParserError);
			var err:OvpError = e.data as OvpError;
			pluginTrace("MAST Parser error: " + err.errorNumber + " : " + err.errorDescription);
		}


		//-------------------------------------------------------------------
		//
		// Helper Methods
		//
		//-------------------------------------------------------------------

		private function onDebugMessage(event:OvpPlayerEvent):void {
			pluginTrace(event.data as String);
		}

		private function pluginTrace(... arguments):void {
			if (arguments[0] && _tracingOn && _hostPlayer) {
				var debugmsg:String = "> OVP MainSail - " + arguments;
				trace(debugmsg);
				_hostPlayer.dispatchEvent(new OvpPlayerEvent(OvpPlayerEvent.DEBUG_MSG, debugmsg));
			} else
				return;
		}
	}
}
