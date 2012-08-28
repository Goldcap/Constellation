var CTV = CTV || {};
CTV.TheaterColorDisplay = function(options, domNode) {
	this.options = $.extend({

	}, options);
	this.domNode = $(domNode);
	this.init();

}

CTV.TheaterColorDisplay.prototype = {
	template: '<div id="user_wrap_{{author}}" class="theater_icon {{body}}"><a target="_blank" href="/profile/{{author}}"><img width="50" src="{{user_image}}" id="user_image_{{author}}" class="colorme_user"><span class="colorme-icon-small"></span></a></div>',
	init: function() {
		this.collection = [];
		this.elementCollection = [];
		this.users = [];
		this.isVisible = false;
		this.attachPoints();
		this.hasInitializedUpdates = true;
		this.currentSequence = 0;
		// this.getUsers();

	},
	attachPoints: function() {
		this.iconContainer = $('.color-icons-container ', this.domNode);
	},
	getUsers: function() {
		$.ajax({
			url: '/services/Screenings/colorme?screening=' + this.options.screeningId,
			type: "GET",
			cache: false,
			dataType: "json",
			timeout: 3000,
			success: _.bind(this.getUsersSuccess, this)
		});
	},
	getUsersSuccess: function(response) {
		if (response && typeof response == 'object' && response.users) {
			this.users = response.users;
			// $(this).trigger('userCount', response.users.length)
		}
		window.setTimeout(_.bind(this.getUsers, this), 60000);
	},
	queueColor: function(message, hasInitializedUpdates) {
		this.hasInitializedUpdates = hasInitializedUpdates;
		this.serializeMe(message);
		this.dequeue();

	},
	serializeMe: function(message) {
		if(!this.hasInitializedUpdates) message.reverse();
		var endIndex = message.length < this.maxIcons ? message.length : this.maxIcons;
		for (var i = 0; i < endIndex; i++) {
			message[i].body = message[i].body.replace('colorme:', '')
			this.collection.push(message[i]);
		}
	},
	dequeue: function() {
		if (this.collection.length) {
			var message = this.collection.shift();
			this.appendIcon(message);
		}
	},
	appendIcon: function(message) {
		// message.from = $('<div/>').text(message.from).html();

 		if(!/http/.test(message.user_image)){
 			message.user_image = __CONFIG.assetUrl + message.user_image
 		}

		var element = $(_.template(this.template, message)).tooltip({
			offsetX: 12,
			offsetY: 0 ,
			placement: 'left',
			title: message.isfrom
		});

		if(this.hasInitializedUpdates){
			element.css({
				'margin-left': -60,
				opacity: 0
			})
			element.prependTo(this.iconContainer).animate({
					'opacity': 1,
					'margin-left': 10
			}, 200, _.bind(function() {
				this.dequeue()
			}, this));
			this.elementCollection.push(element);

		} else {
			element.appendTo(this.iconContainer)
			.css({opacity:0})
			.animate({opacity: 1}, 200);
			this.dequeue()
			this.elementCollection.push(element);
		}

		if (this.elementCollection.length >= this.maxIcons) {
			this.shiftElements();
		}
	},
	resize: function(width) {
		this.maxIcons = Math.ceil(width / 62);

		if (!this.isVisible) {
			this.domNode.fadeIn();
		}
	},
	shiftElements: function() {
		for (i = this.elementCollection.length; i > this.maxIcons; i--) {
			var element = this.elementCollection.shift();
			element.remove();
		}
	},
	reset: function(){
		this.iconContainer.empty();
		this.elementCollection.length = 0;
	}
}