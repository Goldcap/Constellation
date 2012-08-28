
var login = {
  
  init : function() {
    login.$login = $('#log-in');
    login.$signup = $('#sign-up');
    login.$forget = $('#password-out');
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
    
    $('#main-choose-signup').click(login.showsignup);
    $('#return-login').click(login.onShowLogin);
    $('#have-account').click(login.onShowLogin);
    $('#main-choose-password').click(login.showpassword);


    /* loose Events to track down */

    $('.main-login').click(function (e) {
      login.showlogin(e);
    });
    
    $('.main-showtimes').click(function (e) {
      login.showpopup(e);
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
  hidepopup : function() {
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

    $('#main-login-popup').fadeIn()

		$('#login-email').focus();
		if ($("#your-screenings").length > 0) {
		  login.showscreeningpop();
		} else {
			$('#main-choose-signup').fadeIn(100);
		}
		login.goModal();
  },
  
  showpopupSignup: function(e){
        if (e != undefined)
      e.stopPropagation();
    setTop("#main-login-popup");


    login.$login.hide();
    login.$signup.show();
    login.$forget.hide();
    $('#main-login-popup').fadeIn()


    login.goModal();
  },
  showlogin : function(e) {
  	e.stopPropagation();
    setTop("#main-login-popup");
    login.$login.show();
    login.$signup.hide();
    login.$forget.hide();
		$('#main-login-popup').show();

    login.goModal();

  },
  
  showsignup : function(e) {
  	if (e != undefined)	e.stopPropagation();

    setTop("#main-login-popup");
		
    $('#main-login-popup').show();

    login.$login.fadeOut(function(){
      login.$signup.fadeIn({opacity:1});
    });
  },
  onShowLogin: function(){
    var visible = login.$forget.is(":visible") ? login.$forget : login.$signup;
    visible.fadeOut(function(){
      login.$login.fadeIn();
    });
  },
  showpassword : function() {
    login.$login.fadeOut(function(){
      login.$forget.fadeIn();
    });
  },
  
  validateLogin: function() {
    if (($("#login_email").val() == 'EMAIL') || ($("#login_password").val() == 'PASSWORD') || ($("#login_email").val() == '') || ($("#login_password").val() == '')) {
      return;
    }
    var err = 0;
    if (($("#login_email").val() == '') || ($("#login_email").val() == 'EMAIL') || ( ! login.isValidEmailAddress($("#login_email").val()))) { $("#login_email").css('background','#ff6666'); err++; } else { $("#login_email").css('background','#ffffff'); }
    if (($("#login_password").val() == '') || ($("#login_password").val() == 'PASSWORD')) { $("#login_password").css('background','#ff6666'); err++; } else { $("#login_password").css('background','#ffffff'); }
    if (err > 0) {
      error.showError("error","Your login information is invalid.","",2000);
      return false;
    }
    return true;
  },
  
  validatePassword: function() {
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
    //if ($("#main-signup-name").val() == '') { $("#main-signup-name").css('background','#ff6666'); err++; } else { $("#main-signup-name").css('background','#ffffff'); }
    if ($("#main-signup-username").val() == '') { $("#main-signup-username").css('background','#ff6666'); err++; } else { $("#main-signup-username").css('background','#ffffff'); }
    if (! login.isValidEmailAddress($("#main-signup-email").val())) { $("#main-signup-email").css('background','#ff6666'); err++; } else { $("#main-signup-email").css('background','#ffffff'); }
    if ($("#main-signup-password").val() == '') { $("#main-signup-password").css('background','#ff6666'); err++; } else { $("#main-signup-password").css('background','#ffffff'); }
    if ($("#main-signup-password2").val() == '') { $("#main-signup-password2").css('background','#ff6666'); err++; } else { $("#main-signup-password2").css('background','#ffffff'); }
    if (($("#main-signup-password").val() != '') && ($("#main-signup-password2").val() != '')) {
      if ($("#main-signup-password").val() != $("#main-signup-password2").val()) { $("#main-signup-password").css('background','#ff6666'); $("#main-signup-password2").css('background','#ff6666'); err++; } else { $("#main-signup-password").css('background','#ffffff'); $("#main-signup-password2").css('background','#ffffff'); }
    }
    if ($("#main-signup-optin:checked").length == 0) {
      $("#main_signup_text").css('color','#ff3300'); 
    } else {
      $("#main_signup_text").css('color','#565656');
    }
    if (err > 0) {
      error.showError("error","Your signup information is invalid.");
      return false;
    }
    return true;
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
