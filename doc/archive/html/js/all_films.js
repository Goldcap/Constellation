var AllFilms = {

    options:{
        listUrl: '/service/AllFilms.php',
        viewUrl: '/film/',
        records: 12,
        filterBy: {
            alpha: 'name',
            upcoming: 'rename',
            genre: 'all'
        }
    },

    init: function(){
        this.params = {page: 1, sort: this.options.filterBy.upcoming, records: this.options.records};
        this.attachPoints();
        this.makeTip();
        this.attachEvents();
        this.getList();
        
        this.canAddMore = true;
        this.genreIsVisible = false;
    },
    attachPoints: function(){
        this.filmCollectionContainer = $('#filmContainer');
        this.filmCollectionParent = this.filmCollectionContainer.parent();
        this.addMoreButton = $('#moreFilms');
        
        this.filterByTimeButton = $('#toggleTime');
        this.filterByAlphaButton = $('#toggleAlpha');
        this.filterByGenre = $('#toggleGenre');
        this.filterByGenreSelect = this.filterByGenre.next();
    },
    makeTip: function(){
        var self = this;
        this.isOverTip = false;
        this.tip = $('<a class="poster_tip"></a>');
        this.filmCollectionParent.append(this.tip);
    },
    attachEvents: function(){
        var self = this;
        $('#filmContainer li').live('mousemove', jQuery.proxy( self, 'onShowTip' )).live('mouseleave', jQuery.proxy( self,'onHideTip'));
        this.addMoreButton.bind('click', jQuery.proxy( self, 'onAddMoreClick' ));
        this.filterByTimeButton.bind('click', jQuery.proxy( self, 'onFilterByTime'));
        this.filterByAlphaButton.bind('click', jQuery.proxy( self, 'onFilterByAlpha'));
        this.filterByGenre.bind('click', jQuery.proxy( self, 'onFilterByGenre'));
        this.filterByGenreSelect.find('.genre_options').bind('click', jQuery.proxy( self, 'onSelectGenre'));
        this.filterByGenreHeight = this.filterByGenreSelect.find('.genre_options').length * 38;
    },
    getList: function(){
        var self = this;
        jQuery.ajax({
            url: this.options.listUrl,
            data: this.params,
            dataType: 'json',
            success: jQuery.proxy( self, 'displayList' )
        })
    },
    displayList: function(response){
        var self = this,
            i = 0;
        jQuery.each(response.filmList.films, function(index, film){
            $film = $('<li'+(!(i % 4)? ' class="first_of_row"':'') +'><span class="poster_shadow"><img src="http://constellation.tv'+ film.small_logo_src+'"/></span><p class="title">' + film.name + '</p></a></li>')
            self.filmCollectionContainer.append($film);        
            jQuery.data($film[0], 'filmData', film);
           
           i++;
        });
        this.filmCollectionParent.animate({'opacity': 1 },150).removeClass('loading');

        if(this.params.page >= parseInt(response.filmList.totalResults) / this.options.records ){
            this.disableMore();
        } else {
            this.enableMore();
        }
        
    },
    disableMore: function(){
        this.addMoreButton.addClass('disabled').css('opacity', '0.5');
        this.canAddMore = false;
    },
    enableMore: function(){
        this.addMoreButton.removeClass('disabled').css('opacity', '1');
        this.canAddMore = true;
    },
    onAddMoreClick: function(){
        if(this.canAddMore){
            this.params.page++;
            this.getList();
        }
    },
    onFilterByTime: function(){
        if(this.params.sort != this.options.filterBy.upcoming){
            this.filterByTimeButton.addClass('active');
            this.filterByAlphaButton.removeClass('active');
            this.params.sort = this.options.filterBy.upcoming;
            this.params.page = 1;
            this.resetContainer();
            
        }
    },
    onFilterByAlpha: function(){
        if(this.params.sort != this.options.filterBy.alpha){
            this.filterByTimeButton.removeClass('active');
            this.filterByAlphaButton.addClass('active');
            this.params.sort = this.options.filterBy.alpha;
            this.params.page = 1;
            
            this.resetContainer();
        }
    },
    onFilterByGenre: function(e){
        e.stopPropagation()
    
        if(!this.genreIsVisible){
            this.genreOpen();
        } else {
            this.genreClose();
        }
    },
    onSelectGenre: function(event){
        var genre = $(event.target).html()
        this.filterByGenre.html(genre);
//        this.params.genre = genre.toLowerCase();
        if(this.params.genre != genre.toLowerCase()){
            this.params.sort = genre.toLowerCase();
            this.params.page = 1;
            
            this.resetContainer();
        }
    },
    genreClose: function(){
        this.filterByGenreSelect.animate({
            'height': 0
        });
        this.genreIsVisible = false;
        $('body').unbind('click', jQuery.proxy(this, 'genreClose'));
    
    },
    genreOpen: function(){
        this.filterByGenreSelect.animate({
            'height': this.filterByGenreHeight
        });
        this.genreIsVisible = true;
        $('body').bind('click', jQuery.proxy(this, 'genreClose'));
    },
    resetContainer: function(){
        var self = this;
        this.filmCollectionParent.animate({'opacity': 0}, 150, function() {
            self.filmCollectionContainer.empty();
            self.getList();
          });
    },
    onShowTip: function(event){
        var $li = $(event.target);
        
        if(!$li.is('li')){
            $li = $li.closest('li');
        }
        $film = $li[0];
         var position = $li.position()
           , data = jQuery.data($film, 'filmData')
           , html = '<span class="tip_synopsis">' + data.info + '</span>';
        
        data.showtimes = '9:30PM|10:30PM|12:30PM'
       
         if(data.makers){
             var maker = data.makers.split('|');
             html += '<span class="tip_maker">Directed by '+ maker[1] +'</span>';
         }
         if(data.showtimes){
             var showtimes = data.showtimes.split('|');
             html += '<span class="tip_showtimes"><span class="tip_sub_header">Upcomming Showtimes</span><span class="tip_schedule">'+ showtimes.join(' | ') +'</span></span>';
         } 
         $li.append(this.tip);
         this.tip.attr('href', this.options.viewUrl + data.id).html(html);
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
