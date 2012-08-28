var login = {
  
  init : function() {
    
    if ($("#login_email").length > 0) {
      $("#login_email").watermark("EMAIL");
      $("#login_password").watermark("PASSWORD");
    }
    
    if ($("#password_email").length > 0) {
      $("#password_email").watermark("EMAIL");
    }
    
    //Form Submissions;
    $("#login-button").click(function(e) {
      console.log("Login Button!");
      e.preventDefault();
      if (login.validateLogin()) {
        $("#login_form").submit();
      }
    });
    
    $("#signup-button").click(function(e) {
      e.preventDefault();
      if (login.validateJoin()) {
        $("#sign-up_form").submit();
      }
    });
    
    $("#password-button").click(function(e) {
      e.preventDefault();
      if (login.validatePassword()) {
        login.resetPassword();
      }
    });
    
    $('.main-login').click(function () {
      login.showpopup();
    });
    
    $('.main-showtimes').click(function () {
      login.showpopup();
    });
    
    $('#main-choose-signup').click(function(){
      login.showsignup();
    });
    
    $('#main-choose-login').click(function(){
      login.showlogin();
    });
    
    $('#main-choose-password').click(function(){
      login.showpassword();
    });
    
    
    $('#main-custom-connect-icon').click(function(){
      login.showsignup();
    });
    
    $('.popup-close a').click(function(){
      login.hidepopup();
    });
    
  },
  
  //NOTE, THIS IS A GLOBAL FUNCTION
  hidepopup : function() {
		$('.pop_up').fadeOut("slow");
  },
  
  hidescreeningpop : function() {
    $('#your-screenings-popup').hide();
    $("#your-screenings-ajax-load").html('');
  },
  
  showpopup : function() {
		$('#main-login-popup').show();
		$('#popup-login-email').focus();
		if ($("#your-screenings").length > 0) {
		  login.showscreeningpop();
		}
  },
  
  showlogin : function() {
    $('#sign-up').fadeOut("slow").queue(function(){
      $('#password-out').fadeOut("slow");
      $('#log-in').fadeIn("slow");
      $('#sign-up').clearQueue();
    });
    $('#main-choose-login').fadeOut("slow").queue(function(){
      $('#main-choose-signup').fadeIn("slow");
      $('#main-choose-login').clearQueue();
    });
  },
  
  showsignup : function() {
    $('#password-out').fadeOut("slow").queue(function(){
      $('#sign-up').fadeIn("slow");
      $('#password-out').clearQueue();
    });
    $('#main-choose-signup').fadeOut("slow").queue(function(){
      $('#main-choose-login').fadeIn("slow");
      $('#main-choose-signup').clearQueue();
    });
  },
  
  showpassword : function() {
    $('#sign-up').fadeOut("slow").queue(function() {
      $('#password-out').fadeIn("slow");
      $('#sign-up').clearQueue();
    });
    
  },
  
  validateLogin: function() {
    if (($("#login_email").val() != 'email') && ($("#login_password").val() == '')) {
      return;
    }
    if (($("#login_password").val() != 'password') && ($("#login_email").val() == '')) {
      return;
    }
    var err = 0;
    if (($("#login_email").val() == '') || ($("#login_email").val() == 'EMAIL') || ( ! login.isValidEmailAddress($("#login_email").val()))) { $("#login_email").css('background','#ff6666'); err++; } else { $("#login_email").css('background','#ffffff'); }
    if (($("#login_password").val() == '') || ($("#login_password").val() == 'PASSWORD')) { $("#login_password").css('background','#ff6666'); err++; } else { $("#login_password").css('background','#ffffff'); }
    if (err > 0) {
      error.showError("error","Your login information is invalid.");
      return false;
    }
    return true;
  },
  
  validatePassword: function() {
    console.log("Submit Password");
    var err = 0;
    if (($("#password_email").val() == '') || ($("#password_email").val() == 'EMAIL') || ( ! login.isValidEmailAddress($("#password_email").val()))) { $("#password_email").css('background','#ff6666'); err++; } else { $("#password_email").css('background','#ffffff'); }
    if (err > 0) {
      error.showError("error","Your email address is invalid.");
      return false;
    }
    return true;
  },
  
  validateJoin: function() {
    var err = 0;
    if ($("#main-signup-name").val() == '') { $("#main-signup-name").css('background','#ff6666'); err++; } else { $("#main-signup-name").css('background','#ffffff'); }
    if ($("#main-signup-username").val() == '') { $("#main-signup-username").css('background','#ff6666'); err++; } else { $("#main-signup-username").css('background','#ffffff'); }
    if (! login.isValidEmailAddress($("#main-signup-email").val())) { $("#main-signup-email").css('background','#ff6666'); err++; } else { $("#main-signup-email").css('background','#ffffff'); }
    if ($("#main-signup-password").val() == '') { $("#main-signup-password").css('background','#ff6666'); err++; } else { $("#main-signup-password").css('background','#ffffff'); }
    if ($("#main-signup-password2").val() == '') { $("#main-signup-password2").css('background','#ff6666'); err++; } else { $("#main-signup-password2").css('background','#ffffff'); }
    if (($("#main-signup-password").val() != '') && ($("#main-signup-password2").val() != '')) {
      if ($("#main-signup-password").val() != $("#main-signup-password2").val()) { $("#main-signup-password").css('background','#ff6666'); $("#main-signup-password2").css('background','#ff6666'); err++; } else { $("#main-signup-password").css('background','#ffffff'); $("#main-signup-password2").css('background','#ffffff'); }
    }
    if (err > 0) {
      error.showError("error","Your signup information is invalid.");
      return false;
    }
    return true;
  },
  
  showscreeningpop : function() {
    $("#your-screening-ajax-load").html('<img src="/images/ajax-loader.gif" />');
    $("#your-hosting-ajax-load").html('<img src="/images/ajax-loader.gif" />');
    $("#your-screening-ajax-load").fadeIn("slow");
    $("#your-hosting-ajax-load").fadeIn("slow");
		$.ajax({url: '/services/Screenings/get', 
      type: "GET", 
      cache: true, 
      dataType: "text", 
      success: function(response) {
        login.showscreenings(response);
      }, error: function(response) {
         console.log("ERROR:", response)
      }
    });
    $.ajax({url: '/services/Screenings/host', 
      type: "GET", 
      cache: true, 
      dataType: "text", 
      success: function(response) {
        login.showhostings(response);
      }, error: function(response) {
         console.log("ERROR:", response)
      }
    });
  },
  
  showscreenings: function ( response ) {
    $("#your-screening-ajax-load").html(response);
  },
  
  showhostings: function ( response ) {
    $("#your-hosting-ajax-load").html(response);
  },
  
  isValidEmailAddress: function (emailAddress) {
  	var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
  	return pattern.test(emailAddress);
  },
  
  resetPassword: function() {
    var args = { 'email' : $("#password_email").val() };
    $.ajax({url: '/services/PasswordReminder', 
      data: $.param(args), 
      dataType: "json", 
      type: "POST",
      success: function(response) {
    	 error.showError('error',response.response.message);
      }, 
      error: function(response) {
    	 error.showError('error','There was an error. Please try again.');
      }
    });
  }
}

$(document).ready( function() {
  if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
  
  login.init();
});
