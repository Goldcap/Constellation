// JavaScript Document

var slideshow = {

	init: function() {
		$('#hp-slideshow').slides({
		    generatePagination: false,
		    pagination: true,
		    currentClass: 'active',
		    play: 5000,
		    hoverPause: true
		});
	}
}

$(document).ready(function() {
    if (!window.console) window.console = {};
    if (!window.console.log) window.console.log = function() {};
 		
		slideshow.init();   
});
