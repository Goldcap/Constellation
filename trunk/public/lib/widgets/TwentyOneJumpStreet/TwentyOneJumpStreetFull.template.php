<?php 
if (isset ($form) ) {echo $form;}
$isLoggedIn = false;
if (($sf_user -> isAuthenticated()) && ((is_null($sf_user -> getAttribute("temp"))) || (! $sf_user -> getAttribute("temp")))){
	$isLoggedIn = true;
}
?>

<div id="content" class="full">
	<div class="section-1">
		<div class="inner_container clearfix">
			<div class="tojs-poster-aside"></div>
			<div class="tojs-event-hero">
				<img src="/images/pages/21jumpstreet/21jump-event-text.png" />
				<p class="tojs-time uppercase">
				Thursday, March 15<sup>th</sup> at 8 PM EST
				</p>
				<?php if($hasTicket):?>
				<p class="center button-social-wrap">
					<a class="button button_blue condensed button-login uppercase" href="/theater/21jumpstlive">ENTER THEATER</a>
				</p>		
				<?php elseif(!$isLoggedIn) :?>
				<p class="center button-social-wrap">
					<a class="button button_faceblue condensed button-login uppercase" href="/services/Facebook/login?dest=<?php echo urlencode('http://' . $_SERVER['SERVER_NAME'] .'/boxoffice/screening/21jumpstlive')?>"><span class="login-social-icon login-social-icon-facebook"></span>RSVP with Facebook</a>
					<a class="button button_bluetwitter condensed button-login uppercase" href="/services/Twitter/login?dest=<?php echo urlencode('http://' . $_SERVER['SERVER_NAME'] . '/boxoffice/screening/21jumpstlive')?>"><span class="login-social-icon login-social-icon-twitter"></span>RSVP with Twitter</a>
				</p>
				<p class="center tojs-login-wrap">Or <span class="link" onclick="return login.showpopupSignup();">RSVP with an email.</span></p>
				<?php else :?>
				<p class="center button-social-wrap">
					<a class="button button_blue condensed button-login uppercase" href="/boxoffice/screening/21jumpstlive">RSVP FOR EVENT</a>
				</p>				
				<?php endif;?>
				<div class="tojs-video center"><iframe width="433" height="250" src="http://www.youtube.com/embed/_QbkqAHjTd4?wmode=opaque" frameborder="0" allowfullscreen></iframe></div>
			</div>
		</div>
	</div>
	<div class="section-2">
		<div class="inner_container clearfix">
			<h2 class="uppercase">About the event</h2>
			<p class="p">RSVP to reserve your ticket to High School Confidential, a live online interactive event. <em>21 Jump Street</em> stars Jonah Hill and Channing Tatum will be live via web-cam presenting a special screening of exclusive footage from <em>21 Jump Street</em>, along with high school confessions (see below).</p>
			<div class="center"><span><a href="http://getglue.com" class="glue-checkin-widget" data-objectId="movies/21_jump_street/phil_lord" data-sticker="constellation_tv/21_jump_street_high_school_confidential_live" data-borderColor="#000" data-bgColor="#111" data-textColor="#e0e0e0" data-type="sticker" data-rolloverBgColor="#242424" data-rolloverTextColor="#ffffff">Check-in on GetGlue</a></span><span style="display: inline-block;margin: 10px 0 0 22px;overflow: hidden;vertical-align: top;width: 234px; *display: inline">
<a href="http://www.fandango.com/_movietimes?wss=link234x60"><img src="http://images.fandango.com/r87.1/images/linktous/sw_234x60_full.gif" border="0" alt="Find Theater Showtimes" /></a>
<script type="text/javascript" src="http://www.fandango.com/affiliatewidget_srchban_234x60,searchby.movieid,mid.131465,r.768_107983.js"></script>
</span></div>

		</div>
	</div>
	<div class="section-3">
		<div class="inner_container clearfix">
			<h2 class="uppercase">HIGH SCHOOL CONFIDENTIAL</h2>
			<p class="p">Post the story of your most legendary high school achievement &mdash; of the non-academic kind, of course, along with a video or photo.  Vote for your favorites &mdash; some of the most popular stories will be presented (and maybe acted out...) by Jonah and Channing during the event.</p>
		<div class="comment-box clearfix" id="comment-box">
	<?php if ($isLoggedIn) :?>
		<div class="swfuploader">
			<input type="file" name="jump_element" id="tojs-upload" style="display: none">
		</div>
		<textarea class="comment-box-field" placeholder="Add your voice to the conversation!"></textarea>
		<span class="button button_blue uppercase">post</span>
	<?php else: ?>
		<a href="javascript:void(0)" onclick="login.showpopup()">You must be logged in to post.</a>
	<?php endif;?>
		
	</div>
	<div id="comment-success">
			<h4>Thank you. Your story was submitted</h4>
			<p class="p">Get your friends to vote on your story</p>
			<p class="center button-social-wrap">
				<span class="button button_faceblue" id="share-facebook"><span class="login-social-icon login-social-icon-facebook"></span>Share on Facebook</span>
				<span class="button button_bluetwitter" id="share-twitter"><span class="login-social-icon login-social-icon-twitter"></span>Share on Twitter</span>
			</p>
		</div>
	<div id="comment-filter" class="comment-filter">
		<span>Filter By:</span><ul><li class="active" data-sort-filter="popular">Most Popular</li><li data-sort-filter="recent">Most Recent</li></ul>
	</div>
	<ul class="comment-list comment-loading " id="comment-list"></ul>
	<div  id="pagination"></div>
	</div>
</div>


<script src="/js/CTV.Uploader.js" type="text/javascript"></script>
<script src="http://widgets.getglue.com/checkin.js" type="text/javascript"></script>


<script> 
jQuery(function(){
    _.templateSettings = {
	  interpolate : /\{\{(.+?)\}\}/g
	};
	new CTV.TOJSComments({
		list: $('#comment-list'),
		filmId: 119,
		rpp: 10,
		isLoggedIn: <?php echo $isLoggedIn ? 'true': 'false';?>,
		fbShareImage : '',
		fbShareCaptions: '',
		tShareCaption: ''
	});

if($('#tojs-upload').length > 0){
		new CTV.Uploader ({
			domNode: $('#tojs-upload'),
			upload_url: "/services/ImageManager/twentyonejump?constellation_frontend=<?php echo session_id();?>",
			debug: false,
	 		file_size_limit: '100 MB',
	 		isFilm: false,
	 		uploadFolder : '21jump',
	 		thumbWidth: '200px',
	 		fieldName: 'jump_element',
	 		dialogTitle: 'Select a File'
	});
}

	$('#share-twitter').bind('click', function(){
		var params =[];
			params.push('text=' + encodeURIComponent('I just posted my high school story for the @21jumpstmovie online event on March 15th. Vote for my story here: http://bit.ly/x4UlrG. #21jumpstlive'));
			// params.push('url=' + encodeURIComponent('http://www.constellation/21jumpstreet'));
			window.open('https://twitter.com/intent/tweet?' +params.join('&'),'_share_twitter','width=450,height=250')
	});

	$('#share-facebook').bind('click', function(){
        var obj = {
          method: 'feed',
          link: 'http://www.constellation.tv/21jumpstreet',
          // picture: 'http://www.constellation.tv/images/pages/21jumpstreet/21jumpstreet-pre-logo.jpg',
          picture: 'http://www.constellation.tv' + $('#comment-box .image-container img').attr('src'),
          name: '21 Jump Street Live Event',
          caption: 'Live online event with Jonah Hill and Channing Tatum',
          description: 'I just posted my high school story for the 21 Jump Street online interactive event presented Jonah Hill and Channing Tatum. Vote for my story and RSVP for the live event here: http://bit.ly/x4UlrG'
        };

		FB.ui(obj);
	});
});
</script>
