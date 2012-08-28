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

				<div class="tojs-video center"><iframe width="560" height="315" src="http://www.youtube.com/embed/xE24M_DK7iw?wmode=opaque" frameborder="0" allowfullscreen></iframe></div>
			<div class="center" style="margin-top: 20px">
			<a href="http://www.fandango.com/_movietimes?wss=link468x6Ot"><img src="http://images.fandango.com/r87.1/images/linktous/sw_468x60_tix_full.gif" border="0" alt="Find Theater Showtimes" /></a>
			<script type="text/javascript" src="http://www.fandango.com/affiliatewidget_srchban_468x60_tix,searchby.movieid,mid.131465,r.129_764729.js"></script>
</div>
			</div>


		</div>
	</div>
	
	<div class="section-2">
		<div class="inner_container clearfix">

<!-- Begin MailChimp Signup Form -->
<link href="http://cdn-images.mailchimp.com/embedcode/slim-081711.css" rel="stylesheet" type="text/css">
<style type="text/css">
	#mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; margin-bottom: 20px; margin-top: 20px;}
	#mc_embed_signup label {color: #333; font-family: "helveticaneue"; font-weight: normal;}
	#mc_embed_signup input.email {display: inline; width: 600px;}
	#mc_embed_signup input.button,#mc_embed_signup input.button:hover {display: inline-block; *display: inline;width: 240px; background-color: #277bb0;}
	/* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
	   We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
</style>
<div id="mc_embed_signup" class="block">
	<!-- <h4>Clips from Q+A with Channing Tatum </h4> -->
<form action="http://constellation.us2.list-manage.com/subscribe/post?u=5dcb972a18f2ca884af5f6bd9&amp;id=6c1902192e" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank">
	<label for="mce-EMAIL">Enter your email address to receive updates on upcoming Constellation events. </label>
	<input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="email address" required><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button button_blue">
</form>
</div>

<!--End mc_embed_signup-->

<div id="showtime-wrap" class="grid-2 clearfix">
		<div class="block">
			<h4>Clips from Q+A with Jonah  &amp; Channing </h4>
			<div class="vow-question clearfix">
				<div class="vow-question-video">
					<iframe width="450" height="259" src="http://www.youtube.com/embed/r3EE05ASEJ4?wmode=opaque" frameborder="0" allowfullscreen></iframe>
				</div>
				<div class="vow-question-video">
					<iframe width="450" height="259" src="http://www.youtube.com/embed/TVq-VXEjbiQ?wmode=opaque" frameborder="0" allowfullscreen></iframe>
				</div>
				<div class="vow-question-video">
					<iframe width="450" height="259" src="http://www.youtube.com/embed/ZpqVXokh3-s?wmode=opaque" frameborder="0" allowfullscreen></iframe>
				</div>
				<div class="vow-question-video">
					<iframe width="450" height="259" src="http://www.youtube.com/embed/y3RB6yaGsqk?wmode=opaque" frameborder="0" allowfullscreen></iframe>
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
		filmId: 119,
		isLoggedIn: <?php echo $isLoggedIn ? 'true': 'false';?>,
		fbShareImage :'http://s3.amazonaws.com/cdn.constellation.tv/prod/images/pages/thevow/channing-tatum-film-profile.png',
		fbShareCaption:'Channing Tatum Hosts An Evening of Vows @ Constellation.tv/thevow',
		tShareCaption: '#TheVow',
		rpp: 25
	});

});
</script>

