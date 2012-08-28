/* jQuery.head - v1.0.3 - K Reeve aka BinaryKitten
*
*	makes a Head Request via XMLHttpRequest (ajax) and returns an object/array of headers returned from the server
*	$.head(url, [data], [callback])
*		url			The url to which to place the head request
*		data		(optional) any data you wish to pass - see $.post and $.get for more info
*		callback	(optional) Function to call when the head request is complete.
*					This function will be passed an object containing the headers with
*					the object consisting of key/value pairs where the key is the header name and the
*					value it's corresponding value
*
*	for discussion and info please visit: http://binarykitten.me.uk/jQHead
*
* ------------ Version History -----------------------------------
* v1.0.3
* 	Fixed the zero-index issue with the for loop for the headers
* v1.0.2
* 	placed the function inside an enclosure
*
* v1.0.1
* 	The 1st version - based on $.post/$.get
*/

(function ($) {
  $.extend({
	head: function( url, data, callback, timeout ) {
	  if ( $.isFunction( data ) ) {
		  callback = data;
		  data = {};
	  }
    if (timeout == undefined) {
      timeout = 2000;
    }
	  return $.ajax({
		type: "HEAD",
		url: url,
		data: data,
		timeout: timeout,
		complete: function (XMLHttpRequest, textStatus) {
		  var headers = XMLHttpRequest.getAllResponseHeaders().split("\n");
		  var new_headers = {};
		  var l = headers.length;
		  for (var key=0;key<l;key++) {
			  if (headers[key].length != 0) {
				  header = headers[key].split(": ");
				  new_headers[header[0]] = header[1];
			  }
		  }
		  if ($.isFunction(callback)) {
			callback(new_headers);
		  }
		}
	  });
	}
  });
})(jQuery);
// JavaScript Document
/**
 * Cookie plugin
 *
 * Copyright (c) 2006 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */

/**
 * Create a cookie with the given name and value and other optional parameters.
 *
 * @example $.cookie('the_cookie', 'the_value');
 * @desc Set the value of a cookie.
 * @example $.cookie('the_cookie', 'the_value', { expires: 7, path: '/', domain: 'jquery.com', secure: true });
 * @desc Create a cookie with all available options.
 * @example $.cookie('the_cookie', 'the_value');
 * @desc Create a session cookie.
 * @example $.cookie('the_cookie', null);
 * @desc Delete a cookie by passing null as value. Keep in mind that you have to use the same path and domain
 *       used when the cookie was set.
 *
 * @param String name The name of the cookie.
 * @param String value The value of the cookie.
 * @param Object options An object literal containing key/value pairs to provide optional cookie attributes.
 * @option Number|Date expires Either an integer specifying the expiration date from now on in days or a Date object.
 *                             If a negative value is specified (e.g. a date in the past), the cookie will be deleted.
 *                             If set to null or omitted, the cookie will be a session cookie and will not be retained
 *                             when the the browser exits.
 * @option String path The value of the path atribute of the cookie (default: path of page that created the cookie).
 * @option String domain The value of the domain attribute of the cookie (default: domain of page that created the cookie).
 * @option Boolean secure If true, the secure attribute of the cookie will be set and the cookie transmission will
 *                        require a secure protocol (like HTTPS).
 * @type undefined
 *
 * @name $.cookie
 * @cat Plugins/Cookie
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 */

/**
 * Get the value of a cookie with the given name.
 *
 * @example $.cookie('the_cookie');
 * @desc Get the value of a cookie.
 *
 * @param String name The name of the cookie.
 * @return The value of the cookie.
 * @type String
 *
 * @name $.cookie
 * @cat Plugins/Cookie
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 */
jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};
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
    errorSleepTime: 5000,
    cursor: null,
    host: null,
    port: null,
    instance: null,
    room: null,
    destination: null,
    user: null,
    users: null,
    registry: null,
    
    construct: function() {
      activity.host = $("#host").html();
      activity.port = parseInt($("#port").html()) + 13090;
      activity.instance = $("#instance").html();
      activity.room = $("#room").html();
      activity.film = $("#film").html();
      activity.destination = activity.host + ":" + activity.port;
      activity.user = $("#userid").html();
      activity.users = new Array();
      activity.registry = new Array();
    },
    
    //This pulls the current list of users from the room
    init: function() {
      if ($("#noauth").length != 0) {
        return;
      }
    
      if (activity.room == '') return;
      var args = {"room": activity.room,
                  "instance": activity.instance,
                  "userid": activity.user,
                  "location": activity.location,
                  "cookie": $.cookie("constellation_frontend")
                };
      $.ajax({url: "/services/activity/init?i="+activity.destination, 
              type: "GET", 
              cache: false, 
              dataType: "text",
              data: $.param(args), 
              //timeout: activity.errorSleepTime,
              success: activity.onInitSuccess,
              error: activity.onInitError
              });
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
              //console.log("TEMP INCOMING "+response.users[i].userid);
              if (response.users[i].userid != undefined) {
                activity.pushUser(response.users[i]);
                //Show the User
                activity.showUser(response.users[i]);
              }
            }
            //console.log("Current Users:"+activity.users.length+" and NEW Users:"+ activity.tempusers.length);
            
            //console.log("Total Prior to DROP users: " + activity.users.length)
            //Now, if there are more "Current" users than "Incoming" users
            //We need to delete some
            if (activity.users.length > activity.tempusers.length) {
              for (var i = 0; i < activity.users.length; i++) {
                activity.dropUser(activity.users[i]);
              }
            }
            //console.log("Total AFTER DROP users: " + activity.users.length)
            //Finally, update total user count
            //console.log("From Init, set count...");
            activity.showUserCount();
            
        } catch (e) {
            console.log("Activity Error");
            activity.onInitError();
            return;
        }
    },
    
    onInitError: function(response) {
      console.log("Activity Init Error");
      window.setTimeout(activity.init, 5000);
    },
    
    pushUser : function (user) {
      if (user.userid == undefined)
        return;
      //Add All Incoming Users to the Temp Array
      activity.tempusers.push(user.userid);
      //If the Current Loop User isn't here, add him/her
      if ($.inArray(user.userid, activity.users) == -1){
        //console.log("Adding " + user.userid + " to user array" );
        activity.users.push(user.userid);
        activity.registry[user.userid] = user.username;
      }
    },
    
    dropUser : function(user,i) {
      if ($.inArray(user,activity.tempusers) == -1){
        activity.removeUser(user);
        delete activity.registry[user];
        activity.users = jQuery.grep(activity.users, function(value) {return value != user;});
      }
    },
    
    //This adds the user to the room
    announce: function() {
        if ($("#noauth").length != 0) {
          return;
        }
        if (activity.room == '') return;
        var args = {"film": activity.film,
                    "room": activity.room,
                    "instance": activity.instance,
                    "userid": activity.user,
                    "location": activity.location,
                    "cookie": $.cookie("constellation_frontend")
                    };
        $.ajax({url: "/services/activity/announce?i="+activity.destination, 
                type: "POST",  
                cache: false, 
                dataType: "text",
                data: $.param(args), 
                //timeout: activity.errorSleepTime,
                success: activity.onAnnounceSuccess,
                error: activity.onAnnounceError});
    },
    
    onAnnounceSuccess: function(response) {
        try {
           var response = eval("(" + response + ")");
           //Put user in Constellation
           activity.showUser( response.users[0] );//Set User Count
           //Add user to current array
           activity.tempusers = new Array();
           activity.pushUser(response.users[0]);
           //Update User count
           //console.log("From Announce, set count...");
           activity.showUserCount();
        } catch (e) {
           activity.onAnnounceError();
            return;
        }
    },
    
    onAnnounceError: function(response) {
      console.log("Activity Announce Error");
      window.setTimeout(activity.announce, 5000);
    },
    
    status: function() {
      if ($("#noauth").length != 0) {
        return;
      }
      
      if (activity.room == '') return;
      /*
      $.head("index.php",{'a':5,'bc':'help'},function(headers) {
      	$.each(headers,function(key,header){ console.log(key+':--:'+header);});
      });
      */
      try {
        var url = "/services/activity/status?i="+activity.destination+"&room="+activity.room+"&userid="+activity.user+"&instance="+activity.instance+"&location="+activity.location+"&film="+activity.film+"&cookie="+$.cookie("constellation_frontend");
        $.head( url,{},function(headers) {
          $.each(headers,function(key,header){ 
            if(key == "X-User-Get") {
              try {
                //console.log("Total users: " + header)
                if (header != activity.users.length) {
                  //Removed 12-07-2011
                  //activity.init();
                }
              } catch (e) {
                //console.log("No Activity Available");
              }
            }
            if(key == "X-Server-Time") {
              try {
                timekeeper.poll(header);
              } catch (e) {
                //console.log("No Timer Available");
              }
            }
          });
          window.setTimeout(activity.status, 5000);
        },activity.errorSleepTime - 2000);
      } catch (e) {
        window.setTimeout(activity.status, 5000);
      }
    },
    
    /*
    //Updates our Activity List
    onHistorySuccess: function(response) {
        try {
            //console.log("Activity Init Success");
            if (!response) return;
            var response = eval("(" + response + ")");
            //console.log(response.activities.length, " activites from init");
            
            //Clear out the temp array
            for (var i = response.activities.length-1; i >=0; i--) {
              activity.showActivity(response.activities[i],true);
            }
        } catch (e) {
            activity.onError();
            return;
        }
    },
    */
    
    //This pulls new users when they arrive in the room 
    poll: function() {
        if ($("#noauth").length != 0) {
          return;
        }
        if (activity.room == '') return;
        var args = {"room": $("#room").html(),
                    "userid": $("#userid").html(),
                    "location": activity.location,
                    "cookie": $.cookie("constellation_frontend")
                    };
        $.ajax({url: "/services/activity/update?i="+activity.destination, type: "POST", 
                cache: false, 
                dataType: "text",
                data: $.param(args), 
                timeout: activity.errorSleepTime,
                success: activity.onPollSuccess,
                error: activity.onPollError
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
            activity.onAnnounceError();
            return;
        }
        activity.errorSleepTime = 100;
        window.setTimeout(activity.poll, 0);
    },

    onPollError: function(response) {
        //activity.errorSleepTime *= 2;
        console.log("Activity Poll error; sleeping for", activity.errorSleepTime, "ms");
        window.setTimeout(activity.poll, activity.errorSleepTime);
    },
    
    showUserCount: function() {
      //console.log("Setting User Count to "+activity.users.length);
      if ($("#userCount").length > 0) {
        $("#userCount").html(activity.users.length);
      }
    },
    
    showUser: function(user) {
      if ($("#adminmessage-users").length > 0) {
        //console.log("Admin Message Users");
        var color = "green";
        if (user.location == "lobby") {
          color = "orange";
        }
        if ($("#adminuser-"+user.userid).length > 0) {
          userdata = '<a name="adminmessageuser-'+user.userid+'" id="adminmessageuser-'+user.userid+'" title="'+user.username+'" onClick="adminmessage.startAdminChat(this)"><img src="/images/Neu/8x8/status/online-'+color+'.png" /></a>';
          $("#adminuser-"+user.userid+"-status").html(userdata);
        } else {
          //console.log("User not here: " + user.userid);
          html = '<div class="message" id="adminuser-'+user.userid+'"><span id="adminuser-'+user.userid+'-block"><a name="adminmessageuser-'+user.userid+'" id="blockmessageuser-'+user.userid+'" title="'+user.username+'" onClick="adminmessage.blockSeat(this)"><img src="/images/Neu/8x8/status/important.png" /></a></span><span id="adminuser-'+user.userid+'-status"><a name="adminmessageuser-'+user.userid+'" id="adminmessageuser-'+user.userid+'" title="'+user.username+'" onClick="adminmessage.electAdminChat(this)"><img src="/images/Neu/8x8/status/online-'+color+'.png" /></a></span><strong>'+user.username+'</strong></div>';
          //node.hide();
          $("#adminmessage-users").append(html);
          //console.log("Appended User Name:"+user.username);
        }
      }
      if ($("#user-"+user.userid).length == 0) {
        var left = Math.floor(Math.random()*98);
        var top = Math.floor(Math.random()*121);
        html = '<a style="position: relative; top: '+top+'px; left: '+left+'%;" name="constellation-'+user.userid+'" class="host-star" id="user-'+user.userid+'" title="'+user.username+'" onClick="privatechat.startPrivateChat(this)"><img src="/images/host-star.png" /></a>';
        //node.hide();
        $("#constellation_map").append(html);
        //console.log("Appended User Name:"+user.username);
      }
      
    },
    
    setUserLocation:function(user,name,location) {
      var color = "green";
      if (location == "lobby") {
        color = "orange";
      }
      if ($("#adminuser-"+userid).length > 0) {
          userdata = '<a name="adminmessageuser-'+userid+'" id="adminmessageuser-'+userid+'" title="'+username+'" onClick="adminmessage.startAdminChat(this)"><img src="/images/Neu/8x8/status/online-'+color+'.png" /></a>';
          $("#adminuser-"+userid+"-status").html(userdata);
      }
    },
    
    removeUser: function(userid) {
      if ($("#adminmessage-users").length == 0) {
        if ($("#adminuser-"+userid+"-status").length == 0) {
          $("#adminuser-"+userid+"-status").html("Offline").attr("style","color:red");
        }
      }
      if (($("#user-"+userid).length > 0) && (userid != $("#userid").html())) {
        $("#user-"+userid).remove();
      }
      //console.log("Removed User:"+userid);
    }
};

$(document).ready(function() {
    if (!window.console) window.console = {};
    if (!window.console.log) window.console.log = function() {};
    
    //console.log("In Theater");
    if (window.location.pathname.match(/theater/)) {
      activity.location = "theater";
      //Get the last five events
    }
    if (window.location.pathname.match(/lobby/)) {
      activity.location = "lobby";
      //Get the last five events
    }
    
    //Set our constants
    activity.construct();
    //Get the Current list of Users
    
    //Removed 12/7/2011
    //activity.init();
    
    //Tell The System You're Here
    activity.announce();
    
    //Wait for New Additions
    //activity.poll();
    //Keep a persistent timestamp
    activity.status();
    
});
