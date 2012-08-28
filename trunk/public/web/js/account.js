// JavaScript Document
var account = {
	
	init: function() {
		
		$("#user_facebook_url").watermark("facebook-account",{className: "wmInput"});
		$("#user_twitter_url").watermark("twitter-account",{className: "wmInput"});
		$("#user_website_url").watermark("www.yourwebsite.com",{className: "wmInput"});
		
		$('.selectOveride select').each(function(select){
        var $select = $(this);
        var text = $('option:selected', $select).html();
    		$select.prev().html(text);
    		$select.bind('change', account.onSelectChange);
    });
    
		$("#REF_user_image_original").fancybox({
      'autoScale'		: false,
			'transitionIn'	: 'none',
			'transitionOut'	: 'none',
			'width'		: 140,
			'height'		: 120,
      overlayShow: true,
      hideOnContentClick: false,
      'scrolling'		: 'no',
    	'titleShow'		: false,
    	'onComplete': function() {
        // set up the form for ajax submission
        $("#FILE_user_image_original").makeAsyncUploader({
          upload_url: "/services/ImageManager/user/"+$("#user_id").html()+'?constellation_frontend='+$("#session_id").html(),
          flash_url: '/js/swfupload/swfupload.swf',
          button_image_url: '/js/swfupload/blankButton.png',
          debug: false
        });
      },
    	'onClosed' : function() {
    		console.log($("input[name=FILE_user_image_original_guid]").val());
    		var pic = '/uploads/hosts/'+$("input[name=FILE_user_image_original_guid]").val();
        if ($("input[name=FILE_user_image_original_guid]").val().length > 0) {
          pic=pic+".jpg";
          $("#user_image_original_preview").attr("src",pic);
        }
    	}
    });
	},
	
	onSelectChange: function (event){
    var $select = $(event.target);
    var text = $('option:selected', $select).html();
    $select.prev().html(text);
  },
  
	toggleEdit: function() {
		console.log($("#editor").css("display"));
		if ($("#editor").css("display") == "none") {
			$("#editor").fadeIn(100);
		} else {
			$("#editor").fadeOut(100);
		}	
	},
	
	doSubmit: function() {
	
		var submit = true;
		console.log("Checking Email");
		if (! account.confirmEmail()) {
			submit = false;
		}
		
		console.log("Checking Password");
		if (! account.confirmPassword()) {
			submit = false;
		}
		return submit;
	},
	
	confirmEmail: function() {
		var doreturn = true;
	  //If the fields are both full
		if (($("#user_email_new").val() != '') && ($("#user_email_confirm").val() != '')) {
		  if (! account.validEmail($("#user_email_new").val())) {
				console.log("New Email Bad");
				$("#user_email_new").css("background","#ffcccc");
				$("#user_email_new").css("border","1px solid #cc0000");
				doreturn = false;
			} else {
				console.log("New Email Good");
				$("#user_email_new").css("background","white");
				$("#user_email_new").css("border","1px solid #BFBFBF");
			}
			
		  if (! account.validEmail($("#user_email_confirm").val())) {
		  	console.log("Confirm Email Bad");
				$("#user_email_confirm").css("background","#ffcccc");
				$("#user_email_confirm").css("border","1px solid #cc0000");
				doreturn = false;
			} else {
				console.log("Confirm Email Good");
				$("#user_email_confirm").css("background","white");
				$("#user_email_confirm").css("border","1px solid #BFBFBF");
			}
		}
		
		//Check to make sure there's something in the fields
		if (doreturn) {
		if (($("#user_email_new").val() != '') && ($("#user_email_confirm").val() == '') || ($("#user_email_new").val() == '') && ($("#user_email_confirm").val() != '') || ($("#user_email_confirm").val() != $("#user_email_new").val())) {
	    console.log("Email issue!");
			$("#user_email_new").css("background","#ffcccc");
			$("#user_email_new").css("border","1px solid #cc0000");
			$("#user_email_confirm").css("background","#ffcccc");
			$("#user_email_confirm").css("border","1px solid #cc0000");
			doreturn = false;
	  }}
	  
	  return doreturn;
	},
	
	confirmPassword: function() {
		var doreturn = true;
		
		//Not changing passwords
		if (($("#current_password").val() == '') && ((($("#new_password").val() == '') || ($("#new_password_confirm").val() == '')))) {
			console.log("No Password");
			$("#current_password").css("background","white");
			$("#current_password").css("border","1px solid #BFBFBF");
			$("#new_password").css("background","white");
			$("#new_password").css("border","1px solid #BFBFBF");
			$("#new_password_confirm").css("background","white");
			$("#new_password_confirm").css("border","1px solid #BFBFBF");
			return true;
		}
		
		//Current Password is Incorrect
		if (($("#current_password").val() == '') && ((($("#new_password").val() != '') || ($("#new_password_confirm").val() != '')))) {
			console.log("Current Password Blank");
			$("#current_password").css("background","#ffcccc");
			$("#current_password").css("border","1px solid #cc0000");
			return false;
		}
		
		//Password Match Issue
		if ((($("#new_password").val() == '') && ($("#new_password_confirm").val() != '')) || (($("#new_password").val() == '') && ($("#new_password_confirm").val() == '')) || ($("#new_password_confirm").val() != $("#new_password").val())) {
	    console.log("Password Match Issue");
	    if (($("#new_password").val() == '') && ($("#new_password_confirm").val() != '')) {
				$("#new_password").css("background","#ffcccc");
				$("#new_password").css("border","1px solid #cc0000");
				return false;
			} else {
				$("#new_password").css("background","white");
				$("#new_password").css("border","1px solid #BFBFBF");
			}
			
			if (($("#new_password").val() != '') && ($("#new_password_confirm").val() == '')) {
				$("#new_password_confirm").css("background","#ffcccc");
				$("#new_password_confirm").css("border","1px solid #cc0000");
				return false;
			} else {
				$("#new_password_confirm").css("background","white");
				$("#new_password_confirm").css("border","1px solid #BFBFBF");
			}
			
			if (($("#new_password_confirm").val() != $("#new_password").val()) || ($("#new_password_confirm").val() == '' && $("#new_password").val() == '')) {
				$("#new_password").css("background","#ffcccc");
				$("#new_password").css("border","1px solid #cc0000");
				$("#new_password_confirm").css("background","#ffcccc");
				$("#new_password_confirm").css("border","1px solid #cc0000");
				return false;
			}
	  }
	  
	  return true;
	  
	},
	
	validEmail:function ( email_val ) {
	  var gotIt = /^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/.exec(email_val);
	  if ((! gotIt) || (gotIt == null)) {
			return false;
		} else {
		  return true;
		}
	},
	
	follow:function(element) {

		if(!!$('.main-login').length){
			$("#login_destination").val();
			$("#signup_destination").val();
			login.showpopup();
			return false;
		}

		var $target = $(element);

		var args = {
			"user_id": $target.data('user-id'),
            "type": $target.data('is-following')
        };
		$.ajax({url: '/services/Follow', 
            type: "GET", 
            cache: false, 
            dataType: "json",
            data: $.param(args), 
            success: _.bind(account.followSuccess, account, $target),
            error: account.followFailure
        });
	},
	
	followSuccess:function(element, response) {
		if(response.followResponse.result == "unfollowed") {
			element.html('Follow').data('is-following', true);
			
		} else if(response.followResponse.result == "followed") {
			element.html('Unfollow').data('is-following', false);
		}
	},
	
	followFailure:function(response) {
		alert(response.followResponse.result);
	}

	
}


$(document).ready(function(){
	if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
  
	account.init();
	
});
