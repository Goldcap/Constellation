<?php 
  $theurl = "http://".sfConfig::get("app_domain")."/theater/".$item->getFkScreeningUniqueKey()."/".$item->getAudienceInviteCode();
	$newdate = formatDate($film["screening_date"],"W3XMLOUT",$film["screening_default_timezone_id"]);
?>
================================================================================
This is a receipt of your recent purchase of a ticket to the screening of <?php echo strtoupper($film["screening_film_name"]);?>.
You will receive a reminder six (6) hours prior to the screening.

If you can't make this showtime anymore, use the exchange code printed above during checkout to get a free ticket to any other showtime on Constellation. 

<?php echo $theurl;?>

ADMIT ONE

<?php echo strtoupper($film["screening_film_name"]);?>

DATE: <?php echo formatDate($newdate,"MDY");?><br />

TIME: <?php echo date("H:i A",strtotime($newdate));?> (<?php echo $film["screening_default_timezone_id"];?>)

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
Card type : <?php echo $order -> getPaymentCardType();?>
<?php if ($order -> getPaymentCardType() == "Paypal") {} elseif ($order -> getPaymentCardType() == "Coupon") {} else { ?>Last four digits of CC : <?php echo $order -> getPaymentLastFourCcDigits();}?>
Amount : $<?php echo $order -> getPaymentAmount();?>
Screening : "<?php echo $film["screening_film_name"];?>" on <?php echo formatDate($newdate,"MDY")?> at <?php echo formatDate($newdate,"time")?> <?php echo $film["screening_default_timezone_id"];?>
Screening URL : http://<?php echo sfConfig::get("app_domain");?>/theater/<?php echo $item->getFkScreeningUniqueKey();?>/<?php echo $item->getAudienceInviteCode();?>
TS-AST-<?php echo time();?>-UTC::F20
