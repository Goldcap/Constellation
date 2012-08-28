var videoplayer = {
  
  state: "pre",
  div: "none",
  volume: 5,
  is_mute: false,
  trailer: 41,
  version: "2.39",
	file: null,
	autoplay: null,
	videoHidden: 0,
	startTime: 0,
	errorSleepTime: 5000,
  theseek: 0,
  Tokbox_Session: null,
  
  init: function() {
  },
  
  initPlayer: function() {
    
    var args = {k:$("#video_data").html()};
    $.ajax({url: '/services/Tokenizer/'+$("#film").html()+'/map.smil?k='+args.k, 
            data: $.param(args), 
            type: "POST", 
            cache: false, 
            dataType: "json", 
            success: function(response) {
            	videoplayer.setVODControls(response);
            }, error: function(response) {
              videoplayer.setVODControls("true");
              //console.log("ERROR:", response);
            }
    });
    //videoplayer.setVODControls( $("#video").html() );
    // videoplayer.setSoundControl();
    
  },
  
	setVODControls: function( response ) {
		  //console.log("VOD");
		  
      videoplayer.state = "playing";
      videoplayer.div = 'cPlayer';
     
      if ($("#runtime").html() > 0) {
        videoplayer.theseek = $("#runtime").html();
      } else {
        videoplayer.theseek = 0;
      }
			
			//console.log("THESEEK is " + videoplayer.theseek);
      
			var flashvars = {
          src: response.fileURL,
       		plugin_AkamaiAdvancedStreamingPlugin: 
					//"http://localhost/test/AkamaiAdvancedStreamingPlugin.swf",
					"http://players.edgesuite.net/flash/plugins/osmf/advanced-streaming-plugin/v2.5/osmf1.6/AkamaiAdvancedStreamingPlugin.swf",
          autoPlay: "false",
          loop: "false",
          verbose: true,
          sint: videoplayer.theseek,
					controlBarAutoHide: "true",
          controlBarPosition: "bottom",
          ticketParams: $("#video_data").html(), 
        	sip: $("#host").html(),
					sprt: parseInt($("#port").html()) + 16090, 
					javascriptCallbackFunction: "onJavaScriptBridgeCreated"
      };
      var params = 
        {
          allowFullScreen: 'true',
          allowScriptAccess: 'always',
          wmode: 'direct',
					bgcolor:"#000000"
        };
      var attributes = 
        {
          id: 'cPlayer',
          name: 'cPlayer'
        };
      
      swfobject.embedSWF(
								"/flash/StrobeMediaPlayback.swf"
								, "movie_stream"
								, "100%"
								, "100%"
								, "10.2.0"
								, "/flashexpressInstall.swf"
								, flashvars
								, params
								, attributes
                );
	},
	
	hideVODPlayer: function() {
    try {
    if (swfobject != undefined) {
      swfobject.removeSWF('cPlayer');
    	$("#video_stream").append('<div id="movie_stream" class="nx_widget_player">');
    }
    } catch (e) {}
  },
   
  showHostCam: function() {
  	TB.setLogLevel(TB.DEBUG); // Set this for helpful debugging messages in console
 
    videoplayer.Tokbox_Session = TB.initSession($("#tokbox_session").html());
    videoplayer.Tokbox_Session.addEventListener('sessionConnected', sessionConnectedHandler);      
    videoplayer.Tokbox_Session.connect($("#tokbox_key").html(),$("#tokbox_token").html());
    
		function sessionConnectedHandler(event) {
      console.log('Connected to OpenTok');
      publisher = videoplayer.Tokbox_Session.publish('host_stream');
      //subscribeToStreams(event.streams);
    }
  },

	hideHostCam: function() {
	
	},
	
  //This is the same as "camViewerReStart"
  //Except it does not attempt to destroy the viewer
  //Before it Starts
  showLiveViewer: function() {
    TB.setLogLevel(TB.DEBUG); // Set this for helpful debugging messages in console
 
    videoplayer.Tokbox_Session = TB.initSession($("#tokbox_session").html());
    videoplayer.Tokbox_Session.addEventListener('sessionConnected', sessionStartHandler);
    videoplayer.Tokbox_Session.addEventListener('streamCreated', sessionStartHandler); 
    videoplayer.Tokbox_Session.addEventListener('connectionDestroyed', sessionStopHandler);
		videoplayer.Tokbox_Session.addEventListener('streamPropertyChanged', sessionChangeHandler);
		      
    videoplayer.Tokbox_Session.connect($("#tokbox_key").html(),$("#tokbox_token").html());
    
		function sessionStartHandler(event) {
      
			console.log('Watching to OpenTok');
      console.log('Streams: '+event.streams.length);
      
      //Enforce the HOST ONLY model
      if (event.streams.length == 0) {
			  return;
			} else if (event.streams.length != 1) {
				stream = event.streams[0];
				//return;
			} else {
				stream = event.streams[0];
			}
			
      // Make sure we don't subscribe to ourself
      if (stream.connection.connectionId == videoplayer.Tokbox_Session.connection.connectionId) {
        return;
      }

      // Create the div to put the subscriber element in to
      var div = document.createElement('div');
      div.setAttribute('id', 'stream' + stream.streamId);
      $("#host_stream").append(div);
                         
      // Subscribe to the stream
      videoplayer.Tokbox_Session.subscribe(stream, div.id);
      
    }
    
    function sessionStopHandler(event) {
			console.log("The Host Stream Has Disconnected");
		}
		
		 function sessionChangeHandler(event) {
			console.log(event.changedProperty);  
			console.log(event.newValue);
		}
		
  },
  
  hideLiveViewer: function() {
	
	},
	
	mute: function() {
	
	}
  
}

$(document).ready(function(){
	if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
 
  videoplayer.init();
  
  if ($("#video-type").html() == "TRAILER") {
    videoplayer.initPlayer();
  }
  
  //$("#status_panel").click(function() {
  //  videoplayer.message("notice","Some Friggin Notice","Some Friggin Message");
  //});
});

function onMediaPlaybackError(playerId, code, message, detail)  {
  alert(playerId + "\n\n" + code + "\n" + message + "\n" + detail);            
}

//http://forums.adobe.com/thread/758695            
var player = null;
var state = null;
var seeked = false;
function onJavaScriptBridgeCreated(playerId)
{
	if (player == null) {
		player = document.getElementById(playerId);
  }
  //console.log("STATE IS " + player.getState() );
  
  if (((player.getState() == "playing") || (player.getState() == "paused")) && (player.canSeekTo(videoplayer.theseek)) && (! seeked)) {
      //console.log("Seeking");
      if  (videoplayer.theseek > 0) {
				player.seek(videoplayer.theseek);
			}
      seeked = true;
  }
  if (player.getState() == "ready") {
    //console.log("Playing");
    player.play2();
    player.pause();
  }
  		
}