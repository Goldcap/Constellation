var playingFake = false,
	firstPlay = true,
	canStart = false,
	pinPanels = false,
	ignoreQandaPanelVisibility = false,
	clipObj = '';

var webcamFeedInitialized = false;

if(typeof flowplayerPath == 'undefined')
{
	flowplayerPath = '/flash/flowplayer-3.2.4.swf';
	flowplayerRtmpPath = '/flash/flowplayer.rtmp-3.2.3.swf';
	flowplayerControlsPath = '/flash/flowplayer.controls-3.2.2.swf';
}


function createElement(tag, tagMeta, data) {
	return ("<" + tag.toLowerCase() + " " + tagMeta + ">" + data + "</" + tag.toLowerCase() + ">");
}

function hideChatWindow(length) {
	if (typeof $f().getPlugin('popupWindow') != 'undefined' && $f().getPlugin('popupWindow')) {
		setTimeout("$f().getPlugin('popupWindow').animate({opacity: 0.01}, (length) ? length : 4000)", 2500);
	}
}

function markAllChatsRead() {
	numChatsReceived = 0;
	numPrivateChatsReceived = 0;
	if(flowplayerInitialized)
	{
		updateChatPopupWindow($f().getPlugin('popupWindow'));
	}
}

function updateChatPopupWindow(chatPopup) {
	var htmlData = "",
		spanStyle = 'class="number"',
		paraStyle = 'class="message"';
	
	if (numChatsReceived || numPrivateChatsReceived) {
		try
		{
			if (chatPopup.getPlayer().isFullscreen()) {
				chatPopup.animate({opacity: .9}, 'fast');
			}
			else {
				chatPopup.hide();
			}
		}
		catch(err)
		{
			
		}
		htmlData += createElement('p', paraStyle, 'You have:');
		if (numPrivateChatsReceived) {
			htmlData += createElement('p', paraStyle, (numPrivateChatsReceived > 1) ? createElement('span', spanStyle, numPrivateChatsReceived) + ' new messages' : 
												  createElement('span', spanStyle, '1') + ' new message');
		}
		if (numChatsReceived) {
			htmlData += createElement('p', paraStyle, (numChatsReceived > 1) ? createElement('span', spanStyle, numChatsReceived) + ' unread chats' : 
											   createElement('span', spanStyle, '1') + ' unread chat');
		}
		try
		{
			chatPopup.setHtml(htmlData);
		}
		catch(err)
		{
			
		}
		hideChatWindow(8000);
	} else {
		try
		{
			chatPopup.setHtml('');
			chatPopup.hide();
		}
		catch(err)
		{
		}
	}
}

var commonClipEvents = {
	onStart: function() { 
		//alert("On Start");
	},
	onBegin: function() {
		$('#video .play-button').fadeOut("slow");
	},
	onResume: function() {
		$('#video .play-button').fadeOut("slow");
	},
	onPause: function() {
		$('#video .play-button').fadeIn("slow");
	},
	onFinish : function() {
		//$('#video .play-button').fadeIn("slow");
		//alert("Clip finished - load webcam");
	},
	onBeforeSeek : function() {
		return false; //Disable Users from selecting time
	}
};

var commonControls = {
        url: flowplayerControlsPath,
        all: false,
        fullscreen: true,
        scrubber: true,
        volume: true,
        mute: true,

        autoHide: {
                enabled: true,
                fullscreenOnly: false,
                hideDelay: 750,
                hideDuration: 200
        },
        height:'36px',
        width: '30%',
        left: '85%',
        backgroundColor: 'transparent',
        backgroundGradient: 'none',
        top: '-28px',
        buttonColor: '#214670',
        buttonOverColor: '#50AADD',
        tooltips: {
                buttons: true
        }
};

var commonCanvasObj = {
		background: '#000000',
		backgroundGradient: 'none'
};

var commonPlayerEvents = {
		onBeforeFullscreen : function() {
			markAllChatsRead();
			var chatWindow = $f().getPlugin("popupWindow");
			chatWindow.animate({opacity: 0.01}, 0);
			chatWindow.show();	
		},
		onFullscreenExit : function() {
			var chatWindow = $f().getPlugin("popupWindow");
			chatWindow.hide();
		},
		onLoad : function() {
			this.getPlugin('popupWindow').animate({opacity: 0.01}, 0);
			if(this.getPlugin('qandaPopup')) {
				this.getPlugin('qandaPopup').animate({opacity: 0.01}, 0);	
			}
		}
	};

var chatContentWindow = {
	width: 130,
	bottom: '5px',
	left: '65%',
	height: 60,
	url: 'flowplayer.content-3.2.0.swf',
	onClick: function() {
		$('#chat_list').focus().show();
		if (!chatPanelVisible) {
			chatPanelVisible = true;
			addActionToQueue($('#sidebar'), 'slidePanels', '#chat_panel'); 
			markAllChatsRead();	
		}
		//Event triggered twice, so block second
		if ($f().isFullscreen()) {
			$f().toggleFullscreen();
		}
	},
	onMouseOut: function() {
		if (this.getPlayer().isFullscreen()) {
			hideChatWindow(8000);
		}
	},
	onMouseOver: function() {
		if (this.getPlayer().isFullscreen() && (numChatsReceived || numPrivateChatsReceived)) {
			this.animate({opacity: .9}, 1000);	
		}
	},
	style: {
		".number" : { 
			color: '#FF6600',
			fontSize: 16,
			marginRight: 4,
			fontFamily: 'futura,"Lucida Grande","bitstream vera sans","trebuchet ms",verdana,arial'
		},
		".message" : {
			color: '#FFFFFF',	
			fontSize: 12,
			fontWeight: 'bold',
			marginLeft: 5,
			fontFamily: 'futura,"Lucida Grande","bitstream vera sans","trebuchet ms",verdana,arial'
		}	
	}
};

var qandaContentWindow = {
	width: '80%',
	top: 10,
	left: '50.0%',
	height: 50,
	url: '/flash/flowplayer.content-3.2.0.swf',
	onClick: function() {
		addActionToQueue($('#sidebar'), 'slidePanels', '#qanda_panel');
		//Event triggered twice, so block second
		if ($f().isFullscreen()) {
			$f().toggleFullscreen();
		}
	},
	style: {
		".qanda-small-player" : {
			color: '#FFFFFF',	
			fontSize: 14,
			fontWeight: 'bold',
			marginLeft: 5,
			textAlign: 'center',
			fontFamily: 'futura,"Lucida Grande","bitstream vera sans","trebuchet ms",verdana,arial'
		}	
	}
};

$(window).load(function(){
	var playButton = $('#video .play-button');

	if(typeof useStreaming != 'undefined' && useStreaming)
	{
		pluginsObj = {
			controls: commonControls,
				
			rtmp: {    
				url: flowplayerRtmpPath,    
				netConnectionUrl: streamingUrl
			}				
		};
		clipObj = commonClipEvents;
		clipObj.provider = 'rtmp';
        clipObj.live = true;
        clipObj.autoBuffering = false;
		clipObj.autoPlay = true;
        clipObj.onBeforePause = function() {
          this.stop();
          return false;
        };
        clipObj.onBeforeResume = function() {
          this.play();
          return false;
        };
		canvasObj = commonCanvasObj;	
	}
	else if (typeof progressiveDownloadFile != 'undefined')
	{
		pluginsObj = { controls: commonControls };
		clipObj = commonClipEvents;
		clipObj.autoPlay = true;
		clipObj.autoBuffering = true;
		clipObj.scaling = 'fit';
		clipObj.url = progressiveDownloadFakeFile;
		canvasObj = commonCanvasObj;	
		
	}
	playButton.click(function(){
		if ($f('player').isPaused()) 
		{
			$f('player').resume();
		}
		else
		{
			$f('player').play();
		}
	});

	$('.interactive_box div').click(function(){
		if(playingFake)
		{
			if( $(this).parent('div:first').attr('id') != 'box_qanda' || $('#qanda_panel').is(':visible'))
			{
				$f('player').pause();
			}
			else
			{
				ignoreQandaPanelVisibility = true;
				$f('player').play();
			}
		}
		
   		return true;
	});
	$('#player, #liveplayer').click(function(e){
		e.preventDefault();
		e.stopPropagation();
		
		return false;
	});
});

