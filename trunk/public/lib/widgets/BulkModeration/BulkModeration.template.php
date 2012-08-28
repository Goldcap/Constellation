
<div id="moderator-wrap">
<div id="moderator-container" class="chat-container aside">
	<h2><span class="icon-chat"></span>User List</h2>
	<div class="user-input-container">
		<textarea placeholder="Search Here..." resize="none" class="user-search" id="user-search"></textarea>
	</div>
	<div id="moderator-content" class="chat-list nano">
		<div class="content"></div>
	</div>
</div>


<div id="chat-container" class="chat-container aside">
	<h2><span class="chat-room right">Change Rooms</span><span class="icon-chat"></span>Approved Chat</h2>
	<div id="chat-content" class="chat-list nano">
		<div class="content"></div>
	</div>
	<div id="room-content" class="room-list nano">
		<div class="content"></div>
	</div>
</div>

<div id="qa-container" class="chat-container aside">
	<h2><span class="icon-chat"></span>Q&amp;A Current Question</h2>
	<div id="qa-current" class="current-question">
		
	</div>
	<h2><span class="icon-chat"></span>Q&amp;A Questions</h2>
	<div id="qa-questions" class="chat-list">
		<div class="content"></div>
	</div>
</div>

</div>


<script src="/js/CTV.ModeratorController.js"></script>
<script src="/js/CTV.ModeratorQa.js"></script>
<script>

$(function(){
	_.templateSettings = {
	  interpolate : /\{\{(.+?)\}\}/g
	};
 
	new CTV.ModeratorController({
		startTime: <?php echo 	$film_start_time?>,
		endTime: <?php echo $film_end_time;?>,
		hasTicket: <?php echo $auth_msg ==''? 'true': 'false';?>,
		hasHost: <?php echo $has_host? 'true': 'false';?>,
		hasVideo: <?php echo $has_video? 'true': 'false';?>,
		hasQA: <?php echo $has_video && $has_host && $film["screening_has_qanda"] == '1'? 'true': 'false';?>,
		searchNode: $("#moderator-content"),
			
		chatOptions: {
	        room: '<?php echo $film["screening_unique_key"];?>',
	        cookie: $.cookie("constellation_frontend"),
			mdt: 1,
			cmo: <?php echo $film["screening_chat_moderated"] =='true'?'true':'false';?>,
			filmId: <?php echo $film["screening_film_id"];?>,
			userId: <?php echo ($sf_user -> isAuthenticated()) && ($sf_user["user_id"] > 0) ? $sf_user["user_id"] : 0 ?>,
			userImage: '<?php echo $sf_user["user_image"];?>',
			hostId: <?php echo $film["screening_user_id"];?>,
			instance: '<?php echo $chat_instance_key;?>',
			host: '<?php echo $chat_instance_host;?>',
			port: <?php echo $chat_instance_port_base;?>,
			hasTicket: true,
			hasHost: false,
			hasVideo: false,
			isModeratorPanel: true,
			moderatorNode: $("#moderator-container")		
		},
		moderatorOptions: {
      room: '<?php echo $film["screening_unique_key"];?>',
      cookie: $.cookie("constellation_frontend"),
			mdt: 1,
			cmo: true,
			filmId: <?php echo $film["screening_film_id"];?>,
			userId: <?php echo ($sf_user -> isAuthenticated()) && ($sf_user["user_id"] > 0) ? $sf_user["user_id"] : 0 ?>,
			userImage: '<?php echo $sf_user["user_image"];?>',
			hostId: <?php echo $film["screening_user_id"];?>,
			instance: '<?php echo $chat_instance_key;?>',
			host: '<?php echo $chat_instance_host;?>',
			port: <?php echo $chat_instance_port_base;?>,
			hasTicket: true,
			hasHost: false,
			hasVideo: false,
			isModeratorPanel: true
		}
	});

 })
</script>