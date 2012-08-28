initializeCounter = function(date){
	//var startDate = new Date(date);
	$('#countdown').countdown({layout: '<span style ="text-transform:lowercase">{d<}{dn} {dl} {d>}'+'{hn} {hl}, {mn} {ml}, {sn} {sl}</span>', until: startDate, format: 'DHMS', onExpiry: function(){ if(!screeningEnded) { startFilmScreening(); } }});
};

$(document).ready(function(){
	
	if(typeof startDate != 'undefined' && startDate.length)
	{
		try {
			initializeCounter(startDate);
		}
		catch(err)
		{};
	}
	
});
