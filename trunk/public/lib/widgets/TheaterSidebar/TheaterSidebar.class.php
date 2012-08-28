<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/TheaterSidebar_crud.php';
  
 class TheaterSidebar_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {
	
	 if ($this -> page_vars["Theater"]["column2"][0]["host"]) {
	  $questions = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/TheaterSidebar/query/qandahost_list_datamap.xml"); 
   } else {
    $questions = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/TheaterSidebar/query/qanda_list_datamap.xml");
   }
   
   //$this -> showData();
   //sfConfig::set("screening_id",$this -> page_vars["Theater"]["column2"][0]["film"]["screening_id"]);
   $selected_questions = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/TheaterSidebar/query/qandahost_selected_list_datamap.xml"); 
   $this -> widget_vars["selected"] = $selected_questions["data"];
   //dump($selected_questions);
   
   if ($this -> page_vars["Theater"]["column2"][0]["film"]["screening_film_sponsor_id"] > 1) {
    $this -> setTemplate("TheaterSidebarSponsor");
   }
   if ($this -> page_vars["Theater"]["column2"][0]["film"]["screening_user_id"] > 0){
    $this -> widget_vars["has_host"] = true;
   } else {
    $this -> widget_vars["has_host"] = false;
   }
  
   if(($this -> page_vars["Theater"]["column2"][0]["film"]["screening_user_id"] > 0)
    && ($this -> page_vars["Theater"]["column2"][0]["film"]["screening_live_webcam"] == 1)) {
    $this -> widget_vars["has_video"] = true;
   } else {
		$this -> widget_vars["has_video"] = false;
   }
   
   $this -> widget_vars["questions"] = $questions["data"];
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
