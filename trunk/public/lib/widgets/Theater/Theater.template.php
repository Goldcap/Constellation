<?php //dump($film) ;?>
<?php include_component('default', 'TheaterChat', array('film'=>$film))?>

<div class="theater-container">
	<div id="main-panel" class="main-panel">
		<?php include_component('default', 'TheaterContent', array(
			'auth_msg' =>$auth_msg,
			'film'=>$film,
			'film_start_time'=>	$film_start_time,
			'film_end_time' => $film_end_time,
			'user_id' => ($sf_user -> isAuthenticated()) && ($sf_user["user_id"] > 0) ? $sf_user["user_id"] : 0 ,
			'screening_qanda_status' => $screening_qanda_status
		))?>

		<div id="video-container" class="video-container">
			<div id="movie-placeholder"></div>
		</div>
		<?php include_component('default', 'TheaterQa', array(
			'auth_msg' =>$auth_msg,
			'film'=> $film,
			'film_start_time'=>	$film_start_time,
			'film_end_time' => $film_end_time
		))?>
		<?php include_component('default', 'TheaterCredit', array(
			'auth_msg' =>$auth_msg,
			'film'=> $film,
			'film_start_time'=>	$film_start_time,
			'film_end_time' => $film_end_time,
			'user_id' => ($sf_user -> isAuthenticated()) && ($sf_user["user_id"] > 0) ? $sf_user["user_id"] : 0 
		))?>
	</div>
<?php //include_component('default', 'TheaterColor')?>
<?php include_component('default', 'TheaterFooter', array(
	'film'=>$film, 
	'seat' =>$seat,
	'fbeacon'=>$fbeacon,
	'tbeacon'=>$tbeacon,
	'partner' => isset($partner["partner_logo"]) ? $partner : false
))?>
</div>

<?php //include_component('default', 
                        //'LoginAltFree');?>
<?php //include_component('default', 
                        //'GrowlerAlt');?>
<script>
$(function(){
	_.templateSettings = {
	  interpolate : /\{\{(.+?)\}\}/g
	};
	setTop = function(){}

	new CTV.TheaterController({
		startTime: <?php echo 	$film_start_time?>,
		endTime: <?php echo $film_end_time;?>,
		hasTicket: <?php echo $auth_msg ==''? 'true': 'false';?>,
		hasHost: <?php echo $film['screening_user_id'] != ''? 'true': 'false';?>,
		hasVideo: <?php echo  $film['screening_live_webcam'] == '1'? 'true': 'false';?>,
		hasQA: <?php echo $film['screening_live_webcam'] == '1' && $film['screening_user_id'] != '' && $film["screening_has_qanda"] == '1'? 'true': 'false';?>,
		qaStatus: '<?php echo $screening_qanda_status;?>',
		userId: <?php echo ($sf_user -> isAuthenticated()) && ($sf_user["user_id"] > 0) ? $sf_user["user_id"] : 0 ?>,
		hostId: <?php echo $film["screening_user_id"];?>,

		chatOptions: {
	    port: <?php echo $chat_instance_port_base;?>,
			room: '<?php echo $film["screening_unique_key"];?>',
		    cookie: $.cookie("constellation_frontend"),
			mdt: <?php echo isset($moderator)? '1': '0'?>,
			cmo: <?php echo $film["screening_chat_moderated"] == 'true'? 'true': 'false' ;?>,
			filmId: <?php echo $film["screening_film_id"];?>,
			userId: <?php echo ($sf_user -> isAuthenticated()) && ($sf_user["user_id"] > 0) ? $sf_user["user_id"] : 0 ?>,
			userImage: '<?php echo $sf_user["user_image"];?>',
			hostId: <?php echo $film["screening_user_id"];?>,
			ishost:  <?php echo $sf_user["user_id"] == $film["screening_user_id"] ? '1' : '0';?>,
			instance: '<?php echo $chat_instance_key;?>',
			hasTicket: <?php echo $auth_msg ==''? 'true': 'false';?>,
			hasHost: <?php echo $film['screening_user_id'] == ''? 'true': 'false';?>,
			hasVideo: <?php echo $film['screening_live_webcam'] == '1'? 'true': 'false';?>,
			fBeacon: '<?php echo $fbeacon?>',
			tBeacon: '<?php echo $tbeacon?>',
			shareOptions : 
				{
					shareTitle: '<?php echo addslashes($film["screening_film_name"]) ?>',
					twitterHash: '<?php echo addslashes($htags)?> #constellationtv',
					urlLink: 'http://www.constellation.tv/theater/<?php echo $film["screening_unique_key"]?>'
				} 
		},
		qaOptions: {
      port: <?php echo $chat_instance_port_base;?>,
			room: '<?php echo $film["screening_unique_key"];?>',
			instance: '<?php echo $chat_instance_key;?>',
			videoData: '<?php echo $video_data;?>',
      cookie: $.cookie("constellation_frontend"),
			mdt: <?php echo isset($moderator)? '1': '0'?>,
			cmo: <?php echo $film["screening_chat_moderated"] == 'true'? 'true': 'false' ;?>,
			filmId: <?php echo $film["screening_film_id"];?>,
			userId: <?php echo ($sf_user -> isAuthenticated()) && ($sf_user["user_id"] > 0) ? $sf_user["user_id"] : 0 ?>,
			userImage: '<?php echo $sf_user["user_image"];?>',
			ishost: <?php echo $sf_user["user_id"] == $film["screening_user_id"] ? '1' : '0';?>,
			hostServer: '<?php if ($film["screening_video_server_hostname"] == "fms") { echo sfConfig::get("sf_environment"); } else { echo "akamai"; }?>',
			hasHost: <?php echo $has_host? 'true': 'false';?>,
			hasVideo: <?php echo $has_video? 'true': 'false';?>,
			hostId: <?php echo $film["screening_user_id"];?>,
			userId: <?php echo ($sf_user -> isAuthenticated()) && ($sf_user["user_id"] > 0) ? $sf_user["user_id"] : 0 ?>,
			tokboxSession: '<?php echo $tokbox_session?>',
			tokboxKey: '<?php echo $tokbox_key?>', 
			tokboxToken: '<?php echo $tokbox_token?>'
		},
		hostOptions: {
      port: <?php echo $chat_instance_port_base;?>,
			room: '<?php echo $film["screening_unique_key"];?>',
			videoData: '<?php echo $video_data;?>',
			host: '<?php echo $chat_instance_host;?>',
			filmId: <?php echo $film["screening_film_id"];?>,
			hostServer: '<?php if ($film["screening_video_server_hostname"] == "fms") { echo sfConfig::get("sf_environment"); } else { echo "akamai"; }?>',
			hasHost: <?php echo $has_host? 'true': 'false';?>,
			hasVideo: <?php echo $has_video? 'true': 'false';?>,
			hostId: <?php echo $film["screening_user_id"];?>,
			recordHost: <?php echo $film['screening_video_server_hostname'] == 'fms' ? 'true' : 'false'?>,
			userId: <?php echo ($sf_user -> isAuthenticated()) && ($sf_user["user_id"] > 0) ? $sf_user["user_id"] : 0 ?>,
			tokboxSession: '<?php echo $tokbox_session?>',
			tokboxKey: '<?php echo $tokbox_key?>',
			tokboxToken: '<?php echo $tokbox_token?>'

		},
		colorOptions: {
      port: <?php echo $chat_instance_port_base;?>,
			room: '<?php echo $film["screening_unique_key"];?>',
      screeningId: '<?php echo $film["screening_id"];?>',
      cookie: $.cookie("constellation_frontend"),
			mdt: <?php echo isset($moderator)? '1': '0'?>,
			cmo: <?php echo $film["screening_chat_moderated"] == 'true'? 'true': 'false' ;?>,
			filmId: <?php echo $film["screening_film_id"];?>,
			userId: <?php echo ($sf_user -> isAuthenticated()) && ($sf_user["user_id"] > 0) ? $sf_user["user_id"] : 0 ?>,
			userImage: '<?php echo $sf_user["user_image"];?>',
			ishost: <?php echo $host == $film["screening_film_id"] ? '1' : '0';?>,
			instance: '<?php echo $chat_instance_key;?>'
		},		
		activityOptions: {
      port: <?php echo $chat_instance_port_base;?>,
			room: '<?php echo $film["screening_unique_key"];?>',
			userId: <?php echo ($sf_user -> isAuthenticated()) && ($sf_user["user_id"] > 0) ? $sf_user["user_id"] : 0 ?>,
			filmId: <?php echo $film["screening_film_id"];?>,
			instance: '<?php echo $chat_instance_key;?>',
      cookie: $.cookie("constellation_frontend"),
			instance: '<?php echo $chat_instance_key;?>'
		},
		detailOptions: {
			port: <?php echo $chat_instance_port_base;?>,
			filmStartTime: <?php echo $film_start_time?>,
			filmEndTime: <?php echo $film_end_time?>,
			currentTime: <?php echo time();?>,
			hasTicket: <?php echo $auth_msg ==''? 'true': 'false';?>
		},
		videoPlayerOptions: {
			ooyalaEmbedId: <?php echo $film["screening_film_ooyala_embed"] != '' ?  "'" . $film["screening_film_ooyala_embed"]."'" : 'false' ;?>,
			filmId: <?php echo $film["screening_film_id"];?>,
			host: '<?php echo $chat_instance_host;?>',
			port: <?php echo $chat_instance_port_base;?>,
			videoData: '<?php echo $video_data;?>',
			runtime: <?php echo $runtime;?>,
			startTime: <?php echo 	$film_start_time?>

		}
	});

 })
</script>

<style>
.reqs {
	visibility:hidden;
}
</style>
<span class="reqs" id="mechanize_room"><?php echo $film["screening_unique_key"];?></span>
<span class="reqs" id="mechanize_cookie"><?php echo $_COOKIE["constellation_frontend"];?></span>
<span class="reqs" id="mechanize_mdt"><?php echo isset($moderator)? '1': '0'?></span>
<span class="reqs" id="mechanize_cmo"><?php echo $film["screening_chat_moderated"] == 'true'? 'true': 'false' ;?></span>
<span class="reqs" id="mechanize_filmId"><?php echo $film["screening_film_id"];?></span>
<span class="reqs" id="mechanize_userId"><?php echo ($sf_user -> isAuthenticated()) && ($sf_user["user_id"] > 0) ? $sf_user["user_id"] : 0 ?></span>
<span class="reqs" id="mechanize_userImage"><?php echo $sf_user["user_image"];?></span>
<span class="reqs" id="mechanize_hostId"><?php echo $film["screening_user_id"];?></span>
<span class="reqs" id="mechanize_ishost"><?php echo $sf_user["user_id"] == $film["screening_user_id"] ? '1' : '0';?></span>
<span class="reqs" id="mechanize_instance"><?php echo $chat_instance_key;?></span>
<span class="reqs" id="mechanize_host"><?php echo $chat_instance_host;?></span>
<span class="reqs" id="mechanize_port"><?php echo $chat_instance_port_base;?></span>
<span class="reqs" id="mechanize_hasTicket"><?php echo $auth_msg ==''? 'true': 'false';?></span>
<span class="reqs" id="mechanize_hasHost"><?php echo $film['screening_user_id'] == ''? 'true': 'false';?></span>
<span class="reqs" id="mechanize_hasVideo"><?php echo $film['screening_live_webcam'] == '1'? 'true': 'false';?></span>
<span class="reqs" id="mechanize_port"><?php echo $chat_instance_port_base;?></span>


<script src="/js/jquery.jqcanvas-modified.js"></script>
<script src="/js/CTV.TheaterPoll.js"></script>

<div id="fb-root"></div>

