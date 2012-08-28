<script type="text/javascript"> 
	// flag to mark that a screening started
	var startScreeningOnLoad = false;
	/////////////////////////////////////////////////////
	// flag to mark an overlay is visible and the trailer should not play
	var overlayVisible = true;
	//////////////////////////////////////////////////////////////////////////
	
	//movie related files locations
	var trailerFilePath = 'http://c2.cdn.constellation.tv/uploads/screeningResources/4/trailerFile/lottery-trailer.trailer.mp4'; 
	//var movieFilePath = '/screeningRoom.php/main/xsendFile/screeningKey/ieTFNqei7Fu4hjV/seatKey/Lpg0OW';
	var movieFilePath = 'mp4:Test0';
	var qandaFilePath = 'http://movies.thelotteryfilm.com/movies/Cory Booker.mov';
	////////////////////////////////////////////////////////////
	
	var rateMovieUrl = '/services/film/rate';
	var getPanelUrl = '/services/user/profile';
	var updaterUrl = '/services/chat/status';
	var chatUrl = '/services/chat/status';
	var screeningId = '697';
	var seatKey = 'Lpg0OW';
	var seatId = '2128';
	var refreshInterval = '6000';
	var postChatUrl = '/services/chat/post';
	var streamingUrl = '';
	var streamingFile = "mp4:Test1";
	var progressiveDownloadFakeFile = 'http://movies.thelotteryfilm.com/movies/Cory Booker.mov';
	var useStreaming = true;
	var staticUpdater = "/services/updater/status";
	var flashVars = {
            server: "",
            stream: 'ieTFNqei7Fu4hjV',
		    videoX: 30,
		    camWidth: 300,
		    camHeight: 226,
		    camFPS: 12
	    };
	var flashParams = {
		wmode: 'transparent'
	};
		
	var postQandaUrl = '/services/film/qanda';
	var qandasLeft = '4';
	var webcamFeedStatus = 0;
	var templateFullName = "<?php echo $user_fullname?>";
	var templateShortName = "<?php echo $user_username?>";
	var chatContentTemplate = '<div id="chat-post-{insertIdHere}" reply="{insertRepliedIdHere}" name="not-processed" class="new-chat-item processed {insertReplyClassHere} {insertShiftUpClassHere}">'+
									'<div class="image-name {insertOwnPostCustomClassHere}" title="{insertFullNameHere}">'+
									'<p class="chat-post-body" title="{insertFullNameHere} {insertActionHere}: {insertChatHere}">'+
									'{insertChatPostOwnerTemplateHere}'+
									'{insertChatHere} {insertDateHere}</p>'+
									'{insertReplyFunctionsHere}'+
									'</div>'+
								'</div>';
	var chatPostOwnerTemplate = '<span class="login-type-{insertCustomHeaderClassHere}"><a title="{insertFullNameHere}" class="message-star" href="#">{insertFullNameHere}</a>: </span>';
	var	replyButtonTemplate = '';
	var replyPostHeader = '';
	var chatPostHeader = '<span><img src="/images//white-dot.png" width="9" height="9" />{insertFullNameHere}<span>';
 
	var serverTime = new Date("<?php echo formatDate(null,'js');?>");
	//var serverTime = new Date("December 09, 2010 16:29:55");
	var flowplayerPath = "/flash/flowplayer.commercial-3.2.4.swf";
	var flowplayerRtmpPath = "/flash/flowplayer.rtmp-3.2.3.swf";
	var flowplayerRtmpURL = "/flash/flowplayer.rtmp-3.2.3.swf";
	var flowplayerControlsPath = "";
	var fakeStreamingFile = "mp4:lottery.mp4";
	var flowplayerCommercialCode = "#$99f8ac2b53358df32f3";
	var contactUrl = "/services/online";
	var contactInterval = "20000";
	var individualChatPanelRefreshUrl = "/services/chat/individual";
	var userId = "<?php echo $user_id;?>";
	var userLoginType = "3";
	var debugFlag = false;
	var webcamFeedAvailable = false;
	var startDate = '+30605';
	var screeningType = 'public_hosted';
	var statisticsUrl = '/services/film/timing';
	var gatherStatisticsLimit = '100';
	var gatherStatisticsTimeLimit = 300000;
	var screeningEnded = false;
	var getRequestRunning = false;
	var headRequestRunning = false;
</script> 
