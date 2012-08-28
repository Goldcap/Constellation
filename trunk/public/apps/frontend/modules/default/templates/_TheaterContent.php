<?php 
	$hasQa = $film['screening_live_webcam'] == '1' && $film['screening_user_id'] != '' && $film["screening_has_qanda"] == '1';
?>

<div id="theater-film-details" class="film-details clearfix">
	<div class="clearfix">
	<div class="poster-wrap">
		<img class="poster" src="/uploads/screeningResources/<?php echo $film["screening_film_id"];?>/logo/small_poster<?php echo $film["screening_film_logo"];?>" alt="<?php echo $film["screening_film_name"];?>" class="widget_video_still"  border="0"  width="189" height="280"/>
		<?php if (strtotime($film["screening_date"]) < strtotime(date("Y-m-d H:i:s")) && (!$hasQa || ($hasQa && $screening_qanda_status != 'closed'))) :?>
		<div class="poster-notice in-progress"></div>
		
		<?php elseif (strtotime(date("Y-m-d H:i:s")) > $film_end_time) :?>
		<div class="poster-notice complete"></div>
		<?php endif; ?>
	</div>


	<div class="film-text">
		<h1 class="film-title"><?php echo $film["screening_film_name"];?></h1>
		<?php if ($film["screening_user_full_name"] != '') :?>
			<div class="film-host clearfix">
				<a href="/profile/<?php echo $film['screening_user_id']?>" target="_blank">
				<img src="<?php echo getShowtimeUserAvatar($film, 'large');?>" height="48" width="48"/>
 				</a>
				<p>Hosted By</p>
				<h2><a href="/profile/<?php echo $film['screening_user_id']?>" target="_blank"><?php echo $film["screening_user_full_name"];?></a></h2>

			</div>
		<?php endif; ?>
		<p class="film-info"><?php echo strlen($film["screening_film_info"]) > 310 ? substr($film["screening_film_info"],0,310) . '&hellip;': $film["screening_film_info"];?></p>

		<div class="film-timer clearfix">
			<div id="time"> </div>
			<div id="period" class="period"></div>
		</div>

	</div>

	</div>
	<div class="hr"></div>
	<?php if($gbip):?>
	<div class="hr"></div>
	<p class="error-block error-block-small" style="width: 340px; margin-top: 10px; float: left">We're sorry, this film cannot be streamed in your current location.</p>
    <?php else :?>
 	<div class="clearfix">
	<?php// if (time() < $film_end_time - 300):?> 
		<?php if (isset($auth_msg)) :?>
		<div class="film-notice left">
	  		<?php if ($film["screening_film_use_sponsor_codes"] == 1) :?>
			Welcome to the theater! You'll be able to chat with everyone else before, during, and after the film. Enter your code to start viewing.

			<?php else :?>
<!-- 			You're in the theater. You'll be able to watch the movie and chat with everyone else, but you'll need to purchase a ticket first.<br/>
 -->			<?php if($user_id == 0):?>
			<p style="font-size: 18px ; line-height: 44px">If you already have a ticket, <a href="javascript:void(0)" class="main-login">Login Here</a></p>
			<?php else : ?>
			
			<?php endif;?>
			<?php endif; ?>
		</div>
		
		<div class="prescreen_purchase">
			<?php if ($film["screening_film_use_sponsor_codes"] == 1) :?>
				<a href="/boxoffice/screening/<?php echo $film["screening_unique_key"];?>" class="button button-green uppercase right">Get Ticket</a>
			<?php else :?> 
				<?php if ($auth_msg =='This screening is sold out.') :?>
					<div class="right" style="margin-top: -10px"><img src="/images/alt1/theater_max.png" /><p>Check back soon to see clips from the live event.</p></div>
				<?php else :?>
					<a href="/boxoffice/screening/<?php echo $film["screening_unique_key"];?>" class="button button-green uppercase right">Get Ticket</a>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php else : ?>
			<div class="notice-ticket">

			<h3>You have RSVP'd to this event. </h3> <p>You can find this event again via the "My Events" tab on the navigation bar, or via the link in the ticket emailed to you.</p>
			</div>
		<?php endif;?>
	<?php endif;?>
	</div>
	<?php //endif;?>
</div>
 