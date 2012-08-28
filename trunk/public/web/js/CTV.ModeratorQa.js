var CTV = CTV || {};
CTV.ModeratorQa = function(options, domNode) {
	this.options = $.extend({
		file: null,
		// timeout: 5000,
		// isHost: false
	}, options);
	this.domNode = $(domNode);
	this.init(); 
}
CTV.ModeratorQa.prototype = {
	approvedTemplate: '<div pair="public" to="public" from="{{isfrom}}" instance="{{instance}}" type="chat" cursor="{{id}}" id="m{{id}}" class="chat_message"><span class="chat_icon chat_icon_user_2216"><img width="40" src="{{user_image}}"></span><span class="chat_text"><h5><a target="_blank" href="/profile/{{author}}">{{isfrom}}</a></h5><p>{{body}}</p></span></div>',
	unapprovedTemplate: '<div pair="public" to="public" from="{{isfrom}}" instance="{{instance}}" type="chat" cursor="{{id}}" id="m{{id}}" class="chat_message"><span class="chat_icon chat_icon_user_2216"><img width="40" src="{{user_image}}"></span><span class="chat_text"><h5><a target="_blank" href="/profile/{{author}}">{{isfrom}}</a></h5><p>{{body}}</p></span><p class="chat_message_moderation"><span class="chat_message_approve" data-id="{{id}}">Answer</span><span class="chat_message_decline" data-id="{{id}}">Decline</span></p></div>',
	init: function() {
		this.isVisible = false;
		this.ip = this.options.host + ':' + (this.options.port + 9090);
		this.sequenceApproved = 0;
		this.sequence = 0
		this.timestamp = 0;
		this.attachPoints();
		this.attachEvents();
		//this.getInit();
		this.getLatestQaQuestion();
	},
	attachPoints: function(){
		this.questionContainer = $('.chat-list', this.domNode);
		this.questionContent = $('.content', this.questionContainer);
		this.questionCurrent = $('.current-question', this.domNode);
	},
	attachEvents: function(){
		this.domNode.delegate('.chat_message_decline, .chat_message_approve', 'click', _.bind(this.onModerate, this));

	},
	resize: function(y) { 
		this.questionContainer.height(y - 275);
		if (!this.isVisible) {
			this.domNode.fadeIn();
		}
	},
	addApproved: function(question){
		$('#m'+question.id, this.questionContent).remove();

		this.questionCurrent.html($(_.template(this.approvedTemplate, question)));
	},
	addUnapproved: function(question){
		this.questionContent.append($(_.template(this.unapprovedTemplate, question)));
	},
	removeUnapproved: function(question){
		$('#m'+question.id, this.questionContent).remove();	
	},
	getLatestQaQuestion: function(){
		var args = {
			"instance": this.options.instance,
			"room": this.options.room,
			"cookie": this.options.cookie,
			"mdt": this.options.mdt,
			"cmo": this.options.cmo,
			t: this.timestamp,
			u: this.options.userId,
			"s": this.sequence,
			"p": this.options.port
		};
		$.ajax({
			url: '/services/qanda/update',
			type: "POST",
			cache: false,
			data: $.param(args),
			dataType: "json",
			success: _.bind(this.getLatestQaQuestionSuccess, this),
			complete: _.bind(this.getLatestQaQuestion, this),
		})
	},
	getLatestQaQuestionSuccess: function(response){

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
			// console.log(question.approved == 1, this.sequenceApproved < question.sequence_approved)
			// console.log(question.approved == 1, this.sequenceApproved < question.sequence_approved)

			_.each(response.response.qanda, function(question){
				if(question.type != "qanda") return;
				if( question.approved == -1){
					this.addUnapproved(question)
				} else if(question.approved == 1 && this.sequenceApproved == question.sequence_approved){
					this.addApproved(question);
				} else if(question.approved == 0){
					this.removeUnapproved(question);
				}
			}, this);
		}		

	},
	onModerate: function(event) {
		var target = $(event.target),
			allow = 0,
			id = target.data('id')

		if (target.hasClass('chat_message_approve')) {
			allow = 1;
		}

		target.parents('.chat_message').remove();

		var args = {
			// "instance": this.options.instance,
			// "room": this.options.room,
			// "cookie": this.options.cookie,
			// "mdt": this.options.mdt,
			// "cmo": this.options.cmo,
			// "cursor": id,
			// "allow": allow,
			// "type": 'qanda'
			instance: this.options.instance,
			room: this.options.room,
			cookie: this.options.cookie,
			mdt: this.options.mdt,
			cmo: this.options.cmo,
			ishost: this.options.ishost,
			cursor: id,
			allow: allow,
			p: this.options.port
		};
		//**if (chat.cursor) args.cursor = chat.cursor;
		$.ajax({
			url: "/services/qanda/approve",
			type: "GET",
			cache: false,
			dataType: "json",
			data: $.param(args),
			success: _.bind(this.onModerateSuccess,this),
			error: _.bind(this.onModerateError,this)
		});

	},

	onModerateSuccess: function(response) {
		try {
			if ($("div[cursor="+response.id+"]", this.questionContent).length > 0) {
			 	$("div[cursor="+response.id+"]", this.questionContent).remove();
			}
		} catch (e) {
			this.onGetInitFailure();
			return;
		}
	},

	onModerateError: function(response) {
		//Try to init in five more seconds
		// window.setTimeout(chat.init, 5000);
	}
}