<div class="aside-slider">
<div id="host-container" class="host-container aside">
	<h2 class="clearfix"><span class="icon-toggle active" id="toggle-host" title="Collapse/Expand"></span><span class="icon-host"></span>Host <strong><?php echo $film["screening_user_full_name"] != '' ? $film["screening_user_full_name"]: '';?></strong></h2>
	<div id="host-player-placeholder"></div>
</div>
<div id="chat-container" class="chat-container aside">
	<h2 class="clearfix"><span class="chat-room right ">Change Rooms</span><span class="icon-chat"></span>Chat</h2>
	<div id="chat-content" class="chat-list nano">
		<div class="content"></div>
	</div>
	<div id="room-content" class="room-list nano">
		<div class="content"></div>
	</div>
	<div class="chat-input-container">
		<textarea id="chat-input" class="chat-input" resize="none" placeholder="Type message here ..."></textarea>
	</div>
</div>

		<div class="colorblock" id="color-container">
		  	<span class="color-text">Mood?</span>
		  	<span class="color-icon"><span class="color_icon happy use-tip" title="Happy" data-color="happy"></span></span>
		  	<span class="color-icon"><span class="color_icon sad use-tip" title="Sad" data-color="sad"></span></span>
		  	<span class="color-icon"><span class="color_icon wow use-tip" title="Suprised" data-color="wow"></span></span>
		  	<span class="color-icon"><span class="color_icon none use-tip" title="Confused" data-color="none"></span></span>
		  	<span class="color-icon"><span class="color_icon heart use-tip" title="Romantic" data-color="heart"></span></span>
		  	<!-- <span class="color-icon"><span class="color_icon quest use-tip" title="Curious" data-color="quest"></span></span> -->
		</div>

<div class="slider-border" id="slider"></div>
</div>