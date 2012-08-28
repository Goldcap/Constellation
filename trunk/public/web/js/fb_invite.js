var fb_invite = {
  
  itRetrievedContacts: 0,
  collectedEmails : new Array(),
  purchaseSubmitted : false,
  currentPrice: 0,
  priceDiscount: .25,
  gbip: false,
  fb_token: null,
  screening: null,
  user_type: null,
  user_id: null,
  user_name: null,
                               
  init: function() {
  
  	$(".fb_dialog_close_icon").click(function(){
  		$("#invite_facebook_lb").fadeOut(100);
			modal.modalOut( login.hidepopup );
		});
  	
  	$("#ok_clicked").click(function(e){
  		e.preventDefault();
			fb_invite.sendInvites();
		});
		
		$("#cancel_clicked").click(function(e){
  		e.preventDefault();
  		$('#invite_facebook_lb').fadeOut(100);
      modal.modalDestroy();
		});
  	
  	$(".all_friends").click(function(e){
			fb_invite.selectAllFriends();
		});
		
		//If we're in the hosting step, show the popup
    if (window.location.href.match(/fb_invite/)) {
      var obj = window.location.href.split("fb_invite=");
      //console.log("Found "+obj[1]+" in the path");
      fb_invite.invite( 'screening', obj[1] );
      //host_screening.detail();
    }
    
  },
  
  goModal: function() {
		modal.modalIn( login.hidepopup );
	},
	
  invite : function( type, screening ) {
  	
		if (screening === undefined){
			screening = $("#screening").html();
		}
		
   	fb_invite.user_type = type;
   	fb_invite.screening = screening;
   	
		if ($("#fb_user_list .selectItem").length==0) {
			error.showError("alert",'<p align="center">Loading Your Friends</p>','<img src="/images/ajax-loader.gif" alt="loading" />',0);
	    
			if ($("#fb_user_list").length > 0) {
				//fb_invite.updateCount();
				$.ajax({
		      url: '/services/Facebook/token', 
		      type: "GET", 
		      cache: false, 
		      dataType: "json", 
		      timeout: 10000,
		      success: function(response) {
		          fb_invite.getLoginStatus( response );
		      }, error: function(response) {
		          console.log("ERROR:", response);
							error.showError('error','communication error','Please try again');
		      }
		    });
				
	  	}
		} else {
			fb_invite.showDialog();
		}
  },
  
  getLoginStatus: function(response) {
		if (response.status != "undefined") {
				fb_invite.fb_token = response.authResponse.accessToken;
				fb_invite.user_id = response.authResponse.userId;
				fb_invite.user_name = response.authResponse.userName;
  			fb_invite.getInvites();
      } else {
      	var theurl = "/services/Facebook/auth?dest="+document.location.pathname+"^fb_invite="+fb_invite.screening;
				//alert(theurl);
      	window.location = theurl;
			}
	},
	
  getInvites: function() {
  	
  	$.ajax({
	      url: '/services/Facebook/friends', 
	      type: "GET", 
	      cache: false, 
	      dataType: "json", 
	      timeout: 4000,
	      success: function(result) {
	          fb_invite.showInvites(result);
	      }, error: function(response) {
	          //console.log("ERROR:", response);
						error.showError('error','communication error','Please try again');
	      }
	    });
	    
  },
	
  showInvites: function( result ) { 
		for (x=0;x<result.data.length;x++) {
			//console.log(result.data[x].name);
			if ($(".fb_invites[value="+result.data[x].id+"]").length == 0) {
				$("#fb_user_list").append('<span class="selectItem"><table cellspacing="4"><tr><td><input class="fb_invites" type="checkbox" value="'+result.data[x].id+'" /></td><td><img src="https://graph.facebook.com/'+result.data[x].id+'/picture" title="'+result.data[x].name+'" /></td><td>'+result.data[x].name+'</td></tr></table></span>');
			}
		}
		//$("#fb_share_preview_image").attr('src','https://graph.facebook.com/'+fb_invite.user_id+'/picture');
		$("#fb_share_user_image").attr('src','https://graph.facebook.com/'+fb_invite.user_id+'/picture');
		fb_invite.showDialog();
		
	},
	
	showDialog: function() {
		setTop("#invite_facebook_lb");
		$("#fb_in").show();
  	error.unblock();
		$("#invite_facebook_lb").fadeIn();
  	fb_invite.goModal();
  	
  	surl = fb_invite.screening.replace(/(#.+)/,"");
    var args = {"screening": surl};
  	$.ajax({
	      url: '/services/Invite/info', 
	      type: "GET", 
	      cache: true, 
	      dataType: "json", 
        data: $.param(args),
	      timeout: 4000,
	      success: function(result) {
	          if (result.screening_image != '') {
	          	$("#fb_share_preview_image").attr("src",result.screening_image);
	          } else {
							$("#fb_share_preview_image").attr("src","/uploads/screeningResources/"+result.screening_film_id+"/logo/film_logo"+result.screening_film_logo);
	          }
						var desc = "{Your Message} - " + fb_invite.user_name + " has invited you to a showing of " + result.screening_film_name + " on Constellation, at " + result.time_tz + " on " + result.time_dayofweek + ", " + result.time_date + ". - '" + result.screening_film_synopsis + "'";
	      		$("#fb_share_preview_text").html(desc);
				}, error: function(response) {
	          console.log("ERROR:", response);
						error.showError('error','communication error','Please try again');
	      }
	    });
	},
	
	selectAllFriends: function() {
		if ($(".all_friends").html() == "All Friends") {
			$(".all_friends").html("No Friends");
			$(".fb_invites").attr("checked","checked");
		} else {
			$(".all_friends").html("All Friends");
			$(".fb_invites").removeAttr("checked");
		}
	},
	
	sendInvites: function() {
		
		if ($("#fb_invite_message").val() == '') {
			error.showError('error','Please enter a message prior to sending your invitations.',null,2000);
			return;
		}
		error.showError("alert",'<p align="center">Sending Invitations...</p>','<img src="/images/ajax-loader.gif" alt="loading" />',2000);
    
    fbs = [];
    j=0;
    
    var eml = $(':checkbox:checked','#invite_facebook');
    $.each(eml,function(index,elm) {
    	fbs.push($(this).val());
    });
    
    var args = {"facebooks":fbs,
                "user_type": fb_invite.user_type,
                "fb_session":fb_invite.fb_token,
                "screening":fb_invite.screening,
                "name": fb_invite.user_name,
                "subject":$('#form_invite_email_subject').val().replace(/\n/g, '<br/>' ),
                "message":$('#fb_invite_message').val().replace(/\n/g, '<br/>' )};
    
    $.ajax({
      url: '/services/Invite/send', 
      data: $.param(args), 
      type: "POST", 
      cache: false, 
      dataType: "json", 
      timeout: 4000,
      success: function(response) {
          fb_invite.finishInvites( response );
      }, error: function(response) {
          console.log("ERROR:", response);
					error.showError('error','communication error','Please try again');
      }
    });
	},
	
	finishInvites: function( response ) {
  	//Release the "process" UI
  	if (response.result == "success") {
			try {
			if (typeof screening_room != "undefined") {
				screening_room.sentInvites(response.count);
			}} catch (err) {}
			
			try {
			if (typeof host_screening != "undefined") {
				host_screening.sentInvites(response.count);
			}} catch (err) {}
			
    	error.showError('alert',response.message,null,2000); 
      $('#invite_facebook_lb').delay(4000).fadeOut(100);
      modal.modalDestroy();
    } else {
      error.showError('error',response.message,null,2000); 
		}
  }
  
}

function toggleChecked(status) {
	$(".fb_invites").each( function() {
		$(this).attr("checked",status);
	})
}

$(document).ready(function(){
	if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
  
	fb_invite.init();
	
});
