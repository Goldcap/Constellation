var CTV = CTV || {};
CTV.UploadController = function(options){
	this.options = $.extend({
		filmId: null	      
    }, options);
    this.init();
}

CTV.UploadController.prototype = {
	init: function(){
		this.attachPoints();
		this.attachEvents();
		this.currentTab = 0;
		this.validator = new CTV.Validator();


	},
	attachPoints: function(){
		this.forms = $('form', this.domNode);	

		// $('')

		this.panels = $('.bo-panel', this.domNode);
		this.tabs = $('.tab-list li', this.domNode);
	},
	attachEvents: function(){
		this.forms.bind('submit', _.bind(this.onFormSubmit, this));
		this.tabs.bind('click',  _.bind(this.onTabClick, this));

		// _.each($('input[data-filter]'), function(element){
		// 	new CTV.InputFilter(element)
		// });
	},
	onFormSubmit: function(event){
		event.preventDefault();
		if(!this.validateForm( $(event.target)).length){
			var data = $(event.target).serialize();
			data += '&film_id=' + this.options.filmId;

			// console.log(this.options.filmId, data);
			// return;

			$.ajax({
				type:'POST',
				url: $(event.target).attr('action'),
				data: data,
				dataType: 'json',
				success: _.bind(this.onFormSubmitSuccess, this),
				failure: _.bind(this.onFormSubmitFailure, this),
			});
		} else {
			event.preventDefault();
			$('.error-row:eq(0)')	
			$('html').animate({scrollTop: $('.error-row:eq(0)').offset().top - 30}, 400);
		}
		// return;


	},
	onFormSubmitSuccess: function(response){
		if(response.accountFilmResponse.status == 'success' && !this.options.filmId){
			if(this.currentTab == 1) this.tabs.removeClass('disabled');
			// this.currentTab++;
			// this.panelSwitch(this.currentTab);

			Dialog.open({
				title: 'Form Submit',
				body: 'Your form was successfully saved. What do you want to do next?',
				buttons: [
					{
						text: 'Edit Settings',
						callback: _.bind(this.panelSwitch, this,[this.currentTab +1])
					},
					{
						text: 'Continue Editing'
					}
				]
			})
		} else if(response.accountFilmResponse.status == 'success'){

		} else {
			console.log(response)
			alert(response.accountFilmResponse.message)
		}
	},
	onFormSubmitFailure: function(response){
	},
	onTabClick: function(event){
		var index = $(event.target).index();
		if (index != this.currentTab && !$(event.target).hasClass('disabled')){
			this.panelSwitch(index);
		}
	},
	panelSwitch: function(index){
		this.currentTab = index;
		this.tabs.removeClass('active');
		this.panels.removeClass('active');
		$(this.panels.get(index)).addClass('active');
		$(this.tabs.get(index)).addClass('active').removeClass('disabled');
	},
	validateForm: function(form){
		var elements = $('input[data-validators], select[data-validators], textarea[data-validators]', form);
		return _.reject(elements, _.bind(this.validateElement,this));
	},
	validateElement: function(element){
		return this.validator.validate($(element))
	}
}
/*
CTV.InputFilter = function(element){
	this.element = $(element);
	this.regex = new RegExp(this.element.data('filter'));
	this.init()
}
CTV.InputFilter.prototype = {
	_keys: {'38': 'up', '40': 'down', '37': 'left', '39': 'right',
	'27': 'esc', '32': 'space', '8': 'backspace', '9': 'tab',
	'46': 'delete', '13': 'enter'
	},
	init: function(){
		this.element.bind('keydown', _.bind(this.onChange, this));
	},
	getKey: function(event){
		var code = this.code = (event.which || event.keyCode);
		key = this._keys[code]
		console.log(code)
		// if (event.type == 'keydown'){
			if (code > 111 && code < 124) key = 'f' + (code - 111);
			else if (code > 95 && code < 106) key = code - 96;
		// }
		if (key == null) key = String.fromCharCode(code).toLowerCase();
		return key;
	},
	onChange: function(event){
		var key = this.getKey(event);

		console.log(key)

		if(key.length ==1 && !this.regex.test(key)){
		event.preventDefault();
		}

		// console.log(key);
	}
}
*/

CTV.Validator = function(){}
CTV.Validator.prototype = {
	_validators: {
		email: /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i,
		notEmpty: /^\s*\S.*$/
	},
	_errorMessage: {
		notEmpty: '{{arg}} is required',
		match: '{{arg}} needs to match {{arg}}',
		valid: '{{arg}} is not valid'
	},
	validate: function(element){
		var validators = element.data('validators').split(',');
		return _.all(validators, _.bind(this.test, this, element));
	},
	test: function(element, validator){

		if(!this._validators[validator].test(element.val())){
			this.showError(element, validator, element.data('name'));
			return false;
		} else {
			return true;
		}
	},
	showError: function(field, type, name) {
		this.clearValidation(field);
		field.parents('.form-row').addClass('error-row');

		var errorMessage = this._errorMessage[type].replace('{{arg}}', name)

		$('<span class="error-message"><span class="tip"></span>' + errorMessage + '</span>').appendTo(field.parents('.form-row'))

		field.bind('focus', _.bind(this.clearValidation, this, field))

	},
	clearValidation: function(element){
		$(element.parents('.form-row').removeClass('error-row').find('.error-message')).remove();
		element.unbind('focus')
	}
}