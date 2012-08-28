<!-- WATCH BY REQUEST POPUP -->
<div class="pop_up" id="watch_by_request" style="display: none">
  <div class="pop_mid">
  <div class="pop_top popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
      	<p><strong>
        <?php echo $film["film_name"];?> <span class="current_screening_time"></span><br />
        <!--HOSTED BY <span class="current_screening_host"></span>-->
        </strong></p>
        <br />
        <div class="code_break">
        	<!--<p>If you have an unused ticket, you can use it to view this screening. Enter the ticket code here, and click "Submit Code".</p>-->
          <form id="step-watch-form" class="buy-ticket_form" action="#">	  
            <fieldset class="data">		      
              <div class="clearfix">
                <label>ENTER CODE: </label>
              	<input id="watch-fld-code" name="ticket_code" class="text" type="text" />		        
              </div>
            </fieldset>		
          </form>
        </div>
        <br />
        <a id="watch-enter-code" class="link" href="javascript:void(0);"><button class="btn_small">Submit Code</button></a>
      </div>
  <div class="pop_bot"></div>
  </div>    
</div>
<!-- END WATCH BY REQUEST POPUP -->

<!-- WATCH BY REQUEST CONFIRM POPUP -->
<div class="pop_up" id="watch_by_request_confirm" style="display: none">
  <div class="pop_mid">
  <div class="pop_top popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
      	<p><strong>
        <?php echo $film["film_name"];?> <span class="current_screening_time"></span><br />
        <!--HOSTED BY <span class="current_screening_host"></span>-->
        </strong></p>
        <br />
        <div class="title"><strong><span id="purchase_result_wbr">Your screening was confirmed</span></strong></div>
      	<!--Your screening link is:<br />
        <span id="wbr_screening_full_link"></span><br />-->
        <br />
        <a id="wbr_screening_click_link" href="#"><button class="btn_small">Enter Theater</button></a>
      </div>
  <div class="pop_bot"></div>
  </div>    
</div>
<!-- END WATCH BY REQUEST CONFORM POPUP -->
