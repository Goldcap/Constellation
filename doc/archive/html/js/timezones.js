/*
 * Accepts a date, a mask, or a date and a mask.
 * Returns a formatted version of the given date.
 * The date defaults to the current date/time.
 * The mask defaults to dateFormat.masks.default.
 */

$(window).load(function(){
	 if (!window.console) window.console = {};
   if (!window.console.log) window.console.log = function() {};
 
   $("#timezone-select").click(function(e){
  		if (e != undefined)
  			e.stopPropagation();
			
			$("#timezone-select-popup").fadeIn(100);
			
			modal.modalIn();
			
      $(".modal").click(function(e){
      	$("#timezone-select-popup").fadeOut(100);
	    	modal.modalOut();
	    });
   });
   
   $("#timezone-popup-close").click(function(){
      $("#timezone-select-popup").fadeOut(100);
   });
   
   
   $("#timezone-button").click(function(e) {
     $("#timezone_form").submit();
   });
   
   /*DO THIS AT SOME POINT
   $.ajax({
		   type: "POST",
		   url: changeTimezoneUrl,
		   data: "tz_option="+tzId
		 });
	*/
});
