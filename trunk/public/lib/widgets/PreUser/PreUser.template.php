<style>
/*** splash page ***/
#first-splash {
	background: url(/dev/images/bkg%5bsplash%5d%5b1%5d.jpg) left top no-repeat;
	width: 1020px;
	height: 664px;
	margin: 0 auto;
	position: relative;
}
#first-splash #enter { 
	background: url(/dev/images/btn%5benter-2%5d.png) left top no-repeat;
	width: 135px;
	height: 33px;
	display: block;
	position: absolute;
	bottom: 157px;
	right: 285px;
}
#festivus {
  position: relative;
  top: 400px;
  margin-left: -120px;
}
#main-login-popup {
  margin: 0px auto;
  top: 300px;
  left: 260px;
}
</style>

<div class="pop_up" id="main-login-popup" style="display: none;">
  <div class="pop_mid">
  <div class="pop_top popup-close"></div>
  	<div class="layout-1 clearfix">
  	  <div id="log-in">
        <div class="title"><strong>If you've previously requested access, and have received a code, enter it here.</strong></div><br />
        <form id="login_form" name="login_form" action="/services/PreUser/signin" method="POST" class="login_form">
        	<div>
            <input name="email" id="login_email" class="input login-element" type="text" />
            <input name="password" id="login_password" class="input login-element" type="password" />
            <input name="login" id="login-button" class="btn_small" value="log in" type="submit" />
          </div>
          <?php if ((isset($_GET["err"])) && ($_GET["err"] == 'login')) {?>
          <script type="text/javascript">
            $(document).ready(function() {
            <?php if ($_GET["errs"] == 'pass') {?>
            error.showError('error','Your code is incorrect, please try again.');
            <?php } elseif ($_GET["errs"] == 'email') {?>
            error.showError('error','Your email wasn\'t found, please try again.');
            <?php } else{ ?>
            error.showError('error','There was an error, please try again.');
            <?php }?>
            });
          </script>
          <?php }?>
            
        </form>
        
        <div class="forgot_sign-up"><a id="main-choose-signup" href="#">click here to request access</a><a id="main-choose-login" href="#" style="display:none">enter code</a></div>
      </div>
      
      <div id="sign-up" style="display: none">
        <!--<div class="title"><strong>Sign-up</strong></div><br />-->
        <form id="sign-up_form" name="sign-up_form" action="/services/PreUser/join" method="POST" class="sign-up_form">
          	<fieldset>
              	
                  <div class="clearfix"><label>Email Address</label> <input id="main-signup-email" value="" name="email" type="text" class="input signup-element" /></div>
                  <div class="button"><input name="signup" id="signup-button" class="btn_small" value="sign up" type="submit" /></div>
              </fieldset>
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
	preuser.showsignup();
  <?php } else {?>
  preuser.showpopup();
  <?php } ?>
});
</script>
<?php }?>

<!-- GROWLER -->
<?php include_component('default', 
                        'Growler')?>
<!-- GROWLER -->	 		

<div id="first-splash"> 
    
    <span class="emailus">Interested? Email us at <a href="mailto:contact@constellation.tv">contact@constellation.tv</a>.</span><br />
		<!--<span class="jiglink"><a style="color:#FFCCFF" href="/jig">Looking for JIG?</a></span>-->
		
    <!--<a id="enter" href="home.html"></a>--> 
    <!--<a id="festivus" href="http://festival.constellation.tv"><img src="/images/festival/Festival_Banner.png" /></a>-->
    
    <?php include_component('default', 
                        "mailChimp")?>
                        
</div> 
