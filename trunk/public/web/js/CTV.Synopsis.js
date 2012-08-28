var CTV = CTV || {};
CTV.Synopsis = function(options){
	this.options = $.extend({
		triggers: '.synopsis-click'	      
    }, options);
    this.init();

 
}

CTV.Synopsis.prototype = {
	// template: '<div id="dialog" class="pops dialog"></div>',
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
		options.title = '<span class="synopsis-icon"></span><span>Synopsis</span>';
		options.klass = 'dialog-synopsis';
		if(Film.film_running_time){
			body.push('<strong>Runtime:</strong>  ' + Film.film_running_time)
		}
		if(Film.film_directors){
			body.push(this.getDirectors());	
		}
		if(Film.film_actors){
			body.push(this.getActors());	
		}
		if(Film.film_info){
			body.push(Film.film_info)
		}
		options.body = $('<div class="synopsis-content"><img src="'+ __CONFIG.assetUrl+'/uploads/screeningResources/'+Film.film_id +'/logo/small_poster' + Film.film_logo+'"/><p class="title">'+ Film.film_name +'</p><p>'+ body.join('</p><p>') +'</p></div>');

		return options;
	},
	getDirectors: function(){
		if(Film.film_directors){
			var directors = [];
			_.forEach(Film.film_directors, function(datum){
				datum = datum.split('|');
				directors.push('<a href="'+datum[0]+'" target="_blank">'+datum[1]+'</a>')
			})
			return '<strong>Directors: </strong> ' + directors.join(', ');
		}
	},
	getActors: function(){
		var actors = [];
		_.forEach(Film.film_actors, function(datum){
			datum = datum.split('|');
			directors.push('<a href="'+datum[0]+'" target="_blank">'+datum[1]+'</a>')
		})
		return '<strong>Actors:</strong> ' + actors.join(', ');
	}
}