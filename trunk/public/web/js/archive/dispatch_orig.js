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
  
  if (window.location.pathname.match(/theater/)) {
    //console.log("In Theater");
    dispatch.location = "theater";
    dispatch.pingurl = "/services/dispatch/status";
  }
  if (window.location.pathname.match(/screening/)) {
    //console.log("In Screening");
    dispatch.location = "screening";
    dispatch.pingurl = "/services/dispatch/watch";
  }
  if (window.location.pathname.match(/lobby/)) {
    //console.log("In Screening");
    dispatch.location = "lobby";
    dispatch.pingurl = "/services/dispatch/status";
  }
  dispatch.ping();
  
});

var dispatch = {

    ping: function() {
      $.head(
        dispatch.pingurl+"?room="+$("#room").html()+"&userid="+$("#userid").html()+"&location="+dispatch.location,
        {},
        function(headers) {
          $.each(headers,function(key,header){ 
            if(key == "X-User-Get") {
              try {
                activity.setUsers(header);
                if (header != activity.users.length) {
                  activity.init();
                }
              } catch (e) {
                console.log("No Activity Available");
              }
            }
            if(key == "X-Server-Time") {
              try {
                timekeeper.poll(header);
              } catch (e) {
                console.log("No Timer Available");
              }
            }
            if(key == "X-Private-Get") {
              try {
                //console.log ("Private Chats: "+header);
              } catch (e) {
                console.log("No Timer Available");
              }
            }
            if(key == "X-Invite") {
              try {
                privatechat.showInvite(header);
              } catch (e) {
                console.log("No Private Chat Available");
              }
            }
            if(key == "X-Admin-Get") {
              try {
                //console.log ("Private Chats: "+header);
              } catch (e) {
                console.log("No Timer Available");
              }
            }
            if(key == "X-AdminInvite") {
              try {
                adminmessagechat.showInvite(header);
              } catch (e) {
                console.log("No Private Chat Available");
              }
            }
        });
        window.setTimeout(dispatch.ping, 2000);
      });
    }
    
};
