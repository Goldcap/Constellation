var CTV = CTV || {};
CTV.TOJSConfirm = function(options){
	 this.options = $.extend({
      
    }, options);
    this.init();
 
}

CTV.TOJSConfirm.prototype = {
	email: /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i,

	init: function(){
		this.fbFiendsIds = [];

		this.attachPoints();
		this.attachEvents();
	},
	attachPoints: function(){
		this.fbShareButton = $('#share-facebook');
		this.tShareButton = $('#share-twitter');
		this.fbInviteButton = $('#invite-friends');

		this.friendsUl = $('#facebook-friends');
		this.friendsLi = $('#facebook-friends li');
		this.friendsInput = $('#facebook-friends input');
		this.fbCheck = $('#facebook-friends-search');
		this.fbSearch = $('#facebook-friends-search');
		this.fbCheckToggler = $('#facebook-check');
		this.emailSubmit= $('#email-submit');
		this.emailField = $('#email');
		this.emailWrap = $('#email-wrap');
		this.emailConfirm = $('#confirm-ticket');
		this.formBlock = $('.form-row', this.emailWrap);
		//http://dev.constellation.tv/services/Account/modify?field=user_username&value=GoldcapX
	},
	attachEvents: function(){
		this.fbShareButton.bind('click',_.bind(this.onFbShareClick, this));
		this.tShareButton.bind('click',_.bind(this.onTShareClick, this));
		this.fbInviteButton.bind('click',_.bind(this.onFBInviteInlineClick, this));
		this.fbSearch.bind('keyup', _.bind(this.onFilterFbFriends, this));
		this.fbCheckToggler.bind('click', _.bind(this.fbCheckToggle, this));
		this.friendsLi.bind('click', _.bind(this.onFriendsLiClick, this));
		this.emailSubmit.bind('click', _.bind(this.onEmailSubmit, this))
		this.emailField.bind('focus', _.bind(this.onFieldFocus, this))
	},
	onFbShareClick: function(){
        var obj = {
          method: 'apprequests',
		  message: 'Join me at High School Confidential with Jonah Hill and Channing Tatum, a live online interactive event happening on March 15 at 8pm EST. www.constellation.tv/21jumpstreet'+this.options.fBeacon
        };
		FB.ui(obj, _.bind(this.fbFiendsIdDequeueSuccess, this));
	},
	onTShareClick: function(){
		var params =[];
		params.push('text=' + encodeURIComponent('Join the @21jumpstmovie event with @ChanningTatum and @JonahHill on @constellationtv. http://bit.ly/x4UlrG. #21jumpstlive'));
		// params.push('url=' +encodeURIComponent('http://bit.ly/x4UlrG'))
		window.open('https://twitter.com/intent/tweet?' +params.join('&'),'_share_twitter','width=450,height=300');
		this.recordInvite('twitter', 1);
	},
	onFilterFbFriends: function(){
		var value = this.fbSearch.val();
		_.each(this.friendsLi, function(element){
			var element = $(element),
				name = $(element).data('name');

			if(name.toLowerCase().indexOf(value.toLowerCase()) != -1 ){
				element.show();
			} else {
				element.hide();
			}
		}, this)
	},
	onFriendsLiClick: function(event){
		var li = $(event.target);
		var	isCheckbox = li.is('input')		
		if(!li.is('li')){
			li = li.parents('li');
		}
		var input = $('input', li);
		var checked = isCheckbox ? input.is(':checked') : !input.is(':checked') ;

		li[checked? 'addClass': 'removeClass']('checked');
		if(!isCheckbox)	input.attr('checked' , checked)

	},
	onFBInviteInlineClick: function(){
		this.fbFiendsIds.length = 0;
		_.each($('input:checked', this.friendsUl), function(input, index){
			this.fbFiendsIds.push($(input).val());
		},this);
		this.fbFiendsIdDequeue();
	},
	fbFiendsIdDequeue: function(){
		if(this.fbFiendsIds.length > 0){
			var ids = this.fbFiendsIds.splice(0,49);
			var obj = {
				method: 'apprequests',
				to: ids.join(','),
				message: 'Join me at High School Confidential with Jonah Hill and Channing Tatum, a live online interactive event happening on March 15 at 8pm EST. www.constellation.tv/21jumpstreet'+this.options.fBeacon 
			};

			FB.ui(obj, _.bind(this.fbFiendsIdDequeueSuccess, this));
		}
	},
	fbFiendsIdDequeueSuccess: function(response){
		if(response != null){
			this.recordInvite('facebook', response.to.length);
			this.fbFiendsIdDequeue();
		}
	},

	fbCheckToggle: function(){
		var checked = this.fbCheckToggler.is(':checked');
		this.friendsInput.attr('checked' , checked)
		this.friendsLi[checked? 'addClass': 'removeClass']('checked');
	},
	recordInvite: function(type, count){
		$.ajax({
			url: '/services/Invite/record',
			data: {
				film : this.options.filmId,
				screening: '21jumpstlive',
				type: type,
				user_type: 'screening',
				count: count,
				source: 'boxoffice'
			}
		});
	},
	onEmailSubmit: function(){
		if(this.email.test(this.emailField.val())){
			this.postEmail()
		} else {
			this.showError();
		}
	},
	postEmail: function(){

		$.ajax({
			url: '/services/Account/modify',
			data: {
				field: 'user_email',
				value: this.emailField.val(),
			},
			type: "POST",
			cache: false,
			success: jQuery.proxy(this.showSuccess, this)
		});
	},
	onFieldFocus: function(event) {
		if (this.emailField.data('hasError')) {
			$(this.formBlock.removeClass('error').find('.error-message')).remove();
			this.emailField.data('hasError', false);
		}
	},
	showSuccess: function(){
		this.emailWrap.fadeOut(300, _.bind(function(){
			this.emailConfirm.fadeIn(300);
		},this));

		$.ajax({url: '/services/MessageManager/reminder/21jumpstlive' })
	},
	showError: function() {
		if (!this.emailField.data('hasError')) {
			this.formBlock.addClass('error');
			this.emailField.data('hasError', true);
			var errorMessage = 'The email you entered is not valid';
			$('<span class="error-message"><span class="tip"></span>' + errorMessage + '</span>').appendTo(this.formBlock);
		}
	}
}
