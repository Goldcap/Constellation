<div class="image_gallery small">
	<div class="main_image"><img src="/images/temp_images/scroll-main.jpg" alt="" /></div>
    <div class="navigation"><a href="" class="previous">previous</a><a href="" class="next">next</a></div>
    <div class="scroll">
        <ul>
            <li><a href=""><img src="/images/temp_images/scroll-off.jpg" alt="" /></a></li>
            <li><a href=""><img src="/images/temp_images/scroll-off.jpg" alt="" /></a></li>
            <li><a href=""><img src="/images/temp_images/scroll-off.jpg" alt="" /></a></li>
            <li><a href=""><img src="/images/temp_images/scroll-on.jpg" alt="" /></a></li>
            <li><a href=""><img src="/images/temp_images/scroll-off.jpg" alt="" /></a></li>
            <li><a href=""><img src="/images/temp_images/scroll-off.jpg" alt="" /></a></li>
            <li><a href=""><img src="/images/temp_images/scroll-off.jpg" alt="" /></a></li>
        </ul>
    </div>
</div>
<div id="host" class="reqs"><?php echo $chat_instance_host;?></div>
<div id="port" class="reqs"><?php echo $chat_instance_port_base;?></div>


<?php if ($sf_user -> isAuthenticated()) {?>
<!-- HOST A SCREENING POPUP -->
<div class="pop_up" id="subscription_details" style="display: none">
  <div class="pop_mid">
  <div class="pop_top popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
      	<div class="title"><strong>BUY TICKETS AND SUBSCRIPTIONS</strong>  </div>
      	 
         <form id="detail-form" action="#" class="buy-ticket_form" onSubmit="return false;">
         
          <fieldset class="subscription_buttons">		
              <button id="btn-gift" class="btn_tiny" alt="purchase gift tickets">Tickets</button>
              <button id="btn-subscription" class="btn_tiny" alt="purchase subscriptions">Subscription</button>
          </fieldset>
          
          <div id="gift_info" class="subscriptions" style="display:none">
           <fieldset>
              
              <div class="title pad_bot">Purchase Tickets</div>
              
              <div class="clearfix">
                <label>Number of Tickets:</label>		        
                <input id="fld-gift_ticket_number" name="gift_ticket_number" type="text" value="<?php echo $post["gift_ticket_number"];?>" size="6" />		      
              </div>
              
              <div class="clearfix">
                <label>Ticket Price:</label>
                <select id="fld-gift_ticket_price" name="fld-gift_ticket_price">
                  <option value="2.99">$2.99</option>
                  <option value="3.99">$3.99</option>
                  <option value="4.99">$4.99</option>
                  <option value="5.99">$5.99</option>
                </select>
              </div>
              
              <div class="clearfix">
                <input type="button" id="btn-gift-next" class="btn_tiny" value="next" alt="next step"  />
              </div>	        
            </fieldset>
          </div>
          
          <div id="subscription_info" class="subscriptions" style="display:none">
              
              <div class="title pad_bot">Purchase Subscriptions</div>
              
              <fieldset>
                <div class="clearfix">
                  <label for="fld-subscription_ticket_number">Number of Tickets:</label>		        
                  <input id="fld-subscription_ticket_number" name="subscription_ticket_number" class="formfield-security" type="text" value="<?php echo $post["subscription_ticket_number"];?>" size="6" />		      
                </div>
                
                
                <div class="clearfix">
                <label for="fld-subscription_date">Choose A Start Date:</label><input type="text" name="fld-subscription_date" id="subscription_date" value="" />
                </div>
              
                <div class="clearfix">
                <label for="fld-subscription_term">Repeating:</label>
                    <select name="fld-subscription_term" id="fld-subscription_term">
                      <option value="weekly">Weekly</option>
                      <option value="bi-weekly">Bi-Weekly</option>
                      <option value="monthly">Monthly</option>
                      <option value="yearly">Yearly</option>
                    </select>
                </div>
                
                <div class="clearfix">   
                  <label for="fld-subscription_period">Lasting:</label>
                  <input id="fld-subscription_period" name="fld-subscription_period" class="formfield-security" type="text" value="<?php echo $post["subscription_period"];?>" size="6" />		      
                  <span id="period-term"></span>
                </div>
                
                <div class="clearfix">
                <label for="fld-subscription_ticket_price">Ticket Price:</label>
                    <select name="fld-subscription_ticket_price">
                      <option value="2.99">$2.99</option>
                      <option value="3.99">$3.99</option>
                      <option value="4.99">$4.99</option>
                      <option value="weekly">$5.99</option>
                    </select>
                </div>
                
                <div class="clearfix">
                <input type="button" id="btn-subscribe-next" class="btn_tiny subscription-next" value="next" alt="next step"  />	        
                </div>
              </fieldset>
           </div>
          <input type="hidden" name="step" id="form-subscription-step" value="" />	
        </form>
      </div>
      
  <div class="pop_bot"></div>
  </div>    
</div>
<!-- END HOST A SCREENING POPUP -->
<?php } ?>

<?php if ($sf_user -> isAuthenticated()) {?>
<!-- PURCHASE FROM HOST POPUP -->
<div class="pop_up" id="subscription_purchase" style="display: none">
  <div class="pop_mid">
  <div class="pop_top popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
      	<div class="title"><strong>Total price: $<span id="total_price"><?php echo sprintf("%.02f",$film["film_setup_price"]);?></span></strong></div>
      	<form id="subscription_purchase_form" action="#" class="buy-ticket_form" onSubmit="return false">
          	<fieldset>
              	<br />
                  <!-- TICKET OPTIONS -->
                  <div id="ticket-payment-details" class="border_under" style="display: none">
                    <div class="clearfix">		        
                      <label for="fld-gift_ticket_number">Number of Tickets:</label>		        
                      <span id="ticket_number" style="font-weight: bold"><?php echo $subscription["subscription_ticket_number"];?></span>	      
                    </div>
                    <div class="clearfix">
                      <label for="fld-gift_ticket_price">Ticket Price:</label>
                      <span id="ticket_price" style="font-weight: bold"><?php echo sprintf("%.02f",$subscription["subscription_ticket_price"]);?></span>
                    </div>
                  </div>
                     
                  <!-- SUBSCRIPTION OPTIONS -->
                  <div id="subscription-payment-details" class="border_under" style="display: none">
                    <div class="formfield">		        
                      <label for="fld-subscription_ticket_number">Number of Tickets:</label>		        
                      <span id="subscription_ticket_number" style="font-weight: bold"><?php echo $subscription["subscription_ticket_number"];?></span>      
                    </div>
                    <div class="clearfix">
                      <label for="fld-subscription_date">Start Date:</label><br />
                      <span id="subscription_start_date" style="font-weight: bold"><?php echo formatDate($subscription["subscription_start_date"],"pretty");?></span>
                    </div>
                    <div class="clearfix">
                      <label for="fld-subscription_term">Repeating:</label>
                      <span id="subscription_term" style="font-weight: bold"><?php echo ucwords($subscription["subscription_term"]);?></span>
                    </div>
                    <div class="clearfix">
                      <label for="fld-subscription_period">Lasting:</label>
                      <span id="subscription_period" style="font-weight: bold"><?php echo ucwords($subscription["subscription_period"]);?></span>      
                      <span id="period-term"></span>
                    </div>
                    <div class="clearfix">
                        <label for="fld-subscription_ticket_price">Ticket Price:</label>
                        <span id="subscription_ticket_price" style="font-weight: bold"><?php echo sprintf("$%.02f",$subscription["subscription_ticket_price"]);?></span>
                    </div>
                  </div>
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
                        <a id="paypal-button" title="paypal" class="credit-cards" href="/services/Paypal/express/host?vars=<?php echo $screening?>-<?php echo $film["film_id"];?>"><img src="/images/icon-cards-paypal.png" /></a>						
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
                      <option value="01" >01</option>				            
                      <option value="02" >02</option>				            
                      <option value="03" >03</option>				            
                      <option value="04" >04</option>				            
                      <option value="05" >05</option>				            
                      <option value="06" >06</option>				            
                      <option value="07" >07</option>				            
                      <option value="08" >08</option>				            
                      <option value="09" >09</option>				            
                      <option value="10" >10</option>				            
                      <option value="11" >11</option>				            
                      <option value="12" >12</option>				          
                    </select>		         						         						          		          
                    <select id="fld-cc_exp_year" name="expiration_date_year">				            
                      <option value="2011" >2011</option>				            
                      <option value="2012" >2012</option>				            
                      <option value="2013" >2013</option>				            
                      <option value="2014" >2014</option>				            
                      <option value="2015" >2015</option>				            
                      <option value="2016" >2016</option>				            
                      <option value="2017" >2017</option>				            
                      <option value="2018" >2018</option>				            
                      <option value="2019" >2019</option>				            
                      <option value="2020" >2020</option>				          
                    </select>
                  </div>
                  <div class="clearfix"><label>Billing Address</label> <input id="fld-cc_address1" name="b_address1" class="input" type="text" value="<?php echo $post["b_address1"];?>"" /></div>
                  <div class="clearfix"><label>&nbsp;</label> <input id="fld-cc_address2" name="b_address2" class="input" type="text" value="<?php echo $post["b_address2"];?>" /></div>
                  <div class="clearfix"><label>City</label> <input id="fld-cc_city" name="b_city" class="input" type="text" value="<?php echo $post["b_city"];?>" /></div>
                  <div class="clearfix"><label>State Zip</label>
                    <select id="fld-cc_state" name="b_state">				          
                      <?php foreach ($states as $state) {?>
                        <option value="<?php echo $state -> getAttribute("abbr");?>" <?php if($post["b_state"] == $state -> getAttribute("abbr")) { echo "selected=\"selected\""; }?>><?php echo ucwords(strtolower($state -> getAttribute("name")));?></option>
                      <?php } ?> 
                    </select>
                   <input id="fld-cc_zip" name="b_zipcode" class="short" type="text" value="<?php echo $post["b_zipcode"];?>" /></div>
                  <input id="subscription_purchase_submit" type="button" value="buy ticket" class="btn_large-og" name="" /> <span><?php echo $film["film_name"]?>, 7:00pm EST, January 3, 2011. Hosted by Madeleine Sackler"</span>
              </fieldset>
          </form>
      </div>
  <div class="pop_bot"></div>
  </div>    
</div>
<!-- END PURCHASE FROM HOST POPUP -->
<?php } ?>


<?php if ($sf_user -> isAuthenticated()) {?>
<!-- INVITE FROM HOST POPUP -->
<div class="pop_up" id="subscription_invite" style="display: none">
  <div class="pop_mid">
  <div class="pop_top popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
      	<div class="title"><strong>Invite Your Friends!</strong></div>
      	<p><br /><strong>invite friends via:</strong></p>
          <form id="host-invite" action="#" class="email_enter-form" onSubmit="return false">
              <fieldset>
                <div class="soc-app clearfix">
                  <input type="button" value="facebook" class="btn_small subscription_import_click" name="facebook" alt="facebook" />
                  <input type="button" value="twitter" class="btn_small subscription_import_click" name="twitter" alt="twitter" />
                  <input type="button" value="gmail" class="btn_small subscription_import_click" name="gmail" alt="gmail" />
                  <input type="button" value="yahoo" class="btn_small subscription_import_click" name="yahoo" alt="yahoo" />
                  <input type="button" value="msn" class="btn_small subscription_import_click" name="msn" alt="msn" />
                  <input type="button" value="aol" class="btn_small subscription_import_click" name="aol" alt="aol" />
         			  </div>
         			    
                <!-- CUSTOM IMPORT -->
                 <div id="host-import-contacts-container" style="display: none">		  	
                  
                  <!-- INVITE LOGIN CONTAINER -->
                  <div class="login" action="#" method="post">		  		
                    
                    <fieldset>		  			
                      <input id="import-contacts-type" type="hidden" name="import-contacts[type]" value="" />					
                         <img src="" id="contact-source" />
                          <span id="provider-select" style="display:none;">
                            <select id="host-provider-alternate" name="provider">
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
                          <label>Email: </label> <input name="import-contacts[email]" type="text" class="text" id="host-import-contacts-email" />						
                          <label>Password: </label> <input name="import-contacts[password]" type="password" class="text" id="host-import-contacts-password" />						
                          <input type="button" id="host-btn-import" class="btn_tiny" value="import" alt="import" />					
                      
                        <div id="host-contacts-error-area" style="color: red"></div>					
                     
                        <input name="import-contacts[provider]" type="hidden" class="text" id="host-import-contacts-provider" value="0" />
                        
                        <div id="host-contacts-loading-area"><img src="/images/ajax-loader.gif" alt="loading" /></div>	
                    
                    </fieldset>	
                    	  	
                  </div>	
                  
                  <!-- INVITE IMPORT CONTAINER -->
                  <div class="import-contact-result" id="host-contacts-area"  style="display: none">			
                    <label id="all_contacts" for="checkAll" >
                      <input id="checkAll" type="checkbox" name="checkall" uncheck/>
                      <span>Select All Contacts</span>
                    </label>				
                    <div class="host-invite-content"></div>				
                    <input id="host-grab-contacts" type="button" class="btn_tiny host-accept-contacts" id="host-grab-contacts" alt="import" value="import" />
                  </div>
                  	  
                </div>
                <!-- END CUSTOM IMPORT -->
                
              	<p><strong>or manually enter email adresses</strong><br />(separate with commas)</p>
                  
                    <div class="left"><textarea id="host-fld-invites" name="invite_emails" ></textarea><a id="text-contacts" class="host-accept-contacts" href="#"></a></div>
                    <div class="right clearfix">
                        <ul id="host-accepted-invites-container"></ul>
			              </div>
                    <div class="soc_message">     	
                      <p class="legend">Personalize your invite:</p>	        
                      <label id="host-invite-fld-greeting-label" for="fld-greeting">Add a personal message to your invite <span id="host-invite-textbox-limit">150 characters</span></label>	        
                      <textarea id="host-invite-fld-greeting" name="greeting" class="with-label host-invite" rows="2"></textarea>	      
                    </div>
                    <div class="soc_message">
                        <input type="button" id="host-btn-preview_invite" value="preview" class="btn_small mr_3" name="" />
                        <input type="button" id="btn-host-invite" value="send" class="btn_small" name="" />
                    </div>
                  <div class="clear"></div>
                  <input type="button" id="host-go-purchase" value="invite friends" class="btn_large-og" name="" />
             </fieldset>
          </form>
      </div>
      
  <div class="pop_bot"></div>
  </div>    
</div>
<!-- END INVITE FROM HOST POPUP -->
<?php } ?>

<?php if ($sf_user -> isAuthenticated()) {?>
<!-- PURCHASE FROM HOST CONFIRM POPUP -->
<div class="pop_up" id="subscription_confirm" style="display: none">
  <div class="pop_mid">
  <div class="pop_top popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
      	<div class="title"><strong>Your purchase was confirmed</strong></div>
      	Your screening link is:<br />
        <span id="subscription_screening_full_link"></span><br />
        <br />
        <a id="subscription_screening_link" href="#"><button class="btn_small">Enter Screening</button></a>
      </div>
  <div class="pop_bot"></div>
  </div>    
</div>
<!-- END PURCHASE FROM HOST CONFORM POPUP -->
<?php } ?>

<div id="subscription_cost" style="display:none"><?php echo $afilm["film_setup_price"];?></div>
<div id="domain" style="display:none"><?php echo sfConfig::get("app_domain");?></div>
<div id="subscription" style="display:none"><?php if (isset($subscription)) {echo $subscription;}?></div>
