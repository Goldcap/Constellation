// JavaScript Document

$(document).ready(function(){
	
	genre_search.init();
	
});

var genre_search = {
  
  init: function() {
    
    $('#gv').keydown(function( e ) {
      var keyCode = e.keyCode || e.which;
      if (keyCode == 13) {
        $.ajax({url: '/services/CarouselSearch/'+$("#gv").val(), 
          dataType: "text", 
          type: "GET",
          success: function(response) {
           carousel_auto_increment = 0;
        	 genre_search.populateCarousel(response);
          }, 
          error: function(response) {
        	 //console.log("ERROR:", response)
          }
        });
        return false;
      }
      
    });

    $("#gs").click(function() {
      $("#drop_genre").toggle();
    });
    
    $("#gg").click(function() {
      $.ajax({url: '/services/CarouselSearch/'+$("#gv").val(), 
        dataType: "text", 
        type: "GET",
        success: function(response) {
         carousel_auto_increment = 0;
      	 genre_search.populateCarousel(response);
        }, 
        error: function(response) {
      	 //console.log("ERROR:", response)
        }
      });
    });
    
    $(".drop_genre li").click(function(e) {
      //console.log("clicked "+$(this).attr("id"));
      if ($(this).attr("id") == 'all') {
        $.ajax({url: '/services/FeaturedSearch', 
          dataType: "text", 
          type: "GET",
          success: function(response) {
           $("#drop_genre").toggle();
           carousel_auto_increment = 6;
        	 genre_search.populateCarousel(response);
          }, 
          error: function(response) {
        	 error.showError('notice','communication error','Please try again'); 
          }
        });
      } else {
        $.ajax({url: '/services/GenreSearch/'+$(this).attr("id"), 
          dataType: "text", 
          type: "GET",
          success: function(response) {
        	 $("#drop_genre").toggle();
           carousel_auto_increment = 0;
           genre_search.populateCarousel(response);
          }, 
          error: function(response) {
           error.showError('notice','communication error','Please try again'); 
        	 //console.log("ERROR:", response);
          }
        });
      }
    });
  },
  
  populateCarousel: function(response) {
    response = eval("(" + response + ")");
    if (response.result == undefined) {
      //carousel.films = response;
      //console.log("Have "+ $("#main_carousel li").length);
      
      carousel.initAuto();
      $("#main_carousel li").remove();
      
      i = 0;
      for (film in response){
        node = '<li id="film_'+film+'"><div class="img-container"></div><img class="reflected" index="'+film+'" src="'+response[film]["logo_src"]+'" width="163" height="235" alt="" /><div class="overlay"></div></li>';
        carousel.home_carousel.add(i+1,node);
        i++;
      }
      carousel.home_carousel.size(i);
      
      $('#carousel .jcarousel-container img.reflected').reflect(carousel.reflectOptions);
      
      var amt = $('#main_carousel').css("left").replace("px","");
      
      if (parseInt(amt) < 0) {
        $('#main_carousel').css("left",-175);
      }
      carousel.home_carousel.scroll(0);
       
      carousel.bind();
      carousel.reset();
      carousel.link();
      
    } else {
      error.showError('notice','No results.','That search found no films. Please try again'); 
      //console.log("No Results");
    }
    /*
    $("#main_carousel li").each(function(){
      var id = $(this).attr("id").split('_');
      if (response[id[1]] == undefined) {
        $(this).css("display","none");
      } else {
        $(this).css("display","block");
      }
    });
    */
  }
}
