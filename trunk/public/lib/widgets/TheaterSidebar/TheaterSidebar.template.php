<?php
if (isset($vars["Theater"])) {
  $film = $vars["Theater"]["column2"][0]["film"];
  $states = $vars["Theater"]["column2"][0]["states"];
  $countries = $vars["Theater"]["column2"][0]["countries"];
  $post = $vars["Theater"]["column2"][0]["post"];
  $auth_msg = $vars["Theater"]["column2"][0]["auth_msg"];
  $auth_text = $vars["Theater"]["column2"][0]["auth_text"];
  $auth_link = $vars["Theater"]["column2"][0]["auth_link"];
  $chat_instance_key = $vars["Theater"]["column2"][0]["chat_instance_key"];
  $chat_instance_proxy = $vars["Theater"]["column2"][0]["chat_instance_proxy"];
  $chat_instance_host = $vars["Theater"]["column2"][0]["chat_instance_host"];
  $chat_instance_port_base = $vars["Theater"]["column2"][0]["chat_instance_port_base"];
  
}
if (($sf_user["user_id"] > 0) && (! $userid)) {
  $userid = $sf_user["user_id"];
}
?>

<span id="toolbarbut" class="footer-hidden" style="display: none">
  <a class="right showbar" href="#" >show</a>
</span>

<!-- FILM INFO PANEL -->
<div id="info_panel" class="chat_box killer" style="display: none">
  <div class="title">Film Info</div>
  <h4>close</h4>			
  <p><?php echo WTVRCleanString($film["screening_film_info"],false);?></p>	          
</div> 
<!-- END FILM INFO PANEL -->

<?php if (! $auth_msg) {?>
<!-- START QA PANEL -->
<div id="qanda_panel" class="chat_box qanda_box" style="display: none">
	<div class="title">Q AND A<!--<img id="chat_refresh" src="/images/Neu/16x16/actions/gtk-refresh.png" />--></div>
    <h4><img src="/images/alt1/chat_close.png" /></h4>
		<div id="host_stream" class="nx_widget_player"></div>
</div>
<?php } ?>

<!-- START CHAT PANEL -->
<div id="chat_panel" class="chat_box" style="display: none">
    <div class="title">Chat<!--<img id="chat_refresh" src="/images/Neu/16x16/actions/gtk-refresh.png" />--></div>
    <h4><img src="/images/alt1/chat_close.png" /></h4>
    <div id="history" class="history">
    </div>
    <div id="inbox" class="inbox">
			<!-- START CHAT MESSAGES -->
			
			<!-- END CHAT MESSAGES -->
		</div>					
    <?php if (! isset($auth_msg)) {?>
    <form action="/services/chat/post" method="post" name="chat_post" id="messageform">
			<div id="sending" style="display:none">Sending Message...</div>
			<div>				
        <textarea id="message" name="body" class="grey chatbox_entry" placeholder="Enter Your Message Here"></textarea>
        <input id="chat-reply-id" type="hidden" name="reply_id" />
      </div>
      <div id="chat-submit-container">
				<input id="chat-submit" name="" value="post" class="chat_post" type="image" src="/images/alt1/chat_post.png" />
    	</div>
      <div id="chat-reply-container" style="display: none;">
        <span id="chat-reply-cancel">Cancel</span>
        <input id="chat-reply" name="" value="post" class="chat_post" type="image" src="/images/alt1/chat_reply.png" />
      </div>
		</form>
    <?php } ?>
    <div class="clear"></div>
</div>
<!-- END CHAT PANEL -->

<!-- START HELP PANEL-->
<div id="help_panel" class="chat_box killer" style="display: none">	      
    <div class="title">Help</div>  				
    <h4>close</h4>  			
    
    <p>If your streaming is stuttering or freezing, try refreshing your browser.</p>  		
    
    <p>You can always re-enter the theater via the link on your ticket, or by clicking on the "My Showtimes" link beneath your login name on the Constellation website.</p>
      		
    <p>To ensure you have the best quality experience, be sure to close all other browser windows (especially those with videos or media), and try to run as few programs on your computer as possible.</p>  		
    
    <p>For immediate support, please contact: <a class="help-divnk" href="mailto:support@constellation.tv">support@constellation.tv</a>.</p>		    
</div>
<!-- END HELP PANEL-->


<div id="status_panel">
  Status: Prescreening <span id="mini-timer"></span>
</div>

<?php if ($has_host) {?>
  <span id="has_host" class="reqs">true</span>
<?php } ?>

<?php if ($vars["Theater"]["column2"][0]["host"] == $film["screening_film_id"]) {?>
  <span id="is_host" class="reqs">true</span>
<?php } ?>

<?php if ($has_video) {?>
  <span id="has_video" class="reqs">true</span>
<?php } ?>

<span id="film" class="reqs"><?php echo $film["screening_film_id"];?></span>
<span id="movie_type" class="reqs"><?php echo ($film["screening_film_movie_file"] == "") ? "mp4" : $film["screening_film_movie_file"];?></span>
<span id="room" class="reqs"><?php echo $film["screening_unique_key"];?></span>
<span id="screening_id" class="reqs"><?php echo $film["screening_id"];?></span>
<span id="screening" class="reqs"><?php echo $film["screening_unique_key"];?></span>
<span id="hbr" class="reqs"><?php if ($film["screening_type"] == 3) { echo "true"; } else { echo "false"; } ?></span>
<span id="bitrates" class="reqs"><?php echo $film["screening_film_bitrate_small"];?>,<?php echo $film["screening_film_bitrate_medium"];?>,<?php echo $film["screening_film_bitrate_large"];?></span>
<span id="location" class="reqs">theater</span>
<?php if (isset($vars["Theater"]["column2"][0]["seat"])){?>
<span id="seat" class="reqs"><?php echo $vars["Theater"]["column2"][0]["seat"] -> getAudienceInviteCode();?></span>
<?php } ?>
<?php if (isset($vars["Theater"]["column2"][0]["moderator"])){?>
<span id="moderator" class="reqs">true</span>
<?php } ?>
<span id="userid" class="reqs"><?php if (($sf_user -> isAuthenticated()) && ($sf_user["user_id"] > 0)) {echo $sf_user["user_id"];} else { echo 0;}php?></span>
<?php if (isset($vars["Theater"]["column2"][0]["moderator"])){?>
<span id="hostid" class="reqs"><?php echo $film["screening_user_id"];?></span>
<?php } ?>
<span id="user_image" class="reqs"><?php echo $sf_user["user_image"];?></span>
<span id="pairid" class="reqs"></span>
<span id="toid" class="reqs"></span>
<span id="adminmessage-pairid" class="reqs"></span>
<span id="cmo" class="reqs"><?php echo $film["screening_chat_moderated"];?></span> 
<span id="hostserver" class="reqs"><?php if ($film["screening_video_server_hostname"] == "fms") { echo sfConfig::get("sf_environment"); } else { echo "akamai"; }?></span>
<span id="video_data" class="reqs"><?php echo $vars["Theater"]["column2"][0]["video_data"];?></span>
<?php if (isset($film["screening_user_full_name"])) {?>
<span id="time_<?php echo $film["screening_unique_key"];?>" class="reqs"><?php echo formatDate($film["screening_user_full_name"],"prettyshort");?></span>
<span id="host_<?php echo $film["screening_unique_key"];?>" class="reqs"><?php echo $film["screening_user_full_name"];?></span>
<?php } ?>
<div id="host" class="reqs"><?php echo $chat_instance_host;?></div>
<div id="port" class="reqs"><?php echo $chat_instance_port_base;?></div>
<div id="instance" class="reqs"><?php echo $chat_instance_key;?></div>
<span id="fic" class="reqs"><?php echo $film["screening_film_freewithinvite"];?></span> 


<div id="tokbox_session" class="reqs"><?php echo $vars["Theater"]["column2"][0]["tokbox_session"];?></div>
<div id="tokbox_key" class="reqs"><?php echo $vars["Theater"]["column2"][0]["tokbox_key"];?></div>
<div id="tokbox_token" class="reqs"><?php echo $vars["Theater"]["column2"][0]["tokbox_token"];?></div>

<!-- TIMEZONE -->
<?php include_component('default', 
                        'GrowlerAlt')?>
<!-- TIMEZONE -->	 		

<!-- START LOGIN POPUP -->
<?php include_component('default', 
                        'LoginAltFree')?>
<!-- END LOGIN POPUP -->

<!-- HOME INVITE -->
<?php include_component('default', 
                        'InviteAlt')?>
<!-- END HOME INVITE -->

<?php if (isset($vars["Theater"]["column2"][0]["moderator"])){?>
<!-- HUD POPUP -->
<?php include_component('default', 
                        'HeadsUpDisplay')?>
<!-- END HUD POPUP -->
<?php } ?>
