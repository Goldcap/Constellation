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
  price_threshold: .75,
  total_invites: 0,
  facebook_discount: false,
  gbip: false,
  step_size: 500,
                      
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
    if (window.location.pathname.match(/\/theater\/(.+)\/detail/)) {
    	var obj = window.location.pathname.split("/");
			if (obj[2] != undefined) {
				console.log("Found "+obj[2]+" in the path");
	      //$("#screening").html( obj[2] );
        screening_room.pay();
      }
    }
    
    //If we're in the hosting step, show the popup
    if (window.location.pathname.match(/\/detail/)) {
    	var obj = window.location.pathname.split("/");
      if (obj[4] != undefined) {
				console.log("Found "+obj[4]+" in the path");
	      $("#screening").html( obj[4] );
      }
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
  
  sentInvites: function ( count ) {
    screening_room.total_invites = screening_room.total_invites + count;
    $(".count_friends").html(screening_room.total_invites);
    $("#invite_count").val(screening_room.total_invites);
    var dcnt = new Number(screening_room.priceDiscount);
    var aprice = dcnt * count;
    screening_room.applyInviteDiscount();
		screening_room.updatePrice( screening_room.currentPrice - aprice );
  },
  
  updatePrice : function ( price ) {
   var tot = new Number(screening_room.totalPrice);
   var thaprice = tot * screening_room.price_threshold;
   if (screening_room.currentPrice == 0) return;
   if ((price > 0) && (price >= thaprice)) {
   		//console.log("updating price");
      screening_room.currentPrice = price;
      var num = new Number(screening_room.currentPrice);
      if (price > 0) {
       $(".ticket_price").html("$"+num.toFixed(2));
      } else {
        screening_room.setScreeningFree();
      }
      $("#ticket_price").val(num.toFixed(2));
    } else if (price < thaprice) {
			screening_room.currentPrice = thaprice;
			var num = new Number(screening_room.currentPrice);
      if (thaprice > 0) {
       $(".ticket_price").html("$"+num.toFixed(2));
      } else {
        screening_room.setScreeningFree();
      }
      $("#ticket_price").val(num.toFixed(2));
		}
  },
  
  applyInviteDiscount: function () {
   if (screening_room.currentPrice == 0) return;
   var tefic = new Number($("#fic").html());
   console.log(tefic);
   if (tefic < 1) return;
   if (screening_room.total_invites >= tefic) {
      console.log("updating total invite discount");
      screening_room.currentPrice = 0;
      var num = new Number(screening_room.currentPrice);
      screening_room.setScreeningFree();
      $("#ticket_price").val(num.toFixed(2));
    }
  },
  
  applyDiscount: function ( price ) {
   console.log(price);
   if (screening_room.currentPrice == 0) return;
   if (price >= 0) {
      console.log("updating discount");
      screening_room.currentPrice = price;
      var num = new Number(screening_room.currentPrice);
      if (price > 0) {
       $(".ticket_price").html("$"+num.toFixed(2));
      } else {
        screening_room.setScreeningFree();
      }
      $("#ticket_price").val(num.toFixed(2));
    }
  },
  
  setScreeningFree: function () {
  	
    //console.log("Screening is: '"+$("#screening").html()+"'");
    var this_screening = $("#screening").html();
    
		$(".ticket_price").html("FREE");
		$(".purchase_next").hide();
    $(".cc_items").hide();
    var node = '<input id="purchase_submit_free" type="image" src="/images/alt1/purchase_final.png" value="get ticket" />';
    if ($(".step_one_purchase").length > 0) {
      $("#purchase_submit").remove();
      $(".step_one_purchase").append(node);
    } else if ($(".cc-submit").length > 0) {
		  $("#purchase_submit").remove();
		  $(".cc-submit").append(node);
		}    
    
		$("#purchase_submit_free").click(function(){
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
	},
	
  updateCount : function () {
    //IC are previously sent Invites
    var ic = $("#invite_count").val();
    $("#number_invites").html( parseInt(ic) + parseInt($('#accepted-invites-container li').length) );
  },
  
  geoblock : function() {
   	error.showError("error","Viewing this film is restricted.","We are not allowed to play this film in your geographical area.",0);
  },
  
  showErrors : function( step ) {
    if (! $("#" + step + ' .error-panel').is(':visible')) {
      $("#" + step + ' .error-panel').animate({
        width: 'toggle'
      }, 500);
    }
  },
  
  goPaypal : function() {
    //console.log($("#screening").html());
    //console.log($("#film").html());
    console.log("go paypal!");
    if ((($("#screening").html() != "") || ($("#dohbr").val() == "true")) && ($("#film").html() != "")) {
      var theurl = $("#ticket_price").val();
      var theprice = theurl.replace(".","_");
      if ($("#dohbr").val() == "true") {
        screening = "hbr";
      } else {
        screening = $("#screening").html();
      }
      window.location.href = "/services/Paypal/express/screening?vars="+screening+"-"+$("#film").html()+"-"+theprice;
    }
  },
  
  pay : function() {
    
		if ($("#userid").html() == "0") {
    	if (window.location.pathname.match(/\/theater/)) {
      	$("#login_destination").val(window.location.href.replace("#","")+"/detail");
				$("#signup_destination").val(window.location.href.replace("#","")+"/detail");
      	$("#login_fb_link").attr("href","/services/Facebook/login?dest="+window.location.href.replace("#","")+"/detail");
      } else {
				$("#login_destination").val(window.location.href.replace("#","")+"/"+$("#screening").html()+"/detail");
				$("#signup_destination").val(window.location.href.replace("#","")+"/"+$("#screening").html()+"/detail");
      	$("#login_fb_link").attr("href","/services/Facebook/login?dest="+window.location.href.replace("#","")+"/"+$("#screening").html()+"/detail");
			}
      login.showpopup();
      return;
    }
    
    /*
    if ($("#screening_invite").length == 0) {
      login.showpopup();
      return;
    }
    */
    
    $(".special_offer").fadeIn();
    $(".purchase_form_coupon").fadeIn();
    
    //If we've already detected the bandwidth, and it's too low...
    if (typeof bandwidth != undefined) {
      if ((bandwidth.bandwidth > -1) && (bandwidth.bandwidth < bandwidth.threshold)){
        $(".current_bandwidth").html(bandwidth.bandwidth);
        $(".bandwidth_warning").fadeIn(100);
      }
    }
    
    //Note, this was moved from the #invite() feature
    //And should be moved back when invite is re-integrated
    if (screening_room.gbip) {
      screening_room.geoblock();
      return false;
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
    
    $(".paypal_icon").click(function(e){
      e.preventDefault();
      screening_room.goPaypal();
    });
    
    $("#purchase_submit").click(function(e){
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
        if (screening_room.validatePurchaseOne()) {
        $(".step_one").animate({
          left: '-='+screening_room.step_size
        }, 100, 'linear', function() {
          // Animation complete.
        });
        $(".step_two").animate({
          left: '-='+screening_room.step_size
        }, 100, 'linear', function() {
          // Animation complete.
        });
        }
      });
      
      $(".purchase_last").click(function(){
        $(".step_one").animate({
          left: '+='+screening_room.step_size
        }, 100, 'linear', function() {
          // Animation complete.
        });
        
        $(".step_two").animate({
          left: '+='+screening_room.step_size
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
    
    // code for z-index
    $('#footer').fadeOut();
    $('#chat_panel').fadeOut();
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
  	
    if ($("#film_free_screening").val() == 1) {
			return true;
		}
		var err = 0;
    var price = $("#ticket_price").val();
    if ($("#fld-cc_first_name").val() == '') { $("#fld-cc_first_name").css('background','#ff6666'); err++; } else { $("#fld-cc_first_name").css('background','#A5D0ED'); }
    if ($("#fld-cc_last_name").val() == '') { $("#fld-cc_last_name").css('background','#ff6666'); err++; } else { $("#fld-cc_last_name").css('background','#A5D0ED'); }
    if (! screening_room.isValidEmailAddress($("#fld-cc_email").val())) { $("#fld-cc_email").css('background','#ff6666'); err++; } else { $("#fld-cc_email").css('background','#A5D0ED'); }
    if (! screening_room.isValidEmailAddress($("#fld-cc_confirm_email").val())) { $("#fld-cc_confirm_email").css('background','#ff6666'); err++; } else { $("#fld-cc_confirm_email").css('background','#A5D0ED'); }
    if (screening_room.isValidEmailAddress($("#fld-cc_email").val()) && screening_room.isValidEmailAddress($("#fld-cc_confirm_email").val())) {
      if ($("#fld-cc_email").val() != $("#fld-cc_confirm_email").val()) { $("#fld-cc_email").css('background','#ff6666'); $("#fld-cc_confirm_email").css('background','#ff6666'); err++; } else { $("#fld-cc_email").css('background','#A5D0ED'); $("#fld-cc_confirm_email").css('background','#A5D0ED'); }
    }
    if ($("#fld-cc_city").val() == '') { $("#fld-cc_city").css('background','#ff6666'); err++; } else { $("#fld-cc_city").css('background','#A5D0ED'); }
    //console.log("PRICE SHOULD SKIP!" + price );
    if (price != 0) {
      //console.log("PRICE DIDN'T SKIP!" + price );
      if ($("#fld-cc_number").val() == '') { $("#fld-cc_number").css('background','#ff6666'); err++; } else { $("#fld-cc_number").css('background','#A5D0ED'); }
      if ($("#fld-cc_security_number").val() == '') { $("#fld-cc_security_number").css('background','#ff6666'); err++; } else { $("#fld-cc_security_number").css('background','#A5D0ED'); }
      if ($("#fld-cc_address1").val() == '') { $("#fld-cc_address1").css('background','#ff6666'); err++; } else { $("#fld-cc_address1").css('background','#A5D0ED'); }
      if ($("#fld-cc_zip").val() == '') { $("#fld-cc_zip").css('background','#ff6666'); err++; } else { $("#fld-cc_zip").css('background','#A5D0ED'); }
    }
    console.log(err);
    if (err > 0) {
      console.log("price: " + price);
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
  
  validatePurchaseOne: function() {
    var err = 0;
    var price = $("#ticket_price").val();
    if ($("#fld-cc_first_name").val() == '') { $("#fld-cc_first_name").css('background','#ff6666'); err++; } else { $("#fld-cc_first_name").css('background','#A5D0ED'); }
    if ($("#fld-cc_last_name").val() == '') { $("#fld-cc_last_name").css('background','#ff6666'); err++; } else { $("#fld-cc_last_name").css('background','#A5D0ED'); }
    if (! screening_room.isValidEmailAddress($("#fld-cc_email").val())) { $("#fld-cc_email").css('background','#ff6666'); err++; } else { $("#fld-cc_email").css('background','#A5D0ED'); }
    if (! screening_room.isValidEmailAddress($("#fld-cc_confirm_email").val())) { $("#fld-cc_confirm_email").css('background','#ff6666'); err++; } else { $("#fld-cc_confirm_email").css('background','#A5D0ED'); }
    if (screening_room.isValidEmailAddress($("#fld-cc_email").val()) && screening_room.isValidEmailAddress($("#fld-cc_confirm_email").val())) {
      if ($("#fld-cc_email").val() != $("#fld-cc_confirm_email").val()) { $("#fld-cc_email").css('background','#ff6666'); $("#fld-cc_confirm_email").css('background','#ff6666'); err++; } else { $("#fld-cc_email").css('background','#A5D0ED'); $("#fld-cc_confirm_email").css('background','#A5D0ED'); }
    }
    if ($("#fld-cc_address1").val() == '') { $("#fld-cc_address1").css('background','#ff6666'); err++; } else { $("#fld-cc_address1").css('background','#A5D0ED'); }
    if ($("#fld-cc_city").val() == '') { $("#fld-cc_city").css('background','#ff6666'); err++; } else { $("#fld-cc_city").css('background','#A5D0ED'); }
    if (err > 0) {
      console.log("price: " + price);
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
      error.showError("error",response.purchaseResponse.result,response.purchaseResponse.message);
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
    
    //console.log("HBR!");
    //Since we HBR, there is no time or host
    $("#screening").html("");
    
    if ($('#screening_purchase').length == 0) {
      var theloc = window.location.href;
			$("#login_destination").val(theloc.replace(/#p=(\d)+/,"")+"/request");
      $("#signup_destination").val(theloc.replace(/#p=(\d)+/,"")+"/request");
      $("#login_fb_link").attr('href',$("#login_fb_link").attr('href')+'/request');
      $("#login_twitter_link").attr('href',$("#login_twitter_link").attr('href')+'/request');
      
      login.showpopup();
      return;
    }
    
    modal.modalIn( screening_room.hideWindow );
    
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
    $('.pre_host_message').delay(10000).fadeOut();
    
    console.log("Confirm Redirect to "+'/theater/'+$("#screening").html());
    window.location = '/forward?dest='+$("#screening").html();
    //window.location = '/theater/'+$("#screening").html();
  },
  
  confirmWBR : function() {
    console.log("Purchse WBR Confirm");
    $("#wbr_screening_full_link").html('/forward?dest='+$("#screening").html());
    $("#wbr_screening_click_link").attr('href','/forward?dest='+$("#screening").html());
    $(".current_screening_time").html($("#time_"+$("#screening").html()));
    $('#wbr_screening_click_link').click(function(e){
      e.preventDefault();
      window.location = '/forward?dest='+$("#screening").html();
    });
    screening_room.purchaseSubmitted = false;
    $("#process").fadeOut("slow").queue(function() {
      setTop("#watch_by_request_confirm");
      $("#watch_by_request_confirm").fadeIn("slow");
      $("#process").clearQueue();
    });
    console.log("Confirm WBR Redirect to "+'/theater/'+$("#screening").html());
    window.location = '/forward?dest='+$("#screening").html();
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

          window.opener.onFacebookDiscount();


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
