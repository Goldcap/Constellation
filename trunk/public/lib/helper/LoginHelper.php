<?php

function getUser( $email ) {
  //Check for email in database
  $c = new Criteria();
  $c->add(UserPeer::USER_EMAIL,trim($email));
  $theuser = UserPeer::doSelect($c);
  
  return $theuser;
}

//This Function is Deprecated
//As it's completely unusable
//Non-Unique users returned by HBR Sponsor
function getUserByName( $username ) {
  //Check for username in database
  $c = new Criteria();
  $c->add(UserPeer::USER_USERNAME,$username);
  $theuser = UserPeer::doSelect($c);
  
  return $theuser;
}

function getUserByTid( $tid ) {
  //Check for email in database
  $c = new Criteria();
  $c->add(UserPeer::USER_T_UID,$tid);
  $theuser = UserPeer::doSelect($c);
  
  return $theuser;
}

function getUserByFid( $fid ) {
  //Check for email in database
  $c = new Criteria();
  $c->add(UserPeer::USER_FB_UID,$fid);
  $theuser = UserPeer::doSelect($c);
  
  return $theuser;
}

function getUserById( $id ) {
  //Check for email in database
  $theuser = UserPeer::retrieveByPk( $id );
  
  return $theuser;
}

function setUser( $userobj, $user, $timezone="America/New_York" ) {
  
  setAction ( $user->getUserId(), 1 );
  
  $userobj ->setAuthenticated(true);
  $userobj ->setAttribute("user_id",$user->getUserId());
  $userobj ->setAttribute("user_fullname",$user->getUserFullname());
  $userobj ->setAttribute("user_email",$user->getUserEmail());
  if(stristr($user->getUserUsername(), '@')){
    $username = explode('@', $user->getUserUsername());
    $userobj ->setAttribute("user_username", $username[0]);
  } else {
    $userobj ->setAttribute("user_username", $user->getUserUsername());
  }
  $userobj ->setAttribute("temp",false);
  if($user->getUserPhotoUrl() != '') {
    $userobj ->setAttribute("user_image",$user->getUserPhotoUrl());
  } else {
    $userobj ->setAttribute("user_image",'/images/icon-custom.png');
  }
  $ual = unserialize($user->getUserUal());
  
  if (($ual) && (count($ual) > 0)) {
    foreach($ual as $auth) {
      $userobj ->addCredential($auth);
    }
  }
  if ($user->getUserDefaultTimezone() != "") {
  	$timezone = $user->getUserDefaultTimezone();
    $userobj ->setAttribute("user_timezone",$user->getUserDefaultTimezone());
  } else {
    $userobj ->setAttribute("user_timezone",$timezone);
  }
  date_default_timezone_set($timezone);
  
}

function setAudienceUser( $userobj, $seat, $timezone="America/New_York" ) {
  
  //throw new MyException('Audience User Insert');
  //die();
  
  $userobj ->setAuthenticated( true );
  if (! $userobj -> getAttribute("user_id") > 0) {
    $userobj ->setAttribute("user_id",getRandomUserId());
  }
  $userobj ->setAttribute("user_fullname","Constellation User");
  $userobj ->setAttribute("user_email",$seat -> getAudienceUsername());
  $userobj ->setAttribute("user_username",$seat -> getAudienceUsername());
  //if($user->getUserPhotoUrl() != '') {
  //  $userobj ->setAttribute("user_image",$user->getUserPhotoUrl());
  //} else {
    $userobj ->setAttribute("user_image",'/images/icon-custom.png');
  //}
  $userobj ->setAttribute("user_timezone",$timezone);
  $userobj ->setAttribute("temp",true);
  putlog("{CreatedAudienceUser} ". $userobj -> getAttribute("user_id")."::".$userobj -> getAttribute("user_username"));
  
  return $userobj;
}

function setRandomUser( $userobj, $timezone="America/New_York" ) {
  
  //throw new MyException('Audience User Insert');
  //die();
  
  if ($userobj -> getAttribute("user_id") > 0) {
    return;
  }
  $userobj ->setAuthenticated( true );
  $userobj ->setAttribute("user_id",getRandomUserId());
  $userobj ->setAttribute("user_fullname","Constellation User");
  $userobj ->setAttribute("user_email","");
  $userobj ->setAttribute("user_username","Anonymous");
  $userobj ->setAttribute("user_image",'/images/icon-custom.png');
  $userobj ->setAttribute("user_timezone",$timezone);
  $userobj ->setAttribute("temp",true);
  putlog("{CreatedRandomUser} ". $userobj -> getAttribute("user_id")."::".$userobj -> getAttribute("user_username"));
  
  return $userobj;
  
}

function ifUser() {
  
  //Check for email in database
  $c = new Criteria();
  $c->add(UserPeer::USER_EMAIL,$email);
  $theuser = UserPeer::doSelect($c);
  
  return $theuser;
}

function setAction( $user_id, $action_id ) {
  
  $action = new UserAction();
  $action -> setFkUserId( $user_id );
  $action -> setFkActionId( $action_id  );
  $action -> setUserActionIpAddress( REMOTE_ADDR() );
  $action -> setUserActionUserAgent( $_SERVER["HTTP_USER_AGENT"] );
  $action -> setUserActionDate( now() );
  $action -> save();
  
  //Update the SOLR Search Engine
  $solr = new SolrManager_PageWidget(null, null, null);
  $solr -> execute("add","action",$action -> getUserActionId());
          
}
?>
