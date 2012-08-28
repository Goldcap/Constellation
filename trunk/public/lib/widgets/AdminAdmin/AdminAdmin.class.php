<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/LoginHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/UserHelper.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/CryptHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/user_crud.php';
  
   class AdminAdmin_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> XMLForm = new XMLForm($this -> widget_name);
	  $this -> XMLForm -> item_forcehidden = true;
    $this -> crud = new AdminUserCrud( $context, 0 );
    parent::__construct( $context );
  }

	function parse() {
    
	 //return $this -> widget_vars;
   
	  if ($this -> XMLForm -> isPosted()) {  
      $this -> doPost();
    }
    $this -> doGet();
    
    return $this -> drawPage();
    
  }

  function doPost(){
     
     if ($this -> XMLForm -> validateForm()) {
        switch ($this -> getFormMethod()) {
          case "submit":
          if ($this -> postVar("admin_user_id") > 0) {
            $this -> crud -> hydrate( $this -> postVar("admin_user_id") );
          }
          $this -> crud -> write();
          $user_ual = serialize($this -> postVar("admin_user_ual_array"));
          if ($this -> postVar("admin_user_password")) {
            $this -> crud -> AdminUser -> setAdminUserPassword(encrypt($this -> postVar("admin_user_password")));
          }
          $this -> crud -> AdminUser -> setAdminUserUal($user_ual);
          $this -> crud -> AdminUser -> save();
          
          if ($this -> sessionVar("admin_user_id") ==  $this -> crud -> AdminUser -> getAdminUserId()) {
          $uals = unserialize($this -> crud -> AdminUser->getAdminUserUal());
          
          foreach ($uals as $ual) {
            $this -> addcredential($ual);
          }
          }
          
          break;
          case "delete":
          if ($this -> postVar("admin_user_id") > 0) {
            $this -> crud -> hydrate( $this -> postVar("admin_user_id") );
          }
          $this -> crud -> remove();
          break;
        }
      }
    
  }

  function doGet(){
    if (($this ->getOp() == "detail") && ($this -> getId()>0) && ($this -> crud)) {
      $this -> pushItem();
      if ($this -> crud -> AdminUser -> getAdminUserPassword() != '') {
        $this -> XMLForm -> item["admin_user_password"] = decrypt($this -> crud -> AdminUser -> getAdminUserPassword());
      }
      $vals = unserialize($this -> crud -> getAdminUserUal());
      $this -> XMLForm -> item["admin_user_ual_array[]"] = $vals;
      
    }
    
  }

  function drawPage(){
    
    if (($this ->getOp() == "detail") && ($this -> getId()>=0) && ($this -> crud)) {
      return $this -> returnForm();
    } elseif ($this ->getOp() == "search" ) {
      if ($this -> greedyVar("word")) {
        $word = $this -> greedyVar("word");
      } else {
        $word = $this -> getId();
      }
      return $this -> searchAdminUser( $word );
    } elseif ($this ->getOp() == "list" ) {
      return $this -> returnList();
    }
    
  }
  
  function searchAdminUser($phrase) {
      
      $result = user_search($phrase);
      
      $result["attribs"]["name"] = "AdminUsers";
      $result["attribs"]["title"] = "";
      $result["attribs"]["allow_add"] = "true";
      $result["attribs"]["url"] = "/user/search/".$phrase;
      $result["attribs"]["query_string"] = "/user/search/".$phrase;
      
      return $this -> addList($result);
  }

	}

  ?>
