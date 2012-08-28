function flashLoaded(e) {
 // e.ref is a reference to the Flash object. We'll pass it to jwplayer() so the API knows where the player is.
 //console.log("FlashLoaded");
 // Add event listeners
 
 trailer.startTimers(jwplayer(trailer.elementId));
 
 // Interact with the player
 //jwplayer(e.ref).play();
}

var trailer = {
  
  trailerFilm : $('#film').html(),
  trailerStream : $('#video').html(),
  trailerImage : $('#trailer-image').html(),
  trailerClass: '.watch_trailer',
	div: null,
	dohide: false,
	elementId: null,
	slider: null,
	
	init: function() {
		
		$( trailer.trailerClass).click(function(){
			trailer.linkclick( trailer.trailerClass );
		});
		
	},
	
	clickStart: function( element, film, stream, image ) {
		trailer.trailerFilm = film;
		trailer.trailerStream = stream;
		trailer.trailerImage = image;
		trailer.trailerClass = "#"+$(element).attr('id');
		trailer.linkclick();
	},
	
	linkclick: function() {
		$(trailer.trailerClass).fadeOut(100).queue(function(){
    	// $('.get_info').fadeOut(100); Remove fade from homepage and film page.
			trailer.startTrailer();
    	if (trailer.slider) {
    		trailer.slider.halten();
			}
			$(trailer.trailerClass).clearQueue();
		});
	},
	
  //A convencience method
  //If you don't want to add other controls to this
  startTrailer: function() {
    //console.log("Starting from Trailer");
  	$('#movie_trailer_container').css({zIndex: 100});
    trailer.populateVideo( trailer.trailerFilm, trailer.trailerStream , trailer.trailerImage);
  },
  
  populateVideo : function( film, stream, image ) {
  
    $('#video').html(stream);
    trailer.div = "movie_trailer_" + film;
    trailer.elementId = 'myPlayer_'+Math.floor(Math.random()*101)
		
		//console.log("Setting div to: " + trailer.div);
    //console.log("Stream is: " + stream);
    var auth = stream.split("%3F");
    html = '<div id="'+ trailer.div + '"><img src="'+image+'" class="widget_video_still"  border="0"/></div>';
    $('#movie_trailer_container').append(html);
    $('#movie_trailer_container').css({zIndex: 10});
    
    ///mp4:constellation/uploads/screeningResources/56/trailerFile/JIG TRAILER.mov?auth=da.b3aNadcddyd8bZdKcDaKdhcXbtbRdEcr-bopF58-uP4-HnKC1CArvxn-l8q9tUqer0tVtdnRkvrZpW%26aifp=v0006%26slist=constellation%2Fuploads%2FscreeningResources%2F56%2FtrailerFile%2FJIG+TRAILER
    var flashvars = 
        {file: auth[0]+'%3F'+ auth[1],
        'image':  $("#video_still").html(),
        streamer: 'rtmp://cp113558.edgefcs.net/ondemand'+'%3F'+auth[1],
        'skin':   '/flash/glow/glow.zip',
        'autostart': true
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
          id: trailer.elementId,
          name: trailer.elementId
        };
      
      //console.log('Loading Trailer');
      swfobject.embedSWF('/flash/mediaplayer-5.7-licensed/player.swf', trailer.div, '100%', '100%', '9.0.0', '/flash/expressInstall.swf', flashvars, params, attributes, flashLoaded);
      
      $(document).click(function(e){
    		trailer.dohide = true;
				trailer.hideVideo();
	    });
  },
  
  stopVideo : function() {
		//$('#video').html('');
		//$('#movie_trailer_container').html('');
		if (jwplayer(trailer.elementId) != null) {
			jwplayer(trailer.elementId).remove();
		}
		swfobject.removeSWF(trailer.elementId);
    //console.log('Removed Player');
  },
  
  startTimer : function() {
  	trailer.dohide = true;
		window.setTimeout(trailer.hideVideo, 2000);
	},
	
	endTimer : function() {
  	trailer.dohide = false;
	},
	
  hideVideo : function() {
		if (trailer.dohide) {
			trailer.stopVideo();
			// $('.get_info').fadeIn(100);
			$(trailer.trailerClass).fadeIn(100);
	  	if (trailer.slider) {
	  		trailer.slider.anfagen();
	
			}
		}
  	$('#movie_trailer_container').css({zIndex: -2});
	},
	
	startTimers : function(player) {
		//console.log("Starting Timers");
		
 		player.onReady(function() { 
		 	//console.log('Player is ready'); 
		});
 		player.onPlay(function() { 
			//console.log('Player is playing'); 
			trailer.endTimer()
		});
		player.onPause(function(){
			//console.log('Player is paused');
			trailer.startTimer()
		});
		player.onComplete(function(){
			//console.log('Player is done');
			trailer.startTimer()
		});

	}
  
}

$(document).ready(function() {
		
    if (!window.console) window.console = {};
    if (!window.console.log) window.console.log = function() {};
    
    if (window.location.pathname.match(/film/)) {
      
      //trailer.init();
      //trailer.startTrailer();
      
    }

});

