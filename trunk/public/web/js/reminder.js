var reminder = {

  screening: null,
  
  remind : function( screening ) {
    
    reminder.screening = screening;
    
    $("#reminder-button").click(function(){
        if (reminder.validateReminder()) {
          reminder.sendReminder();
        }
    });
    $("#reminder_lb").fadeIn();
    
    reminder.goModal();
    
  },
  
  validateReminder: function() {
    if (reminder.isValidEmailAddress($("#form_reminder_email").val())) {
      return true;
    }
    return false;
  },
  
  isValidEmailAddress: function (emailAddress) {
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
  },
  
  sendReminder: function() {
      
      var args = {'screening': reminder.screening,
                  'email': $("#form_reminder_email").val()};
      
      $.ajax({url: '/services/ScreeningReminder', 
            data: $.param(args), 
            type: "GET", 
            cache: false, 
            dataType: "json", 
            success: function(response) {
            	reminder.reminderSuccess(response);
            }, error: function(response) {
              reminder.reminderError(response);
            }
      });
  },
  
  reminderSuccess: function(response) {
    if (response.reminderResponse.result == "success") {
      error.showError("alert",response.reminderResponse.message);
      login.hidepopup();
    } else {
     error.showError("error",response.reminderResponse.message);
    }
  },
  
  reminderError: function(response) {
     error.showError("error","There was an error with your request, please try again.");
  },
  
  
  goModal: function() {
    modal.modalIn( login.hidepopup );
  }
  
}

$(document).ready(function(){
  if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
  
});
