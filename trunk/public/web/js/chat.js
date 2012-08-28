function newMessage(form) {
    var message = form.formToDict();
    if ((/Enter Your Message Here/.test($("#messageform [name=body]").val())) || ($("#messageform [name=body]").val() == '')) {
      return false;
    }
    if (chat.allow_input == false) return;
    chat.blockInput();
		$.postJSON("/services/chat/post", message, function(response) {
        //chat.init();
        // chat.showMessage(response);//
        if (message.id) {
            form.parent().remove();
        }
        $("#messageform [name=body]").val("");
        chat.allowInput();
        chat.onReplyCancel();
        chat.scrollToBottom(true);

    });
}

function getCookie(name) {
    var r = document.cookie.match("\\b" + name + "=([^;]*)\\b");
    return r ? r[1] : undefined;
}

jQuery.fn.setCursorPosition = function(pos) {
  this.each(function(index, elem) {
    if (elem.setSelectionRange) {
      elem.setSelectionRange(pos, pos);
    } else if (elem.createTextRange) {
      var range = elem.createTextRange();
      range.collapse(true);
      range.moveEnd('character', pos);
      range.moveStart('character', pos);
      range.select();
    }
  });
  return this;
};

jQuery.postJSON = function(url, args, callback) {
    args.room = $("#room").html();
    args.instance = $("#instance").html();
    args.author = $("#userid").html();
    args.cookie = $.cookie("constellation_frontend");
    args.film = $("#film").html();
    args.user_image = $("#user_image").html();
    args.ishost = $("#is_host").length;
		args.mdt = $("#moderator").length;
		args.cmo = $("#cmo").html();
    if(!!$('#chat-reply-id').value){
      args.c = $('#chat-reply-id').value;
    }
    $.ajax({url: url+'?i='+$("#host").html()+":"+(parseInt($("#port").html()) + 9090), 
            data: $.param(args), 
            type: "POST", 
            cache: false, 
            dataType: "text", 
            success: function(response) {
            	if(response) {
              	if (callback) callback(eval("(" + response + ")"));
              }
            }, error: function(response) {
                //console.log("ERROR:", response);
            }
    });
};

jQuery.fn.formToDict = function() {
    var fields = this.serializeArray();
    var json = {}
    for (var i = 0; i < fields.length; i++) {
        json[fields[i].name] = fields[i].value;
    }
    if (json.next) delete json.next;
    return json;
};

jQuery.fn.disable = function() {
    this.enable(false);
    return this;
};

jQuery.fn.enable = function(opt_enable) {
    if (arguments.length && !opt_enable) {
        this.attr("disabled", "disabled");
    } else {
        this.removeAttr("disabled");
    }
    return this;
};

var chat = {
  
    location: null,
    errorSleepTime: 150000,
    cursor: null,
    host: null,
    port: null,
    instance: null,
    room: null,
    destination: null,
    inbox: null,
    scroller: null,
    watermark: "Enter Your Message Here:",
    started: false,
    mode: "tall",
    allow_input: true,
    blockScroll: false,
    scrollTimer: null,
    sequence: 0,
    initPoll: false,
    
    construct: function() {

      chat.proxy = "http://"+$("#proxy").html();
      chat.cookie = $.cookie("constellation_frontend");
      //chat.proxy = "";
      chat.host = $("#host").html();
      chat.port = parseInt($("#port").html()) + 9090;
      chat.instance = $("#instance").html();
      chat.room = $("#room").html();
      chat.destination = chat.host + ":" + chat.port;
      
      if (window.location.pathname.match(/theater/)) {
        //console.log("In Theater");
        chat.location = "theater";
      }
      if (window.location.pathname.match(/screening/)) {
        //console.log("In Screening");
        chat.location = "screening";
      }
      if (window.location.pathname.match(/lobby/)) {
        //console.log("In Screening");
        chat.location = "lobby";
      }
      
      if ($("#messageform").length > 0) {
        $("#messageform").submit(function() {
            if (($("#message").val() == chat.watermark) || ($("#message").val() == '')) {
              return false;
            }
            newMessage($("#messageform"));
            $("#message").val('');
            return false;
        });
        
        $("#chat-submit").click(function(e){
            if (($("#message").val() == chat.watermark) || ($("#message").val() == '')) {
              return false;
            }
            e.stopPropagation();
            newMessage($("#messageform"));
            $("#message").val('');
            return false;
        });
        $("#reply-submit").click(function(e){
            if (($("#message").val() == chat.watermark) || ($("#message").val() == '')) {
              return false;
            }
            e.stopPropagation();
            newMessage($("#messageform"));
            $("#message").val('');
            return false;
        });


        $("#chat_panel").click(function(e){
          // Removed By Matthew L.
          // $("#message").select();
        });
        
        $("#message").select();
        // $("#message").watermark(chat.watermark);
      }
      
      chat.inbox = $("#chat_panel #inbox");
      $(window).resize(function () { chat.resize() });
      
      if ($("#chat_refresh").length > 0) {
        $("#chat_refresh").click(function(e){
            chat.inbox.html("");
            chat.init();
            return false;
        });
      }
      
      $("#inbox").mouseup(function(e){
				chat.checkScroll();
			});
      $('.chat_reply',chat.inbox).live('click', chat.onReply);
      $('.chat_vote',chat.inbox).live('click', chat.onVote);
      $('#chat-reply-cancel').bind('click', chat.onReplyCancel);
    },
    
    init: function() {
      
      if ($("#noauth").length != 0) {
        //return;
      }
      
      var args = {"instance": chat.instance,
                  "room": chat.room,
                  "cookie": chat.cookie,
									"mdt": $("#moderator").length,
									"cmo": $("#cmo").html(),
									"s":chat.sequence};
      //**if (chat.cursor) args.cursor = chat.cursor;
      $.ajax({url: "/services/chat/init?i="+chat.destination, 
              type: "GET", 
              cache: false, 
              dataType: "json",
              data: $.param(args), 
              success: chat.onInitSuccess,
              error: chat.onInitError
      });
        
    },
    
    onInitSuccess: function(response) {
    		//try {
        	if ((response != null) && (response.html != "undefined")) {
          	chat.inbox.append(response.html);
            chat.scrollToBottom();

          }
          if ((response != null) && (response.colorme != "undefined")  && (colorme != "undefined")) {
          	colorme.initColors(response.colorme);
          }
          chat.resize();
          chat.started = true;
          if ((response != null) && (response.sequence != "undefined")) {
          	//console.log("Current Sequence " + response.sequence);
          	chat.sequence = response.sequence;
          }
        //} catch (e) {
        	//chat.onInitError();
          //return;
        //}
    },
    
    onInitError: function(response) {
        //Try to init in five more seconds
        window.setTimeout(chat.init, 5000);
    },
        
    poll: function() {
      
      if (! chat.started) {
        window.setTimeout(chat.poll, 3000);
				return;
      }
      
      if ($("#noauth").length != 0) {
        //return;
      }
    
      var args = {"instance": chat.instance,
                  "room": chat.room,
                  "cookie": chat.cookie,
									"mdt": $("#moderator").length,
									"cmo": $("#cmo").html()};
      //console.log("Waiting for chat from "+chat.instance);
      //**if (chat.cursor) args.cursor = chat.cursor;
      $.ajax({url: "/services/chat/update?i="+chat.destination, 
              type: "POST", 
              cache: false, 
              dataType: "text",
              data: $.param(args),
              timeout: chat.errorSleepTime,
              success: chat.onSuccess,
              error: chat.onError
      });
    },

    onSuccess: function(response) {
      	//console.log("Got it!");
        try {
            chat.newMessages(eval("(" + response + ")"));
        } catch (e) {
            chat.onError();
            return;
        }
        //chat.errorSleepTime = 100;
        window.setTimeout(chat.poll, 100);

        chat.scrollToBottom(chat.initPoll);
        chat.initPoll = false;

    },

    onError: function(response) {
      //console.log("Error in Chat");
      window.setTimeout(chat.poll, 100);
    },
    
    checkScroll: function() {
			//console.log("Pause Scrolling");
			//console.log($("#inbox").attr("scrollTop"));
			//console.log($("#inbox").height());
			//if ($("#inbox").attr("scrollTop") < $("#inbox").height()) {
				//console.log("Not Scrolling");
				chat.blockScroll = true;
				chat.scrollTimer = window.setTimeout(chat.allowScroll, 20000);
			//} else {
			//	chat.allowScroll();
			//}
		},
		
		allowScroll: function() {
			//console.log("Allow Scrolling");
			chat.blockScroll = false;
			clearTimeout(chat.scrollTimer);
		},
		
    newMessages: function(response) {
        if (!response.messages) return;
        //**chat.cursor = response.cursor;
        var messages = response.messages;
        //Check to make sure we are in sequence
        //ie: Our current "SEQUENCE" is the one JUST before incoming
        //Otherwise, Re-Init at our current "SEQUENCE"
				var first_sequence = messages[0].sequence - 1;
				//console.log("First Sequence is" + first_sequence);
				//console.log("Current Sequence is" + chat.sequence);
        if (first_sequence != chat.sequence) {
					chat.init();
				} else {
        //**chat.cursor = messages[messages.length - 1].id;
        //console.log(messages.length, "new messages, cursor:", chat.cursor);
        for (var i = 0; i < messages.length; i++) {
            //console.log("Message Recieved for room: " + messages[i].room);
            //console.log("This room: " + chat.room);
            if (messages[i].room == chat.room ) {
              //console.log("Adding Message");
              chat.showMessage(messages[i]);
            }
        }}
    },

    showMessage: function(message) {
    	
			//console.log(message.html);    	
			if (((message.colorme != undefined) && (message.colorme == "1")) || ((message.html != undefined) && (message.html.substring(0,7) == "colorme"))) {
  			if (colorme != undefined) {
					colorme.incomingMessage(message);
				}
				return;
			}
			//console.log(message.html);    	
			if (((message.body != undefined) && (message.body.substring(0,5) == "qanda"))) {
  			console.log("QANDA");
				return;
			}
			if ((message.html != undefined) && (message.html.substring(0,7) == "hostset")) {
  			//console.log("hostset!");
        if ((videoplayer != undefined) && ( /setLogLevel/.test(message.html))) {
          videoplayer.setLogLevel(message);
          return;
        }
        
				if (HUD != undefined) {
					HUD.incomingMessage(message);
				}
				return;
			}
			
      var existing = $("#m" + message.id);
      //console.log("Existing:" + existing.length);
      if (existing.length > 0) {
				existing.remove();
			}
      var node = $(message.html);
      //node.hide();
      if ((chat.location == "screening") || (chat.location == "theater")) {
      	//console.log("appending " + node);
        $("#chat_panel #inbox").append(node);
				//chat.inbox.append(node);
				chat.sequence = message.sequence;
        chat.resize();
      } else {
        $("#constellation_map").append(html);
        chat.remove();
      }
    },
    
    remove: function() {
      var num = 19;
      if ($("#constellation_map div").length > num) {
        for(i=num;i<=$("#constellation_map div").length;i++) {
          $("#constellation_map div:nth-child("+i+")").remove();
        }
      }
    },
    
    zebra: function() {
    	//console.log("Applying Zebra");
			$('#inbox div:odd').addClass('chat_message_1');
		},
		
    resize: function() {
			if ($("#chat_panel").css("display") == "block") {
	    	chat.zebra();
	    	if (chat.mode == "tall") {
					var wh = ($(window).height() - 246);
	    	} else {
					var wh = ($(window).height() - 500);
	    	}
    		//console.log("RESIZING TO "+ wh );
				$("#inbox").height(wh);

        //console.log("Block Scrolling is " + chat.blockScroll);
				if (! chat.blockScroll) {
	    		$("#inbox").animate({ scrollTop: $("#inbox").attr("scrollHeight") }, 100);
	    	}
    	} else if ($("#qanda_panel").css("display") == "block") {
    		//console.log("RESIZE QA PANEL");
    		var wh = ($(window).height() - 350);
    		$("#qanda_panel").css("top",wh+"px");
    		
			}
			return;
			
    },
    scrollToBottom: function(bool){

      var scrollHeight = chat.inbox.prop("scrollHeight");
      var scrollTop = chat.inbox.prop("scrollTop");
      var height = chat.inbox.height();

      if(!!bool || scrollHeight - height - scrollTop < 200){
        chat.inbox.prop({ scrollTop: scrollHeight });      
      }
    },
    //This shows all available chat rooms
    //For a specific Screening
    getRooms: function() {
      //console.log("Getting Room List");
      var args = {"room": chat.room,
                  "instance": chat.instance};
      
      $.ajax({url: "/services/chat/rooms?i="+chat.destination, 
                type: "GET", 
                cache: false, 
                dataType: "json",
                data: $.param(args), 
                success: function(response) {
                  //console.log("SUCCESS with JSON");
                  var thtml = '';
                  for (i=0;i<response.rooms.length;i++) {
                    if (response.rooms[i].key != undefined) {
                      if ((response.rooms[i].name == undefined) || (response.rooms[i].name == '')) {
                        response.rooms[i].name = response.rooms[i].key;
                      }
                      thtml += '<li><a href="javascript:void(0);" onclick="chat.switchRoom(this)" title="'+response.rooms[i].key+'">Chat Room '+response.rooms[i].name+'</a></li>';
                    }
                    //console.log(response.rooms[i].key);
                  }
                  if (thtml != '') {
                    $("#chatroom-instances").html(thtml);
                  }
                  /*
                  if ($("#qe-"+response.id).length == 0) {
                    html = '<li id="qe-'+response.id+'">'+response.html+'</li>';
                    $("#qa_showing_list").append(html);
                  }
                  */
                }});
    },
    
    //This shows all available chat rooms
    //For a specific Screening
    switchRoom: function( element ) {
      if ($("#instance").html() != element.getAttribute("title")) {
        $("#instance").html(element.getAttribute("title"));
        chat.inbox.html('');
        chat.resize();
        chat.construct();
        chat.init();
        $('.chat-click').click();
      }
    },
    
    approve: function( type, cursor ) {
			//console.log(type);
			//console.log(cursor);
			
			if ($("#noauth").length != 0) {
        return;
      }
      
      if (type == ('approve')) {
				allow = 1;
			} else {
				allow = 0;
			}
			
			//console.log("removing " + cursor);
			$("#chat_panel #inbox div[cursor='"+cursor+"']").remove();
			
      var args = {"instance": chat.instance,
                  "room": chat.room,
                  "cookie": chat.cookie,
									"mdt": $("#moderator").length,
									"cmo": $("#cmo").html(),
									"cursor": cursor,
									"allow": allow};
      //**if (chat.cursor) args.cursor = chat.cursor;
      $.ajax({url: "/services/chat/approve?i="+chat.destination, 
              type: "GET", 
              cache: false, 
              dataType: "json",
              data: $.param(args), 
              success: chat.onApproveSuccess,
              error: chat.onApproveError});
        
    },
    
    onApproveSuccess: function(response) {
        try {
					//console.log("Approve Success");
          //chat.inbox.append(response);
					var node = $(response.html);
          $("#chat_panel #inbox").append(node);
					//chat.inbox.append(node);
					chat.sequence = response.sequence;
					chat.resize();
        } catch (e) {
          chat.onError();
          return;
        }
    },
    
    onApproveError: function(response) {
        //Try to init in five more seconds
        window.setTimeout(chat.init, 5000);
    },
    
    blockInput: function() {
	    //var disabled = form.find("input[type=submit]");
	    if (chat.allow_input == true) {
	    	chat.allow_input = false;
		    $("#chat-submit").attr("disabled", "disabled");
		    $("#message").attr("disabled", "disabled");
		    $("#sending").fadeIn(100);
        window.setTimeout(chat.allowInput, 3000);
	    }
		},
		
		allowInput: function() {
			if (chat.allow_input == false) {
				chat.allow_input = true;
	      $("#chat-submit").removeAttr("disabled");
	  		$("#message").removeAttr("disabled"); 
	  		$("#sending").fadeOut(100);
	  	}
		}

    ,
    onVote: function(event){
        var target = $(event.target); 
        if(!target.hasClass('chat_vote_active')){
          var args = {
            c: target.parent().attr('cursor'),
            room: $("#room").html(),
            instance: $("#instance").html(),
            mdt: $("#moderator").length,
            cmo: $("#cmo").html()
          }

          target.addClass('chat_vote_active');
          $.ajax({
            url:'/services/conversation/promote',
            type: "GET", 
            cache: false,
            data: $.param(args)
          });
        }
    },
    onReply: function(event){
        var target = $(event.target);
        var id = target.parent().attr('cursor');

        $('#chat-reply-id').val(id);
        $("#message").val( '@' + target.parent().find('h5').text()+' ').focus();
        $("#message").setCursorPosition($("#message").val().length);
        $('#chat-submit-container').hide();
        $('#chat-reply-container').show();
    },
    onReplyCancel: function(){

        $('#chat-reply-id').val('');
        $("#message").val('')
        $('#chat-submit-container').show();
        $('#chat-reply-container').hide();
    }
        
};

$(document).ready(function() {
    if (!window.console) window.console = {};
    if (!window.console.log) window.console.log = function() {};
    
    chat.construct();
    chat.init();
    chat.poll();
});
