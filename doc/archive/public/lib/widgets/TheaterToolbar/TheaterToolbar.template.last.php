<p class="left">watching now: <span id="userCount_NEW"><?php echo $vars["Theater"]["column2"][0]["screening_audience_count"];?></span></p>
<a class="right downarr" href="#">close</a>

<div class="bottomblock">
  <div class="controller">
			<a class="seat_refresh" href="#"><img src="/images/icon-11.png" alt="Refresh" /></a>
      <a class="seat_surrender" href="#"><img src="/images/icon-9.png" alt="Leave Theater" /></a>
			<?php if(! $vars["sponsor"]): ?>
				<a class="facebook_tool" href="http://www.facebook.com/sharer.php?u=<?php echo urlencode('http://'.sfConfig::get("app_domain").'/film/'.$vars["Theater"]["column2"][0]["film"]["screening_film_id"].'/detail');?>&t=<?php echo urlencode("Constellation.tv presents: \"".$vars["Theater"]["column2"][0]["film"]["screening_film_name"]."\" viewed online for yourself or with your friends.");?>" target="_new"><img src="/images/icon-7.jpg" alt="Share on Facebook" /></a>
				<a class="twitter_tool"href="http://twitter.com/share?url=<?php echo ('http://'.sfConfig::get("app_domain").'/film/'.$vars["Theater"]["column2"][0]["film"]["screening_film_id"].'/detail');?>&text=<?php echo ("Constellation.tv presents: '".$vars["Theater"]["column2"][0]["film"]["screening_film_name"]."' viewed online for yourself or with your friends.");?>" target="_new"><img src="/images/icon-6.jpg" alt="Share on Twitter" /></a>
			<?php endif; ?>
      <!--<a class="chat_tool chat-click" href="#"><img src="/images/icon-5.jpg" alt="Chat" /></a>-->
      <?php if ($is_host) {?>
        <a class="hostchat_tool hostchat-click" href="#"><img src="/images/icon-10.jpg" alt="Q and A" /></a>
      <?php } ?>
      <a class="info_tool info-click" href="#"><img src="/images/icon-4.jpg" alt="Film Info" /></a>
      <?php if ($has_host) {?>
        <a class="qanda_tool qanda-click" href="#"><img src="/images/icon-3.jpg" alt="Q and A" /></a>
      <?php } ?>
      <a class="help_tool help-click" href="#"><img src="/images/icon-2.jpg" alt="Help" /></a>
      <!--<a class="constellation_tool constellation-click" href="#"><img src="/images/icon-1.jpg" alt="" /></a>-->
      <?php if ($sf_user->hasCredential(2)) {?>
      <!--<a class="admin_tool admin-click" href="#"><img src="/images/icon-8.jpg" alt="" /></a>-->
      <?php } ?>
      <!--<a class="test_tool test-click" href="#"><img src="/images/icon-8.jpg" alt="" /></a>-->
  </div>
  
  <div class="volume_bar">
    <div id="mute" class="mutestyle">
    </div>
    <div id="slider"></div>
  </div>
  
  <!--<div class="full_screen">
    <img id="fullscreen" src="/images/fullscreen.png" />
  </div>-->
</div>

