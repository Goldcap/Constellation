function newRevMessage(form) {
    var theval = form.find("textarea[name=messagebody]").val();
    if ((/Enter Your Message Here/.test(theval)) || (theval == '')) {
      $("#"+recommend.currentPanel+"message").html("Please enter a value.").css("color","red");
      return false;
    }
    var message = form.formToDict();
    var disabled = form.find("input[type=submit]");
    disabled.disable();
    $.postJSON("/services/Review", message, function(response) {
        recommend.showMessage(response);
        if (message.id) {
            form.parent().remove();
        } else {
            form.find("input[type=text]").val("").select();
            disabled.enable();
        }
    });
}

var recommend = {
    
    currentPanel: "none",
    watermark: "Enter Your Message Here",
    
    init: function() {
      
        $("#review_panel h4").click(function(e){
          $("#review_panel").fadeOut("slow");
        });
        
        if ($("#writeform").length > 0) {
          $("#writeform").live("submit", function() {
              newRevMessage($(this));
              $("#writemessage").val('');
              return false;
          });
          
          $("#write-submit").click(function(e){
              newRevMessage($("#writeform"));
              $("#writemessage").val('');
              return false;
          });
          
          $("#writemessage").watermark(recommend.watermark);
          
          $("#btn-write").click(function(e){
            recommend.togglePanel('write');
          });
          
          $("#write_edit").click(function(e){
            $("#write_inbox").hide();
            $("#write_form").fadeIn("slow");
          });
        }
    },
    
    showMessage: function(message) {
      try {
      if ((message) && (message.length > 0)) {
        var existing = $("#r" + message.response[0].id);
        if (existing.length > 0) {
          $("#r" + message.response[0].id).remove();
        }
        var node = $(message.response[0].html);
        var panel = message.response[0].panel;
        $("#"+panel+"_form").hide();
        $("#"+panel+"_inbox").fadeIn("slow");
        $("#"+panel+"_inbox").append(node);
      }} catch (e) {
        console.log("Message Recommend Error");
      }
    },
    
    togglePanel: function( panel ) {
      if ($("#review_panel").css("height") == "400px") {
        if (panel != recommend.currentPanel) {
          $("#"+recommend.currentPanel+"_popup").hide()
          recommend.doFadeIn( panel );
          return;
        }
        recommend.doFadeOut( panel );
        $('#review_panel').animate({
          height: '230px'
        }, {
          duration: 500,
          complete: function() {
          }
        });
      } else {
        $('#review_panel').animate({
          height: '400px'
        }, {
          duration: 500,
          complete: function() {
            recommend.doFadeIn( panel );
          }
        });
      }
    },
    
    doFadeIn: function ( apanel ) {
      if (apanel != undefined) {
        recommend.currentPanel = apanel;
        $("#"+apanel+"_popup").fadeIn("slow");
        $("#"+apanel+"message").select();
      }
    },
    
    doFadeOut: function ( apanel ) {
      recommend.currentPanel = "none";
      $("#"+panel+"_popup").fadeOut("slow");
    }

}

$(document).ready(function() {
  if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
  
  recommend.init();
  
});
