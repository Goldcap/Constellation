<?php 
//OK, we have to remove the "screening_" part of the keys
//Sorry, hack
foreach($film as $aspect => $value) {
	$newaspect = str_replace("screening_","",$aspect);
	$film[$newaspect] = $value;
}
?>

<span class="bandwidth_warning" style="display: none; font-size: 11px;">Warning: You have low bandwidth at this location. You may experience issues with image quality and continuity due to your connection. (<span class="current_bandwidth"></span>k/sec)</span>

<!-- PURCHASE POPUP -->
<div id="screening_purchase" style="display: none">
	<form id="purchase_form" name="purchase_form" action="/screening/<?php echo $film["film_id"];?>/purchase" method="POST" class="buy-ticket_form" onSubmit="return false">
	<table class="step_one" cellpadding="0" cellspacing="0" border="0">
  	<tr>
  		<td colspan="2">
  			<h5>
  				<?php echo $film["screening_film_name"];?>
  			</h5>
  		</td>
  	</tr>
  	<?php if ($film["screening_user_full_name"] != '') {?>
  	<tr height="20">
  		<td colspan="2" valign="top">
  			<span class="pre_by">
  			Hosted By
  			</span>
  		</td>
  	</tr>
  	<tr>
  		<td height="40" colspan="2" valign="top">
  			<span class="pre_host">
  				<span class="current_screening_host"><?php echo $film["screening_user_full_name"];?></span>
  			</span>
  		</td>
  	</tr>
  	<?php }?>
  	<tr>
  		<td height="70" valign="middle">
  			<span class="pre_enter">
	  			<label>Enter Code</label>
				</span>
			</td>
  		<td height="70" valign="middle">
  			<span class="pre_pfl">
					<input id="fld-code" name="ticket_code" class="input" type="text" value="" />
				</span>
			</td>
  	</tr>
		<tr>
  		<td colspan="2" valign="top">
  			<span class="pre_pfl">
  				<table>
  					<tr>
  						<td colspan="2">
								<input id="enter-code" type="image" src="/images/alt1/purchase_code.png" value="get ticket" /> 
  						</td>
		      	</tr>
		      </table>
				</span>
			</td>
  	</tr>
  </table>
  
  <input type="hidden" name="ticket_code" id="ticket-code" value="false" />	
  <input type="hidden" name="invite_count" id="invite_count" value="0" />	
  <?php if ($film["film_ticket_price"] == 0) {?>
    <input type="hidden" id="ticket_price" name="ticket_price" value="0" />
  <?php } else {?>
    <input type="hidden" id="ticket_price" name="ticket_price" value="<?php echo $film["film_ticket_price"];?>" />
  <?php } ?>
  <input type="hidden" name="promo_code" id="promo_code" value="0" />	
  <?php if ($film["film_allow_hostbyrequest"] == 1) {?>
    <input type="hidden" id="dohbr" name="dohbr" value="false" />
  <?php } ?>
  
  </form>
	
  <div id="purchase_errors" style="color: red"></div>  
	 
</div>
<!-- END PURCHASE POPUP -->

<!-- PURCHASE PROCESS POPUP -->
<div id="process" style="display: none">
	<table class="step_one" cellpadding="0" cellspacing="0" border="0">
  	<tr>
  		<td colspan="2">
  			<h5>
  				<?php echo $film["film_name"];?>
  			</h5>
  		</td>
  	</tr>
  	<?php if ($film["screening_user_full_name"] != '') {?>
  	<tr height="20">
  		<td colspan="2" valign="top">
  			<span class="pre_by">
  			Hosted By
  			</span>
  		</td>
  	</tr>
  	<tr>
  		<td height="40" colspan="2" valign="top">
  			<span class="pre_host">
  				<span class="current_screening_host"><?php echo $film["screening_user_full_name"];?></span>
  			</span>
  		</td>
  	</tr>
  	<?php }?>
  	<tr>
  		<td height="30" colspan="2" valign="top">
  			<span class="pre_host">
					Your code is being processed.
				</span>
			</td>
		</tr>
  	<tr>
  		<td height="30" colspan="2" valign="top">
  			<img src="/images/ajax-loader.gif" />
			</td>
		</tr>
	</table>
</div>
<!-- END PURCHASE PROCESS POPUP -->

<!-- PURCHASE CONFIRM POPUP -->
<div id="confirm" style="display: none">
	<table class="step_one" cellpadding="0" cellspacing="0" border="0">
  	<tr>
  		<td colspan="2">
  			<h5>
  				<?php echo $film["film_name"];?>
  			</h5>
  		</td>
  	</tr>
  	<tr height="20">
  		<td colspan="2" valign="bottom">
  			<span class="pre_by">
  			Hosted By
  			</span>
  		</td>
  	</tr>
  	<tr>
  		<td height="40" colspan="2" valign="top">
  			<span class="pre_host">
  				<span class="current_screening_host"></span>
  			</span>
  		</td>
  	</tr>
  	<tr>
  		<td height="30" colspan="2" valign="top">
  			<span class="pre_ticket">
	  		Ticket price: <span class="pre_price">FREE</span>
				</span>
			</td>
  	</tr>
  	<tr>
  		<td height="30" colspan="2" valign="top">
  			<span class="pre_host">
					Your code was confirmed
				</span>
			</td>
		</tr>
  	<tr>
  		<td height="50" colspan="2" valign="bottom">
  			<a id="screening_click_link" href="#"><button class="btn_small">Watch the Film</button></a>
			</td>
		</tr>
	</table>
</div>
<!-- END PURCHASE CONFORM POPUP -->
