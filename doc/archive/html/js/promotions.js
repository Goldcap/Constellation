var promotions = {
  
  slide: 0,
  delay: 1000,
  
  init: function() {
    $('.slideshow').fadeIn("slow");
    
    console.log('Current Slide: '+promotions.slide);
    if (promotions.slide == 0) {
      promotions.showSlide(1);
    }
    
  },
  
  update: function() {
    $(".slideshow div:nth-child("+(promotions.slide - 1)+")").hide();
    promotions.showSlide(promotions.slide);
  },
  
  showSlide: function( seq ) {
    console.log('Showing Slide: '+seq);
    if ($(".slideshow div:nth-child("+seq+")").length == 0) {
      //Time for the Q and A!
      timekeeper.startQA();
      return;
    }
    $(".slideshow div:nth-child("+seq+")").fadeIn("slow");
    promotions.delay = $(".slideshow div:nth-child("+seq+")").attr("delay");
    promotions.slide = seq + 1;
    window.setTimeout(promotions.update, promotions.delay);
  } 
}

$(document).ready(function(){
  if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
  
  //promotions.init();
  
});
