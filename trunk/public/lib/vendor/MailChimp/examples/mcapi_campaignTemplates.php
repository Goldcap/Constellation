<?php
/**
This Example shows how to retrieve a list of your Campaign Templates
via the MCAPI class.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI($apikey);

$retval = $api->campaignTemplates();

if ($api->errorCode){
	echo "Unable to Load Templates!";
	echo "\n\tCode=".$api->errorCode;
	echo "\n\tMsg=".$api->errorMessage."\n";
} else {
	echo "Your templates:\n";
	foreach($retval as $tmpl){
	    echo $tmpl['id']." - ".$tmpl['name']." - ".$tmpl['layout']."\n";
	}
}

?>
