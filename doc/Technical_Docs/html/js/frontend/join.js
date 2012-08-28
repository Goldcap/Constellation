
bindEvents = function(){
	
	$('#provide-username, #join-custom-connect-icon').click(function(){
		
		//show the form container
		$('#custom-login-container').animate({width:'show'}, 317);
		
		$('#signup-connect-type').val('username');
		$('#custom-login-container .formlet:visible').hide();
		$('#formlet-username').show();
	});
	
	$('#join-custom-connect-icon').click(function(){
		
		$('#signup-connect-type').val('login');
		$('#custom-login-container .formlet').hide();
		$('#formlet-login').show();
	});
	
	// logout from join the screening popup button
	$('#login-options a.logout').click(function(e){
		e.preventDefault();
		
		// logout 
		screening_room.logOut();
		
		//$('#join-login-popup a.popup-close').click();
		$('#join-login').html("Already a user? <span>LOGIN</span>");
		$('#formlet-login').toggle();
		$('#formlet-other').toggle();
		
		$('#RES_ID_fb_login_image').hide();
		$('#RES_ID_fb_login_image').attr('src', '/images/fb-connect.png');
		$('#RES_ID_fb_login_image').show();
		
		// log status
		$('#logged-out, .line-container').show();
		$('#logged-in').hide();
	
	});
}
$(window).load(function (){
	if(openSeats == 0)
	{
		togglePopup('sold-out');
		//$('#sold-out-popup').fadeToggle('normal');
	}
});
$(document).ready(function(){	
	
	bindEvents();
	
	// rearrange join login popup 
	$('a#join-login').click(function(){		
		$('#RES_ID_fb_login_image').attr('src', '/images/fb-connect.png');
		togglePopup('join-login');
		movePopup('#join-login-popup', null, 550);
	});
	
	$('.btn-submit-username').click(function(event) {
		$.getJSON(urlSaveUsername + '?username=' + $('#set_username').val() + '&audience_id=' + audienceId, function(data){
			if (data.status == 1) {
				// redirect
				window.location = screeningSeatUrl;
			} else {
				// delete username
				$('#set_username').val('');
				$('#username-error').html(data.msg);
			}
		});
	});
	
	$('#step-enter').click(function(event){
		if(audienceId == null) {
			togglePopup('no_ticket');
			return;
		}
		else
		{
			window.location = screeningSeatUrl;
		}
		
		event.stopPropagation();
		event.preventDefault();
	});
})