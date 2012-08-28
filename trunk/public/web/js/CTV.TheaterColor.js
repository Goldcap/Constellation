var CTV = CTV || {};
CTV.TheaterColor = function(options, domNode){
	this.options = $.extend({
		postTimeout: 5000
    }, options);
    this.domNode = $(domNode);
    this.init();

}

CTV.TheaterColor.prototype = {
	init: function(){
		this.currentColor = null;
		if(this.options.userId == 0) this.domNode.hide();
		this.attachPoints();
		this.attachEvents();
	},
	attachPoints: function(){
		this.colorButtons = $('.color_icon');
	},
	attachEvents: function(){
		_.each(this.colorButtons, function(element){
			element = $(element);
			var throttled = _.throttle(_.bind(this.onButtonClick, this, element.data('color'),element), 500);
			element.bind('click', throttled);
		}, this);
	},
	onButtonClick: function(color,element){
		if(this.currentColor != color){
			this.currentColor = color;
			this.colorButtons.removeClass('active');
			element.addClass('active');
			this.postColor(color);
		}
	},
	postColor: function(color){
		var arg =  'colorme:' + color;
		$(this).trigger('sendColor', color);
	},
	reset: function(){
		this.colorButtons.removeClass('active');
		this.currentColor = null;
	}
}
