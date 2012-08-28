<link rel="stylesheet" href="/css/form.css" />
<link rel="stylesheet" href="/css/upload.css" />
<div class="inner_container clearfix">
    <h3 class="uppercase host_show_header">Your Films</h3>

	<div class="block clearfix" style="margin-bottom: 40px">
		<?php for ($i = 0; $i < 5; $i ++ ) { ?>
		<div class="film-block <?php echo ($i % 2 == 1) ? "odd" : "even"; ?> clearfix">
			<!-- SCREENING CAROUSEL -->
			<?php include_component('default', 
			                        "AccountFilm")?>
			<!-- SCREENING CAROUSEL -->
		</div>
		<?php } ?>		
			<div class="clear"></div>
	<div class="form-row clearfix clear" style="margin-top: 20px">
		<a class="button button_green uppercase right" href="/account/upload">Add New Film</a>
	</div>		
	</div>
</div>