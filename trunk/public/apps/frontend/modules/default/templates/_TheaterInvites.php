<div class="pop_up" id="screening_invite" style="display: none">
  <div class="pop_mid">
  <div class="pop_top popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
      	<!--<p><strong>
        <?php echo $film["screening_film_name"];?>
        </strong></p>-->
        <p><strong>invite friends via:</strong></p>
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
                      <p class="legend">Personalize your invite:</p>	        
                      <label id="invite-fld-greeting-label" for="fld-greeting">Add a personal message to your invite <span id="invite-textbox-limit">150 characters</span></label>	        
                      <textarea id="invite-fld-greeting" name="greeting" class="with-label host-invite" rows="2"></textarea>	      
                    </div>
                    <div class="soc_message">
                      <input type="button" id="btn-preview_invite" value="preview" class="btn_small mr_3" name="" />
                      <input type="button" id="btn-invite" value="send" class="btn_small" name="" />
                    </div>
                  <div class="clear"></div>
                  <!--<h2>Number of invites: <span id="number_invites">0</span></span></h2>-->
                  <input type="hidden" id="fb_session" value="" name="fb_session" />
                  <input type="hidden" id="tw_session" value="" name="tw_session" />
                  <input type="hidden" id="email_session" value="" name="email_session" />
             </fieldset>
          </form>
      </div>
      
  <div class="pop_bot"></div>
  </div>    
</div>
