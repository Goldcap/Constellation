var CTV = CTV || {};
CTV.TheaterChat = function(options, domNode) {
	this.options = $.extend({
		timeout: 60000,
		postTimeout: 5000,
		footerHeight: 61,
		chatInputHeight: 83,
		titleHeight: 34,
		hostHeight: 226,
		ishost: 0,
		hostIsVisible: false,
		hasVideo: false,
		isModeratorPanel: false,
		moderatorNode: null,
		searchNode: null,
		insertSeek: 10,
		serviceType: 'chat',
		port: 0,
		userId: 0,
		colorMap: {
			none: 'is meh',
			heart: 'is in love',
			happy: 'is happy',
			sad: 'is sad',
			wow: 'is shocked'
		}
	}, options);
	this.domNode = $(domNode);
	this.init();

}

CTV.TheaterChat.prototype = {
	
	userTemplate:  '<div class="chat_message {{color}}" id="m{{id}}" cursor="{{id}}" type="{{type}}" instance="{{instance}}" from="{{isfrom}}" to="{{to}}" pair="{{pair}}" sequence="{{sequence}}"><div class="chat_icon chat_icon_user_{{author}}"><a href="/profile/{{author}}" target="_blank" class="color"><img src="{{user_image}}"/><span class="colorme-icon-small"></span></a></div><div class="chat_text"><h5><a href="/profile/{{author}}" target="_blank">{{isfrom}}</a></h5><p>{{body}}</p></div></div>',
	hostTemplate:  '<div class="chat_message chat_message_host {{color}}" id="m{{id}}" cursor="{{id}}" type="{{type}}" instance="{{instance}}" from="{{isfrom}}" to="{{to}}" pair="{{pair}}" sequence="{{sequence}}"><div class="chat_icon chat_icon_user_{{author}}"><a href="/profile/{{author}}" target="_blank" class="color"><img src="{{user_image}}"/><span class="colorme-icon-small"></span></a></div><div class="chat_text"><h5><a href="/profile/{{author}}" target="_blank">{{isfrom}}</a><span class="chat_host"><img src="/images/alt1/chat_host.png" /></span></h5><p>{{body}}</p></div></div>',
	moderatorTemplate:  '<div class="chat_message {{color}}" id="m{{id}}" cursor="{{id}}" type="{{type}}" instance="{{instance}}" from="{{isfrom}}" to="{{to}}" pair="{{pair}}" sequence="{{sequence}}"><div class="chat_icon chat_icon_user_{{author}}"><a href="/profile/{{author}}" target="_blank" class="color"><img src="{{user_image}}"/><span class="colorme-icon-small"></span></a></div><div class="chat_text"><h5><a href="/profile/{{author}}" target="_blank">{{isfrom}}</a><span class="chat_host"><img src="/images/alt1/chat_moderator.png" /></span></h5><p>{{body}}</p></div></div>',
	chatApprove: '<p class="chat_message_moderation"><span class="chat_message_approve" data-id="{{id}}">Approve</span><span class="chat_message_decline" data-id="{{id}}">Decline</span></p>',
	roomTemplate: '<li class="clearfix"><span class="room-name">Room {{name}}</span><span class="room-count">({{count}} users)</span></li>',
	init: function() {
		this.attachPoints();
		this.attachEvents();
		if (this.options.isModeratorPanel) { 
			this.attachModeratorPoints();
			this.attachModeratorEvents();
		}
		this.isVisible = false;
		this.hasInitializedUpdates = false;
		this.state = null;
		this.isOpen = true;
		this.hostIsToggled = true;
		this.isHost = this.options.hostId == this.options.userId && this.options.userId != 0;
		this.chatCache = [];

		this.currentColor = '';

		if(!this.options.hasTicket) {
			this.chatInput.parent().hide()
			this.domNode.css({
				bottom: 0
			});
			this.changeRoomDom.hide();
		}
		this.createConnection();
		shareChatOnTwitter = _.bind(this.shareChatOnTwitter, this);
		shareChatOnFacebook = _.bind(this.shareChatOnFacebook, this);
	},
	createConnection: function(){
	
		this.connection = new CTV.Connection(this.options);
		// $(this.connection).bind('postSuccess', _.bind(this.onPostSuccess, this));
		$(this.connection).bind('updateSuccess', _.bind(this.onUpdateSuccess,this));
		$(this.connection).bind('postSuccess', _.bind(this.onPostSuccess,this));
		this.connection.update();
	},
	attachPoints: function() {
		this.chatContainer = $('.chat-list', this.domNode);
		this.chatContent = $('.content', this.chatContainer);
		this.chatInput = $('.chat-input', this.domNode);
		this.slider = $('.slider-border', this.domNode.parent());
		// this.userCountDom = $('.user-count', this.domNode);
		this.changeRoomDom = $('.chat-room', this.domNode);
		this.roomContainer = $('#room-content')
		this.roomContent = $('.content', this.roomContainer)
	},
	attachEvents: function() {
		this.chatContainer.nanoScroller();
		this.chatInput.bind('keypress', _.bind(this.onChatInputKeypress, this));
		this.slider.bind('click', _.bind(this.onSliderClick, this));
		if (! this.options.isModeratorPanel) {
			this.domNode.delegate('.chat_message_decline, .chat_message_approve', 'click', _.bind(this.onModerate, this));
		}
		this.changeRoomDom.bind('click', _.bind(this.getRooms, this))
		this.roomContainer.nanoScroller();
	},
	attachModeratorPoints: function() {
		this.moderatorContainer = $('.chat-list', this.options.moderatorNode);
		this.moderatorContent = $('.content', this.options.moderatorNode);
		this.moderatorInput = $('.chat-input', this.options.moderatorNode);
		this.moderatorSlider = $('.slider-border', this.options.moderatorNode.parent());
	},
	attachModeratorEvents: function() {
		this.moderatorContainer.nanoScroller();
		this.moderatorInput.bind('keypress', _.bind(this.onChatInputKeypress, this));
		this.moderatorSlider.bind('click', _.bind(this.onSliderClick, this))
		this.options.moderatorNode.delegate('.chat_message_decline, .chat_message_approve', 'click', _.bind(this.onModerate, this));
		
	},
	onSliderClick: function(){
		if(this.isOpen){
			this.isOpen = false;
			this.close();
		} else {
			this.isOpen = true;
			this.open()
		}
	},
	open: function(){
		this.domNode.parent().animate({'margin-left': 0})	
	},
	close: function(){
		this.domNode.parent().animate({'margin-left':-310})	
	},
	resize: function(y) { 
		if(this.options.isModeratorPanel){
			this.chatContainer.height(y);
					this.roomContainer.height(y);

			this.moderatorContainer.height(y);
			if (!this.isVisible) {
				this.domNode.fadeIn();
				this.options.moderatorNode.fadeIn();
			}
			return;
		}
		var height = 
				y 
				- (this.options.footerHeight 
					+ (this.options.hasTicket ? this.options.chatInputHeight: 0)
					+ this.options.titleHeight 
					+ ( this.state != 'QA' && this.options.hasVideo && this.options.hasTicket ? this.options.hostHeight : 0)
					- (!this.hostIsToggled && this.state != 'QA' && this.options.hasVideo && this.options.hasTicket ? 227 : 0)
				);

		this.roomContainer.height(height);
		this.chatContainer.height(height);
		this.lastHeight = !this.hostIsToggled && this.state != 'QA' && this.options.hasVideo && this.options.hasTicket ? height -227 : height;
		if (!this.isVisible) {
			this.domNode.fadeIn();
			if (this.options.isModeratorPanel) {
				this.options.moderatorNode.fadeIn();
			}
		}
	},
	setUserCount: function(count){
		this.userCountDom.html('(' + count + ' users)')	
	},
	toggleHost: function(bool){
		this.hostIsToggled = bool;

		this.chatContainer.stop().animate({
			height: bool? this.lastHeight: this.lastHeight + 190
		},100, _.bind(this.refreshScroller, this, true));

		this.roomContainer.stop().animate({
			height: bool? this.lastHeight: this.lastHeight + 190
		},100, _.bind(this.refreshScroller, this, true));
	},
	hostIsHidden: function(){
		this.options.hostIsVisible = false;
	},
	hostIsShown: function(){
		if(this.state == 'QA') return;
		this.options.hostIsVisible = true;
	},
	setState: function(state){
		this.state = state;	
	},
	refreshScroller: function(bool) {
		var scrollHeight = this.chatContent.prop("scrollHeight"),
			scrollTop = this.chatContent.prop("scrollTop"),
			height = this.chatContent.height();

		if(bool || !this.hasInitializedUpdates || scrollHeight - height - scrollTop < 500){
			this.chatContent.prop("scrollTop",scrollHeight )
		}
		this.chatContainer.nanoScroller();

	},
	onUpdateSuccess: function(event, response){
		this.renderUpdate(response);
	},
	onPostSuccess: function(event, response){
		if(response.meta.block){
			this.chatInput.addClass('error').val('You have been blocked from chatting.').attr('disabled', true);
			setTimeout(_.bind(function(){
				this.chatInput.removeClass('error').val('').attr('disabled', false);
			},this),5000)
		}
	},
	postColor: function(arg){
		this.currentColor = arg;		
		this.connection.post({color: arg, body: this.mapColorText(arg), type: 'chat'});	
	},
	mapColorText: function(arg){
		return this.options.colorMap[arg];
	},
	renderUpdate: function(response) {
		//$(this).trigger('colorMe', [response.colorme, this.hasInitializedUpdates]);

		_.each(response.chat, _.bind(this.addMessage, this));
		this.refreshScroller();
		this.hasInitializedUpdates = true;
	},
	addMessage: function(message) {
		this.addChat(message);
		return;
		if (this.options.cmo && message.approved == -1 && this.options.isModeratorPanel) {
			this.addUnmoderatedChat(message);
		} else {
			this.addChat(message);
		}
	},
	addChat: function(chatData) {
		// if(chatData.type != 'chat') return;

		if(this.chatCache.length > 200){
			var chatRm = this.chatCache.shift();
			chatRm.dom.remove();
		}

		var chat = this.renderChatBox(chatData),
			insertAfter, 
			chatIsReplaced = false;

		for(var i = 0; i< this.chatCache.length; i++){
			if(this.chatCache[i].id == chatData.id){
				this.chatCache[i].dom.replaceWith(chat);
				this.chatCache[i] = {sequence: chatData.sequence, id: chatData.id,  dom: chat, data: chatData };
				chatIsReplaced = true;
				break;
			}
		}

		if(!chatIsReplaced){ 

			if(this.chatCache.length > 0 && this.chatCache[this.chatCache.length - 1].sequence == chatData.sequence){
				var index = this.chatCache.length - 1 ;
				this.chatContent.append(chat);
			} else {
				var index = this.getInsertIndex( this.chatCache, chatData.sequence);
				if(index == 0){
					this.chatContent.prepend(chat);
				} else if(index == this.chatCache.length){
					this.chatContent.append(chat);
				} else {
					this.chatCache[index].dom.before(chat);
				} 
			}
			this.chatCache.splice(index, 0, {sequence: chatData.sequence, id: chatData.id,  dom: chat, data: chatData });
		}		
	},
	getInsertIndex: function(array, value) {
	    var low = 0, high = array.length;
	    while (low < high) {
	      var mid = (low + high) >> 1;

	      array[mid].sequence < value ? low = mid + 1 : high = mid;
	    }
	    return low;
 	},
 	renderChatBox: function(chatData){
 		chatData.color = chatData.color || ''; //.replace('{{author}}', chatData.isfrom);
 		if(!/http/.test(chatData.user_image)){
 			chatData.user_image = __CONFIG.assetUrl + chatData.user_image
 		}
 		if(chatData.ishost){
			var chat = $(_.template(this.hostTemplate, chatData));
		} else if(chatData.asmoderator){
			var chat = $(_.template(this.moderatorTemplate, chatData));
		} else {
			var chat = $(_.template(this.userTemplate, chatData));
		}
		if(chatData.approved == 0){
			chat.addClass('chat-decline')
		} else if(chatData.approved == -1){
			$(_.template(this.chatApprove, chatData)).appendTo(chat);
		}
		return chat;
 	},

	addUnmoderatedChat: function(html) {
		// this.moderatorContent.append(html);
	},
	onChatInputKeypress: function(event) {
		if (event.keyCode == 13) {
			event.preventDefault();
			// if(/goTo/.test(this.chatInput.val())){
			// 	var instance = this.chatInput.val().split(':');
			// 	this.changeInstance(instance[1]);
			// } else if ( !! this.chatInput.val()) {
			this.sendChat();
			// }
			return false;
		}
	},
	sendChat: function() {
		this.connection.post({body:this.chatInput.val(), color: this.currentColor, type: 'chat'});
		this.chatInput.val('');
	},
	onModerate: function(event) {
		var target = $(event.target),
			allow = target.hasClass('chat_message_approve') ? 1 : 0,
			id = target.data('id')

		target.text(target.text().replace(/e$/,'ing'));
		target.parents('.chat_message').css({opacity: 0.5});
		this.connection.approve(id, allow);

	},
	getRooms: function(){
		$.ajax({
			dataType: "json",
			url: '/services/chat/rooms?room=' + this.options.room + '&instance=' + this.options.instance,
			success: _.bind(this.onGetRoomsSuccess, this)
		});
	},
	onGetRoomsSuccess: function(response){
		this.chatContainer.hide();
		this.roomContainer.show();

		if(!!response && response.rooms){
			this.roomContent.empty();
			var roomUl = $('<ul class="rooms"></ul>').appendTo(this.roomContent);
			$('<li class="room-back">&laquo; Back to Chat</li>').bind('click', _.bind(this.onBackChatClick, this)).appendTo(roomUl);
			_.each(response.rooms, function(room){
				if(!!room.name){
					var roomLi = $(_.template(this.roomTemplate, room)).appendTo(roomUl);
					// console.log("RI:" + room.instance + " is " + room.port);
					if(this.options.instance == room.instance){
						roomLi.addClass('active');
					} else {
						roomLi.bind('click', _.bind(this.changeInstance, this, room.instance, room.port))
					}
				}
			}, this);
			this.roomContainer.nanoScroller();

		}
	},
	onBackChatClick: function(){
		this.chatContainer.show();
		this.roomContainer.hide();
	},
	changeInstance: function(instance, port ,event){

		this.chatContainer.show();
		this.roomContainer.hide();
		this.chatCache.length = 0;
		this.chatContent.empty();
		this.hasInitializedUpdates = false;
		$(this).trigger('colorMeReset');
		this.options.instance = instance;
		this.options.port = port;
		$.cookie('ci_' +this.options.room, this.options.instance, { expires: 30, path: '/', domain: '.constellation.tv' });
		this.connection.changeInstance(instance, port);
	},
	shareChatOnFacebook: function(id){
		var chatData = _.filter(this.chatCache, function(chat){
			return chat.id == id;
		});
		if(chatData.length >0){
			var message = chatData[0].data.body.length > 100 ? chatData[0].data.body.substring(0,100) + '...' : chatData[0].data.body; 
			var params =[];
			params.push('app_id=185162594831374');
			params.push('link=' + encodeURIComponent(this.options.shareOptions.urlLink));
			params.push('picture=' + encodeURIComponent('http://www.constellation.tv' + $('#theater-film-details .poster-wrap img').attr('src')));
			params.push('description=' + encodeURIComponent( chatData[0].data.isfrom + ' is chatting at the ' + this.options.shareOptions.shareTitle + ' theater.'));
			params.push('caption=' + encodeURIComponent(message));
			params.push('redirect_uri=' + encodeURIComponent(window.location.href));
			window.open('http://www.facebook.com/dialog/feed?' +params.join('&'),'_share_facebook','width=450,height=250');

		}		
	},
	shareChatOnTwitter: function(id){
		var chatData = _.filter(this.chatCache, function(chat){
			return chat.id == id;
		});
		if(chatData.length >0){
			var message = chatData[0].data.body.length > 100 ? chatData[0].data.body.substring(0,100) + '...' : chatData[0].data.body; 
			var params =[];
			params.push('text=' + encodeURIComponent(message + ' ' + this.options.shareOptions.twitterHash));
			window.open('https://twitter.com/intent/tweet?' +params.join('&'),'_share_twitter','width=450,height=300');
			// this.recordInvite('twitter', 1);			
		}
	},
	recordInvite: function(type, count){
		$.ajax({
			url: '/services/Invite/record',
			data: {
				film : 69,
				screening: '21jumpstlive',
				type: type,
				user_type: 'screening',
				count: count,
				source: 'theater'
			}
		});
	} 
}