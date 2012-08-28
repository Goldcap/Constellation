jQuery.fn.FeedbackFormToDict = function() {
   var fields = this.serializeArray();
    var json = {}
    for (var i = 0; i < fields.length; i++) {
	json[fields[i].name] = fields[i].value;
    }
    if (json.next) delete json.next;
    return json;
}


// JavaScript Document
var feedback = {
  
  init: function() {
    //$.cookie("csfeedback",1);
    //console.log("Feedback Cookie Is " + $.cookie("csfeedback"));
    /*if ($.cookie("csfeedback") == undefined) {
      var randomnumber=Math.floor(Math.random()*11);
      var randomnumber=Math.floor(Math.random()*11);
      $.cookie("csfeedback",randomnumber);
    } else 
    */
    if ($.cookie("csfeedback") == undefined) {
      //Alert with the "Do You Want to Take A Survey"
      //If "Yes", set Cookie to 100
      //IF "No", set Cookie to -1
      $.cookie("csfeedback",-1, { path: "/", expires: 365 });
      feedback.surveyAsk();
    }
    /*
     else if ($.cookie("csfeedback") == 100) {
      //Add the "Survey" Tab
      feedback.getFeedback();
    }
    */
  },
  
  surveyAsk: function() {
    var message = 'Can you help us make Constellation.tv better by answering a few brief questions?<br /><br /><a class="btn_med" href="javascript:void(0)" onclick="feedback.addTab()" style="padding-top: 15px !important; margin-right: 15px;">Yes, I\'d love to!</a><a class="btn_med" href="javascript:void(0)" onclick="feedback.dropTab()" style="padding-top: 15px !important;">No Thanks!</a>';
    error.showError( "error", "We'd love some feedback!", message, 0);
  },
  
  addTab: function( e ) {
    //console.log("Tab Will Show Up Next Time");
    //$.cookie("csfeedback",100);
    $.unblockUI(); 
    //feedback.showTab();
    feedback.getFeedback();
  },
  
  dropTab: function( e ) {
    //console.log("Tab Will NOT Show Up Next Time");
    $.cookie("csfeedback",-1, { path: "/",  expires: 365 } );
    $.unblockUI(); 
  },
  
  showTab: function() {
    //console.log("Showin' the tab...");
    var node = '<a style="background-color:#222" class="fdbk_tab_left" id="fdbk_tab" href="javascript: void(0)">FEEDBACK</a>';
    $("body").append(node);
    $("#fdbk_tab").click(function(e){
      feedback.getFeedback();
    });
  },
  
  getFeedback: function() {
    $("#feedback_popup").fadeIn();
    $("#feedback").click(function(e){
      var vals = $("#feedback_form").FeedbackFormToDict();
      $.ajax({url: "/services/UserFeedback", 
        data: $.param(vals), 
        dataType: "text", 
        type: "POST",
        success: function(response) {
      	 feedback.giveThanks();
        }, 
        error: function(response) {
      	 console.log("ERROR:", response)
        }
      });
    });
  },
  
  giveThanks: function() {
    $.cookie("csfeedback",-1, { path: "/", expires: 365});
    var theml = '<div class="clearfix description">Thank you!</div>';
    $("#feedbody").html(theml);
    $("#feedback_popup").delay(3000).fadeOut();
  }
  
}

$(document).ready(function() {
    if (!window.console) window.console = {};
    if (!window.console.log) window.console.log = function() {};
    
    feedback.showTab();
    feedback.init();
    
});
