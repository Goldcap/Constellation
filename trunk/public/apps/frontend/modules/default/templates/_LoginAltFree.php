<?php 
if (!isSet($isModal)){
  $isModal = true;
}

$defaultPanelSignup = true;
  if ($sf_user -> hasFlash('error-signup')){
  $defaultPanelSignup = false;
}

?>

<?php if ((! $sf_user -> isAuthenticated()) || ($sf_user -> getAttribute("temp"))) :?>
<div id="main-login-popup" <?php echo $isModal ? 'class="pops" style="display: none;"' : '' ?>>

<?php /* Login */ ?>
  <div id="log-in" <?php echo !$defaultPanelSignup ? 'style="display: none"':''?>>
    <h4>Log In</h4>
    <p class=" change-state">Don't have an account yet? <span id="main-choose-signup" class="link">Sign up &raquo;</span></p>


    <div class="center">
      <a id="login_fb_link" class="button button-facebook" href="/services/Facebook/login?dest=<?php echo urlencode('http://' . sfConfig::get("app_domain") . $_SERVER["REQUEST_URI"]);?>"><span class="icon-facebook"></span>Log In with Facebook</a>
    </div>
    <div class="or"><span class="uppercase">Or</span></div>
    <div class="center">
      <a id="login_twitter_link" class="button button-twitter" href="/services/Twitter/login?dest=<?php echo urlencode('http://' . sfConfig::get("app_domain") . $_SERVER["REQUEST_URI"]);?>"><span class="icon-twitter"></span>Log In with Twitter</a> 
    </div>
 <div class="or"><span class="uppercase">Or</span></div>

		<form id="login_form" name="login_form" action="/services/Login" method="POST" class="login_form">
      <div class="label">E-Mail</div>
      <input name="email" id="login_email" class="input login-element text-input" type="text" />
      <div class="clearfix">
        <div class="label left">Password</div>
        <span id="main-choose-password" class="right link">Forgot password?</span>
      </div>
      <input name="password" id="login_password" class="input login-element text-input" type="password" />
      <div class="clearfix">
        <span  id="login-button" class="button button-blue uppercase right">Log In</span>
      </div>
      <input type="hidden" value="<?php echo preg_replace("/\?err=([^&].+)/","",$_SERVER["REQUEST_URI"]);?>" name="source" />
      <input type="hidden" id="login_destination" value="<?php echo preg_replace("/\?err=([^&].+)/","",$_SERVER["REQUEST_URI"]);?>" name="destination" />
      <input type="hidden" value="true" name="indirect" />
      <input type="hidden" value="true" name="popup" />
      <input type="hidden" value="login" name="type" />
    </form>        
   

  </div>

<?php /* Forget Password */ ?>
  
  <div id="password-out" style="display:none">
    <h4>Forget Password</h4>
        <p id="return-login" class="change-state link"><span class="link">&laquo; Return to login</span></p>
    <form id="password_form" name="password_form" action="#" method="POST" class="password_form">
    	<div class="label">Your Email</div>
			<input name="email" id="password_email" class="input password-element text-input" type="text" />
      <div class="signup clearfix">
        <span  id="password-button" class="button button-blue uppercase right">Send Password</span>
      </div>
    </form>
  </div>

<?php /*Sign Up */ ?>

  <div id="sign-up" <?php echo $defaultPanelSignup ? 'style="display: none"':''?>>
    <h4>Sign Up</h4>
    <p id="have-account" class="change-state"> Already have an Account? <span id="main-choose-signup" class="link">Login &raquo;</span></p>

    <div class="center">
      <a id="login_fb_link" class="button button-facebook" href="/services/Facebook/login?dest=<?php echo urlencode('http://' . sfConfig::get("app_domain") . $_SERVER["REQUEST_URI"]);?>"><span class="icon-facebook"></span>Sign Up In with Facebook</a>
    </div>
    <div class="or"><span class="uppercase">Or</span></div>
    <div class="center">
      <a id="login_twitter_link" class="button button-twitter" href="/services/Twitter/login?dest=<?php echo urlencode('http://' . sfConfig::get("app_domain") . $_SERVER["REQUEST_URI"]);?>"><span class="icon-twitter"></span>Sign Up with Twitter</a> 
    </div>   

      <div class="or"><span class="uppercase">Or</span></div>

    <form id="sign-up_form" name="sign-up_form" action="/services/Join" method="POST" class="sign-up_form">
      <div class="label">Username</div>
			<input id="main-signup-username" value="" name="username" type="text" class="input signup-element text-input" />
			<div class="label">Email Address</div>
			<input id="main-signup-email" value="" name="email" type="text" class="input signup-element text-input" />
      <div class="label">Password</div>
			<input id="main-signup-password" name="password" type="password" class="input signup-element text-input" />
      <div class="label">Optin Email</div>
			<div class="optin">
        <span>
          <input id="main-signup-optin" name="optin" type="checkbox" class="" checked="checked" />I agree to receive Constellation's weekly newsletter.</span>
      </div>
              
      <div class="signup clearfix">
        <span  id="signup-button" class="button button-blue uppercase right">Sign Up</span>
      </div>          
                                                                               
      <input type="hidden" value="<?php echo preg_replace("/\?err=([^&].+)/","",$_SERVER["REQUEST_URI"]);?>" name="source" />
      <input type="hidden" id="signup_destination" value="<?php echo preg_replace("/\?err=([^&].+)/","",$_SERVER["REQUEST_URI"]);?>" name="destination" />
      <input type="hidden" value="true" name="indirect" />
      <input type="hidden" value="true" name="popup" />
      <input type="hidden" value="signup" name="type" />
    </form>
  </div>    
</div>

<script>
require(['CTV/View/Alert'], function(){
<?php if($sf_user -> hasFlash('error-signup')):?>
  Alert.open({type:'error', body: '<p>The e-mail you entered already exist in our system.<p>'})
<?php elseif($sf_user -> hasFlash('error-login')):?>
  Alert.open({type:'error', body: 'Oh no! The e-mail and password you entered did not match an active account.'})
<?php endif;?>
})

</script>
<?php endif;?>
