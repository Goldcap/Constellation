
changeDisplayedScreening = function(dateText, inst) {

	$.ajax({
		url: updateScreeningsUrl,
		type: "POST",
		data: 'date=' + dateText + '&film_id=' + filmId + '&included_from=' + includedFrom,
		success: function(response){
			$('#showtimes-container').html(response);
			$('#showtimes-container').jScrollPane();
			//if(includedFrom.length == 0)
			//{
				bindHostInfoEvents();
			//}
		}
	});
};


$(document).ready( function() {
	
	$('#showtimes-container').css('height', $('#showtimes-container').height());
	
	
	$("#click-more-showtimes").click(function (e) {
		e.preventDefault();
		$("#fld-date-for-showtimes").focus();		
	});
	
	// about Q&A sidepanel
	$('#about-qanda').click(function(){
		togglePopup('about-qanda');
	});
	
	// what is this link (on setup page)
	$('#about-setup').click(function(){
		togglePopup('about-setup');
	});
	
});


