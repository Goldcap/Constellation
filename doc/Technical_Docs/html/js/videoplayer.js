$(document).ready(function(){
	
	var controlsObj = { 
			buttonColor: '#587EA4', 
			durationColor: '#587EA4'
	};
	if(typeof flowplayerStreamingUrl != 'undefined')
	{
		 var pluginsObj = {
                 rtmp: {
                     url: flowplayerRtmpPath,
                     netConnectionUrl: flowplayerStreamingUrl
                 }
	 	};
		var clipObj = {
					 provider: 'rtmp',
					 url: movie,
                     live: true,
			         autoPlay: false, 
			         autoBuffering: false,
			         onStart: function() { 
			         },
			         onBegin: function() {
						playButton.fadeOut("slow");
					 },
			         onResume: function() {
					 	playButton.fadeOut("slow");
                        this.play();
			         },
			         onPause: function() {
                        this.stop();
					 	//playButton.fadeIn("slow");
			         },
			         onFinish : function() {
			        	playButton.fadeIn("slow");
			         }
		};
	}
	else
	{
		var pluginsObj = { controls: controlsObj };

		var clipObj = {
				 url: movie,
		         autoPlay: true, 
		         autoBuffering: true,
		         onStart: function() { 
		         },
		         onBegin: function() {
					playButton.fadeOut("slow");
				 },
		         onResume: function() {
				 	playButton.fadeOut("slow");
		         },
		         onPause: function() {
				 	//playButton.fadeIn("slow");
		         },
		         onFinish : function() {
		        	playButton.fadeIn("slow");
		         }
		};
	}

	if(typeof flowplayerCommercialCode == 'undefined')
	{
		flowplayerCommercialCode = '';
	}
	
	flowplayer ( containerId, 
			{ src: flowplayerPath, wmode: 'transparent' }, 
			{
				clip: clipObj,
				plugins: pluginsObj,
                log: { level : 'debug' , filter: 'org.flowplayer.rtmp.*' },
				key: flowplayerCommercialCode
			} 
	);
});
