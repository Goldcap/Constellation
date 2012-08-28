// JavaScript Document
var film_link = {
	
	init: function() {
		$(".rightside .film_list, .leftside .film_list").sortable({
        //axis: 'x',
        connectWith: '.connectedSortable',
        stop: function(e,ui){
            var thid = ui.item.attr("id");
            console.log("DND:: " + thid);
						var parent = $("#"+thid).parent().parent().parent().parent().attr("class");
            console.log("PARENT:: " + parent);
            //Removed Question
            if (parent == "leftside halfrow") {
              var doit = confirm("Are you sure you want to remove '"+$("#"+thid+" .column_Name a").html()+"'");
    					if (doit) {
								$(".rightside .film_list #keyword_empty").remove();
	      				$(".rightside .film_list").append(ui.item);
	      				$(".leftside .film_list #"+thid).remove();
	            	film_link.removeItem( thid );
            	} else {
								film_link.sortItems();
							}
            //Added Question
            } else {
              $(".leftside .film_list #keyword_empty").remove();
							$(".leftside .film_list").append(ui.item);
							$(".rightside .film_list #"+thid).remove();
              film_link.addItem( thid );
            }
        }
    }).disableSelection();
	},
	
  //Take an item out of "Selected Items"
  removeItem: function(thid) {
  	$.ajax({url: "/services/ProgramAdmin/remove", 
        type: "GET", 
        dataType: "text",
        data: {"kid": thid,"key":$("#program_id").val()}});
    film_link.sortItems();
  },
  
  //Put an item in "Selected Items"
  addItem: function(thid) {
    $.ajax({url: "/services/ProgramAdmin/add", 
            type: "GET", 
            dataType: "text",
            data: {"kid": thid,"key":$("#program_id").val()}});
    film_link.sortItems();
  },
  
  sortItems: function() {
  	console.log("Sorting");
		$(".leftside .film_list").jSort({
        sort_by: '.entry .column_Name',
        item: '.sortable',
        order: 'asc'
    });
    
    $(".rightside .film_list").jSort({
        sort_by: '.entry .column_Name',
        item: '.sortable',
        order: 'asc'
    });
    
	},
	
	resetKeys: function() {
		var uri = "/keywords/link/"+$("#program_id").val();
		window.location = uri;
	}
}

$(document).ready(function() {
  
  film_link.init();
  
});
