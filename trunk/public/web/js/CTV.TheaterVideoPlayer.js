var CTV = CTV || {};
CTV.TheaterVideoPlayer = function(options, domNode) {
	this.options = $.extend({

	}, options);
	this.domNode = domNode;
	this.init();
}
CTV.TheaterVideoPlayer.prototype = {
	init: function() {

		this.hasSeeked = false;
	},
	resize: function(){},
	initPlayer: function() {
		this.show();
		$.ajax({
			url: '/services/Tokenizer/' + this.options.filmId + '/map.smil',
			type: "POST",
			cache: false,
			data: $.param({
				k: this.options.videoData
			}),
			dataType: "json",
			success: _.bind(this.setToken, this),
			error: _.bind(this.setToken, this)
		});
	},
	show: function() {
		this.domNode.fadeIn();
	},
	hide: function() {
		this.domNode.fadeOut();
		swfobject.removeSWF('cPlayer');
		this.domNode.empty();
	},
	onInitPlayerError: function() {
		// console.log('error')
	},
	setToken: function(response) {
		this.state = "playing";
		this.div = 'cPlayer';

		// var response = '';
		if (this.options.runtime) {
			this.seekTime = this.options.runtime;
		} else {
			this.seekTime = 0;
		}

		onJavaScriptBridgeCreated = _.bind(this.onJavaScriptBridgeCreated, this)
		onVideoComplete = _.bind(this.onVideoComplete, this)
		onMediaPlaybackError = _.bind(this.onMediaPlaybackError, this)
		setCurrentTime = _.bind(this.setCurrentTime, this)

		var flashvars = {
			src: response.fileURL,
			plugin_AkamaiAdvancedStreamingPlugin: "http://players.edgesuite.net/flash/plugins/osmf/advanced-streaming-plugin/v2.6/osmf1.6/AkamaiAdvancedStreamingPlugin.swf",
			autoPlay: "false",
			loop: "false",
			//verbose: true,
			sint: this.seekTime,
			controlBarAutoHide: "true",
			controlBarPosition: "bottom",
			ticketParams: this.options.videoData,
			sip: 0,
			sprt: 0,
			javascriptCallbackFunction: 'onJavaScriptBridgeCreated'
		};
		var params = {
			allowFullScreen: 'true',
			allowScriptAccess: 'always',
			wmode: 'direct'
		};
		var attributes = {
			id: 'cPlayer',
			name: 'cPlayer'
		};
		this.domNode.html('<div id="movie-placeholder"></div>');

		swfobject.embedSWF("/flash/StrobeMediaPlayback.swf", "movie-placeholder", '100%', '100%', "10.1.0", "/flash/expressInstall.swf", flashvars, params, attributes);
	},
	onJavaScriptBridgeCreated: function(playerId) {
		if (this.player == null) {
			this.player = document.getElementById(playerId);
    		this.player.addEventListener("complete", "onVideoComplete");
		}

		if (!this.hasSeeked && _.indexOf(["playing", "paused",'buffering'], this.player.getState()) != -1 && this.player.canSeekTo(this.seekTime)) {
			if (this.seekTime > 0) {
				this.player.pause();
				this.player.seek(this.seekTime);
				this.player.play2();
			}
			this.hasSeeked = true;
		}
		if (this.player.getState() == "ready") {
			this.player.pause();
			this.player.play2();
		}
	},
	setServerTime: function(time){
		this.time = parseInt(time); 
	},
	setCurrentTime: function(val){
		//console.log("Current Time is " + val);
	},
	onMediaPlaybackError: function(playerId, code, message, detail) {
		console.log( code)

		if(code != 7 && code != 1003){
			this.hasSeeked = false;
			swfobject.removeSWF(playerId);
	    	this.domNode.html('<div id="movie-placeholder"></div>');
	    	this.options.runtime = Math.floor(this.options.endTime - this.currentTime);
			window.setTimeout(_.bind(this.initPlayer, this), 500);
		}

	},
	onVideoComplete: function(event){
		// console.log('videoComplete')
		$(this).trigger('videoComplete', true)
	}
}