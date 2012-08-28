$(document).ready( function() {

	$('.btn-join').click(function(e){
		if(typeof ipAllowedToJoin != 'undefined')
		{
			if(ipAllowedToJoin == true)
			{
				return true;
			}
			else if( ipAllowedToJoin == false)
			{
				togglePopup('geo-blocked');
				e.preventDefault();
				
				return false;
			}
		}
	});
	
	//disable copy/paste between email/confirm email inputs on the pay step of the join a screening process
	if($('#fld-cc_email').length)
	{
		$("#fld-cc_email, #fld-cc_confirm_email").get(0).oncontextmenu = function() { return false; };
		$("#fld-cc_email, #fld-cc_confirm_email").keydown(function(event) {
			var forbiddenKeys = new Array('c', 'x', 'v');
			var keyCode = (event.keyCode) ? event.keyCode : event.which;
			var isCtrl;
			isCtrl = event.ctrlKey
			if (isCtrl) {
				for (i = 0; i < forbiddenKeys.length; i++) {
					if (forbiddenKeys[i] == String.fromCharCode(keyCode).toLowerCase()) {
						return false;
					}
				}
			}
			return true;
	
		});
	}
	
	$('.main-login').click(function () {
		togglePopup('main-login');
		
		$('popup-login-email').focus();

		if(typeof openLoginPopup != 'undefined' && openLoginPopup)
		{
			$('.pseudo-step .error-panel').show();
			openLoginPopup = false;
		}
		movePopup('#main-login-popup', 145, null);
		$('#RES_ID_fb_login_image').attr('src', '/images/fb-connect.png');
		$('#RES_ID_fb_login_image').show();
	});

	$('#main-btn-login').click(function(e) {
		$('#main-login-form').attr('action', secureStepLoginFormUrl).submit();
		/*$.post(stepLoginFormUrl, 'login[email]=' + $('#popup-login-email').val() + '&login[password]=' + $('#popup-login-password').val(), function(data){
			if( data && data.status == 'fail') {				
				
				$('#main-login-popup .error-panel .errors').html('');
				//$('#global-login-errors').html('');
				
				errors = data.errors;
				for (label in errors) {
					//$('#global-login-errors').append(errors[label] + '<br/>');
					$('#main-login-popup .error-panel .errors').append('<p>' + errors[label] + '</p>');
				}
				
				//$('#main-login-popup .error-panel').show(); 
				if (!$('#main-login-popup .error-panel').is(':visible')) {
					$('#main-login-popup .error-panel').animate({
						width: 'toggle'
					}, 500);
				}
				
			} 
			else if(data.status == 'success') {
				doGlobalLogin(data);
				togglePopup('main-login');
			}
		}, "json");
		
		return false; */
	});
	
	$('#main-btn-signup').click(function(e) {
		$('#main-login-form').attr('action', secureStepSignupFormUrl).submit();
		
		/*$.post(stepSignupFormUrl, 'signup[name]=' + $('#main-signup-name').val() + '&signup[username]=' + $('#step-signup-username').val()+ '&signup[email]=' + $('#step-signup-email').val()+ '&signup[month]=' + $('#step-fld_month').val()+ '&signup[day]=' + $('#step-fld_day').val()+ '&signup[year]=' + $('#step-fld_year').val()+ '&signup[password]=' + $('#step-signup-password').val()+ '&signup[password2]=' + $('#step-signup-password2').val()+ '&connect-type=signup'+ '&signup[from]=join', function(data){
			if(data.status == 'fail') {				main
				
				$('#main-login-popup .error-panel .errors').html('');
				//$('#global-login-errors').html('');
				
				errors = data.errors;
				for (label in errors) {
					//$('#global-login-errors').append(errors[label] + '<br/>');
					$('#main-login-popup .error-panel .errors').append('<p>' + errors[label] + '</p>');
				}
				
				//$('#main-login-popup .error-panel').show(); 
				if (!$('#main-login-popup .error-panel').is(':visible')) {
					$('#main-login-popup .error-panel').animate({
						width: 'toggle'
					}, 500);
				}
				
			} 
			else if(data.status == 'success') {
				doGlobalLogin(data);
				togglePopup('main-login');
			}
		}, "json");
		
		return false;
		*/
	});
	
	$('#main-login-form .logout').click(function() {
		doGlobalLogout(false);
	});
	
	$('#your-screenings').click(function() {
		togglePopup('your-screenings');
		
		$('#your-screenings-ajax-load').html('<center><img src="/images/ajax-loader.gif" /></center>');
		
		$.ajax({ url: urlGetEditableScreenings, success: function(data){
				$('#your-screenings-ajax-load').html(data);
			}
		});		
	});
	
	$('.inProgress').click(function (e) {
		e.preventDefault();
		var link = e.currentTarget.href;
		togglePopup('joinScreening');
		movePopup('#joinScreening-popup', null, 500);
		$(".btn-buy-anyway").click(function(e1){
			e1.preventDefault()
			window.location = link;
		});
		$(".btn-another-showtime").click(function(e2) {
			e2.preventDefault();
			togglePopup("joinScreening");
		});
	});
		
	if(typeof openLoginPopup != 'undefined' && openLoginPopup)
	{
		if(typeof showSignupPanel != 'undefined' && showSignupPanel)
		{
			$('#main-choose-signup').click();
		}
		$('#login-area .main-login').click();	
	}	

});

$(window).load(function(){
	replaceFbIcon();
});

var mainLogout = function() {	
	$('#main-login-form #main-formlet-other').hide();
	$('#main-login-form #main-formlet-login').show();
	$('#main-login-form #main-formlet-signup').show();
	
	$('#main-login-form #main-logged-out').show();
	$('#main-login-form #main-logged-in').hide();
}

var doLogout = function(withRefresh, new_step) {
	$('#RES_ID_fb_login_image').attr('src', '/images/fb-connect.png');
	
	$.post(stepLogoutUrl, function(data){
		// if the new_step is not defined, we probably logout from join popup
		if(new_step)
		{
			new_step.find('form').slideDown('slow');
			new_step.addClass('step_current').addClass('step_editing').removeClass('step_done');
			
			if(withRefresh) {
				refreshPage();
			}
		}
		
		mainLogout();
	});
};

var doGlobalLogout = function(new_step) {
	if(isFbUserLoggedIn) {
		FB.Connect.logout(function() {
			doLogout(true, new_step);
		});
	} else {
		doLogout(false, new_step);
	}
	
	$('#loggedin-area').hide();
	$('#login-area').show();
	
	if(typeof resetFormlets != 'undefined') {
		resetFormlets();
	}
};

var doGlobalLogin = function(data) {	
	$('#loggedin-area #full-name').html(data.display_name);
	$('#loggedin-area #photo-url').attr('src', data.profile_pic);
	
	$('#global-login-errors').html('');
	$('#main-login-popup .error-panel').hide();
	
	$('#loggedin-area').show();
	$('#login-area').hide();
	
	$('#main-login-form #formlet-other-label-full-name').html(data.display_name);
	$('#main-login-form .full-name-area').html(data.display_name);
	$('#main-login-form #main-login-icon').attr('src', '/images/' + data.user_type_s + '-connect.png');
	$('#main-login-form #formlet-other-img-photo-url').attr('src', data.profile_pic);
	
	$('#main-login-form #main-formlet-other').show();
	$('#main-login-form #main-formlet-login').hide();
	$('#main-login-form #main-formlet-signup').hide();
	
	$('#main-login-form #main-logged-out').hide();
	$('#main-login-form #main-logged-in').show();
	
	if(typeof setupLoginSuccess != 'undefined') {
		setupLoginSuccess();
	}
};

replaceFbIcon = function() {
	//$('#RES_ID_fb_login_image').attr('src', '/images/fb-connect.png');
	$('.fbconnect_login_button img').attr('src', '/images/fb-connect.png');
	//$('#RES_ID_fb_login_image').show();
	$('.fbconnect_login_button img').show();
};
