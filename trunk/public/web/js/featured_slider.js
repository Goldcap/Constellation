(function(){

    this.FeatureSlider = function(container, options){
        this.container = $(container);
        this.nextButton = $(this.container.next(options.next));
        this.prevButton = $(this.container.prev(options.previous)).css({opacity: 0.5});
        this.currentList = $(this.container.find(this.options.listTag));
        this.url = options.url;

        this.nextButton.bind('click', jQuery.proxy(this, 'onNextClick'));
        this.prevButton.bind('click', jQuery.proxy(this, 'onPrevClick'));
        
        this.params = {
            type: options.type,
            page: 2,
            film: $("#film_id").html()
        };
        this.isRunning = false;
        this.canPrevious = false;
        this.canNext = true;
        
        this.getList();
    
    }
    
    FeatureSlider.prototype = {
        options: {
            listTag: 'ul'
        },
        onNextClick: function(){
            if(this.canNext && !this.isRunning){
                this.getList(true);
            }
        },
        onPrevClick: function(){
            if(this.canPrevious && !this.isRunning){
                this.getList(false);
            }
        },

        getList: function(isNext){
            this.isRunning = true;
            this.isNext = isNext;
            this.params.page =  isNext ? this.params.page + 1 :   this.params.page - 1 ;
        
            jQuery.ajax({
                url: this.url,
                data: this.params,
                dataType: 'json',
                success:  jQuery.proxy(this, 'onGetListSuccess')
            })
        },
        onGetListSuccess: function(response){
            if(response.screeningList.meta.totalresults  > 0) {
                var self = this;
                this.newList = $('<ul class="featured_footer_list_thumbs"></ul>').css({left: this.isNext ? 940: -940});
                this.newList.appendTo(this.container);
                jQuery.each(response.screeningList.data, function(index, film){
										// if (film.screening_times.length > 0) {
										//   var lnkz = '<span class="upcoming_title uppercase">Next Showtime</span><a href="/theater/'+ film.screening_times[0].key+'"><span class="upcoming_time uppercase">'+ film.screening_times[0].date+'</span></a></span>';
										// } else {
										//   var lnkz = '';
										// }	
                                        var lnkz = '';
                	  var $film = $('<li><a href="/film/'+ film.screening_film_id+'"><img width="50" height="76" src="'+film.screening_film_logo+'"></a><span class="details"><a href="/film/'+ film.screening_film_id+'"><span class="title">'+ film.screening_film_name +'</span></a>'+lnkz+'</li>');
                    $film.appendTo(self.newList);
                });
                
                this.currentList.animate({
                    left: this.isNext? -940: 940
                },500, function(){
                    self.currentList.remove()
                    self.currentList = self.newList;
                    self.isRunning = false;
                });
                this.newList.animate({
                    left: 0
                },500);
                
                
                if((response.screeningList.meta.totalresults / 4 - 1) <= this.params.page){
                    this.canNext = false;
                    this.nextButton.css({opacity: 0.5});
                } else {
                    this.canNext = true;
                    this.nextButton.css({opacity: 1});
                }
                
                if(this.params.page == 1){
                    this.canPrevious = false;
                    this.prevButton.css({opacity: 0.5});
                } else {
                    this.canPrevious = true;
                    this.prevButton.css({opacity: 1});
                }
                
            }
        }
    };
})();

$(document).ready(function() {
    if (!window.console) window.console = {};
    if (!window.console.log) window.console.log = function() {};
    
    new FeatureSlider('#featureFilmsSlider', {next:'.next', previous: '.previous', url: '/service/FilmCarousel'})
    
});

    
