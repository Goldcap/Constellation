<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/PreUser_crud.php';
  
   class PreUser_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> XMLForm = new XMLForm($this -> widget_name);
	  $this -> XMLForm -> item_forcehidden = true;
    $this -> crud = new PreUserCrud( $context );
    parent::__construct( $context );
  }

	function parse() {
	   
     $this -> redirect("/");
     die();
     
	   if ($this -> as_service) {
	     $this -> setTemplate("PreUserLogin");
      switch ($this -> getVar("id")) {
        case "join":
          $vars = array("pre_user_email"=>$this -> postVar("email"));
          $this -> crud -> checkUnique( $vars );
          $this -> crud -> setPreUserEmail($this -> postVar("email"));
          $this -> crud -> setPreUserDateAdded(now());
          if ($this -> crud -> getPreUserStatus() < 1) {
            $this -> crud -> setPreUserStatus(0);
            $this -> crud -> save();
          }
          $message = $this -> postVar("email") . " has requested access to ".sfConfig::get("app_domain")." on ".formatDate(now(),"pretty");
          QAMail ( $message, $message, true, "admin@constellation.tv");
          
          $this -> redirect('/preuser/join_success');
        	break;
        case "signin":
          //return false;
          $success = false;
          $err = array();
          $destination="/preuser";
          
          $email = $this -> postVar('email');
          $password = $this -> postVar('password');
          
          if ($email == '') {
            $err[] = "email";
          }
          
          if ($password == '') {
            $err[] = "pass";
          }
          
          //Check for email in database
          //Check for email in database
          $c = new Criteria();
          $c->add(PreUserPeer::PRE_USER_EMAIL,$email);
          $c->add(PreUserPeer::PRE_USER_STATUS,1);
          $theuser = PreUserPeer::doSelect($c);
          
          //Email is in the database
          if ((count($err) == 0) && ($theuser)) {
            if (strtolower($theuser[0]->getPreUserCode()) == strtolower($password)) {
              //$this->setCookieVar('cs_user',$email,30,"/",".constellation.tv");
              $time = time()+60*60*24*30;
              setcookie('cs_user', $email, $time, "/",".constellation.tv");
              $destination = "/";
              $this -> redirect($destination);
            } elseif (strtolower($theuser[0]->getPreUserCode()) != strtolower($password)) {
              $err[] = "pass";
            }
           } else {
              $err[] = "email";
           }
          
          if (count($err) > 0) {
            $errs='?err=login&errs='.implode("&",$err);
            if ($popup) {
              $errs.='&p=1';
            }
            $destination .= $errs;
          } else {
            $this -> context ->getLogger()->info("{Login} Success\"");
          }
          $this -> context -> getRequest() -> setAttribute("dest",$destination);
          $this -> redirect($destination);
          break;
        }
     }
    
    $this -> setMeta( "og:title", "Constellation.tv" );
    $this -> setMeta( "og:type", "Movie" );
    $this -> setMeta( "og:url", "http://www.constellationt.tv" );
    $this -> setMeta( "og:image", "http://cdn.constellation.tv.s3-website-us-east-1.amazonaws.com/prod/images/constellation_external.jpg" );
    $this -> setMeta( "og:site_name", "Constellation.tv" );
    $this -> setMeta( "og:description", "Constellation.tv is great films viewed online for yourself or with your friends." );
      
     return $this -> widget_vars;
    
  }

	}

  ?>
