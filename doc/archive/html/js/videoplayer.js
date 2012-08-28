var videoplayer = {
  
  state: "pre",
  div: "none",
  volume: 5,
  is_mute: false,
  version: 50,
  trailer: 41,
  hostCamVersion: 18,
	guestCamVersion: 12,
	file: null,
	autoplay: null,
	videoHidden: 0,
	startTime: 0,
  
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
  	
    if ($("#video-type").html() == "TRAILER") {
      videoplayer.setTRAILERControls( $("#video").html(), $("#video-autoplay").html() );
    } else if ($("#video-type").html() == "EMBED") {
      //console.log("Embed player deprecated.");
      //videoplayer.setEMBEDControls( $("#video").html(), $("#video-autoplay").html() );
    } else if ($("#video-type").html() == "VOD") {
      videoplayer.setVODControls( $("#video").html(), $("#video-autoplay").html() );
    } else {
      console.log("No player type found.");
    }
    
    videoplayer.setSoundControl();
    
  },
  
	setVODControls: function( file, autoplay ) {
			
			videoplayer.file = file;
			videoplayer.autoplay = autoplay;
			var args={"k":$("#video_data").html()};
			videoplayer.state = "playing";
      videoplayer.div = 'myPlayer';
      
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
      
      var flashvars = 
        {src: videoplayer.file,
        autostart: videoplayer.autoplay,
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
        timestamp: currentTime
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
          id: 'myPlayer',
          name: 'myPlayer'
        };
      
      
      videoplayer.div = 'myPlayer';
      //console.log('Loading '+'/flash/Constellation_v'+videoplayer.version+'_secure.swf');
      //swfobject.embedSWF('/flash/Constellation_v'+videoplayer.version+'_secure.swf', 'movie_stream', '100%', '100%', '10.2.0', '/flash/expressInstall.swf', flashvars, params, attributes);
      swfobject.embedSWF('/flash/Constellation_v'+videoplayer.version+'.swf', 'movie_stream', '100%', '100%', '10.2.0', '/flash/expressInstall.swf', flashvars, params, attributes);
      
	},
	
	setTRAILERControls: function( thefile, autoplay ) {
      
      videoplayer.state = "playing";
      //console.log("Start TRAILER Movie");
			//console.log(thefile);
			
      var flashvars = 
        {
          src: thefile,
          autostart: autoplay,
    		  debugConsole: 'false',
		      fitMode: 'fit',
		      fullscreenButtonX: 3,
		      fullscreenButtonY: 6,
		      asTrailer: 'true'
        };
      var params = 
        {
          allowFullScreen: 'true',
          allowScriptAccess: 'always',
          wmode: 'transparent',
		      seekInto: 0
        };
      var attributes = 
        {
          id: 'myPlayer',
          name: 'myPlayer'
        };
       
       swfobject.embedSWF('/flash/ConstellationTrailer_v_'+videoplayer.trailer+'.swf', videoplayer.div, '100%', '100%', '10.2.0', '/flash/expressInstall.swf', flashvars, params, attributes);
       
	},
	
  showHostCam: function() {
    if (swfobject != undefined) {
      //document.getElementById(videoplayer.div).focus();
      //document.getElementById(videoplayer.div).showHostCam();
      videoplayer.setHOSTControls();
			$(".show_hostcam").hide(100);
      $(".hide_hostcam").show(100);
      if (qanda != undefined) {
        console.log("VideoPlayer Publishing Host Cam");
        qanda.publishHost();
        qanda.qanda_showing = true;
      }
    }
  },

  hideHostCam: function() {
    try {
    if (swfobject != undefined) {
      swfobject.removeSWF('host_stream');
			//document.getElementById(videoplayer.div).hideHostCam();
      $(".hide_hostcam").hide(100);
      $(".show_hostcam").show(100);
      if (qanda != undefined) {
        qanda.qanda_showing = false;
      }
    }
    } catch (e) {}
  },
  
	setHOSTControls: function() {
			
			console.log("Setting Host Control");
			
			var args={"k":$("#video_data").html()};
      //videoplayer.div = 'myHost';
      
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
          id: 'myHost',
          name: 'myHost'
        };
      
      console.log('Loading '+'/flash/HostCam_v'+videoplayer.hostCamVersion+'.swf');
      //swfobject.embedSWF('/flash/Constellation_v'+videoplayer.version+'_secure.swf', 'movie_stream', '100%', '100%', '10.2.0', '/flash/expressInstall.swf', flashvars, params, attributes);
      swfobject.embedSWF('/flash/HostCam_v'+videoplayer.hostCamVersion+'.swf', 'host_stream', '290', '220', '10.2.0', '/flash/expressInstall.swf', flashvars, params, attributes);
      
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
    console.log("Video Player Cam Viewer Click");
    swfobject.removeSWF('host_stream');
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
      swfobject.removeSWF('host_stream');
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
			
			console.log("Setting View Control");
			
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
      
      console.log('Loading '+'/flash/LiveStream_v'+videoplayer.guestCamVersion+'.swf');
      //swfobject.embedSWF('/flash/Constellation_v'+videoplayer.version+'_secure.swf', 'movie_stream', '100%', '100%', '10.2.0', '/flash/expressInstall.swf', flashvars, params, attributes);
      swfobject.embedSWF('/flash/LiveStream_v'+videoplayer.guestCamVersion+'.swf', 'host_stream', '290', '220', '10.2.0', '/flash/expressInstall.swf', flashvars, params, attributes);
      
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
    console.log("Going FullScreen");
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
