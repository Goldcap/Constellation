// JavaScript Document
var countdown_alt = {
  
  init: function(id,adate) {
    //console.log("Init " + id + " with " + adate);
    var times = adate.split('|');
    var td = new Date(times[0], times[1]-1, times[2], times[3], times[4], times[5]);
    
    //try {
	  $('#timekeeper_'+id).countdown({
        layout: '<span style ="text-transform:lowercase">{dnn}<span class="shorty">DAYS</span>{hnn}<span class="shorty">HRS</span>{mnn}<span class="shorty">MIN</span>{snn}<span class="shorty">s</span></span>', 
        until: td,
        serverSync: getCurrentTime,
        format: 'DHMS'
        //,timezone: $("#tz_offset").html()
    });
  }
}

// JavaScript Document
var remaining_alt = {
  
  init: function() {
    
    $(".time_remaining").each(function() {
    	var times = $(this).html().split('|');
			
    	var td = new Date(times[0], times[1]-1, times[2], times[3], times[4], times[5]);
    	
			//try {
		  $($(this)).countdown({
	        layout: '{hnn}:{mnn}:{snn} Elapsed', 
	        since: td,
        	serverSync: getCurrentTime,
	        format: 'HMS'
	        //onTick: remaining_alt.tickin
	        //,timezone: $("#tz_offset").html()
	    });
	    $(this).fadeIn(100);
	    
		});
    
  },
  
  run: function( id, atime ) {
    //console.log(id + " " + atime)
  	var times = atime.split('|');

  	var td = new Date(times[0], times[1]-1, times[2], times[3], times[4], times[5]);
  	//console.log(td);
  	 
  	//try {
	  $("#time_remaining_"+id).countdown({
        layout: '{hnn}:{mnn}:{snn} Elapsed', 
        since: td,
      	serverSync: getCurrentTime,
        format: 'HMS'
        //onTick: remaining_alt.tickin
        //,timezone: $("#tz_offset").html()
    });
  },
  
  runHrMin: function( id, atime ) {
    //console.log(id + " " + atime)
    var times = atime.split('|');

    var td = new Date(times[0], times[1]-1, times[2], times[3], times[4], times[5]);
    //console.log(td);
    
    //try {
    $("#time_remaining_"+id).countdown({
        layout: '<span>{hnn}<span class="shorty">HRS</span>{mnn}<span class="shorty">MIN</span></span>', 
        since: td,
        serverSync: getCurrentTime,
        format: 'HMS'
        //onTick: remaining_alt.tickin
        //,timezone: $("#tz_offset").html()
    });
  },
  
	//To enable this, we need to add the end time, and calculate each time
  tickin: function(periods) {
  	var id = $(this).attr("id").replace("time_remaining_","");
  	//console.log(id);
		//console.log(periods[4]+" hours " + periods[5]+" minutes " + periods[6]+" seconds");
		if ((periods[4] == 0) && (periods[5] == 0) && (periods[6] == 0)) {
  		$(this).fadeOut(100);
		}
	}
}


$(document).ready(function() {
    if (!window.console) window.console = {};
    if (!window.console.log) window.console.log = function() {};
    
		remaining_alt.init();
});
