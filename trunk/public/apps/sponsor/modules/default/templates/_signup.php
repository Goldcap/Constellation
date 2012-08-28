<form id="main-login-form" action="/services/Join" method="post">    
<input type="hidden" value="<?php echo $source;?>" name="source">   		  	
<input type="hidden" value="<?php echo $destination;?>" name="destination">
<input type="hidden" value="true" name="indirect">
<fieldset class="formlet" id="step-formlet-signup">
  <div class="head">
    <?php if ((isset($error)) && ($error=='signup')) {?>
    <span style="color: red">Sign-up: Please check your information.</span>
    <?php }  else { ?>
    <span>Sign-up</span>
    <?php  } ?>
    <p>or <a href="javascript:;" rel="login-field" id="step-choose-login">Login</a></p>
  </div>
  <div class="formfield">
    <label>Your Name: </label>
    <input type="text" class="text" name="name" value="" id="step-signup-name">
  </div>
  <div class="formfield">
    <label>Username: </label>
    <input type="text" class="text" name="username" value="" id="step-signup-username">
  </div>
  <div class="formfield">
    <label>Email: </label>
    <input type="text" class="text" name="email" value="" id="step-signup-email">
  </div>
  <div class="formfield">
    <fieldset class="birthday">
      <label> Birthdate: </label>
      <div class="formfield-birthday">
        <select name="month" id="step-fld_month">
          <option value="01">Jan</option>
          <option value="02">Feb</option>
          <option value="03">Mar</option>
          <option value="04">Apr</option>
          <option value="05">May</option>
          <option value="06">Jun</option>
          <option value="07">Jul</option>
          <option value="08">Aug</option>
          <option value="09">Sep</option>
          <option value="10">Oct</option>
          <option value="11">Nov</option>
          <option value="12">Dec</option>
        </select>
      </div>
      <div class="formfield-birthday">
        <select name="day" id="step-fld_day">
        <?php for ($i=1;$i<=31;$i++) {?>
        <option value="<?php echo $i;?>"><?php echo $i;?></option>
        <?php } ?>
        </select>
      </div>
      <div class="formfield-birthday">
        <select name="year" id="step-fld_year">
        <?php for ($i=1922;$i<=year();$i++) {?>
        <option value="<?php echo $i;?>"><?php echo $i;?></option>
        <?php } ?>
        </select>
      </div>
    </fieldset>
  </div>
  
  <div class="formfield">
    <label>Password: </label>
    <input type="password" class="text" name="password" id="step-signup-password">
  </div>
  <div class="formfield">
    <label>Confirm Password: </label>
    <input type="password" class="text" name="password2" id="step-signup-password2">
  </div>
  
  <fieldset class="buttons">
    <input type="image" alt="signup" src="/images/btn-text[sign-up][2].png" class="btn-submit btn-login-signup" id="step-btn-signup" name="signup_action" />
  </fieldset>
  <?php if ((isset($_GET["err"])) && ($_GET["err"] == 'signup')) {?>
  <fieldset class="formfield">
    <span style="color:red">
    <?php if ($_GET["errs"] == 'pass') {?>
    Your password is incorrect, please try again...
    <?} elseif ($_GET["errs"] == 'username') {?>
    That username is already in use...
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
