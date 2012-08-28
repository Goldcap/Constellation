<?php //dump($film);?>
<?php //dump($event);?>
<?php //dump($host);?>
<?php //dump(strtotime($event["screening_date"])) ;?>
<?php 
if (isset ($form) ) {echo $form;}
$isLoggedIn = false;
if (($sf_user -> isAuthenticated()) && ((is_null($sf_user -> getAttribute("temp"))) || (! $sf_user -> getAttribute("temp")))){
	$isLoggedIn = true;
}
$twitterText = 'Join me at the event "'. ($event["screening_name"] !='' ? $event["screening_name"] : $event["screening_film_name"]).'". Join here: http://www.constellation.tv/event/' . $event['screening_unique_key'] ;
if($event['screening_twitter_text'] != ''){
	$twitterText = $event['screening_twitter_text'];
}

$facebookDescription = $event["screening_name"] != '' ? $event["screening_name"] : $event["screening_film_name"]. ' on Constellation';
if($event['screening_facebook_text'] != ''){
	$facebookCaption = $event['screening_facebook_text'];
} elseif ($event["screening_user_full_name"] != ''){
	$facebookCaption = $event["screening_user_full_name"] . ' is live on Constellation presenting ' . $event["screening_film_name"] .'.';
} else if($event["screening_description"] != '') {
	$facebookCaption = $event["screening_description"];
}

?>
<div class="content_inner event_content_inner clearfix">
	<div class="event_content">
		<div class="event-section">
			<?php if($event["screening_id"] == 23356):?>
            <img src="/images/pages/oddlife/oddlife_header.gif" />
            <?php elseif($event["screening_name"] !=''):?>
			<h1 class="event-title"><?php echo $event["screening_name"];?></h1>
			<?php else :?>
			<h1 class="event-title"><?php echo $event["screening_film_name"];?></h1>
			<?php endif;?>
			<p class="event-time"><span class="icon-calendar"></span><?php echo date("D, F dS \@ g:i A T",strtotime($event['screening_date'])) ;?></p>

			<?php if($gbip):?>
		    <div class="error-block clear">
		        This event cannot be accessed in your current location.
		    </div>
			<?php elseif (strtotime($event["screening_end_time"]) + 120 < strtotime(date("Y-m-d H:i:s"))) :?>
			<div class="event-ticket-container clearfix">
				This event is over. <a href="/#events">Find more events &rarr;</a> 
			</div>
			<?php else:?>
				<?php if($event['screening_film_free_screening'] == 1):?>
				<p class="event-price"><span class="icon-ticket"></span>This event is <strong>FREE</strong></p>
				<?php elseif($event['screening_film_free_screening'] == 2):?>
				<p class="event-price"><span class="icon-ticket"></span>This event is only available via code</p>
				<?php else:?>
				<p class="event-price"><span class="icon-ticket"></span>Tickets to this event are $<?php echo $event["screening_film_ticket_price"];?></p>
				<?php endif;?>

				<div class="event-ticket-container clearfix">

				<?php if (strtotime($event["screening_date"]) > strtotime(date("Y-m-d H:i:s"))) :?>
					<div class="left">
						<p class="event-countdown-label">Event Starts In:</p>
						<p id="event-countdown" class="event-coundown"></p>
					</div>
				<?php else : ?>
					<div class="left">
						<p class="event-countdown-label">Time Elsapsed</p>
						<p id="event-countdown" class="event-coundown"></p>
					</div>
				<?php endif; ?>
					<?php if(!$isAttending):?>
					<a href="/boxoffice/screening/<?php echo $event['screening_unique_key'];?>" class="button button-blue right uppercase" <?php if($event["screening_id"] == 23356) {?>style="background: #8EB22A; border-bottom-color: #66811F;"<?php }?>><?php echo $event['screening_film_free_screening'] == 1? 'RSVP to FREE Event': 'RSVP To Event';?></a>
					<?php else:?>
					<a href="/theater/<?php echo $event['screening_unique_key'];?>" class="button button-blue right uppercase">Enter Event</a>
					<?php endif;?>
				</div>

			<?php endif; ?>

		<?php if($event["screening_description"] != ''):?>
			<div class="event-description">
				<p><?php echo str_replace(array( "\n"), '</p><p>', $event["screening_description"]);?></p>
			</div>
		<?php endif;?>
		</div>
		<?php if(count($event["screening_qa"]) > 0):?>
		<div class="event-section">

			<h2>Clips From The Event</h2>
			<?php foreach ($event["screening_qa"] as $qa) :?>
			<div class="event-qa">
				<h5><?php echo $qa['title'];?></h5>
				<iframe width="600" height="300" src="http://www.youtube.com/embed/<?php echo $qa['youtubeId'];?>?wmode=opaque" frameborder="0" allowfullscreen></iframe>
			</div>
			<?php endforeach ;?>
		</div>
	<?php endif;?>
		<?php if($event["screening_user_full_name"] != ''):?>
		<div class="event-section">

			<h2>About <?php echo $event["screening_user_full_name"];?></h2>
			<div class="event-description clearfix">
				<a href="/profile/<?php echo $event['screening_user_id']?>" class="event-host-image">
					<img src="http://www.constellation.tv/<?php echo getShowtimeUserAvatar($event, 'large');?>" height="96" width="96"/>
	 			</a>
	 			<div class="event-host-text">
					<?php if($event['screening_user_tagline']):?>
					<p class="event-host-tagline"><?php echo $event["screening_user_tagline"];?></p>
					<?php endif;?>
					<div class="event-host-bio"><?php echo $event['screening_user_bio']?></div>
					<p><a href="/profile/<?php echo $event['screening_user_id']?>">Learn more about <?php echo $event["screening_user_full_name"];?> &rarr;</a>
				</div>
			</div>
		</div>
	<?php endif;?>

		<div class="event-section">
				<ul class="profile-social-nav right">
				<?php if(!empty($film['film_facebook'])):?>
			    <li><a target="_blank" href="<?php echo $film['film_facebook']?>" target="_blank"><span class="profile-social-icon profile-social-facebook"></span></a></li> 
				<?php endif; ?>
				<?php if(!empty($film['film_twitter'])):?>
			    <li><a target="_blank" href="<?php echo $film['film_twitter']?>" target="_blank"><span class="profile-social-icon profile-social-twitter"></span></a></li> 
				<?php endif; ?>
				<?php if(!empty($film['film_website'])):?>
			    <li><a target="_blank"href="<?php echo $film['film_website']?>" target="_blank"><span  class="profile-social-icon profile-social-site"></span></a></li> 
				<?php endif; ?>
				</ul>
			<h2>About <?php echo $film['film_name'];?></h2>
			<div class="clear"></div>
			<?php if (!empty($film['film_youtube_trailer'])):?>
			<div class="event-trailer-container">
			<iframe width="600" height="300" src="http://www.youtube.com/embed/<?php echo $film['film_youtube_trailer'];?>?wmode=opaque" frameborder="0" allowfullscreen></iframe>
			</div>
			<?php elseif(!empty($film['film_trailer_file'])):?>
			<div class="event-trailer-container"><div id="event-trailer"></div></div>
			<?php endif;?>
			<?php echo $film['film_trailer_url'];?>
			<div class="event-cast clearfix">
				<p class="event-label">Synopsis</p>
				<div  class="event-cast-text"><p><?php echo str_replace(array( "\n"), '</p><p>', $event['screening_film_info']);?></p></div>
				<?php if(!empty($film['film_directors'])):?>								
				<p class="event-label">Director</p>
				<div class="event-cast-text"><p><?php $i = 0; foreach($film['film_directors'] as $item): ?>
					<?php $parts = explode('|',$item);?>
					<?php echo ($i > 0 ? ', ': '') . trim($parts[1]);?>
				<?php $i++; endforeach;?></p></div>
				<?php endif;?>				
				<?php if(!empty($film['film_producers'])):?>
				<p class="event-label">Producers</p>
				<div class="event-cast-text"><p><?php $i = 0; foreach($film['film_producers'] as $item): ?>
					<?php $parts = explode('|',$item);?>
					<?php echo ($i > 0 ? ', ': '') . trim($parts[1]);?>
				<?php $i++; endforeach;?></p></div>
				<?php endif;?>
				<?php if(!empty($film['film_actors'])):?>							
				<p class="event-label">Cast</p>
				<div class="event-cast-text"><p><?php $i = 0; foreach($film['film_actors'] as $item): ?>
					<?php $parts = explode('|',$item);?>
					<?php echo ($i > 0 ? ', ': '') . trim($parts[1]);?>
				<?php $i++; endforeach;?></p></div>	
				<?php endif;?>
				<?php if(!empty($film['film_genre'])):?>							
				<p class="event-label">Genre</p>
				<div class="event-cast-text"><p><?php $i = 0; foreach($film['film_genre'] as $item): ?>
					<?php echo ($i > 0 ? ', ': '') . trim($item);?>
				<?php $i++; endforeach;?></p></div>	
				<?php endif;?>
				<?php if(!empty($film['film_genre'])):?>							
				<p class="event-label">Runtime</p>
				<div class="event-cast-text"><p><?php echo $film['film_running_time']?></p></div>	
				<?php endif;?>
			<!--	<?php if(!empty($film['film_facebook'])):?>
				<p class="event-label">Facebook</p>
				<div class="event-cast-text"><p><a href="<?php echo $film['film_facebook']?>" target="_blank"><?php echo $film['film_facebook']?></a></p></div>
				<?php endif;?>
				<?php if(!empty($film['film_twitter'])):?>
				<p class="event-label">Twitter</p>
				<div class="event-cast-text"><p><a href="<?php echo $film['film_twitter']?>" target="_blank"><?php echo $film['film_twitter']?></a></p></div>
				<?php endif;?>
				<?php if(!empty($film['film_website'])):
					$http = array('http://', 'www.');
				?>
				<p class="event-label">Website</p>
				<div class="event-cast-text"><p><a href="<?php echo $film['film_website']?>" target="_blank"><?php echo str_replace($http, "", $film['film_website']);?></a></p></div>
				<?php endif;?>-->


			</div>

		</div>
		<?php if ($film["film_allow_hostbyrequest"] == 1) :?>
		<div class="event-section event-section-ondemand clearfix">
			<h2>On Demand</h2>
				<p><?php echo $film['film_name'];?> is available to watch on-demand.</p>
				<a href="/boxoffice/screening/none/ondemand/film/<?php echo $film['film_id']?>/dohbr/true" class="button button-blue button-large uppercase">Watch On Demand</a>
			</div>
		<?php endif; ?>
		<div class="event-section event-section-last">

<div class="block">
	<h2>Ask <?php echo $event["screening_user_full_name"]?> a Question or Leave a Comment</h2>
	<div class="comment-box clearfix" id="comment-box">
	<?php if ($isLoggedIn) :?>
			<img src="<?php echo getSessionAvatar($sf_user);?>" width="50" height="50" class="comment-avatar comment-box-image"/>
		<textarea class="comment-box-field" placeholder="Add your voice to the conversation!"></textarea>
		<span class="button button-blue  button-medium uppercase">post</span>
	<?php else: ?>
		<a href="javascript:void(0)" onclick="return $(window).trigger('auth:login');">You must be logged in to post.</a>
	<?php endif;?>
	</div>
	<ul class="comment-list comment-loading " id="comment-list"></ul>
	<div class="comment-pagination" id="pagination">
		<ul></ul>
	</div>
	</div>
	</div>

	</div>
	<div class="event_aside">
		<div class="event-section event_poster_container">
			<img class="poster" src="/uploads/screeningResources/<?php echo $event["screening_film_id"];?>/logo/logo_event_<?php echo $event["screening_film_logo"];?>" alt="<?php echo $event["screening_film_name"];?>" class="event_poster"  width="280" />
		</div>
        <?php if($event["screening_id"] == 23356) {?>
        <a href="http://disney.go.com/the-odd-life-of-timothy-green/tickets.html"><div id="reminder-calendar" class="button button-black button-block" <?php if($event["screening_id"] == 23356) {?>style="background: #8EB22A; border-bottom-color: #66811F;"<?php } ?>>Get Tickets and Showtimes</div></a>
        <br />
		<?php } ?>	
		<?php if (strtotime($event["screening_end_time"]) + 120 > strtotime(date("Y-m-d H:i:s"))) :?>
		<div class="event-section">
			<h2>Remind Me</h2>
			<div id="reminder-calendar" class="button button-black  button-block"><span class="icon-calendar-32"></span>Add to Calendar</div>		
			<div id="reminder-email" class="button button-black  button-block"><span class="icon-reminder-32"></span>Email Reminder</div>

		</div>
		<div class="event-section">
			<h2>Share this Event</h2>
			<div onclick="window.open('http://www.facebook.com/dialog/feed?app_id=185162594831374&link=<?php echo urlencode('http://www.constellation.tv/event/'.$event['screening_unique_key'])?>&name=<?php echo urlencode( 'Constellation.tv presents: ' . $film["screening_film_name"])?><?php echo isSet($facebookCaption) ? '&caption=' . urlencode($facebookCaption) : ''?>&description=<?php echo urlencode($facebookDescription)?>&picture=<?php echo urlencode('http://www.constellation.tv/uploads/screeningResources/'.$film["screening_film_id"].'/logo/small_poster'.$film["screening_film_logo"]);?>&redirect_uri=http%3A%2F%2Fwww.constellation.tv','_share_facebook','width=450,height=300'); return false;" class="button button-facebook button-block button-facebook-invite"><span class="icon-facebook"></span>Share on Facebook</div>		
			<div onclick="window.open('http://twitter.com/intent/tweet?text=<?php echo urlencode($twitterText)?>','_share_twitter','width=450,height=300'); return false;" class="button button-twitter button-block button-twitter-invite"><span class="icon-twitter"></span>Share on Twitter</div>
			<?php if($event["screening_id"] == 23356):?>
            <a href="http://pinterest.com/pin/create/button/?url=http%3A%2F%2Fdisney.go.com%2Fthe-odd-life-of-timothy-green%2F%20%20&media=http%3A%2F%2Fwww.constellation.tv%2Fuploads%2FscreeningResources%2F157%2Flogo%2Flogo_event_6b5185ded81c1ea18653f2082195b2e2.jpg&description=%20The%20Odd%20Life%20of%20Timothy%20Green%20in%20theaters%20August%2015" target="_new"><div class="button button-twitter button-block button-twitter-invite"><span class="icon-pinterest"></span>Pinterest</div></a>
            <?php endif; ?>
        </div>
	<?php endif;?>
	<?php if($event['audience']):?>
		<div class="event-section event-section-last">
			<h2>Other People Attending</h2>
			<?php foreach ($event['audience'] as $user):?>
				<a href="/profile/<?php echo $user['id'] ;?>"><img src="<?php echo $user['image'];?>" height="32" width="32" alt="<?php echo $user['username'] ;?>" /></a>
			<?php endforeach ;?>
		</div>		
	<?php endif;?>
	</div>



</div>


<script>
require(['CTV/Controller/Event'], function(Controller){

	var options = {
		calendarOptions: {
			screening: '<?php echo $event['screening_unique_key'];?>',
			text: '<?php echo htmlspecialchars($event["screening_name"] != '' ? $event["screening_name"] : $event["screening_film_name"], ENT_QUOTES) ?>',
			location: window.location.href,
			details: '<?php echo str_replace(array( "\n"),' ',htmlspecialchars($event["screening_description"], ENT_QUOTES));?>',
			startDate: '<?php echo gmdate("Ymd\THis\Z",strtotime($event["screening_date"]))?>',
			endDate: '<?php echo gmdate("Ymd\THis\Z",strtotime($event["screening_end_time"]))?>',
			duration: '<?php echo ((strtotime($event["screening_end_time"]) - strtotime($event["screening_date"]))/60 );?>'
		},
		reminderOptions: {
			screening: '<?php echo $event['screening_unique_key'];?>',
			isLoggedIn: <?php echo $isLoggedIn ? 'true': 'false' ?>,
			email: <?php echo $isLoggedIn? '\'' . $sf_user -> getAttribute('user_email') .'\'': 'null';?>
		},
		screeningDate: <?php echo strtotime($event['screening_date'])?>,
		commentOptions: {
			list: $('#comment-list'),
			screeningId: <?php echo $event['screening_id']?>,
			filmId: <?php echo $film['film_id']?>,
			rpp: 5,
			isLoggedIn: <?php echo $isLoggedIn ? 'true': 'false';?>,
			fbShareImage : 'http://www.constellation.tv/uploads/screeningResources/56/logo/small_poster<?php echo $film['film_logo']?>',
			fbShareCaptions: 'Constellation | Social Movie Theater',
			fbShareDescription: 'to <?php echo htmlspecialchars($event["screening_name"] !=''? $event["screening_name"]: $event["screening_film_name"], ENT_QUOTES);?>',
			tShareCaption: 'to <?php echo htmlspecialchars($event["screening_name"] !=''? $event["screening_name"]: $event["screening_film_name"], ENT_QUOTES);?>'
		},
		trailerOptions: {
			streamUrl: '<?php echo $stream_url ;?>'
		},
		inviteRecord: {    
			film: <?php echo $event["screening_film_id"];?>,
	    	screening: '<?php echo $event["screening_unique_key"];?>',
	    	source: 'event'
	    }
	}
	new Controller(options);
});
</script>