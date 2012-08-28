var CTV = CTV || {};
CTV.Film = CTV.Film || {};
CTV.Film.Review = function(options){
	this.options = $.extend({
		triggers: '.review-click'	      
    }, options);
    this.init();

 
}

CTV.Film.Review.prototype = {
	init: function(){
		this.triggers = $(this.options.triggers).bind('click', _.bind(this.open, this));
	},

	open: function(){

		var options = this.getDialogOptions();
		Dialog.open(options);
	},
	getDialogOptions: function(){
		var options = {},
			body = [];
		options.title = '<span class="review-icon"></span><span>Reviews</span>';
		options.klass = 'dialog-review';
		if(Film.film_review){
			body.push(this.getReviews());	
		}
		
		options.body = $('<div class="review-content">' + body + '</div>');

		return options;
	},
	getReviews: function(){
		var reviews = [];
			_.forEach(Film.film_review.split('|'), function(datum){
				console.log(datum)
				datum = datum.replace('&#8221;', '&#8221;<br/><em>') + '</em>';
				reviews.push(datum);
			});
			return '<div class="film-review-block">' + reviews.join('</div><div class="film-review-block">') + '</div>';
		// }
	}
}