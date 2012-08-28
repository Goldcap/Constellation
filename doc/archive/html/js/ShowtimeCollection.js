(function(){

    this.ShowtimeCollection = function(options){
        this.url = options.url;
        this.button = $(options.button);
        this.isActive = true;
        this.list = $(this.button.parent().prev());
        this.button.bind('click', jQuery.proxy(this, 'onButtonClick'));
        this.params = {
            type: options.type
        };
        this.setDefaults();
    
    }
    
    ShowtimeCollection.prototype = {
        options: {
            parseTag : '.movie_block',
//            listUrl : '/service/upcoming-shows.php',
            blockHeight: 224
        },
        onButtonClick: function(){
            if(this.isActive){
                this.getList();
            }
        },
        setDefaults: function(){
            var height = this.list.height();
            this.list.css({height: height});
            this.showtimeBlockCount = this.list.find(this.options.parseTag).length;
        },
        getList: function(){
        
            this.params.offset = this.showtimeBlockCount;
        
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
                jQuery.each(response.response.showtimes, function(index, showtime){
                   
                   var showtimeElement = new ShowtimeBlock(showtime);
                   $(showtimeElement.domNode).appendTo(self.button.parent().prev())

                   self.showtimeBlockCount = self.showtimeBlockCount+1;
                });
                this.slideDown();
                if(this.showtimeBlockCount >= response.response.total_showtimes){
                    this.isActive = false;
                    this.button.css({opacity: 0.5});
                }
            }
        },
        slideDown: function(){
            var height = this.list.height();
            this.list.animate({
                height: this.showtimeBlockCount * this.options.blockHeight
            })
        }
    };


})();
