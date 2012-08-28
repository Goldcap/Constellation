var CTV = CTV || {};
CTV.TheaterHostSubscriber = function(options) {
	this.options = $.extend({
		file: null,
		timeout: 5000,
		isHost: false
	}, options);
	this.init();
}
CTV.TheaterHostSubscriber.prototype = {
	init: function() {
		this.isVisible = false;
		this.isToggled = true;
		this.hostIsLive = true;

		this.connect();

	},
	connect: function(){
		if(window.location.hash == '#debug'){
			TB.setLogLevel(TB.DEBUG);
		}		
		if(this.options.tokboxSession =='') return;
		// TB.setLogLevel(TB.DEBUG);
		this.Tokbox_Session = TB.initSession(this.options.tokboxSession);
		this.Tokbox_Session.addEventListener('sessionConnected', _.bind(this.onSessionConnected, this));
		this.Tokbox_Session.addEventListener('streamCreated', _.bind(this.onStreamCreated, this));
		this.Tokbox_Session.addEventListener('streamDestroyed', _.bind(this.onStreamDestroyed, this));
		this.Tokbox_Session.addEventListener('connectionDestroyed', _.bind(this.onSessionStop, this));
		this.Tokbox_Session.addEventListener('streamPropertyChanged', _.bind(this.onSessionChange, this));

		this.Tokbox_Session.connect(this.options.tokboxKey, this.options.tokboxToken);
	},
	onSessionConnected: function(event){
		if (event.streams.length == 0) {
			return;
		} else {
			var stream = event.streams[0];
		}
		// Make sure we don't subscribe to ourself
		if (stream.connection.connectionId == this.Tokbox_Session.connection.connectionId) {
			return;
		} else if(this.stream != stream){
			this.stream = stream;
			$(this).trigger('TBIsConnected', true);
		}
	},
	onStreamCreated: function(event) {
		if (event.streams.length == 0) {
			return;
		} else {
			var stream = event.streams[0];
		}
		// Make sure we don't subscribe to ourself
		if (stream.connection.connectionId == this.Tokbox_Session.connection.connectionId) {
			return;
		} else if(this.stream != stream){
			this.stream = stream;
			$(this).trigger('TBIsConnected', true);
		}
	},
	onStreamDestroyed: function(){
		this.stop();
		$(this).trigger('TBIsConnected', false);
	},
	onSessionStop: function() {
		this.stop();
		$(this).trigger('TBIsConnected', false);
	},
	onSessionChange: function(event) {
		// console.log(event);
		// console.log(event.changedProperty);
		// console.log(event.newValue);
	},
	start: function(domNode, options){
		if(this.stream){
			var time = new Date().getTime();
			$(domNode).html('<div id="host_stream_'+ time +'"></div>');
			this.subscriber = this.Tokbox_Session.subscribe(this.stream, 'host_stream_'+ time, options);
		} else {
			window.setTimeout(_.bind(this.start, this, domNode, options), 300);
		}
	},
	stop: function(){
		if(this.subscriber){
			this.Tokbox_Session.unsubscribe(this.stream);
			this.subscriber = null;
		}
	}
}