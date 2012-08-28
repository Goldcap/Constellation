<?php 
$image = getUserAvatarByClass($user);
if(preg_match("/graph.facebook.com/",$image)){
 	$image .= '?type=large';
} else if(preg_match("/twimg/" ,$image)){
 	$image = str_replace('_normal' ,'',$image);
} else {
	$image = $image;
}
				$name = 'User';
			if ( $user -> getUserUsername()!=''){
					if (preg_match("/@/",$user -> getUserUsername())) {
					  $aname = explode("@",$user -> getUserUsername());
					  $name = $aname[0];
					} else {
						$name =$user -> getUserUsername();
					}
				}

?>
<?php 
if (isset ($form) ) {echo $form;}
$isLoggedIn = false;
if (($sf_user -> isAuthenticated()) && ((is_null($sf_user -> getAttribute("temp"))) || (! $sf_user -> getAttribute("temp")))){
	$isLoggedIn = true;
}
$eventWithQa = array();
?>
<?php //dump($history);?>
<div class="content_inner profile_content_inner clearfix">
	<div class="profile_content">
		<div class="profile-section">

	        <h1 class="profile-title"><?php echo $name;?></h1>

				        <?php if($user -> getUserTagline() != ''):?>
	        <p class="profile-tagline"><?php echo $user -> getUserTagline();?><p>
			<?php endif; ?>

			<ul class="profile-social-nav clearfix">
			<?php if ( $user -> getUserFacebookUrl()!=''):?>
		    <li><a target="_blank" href="http://www.facebook.com/<?php echo preg_replace("/http:\/\/(www\.)?facebook.com\//","",$user -> getUserFacebookUrl());?>" target="_blank"><span class="profile-social-icon profile-social-facebook"></span></a></li> 
			<?php endif; ?>
			<?php if ( $user -> getUserTwitterUrl()!=''):?>
		    <li><a target="_blank" href="http://twitter.com/<?php echo preg_replace("/http:\/\/(www\.)?twitter.com\//","",$user -> getUserTwitterUrl());?>" target="_blank"><span class="profile-social-icon profile-social-twitter"></span></a></li> 
			<?php endif; ?>
			<?php if ( $user -> getUserWebsiteUrl()!=''):?>
		    <li><a target="_blank"href="<?php echo  $user -> getUserWebsiteUrl();?>" target="_blank"><span  class="profile-social-icon profile-social-site"></span><span class="profile-social-text"><?php echo  $user -> getUserWebsiteUrl();?></span></a></li> 
			<?php endif; ?>
			</ul>

			<?php if ( $user -> getUserBio()!=''):?>
		    <p class="profile-details-bio"><?php echo str_replace(array( "\n"), '</p><p>',$user -> getUserBio());?></p> 
			<?php endif; ?>



		</div>

		<div class="profile-section">
			<h2>Upcoming Events</h2>

				<?php if(count($purchases["data"]) > 0):?>
				<?php foreach ($purchases["data"] as $purchase) :?>
				<?php //dump($purchase);?>
				<div class="profile-event-module clear clearfix">
					<div class="profile-event-module-poster">
						<a href="/event/<?php echo $purchase["audience_screening_unique_key"];?>"><img  width="120" src="/uploads/screeningResources/<?php echo $purchase["audience_film_id"];?>/logo/small_poster<?php echo $purchase["audience_film_logo"];?>"></a>
			  		</div>
					<div class="profile-event-module-text">

						<div class="event-section">
							<p class="event-module-title">
								<a href="/event/<?php echo $purchase["audience_screening_unique_key"];?>">
									<?php echo $purchase["audience_screening_name"] !='' ? $purchase["audience_screening_name"]: $purchase["audience_film_name"];?>
								</a>
							</p>
						</div>
						<div class="event-section">
							<p class="event-module-time"><span class="icon-calendar"></span><?php echo date("D, M dS \@ g:i A T", strtotime($purchase['audience_screening_date'])) ;?></p>
						</div>
						<div class="event-section event-module-description">
						<?php if($purchase["audience_screening_description"] != ''):?>
							<p><?php echo str_replace(array( "\n"), '</p><p>', $purchase["audience_screening_description"]);?></p>
						<?php else:?>
							<p><?php echo str_replace(array( "\n"), '</p><p>', $purchase["audience_film_info"]);?></p>
						<?php endif;?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
			<?php else: ?>
				<h5 class="showtime-block clear"><?php echo $name;?> is not attending any upcoming events.</h5>
			<?php endif; ?>
		</div>
		<div class="profile-section">
			<h2>Previous Events</h2>

				<?php if(count($history["data"]) > 0):?>
				<?php foreach ($history["data"] as $purchase) :?>
				<?php if( $purchase['audience_screening_qa'] != ''){
					$eventWithQa[] = $purchase;
				}?>
				<?php //dump($purchase);?>
				<div class="profile-event-module clear clearfix">
					<div class="profile-event-module-poster">
						<a href="/event/<?php echo $purchase["audience_screening_unique_key"];?>"><img  width="120" src="/uploads/screeningResources/<?php echo $purchase["audience_film_id"];?>/logo/small_poster<?php echo $purchase["audience_film_logo"];?>"></a>
			  		</div>
					<div class="profile-event-module-text">

						<div class="event-section">
							<p class="event-module-title">
								<a href="/event/<?php echo $purchase["audience_screening_unique_key"];?>">
									<?php echo $purchase["audience_screening_name"] !='' ? $purchase["audience_screening_name"]: $purchase["audience_film_name"];?>
								</a>
							</p>
						</div>
						<div class="event-section">
							<p class="event-module-time"><span class="icon-calendar"></span><?php echo date("D, M dS \@ g:i A T", strtotime($purchase['audience_screening_date'])) ;?></p>
						</div>
						<div class="event-section event-module-description">
						<?php if($purchase["audience_screening_description"] != ''):?>
							<p><?php echo str_replace(array( "\n"), '</p><p>', $purchase["audience_screening_description"]);?></p>
						<?php else:?>
							<p><?php echo str_replace(array( "\n"), '</p><p>', $purchase["audience_film_info"]);?></p>
						<?php endif;?>
						<?php if( $purchase['audience_screening_qa'] != ''):?>
							<p><a href="/event/<?php echo $purchase["audience_screening_unique_key"];?>">View clips from this event &rarr;</a></p>
						<?php endif;?>

						</div>
					</div>
				</div>
			<?php endforeach; ?>
			<?php else: ?>
				<h5 class="showtime-block clear"><?php echo $name;?> has not attended previous events.</h5>
			<?php endif; ?>
		</div>
		<div class="profile-section profile-section-last">
			<h2>Ask <?php echo $name;?> a Question or Leave a Comment</h2>
			<div class="comment-box clearfix" id="comment-box">
			<?php if ($isLoggedIn) :?>
					<img src="<?php echo getSessionAvatar($sf_user);?>" width="50" height="50" class="comment-avatar comment-box-image"/>
				<textarea class="comment-box-field" placeholder="Add your voice to the conversation!"></textarea>
				<span class="button button-blue  button-medium uppercase">post</span>
			<?php else: ?>
		<a href="javascript:void(0)" onclick="return $(window).trigger('auth:login');">You must be logged in to post.</a>
			<?php endif;?>
			</div>
			<ul id="comment-list" class="comment-loading"></ul>
			<div id="pagination" class="comment-pagination"><ul></ul></div>
		</div>

	</div>
	<div class="profile_aside">
		<div class="profile-section profile-section-avatar">
	      	<img src="<?php echo $image?>"  />															          	
		</div>
		<div class="profile-section">
		<?php if ($allow_fallow == "yes"):?>
			<span id="profile-follow-button" class="button button-blue button-block center" data-user-id="<?php echo $user -> getUserId();?>" data-is-following="true">Follow</span>
			<p class="profile-follow-text">Follow <?php echo $name;?> to receive an email update whenever <?php echo $name;?> hosts an event.</p>
	    <?php elseif ($allow_fallow == "no"):?>
			<span id="profile-follow-button" class="button button-green  button-block center"  data-user-id="<?php echo $user -> getUserId();?>" data-is-following="false">Following</span>
			<p class="profile-follow-text">Follow <?php echo $name;?> to receive an email update whenever <?php echo $name;?> hosts an event.</p>
		<?php else :?>
			<a href="/account" class="button button-blue button-block center">Edit Account</a>
		<?php endif;?>
	</div>

	<div class="profile-section">
		<h2 id="profile-followers">Followers (<?php echo count($followed["data"]);?>)</h2>
		<div>

			<?php if (count($followed["data"]) > 0):?>
			<?php $i=0;?>
			<?php foreach ($followed["data"] as $follow) :?>
			<?php if($i< 20):?>
				<a href="/profile/<?php echo $follow["user_id"];?>"><img height="32" width="32" src="<?php echo getUserAvatar($follow,"small");?>" alt="<?php echo $follow["user_username"];?>" ></a>
			<?php endif;?>
			<?php $i++;?>
			<?php endforeach;?>
			<?php if (count($followed["data"]) > 40):?>
			<span class="button button-gray button-block button-medium">and <?php echo count($followed["data"]) - 40;?> more</span>
			<?php endif;?>
			<?php endif; ?>
		</div>

		</div>
		<div class="profile-section">
			<h2 class="profile-followings">Following (<?php echo count($following["data"]);?>)</h2>
			<div>
			<?php if (count($following["data"]) > 0):?>
			<?php $i=0;?>
			<?php foreach ($following["data"] as $follow) :?>
			<?php if($i< 20):?>
				<a href="/profile/<?php echo $follow["user_id"];?>"><img height="32" width="32" src="<?php echo getUserAvatar($follow,"small");?>" alt="<?php echo $follow["user_username"];?>" ></a>
			<?php endif;?>
			<?php $i++;?>
			<?php endforeach;?>
			<?php if (count($following["data"]) > 40):?>
			<span class="button button-gray button-block button-medium">and <?php echo count($following["data"]) - 40;?> more</span>
			<?php endif;?>
			<?php endif; ?>
			</div>
		</div>

	<?php if(count($eventWithQa) > 0):?>
		<div class="profile-section profile-section-last">
			<h2>Clips From Events Attended</h2>
			<?php foreach($eventWithQa as $event):?>
				<?php 

					$imp = explode(',', $event['audience_screening_qa']);
		            $imp2 = explode('|', $imp[0]);
		            $qas = array('title' => $imp2[0], 'youtubeId' => $imp2[1]);
		            // $event['screening_qa'] = $qas;
				?>

				<div class="event-qa"> 
					<h5>From: <?php echo $event["audience_screening_name"] !='' ? $event["audience_screening_name"]: $event["audience_film_name"];?></h5>
					<h6><?php echo $qas['title'];?></h6>
					<iframe width="280" height="150" src="http://www.youtube.com/embed/<?php echo $qas['youtubeId'];?>?wmode=opaque" frameborder="0" allowfullscreen></iframe>
					<p class="more-details"><a href="/event/<?php echo $event["audience_screening_unique_key"];?>">View more clips from this event &rarr;</a></p>
				</div>
			<?php endforeach;?>
	<?php endif;?>		
	</div>
</div>


	<script> 
require(['CTV/Controller/Host'], function(Controller){

	    _.templateSettings = {
		  interpolate : /\{\{(.+?)\}\}/g
		};
	var options = {
		isLoggedIn: <?php echo $isLoggedIn?'true':'false';?>,
		commentOptions: {
			list: $('#comment-list'),
			userId: <?php echo $user -> getUserId();?>,
			rpp: 5,
			isLoggedIn: <?php echo $isLoggedIn ? 'true': 'false';?>,
			fbShareImage : '<?php echo $image;?>',
			fbShareCaptions: 'Constellation | The Social Movie Theater',
			fbShareDescription: 'on <?php echo  htmlspecialchars($name, ENT_QUOTES)?>\s profile',
			tShareCaption: ' on <?php echo htmlspecialchars($name, ENT_QUOTES)?>\s profile'
		}
	}
	new Controller(options);
	
	
	});
	</script>
