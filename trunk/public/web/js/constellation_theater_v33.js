/*!
 * jQuery Cycle Lite Plugin
 * http://malsup.com/jquery/cycle/lite/
 * Copyright (c) 2008 M. Alsup
 * Version: 1.0 (06/08/2008)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 * Requires: jQuery v1.2.3 or later
 */
/*
;(function(D){var A="Lite-1.0";D.fn.cycle=function(E){return this.each(function(){E=E||{};if(this.cycleTimeout){clearTimeout(this.cycleTimeout)}this.cycleTimeout=0;this.cyclePause=0;var I=D(this);var J=E.slideExpr?D(E.slideExpr,this):I.children();var G=J.get();if(G.length<2){if(window.console&&window.console.log){window.console.log("terminating; too few slides: "+G.length)}return }var H=D.extend({},D.fn.cycle.defaults,E||{},D.metadata?I.metadata():D.meta?I.data():{});H.before=H.before?[H.before]:[];H.after=H.after?[H.after]:[];H.after.unshift(function(){H.busy=0});var F=this.className;H.width=parseInt((F.match(/w:(\d+)/)||[])[1])||H.width;H.height=parseInt((F.match(/h:(\d+)/)||[])[1])||H.height;H.timeout=parseInt((F.match(/t:(\d+)/)||[])[1])||H.timeout;if(I.css("position")=="static"){I.css("position","relative")}if(H.width){I.width(H.width)}if(H.height&&H.height!="auto"){I.height(H.height)}var K=0;J.css({position:"absolute",top:0,left:0}).hide().each(function(M){D(this).css("z-index",G.length-M)});D(G[K]).css("opacity",1).show();if(D.browser.msie){G[K].style.removeAttribute("filter")}if(H.fit&&H.width){J.width(H.width)}if(H.fit&&H.height&&H.height!="auto"){J.height(H.height)}if(H.pause){I.hover(function(){this.cyclePause=1},function(){this.cyclePause=0})}D.fn.cycle.transitions.fade(I,J,H);J.each(function(){var M=D(this);this.cycleH=(H.fit&&H.height)?H.height:M.height();this.cycleW=(H.fit&&H.width)?H.width:M.width()});J.not(":eq("+K+")").css({opacity:0});if(H.cssFirst){D(J[K]).css(H.cssFirst)}if(H.timeout){if(H.speed.constructor==String){H.speed={slow:600,fast:200}[H.speed]||400}if(!H.sync){H.speed=H.speed/2}while((H.timeout-H.speed)<250){H.timeout+=H.speed}}H.speedIn=H.speed;H.speedOut=H.speed;H.slideCount=G.length;H.currSlide=K;H.nextSlide=1;var L=J[K];if(H.before.length){H.before[0].apply(L,[L,L,H,true])}if(H.after.length>1){H.after[1].apply(L,[L,L,H,true])}if(H.click&&!H.next){H.next=H.click}if(H.next){D(H.next).bind("click",function(){return C(G,H,H.rev?-1:1)})}if(H.prev){D(H.prev).bind("click",function(){return C(G,H,H.rev?1:-1)})}if(H.timeout){this.cycleTimeout=setTimeout(function(){B(G,H,0,!H.rev)},H.timeout+(H.delay||0))}})};function B(J,E,I,K){if(E.busy){return }var H=J[0].parentNode,M=J[E.currSlide],L=J[E.nextSlide];if(H.cycleTimeout===0&&!I){return }if(I||!H.cyclePause){if(E.before.length){D.each(E.before,function(N,O){O.apply(L,[M,L,E,K])})}var F=function(){if(D.browser.msie){this.style.removeAttribute("filter")}D.each(E.after,function(N,O){O.apply(L,[M,L,E,K])})};if(E.nextSlide!=E.currSlide){E.busy=1;D.fn.cycle.custom(M,L,E,F)}var G=(E.nextSlide+1)==J.length;E.nextSlide=G?0:E.nextSlide+1;E.currSlide=G?J.length-1:E.nextSlide-1}if(E.timeout){H.cycleTimeout=setTimeout(function(){B(J,E,0,!E.rev)},E.timeout)}}function C(E,F,I){var H=E[0].parentNode,G=H.cycleTimeout;if(G){clearTimeout(G);H.cycleTimeout=0}F.nextSlide=F.currSlide+I;if(F.nextSlide<0){F.nextSlide=E.length-1}else{if(F.nextSlide>=E.length){F.nextSlide=0}}B(E,F,1,I>=0);return false}D.fn.cycle.custom=function(K,H,I,E){var J=D(K),G=D(H);G.css({opacity:0});var F=function(){G.animate({opacity:1},I.speedIn,I.easeIn,E)};J.animate({opacity:0},I.speedOut,I.easeOut,function(){J.css({display:"none"});if(!I.sync){F()}});if(I.sync){F()}};D.fn.cycle.transitions={fade:function(F,G,E){G.not(":eq(0)").css("opacity",0);E.before.push(function(){D(this).show()})}};D.fn.cycle.ver=function(){return A};D.fn.cycle.defaults={timeout:4000,speed:1000,next:null,prev:null,before:null,after:null,height:"auto",sync:1,fit:0,pause:0,delay:0,slideExpr:null}})(jQuery);
*/
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
		complete: function (request, textStatus ) {
		  try {
		  var headers = request.getAllResponseHeaders().split("\n");
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
			} catch (e){
				console.log(e)
			}
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
remText=remText.replace(/\%n/g,charsRemaining);remText=remText.replace(/\%s/g,(opts.zeroPlural?(charsRemaining==1?'':'s'):(charsRemaining<=1?'':'s')));return remText;};$.fn.inputlimiter.limittextfilter=function(opts){var limitText=opts.limitText;limitText=limitText.replace(/\%n/g,opts.limit);limitText=limitText.replace(/\%s/g,(opts.limit<=1?'':'s'));return limitText;};$.fn.inputlimiter.defaults={limit:255,boxAttach:true,boxId:'limiterBox',boxClass:'limiterBox',remText:'%n character%s remaining.',remTextFilter:$.fn.inputlimiter.remtextfilter,remTextHideOnBlur:true,remFullText:null,limitTextShow:false,limitText:'Field limited to %n character%s.',limitTextFilter:$.fn.inputlimiter.limittextfilter,zeroPlural:true};})(jQuery);function getCookie(name) {
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
