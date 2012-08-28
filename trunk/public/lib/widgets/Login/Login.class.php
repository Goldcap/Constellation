<?php
  
  //Set Specific Session Vars in the LoginHelper
  //Vendor ID => user_vendor_id
  //Artist => user_artist_id
  //etc...
  include_once(sfConfig::get('sf_lib_dir')."/helper/UserHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/TrackHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/FacebookHelper.php");
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Login_crud.php';
  
  class Login_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	var $user;
  // var $facebook;
  // var $destination;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> XMLForm = new XMLForm($this -> widget_name);
	  $this -> XMLForm -> item_forcehidden = true;
    parent::__construct( $context );
    
  }
  
  //NOTE: This action is intercepted prior to parsing by a filter
  //Please see /apps/*/lib/loginFilter.class.php
  //For the intercept rules
	function parse() {
	 // dump($this -> facebook );
  if($this -> getVar("id") == "facebook"){

    $facebook = new FacebookAPI(array(
    'appId'  => sfConfig::get("app_facebook_app_id"),
    'secret' => sfConfig::get("app_facebook_secret"),
    'cookie' => true,
    ));
    // dump($facebook );
    $this-> doFacebookAuth( $facebook, '/' );
  }
  if($this ->getVar('id') == 'ajax'){
    $this -> doAjaxPost();
  } elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $this -> doPost();
  }
	 
   $this -> user = $this -> context -> getUser();
   if ($this -> as_service) {
	  
    switch ($this -> getVar("id")) {
      case "updateual":
        /*
        $c=new Criteria();
        $users = UserPeer::doSelect($c);
        foreach($users as $user) {
          $ual = array(1);
          $user -> setUserUal($ual);
          $user -> save();
        }
        */
        die();
        break;
      case "form":
        $this -> setTemplate( "LoginForm" );
        $this -> assignWidgetVar( $this -> drawPage() );
        return $this -> widget_vars;
        break;
    }
    die();
   }
   
   if ($this -> widget_vars["Styroform"] == "true") {
      return $this -> drawPage();
   }
  }

  function drawPage(){
    
    if ($this -> user -> isAuthenticated()) {
      if (! $this -> widget_vars["Styroform"] == "true") {
        $this -> XMLForm -> item["destination"] = $_SERVER["REQUEST_URI"];
      }
    } elseif (($this -> XMLForm -> isPosted()) && (! $this -> user -> isAuthenticated())) {
      $this -> XMLForm -> addError("Login","Your username/password is incorrect, please try again.");
    }
    
    return $this -> returnForm();
    
  }

  function doPost( $redirect=true ) {

    $this -> context ->getLogger()->info("{Login} Username:: ".$this -> postVar('email')."\"");
    $isValid = true;
    $success = false;
    $err = array();
    
    $user = $this->context->getUser();
    
    $email = $this -> postVar('email');
    $password = $this -> postVar('password');
    
    $popup = false;
    if ($this -> postVar("popup")) {
      $popup = true;
    }
    
    if ($this -> postVar("source")) {
      $source = $this -> postVar("source");
    } else {
      $source = "/";
    }
    
    if ($this -> postVar("destination")) {
      $destination = $this -> postVar("destination");
    } else {
      $destination = "/";
    }
    
    if ($this -> postVar("indirect")) {
      $direct=false;
    } else {
      $direct=true;
    }
    
    if ($email == '') {
      $user ->setAttribute("login_error","Please enter your email.");
      $this -> context ->getLogger()->info("{Login} Failure:: email\"");
      $isValid = false;
      //return false;
    }
    
    if ($password == '') {
      $user ->setAttribute("login_error","Please enter your password.");
      $this -> context ->getLogger()->info("{Login} Failure:: password\"");
      $isValid = false;
    }
    
    //Check for email in database
    $theuser = getUser( $email );
    
    //Email is in the database
    if ((count($err) == 0) && ($theuser)) {
      if (strtolower(decrypt($theuser[0]->getUserPassword())) == strtolower($password)) {
        $this -> context ->getLogger()->info("{Login} Success\"");
        
        setUser( $user, $theuser[0] );
        addUserBeacons($theuser[0]->getUserId());
        recordBeaconAction( $this-> sessionVar("user_id"), "login", mull, null, null );
        $user->getAttributeHolder()->remove('login_error');
        
      } elseif (strtolower(decrypt($theuser[0]->getUserPassword())) != strtolower($password)) {
        $this -> context ->getLogger()->info("{Login} DB Failure:: password\"");
        $user -> setAuthenticated( false );
        $user -> getAttributeHolder()->clear();
        $isValid = false;
        $user ->setAttribute("login_error","Your password is incorrect, please try again.");
        //$err[] = "pass";
      }
     } else {
        $this -> context ->getLogger()->info("{Login} DB Failure:: email\"");
        $user -> setAuthenticated( false );
        $user -> getAttributeHolder()->clear();
        $user ->setAttribute("login_error","That username wasn't found, please try again.");
        //$err[] = "email";
        $isValid = false;
     }
     if(!$isValid){
        $this -> setflashVar("error-login","That email is already in use, please try a different address.");
     }
    
    if ($redirect) {
      
      if (! $direct) {
        if (count($err) > 0) {
          // $errs='?err=login&errs='.implode("&",$err);
          // if ($popup) {
          //   $errs.='&p=1';
          // }
          // $source .= $errs;

          $destination = $source;
          //dump($err);
        } else {
          $this -> context ->getLogger()->info("{Login} Success\"");
        }
        $this -> context -> getRequest() -> setAttribute("dest",$destination);
        //The redirect is passed back to the Module "Action"
        //So look here: apps/*/modules/default/actions
        return true;
      }
      
      if ((isset($destination)) && ($destination != '')) {
        $_POST["destination"] = preg_replace("/\?/","?PHPSESSID=".htmlspecialchars(session_id())."&".$destination,$destination);
        $this -> context -> getRequest() -> setAttribute("dest",$destination);
        header('Refresh: 2;url=http'.$proto.'://'.sfConfig::get("app_domain").'/'.$destination);
      }
      die();
    }
    
    return $user -> getAttribute("user_id") ;
    
  }
  
  function doTwitter( $twitterInfo,$token,$secret,$destination,$redirect=true ) {
    
    $user = $this->context->getUser();
    $theuser = getUserByTid( $twitterInfo->response["id_str"] );
    
    //User with this Twitter ID is in the database
    if ($theuser) {
      updateTwitterUser($this -> context,
      									$theuser[0]->getUserId(),
                        $twitterInfo->response["id_str"],
                        $twitterInfo->response["screen_name"],
                        $twitterInfo->response["profile_image_url"],
                        $twitterInfo->response["name"],
                        date_default_timezone_get(),
                        $token,
                        $secret);
      $theuser = getUserByTid( $twitterInfo->response["id_str"] );
			setUser( $user, $theuser[0] ); 
      addUserBeacons($theuser[0]->getUserId());
      recordBeaconAction( $theuser[0]->getUserId(), "twitter_login", mull, null, null );
      $user->getAttributeHolder()->remove('login_error');
      $this->redirect ($destination);
      die();
      
    //No user available, let's make one!
    } else {
      //dump($twitterInfo->response);
      //$context,$tid,$username,$image,$name,$timezone,$token,$secret,$ual=1
			$auser = createTwitterUser($this -> context,
                        $twitterInfo->response["id_str"],
                        $twitterInfo->response["screen_name"],
                        $twitterInfo->response["profile_image_url"],
                        $twitterInfo->response["name"],
                        date_default_timezone_get(),
                        $token,
                        $secret);
      setUser( $user, $auser ); 
      addUserBeacons($auser->getUserId());
      recordBeaconAction( $auser->getUserId(), "twitter_signup", mull, null, null );
      
	    $user->setAttribute("user_twitter",true);
      $user->getAttributeHolder()->remove('login_error');
      $this->redirect ($destination);
      die();
    }
     
  }
  
  function doFacebookAuth( $facebookInfo,$destination,$redirect=true ) {
    $this->context->getUser()->setAttribute("user_facebook",true);
    $this->redirect ($destination);
    die();
  }
  
  function doFacebook( $facebookInfo,$destination,$redirect=true ) {
   
		try {
      $fb = $facebookInfo->api('/me');
    } catch ( Exception $e ) {
      //We'll assume the session is no longer valid
      //And so delete the "FBS" cookie
      //And Try Again
      setcookie('fbsr_' . $facebookInfo->getAppId(), "", time() - 3600, '/');
      redirect("/services/Facebook/login?dest=".$destination);
      die();
    }
    
    $user = $this->context->getUser();
    $theuser = getUserByFid( $fb["id"] );
    
    //User with this Facebook ID is in the database
    if ($theuser) {
      
      setUser( $user, $theuser[0] );
      addUserBeacons($theuser[0]->getUserId());
      recordBeaconAction( $theuser[0]->getUserId(), "facebook_login", mull, null, null );
      
      $user->setAttribute("user_facebook",$fb["id"]);
      $theuser[0] -> setUserEmail($fb["email"]);
      $theuser[0] -> save();
      sendJoinEmail($theuser[0], $this -> context);
      
			$user->getAttributeHolder()->remove('login_error');
      $this->redirect ($destination);
      die();
      
    } else {
      
      //Let's see if there's a user with this email!
      if ($fb["email"] != '') {
				$theuser = getUser( $fb["email"] );
				
    		if ($theuser) {
	    		setUser( $user, $theuser[0] );
          addUserBeacons($theuser[0]->getUserId());
          recordBeaconAction( $theuser[0]->getUserId(), "facebook_login", mull, null, null );
		      sendJoinEmail($theuser[0], $this -> context);
      
          $picture = false;
          $description = false;
          if (preg_match("/thevow/",$destination)) {
						if (file_exists(sfConfig::get("sf_app_dir")."/templates/text/invite_93.txt")) {
								  $vals = file_get_contents(sfConfig::get("sf_app_dir")."/templates/text/invite_93.txt");
								  list($picture,$description,$message) = explode("|",$vals);
						}
					} else { 
						if (preg_match("/(film|theater)/",$destination)) {
	            if (preg_match("/\/theater\/([^\/].+)\/?/",$destination,$matches)) {
	              $this -> setGetVar("screening",$matches[1]);
	              $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Screenings/query/ScreeningByKey_list_datamap.xml");
	              $picture = 'http://www.constellation.tv/uploads/screeningResources/'.$list["data"][0]["screening_film_id"].'/logo/film_logo'.$list["data"][0]["screening_film_logo"];
	              $description = "Constellation.tv, 'Your Online Movie Theater'; join Constellation to watch movies with others. Invite five friends and get your ticket for free. ".$list["data"][0]["screening_film_name"]." -- " .$list["data"][0]["screening_film_info"];
							}
	            if (preg_match("/\/film\/([^\/].+)\/?/",$destination,$matches)) {
	              $this -> setGetVar("op",$matches[1]);
	              $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/FilmView/query/Film_list_datamap.xml");
	              $picture = 'http://www.constellation.tv/uploads/screeningResources/'.$list["data"][0]["film_id"].'/logo/film_logo'.$list["data"][0]["film_logo"];
	              $description = "Constellation.tv, 'Your Online Movie Theater'; join Constellation to watch movies with others. Invite five friends and get your ticket for free. ".$list["data"][0]["film_name"]." -- " .$list["data"][0]["film_info"];
							}
	          }
          }
          $beacon = "?".getBeaconByType( $this -> sessionVar("user_id"), 2);
          sendFacebookWalls( array($fb["id"]), false, false, $destination, $description, false, $picture, $beacon );
          
          $theuser[0] -> setUserEmail($fb["email"]);
		      $theuser[0] -> setUserFbUid($fb["id"]);
		      $theuser[0] -> save();
		      
          $user->setAttribute("user_facebook",$fb["id"]);
          
          $this->removesessionVar("destination");
	        $user->getAttributeHolder()->remove('login_error');
	        $this->redirect ($destination);
          die();
    		}
			}
			
      if (is_null($theuser[0])) {
				//No user available, let's make one!
				$auser = createFacebookUser($this -> context,
	                        $fb["id"],
	                        $fb["first_name"],
	                        $fb["first_name"],
	                        $fb["last_name"],
	                        $fb["email"],
	                        "https://graph.facebook.com/".$fb["id"]."/picture",
	                        $fb["name"],
	                        getTimeZoneFromOffset($fb["timezone"]));
	      setUser( $user, $auser );
        addUserBeacons($auser->getUserId());
        recordBeaconAction( $auser->getUserId(), "facebook_signup", mull, null, null );
	      sendJoinEmail($auser, $this -> context);
      
        $picture = false;
        $description = false;
        if (preg_match("/thevow/",$destination)) {
					if (file_exists(sfConfig::get("sf_app_dir")."/templates/text/invite_93.txt")) {
							  $vals = file_get_contents(sfConfig::get("sf_app_dir")."/templates/text/invite_93.txt");
							  list($picture,$description,$message) = explode("|",$vals);
					}
				} else { 
					if (preg_match("/(film|theater)/",$destination)) {
            if (preg_match("/\/theater\/([^\/].+)\/?/",$destination,$matches)) {
              $this -> setGetVar("screening",$matches[1]);
              $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Screenings/query/ScreeningByKey_list_datamap.xml");
              $picture = 'http://www.constellation.tv/uploads/screeningResources/'.$list["data"][0]["screening_film_id"].'/logo/film_logo'.$list["data"][0]["screening_film_logo"];
              $description = "Constellation.tv, 'Your Online Movie Theater'; join Constellation to watch movies with others. Invite five friends and get your ticket for free. ".$list["data"][0]["screening_film_name"]." -- " .$list["data"][0]["screening_film_info"];
						}
            if (preg_match("/\/film\/([^\/].+)\/?/",$destination,$matches)) {
              $this -> setGetVar("op",$matches[1]);
              $list = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/FilmView/query/Film_list_datamap.xml");
              $picture = 'http://www.constellation.tv/uploads/screeningResources/'.$list["data"][0]["film_id"].'/logo/film_logo'.$list["data"][0]["film_logo"];
              $description = "Constellation.tv, 'Your Online Movie Theater'; join Constellation to watch movies with others. Invite five friends and get your ticket for free. ".$list["data"][0]["film_name"]." -- " .$list["data"][0]["film_info"];
						}
          }
        }
        $beacon = "?".getBeaconByType( $this -> sessionVar("user_id"), 2);
        sendFacebookWalls( array($fb["id"]), false, false, $destination, $description, false, $picture, $beacon );
        
        $user->setAttribute("user_facebook",$fb["id"]);
        
	      $this->removesessionVar("destination");
	      $user->getAttributeHolder()->remove('login_error');
	      $this->redirect ($destination);
        die();
      }
    }
     
  }
  
  function doAjaxPost(){
    $this -> context ->getLogger()->info("{Login} Username:: ".$this -> postVar('email')."\"");
    $isValid = true;
    // $success = false;
    $errors = array();
    
    $user = $this->context->getUser();
    
    $email = $this -> postVar('email');
    $password = $this -> postVar('password');

    if ($email == '') {
      $isValid = false;
    }
    
    if ($password == '') {
      $isValid = false;
    }
    
    //Check for email in database
    $theuser = getUser( $email );
    
    //Email is in the database
    if ((count($err) == 0) && ($theuser)) {
      if (strtolower(decrypt($theuser[0]->getUserPassword())) == strtolower($password)) {
        // $this -> context ->getLogger()->info("{Login} Success\"");
        
        // setUser( $user, $theuser[0] );
        // addUserBeacons($theuser[0]->getUserId());
        // recordBeaconAction( $this-> sessionVar("user_id"), "login", mull, null, null );
        // $user->getAttributeHolder()->remove('login_error');
        
      } elseif (strtolower(decrypt($theuser[0]->getUserPassword())) != strtolower($password)) {
        // $this -> context ->getLogger()->info("{Login} DB Failure:: password\"");
        $user -> setAuthenticated( false );
        $user -> getAttributeHolder()->clear();
        $isValid = false;
        // $user ->setAttribute("login_error","Your password is incorrect, please try again.");
        //$err[] = "pass";
      }
     } else {
        // $this -> context ->getLogger()->info("{Login} DB Failure:: email\"");
        $user -> setAuthenticated( false );
        $user -> getAttributeHolder()->clear();
        // $user ->setAttribute("login_error","That username wasn't found, please try again.");
        //$err[] = "email";
        $isValid = false;
        // $errors[] = 'That email wasn\'t found, please try again.';
     }

     if($isValid){
        setUser( $user, $theuser[0] );
        addUserBeacons($theuser[0]->getUserId());
        recordBeaconAction( $this-> sessionVar("user_id"), "login", mull, null, null );
      }

     // if(!$isValid){
     //    // $this -> setflashVar("error-login","That email is already in use, please try a different address.");
     // }
      header('content-type: application/json; charset=utf-8');    
      print json_encode(array(
        "meta" => array(
            "success" => "200"
        ),
        "response" => array(
            "success" => $isValid,
            "errors" => $errors
        )
      ));
      die();
  }

  function logOut() {
  
    $user = $this->context->getUser();
    $user -> setAuthenticated( false );
    $user -> getAttributeHolder()->clear();
    $user -> shutdown();
    if ($this -> getVar("dest")) {
      $destination = $this -> getVar("dest");
    } else {
      $destination = "/";
    }
   //dump('fbsr_' . sfConfig::get("app_facebook_app_id")); 
   unset($_COOKIE['fbsr_' . sfConfig::get("app_facebook_app_id")]);
   setcookie('fbsr_' . sfConfig::get("app_facebook_app_id"), addslashes(serialize(array())), time() - 31500000);
   setcookie('fbsr_' . sfConfig::get("app_facebook_app_id"), addslashes(serialize(array())), time() - 31500000, '/', "", 0);
   setcookie('fbsr_' . sfConfig::get("app_facebook_app_id"), addslashes(serialize(array())), time() - 31500000, '/', 'dev.constellation.tv', 0);
   setcookie('fbsr_' . sfConfig::get("app_facebook_app_id"), addslashes(serialize(array())), time() - 31500000, '/', 'stage.constellation.tv', 0);
   setcookie('fbsr_' . sfConfig::get("app_facebook_app_id"), addslashes(serialize(array())), time() - 31500000, '/', 'www.constellation.tv', 0);
   setcookie('fbsr_' . sfConfig::get("app_facebook_app_id"), addslashes(serialize(array())), time() - 31500000, '/', '.constellation.tv', 0);
   setcookie('ctv_21_js_age', addslashes(serialize(array())), time() - 31500000, '/', '.constellation.tv', 0);
   $this -> facebook = new FacebookAPI(array(
    'appId'  => sfConfig::get("app_facebook_app_id"),
    'secret' => sfConfig::get("app_facebook_secret"),
    'cookie' => true,
    ));
    $this -> facebook -> destroySession();
    //dump($_SESSION);
    $this -> context -> getRequest() -> setAttribute("dest",$destination);
   
  }
  
}?>
