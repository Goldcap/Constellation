function newPurchase(form,screening) {
    //console.log("Submitting Purchase");
    $("#screening_purchase").fadeOut();
    setTop("#process");
    $("#process").fadeIn();
    var vals = form.PurchaseFormToDict();
    if ($("input[name='dohbr']").val() == "true") {
      //console.log("Do HBR");
      theurl = "/screening/"+$("#film").html()+"/purchase/none";
    } else {
      //console.log("Normal Purchase of "+screening);
      theurl = "/screening/"+$("#film").html()+"/purchase/"+screening;
    }
    $.postPurchaseJSON(
      theurl, 
      vals
    );
}

jQuery.postPurchaseJSON = function(url, args, callback) {
    $.ajax({url: url, 
            data: $.param(args), 
            dataType: "text", 
            type: "POST",
	          success: function(response) {
          	 screening_room.purchaseResult(response);
            }, 
            error: function(response) {
          	 //console.log("ERROR:", response)
            }
    });
};

jQuery.fn.PurchaseFormToDict = function() {
   var fields = this.serializeArray();
    var json = {}
    for (var i = 0; i < fields.length; i++) {
	json[fields[i].name] = fields[i].value;
    }
    if (json.next) delete json.next;
    return json;
}

var screening_room = {
  
  itRetrievedContacts: 0,
  collectedEmails : new Array(),
  inviteSubmitted : false,
  purchaseSubmitted : false,
  totalPrice: 0,
  currentPrice: 0,
  priceDiscount: .10,
  price_threshold: .5,
  facebook_discount: false,
  gbip: false,
                      
  init: function() {
    
    $("#invite_count").val( 0 );
    
    $("#ticket_discount").html(screening_room.priceDiscount);
    screening_room.totalPrice = $("#ticket_cost").html();
    screening_room.currentPrice = $("#ticket_cost").html();
    //console.log(screening_room.currentPrice);
    
    //console.log("Total Price is "+screening_room.totalPrice);
    
    if ($("#gbip").html() == 1) {
      screening_room.gbip = true;
    }
    
    $("#hbr_request_button").click(function(e){
      e.preventDefault();
      screening_room.hostbyrequest();
      return false;
    });
    
    $("#watch_request_button").click(function(e){
      e.preventDefault();
      screening_room.request();
      return false;
    });
    
    $('.popup-close a').click(function(){
      $("#dohbr").val("false");
    });
    
    //If we're in the hosting step, show the popup
    if (window.location.pathname.match(/\/purchase/)) {
      var obj = window.location.pathname.split("/");
      //console.log("Found "+obj[4]+" in the path");
      $("#screening").html( obj[4] );
      screening_room.pay();
      //host_screening.detail();
    } else
    
    //If we're in the hosting step, show the popup
    if (window.location.pathname.match(/\/confirm/)) {
      var obj = window.location.pathname.split("/");
      //console.log("Found "+obj[4]+" in the path");
      $("#screening").html( obj[4] );
      screening_room.confirm();
      //host_screening.detail();
    } else 
    
    //If we're in the hosting step, show the popup
    if (window.location.pathname.match(/\/detail/)) {
      var obj = window.location.pathname.split("/");
      //console.log("Found "+obj[4]+" in the path");
      $("#screening").html( obj[4] );
      if ($(".screening_link").length > 0) {
        //MODIFIED 07/27/2011
        //screening_room.invite();
        screening_room.pay();
      } else if ($(".purchase_link").length > 0) {
        screening_room.pay();
      }
      //host_screening.detail();
    }
    
    //If we're in the hosting step, show the popup
    if (window.location.pathname.match(/\/request/)) {
      screening_room.hostbyrequest();
      //host_screening.detail();
    }
    
    screening_room.linkInvite();
    screening_room.linkPurchase();
    
    /* GENERIC CLICK HIDE ANY FLOATING DIVS */
  	$(document).click(function(){
  		$('#preview-invite-popup').hide();
  		
      $('.reviews #see-more-review').click(function(e) {
  			$('#film-reviews #hidden-reviews').show();
  	 });
  	});
  	/* END GENERIC CLICK HIDE ANY FLOATING DIVS */
  	
    $("#btn-invite").click( function(e) {
      //console.log("Clicked Invite");
      e.preventDefault();
      screening_room.updateCount();
      screening_room.sendInvites();
  	});
    
  },
  
  linkInvite: function() {
  
    //If we're in the hosting step, show the popup
    //NOTE:: This link is unused if the "upcoming.js" has been triggered
    //Look at "upcoming.js" in the "init()" function for a screening_room.pay(); call.
    $(".screening_link").click( function(e) {
      e.preventDefault();
      if ($('#screening_invite').length > 0) {
        //console.log("Showing Screening Invite");
        $("#screening").html( $(this).attr("title") );
        //MODIFIED 07/27/2011
        //screening_room.invite();
        screening_room.pay();
      } else {
        $("#login_destination").val($(this).attr('href')+'?currentDate='+$("#current_date").html());
        $("#signup_destination").val($(this).attr('href')+'?currentDate='+$("#current_date").html());
        login.showpopup();
      }
    });
    
  },
  
  linkPurchase: function() {
    
    //If we're in the hosting step, show the popup
    //NOTE:: This link is unused if the "upcoming.js" has been triggered
    //Look at "upcoming.js" in the "init()" function for a screening_room.pay(); call.
    $(".purchase_link").click( function(e) {
      e.preventDefault();
      if ($('#screening_invite').length > 0) {
        //console.log("Showing Screening Invite");
        $("#screening").html( $(this).attr("title") );
        screening_room.pay();
      } else {
        $("#login_destination").val($(this).attr('href')+'?currentDate='+$("#current_date").html());
        $("#signup_destination").val($(this).attr('href')+'?currentDate='+$("#current_date").html());
        login.showpopup();
      }
    });
    
  },
  
  updatePrice : function ( price ) {
   var tot = new Number(screening_room.totalPrice);
   var thaprice = tot * screening_room.price_threshold;
   if ((price > 0) && (price >= thaprice)) {
      //console.log("updating price");
      screening_room.currentPrice = price;
      var num = new Number(screening_room.currentPrice);
      $(".ticket_price").html(num.toFixed(2));
      $("#ticket_price").val(num.toFixed(2));
    }
  },
  
  applyDiscount: function ( price ) {
   console.log(price);
	 if (price >= 0) {
      console.log("updating discount");
      screening_room.currentPrice = price;
      var num = new Number(screening_room.currentPrice);
      $(".ticket_price").html(num.toFixed(2));
      $("#ticket_price").val(num.toFixed(2));
    }
  },
  
  updateCount : function () {
    //IC are previously sent Invites
    var ic = $("#invite_count").val();
    $("#number_invites").html( parseInt(ic) + parseInt($('#accepted-invites-container li').length) );
  },
  
  geoblock : function() {
    $(".gbip-close").click(function(){
      $("#geoblock").fadeOut(400).queue(function() {
        //MODIFIED 07/27/2011
        //screening_room.invite();
        screening_room.pay();
        $("#geoblock").clearQueue();
      });
    });
    screening_room.gbip = false;
    
    setTop("#geoblock");
    $("#geoblock").fadeIn();
  },
  
  invite : function() {
    
    screening_room.totalPrice = $("#ticket_cost").html();
    screening_room.currentPrice = $("#ticket_cost").html();
    //console.log("Invite");
    
    if ($("#screening_invite").length == 0) {
      login.showpopup();
      return;
    }
    
    if (screening_room.gbip) {
      screening_room.geoblock();
      return;
    }
   
   $("#accepted-invites-container li").remove();
    
   $("#add_invite").editable(function(value, settings) { 
       ////console.log(value);
       screening_room.addContactToList(value);
       return "Click To Add Email";
    }, { 
       cssclass : 'invite_input',
       height: '12',
       width: '150',
       type    : 'textarea',
       submit  : 'add',
       name: 'editablepurchase'
   });
    
    $(".current_screening_time").html($("#time_"+$("#screening").html()).html());
    //console.log("Host is: '"+$("#host_"+$("#screening").html()).html()+"'");
    
    if ($("#host_"+$("#screening").html()).html() != '') {
      $(".current_screening_host").html($("#host_"+$("#screening").html()).html());
    } else {
      $(".current_screening_hb").hide();
    }
    
    //Every time we hit the "invite" step, clear price and invites
    screening_room.currentPrice = screening_room.totalPrice;
    screening_room.updatePrice( screening_room.currentPrice );
    $("#number_invites").html("0");
    
    var theurl = $("#ticket_price").val();
    var theprice = theurl.replace(".","_");
    $("#paypal-button").attr("href","/services/Paypal/express/screening?vars="+$("#screening").html()+"-"+$("#film").html()+"-"+theprice);
    
    //console.log("Fading In Screening Invite");
    
    setTop("#screening_invite");
    $("#screening_invite").fadeIn();
    //console.log("Purchasing " + "/film/"+$("#film").html()+"/purchase/"+$("#screening").html());
      
    $("#go-purchase").click(function(e) {
      e.preventDefault();
      if ((screening_room.inviteSubmitted == false) && ($('#accepted-invites-container li').length > 0)) {
        error.showError('notice','Your Invites Aren\'t Sent','Please send your pending invites, then purchase your ticket.');
      } else {
        $("#screening_invite").fadeOut("slow").queue(function(){
          screening_room.pay();
          $("#screening_invite").clearQueue();
        });
      }
    });
    
    //view already invited popup
  	$('#view-already-invited').click(function(){
  		movePopup('#already-invited-popup', 100, 85);
  		togglePopup('already-invited');
  	});
  	
  	screening_room.getInvites();
  	
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
  		////console.log("PREVIEWING!");
  		
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
  		  screening_room.getContacts(e);
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
    ////console.log("Removing!");
		$('#accepted-invites-container li[title="'+email+'"] a.remove-btn').remove();
		screening_room.collectedEmails = jQuery.grep(screening_room.collectedEmails, function(value) {return value != email;});
    $('#accepted-invites-container li[title="'+email+'"]').remove();
		
    screening_room.updatePrice( screening_room.currentPrice + screening_room.priceDiscount );
    screening_room.updateCount();
    
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
          screening_room.populateContacts( response );
      }, error: function(response) {
          error.showError('notice','communication error','Please try again'); 
          $("#contacts-loading-area").fadeOut("slow");
      }
    });
   }, 
   
   populateContacts: function ( json ){
    
    ////console.log("Populating Contacts");
    
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
        screening_room.generateHtmlForContact(json.emails[email]);
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
      var id = screening_room.itRetrievedContacts;
    }
  	$('#fld-invites-container').append('<li class="holder" onclick="screening_room.pushContactToList(\''+email.email+'\',\''+id+'\',\''+$("#service_name").html()+'\');" id="holder-'+id+'" title="holder-email-'+screening_room.itRetrievedContacts+'"><div>' + img + email.email + '</div><a class="add-btn" href="javascript:void(0);">&raquo;</a></li>');
  	screening_room.itRetrievedContacts++;
  },
  
  pushContactToList : function( item,id,type ) {
    
    if ((type == 'twitter') || (type == 'facebook')) {
      screening_room.addEmailtoList(item,id,type);
    } else {
      screening_room.checkSmtpEmail(item);
    }
  },
  
  addContactToList : function(val) {
  	var trimValue = $.trim(val);
  	screening_room.checkSmtpEmail(trimValue);
  	
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
          ////console.log( anemail +" is valid" );
          screening_room.addEmailtoList(anemail,anemail,'email');
          return true;
        } else {
          ////console.log( anemail +" is not valid");
          error.showError('error','The email you have entered is invalid', anemail +' is not valid.'); 
          return false;
        }
        
      }, error: function(response) {
        ////console.log( anemail +" is not valid");
        error.showError('notice','communication error','Please try again'); 
        return false
      }
    });
  },
  
  addEmailtoList: function( trimValue,id,type ) {
    //console.log("Adding Email");
    if($("#accepted-invites-container li[title='"+trimValue+"']").length > 0) {
      return;
    }
    if($.inArray(trimValue, screening_room.collectedEmails) == -1)
  	{
  		len = screening_room.collectedEmails.length;
  		len -= 1;
  		
  		$('#accepted-invites-container').append('<li title="'+id+'" type="'+type+'"><div>'+trimValue+'</div><a href="javascript:void(0);" class="remove-btn" onClick="screening_room.removeEmail(\''+id+'\');">x</a></li>');
  		
  		//console.log("Adding Email, Current Price is:" + screening_room.currentPrice);
  		//console.log(screening_room.priceDiscount);
      screening_room.updatePrice( screening_room.currentPrice - screening_room.priceDiscount );
      screening_room.updateCount();
      
      return false;
    } else {
  		return val;
  	}	
  },
  
  sendInvites: function() {
    
    screening_room.inviteSubmitted = true;
    
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
    
    var args = {"emails":emails,
                "user_type":'screening',
                "facebooks":fbs,
                "fb_session":$("#fb_session").val(),
                "tweets":tws,
                "tw_session":$("#tw_session").val(),
                "screening":$('#screening').html(),
                "message":$('#invite-fld-greeting').val().replace(/\n/g, '<br/>' )};
    
    $.ajax({
      url: '/services/Invite/send', 
      data: $.param(args), 
      type: "POST", 
      cache: false, 
      dataType: "json", 
      success: function(response) {
          screening_room.finishInvites( response );
      }, error: function(response) {
          ////console.log("ERROR:", response);
          error.showError('notice','communication error','Please try again'); 
          $('#inviting-popup').delay(2000).fadeOut(400);
      }
    });
  },
  
  finishInvites: function( response ) {
    if (response.result == "success") {
      var ic = $("#invite_count").val();
      $("#invite_count").val(parseInt(ic) + parseInt($('#accepted-invites-container li').length));
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
  
  goPaypal : function() {
    //console.log($("#screening").html());
    //console.log($("#film").html());
    console.log("go paypal!");
    if ((($("#screening").html() != "") || ($("#dohbr").val() == "true")) && ($("#film").html() != "")) {
      var theurl = $("#ticket_price").val();
      var theprice = theurl.replace(".","_");
      window.location.href = "/services/Paypal/express/screening?vars="+$("#screening").html()+"-"+$("#film").html()+"-"+theprice;
    }
  },
  
  pay : function() {
    
    if ($("#screening_invite").length == 0) {
      login.showpopup();
      return;
    }
    
    //If we've already detected the bandwidth, and it's too low...
    if (bandwidth != undefined) {
      if ((bandwidth.bandwidth > -1) && (bandwidth.bandwidth < bandwidth.threshold)){
				$(".current_bandwidth").html(bandwidth.bandwidth);
				$(".bandwidth_warning").fadeIn(100);
			}
    }
    
    //Note, this was moved from the #invite() feature
    //And should be moved back when invite is re-integrated
    if (screening_room.gbip) {
      screening_room.geoblock();
      return;
    }
    
    $("#facebook_share").click(function(e){
      e.preventDefault();
      screening_room.facebookShare();
    });
    
    if ($("select.cc-country-drop").val() != "US") {
			screening_room.swapState();
		}
		
    $("select.cc-country-drop").change(function () {
			screening_room.swapState();
		});
		
    //console.log("Payment Step");
    $("#process").fadeOut();
    
    $('#fld-code').removeAttr("disabled");
    $("#promo_code").val("")
    $("#enter-code").show();
    
    //console.log("Screening is: '"+$("#screening").html()+"'");
    var this_screening = $("#screening").html();
    
    if ($("#dohbr").val() == "true") {
      $(".current_screening_time").html("Now");
    } else {
      $(".current_screening_time").html($("#time_"+$("#screening").html()).html());
    }
    
    //console.log("Host is: '"+$("#host_"+$("#screening").html()).html()+"'");
    if ($("#dohbr").val() == "true") {
      $(".current_screening_host").html("You");
    } else if ($("#host_"+$("#screening").html()).html() != '') {
      $(".current_screening_host").html($("#host_"+$("#screening").html()).html());
    } else {
      $(".current_screening_hb").hide();
    }
    
    $("#purchase_submit").click(function(){
      //console.log("Purchase Click of "+this_screening);
      if (! screening_room.purchaseSubmitted) {
        if (screening_room.validatePurchase()) {
          screening_room.purchaseSubmitted = true;
          newPurchase($("#purchase_form"),this_screening);
        }
        //$("#purchase_form").submit();
      } else {
        //console.log("Already Submitted");
      }
    });
    
    if ($(".purchase_next").length > 0) {
	    $(".purchase_next").click(function(){
	  	  $(".step_one").animate({
			    left: '-=420',
			  }, 100, 'linear', function() {
			    // Animation complete.
			  });
			  $(".step_two").animate({
			    left: '-=420',
			  }, 100, 'linear', function() {
			    // Animation complete.
			  });
			});
			
			$(".purchase_last").click(function(){
	  	  $(".step_one").animate({
			    left: '+=420',
			  }, 100, 'linear', function() {
			    // Animation complete.
			  });
			  $(".step_two").animate({
			    left: '+=420',
			  }, 100, 'linear', function() {
			    // Animation complete.
			  });
			});
		}
		
    //setTop("#screening_purchase");
    $(".pop_up").fadeOut();
		$(".pre_content").fadeOut();
		$(".prescreen_notice").fadeOut();
		$(".prescreen_purchase").fadeOut();
		
		$("#screening_purchase").fadeIn();
    
    $('#btn-ticket-code').click(function(e){
  		e.preventDefault();
  		if ($("#ticket-code").val() == "false") {
    		$("#all-payment-info").fadeOut("slow").queue(function() {
    		  $("#ticket-code").val("code");
          $("#fieldsets-ticket-code").fadeIn("slow");
          $('#all-payment-info').clearQueue();
    	   });
  	  } else if ($("#ticket-code").val() == "code") {
  	     $("#fieldsets-ticket-code").fadeOut("slow").queue(function() {
    		  $("#ticket-code").val("false");
          $("#all-payment-info").fadeIn("slow");
          $('#fieldsets-ticket-code').clearQueue();
    	   });
  	  }
    });
    
  	$('#btn-ticket-code-next').click(function(e){
  		e.preventDefault();
  		if (screening_room.validateTicket()) {
        $("#step-redeem-form").submit();
      }
  	});
  	
  },
  
  validateTicket: function() {
    var err = 0;
    if ($("#fld-ticket_code").val() == '') { $("#fld-ticket_code").css('background','#ff6666'); err++; } else { $("#fld-ticket_code").css('background','#A5D0ED'); }
    if ($("#fld-email_recipient").val() == '') { $("#fld-email_recipient").css('background','#ff6666'); err++; } else { $("#fld-email_recipient").css('background','#A5D0ED'); }
    
    if (err > 0) {
      error.showError("error","Your ticket information is invalid.");
      return false;
    }
      return true;
  },
  
  validatePurchase: function() {
    ////console.log("SCREEINING Purchase Here");
    var err = 0;
    if ($("#fld-cc_first_name").val() == '') { $("#fld-cc_first_name").css('background','#ff6666'); err++; } else { $("#fld-cc_first_name").css('background','#A5D0ED'); }
    if ($("#fld-cc_last_name").val() == '') { $("#fld-cc_last_name").css('background','#ff6666'); err++; } else { $("#fld-cc_last_name").css('background','#A5D0ED'); }
    if (! screening_room.isValidEmailAddress($("#fld-cc_email").val())) { $("#fld-cc_email").css('background','#ff6666'); err++; } else { $("#fld-cc_email").css('background','#A5D0ED'); }
    if (! screening_room.isValidEmailAddress($("#fld-cc_confirm_email").val())) { $("#fld-cc_confirm_email").css('background','#ff6666'); err++; } else { $("#fld-cc_confirm_email").css('background','#A5D0ED'); }
    if (screening_room.isValidEmailAddress($("#fld-cc_email").val()) && screening_room.isValidEmailAddress($("#fld-cc_confirm_email").val())) {
      if ($("#fld-cc_email").val() != $("#fld-cc_confirm_email").val()) { $("#fld-cc_email").css('background','#ff6666'); $("#fld-cc_confirm_email").css('background','#ff6666'); err++; } else { $("#fld-cc_email").css('background','#A5D0ED'); $("#fld-cc_confirm_email").css('background','#A5D0ED'); }
    }
    if ($("#fld-cc_number").val() == '') { $("#fld-cc_number").css('background','#ff6666'); err++; } else { $("#fld-cc_number").css('background','#A5D0ED'); }
    if ($("#fld-cc_security_number").val() == '') { $("#fld-cc_security_number").css('background','#ff6666'); err++; } else { $("#fld-cc_security_number").css('background','#A5D0ED'); }
    if ($("#fld-cc_address1").val() == '') { $("#fld-cc_address1").css('background','#ff6666'); err++; } else { $("#fld-cc_address1").css('background','#A5D0ED'); }
    if ($("#fld-cc_city").val() == '') { $("#fld-cc_city").css('background','#ff6666'); err++; } else { $("#fld-cc_city").css('background','#A5D0ED'); }
    if ($("#fld-cc_zip").val() == '') { $("#fld-cc_zip").css('background','#ff6666'); err++; } else { $("#fld-cc_zip").css('background','#A5D0ED'); }
    
    if (err > 0) {
			var price = $("#ticket_price").val();
			//console.log("price: " + price);
			if(price == 0) {
				error.showError("error","Please complete the required fields.");
			}
			else {
				error.showError("error","Your payment information is invalid.");
			}
      return false;
    }
      return true;
  },
  
  purchaseResult: function (response) {
    //console.log("Purchase Result");
    var response = eval("(" + response + ")");
    if (response.purchaseResponse.status == "success") {
      $("#host-accepted-invites-container li").remove();
      $("#invite_count").val( 0 );
      $("#screening").html(response.purchaseResponse.screening);
      $("#purchase_result").html(response.purchaseResponse.result);
      // $("#purchase_result").html(response.purchaseResponse.result);
      screening_room.confirm();
    } else {
      error.showError("error",response.purchaseResponse.message);
      screening_room.purchaseSubmitted = false;
      screening_room.pay();
    }
  },
  
  code : function() {
  
    ////console.log("Doing the Code");
  	/* ENTER CODE */
  	$('#enter-code').click(function(e){ 
  	  e.preventDefault();
      screening_room.sendCode(e); 
    });
  	/* END ENTER CODE */
  	
  },
  
  sendCode : function(e) {
  	
    ////console.log("Sending Code");
  	if($('#fld-code').val() == '')
  	{				
  		error.showError("error","Your code is empty. Please enter a code");
      return false;
  	}
  	
  	var args = {'ticket': $('#fld-code').val(),
                'film': $('#film').html(),
                'screening': $('#screening').html()}
  	$.ajax({
      url: '/services/Exchange', 
      data: $.param(args), 
      type: "POST", 
      cache: false, 
      dataType: "json", 
      success: function(response) {
        if (response.exchangeResponse.result == "promo") {
          if ($("#promo_code").val() == '') {
            $("#promo_code").val($('#fld-code').val());
            $("#fld-code").attr("disabled","disabled");
            $("#enter-code").hide();
            if (response.exchangeResponse.type == 1) {
              screening_room.applyDiscount( screening_room.currentPrice * (1 - (response.exchangeResponse.discount/100) ) );
            } else if (response.exchangeResponse.type == 2) {
              //console.log("Current Price:" + screening_room.currentPrice);
              //console.log("Discount Price:" + response.exchangeResponse.discount);
              //console.log("New Price:" + screening_room.currentPrice - response.exchangeResponse.discount);
              screening_room.applyDiscount( screening_room.currentPrice - response.exchangeResponse.discount );
            }
            error.showError("error",response.exchangeResponse.message);
          } else {
            error.showError("error","You've already used a discount code.");
          }
        }else if (response.exchangeResponse.result == "success") {
          $("#screening").html(response.exchangeResponse.theurl);
          screening_room.confirm();
        } else {
          error.showError("error",response.exchangeResponse.message);
        }
      }, error: function(response) {
          ////console.log("ERROR:", response);
          error.showError("error",response.exchangeResponse.message);
      }
    });
  },
  
  isValidEmailAddress: function (emailAddress) {
  	var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
  	return pattern.test(emailAddress);
  },
  
  //HBR involves someone hosting their own show to see a screening immediately
  //So we need to clear the "Screening" Value
  hostbyrequest: function() {
    
    modal.modalIn( screening_room.hideWindow );
		
		//console.log("HBR!");
    //Since we HBR, there is no time or host
    $("#screening").html("");
    
    if ($('#screening_purchase').length == 0) {
      $("#login_destination").val(window.location.href+"/request");
      $("#signup_destination").val(window.location.href+"/request");
      login.showpopup();
    }
    
    $("#dohbr").val("true");
    $(".current_screening_time").html("Now");
    $(".current_screening_host").html("You");
    
    screening_room.updatePrice( $("#dohbr_ticket_price").html() );
    
    setTop("#screening_purchase");
    $("#screening_purchase").fadeIn();
    
    //console.log($("#screening").html());
    
    screening_room.pay();
    
  },
  
  request : function() {
    
    $("#dohbr").val("true");
    
    $(".current_screening_time").html("Now");
    $(".current_screening_host").html("You");
    
    setTop("#watch_by_request");
    $("#watch_by_request").fadeIn();
    
    ////console.log("Doing the Code");
  	/* ENTER CODE */
  	$('#watch-enter-code').click(function(e){ 
  	  e.preventDefault();
      screening_room.sendWBR(e); 
    });
  	/* END ENTER CODE */
  	
  },
  
  //WBR involves someone using a "CODE" to see a screening immediately
  sendWBR : function(e) {
  	
    ////console.log("Sending Code");
  	if($('#watch-fld-code').val() == '')
  	{				
  		error.showError("error","Your code is empty. Please enter a code");
      return false;
  	}
  	
    $("#watch_by_request").fadeOut();
    setTop("#process");
    $("#process").fadeIn();
    
  	var args = {'ticket': $('#watch-fld-code').val(),
                'screening': $('#screening').html(),
                'film': $('#film').html()}
  	$.ajax({
      url: '/services/Code', 
      data: $.param(args), 
      type: "POST", 
      cache: false, 
      dataType: "json", 
      success: function(response) {
        if (response.codeResponse.result == "success") {
          $("#screening").html(response.codeResponse.theurl);
          screening_room.confirmWBR();
        } else {
          error.showError("error",response.codeResponse.message);
        }
      }, error: function(response) {
          ////console.log("ERROR:", response);
          error.showError("error",response.codeResponse.message);
      }
    });
  },
  
  confirm : function() {
    console.log("Purchse Confirm 2");
    $("#screening_full_link").html('/theater/'+$("#screening").html());
    $("#screening_click_link").attr('href','/theater/'+$("#screening").html());
    if ($("#dohbr").val() == "true") {
      $(".current_screening_time").html("Now");
      $(".current_screening_host").html("You");
    } else {
      $(".current_screening_time").html($("#time_"+$("#screening").html()).html());
      $(".current_screening_host").html($("#host_"+$("#screening").html()).html());
    }
    $('#screening_click_link').click(function(e){
  		e.preventDefault();
      window.location.href = '/theater/'+$("#screening").html();
    });
    screening_room.purchaseSubmitted = false;
    $("#process").fadeOut("slow").queue(function() {
      setTop("#confirm");
      $("#confirm").fadeIn("slow");
      $("#process").clearQueue();
    });
    console.log("Redirect to "+'/theater/'+$("#screening").html());
    //window.location.href = '/theater/'+$("#screening").html();
  },
  
  confirmWBR : function() {
    console.log("Purchse WBR Confirm");
    $("#wbr_screening_full_link").html('/forward?dest='+$("#screening").html());
    $("#wbr_screening_click_link").attr('href','/forward?dest='+$("#screening").html());
    $(".current_screening_time").html($("#time_"+$("#screening").html()));
    $('#wbr_screening_click_link').click(function(e){
  		e.preventDefault();
      window.location.href = '/forward?dest='+$("#screening").html();
    });
    screening_room.purchaseSubmitted = false;
    $("#process").fadeOut("slow").queue(function() {
      setTop("#watch_by_request_confirm");
      $("#watch_by_request_confirm").fadeIn("slow");
      $("#process").clearQueue();
    });
    console.log("Redirect to "+'/theater/'+$("#screening").html());
    //window.location.href = '/forward?dest='+$("#screening").html();
  },
  
  facebookShare : function () {
    window.open("/facebook/"+$("#film").html(), "facebookShare", "width=600,height=300,scrollbars=yes");
  }, 
  
  facebookDiscount: function() {
    
    console.log("Applying Discount");
    if (! screening_room.facebook_discount) {
	    console.log("Applying Discount Really");
      screening_room.facebook_discount = true;
      screening_room.applyDiscount( screening_room.currentPrice - 1 );
      $('#facebook_share').attr('checked','checked');
    }
  },
  
  remoteDiscount : function() {
    console.log("Remove Discount");
    window.opener.screening_room.facebookDiscount();
    window.close();
  },
  
  swapState : function() {
		var code = $("select.cc-country-drop").val();
		var country = $("option:selected", this).text();
		
		if(code == "US") {
			$("select.cc-state-drop").show();
			$("input.cc-state-text").hide();
		} else {
			$("select.cc-state-drop").hide();
			$("input.cc-state-text").show();
			$("input.cc-state-text").val(country);
		}
	},
	
	hideWindow : function() {
		$(".pops").fadeOut(100);
	}
  
}

$(document).ready(function(){
	if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
  
	screening_room.init();
	screening_room.code();
	
});
