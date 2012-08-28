var lastUpdatedAtFromAjax = 0;
var updatePanel = '';
var qandaBlinkFlag = false;

var lastTimingInfoSend = 0;

var globalDateObject = new Date();
var globalCounter = 0;
var gatherStatisticsLimit = 100;
var currentTimingStackIndex = 0;
var lastTimingStackIndex = 0;

var newChatPosts = [];

newChatPosts['heights'] = [];
newChatPosts['content'] = [];
newChatPosts['reply_to'] = [];
newChatPosts['is_reply'] = [];
newChatPosts['owner_id'] = [];

var lastAddedElementId = 0,
	freeChatHeight = 0,
	processing = false,
	numChatsReceived = 0,
	numPrivateChatsReceived = 0;
var numChatsReceived = 0;
var numPrivateChatsReceived = 0;

var statisticObjects = new Array();
statisticObjects[0] = new Array();

var Statistic = function(){
	this.id = globalCounter;
	globalCounter++;

	this.ajaxStart = new Date().getTime();
	this.ajaxEnd = 0;
	this.domActions = new Array();
	addToStatistics(this);
};

var DomAction = function(){
	this.parentId = '';
	this.name = '';
	this.actionStart = new Date().getTime();
	this.actionEnd = 0;
};

addToStatistics = function(statistic) {
	var gatherInterval = gatherStatisticsTimeLimit;
	var randomIntervalPart = Math.random()*( 0.2*gatherInterval );
	gatherInterval = gatherInterval + randomIntervalPart;
	
	statisticObjects[currentTimingStackIndex][statistic.id] = statistic;
	
	if(statisticObjects[currentTimingStackIndex].length >= gatherStatisticsLimit && ( (new Date().getTime() - lastTimingInfoSend ) >= gatherInterval ) ) {
		lastTimingInfoSend = new Date().getTime();
		submitStatistics();
	}
};

addDomActionToStatistic = function(domAction)	{
	
	if(typeof domAction != 'undefined' && typeof statisticObjects[currentTimingStackIndex][domAction.parentId] != 'undefined')
	{
		statisticObjects[currentTimingStackIndex][domAction.parentId].domActions.push(domAction);
	}
}

submitStatistics = function() {
	
	statisticObjectsCopy = statisticObjects[currentTimingStackIndex];
	lastTimingStackIndex = currentTimingStackIndex;
	 
	currentTimingStackIndex++;
	
	statisticObjects[currentTimingStackIndex] = new Array();
	
	$.ajax({
		type: "POST",
		url: statisticsUrl,
		data: 'info='+$.toJSON(statisticObjectsCopy),
		success: function(data){
			statisticObjects[lastTimingStackIndex] = [];
		}
	});				

//console.log(statisticObjects);
};

animateNewItems = function(holderId) {
	$(holderId+' div[name="not-processed"]').animate({	'margin-right' : 0, 'background-color' : '#323A3E', 'border' : 0 }, 200, 
															function(){ 
																		processing = false; 
																		startPushingElements(); 
																	}).attr('name', 'processed').css('height', 'auto'); 
};

animateOpacity = function($animateThis, holderId) {
	var animationCompleted = false;

	$animateThis.animate({ opacity : 1 }, 200, function(){ if(!animationCompleted) { animationCompleted = true; animateNewItems(holderId); } });
};

addWithSlide = function($insert, newHeight, holderId) {
	var $animateThis = $insert;
	var animationCompleted = false;
	
	if(typeof $insert != 'undefined')
	{
		$insert.animate({ 'height' : newHeight }, 200, function(){
							
							//keep jScrollPane to the bottom of the panel
							//var autoScroll = $(holderId).data('jScrollPanePosition') == $(holderId).data('jScrollPaneMaxScroll');
							
							$(holderId).jScrollPane( { animateTo: true } );

							scrollToBottom(holderId, true); //function defined in main.js
							animateOpacity($animateThis, holderId);
		}).addClass('processed');
	}
	else
	{
		animationCompleted = true;
		startPushingElements();
	}
};

startPushingElements = function(action){
	if(!processing)
	{
		processing = true;
		var chatItemHolderId = '';
		var currentItemContent = newChatPosts['content'].shift(); 
		var currentItem = $(currentItemContent);
		var itemIsReply = newChatPosts['is_reply'].shift();
		var itemReplied = newChatPosts['reply_to'].shift();
		var itemOwnerId = newChatPosts['owner_id'].shift();
        var idForPrivatePosts = '';
		
		if(itemOwnerId)
		{
			chatItemHolderId = '#private_chat';
			idForPrivatePosts = 'private-';
		}
		else
		{
			chatItemHolderId = '#chat_list';
		}

		if(itemIsReply && itemIsReply != '0')
		{
			if($(chatItemHolderId+' div[reply="'+itemReplied+'"]:last').length)
			{
				if(typeof appendWithoutAnimation == 'undefined')
				{
					var $insert = currentItem.css({
				        height : 0,
				        opacity : 0,
				        display : 'none'
				      }).insertAfter(chatItemHolderId+' div[reply="'+itemReplied+'"]:last');
					if(typeof action != 'undefined')
					{
						action.actionEnd = new Date().getTime();
						addDomActionToStatistic(action);
					}  
				}
				else
				{
					var $insert = currentItem.css({ display : 'block' }).addClass('processed').attr('name', 'processed').css('height', 'auto').insertAfter(chatItemHolderId+' div[reply="'+itemReplied+'"]:last');
					if(typeof action != 'undefined')
					{
						action.actionEnd = new Date().getTime();
						addDomActionToStatistic(action);
					}  
				}
			}
			else if( $(chatItemHolderId+' div#chat-post-'+idForPrivatePosts+itemReplied).length )
			{
				if(typeof appendWithoutAnimation == 'undefined')
				{
					var $insert = currentItem.css({
				        height : 0,
				        opacity : 0,
				        display : 'none'
				      }).insertAfter(chatItemHolderId+' div#chat-post-'+idForPrivatePosts+itemReplied);
					if(typeof action != 'undefined')
					{
						action.actionEnd = new Date().getTime();
						addDomActionToStatistic(action);
					}  
				}
				else
				{
					var $insert = currentItem.css({ display : 'block' }).addClass('processed').attr('name', 'processed').css('height', 'auto').insertAfter(chatItemHolderId+' div#chat-post-'+idForPrivatePosts+itemReplied);
					if(typeof action != 'undefined')
					{
						action.actionEnd = new Date().getTime();
						addDomActionToStatistic(action);
					}  
				}
			}
		}
		else
		{
			if(typeof appendWithoutAnimation == 'undefined')
			{
				var $insert = currentItem.css({
			        height : 0,
			        opacity : 0,
			        display : 'none'
			      }).appendTo(chatItemHolderId);
			    																		
				if(typeof action != 'undefined')
				{
					action.actionEnd = new Date().getTime();
					addDomActionToStatistic(action);
				}  
			}
			else
			{
				var $insert = currentItem.css({ display : 'block' }).addClass('processed').attr('name', 'processed').css('height', 'auto').appendTo(chatItemHolderId);
				if(typeof action != 'undefined')
				{
					action.actionEnd = new Date().getTime();
					addDomActionToStatistic(action);
				}  
			}
		}
		var newHeight = newChatPosts['heights'].shift();

		if(typeof newHeight != 'undefined' && newHeight && typeof appendWithoutAnimation == 'undefined')
		{
			addWithSlide($insert, parseInt(newHeight), chatItemHolderId, action);
		}
		else
		{
			bindEvents();
			processing = false;
		}
	}
};

//retrieve new elements to be added in the page(chat  posts / activity log posts/ etc.)
chatRefresh = function(lastUpdate){

	if(getRequestRunning && (getRequestRunning < (new Date().getTime() - 600)))
	{
		return false;
	}
	getRequestRunning = new Date().getTime();
	
	if(updatePanel == 'private_panel')
	{
		var updatePanelParameter = $('#private_chat').attr('name');
	}
	else
	{
		var updatePanelParameter = updatePanel;
	}
	
	var timingInfo = new Statistic();
	
	$('.chat_list').attr({ scrollTop: $(".chat_list").attr("scrollHeight") });
	
	$.ajax({
		method: "GET",
		url: chatUrl,
		data: 'screeningId='+screeningId+'&updatePanel='+updatePanelParameter+'&lastUpdatedAt='+lastUpdatedAtFromAjax,
		success: function(response){
			getRequestRunning = false;			
			timingInfo.ajaxEnd = new Date().getTime();

			addToStatistics(timingInfo);			
			lastUpdatedAtFromAjax = lastUpdate;
			
			if(response.length)
			{	
				response = eval('(' + response + ')');
				
				/********************* update chat ************************/
				var first = true;

				for(var i in response['chat']){
          
          if(first){
						var action = new DomAction;
						action.name = 'add_chat';
						action.parentId = timingInfo.id;
						
						//console.log(action);
					}
					
					//Truncate Longer Names
          if(response['chat'][i]['name'] && response['chat'][i]['name'].length >= 20)
					{
						var origName = response['chat'][i]['name'];
						response['chat'][i]['name'] = response['chat'][i]['name'].substr(0, 20);
					}
					
					newChatContent = generateNewItem(response['chat'][i]['name'], response['chat'][i]['chat'], response['chat'][i]['id'], response['chat'][i]['reply_to'], response['chat'][i]['owner_id'], false, response['chat'][i]['custom_header_class'], response['chat'][i]['talker_id'], action, response['chat'][i]['created_at']);
						
          if(screeningStarted)
					{
						numChatsReceived++;
						updateChatPopupWindow($f().getPlugin('popupWindow'));
					}
				}

				for(i in response['private']){
					if(response['private'][i]['name'] && response['private'][i]['name'].length >= 20)
					{
						var origName = response['private'][i]['name'];
						response['private'][i]['name'] = response['private'][i]['name'].substr(0, 20);
					}
					
					newChatContent = generateNewItem(response['private'][i]['name'], response['private'][i]['chat'], response['private'][i]['id'], response['private'][i]['reply_to'], response['private'][i]['owner_id'], false, response['private'][i]['custom_header_class'], response['private'][i]['talker_id'], false);
					if(screeningStarted)
					{
						numPrivateChatsReceived++;
						updateChatPopupWindow($f().getPlugin('popupWindow'));
					}
				}

			}
		}
	});
};

//retrieve new elements to be added in the page(chat  posts / activity log posts/ etc.)
ajaxRefresh = function(lastUpdate){

	if(getRequestRunning && (getRequestRunning < (new Date().getTime() - 600)))
	{
		return false;
	}
	getRequestRunning = new Date().getTime();
	
	if(updatePanel == 'private_panel')
	{
		var updatePanelParameter = $('#private_chat').attr('name');
	}
	else
	{
		var updatePanelParameter = updatePanel;
	}
	
	var timingInfo = new Statistic();
	
	$('.chat_list').attr({ scrollTop: $(".chat_list").attr("scrollHeight") });
	
	$.ajax({
		method: "GET",
		url: updaterUrl,
		data: 'screeningId='+screeningId+'&updatePanel='+updatePanelParameter+'&lastUpdatedAt='+lastUpdatedAtFromAjax,
		success: function(response){
			getRequestRunning = false;			
			timingInfo.ajaxEnd = new Date().getTime();

			addToStatistics(timingInfo);			
			lastUpdatedAtFromAjax = lastUpdate;
			
			if(response.length)
			{	
				response = eval('(' + response + ')');
				
				/***************** Swap the Flowplayer url if the screening started ************************/
				if(response['screening_started'] && !screeningStarted)
				{
					var action = new DomAction;
					action.name = 'start_film_screening';
					action.parentId = timingInfo.id;

					startFilmScreening(action); //function located in viewer.js/host.js
				}
				/*******************************************************************************************/
				/***************** Updated the number of connected users ***********************************/
				if(response['count_online_users'])
				{
					$('#online-seats').html(response['count_online_users']);
				}
				/*******************************************************************************************/
				/***************** Initialize the Webcam Feed *********************************************/
				//if(response['webcam'] == 1 && !webcamFeedInitialized && !qandaStarted)
				if(typeof response['webcam'] != 'undefined' && response['webcam'] == 1 && !webcamFeedInitialized)
				{
					var action = new DomAction;
					action.name = 'initialize_webcam_feed_player';
					
					initializeWebcamFeedPlayer(action);

					var action = new DomAction;
					action.name = 'start_qanda_viewer';

					startQandaViewer(action);
					
					//startQanda();
					if(!$('#qanda_panel').is(':visible'))
					{
						allowBlink = true;
						addActionToQueue($('#interactive_panel'), 'slideDown', '');
						if(typeof blinkQandaButton != 'undefined')
						{
							blinkQandaButton();
						}
					}
					webcamFeedInitialized = true;
					qandaBlinkFlag = true;
				}
				else if(typeof response['webcam'] != 'undefined' && response['webcam'] == 0 && !webcamFeedInitialized)
				{
					/*$f('liveplayer').unload();*/

					if (typeof blink != 'undefined') {
						clearInterval(blink);
					}
					allowBlink = false;
					$('#box_qanda').stop(true, true);
					//$('#webcam-feed').hide();
					webcamFeedInitialized = false;
					qandaBlinkFlag = false;
				}
				if(typeof response['webcam'] != 'undefined')
				{
					var action = new DomAction;
					action.name = 'toggle_webcam_feed_placeholder';

					toggleWebcamFeedPlaceholder(response['webcam'], action);
				}
				/******************************************************************************************/
				
				/***************** Swap Constellation Overlay if new seats joined *************************/	
				if(response['constellation'].length)
				{
					rebind = true;

					var action = new DomAction;
					action.name = 'refresh_constellation';

					$('#interactive_panel').fadeOut('slow', function(){
						$('#visualization').replaceWith(response['constellation']);
					}).fadeIn('slow', function(){ bindEvents(); });
					
					action.actionEnd = new Date().getTime();
					addDomActionToStatistic(action);  
				}
				/*******************************************************************************************/
				
				/********************* update activity logs ************************/
				/*for(i in response['activity_log']){
					$('.activity_log').prepend('<li><span class="timestamp">'+response['activity_log'][i]['time']+'</span> '+response['activity_log'][i]['message']+'</li>');
				}*/
				/*********************************************************************/
				
				/********************* update chat ************************/
				var first = true;

				for(var i in response['chat']){

					if(first){
						var action = new DomAction;
						action.name = 'add_chat';
						action.parentId = timingInfo.id;
						
						//console.log(action);
					}
					if(response['chat'][i]['name'] && response['chat'][i]['name'].length >= 20)
					{
						var origName = response['chat'][i]['name'];
						response['chat'][i]['name'] = response['chat'][i]['name'].substr(0, 20);
					}
					
					newChatContent = generateNewItem(response['chat'][i]['name'], response['chat'][i]['chat'], response['chat'][i]['id'], response['chat'][i]['reply_to'], response['chat'][i]['owner_id'], false, response['chat'][i]['custom_header_class'], response['chat'][i]['talker_id'], action);
					if(screeningStarted)
					{
						numChatsReceived++;
						updateChatPopupWindow($f().getPlugin('popupWindow'));
					}
				}

				for(i in response['private']){
					if(response['private'][i]['name'] && response['private'][i]['name'].length >= 20)
					{
						var origName = response['private'][i]['name'];
						response['private'][i]['name'] = response['private'][i]['name'].substr(0, 20);
					}
					
					newChatContent = generateNewItem(response['private'][i]['name'], response['private'][i]['chat'], response['private'][i]['id'], response['private'][i]['reply_to'], response['private'][i]['owner_id'], false, response['private'][i]['custom_header_class'], response['private'][i]['talker_id']);
					if(screeningStarted)
					{
						numPrivateChatsReceived++;
						updateChatPopupWindow($f().getPlugin('popupWindow'));
					}
				}

				startPushingElements();

				/*******************************************************************************************/
				
				/******************************* Light Up / Down Constellation Stars ***********************/
					var lightUpIdsString = '';
					var lightDownIdsString = '';
					var activateNamesString = '';
					var deactivateNamesString = '';
					
					var action = new DomAction;
					action.name = 'refresh_stars_status';

					for(i in response['stars']['light_up']){
						lightUpIdsString = lightUpIdsString + '#hidden-star-'+response['stars']['light_up'][i]+', ';
						activateNamesString = activateNamesString + '#user-'+response['stars']['light_up'][i]+', ';
					}
					
					for(i in response['stars']['light_down']){
						lightDownIdsString = lightDownIdsString + '#hidden-star-'+response['stars']['light_down'][i]+', ';
						deactivateNamesString = deactivateNamesString + '#user-'+response['stars']['light_down'][i]+', ';
					}
					if(lightUpIdsString.length)
					{
						lightUpIdsString = lightUpIdsString.substr(0, lightUpIdsString.length-2); 	
						activateNamesString = activateNamesString.substr(0, activateNamesString.length-2);
						
						$(lightUpIdsString).fadeIn('slow');
						$(activateNamesString).removeClass('inactive');
						$(activateNamesString).addClass('active');
					}
					
					if(lightDownIdsString.length)
					{
						lightDownIdsString = lightDownIdsString.substr(0, lightDownIdsString.length-2);
						deactivateNamesString = deactivateNamesString.substr(0, deactivateNamesString.length-2);
						
						$(lightDownIdsString).fadeOut('slow');
						$(deactivateNamesString).removeClass('active');
						$(deactivateNamesString).addClass('inactive');
					}
					
					action.actionEnd = new Date().getTime();
					addDomActionToStatistic(action);  
				/*******************************************************************************************/
				
				/************************ update Q&A ********************************************/
				var action = new DomAction;
				action.name = 'refresh_qanda_overlay';

				if(!response['qanda'].length)
				{
					$('#qanda-overlay').hide();	
				}
				else
				{
					if($('div[name="fake-player-holder"]').length != 0 && qandaStarted)
					{
						$('#qanda-overlay').show();
					}
				}
				if($('#qanda-overlay').html() != response['qanda'])
				{
					$('#qanda-overlay').html(response['qanda']);	
						//$f().getPlugin('qandaPopup').setHtml(response['qanda']);
				}
				if(response['qanda'].length)
				{
					if (playersSwaped && flowplayerInitialized && $f().getPlugin('qandaPopup')) {
						updateQandAPopup($f().getPlugin('qandaPopup'), response['qanda']);
					}
				}
				action.actionEnd = new Date().getTime();
				addDomActionToStatistic(action);  
				
				var action = new DomAction;
				action.name = 'refresh_stars_status';

				var rebind = false;
				for(i in response['new_qanda']){
					rebind = true;
					$('.choose-qanda').append('<li><label for="new-qanda-'+response['new_qanda'][i]['id']+'" '+(response['new_qanda'][i]['answered'] == 1 ? 'class="answered"' : '')+'>'+response['new_qanda'][i]['full_name']+': '+response['new_qanda'][i]['body']+'</label><a title="Dismiss Question" id="dismiss-qanda-'+response['new_qanda'][i]['id']+'" class="dismiss"></a></li>');
					$(".choose-qanda").attr({ scrollTop: $(".choose-qanda").attr("scrollHeight") });
				}
				
				action.actionEnd = new Date().getTime();
				addDomActionToStatistic(action);  
				/********************************************************************************/
				
				/***************************** streaming server is live *************************/
				if(response['streaming_server_url'].length)
				{
					var action = new DomAction;
					action.name = 'load_webcam_flash';

					flashVars.server = response['streaming_server_url'];
//					startFilmScreening();
					loadWebcamFlash(action);
				}
				/********************************************************************************/
				
				/***************************** the screening and the q&a session finished *******/
				if(response['screening_finished'] && screeningEnded)
				{
					var action = new DomAction;
					action.name = 'load_webcam_flash';
					
					screeningFinished(action);
				}
				/********************************************************************************/
				if(rebind)
				{
					bindHostEvents();
					bindEvents();
				}
				/**********************************************************************************/
			}
		}
	});
};

updateContent = function(){
	
	if(headRequestRunning && (headRequestRunning < (new Date().getTime() - 600)))
	{
		return false;
	}

	headRequestRunning = new Date().getTime();		
	
	var goWithGet= false,
	    visiblePanel = '',
	    panelNames = [ 'chat_panel', 'activitylog_panel', 'qanda_panel'],
	    updatePanelNumber = Math.floor(Math.random() * 11); // Generate a random number form 0 to 10

	//Set an update panel to randomly update, 10% q and a & activitylog with 80% update to chat
	switch (updatePanelNumber) {
		case 3:
			visiblePanel = 'qanda';
			break;
		
		/*
		case 7:
			visiblePanel = 'activitylog';
			break;
		*/
		
		default:
			visiblePanel = 'chat';
			break;
	}
	updatePanel = visiblePanel;

	if($('#private_panel').is(':visible'))
	{
		visiblePanel = $('#private_chat').attr('name');
		updatePanel = visiblePanel;
	}
	
	$.head(staticUpdater+"?screeningId="+screeningId+"&lastUpdatedAt="+lastUpdatedAtFromAjax+"&updatePanel="+visiblePanel+"&visibleStars="+$('.hidden-star:visible').length,{},
			function(headers) {
					$.each(headers,function(key,header){ 
						headRequestRunning = false;
						if(key == "X-Go-With-Get")
						{ 
							//ajaxRefresh(header);
							chatRefresh(header);
						}
						if(key == "X-Enable-Review")
						{
							$('#after-screening-popup').show();
						}
						if(key == "X-Screening-Started" && !screeningEnded)
						{
							screeningEnded = true;
							screeningClipFinished();
						}

					});
			});
};

var updatePanelNumber = 0;

$(document).ready(function(){
	setInterval( "updateContent()", refreshInterval);
});
