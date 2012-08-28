<form method="post" action="/services/Login">
<input type="hidden" value="<?php echo $source;?>" name="source">
<input type="hidden" value="<?php echo $destination;?>" name="destination">
<input type="hidden" value="true" name="indirect">

<fieldset style="display: block;" class="formlet" id="step-formlet-login">
  <div class="head">
    <?php if ((isset($error)) && ($error=='login')) {?>
    <span style="color: red">Login: Please check your information.</span>
    <?php }  else { ?>
    <span>Login</span>
    <?php  } ?>
    <p>or <a href="javascript:void(0);" rel="login-field" id="step-choose-signup">Sign-up</a></p>
  </div>
  <div id="step-login-holder">
    <div class="formfield first-formfield">
      <label>Email: </label>
      <input type="text" class="text" name="email" value="" id="step-login-email">
    </div>
    <div class="formfield">
      <label>Password: </label>
      <input type="password" class="text" name="password" id="step-login-password">
    </div>
    <fieldset class="buttons">
      <input type="image" alt="login" src="/images/btn-text[login][2].png" class="btn-submit btn-login-signup" id="step-btn-login" name="login_action" />
    </fieldset>
  </div>
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
