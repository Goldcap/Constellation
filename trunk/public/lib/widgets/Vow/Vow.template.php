<?php 
if (isset ($form) ) {echo $form;}
$isLoggedIn = false;
if (($sf_user -> isAuthenticated()) && ((is_null($sf_user -> getAttribute("temp"))) || (! $sf_user -> getAttribute("temp")))){
	$isLoggedIn = true;
}
$imageUrl = '/images/icon-custom.png';
if ($sf_user -> getAttribute('user_image') && (left($sf_user -> getAttribute('user_image'),4) == "http")) {
	$imageUrl = $sf_user -> getAttribute('user_image');
} elseif($sf_user -> getAttribute('user_image')) {
	$imageUrl = '/uploads/hosts/'. $sf_user -> getAttribute('user_id') .'/'. $sf_user -> getAttribute('user_image');
} 
?>
				<?php if ($isLoggedIn) :?>

<div class="hero_block" onclick="vowPurchase.open()" >
<?php elseif ($isLoggedIn) :?>
<div class="hero_block" onclick="vowPurchase.open()" >
						<?php else :?>
						<div class="hero_block" onclick="login.showpopup()" >

						<?php endif;?>

	<img src="/images/pages/thevow/the-vow-hero.jpg" />
	<div class="hero_block_side">
		<p>An interactive online event with Channing Tatum.</p>
		<p class="hero-button-wrap">
			<span class="button button_pink uppercase share-vow">Reserver your ticket</span>
			<?php /*if ($isLoggedIn) :?>
			<span class="button button_pink uppercase share-vow">Share your Vow</span>
			<?php else :?>
			<span class="button button_pink uppercase" onclick="login.showpopup()">Share your Vow</span>
			<?php endif;*/?>
		</p>

	</div>
		<div style="position: absolute; left: -10px; top: 20px; width: 400px; height: 80px; background: url(/images/pages/thevow/happening.png"></div>
</div>

<div id="content">
	<div class="inner_container clearfix">
		<div class="grid-block-2-3 left">
			<div class="top"></div>
			<div class="content clearfix vow-fixed-height-block">
				<h4>Channing Tatum Hosts <strong>An Evening of Vows</strong></h4>
					<div class="grid-block-poster">
						<img src="/images/pages/thevow/channing-tatum-film-profile.png"  />
					</div>
					<div class="grid-block-film-details">
						<p><strong>The Vow</strong> star Channing Tatum will host a live interactive online event that may include the story of your vow. Channing will appear live via web-cam presenting an online movie screening of fan-submitted vows, along with special clips from the movie. Tickets to this online event are free. </p>

						<p>The hour-long live interactive event will take place on <strong><?php echo date("l, F jS Y",strtotime('2012-02-09 20:00:00 EST'))?>
						 at <?php echo date("g A T",strtotime('2012-02-09 20:00:00 EST')) ;?></strong>.</p>
						<?php if ($hasTicket) :?>
							<p class="grid-button-wrap"><a class="button button_green uppercase" href="/theater/thevowevent" style="display: inline-block">Enter Theater</a>						
						<?php elseif ($isLoggedIn) :?>
							<p class="grid-button-wrap"><span class="share-vow button button_pink uppercase" >Reserve Free Ticket</span>
						<?php else :?>
							<p class="grid-button-wrap"><span class="button button_pink uppercase" onclick="login.showpopup()">Reserve Free Ticket</span>
						<?php endif;?>
						</p>
					</div>
			</div>
			<div class="bottom"></div>
		</div>

		<div class="grid-block-1-3 right">
			<div class="top"></div>
			<div class="content clearfix vow-fixed-height-block">
			<h4>How To Share Your Vow</h4>
			<ul class="vow-bullit">
				<li class="clearfix"><span class="vow-bullit-circle">1</span><div>Make a video or take a picture that captures your vow, or a promise you made to a loved one.</div></li>
				<li class="clearfix"><span class="vow-bullit-circle">2</span><div>Describe your vow in 500 words or less.</div></li>

			</ul>
			<p><strong>Submission Process is Closed.</strong></p>

						<div style="margin-top: 20px; text-align:center">
			                <span class="button button_gray button_invite_email" onclick="invite.invite('screening','thevowevent');" style="margin-bottom: 10px;"><span class="icon_email"></span> Invite Friend By Email</span>
			                <span class="button button_faceblue button_invite_facebook" onclick="fb_invite.invite('screening','thevowevent');"><span class="icon_facebook"></span> Invite Facebook friends</span>
			            </div>
			</div>
			<div class="bottom"></div>
		</div>
    
    <div class="grid-block-2-3 left">
			<div class="top"></div>
			<div class="content clearfix">
				<h4>Conversation</h4>
				<div class="comment-box clearfix" id="comment-box">
				<?php if ($isLoggedIn) :?>
 					<img src="<?php echo $imageUrl; ?>" width="50" height="50" class="comment-avatar comment-box-image"/>
					<textarea class="comment-box-field" placeholder="Add your voice to the conversation!"></textarea>
					<span class="button button_orange_medium uppercase">post</span>
				<?php else: ?>
					<a href="javascript:void(0)" onclick="login.showpopup()">You must be logged in to post.</a>
				<?php endif;?>
				</div>
				<ul class="comment-list" id="comment-list"></ul>
				<div class="comment-pagination" id="pagination">
					<ul></ul>
				</div>
			</div>

			
			<div class="bottom"></div>
		</div>

    <div class="grid-block-1-3 right">
			<div class="top"></div>
			<div class="content clearfix">
			<h4>Shared Vows</h4>
			<ul class="vow-bullit vow-item" id="vow-bullit"></ul>
				<div class="comment-pagination" id="vow-pagination">
					<ul></ul>
				</div>
      </div>
      <div class="bottom"></div>
		</div>    

		<div class="vow-twitter-block right">
			<h4>#TheVow on Twitter</h4>
			<div id="twitter"></div>
		</div>
		<div class="grid-block-2-3 left">
			<div class="top"></div>
			<div class="content clearfix">
				<h4>Official Media</h4>
				<ul class="list-inline">
					<li><a href="http://www.thevow-movie.com/" target="_blank"><span class="icon-social-site"></span>thevow-movie.com/</a></li>
					<li><a href="https://www.facebook.com/TheVow" target="_blank"><span class="icon-social-facebook"></span>facebook.com/TheVow</a></li>
					<li><a href="http://twitter.com/#!/thevowmovie" target="_blank"><span class="icon-social-twitter"></span>twitter.com/thevowmovie</a></li>
				</ul>
			</div>
			<div class="bottom"></div>
		</div>

	</div>
</div>

<div id="vow-pruchase-lb" class="pops vow-purchase-lb clearfix" style="display:none">
    <h4>Reserve Your Free Ticket</h4>
    <div class="vow-purchase-poster">
    	<img src="/images/pages/thevow/channing-tatum-film-profile.png" />
    </div>
    <div class="vow-purchase-details">
		<form method="POST" action="/vow" name="purchase_form" id="purchase_form" onsubmit="return false;">
	    <p class="title">An Evening of Vows</p>
	    <p class="host">Hosted by: <strong>Channing Tatum</strong></p>
	    <p class="pricing">Date: <strong><?php echo date("l, F jS Y",strtotime('2012-02-09 20:00:00 EST'))?>
						 at <?php echo date("g A T",strtotime('2012-02-09 20:00:00 EST')) ;?></strong></p>
	    <p class="pricing">Price: <span class="price uppercase">FREE</span></p>
    	<p class="details"><strong>The Vow</strong> star Channing Tatum will host a live interactive online event that may include the story of your vow. Channing will host the screening of fan-submitted vows and special clips from the upcoming movie.</p>
		  <?php if ($facebook) {?>
			<!--<div class="form_row clearfix">
				<input type="checkbox" checked id="vow-purchase-facebook" name="post_facebook" value="true" /><label for="vow-purchase-facebook" class="">Post on Facebook Wall</label>
			</div>-->
			<?php } ?>
			<div class="form_row clearfix">
				<button id="purchase_submit" class="button_orange button uppercase right">Reserve Your Ticket</button>
			</div>
			<input type="hidden" id="ticket_price" name="ticket_price" value="0" />
			<input type="hidden" id="film_free_screening" name="film_free_screening" value="1" />
			<input type="hidden" id="email" name="email" value="<?php echo $email;?>" />
			<input type="hidden" id="username" name="username" value="<?php echo $username;?>" />
		 </form>
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
<div id="session_id" class="reqs"><?php echo session_id();?></div>
<div id="film" class="reqs">93</div>
<div id="screening" class="reqs">thevowevent</div>
<div id="userid" class="reqs"><?php echo $user_id;?></div>

<script> 
jQuery(function(){
    _.templateSettings = {
	  interpolate : /\{\{(.+?)\}\}/g
	};
	new CTV.Comments({
		list: $('#comment-list'),
		filmId: <?php echo $film["film_id"];?>,
		isLoggedIn: <?php echo $isLoggedIn ? 'true': 'false';?>,
		fbShareImage :'http://s3.amazonaws.com/cdn.constellation.tv/prod/images/pages/thevow/channing-tatum-film-profile.png',
		fbShareCaption:'Channing Tatum Hosts An Evening of Vows @ Constellation.tv/thevow',
		tShareCaption: '#TheVow',
		rpp: 10
	});
  new CTV.Vows({
		list: $('#vow-bullit'),
		filmId: <?php echo $film["film_id"];?>,
		isLoggedIn: <?php echo $isLoggedIn ? 'true': 'false';?>,
		fbShareImage :'http://s3.amazonaws.com/cdn.constellation.tv/prod/images/pages/thevow/channing-tatum-film-profile.png',
		fbShareCaption:'Channing Tatum Hosts An Evening of Vows @ Constellation.tv/thevow'
	});
});

$(document).ready( function() {
$("#login_destination").val("/thevow/login");
$("#login_fb_link").attr("href","/services/Facebook/login?dest=http://<?php echo sfConfig::get("app_domain");?>/thevow/login");
$("#login_twitter_link").attr("href","/services/Twitter/login?dest=http://<?php echo sfConfig::get("app_domain");?>/thevow/login");
<?php if ($_GET["purchase"] == "true") {
if ($isLoggedIn) {?>
	vowPurchase.open();
<?php } else { ?>
	login.showpopup();
<?php }}?>
});
</script>

<!-- HOME INVITE -->
<?php include_component('default', 
                        'InviteAlt')?>
<!-- HOME INVITE -->

<div class="pops" id="main-vow-popup" style="display: none;">
  
  <div id="vow_popup">
    <img src="" class="large_vow" />
    <p class="vow_description"></p>
  </div>
      
</div>
