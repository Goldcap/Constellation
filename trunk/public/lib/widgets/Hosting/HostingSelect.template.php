<!--<div class="inner_container clearfix">
	<div class="clearfix">  
			<h4 class="no_show">Sorry, showtime hosting isn't live yet. Check back soon!</h4>
  </div>
</div>-->  

<?php 
//return;
$ttz = date_default_timezone_get();
$zones = zoneList(); 
?>


<script type="text/javascript">

	var fdata = <?php echo $json;?>;
	
</script>

<div class="inner_container clearfix host_step_one">
    
  <div class="host_show_header">
      <h3>Host a showtime:</span></h3>
      <ul class="host_show_steps">
          <li class="active">1. Select a Film</li>
          <li class="host_show_steps_sep">&raquo;</li>
          <li>2. Date &amp; Time</li>
          <li class="host_show_steps_sep">&raquo;</li>
          <li>3. Buy Ticket</li>
      </ul>
  </div>
  
  <div class="host_container">
      <div class="host_container_top"></div>
      <div class="host_container_center clearfix">
          
          <form id="host_form_zero" action="#" name="host_detail" method="POST" onSubmit="return false;">
          
          <div class="film_details">
              <img src="/uploads/screeningResources/" alt="" width="150" height="220"/>
          </div>
          
          <div class="host_form clearfix">
              <table>
        	    	<tr height="20">
        	    		<td valign="bottom">
        	    			<label>Film</label>
        	    		</td>
        	    	</tr>
        	    	<tr height="40">
        	    		<td valign="top">
        	    			<div class="selectOveride">
                      <div class="displayDiv">Select your film</div>
                      <select id="film_id" name="film_id" >
											  <?php foreach ($films as $film) {?>
													<option value="<?php echo $film["film_id"];?>" <?php if ((isset($fid)) && ($fid == $film["film_id"])) { echo 'selected="selected"'; } ?> title="<?php echo $film["film_allow_user_hosting"]?>">&nbsp;&nbsp;&nbsp;<?php echo $film['film_name'];?></option>
												<?php } ?>
											</select>
                  	</div>
										<!--<div id="hosting_aval">
											<?php echo !$film["film_allow_user_hosting"] ? 'Currently, this option is unavailable.': ''?>
										</div>-->
        	    		</td>
        	    	</tr>
        	    </table>
              <div class="form_row">
                  <button id="host_next_detail" type="submit" class="button button_orange uppercase" <?php echo !$film["film_allow_user_hosting"] ? 'disabled': ''?>>Next &raquo;</button>
              </div>
              <input type="hidden" name="type" class="type" value="select" />		
	            <input type="hidden" id="host_id" name="host_id" value="<?php echo $sf_user -> getAttribute("user_id");?>" />
	            <input type="hidden" id="session_id" name="session_id" value="<?php echo session_id();?>" />
              
          	</form>
          </div>
          
      </div>
      <div class="host_container_bottom"></div>
  </div>
</div>
<div id="domain" style="display:none"><?php echo sfConfig::get("app_domain");?></div>
<div id="current_date" style="display:none"></div>
<div id="thistime" style="display:none"><?php if (isset($thistime)) {echo $thistime;}?></div>
