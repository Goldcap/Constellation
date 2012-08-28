<?php 
//OK, we have to remove the "screening_" part of the keys
//Sorry, hack
foreach($film as $aspect => $value) {
	$newaspect = str_replace("screening_","",$aspect);
	$film[$newaspect] = $value;
}
?>

<span class="bandwidth_warning" style="display: none; font-size: 11px;">Warning: You have low bandwidth at this location. You may experience issues with image quality and continuity due to your connection. (<span class="current_bandwidth"></span>k/sec)</span>

<!-- PURCHASE POPUP -->
<div id="screening_purchase" style="display: none">
	<form id="purchase_form" name="purchase_form" action="/screening/<?php echo $film["film_id"];?>/purchase" method="POST" class="buy-ticket_form" onSubmit="return false">
	<table class="step_one" cellpadding="0" cellspacing="0" border="0">
  	<tr>
  		<td colspan="2">
  			<h5>
  				<?php echo $film["film_name"];?>
  			</h5>
  		</td>
  	</tr>
  	<?php if ($film["screening_user_full_name"] != '') {?>
  	<tr height="15">
  		<td colspan="2" valign="top">
  			<span class="pre_by">
  			Hosted By
  			</span>
  		</td>
  	</tr>
  	<tr>
  		<td height="25" colspan="2" valign="top">
  			<span class="pre_host">
  				<span class="current_screening_host"><?php echo $film["screening_user_full_name"];?></span>
  			</span>
  		</td>
  	</tr>
  	<?php }?>
		<tr>
      <td colspan="2" valign="top" height="50" class="purchase_form_ticket_price">
				<label>Ticket Price</label><br />
				<span class="ticket_prices">
        	<span class="price">$<?php echo $film["film_ticket_price"];?></span> - 
					(<span class="price">0.10</span> <strong>x</strong> 
					<span class="count_friends">0</span> friends) 
					<!--- <span class="price">15%</span>--> = 
					<span class="ticket_price total price">$<?php echo $film["film_ticket_price"];?></span>
				</span>
      </td>
    </tr>
  	<!--<tr>
  		<td height="25" colspan="2" valign="top">
  			<span class="pre_ticket">
	  			Ticket price: <span class="pre_price"><?php if ($film["film_ticket_price"] == 0) {?>FREE<?php } else {?><span class="ticket_price">$<?php echo sprintf("%.02f",$film["film_ticket_price"]);?></span><?php } ?></span>
  			</span>
  		</td>
  	</tr>-->
  	<tr class="cc_items">
			<td colspan="2" height="50" valign="top">
  			<span class="pre_pfl">
	  			<label>We Accept:</label><br />								
          <ul class="cclist">						
            <li id="visa">
              <a title="visa" class="credit-cards"><img src="/images/icon-cards-visa.png" /></a>
            </li>						
            <li id="mastercard">
              <a title="mastercard" class="credit-cards"><img src="/images/icon-cards-master.png" /></a>
            </li>						
            <li id="amex">
              <a title="amex" class="credit-cards"><img src="/images/icon-cards-amex.png" /></a>
            </li>						
            <li id="discover">
              <a title="discover" class="credit-cards"><img src="/images/icon-cards-discover.png" /></a>
            </li>						
            <li id="paypal_icon">							
              &nbsp;&nbsp;or click: &nbsp;
            </li>
            <li id="paypal">							
              <a id="paypal-button" onclick="screening_room.goPaypal()" title="paypal" class="credit-cards"><img src="/images/icon-cards-paypal.png" /></a>						
            </li>					
          </ul>	
				</span>
			</td>
		</tr>
  	<tr>
  		<td height="50" valign="top">
  			<span class="pre_pfl">
	  			<label>First Name</label><br />
					<input id="fld-cc_first_name" name="first_name" class="input" type="text" value="<?php echo $post["first_name"];?>" />
				</span>
			</td>
			<td height="50" valign="top">
  			<span class="pre_pfl">
	  			<label>Last Name</label><br />
					<input id="fld-cc_last_name" name="last_name" class="input" type="text" value="<?php echo $post["last_name"];?>" />
				</span>
			</td>
			
  	</tr>
  	<tr>
  		<td height="50" valign="top">
  			<span class="pre_pfl">
	  			<label>E-Mail Address</label><br />
					<input id="fld-cc_email" name="email" class="input" type="text" value="<?php echo $post["email"];?>" />
				</span>
			</td>
			<td height="50" valign="top">
  			<span class="pre_pfl">
	  			<label>Confirm E-mail</label><br />
					<input id="fld-cc_confirm_email" name="confirm_email" class="input" type="text" value="<?php echo $post["email_confirm"];?>" />
				</span>
			</td>
		</tr>
  	<tr>
  		<td height="50" valign="top">
  			<span class="pre_pfl">
	  			<label>Billing Address</label><br />
					<input id="fld-cc_address1" name="b_address1" class="input" type="text" value="<?php echo $post["b_address1"];?>" />
				</span>
			</td>
			<td height="50" valign="top">
  			<span class="pre_pfl">
  				<label>Billing Address (cont)</label><br />
	  			<input id="fld-cc_address2" name="b_address2" class="input" type="text" value="<?php echo $post["b_address2"];?>" />
				</span>
			</td>
  	</tr>
		<tr>
			<td height="50" valign="top">
  			<span class="pre_pfl">
	  			<label>City</label><br />
					<input id="fld-cc_city" name="b_city" class="input" type="text" value="<?php echo $post["b_city"];?>" />
				</span>
			</td>
			<td height="50" valign="top">
  			<span class="pre_pfl">
	  			<label>State</label><br />
					<input type="text" class="cc-state-text" id="host-fld-cc_state_txt" name="b_state" style="display:none" />
          <select class="cc-state-drop" id="fld-cc_state" name="b_state">				          
            <?php foreach ($states as $state) {?>
              <option value="<?php echo $state -> getAttribute("abbr");?>" <?php if($post["b_state"] == $state -> getAttribute("abbr")) { echo "selected=\"selected\""; }?>><?php echo ucwords(strtolower($state -> getAttribute("name")));?></option>
            <?php } ?> 
          </select>
				</span>
			</td>
  	</tr>
  	<tr>
  		<td height="55" colspan="2" valign="top">
  			<span class="pre_pfl">
	  			<label>Country</label><br />
					<select class="cc-country-drop" id="fld-cc_country" name="b_country">				          
            <?php foreach ($countries as $country) {?>
              <option value="<?php echo $country -> getAttribute("abbr");?>" <?php if($post["b_country"] == $country -> getAttribute("abbr")) { echo "selected=\"selected\""; }?>><?php echo ucwords(strtolower($country -> getAttribute("name")));?></option>
            <?php } ?> 
          </select>
				</span>
			</td>
  	</tr>
		<tr>
  		<td colspan="2" valign="top">
  			<span class="pre_pfl step_one_purchase">
  				<?php if ($film["film_ticket_price"] == 0) {?>
	  			<input id="purchase_submit" type="image" src="/images/alt1/purchase_final.png" value="get ticket" name="" /> 
		      <input type="hidden" name="screening_free" id="screening_free" value="true" />
		      <input type="hidden" id="ticket_price" name="ticket_price" value="0" />
	  			<?php } else { ?>
	  			<img class="purchase_next" src="/images/alt1/purchase_next.png" style="margin: 8px 3px;" /> 
	  			<?php } ?>
				</span>
			</td>
  	</tr>
  </table>
  
  <?php if ($film["film_ticket_price"] != 0) {?>
  <table class="step_two" cellpadding="0" cellspacing="0" border="0">
  	<tr>
  		<td colspan="2">
  			<h5>
  				<?php echo $film["film_name"];?>
  			</h5>
  		</td>
  	</tr>
  	<?php if ($film["screening_user_full_name"] != '') {?>
  	<tr height="15">
  		<td colspan="2" valign="top">
  			<span class="pre_by">
  			Hosted By
  			</span>
  		</td>
  	</tr>
  	<tr>
  		<td height="25" colspan="2" valign="top">
  			<span class="pre_host">
  				<span class="current_screening_host"><?php echo $film["screening_user_full_name"];?></span>
  			</span>
  		</td>
  	</tr>
  	<?php }?>
		<tr>
      <td colspan="2" valign="top" height="50" class="purchase_form_ticket_price">
				<label>Ticket Price</label><br />
				<span class="ticket_prices">
        	<span class="price">$<?php echo $film["film_ticket_price"];?></span> - 
					(<span class="price">0.10</span> <strong>x</strong> 
					<span class="count_friends">0</span> friends) 
					<!--- <span class="price">15%</span>--> = 
					<span class="ticket_price total price">$<?php echo $film["film_ticket_price"];?></span>
				</span>
      </td>
    </tr>
    <tr>
    	<td height="20" colspan="2">
    		<hr width="400" />
			</td>
    </tr>
		<tr>
  		<td height="50" valign="top">
  			<span class="pre_pfl">
	  			<label>Credit Card Number</label><br />
					<input id="fld-cc_number" name="credit_card_number" class="input" type="text" value="<?php echo $post["card_number"];?>" />
				</span>
			</td>
			<td height="50" valign="top">
  			<span class="pre_pfl">
	  			<label>Security Code</label><br />
					<input id="fld-cc_security_number" name="card_verification_number" class="input" type="text" value="<?php echo $post["security_code"];?>" />
				</span>
			</td>
		</tr>
		<tr>
			<td height="50" valign="top">
  			<span class="pre_pfl">
	  			<label>Exp Date </label><br />
          <select id="fld-cc_exp_month" name="expiration_date_month">
            <?php for ($i=1;$i<13;$i++) {?>          
            <option value="<?php echo ($i);?>" <?php if ($post["cc_exp_month"] == $i) {?>selected="selected"<? }?>><?php echo sprintf("%02d",$i);?></option>
            <?php } ?>			          
          </select>		         						         						          		          
          <select id="fld-cc_exp_year" name="expiration_date_year">				            
            <?php for ($i=year();$i<year()+10;$i++) {?>          
            <option value="<?php echo ($i);?>" <?php if ($post["cc_exp_year"] == $i) {?>selected="selected"<? }?>><?php echo sprintf("%02d",$i);?></option>
            <?php } ?>				          
          </select>	
				</span>
			</td>
  		<td height="50" colspan="2" valign="top">
  			<span class="pre_pfl">
	  			<label>Zip Code</label><br />
					<input id="fld-cc_zip" name="b_zipcode" class="short" type="text" value="<?php echo $post["b_zipcode"];?>" />
				</span>
			</td>
  	</tr>
		<tr>
  		<td colspan="2" valign="top">
  			<span class="pre_pfl">
  				<table>
  					<tr>
  						<td>
								<img class="purchase_last" src="/images/alt1/purchase_last.png" /> 
  						</td>
  						<td>
								<?php if ($film["film_ticket_price"] != 0) {?>
				  			<input id="purchase_submit" type="image" src="/images/alt1/purchase_final.png" value="get ticket" /> 
					      <?php } ?>
		      		</td>
		      	</tr>
		      </table>
				</span>
			</td>
  	</tr>
  </table>
  <?php } ?>
  
  <input type="hidden" name="ticket_code" id="ticket-code" value="false" />	
  <input type="hidden" name="invite_count" id="invite_count" value="0" />	
  <?php if ($film["film_ticket_price"] == 0) {?>
    <input type="hidden" id="ticket_price" name="ticket_price" value="0" />
  <?php } else {?>
    <input type="hidden" id="ticket_price" name="ticket_price" value="<?php echo $film["film_ticket_price"];?>" />
  <?php } ?>
  <input type="hidden" name="promo_code" id="promo_code" value="0" />	
  <?php if ($film["film_allow_hostbyrequest"] == 1) {?>
    <input type="hidden" id="dohbr" name="dohbr" value="false" />
  <?php } ?>
  
  </form>
	
  <div id="purchase_errors" style="color: red"></div>  
	 
</div>
<!-- END PURCHASE POPUP -->

<!-- PURCHASE PROCESS POPUP -->
<div id="process" style="display: none">
	<table class="step_one" cellpadding="0" cellspacing="0" border="0">
  	<tr>
  		<td colspan="2">
  			<h5>
  				<?php echo $film["film_name"];?>
  			</h5>
  		</td>
  	</tr>
  	<tr height="20">
  		<td colspan="2" valign="bottom">
  			<span class="pre_by">
  			Hosted By
  			</span>
  		</td>
  	</tr>
  	<tr>
  		<td height="40" colspan="2" valign="top">
  			<span class="pre_host">
  				<span class="current_screening_host"></span>
  			</span>
  		</td>
  	</tr>
  	<tr>
  		<td height="30" colspan="2" valign="top">
  			<span class="pre_ticket">
	  			Ticket price: <span class="pre_price"><?php if ($film["film_ticket_price"] == 0) {?>FREE<?php } else {?><span class="ticket_price">$<?php echo sprintf("%.02f",$film["film_ticket_price"]);?></span><?php } ?></span>
  			</span>
			</td>
  	</tr>
  	<tr>
  		<td height="30" colspan="2" valign="top">
  			<span class="pre_host">
					Your purchase is being processed.
				</span>
			</td>
		</tr>
  	<tr>
  		<td height="30" colspan="2" valign="top">
  			<img src="/images/ajax-loader.gif" />
			</td>
		</tr>
	</table>
</div>
<!-- END PURCHASE PROCESS POPUP -->

<!-- PURCHASE CONFIRM POPUP -->
<div id="confirm" style="display: none">
	<table class="step_one" cellpadding="0" cellspacing="0" border="0">
  	<tr>
  		<td colspan="2">
  			<h5>
  				<?php echo $film["film_name"];?>
  			</h5>
  		</td>
  	</tr>
  	<tr height="20">
  		<td colspan="2" valign="bottom">
  			<span class="pre_by">
  			Hosted By
  			</span>
  		</td>
  	</tr>
  	<tr>
  		<td height="40" colspan="2" valign="top">
  			<span class="pre_host">
  				<span class="current_screening_host"></span>
  			</span>
  		</td>
  	</tr>
  	<tr>
  		<td height="30" colspan="2" valign="top">
  			<span class="pre_ticket">
	  			Ticket price: <span class="pre_price"><?php if ($film["film_ticket_price"] == 0) {?>FREE<?php } else {?><span class="ticket_price">$<?php echo sprintf("%.02f",$film["film_ticket_price"]);?></span><?php } ?></span>
  			</span>
			</td>
  	</tr>
  	<tr class="pre_host_message">
  		<td height="30" colspan="2" valign="top">
  			<span class="pre_host">
					Your purchase was confirmed. You'll be redirected into the theater in a moment. If this message persists, please refresh your browser.
				</span>
			</td>
		</tr>
  	<!--<tr>
  		<td height="50" colspan="2" valign="bottom">
  			<a id="screening_click_link" href="#"><button class="btn_small">Watch the Film</button></a>
			</td>
		</tr>-->
	</table>
</div>
<!-- END PURCHASE CONFORM POPUP -->

<div id="purchase_offers">
	<table width="280" cellpadding="0" cellspacing="0" border="0">
		<tr>
      <td colspan="2">
          <div class="purchase_form_invite pre_pfl">
              <label>Invite Friends</label><br />
              <?php if($auth_msg=='This screening is sold out.') {?>
          		<p>Invite Friends to Constellation.tv.</p>
							<?php } else if($auth_msg!='') {?>
              <p>For each person you invite via email or Facebook, get 10 cents off, up to 25% off the total ticket price.</p>
          		<?php } else {?>
          		<p>The more the merrier.</p>
          		<?php } ?>
					</div>
      </td>
    </tr>
		<tr>
      <td colspan="2" height="90" valign="top">
          <span class="button button_gray button_invite_email mrt" onclick="invite.invite('screening','<?php echo $film["screening_unique_key"];?>');"><span class="icon_email"></span> Email invitations</span>
      		<span class="button button_faceblue button_invite_facebook" onclick="fb_invite.invite('screening','<?php echo $film["screening_unique_key"];?>');"><span class="icon_facebook"></span> Invite Facebook friends</span>
      </td>
    </tr>
    <?php if($auth_msg!='') {?>
    <tr>
  		<td height="20" colspan="2" valign="top">
  			<span class="pre_fb special_offer" style="display:none">
	  			<table width="100%" cellpadding="5" cellspacing="5">
		  			<tbody><tr>
							<td width="10" valign="middle">
              	<input type="checkbox" name="facebook_share" id="facebook_share" autocomplete="off" />
	            </td>
	            <td valign="top" class="purchase_form_invite">
								<label>Special Offer</label>: 
								Share this to your Facebook Wall and get $1.00 OFF your ticket.
							</td>
						</tr>
					</tbody></table>
				</span>
			</td>
  	</tr>
  	<tr>
      <td colspan="2" valign="top" height="50">
        <div class="purchase_form_coupon pre_pfl" style="display:none">
					<label>Discount Code (Optional)</label><br />
					<table>
						<tr>
							<td valign="middle">
              <input id="fld-code" name="ticket_code" class="input text" type="text" />	
              </td>
              <td valign="middle">
							<a id="enter-code"  href="javascript:void(0);" class="button button_gray_light">Submit</a>
          		</td>
          	</tr>
          </table>
				</div>
      </td>
    </tr>
  	<?php } ?>
	</table>
</div>
