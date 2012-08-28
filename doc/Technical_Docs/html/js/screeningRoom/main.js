document.write('<style type="text/css"><!--/*--><![CDATA[/*><!--*/ .js { visibility: visible; } /*]]>*/--><'+'/style>');
var panelsPlaced = false;
var sidebarOffleft = '0px';
if(typeof console == 'undefined') 
{
	var console = {
		log: function(message){ return false; }
	};
}

//Queue used to control the order in which elements of the page are animated 
var actionQueue = new Array();
actionQueue['element'] = new Array();
actionQueue['callback'] = new Array();
actionQueue['callbackParameter'] = new Array();

//Flag -> are there any animations playing
var actionInProgress = false;
var blink = null;
var heightSet = false;
var lastDisplayedTalkerPerPanel = new Array();

//Height for flowplayer is being set (but not finished yet)
var settingHeight = false;

var fakedContentPersonalized = '',
    firstConstellationClick = true,
    firstFlowplayerResize = true,
    secondsCounter = new Date(),
    videoWidth = '',
    videoHeight = '',
    videoAspect = '',
    chatPanelVisible = false,
    flowplayerInitialized = false,
    chatSharesSidebar = false,
    playersSwaped = false;

//scrolls the panel to the last element with the class .main-message (used to auto scrolldown the chat/private chat panels) . Only scrolls if the last chat post is visible.
scrollToBottom = function(panel, pin) {
	try
	{
		var lastChatPostOffset = $(panel+' .main-message:last').offset();

		if($(panel).parents('.jScrollPaneContainer:first').length)
		{
			var panelOffset = $(panel).parents('.jScrollPaneContainer:first').offset();
			var panelHeight = $(panel).parents('.jScrollPaneContainer:first').height();
		}

		if($('.jScrollPaneTrack:visible').length )
		{	
			if(typeof pin == 'undefined' || ((lastChatPostOffset.top - ($(panel+' .main-message:last').height() + $('textarea.reply:first').height()))  <= ((panelOffset.top + panelHeight) - $('textarea.reply:first').height()) ) )
			{
				$(panel)[0].scrollTo( '.main-message:last', { axis:'y' } );
			}
		}
	}
	catch(err)
	{
	}
};

generateNewItem = function(fullName, text, idForNewPost, replyToId, panelOwnerId, appendWithoutAnimation, talkerLoginType, talkerId, action){
	/******** FILL OUT THE TEMPLATE, SO WE CAN ADD THE NEW CONTENT TO THE USERS CHAT BOX  WITHOUT WAITING FOR THE UPDATER TO RUN *********/

		var newChatContent = '';
		var extraClasses = '';
		if(!panelOwnerId || typeof panelOwnerId == 'undefined') //FOR THE MAIN CHAT PANEL
		{
			var idForPrivatePosts = '';
		}
		else
		{
			var idForPrivatePosts = 'private-';
		}
		shortName = fullName;
		
		if(!fullName )
		{
			fullName = ' ';
		}
		if(fullName.length > 20)
		{
			shortName = fullName.substr(0, 20)+'...';
		}

		newChatContent = chatContentTemplate;

		if(typeof panelOwnerId != 'undefined' && panelOwnerId != null && panelOwnerId.length)
		{
			var displayOnPanel = panelOwnerId;
		}
		else
		{
			var displayOnPanel = 0;
		}

		if(typeof lastDisplayedTalkerPerPanel[displayOnPanel] != 'undefined' && lastDisplayedTalkerPerPanel != null && lastDisplayedTalkerPerPanel[displayOnPanel] == talkerId)
		{
			newChatContent = newChatContent.replace("{insertChatPostOwnerTemplateHere}", "");
			newChatContent = newChatContent.replace("{insertShiftUpClassHere}", "shift-up");
			extraClasses = 'shift-right';
		}
		else
		{
			newChatContent = newChatContent.replace("{insertChatPostOwnerTemplateHere}", chatPostOwnerTemplate);
			newChatContent = newChatContent.replace("{insertCustomHeaderClassHere}", talkerLoginType);
		}
		lastDisplayedTalkerPerPanel[displayOnPanel] = talkerId;

		newChatContent = str_replace('{insertChatHere}', text, newChatContent);

		newChatContent = newChatContent.replace("{insertFullNameHere}", fullName);
		newChatContent = newChatContent.replace("{insertFullNameHere}", fullName);
		newChatContent = newChatContent.replace("{insertFullNameHere}", shortName);
		newChatContent = newChatContent.replace("{insertIdHere}", idForPrivatePosts+idForNewPost);
		if(fullName == templateFullName)
		{
			newChatContent = newChatContent.replace("{insertOwnPostCustomClassHere}", "me "+extraClasses);
		}
		else
		{
			newChatContent = newChatContent.replace("{insertOwnPostCustomClassHere}", extraClasses);
		}
		if(typeof replyToId == 'undefined' || !replyToId || replyToId == '0')
		{
			newChatContent = newChatContent.replace("{insertReplyFunctionsHere}", replyButtonTemplate);
			newChatContent = newChatContent.replace("{insertChatIdHere}", idForPrivatePosts+idForNewPost);
			newChatContent = newChatContent.replace("{insertChatIdHere}", idForPrivatePosts+idForNewPost);
			newChatContent = newChatContent.replace("{insertChatIdHere}", idForPrivatePosts+idForNewPost);
			//newChatContent = newChatContent.replace("{disableTextarea}", 'readonly="readonly"');
			newChatContent = newChatContent.replace("{insertReplyClassHere}", "main-message");
			newChatContent = newChatContent.replace("{insertHeaderHere}", chatPostHeader);
			newChatContent = newChatContent.replace("{insertFullNameHere}", shortName);
			newChatContent = newChatContent.replace("{insertFullNameHere}", fullName);
			newChatContent = newChatContent.replace("{insertFullNameHere}", fullName);
			newChatContent = newChatContent.replace("{insertRepliedIdHere}", 0);
			newChatContent = newChatContent.replace("{insertActionHere}", 'says');
			newChatPosts['reply_to'].unshift(' ');
			newChatPosts['is_reply'].unshift(0);
		}
		else
		{
			newChatContent = newChatContent.replace("{insertReplyFunctionsHere}", "");
			//newChatContent = newChatContent.replace("{disableTextarea}", 'readonly="readonly"');
			newChatContent = newChatContent.replace("{insertChatIdHere}", idForPrivatePosts+idForNewPost);
			newChatContent = newChatContent.replace("{insertChatIdHere}", idForPrivatePosts+idForNewPost);
			newChatContent = newChatContent.replace("{insertChatIdHere}", idForPrivatePosts+idForNewPost);
			newChatContent = newChatContent.replace("{insertReplyClassHere}", "posted-reply");
			newChatContent = newChatContent.replace("{insertRepliedIdHere}", replyToId);
			newChatContent = newChatContent.replace("{insertActionHere}", 'replies');
			newChatContent = newChatContent.replace("{insertHeaderHere}", replyPostHeader);
			newChatContent = newChatContent.replace("{insertFullNameHere}", shortName);
			newChatContent = newChatContent.replace("{insertFullNameHere}", fullName);
			newChatContent = newChatContent.replace("{insertFullNameHere}", shortName);
			newChatPosts['reply_to'].unshift(replyToId);
			newChatPosts['is_reply'].unshift(1);
		}
		
		newChatPosts['content'].unshift(newChatContent);
		
		//ADDING THE ELEMENT TO THE CHAT POSTS ANIMATION QUEUE
		if(!panelOwnerId || typeof panelOwnerId == 'undefined') //FOR THE MAIN CHAT PANEL
		{
			$('#aux-chat-2').html(newChatContent);
			newChatPosts['heights'].unshift($('div#aux-chat-2 div.new-chat-item').height());
			$('#aux-chat-2').html('');
			newChatPosts['owner_id'].unshift(0);
		}
		else
		{
			$('#aux_chat_measurement').html(newChatContent); //FOR A USER'S CHAT PANEL
			newChatPosts['heights'].unshift($('div#aux_chat_measurement div.new-chat-item').height());
			$('#aux_chat_measurement').html('');
			newChatPosts['owner_id'].unshift(panelOwnerId);
		}
		//START ANIMATING
		if(typeof appendWithoutAnimation == 'undefined')
		{
			startPushingElements(action);
		}
		else
		{
			startPushingElements(action);
		}

		if(typeof panelOwnerId != 'undefined' && panelOwnerId && !replyToId && panelOwnerId == userId)
		{
			//generateNewItem(fullName, text, idForNewPost, replyToId, null, true, talkerLoginType, talkerId);
		}
	/***********************************************************************************************************************************/	
};

postNewReply = function(textareaElement, replyToId){
	var newId = 0;
	var text = textareaElement.val();
	textareaElement.val('');
	if($('#private_panel').is(':visible'))
	{
		var panelOwnerId = $('#private_chat').attr('name');
	}
	else
	{
		var panelOwnerId = 0;
	}

	$.ajax({
		type: "POST",
		data: "post="+encodeURIComponent(text)+'&screeningId='+screeningId+'&chatId='+replyToId+'&panelOwner='+panelOwnerId,
		url: postChatUrl,
		success: function(idForNewPost){
			newId = idForNewPost;
			if(panelOwnerId)
			{
				$('#reply-textarea-private-'+replyToId).val('').hide();
			}
			else
			{
				$('#reply-textarea-'+replyToId).val('').hide();
			}
			generateNewItem(templateFullName, text, idForNewPost, replyToId, panelOwnerId, false, userLoginType, userId);

		}
	});	
	
	return newId;
};

updateServerTime = function(){
	serverTime.setMinutes(serverTime.getMinutes()+1);
};

bindEvents = function(){
	 $(document).unbind('click').bind('click',function(){
		 $('#sidebar .pop-up').hide();
	 });
	 $('.message-star').unbind('click').bind('click', function(){
		 var usernameForReply = $(this).attr('title').replace('Message ', '');
		
		 var panelOwnerId = $('#node_list a[title="'+usernameForReply+'"]').attr('name');
		 if(panelOwnerId)
		 {
		 	panelOwnerId = panelOwnerId.replace('constellation-', '');
			updatePanel = 'private_panel';
			$.ajax({
					url: individualChatPanelRefreshUrl,
					type: "GET",
					data: "id="+panelOwnerId+'&screeningId='+screeningId,
					success: function(html){
						$('.sidebar_panel_wrap_private').html(html);
						bindEvents();
						addActionToQueue($('#sidebar'), 'slidePanels', '#private_panel');
						addActionToQueue('', 'noCallback', '');
					}
			});
		 }
		 
		 return false;
	 });
	 $('.arrow-down').unbind('click').bind('click', function(event){
		 if($(this).next('.pop-up').is(':visible'))
		 {
			 $(this).next('.pop-up').hide();
		 }
		 else
		 {
			 $('.pop-up').hide();
			 $(this).next('.pop-up').toggle();
		 }
		 event.preventDefault();
		 event.stopPropagation();
		 
		 return false;
	 });
	  $('#node_list a').unbind('click').click(function(event){
		  if($(this).hasClass('active'))
		  {
			var panelOwnerId = $(this).attr('name');
			panelOwnerId = panelOwnerId.replace('constellation-', '');
			updatePanel = 'private_panel';
			$.ajax({
					url: individualChatPanelRefreshUrl,
					type: "GET",
					data: "id="+panelOwnerId+'&screeningId='+screeningId,
					success: function(html){
						$('.sidebar_panel_wrap_private').html(html);
						bindEvents();
						//addActionToQueue('', 'noCallback', '');
						addActionToQueue($('#sidebar'), 'slidePanels', '#private_panel');
						addActionToQueue('', 'noCallback', '');
					}
			});
		  }
		  else
		  {
			movePopup('#user_offline-popup', 1, 350);
			togglePopup('user_offline');
			setTimeout('togglePopup("user_offline")', 800);
		  }
		  event.preventDefault();	
		  return false;
	  });
	  
	$('.hide-chat').click(function(event){
		var leftValue = $('.page_wrap').width(); 

		$('#sidebar .pop-up').hide();
		$("#sidebar").animate({ left: leftValue+255 }, 1000, function(){ 
						addActionToQueue($('#sidebar'), 'noCallback', '');
						//$("#chat_panel").removeClass('sidebar_panel_show').hide();
						$('.sidebar_panel').removeClass('sidebar_panel_show');
						//$("#private_panel").removeClass('sidebar_panel_show').hide();
					
		}); 
		
		if($('#qanda_panel').is(':visible') && typeof eventWebcamStopped != 'undefined')
		{
			eventWebcamStopped();
		}
		
		updatePanel = '';
		
		if(playingFake)
		{
			$f('player').pause();
		}
		
		//Assume chats were read
		markAllChatsRead();
		chatPanelVisible = false;

		event.preventDefault();
		return false; 
	});
	
	$('.fld-talk').unbind('click').click(function(){chat($(this));});
	$('.fld-talk').unbind('focus').focus(function(){chat($(this));}); 
	
	$('div[name="reply-button"]').unbind('click').click(function(){
		var replyToId = $(this).attr('id');
		var isPrivatePanel = false;
		var replySelector = '';
		
		replyToId = replyToId.replace('reply-private-', '');
		if(replyToId != $(this).attr('id'))
		{
			isPrivatePanel = true;
			replySelector = 'private-';
		}
		else
		{
			replyToId = replyToId.replace('reply-', '');
		}
		
		var replyBody = $('#reply-textarea-'+replySelector+replyToId).val();

		if($('#reply-textarea-'+replySelector+replyToId).is(':visible') && replyBody.length)
		{
			postNewReply($('#reply-textarea-'+replySelector+replyToId), replyToId);
			$('#reply-textarea-'+replySelector+replyToId).hide(); 
			$(this).addClass('top-border');
		}
		else
		{
			$('#reply-textarea-'+replySelector+replyToId).slideDown('slow', function(event){ $(this).focus(); }); 
		//	$('#reply-textarea-'+replyToId).focus();
			$(this).removeClass('top-border');
		}
		
		$('#chat_list').jScrollPane();
		$('#private_chat').jScrollPane();
	});
	
	$('#fld-private').unbind('click').click(function(){chat($(this));});
	
	$('#fld-private').unbind('focus').focus(function(){chat($(this));});
	
	$('#btn-chat').unbind('click').click(function(){
		var text = $('#fld-talk').val();
		newId = postNewReply($('#fld-talk'), null);
		//$('#fld-talk').val('');

		return false;
	});
	$('#btn-private-chat').unbind('click').click(function(){
		var text = $('#fld-private').val();
		newId = postNewReply($('#fld-private'), null);
		//$('#fld-talk').val('');
		
		return false;
	});
	$('#fld-talk').unbind('keydown').keydown(function(e){
		if(e.keyCode == 13) {
			
			e.preventDefault();
			$('#btn-chat').click();
			
			return false;
		}
	});
	$('#fld-private').unbind('keydown').keydown(function(e){
		if(e.keyCode == 13) {
			
			e.preventDefault();
			$('#btn-private-chat').click();
			
			return false;
		}
	});
	$('textarea.reply').unbind('keydown').keydown(function(e){
		if(e.keyCode == 13) {
			var textareaId = $(this).attr('id');
			textareaId = textareaId.replace('reply-textarea-', '');
			e.preventDefault();
			$('#reply-'+textareaId).click();
			
			return false;
		}
	});
	$('#review-recommend').unbind('click').click(function(e){
		if($('#after-screening-popup').is(':visible'))
		{
			$('#after-screening-popup').hide();
		}
		else 
		{
			$('#after-screening-popup').show();
		}
	});
	
	$("#hide-popup").unbind('click').click(function(e){
		$('#after-screening-popup').hide();
	});
	
	// popup which appears after screening -> write a review
	$('#after-screening-popup #btn-write').unbind('click').click(function(e) {
		var reviewPanel = $('#after-screening-popup #screening-review');
		if($('#after-screening-popup #screening-review').is(':visible'))
		{
			reviewPanel.hide();
		}
		else
		{
			$('#after-screening-popup #recommendation').hide();
			reviewPanel.show();
		}
		
	});
	
	// popup which appears after screening -> send a recommendation
	$('#after-screening-popup #btn-recommend').unbind('click').click(function(e) {
		var reviewPanel = $('#after-screening-popup #recommendation');
		if($('#after-screening-popup #recommendation').is(':visible'))
		{
			reviewPanel.hide();
		}
		else
		{
			$('#after-screening-popup #screening-review').hide();
			reviewPanel.show();
		}
	});
	
	$('#post-review').unbind('click').click(function(e) {
		$.ajax({
			url: urlSaveReviewMessage,
			type: 'POST',
			data: $('#form-review').serialize(),
			success: function(e){
				$('#after-screening-popup #screening-review').hide();
				$('#after-screening-popup #screening-review .title').html("<p>EDIT YOUR REVIEW</p>");
				//bindEvents();
			}
		
		});
	return false;
	});
	
	$('#send-recommendation').unbind('click').click(function(e) {
		$('.recommendation_overlay').show();
		//*******Email Validation******
		var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
		var emails = new Array();
		var email_list = $.trim($('#email-list').val());
		
		emails = (email_list).split(',');
		var errors = false;
		
		for ( var i in emails )
		{
			if(pattern.test(emails[i]) == false){
				$('.error-panel').fadeIn('fast', function(){ setTimeout("$('.error-panel').fadeOut('fast')", 3000); });
				$(".errors").html("<p>Invalid email: "+emails[i]+".</p>");
				errors = true;
			}
		}
		//****//
		if(errors == false)
		{
			$.ajax ({
				url: urlSendRecommendation,
				type: 'POST',
				data: $('#form-emails').serialize(),
				success: function(e){
					$('.recommendation_overlay').hide();
					$('#email-list').val("");
					$('.error-panel').fadeIn('fast', function(){ setTimeout("$('.error-panel').fadeOut('fast')", 3000); });
					$(".errors").html("<p>Your recommendation email has been sent.</p>");
				}
				
			});
		}
		$('.recommendation_overlay').hide();
		return false;
	});
};

function chat(element){
	element.attr('value', '');
	element.unbind('click');
	element.unbind('focus');
};

function default_image() {
	//$('#constellation_button img').attr("src", imagesUrl + "btn-constellation.png");
	//$('#constellation_button img').attr("src", imagesUrl + "icon-custom.png");
	$('#constellation_button img').attr("src", imagesUrl + "icon-constellation.png");
};

addScrollPane = function(){
	$('.scroll-pane').jScrollPane();
};

changeChatListHeight = function(newHeight){
	$('#chat_list').animate({ left: '-=250' }, 1000);
};

resizeFlowplayer = function(){

	  var screenHeight = $('.page_wrap').height() - $('#banner').height() - $('#content_title').height() - $('#controls').height() - ($('#content').outerHeight()-$('#content').height()) - 10;
	  var sidebarOffLeft = $('#sidebar').css('left');
	  sidebarOffLeft = sidebarOffLeft.substr(0, sidebarOffLeft.length-2); 
	  var screenWidth = $('.page_wrap').width() - $('#sidebar').width();
	  screenWidth = screenWidth - 275;

	  var videoWidth = $('#player').width();
	  var videoHeight = $('#player').height();
	  var videoAspect = videoWidth / videoHeight;
	  var screenAspect = screenWidth / screenHeight;
	 
	  //for chat textarea 
	  var info = $('#fld-talk').attr('value');

	  if ((screenHeight > videoHeight) && (screenWidth > videoWidth )) {
	  	if (screenAspect > videoAspect) {
		  // screen is wider (relatively) than video, adjust using height
		  var screenWidth = Math.round(screenHeight * videoAspect);
		  $('#player').width(screenWidth).height(screenHeight);
		 // $('#video').width(screenWidth);
	  	} else {
		  // screen is taller (relatively) than video, adjust using width
		  var screenHeight = Math.round(screenWidth  / videoAspect);

		  $('#player').width(screenWidth).height(screenHeight);
		 // $('#video').width(screenWidth);
	  	}
	  }
	  if($('#arrow-toggle-panel').hasClass('active'))
	  {
		  $('#sidebar').height($('.page_wrap').height()-100);
		  $('#chat_list').height($('.page_wrap').height()-280).jScrollPane();
		  //$('#private_list').height($('.page_wrap').height()-290).jScrollPane();
		  $('#private_chat').height($('.page_wrap').height()-250).jScrollPane();
		  $('#screening_film_info').height($('.page_wrap').height()-280).jScrollPane();
		  $('.activity_log').height($('.page_wrap').height()-280).jScrollPane();
		  
	  }
	  else
	  {   
		  $('.activity_log').height($('.page_wrap').height()-(280+$('#interactive_panel').height())).jScrollPane(); 
  	      $('#screening_film_info').height($('.page_wrap').height()-(280+$('#interactive_panel').height())).jScrollPane();
		  $('#sidebar').height($('.page_wrap').height()-(100+$('#interactive_panel').height()));
		  $('#chat_list').height($('.page_wrap').height()-(280+$('#interactive_panel').height())).jScrollPane();
		  //$('#private_list').height($('.page_wrap').height()-290).jScrollPane();
		  $('#private_chat').height($('.page_wrap').height()-(250+$('#interactive_panel').height())).jScrollPane();

	  }
};

slidePanels = function(panel){
	
	$('#chat_panel div[name="not-processed"]').css({'margin-right' : 0, 'background-color' : '#323A3E', 'border' : 0, 'display' : 'block', 'height' : 'auto', 'opacity' : 1 }).attr('name', 'processed');
	var isVisible = $(panel).hasClass('sidebar_panel_show'),
	isCollapse = $('.interactive_panel').hasClass('interactive_collapse'),
	sideBarLocation = $('#sidebar').css('left'),
	visiblePanel = $('.sidebar_panel:visible:first'),
	leftValue = 0;
	sideBarLocation = sideBarLocation.substr(0, sideBarLocation.length - 2);

	  
	if(sideBarLocation > $('.page_wrap').width())
	{
		isVisible = false;
	}
	

	//$('#qanda_panel .choose-qanda').css({ 'height' : ($('#content').height() - $('#sidebar .sidebar-top').height() - $('#sidebar .sidebar-bottom').height() - $('#qanda_panel .top').height() - 400 /* height of the interactive panel + #liveplayer. */) });
	//$('#activitylog_panel .activity_log').css({ 'height' : '500' });
	//$('#filmmaker_panel .screening_film_info').css({ 'height' : ($('#content').height() - $('#sidebar .sidebar-top').height() - $('#sidebar .sidebar-bottom').height() - $('#filmmaker_panel .top').height()q /* height of the interactive panel + #liveplayer. */) });
	if (isVisible || visiblePanel.length) 
	{
		leftValue = $('.page_wrap').width();
		if($(panel).attr('id') == 'private_panel')
		{
			$('#private_chat').jScrollPane();
		}
		if(visiblePanel.attr('id') != $(panel).attr('id') || $(panel).attr('id') == 'private_panel')
		{
			$("#sidebar").animate({ left: leftValue+255 }, 1000, function(){ 
				$(visiblePanel).hide(0, function(){	
											   
											   if(visiblePanel.attr('id') != '#qanda_panel')
											   {
											   		$('#fld-talk').height(64);
											   		$('#chat_panel .hide-chat').show();
											   		$('#chat_panel').hide();
											   		chatSharesSidebar = false;
											   }
											   $(panel).show('fast', function(){
													var infoFilmHeight = $('#content').height() - $('#sidebar .sidebar-top').height() - $('#sidebar .sidebar-bottom').height() - $('#screening_film_info .top').height() - 289;
													
													if(panel == '#private_panel')
													{
														$('#private_chat').jScrollPane({ animate: true });
														scrollToBottom('#private_chat');
													}
													else if (panel == '#filmmaker_panel')
													{
														var screenHeight = $('.page_wrap').height() - ($('.private-chat-header').height()+$('.sidebar-top').height() + $('.top:visible:first').height()+$('#sidebar form:visible:first').height()+$('.reply-button').height()+$('.sidebar-bottom').height()+$('#interactive_panel').height()+25+74);
														$('#screening_film_info').jScrollPane({ animate: true });
														if(screenHeight > $('#screening_film_info').height())
												    	{
												    		if($('#screening_film_info').height()){
												    			$('#filmmaker_panel .jScrollPaneContainer').height($('#screening_film_info').height()+10);
												    		}
												    	}
												    	else
												    	{
												    		$('#filmmaker_panel .jScrollPaneContainer').height(screenHeight);
												    	}
														$('#screening_film_info').jScrollPane();
													}
													else if(panel == '#chat_panel')
													{
														$('#fld-talk').height(64);
														chatSharesSidebar = false;
														$('#chat_panel .hide-chat').show();
														$('#chat_list').jScrollPane({ animate: true });
														scrollToBottom('#chat_list');
													}
													else  if (panel == '#activitylog_panel')
													{
														
														$('.activity_log').jScrollPane({ animate: true });
													}
													else if (panel == '#qanda_panel')
													{
														chatSharesSidebar = true;
														$('#fld-talk').height(32);
														$('#chat_panel').show(0, function(){
															$('#chat_panel').addClass('sidebar_panel_show');
															$('#chat_panel .hide-chat').hide();
															$('#chat_list').jScrollPane({ animate: true });
														});
													} 
											   }).addClass('sidebar_panel_show');
											 
											   
											}).removeClass('sidebar_panel_show');
				
				if($(panel).attr('id') == 'private_panel')
				{
					if(firstConstellationClick)
					{
						firstConstellationClick = false;
					}
				}
			}).animate({ left: leftValue-255 }, 1000, function(){ actionInProgress = false; liveResizeFlowplayer(); });
		}
		else
		{
			$("#sidebar").animate({ left: leftValue+255 }, 1000, function(){ $(panel).hide('fast', function(){ 
					updatePanel = ''; 
					actionInProgress = false; 
					addActionToQueue('', 'noCallback', ''); 
					//$(this).css('display', 'none');
				}).removeClass('sidebar_panel_show'); 
			});
		}
	} 
	else 
	{
		if($(this).attr('id') == 'private_panel')
		{
			$('#private_chat').jScrollPane();
		}
		$(panel).show('fast', function(){ 
			updatePanel = $(panel).attr('id'); 
			actionInProgress = false; 
			addActionToQueue('', 'noCallback', ''); 
		});
		  
		$(panel).addClass('sidebar_panel_show');
		var leftValue = $('.page_wrap').width();
		$('#sidebar').animate({ left: leftValue-255 }, 1000, function(){ actionInProgress = false; liveResizeFlowplayer(); });
		if (!isCollapse) 
		{ 
			$('.interactive_box').addClass('interactive_collapse');
		}
	}
	
	/*liveResizeFlowplayer();*/
};

/************************* RESIZE FLOWPLAYER BASED ON FREE SPACE AVAILABLE ****************************/
liveResizeFlowplayer = function(){
	
	if(!actionInProgress)
	{	
		actionInProgress = true;
		element = actionQueue['element'].pop();
		action = actionQueue['callback'].pop();
		callbackParameter = actionQueue['callbackParameter'].pop();

		if(typeof action == 'undefined')
		{
			actionInProgress = false;
			return;
		}
		var availableWidth = $('.page_wrap').width();
		var availableHeight = $('.page_wrap').height();
		var sidebarOffleft = $('#sidebar').css('left');
		var videoScreenWidth = 0;
		
		if(typeof element == 'object')
		{
			var doCallback = true;
		}
		else
		{
			var doCallback = false;
		}
		sidebarOffleft = sidebarOffleft.replace('px', '');
		if((sidebarOffleft < availableWidth || (doCallback && element.attr('id') == 'sidebar' && (action == 'animate' || action == 'slidePanels'))) && !firstFlowplayerResize)
		{
			videoScreenWidth = availableWidth - $('#sidebar').width();
			availableWidth = availableWidth - (2 * $('#sidebar').width() + 10);
		}
		
		if(($('#interactive_panel').is(':visible') || (doCallback && element.attr('id') == 'interactive_panel' && action == 'slideDown')) && !firstFlowplayerResize)
		{	
			availableHeight = availableHeight - $('#interactive_panel').height();
		}

		availableHeight = availableHeight - 10;
		
		if(videoScreenWidth)
		{
			$('#video_screen').width(videoScreenWidth); 
		}
		else
		{
			$('#video_screen').width(availableWidth);
		}
		if(firstFlowplayerResize)
		{
			videoWidth = $('#player').width();
			videoHeight = $('#player').height();
			videoAspect = videoWidth / videoHeight;
		}
		var screenAspect = availableWidth / availableHeight;
		
		if(screenAspect > videoAspect) 
	  	{
		  // screen is wider (relatively) than video, adjust using height
		  var screenWidth = Math.round(availableHeight * videoAspect);
		  
		  $('#player').width(screenWidth).height(availableHeight);
		  //$('#video').width(availableWidth);
	  	}
	  	else 
	  	{
		  // screen is taller (relatively) than video, adjust using width
		  var screenHeight = Math.round(availableWidth  / videoAspect);
		  $('#player').width(availableWidth).height(screenHeight);
		 // $('#video').width(availableWidth);
	  	}
	    
		// jScrollPane
	    if(typeof screenHeight == 'undefined')
	    {
	    	chatHeight = $('.page_wrap').height() - ($('.sidebar-panel-wrap ul.choose-qanda').height()+$('.private-chat-header').height()+$('.sidebar-top').height() + $('.top:visible:first').height()+$('#sidebar form:visible:first').height()+$('.reply-button').height()+$('.sidebar-bottom').height()+$('#interactive_panel').height()+$('#qanda-actions').height()+25+74);
		    $('#sidebar').height(availableHeight);
	    	
	    	if(action == 'show')
	    	{
	    		chatHeight = chatHeight + 267; //bottom bar height 
	    	}		    
	    	
	    	$('#private_panel .jScrollPaneContainer').height(chatHeight);
	    	
	    	if((action != 'slidePanels' || callbackParameter != '#chat_panel') && (chatSharesSidebar || (action == 'slidePanels' && callbackParameter == '#qanda_panel') || ($('#chat_panel').is(':visible') && $('#qanda_panel').is(':visible'))))
	    	{
	    		$('#fld-talk').height(32);
	    		if(!$('.choose-qanda').length)
		    	{
		    		if(chatHeight > 350)
		    		{
		    			$('#chat_panel .jScrollPaneContainer').height(chatHeight - 310);/* qanda panel height */
		    		}
		    		else
		    		{
		    			$('#chat_panel .jScrollPaneContainer').height(40);
		    		}
		    	}
		    	else
		    	{
		    		if(chatHeight > 280)
		    		{
		    			$('#chat_panel .jScrollPaneContainer').height(chatHeight - 240);/* qanda panel height */
		    		}
		    		else
		    		{
		    			$('#chat_panel .jScrollPaneContainer').height(40);
		    		}
		    	}
	    	}
	    	else
	    	{
	    		$('#chat_panel .jScrollPaneContainer').height(chatHeight);
	    	}

	    	$('#activitylog_panel .jScrollPaneContainer').height(chatHeight + 40);
	    
	    	if(chatHeight > $('#screening_film_info').height())
	    	{
	    		
	    		if($('#screening_film_info').height())
	    		{
	    			$('#filmmaker_panel .jScrollPaneContainer').height($('#screening_film_info').height()+10);
	    		}
	    	}
	    	else
	    	{
	    		
	    		$('#filmmaker_panel .jScrollPaneContainer').height(chatHeight);
	    	} 
	    	//$('#qanda_panel .choose-qanda').height(availableHeight-$('#sidebar .sidebar-top').height() - $('#sidebar .sidebar-bottom').height() - $('#qanda_panel .top').height() - 270);
	    	
			$('#screening_film_info').jScrollPane();
		    $('#private_chat').jScrollPane();
    		$('#chat_list').jScrollPane();
    		$('.activity_log').jScrollPane();
   			scrollToBottom('#private_chat');
   			scrollToBottom('#chat_list');
	    }
	    else
	    {
	    	
	    	$('#sidebar').height(screenHeight);

	    	chatHeight = $('.page_wrap').height() - ($('.sidebar-panel-wrap ul.choose-qanda').height()+$('.private-chat-header').height()+$('.sidebar-top').height() + $('.top:visible:first').height()+$('.reply-button').height()+$('.sidebar-bottom').height()+$('#interactive_panel').height()+$('#qanda-actions').height()+25+74);
	    	if(action == 'show')
	    	{
	    		chatHeight = chatHeight + 267;//bottom bar height 
	    	}
	    	$('#private_panel .jScrollPaneContainer').height(chatHeight);
	    	$('#activitylog_panel .jScrollPaneContainer').height(chatHeight + 40);

	    	var filmInfoHeight = $('#filmmaker_panel #screening_film_info').height();
	    	
	    	if((action != 'slidePanels' || callbackParameter != '#chat_panel') && (chatSharesSidebar || (action == 'slidePanels' && callbackParameter == '#qanda_panel') || ($('#chat_panel').is(':visible') && $('#qanda_panel').is(':visible'))))
	    	{
				$('#fld-talk').height(32);
	    		if(!$('.choose-qanda').length)
		    	{
		    		if(chatHeight > 379)
		    		{
		    			$('#chat_panel .jScrollPaneContainer').height(chatHeight - 339);/* qanda panel height */
		    		}
		    		else
		    		{
		    			$('#chat_panel .jScrollPaneContainer').height(40);
		    		}
		    	}
		    	else
		    	{
		    		if(chatHeight > 370)
		    		{
		    			$('#chat_panel .jScrollPaneContainer').height(chatHeight - 339);/* qanda panel height */
		    		}
		    		else
		    		{
		    			$('#chat_panel .jScrollPaneContainer').height(40);
		    		}
		    	}
	    	}
	    	else
	    	{
	    		$('#chat_panel .jScrollPaneContainer').height(chatHeight);
	    	}
	    	
	    	if(chatHeight > $('#screening_film_info').height() )
	    	{
	    		if($('#screening_film_info').height()){
	    			$('#filmmaker_panel .jScrollPaneContainer').height($('#screening_film_info').height()+10);
	    		}
	    	}
	    	else
	    	{
	    		$('#filmmaker_panel .jScrollPaneContainer').height(chatHeight);
	    	}
	    	
	    	$('#private_chat').jScrollPane();
    		$('#chat_list').jScrollPane();
    		$('#screening_film_info').jScrollPane();
    		$('.activity_log').jScrollPane();
			scrollToBottom('#private_chat');
			scrollToBottom('#chat_list');
	    }
		
		firstFlowplayerResize = false;
		
		if(!panelsPlaced)
		{
			panelsPlaced = true;
			$('#placement-overlay').remove();
		}
		
		if(action != 'noCallback')
		{
			if(action == 'show')
			{
				element.show('fast', function(){ actionInProgress = false; liveResizeFlowplayer(); });
			}
			else if(action == 'slideDown')
			{
				element.slideDown('fast', function(){ actionInProgress = false; liveResizeFlowplayer(); });
			}
			else if(action == 'animate')
			{
				  var leftValue = $('.page_wrap').width();
				  element.animate({ left: leftValue-255 }, 500, function(){ actionInProgress = false; liveResizeFlowplayer(); });
				  $("#chat_panel").removeClass('sidebar_panel_show');
			}
			else if(action == 'slidePanels')
			{
				slidePanels(callbackParameter);
			}
			else if(action == 'slideUp')
			{
				element.slideUp('slow', function() {  actionInProgress = false; liveResizeFlowplayer(); });
			}
			
		}
		else
		{
			actionInProgress = false;
		}
	}
};
/***************************************************************************************************************/
addActionToQueue = function(element, callback, callbackParam){
	actionQueue['element'].push(element);
	actionQueue['callback'].push(callback);
	actionQueue['callbackParameter'].push(callbackParam);
	liveResizeFlowplayer();
};


$(document).ready(function(){
		
	$('#arrow-toggle-panel, arrow-toggle-area p').bind('click', function(e){
	    e.preventDefault();
		$(this).removeClass('active');
		addActionToQueue($('#interactive_panel'), 'slideDown', '');
		if(qandaBlinkFlag)
		{
			allowBlink = true;
			blinkQandaButton();
		}
		liveResizeFlowplayer();
	});
	
	$('.arrow-toggle-area').hover(
			function(e){
				$('#arrow-toggle-panel').addClass('enable');
			},
			function(e){
				$('#arrow-toggle-panel').removeClass('enable');
			}
	);
	//bind click event for facebook share
	$('#on-facebook').click(function(event){
		window.open($(this).attr('href'),'sharer','toolbar=0,status=0,width=626,height=436');
		
		return false;
	});

	$('#interactive_panel_toggle').click(function(e) {
	    if (!$('.interactive_panel').hasClass('interactive_collapse'))
	    {
	        /*$('.interactive_panel').addClass('interactive_buttons')
	                               .addClass('interactive_collapse')
	                               .css('margin-top','-'+$('.interactive_panel').outerHeight()+'px');*/
	    	
	    	$('.interactive_panel').removeClass('interactive_buttons').addClass('interactive_collapse').css('margin-top','-'+$('.interactive_panel').outerHeight()+'px');
	        
	    	if(qandaBlinkFlag)
	        {
	        	allowBlink = false;
	        	clearInterval(blink);
	        	$('#box_qanda').stop(true, true);
	        }
	    	if(screeningStarted || screeningEnded)
		    {
			    $('#review-recommend').css('display', 'inline-block');
		    }
		    else
		    {
			    $('#review-recommend').hide();
		    }
	        //$('.interactive_box').show(0, function(){ addActionToQueue('', 'noCallback', '');  });
	    	$('#buttons_holder').show(0, function(){ addActionToQueue('', 'noCallback', '');  $('#buttons_holder span').css( 'display' , 'inline-block' );	});
	    	
	        
	    }
	    else
	    {
	        if (!$('.interactive_panel').hasClass('interactive_buttons'))
	        {
	            $('.interactive_panel').addClass('interactive_buttons');
	        }
	        else
	        {
	        	$('.sidebar_panel_show').removeClass('sidebar_panel_show');
	            $('.interactive_panel').removeClass('interactive_buttons');
	        }
	        $('#arrow-toggle-panel').addClass('active');
	        $('#interactive_panel').slideUp('fast', function(){ addActionToQueue('', 'noCallback', ''); });
	    }
	    e.preventDefault();

	   // liveResizeFlowplayer();
	    
	    return false;
  });

  $('#how-to').click(function(e){
	  $('#screening-popup').hide();
	  $('#how-to-popup').show();
	   
  });
  
  $('#how-to-popup #hide-how-to').click(function(e){
	  $('#screening-popup').show();
	  $('#how-to-popup').hide();
  });
  
  $('#constellation_button').click(function(e){
	  
      e.preventDefault();
      if($('.interactive_panel').hasClass('interactive_collapse'))
	  {
		  allowBlink = false;
	      clearInterval(blink);
	      $('#box_qanda').stop(true, true);
	      
	      //$('#box_qanda').hide();
	      $('#review-recommend').hide();
	      //$('.interactive_box').hide('fast', function(){addActionToQueue('', 'noCallback', ''); /*liveResizeFlowplayer('', 'noCallback');*/ });
	      //$('.interactive_box').slideUp('fast', function(){addActionToQueue('', 'noCallback', ''); /*liveResizeFlowplayer('', 'noCallback');*/ });
	      $('#buttons_holder').hide('fast', function(){ addActionToQueue('', 'noCallback', ''); });
	  }
	  else
	  {
		  $('#box_qanda').css('display', 'inline-block');
		  if(qandaBlinkFlag)
		  {
			  allowBlink = true;
			  blinkQandaButton();
		  }
		 
		  //addActionToQueue($('.interactive_box'), 'show', '');
		  addActionToQueue($('#buttons_holder'), 'show', '');
		  
		  if(screeningStarted || screeningEnded)
		  {
			  $('#review-recommend').css('display', 'inline-block');
		  }
		  else
		  {
			  $('#review-recommend').hide();
		  }
		  //$('.interactive_box').show();
		  //$('.interactive_box').show(0, function(){ addActionToQueue('', 'noCallback', '');  });
		  $('#buttons_holder').show(0, function(){ addActionToQueue('', 'noCallback', '');  });
	  }
      
	  $('.interactive_panel').toggleClass('interactive_collapse').toggleClass('interactive_buttons').css('margin-top','-'+$('.interactive_panel').outerHeight()+'px');
	  
	  //liveResizeFlowplayer();
	  
	  return false;
  });
  
  $('.interactive_box').click(function(e){

	  var panel = '#'+this.id.replace(/box_/,'') + '_panel';
	  addActionToQueue($('#sidebar'), 'slidePanels', panel);
	  updatePanel = this.id.replace(/box_/,'')+'_panel';
	  if(this.id=='box_filmmaker')
	  {	
		  $('#screening_film_info').jScrollPane();
	  }
  });
  
  setInterval("updateServerTime()", 60000);
  
  $('#fld-talk').click(function(){chat($(this));});
  $('#fld-talk').focus(function(){chat($(this));}); 
 // $('#chat_list').css('left', '0px!important');
  $('#private_chat').css('left', '0px!important');
  //$('#screening_film_info').css('left', '0px!important');
  
  
  $('#constellation_button img').click(function(e) {
	  //$(this).attr("src",  imagesUrl + "btn-constellation-glow.png");
	  //$(this).attr("src",  imagesUrl + "icon-custom.png");
	  $(this).attr("src",  imagesUrl + "icon-constellation.png");
	  setTimeout('default_image()', 500);
  });
  
  //slidePanels($('#chat_panel'));
  
  bindEvents();
  
});

$(window).load(function(){

	/*Closes the interactive panel on window load
	 * if(!webcamFeedStatus)
	{
		$('#interactive_panel').slideUp('slow');
	}*/
	if(screeningEnded  && screeningType == 'public')
	{
		screeningFinished();
	}
	else if(screeningEnded )
	{
		var leftValue = $('.page_wrap').width();
		$('#sidebar').css('left', leftValue+255 );
		screeningClipFinished();
	}
	
	if(!screeningEnded)
	{
		$('#chat_list').jScrollPane().css('left', '');
		$('#chat_panel').hide();
	
		var leftValue = $('.page_wrap').width();
		$('#sidebar').css('left', leftValue+255 );
		
		$('#chat_panel').show(0, function(){
			updatePanel = 'chat_panel';
			addActionToQueue('', 'noCallback', '');
			addActionToQueue($('#sidebar'), 'animate', '');
			chatPanelVisible = true;
	
	        // force chat window to the most recent chat message
			scrollToBottom('#chat_list');
		});
	}
	$('.page_wrap').mouseleave(function(event){
		event.stopPropagation();

		if(event.pageX >= $('.page_wrap').width()-40 && $('.sidebar_wrap .sidebar_panel_show').length === 0) //Ensure no panel is currently displayed -- if a panel shown, block the event
		{
			if($('.sidebar_wrap .sidebar_panel:visible').length)
			{
				$('.sidebar_wrap .sidebar_panel:visible').hide(0, function(){
					$('#chat_panel').show(0, function(){
						$('#chat_panel div[name="not-processed"]').css({'margin-right' : 0, 'background-color' : '#323A3E', 'border' : 0, 'display' : 'block', 'height' : 'auto', 'opacity' : 1 }).attr('name', 'processed');
						updatePanel = 'chat_panel';
						addActionToQueue('', 'noCallback', '');
						addActionToQueue($('#sidebar'), 'animate', '');
						chatPanelVisible = true;
					});
				});
			}
			else
			{
				$('#chat_panel').show(0, function(){
					updatePanel = 'chat_panel';
					addActionToQueue('', 'noCallback', '');
					addActionToQueue($('#sidebar'), 'animate', '');
					chatPanelVisible = true;
				});
			}
		}
		if(event.pageY <= 5 && !pinPanels)
		{
			return true;
			//addActionToQueue($('#banner'), 'slideDown', '');
		}
		/*if(event.pageY >= $('.page_wrap').height()-10  && !pinPanels)
		{
			addActionToQueue($('#interactive_panel'), 'slideDown', '');
			updatePanel = 'chat';
			if(qandaBlinkFlag)
			{
				allowBlink = true;
				blinkQandaButton();
			}
		}*/
		
		return false;
	});

	$('#sidebar').mouseenter(function(event){ 
		
		event.stopPropagation();
		return false;
	});
	
	$('#sidebar').mouseleave(function(event){
			
		event.stopPropagation();
		return false;
	});
	
	$('#banner').mouseleave(function(event){ 
								if(!pinPanels) 
								{ 	
									$(this).slideUp('fast', function(){ addActionToQueue('', 'noCallback', ''); });
								}
							});
	/*$('#interactive_panel').mouseleave(function(event){ 
		
										if(!pinPanels) 
											{ 
												if(qandaBlinkFlag)
												{
													allowBlink = false;
											        clearInterval(blink);
											        $('#box_qanda').stop(true, true);
												}

											 	$(this).slideUp('fast', function(){ 
											 								addActionToQueue('', 'noCallback', ''); 
											 						});
											}
										});*/
	if(startScreeningOnLoad && !screeningEnded )
	{
		startFilmScreening();
	}
	addActionToQueue('', 'noCallback', '');
});
 
$(window).resize(function(){
	addActionToQueue('', 'noCallback', '');
});

