<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/FTPUpload_crud.php';
  
   class FTPUpload_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> XMLForm = new XMLForm($this -> widget_name);
	  $this -> XMLForm -> item_forcehidden = true;
	  $this -> crud = new AccountCrud( $context );	  
    parent::__construct( $context );
  }

	function parse() {
	 
	 if (! $this -> sessionVar("user_id") > 0) {
	 	$this -> redirect('error');
	 }
	 $vars = array("fk_user_id"=>$this -> sessionVar("user_id"),"account_active"=>1);
	 $this -> crud -> checkUnique($vars);
	 $this -> widget_vars["username"] = $this -> crud -> Account -> getUsername();
	 $this -> widget_vars["password"] = $this -> crud -> Account -> getAccountPwRaw();
	 $this -> widget_vars["film_id"] = 21;
	 
	 return $this -> widget_vars;
   
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