function newPurchase(form) {
    var vals = form.PurchaseFormToDict();
    $("#screening_purchase").fadeOut();
    $("#process").fadeIn();
    $.postPurchaseJSON(
      "/screening/"+$("#film").html()+"/purchase/"+$("#screening").html(), 
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
  
  purchaseSubmitted : false,
  gbip: false,
                      
  init: function() {
    
    if ($("#gbip").html() == 1) {
      screening_room.gbip = true;
    }
    
    $("#watch_request_button").click(function(){
      $("#screening").html("");
      screening_room.pay();
      return false;
    });
    
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
  
  pay : function() {
  
    $(".current_screening_time").html($("#time_"+$("#screening").html()).html());
    $(".current_screening_host").html($("#host_"+$("#screening").html()).html());
    
    $("#process").fadeOut();
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
  
  code : function() {
  
    //console.log("Doing the Code");
  	/* ENTER CODE */
  	$('#enter-code').click(function(e){ 
  	  e.preventDefault();
      screening_room.sendCode(e); 
    });
  	/* END ENTER CODE */
  	
  },
  
  sendCode : function(e) {
  	
    //console.log("Sending Code");
  	if($('#fld-code').val() == '')
  	{				
  		error.showError("error","Your code is empty. Please enter a code");
      return false;
  	}
  	
    $("#screening_purchase").fadeOut();
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
          error.showError("error",response.codeResponse.message);
        }
      }, error: function(response) {
          //console.log("ERROR:", response);
          error.showError("error",response.codeResponse.message);
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
      $("#confirm").fadeIn("slow");
      $("#process").clearQueue();
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
 
	screening_room.init();
	screening_room.code();
	
});
