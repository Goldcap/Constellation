define(['vendor/backbone'],function(){
	var CTV = CTV || {};
	CTV.View = CTV.View || {};


	CTV.View.Alert = Backbone.View.extend({
		template: $('<div id="alert" class="alert"></div>'),
		options: {
			timeout: 4 //seconds
		},
		initialize: function(){

			this.domNode = $(this.template).appendTo($('body'));
			this.overlay = $('<div class="overlay"></div>').appendTo($('body'));
			this.overlay.bind('click', _.bind(this.close, this))
		},
		open: function(options){
			this.setBody(options);
			this.timeoutCallback = window.setTimeout(_.bind(this.close, this), this.options.timeout * 1e3);
			this.domNode.fadeIn();
			this.overlay.fadeIn();
		},
		setBody: function(options){
			this.domNode.empty().attr('class','').addClass('alert');
			if(options.type && options.type == 'error'){
				this.domNode.addClass('alert-error');
			} else {
				this.domNode.addClass('alert-success');
			}
			if(options.title){
				$('<h4>'+options.title+'</h4>').appendTo(this.domNode);
			}
			if(options.body){
				var content = $('<div class="alert-content"></div>').appendTo(this.domNode);
				if(typeof options.body == 'string'){
					content.html(options.body)
				} else {
					$(options.body).appendTo(content)
				}
			}
		},
		close: function(){
			clearTimeout(this.timeoutCallback);
			this.domNode.fadeOut(300, _.bind(this.empty, this))
			this.overlay.fadeOut(300);		
		},
		empty: function(){
			this.domNode.empty();
			this.overlay.hide();
		}
	});
	Alert = new CTV.View.Alert();
});