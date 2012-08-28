var CTV = CTV || {};
CTV.Dialog = function(options){
	 this.options = $.extend({
	 	pad: 40,
	 	isIframe: false
    }, options);
    this.init();
 
}

CTV.Dialog.prototype = {
	template: '<div id="dialog" class="pops dialog"></div>',
	init: function(){

		this.domNode = $(this.template).appendTo($('body'));
		this.overlay = $('<div class="overlay"></div>').appendTo($('body'));
		this.overlay.bind('click', _.bind(this.close, this))
	},
	open: function(options){
		this.setBody(options);
		if(this.options.isIframe) {
			FB.Canvas.scrollTo(0,0);
		}
		this.domNode.fadeIn();
		this.overlay.fadeIn();
	},
	setBody: function(options){
		this.domNode.empty();
		this.domNode.attr('class','').addClass('dialog');
		if(options.klass){
			this.domNode.addClass(options.klass)
		}
		if(options.title){
			$('<h4>'+options.title+'</h4>').appendTo(this.domNode);
		}
		if(options.body){
			var content = $('<div class="dialog-content"></div>').appendTo(this.domNode);
			if(typeof options.body == 'string'){
				content.html(options.body)
			} else {
				$(options.body).appendTo(content)
			}
		}
		if(options.buttons){
			var buttonWrap = $('<div class="dialog-buttons"></div>').appendTo(this.domNode);
			_.each(options.buttons, _.bind(this.appendButton,this, buttonWrap));
			// $('<h4>'+options.title+'</h4>').appendTo(this.domNode);
		}
		if(options.image){
			this.appendImage(options.image)
		}
		if(options.video){
			
		}
	},
	appendButton: function(buttonWrap, button){
		$('<span class="button button_blue button-medium">' + button.text + '</span>')
		.bind('click', _.bind(function(){
			if(button.callback) button.callback();
			this.close();
		},this))
		.appendTo(buttonWrap)
	},
	appendImage: function(imageSrc){
		this.domNode.css({width: 40, height: 40}).addClass('loading');
		this.image = $('<img src="'+ imageSrc +'"/>')
			.bind('load', _.bind(this.onImageLoaded, this)); //.html(content.html()).appendTo($('body'));
			// var width = temp.width();
			// var height = temp.height();
			// // console.log(width, height);

			// var wWidth =  $(window).width();
			// var wHeight =  $(window).height();

			// this.domNode.css({
			// 	height: height,
			// 	width: width,
			// 	left: - (width / 2) -20
			// })
			// content.html(temp.html());
			// temp.remove();
	},
	onImageLoaded: function(){
		var content = $('<div class="dialog-content"></div>').appendTo(this.domNode);
		this.image.appendTo(content);
		var maxHeight = $(window).height() - this.options.pad;
		var maxWidth = $(window).width() - this.options.pad;

			var width = this.image.width();
			var height = this.image.height();

		//if image is taller than window...
		if(height > maxHeight + 40) {
			// console.log('here')
			this.image.height(maxHeight);
			this.image.css({
				height: maxHeight - 40,
				width: (width * (maxHeight / height))
			});
			var width = this.image.width()
			this.domNode.css({
				height: maxHeight - 40,
				width: width,
				left: - (width / 2) - 20,
				top: 20
			});
		} else  if(width > maxWidth + 40){

			this.image.width = maxWidth - 40;
			this.image.css({
				width: maxWidth - 40
			});
			this.domNode.css({
				height: this.image.height(),
				width: maxWidth - 40,
				left: - (maxWidth / 2)
			});
		} else {
			this.domNode.css({
				height: height,
				width: width,
				left: - (width / 2) -20
			});
		}

		// this.domNode.
		//get rid of styles
		// this.messageBox.setStyles({ height: '', width: '' });

	},
	close: function(){
		this.domNode.fadeOut(300, _.bind(this.empty, this))
		this.overlay.fadeOut(300);		
	},
	empty: function(){
		if(this.image) {
			this.image.remove(); this.image = null;
		}
		this.domNode.empty().attr('style', '');
		this.overlay.hide();
	}
}
var Dialog;
$(document).ready(function() {
    if (!window.console) window.console = {};
    if (!window.console.log) window.console.log = function() {};
 		
	Dialog = new CTV.Dialog();
});