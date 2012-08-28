var CTV = CTV || {};
CTV.Trailer = function(options){
	this.options = $.extend({
		triggers: '.trailer-click'	      
    }, options);
    this.init();

 
}

CTV.Trailer.prototype = {
	// template: '<div id="dialog" class="pops dialog"></div>',
	init: function(){
		this.triggers = $(this.options.triggers).bind('click', _.bind(this.embed, this));
	},

	embed: function(){
		Dialog.open({body:'<div class="trailer-container"><div id="trailer"></div></div>', klass:'trailer'})

		var flashvars = {
		    file: Film.stream_url.replace('rtmp://cp113558.edgefcs.net/ondemand/','').replace('?','%3F'),
	        'image':  null,
	        streamer: 'rtmp://cp113558.edgefcs.net/ondemand'+'%3F'+Film.stream_url.split('?')[1],
	        'skin':   '/flash/glow/glow.zip',
	        'autostart': true,
			height: 300
	    };
	    		
	    var params = {
	        allowFullScreen: 'true',
	        allowScriptAccess: 'always',
	        wmode: 'opaque',
			bgcolor:"#000000",
			height: 300
	    };
	    var attributes = {
	          id: trailer.elementId,
	          name: trailer.elementId
	    };
	      swfobject.embedSWF('/flash/mediaplayer-5.7-licensed/player.swf', 'trailer', '100%', '100%', '9.0.0', '/flash/expressInstall.swf', flashvars, params, attributes, _.bind(this.onLoaded,this));	
	},
	onLoaded: function(e){
		this.play(jwplayer('trailer'));
	},
	play: function(){
	
	},
	stop: function(){
		
	}
}
