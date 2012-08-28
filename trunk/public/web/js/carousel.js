carousel_auto_increment = 6;

var carousel = {
  
  home_carousel : null,
  films: null,
  auto_increment: 6,
  
  currentFilm: null,
  
  //Setup Our Initial Carousel Object
  setOpts: function() {
    
    carousel.overlay = $('#carousel ul li .overlay');
    carousel.carouselPopup = $('#carousel-popup');
    carousel.trailerPopup = $('#trailer-popup');
    carousel.carouselOverlay = $('.jcarousel-container #carousel-overlay');
    
    carousel.firstVisibleId = '';
    carousel.lastVisibleId = '';
    
    carousel.zoomOutProgress = false;
    carousel.reflectOptions = {height:'55',opacity:0.4};
  
    carousel.zoomInDefaultOptions = {width: '183px', height: '267px', left: '-7px', top: '-21px'};
    carousel.zoomInFirstOptions = {width: '183px', height: '267px', left: '6px', top: '-21px'};
    carousel.zoomInLastOptions = {width: '183px', height: '267px', left: '-20px', top: '-21px'};
    carousel.zoomOutOptions = {width: '163px', height: '235px', left: '0px', top: '0px'};
    
    // Reflection zoom Options
    carousel.rZoomDefaultOptions = {width: '183px', left: '-7px'};
    carousel.rZoomFirstOptions = {width: '183px', left: '6px'};
    carousel.rZoomLastOptions = {width: '183px', left: '-20px'};
    carousel.rZoomOutOptions = {width: '163px', left: '0px'};
  },
	
  //Setup Our Initial Carousel Object
  init: function() {
    
    carousel.films = cfilms;
    
    $('#main_carousel').jcarousel(carousel.jCarouselOptions);
	  if($.browser.msie) {
  		carousel.carouselOverlay.css('z-index', '-1');
  	}
  	$('.jcarousel-container').append('<div id="carousel-overlay" class="overlay"></div>');
	   
	  
  },
  
  reset: function() {
    
    carousel.firstVisibleId = $("#carousel ul li:first").attr("id");
    //console.log("First is " + carousel.firstVisibleId);
  	$('#carousel .jcarousel-container img.reflected').reflect(carousel.reflectOptions);
	  $('#carousel').css('overflow','visible');
	  
    $('#carousel_trailer_link').click(function() {
      carousel.populateVideo();
    });
    
  },
  
  //Bind the Carousel to the Enter/Exit Features
  bind : function() {
    
    $('#carousel img.reflected').mouseenter(function(){
	
      carousel.zoomOutProgress = false;
  		var el = $(this);
  		var parentId = el.parent().parent()[0].id;
  		carousel.carouselOverlay.show();
  		carousel.overlay.show();
  		$('#' + parentId + ' .overlay').hide();
  		
      var elReflection = $('#' + parentId + ' .canvas');
  		var zoomOptions = carousel.getZoomOptions(parentId);
  		
  		var reflectZoomOptions = carousel.getReflectZoomOptions(parentId);
  		$('#' + parentId).css('z-index', '4');
  		
      el.animate(zoomOptions, function(){
  			carousel.toggleImgContainer(parentId, 'in');
  		}).after(function(){
  			elReflection.animate(carousel.reflectZoomOptions);
  			changeScreeningDate($(this).attr('index'));
  		});
  	}).mouseleave(function(){
  		
      carousel.zoomOutProgress = true;
  		var el = $(this);
  		var parentId = el.parent().parent()[0].id;
  		var elReflection = $('#'+parentId+' .canvas');
  		carousel.toggleImgContainer(parentId, 'out');
  		$('#'+parentId).css('z-index', '2');
  		el.animate(carousel.zoomOutOptions, function(){
  			carousel.carouselOverlay.hide();
  		}).after(function(){
  			elReflection.animate(carousel.rZoomOutOptions);
  		});
  	});
  },
  
  jCarouselOptions : {
   scroll: 1,
   auto: carousel_auto_increment,
   wrap: 'last',
   initCallback: initCallback,
	 itemFirstInCallback: {onAfterAnimation: findFirstVisible }, 
	 itemLastInCallback: {onAfterAnimation: findLastVisible }
  },
  
  link: function() {
    
    $('#carousel img.reflected').click(function(){
  		carousel.populatePopup($(this));
  		carousel.carouselPopup.fadeIn();
  	});
  	
  	$('#carousel-popup .pop_mid .carousel-popup-close a').click(function(e){
  		e.preventDefault();
  		carousel.carouselPopup.fadeOut();
  	});
  	
  	$('#trailer-popup .pop_mid .carousel-popup-close a').click(function(e){
  		e.preventDefault();
  		carousel.stopVideo();
  	});
  },
  
  toggleImgContainer : function(parent, effect) {
    
    var image = $('#'+parent+' .img-container');
  	
  	if(effect == 'in') {
  		if (parent == carousel.firstVisibleId) {
  			image.removeClass('img-container-last').addClass('img-container-first');
  		}
  		else if(parent == lastVisibleId) {
  			image.removeClass('img-container-first').addClass('img-container-last');
  		} else {
  			image.removeClass('img-container-first img-container-last');
  		}
  		
  		if (carousel.zoomOutProgress) {
  			image.hide();
  		} else {
  			image.fadeIn('fast');
  		}
  	} else {
  		image.hide();
  	}
  },

  getZoomOptions : function(id) {
  	if(id == carousel.firstVisibleId) {
  		return carousel.zoomInFirstOptions;
  	} else if (id == lastVisibleId) {
  		return carousel.zoomInLastOptions;
  	} else {
  		return carousel.zoomInDefaultOptions;
  	}
  },
  
  getReflectZoomOptions : function(id) {
  	if(id == carousel.firstVisibleId) {
  		return carousel.rZoomFirstOptions;
  	} else if (id == carousel.lastVisibleId) {
  		return carousel.rZoomLastOptions;
  	} else {
  		return carousel.rZoomDefaultOptions;
  	}
  },
  
  populatePopup : function( elem ) {
    	
    	filmId = elem.attr('index');
    	filmType = elem.attr('type');
    	filmShort = elem.attr('name');
    	
      console.log("carousel populate");
    
    	carousel.currentFilm = filmId;
    	
      var photoContainer = $('#carousel_photo_container');
    	var movieTitle = $('#carousel_film_title');
    	var movieDesc = $('#carousel_film_description');
    	var movieInfo = $('#carousel_film_info');
    	var movieScreenings = $('#carousel_movie_link');
    	//var allShows = $('#carousel_film_link');
    	var btnPlayTrailer = $('#carousel_film_link');
    	var btnHostYourOwn = $('#carousel_host_link');
    	var allowHostYourOwn = carousel.films[filmId]['allow_hosting'];
    	var showtimesHtml = '';
    	var showtimesHoursHtml = '';
    	var showtimesTemplate = '<div class="date tzDate" name="{$insert_date_edt_timestamp}" lang="mmm dd, yyyy">{$insert_date_here}</div><div class="hours">{$insert_hours_here}</div>';
      
      //Reset this link, just in case it was hidden earlier
      $("#carousel_host_link").show();
      
    	photoContainer.attr('src', carousel.films[filmId]['logo_src']);
    	//allShows.attr('href', '/film/'+filmId);
    	if (filmType == 'programResources') {
        btnHostYourOwn.attr('href', '/film/'+filmShort+'/host_screening');//hostYourOwn+filmId);
        btnPlayTrailer.attr('href', '/film/'+filmShort);
    	} else {
        btnHostYourOwn.attr('href', '/film/'+carousel.films[filmId]['id']+'/host_screening');//hostYourOwn+filmId);
        btnPlayTrailer.attr('href', '/film/'+carousel.films[filmId]['id']);
    	}
      movieTitle.html(carousel.films[filmId]['name']);
    	movieDesc.html(carousel.films[filmId]['synopsis']);
    	movieInfo.html(carousel.films[filmId]['info']);
    	
    	// get movie screenings for the current day
    	var showTimes = '';
    	var currentDate = new Date();
    	var year = currentDate.getFullYear();
    	var month = currentDate.getMonth() + 1;
    	if(month < 10) // put 0 in front of 1-digit month
    	{
    		month = "0" + month;
    	}
    	var date = currentDate.getDate();
    	if(date < 10) // put 0 in front of 1-digit date
    	{
    		date = "0" + date;
    	}
    	currentDate = year + "-" + month + "-" + date;
    	
    	//If this film isn't "hostable", hide that button
    	if (allowHostYourOwn == "0") {
        $("#carousel_host_link").hide();
      }
    	
    	if ( carousel.films[filmId]['screenings'] != undefined ) {
      	var found = false;
      	for(var i = 0; i < carousel.films[filmId]['screenings'].length; i++) {
      		var show = carousel.films[filmId]['screenings'][i];
      		if(show['date'] === currentDate) {
      			if(!found)
      			{
      				showTimes = "Today's Showtimes: ";
      				found = true;
      				showTimes = showTimes + '<a class="link" href="/screening/' + show['unique_key'] + '">' + show['date_time_with'].substr(-7) + '</a>';
      			} else {
      				showTimes = showTimes + '; <a class="link" href="/screening/' + show['unique_key'] + '">' + show['date_time_with'].substr(-7) + '</a>';
      			}
      		}
      	}
    	
      	if(found) {
      		// get the timezone
      		showTimes = showTimes + carousel.films[filmId]['screenings'][0]['timezone'];
      	}
      	movieScreenings.html(showTimes);
    	}
    	
    	if ( carousel.films[filmId]['screenings_per_day'] != undefined ) {
      	var rowIt = 0;
      	for(screeningDate in carousel.films[filmId]['screenings_per_day']) {
      		if(rowIt < 3)
      		{
      			var colIt = 0;
      			if(colIt < 6)
      			{
      				showtimesHtml = showtimesHtml + showtimesTemplate.replace('{$insert_date_here}', screeningDate);
      				showtimesHtml = showtimesHtml.replace('{$insert_date_edt_timestamp}', carousel.films[filmId]['screenings_per_day'][screeningDate][0]['date_edt_timestamp']);
      				showtimesHoursHtml = '';
      				for(it in carousel.films[filmId]['screenings_per_day'][screeningDate])
      				{
      					for(screeningHour in carousel.films[filmId]['screenings_per_day'][screeningDate][it])
      					{
      						if(screeningHour == 'time') {
      							var screeningObj = carousel.films[filmId]['screenings_per_day'][screeningDate][it];
      							if(screeningObj.type == 0) {
      								showtimesHoursHtml = showtimesHoursHtml + '<a href="'+screeningObj.url+'" class="tzDate" name="'+screeningObj.edt_timestamp+'" lang="h:MMTT">'+screeningObj.time + '</a>, ';
      							} else {
      								showtimesHoursHtml = showtimesHoursHtml + '<span class="tzDate" name="'+screeningObj.edt_timestamp+'" lang="h:MMTT">' + screeningObj.time + '</span>, ';
      							}
      						}
      					}
      				}
      				colIt = colIt + 1;
      			}
      			rowIt = rowIt + 1;
      			showtimesHoursHtml = showtimesHoursHtml.substring(0, showtimesHoursHtml.length - 2);
      			showtimesHtml = showtimesHtml.replace('{$insert_hours_here}', showtimesHoursHtml);
      		}
      	}
      	
      	if(rowIt != 0) {
      		$('#showtimes-holder').html(showtimesHtml);
      	} else {
      		$('#showtimes-holder').html('No upcoming screenings');
      	}
      }
    },
    
    populateVideo : function() {
      carousel.carouselPopup.fadeOut();
      carousel.trailerPopup.fadeIn();
      trailer.populateVideo(carousel.currentFilm,carousel.films[carousel.currentFilm]['stream_url'],carousel.films[carousel.currentFilm]['logo_src']);
    },
    
    stopVideo : function() {
      trailer.stopVideo();
  		carousel.trailerPopup.fadeOut();
  		carousel.carouselPopup.fadeIn();
    },
    
    initAuto: function() {
      //console.log("starting auto with " + carousel_auto_increment);
      carousel.home_carousel.startAuto(carousel_auto_increment);
    }
}

var changeScreeningDate = function(filmId) {
	$('#screening_date').html('<span>'+films[filmId]["name"]+':</span> '+(typeof films[filmId]["screening_date_string"] != "undefined" ? films[filmId]["screening_date_string"] : ''));
};

function findFirstVisible(carousel, liObject, index, action) {
 firstVisibleId = liObject.id;
};

function findLastVisible (carousel, liObject, index, action) {
 lastVisibleId = liObject.id;
};


function initCallback (acarousel){

  carousel.home_carousel = acarousel;
  
  // Disable autoscrolling if the user clicks the prev or next button.
  acarousel.buttonNext.bind('click', function() {
      acarousel.startAuto(0);
      window.setTimeout(function(){acarousel.startAuto(6)}, 6000);
  });

  acarousel.buttonPrev.bind('click', function() {
      acarousel.startAuto(0);
      window.setTimeout(function(){acarousel.startAuto(6)}, 6000);
  });

  // Pause autoscrolling if the user moves with the cursor over the clip.
  acarousel.clip.hover(function() {
      acarousel.stopAuto();
  }, function() {
      acarousel.startAuto();
  });
}
  
  
$(document).ready(function(){
	if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
  
  carousel.setOpts();
  carousel.init();
  carousel.reset();
	carousel.bind();
	carousel.link();
	
});
