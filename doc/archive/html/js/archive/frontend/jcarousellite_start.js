//JCarousel Junk
// Image zoom Options
var zoomInDefaultOptions = {width: '153px', height: '228px', left: '-7px', top: '-21px'};
var zoomInFirstOptions = {width: '153px', height: '228px', left: '6px', top: '-21px'};
var zoomInLastOptions = {width: '153px', height: '228px', left: '-20px', top: '-21px'};
var zoomOutOptions = {width: '139px', height: '207px', left: '0px', top: '0px'};

// Reflection zoom Options
var rZoomDefaultOptions = {width: '153px', left: '-7px'};
var rZoomFristOptions = {width: '153px', left: '6px'};
var rZoomLastOptions = {width: '153px', left: '-20px'};
var rZoomOutOptions = {width: '139px', left: '0px'};

var firstVisibleId = '';
var lastVisibleId = '';

var zoomOutProggress = false;

var findFirstVisible  = function(carousel, liObject, index, action)
{
	firstVisibleId = liObject.id;
};

var findLastVisible  = function(carousel, liObject, index, action)
{
	lastVisibleId = liObject.id;
}

var getZoomOptions = function(id)
{
	if(id == firstVisibleId)
	{
		return zoomInFirstOptions;
	}
	else if (id == lastVisibleId)
	{
		return zoomInLastOptions;
	}
	else
	{
		return zoomInDefaultOptions;
	}
}
var getReflectZoomOptions = function(id)
{
	if(id == firstVisibleId)
	{
		return rZoomFristOptions;
	}
	else if (id == lastVisibleId)
	{
		return rZoomLastOptions;
	}
	else
	{
		return rZoomDefaultOptions;
	}
};

$(document).ready(function(){
	if (featuredScreeningsCarousel) 
	{
		$(".newsticker-jcarousellite").jCarouselLite({
			vertical: true,
			hoverPause: true,
			visible: 3,
			auto: 4000, //set the automatically speed for Featuring Screenings Carousel
			speed: 2300 //set the wanted speed for Featuring Screenings Carousel
		});
	}
});
