<?php //dump($facebookFriends);?>
<style>
.button-social-wrap {
/*	margin-top: 20px;
*/}
.button-social-wrap .button{
	margin: 10px 0;
	/*width: 300px;*/
	width: 438px;
}
#facebook-friends {
	border: solid 1px #e0e0e0;
	background: #fff;
	padding: 4px 0 4px 4px;
	max-height: 300px;	
	min-height: 60px;
	overflow: auto;
}
#facebook-friends li {
	float: left;
	width: 120px;
	/*height: 40px;
	*/
	height: 32px;
	padding: 5px 10px 5px 0;
	overflow: hidden;
	margin: 3px;
	-webkit-transition: linear background 0.2s;
	-moz-transition: linear background 0.2s;
	-o-transition: linear background 0.2s;
	-ms-transition: linear background 0.2s;
	transition: linear background 0.2s;
	cursor: pointer;
}
#facebook-friends li.checked {
	background: #D8F0FF;
}
.input-search { 
	display: block;
	margin-bottom: 10px;
	width: 420px;
	padding: 4px 10px;
	border:solid 1px #e0e0e0;
	-webkit-border-radius: 20px;
	-moz-border-radius: 20px;
	border-radius: 20px;
}
.facebook-friends-button-wrap {
	/*margin-top: 10px;*/
	padding-top: 10px;
	border-top: solid 1px #fff;
}
.facebook-friends-button-wrap .left {
	margin-top: 8px;
}
.faceb-input {float: left; margin-top: 10px
	;}
.faceb-image {float: left;}
.faceb-name { 
    color: #666666;
    display: block;
    font-size: 13px;
    height: 30px;
    line-height: 14px;
    margin-left: 60px;
    overflow: hidden;
    text-overflow: ellipsis;
    width: 60px;
 }

.m-p {
    color: #666666;
    font-size: 14px;
    line-height: 20px;
    margin: 20px 0;
}
h3 {font-size: 16px;}
.form-row label { width: 50px; text-align:left; }
.form-row .input {margin-left: 50px;}
.error-message {left: 50px;}
</style>
<div id="header">
	<img src="/images/checkout-logo.png" />
</div>
<div id="content">
	<div class="block" style="width: 480px; margin: auto">
	<h5 class="success">Congratulations! <br/>You are confirmed to attend the live online event.</h5>
	<div style="padding-bottom: 30px;border-bottom: dotted 1px #c0c0c0;margin-bottom: 30px">

	<p class="m-p <?php echo $sf_user -> getAttribute('user_email') != ''? : 'hide'?>" id="confirm-ticket">Check your email for your ticket. We will also email you a reminder before the event.</p>

	<div id="email-wrap" class="<?php echo $sf_user -> getAttribute('user_email') == ''? : 'hide'?>">
		<p class="m-p">To receive your ticket and reminder before the event, please enter you email.</p>
		<div class="form-row">
		<label>Email</label>
		<div class="input">
			<input name="email" id="email" class="text-input span4" id="email"/>
			<span class="button button_blue button-medium" id="email-submit">Submit</span>
		</div>
		</div>
	</div>

	</div>
	<h4>Invite Friends</h4>
	<p class="m-p">Share this experience with your friends and invite them to this event.</p>


<?php if(!empty($facebookFriends)):?>

<div class="form-fieldset clearfix">

	<h3>Your friends on facebook</h3>
	<input type="text" id="facebook-friends-search" class="input input-search" placeholder="search friends" />
	<ul class="clearfix" id="facebook-friends">
	<?php foreach($facebookFriends as $friend):?>
		<li data-name="<?php echo $friend['name']?>" class="checked">
			<input class="faceb-input" type="checkbox" value="<?php echo $friend['id']?>" checked />
			<div class="faceb-image"><img src="//graph.facebook.com/<?php echo $friend['id']?>/picture" style="height:32px; width: 32px"/></div>
			<div class="faceb-name"><?php echo $friend['name']?></div>
		</li>
	<?php endforeach; ?>
	</ul>
	<div class="facebook-friends-button-wrap clearfix">
		<span class="left"><input type="checkbox" id="facebook-check" checked/><label>Select All Friends</label></span>
		<span class="button button_blue button-medium right" id="invite-friends">Invite Friends</span>
	</div>
</div>
<?php else:?>

		<div class="center button-full  button-social-wrap clear">
			<span class="button button_faceblue uppercase" id="share-facebook"><span class="login-social-icon login-social-icon-facebook"></span>Invite Friends on Facebook</span>
		</div>
<?php endif;?>
		<div class="center button-full button-social-wrap clear">
			<span class="button button_bluetwitter uppercase" id="share-twitter"><span class="login-social-icon login-social-icon-twitter"></span>Share with Friends on Twitter</span>
		</div>
<div style="padding-top: 30px; margin-top: 30px;border-top: dotted 1px #c0c0c0">
	<h4>What to do now?</h4>

	<p class="m-p">Share your high school story on the <a href="/21jumpstreet#comment-box">21 Jump Street Page</a> and vote for your favorites. You can also visit the theater before the event and chat with others. </p>

	<div class="form-row">
		<a href="/forward?dest=<?php echo  $film['screening_unique_key']?>" class="button button_green uppercase" id="go-to-theater" style="padding:0; width:476px; text-align: center;"><span style=" display: block; padding: 20px; font-size: 24px; background: url(/images/bg/showtime-select.png) 100% 50% no-repeat">Go to Theater</span></a>
	</div>
	<!-- <p class="p" style="margin-top: 20px">To ensure that you have appropriate bandwidth and software to enjoy a smooth streaming experience, please <a href="/help?uid=<?php echo $uid;?>"  onclick="window.open('/help?uid=<?php echo $uid;?>? ','_blank','location=0,menubar=0,resizable=0,scrollbars=1,width=850,height=640'); return false" target="_blank">test your computer</a>.</p> -->

	</div>
</div>
</div>


<script src="/js/CTV.TOJSConfirm.js"></script>
<script>
$(function(){
	new CTV.TOJSConfirm({
		fBeacon: '<?php echo $fBeacon ;?>',
		tBeacon: '<?php echo $tBeacon ;?>',
		filmId: 119
	});
});
</script>
<div id="fb-root"></div>
<script type="text/javascript">
window.fbAsyncInit = function() {			
	FB.init({
		appId      : '163925173724311', // Live Event ID
		// appId: '185162594831374', // Contellation ID
		status     : true, // check login status
		cookie     : true, // enable cookies to allow the server to access the session
		oauth      : true, // enable OAuth 2.0
		xfbml      : false  // parse XFBML
	});
};
// Load the SDK Asynchronously
(function(d){
	var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
	js = d.createElement('script'); js.id = id; js.async = true;
	js.src = "//connect.facebook.net/en_US/all.js";
	d.getElementsByTagName('head')[0].appendChild(js);
}(document));
</script>