contactServer = function(){
	$.head(contactUrl+"?screeningId="+screeningId+"&key="+seatKey+"&seatId="+seatId,{},
			function(headers) { });
}
$(document).ready(function(){
	setInterval( "contactServer()", contactInterval);
});