<?php
/**
This Example shows how to Update an A/B Split Campaign via the MCAPI class.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI($apikey);

$retval = $api->getAffiliateInfo();

if ($api->errorCode){
	echo "Unable to Retrieve Affiliate Info!\n";
	echo "\n\tCode=".$api->errorCode;
	echo "\n\tMsg=".$api->errorMessage."\n";
} else {
	echo "Your Affiliate Info:\n";
    echo "User Id = ".$retval['user_id']."\n";
    echo "Rewards link = ".$retval['url']."\n";
}

?>
