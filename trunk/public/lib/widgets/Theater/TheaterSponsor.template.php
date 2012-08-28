<!-- START MAIN CONTENT -->
<div id="prescreening_panel_main" <?php if (! isset($auth_msg)){ echo 'class="movietime"'; }?>>
	<div class="pre_image">
		<img class="poster" src="/uploads/screeningResources/<?php echo $film["screening_film_id"];?>/logo/small_poster<?php echo $film["screening_film_logo"];?>" alt="<?php echo $film["screening_film_name"];?>" class="widget_video_still"  border="0"/>
	</div>
	<?php if ((strtotime($film["screening_date"])) < (strtotime( now()))) {?>
	<div id="in_progress">
		<img src="/images/alt1/in_progress_theater.png" border="0" />
	</div>
	<?php } ?>
	
	<?php if ($film_start_time > $film_end_time  - 1950) {?>
	<div id="in_progress">
		<img src="/images/alt1/completed_theater.png" border="0" />
	</div>
	<?php } ?>
	
	<div class="pre_content">
	  <table class="step_one" cellpadding="0" cellspacing="0" border="0">
	  	<tr>
	  		<td>
	  			<h5>
	  				<?php echo $film["screening_film_name"];?>
	  			</h5>
	  		</td>
	  	</tr>
	  	<?php if ($film["screening_user_full_name"] != '') {?>
			<tr height="20">
	  		<td valign="top">
	  			<span class="pre_by">
	  			Hosted By
	  			</span>
	  		</td>
	  	</tr>
	  	<tr>
	  		<td height="40" valign="top">
	  			<span class="pre_host">
	  				<?php echo $film["screening_user_full_name"];?>
	  			</span>
	  		</td>
	  	</tr>
	  	<?php } ?>
	  	<tr>
	  		<td valign="top">
	  			<span class="pre_info">
		  			<?php echo $film["screening_film_info"];?>
	  			</span>
	  			<span class="pre_director">
	  				Directed by <?php echo $film["screening_film_makers"];?>
	  			</span>
				</td>
	  	</tr>
	  </table>
  </div>
  
  <?php if ($film["screening_film_use_sponsor_codes"] == 1) {?>
		<!-- PURCHASE POPUPS -->
	  <?php include_component('default', 
	                          'PurchaseSponsorAlt', 
	                          array('film'=>$film,
	                                'states'=>$states,
																	'countries'=>$countries,
																	'post'=>$post))?>
	  <!-- PURCHASE POPUPS -->
	<?php } ?>
	
	<?php if ($sf_user -> isAuthenticated()) {?>
	
		<?php if ($film["screening_film_use_sponsor_codes"] == 0) {?>
	  <!-- PURCHASE POPUPS -->
	  <?php include_component('default', 
	                          'PurchaseAlt', 
	                          array('film'=>$film,
	                                'states'=>$states,
																	'countries'=>$countries,
																	'post'=>$post))?>
	  <!-- PURCHASE POPUPS -->
		<?php } ?>
		
		<div id="screening" style="display:none"><?php echo $film["screening_unique_key"];?></div>
	  <div id="time_<?php echo $film["screening_unique_key"];?>" style="display:none"><?php echo formatDate($film["screening_date"],"prettyshort");?></div>
	  <div id="cost_<?php echo $film["screening_unique_key"];?>" style="display:none"><?php echo $film["screening_film_ticket_price"];?></div>
	  <div id="host_<?php echo $film["screening_unique_key"];?>" style="display:none"><?php echo $film["screening_user_full_name"];?></div>
		
		<div id="gbip" style="display:none"><?php echo $gbip;?></div>
		<div id="host_cost" style="display:none"><?php echo $film["screening_film_setup_price"];?></div>
		<div id="ticket_cost" style="display:none"><?php echo $film["screening_film_ticket_price"];?></div>
		<div id="domain" style="display:none"><?php echo sfConfig::get("app_domain");?></div>
		<div id="film" style="display:none"><?php if (isset($film["screening_film_id"])) {echo $film["screening_film_id"];}?></div>
		<?php if ($film["screening_film_allow_hostbyrequest"] == 1) {?>
		  <div id="dohbr_ticket_price" style="display:none"><?php if (isset($film["screening_film_hostbyrequest_price"])) {echo $film["screening_film_hostbyrequest_price"];}?></div>
		<?php } ?>
		
	<?php } ?>
	
	<?php if (isset($auth_msg)) { ?>
  <div class="prescreen_notice">
		You're in the theater! You'll be able to chat with everyone else before and during the movie, but make sure you buy a ticket before showtime or you won't be able to watch the movie!
	</div>
	
	<div class="prescreen_purchase">
		<a href="javascript: void(0)" onclick="screening_room.pay()">
		<?php if ($film["screening_film_use_sponsor_codes"] == 1) {?>
			<img src="/images/alt1/theater_enter_code.png" />
		<?php } else { ?>
			<img src="/images/alt1/theater_buy_ticket.png" />
		<?php } ?>
		</a>
	</div>
	<?php } else {?>
	<div class="prescreen_notice">
		You're in the theater! You'll be able to chat with everyone else before and during the movie. Share your mood with the "Color Me" feature below.
	</div>
	
	<?php } ?>

  <!--
	<?php if ($film["screening_user_id"] > 0) {?>
    <?php if ($film["screening_guest_image"] != '') {
    if (left($film["screening_guest_image"],4) == "http") {?>
      <img class="host" height="141" src="<?php echo $film["screening_guest_image"];?>" alt="host photo" />
    <?php } else { ?>
      <img class="host" height="141" src="/uploads/hosts/<?php echo $film["screening_guest_image"];?>" alt="host photo" />
    <?php }} elseif ($film["screening_still_image"] != '') {?>
      <img class="host" height="141" src="/uploads/screeningResources/<?php echo $film["screening_film_id"];?>/screenings/film_screening_large_<?php echo $film["screening_still_image"];?>" />			
    <?php } else {?>
      <img class="host" height="141" src="/images/constellation_host.jpg" />			
    <?php } ?>
  <?php  }?>
	-->
</div>
<!-- END MAIN CONTENT -->

<!-- START HOWTO POPUP -->
<div id="how-to-popup" style="display: none">
  <h2>How To View This Video</h2>
  <h4>HIDE</h4>  	
  <ol>  		
    <li>Click the 
    <img class="img-align" src="/images/icon-expand.png" alt=" " /> in the bottom left hand corner of this window to access the menu. From the menu you can chat with others in the theater, access the Q+A, and get info about the film or the host.
    </li>  		
    <li>Click on the 
    <img class="img-align" src="/images/icon-constellation.png" alt = "" /> and a map of stars should appear. Each star is a person in the theater. You're the red star. You are connected by lines to any friends you invited who attended the screening. Click on a star and a chat box will appear allowing you to talk directly to that person.
    </li>  		
    <li>Q+A: If your host is leading a Q+A, the Q+A button will flash when the host's webcam goes live. Click on it and you will see the host and a box to ask them a question. You only get 5 questions so choose wisely! To leave the Q+A, click on any other menu button.
    </li>  		
    <li>VIDEO: To make the movie full screen, click on the 
    <img src="/images/icon_fullscreen_fp.png" alt=" " />  image in the lower right hand corner of the movie.
    </li>  		
    <li>SOUND: To mute the film or adjust the volume, click on the 
    <img src="/images/icon_sound_fp.png" alt=" " /> button to the left of the full-screen button or click anywhere on the picture.
    </li>  	
  </ol>    
</div>
<!-- END HOWTO POPUP -->

<?php if (! isset($auth_msg)) { ?>
<div id="video_panel" class="theater_panel panel" style="display: none">	
		<?php if (now() < $film_end_time  - 150) {?>
    <div id="video_stream" class="nx_widget_player">
      <div id="movie_stream" class="nx_widget_player">
      	<div class="screening_wrapper" style="display: none;">
        	<div class="screening_still" style="position: absolute; z-index: 10000; float: left;">
          <p><strong>You don't have the correct version of Adobe Flash installed, and cannot watch films on Constellation.TV <br />
					without Adobe Flash version 10.2 or newer. </strong><br /><br /></p>
					<p>Please download it at <br />
					<a href="http://get.adobe.com/flashplayer">http://get.adobe.com/flashplayer</a> <br />
					and return to watch this film.<br /><br /></p>
          <p><img src="http://wwwimages.adobe.com/www.adobe.com/images/shared/product_mnemonics/165x165/flashplayer_165x165.png" /></p>
          <!--<img src="/uploads/screeningResources/<?php echo $film["screening_film_id"];?>/still/<?php echo $film["screening_film_still_image"];?>" alt="<?php echo $film["screening_film_name"];?>" class="widget_video_still" border="0" width="480" height="320" />-->
        	</div>
				</div>
				<span class="reqs" id="video">/services/Tokenizer/<?php echo $film["screening_film_id"];?>/map.smil</span>
        <span class="reqs" id="video_stream"></span>
        <span class="reqs" id="video-autoplay">true</span>
        <span class="reqs" id="video-port">45907</span>
        <span class="reqs" id="video-type">VOD</span>
      </div>
    </div>
    <?php } ?>
</div>
<!-- END SCREENING PANEL -->

<?php } else { ?>
  <div id="noauth"></div>
  <?php if (($auth_display) && ($film["screening_film_use_sponsor_codes"] != 1)) {?>
	<script type="text/javascript">
    $(document).ready(function() {
      login.showpopup();
    });
  </script>
  <?php } ?>
<?php }?>

<!-- START SCREENING ENDED PANEL -->
<div id="screening-ended-holder" class="theater_panel" style="display: none">  	
  <p id="screening-ended-message">This screening has now ended. Please feel free to review or recommend this movie to your friends!</p>  
</div>
<!-- END SCREENING ENDED PANEL -->

<?php if ($sf_user -> isAuthenticated()) {?>
<!-- INVITE POPUP -->
<?php include_component('default', 
                          'TheaterInvites', 
                          array('film'=>$film,
                          			'oi_services'=>$oi_services))?>

<!-- END INVITE POPUP -->
<?php } ?>

<div class="popup" id="preview-invite-popup" style="display: none">		
  <div class="close-bar">			
    <a id="close-preview-invite-popup">(close)</a>		
  </div>		
  <div class="invite-content">		
  </div>	
</div>

<div class="popup" id="inviting-popup" style="display: none">	
  <p align="center">Sending Invitations...<br />
  <img src="/images/ajax-loader.gif" alt="loading" />	
  </p>
</div>
