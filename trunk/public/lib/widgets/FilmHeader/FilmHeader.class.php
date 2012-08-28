<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  include_once(sfConfig::get('sf_lib_dir')."/helper/TrackHelper.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/FilmHeader_crud.php';
  
   class FilmHeader_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    
    $sxml = new XML();
    $sxml -> loadXML( sfConfig::get("sf_web_dir")."/xml/states.xml");
    $this -> widget_vars["states"] = $sxml -> query("//ASTATE");
    
    $sxml -> loadXML( sfConfig::get("sf_web_dir")."/xml/countries.xml");
    $this -> widget_vars["countries"] = $sxml -> query("//ACOUNTRY");
    
    parent::__construct( $context );
  }

	function parse() {
	 
	 $this -> widget_vars["film"] = $this -> page_vars["FilmMarquee"]["marqee_moon"][0]["film"];
	 $this -> widget_vars["post"] = $this -> page_vars["FilmMarquee"]["marqee_moon"][0]["post"];
	 $this -> widget_vars["gbip"] = $this -> page_vars["FilmMarquee"]["marqee_moon"][0]["gbip"];
   $this -> widget_vars["test"] = $this -> page_vars["FilmMarquee"]["marqee_moon"][0];

   // dump($this -> page_vars["FilmMarquee"]["marqee_moon"][0]);

	 $this -> widget_vars["screening"] = $this -> page_vars["FilmMarquee"]["marqee_moon"][0]["screening"];
	 $this -> widget_vars["fbeacon"] = "?".getBeaconByType( $this -> sessionVar("user_id"), 2);
   $this -> widget_vars["tbeacon"] = "?".getBeaconByType( $this -> sessionVar("user_id"), 3);
   return $this -> widget_vars;
   
    
  }


	}

  ?>
