<?php
if (isset($vars["Theater"])) {
  $film = $vars["Theater"]["column2"][0]["film"];
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


<?php if ($vars["Theater"]["column2"][0]["host"] == $film["screening_film_id"]) {?>

<!-- Q AND A HOST CONTROL POPUP -->
<div class="qa_pop_up" id="q_and_a_control" style="display: none">
  <div class="qa_pop_mid">
  <div class="qa_pop_top popup-close"><a href="#">close</a></div>
	<div class="layout-1 clearfix">
    <div class="chalkboard">
	    <div class="status">YOU ARE LIVE</div>
    	<div class="title"><strong>YOU ARE ANSWERING</strong></div>
    	<div class="questions" id="qa_showing_question">
        <ol id="qa_showing_list">
          <?php if (count($selected) > 0) {foreach($selected as $quest){ 
          if ($quest["qanda_current"] == 1) {          
            echo "<li id=\"qe-".$quest["qanda_id"]."\">".$quest["user_username"]." Asked \"" .$quest["qanda_body"]. "\"</li>"; 
          }}}?>
        </ol>  
      </div>
    	<!--<div class="top_control">
    	 <input type="button" id="host_submit" value="clear" class="btn_med" name="" />
    	</div>-->
    </div>
    <div class="inputs">
    	<div class="left">
        <p>QUESTIONS FROM AUDIENCE</p>
        <div class="vscroller">
        <ul id="qanda-host-inbox" class="connectedSortable inbox">
          <?php if (count($questions) > 0) {foreach($questions as $quest){ 
          echo "<li class=\"question\" id=\"q".$quest["qanda_id"]."\">
                  <table border=\"0\" padding=\"1\">
                  <tr>
                  <td valign=\"top\" class=\"handle\">
                  <img src=\"/images/dnd-arrow.png\"/>
                  </td>
                  <td class=\"handle\" valign=\"top\" style=\"display:none\">
                  <img onclick=\"qanda.showSlide('".$quest["qanda_id"]."')\" src=\"/images/dnd-go.png\" />
                  </td>
                  <td valign=\"top\" class=\"sequence\" style=\"display:none\">".
                  $quest["qanda_sequence"].":
                  </td>
                  <td class=\"quest\" >".
                  $quest["qanda_body"].
                "</td>
                </tr>
                </table>
                </li>"; 
          }}?>
        </ul>
        </div>
      </div>
      <div class="right">
        <p>YOUR SELECTED QUESTIONS</p>
        <div class="vscroller">
        <ul id="qanda-host-selected-inbox" class="connectedSortable inbox">
          <?php if (count($selected) > 0) {foreach($selected as $quest){ 
          if ($quest["qanda_current"] == 1) {
            $img = "dnd-gone.png";
          } else {
            $img = "dnd-go.png";
          }
          echo "<li class=\"question\" id=\"q".$quest["qanda_id"]."\">
                  <table border=\"0\" padding=\"1\">
                  <tr>
                  <td valign=\"top\" class=\"handle\">
                  <img src=\"/images/dnd-arrow.png\"/>
                  </td>
                  <td class=\"handle\" valign=\"top\">
                  <img onclick=\"qanda.showSlide('".$quest["qanda_id"]."')\" src=\"/images/".$img."\" />
                  </td>
                  <td valign=\"top\" class=\"sequence\" >".
                  $quest["qanda_sequence"].":
                  </td>
                  <td class=\"quest\" >".
                  $quest["qanda_body"].
                "</td>
                </tr>
                </table>
                </li>"; 
          }}?>
        </ul>
        </div>
      </div>
    </div>
    <div class="push_buttons">
      <input type="button" id="host_screening_webcam" value="disable webcam" class="btn_med-og" name="" />
      <input type="button" id="host_screening_qanda" value="stop q & a" class="btn_med" name="" />
      <input type="button" id="host_screening_end" value="end screening" class="btn_med" name="" style="display: none;" />
    </div>
  </div>
  <div class="qa_pop_bot"></div>
  </div>    
</div>
<!-- END Q AND A HOST CONTROL POPUP -->

<!-- Q AND A HOST PANEL -->	 	    
<div id="qanda_panel" class="chat_box keeper" style="display: none">  	      
  
  <?php if ($film["screening_film_live_webcam"] == 0) {?>
  <div style="text-align: center; margin-left: 10px; margin-top: 10px;">
    <img src="/images/sponsor/<?php echo $film["screening_film_sponsor_id"];?>/host_image.png" />
  </div>
  <?php } else { ?>
  <script type="text/javascript">
    $(document).ready(function() {
      //qanda.ping(); //This is the constant pull method, too verbose
      if (typeof qanda != "undefined") {
        qanda.status(); //This is the question push method, better!
      }
    });
  </script>
  <?php } ?>
  
</div>

<!-- END Q AND A HOST PANEL -->	

<?php } else {?>

<!-- Q AND A CONTROL POPUP -->
<div class="qa_pop_up" id="q_and_a_control" style="display: none">
  <div class="qa_pop_mid">
  <div class="qa_pop_top popup-close"><a href="#">close</a></div>
	<div class="layout-1 clearfix">
    <div class="chalkboard">
    	<div class="title_blue"><strong>CURRENT QUESTIONS</strong></div>
    	<div class="questions" id="qa_showing_questions">
        <ol id="qa_showing_list">
        <?php if (count($selected) > 0) {foreach($selected as $quest){ 
          if ($quest["qanda_current"] == 1) {          
            echo "<li id=\"qe-".$quest["qanda_id"]."\">".$quest["user_username"]." Asked \"" .$quest["qanda_body"]. "\"</li>"; 
        }}}?>
        </ol>
      </div>
    </div>
    <div class="myquestions">
      <div class="title_blue"><strong>MY QUESTIONS</strong></div>
    	<div class="questions" id="qa_showing_questions">
        <ol id="qa_list">
        <?php if (count($questions) > 0) {foreach($questions as $quest){ 
            echo "<li id=\"qe-".$quest["qanda_id"]."\">You asked \"" .$quest["qanda_body"]. "\"</li>"; 
        }}?>
        </ol>
      </div>
    	<div class="top_control">
    	</div>
    </div>
  </div>
  <div class="qa_pop_bot"></div>
  </div>    
</div>
<!-- END Q AND A CONTROL POPUP -->

<!-- Q AND A PANEL -->	 	    
<div id="qanda_panel" class="chat_box keeper" style="display: none">  
  <div class="title">Q&A</div>			
  <h4>close</h4>
  
  <?php if ($film["screening_live_webcam"] == 0) {?>
  <div style="text-align: center; margin-left: 10px; margin-top: 10px;">
    <img src="/images/sponsor/<?php echo $film["screening_film_sponsor_id"];?>/host_image.png" />
  </div>
  <?php } else { ?>
  <div id="qanda-inbox"><?php if (count($questions) > 0) {foreach($questions as $quest){ echo "<div class=\"question\" id=\"q".$quest["qanda_id"]."\">".$quest["qanda_body"]."</div>"; }}?></div>
  
  <p class="clear" id="qa-message">Type your question (5 remaining).</p>
  
  <form action="/services/QA" method="post" id="qandaform" name="qandaform">				
      <textarea id="qanda_message" name="body" class="grey"></textarea> 				
      <input id="qanda-submit" name="" value="post"  class="btn_og" type="button" />
      <input type="hidden" name="user_id" value="<?php echo $sf_user -> getAttribute("user_id");?>" />
      <input type="hidden" name="screening_id" value="<?php echo $film["screening_id"];?>" />
  </form>
  <?php } ?>
  
</div>
<!-- END Q AND A PANEL -->
<?php } ?>
	    
<!-- START CHAT PANEL -->
<div id="chat_panel" class="chat_box keeper" style="display: none">	        
    <div class="title">Chat</div>
    <h4>close</h4>
    <div id="inbox" class="inbox"></div>					
    <form action="/services/chat/post" method="post" name="chat_post" id="messageform">				
        <textarea id="message" name="body" class="grey"></textarea> 				
        <input id="chat-submit" name="" value="post"  class="btn_og" type="button" />
    </form>
    <div class="clear"></div>
</div>
<!-- END CHAT PANEL -->

<!-- START PRIVATE CHAT PANEL -->
<div id="private_chat_panel" class="chat_box killer" style="display: none">  	        
    <h2>Private Chat</h2>
    <h4>close</h4>
    <div id="private-inbox" class="inbox"></div>					
    <form action="/services/private/post" method="post" id="privateform">				
        <textarea id="private" name="privatebody" class="grey"></textarea> 				
        <input id="private-chat-submit" name="" value="post"  class="btn_og" type="submit" />
    </form>
</div>
<!-- END PRIVATE CHAT PANEL -->

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


<?php if (1 == 1) {//($vars["Theater"]["column2"][0]["host"] == $film["screening_film_id"]) {?>
<!-- START HOST ROOM PANEL -->
<div id="hostchat_panel" class="chat_box killer" style="display: none">
    <div class="title">Chat Rooms</div>
    <h4>close</h4>
    <ul id="chatroom-instances"></ul>
</div>
<!-- END ADMIN CHAT PANEL -->
<?php } ?>

<?php if ($sf_user->hasCredential(2)) {?>
<!-- START ADMIN CHAT PANEL -->
<div id="adminmessage_panel" class="chat_box killer" style="display: none">
    <div class="title">Users Online</div>
    <h4>close</h4>
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
<div id="adminmessage_panel" class="chat_box killer" style="display: none">        
    <div class="title">Chat with the Admin</div>
    <h4>close</h4>
    <div id="adminmessage-inbox" class="inbox"></div>					
    <form action="/services/adminmessage/post" method="post" id="adminmessageform">				
        <textarea id="adminmessage" name="adminmessagebody" class="grey"></textarea> 				
        <input id="adminmessage-chat-submit" name="" value="post"  class="btn_og" type="button" />
    </form>
</div>
<!-- END ADMIN CHAT PANEL -->
<?php } ?>

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
<span id="room" class="reqs"><?php echo $film["screening_unique_key"];?></span>
<span id="location" class="reqs">theater</span>
<?php if (isset($vars["Theater"]["column2"][0]["seat"])){?>
<span id="seat" class="reqs"><?php echo $vars["Theater"]["column2"][0]["seat"] -> getAudienceInviteCode();?></span>
<?php } ?>
<span id="userid" class="reqs"><?php echo $sf_user["user_id"];?></span>
<span id="pairid" class="reqs"></span>
<span id="toid" class="reqs"></span>
<span id="adminmessage-pairid" class="reqs"></span>
<span id="adminmessage-toid" class="reqs"></span>
<span id="video_data" class="reqs"><?php echo $vars["Theater"]["column2"][0]["video_data"];?></span>
    

<div id="host" class="reqs"><?php echo $chat_instance_host;?></div>
<div id="port" class="reqs"><?php echo $chat_instance_port_base;?></div>
<div id="instance" class="reqs"><?php echo $chat_instance_key;?></div>

<div id="status_panel">
  Status: Prescreening <span id="mini-timer"></span>
</div>

<!-- START ADMIN CHAT INVITE -->
<div id="adminmessage_invite">
 	<h2>Messages</h2>					
  <!--<h4>close</h4>-->
  <span id="adminmessage_invite_messages"></span>
</div>
<!-- END ADMIN CHAT INVITE -->

<!-- START PRIVATE CHAT INVITE -->
<div id="private_invite">
 	<h2>Private Invitation</h2>
  <h4>close</h4>
  <span id="private_invite_messages"></span>
</div>
<!-- END PRIVATE CHAT INVITE -->

<!-- TIMEZONE -->
<?php include_component('default', 
                        'Growler')?>
<!-- TIMEZONE -->	 		
