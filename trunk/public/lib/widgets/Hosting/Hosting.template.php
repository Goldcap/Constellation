<!--<div class="inner_container clearfix">
	<div class="clearfix">  
			<h4 class="no_show">Sorry, showtime hosting isn't live yet. Check back soon!</h4>
  </div>
</div>-->  

<?php 
//return;
$ttz = date_default_timezone_get();
$zones = zoneList(); 
?>

<div class="inner_container clearfix host_step_one">
    
  <div class="host_show_header">
      <h3>Host a showtime of <span><?php echo $film["film_name"];?></span></h3>
      <ul class="host_show_steps">
          <li>1. Select a Film</li>
          <li class="host_show_steps_sep">&raquo;</li>
          <li>2. Date &amp; Time</li>
          <li class="host_show_steps_sep">&raquo;</li>
          <li>3. Buy Ticket</li>
      </ul>
  </div>
  
  <div class="host_container">
      <div class="host_container_top"></div>
      <div class="host_container_center clearfix">
          
          <form id="host_form_one" action="/host/<?php echo $film["film_id"];?>/detail" name="host_detail" method="POST" onSubmit="return false;">
          
					<div class="film_details">
              <img src="/uploads/screeningResources/<?php echo $film["film_id"];?>/logo/poster<?php echo $film["film_logo"];?>" alt="<?php echo $film["film_name"];?>" width="150" height="220"/>
          </div>
          <div class="host_form clearfix">
              <table>
        	    	<tr height="20">
        	    		<td valign="bottom">
        	    			<label>Date</label>
        	    		</td>
        	    		<td valign="bottom">
        	    			<label for="last_name">Time</label>
        	    		</td>
        	    	</tr>
        	    	<tr height="40">
        	    		<td valign="top">
        	    			<input type="text" id="host_date" name="fld-host_date" value="Select a date">
        	    		</td>
        	    		<td valign="top">
        	    			<input type="text" id="host_time" name="fld-host_time" value="Select a time">
        	    		</td>
        	    	</tr>
        	    	<tr height="20">
        	    		<td valign="bottom">
        	    			<label for="zip">Time Zone</label>
        	    		</td>
        	    		<td>
        	    		</td>
        	    	</tr>
        	    	<tr height="40">
        	    		<td valign="top">
        	    			<div class="selectOveride">
                      <div class="displayDiv">Select your timezone</div>
                      <select id="tzSelectorPop" name="tzSelector">
											  <?php foreach (array_keys($zones) as $zone) {?>
													<optgroup label="<?php echo strtoupper($zone);?>">
														<?php foreach($zones[$zone] as $key => $atz) {?>
															<option value="<?php echo $key;?>" <?php if ($ttz == $key) {?>selected="selected"<?php } ?>>&nbsp;&nbsp;&nbsp;<?php echo $atz;?></option>
														<?php } ?>
													</optgroup>
													<?php } ?>
											</select>
                  	</div>
        	    		</td>
        	    		<td>
										&nbsp;&nbsp;<input type="checkbox" name="video_host" /> <label class="checkbox_label">Host with a video camera?</label>
        	    		</td>
        	    	</tr>
        	    </table>
              <div class="form_row">
                  <button id="host_first" class="button_orange button uppercase">&laquo; Back</button>
                  <button id="host_next" type="submit" class="button button_orange uppercase">Next &raquo;</button>
              </div>
              <input type="hidden" name="type" class="type" value="detail" />		
	            <input type="hidden" value="<?php echo $film["film_id"];?>" class="unique_key" name="film_id" />
	            <input type="hidden" id="host_id" name="host_id" value="<?php echo $sf_user -> getAttribute("user_id");?>" />
	            <input type="hidden" id="session_id" name="session_id" value="<?php echo session_id();?>" />
              <input type="hidden" id="setup_price" name="setup_price" value="<?php echo $film["film_setup_price"];?>" />		
	            
          	</form>
          </div>
          
      </div>
      <div class="host_container_bottom"></div>
  </div>
</div>

<div class="inner_container clearfix host_step_two" style="display:none">
    
  <div class="host_show_header">
      <h3>Host a showtime of <span><?php echo $film["film_name"];?></span></h3>
      <ul class="host_show_steps">
          <li>1. Select a Film</li>
          <li class="host_show_steps_sep">&raquo;</li>
          <li>2. Date &amp; Time</li>
          <li class="host_show_steps_sep">&raquo;</li>
          <li class="active">3. Buy Ticket</li>
      </ul>
  </div>
  
  <div class="host_container">
      <div class="host_container_top"></div>
      <div class="host_container_center clearfix">
          <div class="film_details">
              <img src="/uploads/screeningResources/<?php echo $film["film_id"];?>/logo/poster<?php echo $film["film_logo"];?>" alt="<?php echo $film["film_name"];?>" width="150" height="220"/>
              <ul class="hosted_by">
                  <li class="film_title"><?php echo $film["film_name"];?></li>
                  <li class="film_Time current_host_screening_time"></li>
                  <li class="film_host">Hosted by You</li>
                  
	                <div class="ticket_price">
		                <p class="label">Ticket Price</p>
		                <p class="price"><span class="green">$<?php echo $film["film_setup_price"];?></span></p>
		                <p class="discount">- <span class="green">.10</span> <strong>X</strong> <strong id="total_friends" class="count_friends">0</strong> friends</p>
		                <p class="host-ticket_price total green">$<?php echo $film["film_setup_price"];?></p>
	                </div> 
              </ul>                             
          </div>
          <div class="host_form">
          <form id="host_form_two" name="host_purchase" action="/host/<?php echo $film["film_id"];?>/purchase" method="POST" onSubmit="return false">
        	    <table>
        	    	<?php if ($film["film_ticket_price"] > 0) {?>
								<tr>
        	    		<td colspan="2">
        	    			<label>We Accept: </label>									
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
                      <li id="paypal">							
                        &nbsp;&nbsp;or click: &nbsp;
                      </li>
                      <li id="paypal">							
                        <a id="paypal-button" onclick="host_screening.goPaypal()" title="paypal" class="credit-card"><img src="/images/icon-cards-paypal.png" /></a>						
                      </li>					
                    </ul>
									</td>
        	    	</tr>
        	    	<?php } ?>
        	    	<tr height="20">
        	    		<td valign="bottom">
        	    			<label for="first_name">First Name</label>
        	    		</td>
        	    		<td valign="bottom">
        	    			<label for="last_name">Last Name</label>
        	    		</td>
        	    	</tr>
        	    	<tr height="40">
        	    		<td valign="top">
        	    			<input id="host-fld-cc_first_name" name="first_name" class="input" type="text" value="<?php echo $post["first_name"];?>" />
        	    		</td>
        	    		<td valign="top">
        	    			<input id="host-fld-cc_last_name" name="last_name" class="input" type="text" value="<?php echo $post["last_name"];?>" />
        	    		</td>
        	    	</tr>
        	    	<tr height="20">
        	    		<td valign="bottom">
        	    			<label for="email">E-mail Address</label>
        	    		</td>
        	    		<td valign="bottom">
        	    			<label for="email_confirm">Confirm E-mail Address</label>
        	    		</td>
        	    	</tr>
        	    	<tr height="40">
        	    		<td valign="top">
        	    			<input id="host-fld-cc_email" name="email" class="input" type="text" value="<?php echo $post["email"];?>" />
        	    		</td>
        	    		<td valign="top">
        	    			<input id="host-fld-cc_confirm_email" name="confirm_email" class="input" type="text" value="<?php echo $post["email_confirm"];?>" />
        	    		</td>
        	    	</tr>
        	    	<tr>
        	    		<td colspan="2">
			        	    <div class="form_row">
			                    <label>Invite Friends</label>
			                    <p>For each friend you invite via email or Facebook, receive 10 cents off, up to 25% off your ticket price.</p>
			                    <span class="button button_gray button_invite_email" onclick="invite.invite('host','');"><span class="icon_email"></span> Email invitations</span>
			                		<span class="button button_faceblue button_invite_facebook" onclick="fb_invite.invite('host','');"><span class="icon_facebook"></span> Invite Facebook friends</span>
			              </div>
									</td>
        	    	</tr>
        	    	<?php if ($film["film_ticket_price"] > 0) {?>
        	    	<tr height="20">
        	    		<td valign="bottom">
        	    			<label for="credit_card">Credit Card</label>
        	    		</td>
        	    		<td valign="bottom">
        	    			<table cellpadding="0" cellspacing="0" width="100%">
        	    				<tr>
        	    					<td width="40%">
        	    						<label>Code</label>
        	    					</td>
        	    					<td>
        	    						<label>Expiration</label>
        	    					</td>
        	    				</tr>
        	    			</table>
        	    		</td>
        	    	</tr>
        	    	<tr height="40">
        	    		<td valign="top">
        	    			<input id="host-fld-cc_number" name="credit_card_number" class="input" type="text" value="<?php echo $post["card_number"];?>" />
        	    		</td>
        	    		<td valign="top">
        	    			<table cellpadding="0" cellspacing="0" width="100%">
        	    				<tr>
        	    					<td width="40%">
        	    						<input id="host-fld-cc_security_number" name="card_verification_number" class="input" type="text" value="<?php echo $post["security_code"];?>" />
        	    					</td>
        	    					<td>
        	    					
        	    					<div class="selectOveride shortyOveride">
												<div class="displayDiv">Month</div>
												<select id="fld-cc_exp_month" name="expiration_date_month" class="shorty">
	                      <?php for ($m=1;$m<13;$m++) {?>          
		                      <option value="<?php echo ($m);?>" <?php if ($post["cc_exp_month"] == $m) {?>selected="selected"<? }?>><?php echo sprintf("%02d",$m);?></option>
		                      <?php } ?>			          
		                    </select>			         						         						          		          
		                    </div>
		                    
        	    					<div class="selectOveride shortyOveride">
												<div class="displayDiv">Year</div>			            
		                    <select id="fld-cc_exp_year" name="expiration_date_year" class="shorty">	
	                        <?php for ($i=year();$i<year()+10;$i++) {?>          
		                      <option value="<?php echo ($i);?>" <?php if ($post["cc_exp_year"] == $i) {?>selected="selected"<? }?>><?php echo sprintf("%02d",$i);?></option>
		                      <?php } ?>          
		                    </select>
		                    </div>
		                    
        	    					</td>
        	    				</tr>
        	    			</table>
        	    		</td>
        	    	</tr>
        	    	<tr height="20">
        	    		<td valign="bottom">
        	    			<label>Billing Address</label>
        	    		</td>
        	    		<td valign="bottom">
        	    			<label>Apt/Unit</label>
        	    		</td>
        	    	</tr>
        	    	<tr height="40">
        	    		<td valign="top">
        	    			<input id="host-fld-cc_address1" name="b_address1" class="input" type="text" value="<?php echo $post["b_address1"];?>" />
        	    		</td>
        	    		<td valign="top">
        	    			<input id="host-fld-cc_address2" name="b_address2" class="input" type="text" value="<?php echo $post["b_address2"];?>" />
        	    		</td>
        	    	</tr>
        	    	<tr height="20">
        	    		<td valign="bottom">
        	    			<label for="city">City</label>
        	    		</td>
        	    		<td valign="bottom">
        	    			<label for="state">State</label>
        	    		</td>
        	    	</tr>
        	    	<tr height="40">
        	    		<td valign="top">
        	    			<input id="host-fld-cc_city" name="b_city" class="input" type="text" value="<?php echo $post["b_city"];?>" />
        	    		</td>
        	    		<td valign="top">
        	    			<div class="selectOveride">
                      <div class="displayDiv">Select your state</div>
                  		<select class="cc-state-drop" id="host-fld-cc_state" name="b_state">				          
                      <?php foreach ($states as $state) {?>
                        <option value="<?php echo $state -> getAttribute("abbr");?>" <?php if($post["b_state"] == $state -> getAttribute("abbr")) { echo "selected=\"selected\""; }?>><?php echo ucwords(strtolower($state -> getAttribute("name")));?></option>
                      <?php } ?> 
                    </select>
                  </div>
        	    		</td>
        	    	</tr>
        	    	<tr height="20">
        	    		<td valign="bottom">
        	    			<label for="host-fld-cc_zip">Zip Code</label>
        	    		</td>
        	    		<td valign="bottom">
        	    			<label for="country">Country</label>
        	    		</td>
        	    	</tr>
        	    	<tr height="40">
        	    		<td valign="top">
        	    			<input id="host-fld-cc_zip" name="b_zipcode" class="short" type="text" value="<?php echo $post["b_zipcode"];?>" />
        	    		</td>
        	    		<td valign="top">
        	    			<div class="selectOveride">
                      <div class="displayDiv">Select your country</div>
                  		<select class="cc-country-drop" id="host-fld-cc_state" name="b_country">				          
	                      <?php foreach ($countries as $country) {?>
	                        <option value="<?php echo $country -> getAttribute("abbr");?>" <?php if($post["b_country"] == $country -> getAttribute("abbr")) { echo "selected=\"selected\""; }?>><?php echo ucwords(strtolower($country -> getAttribute("name")));?></option>
	                      <?php } ?> 
	                    </select>
                  	</div>
        	    		</td>
        	    	</tr>
        	    	<?php } ?>
        	    </table>
              <div class="form_row">
                  <button id="host_last" class="button_orange button uppercase">&laquo; Back</button>
                  <button id="host_purchase_submit" type="submit" class="button_green button uppercase">Purchase</button>
              </div>
              <input type="hidden" name="host_invite_count" id="host_invite_count" value="0" />	
              <input type="hidden" name="film_id" class="s_id" value="<?php echo $film["film_id"];?>" />		
              <input type="hidden" name="ticket_code" id="ticket-code" value="false" />		
           		<input type="hidden" id="setup_price" name="setup_price" value="<?php echo $film["film_setup_price"];?>" />		
	            
              </form>
          </div>
      </div>
      <div class="host_container_bottom"></div>
  </div>
</div>

<!-- PURCHASE FROM HOST PROCESS POPUP -->
<div class="pop_up_alt" id="process" style="display: none">
  	<div class="layout-1 clearfix">
      	<br />
        <div class="title"><strong><span id="purchase_process">Your purchase is being processed.</span></strong></div>
        <br />
        <div style="margin-left: 120px;"><img src="/images/ajax-loader.gif" /></div>
    </div>
</div>
<!-- END PURCHASE FROM HOST PROCESS POPUP -->

<!-- PURCHASE FROM HOST CONFIRM POPUP -->
<div class="pop_up_alt" id="host_confirm" style="display: none">
 	<div class="layout-1 clearfix">
    	<div class="title"><strong><?php echo $film["film_name"];?> <span class="current_host_screening_time"></span></strong></div>
      <br />
      <div class="title"><strong>Your purchase was confirmed</strong></div>
    	<!--Your screening link is:<br />
      <span id="host_screening_full_link"></span><br />-->
      <p class="pad_bot">Thank you. Your purchase has been processed and your showtime has been added to the schedule for this film. You can enter the theater for your showtime via the "My Showtimes" link in the navigation banner, or via the film page for this movie.</p>
			<br />
      <a id="host_screening_link" href="#"><button class="btn_small">Enter Screening</button></a>
    </div>
</div>
<!-- END PURCHASE FROM HOST CONFORM POPUP -->

<div id="gbip" style="display:none"><?php echo $gbip;?></div>
<div id="host_cost" style="display:none"><?php echo $film["film_setup_price"];?></div>
<div id="ticket_cost" style="display:none"><?php echo $film["film_ticket_price"];?></div>
<div id="domain" style="display:none"><?php echo sfConfig::get("app_domain");?></div>
<div id="film" style="display:none"><?php if (isset($film["film_id"])) {echo $film["film_id"];}?></div>
<div id="film_start_offset" style="display:none"><?php echo $film_start_offset;?></div>
<div id="film_end_offset" style="display:none"><?php echo $film_end_offset;?></div>
<div id="screening" style="display:none"><?php if (isset($screening)) {echo $screening;}?></div>
<div id="current_date" style="display:none"></div>
<div id="thistime" style="display:none"><?php if (isset($thistime)) {echo $thistime;}?></div>
<?php if ($film["film_allow_hostbyrequest"] == 1) {?>
  <div id="dohbr_ticket_price" style="display:none"><?php if (isset($film["film_hostbyrequest_price"])) {echo $film["film_hostbyrequest_price"];}?></div>
<?php } ?>


<!-- HOME INVITE -->
<?php include_component('default', 
                        'InviteAlt')?>
<!-- HOME INVITE -->

