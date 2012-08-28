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
        <ul id="qa_showing_list">
          <?php if (count($selected) > 0) {foreach($selected as $quest){ 
          if ($quest["qanda_current"] == 1) {          
            echo "<li id=\"qe-".$quest["qanda_id"]."\">".(($quest["user_username"] == '') ? "Anonymous" : "'".$quest["user_username"]."'")." asked \"" .$quest["qanda_body"]. "\"</li>"; 
          }}}?>
        </ul>  
      </div>
    	<div class="top_control">
    	 <input type="button" id="host_submit" value="clear" class="btn_med" name="" onclick="qanda.clearSlides();" />
    	</div>
    </div>
    <div class="myquestions">
    	  <p>QUESTIONS FROM AUDIENCE&nbsp;&nbsp;<span style="font-size: 12px; color: white">(Click on a question to answer it.)</span></p>
        <div class="vscroller">
        <ul id="qanda-host-inbox" class="connectedSortable inbox">
          <?php if (count($questions) > 0) {foreach($questions as $quest){ 
          echo "<li class=\"question\" id=\"q".$quest["qanda_id"]."\">
                  <table border=\"0\" padding=\"1\">
                  <td class=\"handle\" valign=\"top\">";
          if ($quest["qanda_answered"] == 1) {
            echo "<img onclick=\"qanda.showSlide('".$quest["qanda_id"]."')\" src=\"/images/dnd-stop.png\" />";
          } else {
            echo "<img onclick=\"qanda.showSlide('".$quest["qanda_id"]."')\" src=\"/images/dnd-go.png\" />";
          }
          echo "</td>
                  <td valign=\"top\" class=\"sequence\" style=\"display:none\">".
                  $quest["qanda_sequence"].":
                  </td>
                  <td class=\"quest\" ><a href=\"javascript: void(0);\" onclick=\"qanda.showSlide('".$quest["qanda_id"]."')\">".
                  $quest["qanda_body"].
                "</a></td>
                </tr>
                </table>
                </li>"; 
          }}?>
        </ul>
        </div>
    </div>
    <div class="push_buttons">
      <input type="button" id="host_screening_webcam" value="disable webcam" class="btn_med-og" name="" />
      <input type="button" id="host_screening_qanda" value="exit q+a" class="btn_med" name="" />
      <input type="button" id="host_screening_end" value="end screening" class="btn_med" name="" style="display: none;" />
      <div style="float: left">Click and drag to move the Q+A window. <?php if ($has_video) {?>You may need to grant access to your webcam in order to broadcast video.<? } ?></div>
    </div>
  </div>
  <div class="qa_pop_bot"></div>
  </div>    
</div>
<!-- END Q AND A HOST CONTROL POPUP -->

<?php if ($film["screening_has_qanda"] == 0) {?>
<!-- Q AND A HOST PANEL -->	 	    
<div id="qanda_panel" class="chat_box keeper" style="display: none">  	      
  
  <div style="text-align: center; margin-top: 10px;">
    <?php if ($film["screening_guest_image"] != '') {?>
      <img height="120" src="/uploads/<?php echo $film["screening_guest_image"];?>" />
    <?php } else {?>
      <img class="host" height="120" src="/images/constellation_host.jpg" />	
    <?php } ?>
  </div>
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
<style>
#qanda_panel {
  visibility: hidden;
  height: 0px;
  width: 0px;
}
</style>
<div id="qanda_panel" class="chat_box keeper" style="display: none"></div>
<?php } ?>
<!-- END Q AND A HOST PANEL -->

<?} else {?>

<!-- Q AND A CONTROL POPUP -->
<div class="qa_pop_up" id="q_and_a_control" style="display: none">
  <div class="qa_pop_mid">
  <div class="qa_pop_top popup-close"><a href="#">close</a></div>
	<div class="layout-1 clearfix">
    <div class="chalkboard">
    	<div class="title_white"><strong>THE HOST IS CURRENTLY ANSWERING</strong></div>
    	<div class="questions" id="qa_showing_questions">
        <ul id="qa_showing_list">
        <?php if (count($selected) > 0) {foreach($selected as $quest){ 
          if ($quest["qanda_current"] == 1) {          
            echo "<li id=\"qe-".$quest["qanda_id"]."\"><strong><span class=\"question_author\">".(($quest["user_username"] == '') ? "Anonymous" : $quest["user_username"])." asked, \"</span><span class=\"question_question\">" .$quest["qanda_body"]. "</span>\"</strong></li>"; 
        }}}?>
        </ul>
      </div>
    </div>
    <!--<div class="myquestions">
      <div class="title_blue"><strong>MY QUESTIONS</strong></div>
    	<div class="questions" id="qa_showing_questions">
        <ul id="qa_list">
        <?php if (count($questions) > 0) {foreach($questions as $quest){ 
            echo "<li id=\"qe-".$quest["qanda_id"]."\">You asked \"" .$quest["qanda_body"]. "\"</li>"; 
        }}?>
        </ul>
      </div>
    	<div class="top_control">
    	</div>
    </div>-->
    <div class="push_buttons">
      <input type="button" id="host_screening_refresh" value="refresh" class="btn_med-og" name="" />
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
  
  <?php if ($film["screening_has_qanda"] == 0) {?>
  
    <div style="text-align: center; margin-left: 10px; margin-top: 10px;">
      <img src="/images/sponsor/<?php echo $film["screening_film_sponsor_id"];?>/host_image.png" />
    </div>
  
  <?php } else { ?>
    
    <!--<div id="qanda-inbox"><?php if (count($questions) > 0) {foreach($questions as $quest){ echo "<div class=\"question\" id=\"q".$quest["qanda_id"]."\">".$quest["qanda_body"]."</div>"; }}?></div>-->
    
    <p class="clear" id="qa-message" style="display:none">Type your question (5 remaining).</p>
    
    <form action="/services/QA" method="post" id="qandaform" name="qandaform">				
        <textarea id="qanda_message" name="body" class="grey"></textarea> 				
        <input id="qanda-submit" name="" value="SUBMIT A QUESTION"  class="btn_og" type="button" />
        <input type="hidden" name="user_id" value="<?php echo $sf_user -> getAttribute("user_id");?>" />
        <input type="hidden" name="screening_id" value="<?php echo $film["screening_id"];?>" />
    </form>
  <?php } ?>
  
</div>
<!-- END Q AND A PANEL -->

<?php } ?>
     
<!-- START CHAT PANEL -->
<div id="chat_panel" class="chat_box" style="display: none">
    <div class="title">Chat <img id="chat_refresh" src="/images/Neu/16x16/actions/gtk-refresh.png" /></div>
    <!--<h4>close</h4>-->
    <div id="inbox" class="inbox"></div>					
    <?php if (! isset($auth_msg)) {?>
    <form action="/services/chat/post" method="post" name="chat_post" id="messageform">				
        <textarea id="message" name="body" class="grey"></textarea> 				
        <input id="chat-submit" name="" value="post"  class="btn_og" type="button" />
    </form>
    <?php } ?>
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

<?php if ($vars["Theater"]["column2"][0]["host"] == $film["screening_film_id"]) {?>
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
<span id="movie_type" class="reqs"><?php echo ($film["screening_film_movie_file"] == "") ? "mp4" : $film["screening_film_movie_file"];?></span>
<span id="room" class="reqs"><?php echo $film["screening_unique_key"];?></span>
<span id="qscreening_id" class="reqs"><?php echo $film["screening_id"];?></span>
<span id="bitrates" class="reqs"><?php echo $film["screening_film_bitrate_small"];?>,<?php echo $film["screening_film_bitrate_medium"];?>,<?php echo $film["screening_film_bitrate_large"];?></span>
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

<!-- START LOGIN POPUP -->
<div class="pop_up" id="main-login-popup" style="display: none;">
  <div class="pop_mid">
  <div class="pop_top popup-close"><!--<a href="#">close</a>--></div>
  	<div class="layout-1 clearfix">
  	 <?php if ($sf_user -> isAuthenticated()) {?>
  	 <div id="log-in">
      <div class="title"><strong><?php echo $auth_msg;?></strong></div><br />
      <?php echo $auth_text;?>
      <?php echo $auth_link;?>
     </div>  
  	 <?php } else { ?>
  	  <div id="log-in">
        <div class="title"><strong><?php echo $auth_msg;?></strong></div><br />
        <?php echo $auth_text;?>
      	<?php echo $auth_link;?>
        <form id="login_form" name="login_form" action="/services/Login" method="POST" class="login_form">
        	<div>
            <input name="email" id="login_email" class="input login-element" type="text" />
            <input name="password" id="login_password" class="input login-element" type="password" />
            <input name="login" id="login-button" class="btn_small" value="log in" type="submit" />
          </div>
          <?php if ((isset($_GET["err"])) && ($_GET["err"] == 'login')) {?>
          <script type="text/javascript">
            $(document).ready(function() {
            <?php if ($_GET["errs"] == 'pass') {?>
            error.showError('error','Your password is incorrect, please try again.');
            <?php } elseif ($_GET["errs"] == 'email') {?>
            error.showError('error','Your email wasn\'t found, please try again.');
            <?php } else{ ?>
            error.showError('error','There was an error, please try again.');
            <?php }?>
            });
          </script>
          <?php }?>
          <input type="hidden" value="<?php echo $_SERVER["REQUEST_URI"];?>" name="source" />
          <input type="hidden" id="login_destination" value="<?php echo $_SERVER["REQUEST_URI"];?>" name="destination" />
          <input type="hidden" value="true" name="indirect" />
          <input type="hidden" value="true" name="popup" />
          <input type="hidden" value="login" name="type" />
            
        </form>
        
        <div class="or_login_w">&nbsp;
          or log in with: &nbsp;&nbsp;&nbsp;<a style="overflow: hidden;" href="/services/Facebook/login?dest=http://<?php echo $_SERVER["SERVER_NAME"];?><?php echo $_SERVER["REQUEST_URI"];?>"><img src="/images/icon_face-lg.gif" alt="" /></a> <a style="overflow: hidden;" href="/services/Twitter/login?dest=http://<?php echo $_SERVER["SERVER_NAME"];?><?php echo $_SERVER["REQUEST_URI"];?>"> <img src="/images/icon_twit-lg.gif" alt="" /></a>
        </div>
        <div class="forgot_sign-up"><a href="#" id="main-choose-password">forgot password?</a> | <a id="main-choose-signup" href="#">sign up</a><a id="main-choose-login" href="#" style="display:none">log in</a></div>
      </div>
      
      <div id="password-out" style="display: none">
        <!--<div class="title"><strong>Login</strong></div><br />-->
        <form id="password_form" name="password_form" action="#" method="POST" class="password_form">
        	<div>
            <input name="email" id="password_email" class="input password-element" type="text" />
            <input name="send" id="password-button" class="btn_small" value="send" type="button" />
          </div>
        </form>
      </div>
      
      <div id="sign-up" style="display: none">
        <!--<div class="title"><strong>Sign-up</strong></div><br />-->
        <form id="sign-up_form" name="sign-up_form" action="/services/Join" method="POST" class="sign-up_form">
          	<fieldset>
              	<div class="clearfix"><label>Your Name</label> <input id="main-signup-name" value="" name="name" type="text" class="input signup-element" /></div>
                  <div class="clearfix"><label>Username</label> <input id="main-signup-username" value="" name="username" type="text" class="input signup-element" /></div>
                  <div class="clearfix"><label>Birthday</label> 
                    <select name="month">
                      <option value="01" >Jan</option>  						           				
                      <option value="02" >Feb</option>  						           				
                      <option value="03" >Mar</option>  						           				
                      <option value="04" >Apr</option>  						           				
                      <option value="05" >May</option>  						           				
                      <option value="06" >Jun</option>  						           				
                      <option value="07" >Jul</option>  						           				
                      <option value="08" >Aug</option>  						           				
                      <option value="09" >Sep</option>  						           				
                      <option value="10" >Oct</option>  						           				
                      <option value="11" >Nov</option>  						           				
                      <option value="12" >Dec</option>  				
                    </select> 
                    <select name="day">
                      <option value="01" >01</option>  						           				
                      <option value="02" >02</option>  						           				
                      <option value="03" >03</option>  						           				
                      <option value="04" >04</option>  						           				
                      <option value="05" >05</option>  						           				
                      <option value="06" >06</option>  						           				
                      <option value="07" >07</option>  						           				
                      <option value="08" >08</option>  						           				
                      <option value="09" >09</option>  						           				
                      <option value="10" >10</option>  						           				
                      <option value="11" >11</option>  						           				
                      <option value="12" >12</option>  						           				
                      <option value="13" >13</option>  						           				
                      <option value="14" >14</option>  						           				
                      <option value="15" >15</option>  						           				
                      <option value="16" >16</option>  						           				
                      <option value="17" >17</option>  						           				
                      <option value="18" >18</option>  						           				
                      <option value="19" >19</option>  						           				
                      <option value="20" >20</option>  						           				
                      <option value="21" >21</option>  						           				
                      <option value="22" >22</option>  						           				
                      <option value="23" >23</option>  						           				
                      <option value="24" >24</option>  						           				
                      <option value="25" >25</option>  						           				
                      <option value="26" >26</option>  						           				
                      <option value="27" >27</option>  						           				
                      <option value="28" >28</option>  						           				
                      <option value="29" >29</option>  						           				
                      <option value="30" >30</option>  						           				
                      <option value="31" >31</option> 
                    </select> 
                    <select name="year">
                      <?php for($yr=1920;$yr<=year();$yr++) {?>
                      <option  value="<?php echo $yr;?>" <?php if ($yr == 1970) { echo "selected='selected'"; }?> ><?php echo $yr;?></option>						     			     
                      <?php } ?>
                    </select>
                  </div>
                  <div class="clearfix"><label>Email Address</label> <input id="main-signup-email" value="" name="email" type="text" class="input signup-element" /></div>
                  <div class="clearfix"><label>Password</label> <input id="main-signup-password" name="password" type="password" class="input signup-element" /></div>
                  <div class="clearfix"><label>Confirm Password</label> <input id="main-signup-password2" name="password2" type="password" class="input signup-element" /></div>
                  <div class="button"><input name="signup" id="signup-button" class="btn_small" value="sign up" type="submit" /></div>
              </fieldset>
              <input type="hidden" value="<?php echo $_SERVER["REQUEST_URI"];?>" name="source" />
              <input type="hidden" id="signup_destination" value="<?php echo $_SERVER["REQUEST_URI"];?>" name="destination" />
              <input type="hidden" value="true" name="indirect" />
              <input type="hidden" value="true" name="popup" />
              <input type="hidden" value="signup" name="type" />
              <?php if ((isset($_GET["err"])) && ($_GET["err"] == 'signup')) {?>
              <script type="text/javascript">
                error.showError('error','There was an error with your signup information, please try again.');
              </script>
              <?php }?>
          </form>
          <!--<div class="forgot_sign-up"><a href="#">forgot password?</a> | <a id="main-choose-login" href="#">log in</a></div>-->
        </div>
        <?php } ?>
      </div>
  <div class="pop_bot"></div>
  </div>    
</div>

<?php if ((isset($_GET["err"])) && (! $sf_user -> isAuthenticated())) {?>
<script type="text/javascript">
$(document).ready( function() {
  <?php if ($_GET["err"]=='signup') {?>
	login.showsignup();
  <?php } else {?>
  login.showpopup();
  <?php } ?>
});
</script>
<?php } ?>
<!-- END LOGIN POPUP -->

<?php if ($sf_user -> isAuthenticated()) {?>
	
  <!-- PURCHASE POPUPS -->
  <?php include_component('default', 
                          'Purchase', 
                          array('film'=>$film,
                                'states'=>$states,
																'countries'=>$countries,
																'post'=>$post))?>
  <!-- PURCHASE POPUPS -->
	
	<div id="screening" style="display:none"><?php echo $film["screening_unique_key"];?></div>
  <div id="time_<?php echo $film["screening_unique_key"];?>" style="display:none"><?php echo formatDate($film["screening_date"],"prettyshort");?></div>
  <div id="cost_<?php echo $film["screening_unique_key"];?>" style="display:none"><?php echo $film["screening_film_ticket_price"];?></div>
  <div id="host_<?php echo $film["screening_unique_key"];?>" style="display:none"><?php echo $film["screening_user_full_name"];?></div>
<?php } ?>
