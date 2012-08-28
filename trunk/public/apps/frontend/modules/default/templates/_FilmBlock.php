<?php if (count($screenings) > 0) {
if ($single) {
	$mocount = 1;
} else {
	$mocount = count($screenings);
}
?>
<div class="movies_group <?php if (! isset($single)) { echo "movies_group_shadow"; }?>">
	<?php if(!isset($film) && $header):?>
    <h2 class="content_title"><?php echo $pred;?> Showtimes</h2>
    <p class="content_sub_title">Click "Join" to buy your ticket to a showtime below.</p>
  <?php else :?>
	 <h2 class="content_title"><?php echo $title;?><?php if (isset($film)) { echo " of <strong>" . $film["film_name"] . "</strong>"; }?></h2>
	<?php endif;?>


	<div class="film_block_list_wrap"> 
	<div class="film_block_list film_block_list_<?php if ($title == "Upcoming Showtimes") { echo "upcoming"; } else { echo "featured"; }?>"> 
	<?php $i=0;foreach ($screenings as $screen) {
  $time = strtotime(now()) - strtotime($screen["screening_date"]);
	$hours = floor($time / 3600);
	$time = $time % 3600;
	$minutes = floor($time/ 60);
	$time = $time % 60;
	$seconds = floor($time);
	$remaining_time = sprintf("%02d",$hours) . ":" . sprintf("%02d",$minutes) . ":" . sprintf("%02d",$seconds);
	$runTime = explode( ":", $screen['screening_film_running_time']);
	$totalSeats = ($screen["screening_total_seats"] > 0) ? $screen["screening_total_seats"] : $screen["screening_film_total_seats"];
	$totalSeats = ($totalSeats > 0) ? $totalSeats : 500;
	$isSoldout = $screen["screening_audience_count"] >= $totalSeats;
	$seatLeft = $totalSeats - $screen["screening_audience_count"];
	$extraClass = '';
	$isLimited = ($screen["screening_highlighted"] == "true") ? true : false;
	$isHosting = ($screen["screening_user_id"] == $sf_user -> getAttribute("user_id")) ? true : false;
	$isAttending = $screen["screening_attending"];
		
	if ($isHosting) {
		$extraClass = 'movie_block_hosting';
	} else if($isAttending) {
		$extraClass = 'movie_block_attend';		
	} else if($isLimited){
		$extraClass = 'movie_block_gold';
	}
  if (($screen["screening_user_full_name"] != '') && ($screen["screening_name"] == '')) {
	 $tlen = strlen($screen["screening_user_full_name"] . " hosts " . $screen["screening_film_name"]);
	} else if ($screen["screening_name"] != '') {
   $tlen =  strlen($screen["screening_name"]); 
  } else {
   $tlen =  strlen($screen["screening_film_name"]);
  }
  switch (true) {
    case ($tlen >= 54):
    $fontsize = "12px";
      break;
    case ($tlen >= 48):
    $fontsize = "14px";
      break;
    case ($tlen >= 46):
    $fontsize = "15px";
      break;
    case ($tlen >= 40):
    $fontsize = "16px";
      break;
    case ($tlen >= 36):
    $fontsize = "18px";
      break;
    default:
      $fontsize = "20px";
    	break;
  }
  ?>
	  <div class="<?php if ($i == 0) { echo "movie_group_first "; } ?> movie_block movie_block_<?php if ($title == "Upcoming Showtimes") { echo "upcoming"; } else { echo "featured"; }?> <?php echo $extraClass;?>" alt="/film/<?php echo $screen["screening_film_id"];?>" identifier="<?php echo $screen["screening_id"];?>"> 
			<div class="movie_block_content">
			<!-- Film Time -->
			<a href="/theater/<?php echo $screen["screening_unique_key"];?>" title="<?php echo $screen["screening_unique_key"];?>">
	    		<ul class="featured_time">
	    	<?php if ((strtotime($screen["screening_date"])) > (strtotime( now()))) :?>
				<?php if (formatDate($screen["screening_date"],"TSFloor") == formatDate(now(),"TSFloor")) :?>
	    		<li>TODAY @ <?php echo date("g:iA T",strtotime($screen["screening_date"]));?></li>
	    		<?php else : ?>
				<li><?php echo date("g:iA T",strtotime($screen["screening_date"]));?></li>
				<li><?php echo date("m/d/y",strtotime($screen["screening_date"]));?></li>
		
				<?php endif; ?>
			<?php else :?>
				<li><img border="0" class="in_progress" src="/images/alt1/in_progress.png"></li>					
			<?php endif; ?>
				</ul>
			</a>
			<!-- End Film Time -->
			<?php if($isHosting) :?>
			<p class="movie_block_type"><span class="movie_block_type_hosting"></span></p> 
			<?php elseif($isAttending) :?>
			<p class="movie_block_type"><span class="movie_block_type_attending"></span></p>
			<?php elseif($isLimited):?>
				<?php if($isSoldout):?>
				<p class="movie_block_type">Sold Out <span class="movie_block_type_limited"></span></p>
				<?php else : ?>
				<p class="movie_block_type"><?php echo $seatLeft < 20 ? $seatLeft .' seats left' : '' ?> <span class="movie_block_type_limited"></span></p>
				<?php endif;?>
			<?php elseif($isSoldout) :?>
			<p class="movie_block_type"><span class="movie_block_type_soldout"></span></p> 
			<?php endif;?>
			<!-- Film Poster -->
	        <div class="movie_block_poster"> 
			    <span class="screening_container" id="screening_<?php echo $screen["screening_unique_key"];?>_tip" onclick="window.location='/theater/<?php echo $screen["screening_unique_key"];?>'">
					<?php if ($screen["screening_still_image"] != '') :?>
					  <img src="/uploads/screeningResources/<?php echo $screen["screening_film_id"];?>/screenings/film_screening_next_<?php echo $screen["screening_still_image"];?>" alt="" width="150" />
					<?php elseif ($screen["screening_film_still_image"] != '') :?>
					  <img src="/uploads/screeningResources/<?php echo $screen["screening_film_id"];?>/still/<?php echo $screen["screening_film_still_image"];?>" height="130" />
					<?php else :?>
					   <img src="/images/alt/featured_still.jpg" width="130" />
					<?php endif; ?>
			    </span>
			    <!-- Start Film Tip -->
	        <div class="tooltip film_block_description_tip" style="display:none">
	        	<div class="film_block_description_content">
	        		<?php if ($screen["screening_user_full_name"] != '') :?>
	        			Hosted by <strong><?php echo $screen["screening_user_full_name"]?></strong><br />
	        		<?php endif;?>
	        		<?php echo $screen["screening_description"];?>
	        	</div>
	        	<span class="film_block_description_tip_arrow"></span>
	        </div>
					<!-- End Film Tip -->

	        </div>

	        <div class="movie_block_details">  
	            <span onclick="window.location='/film/<?php echo $screen["screening_film_id"];?>';">
							<style>
								h6.movie_block_<?php echo $screen["screening_unique_key"];?> {
									font-size: <?php echo $fontsize;?> !important;
								}
							</style>
							<h6 class="movie_block_title movie_block_<?php echo $screen["screening_unique_key"];?>">
							<!-- Profile Pic -->
							<?php
							if ($screen["screening_user_photo_url"] != '') {?>
								<?php if (left($screen["screening_user_photo_url"],4) != 'http') {?>
						        <img class="screening_host" width="40" src="/uploads/hosts/<?php echo $screen["screening_user_id"];?>/icon_medium_<?php echo $screen["screening_user_photo_url"];?>" />
						    <?php } else { ?>
						      	<img class="screening_host" width="40" src="<?php echo $screen["screening_user_photo_url"];?>" />
						    <?php }
							} elseif ($screen["screening_user_image"] != '') {
								if (left($screen["screening_user_image"],4) != 'http') {?>
							      <img class="screening_host" width="40" src="/uploads/hosts/<?php echo $screen["screening_user_id"];?>/icon_medium_<?php echo $screen["screening_user_image"];?>" />
							  <?php } else { ?>
							      <img class="screening_host" width="40" src="<?php echo $screen["screening_user_image"];?>" />
							  <?php }
							} elseif ($screen["screening_user_id"] > 0) {?>
							      <img class="screening_host" width="40" src="https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png" />
							<?php }; ?> 
							<!-- End Profile Pic -->
							<?php if (($screen["screening_user_full_name"] != '') && ($screen["screening_name"] == '')) {?>
							<span id="hostname_<?php echo $screen["screening_unique_key"];?>_tip"><?php echo $screen["screening_user_full_name"];?> </span>
							<?php if ($screen["screening_user_bio"] != '') {?>
							<div class="tooltip film_block_description_tip" style="display:none">
								<div class="film_block_description_content">
									<?php echo $screen["screening_user_bio"];?>
								</div>
					      <!-- <span class="film_block_description_tip_arrow"></span> -->
							</div>
							<?php } ?>
							hosts
						<?php } ?>
						<strong><?php echo ($screen["screening_name"] != '') ? $screen["screening_name"] : $screen["screening_film_name"];?></strong>
					</h6>
				</span>
				<?php if ((strtotime($screen["screening_date"])) < (strtotime( now()))) :?>
				<p><span class="countdown_start">ELAPSED TIME</span> <span id="time_remaining_<?php echo $screen["screening_unique_key"];?>_<?php echo $pred;?>" class="elapsed_time"><?php echo date("Y|m|d|G|i|s",strtotime($screen["screening_date"]));?></span> <span class="film_time_total">/ <?php echo $runTime[0] ?> <span class="shorty">HRs</span> <?php echo $runTime[1] ?><span class="shorty">MIN</span><span></span></span></p>
				<?php else :?>
	          	<p><span class="countdown_start">STARTS IN</span> <span class="timekeeper" id="timekeeper_<?php echo $screen["screening_unique_key"];?>_<?php echo $pred;?>">0HRS 0MIN 0S</span> | <span class="uppercase link" onclick="reminder.remind('<?php echo $screen["screening_unique_key"];?>');">Remind Me</span></p>
	          	<?php endif;?>
	          	<div class="user_images"> 
		          	<span class="user_list user_images_<?php echo $screen["screening_unique_key"];?>"></span>
								<span class="user_count user_count_<?php echo $screen["screening_unique_key"];?>">
									<!--Removed 11/4/2011 Bug 001973 -->
									<?php if ($screen["screening_audience_count"] > 0) { 
										//echo $screen["screening_audience_count"];?> Recently Joined 
									<?php } ?>
								</span>
			        		 
	        			<?php 
	        				if($isSoldout){
	        					$buttonClass = 'button_gray';
	        				} else if($isHosting) {
	        					$buttonClass = 'button_green';
	        				} else if ($isAttending) {
	        					$buttonClass = 'button_purple';        					
	        				} else {
		        				$buttonClass = 'button_blue';
	        				}
	        			?>
					
								<?php  if ($isSoldout) {?>
				          <span title="<?php echo $screen["screening_unique_key"];?>" class="button disabled_button <?php echo $buttonClass;?> uppercase">Join</span> 
								<?php } else if ($isHosting) {?>
	        			  <a href="/theater/<?php echo $screen["screening_unique_key"];?>" title="<?php echo $screen["screening_unique_key"];?>" class="button <?php echo $buttonClass;?> uppercase">Enter Theater</a>
				        <?php } elseif ($isAttending) {?>
	        			  <a href="/theater/<?php echo $screen["screening_unique_key"];?>" title="<?php echo $screen["screening_unique_key"];?>" class="button <?php echo $buttonClass;?> uppercase">Enter Theater</a>

				        <?php } else {?>
				          <a href="/theater/<?php echo $screen["screening_unique_key"];?>" title="<?php echo $screen["screening_unique_key"];?>" class="button <?php echo $buttonClass;?> uppercase">Join</a>
				        <?php } ?>
		            </div>
	
		            <?php if($isHosting || $isAttending):?>
		            	<div class="movie_block_invite">
			                Invite your friends to this screening 
			                <span class="button button_gray button_invite_email" onclick="invite.invite('<?php if($isHosting) { echo 'host'; } else { echo 'screening'; } ?>','<?php echo $screen["screening_unique_key"];?>');"><span class="icon_email"></span> Email invitations</span>
			                <span class="button button_faceblue button_invite_facebook" onclick="fb_invite.invite('<?php if($isHosting) { echo 'host'; } else { echo 'screening'; } ?>','<?php echo $screen["screening_unique_key"];?>');"><span class="icon_facebook"></span> Invite Facebook friends</span>
			            </div>
		            <?php endif;?>

	        </div>
	      </div>

		<div id="time_<?php echo $screen["screening_unique_key"];?>" style="display:none"><?php echo formatDate($screen["screening_date"],"DTZ");?></div>
    <div id="cost_<?php echo $screen["screening_unique_key"];?>" style="display:none"><?php echo $screen["screening_film_ticket_price"];?></div>
    <div id="host_<?php echo $screen["screening_unique_key"];?>" style="display:none"><?php echo $screen["screening_user_full_name"];?></div>
		<script type="text/javascript"> 
		$(document).ready(function() {
					<?php if ($screen["screening_user_bio"] != '') {?>
					$("#hostname_<?php echo $screen["screening_unique_key"];?>_tip" ).tooltip({ effect: 'slide', relative: true, position: 'center center ' });
					<?php }?>
					$("#screening_<?php echo $screen["screening_unique_key"];?>_tip" ).tooltip({ effect: 'slide', relative: true, position: 'center right' });
					countdown_alt.init('<?php echo $screen["screening_unique_key"];?>_<?php echo $pred;?>','<?php echo date("Y|m|d|H|i|s",strtotime($screen["screening_date"]));?>');
	        remaining_alt.runHrMin('<?php echo $screen["screening_unique_key"];?>_<?php echo $pred;?>','<?php echo date("Y|m|d|H|i|s",strtotime($screen["screening_date"]));?>');
	        screening_attendees.init('<?php echo $screen["screening_unique_key"];?>',<?php echo $screen["screening_id"];?>);
		});
		</script>
	</div>
	    
	<?php $i++; } ?>
	</div> 
	</div>
	<?php if (($count > $mocount) && (count($screenings) == $mocount)) {?>
	<p class="button_wrap">
      <span class="button button_white" id="<?php if ($title == "Upcoming Showtimes") { echo "moreUpcoming"; } else { echo "moreFeatured"; }?>" <?php if ($count <= $mocount) { echo 'style="opacity: 0.5;"'; }?>>Later Showtimes <span class="down_button_arrow"></span></span>
      <span class="button button_white" id="<?php if ($title == "Upcoming Showtimes") { echo "fewerUpcoming"; } else { echo "fewerFeatured"; }?>" style="display:none">Fewer <span class="down_button_x"></span></span>
  </p>
	<?php } ?>	
</div>
<?php } ?>
