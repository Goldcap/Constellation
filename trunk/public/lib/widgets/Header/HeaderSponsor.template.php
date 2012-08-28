<div class="logo"><a title="Return to the home page" href="/">constellation</a></div>   

<?php if (($sf_user -> isAuthenticated()) && ((is_null($sf_user -> getAttribute("temp"))) || (! $sf_user -> getAttribute("temp")))) {?>
<div class="username">
  <table cellspacing="6">
    <tr>
      <td valign="top" align="right">
        <div id="full-name" class="main-login"><?php echo $sf_user -> getAttribute('user_username');?></div>
        <div id="your-screenings" class="main-showtimes">My Account</div>
      </td>
      <td>
        <div class="photo-container">
          <img id="photo-url" src="<?php echo $sf_user -> getAttribute('user_image');?>" class="main-login" width="48" height="48">
        </div>
      </td>
      
    </tr>
  </table>
</div>
<?php } else { ?>
<div class="login main-login">login</div> 
<?php } ?>

<?php if ((! $sf_user -> isAuthenticated()) || ($sf_user -> getAttribute("temp"))) {?>
<div class="pop_up" id="main-login-popup" style="display: none;">
  <div class="pop_mid">
  <div class="pop_top popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
  	  <div id="log-in">
        <!--<div class="title"><strong>Login</strong></div><br />-->
        <form id="login_form" name="login_form" action="/services/Login" method="POST" class="login_form">
        	<div>
            <input name="email" id="login_email" class="input login-element" type="text" />
            <input name="password" id="login_password" class="input login-element" type="password" />
            <input name="login" id="login-button" class="btn_small" value="log in" type="submit" />
          </div>
          <?php if ((isset($_GET["err"])) && ($_GET["err"] == 'login')) {?>
          <script type="text/javascript">
            $(document).ready(function() {
            <?php if ($_GET["errs"] == 'pass') {?>
            error.showError('error','Your password is incorrect, please try again.');
            <?php } elseif ($_GET["errs"] == 'email') {?>
            error.showError('error','Your email wasn\'t found, please try again.');
            <?php } else{ ?>
            error.showError('error','There was an error, please try again.');
            <?php }?>
            });
          </script>
          <?php }?>
          <input type="hidden" value="<?php echo $_SERVER["REQUEST_URI"];?>" name="source" />
          <input type="hidden" id="login_destination" value="<?php echo $_SERVER["REQUEST_URI"];?>" name="destination" />
          <input type="hidden" value="true" name="indirect" />
          <input type="hidden" value="true" name="popup" />
          <input type="hidden" value="login" name="type" />
            
        </form>
        
        <div class="or_login_w">&nbsp;
          or log in with: &nbsp;&nbsp;&nbsp;<a style="overflow: hidden;" href="/services/Facebook/login"> <img src="/images/icon_face-lg.gif" alt="" /></a> <a style="overflow: hidden;" href="/services/Twitter/login"> <img src="/images/icon_twit-lg.gif" alt="" /></a>
        </div>
        <div class="forgot_sign-up"><a href="#" id="main-choose-password">forgot password?</a> | <a id="main-choose-signup" href="#">sign up</a><a id="main-choose-login" href="#" style="display:none">log in</a></div>
      </div>
      
      <div id="password-out" style="display: none">
        <!--<div class="title"><strong>Login</strong></div><br />-->
        <form id="password_form" name="password_form" action="#" method="POST" class="password_form">
        	<div>
            <input name="email" id="password_email" class="input password-element" type="text" />
            <input name="send" id="password-button" class="btn_small" value="send" type="button" />
          </div>
        </form>
      </div>
      
      <div id="sign-up" style="display: none">
        <!--<div class="title"><strong>Sign-up</strong></div><br />-->
        <form id="sign-up_form" name="sign-up_form" action="/services/Join" method="POST" class="sign-up_form">
          	<fieldset>
              	<div class="clearfix"><label>Your Name</label> <input id="main-signup-name" value="" name="name" type="text" class="input signup-element" /></div>
                  <div class="clearfix"><label>Username</label> <input id="main-signup-username" value="" name="username" type="text" class="input signup-element" /></div>
                  <div class="clearfix"><label>Birthday</label> 
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
                  <div class="clearfix"><label>Email Address</label> <input id="main-signup-email" value="" name="email" type="text" class="input signup-element" /></div>
                  <div class="clearfix"><label>Password</label> <input id="main-signup-password" name="password" type="password" class="input signup-element" /></div>
                  <div class="clearfix"><label>Confirm Password</label> <input id="main-signup-password2" name="password2" type="password" class="input signup-element" /></div>
                  <div class="button"><input name="signup" id="signup-button" class="btn_small" value="sign up" type="submit" /></div>
              </fieldset>
              <input type="hidden" value="<?php echo $_SERVER["REQUEST_URI"];?>" name="source" />
              <input type="hidden" id="signup_destination" value="<?php echo $_SERVER["REQUEST_URI"];?>" name="destination" />
              <input type="hidden" value="true" name="indirect" />
              <input type="hidden" value="true" name="popup" />
              <input type="hidden" value="signup" name="type" />
              <?php if ((isset($_GET["err"])) && ($_GET["err"] == 'signup')) {?>
              <script type="text/javascript">
                error.showError('error','There was an error with your signup information, please try again.');
              </script>
              <?php }?>
          </form>
          <!--<div class="forgot_sign-up"><a href="#">forgot password?</a> | <a id="main-choose-login" href="#">log in</a></div>-->
        </div>
      </div>
  <div class="pop_bot"></div>
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
<div class="pop_up" id="main-login-popup" style="display: none;">
  <div class="pop_mid">
  <div class="pop_top popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
  	  <div class="title"><strong>Logged in</strong></div>  			        
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
        <?php echo($sf_user -> getAttribute('user_username')); ?> - <a href="/account">My Account</a> &nbsp;&nbsp;&nbsp; <a class="link logout" href="/logout">Logout</a>
      </div>
    </div>
  <div class="pop_bot"></div>
  </div>    
</div>
<div id="auth" style="display:none;"></div>
<?php } ?>

<!-- TIMEZONE -->
<?php include_component('default', 
                        'Growler')?>
<!-- TIMEZONE -->	 		
