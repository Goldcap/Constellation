<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/TheaterToolbar_crud.php';
  
   class TheaterToolbar_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {
	 
	 //$this -> film = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/Theater/query/Screening_list_datamap.xml");
	 
	 if (file_exists(sfConfig::get('sf_app_dir')."/templates/html/theater_promo_".$this -> page_vars["Theater"]["column2"][0]["film"]["screening_film_id"].".html")) {
	 	$this -> widget_vars["promo"] = file_get_contents(sfConfig::get('sf_app_dir')."/templates/html/theater_promo_".$this -> page_vars["Theater"]["column2"][0]["film"]["screening_film_id"].".html");
	 }
	 
   //For Customized Screenings
   if($this -> page_vars["Theater"]["column2"][0]["film"]["screening_film_sponsor_id"] > 1) {
		$this->widget_vars["sponsor"] = true;
	 }
   if (($this -> page_vars["Theater"]["column2"][0]["film"]["screening_has_qanda"] > 0) && ($this -> page_vars["Theater"]["column2"][0]["film"]["screening_user_id"] == $this -> sessionVar("user_id"))) {
		$this->widget_vars["is_host"] = true;
	 } else {
    $this->widget_vars["is_host"] = false;
   }
	 if ($this -> page_vars["Theater"]["column2"][0]["film"]["screening_has_qanda"] > 0) {
		$this->widget_vars["has_host"] = true;
	 } else {
    $this->widget_vars["has_host"] = false;
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
