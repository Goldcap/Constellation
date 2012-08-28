var CTV = CTV || {};
CTV.TheaterQa = function(options, domNode){
	this.options = $.extend({
		timeout: 60000,
		postTimeout: 5000,
		ishost: 0,
		serviceType:  'qanda',
    userId:  0
    }, options);
    this.domNode = domNode;
    this.init();
}
CTV.TheaterQa.prototype = {

	questionTemplate: '<div class="qa-question-avatar"><img class="avatar" src="{{user_image}}" height="48" width="48"/></div><div class="qa-question-text"><h3><a href="/profile/{{author}}" target="_blank">{{isfrom}}</a> asked:</h3><p>{{body}}</p></div>',
	init: function(){
		this.isVisible = false;
		this.hostIsLive = true;
		this.currentQuestion = null;
		this.sequenceApproved = 0;
		this.pollIsRunning = false;
		this.qaIsShowing = false;
		this.attachPoints();
		this.attachEvents();
		this.isHost = this.options.userId == this.options.hostId;

		if(this.isHost){
			this.qaCollection = [];
			this.currentQuestionIndex = 0;
			this.qaViewerControlContainer.remove();
			this.checkButtonsStatus();

			// this.getInit();
			this.addSwitch();
			this.hostPlaceholder.addClass('is-publisher');
		} else {
			this.qaHostControlContainer.remove();
			this.qaStartButton.remove();
			this.hostPlaceholder.addClass('is-subscriber');
		}

		this.connection = new CTV.Connection(this.options);
		// $(this.connection).bind('initSuccess', _.bind(this.onInitSuccess, this));
		$(this.connection).bind('postSuccess', _.bind(this.onPostSuccess, this));
		$(this.connection).bind('updateSuccess', _.bind(this.onUpdateSuccess, this));
	},
	onPostSuccess: function() {
		if(!this.isHost){
			this.qaViewerControlContainer.hide();
			this.qaViewerControlSuccess.fadeIn()
			window.setTimeout(_.bind(this.hideSuccess, this), 10000)
		}


	},
	onUpdateSuccess: function(event, response) {
		console.log("QA SUCCESS WITH " + response.qanda.length);
        _.each(response.qanda, _.bind( this.iterateQuestion, this));
	},
	attachPoints: function(){
		this.parentDomNode = this.domNode.parent();
		this.qaInput = $('#qa-input', this.domNode);
		this.qaSubmit = $('#qa-submit', this.domNode);
		this.questionContainer = $('#qa-question', this.domNode);

		this.hostPlaceholder = $('#qa-video-container', this.domNode);

		this.qaQuestionContainer = $('.qa-question-container');
		//this.qaPollContainer = $('.qa-poll-container');


		this.qaHostControlContainer = $('#qa-input-container-host', this.domNode);
		this.qaViewerControlContainer = $('#qa-input-container-viewer', this.domNode);
		this.qaViewerControlSuccess = $('#qa-input-container-success', this.domNode);

		this.qaNextButton = $('#qa-next', this.domNode);
		this.qaPreviousButton = $('#qa-previous', this.domNode);
		this.qaPublishButton = $('#qa-pick', this.domNode);
		this.questionPreview = $('#qa-question-preview', this.domNode);

		this.qaStartButton = $('#qa-start', this.domNode);
		this.qaLobby = $('#qa-lobby', this.domNode);
		this.qaPanel = $('#qa-panel', this.domNode);

		//this.qaPollButton = $('#qa-poll', this.domNode)
		//this.qaPollTextOpen = $('#qa-poll-text-open', this.domNode);
		//this.qaPollTextClose = $('#qa-poll-text-close', this.domNode);

	},
	addSwitch: function(){
		this.switchButton = $('<p class="switch-container"><span class="switch"></span>Turn Camera On/Off</p>').insertBefore(this.hostPlaceholder);
		this.switchButton.bind('click', _.bind(this.onSwitchClick, this));
		this.endQaButton = $('<p class="end-qa-container"><span class="button button_blue button-medium uppercase">End Q&amp;A</span></p>').insertBefore(this.hostPlaceholder);
		this.endQaButton.bind('click', _.bind(this.onEndQaClick, this));
		//this.qaPollButton.bind('click', _.bind(this.onQaPollClick, this));
	},
	showQa: function(){
		if(!this.qaIsShowing){
			this.qaIsShowing= true;
			this.qaLobby.hide();
			this.qaPanel.fadeIn();
			this.setPosition()
			this.startStream();
			// if(this.options.room)

			// console.log('f')
			//this.poll = new CTV.TheaterPoll(this.options, this.qaPollContainer);
			//$(this.poll).bind('showPoll', _.bind(this.onShowPoll, this));
			//$(this.poll).bind('hidePoll', _.bind(this.onHidePoll, this));
		}

	},
	showLobby: function(){
		this.qaPanel.hide();
		this.qaLobby.fadeIn();
		this.setPosition();
	},
	attachEvents: function() {
		this.qaSubmit.bind('click', _.bind(this.sendQa, this));
		this.qaNextButton.bind('click', _.bind(this.onNextQuestion, this));
		this.qaPreviousButton.bind('click', _.bind(this.onPreviousQuestion, this));
		this.qaPublishButton.bind('click', _.bind(this.onPublishQuestion, this));
		this.qaStartButton.bind('click', _.bind(this.onQaStart, this));
	},

	// onInitSuccess: function(event, response){
	// 	_.each(response.qanda, function(qanda){
	// 		if(qanda.approved == -1) {
	// 			this.qaCollection.push(qanda);
	// 			this.dequeueQuestion();
	// 		} else if(qanda.approved == 1){
	// 			this.showQuestion(qanda);
	// 		}
	// 	},this);
	// 	this.checkButtonsStatus();
	// },
	iterateQuestion: function(question){
		console.log("ITERATE SUCCESS");
        if(question.type =='qanda'){
			this.queueQA(question)
		} else if (question.type =='qastart'){
			this.showQa();
		} else if (question.type =='qaend'){
			$(this).trigger('qaend');
		}
	},
	show: function(){
		if(!this.isVisible){
			this.isVisible = true;
			this.domNode.fadeIn();
			this.setPosition();
			this.connection.update();
			// this.getLatestQaQuestion();
		}
	},
	hide: function(){
		if(this.isVisible){
			this.isVisible = true;
			this.domNode.hide();
			$(this).trigger('hostHide', true);
		}
	},
	setPosition: function(){
		if(this.isVisible){
			var dh = this.domNode.height();
			var ph = this.parentDomNode.height();

			if(ph - dh - 80   > 0){
				this.domNode.css({
					'margin-top': (ph - dh) /2
				});
			} else {
				this.domNode.css({
					'margin-top': 40
				});
			}
		}
	},
	onQaStart: function(){
		this.connection.post({body:'start', type:'qastart'})
	},

	onEndQaClick: function(){
		Dialog.open({
			title: 'Close Q&amp;A',
			body: 'Are you sure you want to end the Q&amp;A?',
			buttons: [
				{
					text: 'Yes, End Q&amp;A',
					callback: _.bind(this.onEndQaComfirm, this)
				},
				{
					text: 'No, Continue Q&amp;A'
				}
			]
		})	
	},
	onEndQaComfirm: function(){
		this.connection.post({body:'end', type:'qaend'})
	},
	onEndQaSuccess: function(){
		
	},
	startStream: function(){
		$(this).trigger('getStream', [this.hostPlaceholder, {width: 560, height: 320, encodedHeight: 380, encodedWidth: 540}]);
	},

	sendQa: function() {
		if(this.qaInput.val() == '') return;
		this.connection.post({body:this.qaInput.val(), type: 'qanda'})

		this.qaInput.val('').focus().blur();
	},
	/*onSendQaSuccess: function(response) {

		this.qaViewerControlContainer.hide();
		this.qaViewerControlSuccess.fadeIn()
		window.setTimeout(_.bind(this.hideSuccess, this), 10000)
	},
	onSendQaComplete: function(a,b){
		this.onSendChatFailure()	
	},
	onSendQaFailure: function() {

	},*/
	queueQA: function(question, index){
		if(this.isHost && question.approved == -1){
			this.qaCollection.push(question);
			this.dequeueQuestion();
		} else if(question.approved == 1 && this.sequenceApproved < question.sequence_approved){
			this.sequenceApproved = question.sequence_approved;
			this.showQuestion(question);
		} else if(question.approved == 0){
			this.unshiftQuestion(question)
		}
	},
	dequeueQuestion: function(){
		if(this.qaCollection.length == 1){
			this.currentQuestionIndex = 0;
			this.previewQuestion(this.qaCollection[this.currentQuestionIndex]);
		}
		this.checkButtonsStatus();
	},
	showQuestion: function(question){
		this.questionContainer.html(_.template(this.questionTemplate, question));
		if(this.isHost){
			this.unshiftQuestion(question)
		}
	},
	unshiftQuestion: function(question){

		this.qaCollection = _.reject(this.qaCollection, function(q){ return q.id == question.id; });

		if(this.currentQuestion && this.currentQuestion.id == question.id){
			this.getNextPossibleQuestion();
		} 

		this.checkButtonsStatus();
	},
	previewQuestion: function(question){
		this.currentQuestion = question;
		this.questionPreview.html(_.template(this.questionTemplate, question))
	},
	onNextQuestion: function(){
		if(this.qaCollection.length > 0 && this.qaCollection.length > (this.currentQuestionIndex +1)){
			this.currentQuestionIndex ++;
			this.previewQuestion(this.qaCollection[this.currentQuestionIndex]);
			this.checkButtonsStatus();
		}
	},
	onPreviousQuestion: function(){
		if(this.qaCollection.length > 0 && this.currentQuestionIndex > 0){
			this.currentQuestionIndex --;
			this.previewQuestion(this.qaCollection[this.currentQuestionIndex]);
			this.checkButtonsStatus();
		}
	},
	checkButtonsStatus: function(){
		if(this.qaCollection.length && this.qaCollection.length > (this.currentQuestionIndex +1)){
			this.qaNextButton.removeClass('disabled');
		} else {
			this.qaNextButton.addClass('disabled');
		}
		if(this.qaCollection.length && this.currentQuestionIndex != 0){
			this.qaPreviousButton.removeClass('disabled');
		} else {
			this.qaPreviousButton.addClass('disabled');
		}
	},
	onPublishQuestion: function(){
		if(this.qaPublishButton.hasClass('disabled')) return;
		if(this.qaCollection.length && !this.qaCollection[this.currentQuestionIndex]) return
		var question = this.qaCollection[this.currentQuestionIndex];

		if(question){
			this.connection.approve(question.id, 1);
			this.qaCollection.splice(this.currentQuestionIndex,1);
			this.getNextPossibleQuestion();

			this.checkButtonsStatus();
		}


		/*var args = {
			"instance": this.options.instance,
			"room": this.options.room,
			"cookie": this.options.cookie,
			"mdt": this.options.mdt,
			"cmo": this.options.cmo,
			"cursor": question.id,
			"allow": 1,
			type: 'qanda'
		};
		//**if (chat.cursor) args.cursor = chat.cursor;
		$.ajax({
			url: "/services/chat/approve?i=" + this.ip,
			type: "GET",
			cache: false,
			dataType: "json",
			data: $.param(args),
			success: _.bind(this.onPublishQuestionSuccess,this),
			error: _.bind(this.onPublishQuestionError,this)
		});*/

	},
	getNextPossibleQuestion: function(){
		if(this.currentQuestionIndex < this.qaCollection.length -1){
			this.previewQuestion(this.qaCollection[this.currentQuestionIndex])
		} else if(this.qaCollection.length && (this.currentQuestionIndex >= 1) ){
			this.currentQuestionIndex--;
			this.previewQuestion(this.qaCollection[this.currentQuestionIndex])
		} else if(this.qaCollection.length == (this.currentQuestionIndex + 1)){
			this.previewQuestion(this.qaCollection[this.currentQuestionIndex])
		} else {
			this.currentQuestionIndex = 0;
			this.currentQuestion = null;
			this.questionPreview.html('<p class="qa-no-selection">There are no questions in the queue</p>')
		}
	},
	onPublishQuestionSuccess: function(response){
		// this.showQuestion(response)

	},
	onPublishQuestionError: function(){
		
	},
	showSuccess: function(){
		this.qaViewerControlContainer.hide();
		this.qaViewerControlSuccess.fadeIn();
		window.setTimeout(_.bind(this.hideSuccess, this), 10000)
	},
	hideSuccess: function(){
		this.qaViewerControlSuccess.hide()
		this.qaViewerControlContainer.fadeIn();
	},
	onSwitchClick: function(){
		this.hostIsLive = !this.hostIsLive;
		this.switchButton[this.hostIsLive? 'removeClass': 'addClass']('off');

		if(!this.hostIsLive){
			$(this).trigger('publishHost', false)
		} else {
			$(this).trigger('publishHost', true)
		}
	},
	onShowPoll: function(){
		this.pollIsRunning = true;
		this.qaQuestionContainer.hide();
		this.qaPollContainer.show();
		this.qaPublishButton.addClass('disabled');
		this.qaPollButton.html('Stop Poll');
		this.qaPollTextClose.show();
		this.qaPollTextOpen.hide();		
	},
	onHidePoll: function(){
		this.pollIsRunning = false;
		this.qaQuestionContainer.show();
		this.qaPollContainer.hide();
		this.qaPublishButton.removeClass('disabled')
		this.qaPollButton.html('Start Poll');
		this.qaPollTextOpen.show();
		this.qaPollTextClose.hide();
	},
	onQaPollClick: function(){
		if(!this.pollIsRunning){
			this.poll.pollStart();
		} else {
			this.poll.pollStop();
		}
	}
}