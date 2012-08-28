<?php if ((! $sf_user -> isAuthenticated()) || ($sf_user -> getAttribute("temp"))) {?>
<div class="pops" id="main-login-popup" style="display: none;">
  
  	  <div id="log-in">
        <h4>Log in</h4>
        <p>Don't have an account yet? <a id="main-choose-signup" href="#">Sign up here &raquo;</a></p>
        
				<div class="or_login_alt">
          <span>
            <a id="login_fb_link" style="overflow: hidden;" href="/services/Facebook/login?dest=http://<?php echo $_SERVER["SERVER_NAME"];?><?php echo $_SERVER["REQUEST_URI"];?>"><img src="/images/alt1/fb_connect.png" alt="" /></a> 
            <a id="login_twitter_link" style="overflow: hidden;" href="/services/Twitter/login?dest=http://<?php echo $_SERVER["SERVER_NAME"];?><?php echo $_SERVER["REQUEST_URI"];?>"><img src="/images/alt1/tw_connect.png" alt="" /></a> 
          </span>
        </div>
        
				<form id="login_form" name="login_form" action="/services/Login" method="POST" class="login_form">
      	  <div class="label">E-Mail</div>
          <input name="email" id="login_email" class="input login-element" type="text" />
          
      	  <div class="label">Password</div>
          <input name="password" id="login_password" class="input login-element" type="password" />
          
          <div class="password">
            <table width="365">
							<tr>
								<td valign="top">
									<p>
										<a href="#" id="main-choose-password">Forgot password?</a>
									</p>
								</td>
								<td valign="top" align="right">
									<input type="image" src="/images/alt1/login_button.png" name="login" id="login-button" value="log in" />
								</td>
							</tr>
						</table>
            <a id="main-choose-login" href="#" style="display:none">Log In</a>
          </div>
            
          <?php if ((isset($_GET["err"])) && ($_GET["err"] == 'login')) {?>
          <script type="text/javascript">
            $(document).ready(function() {
            <?php if ($_GET["errs"] == 'pass') {?>
            error.showError("error","Your password is incorrect.","Select \"Forgot Password?\" to have your password emailed to you.",6000);
						<?php } elseif ($_GET["errs"] == 'email') {?>
            error.showError('error','Your email wasn\'t found','We were unable to find your account, please try again.',6000);
            <?php } else{ ?>
            error.showError('error','There was an error, please try again.',null,6000);
            <?php }?>
            });
          </script>
          <?php }?>
          <input type="hidden" value="<?php echo preg_replace("/\?err=([^&].+)/","",$_SERVER["REQUEST_URI"]);?>" name="source" />
          <input type="hidden" id="login_destination" value="<?php echo preg_replace("/\?err=([^&].+)/","",$_SERVER["REQUEST_URI"]);?>" name="destination" />
          <input type="hidden" value="true" name="indirect" />
          <input type="hidden" value="true" name="popup" />
          <input type="hidden" value="login" name="type" />
            
        </form>
        
      </div>
      
      <div id="password-out" style="display: none">
        <!--<div class="title"><strong>Login</strong></div><br />-->
        <form id="password_form" name="password_form" action="#" method="POST" class="password_form">
        	<div class="label">Your Email</div>
					<input name="email" id="password_email" class="input password-element" type="text" />
          <div class="signup" style="width: 100%; text-align: right;">
              <input type="image" src="/images/alt1/send_button.png" name="login" id="password-button" value="log in" />
            </div>
        </form>
      </div>
      
      <div id="sign-up" style="display: none">
        <!--<div class="title"><strong>Sign-up</strong></div><br />-->
        <form id="sign-up_form" name="sign-up_form" action="/services/Join" method="POST" class="sign-up_form">
          	<div class="label">Your Name</div>
						<input id="main-signup-name" value="" name="name" type="text" class="input signup-element" />
            <div class="label">Username</div>
						<input id="main-signup-username" value="" name="username" type="text" class="input signup-element" />
            <div class="label">Birthday</div>
            <div class="label">
						  <select name="month">
                <option value="01" >Jan</option>  						           				
                <option value="02" >Feb</option>  						           				
                <option value="03" >Mar</option>  						           				
                <option value="04" >Apr</option>  						           				
                <option value="05" >May</option>  						           				
                <option value="06" >Jun</option>  						           				
                <option value="07" >Jul</option>  						           				
                <option value="08" >Aug</option>  						           				
                <option value="09" >Sep</option>  						           				
                <option value="10" >Oct</option>  						           				
                <option value="11" >Nov</option>  						           				
                <option value="12" >Dec</option>  				
              </select> 
              <select name="day">
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
                <option value="13" >13</option>  						           				
                <option value="14" >14</option>  						           				
                <option value="15" >15</option>  						           				
                <option value="16" >16</option>  						           				
                <option value="17" >17</option>  						           				
                <option value="18" >18</option>  						           				
                <option value="19" >19</option>  						           				
                <option value="20" >20</option>  						           				
                <option value="21" >21</option>  						           				
                <option value="22" >22</option>  						           				
                <option value="23" >23</option>  						           				
                <option value="24" >24</option>  						           				
                <option value="25" >25</option>  						           				
                <option value="26" >26</option>  						           				
                <option value="27" >27</option>  						           				
                <option value="28" >28</option>  						           				
                <option value="29" >29</option>  						           				
                <option value="30" >30</option>  						           				
                <option value="31" >31</option> 
              </select> 
              <select name="year">
                <?php for($yr=1920;$yr<=year();$yr++) {?>
                <option  value="<?php echo $yr;?>" <?php if ($yr == 1970) { echo "selected='selected'"; }?> ><?php echo $yr;?></option>						     			     
                <?php } ?>
              </select>
              </div>
							<div class="label">Email Address</div>
							<input id="main-signup-email" value="" name="email" type="text" class="input signup-element" />
              <div class="label">Password</div>
							<input id="main-signup-password" name="password" type="password" class="input signup-element" />
              <div class="label">Confirm Password</div>
							<input id="main-signup-password2" name="password2" type="password" class="input signup-element" />
              <div class="label">Optin Email</div>
							<div class="optin">
                <span>
                <input id="main-signup-optin" name="optin" type="checkbox" class="" checked="checked" />
                </span>
                <span id="main_signup_text">
                I agree to allow Constellation.tv to contact me via email and SMS.
                </span>
              </div>
              
              <div class="signup" style="width: 100%; text-align: right;">
                <input type="image" src="/images/alt1/signup_button.png" name="login" id="signup-button" value="sign up" />
              </div>                                                                                            
              <input type="hidden" value="<?php echo preg_replace("/\?err=([^&].+)/","",$_SERVER["REQUEST_URI"]);?>" name="source" />
              <input type="hidden" id="signup_destination" value="<?php echo preg_replace("/\?err=([^&].+)/","",$_SERVER["REQUEST_URI"]);?>" name="destination" />
              <input type="hidden" value="true" name="indirect" />
              <input type="hidden" value="true" name="popup" />
              <input type="hidden" value="signup" name="type" />
              <?php if ((isset($_GET["err"])) && ($_GET["err"] == 'signup')) {?>
              <script type="text/javascript">
              	$(document).ready(function(){
                	<?php if (($_GET["errs"]=='username') || ($_GET["errs"]=='email') || ($_GET["errs"]=='pass')) {?>
									error.showError("error","A user already exists for this email address.","Select \"Forgot Password?\" to have your password emailed to you.",6000);
								  <?php } else if ($_GET["errs"]=='password') {?>
								  error.showError("error","Your password is incorrect.","Select \"Forgot Password?\" to have your password emailed to you.",6000);
								  <?php } else if ($_GET["errs"]=='username&password') {?>
									error.showError("error","A user already exists for this email address.","Select \"Forgot Password?\" to have your password emailed to you.",6000);
								  <?php } ?>
              	});
							</script>
              <?php }?>
          </form>
          <!--<div class="forgot_sign-up"><a href="#">forgot password?</a> | <a id="main-choose-login" href="#">log in</a></div>-->
        </div>
      
</div>

<?php if (isset($_GET["err"])) {?>
<script type="text/javascript">
$(document).ready( function() {
  <?php if ($_GET["err"]=='signup') {?>
	login.showsignup();
  <?php } else {?>
  login.showpopup();
  <?php } ?>
});
</script>
<?php }} else { ?>
<div class="pops" id="main-login-popup" style="display: none;">
  <h4>Logged In</h4>
	<div class="your-screening-area">       
    	<div class="title">My Tickets</div> 
    	<div id="your-screening-ajax-load" class="screening-payload"><center></center></div>
  </div>
  <div class="your-hosting-area">       
    	<div class="title">I Am Hosting</div> 
    	<div id="your-hosting-ajax-load" class="screening-payload"><center></center></div>
  </div>
  <div class="logout-area">
    <!--<img alt="custom connect" class="c-connect-img" src="/images/c-connect.png" id="main-login-icon" />
    <img alt="custom connect"  class="-connect-img" src="/images/fb-connect.png" id="main-login-icon" />
    <img alt="custom connect" class="t-connect-img" src="/images/tw-connect.png" id="main-login-icon">-->
    <?php echo($sf_user -> getAttribute('user_username')); ?> - <a href="/account">My Account</a> &nbsp;&nbsp;&nbsp; <a class="link logout" href="/logout?dest=<?php echo $LOGOUT_URL;?>">Logout</a>
  </div>
</div>
<div id="auth" style="display:none;"></div>
<?php } ?>
