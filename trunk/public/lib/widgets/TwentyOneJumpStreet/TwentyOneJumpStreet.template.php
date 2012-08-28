<div id="content" style="height: 660px ">
	<div class="inner_container clearfix">
		<div class="hero-block">
			<img src="/images/pages/21jumpstreet/21jumpstreet-pre-logo.jpg" alt="21 Jump Street"/>
		</div>
		<div class="tojs-pre-info">
			<div class="tojs-pre-about">
				Enter your email address for early VIP access to an exclusive live online event with Jonah Hill and Channing Tatum.   
			</div>
			<div class="tojs-pre-form clearfix">
				<input type="text" class="text-input" id="email-input" name="email" placeholder="E.G: your.name@host.com"/><span id="submit-pre" class="button button_blue uppercase">Submit</span>
			</div>
		</div>
		<div class="tojs-pre-thanks">
			<p>Thanks for pre-registering for the 21 Jump Street online event.  <br/><br/>
			Details to be announced soon.</p>
			<p class="center button-social-wrap">
				<span class="button button_faceblue" id="share-facebook"><span class="login-social-icon login-social-icon-facebook"></span>Share on Facebook</span>
				<span class="button button_bluetwitter" id="share-twitter"><span class="login-social-icon login-social-icon-twitter"></span>Share on Twitter</span>
			</p>
		</div>
	</div>
</div>

<script>
$(function(){
	new CTV.PreForm();

	$('#share-twitter').bind('click', function(){
		var params =[];
			params.push('text=' + encodeURIComponent('Pre-Register to the @21JumpStMovie LIVE event with @JonahHill and @ChanningTatum! http://www.constellation.tv/21jumpstreet #21JumpsStLive'));
			// params.push('url=' + encodeURIComponent('http://www.constellation/21jumpstreet'));
			window.open('https://twitter.com/intent/tweet?' +params.join('&'),'_share_twitter','width=450,height=250')
	});

	$('#share-facebook').bind('click', function(){
        var obj = {
          method: 'feed',
          link: 'http://www.constellation.tv/21jumpstreet',
          picture: 'http://www.constellation.tv/images/pages/21jumpstreet/21jumpstreet-pre-logo.jpg',
          name: '21 Jump Street Live Event',
          caption: 'Live online event with Jonah Hill and Channing Tatum',
          description: 'Sign-up for VIP early access to the 21 Jump Street LIVE event with Jonah Hill and Channing Tatum!'
        };

		FB.ui(obj);
	});

});
var CTV = CTV || {};
CTV.PreForm = function(){
	this.init();	
}
CTV.PreForm.prototype = {
	email: /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i,
	init: function(){
		this.attachPoints();
		this.attachEvents();
	},
	attachPoints: function(){
		this.submitButton = $('#submit-pre');
		this.preInfo = $('.tojs-pre-info');
		this.preSuccess = $('.tojs-pre-thanks');
		this.formBlock = $('.tojs-pre-form ');
		this.inputField = $('#email-input');
	},
	attachEvents: function(){
		this.submitButton.bind('click', _.bind(this.onSubmitClick, this));
		this.inputField.bind('focus', _.bind(this.onFieldFocus, this));
	},
	validate: function(){
		return this.email.test(this.inputField.val())
	},
	onSubmitClick: function(){
		if(this.validate()){
			// this.showSuccess();
			this.postEmail();
		} else {
			this.showError();
		}
	},
	postEmail: function(){
		// this.showSuccess();
		// return;
		$.ajax({
			url: '/services/jumpnotice',
			data: {
				email: this.inputField.val(),
				referer: 'www.constellation.tv'
			},
			type: "POST",
			cache: false,
			success: jQuery.proxy(this.showSuccess, this)
		});
	},
	onFieldFocus: function(event) {
		if (this.inputField.data('hasError')) {
			$(this.formBlock.removeClass('error').find('.error-message')).remove();
			this.inputField.data('hasError', false);
		}
	},
	showSuccess: function(){
		this.preInfo.fadeOut(300, _.bind(function(){
			this.preSuccess.fadeIn(300);
		},this));
	},
	showError: function() {
		if (!this.inputField.data('hasError')) {
			this.formBlock.addClass('error');
			this.inputField.data('hasError', true);
			var errorMessage = 'The email you entered is not valid';
			$('<span class="error-message"><span class="tip"></span>' + errorMessage + '</span>').appendTo(this.formBlock);
		}
	}
}


</script>