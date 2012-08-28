// Copyright 2009 FriendFeed
//
// Licensed under the Apache License, Version 2.0 (the "License"); you may
// not use this file except in compliance with the License. You may obtain
// a copy of the License at
//
//     http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
// WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
// License for the specific language governing permissions and limitations
// under the License.

$(document).ready(function() {
    if (!window.console) window.console = {};
    if (!window.console.log) window.console.log = function() {};
    
    if (window.location.pathname.match(/theater/)) {
      //console.log("In Theater");
      activity.location = "theater";
      //Tell The System You're Here
      activity.announce();
    }
    if (window.location.pathname.match(/screening/)) {
      //console.log("In Screening");
      activity.location = "screening";
      //Get the last five events
      activity.history();
    }
    if (window.location.pathname.match(/lobby/)) {
      //console.log("In Screening");
      activity.location = "lobby";
      //Get the last five events
      activity.history();
    }
    
    //Update Your Timestamp, And Check User Count
    //activity.ping();
    
    //Wait for New Additions
    activity.poll();
    
    activity.users = new Array();
    activity.registry = new Array();
    
    activity.remove();

});

function getCookie(name) {
    var r = document.cookie.match("\\b" + name + "=([^;]*)\\b");
    return r ? r[1] : undefined;
}

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

var activity = {
    errorSleepTime: 100,
    cursor: null,
    
    
    //This adds the user to the room
    announce: function() {
        var args = {"room": $("#room").html(),
                    "userid": $("#userid").html(),
                    "location": activity.location};
        $.ajax({url: "/services/activity/announce", 
                type: "GET",  
                cache: false, 
                dataType: "text",
                data: $.param(args), 
                success: activity.onAnnounceSuccess,
                error: activity.onError});
    },
    
    onAnnounceSuccess: function(response) {
        try {
           //console.log("Activity Announce Success");
        } catch (e) {
            activity.onError();
            return;
        }
    },
    
    //This pulls the current list of users from the room
    init: function() {
        var args = {"room": $("#room").html(),
                    "userid": $("#userid").html(),
                    "location": activity.location};
        $.ajax({url: "/services/activity/init", 
                type: "GET", 
                cache: false, 
                dataType: "text",
                data: $.param(args), 
                success: activity.onInitSuccess,
                error: activity.onError});
    },
    
    //This is a full list of users in the room
    //So we use it to remap all constellations
    onInitSuccess: function(response) {
        try {
            if (!response) return;
            var response = eval("(" + response + ")");
            
            //Clear out the temp array
            activity.tempusers = new Array();
            for (var i = 0; i < response.users.length; i++) {
              //Add All Incoming Users to the Temp Array
              activity.tempusers.push(response.users[i].userid);
              //If the Current Loop User isn't here, add him/her
              if ($.inArray(response.users[i].userid, activity.users) == -1){
                activity.users.push(response.users[i].userid);
                activity.registry[response.users[i].userid] = response.users[i].username;
              }
              //Show the User
              activity.showUser(response.users[i],false);
            }
            //console.log("Current Users:"+activity.users.length+" and NEW Users:"+ activity.tempusers.length);
            
            //Now, if there are more "Current" users than "Incoming" users
            //We need to delete some
            if (activity.users.length > activity.tempusers.length) {
              for (var i = 0; i < activity.users.length; i++) {
                if ($.inArray(activity.users[i],activity.tempusers) == -1){
                  activity.dropUser(activity.users[i]);
                  delete activity.registry[activity.users[i]];
                  activity.users = jQuery.grep(activity.users, function(value) {return value != activity.users[i];});
                }
              }
            }
        } catch (e) {
            activity.onError();
            return;
        }
    },
    
    //This pulls the last five events from the room
    history: function() {
        var args = {"room": $("#room").html(),
                    "location" : activity.location};
        $.ajax({url: "/services/activity/history", type: "GET", cache: false, dataType: "text",
                data: $.param(args), success: activity.onHistorySuccess,
                error: activity.onError});
    },
    
    //Updates our Activity List
    onHistorySuccess: function(response) {
        try {
            //console.log("Activity Init Success");
            if (!response) return;
            var response = eval("(" + response + ")");
            console.log(response.activities.length, " activites from init");
            
            //Clear out the temp array
            for (var i = response.activities.length-1; i >=0; i--) {
              activity.showActivity(response.activities[i],true);
            }
        } catch (e) {
            activity.onError();
            return;
        }
    },
    
    //This pulls new users when they arrive in the room 
    poll: function() {
        var args = {"room": $("#room").html(),
                    "userid": $("#userid").html(),
                    "location": activity.location};
        $.ajax({url: "/services/activity/update", type: "POST", 
                cache: false, 
                dataType: "text",
                data: $.param(args), 
                success: activity.onPollSuccess,
                error: activity.onError
                });
    },
    
    //Show the users who arrived
    onPollSuccess: function(response) {
        try {
            //console.log("Activity Poll Success");
            if (!response) return;
            var response = eval("(" + response + ")");
            //console.log(response.users.length, "new users");
            for (var i = 0; i < response.users.length; i++) {
              activity.showUser(response.users[i],true);
            }
        } catch (e) {
            activity.onError();
            return;
        }
        activity.errorSleepTime = 100;
        window.setTimeout(activity.poll, 0);
    },

    onError: function(response) {
        //activity.errorSleepTime *= 2;
        console.log("Poll error; sleeping for", activity.errorSleepTime, "ms");
        //window.setTimeout(activity.poll, activity.errorSleepTime);
    },
    
    setUsers: function(count) {
      //console.log("Setting User Count");
      if ($("#userCount").length > 0) {
        $("#userCount").html(count);
      }
    },
    
    showUser: function(user,update) {
      if (activity.location == 'theater') {
        if ($("#adminmessage-users").length > 0) {
          //console.log("Admin Message Users");
          var color = "green";
          if (user.location == "lobby") {
            color = "orange";
          }
          if ($("#adminuser-"+user.userid).length > 0) {
            userdata = '<a name="adminmessageuser-'+user.userid+'" id="adminmessageuser-'+user.userid+'" title="'+user.username+'" onClick="adminmessagechat.startAdminMessageChat(this)">Online</a>';
            $("#adminuser-"+user.userid+"-status").html(userdata).attr("style","color:"+color);
          } else {
            //console.log("User not here: " + user.userid);
            html = '<div class="message" id="adminuser-'+user.userid+'"><b>'+user.username+': </b><span style="color:'+color+';" id="adminuser-'+user.userid+'-status"><a name="adminmessageuser-'+user.userid+'" id="adminmessageuser-'+user.userid+'" title="'+user.username+'" onClick="adminmessagechat.startAdminMessageChat(this)">Online</a></span></div>';
            //node.hide();
            $("#adminmessage-users").append(html);
            //console.log("Appended User Name:"+user.username);
          }
        }
        if (user.location == "theater") {
          if ($("#user-"+user.userid).length == 0) {
            var left = Math.floor(Math.random()*98);
            var top = Math.floor(Math.random()*121);
            html = '<a style="position: relative; top: '+top+'px; left: '+left+'%;" name="constellation-'+user.userid+'" class="host-star" id="user-'+user.userid+'" title="'+user.username+'" onClick="startPrivateChat(this)"><img src="/images/host-star.png" /></a>';
            //node.hide();
            $("#constellation_map").append(html);
            //console.log("Appended User Name:"+user.username);
          }
        }
      } else if ((activity.location == 'screening') && update) {
        html = '<div name="constellation-'+user.userid+'" id="user-'+user.userid+'">'+user.username+' has entered the theater.</div>';
        $("#constellation_map").append(html);
        activity.remove();
      }
    },
    
    dropUser: function(userid) {
      if (activity.location == 'theater') {
        if ($("#adminmessage-users").length == 0) {
          if ($("#adminuser-"+userid+"-status").length == 0) {
            $("#adminuser-"+userid+"-status").html("Offline").attr("style","color:red");
          }
        }
        if (($("#user-"+userid).length > 0) && (userid != $("#userid").html())) {
          $("#user-"+userid).remove();
        }
        //console.log("Removed User:"+userid);
      } else if (activity.location == 'screening') {
        html = '<div name="constellation-'+userid+'" id="user-'+userid+'">'+activity.registry[userid]+' has left the theater.</div>';
        $("#constellation_map").append(html);
        activity.remove();
      }
    
    },
    
    showActivity: function(user,update) {
      if (activity.location == 'theater') {
        //console.log("Appended User Name:"+user.username);
      } else if ((activity.location == 'screening') && update) {
        var activity_type = "";
        switch (user.activity){
          case "leave_room":
            activity_type = " has left the theater.";
            break;
          case "join_room":
            activity_type = " has entered the theater.";
            break;
          default:
            activity_type = " has entered the theater.";
        }
        html = '<div name="constellation-'+user.userid+'" id="user-'+user.userid+'">'+user.username+activity_type+'</div>';
        $("#constellation_map").append(html);
        activity.remove();
      }
    },
    
    remove: function() {
      var num = 19;
      if ($("#constellation_map div").length > num) {
        for(i=num;i<=$("#constellation_map div").length;i++) {
          $("#constellation_map div:nth-child("+i+")").remove();
        }
      }
    }
};
