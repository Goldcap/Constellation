Date.prototype.addHours= function(h){
    this.setHours(this.getHours()+h);
    return this;
}

$(document).ready(function(){
  if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
  
	if ($("#counttime").length > 0) {
	   //console.log("Start Date Found");
	   //console.log($("#counttime").html());
		 var times = $("#counttime").html().split('|');
	   var td = new Date(times[0], times[1]-1, times[2], times[3], times[4], times[5]);
	   //try {
		  $('#countdown').countdown({
          layout: '<span style ="text-transform:lowercase">{dnn}<span class="shorty">DAYS</span>{hnn}<span class="shorty">HRS</span>{mnn}<span class="shorty">MIN</span>{snn}<span class="shorty">s</span></span>', 
          until: td,
          serverSync: getCurrentTime,
          format: 'DHMS',
          onExpiry: countCallBack
          //,timezone: $("#tz_offset").html()
      });
		 //}catch(err){ console.log("err");};
	}
	
	/*
	$('.scroll_film_info').jScrollPane({
    verticalDragMinHeight: 30,
		verticalDragMaxHeight: 30,
    verticalGutter: 30	
  });
	*/
	
	//If we specify that there is a callback
	//Look for the first function called "onExpiry"
	//If it doesn't exist, move along...
	function countCallBack() {
    if ($("#ocb").length > 0) {
      try {
        onExpiry();
      } catch (e) {
      }
    }
  }
	
});
