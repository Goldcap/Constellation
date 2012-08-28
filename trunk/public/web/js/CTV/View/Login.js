define(['text!../Template/Login.html', 'text!../Template/Signup.html','text!../Template/LoginEmail.html', 'text!../Template/SignupEmail.html','text!../Template/ForgotPassword.html','./Dialog' ], function(LoginTemplate, SignupTemplate, LoginEmailTemplate, SignupEmailTemplate, ForgetPassword){

    var CTV = CTV || {};
    CTV.View =  CTV.View || {};

    CTV.View.Login = Backbone.View.extend({
        dialog: Dialog,
        loginTemplate: Handlebars.compile(LoginTemplate),
        signupTemplate: Handlebars.compile(SignupTemplate),
        loginEmailTemplate: Handlebars.compile(LoginEmailTemplate),
        signupEmailTemplate: Handlebars.compile(SignupEmailTemplate),        
        forgetPassword: Handlebars.compile(ForgetPassword),
        data : {
            source: window.location.href,
            destination: window.location.href,
            pathname: encodeURIComponent(window.location.pathname)
        },
    	initialize: function(){

            this.attachPoints();
            this.attachEvents();
    	},
        attachPoints: function(){
            this.$win = $(window);
            this.$loginTriggers = $('.auth-login');
            this.$signupTriggers = $('.auth-signup');
        },
        attachEvents: function(){
            this.$win.bind('auth:login', _.bind(this.onAuthLogin, this));
            this.$loginTriggers.bind('click', _.bind(this.onAuthLogin, this));
            this.$win.bind('auth:signup', _.bind(this.onAuthSignup, this));
            this.$signupTriggers.bind('click', _.bind(this.onAuthSignup, this));
            this.$win.bind('auth:forgetPassword', _.bind(this.onAuthForgetPassword, this));
            this.$win.bind('auth:signupEmail', _.bind(this.onAuthSignupEmail, this));
            this.$win.bind('auth:loginEmail', _.bind(this.onAuthLoginEmail, this));
            // this.$signupTriggers.bind('click', _.bind(this.onAuthSignup, this));            
        },
        onAuthLogin: function(e, arg){
            Dialog.open({body: this.loginTemplate(this.data), title: 'Login', klass: 'dialog-auth'})
        },
        onAuthSignup: function(e, arg){
            Dialog.open({body: this.signupTemplate(this.data), title: 'Sign Up', klass: 'dialog-auth'})
        },
        onAuthSignupEmail: function(e,arg){
            var template = $(this.signupEmailTemplate(this.data));
            $('form', template).bind('submit', _.bind(this.onSignupSubmit, this, $('input[name=email]', template), $('input[name=password]', template), $('input[name=username]', template) , $('input[name=optin]', template) ));
            // console.log($('form', template))
            Dialog.open({body: template, title: 'Sign Up', klass: 'dialog-auth'});
        },
        onAuthLoginEmail: function(e,arg){
            var template = $(this.loginEmailTemplate(this.data));
            $('form', template).bind('submit', _.bind(this.onLoginSubmit, this, $('input[name=email]', template),$('input[name=password]',template) ));
            Dialog.open({body: template, title: 'Login', klass: 'dialog-auth'})
        },
        onAuthForgetPassword: function(e,arg){
            var template = this.forgetPassword();
            $('form', template).bind('submit', _.bind(this.onForgetPasswordSubmit, this, $('input[name=email]', template)));
            Dialog.open({body: template, title: 'Forgot Password?'})
        },
        onLoginSubmit: function(email, password, e){
            e.preventDefault();
            $.ajax({
                type: 'post',
                url:'/services/Login/ajax',
                data: {
                    password: password.val(),
                    email: email.val()
                },
                success: function(response){
                    if(response.meta.success == '200'){
                        if(response.response.success){
                            window.location.reload();
                        } else {
                            Alert.open({type:'error', body: 'Oh no! The e-mail and password you entered did not match an active account.'})
                        }

                    } else {
                        Alert.open({body: 'There was an issue with the request you made.'})
                    }
                }
            });
            return false;
        },
        onSignupSubmit: function(email, password, username, optin, e){
            // alert('f')
            // console.log(email,password,username, e)
            e.preventDefault();
            // return;
            // return false;
            $.ajax({
                type: 'post',
                url:'/services/Join/ajax',
                data: {
                    password: password.val(),
                    email: email.val(),
                    username: username.val(),
                    optin: optin.is(':checked')
                },
                success: function(response){
                    if(response.meta.success == '200'){
                        if(response.response.success){
                            window.location.reload();
                        } else {
                            Alert.open({type:'error', body: '<p>' + response.response.errors.join('</p><p>') + '</p>'})
                        }

                    } else {
                        Alert.open({body: 'There was an issue with the request you made.'})
                    }
                }
            });
            return false;
        },
        onForgetPasswordSubmit: function(email, e){
            e.preventDefault();
            $.ajax({url: '/services/PasswordReminder', 
              data: {
                email: email.val()
              }, 
              dataType: "json", 
              type: "POST",
              success: function(response) {
                 // error.showError('error',response.response.message);
                 Alert.open({type: 'success', body: response.response.message})
              }, 
              error: function(response) {
                        Alert.open({body: 'There was an issue with the request you made.'})
              }
            });
        }
    });

   return CTV.View.Login;
});