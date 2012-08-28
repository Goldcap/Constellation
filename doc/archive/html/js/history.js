var activityHistory = {

    errorSleepTime: 5000,
    cursor: null,
    host: null,
    port: null,
    instance: null,
    room: null,
    user: null,
    destination: null,
    inbox: null,
    listcount: 7,
    
    construct: function() {
      activityHistory.host = $("#host").html();
      activityHistory.port = parseInt($("#port").html()) + 14090;
      activityHistory.room = $("#room").html();
      activityHistory.film = $("#film").html();
      activityHistory.destination = activityHistory.host + ":" + activityHistory.port;
      activityHistory.inbox = $("#constellation_map");
    },
    
    //This pulls the last five events from the room
    activityHistory: function() {
        var args = {"room": activityHistory.room,
                    "film": activityHistory.film,
                    "location" : activityHistory.location};
        $.ajax({url: "/services/history/init?i="+activityHistory.destination, 
                type: "GET", 
                cache: false, 
                dataType: "text",
                data: $.param(args), 
                success: activityHistory.onHistorySuccess,
                error: activityHistory.onError});
    },
    
    //Updates our Activity List
    onHistorySuccess: function(response) {
        try {
            //console.log("Activity Init Success");
            if (!response) return;
            var response = eval("(" + response + ")");
            //console.log(response.activities.length, " activites from init");
            
            //Clear out the temp array
            for (var i = 0; i<response.activities.length-1; i++) {
              //console.log(i);
              if (i == activityHistory.listcount) break;
              activityHistory.showActivity(response.activities[i],false);
            }
            activityHistory.setColors();
        } catch (e) {
            activityHistory.onError();
            return;
        }
    },
    
    //This pulls new users when they arrive in the room 
    poll: function() {
        var args = {"room": activityHistory.room,
                    "film": activityHistory.film,
                    "location": activityHistory.location};
        $.ajax({url: "/services/history/update?i="+activityHistory.destination,
                type: "POST", 
                cache: false, 
                dataType: "text",
                data: $.param(args), 
                //timeout: activityHistory.errorSleepTime,
                success: activityHistory.onPollSuccess,
                error: activityHistory.onPollError
                });
    },
    
    //Show the users who arrived
    onPollSuccess: function(response) {
        try {
            //console.log("Activity Poll Success");
            if (!response) return;
            var response = eval("(" + response + ")");
            //console.log(response.users.length, "new users");
            for (var i = 0; i < response.items.length; i++) {
              activityHistory.showActivity(response.items[i],true);
            }
            activityHistory.setColors();
        } catch (e) {
            activityHistory.onError();
        }
        //activityHistory.errorSleepTime = 100;
        console.log("Restart Poll");
        window.setTimeout(activityHistory.poll, 0);
    },
    
    onPollError: function(response) {
        //activityHistory.errorSleepTime *= 2;
        //console.log("Poll error; sleeping for", activityHistory.errorSleepTime, "ms");
        console.log("Restart Poll");
        window.setTimeout(activityHistory.poll, 0);
    },
    
    onError: function(response) {
        //activityHistory.errorSleepTime *= 2;
        console.log("Poll error; sleeping for", activityHistory.errorSleepTime, "ms");
        //window.setTimeout(activityHistory.poll, activityHistory.errorSleepTime);
    },
    
    showActivity: function(activity,update,i) {
      if ((activityHistory.location == 'screening') || (activityHistory.location == 'film')) {
        html = '<li id="constellation-'+activity.activity_id+'">'+activity.message+'</li>';
        if (update) {
          $("#constellation_map").prepend(html);
        } else {
          $("#constellation_map").append(html);
        }
        activityHistory.removeActivity();
      }
    },
    
    setColors: function() {
      for (i=2;i<=activityHistory.listcount;i++) {
        //console.log("Setting Color for "+i);
        //console.log($("#constellation_map li:nth-child("+i+")").length);
        $("#constellation_map li:nth-child("+i+")").attr("class","color"+i);
      }
    },
    
    removeActivity: function() {
      if ($("#constellation_map li").length > activityHistory.listcount) {
        for(i=activityHistory.listcount;i<=$("#constellation_map ul li").length;i++) {
          $("#constellation_map li:nth-child("+i+")").remove();
        }
      }
    }
};

$(document).ready(function() {
    if (!window.console) window.console = {};
    if (!window.console.log) window.console.log = function() {};
    
    if (window.location.pathname.match(/screening/)) {
      activityHistory.location = "screening";
      //Get the last five events
    }
    if (window.location.pathname.match(/lobby/)) {
      activityHistory.location = "lobby";
      //Get the last five events
    }
    if (window.location.pathname.match(/film/)) {
      activityHistory.location = "film";
      //Get the last five events
    }
    
    activityHistory.construct();
    //Add Prior Activities
    activityHistory.activityHistory();
    //Wait for New Additions
    activityHistory.poll();
    
    //Remove Users who have dissapeared
    activityHistory.removeActivity();

});
