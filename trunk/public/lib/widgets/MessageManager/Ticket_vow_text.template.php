<?php 
  $theurl = "http://".sfConfig::get("app_domain")."/theater/".$item->getFkScreeningUniqueKey()."/".$item->getAudienceInviteCode();
?>
================================================================================

<?php echo $theurl;?>

ADMIT ONE

An Evening of Vows

DATE: DATE: 02-09-2012<br />

TIME: TIME: 8:00 PM (America/New York)

EXCHANGE CODE: <?php echo $item->getAudienceInviteCode();?>

ENTER THEATER: <?php echo $theurl;?>

================================================================================

<?php if($film["screening_user_full_name"] != '') {?>HOST: <?php echo strtoupper($film["screening_user_full_name"]); } ?>

<?php if ($film["screening_description"] != '') {?>
	<?php echo strip_tags($film["screening_description"]);?>
<?php } else { ?>
	<?php echo substr($film["screening_film_info"],0,255);?><?php if (strlen($film["screening_film_info"]) > 255) { echo "..."; } ?>
<?php } ?>

================================================================================

Name : <?php if ($user -> getUserFullName() != ''){  echo $user -> getUserFullName(); } else { echo $user -> getUserFullName();  }?>
Ticket Price : FREE
Screening : "<?php echo $film["screening_film_name"];?>" on <?php echo formatDate($film["screening_date"],"MDY")?> at <?php echo formatDate($film["screening_date"],"time")?> <?php echo $film["screening_default_timezone_id"];?>
Screening URL : http://<?php echo sfConfig::get("app_domain");?>/theater/<?php echo $item->getFkScreeningUniqueKey();?>/<?php echo $item->getAudienceInviteCode();?>
TS-AST-<?php echo time();?>-UTC::F20
