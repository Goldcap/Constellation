initPopup = function(){
	
	// remove all popup overlay divs, except one
	$('.popup-overlay:not(:first)').remove();
	
	//move popups as direct children of body element
	$('.popup-container').each(function(){
		var popup = $(this);
		
		popup.css('left', ($(window).width() - popup.width()) / 2 + 'px'); 
		popup.css('top', ($(window).height() - popup.height()) / 2 + 'px');
	});
}

// function that moves the popup according to { top, left }
togglePopup = function(id) {
	$('.popup-overlay').toggle();
	$('#' + id + '-popup').fadeToggle('normal');
	
	$('#' + id + '-popup a.popup-close').click(function() {
		$('#' + id + '-popup').fadeOut('fast', function(){ $('.popup-overlay').hide(); });
	});
}

movePopup = function(id, top, left){
	// change top position
	if(top)
	{
		$(id).css('top', top + 'px');
	}
	// change left position
	if(left)
	{
		$(id).css('left', left + 'px');
	}
}

jQuery.fn.fadeToggle = function(speed, easing, callback) {
   return this.animate({opacity: 'toggle'}, speed, easing, callback);
};

$(document).ready(function(){	
	initPopup();
	//bindPopupEvents();
});
