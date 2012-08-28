<?php
if (isset ($form) ) {echo $form;}
?>
<div class="header">
	<?php if(isset($partner['partner_logo'])):?>
	<img src="<?php echo $partner['partner_logo'];?>" class="parter-logo" />
	<?php else:?>
		<img src="/images/checkout-logo.png" />
	<?php endif;?>
</div>
<?php if($film['screening_film_free_screening'] == '1'):?>
<div class="checkout-steps">
	<ul class="checkout-steps-number">
		<li><span>1</span></li>
		<li class="checkout-steps-number-sep"></li>
		<li class="active"><span>2</span></li>
		<li class="checkout-steps-number-sep"></li>
		<li><span>3</span></li>
	</ul>
	<ul class="checkout-steps-text">
		<li><span>Log In</span></li>
		<li class="active"><span>RSVP</span></li>
		<li><span>Access Event</span></li>
	</ul>
</div>
<?php elseif($film['screening_film_free_screening'] == '2') :?>
<div class="checkout-steps">
	<ul class="checkout-steps-number">
		<li><span>1</span></li>
		<li class="checkout-steps-number-sep"></li>
		<li class="active"><span>2</span></li>
		<li class="checkout-steps-number-sep"></li>
		<li><span>3</span></li>
	</ul>
	<ul class="checkout-steps-text">
		<li><span>Log In</span></li>
		<li class="active"><span>Enter Code</span></li>
		<li><span>Access Event</span></li>
	</ul>
</div>
<?php else:?>
<div class="checkout-steps">
	<ul class="checkout-steps-number">
		<li><span>1</span></li>
		<li class="checkout-steps-number-sep"></li>
		<li class="active"><span>2</span></li>
		<li class="checkout-steps-number-sep"></li>
		<li><span>3</span></li>
	</ul>
	<ul class="checkout-steps-text">
		<li><span>Log In</span></li>
		<li class="active"><span>Purchase</span></li>
		<li><span>Access Event</span></li>
	</ul>
</div>
<?php endif;?>
<div id="content" >
<div class="bo-panelset">
<?php
	include_component('default','CheckoutMain', array(
		'film' => $film,
		'dohdr' => $dohbr,
		'isConfirmation' => $isConfirmation
	));
	include_component('default','CheckoutInvite');
	include_component('default','CheckoutBilling', array(
		'film' => $film,
		'post'      => $post,
		'states'    => $states,
		'countries' => $countries,
	));
	include_component('default','CheckoutPayment', array(
		'post'      => $post,
	));
	include_component('default','CheckoutConfirmation',array(
		'film' => $film,
		'dohdr' => $dohbr,
		'isConfirmation' => $isConfirmation,
		'audience' => $audience
	));

?>	

	<input type="hidden" name="ticket_code" id="ticket_code" class="post-data" value="false" />	
	<input type="hidden" name="invite_count" id="invite_count" class="post-data" value="0" />	
	<input type="hidden" id="ticket_price" name="ticket_price" class="post-data" value="<?php echo $film["screening_film_ticket_price"];?>" />
	<input type="hidden" name="promo_code" id="promo_code" class="post-data" value="0" />
	<input type="checkbox" name="facebook_share" id="facebook_share" class="post-data hide" value="1" />
	
	<?php if (($film["screening_film_allow_hostbyrequest"] == 1) && ($dohbr)) :?>
	<input type="hidden" id="dohbr" name="dohbr" class="post-data" value="true" />
	<?php else: ?>
	<input type="hidden" id="dohbr" name="dohbr" class="post-data" value="false" />
	<?php endif; ?>

</div>
<div class="bo-summary">
	<h4>Details </h4>

	<img class="screening-poster" src="/uploads/screeningResources/<?php echo $film['screening_film_id'];?>/logo/screening_poster<?php echo $film['screening_film_logo'];?>">
		<div class="screening-detail">
			<p class="quantity">1 x ticket(s) to<p>
			<p class="film-name"><?php echo $film['screening_name'] != '' ? $film['screening_name'] : $film['screening_film_name'];?></p>

			<p class="date"><?php echo $dohbr ? 'Now' : date("g:i A T, F dS, Y",strtotime($film['screening_date'])) ;?></p>

			<?php if($film['screening_film_free_screening'] != '2'):?>	
			<?php if(!empty($audience)):?>
			<p class="price live-price"><?php echo !empty($audience) ? '$' . $audience['data'][0]['payment_amount'] : ''?></p>
			<?php else:?>
			<p class="price live-price"><?php echo $film["screening_film_ticket_price"] > 0 ? '$' . number_format($film["screening_film_ticket_price"],2) : 'Free' ;?></p>
			<?php endif;?>
			<?php endif;?>
		</div>
</div>
	<?php if(isset($partner['partner_logo'])):?>
		<img src="/images/pages/embed/powered-by-checkout.png"   class="powered-logo clear"/>
	<?php endif;?>
</div>

</div>

<?php 
	//include_component('default', 'InviteAlt');
	//include_component('default','GrowlerAlt');
?>
      <div id="fb-root"></div>

<script>
jQuery(function(){
	new CTV.Purchase({
		filmId: <?php echo !empty($film["screening_film_id"]) ? $film["screening_film_id"]: 'null';?>,
		filmName: '<?php echo urlencode($film['screening_film_name']);?>',
		screening: '<?php echo $dohbr ? "none": $film["screening_unique_key"];?>',
		currentPrice: <?php echo $film["screening_film_ticket_price"];?>,
		isProduction: <?php echo sfConfig::get("sf_environment") == 'prod' ? 'true' : 'false' ;?>,
		isFreeScreening: <?php echo $film['screening_film_free_screening'] == '1'? 'true' : 'false' ;?>,
		isCouponProtected: <?php echo $film['screening_film_free_screening'] == '2'? 'true' : 'false' ;?>
	});

	if(/err/.test(window.location.href)){
		error.showError("error", "Your payment was not successful. Please try another type of payment.");
	}

});

			window.fbAsyncInit = function() {
		
		    FB.init({
		      appId      : '185162594831374', // App ID
		      // channelURL : '//<?php echo $domain;?>/channel.html', // Channel File
		      status     : true, // check login status
		      cookie     : true, // enable cookies to allow the server to access the session
		      oauth      : true, // enable OAuth 2.0
		      xfbml      : true  // parse XFBML
		    });		
		  };
		
		  // Load the SDK Asynchronously
		  (function(d){
		     var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
		     js = d.createElement('script'); js.id = id; js.async = true;
		     js.src = "//connect.facebook.net/en_US/all.js";
		     d.getElementsByTagName('head')[0].appendChild(js);
		   }(document));


</script>
