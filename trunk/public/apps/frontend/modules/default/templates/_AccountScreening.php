      <?php if ($purchases["meta"]["totalresults"] > 0) {
			foreach($purchases["data"] as $purchase) {
			$vars = explode("/",$purchase["audience_short_url"]);
				$isHosting = $title == 'Hosting';
				$isAttending = !$isHosting;
				$runTime = explode( ":", $screen['screening_film_running_time']);
			?>

			<div class="movie_block block clearfix" alt="/film/<?php echo $purchase["audience_film_id"];?>" >
		    <div class="movie_block_content"> 
		    	<!-- Time 
					<ul class="featured_time">
					<?php if ((strtotime($purchase["audience_screening_date"])) > (strtotime( now()))) :?>
						<?php if (formatDate($purchase["audience_screening_date"],"TSFloor") == formatDate(now(),"TSFloor")) :?>
				    	<li>TODAY @ <?php echo date("g:iA T",strtotime($purchase["audience_screening_date"]));?></li>
				    	<?php else :?>
							<li><?php echo date("g:iA T",strtotime($purchase["audience_screening_date"]));?></li>
							<li><?php echo date("m/d/y",strtotime($purchase["audience_screening_date"]));?></li>
						<?php endif; ?>
					<?php else :?>
						<li><img border="0" class="in_progress" src="/images/alt1/in_progress.png"></li>					
					<?php endif; ?>
					</ul> 
		    	<!-- End Time -->

		    	<!-- Poster Block -->
		        <div class="movie_block_poster" style="min-height: 200px;; width: 150px">
			        <span class="screening_container screening_<?php echo $vars[2];?>" onclick="window.location='/film/<?php echo $purchase["audience_film_id"];?>'">
							<?php if ($purchase["audience_film_logo"] != ''):?>
							  <img src="/uploads/screeningResources/<?php echo $purchase["audience_film_id"];?>/logo/small_poster<?php echo $purchase["audience_film_logo"];?>" alt="" width="130" />
							<?php  else :?>
							   <img src="/images/alt/featured_still.jpg" width="130" />
							<?php endif; ?>
					</span>
		    </div>
		    </div>


						

		    	<!-- end Poster Block -->

		    	<!-- end Detail Block -->
		        <div class="movie_block_details"> 
		        	<h6 class="movie_block_title"><?php echo $purchase["audience_film_name"];?></h6>

		        	<?php if($purchase["audience_screening_name"]):?>
				    <p class="hosted">
				        	<?php if ($purchase["audience_screening_user_photo_url"] != '') :?>
								<?php if (left($purchase["audience_screening_user_photo_url"],4) != 'http') :?>
						    		<img class="screening_host" src="/uploads/hosts/<?php echo $purchase["audience_screening_user_id"];?>/icon_medium_<?php echo $purchase["audience_screening_user_photo_url"];?>" />
						        <?php else : ?>
						        	<img class="screening_host" src="<?php echo $purchase["audience_screening_user_photo_url"];?>" />
						        <?php endif;?>
						    <?php elseif ($purchase["audience_screening_user_image"] != '') :?>
								<?php if (left($purchase["audience_screening_user_image"],4) != 'http') : ?>
						        	<img class="screening_host" src="/uploads/hosts/<?php echo $purchase["audience_screening_user_id"];?>/icon_medium_<?php echo $purchase["audience_screening_user_image"];?>" />
						        <?php else : ?>
						        	<img class="screening_host" src="<?php echo $purchase["audience_screening_user_image"];?>" />
						        <?php endif ;?>
						    <?php elseif ($purchase["audience_screening_user_id"] > 0) :?>
						    	<img class="screening_host" src="https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png" />
						    <?php endif; ?>
							<?php echo $purchase['audience_screening_name'] != '' ? $purchase['audience_screening_name'] : 'Hosted by ' . $purchase["audience_screening_name"];?>

					</p>
					<?php endif;?>
		          	<!--<?php if ((strtotime($purchase["audience_screening_date"])) > (strtotime( now()))) :?>
						<p><span class="countdown_start">STARTS IN</span> <span class="timekeeper" id="timekeeper_<?php echo $vars[2];?>_<?php echo $title;?>">0HRS 0MIN 0S</span></p>

		          	<?php else:?>
		          		<p><span class="countdown_start">ELAPSED TIME</span> <span id="time_remaining_<?php echo $vars[2];?>_<?php echo $title;?>" class="elapsed_time"><?php echo date("Y|m|d|G|i|s",strtotime($purchase["audience_screening_date"]));?></span> <span class="film_time_total">/ <?php echo $runTime[0] ?> <span class="shorty">HRs</span> <?php echo $runTime[1] ?><span class="shorty">MIN</span><span></span></span></p>

		          	<?php endif;?>-->
		          	<p class="time"><?php echo date("g:i A T, F dS, Y",strtotime($purchase['audience_screening_date'])) ;?></p>
		          	
		         	<div class="user_images"> 
		        		<span class="user_list user_images_<?php echo $vars[2];?>"></span> 
		        		<span class="user_count user_count_<?php echo $vars[2];?>">1 attending</span> 
		                <a href="<?php echo $purchase["audience_short_url"];?>" title="<?php echo $vars[2];?>" class="button button_green uppercase">Enter Theater</a> 
		            </div>

		            <div class="movie_block_invite">
			                Invite your friends to this screening 
			                <span style="padding: 8px 10px;" class="button button_light button_invite_email" onclick="invite.invite('<?php if($isHosting) { echo 'host'; } else { echo 'screening'; } ?>','<?php echo $vars[2];?>');"><span class="icon_email"></span> Email invitations</span>
			                <span class="button button_faceblue button-medium button_invite_facebook" onclick="fb_invite.invite('<?php if($isHosting) { echo 'host'; } else { echo 'screening'; } ?>','<?php echo $vars[2]?>');"><span class="icon_facebook"></span> Invite Facebook friends</span>
			            </div>
		        </div>

	    </div>
	    <script type="text/javascript"> 
			$(document).ready(function() {
			    
				//$(".screening_<?php echo $vars[2];?>" ).tooltip({ effect: 'slide', relative: true, position: 'center right' });
			    //countdown_alt.init('<?php echo $vars[2];?>_<?php echo $title;?>','<?php echo date("Y|m|d|H|i|s",strtotime($purchase["audience_screening_date"]));?>');
			    //remaining_alt.runHrMin('<?php echo $vars[2]?>_<?php echo $title;?>','<?php echo date("Y|m|d|H|i|s",strtotime($purchase["audience_screening_date"]));?>');
					screening_attendees.init('<?php echo $vars[2];?>',<?php echo $purchase["audience_screening_id"];?>,6);
			});
			</script> 
			<?php  }} else {?>
			<div class="movie_block"> 
		  	<?php if ($title == 'Attending') {?>
				<h4 class="no_show">You are not currently scheduled to attend any showtimes <br> Why don't you <a href="/films">Browse upcoming showtimes?</a></h4>	    
				<?php } ?>
			</div>
			<?php } ?>

