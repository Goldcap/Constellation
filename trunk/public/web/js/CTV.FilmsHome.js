var CTV = CTV || {};
CTV.FilmsHome = function(options){
    this.options = $.extend({
        listUrl: '/service/AllFilms',
        viewUrl: '/film/',
        records: 8,
        filterBy: {
            alpha: 'name',
            upcoming: 'showtimes',
            genre: 'all'
        }   
    }, options);
    this.init();
}

CTV.FilmsHome.prototype = {

    init: function(){
        this.params = {page: 1, sort: this.options.filterBy.upcoming, records: this.options.records, featured: true};
        this.filmCollection = $('#not');

        this.attachPoints();
        this.makeTip();
        this.attachEvents();
        this.getList();
        
        this.canAddMore = true;
        this.genreIsVisible = false;
    },
    attachPoints: function(){
        this.filmCollectionContainer = $('#filmContainer');
        this.filmCollectionParent = this.filmCollectionContainer.parent()
        this.pagination = $('<ul class="film-pagination clear"></ul>').appendTo( this.filmCollectionParent)

    },
    makeTip: function(){
        this.isOverTip = false;
        this.tip = $('<a class="poster_tip"></a>');
        this.filmCollectionContainer.parent().append(this.tip);
    },
    onTipClick: function(){
        window.location = this.tip.data('href');
    },
    attachEvents: function(){

        $('#filmContainer li').live('mousemove', _.bind( this.onShowTip,this )).live('mouseleave',  _.bind( this.onHideTip,this ));
        $('.film-pagination li').live('click', _.bind(this.onPaginate, this));
        this.tip.bind('click', _.bind(this.onTipClick , this));
    },
    getList: function(){
        jQuery.ajax({
            url: this.options.listUrl,
            data: this.params,
            dataType: 'json',
            success: _.bind(this.displayList, this)
        });
    },
    displayList: function(response){

        var self = this,
            i = 0;

        var newFilmCollection = $('<ul class="film_collection clearfix clear" style="display: none"></ul>');
        newFilmCollection.appendTo(this.filmCollectionContainer);
        if (response.filmList.totalResults > 0) {
	        _.each(response.filmList.films, function(film, index){

	            $film = $('<li'+(!(i % 4)? ' class="first_of_row"':'') +'><a href="/film/'+ film.id +'"><span class="poster_shadow"><img src="' + __CONFIG.assetUrl + film.small_logo_src+'"/></span></a></li>')
	            newFilmCollection.append($film);        
	            jQuery.data($film[0], 'filmData', film);
	           i++;
	        });
		}
		if(!!this.filmCollection.length){
            this.filmCollection.fadeOut(function(){
                self.filmCollection.remove();
                self.filmCollection = newFilmCollection;
                $(self.filmCollection).fadeIn();
            });
        } else {
            this.filmCollection = newFilmCollection;
            $(this.filmCollection).fadeIn();
        }
        this.filmCollectionContainer.animate({
            height: newFilmCollection.height()
        })

        this.updatePagination(response);
    },


    resetContainer: function(){
        var self = this;
        this.filmCollectionParent.animate({'opacity': 0}, 150, function() {
            self.filmCollectionContainer.empty();
            self.getList();
          });
    },
    updatePagination: function(response){
        this.pagination.empty();
        var total = Math.ceil(parseInt(response.filmList.totalResults) / this.options.records);
        if(total > 1){
            $('<li class="film-pagination-page-previous'+ (1 == this.params.page ? ' disabled':'')+'" data-page="'+(parseInt(this.params.page) -1)+' "></li>').appendTo(this.pagination);   
        }
        for(var i = 1; i <= total; i++){
            $('<li class="film-pagination-page'+ (i == this.params.page ? ' active':'')+'" data-page="'+i+'"></li>').appendTo(this.pagination);   
        }          
                if(total > 1){
            $('<li class="film-pagination-page-next'+ (total == this.params.page ? ' disabled':'')+'" data-page="'+(parseInt(this.params.page) + 1)+'"></li>').appendTo(this.pagination);   
        }  
    },
    onPaginate: function(event){
        if($(event.target).is('li') && $(event.target).attr('data-page') != this.params.page && !$(event.target).hasClass('disabled')){
            this.params.page = $(event.target).attr('data-page');
            this.getList();
        }
    },
    onShowTip: function(event){
        var $li = $(event.target);
        
        if(!$li.is('li')){
            $li = $li.closest('li');
        }
        $('#filmContainer li').css({'z-index': 1})
        $li.css({'z-index': 5})
        $film = $li[0];
         var position = $li.position()
           , data = jQuery.data($film, 'filmData')
           , html = '<span class="tip_synopsis">' + data.name + '</span>'
        html += '<span class="tip_synopsis">' + data.info + '</span>';
       
        if(data.makers){
            var maker = data.makers.split('|');
            html += '<span class="tip_maker">Directed by '+ maker[1] +'</span>';
        }
         
				
        if(data.showtimes != undefined){
        		html += '<span class="tip_showtimes"><span class="tip_sub_header">Upcoming Showtimes</span><span class="tip_schedule">';
				                    html += data.showtimes.join (' | ');

            // }
        } else {
		  		//html += "No Upcoming Showtimes";
				}
		html += '</span></span>';
				 
         $li.append(this.tip);
         this.tip.data('href', this.options.viewUrl + data.id).html(html);
         this.tip.addClass('show').css({
             'margin-left':  - (event.pageX - position.left + 30),
             'margin-top': ( - this.tip.height() / 2) -50
         });
         
         this.tip.height();        
    },
    onHideTip: function(){
        this.tip.removeClass('show');
    }

};

