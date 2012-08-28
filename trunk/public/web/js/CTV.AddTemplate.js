var CTV = CTV || {};
CTV.AddTemplate = function(options){
	 this.options = $.extend({
      
    }, options);
    this.init();
 
}

CTV.AddTemplate.prototype = {
	template: '<div></div>',
	init: function(){
		this.template = this.options.template || this.template;
		this.attachPoints();
		this.attachEvents();
	},
	attachPoints: function(){
		this.templateContainer = this.options.templateContainer;
		this.addButton = this.options.addButton;
	},
	attachEvents: function(){
		this.addButton.bind('click', _.bind(this.onAdd, this));
		$('.remove-template', this.templateContainer).live('click', _.bind(this.onRemove, this));

	},
	onAdd: function(){
		// $(this).parents('.input').before($(html))
		var template = $(this.template).addClass('template-block').append('<span class="remove-template"/>').appendTo(this.templateContainer);
	},
	onRemove: function(event){
		$(event.target).parent().remove();
	}
}
