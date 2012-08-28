<?php if ($sf_user -> isAuthenticated()) {?>
<!-- INVITE POPUP -->
<div class="pops" id="screening_invite" style="display: none">
</div>
<!-- END INVITE POPUP -->
<?php } ?>

<?php if ($sf_user -> isAuthenticated()) {?>
<!-- PURCHASE POPUP -->
<div class="pops pops_wide" id="screening_purchase" style="display: none">
  	<h4>Buy Ticket</h4>
    <div class="film_alt_info">
      <span class="film_alt_name">
        <?php echo $film["film_name"];?><br />
        <span class="current_screening_time">Sunday, August 08 @8:00 PM EST</span><br />
        <span class="current_screening_hb">Hosted By <span class="current_screening_host">Channing Tatum & Jenna Dewan</span></span>
      </span>
      <span class="film_alt_price">
        Ticket Price<br />
        <span class="current_screening_price">
          <?php if ($film["film_ticket_price"] == 0) {?>FREE<?php } else {?><span class="ticket_price">$<?php echo sprintf("%.02f",$film["film_ticket_price"]);?></span><?php } ?>
        </span>
      </span>
    </div>
    <span class="bandwidth_warning" style="display: none">Warning: We've detected that you have low bandwidth at your current location. While you can purchase tickets for this or any other screening, you may experience issues with image quality and continuity due to your connection. (<span class="current_bandwidth"></span>k/sec)<br /></span>
    <?php if ($film["film_ticket_price"] == 0) {?>
  	<form id="purchase_form" name="purchase_form" action="/screening/<?php echo $film["film_id"];?>/purchase" method="POST" class="buy-ticket_form" onSubmit="return false">
    	<br />
        <fieldset>
            <div class="clearfix"><label class="cc_title">Billing Info</label><span class="cc_title">or, pay with paypal</span><span class="paypal_icon"></span></div>
            <div class="clearfix"><label>First Name </label> <input id="fld-cc_first_name" name="first_name" class="input" type="text" value="<?php echo $post["first_name"];?>" /></div>
            <div class="clearfix"><label>Last Name </label> <input id="fld-cc_last_name" name="last_name" class="input" type="text" value="<?php echo $post["last_name"];?>" /></div>
            <div class="clearfix"><label>Email Address </label><input id="fld-cc_email" name="email" class="input" type="text" value="<?php echo $post["email"];?>" /></div>
            <div class="clearfix"><label>Confirm Email</label> <input id="fld-cc_confirm_email" name="confirm_email" class="input" type="text" value="<?php echo $post["email_confirm"];?>" /></div>
            <div class="clearfix"><label>City</label> <input id="fld-cc_city" name="b_city" class="input" type="text" value="<?php echo $post["b_city"];?>" /></div>
            <div class="clearfix"><label>State</label>
							<input type="text" class="cc-state-text" id="host-fld-cc_state_txt" name="b_state" style="display:none" />
              <select class="cc-state-drop" id="fld-cc_state" name="b_state">				          
                <?php foreach ($states as $state) {?>
                  <option value="<?php echo $state -> getAttribute("abbr");?>" <?php if($post["b_state"] == $state -> getAttribute("abbr")) { echo "selected=\"selected\""; }?>><?php echo ucwords(strtolower($state -> getAttribute("name")));?></option>
                <?php } ?> 
              </select>
            </div>
            <div class="clearfix"><label>Zip</label>
             <input id="fld-cc_zip" name="b_zipcode" class="input short" type="text" value="<?php echo $post["b_zipcode"];?>" />
						</div>
            <div class="clearfix"><label>Country</label>
              <select class="cc-country-drop" id="fld-cc_state" name="b_country">				          
                <?php foreach ($countries as $country) {?>
                  <option value="<?php echo $country -> getAttribute("abbr");?>" <?php if($post["b_country"] == $country -> getAttribute("abbr")) { echo "selected=\"selected\""; }?>><?php echo ucwords(strtolower($country -> getAttribute("name")));?></option>
                <?php } ?> 
              </select>
            </div>
            <div class="cc-submit">
              <button id="purchase_submit" class="button uppercase button_orange" type="submit">Buy Ticket</button>
              <!-- <input id="purchase_submit" type="image" src="/images/alt/buy_ticket.png" value="get ticket" class="" name="" />  -->
            </div>
            <!--<div style="float: left; margin-left: 10px"><?php echo $film["film_name"]?><br />
            <span class="current_screening_time"></span><br />
            <span class="current_screening_hb"> Hosted by <span class="current_screening_host"></span></span></div>-->
            <input type="hidden" name="screening_free" id="screening_free" value="true" />
            <input type="hidden" id="ticket_price" name="ticket_price" value="0" />
        </fieldset>
  	<?php } else { ?>
  	<div class="buy-ticket_form">
    	<div class="clearfix"><label class="cc_title">Discount Code</label></div>
      <p>Enter your promo code for a discount, or your exchange code if you are exchanging a ticket you already purchased for this screening:</p>
      <form id="step-enterCode-form" class="buy-ticket_form" action="#">	  
          <div class="clearfix">
            <input id="fld-code" name="ticket_code" class="input dcode" type="text" />
            <a id="enter-code" class="dcode_link button_small" href="javascript:void(0);">Apply Code</a>
          </div>
      </form>
    </div>
    <form id="purchase_form" name="purchase_form" action="/screening/<?php echo $film["film_id"];?>/purchase" method="POST" class="buy-ticket_form" onSubmit="return false">
      	<fieldset>
          	<div class="clearfix"><label class="cc_title">Billing Info</label><span class="cc_title">or, pay with paypal</span><a id="paypal-button" onclick="screening_room.goPaypal()" title="paypal" class=""><span class="paypal_icon"></span></a></div>
            <div class="clearfix"><label>First Name</label> <input id="fld-cc_first_name" name="first_name" class="input" type="text" value="<?php echo $post["first_name"];?>" /></div>
            <div class="clearfix"><label>Last Name</label> <input id="fld-cc_last_name" name="last_name" class="input" type="text" value="<?php echo $post["last_name"];?>" /></div>
            <div class="clearfix"><label>Email Address </label><input id="fld-cc_email" name="email" class="input" type="text" value="<?php echo $post["email"];?>" /></div>
            <div class="clearfix"><label>Confirm Email</label> <input id="fld-cc_confirm_email" name="confirm_email" class="input" type="text" value="<?php echo $post["email_confirm"];?>" /></div>
            <span class="cc_image"></span>
            <div class="clearfix clear"><label>Credit Card</label> <input id="fld-cc_number" name="credit_card_number" class="input" type="text" value="<?php echo $post["card_number"];?>" /></div>
            <div class="clearfix"><label>Security Code</label> <input id="fld-cc_security_number" name="card_verification_number" class="input code" type="text" value="<?php echo $post["security_code"];?>" /> </div>
            <div class="clearfix"><label>Exp Date </label> 
              <select id="fld-cc_exp_month" name="expiration_date_month" class="fld-cc_exp_month">
                <?php for ($i=1;$i<13;$i++) {?>          
                <option value="<?php echo ($i);?>" <?php if ($post["cc_exp_month"] == $i) {?>selected="selected"<? }?>><?php echo sprintf("%02d",$i);?></option>
                <?php } ?>			          
              </select>		         						         						          		          
              <select id="fld-cc_exp_year" name="expiration_date_year" class="fld-cc_exp_year">				            
                <?php for ($i=year();$i<year()+10;$i++) {?>          
                <option value="<?php echo ($i);?>" <?php if ($post["cc_exp_year"] == $i) {?>selected="selected"<? }?>><?php echo sprintf("%02d",$i);?></option>
                <?php } ?>				          
              </select>	
            </div>
            <div class="clearfix"><label>Billing Address</label> <input id="fld-cc_address1" name="b_address1" class="input" type="text" value="<?php echo $post["b_address1"];?>" /></div>
            <div class="clearfix"><label>&nbsp;</label> <input id="fld-cc_address2" name="b_address2" class="input" type="text" value="<?php echo $post["b_address2"];?>" /></div>
            <div class="clearfix"><label>City</label> <input id="fld-cc_city" name="b_city" class="input" type="text" value="<?php echo $post["b_city"];?>" /></div>
             <div class="clearfix"><label>State Zip</label>
							<input type="text" class="cc-state-text" id="host-fld-cc_state_txt" name="b_state" style="display:none" />
              <select class="cc-state-drop" id="fld-cc_state" name="b_state">				          
                <?php foreach ($states as $state) {?>
                  <option value="<?php echo $state -> getAttribute("abbr");?>" <?php if($post["b_state"] == $state -> getAttribute("abbr")) { echo "selected=\"selected\""; }?>><?php echo ucwords(strtolower($state -> getAttribute("name")));?></option>
                <?php } ?> 
              </select>
             <input id="fld-cc_zip" name="b_zipcode" class="input short" type="text" value="<?php echo $post["b_zipcode"];?>" />
						</div>
            <div class="clearfix"><label>Country</label>
              <select class="cc-country-drop" id="fld-cc_state" name="b_country">				          
                <?php foreach ($countries as $country) {?>
                  <option value="<?php echo $country -> getAttribute("abbr");?>" <?php if($post["b_country"] == $country -> getAttribute("abbr")) { echo "selected=\"selected\""; }?>><?php echo ucwords(strtolower($country -> getAttribute("name")));?></option>
                <?php } ?> 
              </select>
            </div>
            <div class="clearfix"><label>Special Offer</label>
              <span class="fb_check">
                <input type="checkbox" name="facebook_share" id="facebook_share" />
              </span>
              <span class="fb_text">
                Share this to your Facebook Wall and get $1.00 OFF your ticket.
              </span>
            </div>
            
            <div class="cc-submit">
              <!-- <input id="purchase_submit" type="image" src="/images/alt/buy_ticket.png" value="get ticket" class="" name="" />  -->
              <button id="purchase_submit" class="button uppercase button_orange" type="submit">Buy Ticket</button>
            </div>
          </fieldset>
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
<?php } ?>

<?php if ($sf_user -> isAuthenticated()) {?>
<!-- PURCHASE PROCESS POPUP -->
<div class="pops" id="process" style="display: none">
	<h4>Your purchase is being processed.</h4>
  <br />
  <div style="margin-left: 120px;"><img src="/images/ajax-loader.gif" /></div>
</div>
<!-- END PURCHASE PROCESS POPUP -->
<?php } ?>

<?php if ($sf_user -> isAuthenticated()) {?>
<!-- PURCHASE CONFIRM POPUP -->
<div class="pops" id="confirm" style="display: none">
 	<h4><?php echo $film["film_name"];?></h4>
	<h4><span class="current_screening_time"></h4>
  <span class="current_screening_hb">Hosted By <span class="current_screening_host"></span></span>
  </strong></p>
  <br />
  <div class="title"><strong><span id="purchase_result">Your purchase was confirmed</span></strong></div>
	<!--Your screening link is:<br />
  <span id="screening_full_link"></span><br />-->
  <br />
  <a id="screening_click_link" href="#"><img src="/images/alt/enter_theater.png" border="0" /></a> 
</div>
<!-- END PURCHASE CONFORM POPUP -->
<?php } ?>
