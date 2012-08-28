<?php 
if (isset ($form) ) {echo $form;}
$isLoggedIn = false;
if (($sf_user -> isAuthenticated()) && ((is_null($sf_user -> getAttribute("temp"))) || (! $sf_user -> getAttribute("temp")))){
	$isLoggedIn = true;
}
?>

<link rel="stylesheet" href="/css/conversation.css" />
<link rel="stylesheet" href="/css/pages/21jumpstreet.css" />
<link rel="stylesheet" href="/css/fonts.css" />
<link rel="stylesheet" href="/css/lightbox.css" />
<script src="/js/CTV.TOJSComments.js"></script>
<script src="/js/CTV.Uploader.js" type="text/javascript"></script>
<script src="/js/CTV.Dialog.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js" type="text/javascript"></script>
<script src="/flash/mediaplayer-5.7-licensed/jwplayer.js?v=44" type="text/javascript"></script>
<script src="/js/swfupload/swfupload.js" type="text/javascript"></script>
<script src="/js/jquery/jquery.cookie.min.js"></script>


<style>
h4 {font-size: 17px; text-align:center;}
.clearfix:before, .clearfix:after { content: ""; display: table; }
.clearfix:after { clear: both; }
.clearfix { zoom: 1; }
body{font-family: 'helveticaneue', 'helvetica', arial, sans-serif;}
#content {position: relative;}
#age-test{ text-align:center;}
.powered-by-default {position: absolute; bottom: 10px; right: 10px;}
.login-social-icon {
    background: url(http://cdn.constellation.tv.s3.amazonaws.com/prod/images/login-social-sprites.png) 0 0 no-repeat;
	height: 31px;
	width: 46px;
    vertical-align: middle;   
    display: inline-block;
    *display: inline;
    zoom:1;
}
.ctv-glue {
	position: absolute;
	left: 10px;
	bottom: 0;
}
.button {
	 vertical-align: top;
}
.tojs-pre-about, .tojs-pre-thanks {
	text-align:center;
}
.login-social-icon.login-social-icon-twitter {
  background-position: -6px -3px;
  height: 23px;
  margin-right: 4px;
  width: 32px;
 /*vertical-align: top;*/
}
.login-social-icon.login-social-icon-facebook {
  background-position: -54px 0;
  height: 25px;
  width: 20px;
  margin-right: 4px;
 /*vertical-align: top;*/

}
.comment-box-field {width:202px; font-family: 'helveticaneue', Helvetica, Arial, sans-serif;}
.tojs-event-hero {margin: 0;}
.tojs-video {margin-top: 40px;}

.age-header {
	background: black;
	padding: 30px 0;
	text-align:center;
}
.tojs-extreme { padding: 40px 0; border-bottom: solid 1px #710c16;}
.tojs-action {padding: 40px 0;  border-top: solid 1px #cf616b;}
.tojs-extreme h3 { font-size: 24px; line-height: 28px; font-weight: bold; text-shadow: 2px 2px 5px rgba(0,0,0,0.3) ;margin: 0 0 30px; }
.form-row label {color: white; font-size: 16px; text-align: right; float: none; padding-right: 20px; width: auto; font-family: helvetica, arial, sans-serif;}
.button-wrap {margin-top: 20px ;}
/*.button-wrap .button { width: 200px;}*/
#submit-age {margin-left: 10px;}
.uppercase {text-transform: uppercase;}
.hide { display: none;}
#ctv-comments {display: none;}
.comment-image + .comment-item {
    margin-left: 110px;
}
/*#ctv-comments {padding-bottom: 96px;}*/

#content p.p {font-size: 16px; line-height: 20px;}

.login { text-align:center;}
.or span {
    background: none repeat scroll 0 0 #fff;
    font-size: 14px;
}

.or {
    border-top: 1px solid #E0E0E0;
    margin: 20px 0 0;
    text-align: center;
 }

#comment-box .comment-box-field {
    width: 270px;
font-family: 'helveticaneue', 'helvetica', arial, sans-serif;}
.swfuploader {
    width: 100px;
}
.uploader-no-file {font-size: 14px;}
.dialog.trailer {
    background: none repeat scroll 0 0 #000000;
    left: -250px;
    padding: 10px;
    width: 480px;
}
#canvas #content .section-2 .inner_container  {margin-bottom:0;}
.button.button-login {
    font-size: 18px;
}
.section-2 {display: none;}
</style>

<div id="content">
	<div class="inner_container clearfix">
		<div class="tojs-event-hero">
				<img src="/images/pages/21jumpstreet/21jump-event-text.png" width="500" />
				<p class="tojs-time uppercase">
				Thursday, March 15<sup>th</sup> at 8 PM EST
				</p>

				<div id="age-test" class="<?php echo $cookie == 0 ? '': 'hide'?>">
				<p style="margin-bottom: 20px; color: #e0e0e0">Please enter your Birthday to RSVP</p>
				<div class="form-row clear">
					<label>Birthday</label>
					<select name="month" id="age-month">
						<option value="">Month</option>
						<?php for ($i = 1 ; $i < 13 ; $i++):?>
						<option value="<?php echo $i?>"><?php echo $i?></option>
						<?php endfor;?>
					</select>
					<select name="day" id="age-day">
						<option value="">Day</option>
						<?php for ($i = 1 ; $i < 31 ; $i++):?>
						<option value="<?php echo $i?>"><?php echo $i?></option>
						<?php endfor;?>
					</select>
					<select name="year" id="age-year">
						<option value="">Year</option>
						<?php for ($i = 1910 ; $i < 2012 ; $i++):?>
						<option value="<?php echo $i?>"><?php echo $i?></option>
						<?php endfor;?>
					</select>			
					<span class="button button-black button-medium uppercase" id="submit-age">Enter</span>
				</div>
				<!-- <div  class="center button-wrap"><span class="button button-black uppercase" id="submit-age">Enter</span></div> -->
			</div>
			<div id="age-failure" class="<?php echo $cookie == -1 ? '': 'hide'?>">
				<h4>You are not old enough to attend to this event.</h4>
			</div>
			<div id="rsvp" class="center button-social-wrap <?php echo $cookie == 1 ? '': 'hide'?>">
				<?php if($hasTicket):?>
					<a class="button button_blue condensed button-login uppercase" target="_top" href="http://www.constellation.tv/theater/21jumpstlive">ENTER THEATER</a>
				<?php elseif(!$isLoggedIn) :?>
					<a class="button button_faceblue condensed button-login uppercase" target="_top" id="rsvp-via-facebook" href="http://<?php echo $_SERVER['SERVER_NAME']?>/services/Facebook/login?&dest=<?php echo urlencode('http://' . $_SERVER['SERVER_NAME'] . '/boxoffice/screening/21jumpstlive')?>"><span class="login-social-icon login-social-icon-facebook"></span>RSVP with Facebook</a>
					<a class="button button_bluetwitter condensed button-login uppercase" target="_top" id="rsvp-via-twitter" href="http://<?php echo $_SERVER['SERVER_NAME']?>/services/Twitter/login?&dest=<?php echo urlencode('http://' . $_SERVER['SERVER_NAME'] . '/boxoffice/screening/21jumpstlive')?>"><span class="login-social-icon login-social-icon-twitter"></span>RSVP with Twitter</a>
				<?php else :?>
					<a class="button button_blue condensed button-login uppercase" href="/boxoffice/screening/21jumpstlive">RSVP FOR EVENT</a>
				<?php endif;?>
			</div>
			<div class="tojs-video center"><iframe width="433" height="220" src="http://www.youtube.com/embed/_QbkqAHjTd4" frameborder="0" allowfullscreen></iframe></div>
		</div>
</div>
	<div class="section-2">
		<div class="inner_container clearfix">
			<h2 class="uppercase">About the event</h2>
			<p class="p">RSVP to reserve your ticket to High School Confidential, a live online interactive event. <em>21 Jump Street</em> stars Jonah Hill and Channing Tatum will be live via web-cam presenting a special screening of exclusive footage from <em>21 Jump Street</em>, along with high school confessions (see below).</p>
			<div class="center"><a href="http://getglue.com" class="glue-checkin-widget" data-objectId="movies/21_jump_street/phil_lord" data-sticker="constellation_tv/21_jump_street_high_school_confidential_live" data-borderColor="#000" data-bgColor="#111" data-textColor="#e0e0e0" data-type="sticker" data-rolloverBgColor="#242424" data-rolloverTextColor="#ffffff">Check-in on GetGlue</a></div>

		</div>
	</div>
	<div class="section-3">

	<div id="ctv-comments" class="inner_container clearfix">
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
			<h4>Thanks for sharing your story.</h4>
			<p class="p">Share with your friends, the stories with the most votes will be included in the live event! </p>
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
		<div class="ctv-glue"><a href="http://getglue.com" class="glue-checkin-widget" data-objectId="movies/21_jump_street/phil_lord" data-sticker="constellation_tv/21_jump_street_high_school_confidential_live" data-borderColor="#000" data-bgColor="#111" data-textColor="#e0e0e0" data-type="sticker" data-rolloverBgColor="#242424" data-rolloverTextColor="#ffffff">Check-in on GetGlue</a></div>
		<p><a href="http://www.constellation.tv" target="_blank"><img class="powered-by-default" src="https://s3.amazonaws.com/cdn.constellation.tv/prod/images/pages/embed/powered-by-c.png" /></a></p>
	
</div>



</div>


<script src="http://widgets.getglue.com/checkin.js" type="text/javascript"></script>
<script> 
var login;
var checkAge = function(){
		var day = $('#age-day'),
			month = $('#age-month'),
			year = $('#age-year'),
			year = parseInt(year.val()),
			month = parseInt(month.val()),
			day = parseInt(day.val()),
			minAge = 17;

		var theirDate = new Date((year + minAge), month, day);

		var eventDate = new Date('2012', '03', '15');

		if(theirDate.toString() == 'Invalid Date'){
			alert("Please Enter a valid Birthday");
		} else if ( (eventDate.getTime() - theirDate.getTime()) < 0) {
			$('#age-test').hide();
			$('#age-failure').show();
			$.cookie('ctv_21js_age', '-1', { expires: 7, path: '/', domain: 'constellation.tv' });
			return false;
		}
		else {
			$.cookie('ctv_21js_age', '1', { expires: 7, path: '/', domain: 'constellation.tv' });

			$('#age-test').hide();
			$('#rsvp').show();
			return true;
		}


}
jQuery(function(){
$('#submit-age').bind('click', checkAge);


	var cs_referer = /facebook/.test(document.referrer) ? 'http://www.facebook.com/tojsevent' : window.top.location.href;

	$('#rsvp-via-facebook').bind('click', function(event){
		event.preventDefault();
		window.top.location = 'http://<?php echo $_SERVER['SERVER_NAME']?>/services/Facebook/login?cs_referer=' + encodeURIComponent(cs_referer) + '&dest=<?php echo urlencode("http://" . $_SERVER["SERVER_NAME"] . "/boxoffice/screening/21jumpstlive")?>';
	});
	$('#rsvp-via-twitter').bind('click', function(event){
		event.preventDefault();
		window.top.location = 'http://<?php echo $_SERVER['SERVER_NAME']?>/services/Facebook/login?cs_referer=' + encodeURIComponent(cs_referer) + '&dest=<?php echo urlencode("http://" . $_SERVER["SERVER_NAME"] . "/boxoffice/screening/21jumpstlive")?>';
	});
	


	if(/facebook/.test(document.referrer)){
		$('.ctv-glue').hide();
		$('.section-2').show();
		$('#ctv-comments').show();
		$('.powered-by-default').css({left: 190})

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
			thumbSize: 100,
			tShareCaption: '',
			videoSize: {width: 480, height: 360 }
		});

	if($('#tojs-upload').length > 0){
		new CTV.Uploader ({
			domNode: $('#tojs-upload'),
			upload_url: "/services/ImageManager/twentyonejump?constellation_frontend=<?php echo session_id();?>",
			debug: false,
	 		file_size_limit: '100 MB',
	 		isFilm: false,
	 		uploadFolder : '21jump',
	 		thumbWidth: '100px',
	 		fieldName: 'jump_element',
	 		dialogTitle: 'Select a File'
		});
	}

		$('#share-twitter').bind('click', function(){
			var params =[];
				params.push('text=' + encodeURIComponent('I just posted my high school story for the @21jumpstmovie online event on March 15th. Vote for my story here: http://bit.ly/x4UlrG. #21jumpstlive'));
				window.open('https://twitter.com/intent/tweet?' +params.join('&'),'_share_twitter','width=450,height=250')
		});

		$('#share-facebook').bind('click', function(){
			var params =[];
			params.push('app_id=185162594831374');
			params.push('link=' + encodeURIComponent('http://www.constellation.tv/21jumpstreet'));
			params.push('picture=' + encodeURIComponent('http://<?php echo $_SERVER['SERVER_NAME']?>' + $('#comment-box .image-container img').attr('src')));
			params.push('caption=' + encodeURIComponent('Live online event with Jonah Hill and Channing Tatum'));
			params.push('description=' + encodeURIComponent('I just posted my high school story for the 21 Jump Street online interactive event presented Jonah Hill and Channing Tatum. Vote for my story and RSVP for the live event here: http://bit.ly/x4UlrG'));
			params.push('redirect_uri=' + encodeURIComponent('http://www.constellation.tv/'));
			window.open('http://www.facebook.com/dialog/feed?' +params.join('&'),'_share_facebook','width=450,height=250');
		});

		login = {
			showpopup: function(){
				Dialog.open({
					title: 'Login',
					klass: 'login',
					body: $('<div class=""><a class="button button_faceblue condensed button-login uppercase" target="_top" href="http://<?php echo $_SERVER['SERVER_NAME']?>/services/Facebook/login?dest=' + encodeURIComponent('http://www.facebook.com/ConstellationTV/app_163925173724311') + '"><span class="login-social-icon login-social-icon-facebook"></span>Login with Facebook</a> <div class="or"><span class="uppercase">Or</span></div><a class="button button_bluetwitter condensed button-login uppercase" target="_top" href="http://<?php echo $_SERVER['SERVER_NAME']?>/services/Twitter/login?dest=' + encodeURIComponent('http://www.facebook.com/ConstellationTV/app_163925173724311') + '"><span class="login-social-icon login-social-icon-twitter"></span>Login with Twitter</a></div>')
				})
			}
		}
		Dialog.options.isIframe = true;
	} else {
		// console.log(jQuery('iframe[src=http://mlauprete.constellation.tv/canvas/21jumpstreet]', window.parent.window.document))
		jQuery('iframe[src="http://www.constellation.tv/canvas/21jumpstreet"]',  window.top.window.document.body).width(520).height(650);
		$('#content').css({height: 620, overflow: 'hidden'})
	}
});
</script>