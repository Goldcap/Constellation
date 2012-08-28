<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/PartnerHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/HomeHero_crud.php';
  
   class HomeHero_PageWidget extends Widget_PageWidget {
	
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
	    
     checkPartner($this -> context,true);
	   
	  if ($this -> getUser() -> isAuthenticated()) {
		 sfConfig::set("startdate","[".formatDate(now(),"W3XMLIN")." TO * ]");
	   $purchases = $this -> dataMap( sfConfig::get("sf_lib_dir")."/widgets/HeaderAlt/query/Screening_list_datamap.xml", true, "array", $util );
		 $this -> widget_vars["total_purchases"] = $purchases["meta"]["totalresults"];
	 }
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