<?php
/**
This Example shows how to Subscribe a New Member to a List using the MCAPI.php 
class and do some basic error checking.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI($apikey);

$retval = $api->listInterestGroupings( $listId );

if ($api->errorCode){
	echo "Unable to load listInterestGroupings()!\n";
	echo "\tCode=".$api->errorCode."\n";
	echo "\tMsg=".$api->errorMessage."\n";
} else {
  echo "Your Groups:\n";
	foreach ($retval as $list){
	 echo "Id = ".$list['id']." - ".$list['name']."\n";
		foreach($list["groups"] as $group) {
		  echo "Id = ".$group['bit']."\tName = ".$group['name']."\tSubscribers = ".$group['subscribers']."\n";
	 }
  }
}

?>
