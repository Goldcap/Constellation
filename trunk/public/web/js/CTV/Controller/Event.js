define(['CTV/View/Reminder','CTV/View/Calendar','CTV/View/Comments','CTV/Controller/InviteRecord','/js/swfobject.js','/flash/mediaplayer-5.7-licensed/jwplayer.js','/js/jquery/jquery.countdown/jquery.countdown.js'], function(Reminder, Calendar, Comments, InviteRecord){
    var CTV = CTV || {};
    CTV.Controller =  CTV.Controller || {};

    CTV.Controller.Event = Backbone.View.extend({
    	initialize: function(){
            this.attachPoints();
            this.attachEvents();
            this.initCalendar();
            this.initReminder();
            this.initInviteRecord();
            this.initCountdown();
            this.initComments();
            if(!!this.options.trailerOptions.streamUrl){
                this.initTrailer();
            }
    	},
    	attachPoints: function(){
    	},
    	attachEvents: function(){
    	},
        initCalendar: function(){
            new Calendar(this.options.calendarOptions);
        },
        initReminder: function(){
            new Reminder(this.options.reminderOptions);
        },
        initInviteRecord: function(){
            new InviteRecord(this.options.inviteRecord);
        },
        initCountdown: function(){
            var layout = '{dn} <span>Days</span> {hn} <span>Hrs</span> {mn} <span>Mins</span> {sn} <span>secs</span>';
            var format = 'DHMS';
            var date  = new Date(this.options.screeningDate * 1e3);
            var currentDate = new Date();

            if(date.valueOf() > currentDate.valueOf()){
                $('#event-countdown').countdown({
                    until: date,  
                    layout: layout, 
                    format: format
                });
            } else {
                var layout = '{hn} <span>Hrs</span> {mn} <span>Mins</span> {sn} <span>secs</span>';
                var format = 'HMS';
                $('#event-countdown').countdown({
                    since: date,  
                    layout: layout, 
                    format: format
                });
            }
        },
        initComments: function(){
            _.templateSettings = {
                interpolate : /\{\{(.+?)\}\}/g
            };
            new Comments(this.options.commentOptions);
        },
        initTrailer: function(){

            var flashvars = {
                file: this.options.trailerOptions.streamUrl.replace('rtmp://cp113558.edgefcs.net/ondemand/','').replace('?','%3F'),
                image: null,
                streamer: 'rtmp://cp113558.edgefcs.net/ondemand'+'%3F'+this.options.trailerOptions.streamUrl.split('?')[1],
                skin: '/flash/glow/glow.zip',
                autostart: false,
                height: 300
            };
                
            var params = {
                allowFullScreen: 'true',
                allowScriptAccess: 'always',
                wmode: 'opaque',
                bgcolor: "#000000",
                height: 300
            };
            var attributes = {
                  id: 'event-trailer',
                  name: 'event-trailer'
            };
            swfobject.embedSWF('/flash/mediaplayer-5.7-licensed/player.swf', 'event-trailer', '600', '300', '9.0.0', '/flash/expressInstall.swf', flashvars, params, attributes); 
        }
    });

   return CTV.Controller.Event;
});