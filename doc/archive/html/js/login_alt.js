
var login = {
  
  init : function() {
    
    if ($("#login_email").length > 0) {
      $("#login_email").watermark("EMAIL", {className: 'input_wm'});
      $("#login_password").watermark("PASSWORD", {className: 'input_wm'});
    }
    
    if ($("#password_email").length > 0) {
      $("#password_email").watermark("EMAIL", {className: 'input_wm'});
    }
    
    if ($("#main-signup-name").length > 0) {
      $("#main-signup-name").watermark("NAME", {className: 'input_wm'});
    }
    
    if ($("#main-signup-username").length > 0) {
      $("#main-signup-username").watermark("USERNAME", {className: 'input_wm'});
    }
    
    if ($("#main-signup-email").length > 0) {
      $("#main-signup-email").watermark("EMAIL", {className: 'input_wm'});
    }
    
    if ($("#main-signup-password").length > 0) {
      $("#main-signup-password").watermark("PASSWORD", {className: 'input_wm'});
    }
    
    if ($("#main-signup-password2").length > 0) {
      $("#main-signup-password2").watermark("CONFIRM", {className: 'input_wm'});
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
    
    $('.main-login').click(function (e) {
      login.showpopup(e);
    });
    
    $('.main-showtimes').click(function (e) {
      login.showpopup(e);
    });
    
    $('#main-choose-signup').click(function(e){
      login.showsignup(e);
    });
    
    $('#main-choose-login').click(function(e){
      login.showlogin(e);
    });
    
    $('#main-choose-password').click(function(e){
      login.showpassword(e);
    });
    
    $('#main-custom-connect-icon').click(function(e){
      login.showsignup(e);
    });
    
    $('.main_signup').click(function(e){
      login.showsignup(e);
    });
    
    $('.popup-close a').click(function(){
      login.hidepopup();
    });
    
  },
  
  goModal: function() {
		modal.modalIn( login.hidepopup );
	},
	
  //NOTE, THIS IS A GLOBAL FUNCTION
  hidepopup : function(e) {
		$('.popout_up').fadeOut(100);
		$('.poplg_up').fadeOut(100);
		$('.pops').fadeOut(100);
  },
  
  hidescreeningpop : function() {
    $('#your-screenings-popup').hide();
    $("#your-screenings-ajax-load").html('');
  },
  
  showpopup : function(e) {
  	if (e != undefined)
  		e.stopPropagation();
    setTop("#main-login-popup");
    $('#sign-up').fadeOut(100);
		$('#main-login-popup').show();
		$('#login-email').focus();
		if ($("#your-screenings").length > 0) {
		  login.showscreeningpop();
		} else {
			$('#main-choose-signup').fadeIn(100);
		}
		login.goModal();
  },
  
  showlogin : function(e) {
  	e.stopPropagation();
    setTop("#main-login-popup");
		$('#main-login-popup').show();
		$('#sign-up').fadeOut("slow").queue(function(){
      $('#password-out').fadeOut(100);
      $('#log-in').fadeIn(100);
      $('#sign-up').clearQueue();
    });
    $('#main-choose-login').fadeOut(100).queue(function(){
      $('#main-choose-signup').fadeIn(100);
      $('#main-choose-login').clearQueue();
    });
		login.goModal();
  },
  
  showsignup : function(e) {
  	if (e != undefined)
  		e.stopPropagation();
    setTop("#main-login-popup");
		$('#main-login-popup').show();
		$('#password-out').fadeOut(100).queue(function(){
      $('#sign-up').fadeIn(100);
      $('#password-out').clearQueue();
    });
    $('#main-choose-signup').fadeOut(100).queue(function(){
      $('#main-choose-login').fadeIn(100);
      $('#main-choose-signup').clearQueue();
    });
  },
  
  showpassword : function(e) {
  	e.stopPropagation();
    $('#sign-up').fadeOut(100).queue(function() {
      $('#password-out').fadeIn(100);
      $('#sign-up').clearQueue();
    });
    
  },
  
  validateLogin: function() {
    if (($("#login_email").val() == 'email') || ($("#login_password").val() == 'password') || ($("#login_email").val() == '') || ($("#login_password").val() == '')) {
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
  
  showscreeningpop : function(e) {
  	if (e != undefined)
			e.stopPropagation();
    $("#your-screening-ajax-load").html('<img src="/images/ajax-loader.gif" />');
    $("#your-hosting-ajax-load").html('<img src="/images/ajax-loader.gif" />');
    $("#your-screening-ajax-load").fadeIn(100);
    $("#your-hosting-ajax-load").fadeIn(100);
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
