var preuser = {
  
  init : function() {
    
    if ($("#login_email").length > 0) {
      $("#login_email").watermark("EMAIL");
      $("#login_password").watermark("CODE");
    }
    
    if ($("#password_email").length > 0) {
      $("#password_email").watermark("EMAIL");
    }
    
    //This is the "PRE PURCHASE" Invite Page
    if (window.location.pathname.match(/join_success/)) {
      error.showError("error","Your request has been sent to the system administrator for approval.");
    } else {
      //preuser.showlogin();
      preuser.showpopup();
    }
    //Form Submissions;
    $("#login-button").click(function(e) {
      e.preventDefault();
      if (preuser.validateLogin()) {
        $("#login_form").submit();
      }
    });
    
    $("#signup-button").click(function(e) {
      e.preventDefault();
      if (preuser.validateJoin()) {
        $("#sign-up_form").submit();
      }
    });
    
    //Authenticated Clicks
    $('.login-element').keydown(function( e ) {
      var keyCode = e.keyCode || e.which;
      if (keyCode == 13) {
        e.preventDefault();
        if (preuser.validateLogin()) {
          $("#login_form").submit();
        }
      }
    });
    
    $('.signup-element').keydown(function( e ) {
      var keyCode = e.keyCode || e.which;
      if (keyCode == 13) {
        e.preventDefault();
        if (preuser.validateJoin()) {
          $("#sign-up_form").submit();
        }
      }
    });
    
    $('.main-login').click(function () {
      preuser.showpopup();
    });
    
    $('.main-showtimes').click(function () {
      preuser.showpopup();
    });
    
    $('#main-choose-signup').click(function(){
      preuser.showsignup();
    });
    
    $('#main-choose-login').click(function(){
      preuser.showlogin();
    });
    
    $('#main-custom-connect-icon').click(function(){
      preuser.showsignup();
    });
  },
  
  //NOTE, THIS IS A GLOBAL FUNCTION
  hidepopup : function() {
		$('.pop_up').fadeOut("slow");
  },
  
  showpopup : function() {
		$('#main-login-popup').show();
		$('#popup-login-email').focus();
  },
  
  showlogin : function() {
    $('#sign-up').fadeOut("slow").queue(function(){
      $('#log-in').fadeIn("slow");
      $('#sign-up').clearQueue();
    });
    $('#main-choose-login').fadeOut("slow").queue(function(){
      $('#main-choose-signup').fadeIn("slow");
      $('#main-choose-login').clearQueue();
    });
  },
  
  showsignup : function() {
    $('#sign-up').fadeIn("slow");
    $('#main-choose-signup').fadeOut("slow").queue(function(){
      $('#main-choose-login').fadeIn("slow");
      $('#main-choose-signup').clearQueue();
    });
  },
  
  validateLogin: function() {
    var err = 0;
    if (($("#login_email").val() == '') || ($("#login_email").val() == 'EMAIL') || ( ! preuser.isValidEmailAddress($("#login_email").val()))) { $("#login_email").css('background','#ff6666'); err++; } else { $("#login_email").css('background','#ffffff'); }
    if (($("#login_password").val() == '') || ($("#login_password").val() == 'CODE')) { $("#login_password").css('background','#ff6666'); err++; } else { $("#login_password").css('background','#ffffff'); }
    if (err > 0) {
      error.showError("error","Your login information is invalid.");
      return false;
    }
    return true;
  },
  
  validatePassword: function() {
    var err = 0;
    if (($("#password_email").val() == '') || ($("#password_email").val() == 'EMAIL') || ( ! preuser.isValidEmailAddress($("#password_email").val()))) { $("#password_email").css('background','#ff6666'); err++; } else { $("#password_email").css('background','#ffffff'); }
    if (err > 0) {
      error.showError("error","Your email address is invalid.");
      return false;
    }
    return true;
  },
  
  validateJoin: function() {
    var err = 0;
    if (! preuser.isValidEmailAddress($("#main-signup-email").val())) { $("#main-signup-email").css('background','#ff6666'); err++; } else { $("#main-signup-email").css('background','#ffffff'); }
    
    if (err > 0) {
      error.showError("error","Your signup information is invalid.");
      return false;
    }
    return true;
  },
  
  isValidEmailAddress: function (emailAddress) {
  	var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
  	return pattern.test(emailAddress);
  }
}

$(document).ready( function() {
  if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
  
  preuser.init();
});
