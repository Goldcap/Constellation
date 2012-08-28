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
		complete: function (arequest, textStatus) {
		  try {
			var headers = arequest.getAllResponseHeaders().split("\n");
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
		  }} catch (e) {}
		}
	  });
	}
  });
})(jQuery);

(function($){$.fn.editable=function(target,options){if('disable'==target){$(this).data('disabled.editable',true);return;}
if('enable'==target){$(this).data('disabled.editable',false);return;}
if('destroy'==target){$(this).unbind($(this).data('event.editable')).removeData('disabled.editable').removeData('event.editable');return;}
var settings=$.extend({},$.fn.editable.defaults,{target:target},options);var plugin=$.editable.types[settings.type].plugin||function(){};var submit=$.editable.types[settings.type].submit||function(){};var buttons=$.editable.types[settings.type].buttons||$.editable.types['defaults'].buttons;var content=$.editable.types[settings.type].content||$.editable.types['defaults'].content;var element=$.editable.types[settings.type].element||$.editable.types['defaults'].element;var reset=$.editable.types[settings.type].reset||$.editable.types['defaults'].reset;var callback=settings.callback||function(){};var onedit=settings.onedit||function(){};var onsubmit=settings.onsubmit||function(){};var onreset=settings.onreset||function(){};var onerror=settings.onerror||reset;if(settings.tooltip){$(this).attr('title',settings.tooltip);}
settings.autowidth='auto'==settings.width;settings.autoheight='auto'==settings.height;return this.each(function(){var self=this;var savedwidth=$(self).width();var savedheight=$(self).height();$(this).data('event.editable',settings.event);if(!$.trim($(this).html())){$(this).html(settings.placeholder);}
$(this).bind(settings.event,function(e){if(true===$(this).data('disabled.editable')){return;}
if(self.editing){return;}
if(false===onedit.apply(this,[settings,self])){return;}
e.preventDefault();e.stopPropagation();if(settings.tooltip){$(self).removeAttr('title');}
if(0==$(self).width()){settings.width=savedwidth;settings.height=savedheight;}else{if(settings.width!='none'){settings.width=settings.autowidth?$(self).width():settings.width;}
if(settings.height!='none'){settings.height=settings.autoheight?$(self).height():settings.height;}}
if($(this).html().toLowerCase().replace(/(;|")/g,'')==settings.placeholder.toLowerCase().replace(/(;|")/g,'')){$(this).html('');}
self.editing=true;self.revert=$(self).html();$(self).html('');var form=$('<form />');if(settings.cssclass){if('inherit'==settings.cssclass){form.attr('class',$(self).attr('class'));}else{form.attr('class',settings.cssclass);}}
if(settings.style){if('inherit'==settings.style){form.attr('style',$(self).attr('style'));form.css('display',$(self).css('display'));}else{form.attr('style',settings.style);}}
var input=element.apply(form,[settings,self]);var input_content;if(settings.loadurl){var t=setTimeout(function(){input.disabled=true;content.apply(form,[settings.loadtext,settings,self]);},100);var loaddata={};loaddata[settings.id]=self.id;if($.isFunction(settings.loaddata)){$.extend(loaddata,settings.loaddata.apply(self,[self.revert,settings]));}else{$.extend(loaddata,settings.loaddata);}
$.ajax({type:settings.loadtype,url:settings.loadurl,data:loaddata,async:false,success:function(result){window.clearTimeout(t);input_content=result;input.disabled=false;}});}else if(settings.data){input_content=settings.data;if($.isFunction(settings.data)){input_content=settings.data.apply(self,[self.revert,settings]);}}else{input_content=self.revert;}
content.apply(form,[input_content,settings,self]);input.attr('name',settings.name);buttons.apply(form,[settings,self]);$(self).append(form);plugin.apply(form,[settings,self]);$(':input:visible:enabled:first',form).focus();if(settings.select){input.select();}
input.keydown(function(e){if(e.keyCode==27){e.preventDefault();reset.apply(form,[settings,self]);}});var t;if('cancel'==settings.onblur){input.blur(function(e){t=setTimeout(function(){reset.apply(form,[settings,self]);},500);});}else if('submit'==settings.onblur){input.blur(function(e){t=setTimeout(function(){form.submit();},200);});}else if($.isFunction(settings.onblur)){input.blur(function(e){settings.onblur.apply(self,[input.val(),settings]);});}else{input.blur(function(e){});}
form.submit(function(e){if(t){clearTimeout(t);}
e.preventDefault();if(false!==onsubmit.apply(form,[settings,self])){if(false!==submit.apply(form,[settings,self])){if($.isFunction(settings.target)){var str=settings.target.apply(self,[input.val(),settings]);$(self).html(str);self.editing=false;callback.apply(self,[self.innerHTML,settings]);if(!$.trim($(self).html())){$(self).html(settings.placeholder);}}else{var submitdata={};submitdata[settings.name]=input.val();submitdata[settings.id]=self.id;if($.isFunction(settings.submitdata)){$.extend(submitdata,settings.submitdata.apply(self,[self.revert,settings]));}else{$.extend(submitdata,settings.submitdata);}
if('PUT'==settings.method){submitdata['_method']='put';}
$(self).html(settings.indicator);var ajaxoptions={type:'POST',data:submitdata,dataType:'html',url:settings.target,success:function(result,status){if(ajaxoptions.dataType=='html'){$(self).html(result);}
self.editing=false;callback.apply(self,[result,settings]);if(!$.trim($(self).html())){$(self).html(settings.placeholder);}},error:function(xhr,status,error){onerror.apply(form,[settings,self,xhr]);}};$.extend(ajaxoptions,settings.ajaxoptions);$.ajax(ajaxoptions);}}}
$(self).attr('title',settings.tooltip);return false;});});this.reset=function(form){if(this.editing){if(false!==onreset.apply(form,[settings,self])){$(self).html(self.revert);self.editing=false;if(!$.trim($(self).html())){$(self).html(settings.placeholder);}
if(settings.tooltip){$(self).attr('title',settings.tooltip);}}}};});};$.editable={types:{defaults:{element:function(settings,original){var input=$('<input type="hidden" id="'+settings.name+'_editable"></input>');$(this).append(input);return(input);},content:function(string,settings,original){$(':input:first',this).val("");},reset:function(settings,original){original.reset(this);},buttons:function(settings,original){var form=this;if(settings.submit){if(settings.submit.match(/>$/)){var submit=$(settings.submit).click(function(){if(submit.attr("type")!="submit"){form.submit();}});}else{var submit=$('<button type="submit" />');submit.html(settings.submit);}
$(this).append(submit);}
if(settings.cancel){if(settings.cancel.match(/>$/)){var cancel=$(settings.cancel);}else{var cancel=$('<button type="cancel" />');cancel.html(settings.cancel);}
$(this).append(cancel);$(cancel).click(function(event){if($.isFunction($.editable.types[settings.type].reset)){var reset=$.editable.types[settings.type].reset;}else{var reset=$.editable.types['defaults'].reset;}
reset.apply(form,[settings,original]);return false;});}}},text:{element:function(settings,original){var input=$('<input />');if(settings.width!='none'){input.width(settings.width);}
if(settings.height!='none'){input.height(settings.height);}
input.attr('autocomplete','off');$(this).append(input);return(input);}},textarea:{element:function(settings,original){var textarea=$('<textarea id="'+settings.name+'_editable" />');if(settings.rows){textarea.attr('rows',settings.rows);}else if(settings.height!="none"){textarea.height(settings.height);}
if(settings.cols){textarea.attr('cols',settings.cols);}else if(settings.width!="none"){textarea.width(settings.width);}
$(this).append(textarea);return(textarea);}},select:{element:function(settings,original){var select=$('<select />');$(this).append(select);return(select);},content:function(data,settings,original){if(String==data.constructor){eval('var json = '+data);}else{var json=data;}
for(var key in json){if(!json.hasOwnProperty(key)){continue;}
if('selected'==key){continue;}
var option=$('<option />').val(key).append(json[key]);$('select',this).append(option);}
$('select',this).children().each(function(){if($(this).val()==json['selected']||$(this).text()==$.trim(original.revert)){$(this).attr('selected','selected');}});}}},addInputType:function(name,input){$.editable.types[name]=input;}};$.fn.editable.defaults={name:'value',id:'id',type:'text',width:'auto',height:'auto',event:'click.editable',onblur:'cancel',loadtype:'GET',loadtext:'Loading...',placeholder:'Click to edit',loaddata:{},submitdata:{},ajaxoptions:{}};})(jQuery);
(function($){$.fn.inputlimiter=function(options){var opts=$.extend({},$.fn.inputlimiter.defaults,options);if(opts.boxAttach&&!$('#'+opts.boxId).length)
{$('<div/>').appendTo("body").attr({id:opts.boxId,'class':opts.boxClass}).css({'position':'absolute'}).hide();if($.fn.bgiframe)
$('#'+opts.boxId).bgiframe();}
$(this).each(function(i){$(this).unbind();$(this).keyup(function(e){if($(this).val().length>opts.limit)
$(this).val($(this).val().substring(0,opts.limit));if(opts.boxAttach)
{$('#'+opts.boxId).css({'width':$(this).outerWidth()-($('#'+opts.boxId).outerWidth()-$('#'+opts.boxId).width())+'px','left':$(this).offset().left+'px','top':($(this).offset().top+$(this).outerHeight())-1+'px','z-index':2000});}
var charsRemaining=opts.limit-$(this).val().length;var remText=opts.remTextFilter(opts,charsRemaining);var limitText=opts.limitTextFilter(opts);if(opts.limitTextShow)
{$('#'+opts.boxId).html(remText+' '+limitText);var textWidth=$("<span/>").appendTo("body").attr({id:'19cc9195583bfae1fad88e19d443be7a','class':opts.boxClass}).html(remText+' '+limitText).innerWidth();$("#19cc9195583bfae1fad88e19d443be7a").remove();if(textWidth>$('#'+opts.boxId).innerWidth()){$('#'+opts.boxId).html(remText+'<br />'+limitText);}
$('#'+opts.boxId).show();}
else
$('#'+opts.boxId).html(remText).show();});$(this).keypress(function(e){if((!e.keyCode||(e.keyCode>46&&e.keyCode<90))&&$(this).val().length>=opts.limit)
return false;});$(this).blur(function(){if(opts.boxAttach)
{$('#'+opts.boxId).fadeOut('fast');}
else if(opts.remTextHideOnBlur)
{var limitText=opts.limitText;limitText=limitText.replace(/\%n/g,opts.limit);limitText=limitText.replace(/\%s/g,(opts.limit==1?'':'s'));$('#'+opts.boxId).html(limitText);}});});};$.fn.inputlimiter.remtextfilter=function(opts,charsRemaining){var remText=opts.remText;if(charsRemaining==0&&opts.remFullText!=null){remText=opts.remFullText;}
remText=remText.replace(/\%n/g,charsRemaining);remText=remText.replace(/\%s/g,(opts.zeroPlural?(charsRemaining==1?'':'s'):(charsRemaining<=1?'':'s')));return remText;};$.fn.inputlimiter.limittextfilter=function(opts){var limitText=opts.limitText;limitText=limitText.replace(/\%n/g,opts.limit);limitText=limitText.replace(/\%s/g,(opts.limit<=1?'':'s'));return limitText;};$.fn.inputlimiter.defaults={limit:255,boxAttach:true,boxId:'limiterBox',boxClass:'limiterBox',remText:'%n character%s remaining.',remTextFilter:$.fn.inputlimiter.remtextfilter,remTextHideOnBlur:true,remFullText:null,limitTextShow:false,limitText:'Field limited to %n character%s.',limitTextFilter:$.fn.inputlimiter.limittextfilter,zeroPlural:true};})(jQuery);var timekeeper = {    //These come from the HTML Page    currentTime: null,    movieStartTime: null,    reviewStartTime: null,    promoStartTime: null,    qaStartTime: null,    viewBlockTime: null,    viewEndTime: null,        currentPanel: "prescreening-panel",        init: function() {      //console.log("Init Timekeeper");      timekeeper.currentTime = new Date().getTime() / 1000;      timekeeper.movieStartTime = $("#starttime").html();      timekeeper.reviewStartTime = $("#reviewtime").html();      timekeeper.promoStartTime = $("#promotime").html();      timekeeper.qaStartTime = $("#qatime").html();      timekeeper.viewBlockTime = $("#blockentrytime").html();      timekeeper.viewEndTime = $("#endtime").html();      //if ($("#noauth").length == 0) {        //error.showError("error","Please Note: If you leave the theater and plan to come back from another location...","be sure to use the 'exit theater' button in the toolbar! Otherwise, you may not be permitted to return to the theater.",4000);      //}      toggleDiv("chat_panel");          },        //This pulls new users when they arrive in the room     poll: function( time ) {      //console.log("Last Time, " + timekeeper.currentTime);      timekeeper.currentTime = time;      //console.log("Time Now, " + timekeeper.currentTime);      //console.log("Block Time, " + timekeeper.viewBlockTime);      //console.log("End Time, " + timekeeper.viewEndTime);      			//Reverse Incremental Order      switch( true ) {        //There is a maximum lifetime for the show, which is "Film End Time" + 30 mins        //Film is Over				case (timekeeper.currentTime > timekeeper.viewEndTime):					console.log("Viewing is over!");					console.log(timekeeper.currentTime + " = " + timekeeper.viewEndTime);          //window.location.href="/film/"+$("#film").html()+"?code=exp";          break;        //This is the "MOVIE WINDOW" during which the movie should play        //viewBockTime is the Movie End Minus 15 Seconds				case ((timekeeper.currentTime >= timekeeper.movieStartTime) && (timekeeper.currentTime < timekeeper.viewBlockTime)):          //if ((timekeeper.currentPanel == 'review_panel') || (timekeeper.currentPanel == 'qa_panel')|| (timekeeper.currentPanel == 'promo_panel'))          //  return;          //console.log("It's Movie Time!");          if ((timekeeper.currentTime > timekeeper.movieStartTime + 5) && ($(".screening_wrapper").css("display") == "none")) {            //We show the non-flash message after five seconds            //If the user isn't seeing the swf, they'll see this...            $(".screening_wrapper").fadeIn();          }          timekeeper.startMovie();          break;        case ((timekeeper.currentTime >= timekeeper.movieStartTime) && (timekeeper.currentTime > timekeeper.viewBlockTime)):          //console.log("video over");					videoplayer.hideVODPlayer();          break;        default:          //console.log("No time detected!");          break;      }    },        //This is the "MOVIE WINDOW" during which the movie should play    //viewBockTime is the Movie End Minus 15 Seconds		checkMovieTime: function() {      if ((timekeeper.currentTime > timekeeper.movieStartTime) && (timekeeper.currentTime < timekeeper.viewBlockTime)) {        return true;      }      return false;    },        startMovie: function() {            if ((timekeeper.currentPanel == 'video_panel') && (document.getElementById(videoplayer.div) != undefined))        return;            //toggleDiv("chat_panel");      $("#status_panel").html("Status: Screening");      timekeeper.currentPanel = 'video_panel';      $("#timer_panel").fadeOut("slow");      $("#prescreening_panel_main").fadeOut(100);      $("#video_panel").fadeIn("slow");      if (videoplayer.state == "pre") {        if ($("#noauth").length == 0) {          //If the problem persists, please 'Exit' the theater, and then re-enter from the lobby. 					//error.showError("error","Please Note: If you encounter any problems with streaming quality,","please first try to refresh your browser. Thank you.",6000);          videoplayer.initPlayer();        }      }    },        startReview: function() {      if (timekeeper.currentPanel == 'review_panel')        return;      $("#status_panel").html("Status: Reviews");      timekeeper.currentPanel = 'review_panel';      //$("#video_panel").fadeOut("slow");      $("#review_panel").fadeIn("slow");    },        startPromo: function() {      if (timekeeper.currentPanel == 'promo_panel')        return;      $("#status_panel").html("Status: Promos");      timekeeper.currentPanel = 'promo_panel';      //$("#video_panel").fadeOut("slow");      $("#review_panel").fadeOut("slow");      $("#promo_panel").fadeIn("slow");      promotions.init();    },        startQA: function() {      //console.log("QANDA in TIMEKEEPER");      if (timekeeper.currentPanel == 'qa_panel')        return;      $("#status_panel").html("Status: Q And A");      timekeeper.currentPanel = 'qa_panel';      qanda.allowScreeningClose();      $("#promo_panel").fadeOut("slow");      //$("#qa_panel").fadeIn("slow");      //This is a user      /*      if ($("#qandaform").length > 0) {        $("#qandaform").hide();        qanda.slidePoll();      }      //This is a host      if ($("#qanda-host-inbox").length > 0) {        if (qanda) {          qanda.closeInput();        }      }      */    }};$(document).ready(function() {  if (!window.console) window.console = {};  if (!window.console.log) window.console.log = function() {};    //$("#review_panel").fadeIn("slow");  timekeeper.init();  });function getCookie(name) {
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
var videoplayer = {
  
  state: "pre",
  div: "none",
  volume: 5,
  is_mute: false,
  trailer: 41,
  version: "2.48-Uncomp",
	file: null,
	autoplay: null,
	videoHidden: 0,
	startTime: 0,
	errorSleepTime: 5000,
  
  init: function() {
    $("#mute").click(function() {
      videoplayer.toggleMute();
    });
    
    $("#fullscreen").click(function() {
      videoplayer.fullscreen();
    });
    
    videoplayer.setSoundControl();
    
  },
  
  initPlayer: function() {
  	
    videoplayer.setVODControls( $("#video").html() );
    // videoplayer.setSoundControl();
    
  },
  
	setVODControls: function( file ) {
			
			videoplayer.file = file;
			var args={"k":$("#video_data").html()};
			videoplayer.state = "playing";
      videoplayer.div = 'cPlayer';
      
      if ($("#runtime").html() > 0) {
        var theseek = $("#runtime").html();
      } else {
        var theseek = 0;
      }
			
			var dts = new Date().getTime();
			
			if (timekeeper != undefined) {
				 var currentTime = timekeeper.currentTime + 1;
			} else {
      	var currentTime = new Date().getTime() / 1000;
      }
      
      //console.log("Seek Time:" + theseek);
      var flashvars = 
        {src: videoplayer.file,
        autostart: true,
    		debugConsole: 'false',
		    delaySeek: '3',
		    csrc: $("#csrc").html(),
		    seekInto: theseek,
		    filmStartTime: $("#starttime").html(),
    		ticketParams:$("#video_data").html(),
				streamName: $("#room").html() + "_stream",
				//auth:response.tokenResponse.token,
				slist:$("#film").html(),
				bitrates:$("#bitrates").html(),
        fileType: $("#movie_type").html(),
        sip: $("#host").html(),
        sprt: parseInt($("#port").html()) + 16090,
        timestamp: currentTime,
        showTimeDebug: false,
        showTimeDebugInterval: 10,
        zeroFPSLimit:30,
        logLevel:3
    	};
        
      var params = 
        {
          allowFullScreen: 'true',
          allowScriptAccess: 'always',
          wmode: 'direct',
					bgcolor:"#000000"
        };
      var attributes = 
        {
          id: 'cPlayer',
          name: 'cPlayer'
        };
      
      
      videoplayer.div = 'cPlayer';
      swfobject.embedSWF('/flash/Constellation_Player_secure.swf?v='+videoplayer.version, 'movie_stream', '100%', '100%', '10.1.0', '/flash/expressInstall.swf', flashvars, params, attributes);
      
	},
	
	hideVODPlayer: function() {
    try {
    if (swfobject != undefined) {
      swfobject.removeSWF('cPlayer');
    	$("#video_stream").append('<div id="movie_stream" class="nx_widget_player">');
    }
    } catch (e) {}
  },
   
  pushLogLevel: function( value ) {
		var args = {body:"hostset:setLogLevel|hostval:"+value};
		$.postJSON("/services/chat/post", args );
	},
  
  setLogLevel: function( message ) {
		vals = message.html.split("|");
		var name=vals[0].split(":");
		var value=vals[1].split(":");
		console.log("setting new value of "+name[1]+" " + value[1]);
		if (document.getElementById('cPlayer') != undefined) {
			document.getElementById("cPlayer").setLogLevel(value[1]);
		}
	},
  
  showHostCam: function() {
    if (swfobject != undefined) {
      //document.getElementById(videoplayer.div).focus();
      //document.getElementById(videoplayer.div).showHostCam();
      videoplayer.setHOSTControls();
			$(".show_hostcam").hide(100);
      $(".hide_hostcam").show(100);
      if (qanda != undefined) {
        //console.log("VideoPlayer Publishing Host Cam");
        //qanda.publishHost();
        qanda.qanda_showing = true;
      }
    }
  },

  hideHostCam: function() {
    try {
    if (swfobject != undefined) {
      swfobject.removeSWF('myHost');
    	$(".qanda_box").append('<div id="host_stream" class="nx_widget_player"></div>');
			//document.getElementById(videoplayer.div).hideHostCam();
      $(".hide_hostcam").hide(100);
      $(".show_hostcam").show(100);
      if (qanda != undefined) {
        qanda.qanda_showing = false;
      }
    }
    } catch (e) {}
  },
  
  //This is the same as "showLiveViewer"
  //Except it tries to destroy the Viewer
  //Before it Starts
  camHostReStart: function() {
    videoplayer.videoHidden = 0;
    //console.log("Video Player Cam Viewer Click");
    swfobject.removeSWF('myHost');
    $(".qanda_box").append('<div id="host_stream" class="nx_widget_player"></div>');
		videoplayer.setHOSTControls();
    //document.getElementById(videoplayer.div).camViewerStart();
    if (qanda != undefined) {
      qanda.qanda_showing = true;
    }
  },
  
	setHOSTControls: function() {
		var args = {"host":$("#userid").html()};
		$.ajax({url: "/services/HUD/get", 
              type: "GET", 
              cache: false, 
              dataType: "json",
              data: $.param(args),
              timeout: videoplayer.errorSleepTime,
              success: function(response) {videoplayer.startHOST(response)},
              error: videoplayer.setHOSTControls});
	},
	
	startHOST: function( response ) {
			//console.log("Setting Host Control");
			var settings = response.hudSettings;
			
      if ($("#hostserver").html() != "akamai") {
        var hswf = "recordHostCamera.swf";
        var rtmphost = "rtmp://"+$("#hostserver").html()+".rtmp.constellation.tv/live/";
      } else {
        var hswf = "HostCam.swf";
        var rtmphost = "rtmp://p.ep45907.i.akamaientrypoint.net/EntryPoint/";
      }
       
      var flashvars = 
        {src: videoplayer.file,
        debugConsole: 'false',
		    ticketParams:$("#video_data").html(),
				streamName: $("#room").html() + "_stream",
				//auth:response.tokenResponse.token,
				slist:$("#film").html(),
        sip: $("#host").html(),
        bandwidthLimit: settings.bandwidthLimit,
		    qualityLevel: settings.qualityLevel,
		    keyFrameInterval: settings.keyFrameInterval,
		    captureFps: settings.captureFps,
		    bufferMin: settings.bufferMin,
		    bufferMax: settings.bufferMax,
		    micRate: settings.micRate,
		    micGain: settings.micGain,
		    motionTimeout: settings.motionTimeout,
		    echoSuppression: settings.echoSuppression,
		    enhancedMicrophone: settings.enhancedMicrophone,
		    silenceLevel: settings.silenceLevel,
		    micSilenceTimeout: settings.micSilenceTimeout,
		    enableVAD: settings.enableVAD,
        recordURL: rtmphost
    		};
    		
      var params = 
        {
          allowFullScreen: 'true',
          allowScriptAccess: 'always',
          wmode: 'opaque',
					bgcolor:"#000000"
        };
      var attributes = 
        {
          id: 'myHost',
          name: 'myHost'
        };
      
      //console.log('Loading '+'/flash/HostCam.swf');
      swfobject.embedSWF('/flash/'+hswf, 'host_stream', '290', '220', '10.1.0', '/flash/expressInstall.swf', flashvars, params, attributes);
      
	},
	
	message: function( type, title, message ) {
	 if (videoplayer.state == "playing") {
	  if (document.getElementById(videoplayer.div) != undefined)
		document.getElementById(videoplayer.div).displayMessage(type,title,message);
	 }
  },

	resize: function() {
	 if (videoplayer.state == "playing") {
	   if (document.getElementById(videoplayer.div) != undefined)
		document.getElementById(videoplayer.div).resizeNotification();
	 }
  },
  
  toggleMute: function() {
  
    if (! videoplayer.is_mute) {
      videoplayer.is_mute=true;
			videoplayer.mute();
    } else {
      videoplayer.is_mute=false;
			videoplayer.unmute();
    }
  },
	
	mute: function() {
		$("#mute").css("backgroundPosition","-20px 0px");
		$(".volume_bar").css("backgroundPosition","-104px 0px");
		if ((document.getElementById(videoplayer.div) != undefined) && (videoplayer.state == 'playing')) {
			document.getElementById(videoplayer.div).setVolume(0);
		}
		videoplayer.unsetSoundControl();
	},
	
	unmute: function() {
		if ((document.getElementById(videoplayer.div) != undefined)  && (videoplayer.state == 'playing')) {
			document.getElementById(videoplayer.div).setVolume(videoplayer.volume);
		}
		$("#mute").css("backgroundPosition","0px 0px");
		$(".volume_bar").css("backgroundPosition","0px 0px");
		videoplayer.setSoundControl();
	},

	setFocus: function() {
    if (document.getElementById(videoplayer.div) != undefined) {
      document.getElementById(videoplayer.div).focus();
		}
	},
	
  //This is the same as "camViewerReStart"
  //Except it does not attempt to destroy the viewer
  //Before it Starts
  showLiveViewer: function() {
    videoplayer.videoHidden = 0;
    try {
    if (swfobject != undefined) {
      //document.getElementById(videoplayer.div).showLiveViewer();
      videoplayer.setVIEWControls();
			$(".show_hostcam").hide(100);
      $(".hide_hostcam").show(100);
      if (qanda != undefined) {
        qanda.qanda_showing = true;
      }
    }
    } catch (e) {}
  },
  
  //This is the same as "showLiveViewer"
  //Except it tries to destroy the Viewer
  //Before it Starts
  camViewerReStart: function() {
    videoplayer.videoHidden = 0;
    //console.log("Video Player Cam Viewer Click");
    swfobject.removeSWF('myViewer');
    //Removing object removes control div
    $(".qanda_box").append('<div id="host_stream" class="nx_widget_player"></div>');
		videoplayer.setVIEWControls();
    //document.getElementById(videoplayer.div).camViewerStart();
    if (qanda != undefined) {
      qanda.qanda_showing = true;
    }
  },

  hideLiveViewer: function() {
    videoplayer.videoHidden++;
    if (videoplayer.videoHidden > 5) {
      return;
    }
    try {
    if (swfobject != undefined) {
      swfobject.removeSWF('myViewer');
    	$(".qanda_box").append('<div id="host_stream" class="nx_widget_player"></div>');
			//document.getElementById(videoplayer.div).hideLiveViewer( null, false );
      $(".hide_hostcam").hide(100);
      $(".show_hostcam").show(100);
      if (qanda != undefined) {
        qanda.qanda_showing = false;
      }
    }
    } catch (e) {}
  },
  
  setVIEWControls: function() {
			
			//console.log("Setting View Control");
			
			var args={"k":$("#video_data").html()};
      //videoplayer.div = 'myViewer';
      
      var flashvars = 
        {src: videoplayer.file,
        debugConsole: 'false',
		    ticketParams:$("#video_data").html(),
				streamName: $("#room").html() + "_stream",
				//auth:response.tokenResponse.token,
				slist:$("#film").html(),
        sip: $("#host").html()
    		};
    		
      var params = 
        {
          allowFullScreen: 'true',
          allowScriptAccess: 'always',
          wmode: 'opaque',
					bgcolor:"#000000"
        };
      var attributes = 
        {
          id: 'myViewer',
          name: 'myViewer'
        };
      
      //console.log('Loading '+'/flash/LiveStream.swf');
      swfobject.embedSWF('/flash/LiveStream.swf', 'host_stream', '290', '220', '10.1.0', '/flash/expressInstall.swf', flashvars, params, attributes);
      
	},
	
  unsetSoundControl: function() {
    $( "#slider" ).slider( "destroy" )
  },
  
  setSoundControl: function() {
    $( "#slider" ).slider({
			value: videoplayer.volume,
      max: 10,
			min: 0,
			step: 1,
			slide: function( event, ui ) {
			 videoplayer.volume = ui.value;
       if ((document.getElementById(videoplayer.div) != undefined)  && (videoplayer.state == 'playing')) {
			   document.getElementById(videoplayer.div).setVolume(ui.value);
			 }
				//$( "#amount" ).val( "$" + ui.value );
			}
		});
	
  },
  
  fullscreen: function() {
    //console.log("Going FullScreen");
    if ((document.getElementById(videoplayer.div) != undefined)  && (videoplayer.state == 'playing')) {
			document.getElementById(videoplayer.div).goFullscreen();
		}
  },
  
  //This comes from the Flash Interface, and is between 0 and 1
  //So we multiply by 10
  setVolume: function( volume ) {
    $( "#slider" ).slider( "value" , (volume * 10) );
  }
	
}

function setCurrentTime(val) {
	$("#seektime").html(Math.round(val));
	//console.log("Current Time is "+ val);
}

$(document).ready(function(){
	if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
 
  videoplayer.init();
  
  if ($("#video-type").html() == "TRAILER") {
    videoplayer.initPlayer();
  }
  
  //$("#status_panel").click(function() {
  //  videoplayer.message("notice","Some Friggin Notice","Some Friggin Message");
  //});
});
// JavaScript Document
var colorme = {

	user: null,
	post: null,
	posl: null,
	icon: null,
	userElement: null,
	animating: false,
	status: null,
/*lpos: 0,
	rpos: null,
	tpos: 0,
	bpos: null,*/
	imagesize: null,
	imagesmall: 28,
	imagelarge: 50,
	scrollsize: 64,
	currentwidth: 0,
	COLORMES: null,

	isSmall: false,
	userOffset: 0,
	usersCollection: {},
	isUpdated: false,

	init: function() {
		colorme.COLORMES = [];
		colorme.imagesize = colorme.imagelarge;

		colorme.screeningId = $("#screening_id").html()

		this.attachPoints();
		this.attachEvents();
		colorme.attachTip();
		if (colorme.user != 0) {
			colorme.attachColorMeIcons();
		}

		colorme.shortBox();
		colorme.user = $("#userid").html();

		colorme.currentwidth = ($(window).width() - 440);
		$(".userblock").width(colorme.currentwidth);
		colorme.updatePagination();

		// colorme.getCurrentUsers();
		// colorme.getCurrentUsers(true);
	},
	attachPoints: function() {
		colorme.nextButton = $("#user_wrap_right");
		colorme.previousButton = $("#user_wrap_left");
		colorme.theaterIconContainer = $("#theater_icons");
		colorme.inboxContainer = $('#inbox ');
	},
	attachEvents: function() {

		colorme.nextButton.bind('click', colorme.nextClick);
		colorme.previousButton.bind('click', colorme.previousClick);

		$(window).bind('resize', colorme.resize);

	},
	attachColorMeIcons: function() {
		$(".color_icon").bind('click', colorme.onColorMeClick);
	},
	onColorMeClick: function(event, e) {
		colorme.icon = $(this).attr("class").replace("color_icon ", "");
		//if (colorme.status != colorme.icon)
		colorme.animateIcon(colorme.user, colorme.icon);
		colorme.status = colorme.icon;
		var args = {
			body: "colorme:" + colorme.icon
		};
		$.postJSON("/services/chat/post", args, colorme.finishPost);
	},
	attachTip: function() {
		colorme.toolTip = $('<div class="tooltip theater-user-tip theater-user-tip-footer"></div>');
		$('#footer').append(colorme.toolTip);

		$('#footer .colorme_user').live('mouseover', colorme.onUserMouseover);

	},
	onUserMouseover: function() {
		if (colorme.toolTipTarget) {
			colorme.toolTipTarget.unbind('mouseleave');
			colorme.toolTip.clearQueue();
		}

		colorme.toolTipTarget = $(this);
		var position = colorme.toolTipTarget.parents('.theater_icon').position();
		colorme.toolTipTarget.bind('mouseleave', colorme.onUserMouseleave);

		colorme.toolTip.stop().css({
			'top': position.top - 60,
			'left': position.left + 10 + parseInt(colorme.theaterIconContainer.css('left')),
			'display': 'block'
		}).animate({
			opacity: 1
		}, 100).html(colorme.toolTipTarget.attr('alt'));
	},
	onUserMouseleave: function() {
		colorme.toolTip.animate({
			opacity: 0
		}, 100, function() {
			colorme.toolTip.css({
				'display': 'none'
			})
		});
	},

	getCurrentUsers: function(init) {
		if (colorme.getCurrentUserTimer) {
			window.clearTimeout(colorme.getCurrentUserTimer);
		}

		var records, offset

		if (colorme.isSmall) {
			offset = colorme.userOffset * 2;
			records = Math.ceil(colorme.currentwidth / 16);
			if (records % 2 != 0) records++;
		} else {
			offset = colorme.userOffset;
			records = Math.ceil(colorme.currentwidth / 64);
		}


		$.ajax({
			url: '/services/Screenings/colorme?screening=' + colorme.screeningId + '&records=' + records + '&offset=' + offset,
			type: "GET",
			cache: false,
			dataType: "json",
			timeout: 3000,
			success: colorme.onGetCurrentUsersSuccess,
			error: function() {
				colorme.isUpdated = true;
			}
		});
		colorme.getCurrentUserTimer = window.setTimeout(colorme.getCurrentUsers, 10000);

	},
	onGetCurrentUsersSuccess: function(response) {

		if ((typeof response == 'object') && response.users) {

			if(response.totalresults != colorme.totalresults ){
				colorme.usersCollection = {};
				colorme.theaterIconContainer.empty();
			} 

			colorme.totalresults = response.totalresults;
			colorme.isSmall = response.totalresults > 50;

			var isOdd = true;
			var tempIds = [];
			for (var i = 0; i < response.users.length; i++) {
				// if ($("#user_wrap_" + response.users[i].userid).length == 0) {
				if(!colorme.usersCollection['user_' + response.users[i].userid]){
					var cssOptions = {
						opacity: 0
					};
					if (colorme.isSmall) {
						cssOptions.top = isOdd ? 0 : 32;
						cssOptions.left = isOdd ? (i + (colorme.userOffset * 2)) * 16 : (i - 1 + (colorme.userOffset * 2)) * 16;
					} else {
						cssOptions.top = 0;
						cssOptions.left = (i + colorme.userOffset) * 64;
					}

					var userDomnode = $('<div class="theater_icon" id="user_wrap_' + response.users[i].userid + '"><a href="/profile/' + response.users[i].userid + '" target="_blank"><img class="colorme_user" id="user_image_' + response.users[i].userid + '" src="' + response.users[i].image + '" alt="' + response.users[i].username + '" width="' + colorme.imagesize + '" /></a></div>').css(cssOptions).appendTo(colorme.theaterIconContainer);
					userDomnode.animate({
						opacity: 1
					}, 300);
					colorme.usersCollection['user_' + response.users[i].userid] = userDomnode;

					isOdd = !isOdd;
				}
				tempIds.push('user_' + response.users[i].userid);
			}


			for (var i in colorme.usersCollection) {
				if (_.indexOf(tempIds, i) == -1) {
					colorme.usersCollection[i].remove();
					delete colorme.usersCollection[i];
				}
			}


			if (colorme.isSmall) {
				var width = colorme.totalresults / 2 * (colorme.imagesize + 4);
				colorme.theaterIconContainer.css('width', width + 'px');
			} else {
				var width = colorme.totalresults * (colorme.imagesize + 14);
				colorme.theaterIconContainer.css('width', width + 'px');
			}

			colorme.boxSwitch();
			// colorme.resize();
			colorme.drawColorMes();
			$(".userblock").fadeIn(100);

			colorme.updatePagination();

		} else if ((typeof response == 'object') && response.users == null) {
			colorme.reset();
			colorme.getCurrentUsers();
		}
		colorme.isUpdated = true;

	},

	nextClick: function() {
		if (colorme.isUpdated) {
			colorme.isUpdated = false;
			colorme.userOffset++;
			colorme.animateIconContainer();
		}
	},
	previousClick: function() {
		if (colorme.userOffset != 0 && colorme.isUpdated) {
			colorme.isUpdated = false;
			colorme.userOffset--;
			colorme.animateIconContainer();
		}
	},
	animateIconContainer: function() {
		colorme.theaterIconContainer.animate({
			left: -1 * (colorme.userOffset * (colorme.isSmall ? 32 : 64))
		}, 100, function() {
			colorme.getCurrentUsers();
			// colorme.updatePagination();
		});
	},
	updatePagination: function() {
		if (colorme.userOffset == 0) {
			colorme.previousButton.fadeOut();
		} else {
			colorme.previousButton.fadeIn();
		}

		var maxOffset = Math.floor(colorme.currentwidth / (colorme.isSmall ? 16 : 64)) + (colorme.userOffset * (colorme.isSmall ? 2 : 1));

		if ((maxOffset >= colorme.totalresults) || Math.floor(colorme.currentwidth / (colorme.isSmall ? 16 : 64)) > colorme.totalresults) {
			colorme.nextButton.fadeOut();
		} else {
			colorme.nextButton.fadeIn();
		}
	},

	initColors: function(users) {
		if (users != undefined) {
			usobj = users.split(",");
			for (i = 0; i < usobj.length; i++) {
				user = usobj[i].split("|");
				if ((user[0] != undefined) && (user[1] != undefined)) {
					colorme.COLORMES[user[0]] = user[1].replace("colorme:", "");
				}
			}
			colorme.drawColorMes();
		}
	},

	drawColorMes: function() {
		for (key in colorme.COLORMES) {
			if (key != '') {
				colorme.setColor(key, colorme.COLORMES[key]);
			}
		}
	},

	animateIcon: function(user, icon) {
		if (colorme.animating == false) {
			colorme.animating = true;

			$("#color_node_" + user).remove();
			nclass = colorme.setColor(user, icon);
			colorme.COLORMES[user] = icon;

			var imageNode = $('<img id="color_node_' + user + '" class="' + nclass + '" src="/images/alt1/' + icon + '_icon.png" />')
				.appendTo($("#user_wrap_" + user, colorme.theaterIconContainer))

			imageNode.animate({
				opacity: 0,
				top: '-=50'
			}, 1500, function() {
				imageNode.remove();
			});
			colorme.animating = false;
		}
	},

	setColor: function(user, icon) {

		$("#user_wrap_" + user, colorme.theaterIconContainer).removeClass('happy sad wow none heart quest').addClass(icon);

		$(".chat_icon_user_" + user, colorme.inboxContainer).removeClass('happy_med sad_med wow_med none_med heart_med quest_med').addClass(icon + '_med');

		var nclass = "color_node";
		if (colorme.imagesize == colorme.imagesmall) {
			nclass = "color_node_small";
		}
		return nclass;

	},

	resize: function() {
		if (colorme.getCurrentUserTimer) {
			window.clearTimeout(colorme.getCurrentUserTimer);
		}
		if (!colorme.previousButton) {
			colorme.attachPoints()
		}

		colorme.currentwidth = ($(window).width() - 440);
		$(".userblock").width(colorme.currentwidth);
		colorme.updatePagination();

		colorme.getCurrentUserTimer = window.setTimeout(colorme.getCurrentUsers, 200);

	},


	//Decides when to switch from LARGE to SMALL icons
	boxSwitch: function() {
		if (colorme.totalresults > 50 && colorme.imagesize != colorme.imagesmall) {
			colorme.tallBox();
		} else if (colorme.totalresults < 50 && colorme.imagesize == colorme.imagesmall) {
			colorme.shortBox();
		}
	},

	//Make the chat icons small
	tallBox: function() {

		if (!colorme.theaterIconContainer) colorme.attachPoints()


		colorme.theaterIconContainer.css("left", "0px").removeClass('large-icons').addClass('small-icons');
		colorme.imagesize = colorme.imagesmall;
		colorme.scrollsize = colorme.imagesmall + 4;

		colorme.reset();
		colorme.getCurrentUsers();

	},

	//Make the chat icons big
	shortBox: function() {

		if (!colorme.theaterIconContainer) colorme.attachPoints()

		colorme.theaterIconContainer.css({
			"top": "0px",
			"left": "0px"
		}).removeClass('small-icons').addClass('large-icons');

		colorme.imagesize = colorme.imagelarge;
		colorme.scrollsize = colorme.imagelarge + 14;
		colorme.reset();

		colorme.getCurrentUsers();
		// colorme.resize();
	},
	reset: function() {
		colorme.userOffset = 0;
		colorme.usersCollection = {};
		colorme.theaterIconContainer.empty();
	},
/*
setChatSize: function() {


	$(".footer").css("height", "122px");
	$(".bottomblock").css("height", "122px");
	$(".colorblock").css("top", "70px");
	$(".userblock").css("height", "68px");

},
*/

	finishPost: function(response) {
		//console.log("Finished");
	},

	incomingMessage: function(message) {
		regexp = new RegExp("colorme:(.+)\</p\>");
		iconObj = regexp.exec(message.html);
		colorme.animateIcon(message.author, iconObj[1]);
	}

}

$(document).ready(function() {
	if (!window.console) window.console = {};
	if (!window.console.log) window.console.log = function() {};

	colorme.init();

});function newMessage(form) {
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
// JavaScript Document

var HUD = {
	
	errorSleepTime: 5000,
	
	init: function() {
	
		$(".show_hud").fadeIn();
    $(".show_log").fadeIn();    
		
    $("#hud_save").click(function(e){
			e.preventDefault();
			HUD.set();
		});
		
		$(".applyButton").click(function(e){
			e.preventDefault();
			HUD.setDynamicValue($(this));
		});
		
		$(".hud-click").click(function(e){
			$("#hud-popup").fadeIn();
      $("#video_stream").width("320px");
  		$("#video_stream").height("240px");
			$(".show_hud").fadeOut();
		});
		
		$(".hud_close").click(function(e){
			$("#hud-popup").fadeOut();
      $("#video_stream").width("100%");
  		$("#video_stream").height("100%");
      //May need to add this back in...
      //$("#video_stream").css("margin-right","300px");
			$(".show_hud").fadeIn();
		});
		
		$("#hud_reload").click(function(e){
			e.preventDefault();
			//console.log("Restarting Host Viewer");
			videoplayer.camViewerReStart();
			var args = {body:"hostset:reload|hostval:null"};
			$.postJSON("/services/chat/post", args, HUD.finishPost );
		});
		
		$("#qualityLevel_display").html($("#qualityLevel").val());
		$("#qualityLevel_slider" ).slider({
											value:$("#qualityLevel").val(),
											min: 0,
											max: 100,
											step: 1,
											slide: function( event, ui ) {
											//videoplayer.volume = ui.value;
												$("#qualityLevel").val(ui.value);
												$("#qualityLevel_display").html(ui.value);
											}
										});
		
		
		$("#keyFrameInterval_display").html($("#keyFrameInterval").val());		
		$("#keyFrameInterval_slider" ).slider({
											value:$("#keyFrameInterval").val(),
											min: 0,
											max: 48,
											step: 1,
											slide: function( event, ui ) {
											//videoplayer.volume = ui.value;
												$("#keyFrameInterval").val(ui.value);
												$("#keyFrameInterval_display").html(ui.value);
											}
										});
										
		$("#captureFps_display").html($("#captureFps").val());
		$("#captureFps_slider" ).slider({
											value:$("#captureFps").val(),
											min: 0,
											max: 120,
											step: 1,
											slide: function( event, ui ) {
											//videoplayer.volume = ui.value;
												$("#captureFps").val(ui.value);
												$("#captureFps_display").html(ui.value);
											}
										});
		
		$("#bufferMin_display").html($("#bufferMin").val());
		$("#bufferMin_slider" ).slider({
											value:$("#bufferMin").val(),
											min: 0,
											max: 120,
											step: 1,
											slide: function( event, ui ) {
											//videoplayer.volume = ui.value;
												$("#bufferMin").val(ui.value);
												$("#bufferMin_display").html(ui.value);
											}
										});
		
		$("#bufferMax_display").html($("#bufferMax").val());
		$("#bufferMax_slider" ).slider({
											value:$("#bufferMax").val(),
											min: 0,
											max: 240,
											step: 1,
											slide: function( event, ui ) {
											//videoplayer.volume = ui.value;
												$("#bufferMax").val(ui.value);
												$("#bufferMax_display").html(ui.value);
											}
										});
										
		$("#micRate_display").html($("#micRate").val());
		$("#micRate_slider" ).slider({
											value:$("#micRate").val(),
											min: 1,
											max: 5,
											step: 1,
											slide: function( event, ui ) {
											//videoplayer.volume = ui.value;
												switch (ui.value) {
													case 1:
														var thevla = 5;
														break;
													case 2:
														var thevla = 8;
														break;
													case 3:
														var thevla = 11;
														break;
													case 4:
														var thevla = 22;
														break;
													case 5:
														var thevla = 44;
														break;
												}
												$("#micRate").val(thevla);
												$("#micRate_display").html(thevla);
											}
										});
										
		$("#micGain_display").html($("#micGain").val());
		$("#micGain_slider" ).slider({
											value:$("#micGain").val(),
											min: 0,
											max: 100,
											step: 1,
											slide: function( event, ui ) {
											//videoplayer.volume = ui.value;
												$("#micGain").val(ui.value);
												$("#micGain_display").html(ui.value);
											}
										});
										
		$("#motionTimeout_display").html($("#motionTimeout").val());
		$("#motionTimeout_slider" ).slider({
											value:$("#motionTimeout").val(),
											min: 0,
											max: 120000,
											step: 1000,
											slide: function( event, ui ) {
											//videoplayer.volume = ui.value;
												$("#motionTimeout").val(ui.value);
												$("#motionTimeout_display").html(ui.value);
											}
										});
										
		$("#silenceLevel_display").html($("#silenceLevel").val());
		$("#silenceLevel_slider" ).slider({
											value:$("#silenceLevel").val(),
											min: 0,
											max: 100,
											step: 1,
											slide: function( event, ui ) {
											//videoplayer.volume = ui.value;
												$("#silenceLevel").val(ui.value);
												$("#silenceLevel_display").html(ui.value);
											}
										});
										
		$("#micSilenceTimeout_display").html($("#micSilenceTimeout").val());
		$("#micSilenceTimeout_slider" ).slider({
											value:$("#micSilenceTimeout").val(),
											min: 0,
											max: 120000,
											step: 1000,
											slide: function( event, ui ) {
											//videoplayer.volume = ui.value;
												$("#micSilenceTimeout").val(ui.value);
												$("#micSilenceTimeout_display").html(ui.value);
											}
										});
										
	},
	
	get: function() {
		var args = {"host":$("#hostid").html()};
		$.ajax({url: "/services/HUD/get", 
              type: "GET", 
              cache: false, 
              dataType: "json",
              data: $.param(args),
              timeout: HUD.errorSleepTime,
              success: HUD.setDefaults,
              error: HUD.onError});
	},
	
	setDefaults: function( response ) {
		for (var key in response.hudSettings) {
			//console.log("Setting "+key+" to "+response.hudSettings[key]);
			switch (key) {
				case "bandwidthLimit":
					$("#bandwidthLimit").val(response.hudSettings[key]);
					break;
				case "echoSuppression":
					if (response.hudSettings[key] == "true") {
						$("#echoSuppression_on").attr("checked","checked");
					} else {
						$("#echoSuppression_off").attr("checked","checked");
					}
					break;
				case "enhancedMicrophone":
					if (response.hudSettings[key] == "true") {
						$("#enhancedMicrophone_on").attr("checked","checked");
					} else {
						$("#enhancedMicrophone_off").attr("checked","checked");
					}
					break;
				case "enableVAD":
					if (response.hudSettings[key] == "true") {
						$("#enableVAD_on").attr("checked","checked");
					} else {
						$("#enableVAD_off").attr("checked","checked");
					}
					break;
				default:
					$("#"+key+"_display").html(response.hudSettings[key]);
					$("#"+key+"_slider" ).slider({"value":response.hudSettings[key]});
					break;
			}
	  }
		
		
	},
	
	set: function() {
		
    var settings = $("#hud_settings").formToDict();
		var args = {"host":$("#hostid").html(),
								"settings":settings};
		$.ajax({url: "/services/HUD/set", 
              type: "GET", 
              cache: false, 
              dataType: "text",
              data: $.param(args),
              timeout: HUD.errorSleepTime,
              success: HUD.onSuccess,
              error: HUD.onError});
	},
	
	onSuccess: function(response) {
		$("#hud_message").fadeIn();
		$("#hud_message").html("Success!").delay(3000).fadeOut();
	},
	
	onError: function(error) {
		$("#hud_message").fadeIn();
		$("#hud_message").html("There was an error.").delay(3000).fadeOut();
	},
	
	setDynamicValue: function( element ) {
		var elem = $(element).attr("id").replace("Submit","");
		var funcname ='host_update_'+elem;
		switch(funcname){
			case "host_update_bandwidthLimit":
				value = $("#"+elem).val();
			break;
			case "host_update_qualityLevel":
				value = $("#"+elem).val();
			break;
			case "host_update_keyFrameInterval":
				value = $("#"+elem).val();
			break;
			case "host_update_captureFps":
				value = $("#"+elem).val();
			break;
			case "host_update_bufferMin":
				value = $("#"+elem).val();
			break;
			case "host_update_bufferMax":
				value = $("#"+elem).val();
			break;
			case "host_update_micGain":
				value = $("#"+elem).val();
			break;
			case "host_update_motionTimeout":
				value = $("#"+elem).val();
			break;
			case "host_update_echoSuppression":
				if ($('#echoSuppression_on:checked').val() == 1) {
					value = "true";
				} else {
					value = "false";
				}
			break;
			case "host_update_enhancedMicrophone":
				if ($('#echoSuppression_on:checked').val() == "true") {
					value = "true";
				} else {
					value = "false";
				}
			break;
			case "host_update_silenceLevel":
				value = $("#"+elem).val();
			break;
			case "host_update_micSilenceTimeout":
				value = $("#"+elem).val();
			break;
			case "host_update_enableVAD":
				if ($('#enableVAD_on:checked').val() == "true") {
					value = "true";
				} else {
					value = "false";
				}
			break;
		}
		//console.log(funcname+" "+value);
		if (document.getElementById('myHost') != undefined) {
			//console.log("HOST!");
			HUD.pushDynamicValue( funcname, value );
		} else {
			//console.log("USER!");
			HUD.callDynamicFunction( funcname, value );
		}
	},
	
	pushDynamicValue: function( funcname, value ) {
		switch(funcname){
			case "host_update_bandwidthLimit":
		   	$("#bandwidthLimit").val(value);
				document.getElementById('myHost').host_update_bandwidthLimit(value);
			break;
			case "host_update_qualityLevel":
		   	$("#qualityLevel_display").html(value);
				$("#qualityLevel_slider" ).slider({"value":value});
				document.getElementById('myHost').host_update_qualityLevel(value);
			break;
			case "host_update_keyFrameInterval":
		   	$("#keyFrameInterval_display").html(value);
				$("#keyFrameInterval_slider" ).slider({"value":value});
				document.getElementById('myHost').host_update_keyFrameInterval(value);
			break;
			case "host_update_captureFps":
		   	$("#captureFps_display").html(value);
				$("#captureFps_slider" ).slider({"value":value});
				document.getElementById('myHost').host_update_captureFPS(value);
			break;
			case "host_update_bufferMin":
		   	$("#bufferMin_display").html(value);
				$("#bufferMin_slider" ).slider({"value":value});
				document.getElementById('myHost').host_update_bufferMin(value);
			break;
			case "host_update_bufferMax":
		   	$("#bufferMax_display").html(value);
				$("#bufferMax_slider" ).slider({"value":value});
				document.getElementById('myHost').host_update_bufferMax(value);
			break;
			case "host_update_micGain":
		   	$("#micGain_display").html(value);
				$("#micGain_slider" ).slider({"value":value});
				document.getElementById('myHost').host_update_micGain(value);
			break;
			case "host_update_motionTimeout":
		   	$("#motionTimeout_display").html(value);
				$("#motionTimeout_slider" ).slider({"value":value});
				document.getElementById('myHost').host_update_motionTimeout(value);
			break;
			case "host_update_echoSuppression":
				if (value == "true") {
					$("#echoSuppression_on").attr("checked","checked");
				} else {
					$("#echoSuppression_off").attr("checked","checked");
				}
				document.getElementById('myHost').host_update_echoSuppression(value);
			break;
			case "host_update_enhancedMicrophone":
		   	if (value == "true") {
					$("#enhancedMicrophone_on").attr("checked","checked");
				} else {
					$("#enhancedMicrophone_off").attr("checked","checked");
				}
				document.getElementById('myHost').host_update_enhancedMicrophone(value);
			break;
			case "host_update_silenceLevel":
		   	$("#silenceLevel_display").html(value);
				$("#silenceLevel_slider" ).slider({"value":value});
				document.getElementById('myHost').host_update_silenceLevel(value);
			break;
			case "host_update_micSilenceTimeout":
		   	$("#micSilenceTimeout_display").html(value);
				$("#micSilenceTimeout_slider" ).slider({"value":value});
				document.getElementById('myHost').host_update_micSilenceTimeout(value);
			break;
			case "host_update_enableVAD":
		   	if (value == "true") {
					$("#enableVAD_on").attr("checked","checked");
				} else {
					$("#enableVAD_off").attr("checked","checked");
				}
				document.getElementById('myHost').host_update_enableVAD(value);
			break;
		}
	},
	
	callDynamicFunction: function( name, value ) {
		var args = {body:"hostset:"+name+"|hostval:"+value};
		$.postJSON("/services/chat/post", args, HUD.finishPost );
	},
	
	incomingMessage: function( message ) {
		vals = message.html.split("|");
		var name=vals[0].split(":");
		var value=vals[1].split(":");
		console.log(name[1]+" is set to "+ value[1]);
		if (name[1]=="reload") {
			console.log("restarting");
			videoplayer.camHostReStart();
		} else {
			console.log("setting new value of "+name[1]+" " + value[1]);
			if (document.getElementById('myHost') != undefined) {
				HUD.pushDynamicValue(name[1],value[1]);
			}
		}
	},
	
	finishPost: function( response ) {
		//console.log("Finished");
	}
	
}

$(document).ready(function() {
    if (!window.console) window.console = {};
    if (!window.console.log) window.console.log = function() {};
    
    if ($("#hud-popup").length == 1) {
	    HUD.init();
	    HUD.get();
	  }
});
/* http://keith-wood.name/countdown.html
   Countdown for jQuery v1.5.8.
   Written by Keith Wood (kbwood{at}iinet.com.au) January 2008.
   Dual licensed under the GPL (http://dev.jquery.com/browser/trunk/jquery/GPL-LICENSE.txt) and 
   MIT (http://dev.jquery.com/browser/trunk/jquery/MIT-LICENSE.txt) licenses. 
   Please attribute the author if you use it. */

/* Display a countdown timer.
   Attach it with options like:
   $('div selector').countdown(
       {until: new Date(2009, 1 - 1, 1, 0, 0, 0), onExpiry: happyNewYear}); */

(function($) { // Hide scope, no $ conflict

/* Countdown manager. */
function Countdown() {
	this.regional = []; // Available regional settings, indexed by language code
	this.regional[''] = { // Default regional settings
		// The display texts for the counters
		labels: ['Years', 'Months', 'Weeks', 'Days', 'Hours', 'Minutes', 'Seconds'],
		// The display texts for the counters if only one
		labels1: ['Year', 'Month', 'Week', 'Day', 'Hour', 'Minute', 'Second'],
		compactLabels: ['y', 'm', 'w', 'd'], // The compact texts for the counters
		whichLabels: null, // Function to determine which labels to use
		timeSeparator: ':', // Separator for time periods
		isRTL: false // True for right-to-left languages, false for left-to-right
	};
	this._defaults = {
		until: null, // new Date(year, mth - 1, day, hr, min, sec) - date/time to count down to
			// or numeric for seconds offset, or string for unit offset(s):
			// 'Y' years, 'O' months, 'W' weeks, 'D' days, 'H' hours, 'M' minutes, 'S' seconds
		since: null, // new Date(year, mth - 1, day, hr, min, sec) - date/time to count up from
			// or numeric for seconds offset, or string for unit offset(s):
			// 'Y' years, 'O' months, 'W' weeks, 'D' days, 'H' hours, 'M' minutes, 'S' seconds
		timezone: null, // The timezone (hours or minutes from GMT) for the target times,
			// or null for client local
		serverSync: null, // A function to retrieve the current server time for synchronisation
		format: 'dHMS', // Format for display - upper case for always, lower case only if non-zero,
			// 'Y' years, 'O' months, 'W' weeks, 'D' days, 'H' hours, 'M' minutes, 'S' seconds
		layout: '', // Build your own layout for the countdown
		compact: false, // True to display in a compact format, false for an expanded one
		significant: 0, // The number of periods with values to show, zero for all
		description: '', // The description displayed for the countdown
		expiryUrl: '', // A URL to load upon expiry, replacing the current page
		expiryText: '', // Text to display upon expiry, replacing the countdown
		alwaysExpire: false, // True to trigger onExpiry even if never counted down
		onExpiry: null, // Callback when the countdown expires -
			// receives no parameters and 'this' is the containing division
		onTick: null, // Callback when the countdown is updated -
			// receives int[7] being the breakdown by period (based on format)
			// and 'this' is the containing division
		tickInterval: 1 // Interval (seconds) between onTick callbacks
	};
	$.extend(this._defaults, this.regional['']);
	this._serverSyncs = [];
}

var PROP_NAME = 'countdown';

var Y = 0; // Years
var O = 1; // Months
var W = 2; // Weeks
var D = 3; // Days
var H = 4; // Hours
var M = 5; // Minutes
var S = 6; // Seconds

$.extend(Countdown.prototype, {
	/* Class name added to elements to indicate already configured with countdown. */
	markerClassName: 'hasCountdown',
	
	/* Shared timer for all countdowns. */
	_timer: setInterval(function() { $.countdown._updateTargets(); }, 980),
	/* List of currently active countdown targets. */
	_timerTargets: [],
	
	/* Override the default settings for all instances of the countdown widget.
	   @param  options  (object) the new settings to use as defaults */
	setDefaults: function(options) {
		this._resetExtraLabels(this._defaults, options);
		extendRemove(this._defaults, options || {});
	},

	/* Convert a date/time to UTC.
	   @param  tz     (number) the hour or minute offset from GMT, e.g. +9, -360
	   @param  year   (Date) the date/time in that timezone or
	                  (number) the year in that timezone
	   @param  month  (number, optional) the month (0 - 11) (omit if year is a Date)
	   @param  day    (number, optional) the day (omit if year is a Date)
	   @param  hours  (number, optional) the hour (omit if year is a Date)
	   @param  mins   (number, optional) the minute (omit if year is a Date)
	   @param  secs   (number, optional) the second (omit if year is a Date)
	   @param  ms     (number, optional) the millisecond (omit if year is a Date)
	   @return  (Date) the equivalent UTC date/time */
	UTCDate: function(tz, year, month, day, hours, mins, secs, ms) {
		if (typeof year == 'object' && year.constructor == Date) {
			ms = year.getMilliseconds();
			secs = year.getSeconds();
			mins = year.getMinutes();
			hours = year.getHours();
			day = year.getDate();
			month = year.getMonth();
			year = year.getFullYear();
		}
		var d = new Date();
		d.setUTCFullYear(year);
		d.setUTCDate(1);
		d.setUTCMonth(month || 0);
		d.setUTCDate(day || 1);
		d.setUTCHours(hours || 0);
		d.setUTCMinutes((mins || 0) - (Math.abs(tz) < 30 ? tz * 60 : tz));
		d.setUTCSeconds(secs || 0);
		d.setUTCMilliseconds(ms || 0);
		return d;
	},

	/* Convert a set of periods into seconds.
	   Averaged for months and years.
	   @param  periods  (number[7]) the periods per year/month/week/day/hour/minute/second
	   @return  (number) the corresponding number of seconds */
	periodsToSeconds: function(periods) {
		return periods[0] * 31557600 + periods[1] * 2629800 + periods[2] * 604800 +
			periods[3] * 86400 + periods[4] * 3600 + periods[5] * 60 + periods[6];
	},

	/* Retrieve one or more settings values.
	   @param  name  (string, optional) the name of the setting to retrieve
	                 or 'all' for all instance settings or omit for all default settings
	   @return  (any) the requested setting(s) */
	_settingsCountdown: function(target, name) {
		if (!name) {
			return $.countdown._defaults;
		}
		var inst = $.data(target, PROP_NAME);
		return (name == 'all' ? inst.options : inst.options[name]);
	},

	/* Attach the countdown widget to a div.
	   @param  target   (element) the containing division
	   @param  options  (object) the initial settings for the countdown */
	_attachCountdown: function(target, options) {
		var $target = $(target);
		if ($target.hasClass(this.markerClassName)) {
			return;
		}
		$target.addClass(this.markerClassName);
		var inst = {options: $.extend({}, options),
			_periods: [0, 0, 0, 0, 0, 0, 0]};
		$.data(target, PROP_NAME, inst);
		this._changeCountdown(target);
	},

	/* Add a target to the list of active ones.
	   @param  target  (element) the countdown target */
	_addTarget: function(target) {
		if (!this._hasTarget(target)) {
			this._timerTargets.push(target);
		}
	},

	/* See if a target is in the list of active ones.
	   @param  target  (element) the countdown target
	   @return  (boolean) true if present, false if not */
	_hasTarget: function(target) {
		return ($.inArray(target, this._timerTargets) > -1);
	},

	/* Remove a target from the list of active ones.
	   @param  target  (element) the countdown target */
	_removeTarget: function(target) {
		this._timerTargets = $.map(this._timerTargets,
			function(value) { return (value == target ? null : value); }); // delete entry
	},

	/* Update each active timer target. */
	_updateTargets: function() {
		for (var i = this._timerTargets.length - 1; i >= 0; i--) {
			this._updateCountdown(this._timerTargets[i]);
		}
	},

	/* Redisplay the countdown with an updated display.
	   @param  target  (jQuery) the containing division
	   @param  inst    (object) the current settings for this instance */
	_updateCountdown: function(target, inst) {
		var $target = $(target);
		inst = inst || $.data(target, PROP_NAME);
		if (!inst) {
			return;
		}
		$target.html(this._generateHTML(inst));
		$target[(this._get(inst, 'isRTL') ? 'add' : 'remove') + 'Class']('countdown_rtl');
		var onTick = this._get(inst, 'onTick');
		if (onTick) {
			var periods = inst._hold != 'lap' ? inst._periods :
				this._calculatePeriods(inst, inst._show, this._get(inst, 'significant'), new Date());
			var tickInterval = this._get(inst, 'tickInterval');
			if (tickInterval == 1 || this.periodsToSeconds(periods) % tickInterval == 0) {
				onTick.apply(target, [periods]);
			}
		}
		var expired = inst._hold != 'pause' &&
			(inst._since ? inst._now.getTime() < inst._since.getTime() :
			inst._now.getTime() >= inst._until.getTime());
		if (expired && !inst._expiring) {
			inst._expiring = true;
			if (this._hasTarget(target) || this._get(inst, 'alwaysExpire')) {
				this._removeTarget(target);
				var onExpiry = this._get(inst, 'onExpiry');
				if (onExpiry) {
					onExpiry.apply(target, []);
				}
				var expiryText = this._get(inst, 'expiryText');
				if (expiryText) {
					var layout = this._get(inst, 'layout');
					inst.options.layout = expiryText;
					this._updateCountdown(target, inst);
					inst.options.layout = layout;
				}
				var expiryUrl = this._get(inst, 'expiryUrl');
				if (expiryUrl) {
					window.location = expiryUrl;
				}
			}
			inst._expiring = false;
		}
		else if (inst._hold == 'pause') {
			this._removeTarget(target);
		}
		$.data(target, PROP_NAME, inst);
	},

	/* Reconfigure the settings for a countdown div.
	   @param  target   (element) the containing division
	   @param  options  (object) the new settings for the countdown or
	                    (string) an individual property name
	   @param  value    (any) the individual property value
	                    (omit if options is an object) */
	_changeCountdown: function(target, options, value) {
		options = options || {};
		if (typeof options == 'string') {
			var name = options;
			options = {};
			options[name] = value;
		}
		var inst = $.data(target, PROP_NAME);
		if (inst) {
			this._resetExtraLabels(inst.options, options);
			extendRemove(inst.options, options);
			this._adjustSettings(target, inst);
			$.data(target, PROP_NAME, inst);
			var now = new Date();
			if ((inst._since && inst._since < now) ||
					(inst._until && inst._until > now)) {
				this._addTarget(target);
			}
			this._updateCountdown(target, inst);
		}
	},

	/* Reset any extra labelsn and compactLabelsn entries if changing labels.
	   @param  base     (object) the options to be updated
	   @param  options  (object) the new option values */
	_resetExtraLabels: function(base, options) {
		var changingLabels = false;
		for (var n in options) {
			if (n != 'whichLabels' && n.match(/[Ll]abels/)) {
				changingLabels = true;
				break;
			}
		}
		if (changingLabels) {
			for (var n in base) { // Remove custom numbered labels
				if (n.match(/[Ll]abels[0-9]/)) {
					base[n] = null;
				}
			}
		}
	},
	
	/* Calculate interal settings for an instance.
	   @param  target  (element) the containing division
	   @param  inst    (object) the current settings for this instance */
	_adjustSettings: function(target, inst) {
		var now;
		var serverSync = this._get(inst, 'serverSync');
		var serverOffset = 0;
		var serverEntry = null;
		for (var i = 0; i < this._serverSyncs.length; i++) {
			if (this._serverSyncs[i][0] == serverSync) {
				serverEntry = this._serverSyncs[i][1];
				break;
			}
		}
		if (serverEntry != null) {
			serverOffset = (serverSync ? serverEntry : 0);
			now = new Date();
		}
		else {
			var serverResult = (serverSync ? serverSync.apply(target, []) : null);
			now = new Date();
			serverOffset = (serverResult ? now.getTime() - serverResult.getTime() : 0);
			this._serverSyncs.push([serverSync, serverOffset]);
		}
		var timezone = this._get(inst, 'timezone');
		timezone = (timezone == null ? -now.getTimezoneOffset() : timezone);
		inst._since = this._get(inst, 'since');
		if (inst._since != null) {
			inst._since = this.UTCDate(timezone, this._determineTime(inst._since, null));
			if (inst._since && serverOffset) {
				inst._since.setMilliseconds(inst._since.getMilliseconds() + serverOffset);
			}
		}
		inst._until = this.UTCDate(timezone, this._determineTime(this._get(inst, 'until'), now));
		if (serverOffset) {
			inst._until.setMilliseconds(inst._until.getMilliseconds() + serverOffset);
		}
		inst._show = this._determineShow(inst);
	},

	/* Remove the countdown widget from a div.
	   @param  target  (element) the containing division */
	_destroyCountdown: function(target) {
		var $target = $(target);
		if (!$target.hasClass(this.markerClassName)) {
			return;
		}
		this._removeTarget(target);
		$target.removeClass(this.markerClassName).empty();
		$.removeData(target, PROP_NAME);
	},

	/* Pause a countdown widget at the current time.
	   Stop it running but remember and display the current time.
	   @param  target  (element) the containing division */
	_pauseCountdown: function(target) {
		this._hold(target, 'pause');
	},

	/* Pause a countdown widget at the current time.
	   Stop the display but keep the countdown running.
	   @param  target  (element) the containing division */
	_lapCountdown: function(target) {
		this._hold(target, 'lap');
	},

	/* Resume a paused countdown widget.
	   @param  target  (element) the containing division */
	_resumeCountdown: function(target) {
		this._hold(target, null);
	},

	/* Pause or resume a countdown widget.
	   @param  target  (element) the containing division
	   @param  hold    (string) the new hold setting */
	_hold: function(target, hold) {
		var inst = $.data(target, PROP_NAME);
		if (inst) {
			if (inst._hold == 'pause' && !hold) {
				inst._periods = inst._savePeriods;
				var sign = (inst._since ? '-' : '+');
				inst[inst._since ? '_since' : '_until'] =
					this._determineTime(sign + inst._periods[0] + 'y' +
						sign + inst._periods[1] + 'o' + sign + inst._periods[2] + 'w' +
						sign + inst._periods[3] + 'd' + sign + inst._periods[4] + 'h' + 
						sign + inst._periods[5] + 'm' + sign + inst._periods[6] + 's');
				this._addTarget(target);
			}
			inst._hold = hold;
			inst._savePeriods = (hold == 'pause' ? inst._periods : null);
			$.data(target, PROP_NAME, inst);
			this._updateCountdown(target, inst);
		}
	},

	/* Return the current time periods.
	   @param  target  (element) the containing division
	   @return  (number[7]) the current periods for the countdown */
	_getTimesCountdown: function(target) {
		var inst = $.data(target, PROP_NAME);
		return (!inst ? null : (!inst._hold ? inst._periods :
			this._calculatePeriods(inst, inst._show, this._get(inst, 'significant'), new Date())));
	},

	/* Get a setting value, defaulting if necessary.
	   @param  inst  (object) the current settings for this instance
	   @param  name  (string) the name of the required setting
	   @return  (any) the setting's value or a default if not overridden */
	_get: function(inst, name) {
		return (inst.options[name] != null ?
			inst.options[name] : $.countdown._defaults[name]);
	},

	/* A time may be specified as an exact value or a relative one.
	   @param  setting      (string or number or Date) - the date/time value
	                        as a relative or absolute value
	   @param  defaultTime  (Date) the date/time to use if no other is supplied
	   @return  (Date) the corresponding date/time */
	_determineTime: function(setting, defaultTime) {
		var offsetNumeric = function(offset) { // e.g. +300, -2
			var time = new Date();
			time.setTime(time.getTime() + offset * 1000);
			return time;
		};
		var offsetString = function(offset) { // e.g. '+2d', '-4w', '+3h +30m'
			offset = offset.toLowerCase();
			var time = new Date();
			var year = time.getFullYear();
			var month = time.getMonth();
			var day = time.getDate();
			var hour = time.getHours();
			var minute = time.getMinutes();
			var second = time.getSeconds();
			var pattern = /([+-]?[0-9]+)\s*(s|m|h|d|w|o|y)?/g;
			var matches = pattern.exec(offset);
			while (matches) {
				switch (matches[2] || 's') {
					case 's': second += parseInt(matches[1], 10); break;
					case 'm': minute += parseInt(matches[1], 10); break;
					case 'h': hour += parseInt(matches[1], 10); break;
					case 'd': day += parseInt(matches[1], 10); break;
					case 'w': day += parseInt(matches[1], 10) * 7; break;
					case 'o':
						month += parseInt(matches[1], 10); 
						day = Math.min(day, $.countdown._getDaysInMonth(year, month));
						break;
					case 'y':
						year += parseInt(matches[1], 10);
						day = Math.min(day, $.countdown._getDaysInMonth(year, month));
						break;
				}
				matches = pattern.exec(offset);
			}
			return new Date(year, month, day, hour, minute, second, 0);
		};
		var time = (setting == null ? defaultTime :
			(typeof setting == 'string' ? offsetString(setting) :
			(typeof setting == 'number' ? offsetNumeric(setting) : setting)));
		if (time) time.setMilliseconds(0);
		return time;
	},

	/* Determine the number of days in a month.
	   @param  year   (number) the year
	   @param  month  (number) the month
	   @return  (number) the days in that month */
	_getDaysInMonth: function(year, month) {
		return 32 - new Date(year, month, 32).getDate();
	},

	/* Determine which set of labels should be used for an amount.
	   @param  num  (number) the amount to be displayed
	   @return  (number) the set of labels to be used for this amount */
	_normalLabels: function(num) {
		return num;
	},

	/* Generate the HTML to display the countdown widget.
	   @param  inst  (object) the current settings for this instance
	   @return  (string) the new HTML for the countdown display */
	_generateHTML: function(inst) {
		// Determine what to show
		var significant = this._get(inst, 'significant');
		inst._periods = (inst._hold ? inst._periods :
			this._calculatePeriods(inst, inst._show, significant, new Date()));
		// Show all 'asNeeded' after first non-zero value
		var shownNonZero = false;
		var showCount = 0;
		var sigCount = significant;
		var show = $.extend({}, inst._show);
		for (var period = Y; period <= S; period++) {
			shownNonZero |= (inst._show[period] == '?' && inst._periods[period] > 0);
			show[period] = (inst._show[period] == '?' && !shownNonZero ? null : inst._show[period]);
			showCount += (show[period] ? 1 : 0);
			sigCount -= (inst._periods[period] > 0 ? 1 : 0);
		}
		var showSignificant = [false, false, false, false, false, false, false];
		for (var period = S; period >= Y; period--) { // Determine significant periods
			if (inst._show[period]) {
				if (inst._periods[period]) {
					showSignificant[period] = true;
				}
				else {
					showSignificant[period] = sigCount > 0;
					sigCount--;
				}
			}
		}
		var compact = this._get(inst, 'compact');
		var layout = this._get(inst, 'layout');
		var labels = (compact ? this._get(inst, 'compactLabels') : this._get(inst, 'labels'));
		var whichLabels = this._get(inst, 'whichLabels') || this._normalLabels;
		var timeSeparator = this._get(inst, 'timeSeparator');
		var description = this._get(inst, 'description') || '';
		var showCompact = function(period) {
			var labelsNum = $.countdown._get(inst,
				'compactLabels' + whichLabels(inst._periods[period]));
			return (show[period] ? inst._periods[period] +
				(labelsNum ? labelsNum[period] : labels[period]) + ' ' : '');
		};
		var showFull = function(period) {
			var labelsNum = $.countdown._get(inst, 'labels' + whichLabels(inst._periods[period]));
			return ((!significant && show[period]) || (significant && showSignificant[period]) ?
				'<span class="countdown_section"><span class="countdown_amount">' +
				inst._periods[period] + '</span><br/>' +
				(labelsNum ? labelsNum[period] : labels[period]) + '</span>' : '');
		};
		return (layout ? this._buildLayout(inst, show, layout, compact, significant, showSignificant) :
			((compact ? // Compact version
			'<span class="countdown_row countdown_amount' +
			(inst._hold ? ' countdown_holding' : '') + '">' + 
			showCompact(Y) + showCompact(O) + showCompact(W) + showCompact(D) + 
			(show[H] ? this._minDigits(inst._periods[H], 2) : '') +
			(show[M] ? (show[H] ? timeSeparator : '') +
			this._minDigits(inst._periods[M], 2) : '') +
			(show[S] ? (show[H] || show[M] ? timeSeparator : '') +
			this._minDigits(inst._periods[S], 2) : '') :
			// Full version
			'<span class="countdown_row countdown_show' + (significant || showCount) +
			(inst._hold ? ' countdown_holding' : '') + '">' +
			showFull(Y) + showFull(O) + showFull(W) + showFull(D) +
			showFull(H) + showFull(M) + showFull(S)) + '</span>' +
			(description ? '<span class="countdown_row countdown_descr">' + description + '</span>' : '')));
	},

	/* Construct a custom layout.
	   @param  inst             (object) the current settings for this instance
	   @param  show             (string[7]) flags indicating which periods are requested
	   @param  layout           (string) the customised layout
	   @param  compact          (boolean) true if using compact labels
	   @param  significant      (number) the number of periods with values to show, zero for all
	   @param  showSignificant  (boolean[7]) other periods to show for significance
	   @return  (string) the custom HTML */
	_buildLayout: function(inst, show, layout, compact, significant, showSignificant) {
		var labels = this._get(inst, (compact ? 'compactLabels' : 'labels'));
		var whichLabels = this._get(inst, 'whichLabels') || this._normalLabels;
		var labelFor = function(index) {
			return ($.countdown._get(inst,
				(compact ? 'compactLabels' : 'labels') + whichLabels(inst._periods[index])) ||
				labels)[index];
		};
		var digit = function(value, position) {
			return Math.floor(value / position) % 10;
		};
		var subs = {desc: this._get(inst, 'description'), sep: this._get(inst, 'timeSeparator'),
			yl: labelFor(Y), yn: inst._periods[Y], ynn: this._minDigits(inst._periods[Y], 2),
			ynnn: this._minDigits(inst._periods[Y], 3), y1: digit(inst._periods[Y], 1),
			y10: digit(inst._periods[Y], 10), y100: digit(inst._periods[Y], 100),
			y1000: digit(inst._periods[Y], 1000),
			ol: labelFor(O), on: inst._periods[O], onn: this._minDigits(inst._periods[O], 2),
			onnn: this._minDigits(inst._periods[O], 3), o1: digit(inst._periods[O], 1),
			o10: digit(inst._periods[O], 10), o100: digit(inst._periods[O], 100),
			o1000: digit(inst._periods[O], 1000),
			wl: labelFor(W), wn: inst._periods[W], wnn: this._minDigits(inst._periods[W], 2),
			wnnn: this._minDigits(inst._periods[W], 3), w1: digit(inst._periods[W], 1),
			w10: digit(inst._periods[W], 10), w100: digit(inst._periods[W], 100),
			w1000: digit(inst._periods[W], 1000),
			dl: labelFor(D), dn: inst._periods[D], dnn: this._minDigits(inst._periods[D], 2),
			dnnn: this._minDigits(inst._periods[D], 3), d1: digit(inst._periods[D], 1),
			d10: digit(inst._periods[D], 10), d100: digit(inst._periods[D], 100),
			d1000: digit(inst._periods[D], 1000),
			hl: labelFor(H), hn: inst._periods[H], hnn: this._minDigits(inst._periods[H], 2),
			hnnn: this._minDigits(inst._periods[H], 3), h1: digit(inst._periods[H], 1),
			h10: digit(inst._periods[H], 10), h100: digit(inst._periods[H], 100),
			h1000: digit(inst._periods[H], 1000),
			ml: labelFor(M), mn: inst._periods[M], mnn: this._minDigits(inst._periods[M], 2),
			mnnn: this._minDigits(inst._periods[M], 3), m1: digit(inst._periods[M], 1),
			m10: digit(inst._periods[M], 10), m100: digit(inst._periods[M], 100),
			m1000: digit(inst._periods[M], 1000),
			sl: labelFor(S), sn: inst._periods[S], snn: this._minDigits(inst._periods[S], 2),
			snnn: this._minDigits(inst._periods[S], 3), s1: digit(inst._periods[S], 1),
			s10: digit(inst._periods[S], 10), s100: digit(inst._periods[S], 100),
			s1000: digit(inst._periods[S], 1000)};
		var html = layout;
		// Replace period containers: {p<}...{p>}
		for (var i = Y; i <= S; i++) {
			var period = 'yowdhms'.charAt(i);
			var re = new RegExp('\\{' + period + '<\\}(.*)\\{' + period + '>\\}', 'g');
			html = html.replace(re, ((!significant && show[i]) ||
				(significant && showSignificant[i]) ? '$1' : ''));
		}
		// Replace period values: {pn}
		$.each(subs, function(n, v) {
			var re = new RegExp('\\{' + n + '\\}', 'g');
			html = html.replace(re, v);
		});
		return html;
	},

	/* Ensure a numeric value has at least n digits for display.
	   @param  value  (number) the value to display
	   @param  len    (number) the minimum length
	   @return  (string) the display text */
	_minDigits: function(value, len) {
		value = '' + value;
		if (value.length >= len) {
			return value;
		}
		value = '0000000000' + value;
		return value.substr(value.length - len);
	},

	/* Translate the format into flags for each period.
	   @param  inst  (object) the current settings for this instance
	   @return  (string[7]) flags indicating which periods are requested (?) or
	            required (!) by year, month, week, day, hour, minute, second */
	_determineShow: function(inst) {
		var format = this._get(inst, 'format');
		var show = [];
		show[Y] = (format.match('y') ? '?' : (format.match('Y') ? '!' : null));
		show[O] = (format.match('o') ? '?' : (format.match('O') ? '!' : null));
		show[W] = (format.match('w') ? '?' : (format.match('W') ? '!' : null));
		show[D] = (format.match('d') ? '?' : (format.match('D') ? '!' : null));
		show[H] = (format.match('h') ? '?' : (format.match('H') ? '!' : null));
		show[M] = (format.match('m') ? '?' : (format.match('M') ? '!' : null));
		show[S] = (format.match('s') ? '?' : (format.match('S') ? '!' : null));
		return show;
	},
	
	/* Calculate the requested periods between now and the target time.
	   @param  inst         (object) the current settings for this instance
	   @param  show         (string[7]) flags indicating which periods are requested/required
	   @param  significant  (number) the number of periods with values to show, zero for all
	   @param  now          (Date) the current date and time
	   @return  (number[7]) the current time periods (always positive)
	            by year, month, week, day, hour, minute, second */
	_calculatePeriods: function(inst, show, significant, now) {
		// Find endpoints
		inst._now = now;
		inst._now.setMilliseconds(0);
		var until = new Date(inst._now.getTime());
		if (inst._since) {
			if (now.getTime() < inst._since.getTime()) {
				inst._now = now = until;
			}
			else {
				now = inst._since;
			}
		}
		else {
			until.setTime(inst._until.getTime());
			if (now.getTime() > inst._until.getTime()) {
				inst._now = now = until;
			}
		}
		// Calculate differences by period
		var periods = [0, 0, 0, 0, 0, 0, 0];
		if (show[Y] || show[O]) {
			// Treat end of months as the same
			var lastNow = $.countdown._getDaysInMonth(now.getFullYear(), now.getMonth());
			var lastUntil = $.countdown._getDaysInMonth(until.getFullYear(), until.getMonth());
			var sameDay = (until.getDate() == now.getDate() ||
				(until.getDate() >= Math.min(lastNow, lastUntil) &&
				now.getDate() >= Math.min(lastNow, lastUntil)));
			var getSecs = function(date) {
				return (date.getHours() * 60 + date.getMinutes()) * 60 + date.getSeconds();
			};
			var months = Math.max(0,
				(until.getFullYear() - now.getFullYear()) * 12 + until.getMonth() - now.getMonth() +
				((until.getDate() < now.getDate() && !sameDay) ||
				(sameDay && getSecs(until) < getSecs(now)) ? -1 : 0));
			periods[Y] = (show[Y] ? Math.floor(months / 12) : 0);
			periods[O] = (show[O] ? months - periods[Y] * 12 : 0);
			// Adjust for months difference and end of month if necessary
			now = new Date(now.getTime());
			var wasLastDay = (now.getDate() == lastNow);
			var lastDay = $.countdown._getDaysInMonth(now.getFullYear() + periods[Y],
				now.getMonth() + periods[O]);
			if (now.getDate() > lastDay) {
				now.setDate(lastDay);
			}
			now.setFullYear(now.getFullYear() + periods[Y]);
			now.setMonth(now.getMonth() + periods[O]);
			if (wasLastDay) {
				now.setDate(lastDay);
			}
		}
		var diff = Math.floor((until.getTime() - now.getTime()) / 1000);
		var extractPeriod = function(period, numSecs) {
			periods[period] = (show[period] ? Math.floor(diff / numSecs) : 0);
			diff -= periods[period] * numSecs;
		};
		extractPeriod(W, 604800);
		extractPeriod(D, 86400);
		extractPeriod(H, 3600);
		extractPeriod(M, 60);
		extractPeriod(S, 1);
		if (diff > 0 && !inst._since) { // Round up if left overs
			var multiplier = [1, 12, 4.3482, 7, 24, 60, 60];
			var lastShown = S;
			var max = 1;
			for (var period = S; period >= Y; period--) {
				if (show[period]) {
					if (periods[lastShown] >= max) {
						periods[lastShown] = 0;
						diff = 1;
					}
					if (diff > 0) {
						periods[period]++;
						diff = 0;
						lastShown = period;
						max = 1;
					}
				}
				max *= multiplier[period];
			}
		}
		if (significant) { // Zero out insignificant periods
			for (var period = Y; period <= S; period++) {
				if (significant && periods[period]) {
					significant--;
				}
				else if (!significant) {
					periods[period] = 0;
				}
			}
		}
		return periods;
	}
});

/* jQuery extend now ignores nulls!
   @param  target  (object) the object to update
   @param  props   (object) the new settings
   @return  (object) the updated object */
function extendRemove(target, props) {
	$.extend(target, props);
	for (var name in props) {
		if (props[name] == null) {
			target[name] = null;
		}
	}
	return target;
}

/* Process the countdown functionality for a jQuery selection.
   @param  command  (string) the command to run (optional, default 'attach')
   @param  options  (object) the new settings to use for these countdown instances
   @return  (jQuery) for chaining further calls */
$.fn.countdown = function(options) {
	var otherArgs = Array.prototype.slice.call(arguments, 1);
	if (options == 'getTimes' || options == 'settings') {
		return $.countdown['_' + options + 'Countdown'].
			apply($.countdown, [this[0]].concat(otherArgs));
	}
	return this.each(function() {
		if (typeof options == 'string') {
			$.countdown['_' + options + 'Countdown'].apply($.countdown, [this].concat(otherArgs));
		}
		else {
			$.countdown._attachCountdown(this, options);
		}
	});
};

/* Initialise the countdown functionality. */
$.countdown = new Countdown(); // singleton instance

})(jQuery);
Date.prototype.addHours= function(h){
    this.setHours(this.getHours()+h);
    return this;
}

$(document).ready(function(){
  if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
  
	if ($("#counttime").length > 0) {
	   //console.log("Start Date Found");
	   //console.log($("#counttime").html());
		 var times = $("#counttime").html().split('|');
	   var td = new Date(times[0], times[1]-1, times[2], times[3], times[4], times[5]);
	   //try {
		  $('#countdown').countdown({
          layout: '<span style ="text-transform:lowercase">{dnn}<span class="shorty">DAYS</span>{hnn}<span class="shorty">HRS</span>{mnn}<span class="shorty">MIN</span>{snn}<span class="shorty">s</span></span>', 
          until: td,
          serverSync: getCurrentTime,
          format: 'DHMS',
          onExpiry: countCallBack
          //,timezone: $("#tz_offset").html()
      });
		 //}catch(err){ console.log("err");};
	}
	
	/*
	$('.scroll_film_info').jScrollPane({
    verticalDragMinHeight: 30,
		verticalDragMaxHeight: 30,
    verticalGutter: 30	
  });
	*/
	
	//If we specify that there is a callback
	//Look for the first function called "onExpiry"
	//If it doesn't exist, move along...
	function countCallBack() {
    if ($("#ocb").length > 0) {
      try {
        onExpiry();
      } catch (e) {
      }
    }
  }
	
});
var permissionDialogComplete = false;
var muteMessageComplete = false;
var chatheight_short = "240px";
var chatheight_tall = "350px";

var boxheight = "450px";
  
function toggleDiv( id ) {
  
  $('.killer').fadeOut(100);
  
  if($("#"+id).css('display')=='none'){ //if menu isn't visible 
      //console.log("#"+id+" is hidden");
      
      if (id == "hostchat_panel") {
        if (typeof chat != undefined) {
          chat.getRooms();
        }
      }
      
      if (id == "qanda_panel") {
      	//console.log("Fading In QandA");
      	if (chat != undefined) {
					chat.mode = "short";
					chat.resize;
				}
				$("#"+id).fadeIn(100);
				showQaPanel();
      } else if (id == "chat_panel") {
        $("#qanda_panel").css("top","0px");
				$("#"+id).fadeIn(100);
        $("#video_stream").css("margin-right","340px");
        colorme.boxSwitch();
			} else {
        $("#"+id).css('top',0);
        //$("#"+id).css('bottom',boxheight);
        //$("#inbox").css("height",chatheight_short);
        $("#"+id).fadeIn(100);
      }
			
			if(chat != undefined) {
				chat.resize();
			}
	} else { //if quick menu is visible 
      //console.log("#"+id+" is showing");
      if (id == "qanda_panel") {
      	$("#"+id).fadeOut(100);
				if (chat != undefined) {
					chat.mode = "tall";
					chat.resize;
				}
        hideQaPanel();
      //} else if ((id == "chat_panel") && ($("#qanda_panel").css("display") == "block")) {
      } else if (id == "chat_panel") {
        $("#qanda_panel").css("bottom","0px");
        $("#"+id).fadeOut(100).queue(function() {
        	chat.resize();
        	$("#"+id).clearQueue();
        });
        $("#video_stream").css("margin-right","0px");
        //console.log("Close Chat");
        colorme.boxSwitch();
        $(".show_chat").show();
      } else {
        $("#"+id).fadeOut(100);
       // $("#inbox").css("height",chatheight_tall);
      }
			
			if(chat != undefined) {
			  chat.resize();
			}
  }

}

function toggleConstellation() {
  //console.log($("#toolbar").css("height"));
  if ($(".crash_file").css("display") == "none") {
    //console.log("showing constellation");
    $(".crash_file").css("display","block");
  } else {
    $(".crash_file").css("display","none");
  }
}

function notifyFlashPermissionDialogComplete(statusStr) {
	//console.log("notifyFlashPermissionDialogComplete: " + statusStr);
	permissionDialogComplete = true;
	//showQaWindow();
}

function hideQaPanel() {
  
  if (qanda != undefined) {
    qanda.qanda_showing = false;
  }
     
  if (typeof videoplayer != undefined) {
    if ($("#has_video").length > 0) {
      if ($("#is_host").length > 0) {
        if (videoplayer) {
          videoplayer.hideHostCam();
				}
      } else {
				if (videoplayer) {
          videoplayer.hideLiveViewer();
			  }
      }
		}
  }
}

function hideQaWindow() {
  //console.log("Toolbar Hiding QA Window");
  $("#q_and_a_control").fadeOut(100);
}

function showQaPanel() {
 
  if (qanda != undefined) {
    qanda.qanda_showing = true;
  }
  
  if ($("#has_video").length > 0) {
	//console.log("QandA Has Video");
  if (typeof videoplayer != undefined) {
		if ($("#is_host").length > 0) {
			videoplayer.showHostCam();
			videoplayer.mute();
		  if (! muteMessageComplete) {
         //error.showError("error","", "While you are in Q+A mode, your movie volume has been muted to prevent those attending the Q+A from hearing a distorting echo. Don't worry - everyone else can still hear you and the movie.",7000);
	       muteMessageComplete = true;
      }
    	//Due to the permissions dialog requirement
		} else {
		
			videoplayer.showLiveViewer();
		}
	}}
	
  if ($("#is_host").length > 0) {
    //qanda.scroller.reinitialise();
  }
}

function showQaWindow() {
}

function exitSeat() {
     
     //console.log("exit seat");
     window.location="/services/Lobby/leave?room="+$("#room").html()+"&seat="+$("#seat").html()+"&film="+$("#film").html()+"&code=exit";
    
}
    
$(document).ready(function(){
  if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
 
	//console.log("Starting Toolbar");
  //hide toolbar and make visible the 'show' button
	$(".downarr").click(function() { $("#footer").slideToggle("fast"); $("#toolbarbut").fadeIn(100);});
  //show toolbar and hide the 'show' button
  $(".showbar").click(function() {$("#footer").slideToggle("fast");$("#toolbarbut").fadeOut();});
  
  //don't jump to #id link anchor 
  $(".facebook, .twitter, .delicious, .digg, .rss, .stumble, .menutit, span.downarr a, span.showbar a").click(function() { return false; });
	
  $('.seat_refresh').click(function(event) {location.reload(true);});
  $('.seat_surrender').click(function(event) {exitSeat();});
  $('.constellation-click').click(function(event) {toggleConstellation();});
  $('.chat-click').click(function(event) {toggleDiv( 'chat_panel' );});
  $('.info-click').click(function(event) {toggleDiv( 'info_panel' );});
  $('.qanda-click').click(function(event) {toggleDiv( 'qanda_panel' );});
  $('.help-click').click(function(event) {toggleDiv( 'help_panel' );});
  $('.hostchat-click').click(function(event) {toggleDiv( 'hostchat_panel' );});
  $('.admin-click').click(function(event) {toggleDiv( 'adminmessage_panel' );});
  $('.test-click').click(function(event) {videoplayer.camViewerReStart();});
  
  $('#qanda_panel h4').click(function(event) { toggleDiv( 'qanda_panel' ); });
  $('#qa_pop_top a').click(function(event) { hideQaWindow(); });
  $('#chat_panel h4').click(function(event) {toggleDiv( 'chat_panel' );});
  $('#info_panel h4').click(function(event) {$('#info_panel').fadeOut(100);});
  $('#help_panel h4').click(function(event) {$('#help_panel').fadeOut(100);});
  $('#hostchat_panel h4').click(function(event) {$('#hostchat_panel').fadeOut(100);});
  $('#adminmessage_panel h4').click(function(event) {$('#adminmessage_panel').fadeOut(100);});
  
  //Start with Q&A Enabled
  //toggleDiv( 'chat_panel' );
  if ($("#has_video").html() == "true") {
  	toggleDiv( 'qanda_panel' );
  }
    
});
var fb_invite = {
  
  itRetrievedContacts: 0,
  collectedEmails : new Array(),
  purchaseSubmitted : false,
  currentPrice: 0,
  priceDiscount: .25,
  gbip: false,
  fb_token: null,
  screening: null,
  user_type: null,
  user_id: null,
  user_name: null,
                               
  init: function() {
  
  	$(".fb_dialog_close_icon").click(function(){
  		$("#invite_facebook_lb").fadeOut(100);
			modal.modalOut( login.hidepopup );
		});
  	
  	$("#ok_clicked").click(function(e){
  		e.preventDefault();
			fb_invite.sendInvites();
		});
		
		$("#cancel_clicked").click(function(e){
  		e.preventDefault();
  		$('#invite_facebook_lb').fadeOut(100);
      modal.modalDestroy();
		});
  	
  	$(".all_friends").click(function(e){
			fb_invite.selectAllFriends();
		});
		
		//If we're in the hosting step, show the popup
    if (window.location.href.match(/fb_invite/)) {
      var obj = window.location.href.split("fb_invite=");
      //console.log("Found "+obj[1]+" in the path");
      fb_invite.invite( 'screening', obj[1] );
      //host_screening.detail();
    }
    
  },
  
  goModal: function() {
		modal.modalIn( login.hidepopup );
	},
	
  invite : function( type, screening ) {
  	
		if (screening === undefined){
			screening = $("#screening").html();
		}
		
   	fb_invite.user_type = type;
   	fb_invite.screening = screening;
   	
		if ($("#fb_user_list .selectItem").length==0) {
			error.showError("alert",'<p align="center">Loading Your Friends</p>','<img src="/images/ajax-loader.gif" alt="loading" />',0);
	    
			if ($("#fb_user_list").length > 0) {
				//fb_invite.updateCount();
				$.ajax({
		      url: '/services/Facebook/token', 
		      type: "GET", 
		      cache: false, 
		      dataType: "json", 
		      timeout: 10000,
		      success: function(response) {
		          fb_invite.getLoginStatus( response );
		      }, error: function(response) {
		          console.log("ERROR:", response);
							error.showError('error','communication error','Please try again');
		      }
		    });
				
	  	}
		} else {
			fb_invite.showDialog();
		}
  },
  
  getLoginStatus: function(response) {
		if (response.status != "undefined") {
				fb_invite.fb_token = response.authResponse.accessToken;
				fb_invite.user_id = response.authResponse.userId;
				fb_invite.user_name = response.authResponse.userName;
  			fb_invite.getInvites();
      } else {
      	var theurl = "/services/Facebook/auth?dest="+document.location.pathname+"^fb_invite="+fb_invite.screening;
				//alert(theurl);
      	window.location = theurl;
			}
	},
	
  getInvites: function() {
  	
  	$.ajax({
	      url: '/services/Facebook/friends', 
	      type: "GET", 
	      cache: false, 
	      dataType: "json", 
	      timeout: 4000,
	      success: function(result) {
	          fb_invite.showInvites(result);
	      }, error: function(response) {
	          //console.log("ERROR:", response);
						error.showError('error','communication error','Please try again');
	      }
	    });
	    
  },
	
  showInvites: function( result ) { 
		for (x=0;x<result.data.length;x++) {
			//console.log(result.data[x].name);
			if ($(".fb_invites[value="+result.data[x].id+"]").length == 0) {
				$("#fb_user_list").append('<span class="selectItem"><table cellspacing="4"><tr><td><input class="fb_invites" type="checkbox" value="'+result.data[x].id+'" /></td><td><img src="https://graph.facebook.com/'+result.data[x].id+'/picture" title="'+result.data[x].name+'" /></td><td>'+result.data[x].name+'</td></tr></table></span>');
			}
		}
		//$("#fb_share_preview_image").attr('src','https://graph.facebook.com/'+fb_invite.user_id+'/picture');
		$("#fb_share_user_image").attr('src','https://graph.facebook.com/'+fb_invite.user_id+'/picture');
		fb_invite.showDialog();
		
	},
	
	showDialog: function() {
		setTop("#invite_facebook_lb");
		$("#fb_in").show();
  	error.unblock();
		$("#invite_facebook_lb").fadeIn();
  	fb_invite.goModal();
  	
  	surl = fb_invite.screening.replace(/(#.+)/,"");
    var args = {"screening": surl};
  	$.ajax({
	      url: '/services/Invite/info', 
	      type: "GET", 
	      cache: true, 
	      dataType: "json", 
        data: $.param(args),
	      timeout: 4000,
	      success: function(result) {
	          if (result.screening_image != '') {
	          	$("#fb_share_preview_image").attr("src",result.screening_image);
	          } else {
							$("#fb_share_preview_image").attr("src","/uploads/screeningResources/"+result.screening_film_id+"/logo/film_logo"+result.screening_film_logo);
	          }
						var desc = "{Your Message} - " + fb_invite.user_name + " has invited you to a showing of " + result.screening_film_name + " on Constellation, at " + result.time_tz + " on " + result.time_dayofweek + ", " + result.time_date + ". - '" + result.screening_film_synopsis + "'";
	      		$("#fb_share_preview_text").html(desc);
				}, error: function(response) {
	          console.log("ERROR:", response);
						error.showError('error','communication error','Please try again');
	      }
	    });
	},
	
	selectAllFriends: function() {
		if ($(".all_friends").html() == "All Friends") {
			$(".all_friends").html("No Friends");
			$(".fb_invites").attr("checked","checked");
		} else {
			$(".all_friends").html("All Friends");
			$(".fb_invites").removeAttr("checked");
		}
	},
	
	sendInvites: function() {
		
		if ($("#fb_invite_message").val() == '') {
			error.showError('error','Please enter a message prior to sending your invitations.',null,2000);
			return;
		}
		error.showError("alert",'<p align="center">Sending Invitations...</p>','<img src="/images/ajax-loader.gif" alt="loading" />',2000);
    
    fbs = [];
    j=0;
    
    var eml = $(':checkbox:checked','#invite_facebook');
    $.each(eml,function(index,elm) {
    	fbs.push($(this).val());
    });
    
    var args = {"facebooks":fbs,
                "user_type": fb_invite.user_type,
                "fb_session":fb_invite.fb_token,
                "screening":fb_invite.screening,
                "name": fb_invite.user_name,
                "subject":$('#form_invite_email_subject').val().replace(/\n/g, '<br/>' ),
                "message":$('#fb_invite_message').val().replace(/\n/g, '<br/>' )};
    
    $.ajax({
      url: '/services/Invite/send', 
      data: $.param(args), 
      type: "POST", 
      cache: false, 
      dataType: "json", 
      timeout: 4000,
      success: function(response) {
          fb_invite.finishInvites( response );
      }, error: function(response) {
          console.log("ERROR:", response);
					error.showError('error','communication error','Please try again');
      }
    });
	},
	
	finishInvites: function( response ) {
  	//Release the "process" UI
  	if (response.result == "success") {
			try {
			if (typeof screening_room != "undefined") {
				screening_room.sentInvites(response.count);
			}} catch (err) {}
			
			try {
			if (typeof host_screening != "undefined") {
				host_screening.sentInvites(response.count);
			}} catch (err) {}
			
    	error.showError('alert',response.message,null,2000); 
      $('#invite_facebook_lb').delay(4000).fadeOut(100);
      modal.modalDestroy();
    } else {
      error.showError('error',response.message,null,2000); 
		}
  }
  
}

function toggleChecked(status) {
	$(".fb_invites").each( function() {
		$(this).attr("checked",status);
	})
}

$(document).ready(function(){
	if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
  
	fb_invite.init();
	
});
var invite = {
  
  itRetrievedContacts: 0,
  collectedEmails : new Array(),
  purchaseSubmitted : false,
  currentPrice: 0,
  priceDiscount: .25,
  gbip: false,
  user_type: 'screening',
  screening: null,
                               
  init: function() {
  	
  	//console.log("INVITE");
  	
    if ($("#gbip").html() == 1) {
      invite.geoblock();
    }
    
		$('.lb_close').click(function(e) {
  		$("#invite_email_lb").fadeOut(100);
			modal.modalOut( invite.hidepopup );
		});
		
		$("#form_invite_email_body").inputlimiter({
                              limit: 150,
                              limitTextShow: false,
                              boxId: 'invite-textbox-limit',
                              remTextHideOnBlur: 'false'}
                              );
  	
    $("#btn-invite").click( function(e) {
      e.preventDefault();
      invite.sendInvites();
  	});
    var hash = window.location.search;
    if(/invite=true/.test(hash)){
      hash = hash.replace('?','');
      hash = hash.split('&');
      for(var i = 0; i < hash.length; i++){
          if(/type=/.test(hash[i])){
            var type = hash[i].replace('type=','')
          } else if(/screening=/.test(hash[i])){
            var screening = hash[i].replace('screening=','');
          }
      }
      if(!!screening && !! type){
        invite.invite(type, screening);
      }

    }
    
  },
  hidepopup: function(){
        $("#invite_email_lb").fadeOut();

  },
  goModal: function() {
		modal.modalIn( invite.hidepopup );
	},
	
  invite : function( type, screening ) {
  	
    if($('.main-login').length == 0 || /boxoffice/.test(window.location)){

  	if (screening === undefined){
			screening = $("#screening").html();
		}
  	invite.user_type = type;
  	invite.screening = screening;
  	
    setTop("#invite_email_lb");
    $("#invite_email_lb").fadeIn();
    invite.goModal();
    } else {
        var url = window.location.protocol + '//' + window.location.hostname + window.location.pathname;
        url = url + (!!window.location.search ? window.location.search + '&' : '?') + 'invite=true&invite-type='+ type + '&screening=' + screening;

        console.log(url);

        $("#login_destination").val(url);
        $("#signup_destination").val(url);
        login.showpopup();
    }
    
  },
  
  showErrors : function( step ) {
  	if (! $("#" + step + ' .error-panel').is(':visible')) {
  	 	$("#" + step + ' .error-panel').animate({
  			width: 'toggle'
  		}, 500);
  	}
  },
  
  sendInvites: function() {
    
    console.log("Sending Invites");
    if ($('#form_invite_email_to').val() == '') {
      error.showError("error","There are no people to invite,<br /> please add some email addresses and try again.");
      return;
    }
    
    if ($('#form_invite_email_subject').val() == '') {
      error.showError("error","Please enter a subject.");
      return;
    }
    
    if ($('#form_invite_email_body').val() == '') {
      error.showError("error","Please enter a message.");
      return;
    }
    
    error.showError("alert",'<p align="center">Sending Invitations...</p>','<img src="/images/ajax-loader.gif" alt="loading" />',2000);
    
    emails = [];
    console.log("There are "+ emails.length + " emails.");
    j=0;
    
    var eml = $('#form_invite_email_to').val().split(',');
    $.each(eml,function(index,elm) {
    	var mail = elm.replace(/^\s+|\s+$/g,'');
    	console.log(mail);
    	if (invite.isValidEmailAddress(mail)) {
        emails.push(mail);
      }
    });
    
    var args = {"emails":emails,
                "user_type": invite.user_type,
                "screening":invite.screening,
                "subject":$('#form_invite_email_subject').val().replace(/\n/g, '<br/>' ),
                "message":$('#form_invite_email_body').val().replace(/\n/g, '<br/>' )};
    
    $.ajax({
      url: '/services/Invite/send', 
      data: $.param(args), 
      type: "POST", 
      cache: false, 
      dataType: "json", 
      timeout: 4000,
      success: function(response) {
          invite.finishInvites( response );
      }, error: function(response) {
          console.log("ERROR:", response);
					error.showError('error','communication error','Please try again');
      }
    });
  },
  
  finishInvites: function( response ) {
  	//Release the "process" UI
		if (response.result == "success") {
			try {
			if (typeof screening_room != "undefined") {
				screening_room.sentInvites(response.count);
			}} catch (err) {}
			
			try {
			if (typeof host_screening != "undefined") {
				host_screening.sentInvites(response.count);
			}} catch (err) {}
			
    	error.showError('alert',response.message,null,2000); 
      $('#invite_email_lb').delay(4000).fadeOut(100);
      modal.modalDestroy();
    } else {
      error.showError('error',response.message,null,2000); 
		}
  },
  
  isValidEmailAddress: function (emailAddress) {
  	var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
  	return pattern.test(emailAddress);
  },
  
  updatePrice : function ( price ) {
    if (price > 0) {
      screening_room.currentPrice = price;
      var num = new Number(screening_room.currentPrice);
      $(".ticket_price").html(num.toFixed(2));
      $("#ticket_price").val(num.toFixed(2));
    }
  },
  
  updateCount : function () {
    $("#number_invites").html($('#accepted-invites-container li').length);
  },
  
  geoblock : function() {
    $(".gbip-close").click(function(){
      $("#geoblock").fadeOut(400).queue(function() {
        screening_room.invite();
        $("#geoblock").clearQueue();
      });
    });
    screening_room.gbip = false;
    $("#geoblock").fadeIn();
  }
  
}

$(document).ready(function(){
	if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
  
  
	invite.init();
	
});
