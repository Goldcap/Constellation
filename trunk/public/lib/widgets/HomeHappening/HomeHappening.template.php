<?php 
if (isset ($form) ) {echo $form;}

$isLoggedIn = ($sf_user -> isAuthenticated()) && ((is_null($sf_user -> getAttribute("temp"))) || (! $sf_user -> getAttribute("temp")));

?>
<div class="ctv-panel ctv-panel-happening clearfix">
	<div class="ctv-panel-happening-top">
		<div class="block">
			<h4>TOP FILMS OF <?php echo date('F Y');?></h5>

	<?php foreach($happening["data"] as $film){?>
			<div class="showtime-set">
			<a class="showtime-link" href="/film/<?php echo $film["film_id"];?>">
				<span class="rank"><?php echo $i;?></span>
				<div class="showtime-set-poster">
					<?php if ($film["film_logo"] != "") {?>
					<img height="83" width="57" src="/uploads/screeningResources/<?php echo $film["film_id"];?>/logo/small_poster<?php echo $film["film_logo"];?>">
				  <?php } else { ?>
				  <img height="83" width="57" src="https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png">
				  <?php } ?>
				</div>
				<div class="showtime-set-details">
					<p class="title"><?php echo $film["film_name"];?></p>
					<?php if ($film["film_director"] != "") {?>
					<p class="director">dir. by <?php echo $film["film_director"];?></p>
					<?php } ?>
				</div>
			</a>
			</div>
		<?php }?>
			<div class="showtime-set-button-wrap  clearfix">
		        <a href="/films" class="button_large"><span>Browse All Films <span class="right_button_arrow"></span></span></a>
		    </div>
    		</div>
	</div>
	<div class="ctv-panel-happening-conversation">
		<div class="block">
			<h4>LATEST DISCUSSED FILMS</h5>

			<div class="">
			<?php foreach($discussed["data"] as $film){?>

			<div class="ctv-panel-happening-conversation-block clearfix" data-id="<?php echo $film["film_id"];?>">
				<div class="conversation-poster">
					<a href="/film/<?php echo $film["film_id"];?>"><img src="http://constellation.tv/uploads/screeningResources/<?php echo $film["film_id"];?>/logo/small_poster<?php echo $film["film_logo"];?>" width="90"></a>
				</div>
				<div class="title"><a href="/film/<?php echo $film["film_id"];?>"> <?php echo $film["film_name"];?></a></div>
				<ul id="comment-list-<?php echo $film["film_id"];?>" class="comment-list"></ul>

		        <a href="/film/<?php echo $film["film_id"];?>" class="button_inset clear"><span class="comment-more"></span><span>More Discussions</span></a>

			</div>
			<?php } ?>
		</div>

		</div>
	</div>
</div>


<script> 
jQuery(function(){
    _.templateSettings = {
	  interpolate : /\{\{(.+?)\}\}/g
	};
	$('.ctv-panel-happening-conversation-block').each(function(){
		new CTV.Comments({
			list: $(this).find('ul'),
			filmId: $(this).attr('data-id'),
			rpp: 2,
			isLoggedIn: <?php echo $isLoggedIn ?'true': 'false'; ?>,
			fbShareCaptions: 'Constellation.tv - your online social movie theater',
			tShareCaption: ''
		});
	});
});
</script>