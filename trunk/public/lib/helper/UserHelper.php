<?php

include_once(sfConfig::get('sf_lib_dir')."/helper/CryptHelper.php");

function sendJoinEmail( $user, $context ) {
  
  if (property_exists($user, 'User')) {
		$user = $user -> User;
	}
	$otz = date_default_timezone_get();
  //Do a temporary timezone conversion
  //Since these are sent as part of the client's browser process
  //Their timezone would muck up the Ticket
  date_default_timezone_set($film["data"][0]["screening_default_timezone_id"]);
  
  $mail_view = new sfPartialView($context, 'widgets', 'signup_email', 'signup_email' );
  $mail_view->getAttributeHolder()->add(array("user"=>$user));
  $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/Signup_html.template.php";
  $mail_view->setTemplate($templateloc);
  $message = $mail_view->render();
  
  $templateloc = sfConfig::get("sf_lib_dir") . "/widgets/MessageManager/Signup_text.template.php";
  $mail_view->setTemplate($templateloc);
  $altbody = $mail_view->render();
  
  date_default_timezone_set($otz);

  //$recips[0]["email"] = "amadsen@gmail.com";
  $recips[0]["email"] = $user->getUserEmail();
  $recips[0]["fname"] = $user->getUserFname();
  $recips[0]["lname"] = $user->getUserLname();
  
  $subject = "Welcome To Constellation";
  $mail = new WTVRMail( $context );
  $mail -> user_session_id = "user_id";
  $mail -> queueMessage("blank",$subject,$message,$altbody,sfConfig::get("app_mail_from_address"),sfConfig::get("app_mail_from_fname"),sfConfig::get("app_mail_from_lname"),$recips);
  
}

function createTwitterUser($context,$tid,$username,$image,$name,$timezone,$token,$secret,$ual=1) {
  
  if (is_null($timezone)) {
    $timezone = date_default_timezone_get();
  }
  
  $user = new UserCrud( null, null );
  $vars = array("user_t_uid"=>$tid);
  $user -> checkUnique($vars);
  $user -> setUserTUid($tid);
  $user -> setUserUsername($username);
  $user -> setUserFullName($name);
  if ($user -> User -> getUserId() == "") {
    $user -> setUserPassword( encrypt(generatePassword()) );
    $user -> setUserUal( serialize(explode(",",$ual)) );
  }
  $user -> setUserPhotoUrl($image);
  $user -> setUserCreatedAt(now());
  $user -> setUserUpdatedAt(now());
  $user -> setUserType(3);
  $user -> setUserDefaultTimezone($timezone);
  $user -> setUserTOauthToken($token);
  $user -> setUserTOauthTokenSecret($secret);
  $user -> save();
  
  //Update the SOLR Search Engine
  $solr = new SolrManager_PageWidget(null, null, $context);
  $solr -> execute("add","user",$user -> User -> getUserId());
  
  return $user;
  
}

function updateTwitterUser($context,$uid,$tid,$username,$image,$name,$timezone,$token,$secret,$ual=1) {
  
  if (is_null($timezone)) {
    $timezone = date_default_timezone_get();
  }
  
  $user = new UserCrud( null, null );
  $vars = array("user_t_uid"=>$tid);
  $user -> checkUnique($vars);
  $user -> setUserTUid($tid);
  $user -> setUserUsername($username);
  $user -> setUserFullName($name);
  if ($user -> User -> getUserId() == "") {
    $user -> setUserPassword( encrypt(generatePassword()) );
    $user -> setUserUal( serialize(explode(",",$ual)) );
  }
  $user -> setUserPhotoUrl($image);
  $user -> setUserCreatedAt(now());
  $user -> setUserUpdatedAt(now());
  $user -> setUserType(3);
  $user -> setUserDefaultTimezone($timezone);
  $user -> setUserTOauthToken($token);
  $user -> setUserTOauthTokenSecret($secret);
  $user -> save();
  
  //Update the SOLR Search Engine
  $solr = new SolrManager_PageWidget(null, null, $context);
  $solr -> execute("add","user",$user -> User -> getUserId());
  
  return $user;
  
}

function createFacebookUser($context,$fid,$username,$fname,$lname,$email,$image,$name,$timezone,$ual=1) {
  
  if (is_null($timezone)) {
    $timezone = date_default_timezone_get();
  }
  
  $user = new UserCrud( null, null );
  $vars = array("user_fb_uid"=>$fid);
  $user -> checkUnique($vars);
  $user -> setUserFbUid($fid);
  $user -> setUserUsername($username);
  $user -> setUserFullName($name);
  $user -> setUserFname($fname);
  $user -> setUserLname($lname);
  $user -> setUserEmail($email);
  if ($user -> User -> getUserId() == "") {
    $user -> setUserPassword( encrypt(generatePassword()) );
    $user -> setUserUal( serialize(explode(",",$ual)) );
  }
  $user -> setUserPhotoUrl($image);
  $user -> setUserCreatedAt(now());
  $user -> setUserUpdatedAt(now());
  $user -> setUserType(3);
  $user -> setUserDefaultTimezone($timezone);
  $user -> save();
  
  $user -> setUserOptin(1);
  $user -> setUserOptinDate(now());
  
  //Update the SOLR Search Engine
  $solr = new SolrManager_PageWidget(null, null, $context);
  $solr -> execute("add","user",$user -> User -> getUserId());
  if (($email != '') && (! preg_match("/constellation\.tv/",$email))) {
    addMailChimpUser($user -> User -> getUserId(),$fname,$lname,$email);
  }
  
  return $user;
  
}

function createUser($context,$fname,$lname,$email,$username,$password,$name=null,$birthday=null,$ual=1) {
  
  $timezone = date_default_timezone_get();
	
  $user = new UserCrud( null, null );
  $vars = array("user_email"=>$email);
  $user -> checkUnique($vars);
  $user -> setUserEmail($email);
  $user -> setUserUsername($username);
  if (! is_null($name)) {
    $user -> setUserFullName($name);
  } else {
    $user -> setUserFullName($fname." ".$lname);
  }
  if (! is_null($fname)) {
    $user -> setUserFname($fname);
  }
  if (! is_null($lname)) {
    $user -> setUserLname($lname);
  }
  if ($user -> User -> getUserId() == "") {
    $user -> setUserPassword( encrypt($password) );
    $user -> setUserUal( serialize(explode(",",$ual)) );
  }
  //$user -> setUserPhotoUrl($image);
  $user -> setUserCreatedAt(now());
  $user -> setUserUpdatedAt(now());
  $user -> setUserType(3);
  $user -> setUserDefaultTimezone($timezone);
  if (! is_null($birthday)) {
    $user -> setUserBirthdayDate($birthday);
  }
  $user -> setUserOptin(1);
  $user -> setUserOptinDate(now());
  $user -> save();
  
  //Update the SOLR Search Engine
  $solr = new SolrManager_PageWidget(null, null, $context);
  $solr -> execute("add","user",$user -> User -> getUserId());
  if (($email != '') && (! preg_match("/constellation\.tv/",$email))) {
    addMailChimpUser($user -> User -> getUserId(),$fname,$lname,$email);
  }
  return $user;

  
}

function createSponsorUser($context,$fname,$lname,$email,$username,$password,$sci,$name=null,$birthday=null,$ual=1) {
  
	$timezone = date_default_timezone_get();
	
  $user = new UserCrud( null, 0 );
  
  //Seeking by ID now, so this is unnecessary
	//$vars = array("user_username"=>$username);
  //$user -> checkUnique($vars);
  $user -> setUserEmail($email);
  $user -> setUserUsername($username);
  if (! is_null($name)) {
    $user -> setUserFullName($name);
  } else {
    $user -> setUserFullName($fname." ".$lname);
  }
  $user -> setUserFname($fname);
  $user -> setUserLname($lname);
  if ($user -> User -> getUserId() == "") {
    $user -> setUserPassword( encrypt($password) );
    $user -> setUserUal( serialize(explode(",",$ual)) );
  }
  //$user -> setUserPhotoUrl($image);
  $user -> setUserCreatedAt(now());
  $user -> setUserUpdatedAt(now());
  $user -> setUserType(3);
  $user -> setUserDefaultTimezone($timezone);
  if (! is_null($birthday)) {
    $user -> setUserBirthdayDate($birthday);
  }
  $user -> save();
  
  //$code = SponsorCodePeer::retrieveByPk($sci);
  //$code -> setFkUserId($user->getUserId());
  //$code -> save();
  
  //Update the SOLR Search Engine
  $solr = new SolrManager_PageWidget(null, null, $context);
  $solr -> execute("add","user",$user -> User -> getUserId());
  if (($email != '') && (! preg_match("/constellation\.tv/",$email))) {
    addMailChimpUser($user -> User -> getUserId(),$fname,$lname,$email);
  }
  
  return $user;

  
}

function addMailChimpUser( $user, $fname, $lname, $email ) {
  
  require_once(dirname(__FILE__).'/../vendor/MailChimp/examples/inc/config.inc.php');
  
  $api = new MCAPI($apikey);
  
  $merge_vars = array('FNAME'=>$fname,
                      'LNAME'=>$lname);
  
  // By default this sends a confirmation email - you will not see new members
  // until the link contained in it is clicked!
  $retval = $api->listSubscribe( $listId, $email, $merge_vars );
  
  if ($api->errorCode){
  	putLog("USER:: ".$user." | MESSAGE::MCAPI Error:: Unable to process ".$email); 
  	putLog("USER:: ".$user." | MESSAGE::MCAPI Error:: Code ".$api->errorCode); 
  	putLog("USER:: ".$user." | MESSAGE::MCAPI Error:: Message ".$api->errorMessage);
  } else {
  	putLog("USER:: ".$user." | MESSAGE::MCAPI Success:: ".$email);
  }
}

function updateUser( $context, $user, $fname,$lname,$email,$address,$address2,$city,$state,$zip,$country,$timezone=null) {
  if (is_null($timezone)) {
    $timezone = date_default_timezone_get();
  }
  $user -> setUserFname($fname);
  $user -> setUserLname($lname);
  $user -> setUserFullName($fname . " " . $lname);
  $user -> setUserEmail($email);
  // $user -> setUserUsername($email);
  $user -> setUserDefaultTimezone($timezone);
  $user -> save();
  
  if (($email != '') && (! preg_match("/constellation\.tv/",$email))) {
    addMailChimpUser($user -> getUserId(),$fname,$lname,$email);
  }
  
  return $user;
  
}

function updateUserTimezone( $timezone=null) {
  if (is_null($timezone)) {
    $timezone = date_default_timezone_get();
  }
  $user -> setUserDefaultTimezone($timezone);
  $user -> save();
  
  return $user;
  
}

function generatePassword ($length = 5) {
  
  
  $d = new WTVRData( null );
  $sql = "select dictionary_word from dictionary where length(dictionary_word) > ".$length." order by rand() limit 1;";
  // start with a blank password
  $temp = $d -> propelQuery( $sql );
  
  if ($temp) {
    while ($row = $temp->fetch()) {
      $password = $row[0];
    }
  }
  
  $password = strtolower($password) . sprintf("%03d",rand(0,999));
  // done!
  
  return $password;

}

function getUAL ( $val ) {
  $ual[1]="Member";
  $ual[2]="Artist";
  $ual[3]="Studio";
  $ual[4]="Vendor";
  $ual[5]="Affiliate";
  $ual[6]="Blogger";
  return $ual[$val];
}

function getUserAddress( $id ) {
  //Check for email in database
  sfConfig::set("address_id",$id);
  //Update the SOLR Search Engine
  $data = new Utils_PageWidget();
  $address = $data -> dataMap( sfConfig::get('sf_lib_dir')."/widgets/OrderManager/query/User_Address_ID_datamap.xml");
  
  return $address["data"][0];
}

function getUserAddresses() {
  //Check for email in database
  //Update the SOLR Search Engine
  $data = new Utils_PageWidget();
  $addresses = $data -> dataMap( sfConfig::get('sf_lib_dir')."/widgets/Account/query/User_Address_Ext_list_datamap.xml");
  
  return $addresses["data"];
}

function addUserKey( $user ) {
  if ((! $user) || (! $user -> getUserId() > 0)) {
    return false;
  }
  
  if (strlen($user -> getUserKey()) == 0) {
    $key = str_replace(".","-",uniqid('',true));
    
    $user -> setUserKey($key);
    $user -> save();
    
    //Update the SOLR Search Engine
    $solr = new SolrManager_PageWidget(null, null, null);
    $solr -> execute("add","user",$user -> getUserId());
  }
  return $crud;
}


function clearUser( $userobj ) {
  if ($userobj -> getAttribute("temp")) {
    $userobj ->setAuthenticated( false );
  }
}

?>
