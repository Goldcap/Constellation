var invite = {
  
  itRetrievedContacts: 0,
  collectedEmails : new Array(),
  purchaseSubmitted : false,
  currentPrice: 0,
  priceDiscount: .25,
  gbip: false,
                               
  init: function() {
  
    if ($("#gbip").html() == 1) {
      invite.geoblock();
    }
    
    $(".popup-close").click( function(e) {
      $("#screening_invite").fadeOut("slow");
    });
    
    $(".screening_link").click( function(e) {
      e.preventDefault();
      if ($('#screening_invite').length > 0) {
        console.log("Showing Screening Invite");
        $("#screening").html( $(this).attr("title") );
        invite.invite();
      }
    });
  },
  
  invite : function() {
    
   $("#add_invite").editable(function(value, settings) { 
       //console.log(value);
       invite.addContactToList(value);
       return "Click To Add Email";
    }, { 
       cssclass : 'invite_input',
       height: '12',
       width: '150',
       type    : 'textarea',
       submit  : 'add'
   });
    
    $(".current_screening_time").html($("#time_"+$("#screening").html()).html());
    $(".current_screening_host").html($("#host_"+$("#screening").html()).html());
    //invite.currentPrice = $("#cost_"+$("#screening").html()).html();
    //invite.updatePrice( invite.currentPrice );
    
    $("#screening_invite").fadeIn();
    
    $("#btn-invite").click( function(e) {
      e.preventDefault();
      //invite.updateCount();
      invite.sendInvites();
  	});
    
    //view already invited popup
  	$('#view-already-invited').click(function(){
  		movePopup('#already-invited-popup', 100, 85);
  		togglePopup('already-invited');
  	});
  	
  	invite.getInvites();
  	
    $("#step_thanks #btn-review_invites").click( function(e) {
  		togglePopup('review_invitees');
  		movePopup('#review_invitees-popup', 110, 1);
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
                  "message":$('#invite-fld-greeting').val().replace(/\n/g, '<br/>' )};
      
      $.ajax({
  			url: '/services/Invite',
  			data: $.param(args), 
        type: "GET", 
        cache: false, 
        dataType: "html", 
        success: function(html){
  				$('#preview-invite-popup .invite-content').html(html);
  				$('#preview-invite-popup').show();
  			}
  			
  		});
  		
  		return false;
  	});
  	/* END PREVIEW INVITE */
    
  },
  
  getInvites: function() {
    
  	/* INVITATION STEPS */
  	//Show the appropriate Form for Input
    $('.import_click').click(function(e){
      
      $("#service_name").html($(this).attr('name'));
      
  		$('#import-contacts-container .login').fadeIn("slow");
  		
  		$('#import-contacts-email').val('');
  		$('#import-contacts-password').val('');
  		
      $("#import-contacts-email").watermark("EMAIL");
      $("#import-contacts-password").watermark("PASSWORD");
      
  		e.preventDefault();
  		
      $('#import-contacts-container').slideDown('fast');
  		$('#import-contacts-provider').val( $(this).attr("name") );
  		
  	});
  	
  	$('#btn-import').click(function(e){
  	  
      e.preventDefault();
      
      if (($('#import-contacts-email').val() == '') || ($('#import-contacts-password').val() == '') || ($('#import-contacts-email').val() == 'EMAIL') || ($('#import-contacts-password').val() == 'PASSWORD')) {
        $('#contacts-error-area').html("Please enter a valid username and password.");
      } else {
  		  invite.getContacts(e);
  		}
  		return false;
  	});
  	
  	$("#invite-fld-greeting").inputlimiter({
                              limit: 150,
                              boxId: 'invite-textbox-limit',
                              remTextHideOnBlur: 'false'}
                              );
  	
  },
  
  removeEmail: function (email) {
    console.log("Removing!");
		$('#accepted-invites-container li[title="'+email+'"] a.remove-btn').remove();
		invite.collectedEmails = jQuery.grep(invite.collectedEmails, function(value) {return value != email;});
    $('#accepted-invites-container li[title="'+email+'"]').remove();
		
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
          invite.populateContacts( response );
      }, error: function(response) {
          error.showError('notice','communication error','Please try again'); 
          $("#contacts-loading-area").fadeOut("slow");
      }
    });
   }, 
   
   populateContacts: function ( json ){
    
    console.log("Populating Contacts");
    
    if (json.result == 'success') {
      if ($('#step-invite .error-panel').is(':visible')) {
        $('#step-invite .error-panel').animate({
        width: 'toggle'
        }, 500);
      }
      
      $('#contacts-error-area').html('');
      $('.holder').remove();
      
      $('#import-contacts-container').fadeOut("slow");
      
      switch($("#service_name").html()) {
        case "twitter":
          $('#tw_session').val(json.session);
          break;
        case "facebook":
          $('#fb_session').val(json.session);
          break;
      }
      
      for(email in json.emails){
        invite.generateHtmlForContact(json.emails[email]);
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
  
  generateHtmlForContact : function( email ) {
    if ($("#service_name").html() == 'facebook') {
      var img = '<img src="'+email.name+'" />';
      var id = email.id;
    } else if ($("#service_name").html() == 'twitter') {
      var img = '';
      var id = email.email;
    } else {
      var img = '';
      var id = invite.itRetrievedContacts;
    }
  	$('#fld-invites-container').append('<li class="holder" onclick="invite.pushContactToList(\''+email.email+'\',\''+id+'\',\''+$("#service_name").html()+'\');" id="holder-'+id+'" title="holder-email-'+invite.itRetrievedContacts+'"><div>' + img + email.email + '</div><a class="add-btn" href="javascript:void(0);">&raquo;</a></li>');
  	invite.itRetrievedContacts++;
  },
  
  pushContactToList : function( item,id,type ) {
    
    if ((type == 'twitter') || (type == 'facebook')) {
      invite.addEmailtoList(item,id,type);
    } else {
      invite.checkSmtpEmail(item);
    }
  },
  
  addContactToList : function(val) {
  	var trimValue = $.trim(val);
  	invite.checkSmtpEmail(trimValue);
  	
  },
  
  checkSmtpEmail: function( anemail ) {
    var args = {"email":anemail};
    
    $.ajax({
      url: '/services/ContactValidator', 
      data: $.param(args), 
      type: "GET", 
      cache: false,
      timeout: 2000,
      dataType: "json", 
      success: function(response) {
        if (response.result = "valid") {
          console.log( anemail +" is valid" );
          invite.addEmailtoList(anemail,anemail,'email');
          return true;
        } else {
          console.log( anemail +" is not valid");
          error.showError('error','The email you have entered is invalid', anemail +' is not valid.'); 
          return false;
        }
        
      }, error: function(response) {
        console.log( anemail +" is not valid");
        error.showError('notice','communication error','Please try again'); 
        return false
      }
    });
  },
  
  addEmailtoList: function( trimValue,id,type ) {
    if($("#accepted-invites-container li[title='"+trimValue+"']").length > 0) {
      return;
    }
    if($.inArray(trimValue, invite.collectedEmails) == -1)
  	{
  		len = invite.collectedEmails.length;
  		len -= 1;
  		
  		$('#accepted-invites-container').append('<li title="'+id+'" type="'+type+'"><div>'+trimValue+'</div><a href="javascript:void(0);" class="remove-btn" onClick="invite.removeEmail(\''+id+'\');">x</a></li>');
  		
      //invite.updatePrice( invite.currentPrice - invite.priceDiscount );
      //invite.updateCount();
      
      return false;
    } else {
  		return val;
  	}	
  },
  
  sendInvites: function() {
    
    $('#inviting-popup').show();
    
    if ($('#accepted-invites-container li').length == 0) {
      $('#inviting-errors').attr("style","color: red").html("There are no people to invite,<br /> please add some email addresses and try again..");
      $('#inviting-popup').delay(2000).fadeOut(400).queue(function() {
        $('#inviting-errors').html('');
        $('#inviting-popup').clearQueue();
      });
      return;
    }
    emails = [];
    fbs = [];
    tws = [];
    j=0;
    $('#accepted-invites-container').children("li[type='email']").each(function(idx, elm) {
        emails.push(elm.title);
        j++;
    });
    $('#accepted-invites-container').children("li[type='facebook']").each(function(idx, elm) {
        fbs.push(elm.title);
        j++;
    });
     $('#accepted-invites-container').children("li[type='twitter']").each(function(idx, elm) {
        tws.push(elm.title);
        j++;
    });
    
    if ($("#is_host").length > 0) {
      var user_type = 'host';
    } else {
      var user_type = 'screening';
    }
    
    var args = {"emails":emails,
                "user_type": user_type,
                "facebooks":fbs,
                "fb_session":$("#fb_session").val(),
                "tweets":tws,
                "tw_session":$("#tw_session").val(),
                "screening":$('#screening').html(),
                "message":$('#invite-fld-greeting').val().replace(/\n/g, '<br/>' )};
    
    $.ajax({
      url: '/services/Invite/send', 
      data: $.param(args), 
      type: "post", 
      cache: false, 
      dataType: "json", 
      success: function(response) {
          invite.finishInvites( response );
      }, error: function(response) {
          console.log("ERROR:", response);
          error.showError('notice','communication error','Please try again'); 
          $('#inviting-popup').delay(2000).fadeOut(400);
      }
    });
  },
  
  finishInvites: function( response ) {
    if (response.result == "success") {
      $('#accepted-invites-container li').remove();
      error.showError('notice',response.message); 
      $('#inviting-popup').delay(2000).fadeOut(400);
    } else {
      error.showError('error',response.message); 
      $('#inviting-popup').delay(2000).fadeOut(400);
    }
  },
  
  isValidEmailAddress: function (emailAddress) {
  	var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
  	return pattern.test(emailAddress);
  },
  
  updatePrice : function ( price ) {
    if (price > 0) {
      screening_room.currentPrice = price;
      var num = new Number(screening_room.currentPrice);
      $(".ticket_price").html(num.toFixed(2));
      $("#ticket_price").val(num.toFixed(2));
    }
  },
  
  updateCount : function () {
    $("#number_invites").html($('#accepted-invites-container li').length);
  },
  
  geoblock : function() {
    $(".gbip-close").click(function(){
      $("#geoblock").fadeOut(400).queue(function() {
        screening_room.invite();
        $("#geoblock").clearQueue();
      });
    });
    screening_room.gbip = false;
    $("#geoblock").fadeIn();
  }
  
}

$(document).ready(function(){
	if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
  
	invite.init();
	
});
