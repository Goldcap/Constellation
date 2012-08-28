define(['text!./../Template/Reminder.html', './Dialog'], function(ReminderTemplate) {

    var CTV = CTV || {};
    CTV.View = CTV.View || {};

    CTV.View.Reminder = Backbone.View.extend({
        dialog: Dialog,
        template: Handlebars.compile(ReminderTemplate),
        trigger: $('#reminder-email'),
        validators: {
            email: /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i
        },
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

            if(!!this.options.email){
                this.sendReminder(this.options.email)
            } else if(this.options.isLoggedIn) {
                var body = $(this.template(this.options.screening));
                this.currentInput =  $('.text-input', body);
                // console.log($('.reminder-button', body));
                $('.reminder-button', body).bind('click', _.bind(this.onReminderSubmit, this));
                Dialog.open({
                    body: body,
                    title: 'Reminder',
                    klass: 'dialog-reminder'
                });
            } else {
                $(window).trigger('auth:login')
            }
        },
        onReminderSubmit: function() {

            if(this.validators.email.test(this.currentInput.val())){
                this.sendReminder();
            } else {
                this.showError()
            }
        },
        showError: function(){

        },
        updateAccount: function(email){
            $.ajax({
                url: '/services/Account/modify',
                data: {
                    field: 'user_email',
                    value: email
                },
                type: "POST",
                cache: false,
                success: jQuery.proxy(this.showSuccess, this, email)
            });
        },
        sendReminder: function(email){
            var args = {
                'screening': this.options.screening,
                'email': email
            };

            $.ajax({
                url: '/services/ScreeningReminder',
                data: $.param(args),
                type: "GET",
                cache: false,
                dataType: "json",
                success: function(response) {
                    Dialog.close();
                    Alert.open({type: 'success', body: response.reminderResponse.message})
                },
                error: function(response) {
                    Dialog.close();
                    // reminder.reminderError(response);
                }
            });
        }
    });

    return CTV.View.Reminder;
});
