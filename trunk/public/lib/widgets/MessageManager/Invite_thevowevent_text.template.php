<?php
$url = "http://".sfConfig::get("app_domain")."/theater/thevowevent".$beacon;
$furl = "http://".sfConfig::get("app_domain")."/thevow".$beacon;
?>
Hi from Constellation.tv.\n
===============================================================================\n
<?php if ($sf_user -> getAttribute("user_username") != "") {echo $sf_user -> getAttribute("user_username"); } else { echo "A friend"; }?> said:\n
\n
<?php echo $message;?>\n
===============================================================================\n
<?php if ($sf_user -> getAttribute("user_username") != "") {  echo $sf_user -> getAttribute("user_username")." has invited you to 'An Evening of Vows'\n"; } else { echo "You are invited to 'An Evening of Vows'\n"; } ?>
has invited you to 'An Evening of Vows'\n 
http://www.constellation.tv::Constellation.tv\n, 
at 8:00 PM EST on Thursday, February 9, 2012.\n
===============================================================================\n
<?php echo $url;?>::Click here to get your ticket\n
===============================================================================\n
An Evening Of Vows|The Vow star Channing Tatum will host a live interactive \n
online event that may include your story of your vow. Channing will be live \n
online via web-cam presenting an online movie screening of fan-submitted "Vows"\n
along with with special clips from the movie. Tickets to this online event \n
are free. The Vow is only in movie theaters, beginning 2/10/12.\n
===============================================================================\n
TS-AST-<?php echo time();?>-UTC::F21\n
===============================================================================\n

