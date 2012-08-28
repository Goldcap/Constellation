function newScreening(form,url,callback,type) {
    var vals = form.HostFormToDict();
    if (type == "purchase") {
      $("#host_purchase").fadeOut();
      $("#process").fadeIn();
    }
    $.postHostJSON(
      url, 
      vals,
      callback
    );
}

jQuery.postHostJSON = function(url, args, callback) {
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

jQuery.fn.HostFormToDict = function() {
   var fields = this.serializeArray();
    var json = {}
    for (var i = 0; i < fields.length; i++) {
	json[fields[i].name] = fields[i].value;
    }
    if (json.next) delete json.next;
    return json;
}

var host_screening = {
  
  itRetrievedContacts: 0,
  collectedEmails : new Array(),
  hostSubmitted: false,
  inviteSubmitted: false,
  purchaseSubmitted: false,
  totalPrice: 0,
  currentPrice: 0,
  priceDiscount: .10,
  price_threshold: .5,
  gbip: false,
  
  init: function() {
    
    host_screening.totalPrice = parseFloat($("#host_ticket_price").val());
    $("#host_invite_count").val( 0 );
    
    if ($("#host-import-email").length > 0) {
      $("#host-import-email").watermark("EMAIL");
      $("#host-import-password").watermark("PASSWORD");
      $("#host-import-password").attr("maxlength",20);
    }
    
    $("#host-ticket_discount").html(host_screening.priceDiscount);
    
    if ($("#gbip").html() == 1) {
      host_screening.gbip = true;
    }
    
    //Detail Steps
  	if ($("#host_screening_button").length > 0) {
  	  //console.log("Host Popup Select Enabled");
      $("#host_screening_button").click(function(e){
        e.preventDefault();
  	    if ($("#host_details").length == 0) {
          $("#login_destination").val('/film/'+$("#film").html()+'/host_screening?currentDate='+$("#current_date").html());
          login.showpopup();
          return;
        } else {
          //console.log("Host Popup Selected");
          host_screening.detail();
        }
      });
    }
    
    $("#host_submit").click(function( e ){
      console.log("Host Step");
      e.preventDefault();
  		if (host_screening.validateDetail()) {
  		  host_screening.hostSubmitted = true;
        newScreening($("#detail-form"),
                    "/host/"+$("#film").html()+"/detail",
                    host_screening.detailResult,
                    "host");
      }
    });
    
    //If we're in the hosting step, show the popup
    if (window.location.pathname.match(/\/host_screening/)) {
      host_screening.detail();
    }
    
    //This is the "PRE PURCHASE" Invite Page
    if (window.location.pathname.match(/\/host_invite/)) {
      if ($("#host_invite").length > 0) {
        //MODIFIED 07/27/2011
        //host_screening.invite();
        host_screening.pay();
      }
    }
    
    //If we're in the purchase step, show the popup
    if (window.location.pathname.match(/\/host_purchase/)) {
      host_screening.pay();
    }
    
    //If we're in the confirm step, show the popup
    if (window.location.pathname.match(/\/host_confirm/)) {
      var obj = window.location.pathname.split("/");
      console.log("Found "+obj[4]+" in the path");
      $("#screening").html( obj[4] );
      host_screening.confirm();
    }
    
    //Send Contacts 
    $("#btn-host-invite").click( function(e) {
      e.preventDefault();
      console.log("SENDING");
      $('.nr-invitees').html($('#host-accepted-invites-container li').length);
      host_screening.sendInvites();
  	});
    
    
  },
  
  geoblock : function() {
    $(".gbip-close").click(function(){
      $("#geoblock").fadeOut(400).queue(function() {
        host_screening.detail();
        $("#geoblock").clearQueue();
      });
    });
    host_screening.gbip = false;
    $("#geoblock").fadeIn();
  },
  
  detail : function() {
    
    console.log("Showing Host");
    host_screening.hostSubmitted = false;
    $("#host_date").val('');
    $("#host_time").val('');
    $("#host-accepted-invites-container li").remove();
    
    if ($("#host_details").length == 0) {
      $("#login_destination").val('/film/'+$("#film").html()+'/host_screening?currentDate='+$("#current_date").html());
      login.showpopup();
      return;
    }
    
    if (host_screening.gbip) {
      host_screening.geoblock();
      return;
    }
    
    $("#host_details").fadeIn("slow");
    
    $(".host_type").click(function(){
      $("#hosting_type").val($(this).html());
      $(".host_type").attr('class','btn_medium host_type');
      $(this).attr('class','btn_medium_og host_type');
    });
    
    // Handle label show/hide.
  	$("#fld-greeting").inputlimiter({
                              limit: 150,
                              boxId: 'textbox-limit',
                              remTextHideOnBlur: 'false'}
                              );
    
    $("#REF_host_image_original").fancybox({
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
        $("#FILE_host_image_original").makeAsyncUploader({
          upload_url: "/services/ImageManager/host/"+$("#host_id").val()+'?constellation_frontend='+$("#session_id").val(),
          flash_url: '/js/swfupload/swfupload.swf',
          button_image_url: '/js/swfupload/blankButton.png',
          debug: false
        });
      },
    	'onClosed' : function() {
  	    var pic = '/uploads/hosts/'+$("input[name=FILE_host_image_original_guid]").val();
        $("#host_image_intro_text").hide();
        if ($("input[name=FILE_host_image_original_guid]").val().length > 0) {
          pic=pic+".jpg";
          $("#host_image_original_preview").attr("src",pic);
          $("#host_image_original_preview_wrapper").show();
        }
    	}
    });
    
    
	  var starttimes = $("#film_start_offset").html().split('|');
    console.log("Start time: " + $("#film_start_offset").html());
    var theMinDate = new Date(starttimes[0], starttimes[1]-1, starttimes[2], starttimes[3], starttimes[4]);
	  
    var endtimes = $("#film_end_offset").html().split('|');
    console.log("End time: " + $("#film_end_offset").html());
    var theMaxDate = new Date(endtimes[0], endtimes[1]-1, endtimes[2], endtimes[3], endtimes[4]);
	  
    $('#host_date').datepicker({
    	numberOfMonths: 2,
    	minDate: theMinDate,
    	maxDate: theMaxDate
    });
    
    //http://docs.jquery.com/UI/Datepicker#option-defaultDate
    $('#host_time').timepicker({
    	minuteGrid: 15,
    	defaultDate: -3,
    	ampm: true
    });
    
    //$('#fld-host-date').datetimepicker();
    
  },
  
  detailResult: function (response) {
    var response = eval("(" + response + ")");
    if ((response.hostResponse.status == "success") && (response.hostResponse.screening != '')) {
      $(".current_host_screening_time").html(response.hostResponse.alt);
      $("#screening").html(response.hostResponse.screening);
      var theurl = $("#host_ticket_price").val();
      var theprice = theurl.replace(".","_");
      $("#paypal-button").attr("href","/services/Paypal/express/host?vars="+response.hostResponse.screening+"-"+$("#film").html()+"-"+theprice);
      $("#host_details").fadeOut("slow");
      //MODIFIED 07/27/2011
      //host_screening.invite();
      host_screening.pay();
    } else {
      error.showError("error",response.hostResponse.message);
      host_screening.hostSubmitted = false;
      host_screening.detail();
    }
  },
  
  validateDetail: function() {
    var err = 0;
    if ($("#host_date").val() == '') { $("#host_date").css('background','#ff6666'); err++; } else { $("#host_date").css('background','#A5D0ED'); }
    if ($("#host_time").val() == '') { $("#host_time").css('background','#ff6666'); err++; } else { $("#host_time").css('background','#A5D0ED'); }
    
    if (err > 0) {
      error.showError("error","You must choose a hosting date and time that is later than the current date and time.");
      host_screening.hostSubmitted = false;
      return false;
    }
    if (host_screening.hostSubmitted) {
      return false;
    }
    
    return true;
  },
  
  
  updatePrice : function ( price ) {
    var tot = new Number(host_screening.totalPrice);
    var thaprice = tot * host_screening.price_threshold;
    if ((price > 0) && (price >= thaprice)) {
      host_screening.currentPrice = price;
      var num = new Number(host_screening.currentPrice);
      $(".host-ticket_price").html(num.toFixed(2));
      $("#host-ticket_price").val(num.toFixed(2));
    }
  },
  
  updateCount : function () {
    //IC are previously sent Invites
    var ic = $("#host_invite_count").val();
    $(".host_number_invites").html(parseInt(ic) + parseInt($('#host_invite_count').val()));
  },
  
  
  invite : function() {
    
    console.log("Showing Host");
    
    host_screening.currentPrice = host_screening.totalPrice;
    host_screening.updatePrice( host_screening.currentPrice );
    $(".host_number_invites").html("0");
    
    console.log("INVITE with"+$("#host-accepted-invites-container li").length);
     
    //if ($("#host-accepted-invites-container li").length > 0) {
    //  host_screening.updatePrice( $("#setup_price").val() );
    //}
     
    $("#host_add_invite").editable(function(value, settings) { 
         host_screening.addContactToList(value);
         return "Click To Add Email";
      }, { 
         cssclass : 'invite_input',
         height: '12',
         width: '150',
         type    : 'textarea',
         submit  : 'add',
         name: 'editablehost'
     });
     
		if ($("#host_invite").length > 0) {
			$("#host_invite").fadeIn("slow");
		}
		else {
			host_screening.pay();
		}
    
    $("#host-go-purchase").click(function( e ) {
      e.preventDefault();
      console.log("Purchasing " + "/film/"+$("#film").html()+"/purchase/"+$("#screening").html());
      if ((host_screening.inviteSubmitted == false) && ($('#host-accepted-invites-container li').length > 0)) {
        error.showError('notice','Your Invites Aren\'t Sent','Please send your pending invites, then purchase your ticket.');
      } else {
        $("#host_invite").fadeOut("slow").queue(function(){
          host_screening.pay();
          $("#host_invite").clearQueue();
        });
      }
    });
    
    
    //view already invited popup
  	$('#view-already-invited').click(function(){
  		movePopup('#already-invited-popup', 100, 85);
  		togglePopup('already-invited');
  	});
  	
    host_screening.getInvites();
    
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
  	$('#host-btn-preview_invite').click(function() {
  		
  		if($('#steps-setup').length !== 0) // we are on setup screening page
  		{
  			uniqueKey = $('input.unique_key').val();
  		}
      
      var args = {"screening":$('#screening').html(),
                  "film":$('#film').html(),
                  "message":$('#host-invite-fld-greeting').val().replace(/\n/g, '<br/>' )};
      
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
    $('.host_import_click').click(function(e){
    
      $("#host_service_name").html($(this).attr('name'));
      
  		$('#host-import-contacts-container .login').fadeIn("slow");
  		
  		$('#host-import-email').val('');
  		$('#host-import-password').val('');
  		
      $("#host-import-email").watermark("EMAIL");
      $("#host-import-password").watermark("PASSWORD");
  		
  		e.preventDefault();
  		
      $('#host-import-contacts-container').slideDown('fast');
  		$('#host-import-contacts-provider').val( $(this).attr("name") );
  		
  	});
  	
  	$('#host-btn-import').click(function(e){
  	  
      e.preventDefault();
      if (($('#host-import-email').val() == '') || ($('#host-import-password').val() == '') || ($('#host-import-email').val() == 'EMAIL') || ($('#host-import-password').val() == 'PASSWORD')) {
        $('#contacts-error-area').html("Please enter a valid username and password.");
      } else {
  		  host_screening.getContacts(e);
  		}
  		
  		return false;
  	});
  	
  	$("#host-invite-fld-greeting").inputlimiter({
                              limit: 150,
                              boxId: 'host-invite-textbox-limit',
                              remTextHideOnBlur: 'false'}
                              );
  	
  },
  
  removeEmail: function (email) {
    console.log("Removing!");
		$('#host-accepted-invites-container li[title="'+email+'"] a.remove-btn').remove();
		screening_room.collectedEmails = jQuery.grep(screening_room.collectedEmails, function(value) {return value != email;});
    $('#host-accepted-invites-container li[title="'+email+'"]').remove();
    
    host_screening.updatePrice( host_screening.currentPrice + host_screening.priceDiscount );
    host_screening.updateCount();
    
  },
  
  getContacts : function(e) {
    e.preventDefault();
    
    itRetrievedContacts = 0;
    $('#host-contacts-loading-area').fadeIn();
    
    var args = {"email":$('#host-import-email').val(),
                "password":$('#host-import-password').val(),
                "provider":$('#host-import-contacts-provider').val(),
                "provider-alt":$('#host-provider-alternate').val()};
    
    $.ajax({
      url: '/services/ContactGrabber', 
      data: $.param(args), 
      type: "GET", 
      cache: false, 
      dataType: "json", 
      success: function(response) {
          host_screening.populateContacts( response );
      }, error: function(response) {
          error.showError('notice','communication error','Please try again');  
          $("#host-contacts-loading-area").fadeOut("slow");
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
      
      $('#host-contacts-error-area').html('');
      $('.holder').remove();
      
      $('#host-import-contacts-container').fadeOut('slow');
      
      switch($("#host_service_name").html()) {
        case "twitter":
          $('#host_tw_session').val(json.session);
          break;
        case "facebook":
          $('#host_fb_session').val(json.session);
          break;
      }
      
      for(email in json.emails){
        host_screening.generateHtmlForContact(json.emails[email]);
      }
      
      $('#host-import-email').val('');
      $('#host-import-password').val('');
      
    } else if(json.result == 'failure') {
    
      $('#host-contacts-error-area').html(json.message);
      
      $('#step-invite .error-panel .errors').html(json.message);
      if (!$('#step-invite .error-panel').is(':visible')) {
        $('#step-invite .error-panel').animate({
        width: 'toggle'
        }, 500);
      }
    }
      
    $('#host-contacts-loading-area').fadeOut();	
    
    return false;
    
  },
  
  generateHtmlForContact : function(email) {
    if ($("#host_service_name").html() == 'facebook') {
      var img = '<img src="'+email.name+'" />';
      var id = email.id;
    } else if ($("#host_service_name").html() == 'twitter') {
      var img = '';
      var id = email.email;
    } else {
      var img = '';
      var id = host_screening.itRetrievedContacts;
    }
    $('#host-fld-invites-container').append('<li class="holder" onclick="host_screening.pushContactToList(\''+email.email+'\',\''+id+'\',\''+$("#host_service_name").html()+'\');" id="host-holder-'+id+'" title="host-holder-email-'+screening_room.itRetrievedContacts+'"><div>' + img + email.email + '</div><a class="add-btn" href="javascript:void(0);">&raquo;</a></li>');
  	host_screening.itRetrievedContacts++;
  },
  
  pushContactToList : function( item,id,type ) {
    if ((type == 'twitter') || (type == 'facebook')) {
      host_screening.addEmailtoList(item,id,type);
    } else {
      host_screening.checkSmtpEmail(item);
    }
  },
  
  addContactToList : function(val) {
  	var trimValue = $.trim(val);
  	host_screening.checkSmtpEmail(trimValue);
  		
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
          host_screening.addEmailtoList(anemail,anemail,'email');
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
    if($("#host-accepted-invites-container li[title='"+trimValue+"']").length > 0) {
      return;
    }
    if($.inArray(trimValue, host_screening.collectedEmails) == -1)
  	{
  		len = host_screening.collectedEmails.length;
  		len -= 1;
  		
  		$('#host-accepted-invites-container').append('<li title="'+id+'" type="'+type+'"><div>'+trimValue+'</div><a href="javascript:void(0);" class="remove-btn" onClick="host_screening.removeEmail(\''+id+'\');">x</a></li>');
  		
      host_screening.updatePrice( host_screening.currentPrice - host_screening.priceDiscount );
      host_screening.updateCount();
      
      return false;
    } else {
  		return val;
  	}	
  },
  
  sendInvites: function() {
    
    host_screening.inviteSubmitted = true;
    
    $('#inviting-popup').show();
    
    if ($('#host-accepted-invites-container li').length == 0) {
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
    $('#host-accepted-invites-container').children("li[type='email']").each(function(idx, elm) {
        emails.push(elm.title);
        j++;
    });
    $('#host-accepted-invites-container').children("li[type='facebook']").each(function(idx, elm) {
        fbs.push(elm.title);
        j++;
    });
     $('#host-accepted-invites-container').children("li[type='twitter']").each(function(idx, elm) {
        tws.push(elm.title);
        j++;
    });
    var args = {"emails":emails,
                "user_type":'host',
                "facebooks":fbs,
                "fb_session":$("#host_fb_session").val(),
                "tweets":tws,
                "tw_session":$("#host_tw_session").val(),
                "screening":$('#screening').html(),
                "message":$('#host-invite-fld-greeting').val().replace(/\n/g, '<br/>' )};
    
    $.ajax({
      url: '/services/Invite/send', 
      data: $.param(args), 
      type: "POST", 
      cache: false, 
      dataType: "json", 
      success: function(response) {
          host_screening.finishInvites( response );
      }, error: function(response) {
          console.log("ERROR:", response);
          error.showError('notice','communication error','Please try again'); 
          $('#inviting-popup').delay(2000).fadeOut(400);
      }
    });
  },
  
  finishInvites: function( response ) {
    if (response.result == "success") {
      var ic = $("#host_invite_count").val();
      $("#host_invite_count").val(parseInt(ic) + parseInt($('#host-accepted-invites-container li').length));
      $('#host-accepted-invites-container li').remove();
      error.showError('notice',response.message); 
      $('#inviting-popup').delay(2000).fadeOut(400);
    } else {
      error.showError('error',response.message); 
      $('#inviting-popup').delay(2000).fadeOut(400);
    }
  },
  
  goPaypal : function() {
    if (($("#screening").html() != "") && ($("#film").html() != "")) {
      var theurl = $("#host_ticket_price").val();
      var theprice = theurl.replace(".","_");
      window.location.href = "/services/Paypal/express/host?vars="+$("#screening").html()+"-"+$("#film").html()+"-"+theprice;
    }
  },
  
  pay : function() {
    
    $("#host_purchase").fadeIn("slow");
    
    $("#host_purchase_submit").click(function(){
      if (host_screening.validatePurchase()) {
        
        host_screening.purchaseSubmitted = true;
        newScreening($("#host_purchase_form"),
                    "/host/"+$("#film").html()+"/purchase/"+$("#screening").html(),
                    host_screening.purchaseResult,
                    "purchase");
        //$("#host_purchase_form").submit();
      }
    });
  	
  },
  
  validatePurchase: function() {
    console.log("Purchase Here");
    var err = 0;
    if ($("#host-fld-cc_first_name").val() == '') { $("#host-fld-cc_first_name").css('background','#ff6666'); err++; } else { $("#host-fld-cc_first_name").css('background','#A5D0ED'); }
    if ($("#host-fld-cc_last_name").val() == '') { $("#host-fld-cc_last_name").css('background','#ff6666'); err++; } else { $("#host-fld-cc_last_name").css('background','#A5D0ED'); }
    if (! host_screening.isValidEmailAddress($("#host-fld-cc_email").val())) { $("#host-fld-cc_email").css('background','#ff6666'); err++; } else { $("#host-fld-cc_email").css('background','#A5D0ED'); }
    if (! host_screening.isValidEmailAddress($("#host-fld-cc_confirm_email").val())) { $("#host-fld-cc_confirm_email").css('background','#ff6666'); err++; } else { $("#host-fld-cc_confirm_email").css('background','#A5D0ED'); }
    if (host_screening.isValidEmailAddress($("#host-fld-cc_email").val()) && host_screening.isValidEmailAddress($("#host-fld-cc_confirm_email").val())) {
      if ($("#host-fld-cc_email").val() != $("#host-fld-cc_confirm_email").val()) { $("#host-fld-cc_email").css('background','#ff6666'); $("#host-fld-cc_confirm_email").css('background','#ff6666'); err++; } else { $("#host-fld-cc_email").css('background','#A5D0ED'); $("#host-fld-cc_confirm_email").css('background','#A5D0ED'); }
    }
    if ($("#host-fld-cc_number").val() == '') { $("#host-fld-cc_number").css('background','#ff6666'); err++; } else { $("#host-fld-cc_number").css('background','#A5D0ED'); }
    if ($("#host-fld-cc_security_number").val() == '') { $("#host-fld-cc_security_number").css('background','#ff6666'); err++; } else { $("#host-fld-cc_security_number").css('background','#A5D0ED'); }
    if ($("#host-fld-cc_address1").val() == '') { $("#host-fld-cc_address1").css('background','#ff6666'); err++; } else { $("#host-fld-cc_address1").css('background','#A5D0ED'); }
    if ($("#host-fld-cc_city").val() == '') { $("#host-fld-cc_city").css('background','#ff6666'); err++; } else { $("#host-fld-cc_city").css('background','#A5D0ED'); }
    if ($("#host-fld-cc_zip").val() == '') { $("#host-fld-cc_zip").css('background','#ff6666'); err++; } else { $("#host-fld-cc_zip").css('background','#A5D0ED'); }
    
    if (err > 0) {
			var price = $("#ticket_price").val();
			console.log("price: " + price);
			if(price == 0) {
				error.showError("error","Please complete the required fields.");
			}
			else {
				error.showError("error","Your payment information is invalid.");
			}
		
      host_screening.purchaseSubmitted = false;
      return false;
    }
    if (host_screening.purchaseSubmitted) {
      error.showError("error","You payment is being processed.");
      return false;
    }
    return true;
  },
  
  purchaseResult: function (response) {
    console.log("CONFIRMED!");
    $("#process").fadeOut();
    
    var response = eval("(" + response + ")");
    if (response.hostResponse.status == "success") {
      $("#screening").html(response.hostResponse.screening);
      $("#purchase_result").html(response.hostResponse.result);
      $("#host_invite_count").val( 0 );
      host_screening.confirm();
    } else {
      error.showError("error",response.hostResponse.message);
      host_screening.purchaseSubmitted = false;
      host_screening.pay();
    }
  },
  
  confirm : function() {
    console.log("CONFIRM");
    $("#host_screening_full_link").html('http://'+$("#domain").html()+'/theater/'+$("#screening").html());
    $("#host_screening_link").attr('href','/theater/'+$("#screening").html());
    $('#host_screening_link').click(function(e){
  		e.preventDefault();
      window.location.href = '/theater/'+$("#screening").html();
    });
    host_screening.hostSubmitted = false;
    host_screening.purchaseSubmitted = false;
    $("#host_purchase").fadeOut("slow").queue(function() {
      $("#host_confirm").fadeIn("slow");
      $("#host_purchase").clearQueue();
    });
  },
  
  isValidEmailAddress: function (emailAddress) {
  	var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
  	return pattern.test(emailAddress);
  }
}

$(document).ready(function(){
	if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
  
	host_screening.init();
	
});
