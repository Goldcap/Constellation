<?php
$url = "http://".sfConfig::get("app_domain")."/theater/".$film["screening_unique_key"].$beacon;
$furl = "http://".sfConfig::get("app_domain")."/film/".$film["screening_film_id"].$beacon;
?>
Hi there.\n
<?php echo $name;?> has invited you check out <?php echo $film['screening_film_name'];?><?php echo !empty($film['screening_film_makers']) ? ", directed by " .$film['screening_film_makers'] : '';?> on Constellation.\n
\n
<?php echo $name;?> said:\n
\n
<?php echo $message;?>\n
\n
http://www.constellation.tv/theater/<?php echo $film["screening_unique_key"];?>?rf=<?php echo $beacon;?>::Click here to enter the theater\n
\n
\n
See you at the movies!\n
Thanks,\n
The Constellation Team\n


