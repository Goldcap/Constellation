var permissionDialogComplete = false;
var muteMessageComplete = false;
var chatheight_short = "240px";
var chatheight_tall = "350px";

var boxheight = "450px";
  
function toggleDiv( id ) {
  
  $('.killer').fadeOut(100);
  
  if($("#"+id).css('display')=='none'){ //if menu isn't visible 
      //console.log("#"+id+" is hidden");
      
      if (id == "hostchat_panel") {
        if (typeof chat != undefined) {
          chat.getRooms();
        }
      }
      
      if (id == "qanda_panel") {
      	//console.log("Fading In QandA");
      	if (chat != undefined) {
					chat.mode = "short";
					chat.resize;
				}
				$("#"+id).fadeIn(100);
				showQaPanel();
      } else if (id == "chat_panel") {
        $("#qanda_panel").css("top","0px");
				$("#"+id).fadeIn(100);
        $("#video_stream").css("margin-right","340px");
        colorme.boxSwitch();
			} else {
        $("#"+id).css('top',0);
        //$("#"+id).css('bottom',boxheight);
        //$("#inbox").css("height",chatheight_short);
        $("#"+id).fadeIn(100);
      }
			
			if(chat != undefined) {
				chat.resize();
			}
	} else { //if quick menu is visible 
      //console.log("#"+id+" is showing");
      if (id == "qanda_panel") {
      	$("#"+id).fadeOut(100);
				if (chat != undefined) {
					chat.mode = "tall";
					chat.resize;
				}
        hideQaPanel();
      //} else if ((id == "chat_panel") && ($("#qanda_panel").css("display") == "block")) {
      } else if (id == "chat_panel") {
        $("#qanda_panel").css("bottom","0px");
        $("#"+id).fadeOut(100).queue(function() {
        	chat.resize();
        	$("#"+id).clearQueue();
        });
        $("#video_stream").css("margin-right","0px");
        //console.log("Close Chat");
        colorme.boxSwitch();
        $(".show_chat").show();
      } else {
        $("#"+id).fadeOut(100);
       // $("#inbox").css("height",chatheight_tall);
      }
			
			if(chat != undefined) {
			  chat.resize();
			}
  }

}

function toggleConstellation() {
  //console.log($("#toolbar").css("height"));
  if ($(".crash_file").css("display") == "none") {
    //console.log("showing constellation");
    $(".crash_file").css("display","block");
  } else {
    $(".crash_file").css("display","none");
  }
}

function notifyFlashPermissionDialogComplete(statusStr) {
	//console.log("notifyFlashPermissionDialogComplete: " + statusStr);
	permissionDialogComplete = true;
	//showQaWindow();
}

function hideQaPanel() {
  
  if (qanda != undefined) {
    qanda.qanda_showing = false;
  }
     
  if (typeof videoplayer != undefined) {
    if ($("#has_video").length > 0) {
      if ($("#is_host").length > 0) {
        if (videoplayer) {
          videoplayer.hideHostCam();
				}
      } else {
				if (videoplayer) {
          videoplayer.hideLiveViewer();
			  }
      }
		}
  }
}

function hideQaWindow() {
  //console.log("Toolbar Hiding QA Window");
  $("#q_and_a_control").fadeOut(100);
}

function showQaPanel() {
 
  if (qanda != undefined) {
    qanda.qanda_showing = true;
  }
  
  if ($("#has_video").length > 0) {
	//console.log("QandA Has Video");
  if (typeof videoplayer != undefined) {
		if ($("#is_host").length > 0) {
			videoplayer.showHostCam();
			videoplayer.mute();
		  if (! muteMessageComplete) {
         //error.showError("error","", "While you are in Q+A mode, your movie volume has been muted to prevent those attending the Q+A from hearing a distorting echo. Don't worry - everyone else can still hear you and the movie.",7000);
	       muteMessageComplete = true;
      }
    	//Due to the permissions dialog requirement
		} else {
		
			videoplayer.showLiveViewer();
		}
	}}
	
  if ($("#is_host").length > 0) {
    //qanda.scroller.reinitialise();
  }
}

function showQaWindow() {
}

function exitSeat() {
     
     //console.log("exit seat");
     window.location="/services/Lobby/leave?room="+$("#room").html()+"&seat="+$("#seat").html()+"&film="+$("#film").html()+"&code=exit";
    
}
    
$(document).ready(function(){
  if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
 
	//console.log("Starting Toolbar");
  //hide toolbar and make visible the 'show' button
	$(".downarr").click(function() { $("#footer").slideToggle("fast"); $("#toolbarbut").fadeIn(100);});
  //show toolbar and hide the 'show' button
  $(".showbar").click(function() {$("#footer").slideToggle("fast");$("#toolbarbut").fadeOut();});
  
  //don't jump to #id link anchor 
  $(".facebook, .twitter, .delicious, .digg, .rss, .stumble, .menutit, span.downarr a, span.showbar a").click(function() { return false; });
	
  $('.seat_refresh').click(function(event) {location.reload(true);});
  $('.seat_surrender').click(function(event) {exitSeat();});
  $('.constellation-click').click(function(event) {toggleConstellation();});
  $('.chat-click').click(function(event) {toggleDiv( 'chat_panel' );});
  $('.info-click').click(function(event) {toggleDiv( 'info_panel' );});
  $('.qanda-click').click(function(event) {toggleDiv( 'qanda_panel' );});
  $('.help-click').click(function(event) {toggleDiv( 'help_panel' );});
  $('.hostchat-click').click(function(event) {toggleDiv( 'hostchat_panel' );});
  $('.admin-click').click(function(event) {toggleDiv( 'adminmessage_panel' );});
  $('.test-click').click(function(event) {videoplayer.camViewerReStart();});
  
  $('#qanda_panel h4').click(function(event) { toggleDiv( 'qanda_panel' ); });
  $('#qa_pop_top a').click(function(event) { hideQaWindow(); });
  $('#chat_panel h4').click(function(event) {toggleDiv( 'chat_panel' );});
  $('#info_panel h4').click(function(event) {$('#info_panel').fadeOut(100);});
  $('#help_panel h4').click(function(event) {$('#help_panel').fadeOut(100);});
  $('#hostchat_panel h4').click(function(event) {$('#hostchat_panel').fadeOut(100);});
  $('#adminmessage_panel h4').click(function(event) {$('#adminmessage_panel').fadeOut(100);});
  
  //Start with Q&A Enabled
  //toggleDiv( 'chat_panel' );
  if ($("#has_video").html() == "true") {
  	toggleDiv( 'qanda_panel' );
  }
    
});
