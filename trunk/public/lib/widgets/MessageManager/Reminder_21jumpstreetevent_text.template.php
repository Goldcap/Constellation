A friendly reminder that your showtime of <?php echo $screening['screening_film_name'];?> begins at <?php echo date("g:i A (T)",strtotime($screening['screening_date'])) ;?> today.

Screenings of <?php echo $screening['screening_film_name'];?>:: http://<?php echo sfConfig::get("app_domain");?>/film/<?php echo $screening['screening_film_id'];?>

<?php if ($screening["screening_user_id"] > 0) {?>Hosted by <?php echo $screening["screening_user_full_name"];?><?php } ?>

<?php echo date("l, F jS, Y",strtotime($screening["screening_date"]));?>
<?php echo date("h:i A",strtotime($screening["screening_date"]));?> (<?php echo $screening["screening_default_timezone_id"];?>)
<?php if (isset($item)) { ?>
EXCHANGE CODE: <?php echo $item->getAudienceInviteCode();?>
<?php } ?>

Enter The Theater:: http://<?php echo sfConfig::get("app_domain");?>/theater/<?php echo $screening["screening_unique_key"];?>

Can't make your showtime? As long as you haven't entered the theater after the start of the movie, you can use the exchange code printed above in the "Discount Code" field during checkout to get a free ticket to this or any other movie.


[!--UNSUBSCRIBE--][!--UNSUBSCRIBE--]

TS-AST-<?php echo time();?>-UTC::F22