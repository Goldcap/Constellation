function newAdminMessage(form) {
  var message = form.formToDict();
  message.type = 'chat';
  var disabled = form.find("input[type=submit]");
  disabled.disable();
  $.postAdminJSON("/services/adminmessage/post", message, function(response) {
      adminmessage.showMessage(response);
      if (message.id) {
          form.parent().remove();
      } else {
          form.find("input[type=text]").val("").select();
          disabled.enable();
      }
  });
}

jQuery.postAdminJSON = function(url, args, callback) {
  if ($("#adminmessage-pairid").html() == '') {
    console.log("No Admin Pair ID");
    return;
  }
  args.room = $("#room").html();
  args.location = $("#location").html();
  args.instance = $("#instance").html();
  args.author = $("#userid").html();
  args.pair = $("#adminmessage-pairid").html();
  args.to = $("#adminmessage-toid").html();
  $.ajax({url: url + "?i=" + $("#host").html() + ":" + (parseInt($("#port").html()) + 11090), 
          data: $.param(args), 
          type: "POST", 
          cache: false, 
          dataType: "text", 
          success: function(response) {
              if ((callback) && (response != '')) callback(eval("(" + response + ")"));
          }, error: function(response) {
              console.log("ERROR:", response)
          }
  });
};

var adminmessage = {

    errorSleepTime: 5000,
    cursor: null,
    seatrequest: "I'd like to have your seat at this screening.",
    seatresponse: "You can have my seat at this screening.",
    showingtype: null,
    host: null,
    port: null,
    instance: null,
    room: null,
    user: null,
    destination: null,
    inbox: null,
    pair: null,
    to: null,
    seat: null,
    deadletter: 0,
    
    construct: function() {
      
      adminmessage.host = $("#host").html();
      adminmessage.port = parseInt($("#port").html()) + 11090;
      adminmessage.instance = $("#instance").html();
      adminmessage.room = $("#room").html();
      adminmessage.user = $("#userid").html();
      adminmessage.destination = adminmessage.host + ":" + adminmessage.port;
      adminmessage.inbox = $("#adminmessage-inbox");
      adminmessage.pair = $("#adminmessage-pairid").html();
      adminmessage.to = $("#adminmessage-toid").html();
      adminmessage.seat = $("#seat").html();
      
    },
    
    init: function() {
        if (adminmessage.room == '') return;
        if (adminmessage.pair == '')  return;
        var args = {"room": adminmessage.room,
                    "location": adminmessage.location,
                    "instance": adminmessage.instance,
                    "pair": adminmessage.pair};
        if (adminmessage.cursor) args.cursor = adminmessage.cursor;
        $.ajax({url: "/services/adminmessage/init?i="+adminmessage.destination, 
                type: "GET", 
                cache: false, 
                dataType: "text",
                data: $.param(args), 
                success: adminmessage.onInitSuccess,
                error: adminmessage.onInitError});
    },
    
    onInitSuccess: function(response) {
        try {
            adminmessage.inbox.html(response);
            var objDiv = document.getElementById("adminmessage-inbox");
            objDiv.scrollTop = objDiv.scrollHeight;
            $("#adminmessage_panel").fadeIn("slow");
        } catch (e) {
            adminmessage.onInitError();
            return;
        }
    },
    
    onInitError: function(response) {
        //adminmessage.errorSleepTime *= 2;
        adminmessage.init();
    },
    
    getInvites: function() {
      if (adminmessage.room == '') return;
      $.head("/services/adminmessage/invites?i="+adminmessage.destination+"&room="+adminmessage.room+"&instance="+adminmessage.instance+"&userid="+adminmessage.user+"&location="+adminmessage.location,
        {},
        function(headers) {
          $.each(headers,function(key,header){ 
            if(key == "X-AdminInvite") {
              try {
                adminmessage.showInvite(header);
              } catch (e) {
                console.log("No Admin Invites Available");
              }
            }
          });
      },
      adminmessage.errorSleepTime - 2000 );
      window.setTimeout(adminmessage.getInvites, adminmessage.errorSleepTime);
    },
    
    /*
    listen: function() {
      if (adminmessage.room == '') return;
      if (adminmessage.cursor) args.cursor = adminmessage.cursor;
      $.head(
        "/services/adminmessage/listen?i="+adminmessage.destination+"&room="+adminmessage.room+"&location="+adminmessage.location+"&instance="+adminmessage.instance+"&to="+adminmessage.user,
        {},
        function(headers) {
          $.each(headers,function(key,header){ 
            if(key == "X-AdminMessage-Get") {
              //try {
                adminmessage.onListen(header);
              //} catch (e) {
              //  console.log("No Admin Messages Available");
              //}
            }
        });
        window.setTimeout(adminmessage.listen, 5000);
      });
    },
    */
    
    /****************************
    listen: function() {
        if (adminmessage.room == '') return;
        var args = {"room": adminmessage.room,
                    "location": adminmessage.location,
                    "instance": adminmessage.instance,
                    "to": adminmessage.user};
        if (adminmessage.cursor) args.cursor = adminmessage.cursor;
        $.ajax({url: "/services/adminmessage/listen?i="+adminmessage.destination, 
                type: "GET", 
                cache: false, 
                dataType: "text",
                data: $.param(args), 
                success: adminmessage.onListen,
                error: adminmessage.onListenError});
    },
    
    onListen: function(response) {
        //try {
           adminmessage.newInvite(eval("(" + response + ")"));
        //} catch (e) {
        //    adminmessage.onListenError();
        //    return;
        //}
        //adminmessage.errorSleepTime = 100;
        //window.setTimeout(adminmessage.listen, adminmessage.errorSleepTime);
    },
    
    onListenError: function(response) {
        console.log("Listen Error!");
        //adminmessage.errorSleepTime = 100;
        //window.setTimeout(adminmessage.listen, 0);
    },
    ***************************************/
    
    setPair: function( element ) {
      console.log("Setting Pair!");
      var thid = element.getAttribute("id").split("-");
      var mid = $("#userid").html();
      
      if (mid == thid[1]) return;
      var pair = (thid[1] > mid) ? mid+"-"+thid[1] : thid[1]+"-"+mid;
      console.log("using id " + thid[1] + " with mid " + mid + " = " + pair);
      
      //Tell the Page which "Room"
      $("#adminmessage-pairid").html(pair);
      adminmessage.pair = pair;
      //Tell the Page which "Recipient" (This is not the author!)
      $("#adminmessage-toid").html(thid[1]);
      return thid[1];
      
    },
    
    getPair: function() {
      console.log("Getting Pair!");
      var mid = $("#userid").html();
      id = $("#adminmessage-pairid").html().replace(mid,"");
      id = id.replace("-","");
      return id;
      
    },
    
    /*
    showPanel: function() {
      $('.rs:not(#adminmessage_panel)').fadeOut("slow");
      if ($('#adminmessage_panel').css('display')=='none') {
        $('#adminmessage_panel').fadeIn("slow");
      } else {
        //$('#adminmessage_panel').fadeIn("slow");
        $('#adminmessage-inbox').html('');
      }
      $("#adminmessage_invite").fadeOut("slow");
      adminmessage.pair = $("#adminmessage-pairid").html();
      $("#adminmessage_invite-"+adminmessage.pair).remove();
      
    },
    
    electAdminChat: function( element ) {
      
      adminmessage.setPair(element);
      adminmessage.init();
      adminmessage.poll();
      
      $("#adminrecipient").html( element.getAttribute("title") );
      
    },
    
    startAdminChat: function( element ) {
      
      adminmessage.setPair(element);
      adminmessage.init();
      adminmessage.poll();
      
      //Finally, delete the invite
      //Do we need this?
      var id = element.parentNode.getAttribute("id");
      var cursor = element.getAttribute("cursor");
      
    },
    
    poll: function() {
        if (adminmessage.room == '') return;
        if (adminmessage.pair == '') return;
        var args = {"room": adminmessage.room,
                    "location": adminmessage.location,
                    "instance": adminmessage.instance,
                    "pair": adminmessage.pair};
        if (adminmessage.cursor) args.cursor = adminmessage.cursor;
        $.ajax({url: "/services/adminmessage/update?i="+adminmessage.destination, 
                type: "POST", 
                cache: false, 
                dataType: "text",
                data: $.param(args), 
                success: adminmessage.onSuccess,
                error: adminmessage.onError
                });
    },

    onSuccess: function(response) {
        try {
            adminmessage.newMessages(eval("(" + response + ")"));
        } catch (e) {
            adminmessage.onError();
            return;
        }
        adminmessage.errorSleepTime = 100;
        window.setTimeout(adminmessage.poll, 0);
    },

    onError: function(response) {
        //adminmessage.errorSleepTime *= 2;
        console.log("Admin Message Poll error; sleeping for", adminmessage.errorSleepTime, "ms");
        window.setTimeout(adminmessage.poll, adminmessage.errorSleepTime);
    },
    
    newMessages: function(response) {
        if (!response.messages) return;
        adminmessage.cursor = response.cursor;
        var messages = response.messages;
        adminmessage.cursor = messages[messages.length - 1].id;
        //console.log(messages.length, "new messages, cursor:", adminmessage.cursor);
        for (var i = 0; i < messages.length; i++) {
          if ((messages[i].room == adminmessage.room) && (messages[i].pair == adminmessage.pair))
            adminmessage.showMessage(messages[i]);
        }
        //console.log("Length Is" + $("#adminmessage_panel").css("display"));
        if (adminmessage.showingtype == "chat") {
          if ($("#adminmessage_panel").css("display")== "none") {
              //console.log("Panel Hidden");
              $("#adminmessage_panel").fadeIn("slow");
          }
        }
    },

    showMessage: function(message) {
      if (message.type == "ss"){
        console.log("Clearing Invite!");
        if (adminmessage.location == "lobby") {
            adminmessage.clearPairInvite( adminmessage.takeSeat );
        }
        return;
      } else {
        var existing = $("#m" + message.id);
        if (existing.length > 0) return;
        var node = $(message.html);
        //node.hide();
        adminmessage.inbox.append(node);
        //node.slideDown();
        var objDiv = document.getElementById("adminmessage-inbox");
        objDiv.scrollTop = objDiv.scrollHeight;
        adminmessage.showingtype = "chat";
      }
    },
    */
    
    /*
    newInvite: function( response ) {
      console.log("New Invite");
      var invites = response.messages;
      for (var i = 0; i < invites.length; i++) {
        console.log("Invite to " + invites[i].to + " " + adminmessage.user);
        if (invites[i].to == adminmessage.user){
          console.log(invites[i].pair + " = " + adminmessage.pair);
          if ((adminmessage.pair == undefined) || (invites[i].pair != adminmessage.pair) || ($("#adminmessage_panel:visible").length == 0)) {
            console.log("New Invite: " + invites[i].type);
            //SC = Seat Capture
            if (invites[i].type == "sc") {
              console.log($.cookie("sc"));
              if ((adminmessage.location == "theater") && ($.cookie("sc") != "sent")) {
                console.log("This user didn't send the capture");
                adminmessage.clearPairInvite( adminmessage.leaveRoom );
              } else {
                console.log("This user sent the capture");
                //adminmessage.clearPairInvite( null );
              }
            //SK = Seat Kill
            } else if (invites[i].type == "sk") {
              adminmessage.clearPairInvite( adminmessage.blockRoom );
            //SR = Seat Request
            } else if (invites[i].type == "sr") {
              console.log($.cookie("sc"));
              console.log(adminmessage.location);
              if ((adminmessage.location == "theater") && ($.cookie("sc") != "sent")) {
                console.log("Showin' Error!");
                html = '<p id="invite-'+invites[i].pair+'"><a onclick="adminmessage.surrenderSeat(this)" id="admininvite-'+invites[i].author+'" name="admininvite-'+invites[i].author+'" cursor="'+invites[i].cursor+'">'+invites[i].from+' has asked to take your seat. Click this message to allow this.</a></p>';
                error.showError("default","SEAT REQUEST",html,"left");
              } else {
                console.log("Not Showin' Error!");
              }
            //SS = Seat Surrender
            } else if (invites[i].type == "ss") {
              if (adminmessage.location == "lobby") {
                adminmessage.clearPairInvite( adminmessage.takeSeat );
              }
            } else {
              html = '<p id="invite-'+invites[i].pair+'"><a onclick="adminmessage.startAdminChat(this)" id="invite-'+invites[i].author+'" name="invite-'+invites[i].author+'" cursor="'+invites[i].id+'">'+invites[i].from+' has a message for you.</a></p>';
              error.showError("default",html,null,"left");
            }
          }
          if (invites[i].type != "ss") {
            //$("#adminmessage_invite").fadeIn("slow");
          }
        }
      }
    },
    */
    
    showInvite: function( header ) {
      console.log("Show Invite");
      var invite = eval("(" + header + ")");
      if (invite.to != adminmessage.user) return;
      if ($("#admininvite-"+invite.author).length == 0) {
        console.log("Show Invite: " + invite.type);
        //SC = Seat Capture
        if (invite.type == "sc") {
          console.log($.cookie("sc"));
          if ((adminmessage.location == "theater") && ($.cookie("sc") != "sent")) {
            console.log("This user didn't send the capture");
            adminmessage.clearPairInvite( adminmessage.leaveRoom );
          } else {
            console.log("This user sent the capture");
            adminmessage.deadletter++;
            if (adminmessage.location == "lobby") {
              html = '<p><a onclick="adminmessage.doRedirect(this)" id="surrender-'+invite.author+'" name="surrender-'+invite.author+'" cursor="'+invite.cursor+'">The seat is ready, you can enter the theater by clicking here.</a></p>';
              // <a onclick="adminmessage.dismiss(this)" cursor="'+invite.cursor+'">Remove this message</a>
              error.showError("default","SEAT READY",html,"left");
              //window.location="/theater/"+adminmessage.room+"/"+adminmessage.seat;
            }
            if (adminmessage.deadletter == 10) {
              adminmessage.deadletter = 0;
              adminmessage.clearCursorInvite( invite.cursor, 0 );
            }
            //adminmessage.clearPairInvite( null );
            //$.cookie('sc', null, { path: '/', expires: -10 })
          }
        //SK = Seat Kill
        } else if (invite.type == "sk") {
          if (adminmessage.location == "theater") {
            adminmessage.clearPairInvite( adminmessage.blockRoom );
          }
        //SR = Seat Request
        } else if (invite.type == "sr") {
          if (adminmessage.location == "theater") {
            html = '<p id="invite-'+invite.pair+'"><a onclick="adminmessage.surrenderSeat(this)" id="admininvite-'+invite.author+'" name="admininvite-'+invite.author+'" cursor="'+invite.cursor+'">'+invite.from+' has asked to take your seat. Click this message to allow this.</a></p>';
            // <a onclick="adminmessage.dismiss(this)" cursor="'+invite.cursor+'">Remove this message</a>
            if ((adminmessage.pair != invite.pair) || ($("#adminmessage_panel:visible").length == 0)) {
              error.showError("default","SEAT REQUEST",html,"left");
            }
          } else if (adminmessage.location == "lobby") {
            adminmessage.clearPairInvite( null );
          }
        //SS = Seat Surrender
        } else if (invite.type == "ss"){
          if (adminmessage.location == "lobby") {
            console.log("Someone surrendered the seat");
            if (adminmessage.location == "lobby") {
              html = '<p><a onclick="adminmessage.doRedirect(this)" id="surrender-'+invite.author+'" name="surrender-'+invite.author+'" cursor="'+invite.cursor+'">The seat is ready, you can enter the theater by clicking here.</a></p>';
              // <a onclick="adminmessage.dismiss(this)" cursor="'+invite.cursor+'">Remove this message</a>
              error.showError("default","SEAT READY",html,"left");
              //window.location="/theater/"+adminmessage.room+"/"+adminmessage.seat;
            }
            //adminmessage.takeSeat();
          } else if (adminmessage.location == "theater") {
            adminmessage.clearPairInvite( adminmessage.leaveRoom );
          }
        } 
        /*
        else {
          html = '<p id="invite-'+invite.pair+'"><a onclick="adminmessage.startAdminChat(this)" id="admininvite-'+invite.author+'" name="admininvite-'+invite.author+'" cursor="'+invite.cursor+'">'+invite.from+' has a message for you.</a></p>';
          if ((adminmessage.pair != invite.pair) || ($("#adminmessage_panel:visible").length == 0)) {
            error.showError("default",html,null,"left");
          }
        }
        */
      }
    },
    
    //The pair and seat info is hardcoded
    //No no need to parse as in "surrenderSeat" below
    requestSeat: function () {
      var args = {"adminmessagebody" : adminmessage.seatrequest,
                  "type" : "sr" };
      $.postAdminJSON("/services/adminmessage/post", 
                      args);
      
    }, 
    
    //BEGIN CAPTURE LOOP
    //A User would like to take a seat
    //That they own, and force the other user to surrender
    captureSeat: function( element ) {
      console.log("Capture Seat");
      
      id = adminmessage.getPair();
      $.cookie("sc", "sent", {path: '/'});
      
      var args = {"pair":adminmessage.pair,
                  "to": id,
                  "room": adminmessage.room,
                  "seat": adminmessage.seat};
      
      $.ajax({url: "/services/Lobby/capture?i="+adminmessage.destination,
              type: "GET", 
              cache: false, 
              dataType: "text", 
              data: $.param(args),
              success: function(response) {
                adminmessage.notifySeat(eval("(" + response + ")"));
              }, error: function(response) {
                console.log("Failure in Seat Capture");
                error.showError("error","Your seat capture was unsuccessful.");
              } 
            });
    },
    
    notifySeat: function ( response ) {
      console.log("Notify Seat!");
      if (response.response.result == "success") {
        var args = {"adminmessagebody" : adminmessage.seatresponse,
                     "type" : "sc" };
        $.postAdminJSON("/services/adminmessage/post", args, adminmessage.takeSeat);
      }
    },
    
    takeSeat: function() {
      console.log("Takin Seat!");
      console.log(adminmessage.location);
      if (adminmessage.location == "lobby") {
        console.log("Showing Error Now!");
        html = '<p><a onclick="adminmessage.doRedirect(this)">The seat is ready, you can enter the theater by clicking here.</a></p>';
        // <a onclick="adminmessage.dismiss(this)">Remove this message</a>
        error.showError("default","SEAT READY",html,"left");
      }
      //window.location="/theater/"+adminmessage.room+"/"+adminmessage.seat;
    },
    
    doRedirect: function( element ) {
      //Remove the Request
      try {
      adminmessage.clearCursorInvite( element.getAttribute("cursor"), element.getAttribute("id") );
      } catch (e) {}
      window.location="/theater/"+adminmessage.room+"/"+adminmessage.seat;
    },
    //END CAPTURE LOOP
    
    //BEGIN BLOCK LOOP
    //Admin tosses you from the theater
    blockSeat: function( element ) {
      console.log("Capture Seat");
      
      id = adminmessage.setPair( element );
      
      var args = {"pair":adminmessage.pair,
                  "to": id,
                  "room": adminmessage.room,
                  "seat": adminmessage.seat};
      
      $.ajax({url: "/services/Lobby/kill",
              type: "GET", 
              cache: false, 
              dataType: "text", 
              data: $.param(args),
              success: function(response) {
                adminmessage.killSeat(eval("(" + response + ")"));
              }, error: function(response) {
                console.log("Failure in Seat Block");
                error.showError("error","Seat block was unsuccessful.");
              } 
            });
    },
    
    killSeat: function ( response ) {
      console.log("Notify Seat!");
      if (response.response.result == "success") {
        var args = {"adminmessagebody" : adminmessage.seatresponse,
                     "type" : "sk" };
        $.postAdminJSON("/services/adminmessage/post", args );
      }
    },
    
    blockRoom: function () {
      console.log("Blocking Room");
      if (adminmessage.location != "lobby")
        window.location="/lobby/"+adminmessage.room+"/"+adminmessage.seat+"?code=block";
    },
    //END BLOCK LOOP
    
    //BEGIN EXIT LOOP
    //The pair and seat are generated from the request
    //And all that's left is to 
    exitSeat: function() {
     
     console.log("exit seat");
     window.location="/services/Lobby/leave?room="+adminmessage.room+"&seat="+adminmessage.seat+"&code=exit";
    
    },
    //END EXIT LOOP
    
    //BEGIN DISMISS LOOP
    //The pair and seat are generated from the request
    //And all that's left is to 
    dismiss: function( element ) {
     console.log("dismiss");
     //Remove the "SR" Request
     try {
     adminmessage.clearCursorInvite( element.getAttribute("cursor"), element.getAttribute("id") );
     } catch(e) {}
    },
    
    //BEGIN SURRENDER LOOP
    //The pair and seat are generated from the request
    //And all that's left is to 
    surrenderSeat: function( element ) {
     console.log("surrender seat");
     id = adminmessage.setPair( element );
     
     //Remove the "SR" Request
     adminmessage.clearCursorInvite( element.getAttribute("cursor"), element.getAttribute("id") );
     
     //Surrender the seat, and then Notify the "Lobby User" the seat is ready
     //In this case, "to" is the id of the waiting user
     var args = {"pair":adminmessage.pair,
                  "to": id,
                  "room": adminmessage.room,
                  "seat": adminmessage.seat};
      $.ajax({url: "/services/Lobby/surrender",
              type: "GET", 
              cache: false, 
              dataType: "text", 
              data: $.param(args),
              complete: adminmessage.notifyLobby
            });
    },
    
    notifyLobby: function ( response ) {
      var args = {"adminmessagebody" : adminmessage.seatresponse,
                   "type" : "ss" };
      $.postAdminJSON("/services/adminmessage/post", args, adminmessage.leaveRoom); 
    },
    
    leaveRoom: function () {
      console.log("leave room");
      console.log($.cookie("sc"));
      /*
      if ($.cookie("sc")) {
        $.cookie('sc', null, { path: '/', expires: -10 })
        return;
      }
      */
      console.log(adminmessage.location);
      if (adminmessage.location == "theater")
        window.location="/lobby/"+adminmessage.room+"/"+adminmessage.seat+"/surrender?code=exit";
    },
    //END SURRENDER LOOP
    
    //An invite will stay in the db until the recipient actually recieves it, at which point it's deleted
    clearCursorInvite: function ( cursor, id ) {
      
      /*
      if ($("#adminmessage_invite_messages > p").length == 1) {
        $("#adminmessage_invite").fadeOut('slow');
      }
      $("#"+id).remove();
      //console.log($("#adminmessage_invite_messages > p").length);
      */
      
      var args = {"cursor": cursor};
      $.ajax({url: "/services/adminmessage/cursor_invite?i="+adminmessage.destination, 
              type: "GET", 
              cache: false, 
              dataType: "text", 
              data: $.param(args)});
    },
    
    //An invite will stay in the db until the recipient actually recieves it, at which point it's deleted
    clearPairInvite: function ( callback ) {
      console.log("Clear Pair Invite");
       var args = {"room": adminmessage.room,
                    "to": adminmessage.user};
       $.ajax({url: "/services/adminmessage/pair_invite?i="+adminmessage.destination, 
                type: "GET", 
                cache: false, 
                dataType: "text", 
                data: $.param(args), 
                success: callback});
    }

};

$(document).ready(function() {
    if (!window.console) window.console = {};
    if (!window.console.log) window.console.log = function() {};
    
    if (window.location.pathname.match(/theater/)) {
      //console.log("In Theater");
      adminmessage.location = "theater";
      //dispatch.poll();
    }
    if (window.location.pathname.match(/screening/)) {
      //console.log("In Screening");
      adminmessage.location = "screening";
    }
    if (window.location.pathname.match(/lobby/)) {
      //console.log("In Screening");
      adminmessage.location = "lobby";
    }
    
    /*    
    if ($("#adminmessageform").length > 0) {
      $("#adminmessageform").live("submit", function() {
          newAdminMessage($(this));
          $("#adminmessage").val('');
          return false;
      });
      
      $("#adminmessage-chat-submit").click(function(e){
          newAdminMessage($("#adminmessageform"));
          $("#adminmessage").val('');
          return false;
      });
      
      $("#adminmessageform").live("keypress", function(e) {
          if (e.keyCode == 13) {
            newAdminMessage($(this));
            $("#adminmessage").val('');
            return false;
          }
      });
      
      $("#adminmessage").select();
      $("#adminmessage").watermark("Enter Your Message Here:");
      
    }
    */

    adminmessage.construct();
    //Wait for Incoming Messages
    adminmessage.getInvites();
    //adminmessage.listen();
    
});
