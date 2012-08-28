var allowBlink = true;
var screeningStarted = false;
var qandaStarted = false;
var firstPlay = true;
var webcamFeedInitialized = false;
var currentQanda = '';

bindHostEvents = function() {
	return false;
};

toggleWebcamFeedPlaceholder = function(webcamFeedStatus, action){
	if(webcamFeedStatus == 0)
	{
		if(!screeningEnded)
		{
			$('#webcam-feed').hide();
			$('#webcam-feed-placeholder').show();
		}
		else if(screeningEnded)
		{
			$('#player').hide();
			$('#player-placeholder').show();
		}
	}
	else
	{
		if(!screeningEnded)
		{
			$('#webcam-feed').show();
			$('#webcam-feed-placeholder').hide();
		}
		else
		{
			$('#player').show();
			$('#player-placeholder').hide();
		}
	}
	
	if(typeof action != 'undefined')  {
		action.actionEnd = new Date().getTime();
		addDomActionToStatistic(action);  
	}
}

swapPlayers = function(){
	if(screeningEnded)
	{
		$('#webcam-feed-placeholder').hide();
		
		playersSwaped = true;
		currentQanda = $('#qanda-overlay').html();
		if(currentQanda == null)
		{
			currentQanda = '';
		}
		$('#player').html($('#webcam-feed').html());
		$('#webcam-feed').remove();
		$('#qanda-overlay').remove();
		$f("player", { src: flowplayerPath, wmode: "transparent" } , {
	            clip: {
	            	autoPlay: true,
	            	mute: false,
	                url: flashVars.stream,
	                live: true,
	                provider: 'rtmp',
					onBeforePause: function() {
	                  /*  if (qaMuted) {
	                             this.unmute();
	                    } else {
	                             this.mute();
	                    }*/
	                    return false;
	                }
	            },
				plugins: {
					qandaPopup: qandaContentWindow,
					controls: commonControls,
	                rtmp: {
	                	url: flowplayerRtmpPath,
	                    netConnectionUrl: flashVars.server
	                }
				},
	            key: flowplayerCommercialCode,
				cxMuted: false,
	         //   onMute : function() { qaMuted = true; },
	          //  onUnmute : function() { qaMuted = false; },
	            onLoad: function(){
					updateQandAPopup(this.getPlugin('qandaPopup'), currentQanda);
	            },
	            onBeforeFullscreen: function() { return false; },
				onError: function(errorCode, errorMessage) { 
					this.unload(); 
				}
		});	
		flowplayerInitialized = true;
		liveResizeFlowplayer();
	}
};

// makes the bottom bar Q&A button blink, to mark the start of a new Q&A session. Stops blinking when the user clicks the Q&A button
blinkQandaButton = function(){
	blink = setInterval(function(){ 
							if(!allowBlink || $('#visualization').is(':visible'))
							{
								clearInterval(blink);
								
								if($('#visualization').is(':visible'))
								{
									$('#box_qanda').hide();
								}
								else
								{
									$('#box_qanda').show();
								}
									
								return;
							}
							if(allowBlink)
							{
								$('#box_qanda').fadeOut(500);
								if(allowBlink)
								{
									$('#box_qanda').fadeIn(500);
								}
								else
								{
									clearInterval(blink);
									$('#box_qanda').show();
								}
							}
							else
							{
								clearInterval(blink);
								$('#box_qanda').show();
							}
						}, 500);
};

updateQandAPopup = function(qanda, html) {
	try {
		qanda.setHtml(createElement('p', 'class="qanda-small-player"', html));
		if (html.length) {
			qanda.animate({opacity: 0.9}, 1000);	
		} else {
			qanda.animate({opacity: 0.01}, 1000);
		}
	}
	catch(err){
		
	}
};

startQandaViewer = function(action) {
    startQanda(action);
};

//changes the Flowplayer clip source from the trailer file to the qanda file (the screening ended and the Q&A started)
startQanda = function(action){
	qandaStarted = true;
	
	$f("webcam-feed", flowplayerPath, {
		clip: {
			url: flashVars.stream,
			live: true,
			mute: false,
			provider: 'rtmp',
			onBeforePause: function() { 
			/*	if (qaMuted) {
					 this.unmute();
				} else {
					 this.mute();
				}*/
				return false;
			}
		},
	
		plugins: {
			controls: null,
	
			rtmp: {
				url: flowplayerRtmpURL,
				netConnectionUrl: flashVars.server
			}
		},
		key: flowplayerCommercialCode,
		cxMuted: false,
		/*onMute : function() { qaMuted = true; },
		onUnmute : function() { qaMuted = false; },*/
		onBeforeFullscreen : function() { return false; },
		onError: function(errorCode, errorMessage) { 
			this.unload(); 
		}
	});
	webcamFeedInitialized = true;
	
	action.actionEnd = new Date().getTime();
	addDomActionToStatistic(action);  
};

screeningFinished = function(action){
	if(screeningEnded)
	{
		try {
			$('#webcam-feed').remove();
			$('#player').remove();
			$('#review-recommend').css('display', 'inline-block');
		}
		catch(err)
		{
			
		}
		
		$('#screening-ended-holder').show();
		$('#after-screening-popup').show();
		
		action.actionEnd = new Date().getTime();
		addDomActionToStatistic(action);  
	}
};

screeningClipFinished = function() {
	if(screeningType == 'public_hosted')
	{
		setTimeout(swapPlayers,2000);
		screeningEnded = true;
	}
	else {
		screeningEnded = true;
		screeningFinished();
	}
		
};

//starts playing the movie in the main Flowplayer
startFilmScreening = function(action){
	pluginsObj = {controls: commonControls,  popupWindow: chatContentWindow, qandaPopup: qandaContentWindow };
	clipObj = commonClipEvents;
	canvasObj = commonCanvasObj;
	if(typeof useStreaming != 'undefined' && useStreaming)
	{
        pluginsObj.controls.scrubber = false;
        clipObj.live = true;
        clipObj.autoBuffering = false;
        clipObj.autoPlay = true;
        clipObj.provider = 'rtmp';
        clipObj.url = streamingFile;
        clipObj.mute = false;
        clipObj.onBeforePause = function() {
        /*  if (this.cxMuted) {
            this.unmute();
          } else {
            this.mute();
          }*/
          return false;
        };

		clipObj.onStart = function() {
			this.unmute();
		};

		clipObj.onLastSecond = function() {
		};

		pluginsObj.rtmp = {
                            url: flowplayerRtmpPath,
                            netConnectionUrl: flashVars.server
                      };

    
		$f("player",
		    { 
				src: flowplayerPath,
				wmode: "transparent"
		    },
		    {
                    /*onMute: function() { this.cxMuted = true; },
                    onUnmute: function() { this.cxMuted = false; },*/
					onBeforeFullscreen: commonPlayerEvents.onBeforeFullscreen,
					onFullscreenExit: commonPlayerEvents.onFullscreenExit,
					onLoad: commonPlayerEvents.onLoad,
					onLastSecond: function() { 
							if(screeningEnded)
							{
								screeningClipFinished();
							} 
					},
			    	plugins: pluginsObj,
			    	clip: clipObj,
			    	key: flowplayerCommercialCode,
			    	width: $('#player').width(),
					play: { opacity: 0 },
					canvas:  canvasObj, 
					cxMuted: false
		     }
		);
	}
	else 
	{
        pluginsObj.controls.scrubber = false;
        clipObj.autoBuffering = false;
        clipObj.autoPlay = true;
        clipObj.url = progressiveDownloadFakeFile;
        clipObj.onBeforePause = function() {
          if (this.cxMuted) {
            this.unmute();
          } else {
            this.mute();
          }
          return false;
        };

		clipObj.onStart = function() {
		};

		clipObj.onLastSecond = function() {
		};
    
		$f("player",
		    { 
				src: flowplayerPath,
				wmode: "transparent"
		    },
		    {
                    onMute: function() { this.cxMuted = true; },
                    onUnmute: function() { this.cxMuted = false; },
					onBeforeFullscreen: commonPlayerEvents.onBeforeFullscreen,
					onFullscreenExit: commonPlayerEvents.onFullscreenExit,
					onLoad: commonPlayerEvents.onLoad,
					onLastSecond: function() { 
							if(screeningEnded)
							{
								screeningClipFinished();
							} 
					},
			    	plugins: pluginsObj,
			    	clip: clipObj,
			    	key: flowplayerCommercialCode,
			    	width: $('#player').width(),
					onFinish: function(){
						$('#controls').hide();
						$('#player-overlay').remove();
	
						if($('div[name="fake-player-holder"]').length)
						{
							var qandaContent = $('#qanda-overlay').html();
							$('#place-qanda-here').html('<div style="display: '+qandaContent.length ? "block" : "none"+';" id="qanda-overlay">'+qandaContent+'</div>');
							$('#qanda-overlay').remove();
							$f("liveplayer").unload();
							$('div[name="fake-player-holder"]').remove();
						}
						else
						{
							$('#qanda-overlay').show();
						}
					},
					play: { opacity: 0 },
					canvas:  canvasObj, 
					cxMuted: false
		     }
		);
	}	
	
	screeningStarted = true;
	$('#review-recommend').css('display', 'inline-block');
	$('#screening-overlay').hide();
	$('#screening-popup').hide();
	overlayVisible = false;

//	$f('player').play();

	flowplayerInitialized = true;
	
	try{
		action.actionEnd = new Date().getTime();
	}
	catch(err) {};
	
	addDomActionToStatistic(action);
	
	if(screeningEnded)
	{
		screeningClipFinished();
	}

};

bindViewerEvents = function(){
	$('#btn-qanda').click(function(event){
		if(qandasLeft > 0)
		{
			var text = $('#fld-qanda').val();
			$('#fld-qanda').val('');
			$('.qanda-submitted').fadeIn(500);
			$.ajax({
				type: "POST",
				data: "post="+text+'&screeningId='+screeningId,
				url: postQandaUrl,
				success: function(response){

				}
			});		
			setTimeout(function(){ $('.qanda-submitted').fadeOut(500); }, 3000 );
		}
		qandasLeft = qandasLeft - 1;
		if(qandasLeft == 1)
		{
			$('#qandaNo').text('You have ' + qandasLeft + ' question remaining.');
		}else {
			$('#qandaNo').text('You have ' + qandasLeft + ' questions remaining.');
		}

		if(qandasLeft <= 0)
		{
			$('.form-qanda').remove();
			$('#btn-qanda').remove();
			//$('#qanda_panel .sidebar_panel_wrap').append('<p> You may post a maximum of 5 questions.</p>');
		}
		return false;
	});
	$('#fld-qanda').keydown(function(e){
		if(e.keyCode == 13) {
			
			e.preventDefault();
			$('#btn-qanda').click();
			
			return false;
		}
	});
	$('#box_qanda').click(function(event){
		clearInterval(blink);
		allowBlink = false;
		qandaBlinkFlag = false;
		$('#box_qanda').stop(true, true);
		$('#box_qanda').show();

	});
   /* $('#constellation_button').click(function(e){
	      e.preventDefault();
			
	      clearInterval(blink);
		  allowBlink = false;
	      $('#box_qanda').stop(true, true);
    });*/
};

// Initializes the Q&A sidebar Flowplayer
initializeWebcamFeedPlayer = function(action){
	var qandaPlayerId = 'webcam-feed';
	
	if(!$('#webcam-feed').length)
	{
		qandaPlayerId = 'player';
	}
	
	$('#'+qandaPlayerId).show();
	$f(qandaPlayerId, flowplayerPath, {
            clip: {
                url: flashVars.stream,
                live: true,
                provider: 'rtmp',
				onBeforePause: function() {
                    if (qaMuted) {
                             this.unmute();
                    } else {
                             this.mute();
                    }
                    return false;
                }
            },
			plugins: {
				controls: null,
                rtmp: {
                	url: flowplayerRtmpPath,
                    netConnectionUrl: flashVars.server
                }
			},
            key: flowplayerCommercialCode,
			cxMuted: false,
            onMute : function() { qaMuted = true; },
            onUnmute : function() { qaMuted = false; },	
            onBeforeFullscreen: function() { return false; },
			onError: function(errorCode, errorMessage) { 
				this.unload(); 
			}
	});
	webcamFeedInitialized = true;
	
	action.actionEnd = new Date().getTime();
	addDomActionToStatistic(action);  
}
// for host view, we do nothing
loadWebcamFlash = function() {
};

$(window).load(function(){

	bindViewerEvents();

	qaMuted = false;

 /* $('.interactive_box').click(function(e){
	  var panel = '#'+this.id.replace(/box_/,'') + '_panel';
	  if(panel == '#qanda_panel')
	  {
	  	try{
	  		if($('#qanda_panel').is(':visible'))
	  		{
	  			$f("player").unmute();
	  		}
			else
			{
	  			$f("player").mute();
			}
	  	}
	  	catch(err)
	  	{
	  		
	  	}
	  }
  });*/
});


