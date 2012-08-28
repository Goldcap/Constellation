<?php
$url = "http://".sfConfig::get("app_domain")."/theater/".$film["screening_unique_key"].$beacon;
$furl = "http://".sfConfig::get("app_domain")."/film/".$film["screening_film_id"].$beacon;
?>
Hi from Constellation.tv.\n
===============================================================================\n
<?php echo $sf_user -> getAttribute("user_username");?> said:\n
\n
<?php echo $message;?>\n
===============================================================================\n
<?php echo $sf_user -> getAttribute("user_username");?> \n
has invited you to a showing of <?php echo $film["screening_film_name"];?> \n
on http://www.constellation.tv<?php echo $beacon;?>::Constellation.tv, \n
at <?php echo $film["time_tz"];?> on <?php echo $film["time_dayofweek"];?>, <?php echo $film["time_date"];?>.\n
===============================================================================\n
<?php echo $url;?>::Click here to get your ticket\n
===============================================================================\n
<?php echo $film["screening_film_info"];?>\n
===============================================================================\n
TS-AST-<?php echo time();?>-UTC::F21\n
===============================================================================\n

