<div class="bo-panel bo-panel-cc">
	<h4>Billing Information</h4>
	
	<div class="form-row form-row-card-type">
		<span class="input-radio">
		<input type="radio" name="payment-type" checked="checked"  value="cc" /><img src="/images/cc_icons.png">
		</span>
		<span class="input-radio">
		<input type="radio" name="payment-type" value="paypal" /><img src="/images/paypal_icon.png"> 
		</span>
	</div>
	<input type="hidden" name="username" id="username" class="post-data" value="<?php echo $post["user_username"];?>" />	

<div class="bo-subpanel-cc">
	<div class="form-fieldset">
	<div class="form-row clearfix">
		<label for="">First Name</label>
		<div class="input">
			<input class="text-input span4 post-data" id="first_name" name="first_name" value="<?php echo $post["first_name"];?>" />
		</div>
	</div>
	<div class="form-row clearfix">
		<label for="last_name">Last Name</label>
		<div class="input">
			<input class="text-input span4 post-data" id="last_name" name="last_name" value="<?php echo $post["last_name"];?>"/>
		</div>
	</div>
<?php if ($film['screening_film_free_screening'] != '2'):?>	
	<div class="form-row clearfix">
		<label for="email">E-Mail Address</label>
		<div class="input">
			<input class="text-input span4 post-data" id="email" name="email" value="<?php echo $post["email"];?>"/>
		</div>
	</div>
<?php endif;?>	
	<div class="form-row clearfix">
		<label for="confirm_email">Confirm E-Mail</label>
		<div class="input">
			<input class="text-input span4 post-data" id="confirm_email" name="confirm_email" value="<?php echo $post["email_confirm"];?>" />
		</div>
	</div>
	
	<div class="form-row clearfix"></div>
	<div class="form-row clearfix">
		<label for="b_address1">Address</label>
		<div class="input">
			<input class="text-input span4 post-data" id="b_address1" name="b_address1" value="<?php echo $post["b_address1"];?>" />
		</div>
	</div>
	<div class="form-row clearfix">
		<label for="b_address2"></label>
		<div class="input">
			<input class="text-input span4 post-data" id="b_address2" name="b_address2" value="<?php echo $post["b_address2"];?>"/>
		</div>
	</div>
	<div class="form-row clearfix">
		<label for="b_city">City</label>
		<div class="input">
			<input class="text-input span4 post-data" id="b_city" name="b_city" value="<?php echo $post["b_city"];?>" />
		</div>
	</div>
	<div class="form-row clearfix">
		<label for="b_state">State</label>
		<div class="input">
      		<select class="span4 post-data" id="b_state" name="b_state">				
		    <?php foreach ($states as $state) :?>
	            <option value="<?php echo $state -> getAttribute("abbr");?>" <?php if($post["b_state"] == $state -> getAttribute("abbr")) { echo "selected=\"selected\""; }?>><?php echo ucwords(strtolower($state -> getAttribute("name")));?></option>
	        <?php endforeach; ?> 
        	</select>
		</div>
	</div>
	<div class="form-row clearfix">
		<label for="b_zipcode post-data">Zip Code</label>
		<div class="input">
			<input class="text-input span2 post-data" id="b_zipcode" name="b_zipcode" value="<?php echo $post["b_zipcode"];?>" />
		</div>
	</div>
	<div class="form-row clearfix">
		<label for="b_country">Country</label>
		<div class="input">
      		<select class="span4 post-data" id="b_country" name="b_country">				          
              <?php foreach ($countries as $country) {?>
                <option value="<?php echo $country -> getAttribute("abbr");?>" <?php if($post["b_country"] == $country -> getAttribute("abbr")) { echo "selected=\"selected\""; }?>><?php echo ucwords(strtolower($country -> getAttribute("name")));?></option>
              <?php } ?> 
            </select>
	    </div>
	</div>
	</div>


	<div class="form-row clearfix">
		<span class="link link-cancel uppercase left switch-panel" data-panel-index="0">&laquo; Back</span>
		<span class="button button-green uppercase right" id="submit-billing"> Next &raquo;</span>
	</div>
</div>
<div class="bo-subpanel-paypal">
	<div class="form-row clearfix">
		<span class="link link-cancel uppercase left switch-panel"  data-panel-index="0">&laquo; Back</span>
		<span class="button button-green uppercase right" id="submit-paypal"> Proceed to Paypal &raquo;</span>
	</div>
</div>

</div>
