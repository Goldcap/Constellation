var keyevent = {
  
  keyCode: null,
  keyId: null,
    
  init: function() {
    
    //console.log("KeyEvent Init");
    $(document).keypress(keyevent.keyhandler);
    
  },
  
  keyhandler: function( e ) {
    keyevent.keyCode = e.keyCode || e.which;
    keyevent.keyId = e.target.id || null;
    if (keyevent.keyCode == 13) {
      e.preventDefault();
    } else {
      return;
    }
    console.log("Code is: " + keyevent.keyCode);
    console.log("ID is: " + keyevent.keyId);
    
    if (keyevent.keyId == null) {
     return;
    }
    keyevent.process( e );
  },
  
  process: function( e ) {
  
    if ((keyevent.keyId == "login_email") || (keyevent.keyId == "login_password")) {
      if (($("#login_email").val() == 'email') || ($("#login_password").val() == 'password')) {
        return;
      } else if (login.validateLogin()) {
        $("#login_form").submit();
      }
    }
    
    if (keyevent.keyId == "password_email") {
       if (login.validatePassword()) {
        login.resetPassword();
      }
    }
    
    if (keyevent.keyId == "message") {
      if (($("#message").val() == chat.watermark) || ($("#message").val() == '')) {
          return false;
        }
        newMessage($("#messageform"));
        $("#message").val('');
        return false;
    }
    
   if (keyevent.keyId == "qanda_message") {
      console.log("qanda_message submit");
      if (($("#qanda_message").val() == qanda.watermark) || ($("#qanda_message").val() == '')) {
        console.log("qanda_message blank");
        return false;
      }
      newQAMessage($("#qandaform"));
      $("#qanda_message").val('');
      return false;
    }
    
    if (keyevent.keyId == "editablepurchase_editable") {
       var value = $("#editablepurchase_editable").val();
       screening_room.addContactToList(value);
    }
    
    if (keyevent.keyId == "editablehost_editable") {
       var value = $("#editablehost_editable").val();
       host_screening.addContactToList(value);
    }
    
  }

}

$(document).ready( function() {
  if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
  
  keyevent.init();
});
