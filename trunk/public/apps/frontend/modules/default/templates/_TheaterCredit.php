<div id="theater-credit" class="film-details clearfix">
	<div class="clearfix">
		<div class="poster-wrap">
			<img class="poster" src="/uploads/screeningResources/<?php echo $film["screening_film_id"];?>/logo/small_poster<?php echo $film["screening_film_logo"];?>" alt="<?php echo $film["screening_film_name"];?>" class="widget_video_still" width="189" height="280" border="0"/>
			<div class="poster-notice complete"></div>
		</div>

		<div class="film-text">
			<h1 class="film-title"><?php echo $film["screening_name"] != '' ? $film["screening_name"] :$film["screening_film_name"];?></h1>
			<?php if ($film["screening_user_full_name"] != '') :?>
				<div class="film-host clearfix">
					<a href="/profile/<?php echo $film['screening_user_id']?>" target="_blank">
					<img src="<?php echo getShowtimeUserAvatar($film, 'large');?>" height="48" width="48"/>
	 				</a>
					<p>Hosted By</p>
					<h2><a href="/profile/<?php echo $film['screening_user_id']?>" target="_blank"><?php echo $film["screening_user_full_name"];?></a></h2>
				</div>
			<?php endif; ?>
			<p class="m-p">Thank you for attending <?php echo $film["screening_film_name"];?>.</br>
				We hope you enjoyed the show. Continue the conversation <a href="/event/<?php echo $film["screening_unique_key"];?>">here</a>.</p>
		</div>
	</div>
</div>