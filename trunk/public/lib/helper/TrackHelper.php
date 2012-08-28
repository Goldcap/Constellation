<?php

function addUserBeacons( $user ) {
  for ($i=2;$i<5;$i++) {
    $c = new Criteria();
    $c -> add(ClickTrackPeer::FK_CLICK_TYPE,$i);
    $c -> add(ClickTrackPeer::FK_CLICK_OWNER_ID,$user);
    $nres = ClickTrackPeer::doSelect($c);
    if (! $nres) {
      $ctf = new ClickTrack();
      $ctf -> setClickTrackGuid(createBeacon());
      $ctf -> setFkClickType($i);
      $ctf -> setFkClickOwnerId($user);
      $ctf -> setClickTrackCount(0);
      $ctf -> save();
    }
  }
}

function createBeacon() {
  $uuid = uniqid (false,true);
  return str_replace(".","-",$uuid);
}

function getBeaconByType( $user, $type ) {
	if (! $user) {
		return "";
	}
  $c = new Criteria();
  $c -> add(ClickTrackPeer::FK_CLICK_TYPE,$type);
  $c -> add(ClickTrackPeer::FK_CLICK_OWNER_ID,$user);
  $nres = ClickTrackPeer::doSelect($c);
  if ($nres) {
    return "rf=".$nres[0]->getClickTrackGuid();
  } else {
    return "";
  }
}

function recordBeaconAction( $user, $type, $film=null, $screening=null, $payment=null ) {
  if (! isset($_COOKIE["ccl"])) { return; }
  $guid = str_replace("ccl|","",$_COOKIE["ccl"]);
  $c = new Criteria();
  $c -> add(ClickTrackPeer::CLICK_TRACK_GUID,$guid);
  $nres = ClickTrackPeer::doSelect($c);
  if ($nres) {
    $action = new ClickAction();
    $action -> setFkUserId($user); 
    
    $action -> setFkClickGuid($guid); 
    $action -> setFkClickId($nres[0]->getClickTrackId());
    $action -> setClickActionDate(now()); 
    $action -> setFkFilmId($film);
    $action -> setFkScreeningId($screening);
    $action -> setFkPaymentId($payment);
    switch($type) {
      case "screening":
        $action -> setFkClickActionType(1);
      break;
      case "host":
        $action -> setFkClickActionType(2);
      break;
      case "signup":
        $action -> setFkClickActionType(3);
      break;
      case "login":
        $action -> setFkClickActionType(4);
      break;
      case "twitter_login":
        $action -> setFkClickActionType(5);
      break;
      case "facebook_login":
        $action -> setFkClickActionType(6);
      break;
      case "twitter_signup":
        $action -> setFkClickActionType(7);
      break;
      case "facebook_signup":
        $action -> setFkClickActionType(8);
      break;
    }
    $action -> save();
  }
  
}
?>
