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
            page: 1
        };
        this.isRunning = false;
        this.canPrevious = false;
        this.canNext = true;
    
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
            if(response.meta.status == 200) {
                var self = this;
                this.newList = $('<ul class="featured_footer_list_thumbs"></ul>').css({left: this.isNext ? 940: -940});
                this.newList.appendTo(this.container);
                jQuery.each(response.response.films, function(index, film){
                    var $film = $('<li><a href="/film/'+ film.id+'"><img width="50" height="76" src="'+film.poster+'"><span class="details"><span class="title">'+ film.title +'</span><span class="upcoming_title uppercase">Next Showtime</span><span class="upcoming_time uppercase">'+ film.time+'</span></span></a></li>');
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
                
                
                if(response.response.page_count <= this.params.page){
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