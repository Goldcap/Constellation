(function(callback) {

	window.jQuery || document.write('<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"><\/script>');

	initCTV = function() {
		if (window.jQuery) {
			var t = jQuery('#ctv-embed');
			if(t){
			new CTV.Embed(t);
			}
		} else {
			window.setTimeout(initCTV, 200);
		}
	}

	var CTV;
	CTV = {} || CTV;
	CTV.config = {
		assetUrl: 'https://s3.amazonaws.com/cdn.constellation.tv/prod',
		baseUrl: 'https://www.constellation.tv',
		partnerUrl: 'http://www.constellation.tv',
		theaterUrl: 'http://www.constellation.tv',
		serviceUrl: 'http://www.constellation.tv'
	}
	CTV.ViewTemplate = '<div class="ctv-wrap"><div class="ctv-head"><img class="ctv-partner-logo" height="42"/><a href="http://www.constellation.tv" target="_blank" class="ctv-powered-by"></a><img height="192" width="600" class="ctv-poster" /></div><div class="ctv-menu"></div><div class="ctv-content"><div class="ctv-content-panel ctv-content-panel-featured clearfix"></div><div class="ctv-content-panel ctv-content-panel-daily clearfix"></div><div class="ctv-content-panel ctv-content-panel-details clearfix"></div><div class="ctv-content-panel ctv-content-panel-how clearfix"></div></div><div class="ctv-related clearfix"><p class="ctv-related-head"></p><div class="ctv-related-container clearfix"></div><span class="ctv-related-next"></span><span class="ctv-related-prev"></span></div></div>';
	CTV.FeaturedTemplate = '<div class="ctv-showtime-se clearfix"><div class="ctv-showtime-se-image"><img width="88" height="88" class="ctv-showtime-profile-avatar" src=""></div><div class="ctv-showtime-se-details"><p class="title"></p><p class="time"></p></div></div>';
	CTV.RegularTemplate = '<div class="ctv-showtime-d clearfix"><div class="ctv-showtime-d-time time"></div><div class="ctv-showtime-d-details"><p class="ctv-attending">Be the first the attend.</p></div></div>';
	CTV.WatchNowTemplate = '<p class="ctv-watch-now-wrap">Antisocial? <a href="{{theaterUrl}}/boxoffice/screening/none?film={{id}}&dohbr=true" class="ctv-button ctv-button-blue" target="_ctv">Watch it Now</a></p>';
	CTV.MenuTemplate = '<ul class="ctv-menu-list clearfix"><li class="active"><span class="ctv-icon-hosted"></span>Special Events</li><li><span class="ctv-icon-daily"></span>Watch Together</li><li><span class="ctv-icon-details"></span>Film Details</span></li><li><span class="ctv-icon-hiw"></span>How It Works</span></li></ul>';
	CTV.DetailTemplate = '<div class="ctv-poster-wrap"><img class="ctv-poster"/></div><div class="ctv-details-wrap"><p class="ctv-title"></p><p class="ctv-runtime"></p><p class="ctv-genre"></p><p class="ctv-directors"></p><div class="ctv-synopsis"></div><span class="ctv-button ctv-button-blue ctv-button-trailer">Watch Trailer</span></div>';
	CTV.HiwTemplate = '<div class="hit-block-wrap clearfix"><div class="hit-block hit-block-1"><span>1</span><h2>Browse Featured Films</h2><p>Watch trailers, read synopses, and check out "Special Events" showtimes.  These are showtimes where a VIP, like the movie’s director, is live in the theater during and after the movie for a virtual Q+A.</p></div><div class="hit-block hit-block-2"><span>2</span><h2>Pick a showtime</h2><p>Click on a showtime in the “Special Events” or “Watch Together” tab to purchase your ticket.  Or, click on "Watch Now" to buy the movie on demand.</p></div><div class="hit-block hit-block-3"><span>3</span><h2>Get a ticket</h2><p>Login via Facebook, or Twitter or create an account with an email address.  Purchase with any major credit card or Paypal.  We’ll email you a ticket and a reminder 6 hours before showtime.</p></div><div class="hit-block hit-block-4"><span>4</span><h2>Enjoy the show</h2><p>Once in the theater, chat with other attendees and the VIP host during and after the film!</p></div><span class="hit-sp hit-sp1"></span><span class="hit-sp hit-sp2"></span><span class="hit-sp hit-sp3"></span><span class="hit-sp hit-sp4"></span><span class="hit-sp hit-sp5"></span><span class="hit-sp hit-sp6"></span><span class="hit-sp hit-sp7"></span><span class="hit-sp hit-sp8"></span><span class="hit-sp hit-sp9"></span><p class="hit-block hit-block-6">Please email us with any questions or feedback at <a href="mailto:support@constellation.tv">support@constellation.tv</a></p></div>';
	CTV.RelatedTemplate = '<div class="ctv-related-film"><div class="ctv-related-poster-wrap"><img class="ctv-related-poster"/></div><div class="ctv-related-details"><h2 class="ctv-related-title"></h2><p class="ctv-related-showtime"></p></div></div>'


	CTV.Embed = function(target) {
		this.isFirstInit = true;
		this.currentIndex = 0;
		this.relatedOffset = 0;
		this.embedContainer = jQuery(target);
		this.filmId = this.embedContainer.data('filmId');
		this.parnerHandle = this.embedContainer.data('partner');
		this.isFacebook = this.embedContainer.data('facebook') || false;
		// this.isIFrame = top != self;
		if(this.isIFrame && !this.isFacebook) this.iFrame  = jQuery('#ctv-iframe',  parent.window.document);
		if(this.filmId && this.parnerHandle && (!this.isIFrame || (this.isIFrame  && this.iFrame))){
			this.showRelated = typeof this.embedContainer.data('related') == 'boolean' ? this.embedContainer.data('related') : true;
			this.init();		
		}

	}
	CTV.Embed.prototype = {
		init: function() {
			this.appendAssets();
			this.getFilmData();
		},
		getFilmData: function() {
			$.ajax({
				url: CTV.config.serviceUrl + '/services/Partner?film_id=' + this.filmId + '&partner=' + this.parnerHandle,
				type: "GET",
				cache: false,
				dataType: "jsonp",
				success: jQuery.proxy(this.render, this)
			});
		},
		appendAssets: function() {
			if (!window.swfobject) {
				jQuery('<script src="https://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>').appendTo(jQuery('body'));
			}
			if (!window.jwplayer) {
				jQuery('<script src="http://www.constellation.tv/flash/mediaplayer-5.7-licensed/jwplayer.js?v=44"></script>').appendTo(jQuery('body'));
			}
			jQuery('<link rel="stylesheet" href="' + CTV.config.assetUrl + '/css/ctv-embed.css" />').appendTo(jQuery('head')); 
			if(this.isIFrame){
				jQuery('<link rel="stylesheet" href="' + CTV.config.assetUrl + '/css/ctv-embed.css" />').appendTo(parent.window.document.head);			
			}
		},
		attachPoints: function() {
			this.headNode = jQuery('.ctv-head', this.template);
			this.navNode = jQuery('.ctv-menu', this.template);
			this.contentNode = jQuery('.ctv-content', this.template);
			this.relatedNode = jQuery('.ctv-related', this.template);
			this.relatedContainerNode = jQuery('.ctv-related-container', this.template);
			this.panelsNode = jQuery('.ctv-content-panel', this.contentNode);
			this.featuredPanel = jQuery('.ctv-content-panel-featured', this.contentNode);
			this.dailyPanel = jQuery('.ctv-content-panel-daily', this.contentNode);
			this.detailsPanel = jQuery('.ctv-content-panel-details', this.contentNode);
			this.hiwPanel = jQuery('.ctv-content-panel-how', this.contentNode);
			this.splashImage = jQuery('.ctv-poster', this.headNode)
			this.nextButton = jQuery('.ctv-related-next', this.relatedNode);
			this.prevButton = jQuery('.ctv-related-prev', this.relatedNode);
		},
		attachEvents: function() {
			this.nextButton.bind('click', jQuery.proxy(this.onRelatedNext, this));
			this.prevButton.bind('click', jQuery.proxy(this.onRelatedPrev, this));
			this.splashImage.bind("load", function() {
				$(this).fadeIn(250);
			});
		},
		render: function(response) {
			if (!response.meta.success) return;
			this.options = response.response;
			this.relatedOffset = 0;
			if (this.isFirstInit) {
				this.template = jQuery(CTV.ViewTemplate).appendTo(this.embedContainer);
				this.attachPoints();
				this.attachEvents();
				this.renderDialog();
				this.renderHeader();
				this.renderNav();
				this.renderHiw();
				this.renderDetails();
				this.isFirstInit = false;

				if(this.options.partner.partnerShortName == 'twitch'){
					CTV.config.theaterUrl = 'http://'+ this.options.partner.partnerShortName + '.constellation.tv';
				}
				jQuery('.ctv-related-head', this.relatedNode).html( 'Featured ' + (this.options.partner.partnerName ? 'on ' +this.options.partner.partnerName : 'Films'));

			}
			this.renderSplash();
			this.renderFeaturedShowtimes();
			this.renderDailyShowtimes();
			this.renderRelatedFilms();
			this.updateDetails();
			this.toggleTabs();
			this.contentNode.stop().animate({
				height: jQuery(this.panelsNode[this.currentIndex]).height() + 20
			}, 250);
			if(this.isIFrame && !this.isFacebook) this.resizeParent();
			// console.log(window)
		},
		renderDialog: function() {
			this.showtimeDialog = new CTV.ShowtimeDetail();
		},
		renderHeader: function() {
			jQuery('.ctv-partner-logo', this.headNode).attr('src', this.options.partner.partnerLogoUrl);
			if(this.options.partner.partnerShortName == 'www'){
				jQuery('.ctv-powered-by', this.headNode).hide();
				jQuery('.ctv-partner-logo', this.headNode).attr('src', this.options.partner.partnerLogoUrl).attr('height', 64);
			} else {
				jQuery('.ctv-partner-logo', this.headNode).attr('src', this.options.partner.partnerLogoUrl);
				
			}
		},
		renderSplash: function() {
			this.splashImage.hide().attr('src', this.options.film.splashUrl);
		},
		renderNav: function() {
			this.nav = new CTV.Nav();
			this.nav.domNode.appendTo(this.navNode);
			jQuery(this.nav).bind('tabClick', jQuery.proxy(this.onTabClick, this));
		},
		renderFeaturedShowtimes: function() {
			this.featuredPanel.empty();
			if (this.options.featuredShowtimes) {
				jQuery.each(this.options.featuredShowtimes, jQuery.proxy(this.renderFeaturedShowtime, this));
			} else {
				this.featuredPanel.html('<p class="ctv-no-showtime">There are currently no scheduled special events.</p>');
			}
		},
		renderFeaturedShowtime: function(index, data) {
			var showtime = new CTV.FeaturedShowtime(data);
			jQuery(showtime).bind('showtimeClick', jQuery.proxy(this.onShowtimeClick, this));
			this.featuredPanel.append(showtime.domNode);
		},
		renderDailyShowtimes: function() {
			this.dailyPanel.empty();
			if (this.options.dailyShowtimes) {
				jQuery.each(this.options.dailyShowtimes, jQuery.proxy(this.renderDailyShowtime, this));
			} else {
				this.dailyPanel.html('<p class="ctv-no-showtime">There are currently no scheduled showtimes.</p>');
			}
			if (this.options.film.allowHostByRequest) {
				jQuery(CTV.template(CTV.WatchNowTemplate, {theaterUrl: CTV.config.theaterUrl, id: this.options.film.id})).appendTo(this.dailyPanel);
			}

		},
		renderDailyShowtime: function(index, data) {
			var showtime = new CTV.DailyShowtime(data);
			jQuery(showtime).bind('showtimeClick', jQuery.proxy(this.onShowtimeClick, this));
			this.dailyPanel.append(showtime.domNode);
		},
		renderDetails: function() {
			this.details = new CTV.Details();
			this.detailsPanel.empty().html(this.details.domNode);
		},
		updateDetails: function() {
			this.details.update(this.options.film)
		},
		renderHiw: function() {
			this.hiwPanel.html(CTV.HiwTemplate);
		},
		renderRelatedFilms: function() {
			if (this.showRelated && this.options.featuredFilms.length) {
				this.relatedContainerNode.hide().empty();
				jQuery.each(this.options.featuredFilms, jQuery.proxy(this.renderRelatedFilm, this));
				this.relatedNode.show();
				this.updateRelatedPagination();
				this.relatedContainerNode.fadeIn(400);
			} else {
				this.relatedNode.hide();
			}
		},
		renderRelatedFilm: function(index, data) {
			if (this.relatedOffset <= index && this.relatedOffset + 3 > index) {
				var film = new CTV.RelatedFilm(data);
				jQuery(film).bind('filmClick', jQuery.proxy(this.onFilmClick, this));
				this.relatedContainerNode.append(film.domNode);
			}
		},
		updateRelatedPagination: function() {
			if (this.relatedOffset != 0) {
				this.prevButton.show();
			} else {
				this.prevButton.hide();
			}
			if (this.relatedOffset + 3 < this.options.featuredFilms.length) {
				this.nextButton.show();
			} else {
				this.nextButton.hide();
			}
		},
		toggleTabs: function() {
			if (this.options.featuredShowtimes) {
				this.nav.show(0);
			} else if (this.options.dailyShowtimes) {
				this.nav.show(1);
			} else {
				this.nav.show(2);
			}
		},
		resizeParent: function(){
			this.iFrame.width(this.template.width() + 20);	
			this.iFrame.stop().animate({
				height:
				jQuery(this.panelsNode[this.currentIndex]).height()
				+ this.headNode.height()
				+ this.navNode.height()
				+ this.relatedNode.height()
				+ 80
			},200);	
		},
		onTabClick: function(event, index) {
			if (this.currentIndex != index) {
				this.currentIndex = index;
				this.panelsNode.hide();
				jQuery(this.panelsNode[index]).fadeIn(250);
				this.contentNode.stop().animate({
					height: jQuery(this.panelsNode[this.currentIndex]).height() + 20
				}, 250);
				if(this.isIFrame && !this.isFacebook) this.resizeParent();

			}
		},
		onShowtimeClick: function(event, data) {
			this.showtimeDialog.open(data);
		},
		onFilmClick: function(event, data) {
			this.filmId = data.filmId;
			this.getFilmData();
		},
		onRelatedNext: function() {
			this.relatedOffset = this.relatedOffset + 3;
			this.renderRelatedFilms();
		},
		onRelatedPrev: function() {
			this.relatedOffset = this.relatedOffset - 3;
			this.renderRelatedFilms();
		}
	}
	CTV.FeaturedShowtime = function(options) {
		this.options = options;
		this.init();
	}
	CTV.FeaturedShowtime.prototype = {
		init: function() {
			this.render();
			this.attachEvents();
		},
		attachEvents: function() {
			this.domNode.bind('click', jQuery.proxy(this.onDomNodeClick, this))
		},
		render: function() {
			this.domNode = jQuery(CTV.FeaturedTemplate);
			jQuery('.title', this.domNode).html(this.options.screening_name ? this.options.screening_name : this.options.screening_user_full_name + ' hosts ' + this.options.screening_film_name);
			jQuery('.time', this.domNode).html(this.options.date);
			// jQuery('.attending', this.domNode).html(this.options.screening_audience_count + ' attending');
			jQuery('.ctv-showtime-profile-avatar', this.domNode).attr('src', CTV.config.baseUrl + '/uploads/hosts/'+ this.options.screening_user_id +'/icon_large_' + this.options.screening_user_photo_url);
		},
		onDomNodeClick: function() {
			jQuery(this).trigger('showtimeClick', this.options);
		}
	}
	CTV.DailyShowtime = function(options) {
		this.options = options;
		this.init();
	}
	CTV.DailyShowtime.prototype = {
		init: function() {
			this.render();
			this.attachEvents();
		},
		attachEvents: function(event) {
			this.domNode.bind('click', jQuery.proxy(this.onDomNodeClick, this))
		},
		render: function() {
			this.domNode = jQuery(CTV.RegularTemplate);
			jQuery('.time', this.domNode).html(this.options.date);
			jQuery('.attending', this.domNode).html(this.options.screening_audience_count + ' attending');
		},
		onDomNodeClick: function() {
			jQuery(this).trigger('showtimeClick', this.options);
		}
	}

	CTV.RelatedFilm = function(options) {
		this.options = options;
		this.init();
	}

	CTV.RelatedFilm.prototype = {
		init: function() {
			this.render();
			this.attachEvents();
		},
		attachEvents: function(event) {
			this.domNode.bind('click', jQuery.proxy(this.onDomNodeClick, this))
		},
		render: function() {
			this.domNode = jQuery(CTV.RelatedTemplate);
			jQuery('.ctv-related-poster', this.domNode).attr('src', CTV.config.baseUrl + '/uploads/screeningResources/' + this.options.filmId + '/logo/small_poster' + this.options.posterUrl);
			jQuery('.ctv-related-title', this.domNode).html(this.options.title);
			if (this.options.screenings) {
				jQuery('.ctv-related-showtime', this.domNode).html(this.options.screening_times.length ? this.options.screenings[0].date : '');
			}
		},
		onDomNodeClick: function() {
			jQuery(this).trigger('filmClick', this.options);
		}
	}

	CTV.Nav = function() {
		this.init();
	}
	CTV.Nav.prototype = {
		init: function() {
			this.render();
			this.attachPoints();
			this.attachEvents();
		},
		render: function() {
			this.domNode = jQuery(CTV.MenuTemplate);
		},
		attachPoints: function() {
			this.tabs = jQuery('li', this.domNode);
		},
		attachEvents: function() {
			var that = this;
			jQuery.each(this.tabs, function(index, element) {
				jQuery(element).bind('click', {
					index: index,
					element: jQuery(element)
				}, jQuery.proxy(that.onTabClick, that))
			});
		},
		show: function(index) {
			this.tabs.eq(index).trigger('click')
		},
		onTabClick: function(event) {
			this.tabs.removeClass('active');
			event.data.element.addClass('active')
			jQuery(this).trigger('tabClick', [event.data.index]);
		}
	}

	CTV.Details = function() {
		this.init();
	}
	CTV.Details.prototype = {
		init: function() {
			// this.isIFrame = top != self;
			this.render();
			this.dialog = new CTV.Dialog();
			this.attachEvents();
		},
		render: function() {
			this.domNode = jQuery(CTV.DetailTemplate);
		},
		update: function(options) {
			this.options = options;

			jQuery('.ctv-title', this.domNode).html(this.options.title);
			jQuery('.ctv-poster', this.domNode).attr('src', this.options.posterUrl);
			jQuery('.ctv-synopsis', this.domNode).html(this.options.synopsis);
			if (!this.options.runtime) {
				jQuery('.ctv-runtime', this.domNode).hide();
			} else {
				jQuery('.ctv-runtime', this.domNode).html('<strong>Runtime:</strong> ' + this.options.runtime);
			}
			if (!this.options.genre) {
				jQuery('.ctv-genre', this.domNode).hide();
			} else {
				jQuery('.ctv-genre', this.domNode).html('<strong>Genres:</strong> ' + this.options.genre.join(', '));
			}
			if (!this.options.directors) {
				jQuery('.ctv-directors', this.domNode).hide();
			} else {
				var directors = [];
				jQuery.each(this.options.directors, function(index, director) {
					directors.push(director.split('|')[1])
				});
				jQuery('.ctv-directors', this.domNode).html('<strong>Directors:</strong> ' + directors.join(', '));
			}

			if (/trailerFile\/\?/.test(this.options.streamUrl)) {
				jQuery('.ctv-button-trailer', this.domNode).hide();
			} else {
				jQuery('.ctv-button-trailer', this.domNode).show();
			}
		},
		attachEvents: function() {
			jQuery('.ctv-button-trailer', this.domNode).bind('click', jQuery.proxy(this.onTrailerClick, this));
		},
		onTrailerClick: function() {
			this.dialog.open({
				body: '<div class="ctv-trailer-container"><div id="ctv-trailer-placeholder"></div></div>',
				klass: 'ctv-dialog-trailer'
			})
			var flashvars = {
				file: this.options.streamUrl.replace('rtmp://cp113558.edgefcs.net/ondemand/', '').replace('?', '%3F'),
				'image': null,
				streamer: 'rtmp://cp113558.edgefcs.net/ondemand' + '%3F' + this.options.streamUrl.split('?')[1],
				'skin': CTV.config.baseUrl + '/flash/glow/glow.zip',
				'autostart': true,
				height: 300
			};

			var params = {
				allowFullScreen: 'true',
				allowScriptAccess: 'always',
				wmode: 'opaque',
				bgcolor: "#000000",
				height: 300
			};
			var attributes = {
				id: 'ctv-trailer',
				name: 'ctv-trailer'
			};
			if(this.isIFrame){
				var temp = $('<div id="ctv-trailer-placeholder"></div>').appendTo('body')
				swfobject.embedSWF(CTV.config.baseUrl + '/flash/mediaplayer-5.7-licensed/player.swf', 'ctv-trailer-placeholder', '100%', '100%', '9.0.0', CTV.config.baseUrl + '/flash/expressInstall.swf', flashvars, params, attributes, jQuery.proxy(this.onLoaded, this));
				jQuery( '#ctv-trailer-placeholder',this.dialog.domNode).replaceWith(jQuery('#ctv-trailer'));
			} else {
				swfobject.embedSWF(CTV.config.baseUrl + '/flash/mediaplayer-5.7-licensed/player.swf', 'ctv-trailer-placeholder', '100%', '100%', '9.0.0', CTV.config.baseUrl + '/flash/expressInstall.swf', flashvars, params, attributes, jQuery.proxy(this.onLoaded, this));
			}
		},
		onLoaded: function(e) {
			this.play(jwplayer('ctv-trailer'));
		},
		play: function() {

		}
	}
	CTV.Dialog = function(options) {
		this.options = jQuery.extend({

		}, options);
		this.init();

	}

	CTV.Dialog.prototype = {
		template: '<div class="ctv-dialog"></div>',
		init: function() {
			// this.isIFrame = top != self;
			this.domNode = jQuery(this.template).appendTo(this.isIFrame? $( parent.window.document.body) : jQuery('body'));
			this.overlay = jQuery('<div class="ctv-overlay"></div>').appendTo(this.isIFrame? $( parent.window.document.body) :jQuery('body'));
			this.overlay.bind('click', jQuery.proxy(this.close, this))
		},
		open: function(options) {
			this.setBody(options);
			this.domNode.show();
			this.overlay.fadeIn();
		},
		setBody: function(options) {
			this.domNode.empty();
			this.domNode.attr('class', '').addClass('ctv-dialog');
			if (options.klass) {
				this.domNode.addClass(options.klass)
			}
			if (options.title) {
				$('<h4>' + options.title + '</h4>').appendTo(this.domNode);
			}
			if (options.body) {
				var content = $('<div class="dialog-content"></div>').appendTo(this.domNode);
				if (typeof options.body == 'string') {
					content.html(options.body)
				} else {
					$(options.body).appendTo(content)
				}
			}
			if (options.buttons) {
				var buttonWrap = jQuery('<div class="dialog-buttons"></div>').appendTo(this.domNode);
				_.each(options.buttons, jQuery.proxy(this.appendButton, this, buttonWrap));
			}
		},
		appendButton: function(buttonWrap, button) {
			jQuery('<span class="button button_blue button-medium">' + button.text + '</span>').bind('click', _.bind(function() {
				if (button.callback) button.callback();
				this.close();
			}, this)).appendTo(buttonWrap)
		},
		close: function() {
			this.domNode.hide().empty();
			this.overlay.hide();
		}
	}


	CTV.ShowtimeDetail = function(options) {
		this.options = $.extend({
			text: 'You\'ll be buying a ticket to an online screening where you can chat with your fellow attendees while you watch.'
		}, options);
		this.init();
	}

	CTV.ShowtimeDetail.prototype = {
		template: '<div class="ctv-dialog-showtime-detail"><div class="ctv-dialog-showtime-screening">{{details}}<div class="ctv-dialog-showtime-text">{{text}}</div><div class="ctv-dialog-showtime-attendees"><p class="uppercase">Attendees</p><ul class="clearfix">{{attendees}}</ul></p></div><div class="ctv-dialog-showtime-buttonwrap">{{button}}</div></div>',
		templateTime: '<div class="date">{{date}}</div>',
		templateHost: '<div class="hosted clearfix"><img src="{{avatar}}" width="48" height="48"  /><p class="host">Hosted by {{screening_user_full_name}}</p><p class="date">{{date}}</p></div>',
		init: function() {
			this.dialog = new CTV.Dialog();
		},

		open: function(screeningData) {
			this.getAttendees(screeningData);
		},
		getDialogOptions: function(attendees, screeningData) {
			var templateData = {}
			templateData.attendees = attendees;
			templateData.text = screeningData.screening_description || this.options.text
			templateData.screening_unique_id = screeningData.screening_id;
			if ( !! screeningData.screening_user_full_name) {
				screeningData.avatar = this.getUserAvatar(screeningData);
				templateData.details = CTV.template(this.templateHost, screeningData)
			} else {
				templateData.details = CTV.template(this.templateTime, screeningData)
			}
			if (screeningData.screening_film_geoblocking_enabled == '1' || screeningData.screening_film_geoblocking_enabled == 'true') {
				templateData.button = '<p class="error-block error-block-small">We\'re sorry, this film cannot be streamed in your current location.</p>';
			} else {
				templateData.button = CTV.template('<a href="' + CTV.config.theaterUrl + '/theater/{{screening_unique_key}}" target="_ctv" class="ctv-button ctv-button-blue uppercase">Enter Theater</a>', screeningData);
			}

			options = {};
			options.klass = 'ctv-dialog-showtime';
			options.title = 'Attend the "' + screeningData.screening_film_name + '" screening';
			options.body = $(CTV.template(this.template, templateData));

			return options;
		},
		getAttendees: function(screeningData) {
			$.ajax({
				url: CTV.config.serviceUrl + '/services/Screenings/users?screening=' + screeningData.screening_id,
				type: "GET",
				cache: false,
				dataType: "jsonp",
				success: jQuery.proxy(this.onGetAttendeesSucess, this, screeningData)
			});
		},
		onGetAttendeesSucess: function(screeningData, response) {
			var attendees = ''
			if (parseInt(response.totalresults) > 0) {
				jQuery.each(response.users, function(index, user) {
					attendees += '<li><img src="' + (/http/.test(user.image) ? '' : CTV.config.baseUrl) + user.image + '" height="48" width="48"><p>' + user.username + '</p></li>';
				})
			} else {
				attendees = '<li class="be-first">Be the first to Join!</li>'
			}
			var options = this.getDialogOptions(attendees, screeningData);
			this.dialog.open(options);
		},
		getUserAvatar: function(showtime) {
			var avatar = 'https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png';
			if (showtime.screening_user_photo_url != '') {
				if (showtime.screening_user_photo_url.substr(0, 4) == 'http') {
					avatar = showtime.screening_user_photo_url;
				} else {
					avatar = CTV.config.baseUrl + '/uploads/hosts/' + showtime.screening_user_id + '/' + showtime.screening_user_photo_url;
				}
			} else if (showtime.screening_user_image != '') {
				if (showtime.screening_user_image.substr(0, 4) == 'http') {
					avatar = showtime.screening_user_image;
				} else {
					avatar = CTV.config.baseUrl + '/uploads/hosts/' + showtime.screening_user_id + '/' + showtime.screening_user_image;
				}
			}
			return avatar;
		}
	}

	CTV.templateSettings = {
		evaluate: /<%([\s\S]+?)%>/g,
		interpolate: /\{\{(.+?)\}\}/g,
		escape: /<%-([\s\S]+?)%>/g
	};

	var noMatch = /.^/;
	var unescape = function(code) {
			return code.replace(/\\\\/g, '\\').replace(/\\'/g, "'");
		};

	CTV.template = function(str, data) {
		var c = CTV.templateSettings;
		var tmpl = 'var __p=[],print=function(){__p.push.apply(__p,arguments);};' + 'with(obj||{}){__p.push(\'' + str.replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(c.escape || noMatch, function(match, code) {
			return "',_.escape(" + unescape(code) + "),'";
		}).replace(c.interpolate || noMatch, function(match, code) {
			return "'," + unescape(code) + ",'";
		}).replace(c.evaluate || noMatch, function(match, code) {
			return "');" + unescape(code).replace(/[\r\n\t]/g, ' ') + ";__p.push('";
		}).replace(/\r/g, '\\r').replace(/\n/g, '\\n').replace(/\t/g, '\\t') + "');}return __p.join('');";
		var func = new Function('obj', '_', tmpl);
		if (data) return func(data);
		return function(data) {
			return func.call(this, data);
		};
	};

	if (callback) {
		callback(CTV);
		initCTV();
	}

})(function() {
	window.CTV = arguments[0];
});