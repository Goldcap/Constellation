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
