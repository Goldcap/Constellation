<div class="image_gallery small">
  <?php if (count($carousel) > 0) {?>
  <!-- SCREENING CAROUSEL -->
  <?php include_component('default', 
                          'Carousel', 
                          array('size'=>'film_','screenings'=>$carousel))?>
  <!-- SCREENING CAROUSEL -->
  <?php } ?>
</div>
<div class="showtimes" id="today_screenings">
<!-- SCREENINGLLIST -->
<?php include_component('default', 
                        'Screeninglist', 
                        array('title'=>'join today\'s showtimes',
                        'screenings'=>$screenings))?>
<!-- SCREENINGLLIST -->
</div>
<div id="is_sponsor_film"></div>
<?php if ($film["film_use_sponsor_codes"] == 0) {?>
<style>
.ui-datepicker { font-size: 10px; }
.specialDate .ui-state-default {color: white;background: url("/js/jquery/ui/themes/smoothness/images/ui-bg_glass_75_93A7BE_1x400.png") repeat-x scroll 50% 50% #93A7BE !important;}
</style> 
<h4 class="future_showtimes">future showtimes</h4>
<input type="hidden" id="featured_datepicker" style="visibility: hidden" />
<?php } ?>

<div id="host" class="reqs"><?php echo $chat_instance_host;?></div>
<div id="port" class="reqs"><?php echo $chat_instance_port_base;?></div>

<?php if ($film["film_use_sponsor_codes"] == 0) {
if ($sf_user -> isAuthenticated()) {?>
<!-- HOST A SCREENING POPUP -->
<div class="pop_up" id="host_details" style="display: none">
  <div class="pop_mid">
  <div class="pop_top popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
      	<div class="title"><strong>HOST A SCREENING</strong>  </div>
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
                
                <!--<p class="pad_bot"><span>
                  
                  <span class="col1"><strong>THE TYPE</strong></span>
                  <span class="col2">&nbsp;</span>
                  <span class="col3">
                  <button id="host_featured" class="btn_medium_og host_type" name="">featured</button>
                  <button id="host_public" class="btn_medium host_type" name="">public</button>
                  <button id="host_private" class="btn_medium host_type" name="" >private</button>
                  </span>
                </span></p>-->
		            
		            <p class="pad_bot"><span>
		              <span class="fullcol"><strong>DESCRIBE YOUR SCREENING</strong></span>
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
                        <span style="float: left;">Upload an image for your host profile </span>
                        <a id="REF_host_image_original" href="#FORM_host_image_original">
                          <input type="button" name="BUTTON_host_image_original" class="btn_small" value="Upload Image" />
                        </a>
                     </div>
                     <div class="textarea">
                     	<p><span id="textbox-limit">150 characters remaining</span></p>
                        <textarea id="fld-greeting" name="greeting" class="with-label screening"></textarea>
                        <!--<input name="" type="checkbox" value="" class="check_box" /> Host a Q+A Session &nbsp;&nbsp;&nbsp;-->
                    </div>
		            </div>
			         <div class="clear"></div>
               
               <input type="submit" id="host_submit" value="continue" class="btn_large-og" name="" />
               <input type="hidden" name="type" class="type" value="detail" />		
               <input type="hidden" name="setup_price" value="<?php echo $film["film_setup_price"];?>" />		
               <input type="hidden" value="<?php echo $film["film_id"];?>" class="unique_key" name="film_id" />
               <input type="hidden" id="host_id" name="host_id" value="<?php echo $sf_user -> getAttribute("user_id");?>" />
               <input type="hidden" id="session_id" name="session_id" value="<?php echo session_id();?>" />
               <input type="hidden" id="hosting_type" name="hosting_type" value="featured" />
               
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
      	<form id="host_purchase_form" name="host_purchase" action="/host/<?php echo $film["film_id"];?>/purchase" class="buy-ticket_form" method="POST" onSubmit="return false">
        	<br />
            <fieldset>
               <div class="clearfix"><label>First Name</label> <input id="host-fld-cc_first_name" name="first_name" class="input" type="text" value="<?php echo $post["first_name"];?>" /></div>
                  <div class="clearfix"><label>Last Name</label> <input id="host-fld-cc_last_name" name="last_name" class="input" type="text" value="<?php echo $post["last_name"];?>" /></div>
                  <div class="clearfix"><label>Email Address </label><input id="host-fld-cc_email" name="email" class="input" type="text" value="<?php echo $post["email"];?>" /></div>
                  <div class="clearfix"><label>Confirm Email Address</label> <input id="host-fld-cc_confirm_email" name="confirm_email" class="input" type="text" value="<?php echo $post["email_confirm"];?>" /></div>
                  <div class="clearfix"><label>City</label> <input id="host-fld-cc_city" name="b_city" class="input" type="text" value="<?php echo $post["b_city"];?>" /></div>
                  <div class="clearfix"><label>State Zip</label>
                    <select id="host-fld-cc_state" name="b_state">				          
                      <?php foreach ($states as $state) {?>
                        <option value="<?php echo $state -> getAttribute("abbr");?>" <?php if($post["b_state"] == $state -> getAttribute("abbr")) { echo "selected=\"selected\""; }?>><?php echo ucwords(strtolower($state -> getAttribute("name")));?></option>
                      <?php } ?> 
                    </select>
                   <input id="host-fld-cc_zip" name="b_zipcode" class="short" type="text" value="<?php echo $post["b_zipcode"];?>" /></div>
                  <div class="clearfix"><label>Country</label>
                    <select id="host-fld-cc_state" name="b_country">				          
                      <?php foreach ($countries as $country) {?>
                        <option value="<?php echo $country -> getAttribute("abbr");?>" <?php if($post["b_country"] == $country -> getAttribute("abbr")) { echo "selected=\"selected\""; }?>><?php echo ucwords(strtolower($country -> getAttribute("name")));?></option>
                      <?php } ?> 
                    </select>
                  </div>
                  <input id="host_purchase_submit" type="submit" value="finish" class="btn_large-og" name="" /> 
                  <div style="float: left; margin-left: 10px; width: 250px;"><?php echo $film["film_name"]?>, <span class="current_host_screening_time"></span> Hosted by You</div>
                <input type="hidden" name="hosting_free" id="hosting_free" value="true" />
            </fieldset>
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
        <div class="title"><strong>Your screening was confirmed</strong></div>
      	<!--Your screening link is:<br />
        <span id="host_screening_full_link"></span><br />-->
        <br />
        <a id="host_screening_link" href="#"><button class="btn_small">Enter Screening</button></a>
      </div>
  <div class="pop_bot"></div>
  </div>    
</div>
<!-- END PURCHASE FROM HOST CONFORM POPUP -->
<?php }?>

<?php if ($sf_user -> isAuthenticated()) {?>
<!-- INVITE POPUP -->
<div class="pop_up" id="screening_invite" style="display: none">
  <div class="pop_mid">
  <div class="pop_top popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
      	<p><strong>
        <?php echo $film["film_name"];?> <span class="current_screening_time"></span><br />
        HOSTED BY <span class="current_screening_host"></span>
        </strong></p>
        <br />
           <form id="invite" action="#" class="email_enter-form" onSubmit="return false">
              
                  <input type="button" id="go-purchase" value="<?php if ($film["film_ticket_price"] != 0) {?>buy ticket<?php } else {?>get ticket<?php } ?>" class="btn_large-og" name="" />
                  <div style="float: left; margin-left: 10px"><?php echo $film["film_name"];?> <span class="current_screening_time"></span><br /> Hosted by <span class="current_screening_host"></span></div>
               
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
<div class="pop_up" id="screening_purchase" style="display: none">
  <div class="pop_mid">
  <div class="pop_top popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
      	<p><strong>
        <?php echo $film["film_name"];?> <span class="current_screening_time"></span><br />
        HOSTED BY <span class="current_screening_host"></span>
        </strong></p>
        <br />
        <div class="title"><strong>Your ticket price: <?php if ($film["film_ticket_price"] == 0) {?>FREE<?php } else {?>$<span class="ticket_price"><?php echo sprintf("%.02f",$film["film_ticket_price"]);?></span><?php } ?></strong></div>
      	
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
                  <select id="fld-cc_state" name="b_state">				          
                    <?php foreach ($states as $state) {?>
                      <option value="<?php echo $state -> getAttribute("abbr");?>" <?php if($post["b_state"] == $state -> getAttribute("abbr")) { echo "selected=\"selected\""; }?>><?php echo ucwords(strtolower($state -> getAttribute("name")));?></option>
                    <?php } ?> 
                  </select>
                 <input id="fld-cc_zip" name="b_zipcode" class="short" type="text" value="<?php echo $post["b_zipcode"];?>" /></div>
                <div class="clearfix"><label>Country</label>
                  <select id="fld-cc_state" name="b_country">				          
                    <?php foreach ($countries as $country) {?>
                      <option value="<?php echo $country -> getAttribute("abbr");?>" <?php if($post["b_country"] == $country -> getAttribute("abbr")) { echo "selected=\"selected\""; }?>><?php echo ucwords(strtolower($country -> getAttribute("name")));?></option>
                    <?php } ?> 
                  </select>
                </div>
                <input id="purchase_submit" type="button" value="get ticket" 
class="btn_large-og" name="" /> <span><?php echo $film["film_name"]?>, <span 
class="current_screening_time"></span> Hosted by <span class="current_screening_host"></span></span>
                <input type="hidden" name="screeing_free" id="screening_free" value="true" />
            </fieldset>
      	<?php } else { ?>
      	<div class="code_break">
        	<p>If you have an unused ticket, you can use it to view this screening. Enter the ticket code here, and click "Submit Code".</p>
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
                        <a id="paypal-button" title="paypal" class="credit-cards" href="/services/Paypal/express/screening?vars=<?php echo $screening?>-<?php echo $film["film_id"];?>"><img src="/images/icon-cards-paypal.png" /></a>						
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
                    <select id="fld-cc_state" name="b_state">				          
                      <?php foreach ($states as $state) {?>
                        <option value="<?php echo $state -> getAttribute("abbr");?>" <?php if($post["b_state"] == $state -> getAttribute("abbr")) { echo "selected=\"selected\""; }?>><?php echo ucwords(strtolower($state -> getAttribute("name")));?></option>
                      <?php } ?> 
                    </select>
                   <input id="fld-cc_zip" name="b_zipcode" class="short" type="text" value="<?php echo $post["b_zipcode"];?>" /></div>
                  <div class="clearfix"><label>Country</label>
                    <select id="fld-cc_state" name="b_country">				          
                      <?php foreach ($countries as $country) {?>
                        <option value="<?php echo $country -> getAttribute("abbr");?>" <?php if($post["b_country"] == $country -> getAttribute("abbr")) { echo "selected=\"selected\""; }?>><?php echo ucwords(strtolower($country -> getAttribute("name")));?></option>
                      <?php } ?> 
                    </select>
                  </div>
                  <input id="purchase_submit" type="button" value="buy ticket" class="btn_large-og" name="" />
                  <div style="float: left; margin-left: 10px"><?php echo 
$film["film_name"]?>, <span class="current_screening_time"></span> Hosted by <span 
class="current_screening_host"></span></div>
              </fieldset>
              <?php } ?>
              
              <input type="hidden" name="ticket_code" id="ticket-code" value="false" />		
              
              <?php if ($film["film_ticket_price"] == 0) {?>
                <input type="hidden" id="ticket_price" name="ticket_price" value="0" />
              <?php } else {?>
                <input type="hidden" id="ticket_price" name="ticket_price" value="<?php echo $film["film_ticket_price"];?>" />
              <?php } ?>
          </form>
          <div id="purchase_errors" style="color: red"></div>
      </div>
  <div class="pop_bot"></div>
  </div>    
</div>
<!-- END PURCHASE POPUP -->
<?php } ?>

<?php if ($sf_user -> isAuthenticated()) {?>
<!-- PURCHASE FROM HOST CONFIRM POPUP -->
<div class="pop_up" id="confirm" style="display: none">
  <div class="pop_mid">
  <div class="pop_top popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
      	<p><strong>
        <?php echo $film["film_name"];?> <span class="current_screening_time"></span><br />
        HOSTED BY <span class="current_screening_host"></span>
        </strong></p>
        <br />
        <div class="title"><strong><span id="purchase_result">Your screening was confirmed</span></strong></div>
      	<!--Your screening link is:<br />
        <span id="screening_full_link"></span><br />-->
        <br />
        <a id="screening_click_link" href="#"><button class="btn_small">Enter Theater</button></a>
      </div>
  <div class="pop_bot"></div>
  </div>    
</div>
<!-- END PURCHASE FROM HOST CONFORM POPUP -->
<?php } ?>

<?php }  else { ?>

<!-- PURCHASE FROM HOST CONFIRM POPUP -->
<div class="pop_up" id="screening_purchase" style="display: none">
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
          <form id="step-enterCode-form" class="buy-ticket_form" action="#">	  
            <fieldset class="data">		      
              <div class="clearfix">
                <label>ENTER CODE: </label>
              	<input id="fld-code" name="ticket_code" class="text" type="text" />		        
              </div>
            </fieldset>		
          </form>
        </div>
        <br />
        <a id="enter-code" class="link" href="javascript:void(0);"><button class="btn_small">Submit Code</button></a>
      </div>
  <div class="pop_bot"></div>
  </div>    
</div>
<!-- END PURCHASE FROM HOST CONFORM POPUP -->

<!-- PURCHASE FROM HOST CONFIRM POPUP -->
<div class="pop_up" id="confirm" style="display: none">
  <div class="pop_mid">
  <div class="pop_top popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
      	<p><strong>
        <?php echo $film["film_name"];?> <span class="current_screening_time"></span><br />
        <!--HOSTED BY <span class="current_screening_host"></span>-->
        </strong></p>
        <br />
        <div class="title"><strong><span id="purchase_result">Your screening was confirmed</span></strong></div>
      	<!--Your screening link is:<br />
        <span id="screening_full_link"></span><br />-->
        <br />
        <a id="screening_click_link" href="#"><button class="btn_small">Enter Theater</button></a>
      </div>
  <div class="pop_bot"></div>
  </div>    
</div>
<!-- END PURCHASE FROM HOST CONFORM POPUP -->
<?php } ?>

<?php if ($sf_user -> isAuthenticated()) {?>
<!-- PURCHASE FROM HOST CONFIRM POPUP -->
<div class="pop_up" id="geoblock" style="display: none">
  <div class="pop_mid">
  <div class="pop_top"></div>
  	<div class="layout-1 clearfix">
      	<div class="title"><strong><span id="purchase_result">Viewing This Film Is Restricted</span></strong></div>
      	Please be aware that due to your location, viewing this film is unavailable due to access rights set by the maker of the film. You may purchase tickets and host screenings, but you will be unable to view the movie from your current location.
        <br /><br />
      <span class="gbip-close"><a id="screening_link" href="#"><button class="btn_small">Continue</button></a></span>
    </div>
  <div class="pop_bot"></div>
  </div>    
</div>
<!-- END PURCHASE FROM HOST CONFORM POPUP -->
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
<div id="domain" style="display:none"><?php echo sfConfig::get("app_domain");?></div>
<div id="film" style="display:none"><?php if (isset($film["film_id"])) {echo $film["film_id"];}?></div>
<div id="film_start_offset" style="display:none"><?php echo $film_start_offset;?></div>
<div id="film_end_offset" style="display:none"><?php echo $film_end_offset;?></div>
<div id="screening" style="display:none"><?php if (isset($screening)) {echo $screening;}?></div>
<div id="current_date" style="display:none"></div>
