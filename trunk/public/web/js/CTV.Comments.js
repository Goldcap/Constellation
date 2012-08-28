var CTV = CTV || {};

CTV.Comments = function(options) {
	this.options = $.extend({
		list: null,
		filmId: 0,
		screeningId: 0,		
		userId: 0,
		isLoggedIn: false,
		fbShareImage: 'https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png',
		fbShareCaption: window.document.title,
		tShareCaption: '',
		rpp: 5,
		showFilmTemplate: false,
		noCommentText: 'Be the first to post',
		noCommentTag: 'li',
		sort: 'recent'
	}, options);
	this.init();

}

CTV.Comments.prototype = {
	template: '<li> \
	<div class="comment-item">\
		<a href="/profile/{{authorid}}"><img src="{{avatar}}" width="50" height="50" class="comment-avatar comment-item-avatar"/></a>\
		<div class="comment-item-content">\
			<div class="comment-item-details">\
				<span class="comment-item-user">{{authorname}}</span>\
				<span class="comment-item-time">{{timestamp}}</span>\
			</div>\
			<div class="comment-item-comment">{{comment}}</div>\
			<p class="comment-item-tools"><a href="javascript:void(0)" class="comment-item-reply"></a><a href="" target="_blank" class="comment-item-share-fb"></a><a href="" target="_blank" class="comment-item-share-twitter"></a><a href="javascript:void(0)" class="comment-item-flag">Flag as inappropriate</a></p>\
		</div>\
		<div class="comment-item-vote">\
			<span class="comment-item-vote-icon"></span> <span class="comment-item-vote-count"></span>\
		</div>\
	</div>\
	<ul></ul>\
</li>',

	filmTemplate: '<div class="ctv-panel-happening-conversation-block clearfix">\
				<div class="conversation-poster">\
					<a href="/film/{{film}}"><img width="90" src="'+ __CONFIG.assetUrl + '/uploads/screeningResources/{{film}}/logo/small_poster{{film_logo}}"></a>\
				</div>\
				<div class="title"><a href="/film/{{film}}">{{film_name}}</a></div>\
				<ul class="comment-list"></ul>\
				\
		        <p class="clear"><a class="button_inset" href="/film/{{film}}"><span class="comment-more"></span><span>More Discussions</span></a></p>\
			</div>',

	commentBox: '<div class="comment-box clearfix">\
					<textarea placeholder="Add your voice to the conversation!" class="comment-box-field"></textarea>\
					<span class="button button_orange_medium uppercase">post</span>\
				</div>',

	urls: {
		list: '/services/conversation/init',
		vote: '/services/conversation/vote',
		flag: '/services/conversation/flag'
	},
	init: function() {
		this.domNode = this.options.list;
		this.commentBox = $(this.commentBox);
		this.currentPage = 1;

		this.getComments();
		this.attachEvents();
		if ( !! $('#comment-box').length) {
			this.CommentBox = new CTV.CommentBox({
				filmId: this.options.filmId,
				screeningId: this.options.screeningId,
				userId: this.options.userId
			}, '#comment-box');
			$(this.CommentBox).bind('addComment', _.bind(this.onAddComment, this));
		}
		this.ReplyBox = new CTV.CommentBox({
			isReply: true,
			filmId: this.options.filmId,
			screeningId: this.options.screeningId,
			userId: this.options.userId
		}, this.commentBox);
		$(this.ReplyBox).bind('addComment', _.bind(this.onAddComment, this));
		if ( !! $('#pagination').length) {
			this.pagination = new CTV.Pagination({}, '#pagination');
			$(this.pagination).bind('paginate', _.bind(this.onPaginate, this));
		}
	},
	attachEvents: function() {

		if (this.options.isLoggedIn) {
			$('.comment-item-vote-icon', this.domNode).live('click', _.bind(this.onVoteClick, this))
			$('.comment-item-reply', this.domNode).live('click', _.bind(this.onReplyClick, this))
			$('.comment-item-flag', this.domNode).live('click', _.bind(this.onFlagClick, this))
		} else {
			$('.comment-item-vote-icon', this.domNode).live('click', _.bind(this.showLogin, this))
			$('.comment-item-reply', this.domNode).live('click', _.bind(this.showLogin, this))
			$('.comment-item-flag', this.domNode).live('click', _.bind(this.showLogin, this))
		}
	},
	getComments: function() {
		$.ajax({
			type: 'GET',
			url: this.urls.list,
			data: {
				f: this.options.filmId,
				e: this.options.screeningId,
				p: this.currentPage,
				r: this.options.rpp,
				u: this.options.userId,
				s: this.options.sort
			},
			dataType: 'json',
			success: _.bind(this.showComments, this)
		});
	},
	showComments: function(response) {
		if (response == null) {
			return;
		}
		this.domNode.removeClass('comment-loading');
		if (response.meta.success) {
			this.ReplyBox.detach();
			this.domNode.empty();
			if (response.meta.totalresults > 0) {
				_.each(response.response.data, _.bind(this.addComment, this, this.domNode), this);
				response.meta.currentPage = this.currentPage;
				if (this.pagination) {
					this.pagination.render(response.meta);
				}
			} else {
				this.addNoComments();
				this.currentPage = 1;
			}
		} else {
			// console.warn('bummer!');
		}
	},
	addComment: function(container, data, prepend) {
		if (this.noComments) {
			this.noComments.remove();
			this.noComments = null;
		}
		
		if(!/http/.test(data.avatar)){
 			data.avatar = __CONFIG.assetUrl + data.avatar
 		}

		var comment = $(_.template(this.template, data));

		$('.comment-item-vote-icon', comment).data('data', data);

		$('.comment-item-vote-count', comment).html(data.favorite_count ? data.favorite_count : 0);
		if (container == this.domNode) {
			$('.comment-item-reply', comment).data('data', data);
		} else {
			$('.comment-item-reply', comment).remove();
		}
		$('.comment-item-flag', comment).data('data', data);

		$('.comment-item-share-fb', comment).attr('href', this.buildFacebookShareLink(data))
		$('.comment-item-share-twitter', comment).attr('href', this.buildTwitterShareLink(data))

		if (data.replies && !! data.replies.length) {
			var replyContainer = $('ul', comment);
			replyContainer.appendTo(comment)
			_.each(data.replies, _.bind(this.addComment, this, replyContainer), this);
		}

		if (this.options.showFilmTemplate && data.film) {
			var filmTemplate = $(_.template(this.filmTemplate, data));
			$(filmTemplate.find('ul')).append(comment);
			filmTemplate.appendTo(this.domNode);
		} else if (typeof prepend == 'boolean' && prepend) {
			comment.prependTo(container ? container : this.domNode);
		} else {
			comment.appendTo(container ? container : this.domNode);
		}
	},
	addNoComments: function() {
		this.noComments = $('<' + this.options.noCommentTag + ' class="comment-item-no-comment">' + this.options.noCommentText + '</' + this.options.noCommentTag + '>').appendTo(this.domNode);
	},
	showLogin: function(e) {
		// $("#login_destination").val();
		// $("#signup_destination").val();
		// login.showpopup();
		$(window).trigger('auth:login');
	},
	buildFacebookShareLink: function(data) {

		var string = 'http://www.facebook.com/dialog/feed';
		string += '?app_id=185162594831374'
		string += '&link=' + encodeURIComponent(window.location.href)
		// string += '&message=' + encodeURIComponent( data.authorname + ' posted: ' + data.comment);
		string += '&picture=' + encodeURIComponent(this.options.fbShareImage);
		string += '&caption=' + encodeURIComponent(this.options.fbShareCaption);
		string += '&description=' + encodeURIComponent(data.authorname + ' posted: ' + data.comment);
		string += '&redirect_uri=' + encodeURIComponent(window.location.href)
		return string;
	},
	buildTwitterShareLink: function(data) {
		return 'http://twitter.com/share?text=' + encodeURIComponent(this.options.tShareCaption + ' ' + data.authorname + ' posted "' + data.comment + '"') + '&url=' + encodeURIComponent(window.location.href)
	},
	onVoteClick: function(e) {
		var target = $(e.target);
		var isDisabled = target.data('isDisabled');
		if (!isDisabled) {
			target.data('isDisabled', true);
			target.addClass('comment-item-vote-submitted');
			var data = target.data('data'),
				html = target.next().html(),
				count = parseInt( !! html ? html : 0);

			target.next().html(++count);
			$.ajax({
				type: 'GET',
				url: this.urls.vote,
				data: {
					c: data.id
				},
				dataType: 'json'
			});

		}
	},
	onFlagClick: function(e) {
		var target = $(e.target);
		var isDisabled = target.data('isDisabled');
		if (!isDisabled) {
			target.data('isDisabled', true);

			$.ajax({
				type: 'GET',
				url: this.urls.flag,
				data: {
					c: target.data('data').id
				},
				dataType: 'json',
				success: _.bind(this.onFlagSuccess, this, target)
			});

		}
	},
	onFlagSuccess: function(target, response) {
		if (response.meta.success && response.meta.success != 'false') {
			var parent = target.parent().parent().find('.comment-item-comment').html('This post has been flagged as inappropriate!')
		}
	},
	onReplyClick: function(e) {
		var isCurrent = $(e.target).data('isCurrent');
		if (isCurrent) {
			this.commentBox.detach();
			$(e.target).data('isCurrent', false);
		} else {
			$('.comment-item-reply', this.domNode).data('isCurrent', false);
			$(e.target).data('isCurrent', true);
			var target = $(e.target).parents('.comment-item-content');
			var data = jQuery.data($(e.target), 'data');
			target.after(this.commentBox);
			this.ReplyBox.setId($(e.target).data('data').id);
		}
	},
	onPaginate: function(e, page) {
		this.currentPage = page;
		this.getComments();
	},
	onAddComment: function(e, response, container) {
		this.addComment(container ? container : this.domNode, response.response.data[0], container ? false : true);
	}
}


CTV.CommentBox = function(options, domNode) {
	this.options = $.extend({
		urls: {
			post: '/services/conversation/post',
			isReply: false
		},
		screeningId: 0,
		userId: 0,
		filmId: null
	}, options);

	this.domNode = $(domNode)
	this.init();
}
CTV.CommentBox.prototype = {
	init: function() {
		this.submitButton = $('.button', this.domNode);
		this.textarea = $('textarea', this.domNode);
		this.attachEvents();

		if (this.options.isReply) {
			this.idInput = $('<input type="hidden" />').appendTo(this.domNode);
		}
	},
	attachEvents: function() {
		this.submitButton.bind('click', _.bind(this.onSubmitClick, this));
	},
	onSubmitClick: function() {
		var value = this.textarea.val();
		var image = $('textarea', this.domNode);
		if ( !! value) {
			this.postComment();
		} else {

		}
	},
	postComment: function() {
		var data = {
			c: this.idInput ? this.idInput.val() : undefined,
			f: this.options.filmId,
			e: this.options.screeningId,
			u: this.options.userId,
			b: this.textarea.val()
		}
		this.textarea.val('')

		$.ajax({
			type: 'GET',
			url: this.options.urls.post,
			data: data,
			success: _.bind(this.onSuccess, this),
			dataType: 'json'
		});
	},
	onSuccess: function(response) {
		if (response.meta.success) {
			$(this).trigger('addComment', [response, this.options.isReply ? this.domNode.parents('.comment-item').next('ul') : null])

			if (this.options.isReply) {
				this.domNode.detach()
			}
		}
	},
	detach: function() {
		this.domNode.detach()
	},
	setId: function(id) {
		this.idInput.val(id)
	}
}


CTV.Pagination = function(options, domNode) {
	this.options = $.extend({
		list: null,
		urls: {
			post: '/services/conversation/post'
		}
	}, options);
	this.domNode = $(domNode);
	this.pagingContainer = $('ul', this.domNode);
	this.init();
}
CTV.Pagination.prototype = {
	templatePage: '<li><span class="button button-medium button-black">{{page}}</span></li>',
	init: function() {
		this.isRunning = false;
		this.attachEvents();
	},

	attachEvents: function() {
		$('.button-pagination', this.domNode).live('click', _.bind(this.onButtonClick, this))
	},
	render: function(meta) {

		this.currentPage = parseInt(meta.currentPage);
		// window.location.hash = 'p=' + this.currentPage;
		var totalPages = Math.ceil(meta.totalresults / meta.rpp);
		this.pagingContainer.empty();


		if (totalPages > 1) this.addPrevious();

		if (totalPages > 4) {
			var pageFloor, 
				pageCeil, 
				extendMin = false,
				extendMax = false;

			if (this.currentPage <= 3 && totalPages > 6) {
				pageFloor = 1;
				pageCeil = pageFloor + 4;
				extendMax = true;
			} else if (this.currentPage <= 3) {
				pageFloor = 1;
				pageCeil = pageFloor + 5;
			} else if ((this.currentPage - 3) < 0) {
				pageFloor = 1;
				pageCeil = pageFloor + 5;
			} else if ((this.currentPage - 2) >= 0 && (this.currentPage + 3) <= totalPages) {
				extendMin = true;
				extendMax = true;
				pageFloor = this.currentPage - 1;
				pageCeil = this.currentPage + 1;
			} else if ((this.currentPage - 2) >= 0 && (this.currentPage + 3) >= totalPages) {
				extendMin = true;
				pageCeil = totalPages;
				pageFloor = pageCeil - 4;
			} else {
				pageCeil = totalPages;
				pageFloor = pageCeil - 5;
			}

			if (extendMin) {
				this.addPage(1);
				this.addExtend();
			}
			for (var i = pageFloor; i <= pageCeil; i++) {
				this.addPage(i);
			}
			if (extendMax) {
				this.addExtend();
				this.addPage(totalPages);
			}

		} else {
			for (var i = 1; i <= totalPages; i++) {
				this.addPage(i);
			}
		}
		if (totalPages > 1) this.addNext(totalPages);
		this.isRunning = false;
	},
	addPage: function(index) {
		var data = {
			page: index
		}
		var page = $(_.template(this.templatePage, data));
		$('span', page).data('index', (index));
		if ((index) == this.currentPage) {
			$('span', page).addClass('active')
		}
		page.appendTo(this.pagingContainer);

	},
	addNext: function(totalPages) {
		var data = {
			page: '&raquo;'
		}
		var button = $(_.template(this.templatePage, data));
		$('span', button).data('index', (this.currentPage + 1));
		if (this.currentPage == totalPages) $('span', button).addClass('disabled');
		button.appendTo(this.pagingContainer);
	},
	addPrevious: function(totalPages) {
		var data = {
			page: '&laquo;'
		}
		var button = $(_.template(this.templatePage, data));
		$('span', button).data('index', (this.currentPage - 1));
		if (this.currentPage == 1) $('span', button).addClass('disabled');
		button.appendTo(this.pagingContainer);
	},
	addExtend: function() {
		$('<li class="extend">...</li>').appendTo(this.pagingContainer);
	},
	onButtonClick: function(e) {
		var button = $(e.target);
		if (!button.hasClass('current') && !button.hasClass('disabled') && !this.isRunning) {
			this.isRunning = true;
			$(this).trigger('paginate', button.data('index'))
		}
	}
};