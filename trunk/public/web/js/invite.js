var invite = {
  
  itRetrievedContacts: 0,
  collectedEmails : new Array(),
  purchaseSubmitted : false,
  currentPrice: 0,
  priceDiscount: .25,
  gbip: false,
  user_type: 'screening',
  screening: null,
                               
  init: function() {
  	
  	//console.log("INVITE");
  	
    if ($("#gbip").html() == 1) {
      invite.geoblock();
    }
    
		$('.lb_close').click(function(e) {
  		$("#invite_email_lb").fadeOut(100);
			modal.modalOut( invite.hidepopup );
		});
		
		$("#form_invite_email_body").inputlimiter({
                              limit: 150,
                              limitTextShow: false,
                              boxId: 'invite-textbox-limit',
                              remTextHideOnBlur: 'false'}
                              );
  	
    $("#btn-invite").click( function(e) {
      e.preventDefault();
      invite.sendInvites();
  	});
    var hash = window.location.search;
    if(/invite=true/.test(hash)){
      hash = hash.replace('?','');
      hash = hash.split('&');
      for(var i = 0; i < hash.length; i++){
          if(/type=/.test(hash[i])){
            var type = hash[i].replace('type=','')
          } else if(/screening=/.test(hash[i])){
            var screening = hash[i].replace('screening=','');
          }
      }
      if(!!screening && !! type){
        invite.invite(type, screening);
      }

    }
    
  },
  hidepopup: function(){
        $("#invite_email_lb").fadeOut();

  },
  goModal: function() {
		modal.modalIn( invite.hidepopup );
	},
	
  invite : function( type, screening ) {
  	
    if($('.main-login').length == 0 || /boxoffice/.test(window.location)){

  	if (screening === undefined){
			screening = $("#screening").html();
		}
  	invite.user_type = type;
  	invite.screening = screening;
  	
    setTop("#invite_email_lb");
    $("#invite_email_lb").fadeIn();
    invite.goModal();
    } else {
        var url = window.location.protocol + '//' + window.location.hostname + window.location.pathname;
        url = url + (!!window.location.search ? window.location.search + '&' : '?') + 'invite=true&invite-type='+ type + '&screening=' + screening;

        console.log(url);

        $("#login_destination").val(url);
        $("#signup_destination").val(url);
        login.showpopup();
    }
    
  },
  
  showErrors : function( step ) {
  	if (! $("#" + step + ' .error-panel').is(':visible')) {
  	 	$("#" + step + ' .error-panel').animate({
  			width: 'toggle'
  		}, 500);
  	}
  },
  
  sendInvites: function() {
    
    console.log("Sending Invites");
    if ($('#form_invite_email_to').val() == '') {
      error.showError("error","There are no people to invite,<br /> please add some email addresses and try again.");
      return;
    }
    
    if ($('#form_invite_email_subject').val() == '') {
      error.showError("error","Please enter a subject.");
      return;
    }
    
    if ($('#form_invite_email_body').val() == '') {
      error.showError("error","Please enter a message.");
      return;
    }
    
    error.showError("alert",'<p align="center">Sending Invitations...</p>','<img src="/images/ajax-loader.gif" alt="loading" />',2000);
    
    emails = [];
    console.log("There are "+ emails.length + " emails.");
    j=0;
    
    var eml = $('#form_invite_email_to').val().split(',');
    $.each(eml,function(index,elm) {
    	var mail = elm.replace(/^\s+|\s+$/g,'');
    	console.log(mail);
    	if (invite.isValidEmailAddress(mail)) {
        emails.push(mail);
      }
    });
    
    var args = {"emails":emails,
                "user_type": invite.user_type,
                "screening":invite.screening,
                "subject":$('#form_invite_email_subject').val().replace(/\n/g, '<br/>' ),
                "message":$('#form_invite_email_body').val().replace(/\n/g, '<br/>' )};
    
    $.ajax({
      url: '/services/Invite/send', 
      data: $.param(args), 
      type: "POST", 
      cache: false, 
      dataType: "json", 
      timeout: 4000,
      success: function(response) {
          invite.finishInvites( response );
      }, error: function(response) {
          console.log("ERROR:", response);
					error.showError('error','communication error','Please try again');
      }
    });
  },
  
  finishInvites: function( response ) {
  	//Release the "process" UI
		if (response.result == "success") {
			try {
			if (typeof screening_room != "undefined") {
				screening_room.sentInvites(response.count);
			}} catch (err) {}
			
			try {
			if (typeof host_screening != "undefined") {
				host_screening.sentInvites(response.count);
			}} catch (err) {}
			
    	error.showError('alert',response.message,null,2000); 
      $('#invite_email_lb').delay(4000).fadeOut(100);
      modal.modalDestroy();
    } else {
      error.showError('error',response.message,null,2000); 
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
