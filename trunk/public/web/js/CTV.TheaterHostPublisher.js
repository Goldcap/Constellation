var CTV = CTV || {};
CTV.TheaterHostPublisher = function(options) {
	this.options = $.extend({
		file: null,
		timeout: 5000,
		isHost: false
	}, options);
	this.init();
}
CTV.TheaterHostPublisher.prototype = {
	init: function() {
		this.isVisible = false;
		this.isToggled = true
		this.isHost = this.options.userId == this.options.hostId;
		this.hostIsLive = true;

		this.connect();
	},
	getArchiveId: function(){
		$.ajax({
			url: '/services/Tokbox/get?screening='+ this.options.room,
			type: "GET",
			cache: false,
			success: _.bind(this.onGetArchiveIdSuccess, this)
		});
	},
	onGetArchiveIdSuccess: function(data){
		this.archiveId = data.length != 0 ? data : null;
		this.connect();
	},

	connect: function() {

		if(window.location.hash == '#debug'){
			TB.setLogLevel(TB.DEBUG);
		}
		
		if(this.options.tokboxSession =='') return;	
		this.Tokbox_Session = TB.initSession(this.options.tokboxSession);

		this.Tokbox_Session.addEventListener('sessionConnected', _.bind(this.onSessionConnected, this));
		this.Tokbox_Session.addEventListener('archiveCreated', _.bind(this.onArchiveCreated, this));
		this.Tokbox_Session.addEventListener('archiveLoaded',  _.bind(this.onArchiveLoaded, this));
		this.Tokbox_Session.addEventListener('sessionRecordingStarted', _.bind(this.onSessionRecordingStarted, this));
		this.Tokbox_Session.addEventListener('sessionRecordingStopped', _.bind(this.onSessionRecordingStopped, this));


		this.Tokbox_Session.connect(this.options.tokboxKey, this.options.tokboxToken);
	},
	onSessionConnected: function(event) {
		if(false && this.options.recordHost && !this.archiveId){
			this.Tokbox_Session.createArchive(this.options.tokboxSession,'perSession');
		} else if(false&&this.options.recordHost && this.archiveId){
			// this.Tokbox_Session.startRecording(this.archive);
			this.Tokbox_Session.loadArchive(this.archiveId);
		} else {
			this.isConnected = true;
			$(this).trigger('TBIsConnected', true);
		}
	},
	onArchiveCreated: function(event){

		this.archive = event.archives[0];
	    this.archiveId = this.archive.archiveId;
		this.Tokbox_Session.startRecording(this.archive);

		$.ajax({
			url: '/services/Tokbox/set?screening='+ this.options.room +'&archive=' + this.archive.archiveId,
			type: "GET",
			cache: false,
			dataType: "json"
		});

	},
	onArchiveLoaded: function(event) {
	    // session.addEventListener('playbackStopped', playbackStoppedHandler);
	    // session.addEventListener('streamCreated', streamCreatedHandler);
	    // console.log(event)
	    this.archive = event.archives[0];
	    this.isConnected = true;
	    // this.Tokbox_Session.startRecording(this.archive);
	},
	onSessionRecordingStarted: function(){
		this.isConnected = true;
		// this.publisher = this.Tokbox_Session.publish('host_stream', {width: 310, height: 200});				
	},
	onSessionRecordingStopped: function(){
		this.Tokbox_Session.unpublish(this.publisher);		
	},

	start: function(domNode, options){
		if(this.isConnected){
			var time = new Date().getTime();
			$(domNode).html('<div id="host_stream_'+ time+'"></div>');
			this.publisher = this.Tokbox_Session.publish('host_stream_' +time, options);
		} else {
			window.setTimeout(_.bind(this.start, this, domNode, options), 300);
		}
	}, 
	stop: function(){
		if(false && this.options.recordHost && this.archive){
			this.Tokbox_Session.stopRecording(this.archive);
		} else if(this.publisher){
			this.Tokbox_Session.unpublish(this.publisher);		
		}
	}
}