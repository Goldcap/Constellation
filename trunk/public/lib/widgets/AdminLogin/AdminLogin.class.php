<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/LoginHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/CryptHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/Login_crud.php';
  
   class AdminLogin_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> XMLForm = new XMLForm($this -> widget_name);
	$this -> XMLForm -> item_forcehidden = true;
    
    parent::__construct( $context );
    
  }

	function parse() {
	  
	  if ($this -> as_cli) {
	    $c = new Criteria;
      $users = AdminUserPeer::doSelect( $c );
      foreach ($users as $user ) {
        $user -> setAdminUserPassword(encrypt($user -> getAdminUserPassword()));
        $user -> save();
      }
      die();
    }
    
	  if ($this -> XMLForm -> isPosted()) {  
      $this -> doPost();
    }
    
    return $this -> drawPage();
    
  }

  function doPost(){
      
      $direct = false;
      
      if ($this -> XMLForm -> validateForm()) {
        
        switch ($this -> getFormMethod()) {
          case "method":
          //Check for email in database
          $c = new Criteria();
          $c->add(AdminUserPeer::ADMIN_USER_EMAIL,$this -> postVar('email'));
          
          $theuser = AdminUserPeer::doSelect($c);
          
          //Email is in the database
          if ($theuser) {
          
            if ($theuser[0]->getAdminUserPassword() == encrypt($this -> postVar('password'))) {
              $user = $this->context->getUser();
              $user->getAttributeHolder()->remove('login_error');
              
              $this->setAuthenticated();
              $this -> id = $theuser[0]->getAdminUserId();
              $this -> setsessionVar("admin_user_id",$theuser[0]->getAdminUserId());
              $this -> setsessionVar("admin_user_fname",$theuser[0]->getAdminUserFname());
              $uals = unserialize($theuser[0]->getAdminUserUal());
              
							foreach ($uals as $ual) {
                $this -> addcredential($ual);
              }
              
        
              if (! $direct) {
                return true;
              }
              
              // redir
              if (($_POST["destination"] != '') && (! preg_match("/login/",$_POST["destination"]))) {
                  $_POST["destination"] = preg_replace("/\?/","?PHPSESSID=".htmlspecialchars(session_id())."&".$_POST["destination"],$_POST["destination"]);
                  $this -> context -> getRequest() -> setAttribute("dest",$_POST["destination"]);
                  if (sfConfig::get("app_enforce_ssl")) {
                    header('Refresh: 2;url=https://'.sfConfig::get("app_domain").$_POST["destination"]);
                  } else {
                    header('Refresh: 2;url='.$_POST["destination"]);
                  }
                } else {
                  $this -> context -> getRequest() -> setAttribute("dest","/?PHPSESSID=".htmlspecialchars(session_id()));
                  if (sfConfig::get("app_enforce_ssl")) {
                    header('Refresh: 2;url=https://'.sfConfig::get("app_domain").'/?PHPSESSID='.htmlspecialchars(session_id()));
                  } else {
                    header('Refresh: 2;url='.$_POST["destination"]);
                  }
              }
              
            } elseif ($theuser[0]->getAdminUserPassword() != encrypt($this -> postVar('password'))) {
              
              $this -> XMLForm -> addError("passwordError","Your password was incorrect, please try again.");
              return false;
              
            }
          } else {
          
            $this -> XMLForm -> addError("validationError","You're email wasn't found in our database..");
          
          }
          break;
          
        }
      }
  }

  function drawPage(){
    $this -> XMLForm -> item["destination"] = $_SERVER["REQUEST_URI"];
    return $this -> returnForm();
    
  }
  
  function logOut() {
  
    $user = $this->context->getUser();
    $user -> setAuthenticated( false );
    $user -> getAttributeHolder()->clear();
    $user -> shutdown();
    
    //setcookie ("tattoojohnny_auth", "", time() - 3600, "/", ".tattoojohnny.com", 0);
    //setcookie ("tattoojohnny_wishlist_cookie", "", time() - 3600, "/", ".tattoojohnny.com", 0);
    //redirect("login");
    //die(); 
  }
  
}

?>
