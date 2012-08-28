var CTV = CTV || {};
CTV.ModeratorController = function(options){
	this.options = $.extend({
    	chatOptions: null,
    	searchNode: null
    }, options);
    this.init();

}

CTV.ModeratorController.prototype = {
	blockTemplate: '<div pair="public" to="public" class="chat_message clearfix chat_message_{{stat}}"><div class="right" style="margin-top: 8px; margin-right: 40px;"><a class="block_message chat_moderation_block" data-type="block" data-id="{{id}}"><img src="/images/Neu/16x16/actions/dialog-cancel.png" /></a><a class="block_message" data-type="warning" data-id="{{id}}"><img src="/images/Neu/16x16/actions/dialog-close.png" /></a><a class="block_message chat_moderation_unblock" data-type="unblock" data-id="{{id}}"><img src="/images/Neu/16x16/actions/dialog-ok.png" /></a></div><div class="chat_icon"><img src="{{image}}"/></div><span class="chat_text"><h5>{{email}}</h5><p>Status: {{stat}}</p></span></div>',
	userTemplate:  '<div class="chat_message" id="m{{id}}" cursor="{{id}}" type="{{type}}" instance="{{instance}}" from="{{isfrom}}" to="{{to}}" pair="{{pair}}" sequence="{{sequence}}"><span class="chat-message-delete" onclick="return deleteMessage(\'{{id}}\',\'{{room}}\')"></span><div class="chat_icon chat_icon_user_{{author}}"><a href="/profile/{{author}}" target="_blank"><img src="{{user_image}}"/></a></div><div class="chat_text"><h5><a href="/profile/{{author}}" target="_blank">{{isfrom}}</a></h5><p>{{body}}</p></div></div>',
	hostTemplate:  '<div class="chat_message chat_message_host" id="m{{id}}" cursor="{{id}}" type="{{type}}" instance="{{instance}}" from="{{isfrom}}" to="{{to}}" pair="{{pair}}" sequence="{{sequence}}"><span class="chat-message-delete" onclick="return deleteMessage(\'{{id}}\',\'{{room}}\')"></span><div class="chat_icon chat_icon_user_{{author}}"><a href="/profile/{{author}}" target="_blank"><img src="{{user_image}}"/></a></div><div class="chat_text"><h5><a href="/profile/{{author}}" target="_blank">{{isfrom}}</a><span class="chat_host"><img src="/images/alt1/chat_host.png" /></span></h5><p>{{body}}</p></div></div>',
	moderatorTemplate:  '<div class="chat_message" id="m{{id}}" cursor="{{id}}" type="{{type}}" instance="{{instance}}" from="{{isfrom}}" to="{{to}}" pair="{{pair}}" sequence="{{sequence}}"><span class="chat-message-delete" onclick="return deleteMessage(\'{{id}}\',\'{{room}}\')"></span><div class="chat_icon chat_icon_user_{{author}}"><a href="/profile/{{author}}" target="_blank"><img src="{{user_image}}"/></a></div><div class="chat_text"><h5><a href="/profile/{{author}}" target="_blank">{{isfrom}}</a><span class="chat_host"><img src="/images/alt1/chat_moderator.png" /></span></h5><p>{{body}}</p></div></div>',
		
	init: function(){
		this.state = null;
		// this.chatIsOpen = true;
		this.initSubClass();
		this.attachPoints();
		this.attachEvents();
		this.onWindowResize();
		
		deleteMessage = _.bind(this.deleteMessage, this);
	},
	attachPoints: function(){
		this.w = $(window);
		this.searchInput = $('.user-search',this.chat.options.moderatorNode);
		this.searchContainer = this.options.searchNode;
		this.searchContent = $('.content', this.searchContainer)
		// this.options.searchNode
	},
	attachEvents: function(){
		this.w.bind('resize', _.bind(this.onWindowResize, this));
		this.searchInput.bind('keypress', _.bind(this.onSearchInputKeypress, this));
		this.options.searchNode.delegate('.block_message', 'click', _.bind(this.onBlock, this));
	},
	initSubClass: function(){
		this.chat = new CTV.TheaterChat(this.options.chatOptions, $('#chat-container'));
		this.chat.userTemplate = this.userTemplate;
		this.chat.hostTemplate = this.hostTemplate;
		this.chat.moderatorTemplate = this.moderatorTemplate;
		
		$(this.chat).bind('qanda', _.bind(this.onQanda, this));
		$(this.chat.connection).bind('searchSuccess', _.bind(this.onSearchSuccess,this));
		
		this.qaModerator = new CTV.ModeratorQa(this.options.moderatorOptions, $('#qa-container'));

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

		this.chat.resize(height -37);
		this.qaModerator.resize(height)
	},
	onQanda: function(event, message){
		if(message.approved == -1){
			this.qaModerator.addUnapproved(message);
		} else if (message.approved == 1){
			this.qaModerator.addApproved(message);
		} else {
			this.qaModerator.removeUnapproved(message);
		}
	},
	onSearchInputKeypress: function(event) {
		if (event.keyCode == 13) {
			event.preventDefault();
			this.chat.connection.search(this.searchInput.val());
		}
	},
	onSearchSuccess: function(event, response){
		this.renderSearch(response);
	},
	renderSearch: function(response) {
		//_.each(response.chat, _.bind(this.addMessage, this));
		this.searchContent.empty()
		_.each(response.users,_.bind(this.addSearchUser,this));
	},
	addSearchUser: function(user) {
		var block = $(_.template(this.blockTemplate, user)).appendTo(this.searchContent);
		// this.searchContent.append(block);
		this.searchContainer.nanoScroller();
	},
	onBlock: function(event) {
		var target = $(event.target).parent(),
			id = target.data('id'),
			type = target.data('type')

		this.chat.connection.block(id,type);
		this.chat.connection.search(this.searchInput.val());
	},
	deleteMessage: function(id,room){
		$.ajax({url: "/services/chat/remove?id="+id+"&room="+room});
		$("#m"+id).remove();
	}
}

