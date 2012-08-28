var CTV = CTV || {};
CTV.TheaterActivity = function(options) {
	this.options = $.extend({
		timeout: 5000,
		// periodical: 5000,
		periodical: 30000,
		host: null,
		port: null,
		instance: null,
		room: null,
		destination: null,
		location: 'theater'
	}, options);
	this.init();

}

CTV.TheaterActivity.prototype = {
	init: function() {
		if(this.options.userid != 0){
			// this.announce();
			this.getStatus();
		}
	},

	//This adds the user to the room
	announce: function() {

		var args = {
			"film": this.options.film,
			"room": this.options.room,
			"instance": this.options.instance,
			"userid": this.options.userId,
			"location": this.options.location,
			"cookie": this.options.cookie
		};
		$.ajax({
			url: "/services/activity/announce?i=" + this.ip,
			type: "POST",
			cache: false,
			dataType: "text",
			data: $.param(args),
			timeout: this.options.timeout,
			success: _.bind(this.onAnnounceSuccess, this),
			error: _.bind(this.onAnnounceError, this)
		});
	},

	onAnnounceSuccess: function(response) {
		// console.log(response);
	},

	onAnnounceError: function(response) {
		window.setTimeout(_.bind(this.announce,this), this.options.timeout);
	},

	getStatus: function() {
		if(this.statusRequestTimer) window.clearTimeout(this.statusRequestTimer);
		var url = "/services/activity/status?room=" + this.options.room + 
			"&userid=" + this.options.userId +
			"&instance=" + this.options.instance +
			"&location=" + this.options.location +
			"&film=" + this.options.filmId +
			"&cookie=" + this.options.cookie;
		$.head(url, {}, _.bind(this.onGetStatusSuccess, this), this.options.timeout);
	},
	onGetStatusSuccess: function(headers) {

		$(this).trigger('updateTime', headers['X-Server-Time'])

		this.statusRequestTimer = window.setTimeout(_.bind(this.getStatus,this), this.options.periodical);
	}
}