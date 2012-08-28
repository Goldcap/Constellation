<?php
  
  //include_once("styroform/".$GLOBALS["styro_version"]."/clsXMLForm.php");
  
  //Data Abstraction classes as needed from propel
  //require_once 'crud/AccountTabs_crud.php';
  
   class AccountTabs_PageWidget extends Widget_PageWidget {
	
  var $XMLForm;
	var $crud;
	
	function __construct( $wvars, $pvars, $context ) {
    $this -> widget_vars = $wvars;
    $this -> page_vars = $pvars;
    $this -> widget_name = str_replace("_PageWidget","",get_class($this));
    parent::__construct( $context );
  }

	function parse() {
	
   $this -> widget_vars["sel"] = "account";
   if ($this -> getVar("op") != "list") {
    $this -> widget_vars["sel"] = $this -> getVar("op");
   }
   if ($this -> getVar("id")) {
    $this -> widget_vars["sel"] = $this -> getVar("id");
   }
	 return $this -> widget_vars;
   
    
  }

}
?>
