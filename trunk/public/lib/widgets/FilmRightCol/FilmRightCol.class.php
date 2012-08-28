<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/FilmRightCol_crud.php';
  
   class FilmRightCol_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {
	
  if ($this -> page_vars["FilmView"]["column1"][0]["film"]["film_sponsor_id"] > 1) {
    //This Template Allows Users To Host
    //$this -> setTemplate("FilmRightColSponsor");
	  //This Template Allows Users to Enter Code for Instant Screenings
    $this -> setTemplate("FilmRightColSponsor");
	}
	return $this -> widget_vars;
   
  }

	}

  ?>
