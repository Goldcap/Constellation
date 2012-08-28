<div class="image_gallery large">
  <h4>featured</h4>
  <?php if (count($carousel) > 0) {?>
  <!-- SCREENING CAROUSEL -->
  <?php include_component('default', 
                          'Carousel', 
                          array('size'=>'','screenings'=>$carousel))?>
  <!-- SCREENING CAROUSEL -->
  <?php } ?>
</div>
