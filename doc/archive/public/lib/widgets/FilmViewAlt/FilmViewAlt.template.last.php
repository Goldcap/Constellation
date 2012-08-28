<?php 
$ttz = date_default_timezone_get();
$zones = zoneList(); 
?>

<?php 
if ($film["film_background_image"] != ''){?>
<!--http://s3.amazonaws.com/cdn.constellation.tv/prod-->
<style>
.marqee {
/*background: #000000 url("/uploads/screeningResources/<?php echo $film["film_id"];?>/background/<?php echo $film["film_background_image"];?>") no-repeat center top !important;*/
}
</style>
<?php } ?>

<?php 
if ((isset($film["program_background_image"])) && ($film["program_background_image"] != '')){?>
<!--http://s3.amazonaws.com/cdn.constellation.tv/prod-->
<style>
.marqee {
/*background: #000000 url("/uploads/programResources/<?php echo $film["program_id"];?>/background/<?php echo $film["program_background_image"];?>") no-repeat center top !important;*/
}
</style>
<?php } ?>

<div class="marqee">
  <div id="movie_trailer_container" class="nx_widget_video"></div>
  <span class="reqs" id="video"><?php echo str_replace(array("rtmp://cp113558.edgefcs.net/ondemand/","?"),array("","%3F"),$film["stream_url"]);?></span>
  <span class="reqs" id="video_still">/uploads/screeningResources/<?php echo $film["film_id"];?>/still/<?php echo $film["film_still_image"];?></span>
  
  <span class="tell_friends">
    <span class="friend_text">Tell your friends about this film:</span> 
    <a href="http://www.facebook.com/sharer.php?u=<?php echo urlencode('http://'.sfConfig::get("app_domain").'/film/'.$film["film_id"].'/detail');?>&t=<?php echo urlencode("Constellation.tv presents: \"".$film["film_name"]."\" viewed online for yourself or with your friends.");?>" target="_new"><span class="facebook_icon"></span></a>
    <a href="http://twitter.com/share?url=<?php echo ('http://'.sfConfig::get("app_domain").'/film/'.$film["film_id"].'/detail');?>&text=<?php echo ("Constellation.tv presents: '".$film["film_name"]."' viewed online for yourself or with your friends.");?>" target="_new"><span class="twitter_icon"></span></a>
  </span>
</div>

<div class="info">
 <?php if ($film["film_allow_hostbyrequest"] == 1) {?>
 <style>
 .info {
  height: 70px !important;
 }
 </style>
 <i>Join the showtime of JIG you would like to attend,<br />
 or <a href="javascript: void(0)" id="hbr_request_button">watch it now</a>.</i>
 <?php } else { ?>
 <i>Join the showtime of JIG you would like to attend.</i>
 <?php } ?>
</div>

<!-- SCREENING CAROUSEL -->
<?php include_component('default', 
                        'CarouselAlt', 
                        array('screenings'=>$carousel))?>
<!-- SCREENING CAROUSEL -->
  
<!-- SCREENINGLLIST -->
<?php include_component('default', 
                        'ScreeninglistAlt', 
                        array('screenings'=>$screenings))?>
<!-- SCREENINGLLIST -->
  

<div id="host" class="reqs"><?php echo $chat_instance_host;?></div>
<div id="port" class="reqs"><?php echo $chat_instance_port_base;?></div>


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

<?php if ($sf_user -> isAuthenticated()) {?>
<!-- INVITE POPUP -->
<div class="pop_up" id="screening_invite" style="display: none">
  <div class="pop_mid">
  <div class="pop_top popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
      	<p><strong>
        <?php echo $film["film_name"];?> <span class="current_screening_time"></span><br />
        <span class="current_screening_hb">HOSTED BY <span class="current_screening_host"></span></span>
        </strong>
				<span class="bandwidth_warning" style="display: none">Warning: We've detected that you have low bandwidth at your current location. While you can purchase tickets for this or any other screening, you may experience issues with image quality and continuity due to your connection. (<span class="current_bandwidth"></span>k/sec)</span>
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
<?php } ?>

<?php if ($sf_user -> isAuthenticated()) {?>
<!-- PURCHASE POPUP -->
<div class="poplg_up" id="screening_purchase" style="display: none">
  <div class="poplg_mid">
  <div class="poplg_top popup-close"><a href="#">Close Window</a></div>
  	<div class="layout-1 clearfix">
      	<h3>Buy Ticket</h3>
        <div class="film_alt_info">
          <span class="film_alt_name">
            <?php echo $film["film_name"];?><br />
            <span class="current_screening_time">Sunday, August 08 @8:00 PM EST</span><br />
            <span class="current_screening_hb">Hosted By <span class="current_screening_host">Channing Tatum & Jenna Dewan</span></span>
          </span>
          <span class="film_alt_price">
            Ticket Price<br />
            <span class="current_screening_price">
              <?php if ($film["film_ticket_price"] == 0) {?>FREE<?php } else {?>$<span class="ticket_price"><?php echo sprintf("%.02f",$film["film_ticket_price"]);?></span><?php } ?>
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
                <div class="clearfix"><label>Confirm Email Address</label> <input id="fld-cc_confirm_email" name="confirm_email" class="input" type="text" value="<?php echo $post["email_confirm"];?>" /></div>
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
                <div class="cc-submit">
                  <input id="purchase_submit" type="image" src="/images/alt/buy_ticket.png" value="get ticket" class="" name="" /> 
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
                <a id="enter-code" class="dcode_link" href="javascript:void(0);"><img src="/images/alt/code_apply.png" /></a>
              </div>
          </form>
        </div>
        <form id="purchase_form" name="purchase_form" action="/screening/<?php echo $film["film_id"];?>/purchase" method="POST" class="buy-ticket_form" onSubmit="return false">
          	<fieldset>
              	<div class="clearfix"><label class="cc_title">Billing Info</label><span class="cc_title"><a id="paypal-button" onclick="screening_room.goPaypal()" title="paypal" class="">or, pay with paypal</span><span class="paypal_icon"></span></a></div>
                <div class="clearfix"><label>First Name (on card) </label> <input id="fld-cc_first_name" name="first_name" class="input" type="text" value="<?php echo $post["first_name"];?>" /></div>
                <div class="clearfix"><label>Last Name (on card) </label> <input id="fld-cc_last_name" name="last_name" class="input" type="text" value="<?php echo $post["last_name"];?>" /></div>
                <div class="clearfix"><label>Email Address </label><input id="fld-cc_email" name="email" class="input" type="text" value="<?php echo $post["email"];?>" /></div>
                <div class="clearfix"><label>Confirm Email Address</label> <input id="fld-cc_confirm_email" name="confirm_email" class="input" type="text" value="<?php echo $post["email_confirm"];?>" /></div>
                <div class="clearfix"><label>Credit Card</label> <input id="fld-cc_number" name="credit_card_number" class="input" type="text" value="<?php echo $post["card_number"];?>" /></div>
                <div class="clearfix"><label>Security Code</label> <input id="fld-cc_security_number" name="card_verification_number" class="input code" type="text" value="<?php echo $post["security_code"];?>" /> <span class="cc_image"></span></div>
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
                  <input id="purchase_submit" type="image" src="/images/alt/buy_ticket.png" value="get ticket" class="" name="" /> 
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
  <div class="poplg_bot"></div>
  </div>    
</div>
<!-- END PURCHASE POPUP -->
<?php } ?>

<?php if ($sf_user -> isAuthenticated()) {?>
<!-- PURCHASE PROCESS POPUP -->
<div class="poplg_up" id="process" style="display: none">
  <div class="poplg_mid">
  <div class="poplg_top popup-close"></div> 
  	<div class="layout-1 clearfix">
      	<br />
        <div class="title"><strong><span id="purchase_process">Your purchase is being processed.</span></strong></div>
        <br />
        <div style="margin-left: 120px;"><img src="/images/ajax-loader.gif" /></div>
    </div>
  <div class="poplg_bot"></div>
  </div>   
</div>
<!-- END PURCHASE PROCESS POPUP -->
<?php } ?>

<?php if ($sf_user -> isAuthenticated()) {?>
<!-- PURCHASE CONFIRM POPUP -->
<div class="poplg_up" id="confirm" style="display: none">
  <div class="poplg_mid">
  <div class="poplg_top popup-close"><a href="#">Close Window</a></div>
  	<div class="layout-1 clearfix">
      	<p><strong>
        <?php echo $film["film_name"];?> <span class="current_screening_time"></span><br />
        <span class="current_screening_hb">Hosted By <span class="current_screening_host"></span></span>
        </strong></p>
        <br />
        <div class="title"><strong><span id="purchase_result">Your purchase was confirmed</span></strong></div>
      	<!--Your screening link is:<br />
        <span id="screening_full_link"></span><br />-->
        <br />
        <a id="screening_click_link" href="#"><img src="/images/alt/enter_theater.png" border="0" /></a>
      </div>
  <div class="poplg_bot"></div>
  </div>    
</div>
<!-- END PURCHASE CONFORM POPUP -->
<?php } ?>

<?php if ($film["film_use_sponsor_codes"] == 1) {?>
<!-- WATCH BY REQUEST POPUP -->
<div class="pop_up" id="watch_by_request" style="display: none">
  <div class="pop_mid">
  <div class="pop_top popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
      	<p><strong>
        <?php echo $film["film_name"];?> <span class="current_screening_time"></span><br />
        <!--HOSTED BY <span class="current_screening_host"></span>-->
        </strong></p>
        <br />
        <div class="code_break">
        	<!--<p>If you have an unused ticket, you can use it to view this screening. Enter the ticket code here, and click "Submit Code".</p>-->
          <form id="step-watch-form" class="buy-ticket_form" action="#">	  
            <fieldset class="data">		      
              <div class="clearfix">
                <label>ENTER CODE: </label>
              	<input id="watch-fld-code" name="ticket_code" class="text" type="text" />		        
              </div>
            </fieldset>		
          </form>
        </div>
        <br />
        <a id="watch-enter-code" class="link" href="javascript:void(0);"><button class="btn_small">Submit Code</button></a>
      </div>
  <div class="pop_bot"></div>
  </div>    
</div>
<!-- END WATCH BY REQUEST POPUP -->

<!-- WATCH BY REQUEST CONFIRM POPUP -->
<div class="pop_up" id="watch_by_request_confirm" style="display: none">
  <div class="pop_mid">
  <div class="pop_top popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
      	<p><strong>
        <?php echo $film["film_name"];?> <span class="current_screening_time"></span><br />
        <!--HOSTED BY <span class="current_screening_host"></span>-->
        </strong></p>
        <br />
        <div class="title"><strong><span id="purchase_result_wbr">Your screening was confirmed</span></strong></div>
      	<!--Your screening link is:<br />
        <span id="wbr_screening_full_link"></span><br />-->
        <br />
        <a id="wbr_screening_click_link" href="#"><button class="btn_small">Enter Theater</button></a>
      </div>
  <div class="pop_bot"></div>
  </div>    
</div>
<!-- END WATCH BY REQUEST CONFORM POPUP -->
<?php } ?>

<?php if ($sf_user -> isAuthenticated()) {?>
<!-- GEOIP WARNING POPUP -->
<div class="pop_up" id="geoblock" style="display: none">
  <div class="pop_mid">
  <div class="pop_top"></div>
  	<div class="layout-1 clearfix">
      	<div class="title"><strong><span id="purchase_result_geoip">Viewing This Film Is Restricted</span></strong></div>
      	Please be aware that due to your location, viewing this film is unavailable due to access rights set by the maker of the film. You may purchase tickets and host screenings, but you will be unable to view the movie from your current location.
        <br /><br />
      <span class="gbip-close"><a id="screening_link" href="#"><button class="btn_small">Continue</button></a></span>
    </div>
  <div class="pop_bot"></div>
  </div>    
</div>
<!-- END GEOIP WARNING POPUP -->
<?php } ?>

<div class="popup" id="preview-invite-popup" style="display: none">		
  <div class="close-bar">			
    <a id="close-preview-invite-popup">(close)</a>		
  </div>		
  <div class="invite-content">		
  </div>	
</div>

<div class="popup" id="inviting-popup" style="display: none">	
  <p align="center">Sending Invitations...<br />
  <img src="/images/ajax-loader.gif" alt="loading" />	
  </p>
  <br />
  <p align="center" id="inviting-errors"></p>
</div>

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

<div id="bandwidth_test"></div>
