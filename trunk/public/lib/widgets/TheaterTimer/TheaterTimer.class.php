<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  include_once(sfConfig::get('sf_lib_dir')."/helper/TrackHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/TheaterTimer_crud.php';
  
   class TheaterTimer_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {
	 
	 $film = $this -> page_vars["Theater"]["column2"][0]["film"];
	 $offset = (get_timezone_offset('UTC',date_default_timezone_get()) > 0) ? "-" : "+";
   $tz = $offset.get_timezone_offset('UTC',date_default_timezone_get());
	 
	 $this -> widget_vars["fbeacon"] = "?".getBeaconByType( $this -> sessionVar("user_id"), 2);
   $this -> widget_vars["tbeacon"] = "?".getBeaconByType( $this -> sessionVar("user_id"), 3);
   $this -> widget_vars["tz_offset"] = $tz;
   return $this -> widget_vars;
   
  }

	}

  ?>
