<?php 
  $theurl = "http://".sfConfig::get("app_domain")."/theater/".$item->getFkScreeningUniqueKey()."/".$item->getAudienceInviteCode();
	$newdate = formatDate($film["screening_date"],"W3XMLOUT",$film["screening_default_timezone_id"]);
?>
================================================================================

Congratulations! This is your ticket to High School Confidential the live online event with Jonah Hill and Channing Tatum presenting fan-submitted high school confessions and exclusive footage from their upcoming movie, 21 Jump Street.

The event will take place on <?php echo date("F dS",strtotime($film['screening_date'])) ;?> at <?php echo date("g:i A (T)",strtotime($film['screening_date'])) ;?> (<?php echo $film['screening_default_timezone_id'];?>). You will receive an email reminder 6 hours before the live event. 

Share the details of the event with your friends, submit your high school story, and vote for your favorites at www.constellation.tv/21jumpstreet/unlocked. 

================================================================================

<?php if($film["screening_user_full_name"] != '') {?>HOST: <?php echo strtoupper($film["screening_user_full_name"]); } ?>

<?php if ($film["screening_description"] != '') {?>
	<?php echo strip_tags($film["screening_description"]);?>
<?php } else { ?>
	<?php echo substr($film["screening_film_info"],0,255);?><?php if (strlen($film["screening_film_info"]) > 255) { echo "..."; } ?>
<?php } ?>

================================================================================
TS-AST-<?php echo time();?>-UTC::F20


Congratulations! This is your ticket to High School Confidential the live online event with Jonah Hill and Channing Tatum presenting fan-submitted high school confessions and exclusive footage from their upcoming movie, 21 Jump Street.

The event will take place on March 15th at 8:00 PM ET. You will receive an email reminder 6 hours before the live event. 

Share the details of the event with your friends, submit your high school story, and vote for your favorites at www.constellation.tv/21jumpstreet. 