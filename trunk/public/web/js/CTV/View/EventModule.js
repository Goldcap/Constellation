define(["text!./../Template/EventModule.html"], function(template){


    var CTV = CTV || {};
    CTV.View =  CTV.View || {};

    CTV.View.EventModule = Backbone.View.extend({
		tagName: "div",
		className: 'event-module',
    	template: Handlebars.compile(template), 
    	render: function(){
            this.$el.html(this.template(this.model));
            return this;
    	}
    	
    });

   return CTV.View.EventModule;
});