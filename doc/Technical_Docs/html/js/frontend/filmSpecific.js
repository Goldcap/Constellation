bindHostInfoEvents = function(){
	
	$(".host-info").click(function(e){
		var linkId = $(this).attr('id');
		linkId = linkId.replace('trigger-', '');
		screeningId = linkId;
		
		$('.about-the-film').hide();
		$('.screening-info').hide();
		$('#hidden-info-' + linkId).show();
		$('.screening-activity-box .overlay').show();
		$('.reviews').hide();
		
		ajaxRefresh(0, linkId);
		e.stopPropagation();
		
		return false;
	});
	$(".hover-title").hover(function(e){
		var linkId = $(this).attr('id');
		linkId = linkId.replace('trigger-', '');
		$('.filmTitle').hide();
		$('.hover-title-date').hide();
		$('#hidden-date-'+linkId).show();

		e.stopPropagation();
	},
	function (e) {
		//$(this).find("span:last").remove();
		$('.hover-title-date').hide();
		$('.filmTitle').show();
	
		e.stopPropagation();
		return false;
		
	});	
	$('#see-more-review').click(function(e) {
			$('.hidden-reviews').show();
	});
	
};

$(document).ready(function(){
	bindHostInfoEvents();
});