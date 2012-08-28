/**
 *
 */
document.write('<style type="text/css"><!--/*--><![CDATA[/*><!--*/ .js { visibility: visible; } /*]]>*/--><'+'/style>');

$(document).ready(function(){

  var screenHeight = $('.page_wrap').height() - $('#banner').height() - $('#content_title').height() - $('#controls').height() - ($('#content').outerHeight()-$('#content').height()) - 10;
  var screenWidth = $('.page_wrap').width() - $('#sidebar').width() - 30;
  var videoWidth = $('#player').width();
  var videoHeight = $('#player').height();
  var videoAspect = videoWidth / videoHeight;
  var screenAspect = screenWidth / screenHeight;

  if ((screenHeight > videoHeight) && (screenWidth > videoWidth )) {
  	if (screenAspect > videoAspect) {
	  // screen is wider (relatively) than video, adjust using height
	  var screenWidth = Math.round(screenHeight * videoAspect);
	  $('#player').width(screenWidth).height(screenHeight);
	  $('#video').width(screenWidth);
  	} else {
	  // screen is taller (relatively) than video, adjust using width
	  var screenHeight = Math.round(screenWidth  / videoAspect);

	  $('#player').width(screenWidth).height(screenHeight);
	  $('#video').width(screenWidth);
  	}
  }
  
  $('#btn-chat').click(function(e){
	  var label = "Start typing your message...";
	  var chat = $("#fld-talk").value();
	  
	  if (chat && chat != label) {
		  $.getJSON('./ajax.php',{ talk: talk }, function(resp){
			  $('#chat_panel .chat_list').before(resp.html);
		  });
		  
	  }
  });
  
  $('#interactive_panel_toggle').click(function(e){
	  $('.interactive_panel').toggleClass('interactive_collapse')
                             .css('margin-top','-'+$('.interactive_panel').outerHeight()+'px');
  });
  
  $('.interactive_box').click(function(e){
	  var panel = '#'+this.id.replace(/box_/,'') + '_panel';
	  var isVisible = $(panel).hasClass('sidebar_panel_show');
	  var isCollapse = $('.interactive_panel').hasClass('interactive_collapse');
	  
	  if (isVisible) {
		  $(panel).removeClass('sidebar_panel_show');
	  } else {
		  $('.sidebar_panel_show').removeClass('sidebar_panel_show');
		  $(panel).addClass('sidebar_panel_show');
		  if (!isCollapse) { 
			  $('.interactive_panel').addClass('interactive_collapse');
		  }
	  }
  });
});  
