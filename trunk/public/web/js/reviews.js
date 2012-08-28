var reviews = {
  
  currentPosition: 0,
  slideWidth: 650,
  slides: $('.slide'),
  numberOfSlides: 0,
  timeout: 10000,
  
  init: function() {
    
    if ($("#slideshow #slidesContainer .slide").length > 0) {
      reviews.slideWidth = parseInt($("#slideshow #slidesContainer .slide").css("width").replace("px",""));
    }
    
    reviews.numberOfSlides = reviews.slides.length;
    
    if (reviews.numberOfSlides == 0) {
      return;
    }
    
    // Remove scrollbar in JS
    $('#slidesContainer').css('overflow', 'hidden');

    // Wrap all .slides with #slideInner div
    reviews.slides.wrapAll('<div id="slideInner"></div>').css({
      'float' : 'left',
      'width' : reviews.slideWidth
    });
  
    // Set #slideInner width equal to total width of all slides
    $('#slideInner').css('width', reviews.slideWidth * reviews.numberOfSlides);
  
    // Hide left arrow control on first load
    window.setTimeout(reviews.moveSlideRight, reviews.timeout);
  },

  // Create event listeners for .controls clicks
  moveSlideRight: function() {
    // Determine new position
    //console.log("Current position: " + reviews.currentPosition);
    //console.log("Number of slides: " + reviews.numberOfSlides);
    reviews.currentPosition = (reviews.currentPosition == (reviews.numberOfSlides-1)) ? 0 : reviews.currentPosition+1;
    //console.log("New position: " + reviews.currentPosition);
  
    // Move slideInner using margin-left
    $('#slideInner').animate({
      'marginLeft' : reviews.slideWidth*(-reviews.currentPosition)
    });
    window.setTimeout(reviews.moveSlideRight, reviews.timeout);
  }
}

//http://sixrevisions.com/tutorials/javascript_tutorial/create-a-slick-and-accessible-slideshow-using-jquery/
$(document).ready(function(){
  if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
  
  reviews.init();

});
