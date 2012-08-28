var reflectOptions = {height:'45',opacity:0.4};
var toggleImgContainer = function(parrentId, effType)
{
	var imgContainer = $('#'+parrentId+' .img-container');
	
	if(effType == 'in')
	{
		if(parrentId == firstVisibleId)
		{
			imgContainer.removeClass('img-container-last').addClass('img-container-first');
		}
		else if(parrentId == lastVisibleId)
		{
			imgContainer.removeClass('img-container-first').addClass('img-container-last');
		}
		else
		{
			imgContainer.removeClass('img-container-first img-container-last');
		}
		
		if (zoomOutProggress)
		{
			imgContainer.hide();
		}
		else
		{
			imgContainer.fadeIn('fast');
		}
	}
	else if(effType == 'out')
	{
		imgContainer.hide();
	}
	
};

var populatePopup = function(filmId)
{
	var photoContainer = $('#carousel-popup #photo-container');
	var btnPlayTrailer = $('#carousel-popup .btn-showtimes-trailer');
	var movieTitle = $('#carousel-popup #movie-title');
	var movieDesc = $('#carousel-popup #movie-desc');
	var movieScreenings = $('#carousel-popup #movie-screens');
	var btnHostYourOwn = $('#carousel-popup .btn-host-your-own');
	var showtimesHtml = '';
	var showtimesHoursHtml = '';
	
	photoContainer.attr('src', films[filmId]['logo_src']);
	btnPlayTrailer.attr('href', filmUrl+'/'+filmId);
	btnHostYourOwn.attr('href', '#');//hostYourOwn+filmId);
	movieTitle.html(films[filmId]['name']);
	movieDesc.html(films[filmId]['synopsis']);
	
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
	
	var found = false;
	for(var i = 0; i < films[filmId]['screenings'].length; i++)
	{
		var show = films[filmId]['screenings'][i];
		if(show['date'] === currentDate)
		{
			if(!found)
			{
				showTimes = "Today's Showtimes: ";
				found = true;
				showTimes = showTimes + '<a class="link" href="' + showtimesUrl + show['unique_key'] + '/self_invited/true">' + show['date_time_with'].substr(-7) + '</a>';
			}
			else
			{
				showTimes = showTimes + '; <a class="link" href="' + showtimesUrl + show['unique_key'] + '/self_invited/true">' + show['date_time_with'].substr(-7) + '</a>';
			}
		}
	}
	if(found)
	{
		// get the timezone
		showTimes = showTimes + films[filmId]['screenings'][0]['timezone'];
	}
	movieScreenings.html(showTimes);
	
	var rowIt = 0;
	for(screeningDate in films[filmId]['screenings_per_day'])
	{
		if(rowIt < 3)
		{
			var colIt = 0;
			if(colIt < 6)
			{
				showtimesHtml = showtimesHtml + showtimesTemplate.replace('{$insert_date_here}', screeningDate);
				showtimesHtml = showtimesHtml.replace('{$insert_date_edt_timestamp}', films[filmId]['screenings_per_day'][screeningDate][0]['date_edt_timestamp']);
				showtimesHoursHtml = '';
				for(it in films[filmId]['screenings_per_day'][screeningDate])
				{
					for(screeningHour in films[filmId]['screenings_per_day'][screeningDate][it])
					{
						if(screeningHour == 'time')
						{
							var screeningObj = films[filmId]['screenings_per_day'][screeningDate][it];
							
							if(screeningObj.type == publicScreeningsType)
							{
								showtimesHoursHtml = showtimesHoursHtml + '<a href="'+screeningObj.url+'" class="tzDate" name="'+screeningObj.edt_timestamp+'" lang="h:MMTT">'+screeningObj.time + '</a>, ';
							}
							else
							{
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
	if(rowIt != 0)
	{
		$('#showtimes-holder').html(showtimesHtml);
	}
	else
	{
		$('#showtimes-holder').html('No upcoming screenings');
	}
};

var changeScreeningDate = function(filmId)
{
	$('#screening_date').html('<span>'+films[filmId]["name"]+':</span> '+(typeof films[filmId]["screening_date_string"] != "undefined" ? films[filmId]["screening_date_string"] : ''));
};

var jCarouselOptions = {
	itemFirstInCallback: {onAfterAnimation: findFirstVisible}, 
	itemLastInCallback: {onAfterAnimation: findLastVisible}
};

$(document).ready(function(){
	var carousel = $('#carousel ul').jcarousel(jCarouselOptions);
	$('.jcarousel-container').append('<div id="carousel-overlay" class="overlay"></div>');
	
	var carouselOverlay = $('.jcarousel-container #carousel-overlay');
	if($.browser.msie)
	{
		carouselOverlay.css('z-index', '-1');
	}
	var overlay = $('#carousel ul li .overlay');
	var mainOverlay = $('#main-overlay');
	var carouselPopup = $('#carousel-popup');
	
	$('#carousel .jcarousel-container img.reflected').reflect(reflectOptions);
	$('#carousel img.reflected').mouseenter(function(){
		zoomOutProggress = false;
		var el = $(this);
		var parrentId = el.parent().parent()[0].id;
		carouselOverlay.show();
		overlay.show();
		$('#' + parrentId + ' .overlay').hide();
		var elReflection = $('#' + parrentId + ' .canvas');
		var zoomOtions = getZoomOptions(parrentId);
		var reflectZoomOptions = getReflectZoomOptions(parrentId);
		$('#' + parrentId).css('z-index', '4');
		el.animate(zoomOtions, function(){
			toggleImgContainer(parrentId, 'in');
		}).after(function(){
			elReflection.animate(reflectZoomOptions);
			changeScreeningDate($(this).attr('index'));
		});
	}).mouseleave(function(){
		zoomOutProggress = true;
		var el = $(this);
		var parrentId = el.parent().parent()[0].id;
		var elReflection = $('#'+parrentId+' .canvas');
		toggleImgContainer(parrentId, 'out');
		$('#'+parrentId).css('z-index', '2');
		el.animate(zoomOutOptions, function(){
			carouselOverlay.hide();
			overlay.hide();
		}).after(function(){
			elReflection.animate(rZoomOutOptions);
		});
	});
	
	$('#carousel img.reflected').click(function(){
		populatePopup($(this).attr('index'));
		mainOverlay.show();
		carouselPopup.fadeIn();
	});
	
	$('#carousel-popup .x a').click(function(e){
		e.preventDefault();
		carouselPopup.fadeOut();
		mainOverlay.hide();
	});
	
	$("#info-make-money").click( function(e) {
		togglePopup('make-money');
	});
	
	if(countFeaturedScreenings > 0) {
		$('#featuredScreeningsList').jcarousel({
        vertical: true,	//http://sorgalla.com/projects/jcarousel/#Configuration
		wrap: 'circular',
        scroll: 1,
		animation: 'slow',
		auto: 4,	//seconds
		size: 3
    	});
	}
	
	function showHeight(ele, h) {
      $("div").text("The height for the " + ele + " is " + h + "px.");
    }

    $("#featuredScreeningsList ul li").bind(function () { 
      showHeight("carouselul are inaltimea de: ", $("#featuredScreeningsList").height()); 
    });
  

});
