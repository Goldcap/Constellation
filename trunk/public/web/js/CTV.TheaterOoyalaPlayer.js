var CTV = CTV || {};
CTV.TheaterOoyalaPlayer = function(options, domNode) {
	this.options = $.extend({

	}, options);
	this.domNode = domNode;
	this.init();
}
CTV.TheaterOoyalaPlayer.prototype = {
	controlTemplate: '<div class="theater-control"><div class="right"><span class="sound-icon"></span><span class="sound"></span></div><div class="clearfix theater-scrubber-wrap"><div class="time-current">00:00:00</div><div class="time-end">00:00:00</div><div class="scrubber"><div></div></div></div></div>"',
	init: function() {
		this.hasSeeked = false;
		this.currentVolume = true;
		this.domNode.addClass('ooyala-player');
		this.currentValue = 1;
		// this.throttledSetScrubber = _.bind(this.setScrubber, this);
		// console.log('f')
	},
	resize: function(height){
		this.domNode.css({height: height - 100 });
	},
	initPlayer: function() {
		if (this.options.runtime) {
			this.seekTime = this.options.runtime;
		} else {
			this.seekTime = 0;
		}
		this.show();
		receiveOoyalaEvent = _.bind(this.receiveOoyalaEvent, this);
		// var ooyalaEmbedId = this.options.ooyalaEmbedId;

		$(document.body).append('<script src="http://player.ooyala.com/player.js?width=640&height=360&embedCode='+ this.options.ooyalaEmbedId +'&videoPcode=F0YW06tUyoXE3Itr8QKSPI9aTQWr&targetReplaceId=movie-placeholder&callback=receiveOoyalaEvent&layout=chromeless"></script>')
		//$(document.body).append('<script src="http://player.ooyala.com/player.js?width=640&height=360&embedCode=p3aDdnNDpJNz4hW8S5MeKdyHzB7gJyC7&code=c1Y3Y6ym6PEABOORqipbdPjDoP7u&targetReplaceId=movie-placeholder&callback=receiveOoyalaEvent"></script>')

		this.controlDom = $(this.controlTemplate);
		this.domNode.append(this.controlDom);
		this.fullscreenButton = $('.fullscreen', this.controlDom).bind('click', _.bind(this.onFullScreen, this));
		this.scrubber = $('.scrubber div', this.controlDom);
		this.timeCurrentDom = $('.time-current', this.controlDom);
		this.timeEndDom = $('.time-end', this.controlDom);
	},
	show: function() {
		this.domNode.fadeIn();
	},
	hide: function() {
		this.domNode.fadeOut();
		// swfobject.removeSWF('cPlayer');
		// this.domNode.empty();
	},
	setServerTime: function(time){
		this.time = time;
	},
	getPlayer: function () {
    	return document.getElementById('movie-placeholder');
    },
    play: function () {
    	this.getPlayer().playMovie();
    },
    pause: function () {
    	this.getPlayer().pauseMovie();
    },
    setPlayheadTime: function () {
    	this.getPlayer().setPlayheadTime($('#playhead_time').val());
    },
	onInitPlayerError: function() {
		// console.log('error')
	},
	onFullScreen: function(){
		var test = this.getPlayer().getFullscreen(true);
	},
	onSoundChange: function(){
		var val = parseInt(this.soundButton.noUiSlider("getValue")[0]) / 100;
		this.currentValue = val;
		this.getPlayer().setVolume(val);
		this.soundIcon[val == 0 ? 'addClass': 'removeClass']('off');
	},
	onSoundClick: function(){
		if (!this.soundIcon.hasClass('off')){
			this.soundButton.noUiSlider("move", { setTo: 0 });		
		} else {
			this.soundButton.noUiSlider("move", { setTo: this.currentValue * 100 });		
		}
		this.soundIcon.toggleClass('off')
	},
	receiveOoyalaEvent: function (playerId, eventName, eventArgs) {
		var ciecc,ttc,ecc,vc;
		// console.log(eventName)
		switch(eventName) {
			case "playerEmbedded":
				this.getPlayer().height = "100%";
				this.getPlayer().width = "100%";
				break;
			case "playheadTimeChanged":
				var time = this.getPlayer().getPlayheadTime();
				this.setScrubber(time);
				//onPlayheadTimeChanged(eventArgs);
				break;
			case "stateChanged":
				//onStateChanged(eventArgs);
				break;
			case "currentItemEmbedCodeChanged":
				//onCurrentItemEmbedCodeChanged(eventArgs);
				ciecc=eventArgs;
				break;
			case "totalTimeChanged":
				//onTotalTimeChanged(eventArgs);
				ttc=eventArgs;
				break;
			case "embedCodeChanged":
				//onEmbedCodeChanged(eventArgs);
				ecc=eventArgs;
				break;
			case "volumeChanged":
				//onVolumeChanged(eventArgs);
				break;
			case "apiReady":
				this.soundButton = $('.sound', this.controlDom).bind('click', _.bind(this.onSoundChange, this));
				this.soundButton.noUiSlider("init", { dontActivate: "lower", scale: [0, 100], startMax: 100 ,tracker: _.bind(this.onSoundChange, this)});
				this.soundIcon =  $('.sound-icon', this.controlDom).bind('click', _.bind(this.onSoundClick, this));

				this.getPlayer().playMovie();
				this.getPlayer().setPlayheadTime(this.seekTime);
				this.totalTime = this.getPlayer().getTotalTime();
				this.timeEndDom.html(this.rectime(Math.floor(parseInt(this.totalTime))));
				//note: apiReady event has no eventArgs (3rd call-back parameter)
				//onCurrentItemEmbedCodeChanged(ciecc);
				//onTotalTimeChanged(ttc);
				//onEmbedCodeChanged(ecc);
				break;
			case "playComplete":
				//note: apiReady event has no eventArgs (3rd call-back parameter)
				//onCurrentItemEmbedCodeChanged(ciecc);
				//onTotalTimeChanged(ttc);
				//onEmbedCodeChanged(ecc);
				this.onVideoComplete();
				break;
		}

	},
	setScrubber: function(time){
		// time = Math.floor(parseInt(time));
		// console.log(time)

		// console.log(time)
		this.timeCurrentDom.html(this.rectime(Math.floor(parseInt(time))));
		var percent = time * 100 / this.totalTime;
		// console.log(percent)
		this.scrubber.css({width: percent + '%'},300);
	},
	rectime: function(secs) {
		var hr = Math.floor(secs / 3600);
		var min = Math.floor((secs - (hr * 3600))/60);
		var sec = secs - (hr * 3600) - (min * 60);
		if (min < 10) {min = '0' + min;}
		if (sec < 10) {sec = '0' + sec;}
		// console.log(hr)
		if (hr > 0){
			hr = hr +':';
		} else {
			hr = '';
		}
		return hr + min + ':' + sec;
	},

	onVideoComplete: function(event){
		this.domNode.empty();
		$(this).trigger('videoComplete', true)
	}
}