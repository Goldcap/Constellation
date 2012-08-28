
<div id="hero_module" class="hero_module">
<?php 
$default = 2;

if (count($screenings) == 0) {?>

<!-- /// CUSTOM NAVIGATION -->
<div class="hero_block">
   <div class="slideshow_container" id="home-slideshow"> 
        <div class="inner_container clearfix">
      <!-- IMAGE -->
      <div class="const_info"><strong>Constellation</strong> is your online movie theater. Join a showtime (or create one), and watch movies with friends.</div>      
    <div class="slideshow slides_container">
    	<div>
            <img src="/images/constellation-banner.jpg" class="slideshow_image"/>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="sub_block">
  <div class="inner_container clearfix">
      <ul class="">
          <li class="slide_film_details_block">
              <div class="right">
                <span class=" film_showtime_impatient">Impatient?</span>
                <a href="/hosting" class="button button_white uppercase ">Create a Showtime</a>
              </div>
          </li>
      </ul>
  </div>
</div>
<?php } else { ?>
<div class="hero_block">
		
    <div class="slideshow_container" id="home-slideshow"> 
        <div class="inner_container clearfix">
			    <div id="movie_trailer_container" class="nx_widget_video"></div>

					<!-- IMAGE -->
			    <div class="const_info"><strong>Constellation</strong> is your online movie theater. Join a showtime (or create one), and watch movies with friends.</div>      
			    <div class="slideshow slides_container">
         <!-- Temp added for hardcoded banner -->

          <div data-banner="isPresentationBanner">
            <img src="/images/constellation-banner.jpg" class="slideshow_image"/>
          </div>
          
					<!-- Temp added for hardcoded banner -->

				  <?php
				      $i=1; 
				      $count = count($screenings); 
				      foreach ($screenings as $screen) :
				  ?>
				      
			      <div style="display:none">
			        <a href="/film/<?php echo $screen["screening_film_id"];?>"><img src="/uploads/screeningResources/<?php echo $screen["screening_film_id"];?>/background/<?php echo $screen["screening_film_splash_image"];?>" class="slideshow_image"/></a>
			        
			        <div class="slideshow_details">
			              <p class="title"><strong><?php echo $screen["screening_film_name"];?></strong></p>
			              <div class="description"><?php echo $screen["screening_film_info"];?></div> 
                    <div style="display:none">
                      <?php //var_dump($screen) ;?>
                    </div>
			        </div>
			        <?php 
							if ($screen["stream_url"] != '') {
							$film = $screen["screening_film_id"];
							$stream = str_replace(array("rtmp://cp113558.edgefcs.net/ondemand/","?"),array("","%3F"),$screen["stream_url"]);
			        $image = '/uploads/screeningResources/'.$screen["screening_film_id"].'/still/'.$screen["screening_film_still_image"];
							?>
							<div class="start_trailer" id="trailer_<?php echo $film;?>" onclick="trailer.clickStart( this, '<?php echo $film;?>', '<?php echo $stream;?>', '<?php echo $image;?>' )">&nbsp;</div>
			    		<?php } ?>
					</div>
						<?php
			        $i++; 
			        endforeach;
			      ?>
				</div>
			  
				<div class="slideshow_controls">
			      <span class="next"></span>
			      <span class="previous"></span>
			      <div class="slideshow_thumbs_wrap">
			      <ul class="slideshow_nav pagination">
              <!-- Temp added for hardcoded banner -->
                <li><a href="#1"></a></li>
              <!-- end Temp added for hardcoded banner -->

			          <?php for($i = 1 ; $i <= $count; $i++):?> 
			          <li><a href="#<?php echo $i +1 ?>"></a></li>
			          <?php endfor;?>                      
			      </ul>
			      </div>
				</div>
	    <div id="howItWorks">
	  		Would you like to know more?  Read <a href="/faq">How it Works</a>.
			</div>
  </div>
</div>

</div>

<div class="sub_block">
    <div class="inner_container clearfix">
        <ul class="slide_film_details">
          <?php $i=1;foreach ($screenings as $screen){?>
            <li class="slide_film_details_block">
                <!--<div class="left">
                <span class="upcomming_showtimes">Upcoming Showtimes of <?php echo $screen["screening_film_name"];?></span> 
                <ul class="showtime_slide">
                <?php $j=1; foreach($screen["screening_times"] as $show) {?>
                  <li><?php echo formatDate($show["date"],"daytimeplus");?></li>
                <? $j++; }?>
                  <li class="last"> <a href=""> View All &raquo;</a></li>
                </ul>
                </div>-->
                <?php if (($screen["screening_film_allow_user_hosting"] == 1) || ($screen["screening_film_allow_hostbyrequest"] == 1)) {?>
								<div class="right">
                	<span class=" film_showtime_impatient">Impatient?</span>
                  <?php if ($screen["screening_film_allow_user_hosting"] == 1) {?>
										<a href="/hosting/<?php echo $screen["screening_film_id"];?>/host_screening" class="button button_white uppercase ">Create a Showtime</a>
                  <?php } ?>
									<?php if ($screen["screening_film_allow_hostbyrequest"] == 1) {?>
										<a href="/film/<?php echo $screen["screening_film_id"];?>/request" class="button button_gray uppercase ">Watch Now</a>
                	<?php } ?>
								</div>
                <?php } ?>
            </li>
            <?php $i++; } ?>
        </ul>
    </div>
</div>
<?php } ?>

<div id="barousel_running" class="reqs">1</div>
<a id="barousel_broadcast" class="reqs" href="#"></a>
