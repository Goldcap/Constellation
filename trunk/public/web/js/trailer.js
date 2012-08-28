var trailer = {
  
  trailerPopup : $('#trailer-popup'),
  trailerFilm : $('#video').html(),
  trailerStream : $('#video').html(),
  trailerImage : $('#trailer-image').html(),
	
  //A convencience method
  //If you don't want to add other controls to this
  startWindow: function() {
    console.log("Starting from Trailer");
    trailer.trailerPopup.fadeIn("slow");
    trailer.populateVideo( trailer.trailerFilm, trailer.trailerStream , trailer.trailerImage);
  },
  
  //A convencience method
  //If you don't want to add other controls to this
  stopWindow: function() {
    trailer.stopVideo();
    trailer.trailerPopup.fadeOut("slow");
  },
  
  populateVideo : function( film, stream, image ) {
    $('#video').html(stream);
    videoplayer.div = "movie_trailer_" + film;
    console.log("Setting div to: " + videoplayer.div);
    html = '<div id="'+ videoplayer.div + '"><img src="'+image+'" class="widget_video_still"  border="0"/></div>';
    $('#movie_trailer_container').append(html);
    videoplayer.initPlayer();
  },
  
  stopVideo : function() {
		$('#video').html('');
		$('#movie_trailer_container').html('');
  }
  
}

$(document).ready(function() {
    if (!window.console) window.console = {};
    if (!window.console.log) window.console.log = function() {};
    
    if (window.location.pathname.match(/film/)) {
      
      $("#start_trailer").click(function() {
        trailer.startWindow();
      });
      
      $("#trailer-popup .carousel-popup-close a").click(function() {
        trailer.stopWindow();
      });
    }

});

