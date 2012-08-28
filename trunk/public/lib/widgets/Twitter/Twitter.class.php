<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Twitter_crud.php';
  
   class Twitter_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	var $consumer_key;
  var $consumer_secret;
    
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
    
    $this -> consumer_key = sfConfig::get("app_twitter_key");
    $this -> consumer_secret = sfConfig::get("app_twitter_secret");
    $this -> callback = "http://".sfConfig::get("app_domain")."/services/Twitter/auth";
    $this -> destination = "";
    
  }

	function parse() {
	
	 //Go to the Login Page
  	if ($this -> getId() == "login") {
  	  if ($this -> getVar("dest")) {
        // $this -> destination = "/".str_replace(array("/",":","."),array("!","-","*"),$this -> getVar("dest"));
        $this -> destination = urldecode($this -> getVar("dest"));
        // dump($this->destination);
        $this -> destination = "/".str_replace(array("/",":","."),array("!","-","*"),$this -> destination);
        // dump($this->destination);
      }
      $twitterObj = new EpiTwitter($this -> consumer_key, $this -> consumer_secret);
    	$twitterObj -> oauth_callback = $this -> callback . $this -> destination;
    	

      $url = $twitterObj->getAuthorizationUrl() ;
      // dump( $twitterObj -> oauth_callback );

      header( "Location: ". $url) ;
      //echo '<a href="' . $twitterObj->getAuthorizationUrl() . '">Authorize with Twitter ('.$twitterObj->getAuthorizationUrl().')</a>';
      die();
    	 //return $this -> widget_vars;
    }
    
    //Get a response from Twitter
    if ($this -> getId() == "auth") {
      if ($this -> getVar("rev")) {
          $this -> destination = str_replace(array("!","-","*"),array("/",":","."),$this -> getVar("rev"));
      }
      if ($this -> destination == "") {
        $this -> destination = "/";
      }
      
      $oauth_token = $this -> getVar("oauth_token");
      $oauth_verifier = $this -> getVar("oauth_verifier");
      
      $twitterObj = new EpiTwitter($this -> consumer_key, $this -> consumer_secret);
      
      $twitterObj->setToken($oauth_token);
      $token = $twitterObj->getAccessToken();
      $twitterObj->setToken($token->oauth_token, $token->oauth_token_secret);
      $twitterInfo= $twitterObj->get_accountVerify_credentials();
      $twitterInfo->response;
      if (isset($twitterInfo->response['error'])) {
        $this->redirect ($this -> destination.'?err=t');
      } else {
        $login = new Login_PageWidget( $this->context, null, null );
        $login -> doTwitter( $twitterInfo,$token->oauth_token,$token->oauth_token_secret,$this -> destination);
      }
      
      die();
    }
  }

  function doPost(){
     
     if ($this -> XMLForm -> validateForm()) {
        switch ($this -> getFormMethod()) {
          case "submit":
          $this -> crud -> write();
          break;
          case "delete":
          $this -> crud -> remove();
          break;
        }
      }
    
  }

  function doGet(){
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      $this -> pushItem();
    }
    
  }

  function drawPage(){
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      return $this -> returnForm();
    } elseif ($this ->getOp() == "list" ) {
      return $this -> returnList();
    }
    
  }

	}

  ?>
