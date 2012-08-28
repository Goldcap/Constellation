<div class="featured_screens">
  <h2><?php echo $title;?></h2>
  
  <?php if (count($screenings) > 0) {?>
  <?php foreach ($screenings as $screen) {
	$time = strtotime(now()) - strtotime($screen["screening_date"]);
	$hours = floor($time / 3600);
	$time = $time % 3600;
	$minutes = floor($time/ 60);
	$time = $time % 60;
	$seconds = floor($time);
	$remaining_time = sprintf("%02d",$hours) . ":" . sprintf("%02d",$minutes) . ":" . sprintf("%02d",$seconds);
	?>
  <div class="featured_item">
    <div class="featured_content">
      <span class="featured_still">
        <?php if ($screen["screening_still_image"] != '') {?>
          <img class="user_host_<?php echo $screen["screening_unique_key"];?>" src="/uploads/screeningResources/<?php echo $screen["screening_film_id"];?>/screenings/screening_large_<?php echo $screen["screening_still_image"];?>" alt="" width="126" />
        <?php } else {?>
          <img class="user_host_<?php echo $screen["screening_unique_key"];?>" src="/images/alt/featured_still.jpg" />
        <?php } ?>
        <?php if ($screen["screening_user_full_name"] != '') {?>
        <div class="tooltip tooltip_large" style="display:none"><?php echo $screen["screening_description"];?></div>
        <script type="text/javascript">
        $(document).ready(function() {
          $(".user_host_<?php echo $screen["screening_unique_key"];?>" ).tooltip({ effect: 'slide', relative: true, position: 'center right' });
        });          
        </script>
        <?php } ?>
      </span>
      <span class="featured_title">
      	<table width="745" border="0" cellpadding="0" cellspacing="0">
      		<tr height="1">
      			<td width="500" height="1"><img src="/images/spacer.gif" height="0" width="0" /></td><td width="170" height="1"><img src="/images/spacer.gif" height="0" width="0" /></td><td width="150" height="1"><img src="/images/spacer.gif" height="0" width="0" /></td>
      		</tr>
					<tr>
      			<td>
      				<?php if ($screen["screening_name"] != '') { ?>
      				<h5><?php echo $screen["screening_name"];?></h5>
      				<?php } else {?>
        			<h5><?php if ($screen["screening_user_full_name"] != '') { ?><a href="/film/<?php echo $screen["screening_film_id"];?>/detail/<?php echo $screen["screening_unique_key"];?>"><?php echo $screen["screening_user_full_name"];?>'s Screening</a><?php } ?></h5>
      				<?php } ?>
						</td>
      			<td colspan="2" align="right">
      				<div style="float: right; text-align: right;">
      				<?php if ((strtotime($screen["screening_date"])) < (strtotime( now()))) {
								echo '<span id="time_remaining_'.$screen["screening_unique_key"].'" class="time_remaining">'.date("Y|m|d|G|i|s",strtotime($screen["screening_date"])).'</span>';
							?>
      					<a class="screening_link" href="/film/<?php echo $screen["screening_film_id"];?>/detail/<?php echo $screen["screening_unique_key"];?>" title="<?php echo $screen["screening_unique_key"];?>"><img src="/images/alt1/in_progress.png" border="0" /></a>
			        <?php } else {?>
			        	<a href="/film/<?php echo $screen["screening_film_id"];?>/detail/<?php echo $screen["screening_unique_key"];?>">
								<?php if (formatDate($screen["screening_date"],"TSFloor") == formatDate(now(),"TSFloor")) {?>
			        	<p class="featured_time">TODAY&nbsp;|&nbsp;</p>
			        	<?php } ?>
        				<p class="featured_time"><?php echo date("g:iA T",strtotime($screen["screening_date"]));?>&nbsp;|&nbsp;<?php echo date("m/d/y",strtotime($screen["screening_date"]));?></p>
        				</a>
        			<?php } ?>
        			</div>
						</td>
        	</tr>
        	<tr>
        		<td colspan="3">
        			<script type="text/javascript">
				      $(document).ready(function() {
				        countdown_alt.init('<?php echo $screen["screening_unique_key"];?>_<?php echo $pred;?>','<?php echo date("Y|m|d|H|i|s",strtotime($screen["screening_date"]));?>');
				      });
				      </script>
        		 	<?php if ((strtotime($screen["screening_date"])) >= (strtotime( now()))) {?>
			        <div class="countdown_start">STARTS IN</div> <span class="timekeeper" id="timekeeper_<?php echo $screen["screening_unique_key"];?>_<?php echo $pred;?>">2HRS 15MIN 37S</span>
        			<?php } ?>
						</td>
        	</tr>
        	<tr>
        		<td colspan="2">
        			<script type="text/javascript">
			        $(document).ready(function() {
			          $.ajax({url:'/services/Screenings/users?screening=<?php echo $screen["screening_id"];?>', 
			                  type: "GET", 
			                  cache: false, 
			                  dataType: "json", 
			                  success: function(response) {
			                    if ((response != null) && (response.users != undefined)) {
			                    for (var i = 0; i < response.users.length; i++) {
			                      $(".user_images_<?php echo $screen["screening_unique_key"];?>").append('<img class="user_image_<?php echo $screen["screening_unique_key"];?>" src="'+response.users[i].image+'" alt="'+response.users[i].username+'" width="50" /><div class="tooltip" style="display:none">'+response.users[i].username+'</div>');
			                    }
			                    $(".user_image_<?php echo $screen["screening_unique_key"];?>" ).tooltip({ effect: 'slide', relative: true, position: 'center right' });
			                    }
			                  }, error: function(response) {
			                      console.log("ERROR:", response);
			                  }
			          });
			        });
			        </script>
        			<div class="user_images">
								<span class="user_list user_images_<?php echo $screen["screening_unique_key"];?>"></span>
								<span class="user_count user_count_<?php echo $screen["screening_unique_key"];?>"><?php echo $screen["screening_audience_count"];?> attending</span>
							</div>
						</td>
        		<td>
        			<?php if ($screen["screening_audience_count"] >= $screen["screening_film_total_seats"]) {?>
			          <a href="/theater/<?php echo $screen["screening_unique_key"];?>" title="<?php echo $screen["screening_unique_key"];?>"><img src="/images/alt/sold_out.png" border="0" /></a>
			        <?php } else {?>
			          <a href="/theater/<?php echo $screen["screening_unique_key"];?>" title="<?php echo $screen["screening_unique_key"];?>"><img src="/images/alt1/attend.png" /></a>
			        <?php } ?>
						</td>
        	</tr>
        </table>
      </span>
    </div>
    <div id="time_<?php echo $screen["screening_unique_key"];?>" style="display:none"><?php echo formatDate($screen["screening_date"],"DTZ");?></div>
    <div id="cost_<?php echo $screen["screening_unique_key"];?>" style="display:none"><?php echo $screen["screening_film_ticket_price"];?></div>
    <div id="host_<?php echo $screen["screening_unique_key"];?>" style="display:none"><?php echo $screen["screening_user_full_name"];?></div>
  </div>
  <?php 
	if (isset($single)) {
		break;
	}}} else { ?>
  <div class="featured_item">
    <div class="featured_content">
      <span class="featured_still">
        <img src="/images/alt1/screening_no_image.png" />
      </span>
      <span class="featured_title">
      	<table width="745" border="0" cellpadding="0" cellspacing="0">
      		<tr height="1">
      			<td width="525" height="1"><img src="/images/spacer.gif" height="0" width="0" /></td><td width="70" height="1"><img src="/images/spacer.gif" height="0" width="0" /></td><td width="150" height="1"><img src="/images/spacer.gif" height="0" width="0" /></td>
      		</tr>
					<tr>
      			<td>
        			<h5>No Screenings Available At This Time</h5>
      			</td>
      			<td colspan="2" align="center">
        			<p class="featured_time"></p>
        		</td>
        	</tr>
        	<tr>
        		<td colspan="3">
        		</td>
        	</tr>
        	<tr>
        		<td colspan="2">
						</td>
        		<td>
        		</td>
        	</tr>
        </table>
      </span>
    </div>
  </div>
  <?php 
	} ?>
  
  <!--
	<div class="featured_item">
    <div class="featured_content">
      <span class="featured_still">
        <img src="/images/alt1/screening_no_image.png" />
      </span>
      <span class="featured_title">
      	<table width="745" border="0" cellpadding="0" cellspacing="0">
      		<tr height="1">
      			<td width="525" height="1"><img src="/images/spacer.gif" height="0" width="0" /></td><td width="70" height="1"><img src="/images/spacer.gif" height="0" width="0" /></td><td width="150" height="1"><img src="/images/spacer.gif" height="0" width="0" /></td>
      		</tr>
					<tr>
      			<td>
        			<h5>No Screenings Available At This Time</h5>
      			</td>
      			<td colspan="2" align="center">
        			<p class="featured_time"><a href="/film/56">8:30PM EST</a> | <a href="/film/56">09/09/11</a></p>
        		</td>
        	</tr>
        	<tr>
        		<td colspan="3">
        		 <div class="countdown_start">STARTS IN</div> <span class="timekeeper" id="timekeeper_<?php echo $screen["screening_unique_key"];?>">2HRS 15MIN 37S</span>
        		</td>
        	</tr>
        	<tr>
        		<td colspan="2">
        			<div class="user_images">
								<span class="user_list user_images_<?php echo $screen["screening_unique_key"];?>">
								<img class="user_image" src="/images/alt1/user_icon.png" alt="Shinkel" width="50" /><div class="tooltip" style="display:none">Shinkel</div>
								<img class="user_image" src="/images/alt1/user_icon.png" alt="Shinkel" width="50" /><div class="tooltip" style="display:none">Shinkel</div>
								<img class="user_image" src="/images/alt1/user_icon.png" alt="Shinkel" width="50" /><div class="tooltip" style="display:none">Shinkel</div>
								<img class="user_image" src="/images/alt1/user_icon.png" alt="Shinkel" width="50" /><div class="tooltip" style="display:none">Shinkel</div>
								<img class="user_image" src="/images/alt1/user_icon.png" alt="Shinkel" width="50" /><div class="tooltip" style="display:none">Shinkel</div>
								<img class="user_image" src="/images/alt1/user_icon.png" alt="Shinkel" width="50" /><div class="tooltip" style="display:none">Shinkel</div>
								</span>
								<span class="user_count user_count_<?php echo $screen["screening_unique_key"];?>"><?php echo $screen["screening_audience_count"];?> attending</span>
							</div>
						</td>
        		<td>
        			  <a class="screening_link" href="/film/<?php echo $screen["screening_film_id"];?>/detail/<?php echo $screen["screening_unique_key"];?>" title="<?php echo $screen["screening_unique_key"];?>"><img src="/images/alt1/attend.png" /></a>
        		</td>
        	</tr>
        </table>
      </span>
    </div>
  </div>
	-->
</div>
