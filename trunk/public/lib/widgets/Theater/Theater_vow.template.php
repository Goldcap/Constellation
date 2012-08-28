<?php
	$isLogedIn = $sf_user -> isAuthenticated();
	$hasTicket = false;
	if (time() < $film_end_time - 300) {
		if (! isset($auth_msg)) {
			$hasTicket = true;
		}
	}
?>
<link href="/css/pages/thevow.css" rel="stylesheet" />
<?php if($hasTicket):?>
<div class="theater-block theater-vow-ticket-holder">
	<img src="/images/ticket-icon.png" class="theater-vow-ticket-holder-icon" /> Congratulations! You Have a Ticket.
</div>
<?php endif;?>

<?php if($hasTicket) {?> 
<div id="prescreening_panel_main" <?php if (! isset($auth_msg)){ echo 'class="movietime"'; }?>>
	<?php if ($film_start_time > $film_end_time  - 130) :?>
	<div id="in_progress">
		<img src="/images/alt1/completed_theater.png" border="0" />
	</div>
	<?php elseif ((strtotime($film["screening_date"])) < (strtotime( now()))) :?>
	<div id="in_progress">
		<img src="/images/alt1/in_progress_theater.png" border="0" />
	</div>
	<?php endif; ?>
	<div class="pre_image">
		<img class="poster" src="/images/pages/thevow/channing-tatum-profile-theater.png" class="widget_video_still"  border="0"/>
	</div>
	<div class="lobby-details">
		<h1 class="title">An Evening of Vows</h1>
		<p class="host"><span class="uppercase">Hosted by</span>Channing Tatum</p>
								<div style="margin-bottom:20px">
			                <span class="button button_gray button_invite_email" onclick="invite.invite('screening','thevowevent');"><span class="icon_email"></span> Invite Friend By Email</span>
			                <span class="button button_faceblue button_invite_facebook" onclick="fb_invite.invite('screening','thevowevent');"><span class="icon_facebook"></span> Invite Facebook friends</span>
			            </div>
		<p class="description"><strong>The Vow</strong> star Channing Tatum will host a live interactive online event that may include the story of your vow. Channing will host the screening of fan-submitted vows and special clips from the upcoming movie.</p>

	</div>
</div>

<div class="pops share_container vow-share-lb clearfix" style="display:none">
  <h4>Share Your Vow</h4>
    <div class="vow_upload_content">
	<p class="details">Upload a video file or picture that captures a special vow you have made. Channing Tatum will present a selection of these submissions during the live event.</p>
		<form onsubmit="return vowshare.doSubmit()" action="/vow" method="POST">
		<div class="form_row">
			<div class="vow_error"></div>
        	<div id="user_image_original_wrapper" class="preview" style="display:none">
         		<img name="vow_element_preview" id="vow_element_preview" src="/images/alt1/logo-fb.png" width="150" />
			</div>
            <label class="uppercase label">Upload File or Video</label>
			<p class="vow-sublabel">(File size should be less than 20 MB)

			<div id="vow-input">
				<div>
					<div class="swfuploader" id="FORM_vow_element_original">
						<input type="file" name="FILE_vow_element" id="FILE_vow_element_original">
					</div>
				</div>					
			</div> 
		</div>			
  	<div class="form_row">
          <label for="vow_element"  class="uppercase label">Please describe your vow in 500 words or less.</label>
          <textarea id="vow_element" name="vow_element"></textarea>
      </div>
      <div class="form_row clearfix">
          <span class="button_light button uppercase right" onclick="$('.modal').click();">Cancel</span>
          <button class="button_pink button uppercase right" type="submit">Submit</button>
      </div>
  </form>
  </div>
  <div class="vow_upload_success" style="display: none">
  		<h3>Thank you, your vow has been uploaded. </h3>
  		<p>Reserve your free ticket and attend the screening to see if Channing Tatum has selected your vow to share.</p>
  		  		<p>
				<a target="_blank" href="http://www.facebook.com/dialog/feed?app_id=185162594831374&link=http%3A%2F%2F2Fwww.constellation.tv%2Fthevow%23p%3D1&picture=http%3A%2F%2Fs3.amazonaws.com%2Fcdn.constellation.tv%2Fprod%2Fimages%2Fpages%2Fthevow%2Fchanning-tatum-film-profile.png&caption=Channing%20Tatum%20Hosts%20An%20Evening%20of%20Vows%20%40%20Constellation.tv%2Fthevow&description=%23The%20Vow%20-%20I%20just%20shared%20my%20vow&redirect_uri=http%3A%2F%2Fwww.constellation.tv%2Fthevow%23p%3D1" class="share-facebook"><span class="share-facebook-icon"></span>Share on Facebook</a>
				<a  target="_blank" href="http://twitter.com/intent/tweet?text=%23The+Vow+-+I+just+shared+my+vow%20-&url=http%3A%2F%2Fwww.constellation.tv%2Fthevow" class="share-twitter"><span class="share-twitter-icon"></span>Share on Twitter</a>
			</p>
  		<p><span class="close-share-vow button_small" onclick="$('.modal').trigger('click');">Close</span></p>
  </div>  
</div>

<?php } else { ?>
<div id="prescreening_panel_main" <?php if (! isset($auth_msg)){ echo 'class="movietime"'; }?>>
	<?php if ($film_start_time > $film_end_time  - 130) :?>
	<div id="in_progress">
		<img src="/images/alt1/completed_theater.png" border="0" />
	</div>
	<?php elseif ((strtotime($film["screening_date"])) < (strtotime( now()))) :?>
	<div id="in_progress">
		<img src="/images/alt1/in_progress_theater.png" border="0" />
	</div>
	<?php endif; ?>
	<div class="pre_image">
		<img class="poster" src="/images/pages/thevow/channing-tatum-profile-theater.png" class="widget_video_still"  border="0"/>
	</div>
	<div class="lobby-details">
		<form method="POST" action="/vow" name="purchase_form" id="purchase_form" onsubmit="return false;">
		<h1 class="title">An Evening of Vows</h1>
		<p class="host"><span class="uppercase">Hosted by</span>Channing Tatum</p>
		<p class="description"><strong>The Vow</strong> star Channing Tatum will host a live interactive online event that may include the story of your vow. Channing will host the screening of fan-submitted vows and special clips from the upcoming movie Tickets to this online event are free. </p>
		<p class="description">The hour-long live interactive event will take place on <strong><?php echo date("l, F jS Y",strtotime('2012-02-09 20:00:00 EST'))?>
						 at <?php echo date("g A T",strtotime('2012-02-09 20:00:00 EST')) ;?></strong>.</p>
		<?php if ($sf_user -> isAuthenticated()) {?>
		<button id="open_link" class="button_orange button uppercase right" onclick="vowPurchase.open()">Get Your Free Ticket</button>
		<?php } else {?>
		<button id="open_link" class="button_orange button uppercase right" onclick="screening_room.pay()">Get Your Free Ticket</button>
		<?php } ?>		
		</form>
	</div>
</div> 
<?php } ?>

<?php if (time() < $film_end_time - 300) {
if (($sf_user -> isAuthenticated()) && (! $hasTicket)) {?>
<div id="vow-pruchase-lb" class="pops vow-purchase-lb clearfix" style="display:none">
  <h4>Reserve Your Ticket</h4>
  <div class="vow-purchase-poster">
  	<img src="/images/pages/thevow/channing-tatum-film-profile.png" />
  </div>
  <div class="vow-purchase-details">
		<form method="POST" action="/vow" name="purchase_form" id="purchase_form" onsubmit="return false;">
	    <p class="title">An Evening of Your Vows</p>
	    <p class="host">Hoted by: <strong>Channing Tatum</strong></p>
	    <p class="pricing">Date: <strong><?php echo date("l, F jS Y",strtotime('2012-02-09 20:00:00 EST'))?>
						 at <?php echo date("g A T",strtotime('2012-02-09 20:00:00 EST')) ;?></strong></p>
	    <p class="pricing">Price: <span class="price uppercase">FREE</span></p>
    	<p class="details"><strong>The Vow</strong> star Channing Tatum will host a live interactive online event that may include your story.  Channing will host the screening of fan-submitted "Vows" spliced together with special clips from the upcoming movie.</p>
		  <?php if ($facebook) {?>
			<div class="form_row clearfix">
				<input type="checkbox" checked id="vow-purchase-facebook" name="post_facebook" value="true" /><label for="vow-purchase-facebook" class="">Post on Facebook Wall</label>
			</div>
			<?php } ?>
			<div class="form_row clearfix">
				<button id="purchase_submit" class="button_orange button uppercase right" onclick="screening_room.pay()">Reserve Your Ticket</button>
			</div>
			
			<input type="hidden" id="ticket_price" name="ticket_price" value="0" />
			<input type="hidden" id="film_free_screening" name="film_free_screening" value="1" />
			<input type="hidden" id="email" name="email" value="<?php echo $sf_user -> getAttribute("user_email");?>" />
			<input type="hidden" id="username" name="username" value="<?php echo $sf_user -> getAttribute("user_username");?>" />
		 </form>
	</div>
</div>
<script>
$(document).ready(function(){
	vowPurchase.init();
	vowPurchase.open();
});
</script>
	
<!--<div id="screening" style="display:none"><?php echo $film["screening_unique_key"];?></div>
<div id="time_<?php echo $film["screening_unique_key"];?>" style="display:none"><?php echo formatDate($film["screening_date"],"prettyshort");?></div>
<div id="cost_<?php echo $film["screening_unique_key"];?>" style="display:none"><?php echo $film["screening_film_ticket_price"];?></div>
<div id="host_<?php echo $film["screening_unique_key"];?>" style="display:none"><?php echo $film["screening_user_full_name"];?></div>-->
	
<div id="gbip" style="display:none"><?php echo $gbip;?></div>
<div id="host_cost" style="display:none"><?php echo $film["screening_film_setup_price"];?></div>
<div id="ticket_cost" style="display:none"><?php echo $film["screening_film_ticket_price"];?></div>
<div id="domain" style="display:none"><?php echo sfConfig::get("app_domain");?></div>

<?php }} ?>


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
        	</div>
				</div>
				<span class="reqs" id="video">/services/Tokenizer/<?php echo $film["screening_film_id"];?>/map.smil</span>
      </div>
    </div>
    <?php } ?>
</div>
<!-- END SCREENING PANEL -->

<?php } else { ?>
  <div id="noauth"></div>
  <?php if (($auth_msg == "Please login or sign-up to view this screening.") && ($auth_display) && ($film["screening_film_use_sponsor_codes"] != 1)) {?>
	<script type="text/javascript">
    /*
		$(document).ready(function() {
      login.showpopup();
    });
    */
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
<div class="pop_up" id="screening_invite" style="display: none"></div>
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

<div id="session_id" class="reqs"><?php echo session_id();?></div>
<div id="film" class="reqs">93</div>
<div id="screening" class="reqs">thevowevent</div>
<div id="userid" class="reqs"><?php if (($sf_user -> isAuthenticated()) && ($sf_user["user_id"] > 0)) {echo $sf_user["user_id"];} else { echo 0;}php?></div>

<!-- HOME INVITE -->
<?php include_component('default', 
                        'InviteAlt')?>
<!-- HOME INVITE -->
