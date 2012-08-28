var CTV = CTV || {};
CTV.TheaterDetails = function(options, domNode){
	this.options = $.extend({

    }, options);
    this.domNode = domNode;
    this.init();

}

CTV.TheaterDetails.prototype = {
	init: function(){
		this.isVisible = false;
		this.attachPoints();
		this.attachEvents();

		this.startCount();
	},
	hide: function(){
		this.domNode.fadeOut();	
	},
	attachPoints: function(){
		this.timer = $('#time', this.domNode);
		this.period = $('#period', this.domNode);
		this.parentDomNode = this.domNode.parent();
	},
	attachEvents: function(){
	},
	show: function(){
		if(!this.isVisible){
			this.isVisible = true;
			this.domNode.fadeIn();
			this.setPosition();
		}
	},
	hide: function(){
		if(this.isVisible){
			this.isVisible = false;
			this.domNode.fadeOut();
			this.pauseTimer();
		}
	},
	setPosition: function(){
		if(this.isVisible){
			var dh = this.domNode.height();
			var ph = this.parentDomNode.height();
			if(ph - dh - 20  > 0){
				this.domNode.css({
					'margin-top': (ph - dh) / 2
				});
			} else {
				this.domNode.css({
					'margin-top': 10
				});
			}
		}
	},
	setServerTime: function(time){
		this.time = time; 
	},
	getCurrentTime: function(){
		return new Date(this.time *1000)	
	},
	startCount: function(){
		if(this.time || this.options.startTime){
			if(this.options.filmStartTime > parseInt(this.time)){
				this.countDown();
			} else{
				this.countUp();
			}
		} else {
			window.setTimeout(_.bind(this.startCount, this), 200);
		}
	},
	countDown: function(){
		var date = new Date(this.options.filmStartTime * 1000);
		this.period.html('until showtime').parent().removeClass('countup').addClass('countdown')
		var layout = '{hnn}<span>Hr</span> {mn}<span>Min</span> {sn}<span>S</span>',
			format = 'HMS';

		if(this.options.filmStartTime - parseInt(this.time) > 60 * 60 * 24){
			layout = '{dn}<span>Days</span> {hn}<span>Hr</span> {mn}<span>Min</span> {sn}<span>S</span>';
			format = 'DHMS'
		}

		this.timer.countdown({
			until: date,  
		    onExpiry: _.bind(this.onExpiry, this),
		    layout: layout, 
        	serverSync: _.bind(this.getCurrentTime, this),
	        format: format
		});

	},
	countUp: function(){
		this.period.html('elapsed').parent().removeClass('countdown').addClass('countup')
		var date = new Date(this.options.filmStartTime*1000)
		this.timer.countdown({
			since: date,  
		    onExpiry: _.bind(this.onExpiry, this),
		    layout: '{hn}<span>Hours</span> {mn}<span>Min</span> {sn}<span>S</span>', 
        	serverSync: _.bind(this.getCurrentTime, this),
	        format: 'HMS'
		});
	},
	onExpiry: function(){
		if(this.options.hasTicket){
			$(this).trigger('startFilm')		
		} else {
			this.countUp();
		}
	},
	pauseTimer: function(){
		this.timer.countdown('pause')
	}
}
