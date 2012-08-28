var CTV = CTV || {};
CTV.TheaterController = function(options){
	this.options = $.extend({
    	chatOptions: null,
    	ooyalaToken: true
    }, options);
    this.init();

}

CTV.TheaterController.prototype = {
	init: function(){
		this.state = null;
		this.chatIsOpen = true;
		this.videoIsComplete = false;
		this.attachPoints();
		this.attachEvents();
		this.initSubClass();

		this.onWindowResize();
	},
	attachPoints: function(){
		this.theater = $('.theater-container');
		this.mainPanel = $('#main-panel');
		this.w = $(window);
		this.footer = $('#footer');
	},
	attachEvents: function(){
		this.w.bind('resize', _.bind(this.onWindowResize, this));
		$('.use-tip',this.footer).tooltip();
		$('.use-tip',$('#color-container')).tooltip();
		$('#twitter-footer-button').bind('click', _.bind(this.recordInvite, this, 'twitter'));
		$('#facebook-footer-button').bind('click', _.bind(this.recordInvite, this, 'facebook'));
	},
	initSubClass: function(){
		this.chat = new CTV.TheaterChat(this.options.chatOptions, $('#chat-container'));
		//$(this.chat).bind('colorMe', _.bind(this.onColorMe,this));
		//$(this.chat).bind('colorMeReset', _.bind(this.onColorMeReset,this));

		// $(this.chat).bind('qanda', _.bind(this.onQueueQA,this));
		// $(this.chat).bind('chatSlide', _.bind(this.onChatSlide, this));
		// $(this.chat).bind('qastart', _.bind(this.onQaStart, this));
		// $(this.chat).bind('qaend', _.bind(this.onQaEnd, this));

		this.activity = new CTV.TheaterActivity(this.options.activityOptions);
		$(this.activity).bind('updateTime', _.bind(this.onUpdateTime, this));

		this.colorPanel = new CTV.TheaterColor(this.options.colorOptions, $('#color-container'));
		$(this.colorPanel).bind('sendColor', _.bind(this.onSendColor, this));

		//this.colorDisplay = new CTV.TheaterColorDisplay(this.options.colorOptions, $('#color-display'));

		this.details = new CTV.TheaterDetails(this.options.detailOptions, $('#theater-film-details'));
		$(this.details).bind('startFilm', _.bind(this.onStartFilm, this));

		if(this.options.videoPlayerOptions.ooyalaEmbedId){
			this.videoPlayer = new CTV.TheaterOoyalaPlayer(this.options.videoPlayerOptions, $('#video-container'));
			$(this.videoPlayer).bind('videoComplete', _.bind(this.onVideoComplete, this));	
		} else {
			this.videoPlayer = new CTV.TheaterVideoPlayer(this.options.videoPlayerOptions, $('#video-container'));
			$(this.videoPlayer).bind('videoComplete', _.bind(this.onVideoComplete, this));	
		}


		if(this.options.hasQA){
			this.qa = new CTV.TheaterQa(this.options.qaOptions, $('#qa-container'));
			$(this.qa).bind('getStream', _.bind(this.onGetHostStream, this));
			$(this.qa).bind('publishHost', _.bind(this.onPublishHost, this));
			$(this.qa).bind('hostHide', _.bind(this.onHostHide, this));
			$(this.qa).bind('getStream', _.bind(this.onGetHostStream, this));
			// $(this.qa).bind('qanda', _.bind(this.onQueueQA,this));
			$(this.qa).bind('qaend', _.bind(this.onQaEnd, this));
		}
		this.credit = new CTV.TheaterCredit({}, $('#theater-credit'));

		if(this.options.hasTicket && this.options.hasHost && this.options.hasVideo){
			this.hostPlayer = new CTV.TheaterHost(this.options.hostOptions, $('#host-container'));
			$(this.hostPlayer).bind('getStream', _.bind(this.onGetHostStream, this));
			$(this.hostPlayer).bind('hostCamStatus', _.bind(this.onHostCamStatus, this));
			$(this.hostPlayer).bind('hostToggle', _.bind(this.onHostToggle, this));
			$(this.hostPlayer).bind('publishHost', _.bind(this.onPublishHost, this));
			$(this.hostPlayer).bind('hostHide', _.bind(this.onHostHide, this));

			if(this.options.userId == this.options.hostId){
				this.hostCam = new CTV.TheaterHostPublisher(this.options.hostOptions);
			} else {
				this.hostCam = new CTV.TheaterHostSubscriber(this.options.hostOptions);
			}
			$(this.hostCam).bind('TBIsConnected', _.bind(this.onTBtIsConnected, this));

		}
		
	},
	onWindowResize: function(){
		if(window.innerHeight){
			var height = window.innerHeight;
			var width = window.innerWidth;
		} else {
			var height = this.w.height();
			var width = this.w.width()
		}

		height = height < 600 ? 600 :height;

		this.chat.resize(height)
		// this.colorDisplay.resize(width);
		this.mainPanel.height(height - 60);
		this.theater.height(height)//.width(this.chatIsOpen ? (width - 320 > 700 ? width - 320: 700): width -10 );
		this.videoPlayer.resize(height);

		if(this.options.hasQA){
			this.qa.setPosition();
		}
		this.details.setPosition();
	},
	onColorMe: function(event, arg, bool){
		this.colorDisplay.queueColor(arg, bool);
	},
	onQueueQA: function(event, arg){
		this.qa.queueQA(arg);	
	},
	onUpdateTime: function(event, arg){
		this.currentTime = arg;
		this.details.setServerTime(arg);
		this.videoPlayer.setServerTime(arg);
		this.pollTime();
	},
	onHostCamStatus: function(event, status){
		if(status == 'stop'){
			this.chat.hostIsHidden();
			this.onWindowResize();
		} else {
			this.chat.hostIsShown();
			this.onWindowResize();
		}
	},
	onHostHide: function(){
		this.hostCam.stop();
	},
	onGetHostStream: function(event, container, options){
		this.hostCam.start(container, options);
	},
	onTBtIsConnected: function(event, bool){
		if(!bool) return;
		if(this.state == 'QA'){
			this.qa.startStream();
		} else {
			this.hostPlayer.startStream();
		}
	},
	onHostToggle: function(event, status){
		this.chatIsToggled = status;
		this.chat.toggleHost(status);
	},
	onPublishHost: function(event, bool){
		if(bool){
			if(this.state == 'QA'){
				this.qa.startStream();
			} else {
				this.hostPlayer.startStream();
			}
		} else {
			this.hostCam.stop();		
		}

	},
	onStartFilm: function(){
		this.activity.getStatus();	
	},
	onVideoComplete: function(){
		this.videoIsComplete = true;
		this.pollTime();
	},
	onSendColor: function(event, arg){
		this.chat.postColor(arg);	
	},
	pollTime: function(){

		if(((this.options.hasTicket && this.currentTime > this.options.endTime && this.state != 'QA' && this.options.hasQA ) || (this.videoIsComplete && this.state != 'QA' && this.options.hasQA)) && this.options.qaStatus != 'closed'){
			this.state = 'QA';
			this.broadcastState()
			this.startQa();
		} else if((this.options.hasTicket && this.currentTime > this.options.endTime && this.state != 'credit' && !this.options.hasQA) ||  (this.videoIsComplete && this.state != 'credit' && !this.options.hasQA) || (this.state != 'credit' && this.options.qaStatus == 'closed' && this.options.hasTicket)){
			this.state = 'credit';
			this.broadcastState()
			this.showCredit();
		} else if(this.options.hasTicket && this.currentTime > this.options.startTime && this.currentTime < (this.options.endTime)  && this.state != 'film' && !this.videoIsComplete){
			this.state = 'film';
      this.startFeature();
			this.broadcastState();
		} else if((!this.options.hasTicket && this.state != 'lobby' ) || (this.currentTime < this.options.startTime && this.state != 'lobby' && !this.videoIsComplete)){
			this.state = 'lobby';
			this.broadcastState();
			this.startLobby()
		}
	},
	startFeature: function(){
		this.details.hide();
		if(this.qa) this.qa.hide();
		this.videoPlayer.initPlayer();
	},
	startQa: function(){
		if(this.hostPlayer){
			this.hostPlayer.hide();
		}
		this.chat.hostIsHidden();
		this.details.hide();
		this.videoPlayer.hide()	
		this.qa.show();
		this.onWindowResize();
		if(this.options.qaStatus == 'running'){
			this.qa.showQa();
		} else {
			this.qa.showLobby();
		}
		
	},
	// onQaStart: function(){
	// 	this.qa.showQa();	
	// },
	onQaEnd: function(){
		// this.showCredit();
		this.options.qaStatus = 'closed';
		this.pollTime();
	},
	startLobby: function(){
		this.videoPlayer.hide()	
		this.details.show();
	},
	broadcastState: function(){
		this.chat.setState(this.state);
	},
	showCredit: function(){
		if(this.qa) this.qa.hide();
		this.videoPlayer.hide();
		this.details.hide();
		this.credit.show();
	},
	onColorMeReset: function(){
		this.colorDisplay.reset();
		this.colorPanel.reset();
	},
	recordInvite: function(type){
		$.ajax({
			url: '/services/Invite/record',
			data: {
				film : this.options.film,
				screening: this.options.screening,
				type: type,
				user_type: 'screening',
				count: 1,
				source: 'theater'
			}
		});
	} 

}

