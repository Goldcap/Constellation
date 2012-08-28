<!-- Slideshow HTML -->
<div id="slideshow">
  <div id="slidesContainer">
<?php 
$reviews = $vars["FilmView"]["column1"][0]["reviews"];
if (count($reviews) > 0) {
foreach ($reviews as $review) {?>
    <h3 class="slide"><?php echo $review;?></h3>
<?php }} ?>
  </div>
</div>
<!-- Slideshow HTML -->


        	
