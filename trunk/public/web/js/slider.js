(function(){

    this.Slideshow = function(element, option){    
        
        this.option = $.extend( {}, this.option, option );
        
        $('.' + this.option.container, $(element)).children().wrapAll('<div class="slides_control"/>');
                
        this.elem = $(element);
        this.control = $('.slides_control', this.elem);
        this.pagination = $('.' + this.option.paginationClass, this.elem);        
        this.details = $('.' + this.option.subBlockClass, this.elem.parent().parent());   
        
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
        this.moving = true; // If False, the show will stop all animation until True
        
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
	        	subBlockClass: 'slide_film_details',
	        	currentClass: 'current', // string, Class name for current class
	        	fadeSpeed: 350, // number, Set the speed of the fading animation in milliseconds
	        	fadeEasing: '', // string, must load jQuery's easing plugin before http://gsgd.co.uk/sandbox/jquery/easing/
	        	slideSpeed: 350, // number, Set the speed of the sliding animation in milliseconds
	        	slideEasing: '', // string, must load jQuery's easing plugin before http://gsgd.co.uk/sandbox/jquery/easing/
	        	start: 1, // number, Set the speed of the sliding animation in milliseconds
	        	effect: 'fade', // string, '[next/prev], [pagination]', e.g. 'slide, fade' or simply 'fade' for both
	        	crossfade: true, // boolean, Crossfade images in a image based slideshow
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
		    				self.restart();
		    			});
		    		}
		    		var self = this;
		    		
		    		this.nextButton = $('.'+ this.option.next, this.elem);
		    		this.prevButton = $('.'+ this.option.prev, this.elem);
		
            this.nextButton.bind('click', jQuery.proxy(this, 'onNextClick'));
            this.prevButton.bind('click', jQuery.proxy(this, 'onPrevClick'));
		            
		
		    		$(' li:eq('+ this.start +')', this.pagination).addClass(self.option.currentClass);
		        //$(' li.slide_film_details_block:eq('+ this.start +')', this.details).addClass(self.option.currentClass);
		    		
		    		// click handling
		    		$('li a' , this.pagination ).bind('click', function(){
		    			if (self.option.play) {
		    				 self.stop();
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
		      
		      //Just FYI, this didn't work, so I added "HALTEN!" and "ANFAGEN!" below...
          /*
					$('.watch_trailer').click(function(){
              self.stop();
              console.log('carousel stopped!')
          })
        	*/      
        },

				animate: function (direction, effect, clicked) {
					//console.log("MOVING? " + this.moving);
					if (! this.moving)
						return;
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
			
		      	var self = this;
		        // fade animation with crossfade
		      	if (this.option.crossfade) { 
		      		// put hidden next above current
		      		this.control.children(':eq('+ this.next +')', this.elem).css({
		      			zIndex: 10
		      		// fade in next
		      		}).fadeIn(this.option.fadeSpeed, this.option.fadeEasing, function(){
		      			if (self.option.autoHeight) {
		      				// animate container to height of next
		      				control.animate({
		      					height: self.control.children(':eq('+ self.next +')', self.elem).outerHeight()
		      				}, self.option.autoHeightSpeed, function(){
		      					// hide previous
		      					self.control.children(':eq('+ self.prev +')', self.elem).css({
		      						display: 'none',
		      						zIndex: 0
		      					});								
		      					// reset z index
		      					self.control.children(':eq('+ self.next +')', self.elem).css({
		      						zIndex: 0
		      					});									
		      					// end of animation
		      					self.option.animationComplete(self.next + 1);
		      					self.active = false;
		      				});
		      			} else {
		      				// hide previous
		      				self.control.children(':eq('+ self.prev +')', self.elem).css({
		      					display: 'none',
		      					zIndex: 0
		      				});									
		      				// reset zindex
		      				self.control.children(':eq('+ self.next +')', self.elem).css({
		      					zIndex: 0
		      				});									
		      				// end of animation
		      				self.option.animationComplete(self.next + 1);
		      				self.active = false;
		      			}
		      		});
		      	} else {
		      		// fade animation with no crossfade
		      		this.control.children(':eq('+ this.prev +')', this.elem).fadeOut(this.option.fadeSpeed,  this.option.fadeEasing, function(){
		      			// animate to new height
		      			if (self.option.autoHeight) {
		      				self.control.animate({
		      					// animate container to height of next
		      					height: self.control.children(':eq('+ next +')', self.elem).outerHeight()
		      				}, self.option.autoHeightSpeed,
		      				// fade in next slide
		      				function(){
		      					self.control.children(':eq('+ self.next +')', self.elem).fadeIn(self.option.fadeSpeed, self.option.fadeEasing);
		      				});
		      			} else {
		      			// if fixed height
		      				self.control.children(':eq('+ self.next +')', self.elem).fadeIn(self.option.fadeSpeed, self.option.fadeEasing, function(){
		      					// fix font rendering in ie, lame
		      					if($.browser.msie) {
		      						$(this).get(0).style.removeAttribute('filter');
		      					}
		      				});
		      			}									
		      			// end of animation
		      			self.option.animationComplete(self.next + 1);
		      			self.active = false;
		      		});
		      	}
		
						if (this.option.pagination) {
							$('li.' + this.option.currentClass, this.pagination).removeClass(this.option.currentClass);
							$('li:eq('+ this.current +')', this.pagination).addClass(this.option.currentClass);
						}
            // Temp Stupid thing 
            if(this.current == 0 && this.control.children(':eq('+ this.current +')', this.elem).attr('data-banner') == 'isPresentationBanner'){
                $('#howItWorks').css({display:'block'})
            } else {
                $('#howItWorks').css({display:'none'})
                
            }
            
            // if(this.current != 0) {
			    $('li.' + this.option.currentClass, this.details).removeClass(this.option.currentClass);
			    $('li.slide_film_details_block:eq('+ (this.current - 1) +')', this.details).addClass(this.option.currentClass);
			// }
						
			}
			var self = this;
			this.playTimeout = setTimeout(function() {
				self.animate('next', self.effect);
			}, this.option.play);
			
		}, // end animate function
		
		halten: function() {
    	this.moving = false;
    },
    
    anfagen: function() {
    	this.moving = true;
    },
    
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

          if (this.option.play) {
          	 this.pause();
          }
          this.animate('next', this.effect);
      },
      onPrevClick: function(){

          if (this.option.play) {
          	 this.pause();
          }
          this.animate('prev', this.effect);
      }
            
    }

})();

$(document).ready(function() {
	var slider = new Slideshow(
   $('#home-slideshow'),{
        generatePagination: false,
        pagination: true,
        currentClass: 'active',
        play: 5000,
        hoverPause: true
    });
});
