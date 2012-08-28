
var CTV = CTV || {};

CTV.Vows = function(options){
	 this.options = $.extend({
      list: null,
      filmId: null,
      userId: 0,
      isLoggedIn: false,
      fbShareImage: 'https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png',
      fbShareCaption: window.document.title,
      tShareCaption: '',
      rpp: 5
    }, options);
    this.init();
}

CTV.Vows.prototype = { 
	template: '<li><div class="comment-item vow-comment-item"><a href="javascript:void(0)" class="vow-avatar-link"><img src="{{vow_asset_guid}}" width="35" height="35" class="comment-avatar comment-item-avatar vow-avatar"/></a>\
    <div class="comment-item-content">\
      <div class="comment-item-details">\
				<span class="comment-item-user vow-content">{{vow_username}}</span>\
			</div>\
			<div class="comment-item-comment vow-content">{{vow_description}}</div>\
			<p class="comment-item-tools"><a href="" target="_blank" class="comment-item-share-fb"></a><a href="" target="_blank" class="comment-item-share-twitter"></a></p>\
    </div>\
  </li>',

  templateLast: '<li> \
  	<div class="comment-item">\
  		<img src="{{avatar}}" width="50" height="50" class="comment-avatar comment-item-avatar"/>\
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

	urls: {
		list: '/services/Vow/init'
	},
  
	init: function(){
		this.domNode = this.options.list;
		var pmatch =  !!window.location.hash && window.location.hash.match(/vp=(\d+)/) ? window.location.hash.match(/vp=(\d+)/,'') : 1;
		if (pmatch != 1) {
			this.currentPage = pmatch[1];
		} else {
			this.currentPage = 1
		}
		this.getComments();
		this.attachEvents();
		if(!!$('#vow-pagination').length){
			this.pagination = new CTV.Pagination({}, '#vow-pagination');
			$(this.pagination).bind('paginate', _.bind(this.onPaginate, this));
		}
	},
	attachEvents: function(){

		$('.vow-avatar-link',this.domNode).live('click', _.bind(this.showFullVow, this));
		$('.vow-content',this.domNode).live('click', _.bind(this.showFullVow, this));
		
	}, 
	getComments: function(){
		$.ajax({
		  type: 'GET',
		  url: this.urls.list,
		  data: { vp: this.currentPage },
		  dataType: 'json',
		  success: _.bind(this.showVows, this)
		});
	},
	showVows: function(response){
		if(response == null) {
			return;
		}
		if((response.data != null) && (response.data.length > 0)){
			this.domNode.empty();
			if(response.meta.totalresults > 0){
				_.each(response.data, _.bind(this.addComment, this, this.domNode), this);
				response.meta.currentPage = this.currentPage;
				if(this.pagination){
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
	addComment: function(container, data, prepend){
		
    if(this.noComments){
			this.noComments.remove();
			this.noComments = null;
		}
 		var comment = $(_.template(this.template, data));
    /*
		$('.comment-item-vote-icon', comment).data('data', data);
		
		$('.comment-item-vote-count', comment).html(data.favorite_count ? '+' + data.favorite_count : '');
		if(container == this.domNode){
			$('.comment-item-reply', comment).data('data', data);
		} else {
			$('.comment-item-reply', comment).remove();
		}
		$('.comment-item-flag', comment).data('data', data);
    */
    
		$('.vow-avatar', comment).data('data', data);
		$('.vow-content', comment).data('data', data);
    $('.comment-item-share-fb', comment).attr('href', this.buildFacebookShareLink(data))
		$('.comment-item-share-twitter', comment).attr('href', this.buildTwitterShareLink(data))
    
		if(typeof prepend == 'boolean' && prepend){
			comment.prependTo(container ? container: this.domNode);
		} else {
      console.log("Append!");
			comment.appendTo(container ? container: this.domNode);
		}
	},
  
	addNoComments: function(){
		this.noComments = $('<li class="comment-item-no-comment">Be the first to post</li>').appendTo(this.domNode);
	},
  
	showFullVow: function(e){
    var target = $(e.target);
    $(".large_vow").attr("src",target.data('data').vow_asset_medium);
    $(".vow_description").html(target.data('data').vow_full_description);
    setTop("#main-vow-popup");
    $("#main-vow-popup").show();
    modal.modalIn( hideFullVow );
  },
     
	buildFacebookShareLink: function(data){
		var string  = 'http://www.facebook.com/dialog/feed';
			string += '?app_id=185162594831374'
			string += '&link='+encodeURIComponent(window.location.href)
			// string += '&message=' + encodeURIComponent( data.authorname + ' posted: ' + data.comment);
			string += '&picture='+ encodeURIComponent(this.options.fbShareImage);
			string += '&caption='+encodeURIComponent(this.options.fbShareCaption);
			string += '&description='+encodeURIComponent( data.vow_username + ' posted: ' + data.vow_description);
			string += '&redirect_uri='+ encodeURIComponent(window.location.href)
		return string;	
	},
	buildTwitterShareLink: function(data){
		return'http://twitter.com/share?text='+ encodeURIComponent( this.options.tShareCaption + ' ' + data.vow_username +' posted "' + data.vow_description + '"') + '&url=' + encodeURIComponent(window.location.href)	
	},
	onPaginate: function(e, page){
		this.currentPage = page;
		this.getComments();
	}
}

CTV.Pagination = function(options, domNode){
	this.options = $.extend({
      list: null,
      urls: {
      	get:'/services/Vow/init'
      } 
    }, options);
    this.domNode = $(domNode);
    this.pagingContainer = $('ul', this.domNode);
    this.init();
}
CTV.Pagination.prototype = {
	templatePage : '<li><span class="button-pagination">{{page}}</span></li>',
	init: function(){
		this.isRunning = false;
		this.attachEvents();
	},

	attachEvents: function(){
		$('.button-pagination', this.domNode).live('click', _.bind(this.onButtonClick, this))
	},
	render: function(meta){

		this.currentPage = parseInt(meta.currentPage);
		window.location.hash = 'vp=' + this.currentPage;
		var totalPages = Math.ceil(meta.totalresults / meta.rpp);
		this.pagingContainer.empty();


		if(totalPages > 1) this.addPrevious();

		if(totalPages >  4){
			var pageFloor,
				pageCeil,
				inRange = false;
			if(this.currentPage  < 2 ){
				pageFloor = 1;
				pageCeil = pageFloor + 5;
			} else if( (this.currentPage - 3) < 0  ){
				pageFloor = 1;
				pageCeil = pageFloor + 5;
			} else if((this.currentPage - 2) >= 0  && (this.currentPage + 2) <= totalPages){
				inRange = true;
				pageFloor = this.currentPage - 1;
				pageCeil = this.currentPage + 1;
			} else {
				console.log((this.currentPage - 1) >= 0  , (this.currentPage + 2) <= totalPages, this.currentPage + 2 , totalPages)
				pageCeil = totalPages;
				pageFloor = pageCeil - 5;
			}

			if(inRange){
				this.addPage(1);
				this.addExtend();
			}
			for(var i  = pageFloor; i <= pageCeil; i++){
				this.addPage(i);
			}	
			if(inRange){
				this.addExtend();
				this.addPage(totalPages);
			}

		} else {
			for(var i  = 1; i <= totalPages; i++){
				this.addPage(i);
			}			
		}
		if(totalPages > 1) this.addNext(totalPages );
		this.isRunning = false;
	},
	addPage: function(index){
		var data = {page : index }
		var page = $(_.template(this.templatePage, data));
		$('span', page).data('index', (index));
		if((index) == this.currentPage){
			$('span', page).addClass('current')
		}
		page.appendTo(this.pagingContainer);

	},
	addNext: function(totalPages){
		var data = {page : '&raquo;'}
		var button = $(_.template(this.templatePage, data));
		$('span', button).data('index', (this.currentPage + 1));
		if(this.currentPage == totalPages) $('span', button).addClass('disabled');
		button.appendTo(this.pagingContainer);
	},
	addPrevious: function(totalPages){
		var data = {page : '&laquo;'}
		var button = $(_.template(this.templatePage, data));
		$('span', button).data('index', (this.currentPage - 1));
		if(this.currentPage == 1) $('span', button).addClass('disabled');
		button.appendTo(this.pagingContainer);
	},
	addExtend: function(){
		$('<li class="extend">...</li>').appendTo(this.pagingContainer);
	},
	onButtonClick: function(e){
		var button = $(e.target);
		if(!button.hasClass('current') && !button.hasClass('disabled') && !this.isRunning){
			this.isRunning = true;
			$(this).trigger('paginate',button.data('index'))
		}
	}
};

function hideFullVow(){
  $("#main-vow-popup").hide();
}
 