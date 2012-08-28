define(["./../View/EventModule","./../View/HomeSlider",'./../../vendor/jquery.isotope'], function(EventModule, HomeSlider){

    var CTV = CTV || {};

    CTV.View = CTV.View  || {};
    CTV.View.EventModule = EventModule;

    CTV.Controller =  CTV.Controller || {};
    CTV.Controller.Events = Backbone.View.extend({
    	el: $("#events"),
        noResults: $('<div class="events-no-result"><h2>There are currently no events matching your query</h2><p>Try searching for another item or completing your term</p></div>'),
    	eventType: 'upcoming',
        page: 1,
		initialize: function () {
			this.attachPoints();
			this.attachEvents();

            this.eventContainer.isotope({
                  itemSelector : '.event-module',
                  animationEngine: 'jquery'
            });
            new HomeSlider();
        },
		attachPoints: function(){
    		this.viewUpcoming = $('#upcoming-button', this.$el);
    		this.viewPast = $('#past-button', this.$el);
    		this.viewMore = $('#more-button', this.$el);
    		this.eventContainer = $('#events-container', this.$el);
            this.searchInput = $('#events-search', this.$el);
            this.searchDelete = $('#search-delete', this.$el).hide()
            // console.log(this.searchDelete);
		},
		attachEvents: function(){
            this.viewUpcoming.bind("click", _.bind(this.onViewUpcomingClick, this));
            this.viewPast.bind("click", _.bind(this.onViewPastClick, this));
            this.viewMore.bind("click", _.bind(this.onViewMoreClick, this));
            this.searchInput.bind('keyup', _.bind(this.onSearchKeyup, this));
            this.searchDelete.bind('click', _.bind(this.onSearchDelete, this));
		},
        onViewUpcomingClick: function(){
            if(this.eventType != 'upcoming'){
                this.eventContainer.isotope( 'remove', $( '.event-module',this.eventContainer) ).empty();
                this.viewUpcoming.addClass('active');
                this.viewPast.removeClass('active');
                this.searchInput.val('').attr('placeholder', 'Search for upcoming events...')
                this.page = 1;
                this.eventType = 'upcoming';
                this.searchDelete.hide();
                this.getEvents();
            }

        },
        onViewPastClick: function(){
            if(this.eventType != 'past'){
                this.viewUpcoming.removeClass('active');
                this.viewPast.addClass('active');
                this.searchInput.val('').attr('placeholder', 'Search for previous events...')
                this.eventContainer.isotope( 'remove', $( '.event-module', this.eventContainer) ).empty();;
                this.page = 1;
            	this.eventType = 'past';
                this.searchDelete.hide();
            	this.getEvents();
            }
        },
        onViewMoreClick: function(){
            this.page++;
            this.getEvents();
        },
        onSearchKeyup: function(){
            if(!!this.searchInput.val()){
                 this.searchDelete.show();
             } else {
                 this.searchDelete.hide();
             }
            if(this.searchTimeOut) clearTimeout(this.searchTimeOut);
            this.searchTimeOut = setTimeout(_.bind(this.search, this), 500);
        },
        search: function(){
            this.page = 1;
            this.eventContainer.isotope( 'remove', $( '.event-module', this.eventContainer) ).empty();
            this.getEvents(this.searchInput.val());
        },
        onSearchDelete: function(){
            this.searchDelete.hide();
            this.page = 1;
            this.searchInput.val('');
            this.eventContainer.isotope( 'remove', $( '.event-module', this.eventContainer) ).empty();
            this.getEvents();
        },
        getEvents: function(keyword){
            if(this.getEventRequest) this.getEventRequest.abort();

            var url = '/services/Screenings/events?type=' + this.eventType + '&page=' + this.page;
            if(keyword){
                url += '&search=' + keyword;
            }

        	this.getEventRequest = $.ajax({
        		url: url,
        		success: _.bind(this.onGetEventsSuccess, this)
        	});
        },
        onGetEventsSuccess: function(response){
        	if(response.totalresults){
        		_.each(response.events, _.bind(this.addEventModule, this));
               // this.eventContainer.isotope( 'reLayout')

               if(response.totalresults <= this.page * 9){
                this.viewMore.hide();
               } else {
                this.viewMore.show();

               }
        	} else {
                this.eventContainer.append($(this.noResults));
        	}
        	// console.log(response);
        },
        addEventModule: function(data){
        	var eventModule  =  new CTV.View.EventModule({model:data});
            var el = eventModule.render().el;
            // console.log(el)
        	this.eventContainer
                // .append(el)
                .isotope( 'insert', $(el) )
        }
    });

   return CTV.Controller.Events;
});