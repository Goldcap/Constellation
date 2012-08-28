<script>
  var cfilms = <?php echo $json;?>;
</script>

<div class="buttons">
  <ul>
      <li><a id="gs" href="javascript: void(0);">view by genre</a>
          <ul id="drop_genre" class="drop_genre">
            <li id="all">View All</li>
            <?php foreach($genres as $genre) {?>
            <li id="<?php echo $genre["genre_name"];?>"><?php echo $genre["genre_name"];?></li>
            <?php }?>
          </ul>
      </li>
      <!--<li><a href="">veiw by rating</a></li>-->
  </ul>
</div>
<div class="search">
  <script type="text/javascript">
    $(document).ready(function(){ $("#gv").watermark("SEARCH"); });
  </script>
  <form>
  	<fieldset>
      	<input id="gg" name="gg" class="btn_search" type="button" /><input id="gv" name="gv" type="text" class="field" /> 
      </fieldset>
  </form>
</div>

<div class="image_gallery">
      <!--<div class="navigation"><a href="" class="previous">previous</a><a href="" class="next">next</a></div>-->
      <div id="carousel" class="scroll">
          <ul id="main_carousel">
              <?php if (count($carousel) > 0) {
              foreach($carousel as $cfilm) {?>
              <li id="film_<?php echo $cfilm["film_uid"];?>">
                <div class="img-container"></div>
                <img class="reflected" type="<?php echo $cfilm["logo_type"];?>" name="<?php echo $cfilm["program_short_name"];?>" index="<?php echo $cfilm["film_uid"];?>" src="/uploads/<?php echo $cfilm["logo_type"];?>/<?php echo $cfilm["film_id"];?>/logo/small_poster<?php echo $cfilm["film_logo"];?>" width="163" height="235" alt="" />				
                <div class="overlay"></div>
              </li>
              <?php }} ?>
          </ul>
      </div>
</div>

<!-- start pop-up -->

<div id="carousel-popup" class="pop_up" style="display: none">
  <div class="pop_mid">
  <div class="pop_top carousel-popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
      	<div class="left"><img id="carousel_photo_container" src="images/spacer.gif" alt="" width="213" height="315" />
          <br /><span class="arrow"><a href="#" id="carousel_trailer_link">Play Trailer</a></span></div>
          <div class="right">
          	<div class="title"><strong><span id="carousel_film_title">Great Films</span></strong>
              <div id="carousel_film_description">Showing now at Constellation.tv</div>
              <div id="carousel_film_info">Constellation.tv hosts some of the world's best independent and upcoming films.</div>
            </div>
            <!--<div class="spacer"></div>-->
            <a id="carousel_film_link" href="#" class="btn_large">all showtimes</a>
            <a id="carousel_host_link" href="#" class="btn_large">host your own screening</a>
          </div>
      </div>
  <div class="pop_bot"></div>
  </div>    
</div>

<div id="trailer-popup" class="pop_up" style="display: none">
  <div class="pop_mid">
  <div class="pop_top carousel-popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
      <div id="movie_trailer_container" class="nx_widget_video"></div>
      <span class="reqs" id="video"></span>
      <span class="reqs" id="video-autoplay">true</span>
      <span class="reqs" id="video-type">TRAILER</span>
    </div>
  <div class="pop_bot"></div>
  </div>    
</div>

<!-- end pop-up -->
