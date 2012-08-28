<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/HomeHappening_crud.php';
  
   class HomeHappening_PageWidget extends Widget_PageWidget {
	
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
	  $next = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/HomeHappening/query/HomeHappening_list_datamap.xml");
	  $this -> widget_vars["happening"] = $next;
	  
		$convo = $this ->dataMap(sfConfig::get('sf_lib_dir')."/widgets/HomeHappening/query/HomeDiscussed_list_datamap.xml");
	  $this -> widget_vars["discussed"] = $convo;
	  
		return $this -> widget_vars;
   
  }

	}

  ?>