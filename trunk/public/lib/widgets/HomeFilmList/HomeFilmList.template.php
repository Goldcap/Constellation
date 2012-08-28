<div class="inner_container clearfix">
	<div class="clearfix">
			
			<?php if ((count($carousel) == 0) && (count($next) == 0)) {?>
			
				<h4 class="no_show">There are currently no showtimes for this film! <br /> Why don't you <a href="/hosting">host a showtime?</a></h4>
      
			<?php } else { ?>
				
				<!-- SCREENINGLLIST -->
				<?php include_component('default', 
				                        'FilmBlock', 
				                        array('screenings'=>$next,'count'=>$nextcount,'title'=>'Upcoming Showtimes','pred'=>'next','header'=>true))?>
				<!-- SCREENINGLLIST -->
				  
				<!-- SCREENING CAROUSEL -->
				<?php include_component('default', 
				                        'FilmBlock', 
				                        array('screenings'=>$carousel,'count'=>$carouselcount,'title'=>'Featured Showtimes','pred'=>'featured'))?>
				<!-- SCREENING CAROUSEL -->
			<?php } ?>
			
	</div> 
</div>

<!-- HOME INVITE -->
<?php include_component('default', 
                        'InviteAlt')?>
<!-- HOME INVITE -->
