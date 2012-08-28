function newQAMessage(form) {
  console.log("newQAMessage");
      
  if (qanda.showCount()) {
    //console.log("showCount result?" + qanda.showCount());
    if ((/Enter Your Message Here/.test($("#qandaform [name=body]").val())) || ($("#qandaform [name=body]").val() == '')) {
      $("#qa-message").html("Please enter a question. You have "+(qanda.totalquestions - $(".question").length)+" more questions.").css("color","red");
      return false;
    }
    var message = form.formToDict();
    //var disabled = form.find("input[type=submit]");
    //disabled.disable();
    console.log("Postin!");
    $.postQandAJSON("/services/qanda/post", message, 
        function(response) {
          qanda.showMessage(response);
          if (message.id) {
              form.parent().remove();
          } else {
              $("#qandaform [name=body]").val("");
              //form.find("input[type=text]").val("").select();
              //disabled.enable();
          }
      });
  }
}

jQuery.postQandAJSON = function(url, args, callback) {
    args.room = $("#room").html();
    args.author = $("#userid").html();
    $.ajax({url: url+'?i='+$("#host").html()+":"+(parseInt($("#port").html()) + 15090), 
            data: $.param(args), 
            type: "POST", 
            cache: false, 
            dataType: "text", 
            success: function(response) {
                if (callback) callback(eval("(" + response + ")"));
            }, error: function(response) {
                //console.log("ERROR:", response);
            }
    });
};

var qanda = {
    
    totalquestions: 4,
    errorSleepTime: 900000,
    watermark: "Enter Your Message Here",
    currentquestions: 0,
    pollRunning: true,
    slideRunning: true,
    host: null,
    port: null,
    instance: null,
    room: null,
    destination: null,
    user: null,
    inbox:null,
    listbox:null,
    selected_inbox:null,
    scroller: null,
    inbox: null,
    qanda_showing: false,
    
    construct: function() {
      qanda.host = $("#host").html();
      qanda.port = parseInt($("#port").html()) + 15090;
      qanda.instance = $("#instance").html();
      qanda.room = $("#room").html();
      //Pull this from the QANDA form value
      qanda.screening = $("#qscreening_id").html();
      qanda.destination = qanda.host + ":" + qanda.port;
      qanda.user = $("#userid").html();
      qanda.inbox = $("#qanda-inbox");
      qanda.listbox = $("#qa_list");
      qanda.selected_inbox = $("#qanda-inbox").html();
      
      /*
      // Set attributes as a second parameter
      $('<iframe />', {
          name: 'qanda_frame',
          id:   'qanda_frame',
          width: '0',
          height: '0',
          frameborder: '0',
          border: '0'
      }).appendTo('body');
      */
    },
    
    init: function() {
      
      $(".qa_pop_up .popup-close").click(function(e){
        toggleDiv("qanda_panel");
      });
      
      $("#qanda_switch").click(function(e){
        toggleDiv("qanda_panel");
      });
      
      $("#host_screening_webcam").click(function(e){
        if ($("#host_screening_webcam").val() == "disable webcam") {
					if (typeof videoplayer != undefined) {
						if ($("#is_host").length > 0) {
							videoplayer.hideHostCam();
						}
					}
					$("#host_screening_webcam").val("enable webcam");
        } else {
					if (typeof videoplayer != undefined) {
						if ($("#is_host").length > 0) {
							videoplayer.showHostCam();
						}
					}
          $("#host_screening_webcam").val("disable webcam");
        }
      });
      
      $("#host_screening_refresh").click(function(e){
        qanda.getCurrent();
      });
      
      $("#host_screening_qanda").click(function(e){
        toggleDiv("qanda_panel");
      });
      
      $("#qa_panel h4").click(function(e){
        toggleDiv("qanda_panel");
      });
      
      //This is the User Input Form
      if ($("#qandaform").length > 0) {
        
        console.log("#qandaform submit");
        $("#qandaform").submit( function() {
            if (($("#qanda_message").val() == qanda.watermark) || ($("#qanda_message").val() == '')) {
              return false;
            }
            newQAMessage($("#qandaform"));
            $("#qanda_message").val('');
            return false;
        });
        
        $("#qanda-submit").click(function(e){
            if (($("#qanda_message").val() == qanda.watermark) || ($("#qanda_message").val() == '')) {
              return false;
            }
            e.stopPropagation();
            newQAMessage($("#qandaform"));
            $("#qanda_message").val('');
            return false;
        });
        
        $("#qanda_message").select();
        $("#qanda_message").watermark(qanda.watermark);
        
        qanda.showCount();
        qanda.slidePoll();
        qanda.setQuestionCount();
        
      }
      
      //This is the host question list...
      if ($("#qanda-host-inbox").length > 0) {
        
        console.log("Setting Up JScroller for QandA");
        var jspane = $("#qanda-host-inbox").jScrollPane({
            verticalDragMinHeight: 10,
        		verticalDragMaxHeight: 10,
            verticalGutter: 0	
          });
          
        qanda.inbox = $("#qanda-host-inbox .jspPane");
        qanda.scroller = jspane.data('jsp');
        
      }
      
      //This is the Host Answer Form
      if ($("#answerform").length > 0) {
        $("#answerform").live("submit", function() {
            qanda.postSlide();
            return false;
        });
        
        $("#answer-submit").click(function(e){
            qanda.postSlide();
            return false;
        });
        
        //$("#answermessage").watermark(qanda.answermark);
        
      }
      
    },
    
    setQuestionCount : function () {
      //console.log("Current Questions Pre: " + qanda.currentquestions);
      qanda.currentquestions = $("#qanda-host-selected-inbox li").length + $("#qanda-host-inbox li").length;
      //console.log("Current Questions Post: " + qanda.currentquestions);
    },
    
    //This is for users, and converts their input
    //into a local stdout
    showMessage: function(message) {
        console.log("Showing Message");
        var existing = $("#q" + message.id);
        if (existing.length > 0) return;
        for (amess in message.questions) {
          console.log(unescape(message.questions[amess].html));
          var node = $(unescape(message.questions[amess].html));
          qanda.inbox.append(node);
          var newnode = '<li id="qe-'+message.questions[amess].id+'">'+unescape(message.questions[amess].body)+'</li>';
          qanda.listbox.append(newnode);
          qanda.showCount();
        }
    },
    
    //This keeps track of the current state of 
    //User input. They can only ask (x) questions
    showCount: function(){
      if ($("#qanda-inbox .question").length > qanda.totalquestions) {
        $("#qa-message").html("You have asked all of your "+qanda.totalquestions+" questions.").css("color","red");
        //console.log ("too many questions!");
        return false;
      } else {
        $("#qa-message").html("You have "+ (qanda.totalquestions - $("#qanda-inbox .question").length) +" more questions.");
        return true;
      }
    },
    
    //We use a "poll" method for host counting
    //Since we don't have any new socket connections available
    status: function() {
      if (qanda.destination == undefined) return;
      $.head(
        "/services/qanda/status?i="+qanda.destination+"&screening="+qanda.screening+"&userid="+qanda.user,
        {},
        function(headers) {
          $.each(headers,function(key,header){ 
            if(key == "X-Question-Get") {
              try {
                //console.log("Current Questions: " + qanda.currentquestions);
                //console.log("Total Questions: " + header);
                if (header != qanda.currentquestions) {
                  //console.log("Init QANDA");
                  qanda.poll();
                }
              } catch (e) {
                //console.log("No Activity Available");
              }
            }
            if(key == "X-Server-Time") {
              try {
                timekeeper.poll(header);
              } catch (e) {
                console.log("No Timer Available");
              }
            }
        });
        window.setTimeout(qanda.status, 5000);
      });
    },
    
    //This is how hosts get the user input
    poll: function() {
      
      var args = {"screening": qanda.screening,
                  "room": qanda.room,
                  "instance": qanda.instance,
                  "userid": qanda.user};
      $.ajax({url: "/services/qanda/history?i="+qanda.destination, 
              type: "GET", 
              cache: false, 
              dataType: "text",
              data: $.param(args), 
              success: qanda.onSuccess,
              error: qanda.onError
              });
    },
    
    //Put the new host questions in the interface
    onSuccess: function(response) {
        //try {
            response = eval("(" + response + ")");
            if (!response.questions) return;
            var questions = response.questions;
            console.log(questions.length, "new qanda messages");
            for (var i = 0; i < questions.length; i++) {
              //console.log(questions[i].room + "=" + qanda.room);
              if (questions[i].room == qanda.room) {
                //console.log("#q" + questions[i].id + " exist? " + $("#q" + questions[i].id).length);
                var existing = $("#q" + questions[i].id);
                if (existing.length > 0) continue;
                var node = $(questions[i].html);
                //if (questions[i].selected == 'false') {
                  //console.log(questions[i].id + " is not selected");
                  qanda.inbox.prepend(node);
                  qanda.scroller.reinitialise();
                //} 
                //Note, this queue is no longer used
                /*
                else if (questions[i].selected == 'false') {
                  console.log(questions[i].id + " is selected");
                  $("#qanda-host-selected-inbox").append(node);
                  qanda.scroller.reinitialise();
                }
                */
              }
            }
            qanda.setQuestionCount();
        //} catch (e) {
        //    qanda.onError();
        //    return;
        //}
        if (qanda.pollRunning) {
          //window.setTimeout(qanda.poll, 0);
        }
    },
    
    //This is how users get the slide input
    slidePoll: function() {
        var args = {"room": qanda.room};
        $.ajax({url: "/services/qanda/update?i="+qanda.destination, 
                type: "POST", 
                cache: false, 
                dataType: "text",
                data: $.param(args), 
                timeout: qanda.errorSleepTime,
                success: qanda.onSlideSuccess,
                error: qanda.onError});
    },
    
    //Show the new "answered" question to listeners
    onSlideSuccess: function(response) {
      try {
          response = eval("(" + response + ")");
          if (!response.questions) return;
          var questions = response.questions;
          //console.log(questions.length, "new messages");
          for (var i = 0; i < questions.length; i++) {
              //In case we're getting the "closed" message
              console.log("Question ID: " + questions[i].id);
              if ((questions[i].id == 0) && ($("#qa_showing_list li").length > 0)) {
                qanda.onSuccess(quesions[i]);
              //If we're getting the "clear" message
              } else if (questions[i].id == -1) {
                console.log("Clearing Questions");
                $("#qa_showing_list li").remove();
              //If we're reannouncing a Video Stream
              } else if (questions[i].id == -2) {
                //if (($("#is_host").length == 0) && ($("#qanda_panel").css('display')!='none')) {
                if (($("#is_host").length == 0) && (! qanda.qanda_showing)) {
                  console.log("Restarting Host Stream");
                  if (videoplayer != undefined)
                    videoplayer.camViewerReStart();
                }
              //If we're unannouncing a Video Stream
              } else if (questions[i].id == -3) {
                //if (($("#is_host").length == 0) && ($("#qanda_panel").css('display')!='none')) {
                console.log("qanda_showing is "+qanda.qanda_showing);
                console.log("IS HOST is "+$("#is_host").length );
                if (($("#is_host").length == 0) && (qanda.qanda_showing)) {
                  console.log("Stopping Host Stream");
                  if (videoplayer != undefined)
                    videoplayer.hideLiveViewer();
                }
              //Otherwise, show the message
              } else {
                if ((questions[i].room == qanda.room) && (questions[i].id != 0) && ($("#qs-"+questions[i].id).length == 0)) {
                  //alert($("#qe-"+questions[i].id).length);
                  html = '<li id="qs-'+questions[i].id+'">'+questions[i].html+'</li>';
                  $("#qa_showing_list li").remove();
                  $("#qa_showing_list").append(html);
                }
              }
          }
        } catch (e) {
            qanda.onError();
            return;
        }
        
       window.setTimeout(qanda.slidePoll, 0);
    },
    
    
    onError: function(){
      window.setTimeout(qanda.slidePoll, qanda.errorSleepTime);
    },
    
    //Deprecated, now that the Q&A interface is surtout
    closeInput: function() {
      qanda.pollRunning = false;
      qanda.closeQandA();
      $("#qanda-host-inbox, #qanda-host-selected-inbox").sortable("destroy");
      $("#qanda-host-selected-inbox .handle img").attr("src","/images/dnd-go.png");
      $("#qanda-host-inbox .handle img").attr("src","/images/dnd-stop.png");
      $("#qanda-host-selected-inbox .handle img").click(function(e){
        e.stopPropagation();
        qanda.showSlide($(this).closest("li").attr("id"));
      });
    },
    
    //Ends the Q and A input
    killQandA: function() {
      //console.log("Closing Q and A");
      var args = {"room": qanda.room,
                  "instance": qanda.instance};
      $.ajax({url: "/services/qanda/close?i="+qanda.destination, 
                type: "GET", 
                cache: false, 
                dataType: "text",
                data: $.param(args)});
    },
    
    //This pushes the current question to all listeners
    //And populates the Host Question Area with user and question
    showSlide: function(id) {
      var args = {"room": qanda.room,
                  "instance": qanda.instance,
                  "screening": qanda.screening,
                  "question": id};
      
      $("#q"+id+" table tr > td:nth-child(1) img").attr("src","/images/dnd-stop.png");
      $.ajax({url: "/services/qanda/slide?i="+qanda.destination, 
                type: "GET", 
                cache: false, 
                dataType: "json",
                data: $.param(args), 
                success: function(response) {
                  if ($("#qe-"+response.id).length == 0) {
                    html = '<li id="qe-'+response.id+'">'+response.html+'</li>';
                    $("#qa_showing_list li").remove();
                    $("#qa_showing_list").append(html);
                    //$("#q"+questions[i].id+" > .handle img").attr("src","/images/dnd-stop.png");
                  }
                }});
    },
    
    clearSlides: function() {
      $("#qa_showing_list li").remove();
      
      var args = {"room": qanda.room,
                  "instance": qanda.instance,
                  "screening": qanda.screening,
                  "message":-1};
      
      $.ajax({url: "/services/qanda/message?i="+qanda.destination, 
                type: "GET", 
                cache: false, 
                dataType: "json",
                data: $.param(args), 
                success: function(response) {}
            });
    },
    
    publishHost: function() {
      console.log("Republishing Host Stream");
      var args = {"room": qanda.room,
                  "instance": qanda.instance,
                  "screening": qanda.screening,
                  "message":-2};
      
      $.ajax({url: "/services/qanda/message?i="+qanda.destination, 
                type: "GET", 
                cache: false, 
                dataType: "json",
                data: $.param(args), 
                success: function(response) {}
            });
    },
    
    unpublishHost: function() {
      console.log("Unpublishing Host Stream");
      var args = {"room": qanda.room,
                  "instance": qanda.instance,
                  "screening": qanda.screening,
                  "message":-3};
      
      $.ajax({url: "/services/qanda/message?i="+qanda.destination, 
                type: "GET", 
                cache: false, 
                dataType: "json",
                data: $.param(args), 
                success: function(response) {}
            });
    },
    
    //Ends the Q and A input
    allowScreeningClose: function() {
      $("#host_screening_end").fadeIn("slow");
      $("#host_screening_end_also").fadeIn("slow");
    },
    
    getCurrent: function() {
      var args = {"room": qanda.room,
                  "screening": qanda.screening};
      $.ajax({url: "/services/qanda/current?i="+qanda.destination, 
              type: "POST", 
              cache: false, 
              dataType: "text",
              data: $.param(args), 
              timeout: qanda.errorSleepTime,
              success: qanda.onCurrentSuccess,
              error: qanda.onCurrentError});
    },
    
    //Show the slide, but don't UPDATE the POLL Mechanism
    onCurrentSuccess: function( response ) {
      
      console.log("Current Success");
      response = eval("(" + response + ")");
      if ($("#qe-"+response.id).length == 0) {
        html = '<li id="qe-'+response.id+'">'+response.html+'</li>';
        $("#qa_showing_list li").remove();
        $("#qa_showing_list").append(html);
        //$("#q"+questions[i].id+" > .handle img").attr("src","/images/dnd-stop.png");
      }
    },
    
    onCurrentError: function() {
      console.log("Current Error");
      window.setTimeout(qanda.getCurrent, 5000);
    }
}

$(document).ready(function() {
  if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
  
  qanda.construct();
  //qanda.init();
  
});
