var CTV = CTV || {};
CTV.Connection = function(options) {
	this.options = $.extend({
		timeout: 60000,
		postTimeout: 5000,
		//baseUrl: '//' + window.location.host + ':81'
		baseUrl: '//' + window.location.host
	}, options);
	this.init();
}

CTV.Connection.prototype = {
	init: function(){
		this.sequence = 0;
		this.sequenceApproved = 0;  
		this.colorSequence = 0;
		this.updateFailureCount = 0;
		this.postData = [];
		this.approveData = [];
		this.sendIsRunning = false;
		this.approveIsRunning = false;
		this.timestamp = 0;
	},
	update: function(){
		var args = {
			instance: this.options.instance,
			room: this.options.room,
			cookie: this.options.cookie,
			mdt: this.options.mdt,
			cmo: this.options.cmo,
			ishost: this.options.ishost,
			u: this.options.userId,
			s: this.sequence,       
			a: this.sequenceApproved,
			c: this.colorSequence,
			t: this.timestamp,
			p: this.options.port
		};
		this.currentRequest = $.ajax({
			url: this.options.baseUrl + '/services/' + this.options.serviceType +'/update',
			type: "POST",
			cache: false,
			dataType: "json",
			data: args,
			timeout: this.options.timeout,
			complete: _.bind(this.onUpdateComplete, this),
			success: _.bind(this.onUpdateSuccess, this),
			error: _.bind(this.onUpdateFailure, this)
		});
	},
	onUpdateSuccess: function(response){
		this.updateFailureCount = 0;
		if (response.meta.status == 200) {
			if(response.sequence){
				this.sequence = response.sequence;
			}		
			if(response.sequence){
				this.sequenceApproved = response.sequence_approved;
			}	

			if(response.colorsequence){
				this.colorSequence = response.colorsequence;
			}
			if(response.meta.timestamp){
				this.timestamp = response.meta.timestamp;
			}
			

			$(this).trigger('updateSuccess', response.response);
		}
	},
	onUpdateFailure: function(){
		$(this).trigger('updateFailure');
		this.updateFailureCount++;
	},
	onUpdateComplete: function(){
		if(this.updateFailureCount > 10){
			window.setTimeout(_.bind(this.update, this), 5000);	
		} else {
			this.update();
		}
	},
	post: function(arg){
		this.postData.push(arg);
		this.dequeuePostData();
	},
	dequeuePostData: function(){
		if(!!this.postData.length && !this.sendIsRunning){
			var postData = this.postData.shift();
			this.send(postData)
		}	
	},
	send: function(postData){
		this.sendIsRunning = true;
		var args = {
			room: this.options.room,
			instance: this.options.instance,
			author: this.options.userId,
			cookie: this.options.cookie,
			film: this.options.filmId,
			user_image: this.options.userImage,
			ishost: this.options.ishost,
			mdt: this.options.mdt,
			cmo: this.options.cmo,
			body: postData.body,
			type: postData.type,
			u: this.options.userId,
			p: this.options.port
		}
		if(postData.color) args.color = postData.color;

		$.ajax({
			url: this.options.baseUrl + '/services/' + this.options.serviceType + '/post',
			data: args,
			type: "POST",
			cache: false,
			dataType: "json",
			timeout: this.options.timeout,
			complete: _.bind(this.onSendComplete, this),
			success: _.bind(this.onSendSuccess, this),
			failure: _.bind(this.onSendFailure, this)
		});
	},
	onSendSuccess: function(response){
		if (response && typeof response == 'object') {
			$(this).trigger('postSuccess', response)
		}
	},
	onSendFailure: function(){
		$(this).trigger('postFailure')
	},
	onSendComplete: function(){
		this.sendIsRunning = false;
		this.dequeuePostData();	
	},
	approve: function(id, allow){
		this.approveData.push({id:id, allow: allow}); 
		this.dequeueApproveData();
	},
	dequeueApproveData: function(){

		if(!!this.approveData.length && !this.approveIsRunning){
			var approveData = this.approveData.shift();
			this.moderate(approveData)
		}	
	},	
	moderate: function(approveData){
		this.approveIsRunning = true;
		var args = {
			instance: this.options.instance,
			room: this.options.room,
			cookie: this.options.cookie,
			mdt: this.options.mdt,
			cmo: this.options.cmo,
			ishost: this.options.ishost,
			cursor: approveData.id,
			allow: approveData.allow,
			p: this.options.port
		};

		$.ajax({
			url: this.options.baseUrl + '/services/'+ this.options.serviceType +'/approve',
			type: "GET",
			cache: false,
			dataType: "json",
			data: args,
			complete: _.bind(this.onModerateComplete,this),
			success: _.bind(this.onModerateSuccess,this),
			error: _.bind(this.onModerateFailure,this)
		});
	},
	onModerateComplete: function(){
		this.approveIsRunning = false;
		this.dequeueApproveData();	
	},
	onModerateSuccess: function(){
		$(this).trigger('approveSuccess');
	},
	onModerateFailure: function(){
		$(this).trigger('approveFailure');
	},
	changeInstance: function(instance, port){
		// console.log(port);
		if(instance && port){
			this.options.instance = instance;	
			this.options.port = port	
		}
		this.sequence = 0;
		this.sequenceApproved = 0;
		this.colorSequence = 0;

		this.currentRequest.abort();
	},
	search: function(postData){
		this.searchIsRunning = true;
		var args = {
			room: this.options.room,
			instance: this.options.instance,
			author: this.options.userId,
			cookie: this.options.cookie,
			ishost: this.options.ishost,
			mdt: this.options.mdt,
			cmo: this.options.cmo,
			term: postData,
			p: this.options.port
		}
		if(this.serachRequest) this.serachRequest.abort();
		this.serachRequest = $.ajax({
			url: this.options.baseUrl + '/services/chat/search',
			data: args,
			type: "GET",
			cache: false,
			dataType: "json",
			timeout: this.options.timeout,
			complete: _.bind(this.onSearchComplete, this),
			success: _.bind(this.onSearchSuccess, this),
			failure: _.bind(this.onSearchFailure, this)
		});
	},
	onSearchComplete: function(){
		this.searchIsRunning = false;
	},
	onSearchSuccess: function(response){
		$(this).trigger('searchSuccess',response);
	},
	onSearchFailure: function(){
		$(this).trigger('searchFailure');
	},
	block: function(userId,type){
		if (type == "unblock") {
			action = "unblock";
		} else {
		  action = "block";
		}
		var args = {
			room: this.options.room,
			cookie: this.options.cookie,
			mdt: this.options.mdt,
			cmo: this.options.cmo,
			userId: userId,
			sev: type,
			act: action
		};
		this.currentRequest = $.ajax({
			url: this.options.baseUrl + '/services/chat/block',
			type: "GET",
			cache: false,
			dataType: "json",
			data: args,
			timeout: this.options.timeout,
			complete: _.bind(this.onBlockComplete, this),
			success: _.bind(this.onBlockSuccess, this),
			error: _.bind(this.onBlockFailure, this)
		});
	},
	onBlockComplete: function(){
		this.blockIsRunning = false;
	},
	onBlockSuccess: function(response){
		$(this).trigger('blockSuccess',response);
	},
	onBlockFailure: function(){
		$(this).trigger('blockFailure');
	}
}