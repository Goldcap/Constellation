var screenings_carousel = {
  
  //Setup Our Initial Carousel Object
  init: function() {
    if ($('#barousel_thslide').length > 0) {
    //http://labs.juliendecaudin.com/barousel/#th_download
    /*
		$('#barousel_thslide').barousel({
        navWrapper: '#thslide_barousel_nav .thslide_hidden',
        manualCarousel: 1,
        navType: 3
    });
		*/
		
    $('#thslide_barousel_nav').thslide({
        infiniteScroll: 1,
        itemOffset: 280
    });
   }
  }

}

$(document).ready(function(){
	if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
  //console.log("Screenings Carousel");
	if ($('#barousel_thslide').length > 0) {
	 screenings_carousel.init();
	}
});
