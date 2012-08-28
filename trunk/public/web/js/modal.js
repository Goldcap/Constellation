var modal = {
	
	showing: false,
	
	modalIn : function ( callback ) {
		if ($(".modal").length > 0) {
			$(".modal").fadeIn(100);
		} else {
			var html = '<div class="modal"></div>';
			$("body").append(html);
		}
		if (modal.showing) {
			modal.showing = false;
		} else {
			modal.showing = true;
		}
		
    $(".modal").click(function(e){
    	modal.modalOut( callback );
    });
    
	},
	
	modalOut : function ( callback ) {
		callback();
		if (modal.showing) {
			$(".modal").remove();
			modal.showing = false;
		}
		if ($(".modal").css("display") == "block") {
			$(".modal").remove();
			modal.showing = false;
		}
	},
	
	modalDestroy : function () {
		$(".modal").remove();
		modal.showing = false;
	}

}
