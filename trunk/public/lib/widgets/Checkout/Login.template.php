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
		<li class="active"><span>1</span></li>
		<li class="checkout-steps-number-sep"></li>
		<li><span>2</span></li>
		<li class="checkout-steps-number-sep"></li>
		<li><span>3</span></li>
	</ul>
	<ul class="checkout-steps-text">
		<li class="active"><span>Log In</span></li>
		<li><span>RSVP</span></li>
		<li><span>Access Event</span></li>
	</ul>
</div>
<?php elseif($film['screening_film_free_screening'] == '2') :?>
<div class="checkout-steps">
	<ul class="checkout-steps-number">
		<li class="active"><span>1</span></li>
		<li class="checkout-steps-number-sep"></li>
		<li><span>2</span></li>
		<li class="checkout-steps-number-sep"></li>
		<li><span>3</span></li>
	</ul>
	<ul class="checkout-steps-text">
		<li class="active"><span>Log In</span></li>
		<li><span>Enter Code</span></li>
		<li><span>Access Event</span></li>
	</ul>
</div>
<?php else:?>
<div class="checkout-steps">
	<ul class="checkout-steps-number">
		<li class="active"><span>1</span></li>
		<li class="checkout-steps-number-sep"></li>
		<li><span>2</span></li>
		<li class="checkout-steps-number-sep"></li>
		<li><span>3</span></li>
	</ul>
	<ul class="checkout-steps-text">
		<li class="active"><span>Log In</span></li>
		<li><span>Purchase</span></li>
		<li><span>Access Event</span></li>
	</ul>
</div>
<?php endif;?>
<div id="content">
<?php
	include_component('default','LoginAltFree', array(
		'film' => $film,
		'isModal' => false
	));
?>
</div>
	<?php if(isset($partner['partner_logo'])):?>
		<img src="/images/pages/embed/powered-by-checkout.png"   class="powered-logo"/>
	<?php endif;?>
<script>
jQuery(function(){
	new CTV.Login();
})
</script>
<?php 
	//include_component('default','GrowlerAlt');
?>