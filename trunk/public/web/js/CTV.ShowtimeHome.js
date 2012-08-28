var CTV = CTV || {};
CTV.ShowtimeHome = function(options){
	this.options = $.extend({
		triggers: '.more-showtimes',
		container: '#featured-showtime',
		urls:{
			list: '/services/Screenings/latest' //?type=featured&rpp=3&page=2
		}	      
    }, options);
    this.init();

 
}

CTV.ShowtimeHome.prototype = {

	template: '<div class="showtime-seh showtime-pop" data-id="{{screening_id}}"> \
		<div class="showtime-seh-poster">\
			<img class="showtime-film-logo" height="88" width="218" src="'+ __CONFIG.assetUrl+'{{screening_film_homelogo}}">\
		</div>\
		<div class="showtime-seh-image">\
			<img class="showtime-profile-avatar" src="{{user_avatar}}" height="88" />\
		</div>\
		<div class="showtime-seh-details">\
			<p class="title">{{title}}</p>\
			<p class="time">{{date}}</p>\
		</div>\
	</div>',

	init: function(){
		this.triggers = $(this.options.triggers).bind('click', _.bind(this.getList, this));
		this.offset = this.getOffset();
	},
	getOffset: function(){
		return $('.showtime-seh').length;
	},
	getList: function(){
		$.ajax({
			type: 'GET',
			url: this.options.urls.list,
			data: {
				type: 'featured',
				page: this.offset
			},
			dataType: 'json',
			success: _.bind(this.showShowtimes, this)
		});

	},
	showShowtimes: function(response){

		if(response.ScreeningListResponse && response.ScreeningListResponse.length > 0){
			_.each(response.ScreeningListResponse, _.bind(this.appendShowtime, this))
			this.offset = this.offset + response.ScreeningListResponse.length;

			if(this.offset == parseInt(response.meta.totalresults)){
				this.triggers.unbind('click', _.bind(this.getList, this)).addClass('disabled');
			}
		}

	},
	appendShowtime: function(showtime){
		showtime.user_avatar = this.getUserAvatar(showtime);
		showtime.date = moment(showtime.screening_date.replace('|', ' '),'YYYY MM DD HH mm ss').format('LT z, MMMM Do, YYYY');
		showtime.title = showtime.screening_name || showtime.screening_user_full_name

		showtime.screening_film_homelogo = showtime.screening_film_homelogo ? '/uploads/screeningResources/' +showtime.screening_film_id +'/logo/wide_poster'+showtime.screening_film_homelogo : 'https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-film-default-seh.png';

		$(_.template(this.template, showtime)).data('screening', showtime).appendTo(this.options.container)

	},
	getUserAvatar: function(showtime){
		var avatar = 'https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png';
		if (showtime.screening_user_photo_url != '') {
			if (showtime.screening_user_photo_url.substr(0,4) == 'http') {
				avatar = showtime.screening_user_photo_url;
			} else {
				avatar = __CONFIG.assetUrl+'/uploads/hosts/'+showtime.screening_user_id+'/icon_large_'+showtime.screening_user_photo_url;
			}
		} else if (showtime.screening_user_image != '') {
			if (showtime.screening_user_image.substr(0,4) == 'http') {
				avatar = showtime.screening_user_image;
			} else {
				avatar = __CONFIG.assetUrl + '/uploads/hosts/'+showtime.screening_user_id+'/icon_large_'+showtime.screening_user_image;
			}
		}
		return avatar;
	}
}