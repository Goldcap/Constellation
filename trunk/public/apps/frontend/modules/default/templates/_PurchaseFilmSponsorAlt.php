<!-- PURCHASE POPUP -->
<div class="pops pops_wide" id="screening_purchase" style="display: none">
  	<h4>Get Ticket</h4>
    <div class="film_alt_info">
      <span class="film_alt_name">
        <?php echo $film["film_name"];?><br />
        <!--<span class="current_screening_time">Now</span><br />
        <span class="current_screening_hb">Hosted By <span class="current_screening_host">You</span></span>-->
			</span>
      <span class="film_alt_text">
				The film will start a few seconds after you enter the theater. Enjoy!
      </span>
    </div>
    <span class="bandwidth_warning" style="display: none">Warning: We've detected that you have low bandwidth at your current location. While you can purchase tickets for this or any other screening, you may experience issues with image quality and continuity due to your connection. (<span class="current_bandwidth"></span>k/sec)<br /></span>
    <form id="purchase_form" name="purchase_form" action="/screening/<?php echo $film["film_id"];?>/purchase" method="POST" class="buy-ticket_form" onSubmit="return false">
  	<br />
      <fieldset>
          
					<div class="clearfix"><label>ENTER CODE </label> <input id="fld-code" name="ticket_code" class="input" type="text" />	</div>
          
          <div class="cc-submit">
            <input id="enter-code" type="image" src="/images/alt/enter_theater.png" value="get ticket" class="" name="" /> 
          </div>
          
      </fieldset>
	
    </form>
    <div id="purchase_errors" style="color: red"></div>
</div>
<!-- END PURCHASE POPUP -->

<!-- PURCHASE PROCESS POPUP -->
<div class="pops" id="process" style="display: none">
	<h4>Your code is being processed.</h4>
  <br />
  <div style="margin-left: 120px;"><img src="/images/ajax-loader.gif" /></div>
</div>
<!-- END PURCHASE PROCESS POPUP -->

<!-- PURCHASE CONFIRM POPUP -->
<div class="pops" id="confirm" style="display: none">
 	<h4><?php echo $film["film_name"];?></h4>
	<h4><span class="current_screening_time"></span></h4>
  <span class="current_screening_hb">Hosted By <span class="current_screening_host"></span></span>
  
	<br />
  <div class="title"><strong><span id="purchase_result">Your code was confirmed</span></strong></div>
	<!--Your screening link is:<br />
  <span id="screening_full_link"></span><br />-->
  <br />
  <a id="screening_click_link" href="#"><img src="/images/alt/enter_theater.png" border="0" /></a> 
</div>
<!-- END PURCHASE CONFORM POPUP -->
