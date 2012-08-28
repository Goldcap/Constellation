<div class="bo-panel">
	<h4>Secure Payment Information</h4>
	
	<div class="form-fieldset">
	<div class="form-row clearfix">
		<label for="credit_card_number ">Credit Card Number</label>
		<div class="input">
			<input class="text-input span4 post-data" id="credit_card_number" name="credit_card_number" value="<?php echo $post["credit_card_number"];?>"  /> <p class="sublabel">(No dashes or spaces)</p>
		</div>
	</div>
	<div class="form-row clearfix">
		<label for="expiration_date_month">Expiration Date</label>
		<div class="input">
      		<select class="select-month post-data" id="expiration_date_month" name="expiration_date_month">
            <?php for ($m=1;$m<13;$m++) {?>          
            	<option value="<?php echo ($m);?>" <?php if ($post["cc_exp_month"] == $m) {?>selected="selected"<? }?>><?php echo sprintf("%02d",$m);?></option>
            <?php } ?>			          
        	</select>
            <select id="expiration_date_year" name="expiration_date_year" class="select-year post-data">	
            <?php for ($i=year();$i<year()+10;$i++) {?>          
              <option value="<?php echo ($i);?>" <?php if ($post["cc_exp_year"] == $i) {?>selected="selected"<? }?>><?php echo sprintf("%02d",$i);?></option>
              <?php } ?>          
            </select>
		</div>
	</div>
	<div class="form-row clearfix">
		<label for="card_verification_number">Security Code</label>
		<div class="input">
			<input class="text-input span1 post-data" id="card_verification_number" name="card_verification_number" maxlength="4"  value="<?php echo $post["card_verification_number"];?>"/> <span class="cvn">
				<img src="/images/cc_code_icon.png" />
			</span>
			<p class="sublabel">(3 on back, Amex: 4 on front)</p>
		</div>
	</div>
	</div>


	<div class="form-row clearfix">
		<span class="link link-cancel uppercase left switch-panel" data-panel-index="2">&laquo; Back</span>
		<span class="button button-green uppercase right" id="submit-payment" > Buy Ticket &raquo;</span>
	</div>


</div>