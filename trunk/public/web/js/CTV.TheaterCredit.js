var CTV = CTV || {};
CTV.TheaterCredit = function(options, domNode) {
	this.options = $.extend({
		file: null,
		timeout: 5000,
		isHost: false
	}, options);
	this.domNode = domNode;
	this.init();
}
CTV.TheaterCredit.prototype = {
	init: function() {

	},
	show: function() {
		if (!this.isVisible) {
			this.isVisible = true;
			this.domNode.fadeIn();
			this.setPosition()
		}
	},
	hide: function() {
		if (this.isVisible) {
			this.isVisible = false;
			this.domNode.fadeOut();
		}
	},
	setPosition: function(){
		if(this.isVisible){
			var dh = this.domNode.height();
			var ph = this.domNode.parent().height();

			if(ph - dh - 80   > 0){
				this.domNode.css({
					'margin-top': (ph - dh) /2
				});
			} else {
				this.domNode.css({
					'margin-top': 40
				});
			}
		}
	}
}