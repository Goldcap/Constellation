<h1 class="join-filmTitle">HOST A SCREENING OF: <br />
  <?php echo strtoupper($film["film_name"]);?>
  </h1>			 			
<div id="movie_trailer_container" class="nx_widget_video">	    
    <a id="movie_trailer" href="/uploads/screeningResources/<?php echo $film["film_id"];?>/still/<?php echo $film["film_still_image"];?>" alt="<?php echo $film["film_name"];?>" class="nx_widget_player" border="0">            
      <img src="/uploads/screeningResources/<?php echo $film["film_id"];?>/still/<?php echo $film["film_still_image"];?>" alt="<?php echo $film["film_name"];?>" class="widget_video_still"  border="0"/>			
      <img src="/images/play.png" width="44" height="44" alt="play" class="play-button"/>			 			
      <script type="text/javascript"> 
      $(document).ready(function(){
        videoplayer.initPlayer();
      });
      </script>
    </a>	    
  <div class="play-button"></div>	
</div>
<span class="reqs" id="video">/uploads/screeningResources/<?php echo $film["film_id"];?>/trailerFile/<?php echo $film["film_trailer_file"];?></span>
<span class="reqs" id="video-autoplay">true</span>
<span class="reqs" id="video-type">VOD</span>

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
      <a href="/logout?dest=/host/<?php echo $film["film_id"]?>" class="edit">logout</a>
      
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
                                                          'destination'=>'/host/'.$film["film_id"].'/detail')) ?>
        
        <!-- LOGIN AND SIGNUP -->
        <div class="login-signup-container" id="step-custom-login-container">
          
          <!-- LOGIN -->
          <?php include_component('default', 'login', array(
                                                      'error'=>$_GET['err'],
                                                      'destination'=>'/host/'.$film["film_id"].'/detail',
                                                      'source'=>'/host/'.$film["film_id"]
                                                      )) ?>
          <!-- END LOGIN -->
          
          <!-- SIGN-UP -->
          <?php include_component('default', 'signup', array(
                                                      'error'=>$_GET['err'],
                                                      'destination'=>'/host/'.$film["film_id"].'/detail',
                                                      'source'=>'/host/'.$film["film_id"]
                                                      )) ?>
          <!-- END SIGNUP -->
        </div>
        <!-- END LOGIN AND SIGNUP -->
        
      </fieldset>
    </div>
    <?php } ?>
  </div>

</div>
<!-- END STEP ONE -->
  
  <?php 
  if ($sf_user -> isAuthenticated()) {
    if (($step =="detail") || ($step =="purchase")) {
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
      <div class="step_title">	    
        <h2 class="title_step">2. HOST A SCREENING for <?php echo sprintf("$%.02f",$film["film_setup_price"]);?></h2>	    
        <h2 class="title_step_done">2. <?php echo sprintf("$%.02f",$film["film_setup_price"]);?> PAID</h2>	  
      </div>
      
      <?php if ($step == "detail") {?>
      <div class="step_subtitle">We will send a hosting information to your email address. You will also be able to invite your friends to this screening!</div>	   	  
      <form id="step-detail-form" action="/host/<?php echo $film["film_id"];?>/detail" method="post">	  	
        <input type="hidden" name="type" class="type" value="detail" />		
        <input type="hidden" name="setup_price" value="<?php echo $film["film_setup_price"];?>" />		
        <input type="hidden" value="<?php echo $film["film_id"];?>" class="unique_key" name="film_id">
        <span id="host_id" style="display:none"><?php echo $sf_user -> getAttribute("user_id");?></span>
        <fieldset class="data" id="fieldsets-payment-option-cc">
        <!-- HOSTING OPTIONS -->
          <div class="formfield">
            <div id="formfield-email" class="formfield">
              <label for="fld-host_date">Choose A Date:</label>
              <input type="text" name="fld-host_date" id="host_date" value="" />
            </div>
          </div>
          <div class="formfield">
            <div id="formfield-host-type" class="formfield">
              <label for="fld-cc_confirm_email">Specify the type:</label><br />
              <input id="fld-host-type" type="radio" name="host-type" value="Featured" checked="checked"/> Featured<br />
              <input id="fld-host-type" type="radio" name="host-type" value="Public" /> Public<br />
              <input id="fld-host-type" type="radio" name="host-type" value="Private" /> Private
            </div>
         </div>
         <div class="formfield greeting">
            <div id="fld-greeting_container" class="formfield">	      	
              <p class="legend">Personalize your screening:</p>	        
              <label id="fld-greeting-label" for="fld-greeting">Add a personal message to your screening (150 characters)...</label>	        
              <textarea id="fld-greeting" name="greeting" class="with-label screening" rows="2"></textarea>	      
            </div>
          </div>
         <div class="formfield">
            <div id="formfield-host-image" class="formfield">
              <span class="col1 display" id="">Upload an image for your host profile:</span>
              <span class="col2">
              	<a id="REF_host_image_original" href="#FORM_host_image_original">
                  <button type="button" name="BUTTON_host_image_original" id="" border="0">Upload Image</button>
                </a>
              </span>
              <script type="text/javascript">
            	$(document).ready(function() {
                pic="<?php echo $sf_user -> getAttribute('user_image');?>";
                $("#host_image_original_preview").attr("src",pic);
                $("#host_image_original_preview_wrapper").show();
              });
              </script>
              <div style="display:none">
                <div id="FORM_host_image_original" class="swfuploader">
                  <input type="file" id="FILE_host_image_original" name="FILE_host_image_original" />
                </div>
              </div>
              <span id="host_image_original_preview_wrapper" style="display:none; text-align: center; width: 100%; float: left; padding-top: 10px; padding-bottom: 10px;">
                <img width="" height="" border="0" onmouseover="" onclick="" class="" id="host_image_original_preview" name="host_image_original_preview" src="" />
              </span>
            </div>
            <div class="formfield">	
              <input id="btn-host-next" class="btn-submit-pay subscription-next" type="image" src="/images/btn-text-subscribe-next.png" alt="next step" name="method" value="action_detail" />	        
            </div>
          </div>
          </form>
          <?php } elseif ($step == "purchase") {?>
          <div id="subscription-payment-details">
            <div id="subscription_info" class="subscriptions">
              <!-- HOST OPTIONS -->
              <div class="formfield">
                <div id="formfield-email" class="formfield">
                  <label for="fld-host_date">Screening Date:</label><br />
                  <span style="font-weight: bold">
                  <?php echo formatDate($screening["screening_date"],"pretty");?>
                  </span>
                </div>
              </div>
              <div class="formfield">
                <div id="formfield-host-type" class="formfield">
                  <label for="fld-cc_confirm_email">Screening Type:</label>
                  <span style="font-weight: bold">
                  <?php if($screening["screening_is_private"] == 1) { echo "Private"; }?>
                  <?php if($screening["screening_type"] == 2) { echo "Featured"; }?>
                  <?php if($screening["screening_type"] != 2) { echo "Public"; }?>
                  </span>
                </div>
             </div>
             <div class="formfield">
              <div id="formfield-host-image" class="formfield">
                <span class="col1 display" id="">Host Image:</span><br />
                <img width="" height="" border="0" onmouseover="" onclick="" class="" id="host_image_original_preview" name="host_image_original_preview" src="/uploads/hosts/<?php echo $screening["screening_guest_image"];?>" />
              </div>
            </div>
            </div>
         <!-- END HOST OPTIONS -->
         </div>	      
          
          <form id="step-pay-form" action="/host/<?php echo $film["film_id"];?>/purchase/<?php echo $screening["screening_unique_key"];?>" method="post">
          <div id="payment_info">
            <fieldset class="data" id="fieldsets-payment-option-cc">
            <div class="formfield">		      	
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
                    <a href="/services/Paypal/express/host?i=<?php echo $screening["screening_unique_key"];?>" class="credit-cards" rel="5" title="paypal" id="paypal-button"></a>						
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
        </form>
        </fieldset>
        </div>
      <?php } ?>
    </div>
  <?php if (((isset($errors)) || (isset($_GET["err"]))) && ($step == 'purchase')) {?>
  <script type="text/javascript">
    $(document).ready(function(){
      screening_room.showErrors('step-pay');
    });
  </script>
  <div class="error-panel">		
    <div class="errors">
      <?php if ($_GET["err"] == 'signup') { 
        if ($_GET["errs"] == "email") {
         echo "There was a problem with your email, please try again."; 
        } elseif ($_GET["errs"] == "pass") {
        echo "There was a problem with your password, please try again."; 
        } else {
        echo "There was a problem with your order, please try again."; 
        }}
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
        <h2><a id="golink">4. BACK TO THE HOMEPAGE</a></h2>  
      </div>
    </div>
  </div>
  <div class="popup-overlay">
  </div>
  <!-- END STEP FOUR -->
  
</div>

<div id="screening" style="display:none"><?php if (isset($screening)) {echo $screening["screening_unique_key"];}?></div>
<div id="step" style="display:none"><?php echo $step;?></div>
