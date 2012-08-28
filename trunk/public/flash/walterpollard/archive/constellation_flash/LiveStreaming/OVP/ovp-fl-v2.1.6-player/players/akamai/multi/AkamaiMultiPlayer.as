//
// Copyright (c) 2009-2011, the Open Video Player authors. All rights reserved.
//
// Redistribution and use in source and binary forms, with or without 
// modification, are permitted provided that the following conditions are 
// met:
//
//    * Redistributions of source code must retain the above copyright 
//notice, this list of conditions and the following disclaimer.
//    * Redistributions in binary form must reproduce the above 
//copyright notice, this list of conditions and the following 
//disclaimer in the documentation and/or other materials provided 
//with the distribution.
//    * Neither the name of the openvideoplayer.org nor the names of its 
//contributors may be used to endorse or promote products derived 
//from this software without specific prior written permission.
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

	import flash.display.*;
	import flash.events.*;
	import flash.geom.Rectangle;
	import flash.net.URLRequest;
	import flash.ui.*;
	import flash.utils.Timer;
	import flash.external.ExternalInterface;
	import flash.system.Capabilities;
	import flash.net.navigateToURL;
	import flash.media.Video;
	import flash.system.SecurityDomain;
	import flash.system.ApplicationDomain;
	import flash.system.LoaderContext;

	import fl.core.UIComponent;

	// OVP specific imports
	import org.openvideoplayer.advertising.IVPAID;
	import org.openvideoplayer.plugins.*;
	import org.openvideoplayer.net.*;
	import org.openvideoplayer.events.*;
	import org.openvideoplayer.version.OvpVersion;

	import model.Model;
	import view.*;
	import advertising.AdManager;

	/**
	 * This class represents the document class of the AkamaiMultiPlayer. In order to render the views, the player depends upon graphic elements
	 * stored in library of the fla. <p/>
	 *
	 * The AkamaiMultiPlayer offers a robust AS3- based platform for playing back a wide variety of streaming and
	 * progressive media delivered by the Akamai platform. The player can handle and differentiate between the following source formats:
	 * <ol>
	 * <li> Stream OS media RSS playlists</li>
	 * <li> Stream OS metafiles, Type I, Type II, Type IV</li>
	 * <li> Stream OS progressive download links</li>
	 * <li> AMD streaming links, both ondemand and live/li>
	 * <li> AMD progressive links</li>
	 * <li> Dynamic Streaming packages (as SMIL files)</li>
	 * </ol>
	 * The player has the following features:
	 * <ul>
	 * <li>Dynamically scalable - all views are re-scaled and positioned each time the flash player is resized.</li>
	 * <li>Supports two layout modes - overlay (where the controls are overlaid over the video and hide themselves if you mouse-off the player) and
	 * side-by-side, where the controls are permanently on the screen and the playlist view is located to the right of the video content area. You may switch
	 * between layout modes during runtime.</li>
	 * <li>Three video rendering modes - fit, strech and native</li>
	 * <li>Standard controls - play/pause, volume, seek, current position, duration</li>
	 * <li>Fullscreen mode</li>
	 * <li>Supports link and embed data for re-distribution.</li>
	 * <li>Playlist button allows playlist visibility to be toggled</li>
	 * <li>Built-in debug screen to assist with debugging connection and play back problems</li>
	 * <li>Right-click context menu control over display mode, video scaling mode and debug panel</li>
	 * </ul>
	 * The player can be initialized via certain flashvars. These are detailed below. Note that only one - the src property - is required. The remainder will be initiliazed
	 * with default values. All values are passed as strings.
	 * <ul>
	 * <li>src - the source content which the player is expected to play. This must be a valid and well structured reference to AMD or Stream OS content, or a HTTP link to
	 * progressive content on any web server. Valid formats include:
	 * <ul>
	 * <li>http://products.edgeboss.net/flash/products/mediaframework/fms/0223_quikpro_highlights_700.flv?xmlvers=1</li>
	 * <li>http://products.edgeboss.net/download/products/mediaframework/fms/0223_quikpro_lgwaveoftheday_web_700.flv</li>
	 * <li>http://products.edgeboss.net/flash-live/products/40504/500_products_productsdemo_080602.flv</li>
	 * <li>rtmp://cp34973.live.edgefcs.net/live/Flash_live_bench_mb&#64;3725</li>
	 * <li>rtmp://cp27886.edgefcs.net/ondemand/14808/nocc_small307K.flv</li>
	 * <li>rtmp://cp39443.live.edgefcs.net/live/mystream&#64;s34</li>
	 * <li>rtmp://cp14808.edgefcs.net/ondemand/mp3:14808/nocc_small.mp3?a=1&b=2</li>
	 * <li>http://metadata.streamos.com/adobe/sample_feed/xml</li>
	 * <li>http://rss.streamos.com/streamos/rss/genfeed.php?feedid=1453&groupname=openvideoplayer</li>
	 * <li>http://sessions.adobe.com/360FlexSJ2008/feed.xml</li>
	 * <li>my-stub-playlist.xml</li>
	 * </ul>
	 * </li>
	 * <li>mode - the layout mode. Pass in "overlay" to specify that the player starts in overlay mode or "sidebyside" for the side-by-side mode.
	 * Default is "sidebyside".</li>
	 * <li>scaleMode - the scaling mode for the video, one of four possible values:
	 * <ul>
	 * <li>"fit" - [Default] the video is scaled as large as possible to fit within the confines of the player while still preserving its native aspect ratio.</li>
	 * <li>"stretch" - the video is is stretched to fit exactly within the confines of the player. Native aspect ratio is not preserved.</li>
	 * <li>"native" - the video is scaled to the native size it was encoded at. Note that this size may be larger than the player that is trying to render the video,
	 * in which case the video will be centered within the available player space. </li>
	 * <li>"nativeorsmaller" - the video will be scaled to its native size unless that is larger than the player in which case "fit" scaling will be invoked.</li>
	 * </ul>
	 * <li>frameColor - the HEX value for the frame color, for example "FF0000". Do not prepend with "0x" or "#". Default is "333333"</li>
	 * <li>fontColor - the HEX value for the control bar font color, for example "FF0000". Do not prepend with "0x" or "#". Default is "CCCCCC"</li>
	 * <li>themeColor - the HEX value for the theme color, for example "FF0000". The theme color is used in multiple locations throughout the player,
	 * including the button mouse-over highlights, scrub bar shading. volume control shading and playlist title font color. Do not prepend with "0x" or "#".
	 * Default is "0395D3"</li>
	 * <li>autostart - if set to "true", video starts playing the moment the player is loaded. if set to "false", the player will render the first keyframe in the video
	 * to create a splash screen and then stop. Default is "true".</li>
	 * <li>link - the URL which the user can use to link to a mounted instance of the player. This parameter must be escaped (url-encoded) or else it will mask other flashvar
	 * attributes. Note that the button to surface the link/embded panel will only be visible if either the link or embed parameter has a non empty-string value.
	 * Default is empty-string.</li>
	 * <li>embed - the URL which the user can use to embed the player. This parameter must be escaped (url-encoded) or else it will mask other flashvar
	 * attributes. Note that the button to surface the link/embded panel will only be visible if either the link or embed parameter has a non empty-string value.
	 * Default is empty-string.</li>
	 * </ul>
	 */

	/**
	 * Dispatched when the user clicks the fullscreen toggle button
	 */
	[Event(name = "toggleFullscreen",type = "flash.events.Event")]

	/**
	 * Dispatched when the underlying video stream begins playing.
	 */
	[Event(name = "playStart",type = "flash.events.Event")]

	/**
	 * Dispatched when the underlying video stream finishes playing.
	 */
	[Event(name = "endOfItem",type = "flash.events.Event")]

	/**
	 * Dispatched when this player isresized
	 */
	[Event(name = "resize",type = "flash.events.Event")]

	/**
	 * Dispatched when the volume changes
	 */
	[Event(name = "volumeChanged",type = "flash.events.Event")]


	[Event(name = "cuepoint",type = "org.openvideoplayer.plugins.OvpPlayerEvent")]

	/**
	 * Dispatched on error
	 */
	[Event(name = "error",type = "org.openvideoplayer.plugins.OvpPlayerEvent")]

	/**
	 * Dispatched when the state changes
	 */
	[Event(name = "statechange",type = "org.openvideoplayer.plugins.OvpPlayerEvent")]

	public class AkamaiMultiPlayer extends MovieClip implements IOvpPlayer {

		private var _model:Model;
		private var _playlist:PlaylistView;
		private var _controlbar:ControlbarView;
		private var _background:BackgroundView;
		private var _videoView:VideoView;
		private var _video:Video;
		private var _shareEmbed:ShareEmbedView;
		private var _debugPanel:DebugPanelView;
		private var _errorDisplay:ErrorDisplayView;
		private var _adMC:MovieClip;
		private var _contextMenu:ContextMenu;
		private var _timer:Timer;
		private var _lastWidth:Number;
		private var _lastHeight:Number;
		private var _src:String;
		private var _flashvars:Object;
		private var _adManager:AdManager;
		
		private var _advertisingMode:Boolean;
		private var _cuePointMgr:OvpCuePointManager;
		private var _filename:String;
		private var _linearAdMC:MovieClip;
		private var _metadata:Object;
		private var _plugin_container:UIComponent;
		private var _pluginFiles:Array;
		private var _pluginsLoaded:int;
		private var _plugins:Array;
		private var _pluginsStr:String;
		private var _state:String;
		private var _videoHolder:UIComponent;
		
		/**
		 * Constructor
		 * @paramstarting width of the player
		 * @paramstarting height of the player
		 * @paramflashvars - the loaderInfo.parameters object passed in from the HTML wrapper
		 */
		public function AkamaiMultiPlayer( width : Number = 774 , height : Number = 473 , flashvars : Object = null ):void 
		{			

			_flashvars = flashvars;
			init( _flashvars == null ? new Object() : _flashvars , width , height );
			createChildren();
			resize( null );

			// Don't load try to load the plugins if there are no FlashVars
			if (_flashvars && _flashvars.plugins) {
				loadPlugins();
			}
			else {
				_model.start();
			}
			this.addEventListener(Event.ADDED_TO_STAGE, activate);
		}

		private function activate(event:Event):void
		{
			this.removeEventListener(Event.ADDED_TO_STAGE, activate);
			_model.stage = this.stage;	
		}		

		public function enableControls():void {
			_model.enableControls();
		}

		public function disableControls():void {
			_model.disableControls();
		}

		public function getAdMovieClip():MovieClip {
			return _adMC;
		}

		public function get volume():Number {
			return _model.volume;
		}

		public function getAdRectangle():Rectangle {
			return new Rectangle( 3 , 3 , _model.availableVideoWidth , _model.availableVideoHeight );
		}

		public function set adManager( _value : AdManager ):void {
			_adManager = _value;
			_adManager.addEventListener( "adStart" , adStartHandler );
			_adManager.addEventListener( "adEnd" , adEndHandler );

			if (_adManager.cuePointManager) {
				_model.cuePointManager = _adManager.cuePointManager;
			}
		}

		public function get video():Video {
			return _video;
		}

		public function get duration():Number {
			return _model.streamLength;
		}

		public function get position():int {
			if (_model.time) {
				return _model.time;
			}
			return 0;
		}

		public function setNewSource( src : String ):void {
			debug( "Setting new source to " + src );
			_model.src = src;
		}
		
		public function startPlayback():void {
			resize(null);
			_model.start();
		}

		public function stopPlayback():void {
			_model.stopPlayback();
		}
		
		public function pausePlayback():void {
			_model.pause();
		}
		
		public function resumePlayback():void {
			_model.play();
		}
				
		public function debug(...args) {
			var msg:String = args[0];
			_model.debug( msg );
		}

		private function init( flashvars : Object , width : Number , height : Number ):void {
			_model = new Model(flashvars);
			
			_model.addEventListener( _model.EVENT_LOAD_UI , loadUIhandler );
			_model.addEventListener( _model.EVENT_TOGGLE_FULLSCREEN , toggleFullscreenHandler );
			_model.addEventListener( _model.EVENT_RESIZE , resizeHandler );
			_model.addEventListener( _model.EVENT_PLAY_START , playStartHandler );
			_model.addEventListener( _model.EVENT_VOLUME_CHANGE , volumeChangeHandler );
			_model.addEventListener( _model.EVENT_END_OF_ITEM , endOfItemHandler );
			_model.addEventListener( _model.EVENT_BUFFER_FULL , bufferFullHandler );
			_model.addEventListener( _model.EVENT_PAUSE, pauseHandler);
			_model.addEventListener( _model.EVENT_PLAY, playHandler);
			_model.addEventListener( _model.EVENT_STOP_PLAYBACK, stopPlaybackHandler);
			_model.addEventListener( _model.EVENT_OVPCONNECTION_CREATED , connectionCreatedHandler );
			_model.addEventListener( _model.EVENT_OVPNETSTREAM_CREATED , netStreamCreatedHandler );
			_model.addEventListener( OvpEvent.NETSTREAM_CUEPOINT , cuePointHandler );
			_model.addEventListener( OvpEvent.SWITCH_REQUESTED , switchRequestedHandler );
			_model.addEventListener( OvpEvent.SWITCH_ACKNOWLEDGED , switchAcknowledgedHandler );
			_model.addEventListener( OvpEvent.SWITCH_COMPLETE , switchCompleteHandler );

			this.addEventListener( MouseEvent.MOUSE_MOVE , mouseMoveHandler );
			this.addEventListener( MouseEvent.ROLL_OUT , leaveStageHandler );

			_lastWidth = width;
			_lastHeight = height;

			_timer = new Timer(5000,1);
			_timer.addEventListener( TimerEvent.TIMER_COMPLETE , leaveStageHandler );
			//btnStopAd.useHandCursor = true;
			//btnStopAd.buttonMode = true;
			_cuePointMgr = new OvpCuePointManager();
			_pluginFiles = new Array();
			_plugins = new Array();
			_state = "";

			if (ExternalInterface.available) {
				try {
					ExternalInterface.addCallback( "setNewSource" , setNewSource );
				} catch (e:Error) {
					// don't notify the user since ExternalInterface is not necessary for standard operation;
				}
			}
		}

		private function createChildren():void {
			_errorDisplay = new ErrorDisplayView(_model);
			addChild( _errorDisplay );

			if (ExternalInterface.available) {
				_model.debug( "External Interface available = " + ExternalInterface.available );
				flash.external.ExternalInterface.call( "isReady" );
			}
		}


		//-------------------------------------------------------------------
		//
		// Load plug-ins specified in the FlashVars
		//
		//-------------------------------------------------------------------

		private function loadPlugins():void {
			_pluginsStr = _flashvars.plugins;
			
			if (!_pluginsStr) {
				return;
			}
			
			_pluginFiles = _pluginsStr.split(",");

			for (var i : int = 0; i < _pluginFiles.length; i++) {
				var url:String = _pluginFiles[i];

				if (url.search(/.swf$/i) == -1) {
					url += ".swf";
				}

				var loader : Loader = new Loader();
				var req:URLRequest=new URLRequest(url);
				var context : LoaderContext = new LoaderContext();
				
				// If the plugin SWF is being loaded from a different domain, and
				// a cross-domain policy file exists, the Flash Player will allow the player
				// and plugin to communicate.
				context.securityDomain = SecurityDomain.currentDomain;

				// We have to load these into the same Application Domain so
				// the plug-ins can call methods here and we 
				// can listen for events fired by plug-ins.
				context.applicationDomain = ApplicationDomain.currentDomain;
				
				loader.contentLoaderInfo.addEventListener( Event.COMPLETE , onSwfLoadComplete );
				loader.contentLoaderInfo.addEventListener( IOErrorEvent.IO_ERROR , onSwfLoadFailure );
				loader.contentLoaderInfo.addEventListener( SecurityErrorEvent.SECURITY_ERROR , securityErrorHandler );
				loader.load( req , context );
			}
		}

		//-------------------------------------------------------------------
		//
		// Plugin Event Handlers
		//
		//-------------------------------------------------------------------

		private function securityErrorHandler( e : Event ):void {
			debug( "securityErrorHandler()" );
		}

		private function onSwfLoadComplete( e : Event ):void {
			var content:DisplayObject=e.target.content;

			// If we don't add it as a child to something, it won't be in the
			// display list and the stage property will be null
			_plugin_container = new UIComponent();
			_plugin_container.visible=false;
			_plugin_container.width=0;
			_plugin_container.height=0;
			addChild( _plugin_container );
			
			_plugin_container.addChild( content );
			_plugins.push( content );

			if (content is IOvpPlugIn) {
				loadPlugin( content as IOvpPlugIn );
			}
		}

		private function onSwfLoadFailure( e : IOErrorEvent ):void {
			debug( "Plug-in load failure: " + e.text );
		}

		// Load plugin
		private function loadPlugin( plugIn : IOvpPlugIn ):void {
			debug( "Loading Plugin: " + plugIn.ovpPlugInName + " built on OVP version " + plugIn.ovpPlugInCoreVersion );
			if (verifyPlugin(plugIn)) {
				plugIn.ovpPlugInTracingOn=true;
				plugIn.initOvpPlugIn( this );
				_pluginsLoaded++;
	
				if (_pluginsLoaded==_pluginFiles.length) {
					handleAllPluginsLoaded();
				}
			}
		}
		
		private function verifyPlugin( plugIn : IOvpPlugIn ):Boolean {
			var okay:Boolean = false;
			var pluginVersion:Object = new Object();
			var playerVersion:Object = new Object();
			
			var tempArray:Array = plugIn.ovpPlugInCoreVersion.split(".");
			if (tempArray && tempArray.length == 3) {
				pluginVersion.major = tempArray[0];
				pluginVersion.minor = tempArray[1];
				pluginVersion.subMinor = tempArray[2];
			
				tempArray = OvpVersion.version.split(".");
				if (tempArray && tempArray.length == 3) {
					playerVersion.major = tempArray[0];
					playerVersion.minor = tempArray[1];
					playerVersion.subMinor = tempArray[2];
					
					okay = comparePluginVersion(playerVersion, pluginVersion);
				}
			}
				
			return okay;
		}
		
		/**
		 * Override to provide different behavior, the default behavior is the plugin version must match
		 * the player version, where version in this case is the OVP version the code was built on.
		 */
		protected function comparePluginVersion(playerVersion:Object, pluginVersion:Object):Boolean {
			return ((pluginVersion.major == playerVersion.major) && (pluginVersion.minor == playerVersion.minor) && 
					(pluginVersion.subMinor == playerVersion.subMinor));
		}

		// All plugins loaded successfully, enable the load buttons
		private function handleAllPluginsLoaded():void {
			debug( _pluginsLoaded + " plug-ins loaded." );
			addEventListener( OvpPlayerEvent.DEBUG_MSG , onDebugMessage , false , 0 , true );
			updateState( OvpPlayerEvent.WAITING );
			_model.start();
		}

		// Debugging Output
		private function onDebugMessage( event : OvpPlayerEvent ):void {
			debug( event.data as String );
		}

		private function updateState( state : String ):void {
			if (_state!=state) {
				_state=state;
				dispatchEvent( new OvpPlayerEvent( OvpPlayerEvent.STATE_CHANGE , _state ));
			}
		}

		private function handleStreamNotFound( e : Event ):void {
			updateState( OvpPlayerEvent.WAITING );
			dispatchEvent( new OvpPlayerEvent( OvpPlayerEvent.ERROR , "stream not found" ));
		}

		private function bufferFullHandler( e : Event ):void {
			if (_state!=OvpPlayerEvent.PAUSED) {
				updateState( OvpPlayerEvent.PLAYING );
			}
		}

		private function stopPlaybackHandler(e:Event=null):void {
			updateState( OvpPlayerEvent.COMPLETE );
		}

		private function pauseHandler(e:Event = null):void {
			updateState( OvpPlayerEvent.PAUSED );
		}

		private function playHandler(e:Event = null):void {
			updateState( OvpPlayerEvent.PLAYING );
		}

		private function handleEndOfItem( e : Event ):void {

		}

		private function adStartHandler( e : Event ):void {
			_model.adStarted();
		}

		private function adEndHandler( e : Event ):void {
			_model.adEnded();
		}

		private function cuePointHandler( e : OvpEvent ):void {
			dispatchEvent( new OvpEvent( OvpEvent.NETSTREAM_CUEPOINT , e.data ));
		}
		
		private function switchRequestedHandler(e:OvpEvent):void {
			dispatchEvent( new OvpPlayerEvent(OvpPlayerEvent.SWITCH_REQUESTED, e.data));
		}
		
		private function switchAcknowledgedHandler(e:OvpEvent):void {
			dispatchEvent( new OvpPlayerEvent(OvpPlayerEvent.SWITCH_ACKNOWLEDGED, e.data));
		}
		
		private function switchCompleteHandler(e:OvpEvent):void {
			dispatchEvent( new OvpPlayerEvent(OvpPlayerEvent.SWITCH_COMPLETE, e.data));
		}
		
		private function connectionCreatedHandler(e:OvpEvent):void {
			dispatchEvent( new OvpPlayerEvent(OvpPlayerEvent.CONNECTION_CREATED, e.data));
		}
		
		private function netStreamCreatedHandler(e:OvpEvent):void {
			dispatchEvent( new OvpPlayerEvent(OvpPlayerEvent.NETSTREAM_CREATED, e.data));
		}
		
		private function loadUIhandler( e : Event ):void {
			_background=new BackgroundView(_model);
			addChild( _background );

			_videoView=new VideoView(_model);
			_video=_videoView.video;
			addChild( _videoView );

			_adMC = new MovieClip();
			addChild( _adMC );

			_shareEmbed=new ShareEmbedView(_model);
			addChild( _shareEmbed );

			_controlbar=new ControlbarView(_model);
			addChild( _controlbar );

			if (_model.hasPlaylist) {
				_playlist=new PlaylistView(_model);
				addChild( _playlist );
			}

			_linearAdMC = new MovieClip();
			_linearAdMC.x=0;
			_linearAdMC.y=0;
			_linearAdMC.name="linearAdMC";

			_videoHolder = new UIComponent();
			_videoHolder.width=this.contentWidth;
			_videoHolder.height=this.contentHeight;
			_videoHolder.x=_video.x;
			_videoHolder.y=_video.y;
			_videoHolder.addChild( _linearAdMC );
			addChild( _videoHolder );

			_debugPanel=new DebugPanelView(_model);
			addChild( _debugPanel );

			addChild( _errorDisplay );

			resize( null );
			addContextMenu();
			_model.UIready();
		}

		private function addContextMenu():void {
			contextMenu = new ContextMenu();
			contextMenu.hideBuiltInItems();

			var idItem:ContextMenuItem=new ContextMenuItem("Built on OpenVideoPlayer v"+OvpVersion.version,true);
			idItem.addEventListener( ContextMenuEvent.MENU_ITEM_SELECT , idItemSelectHandler );
			contextMenu.customItems.push( idItem );

			var modeItem:ContextMenuItem=new ContextMenuItem("Toggle layout mode (overlay|side-by-side)",true);
			modeItem.addEventListener( ContextMenuEvent.MENU_ITEM_SELECT , modeItemSelectHandler );

			contextMenu.customItems.push( modeItem );

			var fitItem:ContextMenuItem=new ContextMenuItem("Video scale: FIT",true,_model.scaleMode!=_model.SCALE_MODE_FIT);
			fitItem.addEventListener( ContextMenuEvent.MENU_ITEM_SELECT , fitItemSelectHandler );

			contextMenu.customItems.push( fitItem );

			var stretchItem:ContextMenuItem=new ContextMenuItem("Video scale: STRETCH",false,_model.scaleMode!=_model.SCALE_MODE_STRETCH);
			stretchItem.addEventListener( ContextMenuEvent.MENU_ITEM_SELECT , stretchItemSelectHandler );

			contextMenu.customItems.push( stretchItem );

			var nativeItem:ContextMenuItem=new ContextMenuItem("Video scale: NATIVE",false,_model.scaleMode!=_model.SCALE_MODE_NATIVE);
			nativeItem.addEventListener( ContextMenuEvent.MENU_ITEM_SELECT , nativeItemSelectHandler );

			contextMenu.customItems.push( nativeItem );

			var nativeOrSmallerItem:ContextMenuItem=new ContextMenuItem("Video scale: NATIVE OR SMALLER",false,_model.scaleMode!=_model.SCALE_MODE_NATIVE_OR_SMALLER);
			nativeOrSmallerItem.addEventListener( ContextMenuEvent.MENU_ITEM_SELECT , nativeOrSmallerItemSelectHandler );

			contextMenu.customItems.push( nativeOrSmallerItem );

			var debugItem:ContextMenuItem=new ContextMenuItem("Toggle Statistics Panel",true,true);
			debugItem.addEventListener( ContextMenuEvent.MENU_ITEM_SELECT , debugItemSelectHandler );

			contextMenu.customItems.push( debugItem );

			var autoItem:ContextMenuItem=new ContextMenuItem("Enable Auto Bitrate Switching",true,_model.isMultiBitrate&&! _model.useAutoDynamicSwitching);
			autoItem.addEventListener( ContextMenuEvent.MENU_ITEM_SELECT , autoSwitchHandler );

			contextMenu.customItems.push( autoItem );

			var manualItem:ContextMenuItem=new ContextMenuItem("Enable Manual Switching",false,_model.isMultiBitrate&&_model.useAutoDynamicSwitching);
			manualItem.addEventListener( ContextMenuEvent.MENU_ITEM_SELECT , manualSwitchHandler );

			contextMenu.customItems.push( manualItem );

			var switchUpItem:ContextMenuItem=new ContextMenuItem("Switch up",false,false);
			switchUpItem.addEventListener( ContextMenuEvent.MENU_ITEM_SELECT , switchUpHandler );

			contextMenu.customItems.push( switchUpItem );

			var switchDownItem:ContextMenuItem=new ContextMenuItem("Switch down",false,false);
			switchDownItem.addEventListener( ContextMenuEvent.MENU_ITEM_SELECT , switchDownHandler );

			contextMenu.customItems.push( switchDownItem );
		}

		private function resizeHandler( e : Event ):void {
			if (contextMenu is ContextMenu) {
				if (_model.isMultiBitrate) {
					contextMenu.customItems[7].enabled=! _model.useAutoDynamicSwitching;
					contextMenu.customItems[8].enabled=_model.useAutoDynamicSwitching;
					contextMenu.customItems[9].enabled=! _model.useAutoDynamicSwitching;
					contextMenu.customItems[10].enabled=! _model.useAutoDynamicSwitching;
				} else {
					contextMenu.customItems[7].enabled=false;
					contextMenu.customItems[8].enabled=true;
					contextMenu.customItems[9].enabled=false;
					contextMenu.customItems[10].enabled=false;
				}
			}
			dispatchEvent( new Event( "resize" ));
		}

		private function idItemSelectHandler( e : ContextMenuEvent ):void {
			navigateToURL( new URLRequest( "http://openvideoplayer.sourceforge.net/" ) , "_blank" );
		}

		private function modeItemSelectHandler( e : ContextMenuEvent ):void {
			_model.isOverlay=! _model.isOverlay;
			resize( null );
		}

		private function fitItemSelectHandler( e : ContextMenuEvent ):void {
			_model.scaleMode=_model.SCALE_MODE_FIT;
			contextMenu.customItems[2].enabled=false;
			contextMenu.customItems[3].enabled=true;
			contextMenu.customItems[4].enabled=true;
			contextMenu.customItems[5].enabled=true;
			resize( null );
		}

		private function stretchItemSelectHandler( e : ContextMenuEvent ):void {
			_model.scaleMode=_model.SCALE_MODE_STRETCH;
			contextMenu.customItems[2].enabled=true;
			contextMenu.customItems[3].enabled=false;
			contextMenu.customItems[4].enabled=true;
			contextMenu.customItems[5].enabled=true;

			resize( null );
		}

		private function nativeItemSelectHandler( e : ContextMenuEvent ):void {
			_model.scaleMode=_model.SCALE_MODE_NATIVE;
			contextMenu.customItems[2].enabled=true;
			contextMenu.customItems[3].enabled=true;
			contextMenu.customItems[4].enabled=false;
			contextMenu.customItems[5].enabled=true;
			resize( null );
		}

		private function nativeOrSmallerItemSelectHandler( e : ContextMenuEvent ):void {
			_model.scaleMode=_model.SCALE_MODE_NATIVE_OR_SMALLER;
			contextMenu.customItems[2].enabled=true;
			contextMenu.customItems[3].enabled=true;
			contextMenu.customItems[4].enabled=true;
			contextMenu.customItems[5].enabled=false;
			resize( null );
		}

		private function autoSwitchHandler( e : ContextMenuEvent ):void {
			_model.useAutoDynamicSwitching=true;
			contextMenu.customItems[7].enabled=false;
			contextMenu.customItems[8].enabled=true;
			contextMenu.customItems[9].enabled=false;
			contextMenu.customItems[10].enabled=false;
		}

		private function manualSwitchHandler( e : ContextMenuEvent ):void {
			_model.useAutoDynamicSwitching=false;
			contextMenu.customItems[7].enabled=true;
			contextMenu.customItems[8].enabled=false;
			contextMenu.customItems[9].enabled=true;
			contextMenu.customItems[10].enabled=true;
		}

		private function switchUpHandler( e : ContextMenuEvent ):void {
			_model.switchUp();
		}

		private function switchDownHandler( e : ContextMenuEvent ):void {
			_model.switchDown();
		}

		private function debugItemSelectHandler( e : ContextMenuEvent ):void {
			_model.toggleDebugPanel();
		}

		private function mouseMoveHandler( e : MouseEvent ):void {
			_model.showControlBar( true );

			if (stage.displayState==StageDisplayState.FULL_SCREEN) {
				_timer.reset();
				_timer.start();
			}
		}

		private function leaveStageHandler( e : Event ):void {
			_model.showControlBar( false );
		}

		private function toggleFullscreenHandler( e : Event ):void {
			dispatchEvent( new Event( "toggleFullscreen" ));
		}

		private function playStartHandler( e : Event = null ):void {
			if(_model.autoStart)
			{
				updateState( OvpPlayerEvent.START_NEW_ITEM );				
				dispatchEvent( new Event( "playStart" ));				
			}
		}

		private function endOfItemHandler( e : Event ):void {
			dispatchEvent( new Event( "endOfItem" ));
		}

		private function volumeChangeHandler( e : Event ):void {
			dispatchEvent( new Event( "volumeChanged" ));
			dispatchEvent( new OvpPlayerEvent(OvpPlayerEvent.VOLUME_CHANGE, _model.volume));
		}

		public function resizeTo( w : Number , h : Number ):void {
			_lastWidth=w;
			_lastHeight=h;
			resize( null );
		}

		private function resize( e : Event ):void {
			_model.resize( _lastWidth , _lastHeight );
		}
		
		// ----------------------------------------------------------------
		//
		// IOvpPlayer Implementation
		//
		// ----------------------------------------------------------------

		public function get plugins():Array {
			return _plugins;
		}

		public function get flashvars():Object {
			return _flashvars;
		}

		public function get currentBitrate():int {
			if (_metadata && _metadata.videodatarate) {
				return _metadata.videodatarate;
			}
			return 0;
		}

		public function get fullScreen():Boolean {
			return false;
		}

		public function get captionsActive():Boolean {
			return false;
		}

		public function get hasVideo():Boolean {
			return true;
		}

		public function get hasAudio():Boolean {
			return true;
		}

		public function get hasCaptions():Boolean {
			return false;
		}

		public function get itemCount():int {
			return 1;
		}

		public function get itemsPlayed():int {
			return 0;
		}

		public function get playerWidth():int {
			return this.width;
		}

		public function get playerHeight():int {
			return this.height;
		}

		public function get contentWidth():int {
			return _model.availableVideoWidth;
		}

		public function get contentHeight():int {
			return _model.availableVideoHeight;
		}

		public function get contentTitle():String {
			return "";
		}

		public function get contentURL():String {
			return _filename;
		}

		public function set advertisingMode( value : Boolean ):void {
			_advertisingMode = value;
			
			if (_advertisingMode) {
				_model.adStarted();
				pausePlayer();
				disableControls();
				_video.visible = false;
				_linearAdMC.visible = true;
				dispatchEvent( new OvpPlayerEvent( OvpPlayerEvent.VOLUME_CHANGE , _model.volume ));
			} else {
				_model.adEnded();
				enableControls();
				resumePlayer();
				_video.visible = true;
				_linearAdMC.visible = false;
			}
		}
		
		public function get advertisingMode():Boolean {
			return _advertisingMode;
		}

		public function getSpriteById( id : String ):Sprite {
			if (id.toLowerCase() == "linearadmc") {
				_linearAdMC.visible = true;
				return _linearAdMC;
			}
			return null;
		}

		public function addCuePoint( cuePoint : Object ):void {
			_cuePointMgr.addCuePoint( cuePoint );
		}

		public function pausePlayer():void {
			pausePlayback();
		}

		public function resumePlayer():void {
			resumePlayback();
		}

		public function startPlayer():void {							
			startPlayback();			
		}

		public function stopPlayer():void {
			stopPlayback();
		}
		
	}
}