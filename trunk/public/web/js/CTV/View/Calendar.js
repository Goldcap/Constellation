define(['text!./../Template/Calendar.html', './Dialog'], function(ReminderTemplate) {

    var CTV = CTV || {};
    CTV.View = CTV.View || {};

    CTV.View.Reminder = Backbone.View.extend({
        dialog: Dialog,
        template: Handlebars.compile(ReminderTemplate),
        trigger: $('#reminder-calendar'),

        initialize: function() {

            this.attachPoints();
            this.attachEvents();
        },
        attachPoints: function(){
        
        },
        attachEvents: function() {
            this.trigger.bind('click', _.bind(this.onReminderClick, this));
        },
        onReminderClick: function() {

            var body = $(this.template());
            // this.googleCalendar =  $('.text-input', body);
            // console.log($('.reminder-button', body));
            $('#calendar-google', body).bind('click', _.bind(this.onGoogleCalenderClick, this));
            $('#calendar-yahoo', body).bind('click', _.bind(this.onYahooCalendarClick, this));
            $('#calendar-ical', body).bind('click', _.bind(this.onICalClick, this));
            $('#calendar-outlook', body).bind('click', _.bind(this.onOutlookClick, this));

            Dialog.open({
                body: body,
                title: 'Add To Calendar',
                klass: 'dialog-reminder'
            });
        },
        onGoogleCalenderClick: function(){

            var dates = this.options.startDate + '/' + this.options.endDate;
            
            var params = [];
            params.push('action=Template');
            params.push('text=' + encodeURIComponent(this.options.text));
            params.push('dates=' + dates);
            params.push('sprop=website:http://www.constellation.tv');
            params.push('location=' + encodeURIComponent(this.options.location));
            params.push('details=' + encodeURIComponent(this.options.details));

            window.open('http://www.google.com/calendar/event?' + params.join('&'),'_blank','location=0,menubar=0,resizable=0,scrollbars=1,width=850,height=540');
        },
        onYahooCalendarClick: function(){

            var params = [];
            params.push('TITLE=' + encodeURIComponent(this.options.text));
            params.push('ST=' + this.options.startDate);
            params.push('DUR=' + this.options.duration);
            params.push('URL=http://www.constellation.tv');
            params.push('in_loc=' + encodeURIComponent(this.options.location));
            params.push('DESC=' + encodeURIComponent(this.options.details));

            window.open('http://calendar.yahoo.com/?v=60&' + params.join('&'),'_blank','location=0,menubar=0,resizable=0,scrollbars=1,width=850,height=540');
       
        },
        onICalClick: function(){
            window.open('/services/Screenings/ical?screening=' + this.options.screening,'_blank','location=0,menubar=0,resizable=0,scrollbars=1,width=850,height=540');
        },
        onOutlookClick: function(){
            window.open('/services/Screenings/outlook?screening=' + this.options.screening,'_blank','location=0,menubar=0,resizable=0,scrollbars=1,width=850,height=540');
        }
    });

    return CTV.View.Reminder;
});
