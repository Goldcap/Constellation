<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/ScreeningJs_crud.php';
  
   class ScreeningJs_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {
	 $this -> widget_vars["user_id"] = $this -> sessionVar("user_id");
	 $this -> widget_vars["user_fullname"] = $this -> sessionVar("user_fullname");
	 $this -> widget_vars["user_username"] = $this -> sessionVar("user_username");
	 return $this -> widget_vars;
   
  }

}

  ?>
