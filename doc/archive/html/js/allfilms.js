// JavaScript Document

var allfilms = {
	
	showing: false,
	
	init: function() {
		$(".main-films a").click(function(e){
			e.stopPropagation();
			$(".allfilms").toggle();
			if (allfilms.showing) {
				allfilms.showing = false;
			} else {
				allfilms.showing = true;
			}
			//
		});
		
		$(document).click(function(e){
			if (allfilms.showing) {
				$(".allfilms").hide();
				allfilms.showing = false;
			}
		});
	}
	
}

$(document).ready(function() {
    if (!window.console) window.console = {};
    if (!window.console.log) window.console.log = function() {};
    
		allfilms.init();
});
