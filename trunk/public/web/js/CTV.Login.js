var CTV = CTV || {};
CTV.Login = function(options) {
	this.options = $.extend({

	}, options);
	this.init();

}

CTV.Login.prototype = {
	init: function() {
		this.attachPoints();
		this.attachEvents();
	},
	attachPoints: function() {
		this.domNode = $('#main-login-popup');
		this.loginPanel = $('#log-in', this.domNode);
		this.signupPanel = $('#sign-up', this.domNode);
		this.forgetPanel = $('#password-out', this.domNode);

		this.loginButton = $("#login-button", this.domNode);
		this.signupButton = $("#signup-button", this.domNode);
		this.passwordButton = $("#password-button", this.domNode);


	},
	attachEvents: function() {
		$("#login-button", this.domNode).bind('click', _.bind(this.onLoginSubmit, this));
		$("#signup-button", this.domNode).bind('click', _.bind(this.onSignupSubmit, this));
		$("#password-button", this.domNode).bind('click', _.bind(this.onPasswordSubmit, this));

		$('#main-choose-signup', this.domNode).bind('click', _.bind(this.onShowSignup, this));
		$('#return-login', this.domNode).bind('click', _.bind(this.onShowLogin, this));
		$('#have-account', this.domNode).bind('click', _.bind(this.onShowLogin, this));
		$('#main-choose-password', this.domNode).bind('click', _.bind(this.onShowPassword, this));
	},
	onShowLogin: function() {
		var visible = this.forgetPanel.is(":visible") ? this.forgetPanel : this.signupPanel;
		visible.fadeOut(_.bind(function() {
			this.loginPanel.fadeIn({
				opacity: 1
			});
		}, this));
	},
	onShowSignup: function() {
		this.loginPanel.fadeOut(_.bind(function() {
			this.signupPanel.fadeIn();
		}, this));
	},
	onShowPassword: function() {
		this.loginPanel.fadeOut(_.bind(function() {
			this.forgetPanel.fadeIn();
		}, this));
	},

	onLoginSubmit: function() {
      if (this.validateLogin()) {
        $("#login_form").submit();
      }
	},
	onSignupSubmit: function() {
      if (this.validateJoin()) {
        $("#sign-up_form").submit();
      }
	},
	onPasswordSubmit: function() {
		if (this.validatePassword()) {
			this.resetPassword();
		}
	},

	validateLogin: function() {
		var isValid = true;

		if ($("#login_email").val() == '' || !this.isValidEmailAddress($("#login_email").val())) {
			isValid = false;
			console.log('2')
		}
		if ($("#login_password").val() == '') {
			isValid = false;
			console.log('3')
		}
		if (!isValid) {
			Alert.open({type:"error", body: "Your login information is invalid." });
		}
		return isValid;
	},

	validatePassword: function() {
		var isValid = true;
		if (($("#password_email").val() == '') || ($("#password_email").val() == 'EMAIL') || (!this.isValidEmailAddress($("#password_email").val()))) {
			isValid = false;
		}
		if (!isValid) {
			Alert.open({type:"error", body: "Your email address is invalid."});
		}
		return isValid;
	},

	validateJoin: function() {
		var isValid = true;

		if ($("#main-signup-username").val() == '') {
			isValid = false;
		}
		if (!this.isValidEmailAddress($("#main-signup-email").val())) {
			isValid = false;
		}
		if ($("#main-signup-password").val() == '') {
			isValid = false;
		}

		if (!isValid) {
			Alert.open({type:'error', body: "Your signup information is invalid."});
		}
		return isValid;
	},

	isValidEmailAddress: function(emailAddress) {
		var pattern = /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i;
		return pattern.test(emailAddress);
	},
	resetPassword: function() {
		var args = {
			'email': $("#password_email").val()
		};
		$.ajax({
			url: '/services/PasswordReminder',
			data: $.param(args),
			dataType: "json",
			type: "POST",
			success: function(response) {
				Alert.open({type:'success', body: response.response.message});
			},
			error: function(response) {
				Alert.open({type:'error', body: 'There was an error. Please try again.'});
			}
		});
	}

}