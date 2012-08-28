<div class="bo-panel <?php echo $isConfirmation ? '': 'active'?>">

	<h4>Ticket to "<?php echo $film['screening_film_name'];?>"</h4>


<?php if($film['screening_film_free_screening'] != '1' && $film['screening_film_free_screening'] != '2'):?>		
	<div class="form-row clear">
		<span class="link" id="toggle-discount-field">Have a discount code?</span>
	</div>
	<div class="screening-coupon form-row" id="screening-coupon-wrap">
		<input type="text" name="exchange" class="text-input large" id="coupon-code" /><span id="coupon-submit" class="button button_orange uppercase">Apply</span>
	</div>


 	<div class="form-row button-full">
		<span class="button button-blue button-social uppercase" id="facebook-share">
			<span class="button-social-icon button-social-icon-email"></span><span class="button-social-text">Share on Facebook &amp; Save <strong>$1.00</strong> </span>
		</span>
	</div>
<?php elseif($film['screening_film_free_screening'] == '2'):?>
	<div class="form-row clear">
		<label style="float: none; width: auto; disply: block;">This film requires a code</label>
	</div>
	<div class="screening-coupon form-row" id="screening-coupon-wrap" style="display: block; height: auto; opacity: 1">
		<input type="text" name="exchange" class="text-input large" id="coupon-code" /><span id="coupon-submit" class="button button_orange uppercase">Apply</span>
	</div>
<?php endif;?>

<?php if($film['screening_film_free_screening'] != '2'):?>	
	<div class="form-row button-full">
		<span class="button button-green button-price uppercase" id="main-submit">
			<span class="button-price-value live-price"><?php echo $film["screening_film_ticket_price"] > 0 ? '$' . number_format($film["screening_film_ticket_price"],2) : 'Free' ;?></span><span class="button-price-text">RSVP Now</span>
		</span>
	</div>
<?php else: ?>
	<input type="hidden" name="email" class="post-data" value="<?php echo $sf_user-> getAttribute("user_email")?>" />	
	<input type="hidden" name="username" class="post-data" value="<?php echo $sf_user-> getAttribute("user_username")?>" />	
<?php endif;?>

</div>  
