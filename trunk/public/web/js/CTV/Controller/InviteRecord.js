define(function(){

    var CTV = CTV || {};
    CTV.Controller =  CTV.Controller || {};

    CTV.Controller.InviteRecord = Backbone.View.extend({
        options: {
            source: 'boxoffice'
        },
    	initialize: function(){
           this.attachPoints();
           this.attachEvents();
    	},
    	attachPoints: function(){
    		this.facebookInviteButtons = $('.button-facebook-invite');
    		this.twitterInviteButtons = $('.button-twitter-invite');
    	},
    	attachEvents: function(){
    		this.facebookInviteButtons.bind('click', _.bind(this.recordInvite, this, 'facebook', 1));
    		this.twitterInviteButtons.bind('click', _.bind(this.recordInvite, this, 'twitter', 1));
    	},
    	recordInvite: function(type, count, event){
			$.ajax({
				url: '/services/Invite/record',
				data: {
					film : this.options.filmId,
					screening: this.options.screening,
					type: type,
					user_type: 'screening',
					count: count,
					source: this.options.source
				}
			});
    	}
    });

   return CTV.Controller.InviteRecord;
});