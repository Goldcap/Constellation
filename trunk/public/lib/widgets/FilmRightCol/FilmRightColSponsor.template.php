<?php
$screening = $vars["FilmView"]["column1"][0]["screenings"][0];
if ((! is_null($screening)) && ($screening["screening_still_image"] != '')) {?>
<h4>Next Screening</h4>
<a href="/film/<?php echo $screening["screening_film_id"];?>/detail/<?php echo $screening["screening_unique_key"];?>" title="<?php echo $screening["screening_unique_key"];?>" class="screening_link">
<div class="image_holder" style="background: url('/uploads/screeningResources/<?php echo $screening["screening_film_id"];?>/screenings/film_screening_next_<?php echo $screening["screening_still_image"];?>') no-repeat top;">
<?php if ($screening["screening_guest_image"] != '') {?>
<img class="host_inset_image" src="<?php echo $screening["screening_guest_image"];?>" alt="" />
<?php } ?>
</div>
</a>
<?php }?>

<?php if ($vars["FilmView"]["column1"][0]["film"]["film_allow_user_hosting"] == 1) {?>
<span><a href="#" id="host_screening_button"><button class="btn_med-og">Host A Screening</button></a></span>
<?php } ?>

<div class="buzz">
	<h5>Buzz</h5>
	<ul id="constellation_map">
    </ul>
</div>
<span id="room"><?php echo $room;?></span>

<!-- TIMEZONE -->
<?php include_component('default', 
                        'Timezone')?>
<!-- TIMEZONE -->	 		

<?php if ($vars["FilmView"]["column1"][0]["film"]["film_use_sponsor_codes"] == 1) {?>
<div style="padding-top: 10px"><span><a href="#" id="watch_request_button"><button class="btn_tiny_og">Watch By Request</button></a></span></div>
<?php }?>

<div id="bandwidth_test"></div>
