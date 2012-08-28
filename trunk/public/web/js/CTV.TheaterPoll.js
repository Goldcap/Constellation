var CTV = CTV || {};
CTV.TheaterPoll = function(options, domNode) {
	this.options = $.extend({
			/* If not specified, look in selector's innerHTML before defaulting to zero. */
		percentage : 50,
		scale: 100,
		limit: true,
		minimum: 0,
		maximum: 100,
		suffix: ' %',
		animate:true,
		constantBuffer : Math.PI*0.165,
		constantFactor: Math.PI / 100 * 0.67
	}, options);
	this.domNode = $(domNode);
	this.init();
}


CTV.TheaterPoll.prototype = {
	init: function(){
		this.meter = $('#poll-meter', this.domNode);
		this.pollYes = $('#poll-yes', this.domNode).bind('click', _.throttle(_.bind(this.onPollYesClick,this), 400));
		this.pollNo = $('#poll-no', this.domNode).bind('click', _.throttle(_.bind(this.onPollNoClick,this), 400));
		this.currentPercentage = null;
		this.percentage = 50;
		this.increment = 0;
		this.currentPoll = 0;
		this.updateCanvas();

	}, 
	updateCanvas: function(){
		this.meter.jqcanvas ( _.bind(this.canvasRender, this), { verifySize: false, customClassName: '' } );
	},
	canvasRender: function ( canvas, width, height ) {
		
		this.ctx = canvas.getContext ( "2d" );

		this.ctx.lineWidth = 2;
		this.ctx.strokeStyle = "rgb(204,0,0)";
		/* point of origin for drawing AND canvas rotation (lines up with middle of the black circle on the image) */
		this.ctx.translate( 130, 150 );	
		this.ctx.save(); //remember linewidth, strokestyle, and translate

		this.animate();
		this.update();

	},
	animate: function(){
		if(this.currentPercentage == null){
			this.currentPercentage = this.percentage;
			this.ctx.restore(); //reset ctx.rotate to properly draw clearRect
			this.ctx.save();	//remember this default state again
			this.ctx.clearRect( -130, -150, 300, 300 ); //erase the canvas	
			/* rotate based on percentage. */
			this.ctx.rotate( this.currentPercentage * this.options.constantFactor +  this.options.constantBuffer);
			/* draw the needle */
			this.ctx.beginPath();
			this.ctx.moveTo( -100, 0 );
			this.ctx.lineTo( 10,0 );
			this.ctx.stroke();
			this.animateTimout = setTimeout(_.bind(this.animate, this),20);
		} else if ( this.currentPercentage != this.percentage ) {

			this.ctx.restore(); //reset ctx.rotate to properly draw clearRect
			this.ctx.save();	//remember this default state again
			this.ctx.clearRect( -130, -150, 300, 300 ); //erase the canvas	
			/* rotate based on percentage. */
			this.ctx.rotate( this.currentPercentage * this.options.constantFactor +  this.options.constantBuffer);
			/* draw the needle */
			this.ctx.beginPath();
			this.ctx.moveTo( -100, 0 );
			this.ctx.lineTo( 10,0 );
			this.ctx.stroke();

			this.increment = ( this.currentPercentage < this.percentage ) ? 0.2 : -0.2;
			this.currentPercentage =  this.currentPercentage + this.increment;
			this.animateTimout = setTimeout(_.bind(this.animate, this),20);
		} else {
			this.animateTimout = setTimeout(_.bind(this.animate, this),500);
		}
	},
	setPercentage: function(value){
		this.percentage = value;
	},
	update: function(){

		$.ajax({
			cache: false,
			url: '/services/poll/update?room='+this.options.room,
			success: _.bind(this.onUpdateSuccess, this),
			complete: _.bind(this.onUpdateComplete, this)
		})
	},
	onUpdateSuccess: function(response){
		if(response.meta.status == '200'){
			if(response.meta.question != '0'){
				this.currentPoll = parseInt(response.meta.question);
				$(this).trigger('showPoll');
				this.percentage = response.meta.count;
			} else {
				this.currentPercentage = null;
				$(this).trigger('hidePoll');
			}
		}
	},
	onUpdateComplete: function(){
		setTimeout(_.bind(this.update,this), 1000);
	},
	pollStart: function(){
		this.currentPoll++;
		$.ajax({
			cache: false,
			url: '/services/poll/post?t=pollstart&room=' + this.options.room + '&q='+this.currentPoll +'&mdt=1'
		});
	}, 
	pollStop: function(){
		$.ajax({
			cache: false,
			url: '/services/poll/post?t=pollstop&room=' + this.options.room +'&q=' + this.currentPoll +'&mdt=1'
		});
	},
	onPollYesClick: function(){
		$.ajax({
			cache: false,
			url: '/services/poll/post?room=' + this.options.room + '&a=1'
		});
	},
	onPollNoClick: function(){
		$.ajax({
			cache: false,
			url: '/services/poll/post?room=' + this.options.room + '&a=0'
		});
	}
}


/*

/services/poll/

*/