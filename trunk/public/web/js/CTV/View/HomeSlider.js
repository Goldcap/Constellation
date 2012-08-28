define(function(){
	var CTV = CTV || {};
	CTV.View = CTV.View || {};


	CTV.View.HomeSlider = Backbone.View.extend({
		el: $('#home-slide-ui'),
		options: {
			slideCycle: 4 * 1e3
		},
		initialize: function(){
			this.currentSlideIndex = 0;
			this.attachPoints();
			setTimeout(_.bind(this.changeSlide, this) , this.options.slideCycle);
			setTimeout(_.bind(this.startEmoicon1, this) , 2 * 1e3)
			setTimeout(_.bind(this.startEmoicon2, this) , 6 * 1e3)
			setTimeout(_.bind(this.startEmoicon3, this) , 10 * 1e3)
			setTimeout(_.bind(this.startEmoicon4, this) , 14 * 1e3)
		},
		attachPoints: function(){
			this.slides = $('.slide-container div', this.$el);
			this.emoicon1 = $('.emoicon1', this.$el);
			this.emoicon2 = $('.emoicon2', this.$el);
			this.emoicon3 = $('.emoicon3', this.$el);
			this.emoicon4 = $('.emoicon4', this.$el);
		},
		changeSlide: function(){
			this.slides.eq(this.currentSlideIndex).fadeOut(200, _.bind(function(){
				if(this.currentSlideIndex < this.slides.length - 1) {
					this.currentSlideIndex++;
				} else {
					this.currentSlideIndex = 0;
				}
				this.slides.eq(this.currentSlideIndex).fadeIn(200);
				setTimeout(_.bind(this.changeSlide, this) , this.options.slideCycle)
			}, this));
		},
		startEmoicon1: function(){
			this.emoicon1.css({top: 250}).animate({
				top: '240',
				opacity: 1
			}, 200)
			.delay( this.options.slideCycle )
			.animate({
					top: '230',
					opacity: 0
				}, 
				200, 
				_.bind(function(){
					setTimeout(_.bind(this.startEmoicon1, this) , 14 * 1e3);
				}, this)
			);
		},
		startEmoicon2: function(){
			this.emoicon2.css({top: 250}).animate({
				top: '240',
				opacity: 1
			}, 200)
			.delay( this.options.slideCycle )
			.animate({
					top: '230',
					opacity: 0
				}, 
				200, 
				_.bind(function(){
					setTimeout(_.bind(this.startEmoicon2, this) , 14 * 1e3);
				}, this)
			);		
		},
		startEmoicon3: function(){
			this.emoicon3.css({top: 240}).animate({
				top: '230',
				opacity: 1
			}, 200)
			.delay( this.options.slideCycle )
			.animate({
					top: '220',
					opacity: 0
				}, 
				200, 
				_.bind(function(){
					setTimeout(_.bind(this.startEmoicon3, this) , 14 * 1e3);
				}, this)
			);		
		},
		startEmoicon4: function(){
			this.emoicon4.css({top: 250}).animate({
				top: '240',
				opacity: 1
			}, 200)
			.delay( this.options.slideCycle )
			.animate({
					top: '230',
					opacity: 0
				}, 
				200, 
				_.bind(function(){
					setTimeout(_.bind(this.startEmoicon4, this) , 14 * 1e3);
				}, this)
			);		
		}
	});
	return CTV.View.HomeSlider;
});