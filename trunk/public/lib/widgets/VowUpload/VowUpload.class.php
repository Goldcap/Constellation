<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/VowUpload_crud.php';
  
   class VowUpload_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    $this -> XMLForm = new XMLForm($this -> widget_name);
	  $this -> XMLForm -> item_forcehidden = true;
    $this -> crud = new VowCrud( $context );
    parent::__construct( $context );
  }

	function parse() {
	 
	 $guidObj = explode("/",$this -> postVar("asset"));
	 $guid = end($guidObj);
	 $vars = array("vow_asset_guid"=>$guid);
	 $this -> crud -> checkUnique($vars);
	 if ($this -> crud -> getVowId() > 0) {
	 	$this -> widget_vars["status"] = "failure";
	 	$this -> widget_vars["message"] = "This asset was already added, please try another.";
	 	return $this -> widget_vars;
	 }
	 $this -> crud -> setVowDateCreated(now());
	 $this -> crud -> setVowAssetGuid($guid);
	 $this -> crud -> setVowDescription($this->postVar("description"));
	 $this -> crud -> setFkUserId($this->sessionVar("user_id"));
	 $this -> crud -> setVowAssetFilename($this->postVar("filename"));
	 $this -> crud -> save();
	 
	 $this -> widget_vars["status"] = "success";
	 $this -> widget_vars["message"] = "Your asset was saved, thanks.";
	 	
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