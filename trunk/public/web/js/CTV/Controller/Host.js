define(['CTV/View/Comments'], function(Comments){
    var CTV = CTV || {};
    CTV.Controller =  CTV.Controller || {};

    CTV.Controller.Host = Backbone.View.extend({
    	initialize: function(){

            this.attachPoints();
            this.attachEvents();
            this.initComments();

    	},
        attachPoints: function(){
            this.followButton = $('#profile-follow-button');
        },
        attachEvents: function(){
            this.followButton.bind('click', _.bind(this.onFollowButtonClick, this));
            this.followButton.bind('mouseenter', _.bind(this.onFollowButtonMouseIn, this));
            this.followButton.bind('mouseleave', _.bind(this.onFollowButtonMouseout, this));
        },
        onFollowButtonMouseIn: function(){
            if(!this.followButton.data('isFollowing')){
                this.followButton.removeClass('button-green').removeClass('button-blue').addClass('button-red').html('Unfollow');
            }
        },
        onFollowButtonMouseout: function(){
            if(!this.followButton.data('isFollowing')){
                this.followButton.removeClass('button-red').addClass('button-green').html('Following');
            } else {
                this.followButton.removeClass('button-red').addClass('button-blue').html('Follow');
            }
        },
        onFollowButtonClick: function(){
            if(this.options.isLoggedIn){
                var id = this.followButton.data('userId'),
                    isFollowing = this.followButton.data('isFollowing');

                var args = {
                    "user_id": id,
                    "type": isFollowing
                };
                $.ajax({url: '/services/Follow', 
                    type: "GET", 
                    cache: false, 
                    dataType: "json",
                    data: $.param(args), 
                    success: _.bind(this.followSuccess, this),
                    error: _.bind(this.followFailure, this),
                });
            } else {
                $(window).trigger('auth:login');
            }
        },
    
        followSuccess: function(response) {
            if(response.followResponse.result == "unfollowed") {
                this.followButton.removeClass('button-red').removeClass('button-green').addClass('button-blue').html('Follow').data('isFollowing', true);
                
            } else if(response.followResponse.result == "followed") {
                this.followButton.removeClass('button-blue').addClass('button-green').html('Following').data('isFollowing', false);
            }
        },
        followFailure: function(){

        },
        initComments: function(){
            _.templateSettings = {
                interpolate : /\{\{(.+?)\}\}/g
            };
            new Comments(this.options.commentOptions);
        }

    });

   return CTV.Controller.Host;
});