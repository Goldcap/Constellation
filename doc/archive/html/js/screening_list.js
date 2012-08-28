// JavaScript Document
$(document).ready(function(){
	if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
 
	$('.screening_list').jScrollPane({
    verticalDragMinHeight: 30,
		verticalDragMaxHeight: 30,
    verticalGutter: 0	
  });
	
});
	
	
