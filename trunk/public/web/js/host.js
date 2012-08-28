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
  total_invites: 0,
  price_threshold: .75,
  gbip: false,
  
  init: function() {
    
    host_screening.totalPrice = parseFloat($("#setup_price").val());
    host_screening.currentPrice = host_screening.totalPrice;
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
    
    $("#host_next_detail").click(function(e) {
    	e.preventDefault();
    	var film_id = $('#film_id option:selected').val();
    	if (film_id > 0) {
				window.location.href="/hosting/"+film_id+"/host_screening";
			}
		});
		
		$("#host_first").click(function(e) {
    	e.preventDefault();
    	console.log($("input[name=film_id]").val());
    	window.location.href="/hosting?fid="+$("input[name=film_id]").val();
		});
		
    $("#host_last").click(function(e) {
    	e.preventDefault();
			$(".host_step_two").fadeOut(100).queue(function() {
				$(".host_step_one").fadeIn(100);
				host_screening.hostSubmitted = false;
				host_screening.linkHostNext();
				$(".host_step_two").clearQueue();
			});
		});
		
		
		$('select').each(function(select){
        var $select = $(this);
        var text = $('option:selected', $select).html();
    		$select.prev().html(text);
    		if($select.attr("name") == "film_id") {
    			var film_id = $('option:selected', $select).val();
    			host_screening.setFilmPoster( film_id );
			  }
    		$select.bind('change', host_screening.onSelectChange);
    });
		
  },
  
  onSelectChange: function (event){
    var $select = $(event.target);
    var text = $('option:selected', $select).html();
		var $option = $('option:selected', $select);
    if($select.attr("name") == "film_id") {
	    var film_id = $('option:selected', $select).val();
	    host_screening.setFilmPoster( film_id );
	  }
		
    $select.prev().html(text);
  	if ($option.attr("title") == 0) {
			$("#hosting_aval").text('Currently, this option is unavailable.');
			$("#host_next_detail").attr('disabled', 'disabled');
		} else {
			$("#hosting_aval").text('');
			$("#host_next_detail").removeAttr('disabled');
		}
  },
  
  setFilmPoster: function (film_id) {
		console.log(film_id);
		if (fdata["f"+film_id] != undefined) {
			$(".film_details img").attr('src','/uploads/screeningResources/'+film_id+'/logo/poster'+fdata["f"+film_id].logo);
		}
		if (fdata["p"+film_id] != undefined) {
			$(".film_details img").attr('src','/uploads/screeningResources/'+film_id+'/logo/poster'+fdata["p"+film_id].logo);
		}
	},
	
	linkHostNext: function() {
		
    $("#host_next").click(function( e ){
      console.log("Host Step");
      e.preventDefault();
  		if (host_screening.validateDetail()) {
  		  host_screening.hostSubmitted = true;
        newScreening($("#host_form_one"),
                    "/host/"+$("#film").html()+"/detail",
                    host_screening.detailResult,
                    "host");
      }
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
    
    if ($("#loggedUser").length == 0) {
      $("#login_destination").val('/hosting/'+$("#film").html()+'/host_screening');
      login.showpopup();
      return;
    }
    
    if (host_screening.gbip) {
      host_screening.geoblock();
      return;
    }
    
    $(".host_step_two").fadeOut(100).queue(function() {
			$(".host_step_one").fadeIn(100);
			$(".host_step_two").clearQueue();
		});
		
		host_screening.linkHostNext();
    /*
    $("#host_next").click(function(e) {
    	e.preventDefault();
			$(".host_step_one").fadeOut(100).queue(function() {
				$(".host_step_two").fadeIn(100);
				$(".host_step_one").clearQueue();
			});
		});        
    */
    
    $("#host_details").fadeIn("slow");
    
    $(".host_type").click(function(){
      $("#hosting_type").val($(this).html());
      $(".host_type").attr('class','btn_medium host_type');
      $(this).attr('class','btn_medium_og host_type');
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
      var theurl = $("#setup_price").val();
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
  
	sentInvites: function ( count ) {
  	host_screening.total_invites = host_screening.total_invites + count;
  	$(".count_friends").html(host_screening.total_invites);
    $("#host_invite_count").val(host_screening.total_invites);
  	var dcnt = new Number(host_screening.priceDiscount);
  	var aprice = dcnt * count;
		host_screening.updatePrice( host_screening.currentPrice - aprice );
  },
  
  updatePrice : function ( price ) {
		var tot = new Number(host_screening.totalPrice);
    var thaprice = tot * host_screening.price_threshold;
    if (host_screening.currentPrice == 0) return;
		if ((price > 0) && (price >= thaprice)) {
      host_screening.currentPrice = price;
      var num = new Number(host_screening.currentPrice);
      if (price > 0) {
     		$(".host-ticket_price").html("$"+num.toFixed(2));
      } else {
				$(".host-ticket_price").html("FREE");
			}
      $("#setup_price").val(num.toFixed(2));
    }
  },
  
  updateCount : function () {
    //IC are previously sent Invites
    var ic = $("#host_invite_count").val();
    $(".host_number_invites").html(parseInt(ic) + parseInt($('#host_invite_count').val()));
  },
  
  goPaypal : function() {
    if (($("#screening").html() != "") && ($("#film").html() != "")) {
      var theurl = $("#setup_price").val();
      var theprice = theurl.replace(".","_");
      window.location.href = "/services/Paypal/express/host?vars="+$("#screening").html()+"-"+$("#film").html()+"-"+theprice;
    }
  },
  
  pay : function() {
    
    $(".host_step_one").fadeOut(100).queue(function() {
			$(".host_step_two").fadeIn(100);
			$(".host_step_one").clearQueue();
		});
			
    $("#host_purchase_submit").click(function(){
    	
      if (host_screening.validatePurchase()) {
        $("#process").fadeIn();
        
        host_screening.purchaseSubmitted = true;
        newScreening($("#host_form_two"),
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
    $("#host_confirm").fadeIn("slow");
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
