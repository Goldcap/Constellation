function newPurchase(form) {
    
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
          	 console.log("ERROR:", response)
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
      $("#screening").html("");
      screening_room.hostbyrequest();
      return false;
    });
    
    /*
    $("#watch_request_button").click(function(){
      $("#screening").html("");
      screening_room.pay();
      return false;
    });
    */
    
    //If we're in the hosting step, show the popup
    if (window.location.pathname.match(/\/detail/)) {
      var obj = window.location.pathname.split("/");
      console.log("Found "+obj[4]+" in the path");
      $("#screening").html( obj[4] );
      screening_room.pay();
      //host_screening.detail();
    } else
    
    //If we're in the hosting step, show the popup
    if (window.location.pathname.match(/\/confirm/)) {
      var obj = window.location.pathname.split("/");
      console.log("Found "+obj[4]+" in the path");
      $("#screening").html( obj[4] );
      screening_room.confirm();
      //host_screening.detail();
    }
    
    //If we're in the hosting step, show the popup
    if (window.location.pathname.match(/\/request/)) {
      screening_room.hostbyrequest();
      //host_screening.detail();
    }
    
    /* GENERIC CLICK HIDE ANY FLOATING DIVS */
  	$(document).click(function(){
  		$('#preview-invite-popup').hide();
  		
  		$('.reviews #see-more-review').click(function(e) {
  			$('#film-reviews #hidden-reviews').show();
  	 });
  	});
  	/* END GENERIC CLICK HIDE ANY FLOATING DIVS */
  	
  	//Authenticated Clicks
    $('#fld-code').keydown(function( e ) {
      var keyCode = e.keyCode || e.which;
      if (keyCode == 13) {
        e.preventDefault();
        screening_room.sendCode();
      }
    });
    
  },
  
  linkInvite: function() {
  
    //If we're in the hosting step, show the popup
    //NOTE:: This link is unused if the "upcoming.js" has been triggered
    //Look at "upcoming.js" in the "init()" function for a screening_room.pay(); call.
    $(".screening_link").click( function(e) {
      e.preventDefault();
      if ($('#screening_purchase').length > 0) {
        console.log("Showing Screening Purchase");
        $("#screening").html( $(this).attr("title") );
        $(".current_screening_time").html($("#time_"+$("#screening").html()).html());
        $(".current_screening_host").html($("#host_"+$("#screening").html()).html());
        screening_room.pay();
      }
    });
    
  },
  
  linkPurchase: function() {
    //Deprecated in Sponsor Template
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
    $("#geoblock").fadeIn();
  },
  
  isValidEmailAddress: function (emailAddress) {
  	var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
  	return pattern.test(emailAddress);
  },
  
  pay: function() {
		
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
    
    $(".pop_up").fadeOut();
		$(".pre_content").fadeOut();
		$(".prescreen_notice").fadeOut();
		$(".prescreen_purchase").fadeOut();
		
		$("#screening_purchase").fadeIn();
		
    $(".current_screening_time").html($("#time_"+$("#screening").html()).html());
    $(".current_screening_host").html($("#host_"+$("#screening").html()).html());
    
    $("#process").fadeOut();
    
    //console.log("Doing the Code");
  	/* ENTER CODE */
  	$('#enter-code').click(function(e){ 
  	  e.preventDefault();
      screening_room.sendCode(e); 
    });
  	/* END ENTER CODE */
  	
	},
	
	
  purchaseResult: function (response) {
    var response = eval("(" + response + ")");
    if (response.purchaseResponse.status == "success") {
      $("#screening").html(response.purchaseResponse.screening);
      // $("#purchase_result").html(response.purchaseResponse.result);
      screening_room.confirm();
    } else {
      $("#purchase_errors").html(response.purchaseResponse.message);
      screening_room.purchaseSubmitted = false;
      screening_room.pay();
    }
  },
  
  hostbyrequest : function() {
  	
    modal.modalIn( screening_room.hideWindow );
		
    $(".current_screening_time").html("Now");
    $(".current_screening_host").html("You");
    
    $("#process").fadeOut();
    
    //console.log("Doing the Code");
  	/* ENTER CODE */
  	$('#enter-code').click(function(e){ 
  	  e.preventDefault();
      screening_room.sendCode(e); 
    });
  	/* END ENTER CODE */
  	
    setTop("#screening_purchase");
    $("#screening_purchase").fadeIn();
    
  },

  sendCode : function(e) {
  	
    //console.log("Sending Code");
  	if($('#fld-code').val() == '')
  	{				
  		error.showError("error","Your code is empty.","Please enter a code.",2000);
      return false;
  	}
  	
    $("#screening_purchase").fadeOut();
    
		setTop("#process");
    $("#process").fadeIn();
    
  	var args = {'ticket': $('#fld-code').val(),
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
          screening_room.confirm();
        } else {
          $("#process").fadeOut();
          $("#screening_purchase").fadeIn();
          error.showError("error",response.codeResponse.message,"",2000);
        }
      }, error: function(response) {
          //console.log("ERROR:", response);
          error.showError("error",response.codeResponse.message,"",2000);
      }
    });
  },
  
  confirm : function() {
    $("#process").fadeOut();
    
    $("#screening_full_link").html('/forward?dest='+$("#screening").html());
    $("#screening_click_link").attr('href','/forward?dest='+$("#screening").html());
    $('#screening_click_link').click(function(e){
  		e.preventDefault();
      window.location.href = '/forward?dest='+$("#screening").html();
    });
    screening_room.purchaseSubmitted = false;
    $("#process").fadeOut("slow").queue(function() {
      setTop("#confirm");
			$("#confirm").fadeIn("slow");
      $("#process").clearQueue();
    });
    
    window.location.href = '/forward?dest='+$("#screening").html();
    
  },
  
  isValidEmailAddress: function (emailAddress) {
  	var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
  	return pattern.test(emailAddress);
  },
  
	hideWindow : function() {
		$(".pops").fadeOut(100);
	}
  
}

$(document).ready(function(){
	if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
 
	screening_room.init();
	
});
