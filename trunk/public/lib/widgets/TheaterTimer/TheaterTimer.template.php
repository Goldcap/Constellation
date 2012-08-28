<?php 
if(($vars["Theater"]["column2"][0]["film"]["screening_user_id"] > 0)
&& ($vars["Theater"]["column2"][0]["film"]["screening_live_webcam"] == 1)) {
	$has_video = true;
} else {
	$has_video = false;
}
?>
   
<div class="logo_alt"><a title="Return to the home page" href="/"></a></div>
<!-- LOG CONTROLS -->
<div class="show_log" style="display:none">
	
<div class="share_text">
	<a onclick="videoplayer.pushLogLevel(0)">LL0</a>
  <a onclick="videoplayer.pushLogLevel(1)">LL1</a>
  <a onclick="videoplayer.pushLogLevel(2)">LL2</a>
  <a onclick="videoplayer.pushLogLevel(3)">LL3</a>
  <a onclick="videoplayer.pushLogLevel(4)">LL4</a>
</div>
</div>
<!-- END LOG CONTROLS -->

<div id="timer_panel">
	<div class="timer" id="countdown"></div>
	<div class="until_showtime">UNTIL<br />SHOWTIME</div>
</div>

<div class="share_panel">

<?php
	if (($sf_user["user_id"] > 0) && !empty($vars["Theater"]["column2"][0]["seat"])) :
  	$uid = $userid = $sf_user["user_id"];
	$tid = $vars["Theater"]["column2"][0]["seat"] -> getAudienceInviteCode();
	$sid = $vars["Theater"]["column2"][0]["film"]["screening_unique_key"];
?>
	<a href="/help?uid=<?php echo $uid;?>&tid=<?php echo $tid;?>&sid=<?php echo $sid;?>" onclick="window.open('/help?uid=<?php echo $uid;?>&tid=<?php echo $tid;?>&sid=<?php echo $sid;?>','_blank','location=0,menubar=0,resizable=0,scrollbars=1,width=850,height=540'); return false" target="_blank" id="help">
		<img src="/images/bg/help.png" style="vertical-align:middle" /> Support
	</a>
<?php endif;?>


  
	<!-- HUD CONTROLS -->
	<div class="show_hud" style="display:none">
  	
	<div class="share_text">
		<a class="hud-click" href="#">Show HUD</a>
	</div>
	</div>
	<!-- END HUD CONTROLS -->
	
	<!-- CHAT CONTROLS -->
	<div class="show_chat" style="display:none">
	<div class="share_images">
		<a class="chat-click" href="#"><img src="/images/alt1/show_chat.png" /></a>
	</div>
	
	<div class="share_text">
		<a class="chat-click" href="#">Show Chat</a>
	</div>
	</div>
	<!-- END CHAT CONTROLS -->
	
	
	<!-- HOSTCAM CONTROLS -->
	<?php if (($has_video) && (! $vars["Theater"]["column2"][0]["auth_msg"])) {?>
	<div class="show_hostcam">
	<div class="share_images">
		<a class="qanda-click" href="#"><img src="/images/alt1/show_hostcam.png" /></a>
	</div>
	
	<div class="share_text">
		<a class="qanda-click" href="#">Show Hostcam</a>
	</div>
	</div>
	<div class="hide_hostcam" style="display:none">
	<div class="share_images">
		<a class="qanda-click" href="#"><img src="/images/alt1/hide_hostcam.png" border="0" /></a>
	</div>
	
	<div class="share_text">
		<a class="qanda-click" href="#">Hide Hostcam</a>
	</div>
	</div>
	<!-- END HOSTCAM CONTROLS -->
	
	<?php } ?>
	<!--<div class="share_text">
		<img src="/images/alt1/theater_fullscreen.png" />
		View fullscreen
	</div>-->
	<div class="share_images">
		<a class="facebook_tool" href="http://www.facebook.com/sharer.php?u=<?php echo urlencode('http://'.sfConfig::get("app_domain").'/film/'.$vars["Theater"]["column2"][0]["film"]["screening_film_id"].'/detail'.$fbeacon);?>&t=<?php echo urlencode("Constellation.tv presents: \"".$vars["Theater"]["column2"][0]["film"]["screening_film_name"]."\" viewed online for yourself or with your friends.");?>" target="_new"><img src="/images/alt1/theater_face.png" /></a>
		<a class="twitter_tool"href="http://twitter.com/share?url=<?php echo ('http://'.sfConfig::get("app_domain").'/film/'.$vars["Theater"]["column2"][0]["film"]["screening_film_id"].'/detail'.$tbeacon);?>&text=<?php echo ("Constellation.tv presents: '".urlencode($vars["Theater"]["column2"][0]["film"]["screening_film_name"])."' Join a showtime!");?>" target="_new"><img src="/images/alt1/theater_twit.png" /></a>
	</div>
	
	<div class="share_text">
		Share
	</div>
	
</div>

<span class="reqs" id="startdate"><?php echo $vars["Theater"]["column2"][0]["film_start_date"];?></span>
<span class="reqs" id="currentdate"><?php echo formatDate(now(),"prettyshort");?></span>
<span class="reqs" id="thistime"><?php echo $vars["Theater"]["column2"][0]["thistime"];?></span>
<span class="reqs" id="counttime"><?php echo $vars["Theater"]["column2"][0]["counttime"];?></span>
<span class="reqs" id="runtime"><?php echo $vars["Theater"]["column2"][0]["runtime"];?></span>
<span class="reqs" id="defaulttime"><?php echo time();?></span>
<span class="reqs" id="starttime"><?php echo $vars["Theater"]["column2"][0]["film_start_time"] - 10;?></span>
<span class="reqs" id="blockentrytime"><?php echo $vars["Theater"]["column2"][0]["film_end_time"] + 10;?></span>
<span class="reqs" id="reviewtime"><?php echo $vars["Theater"]["column2"][0]["film_end_time"];?></span>
<span class="reqs" id="promotime"><?php echo $vars["Theater"]["column2"][0]["film_end_time"];?></span>
<span class="reqs" id="qatime"><?php echo $vars["Theater"]["column2"][0]["film_end_time"];?></span> 
<span class="reqs" id="endtime"><?php echo $vars["Theater"]["column2"][0]["film_end_time"] + 1800;?></span> 
<span class="reqs" id="tz_offset"><?php echo $tz_offset;?></span> 
<span class="reqs" id="seektime"></span> 

<span class="reqs" id="csrc"><?php echo $vars["Theater"]["column2"][0]["film"]["screening_film_cdn"];?></span>
