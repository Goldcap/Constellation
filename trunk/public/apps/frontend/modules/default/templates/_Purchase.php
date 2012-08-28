<?php 
//OK, we have to remove the "screening_" part of the keys
//Sorry, hack
foreach($film as $aspect => $value) {
	$newaspect = str_replace("screening_","",$aspect);
	$film[$newaspect] = $value;
}
?>

<!-- INVITE POPUP -->
<div class="pop_up" id="screening_invite" style="display: none">
  <div class="pop_mid">
  <div class="pop_top popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
      	<p><strong>
        <?php echo $film["film_name"];?> <span class="current_screening_time"></span><br />
        <span class="current_screening_hb">HOSTED BY <span class="current_screening_host"></span></span>
        </strong>
				<span class="bandwidth_warning" style="display: none"><br /><br />Warning: We've detected that you have low bandwidth at your current location. While you can purchase tickets for this or any other screening, you may experience issues with image quality and continuity due to your connection. (<span class="current_bandwidth"></span>k/sec)</span>
				</p>
        <br />
        <div class="title"><strong>Your ticket price: <?php if ($film["film_ticket_price"] == 0) {?>FREE<?php } else {?>$<span class="ticket_price"><?php echo sprintf("%.02f",$film["film_ticket_price"]);?></span><?php } ?></strong><br />
        <?php if ($film["film_ticket_price"] != 0) {?><br />Each friend you invite, get $.10 off, up to 50% off your ticket price!<?php } ?>
        </div>
      	<p><br /><strong>invite friends via:</strong></p>
          <form id="invite" action="#" class="email_enter-form" onSubmit="return false">
              <fieldset>
                <div class="soc-app clearfix">
                  <input type="button" value="facebook" class="btn_small import_click" name="facebook" alt="facebook" />
                  <input type="button" value="twitter" class="btn_small import_click" name="twitter" alt="twitter" />
                  <input type="button" value="gmail" class="btn_small import_click" name="gmail" alt="gmail" />
                  <input type="button" value="yahoo" class="btn_small import_click" name="yahoo" alt="yahoo" />
                  <input type="button" value="hotmail" class="btn_small import_click" name="hotmail" alt="hotmail" />
                  <input type="button" value="aol" class="btn_small import_click" name="aol" alt="aol" />
         			  </div>
         			  <div class="soc-note clearfix">
                  <p><strong>or manually enter email addresses</strong></p>
                </div>
         			  
                <!-- CUSTOM IMPORT -->
                 <div id="import-contacts-container" style="display: none">		  	
                  
                  <h4 id="service_name"></h4>

                  <!-- INVITE LOGIN CONTAINER -->
                  <div class="login" action="#" method="post">		  		
                    
                    <fieldset>		  			
                      <input id="import-contacts-type" type="hidden" name="import-contacts[type]" value="" />					
                          <span id="provider-select" style="display:none;">
                            <select id="provider-alternate" name="provider">
                              <?php foreach ($oi_services as $type=>$providers)	 {?>
                          			<optgroup label='<?php echo $inviter->pluginTypes[$type];?>'>
                          			<?php foreach ($providers as $provider=>$details) {
                                  if (in_array($provider,array('gmail','yahoo','hotmail','aol'))) { continue; }?>
                          				<option value='<?php echo $provider;?>' <?php if ($_POST['provider_box']==$provider) { echo 'selected=\'selected\''; }?>><?php echo $details['name'];?></option>
                          			<?php } ?>
                                </optgroup>
                          		<?php } ?>
                          	</select>
                        	</span>
                          <input name="import-contacts[email]" type="text" class="text import_field" id="import-contacts-email" />						
                          <input name="import-contacts[password]" type="password" class="text import_field" id="import-contacts-password" />						
                          <input type="button" class="btn_tiny" id="btn-import" value="import" alt="import" />					
                      
                        <div id="contacts-error-area" style="color: red"></div>					
                     
                        <input name="import-contacts[provider]" type="hidden" class="text" id="import-contacts-provider" value="0" />
                        
                        <div id="contacts-loading-area"><img src="/images/ajax-loader.gif" alt="loading" /></div>	
                    
                    </fieldset>	
                    	  	
                  </div>	
                  
                </div>
                <!-- END CUSTOM IMPORT -->
                
              	    <div class="left">
              	      <ul id="fld-invites-container">
                        <li class="add_invite" title="add_invite"><div id="add_invite">Click To Add Email</div></li>
                      </ul>
                      <!--<textarea id="fld-invites" name="invite_emails" ></textarea>-->
                      <a id="text-contacts" class="accept-contacts" href="#"></a>
                    </div>
                    <div class="right clearfix">
                        <ul id="accepted-invites-container"></ul>
			              </div>
                    <div class="soc_message">     	       
                      <label id="invite-fld-greeting-label" for="fld-greeting">Add a personal message to your invite <span id="invite-textbox-limit">150 characters</span></label>	        
                      <textarea id="invite-fld-greeting" name="greeting" class="with-label host-invite" rows="2"></textarea>	      
                    </div>
                    <div class="soc_message">
                      <input type="button" id="btn-preview_invite" value="preview" class="btn_small mr_3" name="" />
                      <input type="button" id="btn-invite" value="send" class="btn_small" name="" />
                    </div>
                  <div class="clear"></div>
                  <h2>Number of invites: <span id="number_invites">0</span>
                  <?php if ($film["film_ticket_price"] != 0) {?><br /> new Ticket Price: $<span class="ticket_price"><?php echo sprintf("%.02f",$film["film_ticket_price"]);?></span><?php } ?>
                  </h2>
                  
                  <input type="button" id="go-purchase" value="<?php if ($film["film_ticket_price"] != 0) {?>buy ticket<?php } else {?>get ticket<?php } ?>" class="btn_large-og" name="" />
                  <div style="float: left; margin-left: 10px"><?php echo $film["film_name"];?><br />
                  <span class="current_screening_time"></span><br />
                  <span class="current_screening_hb">Hosted by <span class="current_screening_host"></span></span></div>
                  <input type="hidden" id="fb_session" value="" name="fb_session" />
                  <input type="hidden" id="tw_session" value="" name="tw_session" />
                  <input type="hidden" id="email_session" value="" name="email_session" />
             </fieldset>
          </form>
      </div>
      
  <div class="pop_bot"></div>
  </div>    
</div>
<!-- END INVITE POPUP -->

<!-- PURCHASE POPUP -->
<div class="pop_up" id="screening_purchase" style="display: none">
  <div class="pop_mid">
  <div class="pop_top popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
      	<p><strong>
        <?php echo $film["film_name"];?> <span class="current_screening_time"></span><br />
        <span class="current_screening_hb">HOSTED BY <span class="current_screening_host"></span></span>
        </strong>
				<span class="bandwidth_warning" style="display: none"><br /><br />Warning: We've detected that you have low bandwidth at your current location. While you can purchase tickets for this or any other screening, you may experience issues with image quality and continuity due to your connection. (<span class="current_bandwidth"></span>k/sec)</span>
				</p>
        <br />
        <div class="title"><strong>Your ticket price: <?php if ($film["film_ticket_price"] == 0) {?>FREE<?php } else {?>$<span class="ticket_price"><?php echo sprintf("%.02f",$film["film_ticket_price"]);?></span><?php } ?></strong><br />
        </div>
      	
        <?php if ($film["film_ticket_price"] == 0) {?>
      	<form id="purchase_form" name="purchase_form" action="/screening/<?php echo $film["film_id"];?>/purchase" method="POST" class="buy-ticket_form" onSubmit="return false">
        	<br />
            <fieldset>
                <div class="clearfix"><label>First Name </label> <input id="fld-cc_first_name" name="first_name" class="input" type="text" value="<?php echo $post["first_name"];?>" /></div>
                <div class="clearfix"><label>Last Name </label> <input id="fld-cc_last_name" name="last_name" class="input" type="text" value="<?php echo $post["last_name"];?>" /></div>
                <div class="clearfix"><label>Email Address </label><input id="fld-cc_email" name="email" class="input" type="text" value="<?php echo $post["email"];?>" /></div>
                <div class="clearfix"><label>Confirm Email Address</label> <input id="fld-cc_confirm_email" name="confirm_email" class="input" type="text" value="<?php echo $post["email_confirm"];?>" /></div>
                <div class="clearfix"><label>City</label> <input id="fld-cc_city" name="b_city" class="input" type="text" value="<?php echo $post["b_city"];?>" /></div>
                <div class="clearfix"><label>State Zip</label>
									<input type="text" class="cc-state-text" id="host-fld-cc_state_txt" name="b_state" style="display:none" />
                  <select class="cc-state-drop" id="fld-cc_state" name="b_state">				          
                    <?php foreach ($states as $state) {?>
                      <option value="<?php echo $state -> getAttribute("abbr");?>" <?php if($post["b_state"] == $state -> getAttribute("abbr")) { echo "selected=\"selected\""; }?>><?php echo ucwords(strtolower($state -> getAttribute("name")));?></option>
                    <?php } ?> 
                  </select>
                 <input id="fld-cc_zip" name="b_zipcode" class="short" type="text" value="<?php echo $post["b_zipcode"];?>" />
								</div>
                <div class="clearfix"><label>Country</label>
                  <select class="cc-country-drop" id="fld-cc_state" name="b_country">				          
                    <?php foreach ($countries as $country) {?>
                      <option value="<?php echo $country -> getAttribute("abbr");?>" <?php if($post["b_country"] == $country -> getAttribute("abbr")) { echo "selected=\"selected\""; }?>><?php echo ucwords(strtolower($country -> getAttribute("name")));?></option>
                    <?php } ?> 
                  </select>
                </div>
                <input id="purchase_submit" type="button" value="get ticket" class="btn_large-og" name="" /> 
                <div style="float: left; margin-left: 10px"><?php echo $film["film_name"]?><br />
                <span class="current_screening_time"></span><br />
                <span class="current_screening_hb"> Hosted by <span class="current_screening_host"></span></span></div>
                <input type="hidden" name="screening_free" id="screening_free" value="true" />
                <input type="hidden" id="ticket_price" name="ticket_price" value="0" />
            </fieldset>
      	<?php } else { ?>
      	<div class="code_break">
        	<p>Enter your promo code for a discount, or your exchange code if you are exchanging a ticket you already purchased for this screening:</p>
          <form id="step-enterCode-form" class="buy-ticket_form" action="#">	  
            <fieldset class="data">		      
              <div class="clearfix">
                <label>ENTER CODE: </label>
              	<input id="fld-code" name="ticket_code" class="text" type="text" />		        
              </div>
              <a id="enter-code" class="link" href="javascript:void(0);"><button class="btn_tiny">Submit Code</button></a>		    
            </fieldset>		
          </form>
        </div>
        <form id="purchase_form"  name="purchase_form" action="/screening/<?php echo $film["film_id"];?>/purchase" method="POST" class="buy-ticket_form" onSubmit="return false">
          	<fieldset>
              	<br />
              	  <div class="clearfix">
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
                        <a id="paypal-button" onclick="screening_room.goPaypal()" title="paypal" class="credit-cards"><img src="/images/icon-cards-paypal.png" /></a>						
                      </li>					
                    </ul>
                  </div>
                  <div class="clearfix"><label>First Name (on card) </label> <input id="fld-cc_first_name" name="first_name" class="input" type="text" value="<?php echo $post["first_name"];?>" /></div>
                  <div class="clearfix"><label>Last Name (on card) </label> <input id="fld-cc_last_name" name="last_name" class="input" type="text" value="<?php echo $post["last_name"];?>" /></div>
                  <div class="clearfix"><label>Email Address </label><input id="fld-cc_email" name="email" class="input" type="text" value="<?php echo $post["email"];?>" /></div>
                  <div class="clearfix"><label>Confirm Email Address</label> <input id="fld-cc_confirm_email" name="confirm_email" class="input" type="text" value="<?php echo $post["email_confirm"];?>" /></div>
                  <div class="clearfix"><label>Credit Card</label> <input id="fld-cc_number" name="credit_card_number" class="input" type="text" value="<?php echo $post["card_number"];?>" /></div>
                  <div class="clearfix"><label>Security Code</label> <input id="fld-cc_security_number" name="card_verification_number" class="input" type="text" value="<?php echo $post["security_code"];?>" /></div>
                  <div class="clearfix"><label>Exp Date </label> 
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
                  </div>
                  <div class="clearfix"><label>Billing Address</label> <input id="fld-cc_address1" name="b_address1" class="input" type="text" value="<?php echo $post["b_address1"];?>"" /></div>
                  <div class="clearfix"><label>&nbsp;</label> <input id="fld-cc_address2" name="b_address2" class="input" type="text" value="<?php echo $post["b_address2"];?>" /></div>
                  <div class="clearfix"><label>City</label> <input id="fld-cc_city" name="b_city" class="input" type="text" value="<?php echo $post["b_city"];?>" /></div>
                  <div class="clearfix"><label>State Zip</label>
										<input type="text" class="cc-state-text" id="host-fld-cc_state_txt" name="b_state" style="display:none" />
                    <select class="cc-state-drop" id="fld-cc_state" name="b_state">				          
                      <?php foreach ($states as $state) {?>
                        <option value="<?php echo $state -> getAttribute("abbr");?>" <?php if($post["b_state"] == $state -> getAttribute("abbr")) { echo "selected=\"selected\""; }?>><?php echo ucwords(strtolower($state -> getAttribute("name")));?></option>
                      <?php } ?> 
                    </select>
                   <input id="fld-cc_zip" name="b_zipcode" class="short" type="text" value="<?php echo $post["b_zipcode"];?>" />
									</div>
                  <div class="clearfix"><label>Country</label>
                    <select class="cc-country-drop" id="fld-cc_state" name="b_country">				          
                      <?php foreach ($countries as $country) {?>
                        <option value="<?php echo $country -> getAttribute("abbr");?>" <?php if($post["b_country"] == $country -> getAttribute("abbr")) { echo "selected=\"selected\""; }?>><?php echo ucwords(strtolower($country -> getAttribute("name")));?></option>
                      <?php } ?> 
                    </select>
                  </div>
                  <input id="purchase_submit" type="button" value="buy ticket" class="btn_large-og" name="" />
                  <div style="float: left; margin-left: 10px"><?php echo $film["film_name"]?><br />
                  <span class="current_screening_time"></span><br />
                  <span class="current_screening_hb"> Hosted by <span class="current_screening_host"></span></span></div>
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
  <div class="pop_bot"></div>
  </div>    
</div>
<!-- END PURCHASE POPUP -->

<!-- PURCHASE PROCESS POPUP -->
<div class="pop_up" id="process" style="display: none">
  <div class="pop_mid">
  <div class="pop_top popup-close"></div> 
  	<div class="layout-1 clearfix">
      	<br />
        <div class="title"><strong><span id="purchase_process">Your purchase is being processed.</span></strong></div>
        <br />
        <div style="margin-left: 120px;"><img src="/images/ajax-loader.gif" /></div>
    </div>
  <div class="pop_bot"></div>
  </div>   
</div>
<!-- END PURCHASE PROCESS POPUP -->

<!-- PURCHASE CONFIRM POPUP -->
<div class="pop_up" id="confirm" style="display: none">
  <div class="pop_mid">
  <div class="pop_top popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
      	<p><strong>
        <?php echo $film["film_name"];?> <span class="current_screening_time"></span><br />
        <span class="current_screening_hb">HOSTED BY <span class="current_screening_host"></span></span>
        </strong></p>
        <br />
        <div class="title"><strong><span id="purchase_result">Your purchase was confirmed</span></strong></div>
      	<!--Your screening link is:<br />
        <span id="screening_full_link"></span><br />-->
        <br />
        <a id="screening_click_link" href="#"><button class="btn_small">Enter Theater</button></a>
      </div>
  <div class="pop_bot"></div>
  </div>    
</div>
<!-- END PURCHASE CONFORM POPUP -->
