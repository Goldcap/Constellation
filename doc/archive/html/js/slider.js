(function(){

    this.Slideshow = function(element, option){    
        
        this.option = $.extend( {}, this.option, option );
        
        $('.' + this.option.container, $(element)).children().wrapAll('<div class="slides_control"/>');
                
        this.elem = $(element);
        this.control = $('.slides_control', this.elem);
        this.pagination = $('.' + this.option.paginationClass, this.elem);        
        this.total = this.control.children().size();
        this.width = this.control.children().outerWidth();
        this.height = this.control.children().outerHeight();
        this.start = this.option.start - 1;
        this.effect = 'slide';
        this.paginationEffect = 'slide';

        this.next = 0;
        this.prev = 0;
        this.number = 0;
        this.current = 0;        
        
        this.loaded;
        this.active;
        this.clicked;
        this.position;
        this.direction;
        this.imageParent;
        this.pauseTimeout;
        this.playInterval;
        
        if (this.total < 2) {
        	$('.' + this.option.container, $(this)).fadeIn(this.option.fadeSpeed, this.option.fadeEasing, function(){
        		this.loaded = true;
        	});
        	$('.' + this.option.next + ', .' + this.option.prev).fadeOut(0);
        	return false;
        }
        
        this.postInitialize();
    }
    
    Slideshow.prototype = {
    
        option : {
            url: '/service/feature-films-slide.php',
        	preload: false, // boolean, Set true to preload images in an image based slideshow
        	preloadImage: '/img/loading.gif', // string, Name and location of loading image for preloader. Default is "/img/loading.gif"
        	container: 'slides_container', // string, Class name for slides container. Default is "slides_container"
        	generateNextPrev: false, // boolean, Auto generate next/prev buttons
        	next: 'next', // string, Class name for next button
        	prev: 'previous', // string, Class name for previous button
        	pagination: true, // boolean, If you're not using pagination you can set to false, but don't have to
        	generatePagination: true, // boolean, Auto generate pagination
        	prependPagination: false, // boolean, prepend pagination
        	paginationClass: 'pagination', // string, Class name for pagination
        	currentClass: 'current', // string, Class name for current class
        	fadeSpeed: 350, // number, Set the speed of the fading animation in milliseconds
        	fadeEasing: '', // string, must load jQuery's easing plugin before http://gsgd.co.uk/sandbox/jquery/easing/
        	slideSpeed: 350, // number, Set the speed of the sliding animation in milliseconds
        	slideEasing: '', // string, must load jQuery's easing plugin before http://gsgd.co.uk/sandbox/jquery/easing/
        	start: 1, // number, Set the speed of the sliding animation in milliseconds
        	effect: 'slide', // string, '[next/prev], [pagination]', e.g. 'slide, fade' or simply 'fade' for both
        	crossfade: false, // boolean, Crossfade images in a image based slideshow
        	randomize: false, // boolean, Set to true to randomize slides
        	play: 0, // number, Autoplay slideshow, a positive number will set to true and be the time between slide animation in milliseconds
        	pause: 0, // number, Pause slideshow on click of next/prev or pagination. A positive number will set to true and be the time of pause in milliseconds
        	hoverPause: false, // boolean, Set to true and hovering over slideshow will pause it
        	autoHeight: false, // boolean, Set to true to auto adjust height
        	autoHeightSpeed: 350, // number, Set auto height animation time in milliseconds
        	bigTarget: false, // boolean, Set to true and the whole slide will link to next slide on click
        	animationStart: function(){}, // Function called at the start of animation
        	animationComplete: function(){}, // Function called at the completion of animation
          	slidesLoaded: function() {} // Function is called when slides is fully loaded
        },

        postInitialize: function(){
            $('.' + this.option.container, this.elem).css({
				overflow: 'hidden',
				position: 'relative'
			});
    		
    		// set css for slides
    		this.control.children().css({
    			position: 'absolute',
    			top: 0, 
    			left: this.control.children().outerWidth(),
    			zIndex: 0,
    			display: 'none'
    		 });
    		
    		// set css for control div
    		this.control.css({
    			position: 'relative',
    			width: (this.width * 3),
    			height: this.height,
    			left: -this.width
    		});
    		
    		// show slides
    		$('.' + this.option.container, this.elem).css({
    			display: 'block'
    		});
        		
    		var self = this;
    			
			this.control.children(':eq(' + this.start + ')').fadeIn(this.option.fadeSpeed, this.option.fadeEasing, function(){
				self.loaded = true;
			});    		
    		    		
    		// pause on mouseover
    		if (this.option.hoverPause && this.option.play) {
    			var self = this;
    			this.control.bind('mouseover',function(){
    				self.stop();
    			});
    			this.control.bind('mouseleave',function(){
    				self.pause();
    			});
    		}
    		var self = this;
    		
    		
    		this.nextButton = $('.'+ this.option.next, this.elem);
    		this.prevButton = $('.'+ this.option.prev, this.elem).css({opacity: 0.5});

            this.nextButton.bind('click', jQuery.proxy(this, 'onNextClick'));
            this.prevButton.bind('click', jQuery.proxy(this, 'onPrevClick'));
            
            this.params = {
//                type: options.type,
                offset: 1
            };
            this.isRunning = false;
            this.canPrevious = false;
            this.canNext = true;
            


    		$(' li:eq('+ this.start +')', this.pagination).addClass(self.option.currentClass);
    		
    		// click handling
    		
    		$('li a' , this.pagination ).live('click', function(){
    			if (self.option.play) {
    				 self.pause();
    			}
    									
    			self.clicked = parseInt($(this).attr('href').match('[^#/]+$')[0]) -1;    			
    			if (self.current != self.clicked) {
    				self.animate('pagination', self.aginationEffect, self.clicked);
    			}
    			return false;
    		});
    		    	
			this.playTimeout = setTimeout(function() {
				self.animate('next', self.effect);
			}, self.option.play);
            
        },

		animate: function (direction, effect, clicked) {
			if (!this.active && this.loaded) {
				this.active = true;
				if(this.playTimeout){
    			    clearInterval(this.playTimeout)
                }
				switch(direction) {
					case 'next':
						// change current slide to previous
						this.prev = this.current;
						this.next = this.current + 1;
						this.next = this.total === this.next ? 0 : this.next;
						this.position = this.width*2;
						direction = -this.width*2;
						this.current = this.next;
					break;
					case 'prev':
						this.prev = this.current;
						this.next = this.current - 1;
						this.next = this.next === -1 ? this.total-1 : this.next;								
						this.position = 0;								
						direction = 0;		
						this.current = this.next;
					break;
					case 'pagination':
						this.next = parseInt(clicked,10);
						this.prev = this.current;
						
						
						
						if (this.next > this.prev) {
							this.position = this.width*2;
							direction = -this.width*2;
						} else {
							this.position = 0;
							direction = 0;
						}
						this.current = this.next;
					break;
				}

				this.control.children(':eq('+ this.next +')').css({
					left: this.position,
					display: 'block'
				});

						// animate control
				var self = this;
				
				this.control.animate({
					left: direction
				},this.option.slideSpeed, this.option.slideEasing, function(){
					// after animation reset control position
					self.control.css({
						left: -self.width
					});
					// reset and show next
					self.control.children(':eq('+ self.next +')').css({
						left: self.width,
						zIndex: 5
					});
					// reset previous slide
					self.control.children(':eq('+ self.prev +')').css({
						left: self.width,
						display: 'none',
						zIndex: 0
					});
					// end of animation
					self.option.animationComplete(self.next + 1);
					self.active = false;
				});
				// set current state for pagination
				if (this.option.pagination) {
//				console.log(this.pagination)
					$('li.' + this.option.currentClass, this.pagination).removeClass(this.option.currentClass);
					$('li:eq('+ this.current +')', this.pagination).addClass(this.option.currentClass);
				}
			}
			var self = this;
			this.playTimeout = setTimeout(function() {
				self.animate('next', self.effect);
			}, this.option.play);
			
		}, // end animate function

        
        stop: function() {
            if(this.playTimeout){
            	clearTimeout(this.playTimeout);
        	}
        },
        restart: function(){
            if(this.playTimeout){
            	clearTimeout(this.playTimeout);
            }
			var self = this;
            this.playTimeout = setTimeout(function() {
            	self.animate('next', self.effect);
            }, this.option.play);
        },
        pause: function() {
        	if (this.option.pause) {
        		// clear timeout and interval
        		var self = this;
        		
        		clearTimeout(selfs.elem.data('pause'));
        		clearInterval(self.elem.data('interval'));
        		// pause slide show for option.pause amount
        		pauseTimeout = setTimeout(function() {
        			// clear pause timeout
        			clearTimeout(self.data('pause'));
        			// start play interval after pause
        			playInterval = setInterval(	function(){
        				self.animate("next", effect);
        			},self.option.play);
        			// store play interval
        			self.elem.data('interval',playInterval);
        		},self.option.pause);
        		// store pause interval
        		self.elem.data('pause',pauseTimeout);
        	} else {
        		// if no pause, just stop
        		this.stop();
        	}
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
            this.params.offset =  isNext ? this.params.offset + 1 :   this.params.offset - 1 ;
        
            jQuery.ajax({
                url: this.option.url,
                data: this.params,
                dataType: 'json',
                success:  jQuery.proxy(this, 'onGetListSuccess')
            })
        },
        onGetListSuccess: function(response){
            if(response.meta.status == 200) {
            
                this.stop();
//                console.log(response.response.films[0]);
                var data = response.response.films[0];
            
                var newPagination = $('<li><a href=""><img width="50" height="76" src="'+data.poster_small+'"><span class="details"><span class="title">'+data.title+'</span><span class="upcoming_title uppercase">Next Showtime</span><span class="upcoming_time uppercase">'+data.time.split('|')[0]+'</span></span></a></li>');
                newPagination[this.isNext? 'appendTo' : 'prependTo'](this.pagination);
                
                if(!this.isNext){
                    this.pagination.css({
                        left: -280
                    })
                };
                
                var newSlide = $('<div style="position: absolute; top: 0px; left: 940px; z-index: 0; display: none;"><a href="/film/'+ data.id +'"><img class="slideshow_image" src="'+data.poster+'"><span class="slideshow_details"><span class="title">'+data.title+'</span><span class="description">'+ data.description +'</span><span class="upcoming_title uppercase">Upcoming Show</span><span class="upcoming_time uppercase">'+data.times+'</span><span class="view_all">View All »</span></span></a></div>');
                newSlide[this.isNext? 'appendTo' : 'prependTo'](this.control);
                
                $('div', this.control)[this.isNext? 'first' : 'last']().remove()                
                
                if(!this.isNext){
                    this.pagination.css({
                        left: -280
                    })
                };
                            
                
                var self = this;
                
                this.pagination.animate({
                    left: this.isNext ? -280 : 0
                },this.option.slideSpeed, this.option.slideEasing, function(){
                    $('li', self.pagination)[self.isNext? 'first' : 'last']().remove(); 
                    self.pagination.css({
                        left: 0
                    })
                    
                    $('li', self.pagination).each(function(index){
                        $('a', $(this)).attr('href', '#' + (index+1));
                    });
                    
                        self.animate('next');
                
                });
                                
                if(this.isNext){
                    if(this.current == 0){
                        this.current = 2;
                    } else {
                        this.current = this.current - 1;
                    }
                } else {
                    if(this.current == 2){
                        this.current = 2;
                    } else {
                        this.current = this.current + 1;
                    }
                }

                this.isRunning = false;

                
                
                if(response.response.total < this.params.offset){
                    this.canNext = false;
                    this.nextButton.css({opacity: 0.5});
                } else {
                    this.canNext = true;
                    this.nextButton.css({opacity: 1});
                }
                
                if(this.params.offset == 1){
                    this.canPrevious = false;
                    this.prevButton.css({opacity: 0.5});
                } else {
                    this.canPrevious = true;
                    this.prevButton.css({opacity: 1});
                }
                
            }
        }
        
        
            
    }


})();