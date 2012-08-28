var videoplayer = {
  
  state: "pre",
  div: "none",
  volume: 5,
  is_mute: false,
  trailer: 41,
  version: "2.48",
	file: null,
	autoplay: null,
	videoHidden: 0,
	startTime: 0,
	errorSleepTime: 5000,
  
  init: function() {
    $("#mute").click(function() {
      videoplayer.toggleMute();
    });
    
    $("#fullscreen").click(function() {
      videoplayer.fullscreen();
    });
    
    videoplayer.setSoundControl();
    
  },
  
  initPlayer: function() {
  	
    videoplayer.setVODControls( $("#video").html() );
    videoplayer.setSoundControl();
    
  },
  
	setVODControls: function( file ) {
			
			videoplayer.file = file;
			var args={"k":$("#video_data").html()};
			videoplayer.state = "playing";
      videoplayer.div = 'cPlayer';
      
      if ($("#runtime").html() > 0) {
        var theseek = $("#runtime").html();
      } else {
        var theseek = 0;
      }
			
			var dts = new Date().getTime();
			
			if (timekeeper != undefined) {
				 var currentTime = timekeeper.currentTime + 1;
			} else {
      	var currentTime = new Date().getTime() / 1000;
      }
      
      //console.log("Seek Time:" + theseek);
      var flashvars = 
        {src: videoplayer.file,
        autostart: true,
    		debugConsole: 'false',
		    delaySeek: '3',
		    csrc: $("#csrc").html(),
		    seekInto: theseek,
		    filmStartTime: $("#starttime").html(),
    		ticketParams:$("#video_data").html(),
				streamName: $("#room").html() + "_stream",
				//auth:response.tokenResponse.token,
				slist:$("#film").html(),
				bitrates:$("#bitrates").html(),
        fileType: $("#movie_type").html(),
        sip: $("#host").html(),
        sprt: parseInt($("#port").html()) + 16090,
        timestamp: currentTime,
        showTimeDebug: false,
        showTimeDebugInterval: 10,
        zeroFPSLimit:30,
        logLevel:0
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
      
      
      videoplayer.div = 'cPlayer';
      //console.log('Loading '+'/flash/Constellation_v'+videoplayer.version+'_secure.swf');
      swfobject.embedSWF('/flash/Constellation_Player_secure.swf?v='+videoplayer.version, 'movie_stream', '100%', '100%', '10.1.0', '/flash/expressInstall.swf', flashvars, params, attributes);
      //swfobject.embedSWF('/flash/Constellation_Player.swf?v='+videoplayer.version, 'movie_stream', '100%', '100%', '10.1.0', '/flash/expressInstall.swf', flashvars, params, attributes);
      
	},
	
	hideVODPlayer: function() {
    try {
    if (swfobject != undefined) {
      swfobject.removeSWF('cPlayer');
    	$("#video_stream").append('<div id="movie_stream" class="nx_widget_player">');
    }
    } catch (e) {}
  },
   
  pushLogLevel: function( value ) {
		var args = {body:"hostset:setLogLevel|hostval:"+value};
		$.postJSON("/services/chat/post", args );
	},
  
  setLogLevel: function( message ) {
		vals = message.html.split("|");
		var name=vals[0].split(":");
		var value=vals[1].split(":");
		console.log("setting new value of "+name[1]+" " + value[1]);
		if (document.getElementById('cPlayer') != undefined) {
			document.getElementById("cPlayer").setLogLevel(value[1]);
		}
	},
  
  showHostCam: function() {
    if (swfobject != undefined) {
      //document.getElementById(videoplayer.div).focus();
      //document.getElementById(videoplayer.div).showHostCam();
      videoplayer.setHOSTControls();
			$(".show_hostcam").hide(100);
      $(".hide_hostcam").show(100);
      if (qanda != undefined) {
        //console.log("VideoPlayer Publishing Host Cam");
        //qanda.publishHost();
        qanda.qanda_showing = true;
      }
    }
  },

  hideHostCam: function() {
    try {
    if (swfobject != undefined) {
      swfobject.removeSWF('myHost');
    	$(".qanda_box").append('<div id="host_stream" class="nx_widget_player"></div>');
			//document.getElementById(videoplayer.div).hideHostCam();
      $(".hide_hostcam").hide(100);
      $(".show_hostcam").show(100);
      if (qanda != undefined) {
        qanda.qanda_showing = false;
      }
    }
    } catch (e) {}
  },
  
  //This is the same as "showLiveViewer"
  //Except it tries to destroy the Viewer
  //Before it Starts
  camHostReStart: function() {
    videoplayer.videoHidden = 0;
    //console.log("Video Player Cam Viewer Click");
    swfobject.removeSWF('myHost');
    $(".qanda_box").append('<div id="host_stream" class="nx_widget_player"></div>');
		videoplayer.setHOSTControls();
    //document.getElementById(videoplayer.div).camViewerStart();
    if (qanda != undefined) {
      qanda.qanda_showing = true;
    }
  },
  
	setHOSTControls: function() {
		var args = {"host":$("#userid").html()};
		$.ajax({url: "/services/HUD/get", 
              type: "GET", 
              cache: false, 
              dataType: "json",
              data: $.param(args),
              timeout: videoplayer.errorSleepTime,
              success: function(response) {videoplayer.startHOST(response)},
              error: videoplayer.setHOSTControls});
	},
	
	startHOST: function( response ) {
			//console.log("Setting Host Control");
			var settings = response.hudSettings;
			
      if ($("#hostserver").html() != "akamai") {
        var hswf = "recordHostCamera.swf";
        var rtmphost = "rtmp://"+$("#hostserver").html()+".rtmp.constellation.tv/live/";
      } else {
        var hswf = "HostCam.swf";
        var rtmphost = "rtmp://p.ep45907.i.akamaientrypoint.net/EntryPoint/";
      }
       
      var flashvars = 
        {src: videoplayer.file,
        debugConsole: 'false',
		    ticketParams:$("#video_data").html(),
				streamName: $("#room").html() + "_stream",
				//auth:response.tokenResponse.token,
				slist:$("#film").html(),
        sip: $("#host").html(),
        bandwidthLimit: settings.bandwidthLimit,
		    qualityLevel: settings.qualityLevel,
		    keyFrameInterval: settings.keyFrameInterval,
		    captureFps: settings.captureFps,
		    bufferMin: settings.bufferMin,
		    bufferMax: settings.bufferMax,
		    micRate: settings.micRate,
		    micGain: settings.micGain,
		    motionTimeout: settings.motionTimeout,
		    echoSuppression: settings.echoSuppression,
		    enhancedMicrophone: settings.enhancedMicrophone,
		    silenceLevel: settings.silenceLevel,
		    micSilenceTimeout: settings.micSilenceTimeout,
		    enableVAD: settings.enableVAD,
        recordURL: rtmphost
    		};
    		
      var params = 
        {
          allowFullScreen: 'true',
          allowScriptAccess: 'always',
          wmode: 'opaque',
					bgcolor:"#000000"
        };
      var attributes = 
        {
          id: 'myHost',
          name: 'myHost'
        };
      
      //console.log('Loading '+'/flash/HostCam.swf');
      swfobject.embedSWF('/flash/'+hswf, 'host_stream', '290', '220', '10.1.0', '/flash/expressInstall.swf', flashvars, params, attributes);
      
	},
	
	message: function( type, title, message ) {
	 if (videoplayer.state == "playing") {
	  if (document.getElementById(videoplayer.div) != undefined)
		document.getElementById(videoplayer.div).displayMessage(type,title,message);
	 }
  },

	resize: function() {
	 if (videoplayer.state == "playing") {
	   if (document.getElementById(videoplayer.div) != undefined)
		document.getElementById(videoplayer.div).resizeNotification();
	 }
  },
  
  toggleMute: function() {
  
    if (! videoplayer.is_mute) {
      videoplayer.is_mute=true;
			videoplayer.mute();
    } else {
      videoplayer.is_mute=false;
			videoplayer.unmute();
    }
  },
	
	mute: function() {
		$("#mute").css("backgroundPosition","-20px 0px");
		$(".volume_bar").css("backgroundPosition","-104px 0px");
		if ((document.getElementById(videoplayer.div) != undefined) && (videoplayer.state == 'playing')) {
			document.getElementById(videoplayer.div).setVolume(0);
		}
		videoplayer.unsetSoundControl();
	},
	
	unmute: function() {
		if ((document.getElementById(videoplayer.div) != undefined)  && (videoplayer.state == 'playing')) {
			document.getElementById(videoplayer.div).setVolume(videoplayer.volume);
		}
		$("#mute").css("backgroundPosition","0px 0px");
		$(".volume_bar").css("backgroundPosition","0px 0px");
		videoplayer.setSoundControl();
	},

	setFocus: function() {
    if (document.getElementById(videoplayer.div) != undefined) {
      document.getElementById(videoplayer.div).focus();
		}
	},
	
  //This is the same as "camViewerReStart"
  //Except it does not attempt to destroy the viewer
  //Before it Starts
  showLiveViewer: function() {
    videoplayer.videoHidden = 0;
    try {
    if (swfobject != undefined) {
      //document.getElementById(videoplayer.div).showLiveViewer();
      videoplayer.setVIEWControls();
			$(".show_hostcam").hide(100);
      $(".hide_hostcam").show(100);
      if (qanda != undefined) {
        qanda.qanda_showing = true;
      }
    }
    } catch (e) {}
  },
  
  //This is the same as "showLiveViewer"
  //Except it tries to destroy the Viewer
  //Before it Starts
  camViewerReStart: function() {
    videoplayer.videoHidden = 0;
    //console.log("Video Player Cam Viewer Click");
    swfobject.removeSWF('myViewer');
    //Removing object removes control div
    $(".qanda_box").append('<div id="host_stream" class="nx_widget_player"></div>');
		videoplayer.setVIEWControls();
    //document.getElementById(videoplayer.div).camViewerStart();
    if (qanda != undefined) {
      qanda.qanda_showing = true;
    }
  },

  hideLiveViewer: function() {
    videoplayer.videoHidden++;
    if (videoplayer.videoHidden > 5) {
      return;
    }
    try {
    if (swfobject != undefined) {
      swfobject.removeSWF('myViewer');
    	$(".qanda_box").append('<div id="host_stream" class="nx_widget_player"></div>');
			//document.getElementById(videoplayer.div).hideLiveViewer( null, false );
      $(".hide_hostcam").hide(100);
      $(".show_hostcam").show(100);
      if (qanda != undefined) {
        qanda.qanda_showing = false;
      }
    }
    } catch (e) {}
  },
  
  setVIEWControls: function() {
			
			//console.log("Setting View Control");
			
			var args={"k":$("#video_data").html()};
      //videoplayer.div = 'myViewer';
      
      var flashvars = 
        {src: videoplayer.file,
        debugConsole: 'false',
		    ticketParams:$("#video_data").html(),
				streamName: $("#room").html() + "_stream",
				//auth:response.tokenResponse.token,
				slist:$("#film").html(),
        sip: $("#host").html()
    		};
    		
      var params = 
        {
          allowFullScreen: 'true',
          allowScriptAccess: 'always',
          wmode: 'opaque',
					bgcolor:"#000000"
        };
      var attributes = 
        {
          id: 'myViewer',
          name: 'myViewer'
        };
      
      //console.log('Loading '+'/flash/LiveStream.swf');
      swfobject.embedSWF('/flash/LiveStream.swf', 'host_stream', '290', '220', '10.1.0', '/flash/expressInstall.swf', flashvars, params, attributes);
      
	},
	
  unsetSoundControl: function() {
    $( "#slider" ).slider('destroy');
  },
  
  setSoundControl: function() {
    $( "#slider" ).slider({
			value:videoplayer.volume,
			min: 0,
			max: 10,
			step: 1,
			slide: function( event, ui ) {
			 videoplayer.volume = ui.value;
       if ((document.getElementById(videoplayer.div) != undefined)  && (videoplayer.state == 'playing')) {
			   document.getElementById(videoplayer.div).setVolume(ui.value);
			 }
				//$( "#amount" ).val( "$" + ui.value );
			}
		});
	
  },
  
  fullscreen: function() {
    //console.log("Going FullScreen");
    if ((document.getElementById(videoplayer.div) != undefined)  && (videoplayer.state == 'playing')) {
			document.getElementById(videoplayer.div).goFullscreen();
		}
  },
  
  //This comes from the Flash Interface, and is between 0 and 1
  //So we multiply by 10
  setVolume: function( volume ) {
    $( "#slider" ).slider( "value" , (volume * 10) );
  }
	
}

function setCurrentTime(val) {
	$("#seektime").html(Math.round(val));
	//console.log("Current Time is "+ val);
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
