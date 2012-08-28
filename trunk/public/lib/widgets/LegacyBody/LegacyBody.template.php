<!-- BEGIN MAIN LOGIN -->

<div id="main-login-popup" class="popup-container">	
  <div class="popup-right">		
    <div class="popup-content">			
      <div class="x">				
        <a href="#" class="popup-close">close X</a>			
      </div>			
      <div class="popup-body">			   	
        <div id="join-step-login" class="pseudo-step">	 	  
          <div id="main-join-logged-in" class="step_title" style="display:none;"></div>    
          
          <!-- BEGIN JOIN FORM -->
           
            <fieldset class="data">          		     			
              
              <div id="main-auth-options">     		   		 	      
              
                <!-- LOGIN OPTIONS -->
                   
                <div id="main-login-options" class="formfield">		      	 	      	 	      			      	
                  <div id="main-logged-out" class="login-status" >
                  <?php if($sf_user -> isAuthenticated()) {?>
                    <h2>Logged in <a class="link logout" href="/logout">(logout)</a></h2>  			        
                    <h2 class="done">
                      <img alt="custom connect" class="c-connect-img" src="/images/c-connect.png" id="main-login-icon" />
                      <!--<img alt="custom connect"  class="-connect-img" src="/images/fb-connect.png" id="main-login-icon" />
                      <img alt="custom connect" class="t-connect-img" src="/images/tw-connect.png" id="main-login-icon">-->
                      
                      <span class="full-name-area"><?php echo($sf_user -> getAttribute('user_username')); ?></span> - connected.</h2>  			    
                  <?php } else { ?>
                    <h2>Login <br />
                    <?php if(($_GET["err"] == "t") || ($_GET["err"] == "f")) {?>
                    <i style="color: red; font-size: 10px;">There was an error with your login, please try again.</i>
                    <?php } else { ?>
                    <i>(To identify yourself in the theater. We do not collect your private information).</i>
                    <?php }?>
                    </h2>  						     	
                    <span id="main-login-menu">Choose one:</span>  			     	
                    <a style="overflow: hidden;" href="/services/Facebook/login">  						
                       <img src="/images/fb-connect.png" alt="fb connect" title="facebook connect"  />
                    </a>  			        
                    <a style="overflow: hidden;" href="/services/Twitter/login">  						
                      <img src="/images/tw-connect.png" alt="tw connect" title="twitter connect" />
                    </a>  					
                    <div class="other-login-options" style=""> or create/login with a different username:</div>
                    <img id="main-custom-connect-icon" class="join" src="/images/c-connect.png" alt="custom connect" title="custom connect" />  							
                  <?php } ?>
                  </div>  		   	
                </div>
                
              <?php if ((isset($_GET["err"])) && (isset($_GET['p']))) {?>
              <script type="text/javascript">
              $(document).ready( function() {
                login.showpopup();
                <?php if ($_GET["err"]=='signup') {?>
            		login.showsignup();
                <?php } ?>
              });
              </script>
              <?php } ?>
                					
              </div>
              
              <!-- END LOGIN OPTIONS-->
              
              <!-- BEGIN LOGIN CONTAINER -->         
               <div id="main-custom-login-container" class="login-signup-container">	
                
                 
                <!--LOGIN FORM-->
          	   <input id="main-signup-connect-type" name="connect-type" value="login" type="hidden" />
          	   <?php if($sf_user -> isAuthenticated()) {?>
          	    <div class="formfield first-formfield">
          	     <a href="/account">My Account</a>
                </div>
    		      	  <div class="formfield first-formfield">
        						<label id="main-formlet-other-label-full-name"><?php echo $sf_user -> getAttribute('user_username');?></label>
        						<img id="main-formlet-other-img-photo-url" src="<?php echo $sf_user -> getAttribute('user_image');?>" width="48" height="48" />
        			  	</div>
          		  <?php } else {?>     	
            		    
                    <div id="login-signup">       	       		 		      	
                      
                      <!-- LOGIN -->
                      <div id="main-formlet-login">
                        <form id="main-login-form" action="/services/Login" method="post">       		  	
                        <input type="hidden" value="<?php echo $_SERVER["REQUEST_URI"];?>" name="source" />
                        <input type="hidden" value="<?php echo $_SERVER["REQUEST_URI"];?>" name="destination" />
                        <input type="hidden" value="true" name="indirect" />
                        <input type="hidden" value="true" name="popup" />
                        <fieldset class="formlet" style="display: block;" >  		      		
                          <div class="head">  	      				
                            <span>Login
                            </span>  						
                            <p>or 
                              <a id="main-choose-signup" rel="login-field" href="javascript:;">Sign-up</a>
                            </p>  					
                          </div>  								      		
                          <div class="formfield first-formfield">  							
                            <label>Email: 
                            </label>  							
                            <input id="main-login-email" value="" name="email" type="text" class="text" />  						
                          </div>  						
                          <div class="formfield">  							
                            <label>Password: 
                            </label>  							
                            <input id="main-login-password" name="password" type="password" class="text" />  						
                          </div>  						
                          <fieldset class="buttons">  					      
                            <input id="main-btn-login" class="btn-submit btn-login-signup" type="image" src="/images/btn-text[login][2].png" alt="login" name="login_action" />  					    
                          </fieldset>
                          <?php if ((isset($_GET["err"])) && ($_GET["err"] == 'login')) {?>
                          <fieldset class="formfield">
                            <span style="color:red">
                            <?php if ($_GET["errs"] == 'pass') {?>
                            Your password is incorrect, please try again...
                            <?} elseif ($_GET["errs"] == 'email') {?>
                            Your email wasn't found, please try again...
                            <?} else{?>
                            There was an error, please try again...
                            <?}?>
                            </span>
                          </fieldset>
                          <?php }?>				      	
                        </fieldset>
                        
                        </form>
                      </div>
                          	 		      	
                      <!-- SIGN-UP -->
                      <div id="main-formlet-signup" style="display:none">
                        <form id="main-login-form" action="/services/Join" method="post">       		  	
                        <input type="hidden" value="<?php echo $_SERVER["REQUEST_URI"];?>" name="source" />
                        <input type="hidden" value="<?php echo $_SERVER["REQUEST_URI"];?>" name="destination" />
                        <input type="hidden" value="true" name="indirect" />
                        <input type="hidden" value="true" name="popup" />
                          
                        <fieldset class="formlet">  				
                          <div class="head">        				
                            <span>Sign-up</span>  					
                            <p>or 
                              <a id="main-choose-login" rel="login-field" href="javascript:void(0);">Login</a>
                            </p>  				
                          </div>  				
                          <div class="formfield">  					
                            <label>Your Name: 
                            </label>  					
                            <input id="main-signup-name" value="" name="name" type="text" class="text" />  				
                          </div>  				
                          <div class="formfield">  					
                            <label>Username: 
                            </label>  					
                            <input id="main-signup-username" value="" name="username" type="text" class="text" />  				
                          </div>  				
                          <div class="formfield">  					
                            <label>Email: 
                            </label>  					
                            <input id="main-signup-email" value="" name="email" type="text" class="text" />  				
                          </div>  				
                          <div class="formfield">  				 
                            <fieldset class="birthday">  					
                              <label> Birthdate: 
                              </label>  					
                              <div class="formfield-birthday">  	          			
                                <select id="main-fld_month" name="month">  						           				
                                  <option value="01" >Jan
                                  </option>  						           				
                                  <option value="02" >Feb
                                  </option>  						           				
                                  <option value="03" >Mar
                                  </option>  						           				
                                  <option value="04" >Apr
                                  </option>  						           				
                                  <option value="05" >May
                                  </option>  						           				
                                  <option value="06" >Jun
                                  </option>  						           				
                                  <option value="07" >Jul
                                  </option>  						           				
                                  <option value="08" >Aug
                                  </option>  						           				
                                  <option value="09" >Sep
                                  </option>  						           				
                                  <option value="10" >Oct
                                  </option>  						           				
                                  <option value="11" >Nov
                                  </option>  						           				
                                  <option value="12" >Dec
                                  </option>  						          			
                                </select>  	        		
                              </div>  					
                              <div class="formfield-birthday">  	          			
                                <select id="main-fld_day" name="day">  						           				
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
                                  <option value="13" >13
                                  </option>  						           				
                                  <option value="14" >14
                                  </option>  						           				
                                  <option value="15" >15
                                  </option>  						           				
                                  <option value="16" >16
                                  </option>  						           				
                                  <option value="17" >17
                                  </option>  						           				
                                  <option value="18" >18
                                  </option>  						           				
                                  <option value="19" >19
                                  </option>  						           				
                                  <option value="20" >20
                                  </option>  						           				
                                  <option value="21" >21
                                  </option>  						           				
                                  <option value="22" >22
                                  </option>  						           				
                                  <option value="23" >23
                                  </option>  						           				
                                  <option value="24" >24
                                  </option>  						           				
                                  <option value="25" >25
                                  </option>  						           				
                                  <option value="26" >26
                                  </option>  						           				
                                  <option value="27" >27
                                  </option>  						           				
                                  <option value="28" >28
                                  </option>  						           				
                                  <option value="29" >29
                                  </option>  						           				
                                  <option value="30" >30
                                  </option>  						           				
                                  <option value="31" >31
                                  </option>  						          			
                                </select>  	        		
                              </div>  	        		
                              <div class="formfield-birthday">  	         			
                                <select id="main-fld_year" name="year">  				                                 
                                  <?php for($yr=1920;$yr<=year();$yr++) {?>
                                  <option  value="<?php echo $yr;?>" <?php if ($yr == 1970) { echo "selected='selected'"; }?> ><?php echo $yr;?></option>						     			     
                                  <?php } ?>
                                </select>  	      		  
                              </div>  	          	
                            </fieldset>	        	
                          </div>  			 				
                          <div class="formfield">  					
                            <label>Password: 
                            </label>  					
                            <input id="main-signup-password" name="signup[password]" type="password" class="text" />  				
                          </div>  				
                          <div class="formfield">  					
                            <label>Confirm Password: 
                            </label>  					
                            <input id="main-signup-password2" name="signup[password2]" type="password" class="text" />  				
                          </div>  				 				
                          <fieldset class="buttons">  			      
                            <input id="main-btn-signup" class="btn-submit btn-login-signup" type="image" src="/images/btn-text[sign-up][2].png" alt="signup" name="submit_action" />  			    
                          </fieldset>
                          <?php if ((isset($_GET["err"])) && ($_GET["err"] == 'signup')) {?>
                          <fieldset class="formfield">
                            <span style="color:red">There was an error, please try again...</span>
                          </fieldset>
                          <?php }?>	
                        </fieldset>
                        </form>
                      </div>
                      <!-- END JOIN FORM-->
                      
                    </div> 
                 <?php } ?> 
              <!-- END LOGIN -->
                <div class="error-panel">  	
                  <div class="errors"></div>  
                </div>
                	
              </div> 
        <!-- END LOGIN CONTAINER -->
           
            </fieldset> 
          </form>
        </div>
      </div>  	 
    </div>			
  </div>		
</div>

<!-- END MAIN LOGIN -->

<!-- BEGIN SCREENINGS POPUP -->
<div id="your-screenings-popup" class="popup-container">	
  <div class="popup-right">		
    <div class="popup-content">			
      <div class="x">				
        <a href="#" class="popup-close">close X</a>			
      </div>			
      <div class="popup-body">			  
        <div class="your-screenings-area">           
          	<h2>Your upcoming screenings: </h2> 
          <!--<p>(You can edit a screening until 24 hours before the screening)</p> --> 
          	<div id="your-screenings-ajax-load"><center></center></div>
        </div>			
      </div>		
    </div>	
  </div>
</div>
<!-- END SCREENINGS POPUP -->

<!-- BEGIN JOIN POPUP -->
<div class="popup-overlay"></div>
<div id="joinScreening-popup" class="popup-container">	
  <div class="popup-right">		
    <div class="popup-content">			
      <div class="x">				
        <a href="#" class="popup-close">close X</a>			
      </div>			
      <div class="popup-body">			
        <div class="your-screenings-area">	<h2>This show is in progress: </h2>	
          <div id="buttons">		
            <a href="#" class="btn-buy-anyway"></a>		
            <a href="" class="btn-another-showtime"></a>	
          </div>
        </div>			
      </div>		
    </div>	
  </div>
</div>
<!-- END JOIN POPUP -->
