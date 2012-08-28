<div class="allfilms">
	<div class="film_title main-films"><a href="javascript: void(0);">All Films</a></div>
	
	<div class="film_content">
	<?php foreach($films as $film) {?>
	<span class="afilm"><a href="/film/56"><?php echo $film["name"];?></a></span>
	<?php } ?>
	</div>
	
</div>
