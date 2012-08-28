<?php 
$ttz = date_default_timezone_get();
$zones = zoneList(); 
?>

<?php 
if ($film["film_splash_image"] != ''){?>
<style>
.marqee {
	background: #000000 url("/uploads/screeningResources/<?php echo $film["film_id"];?>/background/<?php echo $film["film_splash_image"];?>") no-repeat center top !important;
}
</style>
<?php } ?>

<?php 
if ((isset($film["program_background_image"])) && ($film["program_background_image"] != '')){?>
<!--http://s3.amazonaws.com/cdn.constellation.tv/prod-->
<style>
.marqee {
	background: url('/images/alt1/jig_banner.jpeg') top left no-repeat;
/*background: #000000 url("/uploads/programResources/<?php echo $film["program_id"];?>/background/<?php echo $film["program_background_image"];?>") no-repeat center top !important;*/
}
</style>
<?php } ?>


<div class="marqee">
	<div class="marqee_moon">	
		<div id="movie_trailer_container" class="nx_widget_video"></div>
	  <span class="reqs" id="video"><?php echo str_replace(array("rtmp://cp113558.edgefcs.net/ondemand/","?"),array("","%3F"),$film["stream_url"]);?></span>
	  <span class="reqs" id="video_still">/uploads/screeningResources/<?php echo $film["film_id"];?>/still/<?php echo $film["film_still_image"];?></span>
	  
	  <?php if ($film["film_use_sponsor_codes"] == 1) {?>
	  	<div class="const_info"><strong>Constellation</strong> is the first global movie theater - for first run films and special presentations of films you love.<br /><br />Choose a showtime you want to attend, redeem your code, and enjoy the film!</div>
	  <?php } else {?>
			<div class="const_info"><strong>Constellation</strong> is the first global movie theater for first run films and special presentations of films you love.<br /><br />Choose a showtime you want to attend, purchase your ticket, and enjoy the show with friends.</div>
		<?php } ?>
		
		<div class="watch_trailer">&nbsp;</div>
		<div class="get_info"><?php echo substr($film["film_info"],0,440);?><br /><br /><!--<a>Read more >></a>--></div>
  </div>
</div>

<div class="info">
	
	<div class="info_moon">
		<div class="share">
			<div>
			<div id="fb-root"></div><script src="https://connect.facebook.net/en_US/all.js#appId=135843816505294&amp;xfbml=1"></script><fb:like href="" send="true" layout="button_count" width="100" show_faces="false" font="arial"></fb:like>
			<a href="https://twitter.com/share?url=<?php echo ('https://'.sfConfig::get("app_domain").$_SERVER["REQUEST_URI"]);?>&text=<?php echo "View '".$film["film_name"]."' on Constellation.tv: ".strip_tags($film["film_synopsis"]);?>" class="twitter-share-button" data-count="horizontal" data-counturl="<?php echo ('https://'.sfConfig::get("app_domain").$_SERVER["REQUEST_URI"]);?>" data-via="#">Tweet</a>
			</div>
		</div>
	  
	  <div class="attend">
	  </div>
	  
	  <?php if ($film["film_allow_hostbyrequest"] == 1) {?>
		<div class="watchbyrequest" id="hbr_request_button">
		</div>
		<?php } ?>
	</div>
	
</div>
  
<!-- SCREENINGLLIST -->
<?php include_component('default', 
                        'CarouselAlt', 
                        array('screenings'=>$next,'title'=>'NEXT SHOWTIME','single'=>true,'pred'=>'next'))?>
<!-- SCREENINGLLIST -->
  
<!-- SCREENING CAROUSEL -->
<?php include_component('default', 
                        'CarouselAlt', 
                        array('screenings'=>$carousel,'title'=>'FEATURED SHOWTIMES','pred'=>'featured'))?>
<!-- SCREENING CAROUSEL -->
  
<!-- SCREENINGLLIST -->
<?php include_component('default', 
                        'CarouselAlt', 
                        array('screenings'=>$screenings,'title'=>'ALL SHOWTIMES','pred'=>'all'))?>
<!-- SCREENINGLLIST -->
  

<div id="host" class="reqs"><?php echo $chat_instance_host;?></div>
<div id="port" class="reqs"><?php echo $chat_instance_port_base;?></div>

<!-- HOST POPUPS -->
<?php include_component('default', 
                        'HostFilmAlt', 
                        array('sf_user'=>$sf_user,
															'film'=>$film,
                              'states'=>$states,
															'countries'=>$countries,
															'post'=>$post))?>
<!-- END HOST POPUPS -->

<?php if ($film["film_use_sponsor_codes"] == 1) {?>
<?php include_component('default', 
                        'PurchaseFilmSponsorAlt', 
                        array('sf_user'=>$sf_user,
															'film'=>$film,
                              'states'=>$states,
															'countries'=>$countries,
															'post'=>$post))?>
<!-- END PURCHASE POPUPS -->
<?php } else { ?>
<!-- PURCHASE POPUPS -->
<?php include_component('default', 
                        'PurchaseFilmAlt', 
                        array('sf_user'=>$sf_user,
															'film'=>$film,
                              'states'=>$states,
															'countries'=>$countries,
															'post'=>$post))?>
<!-- END PURCHASE POPUPS -->
<?php } ?>

<!-- WBR POPUPS -->
<?php include_component('default', 
                        'WBRFilmAlt', 
                        array('sf_user'=>$sf_user,
															'film'=>$film))?>
<!-- END WBR POPUPS -->

<?php if ($sf_user -> isAuthenticated()) {?>
<!-- GEOIP WARNING POPUP -->
<?php include_component('default', 
                        'GEOIP')?>
<!-- END GEOIP WARNING POPUP -->
<?php } ?>

<!-- INVITES POPUP -->
<?php include_component('default', 
                        'Invites')?>
<!-- END INVITES POPUP -->

<div id="gbip" style="display:none"><?php echo $gbip;?></div>
<div id="host_cost" style="display:none"><?php echo $film["film_setup_price"];?></div>
<div id="ticket_cost" style="display:none"><?php echo $film["film_ticket_price"];?></div>
<div id="domain" style="display:none"><?php echo sfConfig::get("app_domain");?></div>
<div id="film" style="display:none"><?php if (isset($film["film_id"])) {echo $film["film_id"];}?></div>
<div id="film_start_offset" style="display:none"><?php echo $film_start_offset;?></div>
<div id="film_end_offset" style="display:none"><?php echo $film_end_offset;?></div>
<div id="screening" style="display:none"><?php if (isset($screening)) {echo $screening;}?></div>
<div id="current_date" style="display:none"></div>
<div id="thistime" style="display:none"><?php if (isset($thistime)) {echo $thistime;}?></div>
<?php if ($film["film_allow_hostbyrequest"] == 1) {?>
  <div id="dohbr_ticket_price" style="display:none"><?php if (isset($film["film_hostbyrequest_price"])) {echo $film["film_hostbyrequest_price"];}?></div>
<?php } ?>

