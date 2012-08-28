<div id="qa-container" class="qa-container">
	<div class="film-details  clearfix" id="qa-lobby">
		<div class="poster-wrap">
			<img class="poster" src="/uploads/screeningResources/<?php echo $film["screening_film_id"];?>/logo/small_poster<?php echo $film["screening_film_logo"];?>" alt="<?php echo $film["screening_film_name"];?>" class="widget_video_still"   width="189" height="280" border="0"/>
		</div>

		<div class="film-text">
			<h1 class="film-title"><?php echo $film["screening_film_name"];?></h1>
			<?php if ($film["screening_user_full_name"] != '') :?>
				<div class="film-host clearfix">
					<a href="/profile/<?php $film['screening_user_id']?>" target="_blank">
					<img src="<?php echo getShowtimeUserAvatar($film, 'large');?>" height="48" width="48"/>
	 				</a>
					<p>Hosted By</p>
					<h2><a href="/profile/<?php $film['screening_user_id']?>" target="_blank"><?php echo $film["screening_user_full_name"];?></a></h2>

				</div>
			<?php endif; ?>
			<div class="qa-notice">
				The Q&amp;A will start shortly.
			</div>
			<span class="button button_blue uppercase" id="qa-start">Start Q&amp;A</span>
		</div>
	</div>
	<div class="clearfix" id="qa-panel">
		<div class="clearfix">
		<div id="qa-video-container" class="qa-video-container">
			<div id="qa-video-placeholder"></div>
		</div>
		<div  class="qa-question-container">
			<!-- <h2>Q&amp;A <?php echo $film["screening_user_full_name"] != ''? 'with ' .$film["screening_user_full_name"] : ''?></h2> -->
			<div id="qa-question" class="clearfix">
				<h3 class="qa-no-selection"><?php echo $film["screening_user_full_name"] != ''? $film["screening_user_full_name"] : 'The host'?> has not selected a question yet. </h3>
			</div>
		</div>
		<!--<div  class="qa-poll-container">
			<div id="poll-meter"></div>
			<div class="poll-meter-bottom clearfix"><span class="button button_blue left poll-button-hands" id="poll-no"><span class="poll-icon-hands poll-icon-hands-down"></span></span><span class="button button-red right poll-button-hands" id="poll-yes"><span class="poll-icon-hands poll-icon-hands-up"></span></span><div class="poll-text">Vote</div></div>
		</div>-->
	</div>
	<div class="hr"></div>
	<div id="qa-input-container-viewer" class="qa-input-container clearfix">
		<div class="qa-input-image">
			<img src="<?php echo getSessionAvatar($sf_user, 'large');?>" height="64" width="64" class="avatar" />
		</div>
		<textarea id="qa-input" class="chat-input" placeholder="Ask your Question"></textarea>
		<span class="button button-blue uppercase" id="qa-submit">Submit</span>
	</div>
	<div id="qa-input-container-success">
		<p class="m-p link">Thanks! Your question has been received.</p>
		<p class="m-p">It is being reviewed by the host.  Stay tuned!</p>
	</div>
	<div id="qa-input-container-host" class="qa-input-container clearfix">
		<div class="left" id="qa-question-preview">
			<p class="qa-no-selection">There are no questions in the queue</p>
		</div>
		<div class="right">
			<div class="">
			<span class="button button-blue uppercase button-medium" id="qa-previous">&laquo;</span>
			<span class="button button-blue uppercase button-medium" id="qa-next">&raquo;</span>
			</div>
			<div class="clearfix">
				<span class="button button-green uppercase clear button-medium" id="qa-pick">Answer</span>
			</div>
			<div class="clearfix" style="margin-top: 10px">
			</div>
		</div>
		<!--<div class="clear clearfix" style="padding-top: 10px">
			<span class="button button_blue uppercase clear right button-medium" id="qa-poll"> Start Poll</span>
			<p class="qa-no-selection" style="line-height:34px;margin-right: 10px" id="qa-poll-text-open">Ask your viewers a question and get their reaction with a poll.</p> 
			<p class="qa-no-selection" style="line-height:34px;margin-right: 10px; display: none" id="qa-poll-text-close">To answer the next question, close the poll.<p>
		</div>-->
	</div>
	</div>
</div>

