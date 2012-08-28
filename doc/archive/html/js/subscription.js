function newSubscription(form,url,callback) {
    var vals = form.SubscriptionFormToDict();
    $.postSubscriptionJSON(
      url, 
      vals,
      callback
    );
}

jQuery.postSubscriptionJSON = function(url, args, callback) {
    $.ajax({url: url, 
      data: $.param(args), 
      dataType: "text", 
      type: "POST",
      success: function(response) {
    	 if (callback) callback(response);
      }, 
      error: function(response) {
    	 console.log("ERROR:", response)
      }
    });
};

jQuery.fn.SubscriptionFormToDict = function() {
   var fields = this.serializeArray();
    var json = {}
    for (var i = 0; i < fields.length; i++) {
	json[fields[i].name] = fields[i].value;
    }
    if (json.next) delete json.next;
    return json;
}

var subscription = {
  
  itRetrievedContacts: 0,
  detailSubmitted: false,
  purchaseSubmitted: false,
  currentPrice: 0,
                      
  init: function() {
    
  	$("#purchase_subscription").click(function() {
      subscription.detail();
    });
    
  },
  
  detail : function() {
    
    subscription.setPeriod();
    
    $("#subscription_details").fadeIn('slow');
    
    $('#btn-gift').click(function(e){
  		e.preventDefault();
  		$("#form-subscription-step").val("gift");
      $("#subscription_info").fadeOut("slow").queue(function() {
        $("#payment_info").hide();
        $("#gift_info").fadeIn("slow");
        $('#subscription_info').clearQueue();
  	   });
    });
  	
  	$('#btn-subscription').click(function(e){
  		e.preventDefault();
      $("#form-subscription-step").val("subscription");
      $("#gift_info").fadeOut("slow").queue(function() {
        $("#payment_info").hide();
        $("#subscription_info").fadeIn("slow");
        $('#gift_info').clearQueue();
      });
  	});
  	
  	$('#btn-gift-next').click(function(e){
  		console.log("Detail Step");
      e.preventDefault();
  		if (subscription.validateDetail()) {
  		  subscription.detailSubmitted = true;
  		  if ($("#form-subscription-step").val() == 'gift') {
          subscription.setTicketInfo();
          $("#ticket-payment-details").show();
        } else if ($("#form-subscription-step").val() == 'subscription') {
  		    subscription.setSubscriptionInfo();
  		    $("#subscription-payment-details").show();
  		  }
        newSubscription($("#detail-form"),
                    "/subscription/detail",
                    subscription.detailResult);
      }
  	});
  	
    $('#subscription_date').datetimepicker({
        minDate: new Date(),
    });
    //$('#fld-host-date').datetimepicker();
    
    $('#fld-subscription_term').change(function() {
      subscription.setPeriod();
    });
    
    $('#fld-subscription_period').change(function() {
      subscription.setPeriod();
    });
  },
  
  validateDetail: function() {
    var err = 0;
    if ($("#form-subscription-step").val() == "gift") {
      if ($("#fld-gift_ticket_number").val() == '') { $("#fld-gift_ticket_number").css('background','#ff6666'); err++; } else { $("#fld-gift_ticket_number").css('background','#A5D0ED'); }
    } else if ($("#form-subscription-step").val() == "subscription") {
      if ($("#fld-subscription_ticket_number").val() == '') { $("#fld-subscription_ticket_number").css('background','#ff6666'); err++; } else { $("#fld-subscription_ticket_number").css('background','#A5D0ED'); }
      if ($("#subscription_date").val() == '') { $("#subscription_date").css('background','#ff6666'); err++; } else { $("#subscription_date").css('background','#A5D0ED'); }
      if ($("#fld-subscription_period").val() == '') { $("#fld-subscription_period").css('background','#ff6666'); err++; } else { $("#fld-subscription_period").css('background','#A5D0ED'); }
    }
    if (err > 0) {
      return false;
    }
      return true;
  },
  
  setTicketInfo: function() {
    $("#ticket_number").html($("#fld-gift_ticket_number").val());
    $("#ticket_price").html($("#fld-gift_ticket_price").val());
    var num = new Number( $("#fld-gift_ticket_number").val() * $("#fld-gift_ticket_price").val() );
    subscription.currentPrice = num.toFixed(2);
    $("#total_price").html(subscription.currentPrice);
  },
  
  setSubscriptionInfo: function() {
    $("#subscription_ticket_number").html();
    $("#subscription_start_date").html();
    $("#subscription_term").html();
    $("#subscription_period").html();
    $("#subscription_ticket_price").html();
    $("#subscription_total_price").html();
  },
  
  detailResult: function (response) {
    var response = eval("(" + response + ")");
    if (response.subscriptionResponse.status == "success") {
      $("#subscription").html(response.subscriptionResponse.screening);
      $("#paypal-button").attr("href","/services/Paypal/express/subscription?vars="+response.subscriptionResponse.screening+"-0");
      $("#subscription_details").fadeOut("slow");
      subscription.pay();
    } else {
      $("#purchase_errors").html(response.subscriptionResponse.message);
      subscription.detailSubmitted = false;
      subscription.detail();
    }
  },
  
  pay : function () {
    
    $("#subscription_purchase").fadeIn('slow');
    
    $("#subscription_purchase_submit").click(function(){
      if (subscription.validatePurchase()) {
        subscription.purchaseSubmitted = true;
        newSubscription($("#subscription_purchase_form"),
                    "/subscription/purchase/"+$("#subscription").html(),
                    subscription.purchaseResult);
        //$("#host_purchase_form").submit();
      }
    });
  	
  },
  
  purchaseResult: function (response) {
    console.log("CONFIRMED!");
    var response = eval("(" + response + ")");
    if (response.subscriptionResponse.status == "success") {
      $("#subscription").html(response.subscriptionResponse.screening);
      $("#purchase_result").html(response.subscriptionResponse.result);
      $("#subscription_purchase").fadeOut();
      subscription.invite();
    } else {
      $("#purchase_errors").html(response.subscriptionResponse.message);
      subscription.purchaseSubmitted = false;
      subscription.pay();
    }
  },
  
  validatePurchase: function() {
    console.log("Purchase Here");
    var err = 0;
    if ($("#fld-cc_first_name").val() == '') { $("#fld-cc_first_name").css('background','#ff6666'); err++; } else { $("#fld-cc_first_name").css('background','#A5D0ED'); }
    if ($("#fld-cc_last_name").val() == '') { $("#fld-cc_last_name").css('background','#ff6666'); err++; } else { $("#fld-cc_last_name").css('background','#A5D0ED'); }
    if (! subscription.isValidEmailAddress($("#fld-cc_email").val())) { $("#fld-cc_email").css('background','#ff6666'); err++; } else { $("#fld-cc_email").css('background','#A5D0ED'); }
    if (! subscription.isValidEmailAddress($("#fld-cc_confirm_email").val())) { $("#fld-cc_confirm_email").css('background','#ff6666'); err++; } else { $("#fld-cc_confirm_email").css('background','#A5D0ED'); }
    if (subscription.isValidEmailAddress($("#fld-cc_email").val()) && subscription.isValidEmailAddress($("#fld-cc_confirm_email").val())) {
      if ($("#fld-cc_email").val() != $("#fld-cc_confirm_email").val()) { $("#fld-cc_email").css('background','#ff6666'); $("#fld-cc_confirm_email").css('background','#ff6666'); err++; } else { $("#fld-cc_email").css('background','#A5D0ED'); $("#fld-cc_confirm_email").css('background','#A5D0ED'); }
    }
    if ($("#fld-cc_number").val() == '') { $("#fld-cc_number").css('background','#ff6666'); err++; } else { $("#fld-cc_number").css('background','#A5D0ED'); }
    if ($("#fld-cc_security_number").val() == '') { $("#fld-cc_security_number").css('background','#ff6666'); err++; } else { $("#fld-cc_security_number").css('background','#A5D0ED'); }
    if ($("#fld-cc_address1").val() == '') { $("#fld-cc_address1").css('background','#ff6666'); err++; } else { $("#fld-cc_address1").css('background','#A5D0ED'); }
    if ($("#fld-cc_city").val() == '') { $("#fld-cc_city").css('background','#ff6666'); err++; } else { $("#fld-cc_city").css('background','#A5D0ED'); }
    if ($("#fld-cc_zip").val() == '') { $("#fld-cc_zip").css('background','#ff6666'); err++; } else { $("#fld-cc_zip").css('background','#A5D0ED'); }
    
    if ((err > 0) || (subscription.purchaseSubmitted)) {
      return false;
    }
      return true;
  },
  
  setPeriod : function() {
    var period = '';
      switch($("#fld-subscription_term option:selected").val()) {
        case "weekly":
          period = 'week(s)';
          break;
        case "bi-weekly":
          period = '('+($("#fld-subscription_period").val()/2) + ') month(s)';
          break;
        case "monthly":
          period = 'month(s)';
          break;
        case "yearly":
          period = 'year(s)';
          break;
      }
      $("#period-term").html(period);
  },
  
  invite : function() {
    
    $("#subscription_invite").fadeIn();
    
    $("#steps-join #btn-invite").click( function(e) {
      e.preventDefault();
      $('.nr-invitees').html($('#accepted-invites-container li').length);
      subscription.sendInvites();
  		//subscription.nextStep(e,'#step-invite','#step-enter');
  	});
    
    $("#steps-join #btn-skip").click( function(e) {
  		e.preventDefault();
      $("#step-invite").attr("class","step step_done");
      $("#step-enter").attr("class","step step_next");
  	});
  	
  	$("#step-invite .edit").click( function(e) {
  		e.preventDefault();
      $("#step-invite").attr("class","step step_current");
      $("#step-enter").attr("class","step");
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
  	
  	$('#close-preview-invite-popup').click(function(){
  		$('#preview-invite-popup').hide();
  	});
  	
  	$('#preview-invite-popup').click(function(event){
  		event.stopPropagation();
  	});
  		
  	$('#btn-invite-more').click( function() {
  		$('#step_thanks').addClass('step_done').removeClass('step_current');
  		$('#step_thanks').slideUp('slow');
  		$('#step-invite').slideDown('slow');
  	
  	});
  	
  	/* PREVIEW INVITE */
  	$('#btn-preview_invite').click(function() {
  		
  		if($('#steps-setup').length !== 0) // we are on setup screening page
  		{
  			uniqueKey = $('input.unique_key').val();
  		}
      
      var args = {"screening":$('#screening').html(),
                  "message":$('#fld-greeting').val().replace(/\n/g, '<br/>' )};
      
      $.ajax({
  			url: '/services/Invite',
  			data: $.param(args), 
        type: "GET", 
        cache: false, 
        dataType: "html", 
        success: function(html){
  				$('#preview-invite-popup .content').html(html);
  				$('#preview-invite-popup').show();
  			}
  			
  		});
  		
  		return false;
  	});
  	/* END PREVIEW INVITE */
  	
  	/* INVITATION STEPS */
  	//Show the appropriate Form for Input
    $('.choose-contacts-from').click(function(e){
  		$('#import-contacts-container .login').show();
  		$('#import-contacts-container .import-contact-result').slideUp('fast');
  		$('#contacts-area .content').html('');
  		
  		$('#import-contacts-email').val('');
  		$('#import-contacts-password').val('');
  		
  		e.preventDefault();
  		
  		imgs = $('#' + this.id + ' img');
  		if (this.id == "other") {
        $("#provider-select").show(); 
      } else {
        $("#provider-select").hide(); 
      }
  		$('#contact-source').attr('src', imgs[0].src);
  		$('#import-contacts-type').val(this.id);
  		$('#import-contacts-container').slideDown('fast');
  		
  		
  		$('#import-contacts-provider').val( this.id );
  	});
  	
  	$('#btn-import').click(function(e){
  		subscription.getContacts(e);
  		
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
  	
  	$('.accept-contacts').click(function(e){
  		e.preventDefault();
  		var currentEmails = new Array();
  		
  		target = this.id;
  		
  		if(target == 'grab-contacts') {
  			var inputs = $('.content input[type=checkbox]:checked');
  			for(it = 0; it < inputs.length; it++)
  			{
  				parentId = inputs[it].parentNode.id;
  				holderEmailId = parentId.replace('holder-', '');
  				holderEmailId = 'holder-email-' + holderEmailId;
  				email = $('#' + holderEmailId).html();
  				currentEmails.push(email);
  			}
  		} else {
  			if($('#fld-invites').val() != '') {
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
  		
  		if(!hasError) {
  			if ($('#step-invite .error-panel').is(':visible')) 
  			{
  				$('#step-invite .error-panel').animate({width: 'toggle'}, 500);
  			}
  			$('#guest-emails').html('');
  			var duplicateEmail = '';
  			$.each(currentEmails, function(i, val){
  				
  				if(subscription.addContactToList(val) != false)
  				{
  					duplicateEmail = duplicateEmail + "<p>"+subscription.addContactToList(val)+"</p>";
  
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
  				$('#accepted-invites-container li[title="'+parent.find('div').html()+'"] a.close-btn').remove();
  				//$('#contact-added-' + elementExists).remove();
				  //$('#guest-emails #contact-added-' + elementExists).remove();
				  parent.remove();
  				
          /*			  
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
  				);*/
  				
  			});
  			
  			if(target == 'text-contacts')
  			{
  				$('#fld-invites').val('');
  			}
  		}
  	});
    
  },
  
  showErrors : function( step ) {
  	if (! $("#" + step + ' .error-panel').is(':visible')) {
  	 	$("#" + step + ' .error-panel').animate({
  			width: 'toggle'
  		}, 500);
  	}
  },
  
  getContacts : function(e) {
    e.preventDefault();
    
    itRetrievedContacts = 0;
    $('#contacts-loading-area').fadeIn();
    
    var args = {"email":$('#import-contacts-email').val(),
                "password":$('#import-contacts-password').val(),
                "provider":$('#import-contacts-provider').val(),
                "provider-alt":$('#provider-alternate').val()};
    
    $.ajax({
      url: '/services/ContactGrabber', 
      data: $.param(args), 
      type: "GET", 
      cache: false, 
      dataType: "json", 
      success: function(response) {
          subscription.populateContacts( response );
      }, error: function(response) {
          console.log("ERROR:", response);
          $('#contacts-error-area').html("There was a communication error, please try again.");
      }
    });
   }, 
   
   populateContacts: function ( json ){
    
    if (json.result == 'success') {
      if ($('#step-invite .error-panel').is(':visible')) {
        $('#step-invite .error-panel').animate({
        width: 'toggle'
        }, 500);
      }
      
      $('#contacts-error-area').html('');
      
      $('#import-contacts-container .login').slideUp('fast');
      $('#import-contacts-container .import-contact-result').slideDown('fast');
      
      $('#contacts-area .content').html();
      
      for(email in json.emails){
        console.log(json.emails[email]);
        subscription.generateHtmlForContact(json.emails[email]);
      }
      
      $('#import-contacts-email').val('');
      $('#import-contacts-password').val('');
      
    } else if(json.result == 'failure') {
    
      $('#contacts-error-area').html(json.message);
      
      $('#step-invite .error-panel .errors').html(json.message);
      if (!$('#step-invite .error-panel').is(':visible')) {
        $('#step-invite .error-panel').animate({
        width: 'toggle'
        }, 500);
      }
    }
      
    $('#contacts-loading-area').fadeOut();	
    
    return false;
    
  },
  
  generateHtmlForContact : function(email) {
  	$('#contacts-area .content').append('<div class="formfield"><label id="holder-'+subscription.itRetrievedContacts+'"><input type="checkbox" class="checkbox" name="list" /><span class="holder-email" id="holder-email-'+subscription.itRetrievedContacts+'">' + email.email + '</span></label></div>');
  	subscription.itRetrievedContacts++;
  },
  
  addContactToList : function(val) {
  	var trimValue = $.trim(val);
  	if($.inArray(trimValue, collectedEmails) == -1)
  	{
  		collectedEmails.push(trimValue);
  		
  		len = collectedEmails.length;
  		len -= 1;
  		
  		$('#accepted-invites-container').append('<li title="'+trimValue+'"><div>'+trimValue+'</div><a href="#" class="close-btn">x</a></li>');
  		$('#step-invite-form').append('<input type="hidden" id="contact-added-'+len+'" name="contacts[]" value="'+ trimValue +'" />');
  		$('#guest-emails').append('<p id="contact-added-'+len+'">'+trimValue+'</p>');
  		$('.nr-invitees').html($('#accepted-invites-container li').length);
    
      return false;
  	} else {
  		return val;
  	}	
  },
  
  isValidEmailAddress : function (emailAddress) {
	 var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
	 return pattern.test(emailAddress);
  },

  sendInvites: function() {
    
    $('#inviting-popup').show();
    if ($('#accepted-invites-container li').length == 0) {
      $('#inviting-errors').attr("style","color: red").html("There are no people to invite,<br /> please add some email addresses and try again..");
      $('#inviting-popup').delay(2000).fadeOut(400).queue(function() {
        $('#inviting-errors').html('');
      });
      return;
    }
    emails = [];
    j=0;
    $('#accepted-invites-container').children('li').each(function(idx, elm) {
        emails.push(elm.title);
        j++;
    });
    var args = {"emails":emails,
                "screening":$('#screening').html(),
                "message":$('#fld-greeting').val().replace(/\n/g, '<br/>' )};
    
    $.ajax({
      url: '/services/Invite/send', 
      data: $.param(args), 
      type: "GET", 
      cache: false, 
      dataType: "json", 
      success: function(response) {
          subscription.finishInvites( response );
      }, error: function(response) {
          console.log("ERROR:", response);
          $('#inviting-errors').attr("style","color: red").html("There was a communication error, please try again.");
          $('#inviting-popup').delay(2000).fadeOut(400).queue(function(){
            $('#inviting-errors').html();
            $('#inviting-popup').clearQueue();
          });
      }
    });
		//movePopup('#inviting-popup', 100, 85);
		//togglePopup('#inviting-popup');
  },
  
  finishInvites: function( response ) {
    if (response.result == "success") {
      $('#accepted-invites-container li').remove();
      $('#inviting-errors').attr("style","color: grey").html(response.message);
      $('#inviting-popup').delay(2000).fadeOut(400).queue(function() {
        $("#step-invite").attr("class","step step_done");
        $("#step-enter").attr("class","step step_next");
        $('#inviting-errors').html();
        $('#inviting-popup').clearQueue();
      });
    } else {
      $('#inviting-errors').attr("style","color: red").html(response.message);
       $('#inviting-popup').delay(2000).fadeOut(400).queue(function() {
        $('#inviting-errors').html();
        $('#inviting-popup').clearQueue();
       });
    }
  },
  
  code : function() {
  
    console.log("Doing the Code");
  	/* ENTER CODE */
  	$('#step-enterCode #enter-code').click(function(e){ 
      subscription.sendCode(e); 
    });
  	$('#step-enterCode #fld-code').keypress(function(e) { 
  		var code = (e.keyCode ? e.keyCode : e.which);
  		// submit the form on enter
  		if(code == 13) {
  			subscription.sendCode(e);
  		}
  	});
  	/* END ENTER CODE */
  	
  },
  
  sendCode : function(e) {
  	
    console.log("Sending Code");
  	e.preventDefault();
  	if($('#step-enterCode #fld-code').val() == '')
  	{				
  		$('#step-enterCode .error-panel .errors').attr("style","color: red").html('<p>Please specify an exchange code.</p>');
  		if (!$('#step-enterCode .error-panel').is(':visible')) {
  			$('#step-enterCode .error-panel').animate({
  				width: 'toggle'
  			}, 500);
  		}
  		
  		return false;
  	}
  	
  	var args = {'ticket': $('#step-enterCode #fld-code').val(),
                'screening': $('#screening').html()}
  	$.ajax({
      url: '/services/Exchange', 
      data: $.param(args), 
      type: "POST", 
      cache: false, 
      dataType: "json", 
      success: function(response) {
        if (response.result == "success") {
          console.log("SUCCESS!");
          console.log(response.theurl);
          window.location = response.theurl;
          //window.location = "http://dev.constellation.tv/screening/aUSXnSKdM2c1s4j/invite";
        } else {
          $('#step-enterCode .error-panel .errors').html(response.message);
        }
      }, error: function(response) {
          console.log("ERROR:", response);
          $('#step-enterCode .error-panel .errors').attr("style","color: red").html("There was an error, please try again.");
      }
    });
  }
  
}

$(document).ready(function(){
  if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
 
	subscription.init();
	
});
