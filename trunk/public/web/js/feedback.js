var is_ssl = ("https:" == document.location.protocol);
var asset_host = is_ssl ? "https://s3.amazonaws.com/getsatisfaction.com/" : "http://s3.amazonaws.com/getsatisfaction.com/";
document.write(unescape("%3Cscript src='" + asset_host + "javascripts/feedback-v2.js' type='text/javascript'%3E%3C/script%3E"));

$(document).ready(function() {
    
		if (!window.console) window.console = {};
    if (!window.console.log) window.console.log = function() {};
		    
		
		var feedback_widget_options = {};
		feedback_widget_options.display = "overlay";  
		feedback_widget_options.company = "constellation";
		feedback_widget_options.placement = "left";
		feedback_widget_options.color = "#91d8f4";
		feedback_widget_options.style = "idea";
		/*
		var feedback_widget = new GSFN.feedback_widget(feedback_widget_options);
		*/
		
});
