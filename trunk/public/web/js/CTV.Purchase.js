
var CTV = CTV || {};
CTV.Purchase = function(options) {
	this.options = $.extend({
		filmId: null,
		filmName: null,
		screening: null,
		currentPrice: null,
		currentPanelIndex: 0,
		isFreeScreening: false
	}, options);
	this.init();

}

CTV.Purchase.prototype = {
	_validators: {
		email: /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i,
		isEmpty: /d/,
		ccv: /^[0-9]{3,4}$/,
		cc: /^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$/
	},
	_errorMessage: {
		empty: '{{arg}} is required',
		match: '{{arg}} needs to match {{arg}}',
		valid: '{{arg}} is not valid'
	},
	init: function() {

		this.currentPrice = parseInt(this.options.currentPrice);
		this.promoCode = null;
		this.facebookDiscount = false;
		this.isSubmitted = false;

		this.currentPanelIndex = this.options.currentPanelIndex;


		this.attachPoints();
		this.attachEvents();
		this.attachFields();
	},
	attachPoints: function() {

		this.panelSet = $('#content');

		this.applyDiscountCode = $('#toggle-discount-field');
		this.couponCodeField = $('#coupon-code');
		this.couponCodeSubmitButton = $('#coupon-submit');
		this.mainSubmitButton = $('#main-submit');
		this.submitBilling = $('#submit-billing');
		this.submitPayment = $('#submit-payment');
		this.submitPaypal = $('#submit-paypal');
		this.panels = $('.bo-panel');
		this.switchPanels = $('.switch-panel');
		this.creditCardType = $('.form-row-card-type');
	},
	attachFields: function() {
		$('input').bind('focus', _.bind(this.onFieldField, this));

		this.firstNameField = $('#first_name');
		this.lastNameField = $('#last_name');
		this.emailField = $('#email');
		this.emailConfirmField = $('#confirm_email');
		this.address1Field = $('#b_address1');
		this.address2Field = $('#b_address2');
		this.cityField = $('#b_city');
		this.zipField = $('#b_zipcode');
		this.stateField = $('#b_state');
		this.countryField = $('#b_country');

		this.creditCardNumberField = $('#credit_card_number');
		this.expirationDateMonthField = $('#expiration_date_month');
		this.expirationDateYearField = $('#expiration_date_year');
		this.cardVerificationNumberField = $('#card_verification_number');

		this.promoCodeField = $('#promo_code');
		this.ticketPriceField = $('#ticket_price');

		$("#facebook-share").bind('click', _.bind(this.onFacebookShare, this));

		$('#invite-friends').bind('click', _.bind(this.onInviteFriends, this));


	},
	attachEvents: function() {
		this.applyDiscountCode.bind('click', _.bind(this.toggleDiscountCodeField, this));
		this.couponCodeSubmitButton.bind('click', _.bind(this.sendCode, this));

		this.submitBilling.bind('click', _.bind(this.onSubmitBilling, this))
		this.submitPayment.bind('click', _.bind(this.onSubmitPayment, this))
		this.mainSubmitButton.bind('click', _.bind(this.onMainSubmit, this))
		this.submitPaypal.bind('click', _.bind(this.goToPaypal, this));

		_.each(this.switchPanels, function(element) {
			element = $(element)
			element.bind('click', _.bind(this.goToPanel, this, element.data('panel-index')))
		}, this);

		$('input[name="payment-type"]').bind('click', function() {
			var type = $(this).val();
			$(this).parents('.bo-panel').removeClass('bo-panel-paypal bo-panel-cc').addClass('bo-panel-' + type)
		});
		$($('input[name="payment-type"]').get(0)).attr('checked', 'checked').click();


		$(window).bind('onFacebookDiscount', _.bind(this.onFacebookDiscount, this));
	},
	onFieldField: function(event) {
		var target = $(event.target);
		if (target.data('hasError')) {
			$(target.parents('.form-row').removeClass('error').find('.error-message')).remove();
		}

	},
	onMainSubmit: function() {
		if (this.options.isFreeScreening ) {
			this.postForm()
		} else {
			this.goToPanel(2);
		}
	},
	onSubmitBilling: function() {
		if (this.validateBilling()) {
			if (this.currentPrice > 0) {
				this.goToPanel(3);
			} else {
				this.postForm()
			}
		}
	},
	onSubmitPayment: function() {
		if (this.validatePayment() && !this.isSubmitted) {
			this.postForm();
		}
	},
	goToPanel: function(index) {

		var currentPanel = $(this.panels.get(this.currentPanelIndex));
		var newPanel = $(this.panels.get(index))
		this.currentPanelIndex = index;

		this.panelSet.css({
			height: currentPanel.height()
		})

		var self = this;

		currentPanel.fadeOut(
		200, function() {
			newPanel.fadeIn(200, function() {
				self.panelSet.css({
					height: 'auto'
				})
			})
			self.panelSet.css({
				height: newPanel.height()
			})
		})
	},
	postForm: function() {
		if (this.isSubmitted) return;
		this.isSubmitted = true;
		$.ajax({
			url: '/screening/' + this.options.filmId + '/purchase/' + this.options.screening,
			data: $('#content .post-data').serialize(),
			dataType: "json",
			type: "POST",
			success: _.bind(this.onPostResponseSuccess, this),
			error: _.bind(this.onPostResponseError, this)

		});
	},
	onPostResponseSuccess: function(response) {

		if (typeof response != 'object' || !response.purchaseResponse || response.purchaseResponse.status != 'success') {
			this.isSubmitted = false;
			Alert.open({type:"error",body: response.purchaseResponse.message});
			this.goToPanel(2);

		} else {
			this.updateConfirmation(response.purchaseResponse);
		}

		this.isSubmitted = false;
	},
	onPostResponseError: function() {
		Alert.open({type:"error",body: 'Critical Error'});
		this.isSubmitted = false;
		this.goToPanel(2);
	},
	validateBilling: function() {

		var isValid = true;

		if (!this.firstNameField.val()) {
			isValid = false;
			this.showError(this.firstNameField, 'empty', 'First Name')
		}
		if (!this.lastNameField.val()) {
			isValid = false;
			this.showError(this.lastNameField, 'empty', 'Last Name')
		}
		if (!this.emailField.val()) {
			this.showError(this.emailField, 'empty', 'Email')

		} else if (!this._validators.email.test(this.emailField.val())) {
			isValid = false;
			this.showError(this.emailField, 'valid', 'Email')
		} else {
			if (!this.emailConfirmField.val()) {
				isValid = false;
				this.showError(this.emailConfirmField, 'empty', 'Confirm Email')
			} else if (!this._validators.email.test(this.emailConfirmField.val())) {
				isValid = false;
				this.showError(this.emailConfirmField, 'valid', 'Confirm Email')
			} else if (this.emailConfirmField.val() != this.emailField.val()) {
				isValid = false;
				this.showError(this.emailConfirmField, 'match', ['Confirm Email', 'Email'])
			}
		}
		if (!this.address1Field.val()) {
			isValid = false;
			this.showError(this.address1Field, 'empty', 'Address')
		}
		if (!this.cityField.val()) {
			isValid = false;
			this.showError(this.cityField, 'empty', 'City')
		}
		if (!this.zipField.val()) {
			isValid = false;
			this.showError(this.zipField, 'empty', 'Zip')
		}
		if (!this.countryField.val()) {
			isValid = false;
			this.showError(this.countryField, 'empty', 'Country')
		}

		return isValid;
	},

	validatePayment: function() {
		var isValid = true;

		if (!this.creditCardNumberField.val()) {
			isValid = false;
			this.showError(this.creditCardNumberField, 'empty', 'Credit Card Number')
		} else if (!this._validators.cc.test(this.creditCardNumberField.val())) {
			this.showError(this.creditCardNumberField, 'valid', 'Credit Card Number')
			isValid = false;
		}
		if (!this.expirationDateMonthField.val()) {
			isValid = false;
			this.showError(this.expirationDateMonthField, 'empty', 'Exipiration Month')
		}
		if (!this.expirationDateYearField.val()) {
			isValid = false;
			this.showError(this.expirationDateYearField, 'empty', 'Exipiration Year')
		}
		if (!this.cardVerificationNumberField.val()) {
			isValid = false;
			this.showError(this.cardVerificationNumberField, 'empty', 'Security Code')
		} else if (!this._validators.ccv.test(this.cardVerificationNumberField.val())) {
			this.showError(this.cardVerificationNumberField, 'valid', 'Security Code')
			isValid = false;

		}
		return isValid;
	},
	updateConfirmation: function(data) {
		// if(this.options.isProduction){
		// 	this.addGaTransation();
		// }
		if($("#dohbr").val() == "true") { 
			setTimeout( function(){window.location = '/forward/' + data.screening}, 2000);
		} else {
			window.location = '/forward/' + data.screening;
		}
		// $('#summary-price').html(this.currentPrice > 0 ? '$' + this.currentPrice.toFixed(2) : 'FREE')
		// this.options.screening = data.screening;
		// screeningUniqueKey =  data.screening;
		// this.goToPanel(4);

	},
	goToPaypal: function() {
		var price = this.currentPrice.toString().replace(".", "_");
		if ($("#dohbr").val() == "true") {
			screening = "hbr";
		} else {
			screening = this.options.screening;
		}
		window.location.href = "/services/Paypal/express/screening?vars=" + screening + "-" + this.options.filmId + "-" + price;
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


	},
	toggleDiscountCodeField: function() {
		$('#screening-coupon-wrap').animate({
			height: 47,
			opacity: 1
		}, 200);
		this.applyDiscountCode.unbind('click')
	},
	sendCode: function() {

		if (!this.couponCodeField.val()) {
			Alert.open({type:"error",body: "Your code is empty. Please enter a code"});
			return false;
		}

		var args = {
			'ticket': this.couponCodeField.val(),
			'film': this.options.filmId,
			'screening': this.options.screening
		}
		$.ajax({
			url: '/services/Exchange',
			data: $.param(args),
			type: "POST",
			cache: false,
			dataType: "json",
			success: _.bind(this.onSendCodeSuccess, this),
			error: function(response) {
				Alert.open({type:"error",body: response.exchangeResponse.message});
			}
		});

	},
	onSendCodeSuccess: function(response) {

		if (response.exchangeResponse.result == "promo") {
			if ( !! this.couponCodeField.val()) {
				this.promoCode = this.couponCodeField.val();
				this.couponCodeField.attr("disabled", "disabled");
				this.promoCodeField.val(this.promoCode);
				this.couponCodeSubmitButton.hide();

				if (response.exchangeResponse.type == 1) {
					this.applyDiscount(this.currentPrice * (1 - (response.exchangeResponse.discount / 100)));
				} else if (response.exchangeResponse.type == 2) {
					this.applyDiscount(this.currentPrice - response.exchangeResponse.discount);
				} else if (response.exchangeResponse.type == 3) {
					this.postForm();
				} else {
					Alert.open({type:"error",body: response.exchangeResponse.message});
				}

			} else {
				Alert.open({type:"error",body: "You've already used a discount code."});
			}
		} else if (response.exchangeResponse.result == "success") {
			// $("#screening").html(response.exchangeResponse.theurl);
			// screening_room.confirm();
		} else {
			Alert.open({type:"error",body: response.exchangeResponse.message});
		}
	},
	applyDiscount: function(price) {
		this.currentPrice = price > 0 ? price : 0;
		this.ticketPriceField.val(this.currentPrice);
		if (price > 0) {
			$('.live-price').html('$' + price.toFixed(2))
		} else {
			$('.live-price').html('Free');
			$(this.mainSubmitButton.find('.button-price-text')).html('Get Ticket Now');
			$(this.submitBilling).html('Get Ticket Now')
			$(this.creditCardType).hide();
		}
	},
	onFacebookShare: function() {
		window.open("/facebook/" + this.options.screening, "facebookShare", "width=600,height=300,scrollbars=yes");
	},
	onFacebookDiscount: function() {
		if (!this.facebookDiscount) {
			this.facebookDiscount = true;
			this.applyDiscount(this.currentPrice - 1);
			$('#facebook_share').attr('checked', 'checked');
		}
	},
	onInviteFriends: function() {
		invite.invite('screening', this.options.screening);
	},
	addGaTransation: function() {
		var UID = (+(new Date))
		UID ++
		UID = UID.toString(36);

		_gaq.push(['_addTrans', UID, // order ID - required
		'Constellation', // affiliation or store name
		this.currentPrice.toString(), // total - required
		'0.00', // tax
		'0', // shipping
		this.cityField.val(), // city
		$(this.stateField.find('option:selected')).text(), // state or province
		this.countryField.val() // country
		]);
		// add item might be called for every item in the shopping cart
		// where your ecommerce engine loops through each item in the cart and
		// prints out _addItem for each
		_gaq.push(['_addItem', UID, // order ID - required
		this.options.screening, // SKU/code - required
		this.options.filmName, // product name
		'Showtime', // category or variation
		this.currentPrice.toString(), // unit price - required
		'1' // quantity - required
		]);

		_gaq.push(['_trackTrans']);
	}
}

function setTop() {}

function onFacebookDiscount() {
	$(window).trigger('onFacebookDiscount');
}