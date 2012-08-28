
var host_show = {    
    
		init: function() {
	    $('#datepicker').datepicker({
	    	numberOfMonths: 2,
	        minDate: new Date()
	    });
	    $('#timepicker').timepicker({
	        ampm: true
	    });
	    
	    $('select').each(function(select){
	        var $select = $(this);
	        $select.bind('change', host_show.onSelectChange);
	        
	    });
	    
	    $("#host_last").click(function(e) {
	    	e.preventDefault();
				$(".host_step_two").fadeOut(100).queue(function() {
					$(".host_step_one").fadeIn(100);
					$(".host_step_two").clearQueue();
				});
			});
			
	    $("#host_next").click(function(e) {
	    	e.preventDefault();
				$(".host_step_one").fadeOut(100).queue(function() {
					$(".host_step_two").fadeIn(100);
					$(".host_step_one").clearQueue();
				});
			});
    },
    
    onSelectChange: function (event){
        var $select = $(event.target);
        var text = $('option:selected', $select).html();
        $select.prev().html(text);
    }
}


$(document).ready(function() {
    if (!window.console) window.console = {};
    if (!window.console.log) window.console.log = function() {};
    
		host_show.init();
});
