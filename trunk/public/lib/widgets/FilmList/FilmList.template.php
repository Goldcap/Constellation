<?php if(!empty($carousel)):?>

<div class="block">
	<h4>Special Events</h4>
	<?php foreach($carousel as $cscreening) :?>
	<div class="showtime-se showtime-pop clearfix" id="screening-<?php echo $cscreening['screening_id']?>" data-id="<?php echo $cscreening['screening_id']?>" data-unique-id="<?php echo $cscreening['screening_unique_key']?>">
	<!-- <a href="/theater/<?php echo $cscreening['screening_unique_key']?>" class="showtime-link" title="<?php echo $cscreening['screening_unique_key']?>"> -->
		<div class="showtime-se-image">
			<img src="<?php echo getShowtimeUserAvatar($cscreening,"large")?>" height="88" width="88" class="showtime-profile-avatar" />
		</div>
		<div class="showtime-se-details">
			<p class="title"><?php echo !empty( $cscreening['screening_name']) ? $cscreening['screening_name'] : $cscreening['screening_user_full_name'] ; ?></p>
			<p class="time"><?php echo date("g:i A T, F dS, Y",strtotime($cscreening['screening_date'])) ;?></p>
			<!-- <p class="attending uppercase"><?php echo $cscreening['screening_audience_count'];?> Attending</p> -->
		</div>
	<!-- </a> -->
	</div>
	<?php endforeach; ?>
	<p class="block-instructions">(click on a showtime above to purchase your ticket)</p>
</div>	
<?php endif;?>
<?php if(!empty($daily)):?>
<div class="block">
	<h4>Daily Showtimes</h4>
	<?php foreach($daily as $dscreening) :?>
	<div class="showtime-d showtime-pop clearfix" id="screening-<?php echo $dscreening['screening_id']?>"  data-id="<?php echo $dscreening['screening_id']?>" data-unique-id="<?php echo $dscreening['screening_unique_key']?>">

		<div class="showtime-d-time time">
			<?php echo date("g:iA",strtotime($dscreening['screening_date'])) ;?>
			<span><?php echo date("T",strtotime($dscreening['screening_date'])) ;?></span>
		</div>
		<div class="showtime-d-details">
			<?php if($dscreening['screening_audience_count'] == 0):?>
			<p class="attending">Be the first the attend.</p>
			<?php else :?>
			<p class="attending uppercase"><?php echo $dscreening['screening_audience_count'];?> Attending</p>
			<?php endif;?>
		</div>

	</div>

	<?php endforeach;?>
	<p class="block-instructions">(Click on a showtime above to purchase your ticket)</p>
</div>		
<?php endif;?>
<?php if ($film["film_allow_hostbyrequest"] == 1) :?>
	<div class="block-clear center">
		<span class="text-impatient">Impatient? </span>
		<a href="/boxoffice/screening/none/ondemand/film/<?php echo $film['film_id']?>/dohbr/true" class="button button_blue button-large uppercase">Watch Now</a>
	</div>
<?php endif; ?>

<?php if($film["film_allow_hostbyrequest"] != 1 && empty($carousel) && empty($daily)):?>
<div class="block">
<h2 style="line-height: 28px">There are currently no scheduled Showtimes for this film.</h2>
</div>
<?php endif;?>

<div id="film_id" class="reqs"><?php echo $film["film_id"];?></div>

<!-- HOME INVITE -->
<?php //include_component('default', 'InviteAlt')?>
<!-- HOME INVITE -->

<script>
jQuery(function(){
	<?php if(!empty($carousel)):?>
			<?php foreach($carousel as $cscreening) :?>
			$('#screening-<?php echo $cscreening['screening_id']?>').data('screening', <?php echo json_encode ($cscreening)?>);
		<?php endforeach;?>
	<?php endif;?>
	<?php if(!empty($daily)):?>
	<?php foreach($daily as $dscreening) :?>
		$('#screening-<?php echo $dscreening['screening_id']?>').data('screening', <?php echo json_encode ($dscreening)?>);
	<?php endforeach;?>
	<?php endif;?>
	new CTV.ShowtimeDetail({
		userId: <?php echo ($sf_user -> isAuthenticated()) && ($sf_user["user_id"] > 0) ? $sf_user["user_id"] : 0 ?>
	});

});
</script>
