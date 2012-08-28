<?php 
if (isset ($form) ) {echo $form;}
?>


<div class="ctv-panel ctv-panel-latest active clearfix">

	<h4 class="uppercase">Featured Showtimes</h4>

	<div id="featured-showtime">
	<?php if (count($featuredFilms) > 0) {foreach ($featuredFilms as $showtime):?>

	<div class="showtime-seh showtime-pop clearfix" id="screening-<?php echo $showtime['screening_id']?>"> 
		<div class="showtime-seh-poster">
			<?php if ($showtime["screening_film_homelogo"] != "") {?>
			  <img src="/uploads/screeningResources/<?php echo $showtime['screening_film_id'];?>/logo/wide_poster<?php echo $showtime['screening_film_homelogo'];?>" class="showtime-film-logo" height="88" width="218" />
			<? } else { ?>
				<img height="88" width="218" src="https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-film-default-seh.png"  class="showtime-film-logo" />
			<?php } ?>
		</div>
		<div class="showtime-seh-image">
			<img src="<?php echo getShowtimeUserAvatar($showtime,"large");?>" height="88" width="88" class="showtime-profile-avatar"/>
		</div>
		<div class="showtime-seh-details">
			<p class="title"><?php echo !empty( $showtime['screening_name']) ? $showtime['screening_name'] : $showtime['screening_user_full_name'] . ' hosts ' . $showtime['screening_film_name']; ?></p>
			<p class="time"><?php echo date("g:i A T, F dS, Y", strtotime($showtime['screening_date'])) ;?></p>
			<!-- <p class="attending uppercase"><?php echo $showtime['screening_audience_count']?> Attending</p> -->
		</div>
	</div>

	<?php endforeach; } ?>
	</div>
	<?php if($featuredFilmsCount > 3):?>
	<div class="button_container clearfix clear">
        <span class="button_large more-showtimes "><span>More Hosted Showtimes<span class="down_button_arrow"></span></span></span>
    </div>
    <?php endif;?>

	<h4 class="uppercase clear">Featured Films - <a href="/films">View All &raquo;</a>
</h4>
	<div id="filmContainer">
	</div>


</div>


<script>
jQuery(function(){

	new CTV.ShowtimeHome();
	new CTV.FilmsHome();
	new CTV.ShowtimeDetail({
		userId: <?php echo ($sf_user -> isAuthenticated()) && ($sf_user["user_id"] > 0) ? $sf_user["user_id"] : 0 ?>
	});

	<?php if (count($featuredFilms) > 0) { foreach ($featuredFilms as $showtime) {?>
		$('#screening-<?php echo $showtime['screening_id']?>').data('screening', <?php echo json_encode ($showtime)?>);
	<?php }} ?>
	});
</script>