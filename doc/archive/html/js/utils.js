// JavaScript Document

function getCurrentTime() { 
    var time = null; 
    var times = $("#thistime").html().split('|');
	  var td = new Date(times[0], times[1]-1, times[2], times[3], times[4], times[5]);
    return td;
}

function setTop( elem ) {
  $(elem).css("top",$(window).scrollTop() + 80);
}
