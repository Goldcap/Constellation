<?php //dump($events);?>
<?php 
$isLoggedIn = ($sf_user -> isAuthenticated()) && ((is_null($sf_user -> getAttribute("temp"))) || (! $sf_user -> getAttribute("temp")));
?>
<div class="home-hero">
	<div class="content_inner clearfix" id="home-slide-ui">	

		<div class="slide-container">
			<div class="slide-4"></div>
			<div class="slide-1" style="display: none"></div>
			<div class="slide-2" style="display: none"></div>
			<div class="slide-3" style="display: none"></div>
		</div>
		<div class="emoicon1"></div>
		<div class="emoicon2"></div>
		<div class="emoicon3"></div>
		<div class="emoicon4"></div>

	</div>
</div>
<?php if (!$isLoggedIn) :?>
			<div class="hero-content-signup">
	<div class="content_inner clearfix">	
				<a href="/services/Facebook/login?dest=<?php echo urlencode('http://' . sfConfig::get("app_domain") . $_SERVER["REQUEST_URI"]);?>" class="button button-facebook"><span class="icon-facebook"></span>Sign up with Facebook</a>
				<span class="or">Or</span>
				<a href="/services/Twitter/login?dest=<?php echo urlencode('http://' . sfConfig::get("app_domain") . $_SERVER["REQUEST_URI"]);?>" class="button button-twitter"><span class="icon-twitter"></span>Sign up with Twitter</a>
	</div>
			</div>
<?php endif;?>
<div id="events">
	<div class="events-filter ">
		<div class="content_inner clearfix">
			<ul class="left events-filter-buttons button-set">
				<li class="button button-black button-medium active" id="upcoming-button">Upcoming Events</li>
				<li  class="button button-black button-medium" id="past-button">Previous Events</li>
			</ul>
			<div class="right events-search-container">
				<input type="text" id="events-search" class="events-filter-input" value="" placeholder="Search for upcoming events..." />
				<span class="events-search-delete" id="search-delete" style="display: none"></span>
			</div>
		</div>
	</div>
	<div id="events-container" class="content_inner events_content_inner clearfix">
		<?php if($events):?>
		<?php foreach ($events as $event):?>
			<div class="event-module">
				<div class="event-module-hero">
					<a href="/event/<?php echo $event['screening_unique_key']?>">
						<?php if($event['screening_still_image'] != ''):?>
						<img width="300" height="150" src="/uploads/screeningResources/<?php echo $event['screening_film_id'];?>/screenings/screening_event_<?php echo $event['screening_still_image'];?>" />
						<?php else :?>
						<img width="300" height="150" src="/uploads/screeningResources/<?php echo $event['screening_film_id'];?>/logo/wide_poster<?php echo $event['screening_film_homelogo'];?>" />
						<?php endif;?>
						<?php if($event['is_inprogress'] ):?>
						<span class="event-module-hero-in-progress">In Progress</span>
						<?php endif;?>
						<div class="event-module-hero-overlay">
							<span class="button button-medium button-black">View Event</span>
						</div>
					</a>
				</div>
				<div class="section-container">

					<?php if(!empty($event['screening_user_full_name'])):?>
					<div class="event-section clearfix">
						<a href="/event/<?php echo $event['screening_unique_key'] ;?>">
						<img src="<?php echo getShowtimeUserAvatar($event,"medium");?>" height="50" width="50" class="event-module-avatar"/>
						</a>
						<p class="event-module-title event-module-title-img"><a href="/event/<?php echo $event['screening_unique_key']?>"><?php echo !empty( $event['screening_name']) ? $event['screening_name'] : ($event['screening_user_full_name'] != '' ? 'Live with '. $event['screening_user_full_name']: $event['screening_film_name'] )?></a></p>

					</div>
					<?php else:?>
					<div class="event-section">
						<p class="event-module-title"><a href="/event/<?php echo $event['screening_unique_key']?>"><?php echo !empty( $event['screening_name']) ? $event['screening_name'] : $event['screening_user_full_name'] . ' hosts ' . $event['screening_film_name']; ?></a></p>
					</div>
					<?php endif;?>
					<div class="event-section">
						<p class="event-module-time"><span class="icon-calendar"></span><?php echo date("D, M dS \@ g:i A T", strtotime($event['screening_date'])) ;?></p>
					</div>
					<div class="event-section">
						<?php if($event['screening_film_free_screening'] == 1):?>
						<p class="event-module-price"><span class="icon-ticket"></span>This event is <strong>FREE</strong></p>
						<?php elseif($event['screening_film_free_screening'] == 2):?>
						<p class="event-module-price"><span class="icon-ticket"></span>This event is only available via code</p>
						<?php else:?>
						<p class="event-module-price"><span class="icon-ticket"></span>Tickets to this event are $<?php echo $event["screening_film_ticket_price"];?></p>
						<?php endif;?>
					</div>
					<div class="event-section event-module-description">
						<?php if($event['screening_description']):?>
						<p><?php echo str_replace(array( "\n"), '</p><p>', $event["screening_description"]);?></p>
						<?php else :?>
						<p><?php echo str_replace(array( "\n"), '</p><p>', $event["screening_film_info"]);?></p>
						<?php endif;?>
						<p class="event-module-more"><a href="/event/<?php echo $event["screening_unique_key"]?>">Learn more about this event &rarr;</a></p>
					</div>
					<div class="event-section event-section-last">
						<?php if($event['is_attending']):?>
						<a class="button button-green button-medium button-block center" href="/theater/<?php echo $event['screening_unique_key']?>">Enter Event</a>
						<?php else:?>
						<a class="button button-blue button-medium button-block center" href="/boxoffice/screening/<?php echo $event['screening_unique_key']?>">RSVP To Event</a>
						<?php endif;?>
					</div>
				</div>
			</div>
		<?php endforeach;?>
		<?php else: ?>
		<div class="events-no-result"><h2>There are currently no upcoming events.</h2><p>Check back later or browse past events.</p></div>
		<?php endif;?>
	</div>
	<div class="clear clearfix content_inner">
		<span id="more-button" class="button button-black" <?php echo $eventsCount > 9 ? '' : 'style="display: none"';?>>View More Events</span>
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
