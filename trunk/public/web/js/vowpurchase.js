var vowPurchase = {
	init: function() {
		vowPurchase.lightbox = $("#vow-pruchase-lb");
	},
	
	open: function(){
		vowPurchase.lightbox = $("#vow-pruchase-lb");
		vowPurchase.lightbox.fadeIn();
		modal.modalIn(vowPurchase.close);
		screening_room.pay();
	},
	
	close: function(){
		vowPurchase.lightbox.fadeOut();
		modal.modalOut(function(){});
	}
}


$(document).ready(function(){
	if (!window.console) window.console = {};
 	if (!window.console.log) window.console.log = function() {};
	vowPurchase.init();
});
