
var CTV = CTV || {};
CTV.ShowtimeDetail = function(options){
	this.options = $.extend({
		triggers: '.showtime-pop',
		text: 'You’ll be buying a ticket to an online screening where you can chat with your fellow attendees while you watch.'
    }, options);
    this.init();
} 

CTV.ShowtimeDetail.prototype = {
	template: '<div class="pop-showtime-detail"><div class="pop-showtime-screening">{{details}}<div class="">{{text}}</div><div class="pop-showtime-attendees"><p class="uppercase">Attendees</p><ul class="clearfix">{{attendees}}</ul></p></div><div class="pop-showtime-buttonwrap">{{button}}</div></div>',
	templateTime: '<div class="date">{{date}}</div>',
	templateHost: '<div class="hosted clearfix"><img src="{{avatar}}" width="48"  /><p class="host">Hosted by {{screening_user_full_name}}</p><p class="date">{{date}}</p></div>',
	init: function(){
		this.triggers = $(this.options.triggers).live('click', _.bind(this.open, this));
	},

	open: function(event){
		var target = $(event.target);

		if(!target.hasClass('showtime-pop')){
			target = target.parents(this.options.triggers);
		}
		var screeningData = target.data('screening')

		if($(event.target).is('img')) {
			if($(event.target).hasClass('showtime-film-logo')){
				window.location = '/film/'+ screeningData.screening_film_id;
				return false;
			} else if($(event.target).hasClass('showtime-profile-avatar')) {
				window.location = '/profile/'+ screeningData.screening_user_id;
				return false;
			}
		} else {
			this.getAttendees(screeningData);
		}
	},
	getDialogOptions: function(attendees, screeningData, isAttending){
		var templateData = {}
		templateData.attendees = attendees;
		templateData.text = screeningData.screening_description || this.options.text
		templateData.screening_unique_id = screeningData.screening_id;
		if(!!screeningData.screening_user_full_name){
			screeningData.avatar = this.getUserAvatar(screeningData);
			templateData.details = _.template(this.templateHost, screeningData)
		} else {
			templateData.details = _.template(this.templateTime, screeningData)
		}
		if(screeningData.screening_film_geoblocking_enabled == '1' || screeningData.screening_film_geoblocking_enabled == 'true' ){
			templateData.button = '<p class="error-block error-block-small">We\'re sorry, this film cannot be streamed in your current location.</p>';
		} else if(isAttending){
			templateData.button = _.template('<a href="/theater/{{screening_unique_key}}" class="button button_orange uppercase">Enter Theater</a>', screeningData);			
		} else {
			templateData.button = _.template('<a href="/boxoffice/screening/{{screening_unique_key}}" class="button button_green uppercase">RSVP</a>', screeningData);
		} 

		options = {};
		options.klass = 'dialog-showtime';
		options.title = 'Attend the "' + screeningData.screening_film_name + '" Screening';
		options.body = $(_.template(this.template, templateData));

		return options;
	},
	getAttendees: function(screeningData){
		$.ajax({url:'/services/Screenings/users?screening='+ screeningData.screening_id , 
		    type: "GET", 
		    cache: false, 
		    dataType: "json", 
		    success: _.bind(this.onGetAttendeesSucess, this, screeningData)
		});
	}, 
	onGetAttendeesSucess: function(screeningData, response){

		var attendees = '',
			isAttending = false;
		if(parseInt(response.totalresults)>0){
			_.each(response.users, function(user, index){
				if(index < 200){
					attendees += '<li><a href="/profile/'+ user.id +'"><img src="' + user.image + '" height="48" width="48"><p>'+ user.username+'</p></a></li>';
				}
				if (user.id == this.options.userId) isAttending = true;
			}, this);
		} else {
			attendees = '<li class="be-first">Be the first to Join!</li>'
		}

		var options = this.getDialogOptions(attendees, screeningData, isAttending);
		Dialog.open(options);
	},
	getUserAvatar: function(showtime){
		var avatar = 'https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png';
		if (showtime.screening_user_photo_url != '') {
			if (showtime.screening_user_photo_url.substr(0,4) == 'http') {
				avatar = showtime.screening_user_photo_url;
			} else {
				avatar = __CONFIG.assetUrl+ '/uploads/hosts/'+showtime.screening_user_id+'/'+showtime.screening_user_photo_url;
			}
		} else if (showtime.screening_user_image != '') {
			if (showtime.screening_user_image.substr(0,4) == 'http') {
				avatar = showtime.screening_user_image;
			} else {
				avatar = __CONFIG.assetUrl + '/uploads/hosts/'+showtime.screening_user_id+'/'+showtime.screening_user_image;
			}
		}
		return avatar;
	}
}