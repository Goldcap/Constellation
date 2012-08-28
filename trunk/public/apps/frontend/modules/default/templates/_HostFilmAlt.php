<?php if ($sf_user -> isAuthenticated()) {?>
<!-- HOST A SCREENING POPUP -->
<div class="pop_up" id="host_details" style="display: none">
  <div class="pop_mid">
  <div class="pop_top popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
      	<div class="title"><strong>HOST A SCREENING <?php echo sprintf("$%.02f",$film["film_setup_price"]);?></strong>  </div>
      	<p class="pad_bot"><strong>Choose date &amp; time, invite your friends, BUY A TICKET</strong></p>
          <form id="detail-form" action="/host/<?php echo $film["film_id"];?>/detail" name="host_detail" class="host-form" method="POST" onSubmit="return false;">
              <fieldset>
                <p class="pad_bot"><span>
                
                  <span class="col1"><strong>CHOOSE A</strong></span>
                  <span class="col2">
                  <label>Date</label>
                  </span>
                  <span class="col3">
                    <input id="host_date" name="fld-host_date" type="text" /> &nbsp;&nbsp;&nbsp;
                    <label>time</label> <input id="host_time" name="fld-host_time" type="text" />
                  </span>
                </span></p>
                
                <div class="info_box">
                	<div class="upload_box clearfix">
                        <p><strong>host image</strong></p>
                        <div style="display:none">
                          <div id="FORM_host_image_original" class="swfuploader">
                            <input type="file" id="FILE_host_image_original" name="FILE_host_image_original" />
                          </div>
                        </div>
                        <span id="host_image_original_preview_wrapper" style="float: left;">
                          <img id="host_image_original_preview" name="host_image_original_preview" src="/images/temp_images/host.png" alt="" />
                        </span>
                        <span id="host_image_intro_text" style="float: left;">Upload an image for your host profile </span>
                        <a id="REF_host_image_original" href="#FORM_host_image_original">
                          <input type="button" name="BUTTON_host_image_original" class="btn_small" value="Upload Image" />
                        </a>
                     </div>
                     <div>
											<p><strong>timezone</strong></p>
											<div>
												<select name="tzSelector" id="tzSelector">
													<?php foreach (array_keys($zones) as $zone) {?>
													<optgroup label="<?php echo strtoupper($zone);?>">
														<?php foreach($zones[$zone] as $key => $atz) {?>
															<option value="<?php echo $key;?>" <?php if ($ttz == $key) {?>selected="selected"<?php } ?>>&nbsp;&nbsp;&nbsp;<?php echo $atz;?></option>
														<?php } ?>
													</optgroup>
													<?php } ?>
												</select>
											</div>
											<p>&nbsp;</p>
											<p><input type="checkbox" name="video_host" value="1" /> <strong>host with video camera</strong></p>
											
										 </div>
		            </div>
               
               <input type="submit" id="host_submit" value="invite friends & pay" class="btn_large-og" name="" />
               <input type="hidden" name="type" class="type" value="detail" />		
               <input type="hidden" name="setup_price" value="<?php echo $film["film_setup_price"];?>" />		
               <input type="hidden" value="<?php echo $film["film_id"];?>" class="unique_key" name="film_id" />
               <input type="hidden" id="host_id" name="host_id" value="<?php echo $sf_user -> getAttribute("user_id");?>" />
               <input type="hidden" id="session_id" name="session_id" value="<?php echo session_id();?>" />
               
               <script type="text/javascript">
              	$(document).ready(function() {
                  pic="<?php echo $sf_user -> getAttribute('user_image');?>";
                  $("#host_image_original_preview").attr("src",pic);
                  $("#host_image_original_preview_wrapper").show();
                });
               </script>

             </fieldset>
          </form>
      </div>
      
  <div class="pop_bot"></div>
  </div>    
</div>
<!-- END HOST A SCREENING POPUP -->
<?php } ?>

<?php if ($sf_user -> isAuthenticated()) {?>
<!-- INVITE FROM HOST POPUP -->
<div class="pop_up" id="host_invite" style="display: none">
  <div class="pop_mid">
  <div class="pop_top popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
      	<p><strong>
        <?php echo $film["film_name"];?> <span class="current_host_screening_time"></span><br />
        HOSTED BY YOU
        </strong></p>
        <br />
        <div class="title"><strong>Your setup price: $<span class="host-ticket_price"><?php echo sprintf("%.02f",$film["film_setup_price"]);?></span></strong><br /> each invite you send =  $ <span id="host-ticket_discount">.10</span> off ticket price</div>
      	<p><br /><strong>invite friends via:</strong></p>
          <form id="host-invite" action="#" class="email_enter-form" onSubmit="return false">
              <fieldset>
                <div class="soc-app clearfix">
                  <input type="button" value="facebook" class="btn_small host_import_click" name="facebook" alt="facebook" />
                  <input type="button" value="twitter" class="btn_small host_import_click" name="twitter" alt="twitter" />
                  <input type="button" value="gmail" class="btn_small host_import_click" name="gmail" alt="gmail" />
                  <input type="button" value="yahoo" class="btn_small host_import_click" name="yahoo" alt="yahoo" />
                  <input type="button" value="hotmail" class="btn_small host_import_click" name="hotmail" alt="hotmail" />
                  <input type="button" value="aol" class="btn_small host_import_click" name="aol" alt="aol" />
         			  </div>
         			  
         			  <div class="soc-note clearfix">
                  <p><strong>or manually enter email addresses</strong></p>
                </div>
                
         			  <!-- CUSTOM IMPORT -->
                 <div id="host-import-contacts-container" style="display: none">		  	
                  
                  <h4 id="host_service_name"></h4>

                  <!-- INVITE LOGIN CONTAINER -->
                  <div class="login" action="#" method="post">		  		
                    
                    <fieldset>		  			
                      <input id="import-contacts-type" type="hidden" name="import-contacts[type]" value="" />					
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
                          <input name="import-contacts[email]" type="text" class="text import_field" id="host-import-email" />						
                          <input name="import-contacts[password]" type="password" class="text import_field" id="host-import-password" maxlength="20" autocomplete="off" foo="bar" baz="foo" />						
                          <input type="button" id="host-btn-import" class="btn_tiny" value="import" alt="import" />					
                      
                        <div id="host-contacts-error-area" style="color: red"></div>					
                     
                        <input name="import-contacts[provider]" type="hidden" class="text" id="host-import-contacts-provider" value="0" />
                        
                        <div id="host-contacts-loading-area"><img src="/images/ajax-loader.gif" alt="loading" /></div>	
                    
                    </fieldset>	
                    	  	
                  </div>	
                  
                </div>
                <!-- END CUSTOM IMPORT -->
                
                    <div class="left">
                      <form id="skedelege">
                      <ul id="host-fld-invites-container">
                        <li class="add_invite" title="host-add_invite"><div id="host_add_invite">Click To Add Email</div></li>
                      </ul>
                      </form>
                    </div>
                    
                    <div class="right clearfix">
                        <ul id="host-accepted-invites-container"></ul>
			              </div>
			              
                    <div class="soc_message">     	        
                      <label id="host-invite-fld-greeting-label" for="fld-greeting">Add a personal message to your invite <span id="host-invite-textbox-limit">150 characters</span></label>	        
                      <textarea id="host-invite-fld-greeting" name="greeting" class="with-label host-invite" rows="2"></textarea>	      
                    </div>
                    <div class="soc_message">
                        <input type="button" id="host-btn-preview_invite" value="preview" class="btn_small mr_3" name="" />
                        <input type="button" id="btn-host-invite" value="send" class="btn_small" name="" />
                    </div>
                  <div class="clear"></div>
                  <h2>Number of invites: <span class="host_number_invites">0</span><br /> new Ticket Price: $<span class="host-ticket_price"><?php echo $film["film_setup_price"];?></span></h2>
                  <input type="button" id="host-go-purchase" value="purchase" class="btn_large-og " name="" />
                  <div style="float: left; margin-left: 10px"><?php echo $film["film_name"];?> <br />
                  <span class="current_host_screening_time"></span><br /> 
                  Hosted by you</div>
                  <input type="hidden" id="host_fb_session" value="" name="fb_session" />
                  <input type="hidden" id="host_tw_session" value="" name="tw_session" />
                  <input type="hidden" id="host_email_session" value="" name="email_session" />
                  
             </fieldset>
          </form>
      </div>
      
  <div class="pop_bot"></div>
  </div>    
</div>
<!-- END INVITE FROM HOST POPUP -->
<?php } ?>

<?php if ($sf_user -> isAuthenticated()) {?>
<!-- PURCHASE FROM HOST POPUP -->
<div class="pop_up" id="host_purchase" style="display: none">
  <div class="pop_mid">
  <div class="pop_top popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
  	    <p><strong>
        <?php echo $film["film_name"];?> <span class="current_host_screening_time"></span><br />
        HOSTED BY YOU
        </strong></p>
        <br />
      	<?php if ($film["film_setup_price"] == 0) {?>
      	<form id="host_purchase_form" name="host_purchase" action="/host/<?php echo $film["film_id"];?>/purchase" class="buy-ticket_form" method="POST" onSubmit="return false">
        	<br />
            <fieldset>
               <div class="clearfix"><label>First Name</label> <input id="host-fld-cc_first_name" name="first_name" class="input" type="text" value="<?php echo $post["first_name"];?>" /></div>
                  <div class="clearfix"><label>Last Name</label> <input id="host-fld-cc_last_name" name="last_name" class="input" type="text" value="<?php echo $post["last_name"];?>" /></div>
                  <div class="clearfix"><label>Email Address </label><input id="host-fld-cc_email" name="email" class="input" type="text" value="<?php echo $post["email"];?>" /></div>
                  <div class="clearfix"><label>Confirm Email Address</label> <input id="host-fld-cc_confirm_email" name="confirm_email" class="input" type="text" value="<?php echo $post["email_confirm"];?>" /></div>
                  <div class="clearfix"><label>City</label> <input id="host-fld-cc_city" name="b_city" class="input" type="text" value="<?php echo $post["b_city"];?>" /></div>
                  <div class="clearfix"><label>Country</label>
                    <select class="cc-country-drop" id="host-fld-cc_state" name="b_country">				          
                      <?php foreach ($countries as $country) {?>
                        <option value="<?php echo $country -> getAttribute("abbr");?>" <?php if($post["b_country"] == $country -> getAttribute("abbr")) { echo "selected=\"selected\""; }?>><?php echo ucwords(strtolower($country -> getAttribute("name")));?></option>
                      <?php } ?> 
                    </select>
                  </div>
                  <div class="clearfix"><label>State Zip</label>
										<input type="text" class="cc-state-text" id="host-fld-cc_state_txt" name="b_state" style="display:none" />
                    <select class="cc-state-drop" id="host-fld-cc_state" name="b_state">				          
                      <?php foreach ($states as $state) {?>
                        <option value="<?php echo $state -> getAttribute("abbr");?>" <?php if($post["b_state"] == $state -> getAttribute("abbr")) { echo "selected=\"selected\""; }?>><?php echo ucwords(strtolower($state -> getAttribute("name")));?></option>
                      <?php } ?> 
                    </select>
                   <input id="host-fld-cc_zip" name="b_zipcode" class="short" type="text" value="<?php echo $post["b_zipcode"];?>" /></div>
                  <input id="host_purchase_submit" type="submit" value="purchase" class="btn_large-og" name="" /> 
                  <div style="float: left; margin-left: 10px; width: 250px;"><?php echo $film["film_name"]?><br />
                  <span class="current_host_screening_time"></span><br />
                  Hosted by You</div>
                <input type="hidden" name="hosting_free" id="hosting_free" value="true" />
                <input type="hidden" id="host_ticket_price" name="ticket_price" value="0" />
            </fieldset>
      	<?php } else { ?>
        <div class="title"><strong>Your ticket price: $<span class="host-ticket_price"><?php echo sprintf("%.02f",$film["film_setup_price"]);?></span></strong><br />
        </div>
      	<form id="host_purchase_form" name="host_purchase" action="/host/<?php echo $film["film_id"];?>/purchase" class="buy-ticket_form" method="POST" onSubmit="return false">
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
                        <a id="paypal-button" onclick="host_screening.goPaypal()" title="paypal" class="credit-card"><img src="/images/icon-cards-paypal.png" /></a>						
                      </li>					
                    </ul>
                  </div>
                  <div class="clearfix"><label>First Name (on card) </label> <input id="host-fld-cc_first_name" name="first_name" class="input" type="text" value="<?php echo $post["first_name"];?>" /></div>
                  <div class="clearfix"><label>Last Name (on card) </label> <input id="host-fld-cc_last_name" name="last_name" class="input" type="text" value="<?php echo $post["last_name"];?>" /></div>
                  <div class="clearfix"><label>Email Address </label><input id="host-fld-cc_email" name="email" class="input" type="text" value="<?php echo $post["email"];?>" /></div>
                  <div class="clearfix"><label>Confirm Email Address</label> <input id="host-fld-cc_confirm_email" name="confirm_email" class="input" type="text" value="<?php echo $post["email_confirm"];?>" /></div>
                  <div class="clearfix"><label>Credit Card</label> <input id="host-fld-cc_number" name="credit_card_number" class="input" type="text" value="<?php echo $post["card_number"];?>" /></div>
                  <div class="clearfix"><label>Security Code</label> <input id="host-fld-cc_security_number" name="card_verification_number" class="input" type="text" value="<?php echo $post["security_code"];?>" /></div>
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
                  <div class="clearfix"><label>Billing Address</label> <input id="host-fld-cc_address1" name="b_address1" class="input" type="text" value="<?php echo $post["b_address1"];?>"" /></div>
                  <div class="clearfix"><label>&nbsp;</label> <input id="host-fld-cc_address2" name="b_address2" class="input" type="text" value="<?php echo $post["b_address2"];?>" /></div>
                  <div class="clearfix"><label>City</label> <input id="host-fld-cc_city" name="b_city" class="input" type="text" value="<?php echo $post["b_city"];?>" /></div>
                  <div class="clearfix"><label>Country</label>
                    <select class="cc-country-drop" id="host-fld-cc_state" name="b_country">				          
                      <?php foreach ($countries as $country) {?>
                        <option value="<?php echo $country -> getAttribute("abbr");?>" <?php if($post["b_country"] == $country -> getAttribute("abbr")) { echo "selected=\"selected\""; }?>><?php echo ucwords(strtolower($country -> getAttribute("name")));?></option>
                      <?php } ?> 
                    </select>
                  </div>
                  <div class="clearfix"><label>State Zip</label>
										<input type="text" class="cc-state-text" id="host-fld-cc_state_txt" name="b_state" style="display:none" />
                    <select class="cc-state-drop" id="host-fld-cc_state" name="b_state">				          
                      <?php foreach ($states as $state) {?>
                        <option value="<?php echo $state -> getAttribute("abbr");?>" <?php if($post["b_state"] == $state -> getAttribute("abbr")) { echo "selected=\"selected\""; }?>><?php echo ucwords(strtolower($state -> getAttribute("name")));?></option>
                      <?php } ?> 
                    </select>
                   <input id="host-fld-cc_zip" name="b_zipcode" class="short" type="text" value="<?php echo $post["b_zipcode"];?>" />
									</div>
                  <input id="host_purchase_submit" type="submit" value="purchase" class="btn_large-og" name="" /> 
                  <div style="float: left; margin-left: 10px; width: 250px;"><?php echo $film["film_name"]?><br />
                  <span class="current_host_screening_time"></span><br />
                  Hosted by You</div>
              </fieldset>
              
              <input type="hidden" name="host_invite_count" id="host_invite_count" value="0" />	
              <input type="hidden" name="film_id" class="s_id" value="<?php echo $film["film_id"];?>" />		
              <input type="hidden" name="ticket_code" id="ticket-code" value="false" />		
              <input type="hidden" id="host_ticket_price" name="ticket_price" value="<?php echo $film["film_setup_price"];?>" />
          </form>
          <?php } ?>
      </div>
  <div class="pop_bot"></div>
  </div>    
</div>
<!-- END PURCHASE FROM HOST POPUP -->
<?php } ?>

<?php if ($sf_user -> isAuthenticated()) {?>
<!-- PURCHASE FROM HOST CONFIRM POPUP -->
<div class="pop_up" id="host_confirm" style="display: none">
  <div class="pop_mid">
  <div class="pop_top popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
      	<p><strong>
        <?php echo $film["film_name"];?> <span class="current_host_screening_time"></span><br />
        </strong></p>
        <br />
        <div class="title"><strong>Your purchase was confirmed</strong></div>
      	<!--Your screening link is:<br />
        <span id="host_screening_full_link"></span><br />-->
        <br />
        <a id="host_screening_link" href="#"><button class="btn_small">Enter Screening</button></a>
      </div>
  <div class="pop_bot"></div>
  </div>    
</div>
<!-- END PURCHASE FROM HOST CONFORM POPUP -->
<?php } ?>
