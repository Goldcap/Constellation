var CTV = CTV || {};
CTV.TheaterHost = function(options, domNode) {
	this.options = $.extend({
		file: null,
		timeout: 5000,
		isHost: false
	}, options);
	this.domNode = domNode;
	this.init();
}
CTV.TheaterHost.prototype = {
	init: function() {
		this.isVisible = false;
		this.isToggled = true
		this.isHost = this.options.userId == this.options.hostId;
		this.hostIsLive = true;

		this.attachPoints();
		this.attachEvents();
		if(this.isHost) {
			this.addSwitch();
			this.hostPlaceholder.addClass('is-publisher');
		} else {
			this.hostPlaceholder.addClass('is-subscriber');
		}
		this.show();

		// $(this).trigger('getHostStream', this.hostPlaceholder);


	},
	attachPoints: function() {
		this.hostPlaceholder = $('#host-player-placeholder', this.domNode);
		this.toggler = $('#toggle-host', this.domNode).tooltip({
			placement: 'bottom'
		});
	},
	attachEvents: function(){
		this.toggler.bind('click', _.bind(this.onToggle, this))
	},
	onToggle: function(){
		this.isToggled = !this.isToggled;
		this.toggler[!this.isToggled? 'removeClass':'addClass']('active')

		$(this).trigger('hostToggle', this.isToggled)

	},
	addSwitch: function(){
		this.switchButton = $('<span class="switch" title="Turn Camera On/Off"></span>').tooltip({
			placement: 'bottom'
		}).insertBefore(this.toggler);
		this.switchButton.bind('click', _.bind(this.onSwitchClick, this));
	},
	onSwitchClick: function(){
		this.hostIsLive = !this.hostIsLive;
		this.switchButton[this.hostIsLive? 'removeClass': 'addClass']('off');

		if(!this.hostIsLive){
			$(this).trigger('publishHost', false)
			// this.unpublishHost();
		} else {
			$(this).trigger('publishHost', true)
			// this.republishHost();
		}
	},
	startStream: function(){
		$(this).trigger('getStream', [this.hostPlaceholder, {width: 310, height: 190 }]);
	},
	show: function() {
		if (!this.isVisible) {
			this.isVisible = true;
			this.domNode.fadeIn();
			$(this).trigger('hostShow', true)
		}
	},
	hide: function() {
		if (this.isVisible) {
			this.isVisible = false;
			// this.domNode.fadeOut();
			$(this).trigger('hostHide', true)
		}
	}
}