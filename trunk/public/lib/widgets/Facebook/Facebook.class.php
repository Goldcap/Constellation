<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Facebook_crud.php';
  
   class Facebook_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	var $facebook;
	var $session;
	var $destination;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
    
    $this -> destination = "";
    $this -> facebook = new FacebookAPI(array(
    'appId'  => sfConfig::get("app_facebook_app_id"),
    'secret' => sfConfig::get("app_facebook_secret"),
    'cookie' => true,
    ));
  }

	function parse() {
		
    $this -> user = $this -> facebook -> getUser();
    
    if ($this -> getId() == "oauth") {

    } else if ($this -> getId() == "token") {
			if (! $this -> user) {
				$obj = new StdClass();
				$obj -> status = "undefined";
				die(json_encode($obj));
			} else {
				try {
					$obj = new StdClass();
					$obj -> status = "connected";
					$obj -> authResponse["accessToken"] = $this -> facebook -> getAccessToken();
					$obj -> authResponse["userId"] = $this -> user;
					$data = $this -> facebook->api('/me/');
					$obj -> authResponse["userName"] = $data["name"];
					die(json_encode($obj));
				} catch ( Exception $e ) {
					$this -> user = null;
					$obj = new StdClass();
					$obj -> status = "undefined";
					die(json_encode($obj));
				}
			}
		} else if ($this -> getId() == "friends") {
			if (! $this -> user) {
				$obj = new StdClass();
				$obj -> authStatus = "undefined";
				die(json_encode($obj));
			} else {
				$friends = $this -> facebook->api('/me/friends');
				array_multisort($friends["data"]);
				die(json_encode($friends));
			}
		} else if (($this -> getId() == "login") || ($this -> getId() == "auth")) {
  	 	 //If we aren't logged in...
      if (! $this -> user){
  	 		//die("HERE");
				$this -> facebook -> returnUrl = "/services/Facebook/login";
        if ($this -> getVar("dest")) {
          $this -> setsessionVar("destination",$this -> getVar("dest"));
					$this -> facebook -> returnUrl .= "/".str_replace(array("/",":","."),array("!","-","*"),$this -> getVar("dest"));
        }
				$params = array('scope'=>'email,status_update,publish_stream,read_stream,user_interests,friends_interests');
				$this -> redirect( $this -> facebook->getLoginUrl($params) ) ;
				die();
      //If there is an issue with our token, try again
      //Caution! Permanent loop possible...
			} else if  (! $this -> facebook -> getAccessToken()) {
        $this -> facebook -> destroySession();
				setcookie('fbsr_' . sfConfig::get("app_facebook_app_id"), "", time() - 3600, '/');
        if ($this -> getId() == "auth") {
					$this -> widget_vars["url"] = "/services/Facebook/auth?dest=".urlencode($this -> getVar("dest"));
				} else {
					$this -> widget_vars["url"] = "/services/Facebook/login?dest=".urlencode($this -> getVar("dest"));
        }
        die();
        //return $this -> widget_vars;
			//If our token is the generic app id, try again
      //Caution! Permanent loop possible...
			} else if (preg_match("/".sfConfig::get("app_facebook_app_id")."/",$this -> facebook -> getAccessToken())) {
				//This will move to the template, write the cookie, and use JS to redirect
				$this -> facebook -> destroySession();
				setcookie('fbsr_' . sfConfig::get("app_facebook_app_id"), "", time() - 3600, '/');
        //dump($this -> user);
				if ($this -> getId() == "auth") {
					$this -> widget_vars["url"] = "/services/Facebook/auth?dest=".urlencode($this -> getVar("dest"));
				} else {
					$this -> widget_vars["url"] = "/services/Facebook/login?dest=".urlencode($this -> getVar("dest"));
        }
        die();
        //return $this -> widget_vars;
			//We're authorized, so continue
      } else {
      	//In case we don't need to OAuth
        if ($this -> getVar("dest")) {
          $this -> destination = urldecode($this -> getVar("dest"));
        } elseif ($this -> getVar("rev")) {
          //$this -> destination = $this -> getVar("rev");
					$this -> destination = urldecode($this -> getVar("rev")); //str_replace(array("!","-","*","^"),array("/",":",".","?"),$this -> getVar("rev"));
        } elseif ($this -> sessionVar("destination")) {
				  $this -> destination = $this -> sessionVar("destination");
				}
				
        if ($this -> destination == "") {
          $this -> destination = "/";
        }
				  
        $this -> widget_vars["me"] = null;
        
        // Session based API call.
        if ($this -> user) {
          try {
          	$login = new Login_PageWidget( $this->context, null, null );
						if ($this -> getId() == "auth") {
							$login -> doFacebookAuth( $this -> facebook, $this -> destination );
						} else {
	            $login -> doFacebook( $this -> facebook, $this -> destination );
            }
            //$this -> widget_vars["uid"] = $this -> facebook->getUser();
            //$this -> widget_vars["me"] = $this -> facebook->api('/me');
          } catch (FacebookApiException $e) {
            //This will move to the template, write the cookie, and use JS to redirect
						setcookie('fbsr_' . sfConfig::get("app_facebook_app_id"), "", time() - 3600, '/');
            if ($this -> getId() == "auth") {
							$this -> widget_vars["url"] = "/services/Facebook/auth?dest=".$this -> destination;
						} else {
							$this -> widget_vars["url"] = "/services/Facebook/login?dest=".$this -> destination;
            }
            return $this -> widget_vars;
          }
        }
      }
    	 //return $this -> widget_vars;
    } elseif ($this -> getId() == "logout") {
      $this -> facebook -> destroySession();
    	setcookie('fbsr_' . sfConfig::get("app_facebook_app_id"), "", time() - 36000);
    	setcookie('fbsr_' . sfConfig::get("app_facebook_app_id"), "", time() - 36000, '/', 'dev.constellation.tv');
      setcookie('fbsr_' . sfConfig::get("app_facebook_app_id"), "", time() - 36000, '/', 'stage.constellation.tv');
      setcookie('fbsr_' . sfConfig::get("app_facebook_app_id"), "", time() - 36000, '/', 'www.constellation.tv');
      setcookie('fbsr_' . sfConfig::get("app_facebook_app_id"), "", time() - 36000, '/', '.constellation.tv');
			$this -> widget_vars["cookie"] = 'fbsr_' . sfConfig::get("app_facebook_app_id");
			$this -> widget_vars["url"] = $this -> getVar("dest");
    	return $this -> widget_vars;
    }
    
  }

	function getFriends() {
	    $this -> user = $this -> facebook -> getUser();

		if (! $this -> user) {
			return null;
		} else {
			$friends = $this -> facebook->api('/me/friends');
			array_multisort($friends["data"]);
			return $friends["data"];
		}
	}

}

?>
