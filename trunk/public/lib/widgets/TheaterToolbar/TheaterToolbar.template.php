
<div class="bottomblock">
	
	<div id="user_wrap_left"><img src="/images/alt1/chat_left.png" /></div>
  <div class="userblock" style="display:none;">
		<div id="theater_icons">
			<?php /*
      for ($i = 1; $i < 60; $i++) {?>
			<div id="user_wrap_<?php echo $i;?>" class="theater_icon"><img alt="<?php echo $i;?>" src="https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png" id="user_image_<?php echo $i;?>" class="colorme_user"><div style="display: none;" class="tooltip">User <?php echo $i;?></div></div>
			<?php } 
      //*/?>
		</div>
	</div>
  <div id="user_wrap_right"><img src="/images/alt1/chat_right.png" /></div>
  
  <div class="colorblock">
  	<span class="colorme">Color Me</span>
  	<a href="javascript: void(0)"><span class="color_icon happy"></span></a>
  	<a href="javascript: void(0)"><span class="color_icon sad"></span></a>
  	<a href="javascript: void(0)"><span class="color_icon wow"></span></a>
  	<a href="javascript: void(0)"><span class="color_icon none"></span></a>
  	<a href="javascript: void(0)"><span class="color_icon heart"></span></a>
  	<a href="javascript: void(0)"><span class="color_icon quest"></span></a>
  </div>
  <?php /*
  <div class="countblock">
  	<!--Removed 11/4/2011 Bug 001973 -->
  	<!--<span class="user_count"><span id="user_count_num"><?php echo $vars["Theater"]["column2"][0]["screening_audience_count"];?></span> Viewers</span>-->
  </div>
  */?>
  <?php if ($promo) {?>
  <div class="promo">
  	<?php echo $promo;?>
  </div>
  <?php } ?>
  
	<a href="/film/<?php echo $vars["Theater"]["column2"][0]["film"]["screening_film_id"];?>">
	<div class="exit">EXIT</div>
  </a>
  
  <div class="volume_bar">
    <div id="mute" class="mutestyle"></div>
    <div id="slider"></div>
  </div>
  
</div>

