<h1 class="join-filmTitle">JOIN: 
  <span id="startdate" class="tzDate"><?php echo formatDate($film["screening_date"],"prettyshorttz");?></span>
  <p>Countdown: <span id="countdown"></span></p></h1>			 			
<div id="movie_trailer_container" class="nx_widget_video">	    
    <div id="movie_trailer">
      <img src="/uploads/screeningResources/<?php echo $film["screening_film_id"];?>/still/<?php echo $film["screening_film_still_image"];?>" alt="<?php echo $film["screening_film_name"];?>" class="widget_video_still"  border="0"/>			
      <!--<a href="http://www.adobe.com/go/getflashplayer">
        <img src='http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif' alt='Get Adobe Flash player' />
      </a>-->
    </div>
</div>
<!--<span class="reqs" id="video">/uploads/screeeningResources/<?php echo $film["screening_film_id"];?>/trailerFile/<?php echo $film["screening_film_trailer_file"];?></span>-->
<span class="reqs" id="video"><?php echo $stream_url;?></span>
<span class="reqs" id="video-autoplay">false</span>
<span class="reqs" id="video-type">TRAILER</span>

<div id="steps-join" class="steps_all">			 			 

<?php if ($sf_user -> isAuthenticated()) {
  $c1 = 'step_done';
} else {
  $c1 = 'step_current';
} ?>

<?php if(isset($_GET["err"])) {?>
<script type="text/javascript">
$(document).ready( function() {
  <?php if($_GET["err"]=='signup') {?>
	$("#step-login").attr("class","step step_current");
  $("#step-login-form").fadeIn("slow");
  $('#step-signup-connect-type').val('signup');		
	$('#step-custom-login-container').slideToggle(0, function() { $('#login-email').focus()});
  $('#step-custom-login-container').animate({"left": "-=418px"}, "slow", function() { $('#step-signup-name').focus()});			
	<?php } ?>
});
</script>
<?php } ?>
<!--STEP ONE -->
<div class="step <?php echo ($c1);?>" id="step-login">
  <div class="step_decorator">
    <div class="step_title">
      <h2 class="not_done">1. Login  <i id="login-info"> (To identify yourself in the theater. We do not collect your private information).</i></h2>
      <h2 class="done">1. 
        <?php if ($sf_user -> getAttribute('user_image') != '') {?>
	    	<img height="48" width="48" alt="<?php echo $sf_user -> getAttribute('user_username');?>" class="-connect-img" src="<?php echo $sf_user -> getAttribute('user_image');?>" id="login-icon">
	    	<?php } else { ?>
        <img height="48" width="48" alt="<?php echo $sf_user -> getAttribute('user_username');?>" class="-connect-img" src="/images/c-connect.png" id="login-icon">
	    	<?php }?>
	    	<span class="full-name-area"><?php echo $sf_user -> getAttribute("user_username");?></span>
	    	 - connected.
	    </h2>
      <a href="/logout?dest=/screening/<?php echo $film["screening_unique_key"]?>" class="edit">logout</a>
      
      <!--TODO:: ADD TWITTER ACTIVITY FEED
      <p id="broadcast-activity"><span id="twitter">Twitter </span> activity feed is
				<a href="#" alt="1" class="broadcast-yes"></a>
        <a href="#" alt="0" class="broadcast-no" style="display: inline;"></a>
      </p>
      -->
      
    </div>
    
    <?php if (! $sf_user -> isAuthenticated()) {?>
    <div id="step-login-form">
      <fieldset class="data">
        
        <!-- LOGIN OPTIONS -->
         <?php include_component('default', 'loginopts', array(
                                                          'error'=>$_GET['err'],
                                                          'destination'=>'/screening/'.$film["screening_unique_key"].'/purchase')) ?>
        
        
        <!-- LOGIN AND SIGNUP -->
        <div class="login-signup-container" id="step-custom-login-container">
          
          <!-- LOGIN -->
          <?php include_component('default', 'login', array(
                                                      'error'=>$_GET['err'],
                                                      'destination'=>'/screening/'.$film["screening_unique_key"].'/purchase',
                                                      'source'=>'/screening/'.$film["screening_unique_key"]
                                                      )) ?>
          <!-- END LOGIN -->
          
          <!-- SIGN-UP -->
          <?php include_component('default', 'signup', array(
                                                      'error'=>$_GET['err'],
                                                      'destination'=>'/screening/'.$film["screening_unique_key"].'/purchase',
                                                      'source'=>'/screening/'.$film["screening_unique_key"]
                                                      )) ?>
          <!-- END SIGNUP -->
        </div>
        <!-- END LOGIN AND SIGNUP -->
        
      </fieldset>
    </div>
    <?php if (((isset($errors)) || (isset($_GET["err"]))) && ($step == 'login')) {?>
    <script type="text/javascript">
      $(document).ready(function(){
         screening_room.showLogin();
      });
    </script>
    <?php }} ?>
  </div>

</div>
<!-- END STEP ONE -->
  
  <?php 
  if ($sf_user -> isAuthenticated()) {
    if ($step =="purchase") {
      $c1="step_current";
    } elseif ($step =="invite") {
      $c1="step_done";
    } else {
      $c1="step_next";
    }
  } else {
      $c1="";  
  } ?>
  <!-- STEP TWO -->
  <div id="step-pay" class="step <?php echo $c1;?>">	
    <div class="overlay">		
      <img src="/images/ajax-loader.gif" alt="loading" />	
    </div>
    
    <div class="step_decorator">
    <?php if ($film["screening_type"] == 2) {?>
      <div class="step_title">
  	    <h2 class="title_step">2. GET YOUR FREE TICKET</h2>
  	    <h2 class="title_step_done">2. FREE TICKET</h2>
  	  </div>
  	  <?php if ($step == "purchase") {?>
  	  <div class="step_subtitle">We will send a ticket to your email address. You will also be able to enter the theater straight from this page!</div>	  
  	  <form id="step-pay-form" action="/screening/<?php echo $film["screening_unique_key"];?>/purchase" method="post">
        <input type="hidden" value="<?php echo $film["screening_unique_key"];?>" class="unique_key" name="s_unique_key">
    		<input type="hidden" value="join" class="screening_type" name="type">
    		<input type="hidden" value="" id="credit-card-type" name="card_type">
    		<input type="hidden" value="true" name="self_invited">
    		<input type="hidden" value="<?php echo $film["screening_film_id"];?>" name="film_id">
        <fieldset id="fieldsets-payment-option-cc" class="data">
          <div style="display: block;" id="formfield-email" class="formfield">
            <label for="fld-cc_email">Email Address:</label>
            <input type="text" class="text" name="email" id="fld-cc_email" />
            <input type="hidden" value="" name="invite_code" />
          </div>
          <div style="display: block;" id="formfield-confirm-email" class="formfield">
            <label for="fld-cc_confirm_email">Confirm Email Address:</label>
            <input type="text" class="text" name="confirm_email" id="fld-cc_confirm_email" />
          </div>
        </fieldset>
      </form>
      <?php }} else {?>
      <div class="step_title">	    
        <h2 class="title_step">2. BUY A TICKET for <?php echo sprintf("$%.02f",$film["screening_film_ticket_price"]);?></h2>	    
        <h2 class="title_step_done">2. <?php echo sprintf("$%.02f",$film["screening_film_ticket_price"]);?> PAID</h2>	  
      </div>
      <?php if ($step == "purchase") {?>
      <div class="step_subtitle">We will send a ticket to your email address. You will also be able to enter the theater straight from this page!</div>	   	  
      <form id="step-redeem-form" action="/services/Redeem/<?php echo $film["screening_unique_key"];?>" method="post">	  	
        <input type="hidden" name="s_unique_key" class="unique_key" value="<?php echo $film["screening_unique_key"];?>" />		
        <input type="hidden" name="s_id" class="s_id" value="<?php echo $film["screening_id"];?>" />		
        <input type="hidden" name="film_id" class="s_id" value="<?php echo $film["screening_film_id"];?>" />		
        <input type="hidden" name="ticket_code" id="ticket-code" value="false" />		
        <input type="hidden" name="ticket_price" value="<?php echo $film["screening_film_ticket_price"];?>" />		
        <fieldset id="fieldsets-ticket-code-select">
          <div class="formfield">				
            <img id="btn-ticket-code" class="btn-code" type="image" src="/images/btn-ticket-code.png" alt="redeem gift or subscription tickets" />
          </div>
        </fieldset>
        <fieldset id="fieldsets-ticket-code" style="display:none">
          <div style="width: 400px;">
            <div class="formfield">
              <label for="fld-ticket_code">Enter Ticket Code(s):</label>     
              <input id="fld-ticket_code" name="ticket_code" class="ticket-code" type="text" value="<?php echo $post["ticket_code"];?>" />
            </div>
            <div class="formfield">	
              <i>If you are entering multiple codes, separate with a comma.</i>        
            </div>
            <div class="formfield">
              <label for="fld-email_recipient">Recipient Email Address:</label>     
              <input id="fld-email_recipient" name="email_recipient" class="ticket-code" type="text" value="<?php echo $sf_user -> getAttribute("user_email");?>" />
            </div>
            <div class="formfield">	
              <input id="btn-ticket-code-nextt" class="btn-submit-pay ticket-code-next" type="image" src="/images/btn-text-subscribe-next.png" alt="next step"  />	        
            </div>
          </div>
        </fieldset>
      </form>
      <form id="step-pay-form" action="/screening/<?php echo $film["screening_unique_key"];?>/purchase" method="post">	  	
        <input type="hidden" name="s_unique_key" class="unique_key" value="<?php echo $film["screening_unique_key"];?>" />		
        <input type="hidden" name="s_id" class="s_id" value="<?php echo $film["screening_id"];?>" />		
        <input type="hidden" name="film_id" class="s_id" value="<?php echo $film["screening_film_id"];?>" />		
        <input type="hidden" name="type" class="screening_type" value="join" />		
        <input type="hidden" name="ticket_price" value="<?php echo $film["screening_film_ticket_price"];?>" />		
        <div id="all-payment-info">
        <fieldset class="data" id="fieldsets-payment-option-cc">	      		      
          <div class="formfield formfield-card-types">		      	
            <span class="label">We Accept:
            </span>				
            <div id="card-types">										
              <ul>						
                <li id="visa">
                  <a href="javascript:void(0);" rel="1" class="credit-cards" title="visa"></a>
                </li>						
                <li id="mastercard">
                  <a href="javascript:void(0);" rel="2" class="credit-cards" title="mastercard"></a>
                </li>						
                <li id="amex" class="short">
                  <a href="javascript:void(0);" class="credit-cards" rel="3" title="amex"></a>
                </li>						
                <li id="discover">
                  <a href="javascript:void(0);" rel="4" class="credit-cards" title="discover"></a>
                </li>						
                <li id="paypal">							
                  <a href="/services/Paypal/express/screening?i=<?php echo $film["screening_unique_key"];?>" class="credit-cards" rel="5" title="paypal" id="paypal-button"></a>						
                </li>					
              </ul>				
            </div>			  
          </div>		      
          <div class="formfield">		        
            <label for="fld-cc_name">First Name (on card):
            </label>		        
            <input id="fld-cc_first_name" name="first_name" class="text" type="text" value="<?php echo $post["first_name"];?>" />		      
          </div>		      
          <div class="formfield">		        
            <label for="fld-cc_name">Last Name (on card):
            </label>		        
            <input id="fld-cc_last_name" name="last_name" class="text" type="text" value="<?php echo $post["last_name"];?>" />		      
          </div>	      		  
          <div class="formfield" id="formfield-email" style="display: block;">	        
            <label for="fld-cc_email">Email Address:
            </label>	        
            <input id="fld-cc_email" name="email" class="text" type="text"  value="<?php echo $post["email"];?>" />	        
            <input type="hidden" name="pay[invite_code]" value="" />	      
          </div>		  
          <div class="formfield" id="formfield-confirm-email"  style="display: block;">	        
            <label for="fld-cc_confirm_email">Confirm Email Address:
            </label>	        
            <input id="fld-cc_confirm_email" name="confirm_email" class="text" type="text"  value="<?php echo $post["email"];?>"  />	      
          </div>	      	      		      
          <div class="formfield">		        
            <label for="fld-cc_number">Card Number:
            </label>		        
            <input id="fld-cc_number" name="credit_card_number" class="text" type="text"  autocomplete="off"   />		      
          </div>			   		      
          <div class="formfield-security">		        
            <label for="fld-cc_number">Security Code:
            </label>		        
            <input id="fld-cc_security_number" name="card_verification_number" class="text1" type="text" autocomplete="off" />		       		      
            <fieldset class="expiry_date">  			  	
              <label for="fld-cc_expiredate">Exp. Date:
              </label>			  			         						          
              <select id="fld-cc_exp_month" name="expiration_date_month">				            
                <option value="01" >01
                </option>				            
                <option value="02" >02
                </option>				            
                <option value="03" >03
                </option>				            
                <option value="04" >04
                </option>				            
                <option value="05" >05
                </option>				            
                <option value="06" >06
                </option>				            
                <option value="07" >07
                </option>				            
                <option value="08" >08
                </option>				            
                <option value="09" >09
                </option>				            
                <option value="10" >10
                </option>				            
                <option value="11" >11
                </option>				            
                <option value="12" >12
                </option>				          
              </select>		         						         						          		          
              <select id="fld-cc_exp_year" name="expiration_date_year">				            
                <option value="2011" >2011
                </option>				            
                <option value="2012" >2012
                </option>				            
                <option value="2013" >2013
                </option>				            
                <option value="2014" >2014
                </option>				            
                <option value="2015" >2015
                </option>				            
                <option value="2016" >2016
                </option>				            
                <option value="2017" >2017
                </option>				            
                <option value="2018" >2018
                </option>				            
                <option value="2019" >2019
                </option>				            
                <option value="2020" >2020
                </option>				          
              </select>		        				 		        
          </div>		      
        </fieldset>
        <div class="formfield long">		        
          <label for="fld-cc_address1">Billing Address:
          </label>		        
          <input id="fld-cc_address1" name="b_address1" class="text" type="text" value="<?php echo $post["b_address1"];?>" />		      
        </div>		      
        <div id="fld-cc_address2_container" class="formfield">		        
          <label for="fld-cc_address2">Billing Address 2:
          </label>		        
          <input id="fld-cc_address2" name="b_address2" class="text" type="text" value="<?php echo $post["b_address2"];?>" />		      
        </div>		      
        <div class="formfield">		        
          <label for="fld-cc_city">City:
          </label>		        
          <input id="fld-cc_city" name="b_city" class="text" type="text" value="<?php echo $post["b_city"];?>" />		      
        </div>		      
        <div id="fld-statezip_container" class="formfield">		        
          <span class="label">		          
            <label for="fld-cc_state">State,
            </label>		          
            <label for="fld-cc_zip">Zip:
            </label>		        
          </span>		        
          <select id="fld-cc_state" name="b_state">				          
            <?php foreach ($states as $state) {?>
              <option value="<?php echo $state -> getAttribute("abbr");?>" <?php if($post["b_state"] == $state -> getAttribute("abbr")) { echo "selected=\"selected\""; }?>><?php echo ucwords(strtolower($state -> getAttribute("name")));?></option>
            <?php } ?> 
          </select>		        
          <input id="fld-cc_zip" name="b_zipcode" class="text" type="text" value="<?php echo $post["b_zipcode"];?>" />		      
        </div>		    
        </fieldset>
            
        <fieldset class="buttons1">		    		      		      	
          <div class="btn-pay1">				
            <input id="btn-pay1" class="btn-submit-pay" type="image" src="/images/btn-text[pay][2].png" alt="pay" />			
          </div>	    
        </fieldset>
        </div> 
      </form>
      <?php }} ?>
    </div>
    <?php if (((isset($errors)) || (isset($_GET["err"]))) && ($step == 'purchase')) {?>
    <script type="text/javascript">
      $(document).ready(function(){
        screening_room.showErrors('step-pay');
      });
    </script>
    <div class="error-panel">		
      <div class="errors">
        
        <?php 
          if ($_GET["err"] == 'ticket') { 
           echo "This ticket is either used, or of not enough value. Please try again."; 
          }
          if ($_GET["err"] == 'validate') { 
           echo "There was a problem with your purchase, please try again."; 
          }
          echo $errors;
        ?>
      </div>	
    </div>
    <?php }?>
  </div>
  <!-- END STEP TWO -->
  
  <!-- STEP TWO AND A HALF-->
  
  <!-- END STEP TWO AND A HALF -->
  
  <?php if ($sf_user -> isAuthenticated()) {
    $c1="";
    if ($step =="invite") {
      $c1="step_current";
    }
  } else {
      $c1="";  
  } ?>
  <!-- STEP THREE -->
  <div id="step-invite" class="step <?php echo $c1;?>" style="position: relative;">	
    <div class="popup" id="preview-invite-popup">		
      <div class="close-bar">			
        <a id="close-preview-invite-popup">(close)</a>		
      </div>		
      <div class="content">		
      </div>	
    </div>
    <div class="popup" id="inviting-popup">	
      <p align="center">Sending Invitations...<br />
      <img src="/images/ajax-loader.gif" alt="loading" />	
      </p>
      <br />
      <p align="center" id="inviting-errors"></p>
    </div>
    <div class="step_decorator">  
      <div class="step_title">	    
        <h2 class="not_done">3. Invite Friends</h2>	    
        <h2 class="done">3. You are inviting 
          <strong class="nr-invitees">0</strong> people.</h2>	    
        <a class="edit" href="">invite more</a>	  
      </div>	  
      <?php if ($step == "invite") {?>
      <div class="popup-overlay"></div>
      <div id="already-invited-popup" class="popup-container">	
        <div class="popup-right">		
          <div class="popup-content">			
            <div class="x">				
              <a href="#" class="popup-close">close X</a>			
            </div>			
            <div class="popup-body">			
              <p>You have already invited the following emails:
              </p>  
              <div class="email-items-list">  
              </div>  	 			
            </div>		
          </div>	
        </div>
      </div>	  
      <form id="step-invite-form" action="#" method="post">	  	
        <input type="hidden" name="unique_key" class="unique_key" value="ieTFNqei7Fu4hjV" />					
        <input type="hidden" name="audience_id" class="audience_id" value="" />			
        <input type="hidden" name="invite_code" value="" />				
        <input type="hidden" name="import-contacts[from]" value="join" />		
        <p>Constellation does not store your email account password or contacts.
        </p>	    
        <fieldset class="buttons">	    	
          <p class="share">
            <span> SHARE ON: 
            </span>	    	 	    		    		 				
            <a href="" id="facebook-share-link">
              <img src="/images/icon-facebook-medium.png" alt="facebook" width="32" height="32" /></a>  			  	
            <a href="http://twitter.com/home?status=Constellation - /" target="_blank" id="twitter-share-link">
              <img src="/images/icon-twitter-medium.png" alt="twitter" width="32" height="32" /></a>  			 	    				
          </p>	    
        </fieldset>	    
        <fieldset class="data">	      
          <p>Or invite via email:
            <a class="help" href=""></a>
          </p>	      
          <ul>	     	  	        
            <li>
            <a id="gmail" href="#" class="choose-contacts-from">
              <img src="/images/btn-gmail.png" alt="gmail" width="70" height="37" /></a>
            </li>	        
            <li>
            <a id="aol" href="#" class="choose-contacts-from">
              <img src="/images/btn-aol.png" alt="aol" width="70" height="37" /></a>
            </li>	        
            <li>
            <a id="hotmail" href="#" class="choose-contacts-from">
              <img src="/images/btn-windows_live.png" alt="hotmail" width="70" height="37" /></a>
            </li>	        
            <li>
            <a id="yahoo" href="#" class="choose-contacts-from">
              <img src="/images/btn-yahoo.png" alt="yahoo!" width="70" height="37" /></a>
            </li>
            <a id="other" href="#" class="choose-contacts-from">
              <img src="/images/btn-other.png" alt="other" width="70" height="37" /></a>
            </li>	      
          </ul>
          <div id="import-contacts-container">		  	
            <div class="login" action="#" method="post">		  		
              <fieldset>		  			
                <input id="import-contacts-type" type="hidden" name="import-contacts[type]" value="" />					
                <div class="inputs">
                  <div class="formfield">							
                    <img src="" id="contact-source" />
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
                  </div>						
                  <div class="formfield">							
                    <label>Email:
                    </label>							
                    <input name="import-contacts[email]" type="text" class="text" id="import-contacts-email" />						
                  </div>						
                  <div class="formfield">							
                    <label>Password:
                    </label>							
                    <input name="import-contacts[password]" type="password" class="text" id="import-contacts-password" />						
                  </div>
                  <div id="contacts-error-area" class="formfield"></div>					
                </div>
                <input name="import-contacts[provider]" type="hidden" class="text" id="import-contacts-provider" value="0" />
                <input id="btn-import" class="btn-submit" type="image" src="/images/btn-text[import][2].png" alt="import" />					
                <div id="contacts-loading-area">						
                  <img src="/images/ajax-loader.gif" alt="loading" />					
                </div>				
              </fieldset>		  	
            </div>			
            <div class="import-contact-result" id="contacts-area">			
              <label id="all_contacts" for="checkAll" >
                <input id="checkAll" type="checkbox" name="checkall" uncheck/>
                </span> Select All Contacts
                </span>
              </label>				
              <div class="content">
              </div>				
              <input class="btn-submit accept-contacts" id="grab-contacts" type="image" src="/images/btn-text[import][2].png" alt="import" />			
            </div>		  
          </div>	      
          <p class="legend">Or manually enter email addresses 
            <span class="arrow-explain">(click the arrow to add to the invite list)
            </span>: 
            <span class="explain">(separate with commas)
            </span>	      		      
          </p>	      
          <div id="invites-container">	       		      
            <div id="fld-invites_container" class="formfield">		        
              <label id="fld-invites-label" for="fld-invites">Insert email addresses...</label>		        
              <textarea id="fld-invites" name="invite_emails" class="with-label"></textarea>  				
              <!-- <img id="text-contacts" class="accept-contacts" src="/images/arrow-right[black].png" alt="Accept email addresses" width="30" height="16" /> --> 		      
            </div>		       		      
            <img id="text-contacts" class="accept-contacts" src="/images/arrow-right[red].png" alt="Accept email addresses" width="35" height="21" />		       	      	  
            <ul id="accepted-invites-container">
            </ul>	      	   	      
          </div>	      
          <div id="fld-greeting_container" class="formfield">	      	
            <p class="legend">Personalize your invite:
            </p>	        
            <label id="fld-greeting-label" for="fld-greeting">Add a personal message to your invite (150 characters)...</label>	        
            <textarea id="fld-greeting" name="greeting" class="with-label" rows="2"></textarea>	      
          </div>	    
        </fieldset>	   	
        <fieldset class="buttons">	   	  
          <input id="btn-skip" class="btn-submit" type="image" src="/images/btn-text[skip-for-now].png" alt="skip for now" />		  
          <input id="btn-preview_invite" class="btn-submit" type="image" src="/images/btn-text[preview-invite][2].png" alt="preview invite" />	      
          <input id="btn-invite" type="image" class="btn-submit" src="/images/btn-text[invite][2].png" alt="invite" />	    
        </fieldset>	  
      </form>	
      <?php } ?>
    </div>	
    <div class="error-panel">		
      <div class="errors">			 		
      </div>	
    </div>
  </div>
  <!-- END STEP THREE -->
  
  <!-- STEP FOUR -->
  <div id="step-enter" class="step">
    <div class="step_decorator">  
      <div class="step_title">
        <h2><a id="golink">4. ENTER THE THEATER</a></h2>  
      </div>
    </div>
  </div>
  <div class="popup-overlay">
  </div>
  <!-- END STEP FOUR -->
  
  <?php if ($sf_user -> isAuthenticated()) {?>
  <!-- STEP FIVE -->
  <div id="step-enterCode">	 	
    <div>
      <h2>If you need to exchange a ticket from another screening for this one, enter your code here:</h2>	   	  
      <div class="error-panel">		
        <div class="errors">
        </div>	  
      </div>	   	  
      <form id="step-enterCode-form" action="#" method="post" style="display:block;">	  
        <input type="hidden" id="enter_unique_key" name="enter[s_unique_key]" class="unique_key" value="ieTFNqei7Fu4hjV" />		  	
        <fieldset class="data">		      
          <div class="formfield">		        
            <input id="fld-code" name="enter[payment_code]" class="text" type="text" />		        
            <a id="enter-code" class="link" href="javascript:void(0);"></a>		      
          </div>		    
        </fieldset>		
      </form>	
    </div>
  </div>
  <?php } ?>
</div>

<!-- #steps-all -->		
<div id="no_ticket-popup" class="popup-container">	
  <div class="popup-right">		
    <div class="popup-content">			
      <div class="x">				
        <a href="#" class="popup-close">close X</a>			
      </div>			
      <div class="popup-body">			
        <p>You must buy a ticket first.
        </p> 
        <br/>If you have already purchased a ticket, login and click on 'My Showtimes' at the top right or click 'Enter the Theater' on your email ticket.			
      </div>		
    </div>	
  </div>
</div>

<div class="popup-overlay">
</div>
<div id="sold-out-popup" class="popup-container">	
  <div class="popup-right">		
    <div class="popup-content">			
      <div class="x">				
        <a href="#" class="popup-close">close X</a>			
      </div>			
      <div class="popup-body"><h2>Sorry, this screening has been sold out.</h2>
        <p>Check out other showtimes on the left.
        </p>			
      </div>		
    </div>	
  </div>
</div>

<div id="screening" style="display:none"><?php echo $film["screening_unique_key"];?></div>
<div id="step" style="display:none"><?php echo $step;?></div>

<div id="host" class="reqs"><?php echo $chat_instance_host;?></div>
<div id="port" class="reqs"><?php echo $chat_instance_port_base;?></div>
