define(function(){

    var CTV = CTV || {};
    CTV.Controller =  CTV.Controller || {};

    CTV.Controller.Profile = Backbone.View.extend({
        _validators: {
            email: /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i,
            isEmpty: /d/
        },
        _errorMessage: {
            empty: '{{arg}} is required',
            valid: '{{arg}} is not valid',
            match: '{{arg}} needs to match {{arg}}'

        },
        el: $('#profile'),
    	initialize: function(){
            this.attachPoints();
            this.attachFields();
            this.attachEvents();
    	},
        attachPoints: function(){
            this.form = $('#account-form', this.$el);
            this.emailSubform = $('#email-sub-form', this.$el);
            this.passwordSubform = $('#password-sub-form', this.$el);
            this.updateEmailButton = $('#update-email-button', this.$el);
            this.updatePasswordButton = $('#update-password-button', this.$el);
        },
        attachEvents: function(){
             this.form.bind('submit', _.bind(this.onSubmit, this));
            this.updateEmailButton.bind('click', _.bind(this.onUpdateEmailClick, this));
            this.updatePasswordButton.bind('click', _.bind(this.onUpdatePasswordClick, this));
        },
        attachFields: function(){
            $('input', this.$el).bind('focus', _.bind(this.onFieldFocus, this));

            this.nickNameField = $('#user_username');
            this.firstNameField = $('#first_name');
            this.lastNameField = $('#last_name');

            // this.emailField = $('#email');
            this.emailNewField = $('#user_email_new');

            this.passwordCurrentField = $('#current_password');
            this.passwordNewField = $('#new_password');
            this.passwordNewConfirmField = $('#new_password_confirm');

        },
        onUpdateEmailClick: function(){
            if(this.emailSubform.is(':visible')){
                this.emailSubform.hide(200, _.bind(function(){
                    this.emailNewField.trigger('focus').val('').attr('disabled', true);
                }, this));
            } else {
                this.emailNewField.attr('disabled', false);
                this.emailSubform.show(200)
            }
        },
        onUpdatePasswordClick: function(){
            if(this.passwordSubform.is(':visible')){
                this.passwordSubform.hide(200, _.bind(function(){
                    this.passwordCurrentField.trigger('focus').val('').attr('disabled', true);
                    this.passwordNewField.trigger('focus').val('').attr('disabled', true);
                    this.passwordNewConfirmField.trigger('focus').val('').attr('disabled', true);
                },this));
            } else {
                this.passwordCurrentField.attr('disabled', false);
                this.passwordNewField.attr('disabled', false);
                this.passwordNewConfirmField.attr('disabled', false);
                this.passwordSubform.show(200);                
            }
        },
        onFieldFocus: function(event) {
            var target = $(event.target);
            if (target.data('hasError')) {
                $(target.parents('.form-row').removeClass('error').find('.error-message')).remove();
            }

        },
        onSubmit: function(e){
            e.preventDefault();
            if(!this.validate()){
                // console.log('f')
            } else {
                this.form.unbind('submit')
                this.form.submit();
                // console.log('f')
            }

            // this.showError(this.nickNameField, 'empty', 'nickname');
            // return false;
        },
        validate: function(){

            var isValid = true;

            if (!this.nickNameField.val()) {
                isValid = false;
                this.showError(this.nickNameField, 'empty', 'Nickname')
            }
            if (!this.firstNameField.val()) {
                isValid = false;
                this.showError(this.firstNameField, 'empty', 'First Name')
            }
            if (!this.lastNameField.val()) {
                isValid = false;
                this.showError(this.lastNameField, 'empty', 'Last Name')
            }

            if(this.emailSubform.is(':visible')){
                if (!this.emailNewField.val()) {
                    isValid = false;
                    this.showError(this.emailNewField, 'empty', 'New E-mail Address');
                } else if (!this._validators.email.test(this.emailNewField.val())) {
                    isValid = false;
                    this.showError(this.emailNewField, 'valid', 'New E-mail Address');
                }
            }
            if(this.passwordSubform.is(':visible')){
                if (!this.lastNameField.val()) {
                    isValid = false;
                    this.showError(this.lastNameField, 'empty', 'Last Name')
                }
                if (!this.passwordCurrentField.val()) {
                    this.showError(this.passwordCurrentField, 'empty', 'Current Password')
                } else {
                    if (!this.passwordNewConfirmField.val()) {
                        isValid = false;
                        this.showError(this.passwordNewConfirmField, 'empty', 'Confirm New Password')
                    } else if (this.passwordNewConfirmField.val() != this.passwordNewField.val()) {
                        isValid = false;
                        this.showError(this.passwordNewConfirmField, 'match', ['Confirm New Password', 'New Password'])
                    }
                }   
            }
            return isValid;

        },
        showError: function(field, type, name) {
            field.parents('.form-row').addClass('error');
            field.data('hasError', true);

            if (typeof name == 'object') {
                var errorMessage = this._errorMessage[type]
                _.each(name, function(val, index) {
                    errorMessage = errorMessage.replace(/{{arg}}/, val)
                });
            } else {
                var errorMessage = this._errorMessage[type].replace('{{arg}}', name)
            }


            $('<span class="error-message"><span class="tip"></span>' + errorMessage + '</span>').appendTo(field.parents('.form-row'))

        }
    });

   return CTV.Controller.Profile;
});