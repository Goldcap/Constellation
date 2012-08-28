<?php //dump($purchases);?>
<div id="events">
<div id="events-container" class="content_inner events_content_inner clearfix">
	<?php if($hosting["meta"]["totalresults"] > 0 || $purchases["meta"]["totalresults"] > 0):?>
	<?php if($hosting["meta"]["totalresults"] > 0):?>
	<?php foreach ($hosting['data'] as $event):?>
		<div class="event-module">
			<div class="event-module-hero">
				<a href="/event/<?php echo $event['audience_screening_unique_key']?>"><img width="300" height="150" src="/uploads/screeningResources/<?php echo $event['audience_film_id'];?>/logo/wide_poster<?php echo $event['audience_film_homelogo'];?>" /></a>
			</div>
			<div class="section-container">

				<?php if(!empty($event['audience_screening_user_full_name'])):?>
				<div class="event-section clearfix">
					<a href="/event/<?php echo $event['audience_screening_unique_key']?>">
					<img src="<?php echo getAudienceUserAvatar($event,"medium");?>" height="50" width="50" class="event-module-avatar"/>
					</a>
					<p class="event-module-title event-module-title-img"><a href="/event/<?php echo $event['audience_screening_unique_key']?>"><?php echo $event['audience_screening_name'] !='' ? $event['audience_screening_name'] : ($event['audience_screening_user_full_name'] != '' ? 'Live with '. $event['audience_screening_user_full_name']: $event['audience_film_name'] )?></a></p>
					<?php if($event['audience_screening_user_tagline'] != '') :?>
					<p class="event-module-user-title"><?php echo $event['audience_screening_user_tagline'] ;?></p>
					<?php endif;?>
				</div>
				<?php else:?>
				<div class="event-section">
					<p class="event-module-title"><a href="/event/<?php echo $event['audience_screening_unique_key']?>"><?php echo !empty( $event['audience_screening_name']) ? $event['audience_screening_name'] : $event['screening_user_full_name'] . ' hosts ' . $event['screening_film_name']; ?></a></p>
				</div>
				<?php endif;?>
				<div class="event-section">
					<p class="event-module-time"><span class="icon-calendar"></span><?php echo date("D, M dS \@ g:i A T", strtotime($event['audience_screening_date'])) ;?></p>
				</div>
				<div class="event-section event-module-description">
					<?php if($event['audience_screening_description']):?>
					<p><?php echo str_replace(array( "\n"), '</p><p>', $event["audience_screening_description"]);?></p>
					<?php else :?>
					<p><?php echo str_replace(array( "\n"), '</p><p>', $event["audience_screening_film_info"]);?></p>
					<?php endif;?>
				</div>
				<div class="event-section event-section-last center">
					<a class="button button-medium button-green button-block" href="/theater/<?php echo $event['audience_screening_unique_key']?>">Enter Event</a>
				</div>
			</div>
		</div>
	<?php endforeach;?>
	<?php endif;?>
	<?php if($purchases["meta"]["totalresults"] > 0):?>
	<?php foreach ($purchases['data'] as $event):?>
		<div class="event-module">
			<div class="event-module-hero">
				<a href="/event/<?php echo $event['audience_screening_unique_key']?>"><img width="300" height="150" src="/uploads/screeningResources/<?php echo $event['audience_film_id'];?>/logo/wide_poster<?php echo $event['audience_film_homelogo'];?>" /></a>
			</div>
			<div class="section-container">

				<?php if(!empty($event['audience_screening_user_full_name'])):?>
				<div class="event-section clearfix">
					<a href="/event/<?php echo $event['audience_screening_unique_key']?>">
					<img src="<?php echo getAudienceUserAvatar($event,"medium");?>" height="50" width="50" class="event-module-avatar"/>
					</a>
					<p class="event-module-title event-module-title-img"><a href="/event/<?php echo $event['audience_screening_unique_key']?>"><?php echo $event['audience_screening_name'] !='' ? $event['audience_screening_name'] : ($event['audience_screening_user_full_name'] != '' ? 'Live with '. $event['audience_screening_user_full_name']: $event['audience_film_name'] )?></a></p>
					<?php if($event['audience_screening_user_tagline'] != '') :?>
					<p class="event-module-user-title"><?php echo $event['audience_screening_user_tagline'] ;?></p>
					<?php endif;?>
				</div>
				<?php else:?>
				<div class="event-section">
					<p class="event-module-title"><a href="/event/<?php echo $event['audience_screening_unique_key']?>"><?php echo !empty( $event['audience_screening_name']) ? $event['audience_screening_name'] : $event['screening_user_full_name'] . ' hosts ' . $event['screening_film_name']; ?></a></p>
				</div>
				<?php endif;?>
				<div class="event-section">
					<p class="event-module-time"><span class="icon-calendar"></span><?php echo date("D, M dS \@ g:i A T", strtotime($event['audience_screening_date'])) ;?></p>
				</div>
				<div class="event-section event-module-description">
					<?php if($event['audience_screening_description']):?>
					<p><?php echo str_replace(array( "\n"), '</p><p>', $event["audience_screening_description"]);?></p>
					<?php else :?>
					<p><?php echo str_replace(array( "\n"), '</p><p>', $event["audience_screening_film_info"]);?></p>
					<?php endif;?>
				</div>
				<div class="event-section event-section-last center">
					<a class="button button-medium button-green button-block" href="/theater/<?php echo $event['audience_screening_unique_key']?>">Enter Event</a>
				</div>
			</div>
		</div>
	<?php endforeach;?>
	<?php endif;?>

	<?php else :?>
		<div class="events-no-result"><h2>You currently are not attended any events</h2></div>
	<?php endif;?>
</div>
</div>
<script>

require(['CTV/Controller/Events'], function(Controller){

	Handlebars.registerHelper('eventDecription', function(description) {
	  return new Handlebars.SafeString(description.replace('\n', '</br></br>'));
	});

	new Controller();
	
});
</script>