<?php 
$default = 2;

if (count($screenings) == 0) {?>

<!-- /// CUSTOM NAVIGATION -->
<div id="barousel_thslide" class="barousel">
    
    <div class="barousel_top">
      <!-- IMAGE -->
      <div class="barousel_image">
          <img src="/images/home_marquee_default.jpg" alt="" />
      </div>
    </div>
</div>
<?php return;
}
?>

<!-- /// CUSTOM NAVIGATION -->
<div id="barousel_thslide" class="barousel">
    
    <div class="barousel_top">
      <!-- IMAGE -->
      <div class="barousel_image">
          <?php $i=1;foreach ($screenings as $screen) {?>
          <a title="barousel_image_<?php echo $i;?>" href="/film/<?php echo $screen["screening_film_id"];?>">
					<img src="/uploads/screeningResources/<?php echo $screen["screening_film_id"];?>/background/<?php echo $screen["screening_film_splash_image"];?>" alt="" <?php if ($i==$default) {?>class="default" <?php }?> />
					</a>
					<?php $i++; } ?>
      </div>
      
      <!-- CONTENT -->
      <div class="barousel_content">
          <?php $i=1;foreach ($screenings as $screen){?>
          <div title="barousel_item_<?php echo $i;?>" <?php if ($i == $default) {?>class="default"<? }?>>
               <span class="slideshow_details">
								<a href="/film/<?php echo $screen["screening_film_id"];?>"><span class="title"><?php echo $screen["screening_film_name"];?></span></a>
								<a href="/film/<?php echo $screen["screening_film_id"];?>"><span class="description"><?php echo $screen["screening_film_info"];?></span></a>
								<?php if (count($screen["screening_times"]) > 0) {?>
								<span class="upcoming_title">Upcoming Showtimes</span>
								<span class="upcoming_time">
								<?php $j=1; foreach($screen["screening_times"] as $show) {?>
								<a href="/theater/<?php echo $show["key"];?>"><?php echo formatDate($show["date"],"daytimeplus");?></a> <?php if ($j != count($screen["screening_times"])){?>|<?php }?>
								<? $j++; }?>
								</span>
								<?php } ?>
								<a href="/film/<?php echo $screen["screening_film_id"];?>"><span class="view_all">View All &raquo;</span></a>
							</span>
          </div>
          
          <?php $i++; } ?>
      </div>
    </div>
    
    <!-- NAV -->
    <div id="thslide_barousel_nav" class="thslide">

        <div class="thslide_nav_previous"><a href="#">&nbsp;</a></div>

        <div class="thslide_list">

            <ul>
                <?php $i=1;foreach ($screenings as $screen){?>
                <li id="screening_<?php echo $i;?>" attachPoint="thslide_parent" <?php if ($i==$default) {?>class="current" <?php }?>>
									<!--/film/<?php echo $screen["screening_film_id"];?>-->
									<a id="img_<?php echo $i;?>" href="#" class="thslide_item">
										<img src="<?php echo $screen["film_logo_small"];?>" height="76" width="50" <?php if ($i!=$default) {?>class="other" <?php }?>>
									</a>
									<span <?php if ($i!=$default) {?>class="other thslide_details"<?php } else {?>class="details thslide_details"<?php }?>>
									    <span class="title"><a href="/theater/<?php echo $screen["screening_times"][0]["key"];?>"><?php echo $screen["screening_film_name"];?></a></span>
									    <span class="upcoming_title uppercase"><a href="/theater/<?php echo $screen["screening_times"][0]["key"];?>">Next Showtime</a></span>
									    <span class="upcoming_time uppercase"><a href="/theater/<?php echo $screen["screening_times"][0]["key"];?>"><?php echo formatDate($screen["screening_times"][0]["date"],"daytimeplus");?></a></span>
									</span>
								
								</li>
                <?php $i++; } ?>
           </ul>

        </div>

        <div class="thslide_nav_next"><a href="#">&nbsp;</a></div>
        
        <div class="thslide_hidden">
          <ul>
            <?php $i=1;foreach ($screenings as $screen){?>
            <li>
              <a id="screening_hidden_<?php echo $i;?>" href="#" class="thslide_hidden_item">
                <img src="/images/spacer.gif" height="1" width="1" />
              </a>
            </li>                        
            <?php $i++; } ?>
           </ul>
         </div>
         
    </div>

</div>

<div id="barousel_running" class="reqs">1</div>
<a id="barousel_broadcast" class="reqs" href="#"></a>
