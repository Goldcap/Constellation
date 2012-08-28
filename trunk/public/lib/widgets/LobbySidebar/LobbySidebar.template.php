<?php
if (isset($vars["Lobby"])) {
  $film = $vars["Lobby"]["main"][0]["film"];
  $chat_instance_key = $vars["Lobby"]["main"][0]["chat_instance_key"];
  $chat_instance_proxy = $vars["Lobby"]["main"][0]["chat_instance_proxy"];
  $chat_instance_host = $vars["Lobby"]["main"][0]["chat_instance_host"];
  $chat_instance_port_base = $vars["Lobby"]["main"][0]["chat_instance_port_base"];
  
}
if ($sf_user["user_id"] > 0) {
  $userid = $sf_user["user_id"];
}
?>

<?php if ($sf_user->hasCredential(2)) {?>
<!-- START ADMIN CHAT PANEL -->
<div id="adminmessage_panel" class="chat_box" style="display: none;">
    <div class="title">Users Online</div>
    <h4>hide >></h4>
    <div id="adminmessage-users"></div>
    <h2 id="adminrecipient">Admin Messages</span></h2>
    <div id="adminmessage-inbox" class="inbox"></div>					
    <form action="/services/adminmessage/post" method="post" id="adminmessageform">				
        <textarea id="adminmessage" name="adminmessagebody" class="grey"></textarea> 				
        <input id="adminmessage-chat-submit" name="" value="post"  class="btn_og" type="button" />
    </form>
</div>
<!-- END ADMIN CHAT PANEL -->
<?php }  else {?>
<!-- START ADMIN CHAT PANEL -->
<div id="adminmessage_panel" class="chat_box" style="display: none;">        
    <div class="title">Chat with the Admin</div>
    <h4>hide >></h4>
    <div id="adminmessage-inbox" class="inbox"></div>					
    <form action="/services/adminmessage/post" method="post" id="adminmessageform">				
        <textarea id="adminmessage" name="adminmessagebody" class="grey"></textarea> 				
        <input id="adminmessage-chat-submit" name="" value="post"  class="btn_og" type="button" />
    </form>
</div>
<!-- END ADMIN CHAT PANEL -->
<?php } ?>

<span id="room"><?php echo $film["screening_unique_key"];?></span>
<span id="film"><?php echo $film["screening_film_id"];?></span>
<span id="location" class="reqs">lobby</span>
<?php if (isset($vars["Lobby"]["main"][0]["seat"])) {?>
<span id="seat"><?php echo $vars["Lobby"]["main"][0]["seat"] -> getAudienceInviteCode();?></span>
<?php } ?>
<span id="userid"><?php echo $sf_user["user_id"];?></span>
<span id="pairid"></span>
<span id="toid"></span>
<span id="adminmessage-pairid"><?php echo $vars["Lobby"]["main"][0]["pair"];?></span>
<span id="adminmessage-owner"><?php echo $vars["Lobby"]["main"][0]["owner"];?></span>
<span id="adminmessage-toid"><?php echo $vars["Lobby"]["main"][0]["user"];?></span>
<span id="adminmessage-authorid"></span>

<div id="host" class="reqs"><?php echo $chat_instance_host;?></div>
<div id="port" class="reqs"><?php echo $chat_instance_port_base;?></div>
<div id="instance" class="reqs"><?php echo $chat_instance_key;?></div>


<div id="status_panel">
  Status: Prescreening <span id="mini-timer"></span>
</div>

<!-- START ADMIN CHAT INVITE -->
<div id="adminmessage_invite">
 	<h2>Messages</h2>					
  <!--<h4>hide >></h4>-->
  <span id="adminmessage_invite_messages"></span>
</div>
<!-- END ADMIN CHAT INVITE -->
