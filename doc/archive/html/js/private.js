function newPrivateMessage(form) {
    //console.log("NEW MESSAGE!");
    var message = form.formToDict();
    var disabled = form.find("input[type=submit]");
    disabled.disable();
    $.postPrivateJSON("/services/private/post", message, function(response) {
        privatechat.showMessage(response);
        if (message.id) {
            form.parent().remove();
        } else {
            form.find("input[type=text]").val("").select();
            disabled.enable();
        }
    });
}

jQuery.postPrivateJSON = function(url, args, callback) {
    //console.log("PAIR:'"+$("#pairid").html()+"'");
    if ($("#pairid").html() == '')  return;
    args.room = $("#room").html();
    args.pair = $("#pairid").html();
    args.author = $("#userid").html();
    args.to = $("#toid").html();
    args.instance = $("#instance").html();
    $.ajax({url: url+'?i='+$("#host").html()+":"+(parseInt($("#port").html()) + 10090), 
            data: $.param(args), 
            type: "POST", 
            cache: false, 
            dataType: "text", 
            success: function(response) {
                if (callback) callback(eval("(" + response + ")"));
            }, error: function(response) {
                //console.log("ERROR:", response)
            }
    });
};

var privatechat = {
    errorSleepTime: 15000,
    cursor: null,
    host: null,
    port: null,
    instance: null,
    room: null,
    destination: null,
    inbox: null,
    pair: null,
    user: null,
    
    construct: function() {
      
      privatechat.host = $("#host").html();
      privatechat.port = parseInt($("#port").html()) + 10090;
      privatechat.instance = $("#instance").html();
      privatechat.room = $("#room").html();
      privatechat.user = $("#userid").html();
      privatechat.destination = privatechat.host + ":" + privatechat.port;
      privatechat.inbox = $("#private-inbox");
      privatechat.pair = $("#pairid").html();
      privatechat.room = $("#room").html();
      
    },
    
    init: function() {
        if (privatechat.pair == '')  return;
        var args = {"room": privatechat.room,
                    "pair": privatechat.pair,
                    "instance": privatechat.instance};
        if (privatechat.cursor) args.cursor = privatechat.cursor;
        $.ajax({url: "/services/private/init?i="+privatechat.destination, 
                type: "GET", 
                cache: false, 
                dataType: "text",
                data: $.param(args), 
                success: privatechat.onInitSuccess,
                error: privatechat.onInitError});
    },
    
    onInitSuccess: function(response) {
        try {
           privatechat.inbox.html(response);
            var objDiv = document.getElementById("private-inbox");
            objDiv.scrollTop = objDiv.scrollHeight;
        } catch (e) {
            privatechat.onInitError();
            return;
        }
    },
    
    onInitError: function(response) {
        window.setTimeout(privatechat.init, 5000);
    },
    
    listen: function() {
        var args = {"room": privatechat.room,
                    "instance": privatechat.instance,
                    "to": privatechat.user};
        if (privatechat.cursor) args.cursor = privatechat.cursor;
        $.ajax({url: "/services/private/listen?i="+privatechat.destination, 
                type: "POST", 
                cache: false, 
                dataType: "text",
                data: $.param(args),
                timeout: privatechat.errorSleepTime,
                success: privatechat.onListen,
                error: privatechat.onListenError});
    },
    
    onListen: function(response) {
        try {
            privatechat.newInvite(eval("(" + response + ")"));
        } catch (e) {
            privatechat.onListenError();
            return;
        }
    },
    
    onListenError: function(response) {
        window.setTimeout(privatechat.listen, 0);
    },
    
    setPair: function( element ) {
    
      var thid = element.getAttribute("id").split("-");
      var mid = $("#userid").html();
      if (mid == thid[1]) return;
      var pair = (thid[1] > mid) ? mid+"-"+thid[1] : thid[1]+"-"+mid;
      //console.log("Starting Private Chat with "+element.getAttribute("title"));
      //console.log("Reserving room number "+pair);
      //Tell the Page which "Room"
      $("#pairid").html(pair);
      
      //Tell the Page which "Recipient" (This is not the author!)
      $("#toid").html(thid[1]);
      $('.rs:not(#private_chat_panel)').fadeOut("slow");
      if ($('#private_chat_panel').css('display')=='none') {
        $('#private_chat_panel').fadeIn("slow");
      } else {
        $('#private-inbox').html('');
      }
      $("#private_invite").fadeOut("slow");
      privatechat.pair = $("#pairid").html();
      $("#invite-"+privatechat.pair).remove();
      
    },
    
    startPrivateChat : function ( element ) {
      privatechat.setPair(element);
      privatechat.init();
      privatechat.poll();
    },

    poll: function() {
        if (privatechat.pair == '') return;
        var args = {"room": privatechat.room,
                    "pair": privatechat.pair,
                    "instance": privatechat.instance};
        if (privatechat.cursor) args.cursor = privatechat.cursor;
        $.ajax({url: "/services/private/update?i="+privatechat.destination, 
                type: "POST", 
                cache: false, 
                dataType: "text",
                data: $.param(args), 
                success: privatechat.onSuccess,
                error: privatechat.onError});
    },

    onSuccess: function(response) {
        try {
            privatechat.newMessages(eval("(" + response + ")"));
        } catch (e) {
            privatechat.onError();
            return;
        }
        privatechat.errorSleepTime = 100;
        window.setTimeout(privatechat.poll, 0);
    },

    onError: function(response) {
        //privatechat.errorSleepTime *= 2;
        //console.log("Private Message Poll error; sleeping for", privatechat.errorSleepTime, "ms");
        window.setTimeout(privatechat.poll, privatechat.errorSleepTime);
    },
    
    newMessages: function(response) {
        if (!response.messages) return;
        privatechat.cursor = response.cursor;
        var messages = response.messages;
        privatechat.cursor = messages[messages.length - 1].id;
        //console.log(messages.length, "new messages, cursor:", privatechat.cursor);
        for (var i = 0; i < messages.length; i++) {
          if ((messages[i].room == privatechat.room) && (messages[i].pair == privatechat.pair))
            privatechat.showMessage(messages[i]);
        }
    },

    showMessage: function(message) {
        var existing = $("#m" + message.id);
        if (existing.length > 0) return;
        var node = $(message.html);
        //node.hide();
        privatechat.inbox.append(node);
        //node.slideDown();
        var objDiv = document.getElementById("private-inbox");
        objDiv.scrollTop = objDiv.scrollHeight;
    },
    
    newInvite: function( response ) {
      var invites = response.messages;
      for (var i = 0; i < invites.length; i++) {
        //console.log("Invite to " + invites[i].to + " " + privatechat.user);
        if (invites[i].to == privatechat.user){
          //console.log($("#private_chat_panel:visible").length);
          //console.log(invites[i].pair + " != " + privatechat.pair);
          if ((invites[i].pair != privatechat.pair) || ($("#private_chat_panel:visible").length == 0)) {
            //console.log("Invite Appending");
            html = '<p id="invite-'+invites[i].pair+'"><a onclick="privatechat.startPrivateChat(this)" id="invite-'+invites[i].author+'" name="invite-'+invites[i].author+'" cursor="'+invites[i].id+'">'+invites[i].from+' would like to chat with you.</a></p>';
            $("#private_invite_messages").append(html);
          }
          error.showError("default",html,null,"left");
          //$("#private_invite").fadeIn("slow");
        }
      }
    }
    
};

$(document).ready(function() {
    if (!window.console) window.console = {};
    if (!window.console.log) window.console.log = function() {};
    
    if ($("#privateform").length > 0) {
      $("#privateform").live("submit", function() {
          newPrivateMessage($(this));
          $("#private").val('');
          return false;
      });
      
      $("#private-chat-submit").click(function(e){
          newPrivateMessage($("#privateform"));
          $("#private").val('');
          return false;
      });
      
      $("#privateform").live("keypress", function(e) {
          if (e.keyCode == 13) {
            newPrivateMessage($(this));
            $("#private").val('');
            return false;
          }
      });
      
      $("#private").select();
      $("#private").watermark("Enter Your Message Here:");
      
    }
    
    privatechat.construct();
    privatechat.listen();
});
