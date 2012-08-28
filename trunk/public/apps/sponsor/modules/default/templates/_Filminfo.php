<h4><?php echo $film["film_name"];?></h4>
<div class="movie_poster">
<img class="poster" src="/uploads/screeningResources/<?php echo $film["film_id"];?>/logo/poster<?php echo $film["film_logo"];?>" alt="<?php echo $film["film_name"];?>" class="widget_video_still"  border="0"/>	</div>
<span class="arrow"><a href="#" id="start_trailer">Play Trailer</a></span>

<p><strong>About the Film</strong>
<?php echo preg_replace("/^<p>/","<p class='friggin_squished'>",WTVRCleanString($film["film_info"]));?></p>

<?php if (count($film["film_directors"])) {?>
<p><strong>Director(s)</strong>
<?php foreach ($film["film_directors"] as $auser) {echo linkSplit($auser)."<br />";}?>
</p>
<?php }?>

<?php if (count($film["film_executive_producers"])) {?>          
<p><strong>Executive Producer(s)</strong>
  <?php foreach ($film["film_executive_producers"] as $auser) {echo linkSplit($auser)."<br />";}?>
</p>         
<?php }?>                   

<?php if (count($film["film_co-executive_producers"])) {?>          
<p><strong>Co-Executive Producer(s) </strong>
<?php foreach ($film["film_co-executive_producers"] as $auser) {echo linkSplit($auser)."<br />";}?>
</p>         
<?php }?>                   

<?php if (count($film["film_producers"])) {?>          
<p><strong>Producer(s)</strong>
<?php foreach ($film["film_producers"] as $auser) {echo linkSplit($auser)."<br />";}?>
</p>         
<?php }?>
                  
<?php if (count($film["film_co-producers"])) {?>          
<p><strong>Co-Producer(s)</strong>
<?php foreach ($film["film_co-producers"] as $auser) {echo linkSplit($auser)."<br />";}?>
</p>         
<?php }?>                   

<?php if (count($film["film_associate_producers"])) {?>          
<p><strong>Associate Producer(s)</strong>
<?php foreach ($film["film_associate_producers"] as $auser) {echo linkSplit($auser)."<br />";}?>
</p>         
<?php }?>

<?php if (count($film["film_writers"])) {?>          
<p><strong>Writer(s)</strong>
<?php foreach ($film["film_writers"] as $auser) {echo linkSplit($auser)."<br />";}?>
</p>         
<?php }?>
     
<?php if (count($film["film_director_of_photography"])) {?>          
<p><strong>Director(s) of Photography</strong>
<?php foreach ($film["film_director_of_photography"] as $auser) {echo linkSplit($auser)."<br />";}?>
</p>         
<?php }?>                   

<?php if (count($film["film_actors"])) {?>          
<p><strong>Actor(s)</strong>
<?php foreach ($film["film_actors"] as $auser) {echo linkSplit($auser)."<br />";}?>
</p>         
<?php }?>                   

<?php if (count($film["film_music"])) {?>          
<p><strong>Music</strong>
<?php foreach ($film["film_music"] as $auser) {echo linkSplit($auser)."<br />";}?>
</p>         
<?php }?>                   

<?php if (count($film["film_supported"])) {?>          
<p><strong>Supporting Cast and Crew</strong>
<?php foreach ($film["film_supported"] as $auser) {echo linkSplit($auser)."<br />";}?>
</p>         
<?php }?> 

<p><strong>Production Company</strong>
<?php echo $film["film_production_company"];?>
</p>	

<div id="trailer-popup" class="pop_up" style="display: none">
  <div class="pop_mid">
  <div class="pop_top carousel-popup-close"><a href="#">close</a></div>
  	<div class="layout-1 clearfix">
      <div id="movie_trailer_container" class="nx_widget_video">	    
        
      </div>
      <span class="reqs" id="video"><?php echo $film["stream_url"];?></span>
      <span class="reqs" id="video-autoplay">true</span>
      <span class="reqs" id="video-type">TRAILER</span>
      <span class="reqs" id="trailer-image">/uploads/screeningResources/<?php echo $film["film_id"];?>/still/<?php echo $film["film_logo"];?></span>
    </div>
  <div class="pop_bot"></div>
  </div>    
</div>
