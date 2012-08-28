<div class="logo_alt"><a title="Return to the home page" href="/">your global movie theater</a></div>

<span class="acc_timezone">
  <span class="acc_timezone_link">
  MY TIMEZONE: <?php echo str_replace("_"," ",date_default_timezone_get());?> (<?php echo date("T");?>)
  </span>
  <span class="acc_timezone_link">
    <a id="timezone-select" href="#">Change</a>
  </span>
</span>

<ul class="logo_alt_set">
  <!--<li class="main-showtimes"><a href="javascript: void(0);">All Films</a></li>
  <li class="main_works"><a href="javascript: void(0);">How It Works</a></li>-->
  <li>
      <?php if (($sf_user -> isAuthenticated()) && ((is_null($sf_user -> getAttribute("temp"))) || (! $sf_user -> getAttribute("temp")))) {?>
      <span class="acc_image"><img id="photo-url" src="/images/icon-custom.png" class="main-login" height="45" /></span>
      <span class="acc_name">
        <?php echo $sf_user -> getAttribute('user_username');?><br />
        <span class="acc_link"><a href="#" id="your-screenings" class="main-showtimes">My Screenings</a> <a href="#">|</a> <a href="/logout?dest=<?php echo $LOGOUT_URL;?>">Sign Out</a></span>
      </span>
      <?php } else { ?>
      <a href="#" class="main-login">Log In</a>
      <?php } ?>
  </li>
</ul>

<?php if ((! $sf_user -> isAuthenticated()) || ($sf_user -> getAttribute("temp"))) {?>
<div class="popout_up" id="main-login-popup" style="display: none;">
  <div class="popout_mid">
  <div class="popout_top popup-close"><a href="#">Close Window</a></div>
  	<div class="layout-1 clearfix">
  	  <div id="log-in">
        <div class="title"><strong>Login</strong></div><br />
        <form id="login_form" name="login_form" action="/services/Login" method="POST" class="login_form">
        	<div>
        	  <p>E-Mail</p>
            <input name="email" id="login_email" class="input login-element" type="text" />
            
        	  <p>Password</p>
            <input name="password" id="login_password" class="input login-element" type="password" />
            
            <div class="signup">
              <span>
                <a href="#" id="main-choose-password">Forgot password?</a>
                <br />
                <a id="main-choose-signup" href="#">Sign Up</a>
                <a id="main-choose-login" href="#" style="display:none">Log In</a>
              </span>
              <span style="text-align: right;">
                <input type="image" src="/images/alt/login.png" name="login" id="login-button" value="log in" />
              </span>
            </div>
            
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
        
        <div class="or_login_alt">
          <span>Or, login with<br />a social network.</span>
          <span>
            <a style="overflow: hidden;" href="/services/Facebook/login?dest=http://<?php echo $_SERVER["SERVER_NAME"];?><?php echo $_SERVER["REQUEST_URI"];?>"><img src="/images/alt/signin_fb.png" alt="" /></a> 
            <a style="overflow: hidden;" href="/services/Twitter/login?dest=http://<?php echo $_SERVER["SERVER_NAME"];?><?php echo $_SERVER["REQUEST_URI"];?>"> <img src="/images/alt/signin_tw.png" alt="" /></a>
          </span>
        </div>
      </div>
      
      <div id="password-out" style="display: none">
        <!--<div class="title"><strong>Login</strong></div><br />-->
        <form id="password_form" name="password_form" action="#" method="POST" class="password_form">
        	<div class="clearfix"><label>Your Email</label> <input name="email" id="password_email" class="input password-element" type="text" /></div>
          <div class="signup" style="width: 100%; text-align: right;">
              <input type="image" src="/images/alt/send.png" name="login" id="password-button" value="log in" />
            </div>
        </form>
      </div>
      
      <div id="sign-up" style="display: none">
        <!--<div class="title"><strong>Sign-up</strong></div><br />-->
        <form id="sign-up_form" name="sign-up_form" action="/services/Join" method="POST" class="sign-up_form">
          	<fieldset>
              	<div class="clearfix"><label>Your Name</label> <input id="main-signup-name" value="" name="name" type="text" class="input signup-element" /></div>
                  <div class="clearfix"><label>Username</label> <input id="main-signup-username" value="" name="username" type="text" class="input signup-element" /></div>
                  <div class="clearfix birthday"><label>Birthday</label> 
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
              </fieldset>
              <div class="signup" style="width: 100%; text-align: right;">
                <input type="image" src="/images/alt/signup.png" name="login" id="login-button" value="log in" />
              </div>
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
  <div class="popout_bot"></div>
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
<div class="popout_up" id="main-login-popup" style="display: none;">
  <div class="popout_mid">
  <div class="popout_top popup-close"><a href="#">Close Window</a></div>
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
        <?php echo($sf_user -> getAttribute('user_username')); ?> - <a href="/account">My Account</a> &nbsp;&nbsp;&nbsp; <a class="link logout" href="/logout?dest=<?php echo $LOGOUT_URL;?>">Logout</a>
      </div>
    </div>
  <div class="popout_bot"></div>
  </div>    
</div>
<div id="auth" style="display:none;"></div>
<?php } ?>

<div id="main-how-popup" style="display: none;">
<div class="poplg_up">
  <div class="poplg_mid">
  <div class="poplg_top popuplg-close"><a href="#">Close Window</a></div>
  	<div class="layout-1 clearfix">
  	<h3>How It Works</h3>
  	<p>Constellation is the first global movie theater.</p>
  	<p>We show movies at set times for live audiences. Anyone can buy a ticket and invite their friends (via email or sharing through a social network). You can chat with others in the theater, and many film showings feature live Q+A's where you can ask questions directly of VIP hosts.</p>
  	<p>Filmmakers, stars, and other VIP guests regularly host screenings on Constellation, so be sure to check the calendar for each movie for special screenings. </p>
  	<p><strong style="color:black">To buy a ticket to any screening, simply follow these steps: </strong></p>
  	<ol class="how-it-works-ol">
      <li>
      LOGIN to Constellation. <span>You can create a new profile, or you can login via your Facebook or Twitter profile in just a couple clicks! </span>
      </li>
      <li>
      INVITE FRIENDS AND GET A DISCOUNT. <span>It's easy &mdash; just enter their e-mail addresses, or login to your Gmail, Yahoo, or Hotmail account and simply click on any of your existing contacts. Your friends will receive an invite in their email from you. You can also post messages via Facebook and Twitter. Each invitation you send will earn you $.10 off your ticket price, up to 50% off. </span>
      </li>
      <li>
      BUY YOUR TICKET. <span>You can easily pay with PayPal or any major credit card. If you have already purchased a ticket to a screening that you did not attend, just enter your exchange code to redeem a ticket for the new showtime. </span>
      </li>
      <li>
      Click ENTER THE THEATER. <span>Once you're in the theater, you'll see a countdown until the movie starts. You can post messages to chat with other attendees, or you can invite more friends to the screening. You can access the menu with the chat and movie-info features at anytime before the screening. . If you experience technical issues, try refreshing your browser. If you still have problems, please email support@constellation.tv. </span>
      </li>
    </ol>
    
    <p>Once you've bought a ticket, you'll promptly receive an e-mail from us containing your ticket and receipt. If you miss your showtime or want to exchange your ticket for another show, simply log-in to your Constellation.tv account, and click on the "My Account" link on the top right of the page. You will see the screenings for which you have purchased tickets. You can click directly on these links to enter the theater.</p>

    <p>We'll also send you an e-mail reminder for your showtime one-hour before the show begins. </p>

    </div>
  <div class="poplg_bot"></div>
  </div>    
</div>
</div>

<!-- GROWLER -->
<?php include_component('default', 
                        'Growler')?>
<!-- GROWLER -->


<!-- FEEDBACK -->
<?php include_component('default', 
                        'Feedback')?>
<!-- GROWLER -->	 		


<!-- FEEDBACK -->
<?php include_component('default', 
                        'TimezoneAlt')?>
<!-- GROWLER -->	 		

