<?php

  include_once(sfConfig::get('sf_lib_dir')."/helper/LoginHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/UserHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/TrackHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/CryptHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/FacebookHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Join_crud.php';
  
   class Join_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	var $validated;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> XMLForm = new XMLForm($this -> widget_name);
	  $this -> XMLForm -> item_forcehidden = true;
    $this -> crud = new UserCrud( $context );
    $this -> validated = false;
    parent::__construct( $context );
  }

	function parse() {
        // dump('d');

    //return $this -> widget_vars;
    if ($this -> isAuthenticated()) {
      $this -> redirect("/");
      die();
    }
    if($this ->getVar('id') == 'ajax'){
      $this -> doAjaxPost();
    } elseif ($this -> as_service) {

      $this -> doService();
      die("ok");
    } else if ($this -> XMLForm -> isPosted()) {  
      $this -> doPost();
    }
    
    if (! $this -> validated) {
      return $this -> drawPage();
    }
  }

  function doPost(){
     if ($this -> XMLForm -> validateForm()) {
        switch ($this -> getFormMethod()) {
          case "submit":
          if ($this -> postVar('user_password') =='') {
            $this -> XMLForm -> addError("login_error","Your passwords don't match, please try again.");
            // return;
            $this -> setflashVar("error-signup",'');
            $isValid = false;
          }
          
          $c = new Criteria();
          $c->add(UserPeer::USER_USERNAME,$this -> postVar("user_username"));
          $res = UserPeer::doSelect($c);
          
          if ($res) {
            $this -> XMLForm -> addError("login_error","That nickname is already in use, please try again.");
            $this -> setflashVar("error-signup",'');
            // return;
            $isValid = false;
          }
          
          $c = new Criteria();
          $c->add(UserPeer::USER_EMAIL,$this -> postVar("user_email"));
          $res = UserPeer::doSelect($c);
          
          if ($res) {
            // $this -> XMLForm -> addError("login_error","That email is already a user, please try again.");
            $this -> setflashVar("error-signup",'');
            // return;
            $isValid = false;
          }
          
          if ($this -> postVar("free")) {
            $user = $this->context->getUser();
            $auser = createUser($this -> context,
                          null,
                          null,
                          $this -> postVar("user_email"),
                          $this -> postVar("user_username"),
                          $this -> postVar("user_password"),
                          null,
                          null);
          } else {
            $dateObj = new XMLFormUtils(); 
            $date = $dateObj -> sfDateTime("user_birthday_date");
            $user = $this->context->getUser();
            $auser = createUser($this -> context,
                          $this -> postVar("user_fname"),
                          $this -> postVar("user_lname"),
                          $this -> postVar("user_email"),
                          $this -> postVar("user_username"),
                          $this -> postVar("user_password"),
                          $this -> postVar("user_fname")." ".$this -> postVar("user_lname"),
                          null);
          }
          
          setUser( $user, $auser );
          addUserBeacons($auser->getUserId());
          recordBeaconAction( $this-> sessionVar("user_id"), "signup", mull, null, null );
          sendJoinEmail($auser, $this -> context);
          
          $user->getAttributeHolder()->remove('login_error');
          $this->redirect ('/');
          $this -> validated = true;
          
          break;
          case "delete":
          $this -> crud -> remove();
          break;
        }
      } else {
        $this -> XMLForm -> addError("login_error","There was a problem with your information, please try again.");
      }
    
  }
  
  function doService(){

    $this -> context ->getLogger()->info("{Join} Username:: ".$this -> postVar('email')."\"");
    
    $success = false;
    $err = array();
    
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
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      
      $user = $this->context->getUser();
      
      if ($this -> postVar('password') == '') {
        $user ->setAttribute("join_error","Please enter your email.");
        $this -> context ->getLogger()->info("{Join} Failure:: password\"");
        $err[] = "pass";
        $this -> setflashVar("error-signup",'');
      }
      
      // $c = new Criteria();
      // $c->add(UserPeer::USER_USERNAME,$this -> postVar("username"));
      // $res = UserPeer::doSelect($c);
      
      // if ($res) {
      //   //$user ->setAttribute("login_error","Your username isn't unique.");
      //   //$this -> context ->getLogger()->info("{Login} Failure:: username\"");
      //   //$err[] = "username";
      // }
      
      $c = new Criteria();
      $c->add(UserPeer::USER_EMAIL,$this -> postVar("email"));
      $res = UserPeer::doSelect($c);
      
      if ($res) {
        $user ->setAttribute("login_error","That email is already in use.");
        $this -> context ->getLogger()->info("{Login} Failure:: email\"");
        $err[] = "email";
        $this -> setflashVar("error-signup",'');
      }
      
      //$date = validDateTime($this -> postVar("month"),$this -> postVar("day"),$this -> postVar("year"));
      
      if ((count($err) == 0) && ($user)) {
        $this -> context ->getLogger()->info("{Join} Success\"");
        $auser = createUser($this -> context,
                      null,
                      null,
                      WTVRCleanString($this -> postVar("email")),
                      WTVRCleanString($this -> postVar("username")),
                      WTVRCleanString($this -> postVar("password")),
                      WTVRCleanString($this -> postVar("name")),
                      null);
        
        setUser( $user, $auser );
        addUserBeacons($auser->getUserId());
        recordBeaconAction( $this-> sessionVar("user_id"), "signup", mull, null, null );
       	sendJoinEmail($auser, $this -> context);
           
        $user -> getAttributeHolder()->remove('login_error');
        $this -> validated = true;
      } else {
        $this -> setflashVar("error-signup",'');
        // $errs='?err=signup&errs='.implode("&",$err);
        // if ($popup) {
        //   $errs.='&p=1';
        // }
        // $source .= $errs;
        $destination = $source;
        //dump($err);
      }
      
      if (! $this -> getVar("nd") == "true") {
			   $this->redirect ($destination);
      }
      
    }
      
  }

  function doAjaxPost(){
    $user = $this->context->getUser();
    $isValid = true;
    $errors = array();

    if ($this -> postVar("email") == "" || !preg_match('/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i', $this -> postVar('email'))) {
      $isValid = false;
      $errors[] = 'Please enter a valid e-mail.';
    } else {
      $c = new Criteria();
      $c->add(UserPeer::USER_EMAIL,$this -> postVar("email"));
      $res = UserPeer::doSelect($c);
      if ($res) {
        $isValid = false;
        $errors[] = 'The e-mail you entered already exist in our system.';
      }      
    }

    if ($this -> postVar('username') == "") {
      $isValid = false;
      $errors[] = 'Please enter a valid username.';
    }
    
    if ($this -> postVar('password') == "") {
      $isValid = false;
      $errors[] = 'Please enter a password.';
    }          

    if($isValid){   
      $user = $this->context->getUser();
      $auser = createUser(
        $this -> context,
        null,
        null,
        $this -> postVar("email"),
        $this -> postVar("username"),
        $this -> postVar("password"),
        null,
        null
      );

          
      setUser( $user, $auser );
      addUserBeacons($auser->getUserId());
      recordBeaconAction( $this-> sessionVar("user_id"), "signup", mull, null, null );
      sendJoinEmail($auser, $this -> context);
      if($this -> postVar("optin") == 'true'){
        addMailChimpUser($auser->getUserId(), null, null, $this -> postVar("user_email"));
      }
    }

      header('content-type: application/json; charset=utf-8');    
      print json_encode(array(
        "meta" => array(
            "success" => "200",
        ),
        "response" => array(
            "success" => $isValid,
            "errors" => $errors,
        )
      ));
      die();

  }

  function drawPage(){
    
    return $this -> returnForm();
    
  }

	}

  ?>
