/**
 *
 */

var screening_room = new Object;
var jsonInProgress = false;
var sUniqueKey = '';
var providerId = 0;

var itRetrievedContacts = 0;

var sharingLinks = new Object();
sharingLinks.fb = 'http://www.facebook.com/sharer.php?u=';
sharingLinks.google = 'http://www.google.com/bookmarks/mark?op=edit&bkmk=';
sharingLinks.twitter = 'http://twitter.com/home?status=Constellation - ';
sharingLinks.myspace = 'http://www.myspace.com/Modules/PostTo/Pages/?u=';

if(typeof collectedEmails == 'undefined')
{
	var collectedEmails = new Array();
}

renderCustomCell = function(date){
	
	var it = 0;
	var customDate = dateFormat(date, "mmm dd, yyyy");

	for(it in dates)
	{
		if(dates[it] == customDate)
		{
			return [ true , 'datepicker-cell-red'];
		}
		
	}
	return [ false , 'datepicker-cell-green'];

};

/**************** checks compatibility of client browser  ***************/

// incompatibility list
//var badBrowsers = [{ 'platform' : 'win', 'browser' : 'msie', 'version' : '6.0' }];

browserCheck = function() {
	
	//return false;
	
	if($.browser.msie)
	{
		if($.browser.version == "6.0")
		{
			return false;
		}
	}
	return true;
};

/************************************************************************/

changeDisplayedScreening = function(dateText, inst) {

	$.ajax({
		url: updateScreeningsUrl,
		type: "POST",
		data: 'date=' + dateText + '&film_id=' + filmId + '&included_from=' + includedFrom,
		success: function(response){
			$('#showtimes-container').html(response);
			$('#showtimes-container').jScrollPane();
			//if(includedFrom.length == 0)
			//{
				bindHostInfoEvents();
			//}
		}
	});
};

screening_room.nextStep = function(e, currentStep, nextStep) {
	// prevent the default form action
	e.preventDefault();

	// submit form information via ajax
	// on error:
	//  show errors & exit, returning false

	// on success:
	//  close form

		switch(currentStep)
		{
			// special case for joining a screening
			case '#join-step-login':
			{
				switch($('#signup-connect-type').val())
				{
					case 'login':
					{						
						screening_room.getJson(stepLoginFormUrl, currentStep, nextStep);
						break;
					}
					case 'username':
					{
						// to do
						screening_room.getJson(stepSetUsernameFormUrl, currentStep, nextStep);
						break;
					}
				}
				
				$('#RES_ID_fb_login_image').attr('src', '/images/fb-connect.png');
				$('#RES_ID_fb_login_image').show();
				
				break;
			}
			case '#step-login':
			{				
				switch($('#step-signup-connect-type').val())
				{
					case 'login':
					{		
						screening_room.getJson(stepLoginFormUrl, currentStep, nextStep);
						break;
					}
					case 'signup':
					{
						screening_room.getJson(stepSignupFormUrl, currentStep, nextStep);
						break;
					}
				}
				break;
			}
			case '#step-schedule':
			{
				screening_room.getJson(stepScheduleFormUrl, currentStep, nextStep);
				break;
			}
			case '#step-invite':
			{
				$('.nr-invitees').html($('#accepted-invites-container li').length);
				screening_room.getJson(stepInviteFormUrl, currentStep, nextStep);
				break;
			}
			case '#step-host-message':
			{
			    screening_room.getJson(stepHostMessageFormUrl, currentStep, nextStep);
			    $('.credit-cards[rel=1]').trigger('click');
				break;
			}
			case '#step-pay':
			{
				screening_room.getJson(urlProcessPayForm, currentStep, nextStep);
				break;
			}
			case '#step-enterCode' :
			{
				screening_room.getJson(stepEnterCodeUrl, currentStep, nextStep);
				break;
			}
		}
		
	    return true;
};

processPaymentStep = function(data) {

	$('.unique_key').val(data.s_unique_key);
	$('#twitter-share-link, #twitter-share').attr('href', data.twitter_share_link);
	$('#facebook-share-link, #fb-share').attr('href', data.facebook_share_link);
	
	if(data.audience_id)
	{
		$('.audience_id').val(data.audience_id);
		if (data.screening_seat_url) 
		{		
			screeningSeatUrl = data.screening_seat_url;
			/*
			$('#step-enter').click(function()
			{
				if ($(this).hasClass('step_current')) 
				{
					document.location.href = data.screening_seat_url;
				}
			});
			*/
		}
		audienceId = data.audience_id;
	}
	else
	{
		if(data.screening_seat_url)
		{
			$('#screening-url').attr('href', data.screening_seat_url);
			$('#screening-url').html(data.screening_seat_url);
		}
		if(data.sharing_url)
		{
			$('#fb-share').attr('href', sharingLinks.fb + data.sharing_url);
			$('#google-share').attr('href', sharingLinks.google + data.sharing_url);
			$('#twitter-share').attr('href', sharingLinks.twitter + data.sharing_url);
			$('#myspace-share').attr('href', "javascript:void(window.open('http://www.myspace.com/Modules/PostTo/Pages/?u='+encodeURIComponent('"+ data.sharing_url +"'),'ptm','height=650,width=1024').focus())");
		}
		
	}
};

screening_room.getJson = function(url, currentStep, nextStep)
{
	if (jsonInProgress == false)
	{
		jsonInProgress = true;
		$(currentStep + ' .overlay').fadeIn();
		
		$.post(url, $(currentStep + ' form').serialize(), function(data){
			if (data == null || data.status == 'fail') {				
				var errors = data.errors;
				var errorContent = '';
				for (label in errors) {
					errorContent += '<p>' + errors[label] + '</p>';
				}
				$(currentStep + ' .error-panel .errors').html(errorContent);
				if (!$(currentStep + ' .error-panel').is(':visible')) {
					$(currentStep + ' .error-panel').animate({
						width: 'toggle'
					}, 500);
				}
			}
			else
				if (data.status == 'success') {
					
					switch (currentStep) {
						case '#join-step-login' :{
							
							// update login click button 
							$('#join-login').html("<span>Change</span> your username settings");
							
							// close the popup
							$('#formlet-login').toggle();
							$('#formlet-other').toggle();
							
							// set the username for checking on entering the theatre
							if(data.full_name)
							{
								$('.full-name-area').html(data.full_name);
								$('#formlet-other-label-full-name').html(data.full_name);
								$('#formlet-other-img-photo-url').attr('src', data.profile_pic);
								
								if(data.payment_info)
								{
									$('#fld-cc_first_name').val(data.payment_info.first_name);
									$('#fld-cc_last_name').val(data.payment_info.last_name);
									$('#fld-cc_number').val(data.payment_info.cc_number);
									$('#fld-cc_security_number').val(data.payment_info.security_code);
									$('#fld-cc_exp_month').val(data.payment_info.expiration_month);
									$('#fld-cc_exp_year').val(data.payment_info.expiration_year);
									$('#fld-cc_address1').val(data.payment_info.b_address_1);
									$('#fld-cc_address2').val(data.payment_info.b_address_2);
									$('#fld-cc_city').val(data.payment_info.b_city);
									$('#fld-cc_zip').val(data.payment_info.b_zip);
									$('#payment-save-my-info').val(1);
								}
							}
							else
							{
								$('.full-name-area').html(data.username);
							}
							
							if(data.email)
							{
								$('#fld-cc_email').val(data.email);
								$('#fld-cc_confirm_email').val(data.email);
							}
							
							// change popup content for logged-in users
							$('#login-icon').attr('src', '/images/' + data.user_type_s + '-connect.png');
							$('#logged-in').show();
							$('#logged-out, .line-container').hide();
							
							doGlobalLogin(data);
							$('.full-name-area').html(data.full_name);
							
							break;
						}
						case '#step-login' :{
							
							$('.full-name-area').html(data.full_name);
							$('#login-icon').attr('src', '/images/' + data.user_type_s + '-connect.png');

							if(data.email)
							{
								$('#fld-cc_email').val(data.email);
								$('#fld-cc_confirm_email').val(data.email);
							}
							
							doGlobalLogin(data);
							
							break;
						}
						case '#step-schedule':{
							if (data.unique_key != 'undefined') {
								$('.unique_key').val(data.unique_key);
								sUniqueKey = data.unique_key;
								if($('#paypal-button').length) {
									$('#paypal-button').attr('href', $('#paypal-button').attr('href') + data.unique_key);
								}
							}
							$('#selected_date').html(data.selected_date);
							$('#selected_time').html(data.selected_time);
							$('#selected_time_part').html(data.selected_time_part + ' ' + data.selected_timezone);
							screeningType = data.selected_type;
							$('#twitter-share-link, #twitter-share').attr('href', data.twitter_share_link);
							$('#facebook-share-link, #fb-share').attr('href', data.facebook_share_link);
							
							break;
						}
						case '#step-pay': {
							processPaymentStep(data);
							break;
						}
						
						case '#step-enterCode':{
							window.location = screeningRoomUrl + data.location;
							break;
						}
					}
					
					if ($(currentStep + ' .error-panel').is(':visible')) {
						$(currentStep + ' .error-panel').animate({
							width: 'toggle'
						}, 500);
					}
					
					//  replace header with ajax response
					//  open next form (if provided)
					if (nextStep)
					{
						// check for browser compatibility
						if(currentStep == '#step-login' && !browserCheck())
						{
							$('#browsers-msg').show();
							$(currentStep).removeClass('step_editing').addClass('step_done');
						}
						else
						{
							// don't do any slide up for optional login step on joining
							if(currentStep != '#join-step-login') 
							{
								$(currentStep).find('form').slideUp('slow');
								$(currentStep).addClass('step_done').removeClass('step_current').removeClass('step_editing');
							}
							
							$(nextStep).find('form').slideDown('slow');
							$(nextStep).addClass('step_current');
						}
					}
					else
					{
						$('#steps-setup').slideUp('slow', function() {
							$('.steps_all').show();
							$('#step-invite').hide();
							$('#step-schedule').remove();
							$('#step-pay').remove();
						});
						$('#step_thanks').slideDown('slow');
						$('#step_thanks').addClass('step_current');
					}
				}
			
				jsonInProgress = false;
				$(currentStep + ' .overlay').fadeOut();
		}, "json");
	}
};

screening_room.logOut = function(new_step)
{
	doGlobalLogout(new_step, 'mainLogout()');
	
	$('#broadcast-activity').hide();
};


var jsonB = null;
var getContacts = function(e) {
	e.preventDefault();
	
	itRetrievedContacts = 0;
	$('#contacts-loading-area').fadeIn();
	
	$.getJSON(urlGetContacts + '?' + 'login[email]=' + escape($('#import-contacts-email').val()) + '&login[password]=' + escape($('#import-contacts-password').val()) + '&login[provider_id]=' + providerId, function(json){
		jsonB = json;
		if(json.response == 'success')
		{
			if ($('#step-invite .error-panel').is(':visible')) {
				$('#step-invite .error-panel').animate({
					width: 'toggle'
				}, 500);
			}
			
			$('#contacts-error-area').html('');
			
			$('#import-contacts-container .login').slideUp('fast');
			$('#import-contacts-container .import-contact-result').slideDown('fast');
			
			$('#contacts-area .content').html();
			
			for(email in json.contacts)
			{
				generateHtmlForContact(email, json['contacts'][email]);
			}
			
			$('#import-contacts-email').val('');
			$('#import-contacts-password').val('');
		}
		else if(json.response == 'failed')
		{
			$('#contacts-error-area').html(json.message);
			
			$('#step-invite .error-panel .errors').html(json.message);
			if (!$('#step-invite .error-panel').is(':visible')) {
				$('#step-invite .error-panel').animate({
					width: 'toggle'
				}, 500);
			}
		}
		
		$('#contacts-loading-area').fadeOut();	
    });
	
	return false;
};

var generateHtmlForContact = function(email, contact) {
	$('#contacts-area .content').append('<div class="formfield"><label id="holder-'+itRetrievedContacts+'"><input type="checkbox" class="checkbox" name="list" /><span class="holder-email" id="holder-email-'+itRetrievedContacts+'">' + email + '</span></label></div>');
	itRetrievedContacts++;
};

var addContactToList = function(val) {
	var trimValue = $.trim(val);
	if($.inArray(trimValue, collectedEmails) == -1)
	{
		collectedEmails.push(trimValue);
		
		len = collectedEmails.length;
		len -= 1;
		
		$('#accepted-invites-container').append('<li title="'+trimValue+'"><div>'+trimValue+'</div><a href="#" class="close-btn">x</a></li>');
		$('#step-invite-form').append('<input type="hidden" id="contact-added-'+len+'" name="contacts[]" value="'+ trimValue +'" />');
		$('#guest-emails').append('<p id="contact-added-'+len+'">'+trimValue+'</p>');
		return false;
	} else {
		return val;
	}	
};

var firstCardSelected = false;

$(document).ready( function() {
	
	$('#showtimes-container').css('height', $('#showtimes-container').height());
	
	$('#btn-preview_invite').click(function() {
		
		var greeting = $('#fld-greeting').val().replace(/\n/g, '<br/>' );
		
		if($('#steps-setup').length !== 0) // we are on setup screening page
		{
			uniqueKey = $('input.unique_key').val();
		}

		$.ajax({
			url: getInviteForPreviewUrl,
			method: 'post',
			data: 'unique_key=' + uniqueKey + '&greeting=' +encodeURIComponent(greeting) /*'screening_type=' + screeningType + '&greeting=' + greeting + '&film_id=' + filmId  + '&date=' + date + '&time=' + time + '&host=' + host + '&inviter=' + inviter*/,
			success: function(html){
				$('#preview-invite-popup .content').html(html);
				$('#preview-invite-popup').show();
			}
			/*error: function(XMLHttpRequest, textStatus, errorThrown){
				$('#preview-invite-popup .content').html();
				$('#preview-invite-popup').show();
			}*/
		});
		
		return false;
	});
	
	$('#close-preview-invite-popup').click(function(){
		$('#preview-invite-popup').hide();
	});
	
	$(document).click(function(){
		$('#preview-invite-popup').hide();
		
		$('.reviews #see-more-review').click(function(e) {
			$('#film-reviews #hidden-reviews').show();
	});
	});
	
	$('#preview-invite-popup').click(function(event){
		event.stopPropagation();
	});
	
	$(".step").click( function(e) {
		//alert('click');
		if ($(this).hasClass('step_next')) {
			
			if($(this).attr('id') == 'step-pay' && !browserCheck())
			{
				$('#browsers-msg').show();
				$(this).removeClass('step_next');
			}
			else
			{
				$('.step_current').removeClass('step_current');
				$(this).find('form').slideDown('slow');
				
				if(this.id == 'step-pay' && firstCardSelected == false)
				{
					firstCardSelected = true;
					//$('.credit-cards[rel=1]').trigger('click');
				}
				$(this).addClass('step_current').removeClass('step_next');
				
				if($(this).attr('id') == 'step-login' && $('#step-pay').hasClass('step_current'))
				{
					$('#step-pay form').slideUp('slow');
				}
			}
		}

		if($(this).attr('id') != 'step-enterCode') {
			if($('#step-enterCode .error-panel').is(':visible')) {
				$('#step-enterCode .error-panel').animate({
					width: 'toggle'
				}, 500);
			}
		}
	});
	
var sendCode = function(e) {
	
	e.preventDefault();
	if($('#step-enterCode #fld-code').val() == '')
	{				
		$('#step-enterCode .error-panel .errors').html('<p>Please specify an exchange code.</p>');
		if (!$('#step-enterCode .error-panel').is(':visible')) {
			$('#step-enterCode .error-panel').animate({
				width: 'toggle'
			}, 500);
		}
		
		return false;
	}
	
	screening_room.nextStep(e,'#step-enterCode','');
};	
	
	$('#step-enterCode #enter-code').click(function(e){ sendCode(e); });
	$('#step-enterCode #fld-code').keypress(function(e) { 
		
		var code = (e.keyCode ? e.keyCode : e.which);
		// submit the form on enter
		if(code == 13)
		{
			sendCode(e);
		}

	});
		  
	$('.step .edit').click( function(e) {
		// stop default action
		
		e.preventDefault();
		
		// close any error panel
		$('.error-panel:visible').animate({width: 'toggle'}, 500);
		
		// close currently open form
		$('.step_current form').slideUp('slow');
		$('.step_current').removeClass('step_current');
		$('.step').removeClass('step_done');
		
		// open the form associated with this edit button
		var new_step = $(this).parents('.step');
		
		if (new_step.attr('id') == 'step-login') 
		{
			screening_room.logOut(new_step);
		}
		else if (new_step.attr('id') == 'step-invite')  
		{ 
			$('#accepted-invites-container li').remove();
			new_step.find('form').slideDown('slow');
			new_step.addClass('step_current').addClass('step_editing').removeClass('step_done');
		}
		else 
		{
			new_step.find('form').slideDown('slow');
			new_step.addClass('step_current').addClass('step_editing').removeClass('step_done');
		}
	});

	$('.help').click( function(e) {
		e.preventDefault();
		$('#'+this.id.replace(/help/,'explain')).show();
	});
	$('.help_close').click( function(e) {
		e.preventDefault();
		$(this).parents('.help_popup').hide();
	});

	/* setup */
	/* Setup 1st Step: */
	$('#step-custom-connect-icon').click(function(){
		//if($(this).hasClass('join'))
		//{
			// join a screening
			// hide the overlay
			//$('#formlet-overlay').hide();
		//}
		//else
		//{
			// host a screening
			$('#step-custom-login-container').slideToggle(0, function() { $('#login-email').focus()});	
			
		//}
		
		return false;
	});
	
	//$('#btn-login, #btn-signup').click(function(){
	//	$('#signup-connect-type').val('login');
	//});
	
	
	$('#main-login-popup').load(function () { 
		$('popup-login-email').focus()
	});
	
	
	/*
	$('#login_img').bind("click", function(){
		$('popup-login-email').focus();
	});	
	*/
	
	$('#step-btn-login').keydown(function(e){
		e.preventDefault();		
		if (e.keyCode == 9) {	
			$('#step-login-email').focus();
		}
	});
	
	$('#step-btn-signup').keydown(function(e){	  	
		e.preventDefault();		
		if (e.keyCode == 9) {
			$('#step-signup-name').focus();
		}
	});
	
	$('#sign-up-arrow').click(function(e){
		e.preventDefault();
		$('#custom-login-container').slideToggle(0, function() { $('#login-email').focus()});	
		/*if($('#custom-login-container').is(':visible')){ 
			$('#custom-login-container').slideUp('slow');
			
			$('#custom-login-container').animate({"left": "+=418px"}, "fast", function() { $('#login-email').focus()});
		}else {
			
			$('#custom-login-container').animate({"left": "-=418px"}, "slow", function() { $('#signup-name').focus()});		
			$('#custom-login-container').slideToggle(0, function() { $('#signup-name').focus()});	
		}*/
	});
	// choose signup panel
	$('#step-choose-signup').click(function(e){
		e.preventDefault();
		$('#step-signup-connect-type').val('signup');		
		$('#step-custom-login-container').animate({"left": "-=418px"}, "slow", function() { $('#step-signup-name').focus()});			
	});
	
	// choose login panel
	$('#step-choose-login').click(function(e){
		e.preventDefault();
		$('#step-signup-connect-type').val('login');		
		$('#step-custom-login-container').animate({"left": "+=418px"}, "slow", function() { $('#step-login-email').focus()});
	});
	
	$('#main-choose-signup').click(function(e){
		e.preventDefault();
		$('#login-signup').animate({"left": "-=400px"}, "slow");
		
	});	
	$('#main-choose-login').click(function(e){
		e.preventDefault();
		$('#login-signup').animate({"left": "+=400px"}, "slow");
		
	});
	var animateOnce = 0;
	$('#main-join-custom-icon').click(function(e){
		if(animateOnce == 0) {
			$('#main-login-signup').animate({"left": "-=400px"}, "slow");
			animateOnce = 1;
		}
	});
	// chose username panel
	/*$('#choose-username').click(function(e){
		e.preventDefault();
		$('#signup-connect-type').val('username');
		
		$('#formlet-username').animate({"left": "+=690px"}, "normal");
		$('#formlet-login').animate({"left": "+=690px"}, "normal");
		$('#formlet-signup').animate({"left": "+=690px"}, "normal");
	});*/
	
	$("#steps-setup .btn-login-signup").click( function(e) {
		screening_room.nextStep(e,'#step-login','#step-pay');
	});
	
	$("#steps-join .btn-login-signup").click( function(e) {
		//screening_room.nextStep(e,'#join-step-login','#step-pay');
		screening_room.nextStep(e,'#step-login','#step-pay');
	});
	
	/*----------------------------------------------------------------*/
	
	$("#steps-setup #btn-set").click( function(e) {
		screening_room.nextStep(e,'#step-schedule','#step-invite');
	});
	/* Setup 3rd Step: */
	$("#steps-setup #btn-preview_invite").click( function(e) {
	});
	$("#steps-setup #btn-invite").click( function(e) {
		screening_room.nextStep(e,'#step-invite','');
	});
	
	$('.choose-contacts-from').click(function(e){
		$('#import-contacts-container .login').show();
		$('#import-contacts-container .import-contact-result').slideUp('fast');
		$('#contacts-area .content').html('');
		
		$('#import-contacts-email').val('');
		$('#import-contacts-password').val('');
		
		e.preventDefault();
		
		imgs = $('#' + this.id + ' img');
		
		$('#contact-source').attr('src', imgs[0].src);
		$('#import-contacts-type').val(this.id);
		$('#import-contacts-container').slideDown('fast');
		
		providerId = this.id;
	});
	
	$('#btn-import').click(function(e){
		getContacts(e);
		
		return false;
	});
	
	// Handle label show/hide.
	$('.with-label').focus(function(){
		$('#'+this.id+'-label').hide();
		if($(this).attr('id') == 'fld-greeting')
		{
			$(this).inputlimiter({limit: 150});
		}
	}).blur(function(){
		if ($(this).val() == '')
		{
			$('#'+this.id+'-label').show();
		}
	});
	/*---------------------------------------------------*/
	
	function isValidEmailAddress(emailAddress) {
		var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
		return pattern.test(emailAddress);
	};
	
	$('.accept-contacts').click(function(e){
		e.preventDefault();
		var currentEmails = new Array();
		
		target = this.id;
		
		if(target == 'grab-contacts')
		{
			var inputs = $('.content input[type=checkbox]:checked');
			for(it = 0; it < inputs.length; it++)
			{
				parentId = inputs[it].parentNode.id;
				holderEmailId = parentId.replace('holder-', '');
				holderEmailId = 'holder-email-' + holderEmailId;
				email = $('#' + holderEmailId).html();
				currentEmails.push(email);
			}
		}
		else
		{
			if($('#fld-invites').val() != '')
			{
				currentEmails = $('#fld-invites').val().split(/,|\r\n|\r|\n/);
			}
		}
		//console.dir(currentEmails);
		var hasError = false;
		$.each(currentEmails, function(i, val){
			if ($.trim(val) != '' && !isValidEmailAddress($.trim(val)))
			{
				var errorContent = '<p>Invalid email address: '+val+' ...</p>';
				$('#step-invite .error-panel .errors').html(errorContent);
				if (!$('#step-invite .error-panel').is(':visible')) 
				{
					$('#step-invite .error-panel').animate({width: 'toggle'}, 500);
				}
				
				hasError = true;
				return false;
			}
		});
		
		if(!hasError)
		{
			if ($('#step-invite .error-panel').is(':visible')) 
			{
				$('#step-invite .error-panel').animate({width: 'toggle'}, 500);
			}
			$('#guest-emails').html('');
			var duplicateEmail = '';
			$.each(currentEmails, function(i, val){
				
				if(addContactToList(val) != false)
				{
					duplicateEmail = duplicateEmail + "<p>"+addContactToList(val)+"</p>";

					var errorContent = '<p>Invitation already send to: '+duplicateEmail+' </p>';
					$('#step-invite .error-panel .errors').html(errorContent);
					if (!$('#step-invite .error-panel').is(':visible')) 
					{
						$('#step-invite .error-panel').animate({width: 'toggle'}, 500);
					}
				}
			});	
			$('#accepted-invites-container a.close-btn').click(function(e){
				e.preventDefault();
				var parent = $(this).parent();
				var elementExists = $.inArray(parent.find('div').html(), collectedEmails);
				
				$.getJSON(checkBeforeDelete+"?unique_key="+$('.unique_key').val()+"&audience_id="+$('.audience_id').val(), 
					function(data) {
						  var it;
						  for(it in data)
						  {
							  $('#accepted-invites-container li[title="'+data[it]+'"] a.close-btn').remove();
						  }
						  
						  if(parent.children('a.close-btn').length)
						  {
							  if(elementExists != -1)
							  {
								  collectedEmails[elementExists] = '';
							  }
							  
							  $('#contact-added-' + elementExists).remove();
							  $('#guest-emails #contact-added-' + elementExists).remove();
							  parent.remove();
						  }
						}
				);
				
			});
			
			if(target == 'text-contacts')
			{
				$('#fld-invites').val('');
			}
		}		
	});
	/*----------------------------------------------------------------*/
	
	/* Setup: 4th Step [ Host Message ] */
	
	/*$('#steps-setup #btn-host-msg').click(function(e){
		var content = tinyMCE.get('post-message-area').getContent();
		$('#post-message-area').html(content);
		screening_room.nextStep(e, '#step-host-message', '#step-pay');
	});*/
	$('#steps-setup #btn-host-msg').click(function(e){
		//var content = document.getElementsByTagId('post_message_area');
		//$('#post_message_area').html(content);
		screening_room.nextStep(e, '#step-host-message', '#step-pay');
	});
	/*----------------------------------------------------------------*/
	
	$("#steps-setup #btn-pay").click( function(e) {
		screening_room.nextStep(e,'#step-pay', '#step-schedule');
	});
	$("#steps-setup #btn-pay1").click( function(e) {
		screening_room.nextStep(e,'#step-pay', '#step-schedule');
	});
	
	$("#step_thanks #btn-review_invites").click( function(e) {
		togglePopup('review_invitees');
		movePopup('#review_invitees-popup', 110, 1);
	});
	/*$("#step_thanks #btn-review_invites").click( function(e) {
		$('.popup-overlay').show();
		$('.popup-container').show();
		
	});
	$("#review-invitees-popup .popup-close").click( function(e){
		$('.popup-container').hide();
		$('.popup-overlay').hide();	
	});*/
	$("#steps-setup #btn-invite_more").click( function(e) {
		
	});
	$("#steps-setup #btn-send_invites").click( function(e) {
	});

	/* join */
	
	/* removed because login step was added. */
	/*
	$("#steps-join .btn-login-signup").click( function(e) {
		//screening_room.nextStep(e,'#step-login','#step-pay');
		$('.step_current form').slideUp('slow');
		$('.step_current').removeClass('step_current');
		$('#step-pay').slideDown('slow');
		$('#step-pay').addClass('step_current');
		$('.step_current').removeClass('step_next');
	});
	*/
	
	//$('#steps-join #btn-submit-username').click(function(e) {
	//	screening_room.nextStep(e,'#express-signup-step','#step-pay');
	//});
	
	$("#steps-join #btn-preview_invite").click( function(e) {
	});
	$("#steps-join #btn-pay").click( function(e) {
		screening_room.nextStep(e,'#step-pay','#step-invite');
	});
	$("#steps-join #btn-pay1").click( function(e) {
		screening_room.nextStep(e,'#step-pay','#step-invite');
	});
	$("#steps-join #btn-invite").click( function(e) {
		screening_room.nextStep(e,'#step-invite','#step-enter');
	});
	$("#steps-join #btn-skip").click( function(e) {
		screening_room.nextStep(e,'#step-invite','#step-enter');
	});
	$("#steps-join #btn-review_invites").click( function(e) {
	});
	$("#steps-join #btn-invite_more").click( function(e) {
	});
	$("#steps-join #btn-send_invites").click( function(e) {
	});
	
	$("#fld-date").datepicker( 
		{ 
			minDate: new Date(), 
			showOn: 'both',
			buttonImage: '/images/icon-calendar.png',
			buttonImageOnly: true
			//maxDate: nrMaxDays
		} 
	);
	$("#fld-date-for-showtimes").datepicker( 
			{ 
				//minDate: new Date(), 
				dateFormat: 'M dd, yy',
				showOn: 'both',
				buttonImage: '/images/icon-calendar[large].png',
				buttonImageOnly: true,
				beforeShowDay: renderCustomCell,
				onSelect: changeDisplayedScreening
				//maxDate: nrMaxDays
			} 
	);
	
	$("#click-more-showtimes").click(function (e) {
		e.preventDefault();
		$("#fld-date-for-showtimes").focus();		
	});
	
	$("#fld-date-picker").click( function(e) {
		e.preventDefault();
		$("#fld-date").focus();
	});
	
	if ($('#post-message-area').length >= 1) 
	{
		tinyMCE.init({
			// General options
			mode: "exact",
			elements: "post-message-area",
			theme: "advanced",
			
			// Theme options
			plugins : "safari,advlink,advhr,fullscreen,print,media,preview,paste",
			theme_advanced_toolbar_location: "top",
			theme_advanced_toolbar_align: "left",
			theme_advanced_statusbar_location: "bottom",
			theme_advanced_resizing: true,
			
			theme_advanced_buttons1 : "fullscreen,preview,print,insertfile,media,removeformat",
			theme_advanced_buttons2 : "bold,italic,underline,forecolor,fontselect,fontsizeselect,pasteword,pastetext",
			theme_advanced_buttons3 : "image,link,unlink,code",
			// Drop lists for link/image/media/template dialogs
			template_external_list_url: "js/template_list.js",
			external_link_list_url: "js/link_list.js",
			external_image_list_url: "js/image_list.js",
			media_external_list_url: "js/media_list.js",
			
			width: 400
		});
	}
	
	$('.credit-cards').click(function(event) {
		$('#credit-card-type').val(event.currentTarget.rel);
		$('.credit-cards').removeClass('selected');
		$('.credit-cards[rel=' + event.currentTarget.rel + ']').addClass('selected');
	});
	
	if(typeof ccType != 'undefined') {
		$('.credit-cards[rel=' + ccType + ']').trigger('click');
	}
	
	if(typeof paymentData != 'undefined') {
		processPaymentStep(paymentData);
	}
	$('#btn-invite-more').click( function() {
		$('#step_thanks').addClass('step_done').removeClass('step_current');
		$('#step_thanks').slideUp('slow');
		$('#step-invite').slideDown('slow');
	
	});
	
	// about Q&A sidepanel
	$('#about-qanda').click(function(){
		togglePopup('about-qanda');
	});
	
	// what is this link (on setup page)
	$('#about-setup').click(function(){
		togglePopup('about-setup');
	});
	
	//view already invited popup
	$('#view-already-invited').click(function(){
		movePopup('#already-invited-popup', 100, 85);
		togglePopup('already-invited');
	});
	
	var ContactImportChecked = false;
	$('#checkAll').click(function() {
		var checkAll = document.getElementsByName("list");
		if (ContactImportChecked == false)
	      {
			ContactImportChecked = true;
	      }
	    else
          {
	    	ContactImportChecked = false;
          }
		for (var i = 0; i < checkAll.length; i++) 
		{
			checkAll[i].checked = ContactImportChecked;
		}
	 
	});
	
	$('#step-login .step_title').click(function() {
		replaceFbIcon();
	});
	//$('#fld-greeting').inputlimiter({limit: 200});
	
	//bind click event for facebook share
	$('#facebook-share-link').click(function(event){
		window.open($(this).attr('href'),'sharer','toolbar=0,status=0,width=626,height=436');
		
		return false;
	});
	
    if (typeof broadcastStatus == 'undefined') broadcastStatus = 0;
	if(broadcastStatus == 1)
	{
		$('.broadcast-yes').show();
	}else {
		$('.broadcast-no').show();
	}
	$('.broadcast-yes').click(function(e){
		//window.open($(this).attr('href'),'login','toolbar=0,status=0,width=626,height=436');
		$.ajax({
			url: startActivityBroadcast,
			method: 'post',
			data: 'broadcast=0&userId='+userId,
			success: function(e){
				$('.broadcast-yes').hide();
				$('.broadcast-no').show();
			}
		});
	});
	$('.broadcast-no').click(function(e){
		$.ajax({
			url: startActivityBroadcast,
			method: 'post',
			data: 'broadcast=1&userId='+userId,
			success: function(e) {
				$('.broadcast-no').hide();	
				$('.broadcast-yes').show();
			}
		});
	});
});

setupLoginSuccess = function() {
	
	$('.step_current form').slideUp('slow');
	$('.step_current').removeClass('step_current');
	
	// check for browser compatibility
	if(browserCheck())
	{
		$('#step-pay').slideDown('slow');
		$('#step-pay').addClass('step_current');
		$('.step_current').removeClass('step_next');
		$('#step-pay-form').show();
	}
};

resetFormlets = function() {
	$('.step_current form').slideUp('slow');
	$('.step_current').removeClass('step_current');
	$('.step_next').removeClass('step_next');
	$('.error-panel').hide();
	
	new_step = $('#step-login');
	new_step.find('form').slideDown('slow');
	new_step.addClass('step_current').addClass('step_editing').removeClass('step_done');
};
