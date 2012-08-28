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

<div class="hero_block">
	<a href="http://www.thevow-movie.com/showtimes/"  onclick="window.open('http://www.thevow-movie.com/showtimes/','_blank','location=0,menubar=0,resizable=0,scrollbars=1,width=400,height=600'); return false" href="http://www.thevow-movie.com/showtimes/"><img src="/images/pages/thevow/the-vow-hero-2.jpg" style="position: relative; left: -6px"/></a>
	<!-- <div class="hero_block_side">
		<p>Reserve your <strong>FREE</strong> ticket to an interactive online event with Channing Tatum.</p>
		<p class="hero-button-wrap">
			<?php /*if ($isLoggedIn) :?>
			<span class="button button_pink uppercase share-vow">Share your Vow</span>
			<?php else :?>
			<span class="button button_pink uppercase" onclick="login.showpopup()">Share your Vow</span>
			<?php endif;*/?>
		</p>
	</div> -->
</div>

<div id="content">
	<div class="inner_container clearfix">
	
<!-- 	<div class="block clearfix" style="margin:4px 0 14px">
		<div class="left vow-live" style="width: 600px">
			<h4>The "Evening of Vows" event took place on February 9th at 8:00 PM EST.  Over 5,000 attendees participated from around the world.</h4>
			<p class="p-m">Missed the event, or want to watch an "Evening of Vows"?  Watch Now.</p>
		</div>
		<a href="/boxoffice/screening/none?film=93&dohbr=true" class="button button_blue right">Watch Now</a>
	</div> -->

	<a href="/boxoffice/screening/none?film=93&dohbr=true" class="button button_pink" style="position: relative;display:block; padding:0;margin:4px 0 14px;"><span style=" display: block; padding: 20px; font-size: 24px; background: url(/images/bg/showtime-select.png) 100% 50% no-repeat">
		Watch "An Evening of Vows" <br/><span style="font-size: 16px; color: #e0e0e0; font-style: italic">Live event took place on February 9th at 8:00 PM EST</span><span style="position: absolute; top: 32px; right: 80px"> <?php echo $screening_views?> Views</span>
	</span></a>

	<div id="showtime-wrap" class="grid-2 clearfix">
		<div class="block">
			<h4>Clips from Q+A with Channing Tatum </h4>
			<div class="vow-question clearfix">
 				<h5 class="vow-question-text clearfix"><img src="/images/pages/thevow/question.png" align="left" /><span>Welcome from Channing</span></h5>
				<div class="vow-question-video">
					<iframe width="436" height="251" src="http://www.youtube.com/embed/H7v7wXauinE" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>
			<div class="vow-question clearfix">
 				<h5 class="vow-question-text clearfix"><img src="/images/pages/thevow/question.png" align="left" /><span>What was your favorite scene in the movie?</span></h5>
				<div class="vow-question-video">
					<iframe width="436" height="251" src="http://www.youtube.com/embed/703vwz_EPfQ" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>			
			<div class="vow-question clearfix">
 				<h5 class="vow-question-text clearfix"><img src="/images/pages/thevow/question.png" align="left" /><span>How did you like working as a stripper?</span></h5>
				<div class="vow-question-video">
					<iframe width="436" height="251" src="http://www.youtube.com/embed/VM13oTwX57w" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>		
			<div class="vow-question clearfix">
 				<h5 class="vow-question-text clearfix"><img src="/images/pages/thevow/question.png" align="left" /><span>If you could have any profession, what would it be?</span></h5>
				<div class="vow-question-video">
					<iframe width="436" height="251" src="http://www.youtube.com/embed/z25X7relfeo" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>	
			<div class="vow-question clearfix">
 				<h5 class="vow-question-text clearfix"><img src="/images/pages/thevow/question.png" align="left" /><span>Do you play pranks on your co-stars?</span></h5>
				<div class="vow-question-video">
					<iframe width="436" height="251" src="http://www.youtube.com/embed/CKlnLIIJpPY" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>	
			<div class="vow-question clearfix">
 				<h5 class="vow-question-text clearfix"><img src="/images/pages/thevow/question.png" align="left" /><span>Did you meet the couple the movie was based on?</span></h5>
				<div class="vow-question-video">
					<iframe width="436" height="251" src="http://www.youtube.com/embed/MPId_nocbEE" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>	
			<div class="vow-question clearfix">
 				<h5 class="vow-question-text clearfix"><img src="/images/pages/thevow/question.png" align="left" /><span>What do you cherish most about being married?</span></h5>
				<div class="vow-question-video">
					<iframe width="436" height="251" src="http://www.youtube.com/embed/F0a5qefrfQw" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>	
						
		</div>
	</div>
    
	<div id="conversation-wrap" class="grid-2 clearfix">
	<div class="block">
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
		<ul class="comment-list comment-loading " id="comment-list"></ul>
		<div class="comment-pagination" id="pagination">
			<ul></ul>
		</div>
	</div>
	</div>
		</div>




	</div>
</div>

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
		rpp: 25
	});

});
</script>
