<link rel="stylesheet" href="/css/pages/21jumpstreet.css" />
<style>
#canvas {
	width: 140px;
}
#content {position: relative;}
.mini-canvas .hero-block {
	padding: 0 0 10px;
}
#canvas #content .inner_container {
	width: 140px;
	margin:0;
}
.tojs-pre-about, .tojs-pre-thanks {
	font-size: 13px;line-height: 1px;
	padding: 10px 0;
	margin-bottom: 10px;
	text-align:center;
}
#canvas .tojs-pre-form  .text-input {
    font-size: 12px;
    height: 20px;
    line-height: 14px;
    padding: 2px 4px;
    width: 130px;
}
.tojs-pre-form .button.button_blue {
    float: right;
    font-size: 14px;
    line-height: 16px;
    padding: 4px 8px;
    margin-top: 6px;
    margin-right: 41px;
}
.powered-by-default {position: absolute; bottom: 0px; left: 15px;}
.login-social-icon {
    background: url(http://cdn.constellation.tv.s3.amazonaws.com/prod/images/login-social-sprites.png) 0 0 no-repeat;
height: 31px;
 width: 46px;
     vertical-align: middle;   
    display: inline-block;
    *display: inline;
    zoom:1;
}
.button {
	 vertical-align: top;

}
.login-social-icon.login-social-icon-twitter {
  background-position: -6px -3px;
  height: 23px;
  margin-right: 4px;
  width: 32px;
}
.login-social-icon.login-social-icon-facebook {
  background-position: -54px 0;
  height: 25px;
  width: 20px;
  margin-right: 4px;
 vertical-align: top;
}
.button_faceblue, .button_bluetwitter {
	height: 24px;
	width: 24px;
	padding: 4px;
 vertical-align: top;
}
.login-social-icon-twitter { margin-left: -4px; margin-top: -1px;}
.button-social-wrap {margin-top:10px;}
</style>
<div id="content" class="mini-canvas" style="height: 300px; width: 140px; overflow: hidden ">
	<div class="inner_container clearfix">
		<div class="hero-block">
			<img src="/images/pages/21jumpstreet/21jump-event-text.png" width="140" />
		</div>
		<div class="tojs-pre-info">
			<div class="tojs-pre-about">
				RSVP to reserve your ticket to High School Confidential, a live online interactive event, hosted by <em>21 Jump Street</em> stars Jonah Hill and Channing Tatum.
			</div>
			<p style="text-align:center">
			<a href="http://www.constellation.tv/21jumpstreet" target="_blank" id="rsvp" class="button button_blue uppercase">RSVP NOW</a></p>

		</div>
	</div>
	<p class="center"><a href="http://www.constellation.tv" target="_blank"><img class="powered-by-default" src="https://s3.amazonaws.com/cdn.constellation.tv/prod/images/pages/embed/powered-by-c.png" /></a></p>
</div>

<script>
$(function(){
	var cs_referer = /facebook/.test(document.referrer) ? 'http://www.facebook.com/tojsevent' : window.top.location.href;

	$('#rsvp').bind('click', function(event){
		event.preventDefault();
		window.top.location = 'http://<?php echo $_SERVER["SERVER_NAME"]?>/21jumpstreet?cs_referer=' + encodeURIComponent(cs_referer));
	});
});

</script>